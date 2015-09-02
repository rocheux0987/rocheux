{* JS Validations *}
{literal}
<script type="text/javascript">
function validate_form(){
	var errors = false;
	var act = "{/literal}{$act}{literal}";
	//Name
	if ($("#name").val() == ""){
		$("#name").addClass("field-error");
		$("#name").focus();
		return false;
	}else{
		$("#name").removeClass("field-error");
	}
	
	//Lastname
	if ($("#lastname").val() == ""){
		$("#lastname").addClass("field-error");
		$("#lastname").focus();
		return false;
	}else{
		$("#lastname").removeClass("field-error");
	}
	
	//Username
	if ($("#username").val() == ""){
		$("#username").addClass("field-error");
		$("#username").focus();
		return false;
	}else{
		$("#username").removeClass("field-error");
	}
	
	//Email
	if ($("#email").val().indexOf("@") == "-1" || $("#email").val().indexOf(".") == "-1"){
		$("#email").addClass("field-error");
		$("#email").focus();
		return false;
	}else{
		$("#email").removeClass("field-error");
	}
	
	if (act == "add"){
		//Method: ADD
		
		//Password
		if ($("#password").val() == ""){
			$("#password").addClass("field-error");
			$("#password").focus();
			return false;
		}else{
			$("#password").removeClass("field-error");
		}
		
		//Password confirm
		if ($("#confirm_password").val() == "" || $("#confirm_password").val() != $("#password").val()){
			$("#confirm_password").addClass("field-error");
			$("#confirm_password").focus();
			return false;
		}else{
			$("#confirm_password").removeClass("field-error");
		}
	}else{
		//Method: EDIT
		
		if ($("#password").val() != "" && $("#confirm_password").val() != ""){
			//Password confirm
			if ($("#confirm_password").val() == "" || $("#confirm_password").val() != $("#password").val()){
				$("#confirm_password").addClass("field-error");
				$("#confirm_password").focus();
				return false;
			}else{
				$("#confirm_password").removeClass("field-error");
			}
		}
	}
	
	
	//Modules
	if ($(".administrator-modules input[type=checkbox]:checked").length == 0){
		$(".administrator-modules").addClass("field-error");
		return false;
	}else{
		$(".administrator-modules").removeClass("field-error");
	}
	
	$(".form-actions").html("<img src='img/loading.gif' />");
	
	if (act == "add"){
		action = "?act=save&";
	}else{
		action = "?act=edit&id={/literal}{$data.id}{literal}";
	}
	
	setTimeout(function(){ 
		$("#form-primary").attr({"action" : action});
		document.getElementById("form-primary").action = action;
		$("#form-primary").submit();
	}, 1000);
	
}
</script>
{/literal}

{* Header title *}
{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/titles.tpl" type=1 title="`$site_module` / `$site_action`"}

<div class="row-fluid sortable">
   <div class="box span12">
      <div class="box-header" data-original-title>
         <h2><i class="halflings-icon white edit"></i><span class="break"></span>General information</h2>
      </div>
      <div class="box-content">
         <form class="form-horizontal" id="form-primary" method="POST">
            <fieldset>
               <div class="control-group">
                  <label class="control-label" for="name">Name </label>
                  <div class="controls">
                     <input type="text" class="span6" id="name" name="form_data[name]" value="{$data.name}">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Lastname </label>
                  <div class="controls">
                     <input type="text" class="span6" id="lastname" name="form_data[lastname]" value="{$data.lastname}">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Username </label>
                  <div class="controls">
                     <input type="text" class="span6" id="username" name="form_data[username]" value="{$data.username}">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Email </label>
                  <div class="controls">
                     <input type="text" class="span6" id="email" name="form_data[email]" value="{$data.email}">
                  </div>
               </div>
               {if $act == "edit"}
               <div class="control-group">
                  <label class="control-label" for="name">Password reset</label>
                  <div class="controls">
                     <input type="password" class="span6" id="password" name="form_data[password]">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Confirm new password </label>
                  <div class="controls">
                     <input type="password" class="span6" id="confirm_password" name="form_data[confirm_password]">
                  </div>
               </div>
               {else}
               <div class="control-group">
                  <label class="control-label" for="name">Password</label>
                  <div class="controls">
                     <input type="password" class="span6" id="password" name="form_data[password]">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Confirm password </label>
                  <div class="controls">
                     <input type="password" class="span6" id="confirm_password" name="form_data[confirm_password]">
                  </div>
               </div>
               {/if}
               <div class="control-group">
                  <label class="control-label">Modules</label>
                  <div class="controls">
                  	 {foreach name="user_modules" from=$user_modules item=user_module}
                     <label class="checkbox inline administrator-modules">
                     	<input type="checkbox" name="form_data[modules][{$user_module.id}]" {foreach name="edit_modules" from=$data.modules item=module}{if $module eq $user_module.id}checked="checked"{/if}{/foreach} value="1"> {$user_module.name}
                     </label>
                     {/foreach}
                  </div>
               </div>
               
                <div class="control-group">
                    <label class="control-label" for="selectError3">Status </label>
                    <div class="controls">
                        <select id="status" name="form_data[status]">
                        <option value="1" {if $data.status eq 1} selected="selected"{/if}>Active</option>
                        <option value="0" {if $data.status eq 0} selected="selected"{/if}>Inactive</option>
                        </select>
                    </div>
                </div>
               
               <div class="form-actions">
               {if $act eq "edit"}
                   {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Update" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               {else}
                   {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               {/if}	
                   
           	   {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
               
            </fieldset>
         </form>
      </div>
   </div>
   <!--/span-->
</div>