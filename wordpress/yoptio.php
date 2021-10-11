<?php
require_once "wp-load.php";
global $yoptioapi;
$SITE_URL     = site_url()."/";
$method       = $_REQUEST['method']; 
$fromnumber   = $_REQUEST['From']; 
$tonumber     = $_REQUEST['To']; 
$from_number  = ($_REQUEST['from_number']!='') ? "+".$_REQUEST['from_number'] : $fromnumber ;
$to_number    = ($_REQUEST['to_number']!='') ? "+".$_REQUEST['to_number'] : $tonumber ;
$response    = ($_REQUEST['response']!='') ? $_REQUEST['response'] : 0 ;
 if($method=='' && empty($method)){    // MAKE A CALL 
	  $params = array(
							'from_number'  => $from_number,
							'to_number'    => $to_number,
							'args'         => array("url" => $SITE_URL.'yoptio.php?method=init'),
							'response'       => $response
		    	     );
					 
 	   $makeacall = $yoptioapi->Makeacall($params);
	   echo $makeacall;   
  }
  elseif($method=='init'){             //  INITIALIZE A CALL 
    	$params = $_REQUEST;
		$init = $yoptioapi->init($params);
		echo $init;
  }
  elseif ($method=='connecttoconference') {   // IF OPTION 1 CONNECT TO CONFERENCE    
		$params = $_REQUEST; 
		$connecttoconference = $yoptioapi->connecttoconference($params);
		echo $connecttoconference;
  }
  elseif($method=='connecttouser') {  // CONNECT TO USER IF CALLER DOESN'T Press any key in welcome stage
		$params = $_REQUEST; 
		$connecttouser = $yoptioapi->connecttouser($params);
		echo $connecttouser;
  }
  elseif ($method=='saveconference') {  // DAVE CONFERENCE IF CALLER DOESN'T Press any key in welcome stage
		$params = $_REQUEST; 
		$saveconference = $yoptioapi->saveconference($params);
		echo $saveconference;
  }
  elseif ($method=='record') { 
		$params = $_REQUEST; 
		$record = $yoptioapi->record($params);
		echo $record;
  }
  elseif ($method=='mail') {	     // SEND RECORDING URL TO EMAIL 
		$params = $_REQUEST; 
		$email = $yoptioapi->email($params);
		echo $email;
	}
  elseif ($method=='hangup') {
		$params = $_REQUEST; 
		$hangup = $yoptioapi->hangup($params);
		echo $hangup;
	}
?>
