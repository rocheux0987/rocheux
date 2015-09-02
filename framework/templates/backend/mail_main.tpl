{include file="`$smarty.const._TPL_BACKEND_DIR_`administrators_search.tpl"}
<div class="row-fluid">
	<a class="btn btn-info" href="{$add_link}" title="Add New E-mail Template" style="margin-bottom: 25px;">
		<i class="halflings-icon white file"></i>  Add New E-mail Template
	</a>
</div>
<div class="row-fluid">            
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>{$site_module}</h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<td>ID</td>
						<td>Subject</td>
						<td>Status</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
				{foreach from=$templates item=templates}
					<tr>						
						<td class="center">{$templates.email_id}</td>
						<td class="center">{$templates.text.subject}</td>
						<td class="center">
						{if $templates.status eq A}
							<span class="label label-success">Active</span>
						{else}
							<span class="label label-important">Inactive</span>
						{/if}
						</td>
						<td>
							<a class="btn btn-info" href="{$edt_link}{$templates.email_id}" title="Edit">
								<i class="halflings-icon white edit"></i>  
							</a>
							<a class="btn btn-danger" href="{$del_link}{$templates.email_id}" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
								<i class="halflings-icon white trash"></i> 
							</a>
						</td>
					</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
	</div>
</div>
