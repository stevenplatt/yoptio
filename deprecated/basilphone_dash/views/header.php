<?php require_once "includes/function.php"; 
if(!is_user_logged_in()):
 wp_redirect(wp_login_url());
  exit; 
endif;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $title;?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="<?php echo SITEURL;?>/assets/img/favicon.ico">
    <link href="<?php echo SITEURL;?>/assets/css/appbasilphone.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITEURL;?>/assets/css/app.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITEURL;?>/assets/css/tables.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo SITEURL;?>/assets/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITEURL;?>/assets/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITEURL;?>/assets/css/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITEURL;?>/assets/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITEURL;?>/assets/css/all.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
	    var ajax_url = "<?php echo basilphone_ajax_url_.'?action=basilphone_action' ?>";
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var APPAJAX = '<?php echo SITEURL ?>/ajax.php';
		var apppage = 1;
		console.log(ajax_url);
   </script> 
</head>
<body class="skin-josh skin-basilphone app-page app-home">
    <header class="header">
        <a href="index.html" class="logo">
            <img src="assets/img/whiteogo.png" alt="logo">
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <div>
                <a href="#" class="navbar-btn sidebar-toggle hide" data-toggle="offcanvas" role="button">
                    <div class="responsive_nav"></div>
                </a>
            </div>
        </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <section class="sidebar ">
                <div class="page-sidebar  sidebar-nav">
                    <div class="nav_icons hide">
                        <ul class="sidebar_threeicons">
                            <li>
                                <a href="advanced_tables.html"> <i class="livicon" data-name="table" title="Advanced tables" data-c="#418BCA" data-hc="#418BCA" data-size="25" data-loop="true"></i> </a>
                            </li>
                            <li>
                                <a href="tasks.html"> <i class="livicon" data-c="#EF6F6C" title="Tasks" data-hc="#EF6F6C" data-name="list-ul" data-size="25" data-loop="true"></i> </a>
                            </li>
                            <li>
                                <a href="gallery.html"> <i class="livicon" data-name="image" title="Gallery" data-c="#F89A14" data-hc="#F89A14" data-size="25" data-loop="true"></i> </a>
                            </li>
                            <li>
                                <a href="users_list.html"> <i class="livicon" data-name="users" title="Users List" data-size="25" data-c="#01bc8c" data-hc="#01bc8c" data-loop="true"></i> </a>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <!-- BEGIN SIDEBAR MENU -->
                    
                    <?php   $slug=getSlug(); ?>
                    <ul class="page-sidebar-menu" id="menu">
                        <li <?php if($slug=="home") echo "class='active'"; else echo '';?>>
                            <a href="<?php echo SITEURL;?>/home.php">
                            <i class="livicon" data-name="home" data-size="18" data-c="#FFF" data-hc="#FFF" data-loop="true"></i>
                            <span class="title">Virtual Reception</span>
                            </a>
                        </li>
                        <li <?php if($slug=="analytics") echo "class='active'"; else echo '';?>>
                            <a href="<?php echo SITEURL;?>/analytics.php">
                            <i class="livicon" data-name="linechart" data-size="18" data-c="#FFF" data-hc="#FFF" data-loop="true"></i>
                            <span class="title">Analytics</span>
                            </a>
                        </li>
                        <li <?php if($slug=="user-extensions") echo "class='active'"; else echo '';?>>
                            <a href="<?php echo SITEURL;?>/user-extensions.php">
                            <i class="livicon" data-name="users" data-size="18" data-c="#FFF" data-hc="#FFF" data-loop="true"></i>
                            <span class="title">User Extensions</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" target="_blank">
                            <i class="livicon" data-name="comment" data-size="18" data-c="#FFF" data-hc="#FFF" data-loop="true"></i>
                            <span class="title">Support</span>
                             <span class="fa arrow"></span>
                             </a>
                             <ul class="sub-menu collapse" aria-expanded="false">
                                    <li>
                                        <a href="https://deskportal.zoho.com/portal/basilphone/kb/virtual-office">
                                            <i class="fa fa-angle-double-right"></i> Contact Support
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">
                                            <i class="fa fa-angle-double-right"></i> Account
                                        </a>
                                    </li>
                                </ul>
                           
                        </li>
                        <li>
                            <a href="<?php echo wp_logout_url(); ?>">
                            <i class="livicon" data-name="sign-out" data-size="18" data-c="#FFF" data-hc="#FFF" data-loop="true"></i>
                            <span class="title">Logout</span>
                            </a>
                        </li>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
            </section>
            <!-- /.sidebar -->
              
        </aside>
        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">