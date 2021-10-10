<?php 
require_once "includes/function.php";
if(isset($_POST) && is_array($_POST) && count($_POST) > 0){
	define("APP_ROOT", dirname(__FILE__));
	require '/inc/functions-twilio.php';
	api_route();
	$resp_array = array('status'=>false,'action_req'=>'','data'=>array(),'msg'=>'');
	$post_datas = $_POST;
	$resp_array['req_data'] 	= $post_datas;
	echo json_encode($resp_array); die;
}
get_header();
?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/js/app.js<?php echo '?ver='.rand(10,99);?>"></script>
<link rel='stylesheet' id='basilphonecss'  href='<?php echo SITEURL;?>/assets/css/app.css' type='text/css' media='all' />
<header class="entry-header">
<?php 
	 $cart_contents = edd_get_cart_contents();
	 $is_full = (empty($cart_contents) || !is_user_logged_in()) ? 'full' : 'full';
	 if ( is_user_logged_in() ){
		    global $user_ID;
			$user_id = get_current_user_id();
			$office_number       = get_user_meta($user_id,'office_number',true);
			$conference_number   = get_user_meta($user_id,'conference_number',true);
	  }
?>
</header>
<div class="header_title">
    <h2> Checkout </h2>
</div>
<div id="main-content">
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="checkout_process">
                <div class="col-md-9 checkout_process_info <?php echo $is_full;?>">
				<?php 
				if(isset($_GET['status']) && $_GET['status'] =='orderconfirm'){
					echo do_shortcode('[app_order_complete]'); 
					
				}
				else{
					echo do_shortcode('[download_checkout]'); 
				}
					?> 
                </div>
               </div> 
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>