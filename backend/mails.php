<?php
require("../framework/config/backend.config.php");
require_once(_CONTROLLERS_BACKEND_DIR_.'emails.controller.php');

$builder = new EmailBuilder();

#Administrators validation
$administrators_controller->session_check();

#Check administrator privileges
if (!$administrators_controller->module_check(1)){
	$notifications->set("You dont have access to ADMINISTRATORS module.", "error", true);
	$common->redirect("home.php?");
	die();
}

#Module name
$current_module = basename(__FILE__);

#Section title
$smarty->assign("section_title", $builder->siteLabels('module') . ' | ' . $builder->siteLabels('action'));

if($_POST['action'] == 'edit_l10n') {
	$builder->updateL10n($_POST);
}

if($_POST['action'] == 'edit_main') {
	$builder->updateTemplateDesc($_POST);
	header($_SERVER['HTTP_REFERER']);
}

if($_GET['act'] == "main" || $_GET['act'] == null) {	
	$smarty->assign('templates', $builder->collectTemplates());
	
	$smarty->assign('add_link', $current_module . '?act=add');
	$smarty->assign('del_link', $current_module . '?act=del&id=');
	$smarty->assign('edt_link', $current_module . '?act=edit&id=');
	
	$smarty->assign("section_content", 'mail_main.tpl');
}

if($_GET['act'] == 'del' AND !is_null($_GET['id'])) {
	$builder->hideTemplate($_GET['id']);
	
	header("Location: $current_module?act=main");
}

if($_GET['act'] == 'edit') {
	$l10n = $builder->getL10n($_GET['id'], _CLIENT_LANGUAGE_);
	
	$smarty->assign('l10n_vars', $l10n);
	$smarty->assign('l10n_selected', _CLIENT_LANGUAGE_);
	
	$smarty->assign('template', $builder->getEdit($_GET['id']));
	
	$smarty->assign('del_link', $current_module . '?act=del&id=');
	$smarty->assign('cancel_link', $_SERVER['HTTP_REFERER']);
	
	$smarty->assign("section_content", 'mail_edit.tpl');
}

if($_GET['act'] == 'add') {
	$smarty->assign('current_l10n', _CLIENT_LANGUAGE_);
	$smarty->assign("section_content", 'mail_add.tpl');
	
	foreach($_SESSION['supported_languages'] as $key) {
		if($key['lang_code'] == _CLIENT_LANGUAGE_) {
			$lng = $key['name'];
		}
	}
	
	$smarty->assign('language', $lng);
}

if($_POST['action'] == 'add_template') {
	$id = $builder->addTemplate($_POST, $_POST['l10n']);
	
	header("location: $current_module?act=edit&id=$id");
}

if($_GET['act'] == 'testparse') {
	$builder->setTemplate(1, _CLIENT_LANGUAGE_);
	
	$builder->replace('name', 'FirstName LastName');
	
	$builder->replace('email_address', 'firstname@someemail.com');
	
	$builder->cprint($builder->getSubject());
	
	$builder->cprint($builder->getTemplate());
}

#Breadcrumb
$breadcrumb[0]["title"] = "Home";
$breadcrumb[0]["url"] = "home.php?";
$breadcrumb[1]["title"] = "E-mails";
$breadcrumb[1]["url"] = "mails.php?";
$breadcrumb[2]["title"] = $builder->siteLabels('action');
$breadcrumb[2]["url"] = $builder->siteLabels('action_key');

$smarty->assign("breadcrumb", $breadcrumb);

$smarty->display(_TPL_BACKEND_DIR_."layout.tpl");