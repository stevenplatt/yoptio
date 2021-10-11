<?php 
require_once('yoptioapi.php');
class yoptioprofile extends yoptioapi{
   public    function __construct() {
      
   }
   public function profile(){
    global $yoptioapi;
    $profile=new Stdclass();
    $profile->name             = $yoptioapi->getUsername();
    $profile->user_id          = $yoptioapi->getUserid();
    $profile->email            = $yoptioapi->getUseremail();
    $profile->country_code     = "+1";
    $profile->reception_number = $yoptioapi->getUserphonenumber();
    $profile->profilepic       = $yoptioapi->getUserprofilepic();
    $profile->per_page         = 6;
    $usage_periods             = '';	$yoptioapi->getcallsoutbound();    $profile->usage            = array(
                                 'periods'          => $usage_periods,
                                 'minutes_used'     => $yoptioapi->getcallsoutbound(),
                                 'readmsg_count'    => $yoptioapi->yoptiomessage(array('action'  => 'getreadcount','received_number'=>$profile->reception_number)),
                                 'unreadmsg_count'  => $yoptioapi->yoptiomessage(array('action' => 'getunreadcount','received_number'=>$profile->reception_number)),
                                 'read_message'     => $yoptioapi->yoptiomessage(array('action' => 'getread','received_number'=>$profile->reception_number,'page'=>1)),
                                 'unread_message'   => $yoptioapi->yoptiomessage(array('action' => 'getunread','received_number'=>$profile->reception_number,'page'=>1)),
                                 'call_logs'        => $yoptioapi->getUsercalllog(),
                                 'follow_up'        => $yoptioapi->followup(array('action' => 'get')),
                                 );
    return  $profile;
   }
}
$GLOBALS['yoptioprofile'] = new yoptioprofile();
?>