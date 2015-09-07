<?php
define('_AREA_', 'B');
require_once("common.config.php");

#Frontend . max inactivity time (5 days)
define(_FRONTEND_INACTIVIY_MAXTIME_ , ((3600*24))*5);
define(_FRONTEND_SESSIONS_VALIDATE_ , true);
define(_FRONTEND_SESSIONS_VALIDATE_IP_ , false);
define(_FRONTEND_SESSIONS_VALIDATE_UA_ , true);
define(_FRONTEND_SESSION_NAME_, 'sess_id');

#Tracking codes
define(_GA_CODE_ , '
<script>
  (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,"script","//www.google-analytics.com/analytics.js","ga");

  ga("create", "UA-XXXXXXXXXXXX-1", "mydomain.com");
  ga("send", "pageview");

</script>
');

#General includes & instances
require_once(_CORE_DIR_."friendly.core.php");
require_once(_CORE_DIR_."misc.core.php");
$misc = new misc();

#LANGS initialization
$languages = new languages_core();

require_once(_CORE_DIR_."sessions.core.php");

#Start session mechanism
$session = new session();
$session->init();

#Friendly URLs initialization
$friendly = new friendly();

#Smarty initialization
$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->debugging = false;
#TODO: Test if its necesary
$smarty->caching = false;

if (!$friendly->page){
	$friendly->__construct();
}

#LANGS Smarty assign
$smarty->assign("lang", $languages->langs[_CLIENT_LANGUAGE_]);

$smarty->assign('user_data' , $_SESSION['user_data']);
?>