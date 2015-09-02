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
{include file="`$smarty.const._TPL_BACKEND_DIR_`seorules_search.tpl"}

{* Save button *}
<div style="margin-bottom:25px; float: left;" class="form-buttons">
{include btn_onclick="save();" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
</div>

{* Add button *}
<div style="margin-bottom:25px; float: right;">
{include btn_onclick="document.location.href='`$url_add`';" btn_size="btn-success" btn_type="btn-primary" btn_title="Add SEO Rule" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
</div>

<div class="row-fluid">            
   <div class="box span12">
      <div class="box-header" data-original-title>
         <h2><i class="halflings-icon white user"></i><span class="break"></span>{$site_module}</h2>
      </div>
      <div class="box-content">        
      	{* Paginator *}
        {capture name="pager"}
			{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/pager.tpl" paginator=$seorules_list}
		{/capture}
        {$smarty.capture.pager}
        <div style="clear:both"></div>
        <form action="?act=edit" class="form-horizontal" id="form-primary" method="POST">
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th width="25%">Script name</th>
                  <th width="65%">SEO Name</th>
                  <th width="10%">Action</th>
               </tr>
            </thead>
            <tbody>
            	{foreach name="results" from=$seorules_list.data item=row}
               <tr>
                  <td>{$row.name}</td>
                  <td class="center"><input type="text" class="languages-textarea" name="seo_rules[{$row.name}]" id="seo_rules_{$row.name}" value="{$row.value}" /></td>
                  <td>
                  	<a class="btn btn-info" href="{$url_edit}&name={$row.name}">
                    	<i class="halflings-icon white edit"></i>
                    </a>
                    <a class="btn btn-danger" href="{$url_delete}&name={$row.name}" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                        <i class="halflings-icon white trash"></i> 
                    </a>
                   </td>
               </tr>
               {foreachelse}
               <tr>
               		<td colspan="3" style="text-align:center">No results found</td>
               </tr>
               {/foreach}
            </tbody>
         </table>
         <input type="hidden" name="lang_code" id="lang_code" value="{$smarty.const._CLIENT_LANGUAGE_}" />
         </form>
         {* Paginator *}
         {$smarty.capture.pager}
      </div>
   </div>
   <!--/span-->
</div>

<div class="form-buttons">
{include btn_onclick="save();" btn_size="btn-medium" btn_type="btn-primary" btn_title="Save" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
</div>