{* JS Validations *}
{literal}
<script type="text/javascript">
$(document).ready(function(){

  $('.select_btn').attr('data-attr' , 'checked');
  var checkboxes = $('.select_all');

  $(document).on('click' , '.select_btn' , function(){
   
   var data = $(this).attr('data-attr');

   if(data == 'checked'){
      $(this).attr('data-attr' , 'unchecked');
      $(this).text('Unselect');
      checkboxes.each(function(){
        $(this).parent().addClass('checked');
        $(this).prop('checked' , true);
      });
   }else{
      $(this).attr('data-attr' , 'checked');
      $(this).text('Select all');
      checkboxes.each(function(){
        $(this).parent().removeClass('checked');
        $(this).prop('checked' , false);
      });
   }
  });


  $(document).on('change' , '#seo_helper' , function(){
    var link = $(this).val();
    $('#link').val(link);
  });


});

function validate_form(){

	$(".image_name").each(function() {
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
	
	action = "?act=edit_image&";
	
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
         <form class="form-horizontal" id="form-primary" method="POST" enctype="multipart/form-data">
            <div style="float:right;">
              {include btn_class="select_btn" btn_size="btn-medium" btn_type="btn-primary" btn_title="Select all" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"} 
            </div>
          <br clear="all">
          <br clear="all">
            <fieldset>
            {foreach name="supported_languages" from=$supported_languages item=row}
            {include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/titles.tpl" type=3 title="<img src='img/flags/`$row.icon`'> `$row.name`"}
               <div class="control-group">
                  <label class="control-label" for="name">Image </label>
                  <div class="controls">
                     <input type="text" class="span6 image_name" id="name" name="form_data[{$row.lang_code}][value]" value="{$slider_lang[$row.lang_code].image}" disabled>
                     <input type="checkbox" class="select_all" name="form_data[{$row.lang_code}][check]" >
                  </div>
               </div>
              {/foreach}
              {include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/titles.tpl" type=3 title="General"}
               <div class="control-group">
                  <label class="control-label" for="name">Position </label>
                  <div class="controls">
                     <input type="text" class="span6" id="position" name="extra_form_data[position]" value="{$slider_type.position}">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Link </label>
                  <div class="controls">
                     <input type="text" class="span6" id="link" name="extra_form_data[link]" value="{$slider_type.link}">
                     <select id="seo_helper">
                      {foreach name="seo_links" from=$seo_link item=row}
                        <option value="{$row.name}">{$row.name}</option>
                      {/foreach}
                     </select>
                  </div>
               </div>
               <div class="control-group">
                    <label class="control-label" for="selectError3">Status </label>
                    <div class="controls">
                        <select id="status" name="extra_form_data[status]">
                        <option value="A" {if $slider_type.status eq 'A'} selected="selected"{/if}>Active</option>
                        <option value="D" {if $slider_type.status eq 'D'} selected="selected"{/if}>Disabled</option>
                        <option value="H" {if $slider_type.status eq 'H'} selected="selected"{/if}>Hidden</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="name">Image File </label>
                  <div class="controls">
                     <input type="file" name="photo" accept="image/*">
                  </div>
               </div>
               <div class="form-actions">
               {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"} 
           	   {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
               
            </fieldset>
            <input type="hidden" name="extra_form_data[slide_id]" id="lang_code" value="{$slider_type.slide_id}" />
         </form>
      </div>
   </div>
   <!--/span-->
</div>