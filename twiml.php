<?php
    require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
    require('lib/base.php');
	
	
	$base_obj = new base();
	
    use Twilio\Twiml;

    $response = new Twiml;

	file_put_contents("/tmp/".date('Y-m-d').".php","POST::".json_encode($_POST)."\n\n", FILE_APPEND);
	file_put_contents("/tmp/".date('Y-m-d').".php","GET::".json_encode($_GET)."\n\n", FILE_APPEND);
	
	$sql="select Customer_Name from xpo_delivery_details where Order_No='".$_GET["order_number"]."'";
	file_put_contents("/tmp/query1.php",$sql."\n\n", FILE_APPEND);
	$data = $base_obj->_Fetch_Data($sql);
	$name = $data[0]['Customer_Name'];	
	
	
	$actionUrl='http://ec2-18-221-58-226.us-east-2.compute.amazonaws.com/expo/action.php?level=1&order_number='.$_GET["order_number"].'&phone_number='.$_GET["phone_number"];

	file_put_contents("/tmp/".date('Y-m-d').".php","URL::".json_encode($actionUrl)."\n\n", FILE_APPEND);
	
    if (empty($_POST["SpeechResult"])) {
		
        $gather = $response->gather([ 
					'input' => 'speech',
					'timeout' => 3 ,
					'action' => $actionUrl ]);
		$gather->pause(['length' => 1]);			
        $gather->say(" Hi ".$name.", This is john from XPO. Is it the right time to talk to you? ");
		
    } 
	
    header('Content-Type: text/xml');
    echo $response
?>
