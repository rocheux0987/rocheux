{literal}
<script type="text/javascript">
function sample_login(){
	errors = 0;	
	if ($("#user").val() == ""){
		$("#user").addClass("field-error");	
		errors++;
	}else{
		$("#user").removeClass("field-error");		
	}
	if ($("#password").val() == ""){
		$("#password").addClass("field-error");	
		errors++;
	}else{
		$("#password").removeClass("field-error");		
	}
	if (errors>0){
		return false;	
	}
	
$.ajax({
	url: '{/literal}{$current_url}{literal}?act=login',
	type:'POST',
	data: 'user='+$('#user').val()+'&password='+$('#password').val(),
	beforeSend: function(){
		$('body, html, a').css({'cursor':'wait'});
	},
	success: function(response){	
		//Parse JSON data
		response_data = jQuery.parseJSON(response);
		$("#login-response").html(response_data["message"]);
		
		if (response_data["status"] == 1){
			//Logged events, like redirect to protected area
			$("#login-response").css({'color':'#008000'});
		}else{
			$("#login-response").css({'color':'red'});	
		}
		
		$("#user").val("");
		$("#password").val("");
		
		$('body, html, a').css({'cursor':'default'});
		$('a').css({'cursor':'pointer'});
	}	
});		
}
</script>
{/literal}


<div class="mid">

	{* Modal login popup HTML *}
    <div class="modal-popup" id="popup-login" style="display:none">
        <div class="modal-popup-overlay" onclick="popup_close();"></div>
        <div class="modal-popup-inner">
        	<div class="modal-popup-close" onclick="popup_close();"><img src="{$smarty.const._IMAGES_URL_}popup_close.png" /></div>
            <h2>Login</h2>
            <div id="login-response" style="font-family:'Century Gothic',CenturyGothic,AppleGothic,sans-serif; color:red; text-align:center;"></div>
            <fieldset>
            	<div class="popup-fields">
                	<div class="popup-field-label">
                    User
                    </div>
                	<div class="popup-field">
                    <input type="text" name="user" id="user" />
                    </div>
                </div>
                <div class="popup-fields">
                	<div class="popup-field-label">
                    Password
                    </div>
                	<div class="popup-field">
                    <input type="password" name="password" id="password" />
                    </div>
                </div>
                <div class="popup-fields">
                	<div class="button-primary" onclick="sample_login();">
                    	Login
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    
    {* Modal popup trigger *}
	<a href="javascript:void(0)" onclick="popup_open('#popup-login');">Open a modal FORM popup</a>
    
    
    
    
</div>