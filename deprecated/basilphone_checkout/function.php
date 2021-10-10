<?php 
if ( !isset($wp_did_header) ) {
	  $wp_did_header = true;
	  require_once( '../wp-load.php' );
	  if(edd_get_cart_quantity() <= 0){
		  if(!isset($_GET['status']) || $_GET['status'] !='orderconfirm'){
			wp_redirect( home_url() );exit;
		  }
	  }
}
define('SITEURL',home_url()."/basilphoneapp");
define( 'basilphone_ajax_url_',home_url().'/wp-admin/admin-ajax.php');
add_action('wp_head','wp_ahead_declaration');

function wp_ahead_declaration(){ ?>
<script type="text/javascript">
	    var ajax_url = "<?php echo basilphone_ajax_url_.'?action=basilphone_action' ?>";
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var apppage = 1;
</script> 
 <?php 
}
?>