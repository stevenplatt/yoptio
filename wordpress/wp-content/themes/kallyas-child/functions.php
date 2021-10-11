<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
define( 'yoptio_ajax_url',home_url().'/wp-admin/admin-ajax.php');
add_action('wp_head','wp_head_declaration');
function wp_head_declaration(){ ?>
<script type="text/javascript"> var ajax_url = "<?php echo yoptio_ajax_url.'?action=yoptio_action' ?>"; </script> 
<?php 
}
require('thirdparty/extensions.php');
require('thirdparty/yoptioprofile.php');

add_action( 'wp_ajax_yoptio_action', 'yoptio_ajax_function' );
add_action( 'wp_ajax_nopriv_yoptio_action', 'yoptio_ajax_function');
function yoptio_ajax_function(){
$response      = array ('status' => 0 ,'msg' =>'','html'=>'');
$method        = $_REQUEST['method'];
switch ($method)
{
        case 'upload_images':
	            upload_images_callback();
        break;
		
        case 'addfollowup':
	            global $yoptioapi;
				//echo "=321==>>><pre>";print_r($yoptioapi); echo "</pre>";die;
	            $followup_text = $_REQUEST['data']['followup_text'];
	            $res= $yoptioapi->followup(array('followup_text'=>$followup_text,'action' => 'add'));
	            if($res) : 
	            	 $class="list-group-item list-group-item-".$res;
                     $html='<li class="'.$class.'">
                           <div class="checkbox checkbox-success followup-'.$res.'">
                              <input id="checkbox'.$res.'" type="checkbox" class="follow-up-checks" data-id="'.$res.'">
                           <label class="clicktostrick"><span>'.$followup_text.'</span></label>
                           <a href="javascript:void(0);" class="followup-close" data-id="'.$res.'"><i class="fa fa-close"></i></a>
                       	</div>
                    	</li>';
                      $response = array ('status' => 1,'msg' =>'The new follow up has been added successfully','html'=>$html);
	             
             	endif;
        break;
		case 'loadmessage':
		       global $yoptioapi;
		       $page = $_REQUEST['data']['page'];$limit = $_REQUEST['data']['limit'];$status = $_REQUEST['data']['status'];
		       $number = $yoptioapi->getUserphonenumber();
		       if($status=="read"){
                  $res=$yoptioapi->yoptiomessage(array('action' => 'getread','page'=>$page,'status' => $status,'received_number'=>$number));
                  $button_label= "Mark Unread";
                  $button_class="read-btn";
		       }else{
                  $res=$yoptioapi->yoptiomessage(array('action' => 'getunread','page'=>$page,'status' => $status,'received_number'=>$number));
                   $button_label= "Mark Read";
                   $button_class="unread-btn";
               }
              
               if(!empty($res)) :
               	       $wrapper_html='';
               	       foreach($res as $message):
                            $getstartdate=$message->received_date;
                            $unreaddate  = date('l M d - h:ia',strtotime($getstartdate));
                            $wrapper_html.='<div class="col-lg-6 col-md-6 message-panel'.$message->message_id.' col-sm-6 col-xs-12 message-panel">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="phoneno color-blue">'.$message->from_number.'</span>
                                        <div class="panel-action">
                                            <a href="javascript:void(0);" data-id='.$message->message_id.' class="close-panel remove-message" data-perform="panel-dismiss" ><i class="fa fa-close"></i></a>
                                        </div>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">'.str_replace("Sent from your Twilio trial account - ", "",stripcslashes($message->message_text)).'
                                        </div>
                                        <div class="text-right"><a class="btn btn-info m-t-10  m-t-'.$message->message_id.' msg-marker '.$button_class.'" data-id='.$message->message_id.'>'.$button_label.'</a></div>
                                        <div class="panel-footer"><span class="dayofcall color-pink">'.$unreaddate.'</span></div>
                                    </div>
                                </div>
                            </div>';
                   endforeach;
               endif;
               $response      = array ('status' => 1,'html' =>$wrapper_html);
		break;
        case 'moveunread':
                global $yoptioapi;
	            $id = $_REQUEST['data']['id'];
	            $res=$yoptioapi->yoptiomessage(array('id'=>$id,'action' => 'update','status' => 1));
	            if(!empty($res)) : 
                     $response      = array ('status' => 1,'msg' =>'The Message has been moved to Read tab');
	            endif;
	    break;

	    case 'moveread':
                global $yoptioapi;
	            $id = $_REQUEST['data']['id'];
	            $res=$yoptioapi->yoptiomessage(array('id'=>$id,'action' => 'update','status' => 0));
	            if(!empty($res)) : 
                     $response      = array ('status' => 1,'msg' =>'The Message  has been moved to Unread tab');
	            endif;
	    break;

        case 'removemsg':
               
                global $yoptioapi;
	            $id = $_REQUEST['data']['id'];
	            
	            $res=$yoptioapi->yoptiomessage(array('id'=>$id,'action' => 'remove'));
	         
	            if(!empty($res)) : 
                     $response      = array ('status' => 1,'msg' =>'The Message  has been removed from your account');
	            endif;
	    break;
		
	    case 'removefollowup':
                global $yoptioapi;
	            $followup_id = $_REQUEST['data']['id'];
	            $res=$yoptioapi->followup(array('id'=>$followup_id,'action' => 'remove'));
	            if(!empty($res)) : 
                     $response      = array ('status' => 1,'msg' =>'The follow up has been removed successfully');
	            endif;

	    break;
		
        case 'findnumber':
                global $yoptioapi;
                $yoptionumber = $_REQUEST['data']['yoptionumber'];

	            $res=$yoptioapi->searchnumber(array('yoptionumber'=>$yoptionumber,'action' => 'searchnumber'));
	            if(!empty($res)) : 
                     $response      = array ('status' => 1,'is_avail' =>$res['is_avail'],'numberhtml' =>$res['numberhtml']);
	            endif;
	    break;
	    case 'purchasenumber':
                global $yoptioapi;
                $yoptionumber = $_REQUEST['data']['yoptionumber'];
               $res=$yoptioapi->purchasenumber(array('yoptionumber'=>$yoptionumber,'action' => 'purchasenumber'));
	            if(!empty($res)) : 
                     $response      = array ('status' => 1,'sid' =>$res['sid']);
	            endif;
        break;

	    default :
	    
	    break;

  }
  echo json_encode($response);
  exit;   
}

