<?php
ini_set("error_reporting", E_ALL ^ E_NOTICE);
ini_set("display_errors", "On");
ini_set("display_startup_errors", "On");

#Timezone
date_default_timezone_set("Europe/London");

#Devmode
#TODO: Document
define(_DEV_MODE_, true);

#Static data
define(_AREA_BACKEND_, 'A');
define(_AREA_GUEST_, 'B');
define(_AREA_MERCHANT_, 'C');
define(_AREA_PETFUNDATION_, 'D');
define(_AREA_VETERINARIAN_, 'E');

#Production database access
define(_DB_HOST_, 'localhost');
define(_DB_NAME_, 'rocky');
define(_DB_USER_, 'root');
define(_DB_PASS_, 'alerce');
define(_TABLE_PREFIX_, 'rck_');

#General . URLs
define(_SITE_DOMAIN_ , '192.168.2.131');
define(_SITE_COOKIE_DOMAIN_ , _SITE_DOMAIN_);
define(_SITE_EXTRA_DIR_ , 'rocky/'); #Only used for installations outside the root dir
define(_SITE_DOMAIN_HTTP_ , 'http://'._SITE_DOMAIN_."/"._SITE_EXTRA_DIR_);
define(_SITE_DOMAIN_HTTPS_ , 'https://'._SITE_DOMAIN_."/"._SITE_EXTRA_DIR_);

if (strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5)) == 'https'){
	define(_SITE_PROTOCOL_ , 'https');
	define(_SITE_URL_ , _SITE_DOMAIN_HTTPS_);
}else{
	define(_SITE_PROTOCOL_ , 'http');
	define(_SITE_URL_ , _SITE_DOMAIN_HTTP_);
}

#Framework relative path
define(_FRAMEWORK_RELATIVE_PATH_ , 'framework');

#TODO: Reveer si son necesarias las ABSOLUTAS
define(_FRAMEWORK_URL_ , _SITE_URL_ . _FRAMEWORK_RELATIVE_PATH_."/");
define(_APPS_URL_ , _FRAMEWORK_URL_ . 'apps/');
define(_TMP_URL_ , _FRAMEWORK_URL_ . 'tmp/');
define(_TMP_LANG_CACHE_URL_ , _TMP_URL_ . 'lang_cache/');
define(_TMP_SESSIONS_URL_ , _TMP_URL_ . 'sessions/');
define(_TMP_COMPILE_URL_ , _TMP_URL_ . 'compile/');
define(_TPL_URL_ , _FRAMEWORK_URL_ . 'templates/');
#Reative URL
define(_UPLOADS_URL_ ,_SITE_EXTRA_DIR_ . _FRAMEWORK_RELATIVE_PATH_ . '/uploads/');
define(_UPLOADS_IMAGES_URL_ , _UPLOADS_URL_ . 'images/');
define(_UPLOADS_THUMBNAILS_URL_ , _UPLOADS_IMAGES_URL_ . 'thumbnails/');
define(_UPLOADS_TMP_URL_ , _TMP_URL_ . 'uploads/');

#Paths
if (stristr(PHP_OS, 'WIN')) {define(_IS_WINDOWS_, true);}
define(_IIS_, false);
define(_SITE_DIR_ , root_path(dirname(__FILE__)));
define(_FRAMEWORK_DIR_ , unified_path(_SITE_DIR_ . _FRAMEWORK_RELATIVE_PATH_."/"));
define(_APPS_DIR_ , unified_path(_FRAMEWORK_DIR_ . 'apps/'));
define(_TMP_DIR_ , unified_path(_FRAMEWORK_DIR_ . 'tmp/'));
define(_TMP_LANG_CACHE_DIR_ , unified_path(_TMP_DIR_ . 'lang_cache/'));
define(_CORE_DIR_ , unified_path(_FRAMEWORK_DIR_.'core/'));
define(_CONTROLLERS_DIR_ , unified_path(_FRAMEWORK_DIR_.'controllers/'));
define(_UPLOADS_DIR_ , unified_path(_FRAMEWORK_DIR_ . 'uploads/'));
define(_UPLOADS_IMAGES_DIR_ , unified_path(_UPLOADS_DIR_ . 'images/'));
define(_UPLOADS_THUMBNAILS_DIR_ , unified_path(_UPLOADS_IMAGES_DIR_ . 'thumbnails/'));
define(_UPLOADS_TMP_DIR_ , unified_path(_TMP_DIR_ . 'uploads/'));
define(_SMARTY_COMPILE_DIR_ , unified_path(_TMP_DIR_.'compile/'));
define(_DEFAULT_SESSIONS_PATH_ , unified_path(_TMP_DIR_ . 'sessions/'));


