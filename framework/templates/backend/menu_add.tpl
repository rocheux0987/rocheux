{* JS Validations *}
{literal}
<script type="text/javascript">
function validate_form(){

	//Description
	if ($("#description").val() == ""){
		$("#description").addClass("field-error");
		$("#description").focus();
		return false;
	}else{
		$("#description").removeClass("field-error");
	}

    if ($("#url").val() == ""){
    $("#url").addClass("field-error");
    $("#url").focus();
    return false;
  }else{
    $("#url").removeClass("field-error");
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
                  <label class="control-label" for="description">Description</label>
                  <div class="controls">
                     <input type="text" class="span6" id="description" name="form_data[description]">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="url"> Url </label>
                  <div class="controls">
                     <input type="text" class="span6" id="url" name="form_data[url]" placeholder= "eg. <description>.php?act=<action>">
                  </div>
               </div>
               <div class="form-actions">
               {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"} 
           	   {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
               
            </fieldset>
         </form>
      </div>
   </div>
   <!--/span-->
</div>