// THIS WILL ALLOW ADDING CUSTOM CSS TO THE style.css FILE and JS code to /js/zn_script_child.js
add_action( 'wp_enqueue_scripts', 'kl_child_scripts',11 );
function kl_child_scripts() {
	wp_deregister_style( 'kallyas-styles' );
    wp_enqueue_style( 'kallyas-styles', get_template_directory_uri().'/style.css', '' , ZN_FW_VERSION );
    wp_enqueue_style( 'kallyas-child', get_stylesheet_uri(), array('kallyas-styles') , ZN_FW_VERSION );
    if ( get_page_template_slug( get_the_ID() ) ){
           //echo get_page_template_slug( get_the_ID() );
    }
   /**
	 **** Uncomment this line if you want to add custom javascript file
   */
	wp_enqueue_script( 'zn_script_child', get_stylesheet_directory_uri() .'/js/zn_script_child.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script( 'zn_script_child', get_stylesheet_directory_uri() .'/js/znscript.min.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script( 'jquery.toast', get_stylesheet_directory_uri() .'/js/jquery.toast.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script( 'yoptioapp', get_stylesheet_directory_uri() .'/js/yoptioapp.js' , '' , ZN_FW_VERSION , true );
	//echo home_url().'--->>'.wp_login_url().'<<<----'; die;
}
/* ======================================================== */
/**
 * Load child theme's textdomain.
 */
add_action( 'after_setup_theme', 'kallyasChildLoadTextDomain' );
function kallyasChildLoadTextDomain(){
   load_child_theme_textdomain( 'zn_framework', get_stylesheet_directory().'/languages' );
}
/* ======================================================== */
/**
 * Example code loading JS in Header. Uncomment to use.
*/
/* ====== REMOVE COMMENT
add_action('wp_head', 'KallyasChild_loadHeadScript' );
function KallyasChild_loadHeadScript(){
	echo '
	<script type="text/javascript">

	// Your JS code here

	</script>';
}
 ====== REMOVE COMMENT */

/* ======================================================== */

/**
 * Example code loading JS in footer. Uncomment to use.
 */

/* ====== REMOVE COMMENT

add_action('wp_footer', 'KallyasChild_loadFooterScript' );
function KallyasChild_loadFooterScript(){

	echo '
	<script type="text/javascript">

	// Your JS code here

	</script>';

}
 ====== REMOVE COMMENT */

/* ======================================================== */
add_filter( 'login_url', 'yoptio_login_page', 10, 3 );
function yoptio_login_page( $login_url, $redirect, $force_reauth ) {
	if(strstr( $redirect,'membership-account')){ //We need to update this condition based different source link to come in login popup
		$login_page = home_url( '/' );
		$login_url = add_query_arg( 'redirect_to', $redirect, $login_page );
		$login_url = add_query_arg( 'panel', 'login', $login_url );
	}
    return $login_url;
}
add_filter( 'kallyas_login_redirect_url', 'login_redirect_url', 10, 2 );
if ( ! function_exists( 'login_redirect_url' ) ) {
	function login_redirect_url(){
		return get_permalink(93);
	}
}
add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
  wp_redirect( home_url( '/' ) );
  exit();
}
add_action( 'wp_enqueue_scripts', 'wp_head_custom',11 );
function wp_head_custom(){
	$files_for = 'reception';
	//wp_enqueue_script( 'zn_script_child_'.$files_for, get_stylesheet_directory_uri() .'/customize/bootstrap/dist/js/bootstrap.min.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script( 'localizedScript' );
	wp_enqueue_style('customize_css_animate', get_stylesheet_directory_uri().'/customize/css/animate.css', '' , ZN_FW_VERSION, true );
	wp_enqueue_style('customize_css_style', get_stylesheet_directory_uri().'/customize/css/style.css', '' , ZN_FW_VERSION, true );
	wp_enqueue_style('customize_css_bootstrap', get_stylesheet_directory_uri().'/customize/bootstrap/dist/css/bootstrap.min.css', '' , ZN_FW_VERSION, true );
	wp_enqueue_style('customize_css_bs_datatable', get_stylesheet_directory_uri().'/customize/plugins/bower_components/datatables/media/css/dataTables.bootstrap.min.css', '' , ZN_FW_VERSION );
	wp_enqueue_style('customize_font_awesome', get_stylesheet_directory_uri().'/customize/font-awesome/css/font-awesome.min.css', '' , ZN_FW_VERSION );
	
	wp_enqueue_script('customize_js_tether', get_stylesheet_directory_uri() .'/customize/bootstrap/dist/js/tether.min.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script('customize_js_bootstrap', get_stylesheet_directory_uri() .'/customize/bootstrap/dist/js/bootstrap.min.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script('customize_js_bootstrap_ext', get_stylesheet_directory_uri() .'/customize/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js' , '' , ZN_FW_VERSION , true );
	wp_enqueue_script('customize_js_datatable', get_stylesheet_directory_uri() .'/customize/plugins/bower_components/datatables/jquery.dataTables.min.js' , '' , ZN_FW_VERSION );
	wp_enqueue_script('customize_js_bs_datatable', get_stylesheet_directory_uri() .'/customize/plugins/bower_components/datatables/media/js/dataTables.bootstrap.min.js' , '' , ZN_FW_VERSION );

}

if ( ! function_exists( 'upload_user_file' ) ) :
	function upload_user_file( $file = array(), $title = false ) {
		require_once ABSPATH.'wp-admin/includes/admin.php';
		$file_return = wp_handle_upload($file, array('test_form' => false));
		if(isset($file_return['error']) || isset($file_return['upload_error_handler'])){
			return false;
		}else{
			$filename = $file_return['file'];
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_content' => '',
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'guid' => $file_return['url']
			);
			if($title){
				$attachment['post_title'] = $title;
			}
			$attachment_id = wp_insert_attachment( $attachment, $filename );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}
		return false;
	}
endif;
if ( ! function_exists( 'reArrayFiles' ) ) :
	function reArrayFiles(&$file_post) {
	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);
	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }
	    return $file_ary;
	}