define(_MAX_FILES_IN_DIR_ , '1000');

#File & Dir permissions
define(_DEFAULT_DIR_PERMISSIONS_ , '0777');
define(_DEFAULT_FILE_PERMISSIONS_ , '0666');

#Expiration in seconds: 1 day (86400 seconds).
define(_TMP_LANG_CACHE_EXPIRATION_ , '86400');

#Expiration in seconds: 15 days (86400 seconds * 15).
define(_COOKIE_LANG_EXPIRATION_ , (86400*15));

#Templates . General
define(_TPL_DIR_ , _FRAMEWORK_DIR_.'templates/');

#Templates . Frontend
define(_TPL_FRONTEND_DIR_ ,  _TPL_DIR_.'frontend/');
define(_TPL_FRONTEND_URL_ , _TPL_URL_.'frontend/');

#Templates . Backend
define(_TPL_BACKEND_DIR_ , _TPL_DIR_.'backend/');
define(_TPL_BACKEND_URL_ , _TPL_URL_.'backend/');

#Content Delivery Network
define(_CDN_ , false);

if (_CDN_ == true){
	define(_CDN_URL_ , 'http://cdn.'._SITE_DOMAIN_."/");
}else{
	define(_CDN_URL_ , '/');
}

#Common resources URL
define(_CSS_URL_, _CDN_URL_._SITE_EXTRA_DIR_.'css/');
define(_SCRIPTS_URL_, _CDN_URL_._SITE_EXTRA_DIR_.'js/');
define(_IMAGES_URL_, _CDN_URL_._SITE_EXTRA_DIR_.'images/');

#Mailing
define(_RECIPIENT_CONTACT_ , 'test@test.com');
define(_RECIPIENT_SUPPORT_ , 'test@test.com');
define(_RECIPIENT_ADMIN_ , 'test@test.com');

#Mobile
define(_IS_MOBILE_ , true);

#Common functions & file system includes & instances
require_once(_CORE_DIR_."common.core.php");
require_once(_CORE_DIR_."filesystem.core.php");
require_once(_CORE_DIR_."images.core.php");
$common = new common();
$filesystem = new filesystem();
$images = new images();

#Clear cache
if (isset($_GET["cc"]) && _AREA_ == _AREA_BACKEND_ && !empty($_SESSION["user_data"]["id"])){
	$common->clear_cache();
}

#Defaul TMP dir creation (TODO: Replace with filesystem.core.php)
if (!file_exists(_TMP_DIR_)){mkdir(_TMP_DIR_, _DEFAULT_DIR_PERMISSIONS_); chmod(_TMP_DIR_, _DEFAULT_DIR_PERMISSIONS_);}
if (!file_exists(_TMP_LANG_CACHE_DIR_)){mkdir(_TMP_LANG_CACHE_DIR_, _DEFAULT_DIR_PERMISSIONS_); chmod(_TMP_LANG_CACHE_DIR_, _DEFAULT_DIR_PERMISSIONS_);}
if (!file_exists(_SMARTY_COMPILE_DIR_)){mkdir(_SMARTY_COMPILE_DIR_, _DEFAULT_DIR_PERMISSIONS_); chmod(_SMARTY_COMPILE_DIR_, _DEFAULT_DIR_PERMISSIONS_);}
if (!file_exists(_UPLOADS_TMP_DIR_)){mkdir(_UPLOADS_TMP_DIR_, _DEFAULT_DIR_PERMISSIONS_); chmod(_UPLOADS_TMP_DIR_, _DEFAULT_DIR_PERMISSIONS_);}

#General includes & instances
require_once(_CORE_DIR_."database.core.php");
$db = new database();
require_once(_CORE_DIR_."smarty/Smarty.class.php");
require_once(_CORE_DIR_."geoip/geoip.php");
require_once(_CORE_DIR_."languages.core.php");
require_once(_CORE_DIR_."notifications.core.php");
require_once(_CORE_DIR_."pager.core.php");
require_once(_CORE_DIR_."controllers_loader.core.php");
$controllers_loader = new controllers_loader();


function root_path($path){
 if (defined('_IS_WINDOWS_')) {
	$path = str_replace(_FRAMEWORK_RELATIVE_PATH_.'\config', '', $path);
	$path = str_replace('\\', '/', $path);
}else{
	$path = str_replace(_FRAMEWORK_RELATIVE_PATH_.'/config', '', $path);
 }
 return $path;
}
function unified_path($path){
if (defined('_IS_WINDOWS_')) {
	$path = str_replace('\\', '/', $path);
}
return $path;
}
?>