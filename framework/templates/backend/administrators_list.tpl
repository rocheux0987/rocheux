	{* Search *}
    {include file="`$smarty.const._TPL_BACKEND_DIR_`administrators_search.tpl"}
    
    <div class="row-fluid">            
   <div class="box span12">
      <div class="box-header" data-original-title>
         <h2><i class="halflings-icon white user"></i><span class="break"></span>{$site_module}</h2>
      </div>
      <div class="box-content">
        {* Paginator *}
        {capture name="pager"}
			{include file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/pager.tpl" paginator=$administrators_list}
		{/capture}
        {$smarty.capture.pager}
        
        
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Last name</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Status</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
            	{foreach name="results" from=$administrators_list.data item=row}
               <tr>
                  <td>{$row.name}</td>
                  <td class="center">{$row.lastname}</td>
                  <td class="center">{$row.email}</td>
                  <td class="center">{$row.username}</td>
                  <td class="center">
                  	{if $row.status eq 1}
                     <span class="label label-success">Active</span>
                    {else}
                    <span class="label label-important">Inactive</span>
                    {/if}
                  </td>
                  <td>
                    <a class="btn btn-info" href="{$url_edit}&id={$row.id}" title="Edit">
                        <i class="halflings-icon white edit"></i>  
                    </a>
                    <a class="btn btn-danger" href="{$url_delete}&id={$row.id}" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                        <i class="halflings-icon white trash"></i> 
                    </a>
                   </td>
               </tr>
               {foreachelse}
               <tr>
               		<td colspan="6" style="text-align:center">No results found</td>
               </tr>
               {/foreach}
            </tbody>
         </table>
         
         {* Paginator *}
         {$smarty.capture.pager}
        
      </div>
   </div>
   <!--/span-->
</div>