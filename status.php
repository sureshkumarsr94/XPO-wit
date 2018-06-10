<?php

  file_put_contents("/tmp/status_".date('Y-m-d').".php","POST::".json_encode($_POST)."\n\n", FILE_APPEND);
  file_put_contents("/tmp/status_".date('Y-m-d').".php","GET::".json_encode($_GET)."\n\n", FILE_APPEND);
  
   require('lib/base.php');
   
   $base_obj = new base();
   
   
  
  if( $_POST["Called"] != "" && $_GET["order_number"] !="" ){
	  
	  $call_status = $_POST["CallStatus"];
	  if( $call_status=="failed"){
		   $call_status = "call terminated";
	  }
	  
	  $sql = " update xpo_delivery_details set call_status='".$call_status."' where Order_No='".$_GET["order_number"]."' ";
	  $base_obj->_Exe_Query($sql);
  }  
  
?>