endif;

if ( ! function_exists( 'upload_images_callback' ) ) :
	function upload_images_callback() {
		$data = array();
		$attachment_ids = array();
		if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'image_upload' ) ){
			$files = reArrayFiles($_FILES['files']);
			if ( empty($_FILES['files']) ) {
				$data['status'] = false;
				$data['message'] = __('Please select an image to upload!','twentysixteen');
			} elseif ( $files[0]['size'] > 5242880 ) { // Maximum image size is 5M
				$data['size'] = $files[0]['size'];
				$data['status'] = false;
				$data['message'] = __('Image is too large. It must be less than 2M!','twentysixteen');
			} else {
				$i = 0;
				$data['message'] = '';
				foreach( $files as $file ){
					if( is_array($file) ){
						$attachment_id = upload_user_file( $file, false );
						
						if ( is_numeric($attachment_id) ) {
							$img_thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
							$data['status'] = true;
							
							$data['message'] .= 
								'<li id="attachment-'.$attachment_id.'">
									<img src="'.$img_thumb[0].'" alt="" />
								</li>';

							$user_id = get_current_user_id();
							//update_user_meta( $user_id, 'avatar_manager_custom_avatar', $attachment_id );	
							

							update_user_meta( $user_id, 'author_avatar', $img_thumb[0] );
							
							$attachment_ids[] = $attachment_id;

							$data['image_url'] =$img_thumb[0];
						}
					}
					$i++;
				}
				if( ! $attachment_ids ){
					$data['status'] = false;
					$data['message'] = __('An error has occured. Your image was not added.','twentysixteen');
				}
			}
		} else {
			$data['status'] = false;
			$data['message'] = __('Nonce verify failed','twentysixteen');
		}
		echo json_encode($data);
		die();
	}
