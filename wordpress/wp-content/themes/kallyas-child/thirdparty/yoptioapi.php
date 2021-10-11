<?php 
//ini_set("display_errors", "1");
//error_reporting(E_ALL);
require_once "Twilio/autoload.php";
use Twilio\Rest\Client;
use Twilio\Twiml;
class yoptioapi {
   public     $AccountSid        = 'AC2ccdf863aabec7b9e62927f64a4e055c';
   public     $AuthToken         = '7f2e338e93e643fdb6d7cef79c65da5c';
   public     $country           = 'US';
   public     $country_code      = '+1';
   public     $prompt_folderpath = 'yoptioreception/prompt';
   public     function __construct() {
   }
   /* Initialize the twilio account */
   public function connect($sid='',$token=''){
    $sid   = ($sid == '') ? $this->getAccountSid() : $sid;
    $token = ($token == '') ? $this->getAuthToken() : $token;
    return $client = new Client($sid,$token);
   }
   /* Get Twiml object */
   public function connectTWIML(){
    return $response = new Twiml;
   }
   /* Get account sid */
   protected function getAccountSid($user_id=''){
    $user_id = $this->getUserid();
    return $my_yoptiofriendlynumber=get_user_meta($user_id,'yoptio_sid',true);
   }
   /* Get account token */
   protected function getAuthToken($user_id=''){
    $user_id = $this->getUserid();
    return $my_yoptiofriendlynumber=get_user_meta($user_id,'yoptio_token',true);
   }
   /* Automatic number assignment */
   public function getnumber($AccountSid='',$AuthToken=''){
    $client = $this->connect($AccountSid,$AuthToken);
    $numbers = $client->availablePhoneNumbers($this->country)->tollFree->read();
    $new_number=array();
    $continue = true;
    foreach($numbers as $number){
       if( $continue)
	   {
          $new_number   =  array(
                                  'friendlyName'         =>  $number->friendlyName,
                                  'phoneNumber'          =>  $number->phoneNumber,
                                  'lata'                 =>  $number->lata,
                                  'rateCenter'           =>  $number->rateCenter,
                                  'latitude'             =>  $number->latitude,
                                  'longitude'            =>  $number->longitude,
                                  'region'               =>  $number->region,
                                  'postalCode'           =>  $number->postalCode,
                                  'isoCountry'           =>  $number->isoCountry,
                                  'addressRequirements'  =>  $number->addressRequirements,
                                  'beta'                 =>  $number->beta,
                                  'capabilities'         =>  $number->capabilities
                              );

        }
        $continue=false;
    }
    return $new_number;
   }
   /* Get site url */
   public function site_URL(){
    return  site_url()."/";
   }
   /* Get prompt url */
   public function getPromptURL(){
    return  $this->site_url().$this->prompt_folderpath;
   }
  /* Purchase a number from twilio */
  public  function purchasenumber($data=array()){
    $status_arr['api_status'] = false; 
    $status_arr['api_msg']    = 'Error in twilio purchase number';
    try{
       $clientObj         = $this->connect($data['sid'],$data['token']);
       $yoptionumber      = $data['yoptionumber'];
       $name              = $data['name'];
       $args              = array("friendlyName" =>$name,"phoneNumber" => $yoptionumber);
       $number            = $clientObj->incomingPhoneNumbers->create($args);
       $status_arr['api_resp']  = $number; 
       if($number->sid!='')
	   {
        $SITE_URL = $this->site_URL();
		$status_arr['api_msg']           = 'Successfully created account for subscribed user';
        $status_arr['api_status']        = true; 
        $status_arr['status']            = true;
        $status_arr['yoptio_phonesid']   = $number->sid; 
        $status_arr['uri']               = $number->uri;
		$clientObj->incomingPhoneNumbers($number->sid)->update(array(
                    "voiceUrl" => $SITE_URL."yoptio.php?method=init","VoiceMethod" => "POST")
                );
       }
      }
      catch(Exception $e)
	  {
       global $twilio_err_msg;
       $err = $e->getCode();
       $yoptiosid['api_error_code'] = $err;
       $yoptiosid['api_msg'] = $e->getMessage();
       return $yoptiosid;
      }
      return  $status_arr;
  }
  /* Create a subaccount */
  public  function createsubaccount($data=array(),$AccountSid='',$AuthToken=''){
    $clientObj  = $this->connect($AccountSid,$AuthToken);
    $yoptiosid['api_status']= false; 
    $yoptiosid['api_msg']   = 'Subaccount not created properly';
    try{
      $account    = $clientObj->api->accounts->create(array('FriendlyName' => $data['name'])); // Create account in twilio
      if($account->sid!=''){
        $yoptiosid['api_msg']     = 'Successfully created account for subscribed user';
        $yoptiosid['api_status']  = true; 
        $yoptiosid['status']      = true; 
        $yoptiosid['yoptio_sid']  = $account->sid;
        $yoptiosid['yoptio_token']= $account->authToken;
      }
      $yoptiosid['api_resp']  = $account; 
    }
    catch(Exception $e){
      global $twilio_err_msg;
      $err = $e->getCode();
      $yoptiosid['api_error_code'] = $err;
      $yoptiosid['api_msg'] = $e->getMessage();
      return $yoptiosid;
    }
    return $yoptiosid;
  }
  /* Clean string is in number */
  public  function cleannumber($String) {
    $string = str_replace('-','',$String);                // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/','', $string);  // Removes special chars.
  }
  /* Get user phone number */
  public  function getUserphonenumber() {
    $user_id = $this->getUserid();
    $phoneNumber= get_user_meta($user_id,'phoneNumber',true);
    return ($phoneNumber!='') ? $phoneNumber : '--';
  }
  /* Get user friendlynumber */
  public  function getUserfriendlyphonenumber() {
    $user_id = $this->getUserid();
    $friendlyName = get_user_meta($user_id,'friendlyName',true);
    return ($friendlyName!='') ? $friendlyName : '--';
  }
 /* Set user Conferrence ID **/
  public  function setUserconferrence($data=array()) {
    $user_id = $this->getUserid();
    $yoptio_conferrenceid = updated_user_meta($user_id,'yoptio_conferrenceid',$data['conferrenceid']);
	return ($yoptio_conferrenceid!='') ? $yoptio_conferrenceid : 0;
  }
 /* Get user sid **/
  public  function getUsersid($data=array()) {
    $user_id = $this->getUserid();
    $yoptio_sid = get_user_meta($user_id,'yoptio_sid',true);
    return ($yoptio_sid!='') ? $yoptio_sid : '--';
  }
  /* Get outbound calls log */
  public  function getcallsoutbound($data=array()) {
    $account_sid=$this->getAccountSid();
	$token=$this->getAuthToken();	$clientObj=$this->connect($account_sid,$token);
	$getUserdate  = $this->getUserdate();
	$records=$clientObj->usage->records->read(
											array('Category' => "calls-outbound", 
												 'StartDate' =>$getUserdate['startdate'],
												 'EndDate' =>$getUserdate['enddate']
												)
											);	$total_miutes = 0; 	if(!empty($records)){	 foreach($records as $eachrecord):		      $total_miutes+=$eachrecord->usage;		 endforeach;	}    return $total_miutes;
  }
  /* Get unread message */
  public  function getUnreadmessage() {
    $user_id = $this->getUserid();
    return 0;
  }
  /* Get the usage periods */
  public function getUsageperiods($user)
  {
	$order = new MemberOrder();
	$order->getLastMemberOrder($user, array('success', 'cancelled', ''));
	if(!empty($order) && !empty($order->id)) {
		$get_startdate=date(get_option('date_format'), $order->timestamp);
	} else {
		$get_startdate="";
	}
	$newDate = array('startdate'=>'','enddate'=>'');
	if(!empty($get_startdate)){
	  $newDate['startdate'] = date('Y-m-d',strtotime($get_startdate));
	  $newDate['enddate'] = date($newDate['startdate'],strtotime('next month'));
	}
	return $newDate;
  }
 /* get inBound message for this number */
  public  function getUserincomingmessage() {
	$getUserphonenumber = $this->getUserphonenumber();
	$credential         = $this->findCredential($getUserphonenumber);
    $clientObj          = $this->connect($credential['Accountsid'],$credential['AccountToken']);
	$args     = array('To' => $getUserphonenumber);
	$messages = $clientObj->messages->read($args);
	return $messages;
  }
 /* Get user log */
   public function getUsercalllog() {
    $getUserphonenumber = $this->getUserphonenumber();
	$credential         = $this->findCredential($getUserphonenumber);
    $clientObj          = $this->connect($credential['Accountsid'],$credential['AccountToken']);
    $getUserdate  		= $this->getUserdate();
	$args       		= array('from' => $getUserphonenumber);
    $calls = $clientObj->calls->read(
					array(
						 "from" =>$getUserphonenumber,
						 "status" =>'completed',
						 "starttimeAfter"  => $getUserdate['startdate'],
						 "starttimeBefore" => $getUserdate['enddate']
					   )
    );
    return $calls;
   }
   /*
   * diff from datetime
   * @param datetime $dt1
   * @param datetime $dt2
   * @return object $dtd (day, hour, min, sec / total)
   */
   public function datetimeDiff($dt1, $dt2){
	$t1 = strtotime($dt1);
	$t2 = strtotime($dt2);
	$dtd = new stdClass();
	$dtd->interval = $t2 - $t1;
	$dtd->total_sec = abs($t2-$t1);
	$dtd->total_min = floor($dtd->total_sec/60);
	$dtd->total_hour = floor($dtd->total_min/60);
	$dtd->total_day = floor($dtd->total_hour/24);
	
	$call_difference = array(); 		
   
	$dtd->day = $dtd->total_day;
	
	if(!empty($dtd->day))
		 $call_difference[]=$dtd->hour;
	 
	$dtd->hour = $dtd->total_hour -($dtd->total_day*24);
	if(!empty($dtd->hour))
		 $call_difference[]=$dtd->hour;
	 
	$call_difference[]=$dtd->min = $dtd->total_min -($dtd->total_hour*60);
	$call_difference[]=$dtd->sec = $dtd->total_sec -($dtd->total_min*60);
	
	$calldifference=(!empty($call_difference)) ? implode(":",$call_difference) : '0:00';
	return $calldifference;
  }
  /* Get user usage periods */
  public function getUserdate() {
  $user_id = $this->getUserid();
  $Usageperiods=$this->getUsageperiods($user_id);
	$getUserdate['startdate'] = $Usageperiods['startdate'];
  $getUserdate['enddate'] =  $Usageperiods['enddate'];
  return $getUserdate;
  }
  /* Get user profile pic */
  public  function getUserprofilepic() {
	global $current_user;
    $user_id = $this->getUserid();
    $author_avatar=get_user_meta($user_id,'author_avatar');
    $profile_pic=get_avatar_url(get_avatar( $current_user->ID, 98));
    return  (isset($author_avatar[0]) && $author_avatar[0]!='') ? $author_avatar[0] : $profile_pic;
  }
  /* Get the username */
  public  function getUsername() {
    global $current_user;
    return $current_user->display_name;
  }
  /* Get the user email */
  public  function getUseremail() {
    global $current_user;
    return $current_user->user_email;
  }
  /* Get the user id */
  protected  function getUserid() {
    global $current_user;
    return $current_user->ID;
  }
  /*
  Twilio setup Module starts here
  * 1. Make a Call
  * 2. Init
  * 3. Connect to conference
  * 4. Connect to user
  * 5. Record
  * 6. Email
  * 7. Hangup
  */
  public  function Makeacall($data=array()) {
    $credential   = $this->findCredential($data['from_number']);
    $clientObj    = $this->connect($credential['Accountsid'],$credential['AccountToken']);
    $call=$clientObj->calls->create($data['to_number'],$data['from_number'],$data['args']);
	if($data['response']==1){
	  echo "<pre>";print_r($call);echo "</pre>";
	}
  }
  /*Init twilio API*/
  public  function init($data=array()) {
 	$SITE_URL = $this->site_URL();
    $prompt_path = $this->getPromptURL();
    $response = new Twiml;
    $gather=$response->gather(array('numDigits'=>'1','timeout'=>'5','finishOnKey'=>'#','action'=>$SITE_URL."yoptio.php?method=connecttoconference",'method'=>'POST'));
    $gather->play($prompt_path.'/amy_reception.mp3');
    $response->redirect($SITE_URL."yoptio.php?method=connecttouser");
    echo $response;
 }
  /*Connect to conference Twilio API*/
  public  function connecttoconference($data=array()) {
	$SITE_URL = $this->site_URL();
    $prompt_path = $this->getPromptURL();
    $from_users = $this->getUsersbyphone($data['From']);   
    $option = $data['Digits'];
    if( $option!=1) {  //Redirect to init mehtod if the options is not #.
      header("Location:".$SITE_URL."yoptio.php?method=init");
    } 
    else{ //IF OPTION # CONNECT TO CONFERENCE
      $response = new Twiml;
      $response->play($prompt_path.'/amy_conference.mp3');
      $dial=$response->dial();
      $dial->conference('moderated-conference-room',array('startConferenceOnEnter' => 'true',
	  'endConferenceOnExit' => 'true','statusCallback'=>$SITE_URL."yoptio.php?method=saveconference","statusCallbackEvent"=>"start end join leave mute"));
      $response->redirect($SITE_URL."yoptio.php?method=hangup");
      echo $response;
    }
  }
  /*Connect to user*/
  public function connecttouser($data=array()){
	$SITE_URL = $this->site_URL();
	$from     = $data['From'];
	$to       = $data['To'];
	$response = new Twiml;
	$dial=$response->dial(array('action'=>$SITE_URL."yoptio.php?method=record","timeout"=>"5")); 
	$dial->number($to,array('url'=>"http://demo.twilio.com/docs/voice.xml"));
	echo $response;
  }
  /*Connect to voice record*/
  public function record($data=array()){
    $SITE_URL = $this->site_URL();
	$prompt_path = $this->getPromptURL();
    $from     = $data['From'];
    $to       = $data['To'];
    $response = new Twiml;
    $response->play($prompt_path.'/amy_message_updated.mp3');
	$response->gather(array('input'=>'speech','action'=>$SITE_URL."yoptio.php?method=mail",'method'=>'GET','timeout' =>60));
    $response->redirect($SITE_URL."yoptio.php?method=hangup");
	echo $response;
  }
  /*Send voice recording as email & message*/
  public function email($data=array()){
    $SITE_URL           = $this->site_URL();
    $from               = $data['From'];
    $to                 = $data['To'];
    $RecordingSid       = $data['RecordingSid'];
	$recordingurl       = $data['RecordingUrl'];
	$Transcription_Text = '';
    $credential         = $this->findCredential($from);
    $clientObj          = $this->connect($credential['Accountsid'],$credential['AccountToken']);
  
	$Transcription_Text=(isset($data['SpeechResult']) && !empty($data['SpeechResult'])) ? $data['SpeechResult'] : $Transcription_Text;
	//$Transcription_Text="This is test message from developer side.";
	if(!empty($Transcription_Text)){
		
		$from_users = $this->getUsersbyphone($from); //Get from user_email & user_login
		$from_email = (!empty($from_users['user_email'])) ? $from_users['user_email'] : get_bloginfo('admin_email') ;
		$from_name  = (!empty($from_users['user_login'])) ? $from_users['user_login'] : get_bloginfo('name') ; 
		
		$to_users  = $this->getUsersbyphone($to); //Get to user_email & user_login
		$to_email  = $to_users['user_email'];
		$to_userid = $to_users['user_id'];
		$to_name   = $to_users['user_login'];
	
		$toemail  = $to_email; //Send email to address
		$subject  = "New message from {$from_name}";
		$message  = "<p>You have received a message from {$from_name}</p></br>
					 <p>Message: {$Transcription_Text}</p></br>
					 <p>Regards,</p>
					 <p>Yoptio Team</p>";
		$headers=array('Content-Type'=>'text/html;charset=UTF-8','Reply-To'=>$from_name.'<'.$from_email.'>',);
		$is_send=wp_mail($toemail,$subject,$message,$headers);
		$send_message=$clientObj->account->messages->create($to,array('from'=>$from,'body'=>$Transcription_Text,));
		if($send_message->sid) //Get the message sid
		{ 
		  $dateSent = (array)$send_message->dateSent;
		  $message_format = array(
									'action'        => 'add',
									'user_id'       => $to_userid,
									'sid'           => $send_message->sid,
									'from'          => $send_message->from,
									'message_text'  => $send_message->body,
									'to'            => $send_message->to,
									'received_date' => $dateSent['date'],
									'callsid'       => $data['CallSid'],
									'status'        => 0
								 );
		  $is_stored=$this->yoptiomessage($message_format); //Store the message into yoptio DB.
		}
		$response = new Twiml;
		$response->redirect($SITE_URL."yoptio.php?method=hangup");
		echo $response;
	}
  }
  /*Exit the call,conference call and record*/
  public function hangup($data=array()){
	$prompt_path = $this->getPromptURL();
	$response = new Twiml;
	$response->play($prompt_path.'/amy_yoptio_goodbye_alt.mp3');
	$response->hangup();
	echo $response;
  }
  /*Find user credential*/
  protected  function findCredential($number=''){
    if(!empty($number)){
 	  $users                 = $this->getUsersbyphone($number);
      $user_id               = $users['user_id'];
      $data['AccountToken']  = get_user_meta($user_id,'yoptio_token',true); 
      $data['Accountsid']    = get_user_meta($user_id,'yoptio_sid',true);
      return $data;
    }
  }
  /*Set the message status and store the message sid in Yoptio DB*/
  public function yoptiomessage($data=array()){
	global $wpdb;
	$table_name = $wpdb->prefix.'yoptiomessages';
	if($data['action']=='add'): //Insert the message into Yoptio DB.
		$data = array(
			'message_id'        => $data['sid'],
			'from_number'       => $data['from'],
			'user_id'           => $data['user_id'],
			'to_number'         => $data['to'],
			'message_text'      => $data['message_text'],
			'received_date'     => $data['received_date'],
			'status'            => $data['status'],
            'callsid'           => $data['callsid']			
		);
		$wpdb->insert($table_name,$data);
		$results=$wpdb->insert_id;
	elseif($data['action']=='get'):
	  $callsid =$data['callsid'];
	  $get_query   = 'SELECT * FROM '.$table_name.' WHERE callsid ="'.$callsid.'"';
	  $results     = $wpdb->get_results( $get_query, OBJECT );
	  
	elseif($data['action']=='getreadcount'):
		$received_number =$data['received_number'];
		$get_query   = 'SELECT count(*) as total FROM '.$table_name.' WHERE status=1 and to_number="'.$received_number.'"';
		$results     = $wpdb->get_results( $get_query, OBJECT );
		return  $results[0]->total;
	elseif($data['action']=='getunreadcount'):
		$received_number =$data['received_number'];
		$get_query   = 'SELECT count(*) as total FROM '.$table_name.' WHERE status=0 and to_number="'.$received_number.'"';
		$results     = $wpdb->get_results( $get_query, OBJECT);
		return  $results[0]->total;
		
	elseif($data['action']=='getread'):
		$received_number =$data['received_number'];
		$getread_page    =(empty($data['page'])) ? 1 : $data['page'];
		$get_query   = 'SELECT * FROM '.$table_name.' WHERE status=1 and to_number ="'.$received_number.'" limit '.$getread_page.',2';
		$results     = $wpdb->get_results( $get_query, OBJECT );
		
	elseif($data['action']=='getunread'):
		$received_number =$data['received_number'];
		$getread_page    =(empty($data['page'])) ? 1 : $data['page'];
		$get_query   = 'SELECT * FROM '.$table_name.' WHERE status=0 and to_number ="'.$received_number.'" limit '.$getread_page.',2';
		$results     = $wpdb->get_results( $get_query, OBJECT );
		
	elseif($data['action']=='getreadstatus'):
		$received_number =$data['received_number'];
		$get_query   = 'SELECT * FROM '.$table_name.' WHERE to_number ="'.$received_number.'"';
		$results     = $wpdb->get_results( $get_query, OBJECT );
		
	elseif($data['action']=='update'):
		$message_id = $data['id'];
		$status = $data['status'];
		$update_qry = "Update " . $table_name . " Set status = $status Where message_id = '".$message_id."'"; 
		//707 need to add user Id
		$results = $wpdb->query($update_qry);
	  
	endif;
	   return $results;
	}
	/*Followup : ADD,DELETE and UPDATE*/
	public function followup($data=array()){
	 global $wpdb;
	 $table_name = $wpdb->prefix.'yoptio_followup';
		if($data['action']=='add'):
		   // INSERT FOLLOW UP DATA
			$date = date("Y-m-d"); 
			$data = array(
						'user_id'        => $this->getUserId(),
						'followup_text'  => $data['followup_text'],
						'created_date'   => $date,
						'status'         => 1 
						);
			$wpdb->insert($table_name,$data);
			$results=$wpdb->insert_id;
		elseif($data['action']=='remove'):
			$id = $data['id'];
			$delet_sql="DELETE FROM ".$table_name." WHERE  id=".$id;     
			$results = $wpdb->query($delet_sql);   
		elseif($data['action']=='get'):
		    $user_id =$this->getUserId();
		    $get_query   = 'SELECT * FROM '.$table_name.' WHERE user_id ='.$user_id.' AND status=1 order by id desc';
		    $results     = $wpdb->get_results( $get_query, OBJECT );
		endif;
		return $results; // @Pass the followup ID.
	}
    /*Get userdetails from WP_users by using phone number.*/
	public function getUsersbyphone($phonenumber=""){
	   $user_query = new WP_User_Query(array('meta_key'=>'phoneNumber','meta_value'=>$phonenumber));
	   $userdata=$user_query->get_results();	
	   if(!empty($userdata)):
	   $user_data = array(
									'user_id'       => $userdata[0]->ID,
									'user_login'    => $userdata[0]->data->user_login,
									'user_nicename' => $userdata[0]->data->user_nicename,
									'user_email'    => $userdata[0]->data->user_email,
									'display_name'  => $userdata[0]->data->display_name
		   );
	 else:
		 $user_data = array();  
	 endif;
      
     return $user_data;	  
	}
}
$GLOBALS['yoptioapi'] = new yoptioapi();
?>