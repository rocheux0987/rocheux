<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>{$site_name}</title>
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
    {literal}
    <style type="text/css">
    body { background: url(img/bg-login.jpg) !important; }
    </style>
    {/literal}
</head>

<body>
		<div class="container-fluid-full">
		<div class="row-fluid">
					
			<div class="row-fluid">
				<div class="login-box">
					<!--div class="icons">
						<a href="index.html"><i class="halflings-icon home"></i></a>
					</div!-->
                    <h1 style="text-align:center"><img src="img/logo.png"></h1>
					<h1 style="text-align:center">Login to your account</h1>
					
                    {include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/notifications.tpl"}
                    
                    <form id="form-login" class="form-horizontal" action="" method="post">
						<fieldset>
							
							<div class="input-prepend" title="Username">
								<span class="add-on"><i class="halflings-icon user"></i></span>
								<input class="input-large span10 enter-trigger" name="username" id="username" type="text" placeholder="username or email"/>
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password">
								<span class="add-on"><i class="halflings-icon lock"></i></span>
								<input class="input-large span10 enter-trigger" name="password" id="password" type="password" placeholder="password"/>
							</div>
							<div class="clearfix"></div>
							<div class="button-login">	
								<button onClick="login();" type="button" class="btn btn-primary">Login</button>
							</div>
							<div class="clearfix"></div>
					</form>
					<hr>
					<p>Forgot Password?</p>
					<p>
						No problem, <a href="#">click here</a> to get a new password.
					</p>	
                    
				</div><!--/span-->
			</div><!--/row-->
			

	</div><!--/.fluid-container-->
	
		</div><!--/fluid-row-->
	    <div class="common-modal modal fade" id="common-Modal1" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-content">
				<ul class="list-inline item-details">
					<li><a href="http://themifycloud.com">Admin templates</a></li>
					<li><a href="http://themescloud.org">Bootstrap themes</a></li>
				</ul>
			</div>
		</div>
		
	<!-- start: JavaScript-->
	{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/scripts.tpl"}
	<!-- end: JavaScript-->
	
    {literal}
	<script type="text/javascript">
	$(document).ready(function() {
    	{/literal}
		{if $smarty.get.error eq 'non-existent'}
			notification("Your user name or password is incorrect, please try again", "error", true);
		{elseif $smarty.get.error eq 'inactive'}
			notification("Your account has being suspended, please contact the site administrator.", "error", true);
		{/if}
		{literal}
		
		$(".enter-trigger").on("keydown", function(e) {
			if (e.which == 13) {
				e.preventDefault();
				login();
			}
		});
				
	});
	
	function login(){
		if ($("#username").val() == ""){
			notification("Please complete your username", "error", true);
			return;
		}
		if ($("#password").val() == ""){
			notification("Please complete your password", "error", true);
			return;
		}
		$("#form-login").submit();
	}
	</script>	
	{/literal}	
    
</body>
</html>
