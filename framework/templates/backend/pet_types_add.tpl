{* JS Validations *}
{literal}
<script type="text/javascript">
function validate_form(){

	//Name
	if ($("#name").val() == ""){
		$("#name").addClass("field-error");
		$("#name").focus();
		return false;
	}else{
		$("#name").removeClass("field-error");
	}
	
	//Name
	if ($("#position").val() == "" || isNaN($("#position").val())){
		$("#position").addClass("field-error");
		$("#position").focus();
		return false;
	}else{
		$("#position").removeClass("field-error");
	}
	
	$(".form-actions").html("<img src='img/loading.gif' />");
	
	action = "?act=add&";
	
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
                  <label class="control-label" for="name">Pet type </label>
                  <div class="controls">
                     <input type="text" class="span6" id="name" name="form_data[value]">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Position </label>
                  <div class="controls">
                     <input type="text" class="span6" id="position" name="form_data[position]">
                  </div>
               </div>
               <div class="control-group">
                    <label class="control-label" for="selectError3">Status </label>
                    <div class="controls">
                        <select id="status" name="form_data[status]">
                        <option value="A" {if $data.status eq 'A'} selected="selected"{/if}>Active</option>
                        <option value="D" {if $data.status eq 'D'} selected="selected"{/if}>Disabled</option>
                        <option value="H" {if $data.status eq 'H'} selected="selected"{/if}>Hidden</option>
                        </select>
                    </div>
                </div>
               <div class="form-actions">
               {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"} 
           	   {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
               
            </fieldset>
            <input type="hidden" name="lang_code" id="lang_code" value="{$smarty.const._CLIENT_LANGUAGE_}" />
         </form>
      </div>
   </div>
   <!--/span-->
</div>