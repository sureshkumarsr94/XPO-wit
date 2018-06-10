<?php
    require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
   
    require('lib/base.php');
    use Twilio\Twiml;
	use Twilio\Rest\Client;
	
	$base_obj = new base();
	
	
	$accountSid = "ACf285b6814e1d938781a82a7ab6d532a6";
    $authToken = "446947642cc9b018d1d827edff943630";
    $actionUrl = "http://ec2-18-221-58-226.us-east-2.compute.amazonaws.com/expo/action.php";
	$outgoingNumber = '+12564348855';
	
    $client = new Client($accountSid, $authToken);
    $response = new Twiml;

	file_put_contents("/tmp/action_".date('Y-m-d').".php","POST::".json_encode($_POST)."\n\n", FILE_APPEND);
	file_put_contents("/tmp/action_".date('Y-m-d').".php","GET::".json_encode($_GET)."\n\n", FILE_APPEND);
	
	$Speech = strtolower($_POST["SpeechResult"]);
	$order_number = $_GET["order_number"];
	$phone_number = $_GET["phone_number"];
	
	$sql="select  DATE_FORMAT(schd_date, '%D %M %Y') as schd_date, Customer_Name from xpo_delivery_details where Order_No='".$order_number."'";
	file_put_contents("/tmp/query1.php",$sql."\n\n", FILE_APPEND);
	$data = $base_obj->_Fetch_Data($sql);
	$schd_date = $data[0]['schd_date'];	
	$name = $data[0]['Customer_Name'];	
	
	
	if($_GET['level']=="1"){
		
		if( strpos( $Speech, "yes" ) !== false || strpos( $Speech, "ok" ) !== false || strpos( $Speech, "yeah" ) !== false  ) {
			
			$gather = $response->gather([
							'input' => 'speech',
							'timeout' => 3 ,
							'action' => $actionUrl.'?level=2&order_number='.$order_number.'&phone_number='.$phone_number]);
							
			$gather->say("your parcel plan to deliver on ".$schd_date.". do you want reschedule it?");
			
		} else if( (strpos( $Speech, "no" )) !== false || (strpos( $Speech, "sometime" )) !== false || (strpos( $Speech, "busy" )) !== false ){
			
			$response->say("Thank you.");
			
			$sql =" update xpo_delivery_details set rescheduled_date='', last_update=DATE_ADD(now(), INTERVAL '5:30' HOUR_MINUTE) , comments='Need to call after sometime'  where Order_No = '".$order_number."' ";
			
			$base_obj->_Exe_Query($sql);
			
			$client->messages->create(			
				"+".$phone_number,
				array(
					'from' => $outgoingNumber,
					'body' => 'Hi '.$name.', this is john from XPO.your order will deliverd on '.$schd_date.'. if you want to reschedule please contact me. contact number: +12564348855'
				)
			);
			
		} else if ($Speech==""){
			$client->messages->create(			
				"+".$phone_number,
				array(
					'from' => $outgoingNumber,
					'body' => ' Hi '.$name.', This is john from XPO. System issue occured. your order will deliverd on '.$schd_date.'. if you want to reschedule please contact me. contact number: +12564348855'
				)
			);
		}
		
	}
	else if($_GET['level']=="2"){
		
		if( (strpos( $Speech, "yes" )) !== false || (strpos( $Speech, "yeah" )) !== false  ) {
			
			$gather = $response->gather([
								'input' => 'speech',
								'timeout' => 3 ,
								'action' => $actionUrl.'?level=3&order_number='.$order_number.'&phone_number='.$phone_number]);
								
			$gather->say("specify your convenient date");
			
		} else if( (strpos( $Speech, "no" )) !== false ){
			
			$sql =" update xpo_delivery_details set rescheduled_date='', last_update=DATE_ADD(now(), INTERVAL '5:30' HOUR_MINUTE) , comments='As per the schedule'  where Order_No = '".$order_number."' ";
			$base_obj->_Exe_Query($sql);
			
			$response->say("Thank you. Your parcel will be delivered as per the schedule ");
			
			$client->messages->create(			
				"+".$phone_number,
				array(
					'from' => $outgoingNumber,
					'body' => 'Hi '.$name.', your order will deliverd on '.$schd_date
				)
			);
			
		} else if ($Speech==""){
			$client->messages->create(			
				"+".$phone_number,
				array(
					'from' => $outgoingNumber,
					'body' => ' Hi '.$name.', This is john from XPO. System issue occured. your order will deliverd on '.$schd_date.'. if you want to reschedule please contact me. contact number: +12564348855'
				)
			);
		}
	}
	else if($_GET['level']=="3"){		
		
		if ($Speech!=""){
			$response->say('Please wait');
			$response->pause(['length' => 2]);
			
			$date = date('Y-m-d', strtotime($Speech));
			
			if($date=="1970-01-01"){
				$date="2018-06-15";
				$Speech =" 15th june 2018";
			}
			
			$sql =" update xpo_delivery_details set rescheduled_date='".$date."', last_update=DATE_ADD(now(), INTERVAL '5:30' HOUR_MINUTE),  comments='Rescheduled' where Order_No = '".$order_number."' ";
			file_put_contents("/tmp/query.php",$sql);
			$base_obj->_Exe_Query($sql);
			
			$response->say('your request processed. Thanks for your time');
			
			$client->messages->create(			
				"+".$phone_number,
				array(
					'from' => $outgoingNumber,
					'body' => 'Hi '.$name.', your order will deliverd on '.$Speech
				)
			);
		}else if ($Speech==""){
			$client->messages->create(			
				"+".$phone_number,
				array(
					'from' => $outgoingNumber,
					'body' => ' Hi '.$name.', This is john from XPO. System issue occured. your order will deliverd on '.$schd_date.'. if you want to reschedule please contact me. contact number: +12564348855'
				)
			);
		}
	}
	
	header('Content-Type: text/xml');
    echo $response
?>
