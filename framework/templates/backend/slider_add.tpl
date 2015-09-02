{literal}
<script type="text/javascript">

$(document).ready(function(){

  $(document).on('change' , '#seo_helper' , function(){
    var link = $(this).val();
    $('#form_link').val(link);
  });
  
});
function validate_form(){

  var errors = true;


  //Name
  if ($("#form_name").val() == ""){
    $("#form_name").addClass("field-error");
    $("#form_name").focus();
  
    return false;
  }else{
    $("#form_name").removeClass("field-error");
  }

  //link
  if ($("#form_link").val() == ""){
    $("#form_link").addClass("field-error");
    $("#form_link").focus();
    return false;
  }else{
    $("#form_link").removeClass("field-error");
  }

  //position
  if ($("#form_position").val() == ""){
    $("#form_position").addClass("field-error");
    $("#form_position").focus();
    return false;
  }else{
    $("#form_position").removeClass("field-error");
  }

  if($("#form_photo").val() != ''){
    errors = false;
  }else{
    alert('please select a file');
    errors = true;
  }

  if(!errors){
    $(".form-buttons").html("<img src='img/loading.gif' />");
    setTimeout(function(){ 
      $( "#form-primary" ).trigger("submit");
    }, 1000); 
  }

}


</script>
{/literal}


<div class="row-fluid sortable">
   <div class="box span12">
      <div class="box-header" data-original-title>
         <h2><i class="halflings-icon white edit"></i><span class="break"></span>General information</h2>
      </div>
      <div class="box-content">
         <form id="form-primary" class="form-horizontal" action="?act=save&" method="POST" enctype="multipart/form-data">
            <fieldset>
               <div class="control-group">
                  <label class="control-label" for="name">Name </label>
                  <div class="controls">
                     <input type="text" class="span6" id="form_name" name="name">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Link </label>
                  <div class="controls">
                     <input type="text" class="span6" id="form_link" name="link">
                     <select id="seo_helper">
                      {foreach name="seo_links" from=$seo_link item=row}
                        <option value="{$row.name}">{$row.name}</option>
                      {/foreach}
                     </select>
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Position </label>
                  <div class="controls">
                     <input type="text" class="span6" id="form_position" name="position">
                  </div>
               </div>
               <div class="control-group">
                  <label class="control-label" for="name">Image name</label>
                  <div class="controls">
                     <input type="file" id="form_photo" name="photo" accept="image/*">
                  </div>
               </div>
               <div class="control-group">
                    <label class="control-label" for="selectError3">Status </label>
                    <div class="controls">
                        <select id="status" name="status">
                        <option value="A">Active</option>
                        <option value="D">Disabled</option>
                        <option value="H">Hidden</option>
                        </select>
                    </div>
                </div>          
               <div class="form-actions form-buttons">
                {include btn_onclick="validate_form()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
                {include btn_onclick="document.location.href='`$cancel_url`';" btn_size="btn-medium" btn_type="btn-danger" btn_title="Cancel" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
               </div>
            </fieldset>
         </form>
      </div>
   </div>
   <!--/span-->
</div>
