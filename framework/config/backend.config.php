<?php
define('_AREA_', 'A');
require_once("common.config.php");

#Backend path
define(_BACKEND_FOLDER_ , 'backend/');
define(_BACKEND_DIR_ , unified_path(_SITE_DIR_ . _BACKEND_FOLDER_));
define(_CONTROLLERS_BACKEND_DIR_ , unified_path(_CONTROLLERS_DIR_ . 'backend/'));

#Backend url
define(_BACKEND_URL_ , _SITE_URL_ . _BACKEND_FOLDER_);
define(_BACKEND_LOGIN_FILE_ , 'chacarita.php');
define(_BACKEND_LOGIN_URL_ , _BACKEND_URL_ . _BACKEND_LOGIN_FILE_);

#Backend . max inactivity time (1 day)
define(_BACKEND_INACTIVIY_MAXTIME_ , (3600*24));

#Sessions path
define(_BACKEND_SESSIONS_PATH_ , unified_path(_BACKEND_DIR_."sessions/"));

#Backend title
define(_BACKEND_TITLE_, 'Rocky superdog');

#General includes & instances
require_once(_CONTROLLERS_BACKEND_DIR_."administrators.controller.php");
	
#LANGS initialization
$languages = new languages_core();

#Smarty initialization
$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->debugging = false;
$smarty->caching = false;

#LANGS Smarty assign
$smarty->assign("lang", $languages->langs[_CLIENT_LANGUAGE_]);

#LANGS Smarty assign
$smarty->assign("site_name", _BACKEND_TITLE_);

#Notifications initialization
$notifications = new notifications();

#Administrators initialization
$administrators_controller = new administrators();

#Pager initialization
$pager = new pager();

#Smarty general assigns
$smarty->assign("backend_base_url", _BACKEND_URL_);

?>