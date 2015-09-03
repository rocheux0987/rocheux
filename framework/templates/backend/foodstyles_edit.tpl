{* JS Validations *}
{literal}
<script type="text/javascript">
function validate_form(){

	//Pet types
	$(".foodstyles").each(function() {
		incomplete = 0;
		
		if ($(this).val() == ""){
			$(this).addClass("field-error");
			incomplete++;
		}else{
			$(this).removeClass("field-error")
		}
	});
	
	if (incomplete > 0){
		return false;	
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
                  <label class="control-label" for="name">Food style </label>
                  <div class="controls">
                     <input type="text" class="span6 foodstyles" id="name" name="form_data[{$row.lang_code}][value]" value="{$foodstyle_lang[$row.lang_code].value}">
                  </div>
               </div>
               {/foreach}
               {include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/titles.tpl" type=3 title="General"}
                <div class="control-group">
                  <label class="control-label" for="name">Position </label>
                  <div class="controls">
                     <input type="text" class="span6" id="position" name="extra_form_data[position]" value="{$foodstyle_data.position}">
                  </div>
               </div>
               <div class="control-group">
                    <label class="control-label" for="selectError3">Status </label>
                    <div class="controls">
                        <select id="status" name="extra_form_data[status]">
                        <option value="A" {if $foodstyle_data.status eq 'A'} selected="selected"{/if}>Active</option>
                        <option value="D" {if $foodstyle_data.status eq 'D'} selected="selected"{/if}>Disabled</option>
                        <option value="H" {if $foodstyle_data.status eq 'H'} selected="selected"{/if}>Hidden</option>
                        </select>
                    </div>
                </div>
               <div class="form-actions">
               {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"} 
           	   {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
               
            </fieldset>
            <input type="hidden" name="extra_form_data[food_style_id]" id="lang_code" value="{$smarty.get.food_style_id}" />
         </form>
      </div>
   </div>
   <!--/span-->
</div>