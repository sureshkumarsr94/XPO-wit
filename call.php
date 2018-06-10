<?php
    require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
	
    use Twilio\Rest\Client;

   
    function makeCall($submittedNumber, $orderNumber)
    {
        // put your project information here
        $accountSid = "ACf285b6814e1d938781a82a7ab6d532a6";
        $authToken = "446947642cc9b018d1d827edff943630";
        $outgoingNumber = '+12564348855';
        $endPoint = "http://ec2-18-221-58-226.us-east-2.compute.amazonaws.com/expo/twiml.php";
		$endPoint .= "?order_number=".$orderNumber."&phone_number=".$submittedNumber;

        // Instantiate a new Twilio Rest Client
        $client = new Client($accountSid, $authToken);

        try {
            // initiate phone call via Twilio REST API
            $client->account->calls->create(
                "+".$submittedNumber,        // The phone number you wish to dial
                $outgoingNumber,         // Verified Outgoing Caller ID or Twilio number
                [ 
					"url" => $endPoint, 
					"statusCallback" => "http://ec2-18-221-58-226.us-east-2.compute.amazonaws.com/expo/status.php?order_number=".$orderNumber,
				]   // The URL of twiml.php on your server
            );		
			
        } catch (Exception $e) {
			file_put_contents("/tmp/msgerr.txt",$e->getMessage());
		}

        
    }

    // require POST request
    if ($_SERVER['REQUEST_METHOD'] != "POST") die;

    
    $submittedNumber = $_POST["phone_number"];
	$orderNumber = $_POST["order_number"];	
    
    
    makeCall($submittedNumber, $orderNumber );
   
?>