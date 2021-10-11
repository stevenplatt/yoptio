<?php
# Database Configuration
define( 'DB_NAME', 'wp_yoptioprod01' );
define( 'DB_USER', 'yoptioprod01' );
define( 'DB_PASSWORD', 'KfzdbBhQLcpnMg4BkODK' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         's9L9_L8@~EOzW`UYPbk8s,j#pLaSz&N1&g+,tS}_b#=y$9&%/XMx]P`PJr5i8NJb');
define('SECURE_AUTH_KEY',  'l|iARco1uz}w@.|miU$Py[6L*;%5xtkOPb+D0,PI6|zZm4N>uPS@HZd#.zdJhRk-');
define('LOGGED_IN_KEY',    'J`?v;CF:HejZ<N$5DpJLF+9Qou.0KUlA}ua2=Fht0c61R(B^6q6`2Q.Z]-+8{PSQ');
define('NONCE_KEY',        'GU -pq7=lC%Nme[9Jkx|uG&f 6M$Nult<04Q8}d`JlmEw9Ylb8d-H0>3Ot:D{P5 ');
define('AUTH_SALT',        '+PFo9n+.5|%l9^G}0>2<&$Rw}%?6~a1]P[mxWA#vw1im[!<c+}}2F-+YX-n`/eXR');
define('SECURE_AUTH_SALT', 'k|%|b& $[3*_2)2_+0l]!$~kB](ULgIjZ]Gm%Ogpf{hg68Y#Kh9uzD/-mC?K8{@H');
define('LOGGED_IN_SALT',   '|FdCMk:3]-vV,OD]!eVmrA9+n$J>-+bUB5f9;yI,o& lO4:*)Qc]`O)wfbAq|iX{');
define('NONCE_SALT',       '1El2_kymu-FAq.(;-&mE]toF/*H/9}p1I>t HYVaZ#86s7/8}GBxV[Qwz-(Z6HWz');

define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );
 
# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'yoptioprod01' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '3cefc2d30872668d1043087adf1e9349d4eedc5a' );

define( 'WPE_CLUSTER_ID', '120072' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', true );

define( 'FORCE_SSL_LOGIN', true );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'yoptio.com', 1 => 'yoptioprod01.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-120072', );

$wpe_special_ips=array ( 0 => '104.196.248.198', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
