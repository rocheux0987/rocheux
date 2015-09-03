{literal}
<script type="text/javascript">
function save(){
	$(".form-buttons").html("<img src='img/loading.gif' />");
	setTimeout(function(){ 
		$("#form-primary").submit();
	}, 1000);	
}
</script>
{/literal}


{* Search *}
{include file="`$smarty.const._TPL_BACKEND_DIR_`foods_search.tpl"}

{* Save button *}
<div style="margin-bottom:25px; float: left;" class="form-buttons">
{include btn_onclick="save();" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
</div>

{* Add button *}
<div style="margin-bottom:25px; float: right;">
{include btn_onclick="document.location.href='`$url_add`';" btn_size="btn-success" btn_type="btn-primary" btn_title="Add Menu" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
</div>

<div class="row-fluid">            
   <div class="box span12">
      <div class="box-header" data-original-title>
         <h2><i class="halflings-icon white user"></i><span class="break"></span>{$site_module}</h2>
      </div>
      <div class="box-content">        

        <div style="clear:both"></div>
        <form action="?act=edit" class="form-horizontal" id="form-primary" method="POST">
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th width="35%">Description</th>
                  <th width="35%">Url</th>
                  <th width="30%">Actions</th>
               </tr>
            </thead>
            <tbody>
              <tr>
            	{foreach name="results" from=$menu_list item=row key=key}
               <tr>
               	  <td><input type="text" class="languages-textarea" name="menu[[{$key}][description]]" id="menu_description_{$key}" value="{$row.description}" /></td>
                  <td><input type="text" class="languages-textarea" name="menu[[{$key}][url]]" id="menu_url_{$key}" value="{$row.url}" /></td>
                  <td>
                    <a class="btn btn-danger" href="{$url_delete}&menu_id={$key}" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                        <i class="halflings-icon white trash"></i> 
                    </a>
                   </td>
               </tr>
               {foreachelse}
               <tr>
               		<td colspan="5" style="text-align:center">No results found</td>
               </tr>
              {/foreach}
            </tbody>
         </table>
         <input type="hidden" name="lang_code" id="lang_code" value="{$smarty.const._CLIENT_LANGUAGE_}" />
         </form>

      </div>
   </div>
   <!--/span-->
</div>

<div class="form-buttons">
{include btn_onclick="save();" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
</div>