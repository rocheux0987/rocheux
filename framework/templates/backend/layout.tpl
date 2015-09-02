<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>{$section_title}</title>
	<meta name="description" content="">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link id="bootstrap-style" href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!-- end: CSS -->
	

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="css/ie.css" rel="stylesheet">
	<![endif]-->
	
	<!--[if IE 9]>
		<link id="ie9style" href="css/ie9.css" rel="stylesheet">
	<![endif]-->
		
	<!-- start: Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico">
	<!-- end: Favicon -->
	
	<!-- start: JavaScript includes-->
	{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/scripts.tpl"}
	<!-- end: JavaScript includes-->
	
	<!-- start: JavaScript includes-->
	{literal}
	<script type="text/javascript">
	$(document).ready(function() {
		{/literal}
		{foreach name="notifications" from=$notifications item=notification}
			notification('{$notification.message}', '{$notification.type}', {if $notification.autoclose}true{else}false{/if});
		{/foreach}
		{literal}
	});
    </script>
    {/literal}	
	<!-- end: JavaScript includes-->	
</head>

<body>
		<!-- start: Header -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="home.php?"><span><img src="img/logo.png" width="84px"></span></a>
								
				<!-- start: Header Menu -->
				<div class="nav-no-collapse header-nav">
					<ul class="nav pull-right">
						<li class="dropdown">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="halflings-icon white user"></i> {$user_data.name} {$user_data.lastname}
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="dropdown-menu-title">
 									<span>Account Settings</span>
								</li>
								<li><a href="#"><i class="halflings-icon user"></i> Profile</a></li>
								<li><a href="home.php?log_out=1"><i class="halflings-icon off"></i> Logout</a></li>
							</ul>
						</li>
						<!-- end: User Dropdown -->
					</ul>
                    
                    {* Lang selector *}
    				{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/language_selector.tpl"}
                    
                    
				</div>
				<!-- end: Header Menu -->
				
			</div>
		</div>
	</div>
	<!-- start: Header -->
	
		<div class="container-fluid-full">
		<div class="row-fluid">
			<!-- start: Main Menu -->
			<div id="sidebar-left" class="span2">
				<div class="nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
                        {foreach from=$user_modules item=module}
                            <li>
                            	<a {if $module.options|@count > 1} class="dropmenu" {/if} {if $module.options|@count eq 1}href="{foreach from=$module.options item=option}{$option.url}{/foreach}"{else}href="#"{/if} title="{$module.title}"><i class="icon-bar-chart"></i><span class="hidden-tablet"> {$module.name}</span></a>
                                {if $module.options|@count > 1}
                                <ul>
                                	{foreach from=$module.options item=option}
                                    <li><a class="submenu" href="{$option.url}"><i class="icon-file-alt"></i><span class="hidden-tablet"> {$option.description}</span></a></li>	
                                    {/foreach}
                                </ul>
                                {/if}
                            </li>
                        {/foreach}
					</ul>
				</div>
			</div>
			<!-- end: Main Menu -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<!-- start: Content -->
			<div id="content" class="span10">
            
            {* Breadcrumbs *}
			{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/breadcrumbs.tpl"}
            
            {* Notifications *}
            {include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/notifications.tpl"}
            
            {* Section TPL *}
			{include file="`$smarty.const._TPL_BACKEND_DIR_``$section_content`"}

	</div><!--/.fluid-container-->
	
			<!-- end: Content -->
		</div><!--/#content.span10-->
		</div><!--/fluid-row-->
		
	<div class="modal hide fade" id="myModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Settings</h3>
		</div>
		<div class="modal-body">
			<p>Here settings can be configured...</p>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			<a href="#" class="btn btn-primary">Save changes</a>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<footer>

		<p>
			<span style="text-align:left;float:left">&copy; Rocky superdog</span>
			
		</p>

	</footer>
</body>
</html>
