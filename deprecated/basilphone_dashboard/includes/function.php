<?php 
if ( !isset($wp_did_header) ) {
	  $wp_did_header = true;
	  require_once( '../wp-load.php' );
	  if(edd_get_cart_quantity() <= 0){
		  if(!isset($_GET['status']) || $_GET['status'] !='orderconfirm'){
			if(is_user_logged_in()){
				$user_id = get_current_user_id();
				$user_status = get_user_meta($user_id,'payment_status',true);
				if($user_status == 1){ } else{}
			}
			else{
				wp_redirect( home_url() );exit;
			}
		  }
	  }
}
/*======================================================
         INCLUDE Extensions CLASS
========================================================*/
if (!class_exists('extensions')) {
require_once( 'includes/extensions.php' );
}
 
$title = "BasilPhone App - Home";
define('SITEURL',home_url()."/basilphoneapp");
define( 'basilphone_ajax_url_',home_url().'/wp-admin/admin-ajax.php');
add_action('wp_head','wp_ahead_declaration');

function wp_ahead_declaration(){ ?>
<script type="text/javascript">
	    var ajax_url = "<?php echo basilphone_ajax_url_.'?action=basilphone_action' ?>";
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var apppage = 1;
		console.log(ajax_url);
</script> 
 <?php 
}


/**   GET THE SLUG FOR DAHSBOARDS SHELL PAGE **/
function getSlug()
{
$url         = $_SERVER['REQUEST_URI'];
$explodeurl  = explode('.',$url);
$explodeurls = explode('/',$explodeurl[0]);
return end($explodeurls);
}
?>