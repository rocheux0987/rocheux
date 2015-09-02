{* JS Validations *}
{literal}
<script type="text/javascript">
function validate_form(){

	incomplete = 0;
	//Variable name
	$("input[type=text].field-required").each(function() {
		if ($(this).val() == ""){
			$(this).addClass("field-error");
			incomplete++;
		}else{
			$(this).removeClass("field-error")
		}
	});
	
	if (incomplete > 0){
		return;	
	}

	$(".form-actions").html("<img src='img/loading.gif' />");
	
	action = "?act=edit_langs&";
	
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
            {foreach name="supported_languages" from=$supported_languages item=row}
            {include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/titles.tpl" type=3 title="<img src='img/flags/`$row.icon`'> `$row.name`"}
               <div class="control-group">
                  <label class="control-label" for="name">Script name </label>
                  <div class="controls">
                     <input type="text" class="span6" id="name" name="form_data[{$row.lang_code}][name]" value="{$smarty.get.name}" readonly="readonly">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">SEO name </label>
                  <div class="controls">
                     <input type="text" class="span6 field-required" id="name" name="form_data[{$row.lang_code}][value]" value="{$seorules_data[$row.lang_code].value}">
                  </div>
               </div>
               {/foreach}
               <div class="form-actions">
               {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"} 
           	   {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
               
            </fieldset>
            <input type="hidden" name="extra_form_data[name]" id="variable_name" value="{$smarty.get.name}" />
         </form>
      </div>
   </div>
   <!--/span-->
</div>