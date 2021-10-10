<?php  
include('includes/function.php');
$action  =  $_REQUEST['action']; 
$method  =  $_REQUEST['method'];  

if(isset($action) && $action == 'extensions'){
    global $extensions;
	$form_data  =  $_REQUEST['form_data']; 
	$response   =  array('status' => 0,'msg' => array());
	switch ($method) {
		case 'addgreeting'   : // ADD GREETING
					   
					   $insert         = TRUE;
					   $extensionsData = $extensions->cleandata($form_data);
					   if($insert){
					     $greeting_id                    = $extensions->addgreetings($extensionsData);
						 $response['status']             = 1;
						 $response['greeting_id']        = $greeting_id;
						 $response['msg']                = 'The record has been added successfully';
					   }
					   
			break;
		 
		
		case 'add'     : // ADD EXTENSIONS
					   $insert         = TRUE;
					   $extensionsData = $extensions->cleandata($form_data);
					   // CHECK isVALID
					   $isValid_email  = $extensions->isValid($extensionsData,'email');
					   $isValid_mobile = $extensions->isValid($extensionsData,'mobile_number');
					   if($isValid_email>0) {   
								$insert                          =   FALSE;  
								$response['msg']['email']        =  'Email is already exists'; 
					   }
						
					   if($isValid_mobile>0) {  
								$insert                              = FALSE;  
								$response['msg']['mobile_number']    = 'Mobile number is already exists'; 
					   }
					   if($insert){
					     $extension_id                  = $extensions->add($extensionsData);
						
						 $response['status']            = 1;
						 $response['extension_id']      = $extension_id;
						 $response['prev_extension_id'] = $extensionsData['extensions_id'];
						 $response['msg']               = 'The record has been added successfully';
					   }
					   
			break;
		
		case 'remove'  : // REMOVE EXTENSIONS
		               $extension_id  =  $_REQUEST['extensions_id'];
			 		   $result = $extensions->remove($extension_id);
					   if($result){
						 $response['status']                 = 1;
						 $response['extension_id']           = $extension_id;
						 $response['next_extension_id']      = intval($result);
						 $response['msg']                    = 'The record has been deleted successfully';
					   }
			break;
		
		case 'isValid' : // CHECK EXTENSIONS
			 		   $response=$extensions->isValid();
			break;
			
		case 'search'  : // SEARCH EXTENSIONS
			           $response=$extensions->search();
			break;
		default:
	}
	echo json_encode($response); exit;
}
?>