endif;
/* 
*  AUTOMATIC NUMBER ASSIGNMENT AFTER THE CHECKOUT COMPLETED
*/
function my_update_yoptioinformation_after_checkout($user_id)
{
   global $current_user,$yoptioapi;
   get_currentuserinfo();
   $user_login   = $current_user->user_login;
   $res          = $yoptioapi->createsubaccount(array('name'=>$user_login,'action'=>'createsubaccount'),$yoptioapi->AccountSid,$yoptioapi->AuthToken); 
   if($res['status'] == true){
        update_user_meta($user_id,"yoptio_sid", $res['yoptio_sid']);
		update_user_meta($user_id,"yoptio_token", $res['yoptio_token']);

		$number=$yoptioapi->getnumber($yoptioapi->AccountSid,$yoptioapi->AuthToken);  // SEARCH A TOLL FREE NUMBER IN US
		$has_purchased= $yoptioapi->purchasenumber(array('name'=>$user_login,'sid'=>$res['yoptio_sid'],'token'=>$res['yoptio_token'],'yoptionumber'=>$number['phoneNumber']));
		
		if($has_purchased['status'] == true){
			 update_user_meta($user_id,"yoptio_phonesid",$res['yoptio_phonesid']);
			 update_user_meta($user_id,"isoCountry_code",$yoptioapi->country_code);
			 update_user_meta($user_id,"phoneNumber",$number['phoneNumber']);
			 update_user_meta($user_id,"friendlyName",$number['friendlyName']);
		}else{ //failed
			 update_user_meta($user_id,"yoptio_phonesid",'');
			 update_user_meta($user_id,"isoCountry_code",'');
			 update_user_meta($user_id,"phoneNumber",'');
			 update_user_meta($user_id,"friendlyName",'');
		}
		
	}else{  //failed
      update_user_meta($user_id, "yoptio_sid",'');
	  update_user_meta($user_id, "yoptio_token",'');
	}
   
}
add_action('pmpro_after_checkout','my_update_yoptioinformation_after_checkout');
/* 
*  SHOW YOPTIO EXTRA FIELD AT BACKEND SIDE
*/
function my_show_extra_profile_fields($user)
{
?>
	<h3>Yoptio Information</h3>
	<table class="form-table">
      <tr>
			<th><label for="companyname">Country Code</label></th>
            <td>
			   <!-- <label><?php echo esc_attr( get_user_meta($user->ID, 'isoCountry_code', true) ); ?></label>-->
				<input type="text" name="isoCountry_code" id="isoCountry_code" value="<?php echo esc_attr( get_user_meta($user->ID, 'isoCountry_code', true) ); ?>" class="regular-text" /><br />				
			</td>
		</tr>
		
		<tr>
			<th><label for="companyname">Yoptio Phone SID</label></th>
            <td>
				<!--<label for="companyname"><?php echo esc_attr( get_user_meta($user->ID, 'yoptio_phonesid', true) ); ?></label>-->
				<input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo esc_attr( get_user_meta($user->ID, 'yoptio_phonesid', true) ); ?>" class="regular-text" /><br />				
			</td>
		</tr>
		<tr>
			<th><label for="companyname">Yoptio Number</label></th>
            <td>
				<!--<label for="companyname"><?php echo esc_attr( get_user_meta($user->ID, 'phoneNumber', true) ); ?></label>-->
				<input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo esc_attr( get_user_meta($user->ID, 'phoneNumber', true) ); ?>" class="regular-text" /><br />				
			</td>
		</tr>
		<tr>
			<th><label for="companyname">Yoptio Friendly Number</label></th>
     		<td>
			   <!-- <label for="companyname"><?php echo esc_attr( get_user_meta($user->ID, 'friendlyName', true) ); ?></label> -->
				<input type="text" name="friendlyName" id="friendlyName" value="<?php echo esc_attr( get_user_meta($user->ID, 'friendlyName', true) ); ?>" class="regular-text" /><br />				
			</td>
		</tr>
		<tr>
			<th><label for="companyname">Yoptio SID</label></th>
     		<td>
			   <!-- <label for="companyname"><?php echo esc_attr( get_user_meta($user->ID, 'yoptio_sid', true) ); ?></label> -->
				<input type="text" name="yoptio_sid" id="yoptio_sid" value="<?php echo esc_attr( get_user_meta($user->ID, 'yoptio_sid', true) ); ?>" class="regular-text" /><br />				
			</td>
		</tr>
		<tr>
			<th><label for="companyname">Yoptio Token</label></th>
     		<td>
			  <!--  <label for="companyname"><?php echo esc_attr( get_user_meta($user->ID, 'yoptio_token', true) ); ?></label> -->
				<input type="text" name="yoptio_token" id="yoptio_token" value="<?php echo esc_attr( get_user_meta($user->ID, 'yoptio_token', true) ); ?>" class="regular-text" /><br />				
			</td>
		</tr>
		

	</table>
<?php
}
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
/* 
*  UPDATE YOPTIO EMAIL BODY 
*/
add_filter( 'pmpro_email_body', 'my_pmpro_email_body', 10, 2 );
function my_pmpro_email_body( $body, $email ) {
  global $wpdb, $current_user;
 
	if ( strpos( $email->template, 'checkout_' ) === 0 && ! empty( $current_user ) ) {
		// Confirmation message for this level
		$level_message = $wpdb->get_var( "SELECT l.confirmation FROM $wpdb->pmpro_membership_levels l LEFT JOIN $wpdb->pmpro_memberships_users mu ON l.id = mu.membership_id WHERE mu.status = 'active' AND mu.user_id = '" . $current_user->ID . "' LIMIT 1" );
		
		//get user invoice so we can filter
		$invoice = new MemberOrder();
		$invoice->getLastMemberOrder();
	
		//filter
		//$level_message = apply_filters("pmpro_confirmation_message", $level_message, $invoice);
		$isoCountry_code    =  get_user_meta($current_user->ID, 'isoCountry_code', true);
		$phoneNumber   =  get_user_meta($current_user->ID, 'phoneNumber', true);
		$friendlyName  =  get_user_meta($current_user->ID, 'friendlyName', true);
		$level_message="<p> <b>Your Yoptio Number is: </b>".$isoCountry_code." ".$friendlyName." </p>";
		//replace it
		if( ! empty( $level_message ) ) {
			// Replace the 'is now active.</p>' string to append the confirmation message
			$body = str_replace( 'is now active.</p>', 'is now active.</p> ' . $level_message, $body );	
		}
	}
 
	return $body;
}
/* 
*  SAVE  YOPTIO INFORMATION 
*/
function save_extra_user_profile_fields( $user_id ) {
if ( !current_user_can( 'edit_user', $user_id ) ) {  return false; }
update_user_meta( $user_id,'isoCountry_code',$_POST['isoCountry_code']); 
update_user_meta( $user_id,'friendlyName',$_POST['friendlyName']);   
update_user_meta( $user_id,'phoneNumber',$_POST['phoneNumber']); 
update_user_meta( $user_id,'yoptio_sid',$_POST['yoptio_sid']); 
update_user_meta( $user_id,'yoptio_token',$_POST['yoptio_token']);   
}
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
/* 
*  CHANGE THE LOGOUT MENU LINK
*/
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
ob_start();
wp_loginout('index.php');
$loginoutlink = ob_get_contents();
ob_end_clean();
$items .= '<li class="none_logout">'. $loginoutlink .'</li>';
return $items;
}
function new_modify_user_table( $column ) {
    $column['phone'] = 'Phone Number';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'phone' :
				$isoCountry_code    =  get_user_meta($user_id, 'isoCountry_code', true);
				$friendlyName  =  get_user_meta($user_id, 'friendlyName', true);
			return "<strong>".$isoCountry_code.$friendlyName."</strong>";
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );