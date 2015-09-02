{literal}
<script type="text/javascript">
function filter_search(){
	if (document.getElementById("email").value == "" && document.getElementById("username").value == "" && document.getElementById("status").value == ""){
		notification("At least one field must be filled to continue", "error", true);
		return false;	
	}
	document.location.href = "?act=list&" + "&email="+document.getElementById("email").value+"&username="+document.getElementById("username").value+"&status="+document.getElementById("status").value;
}

function filter_reset(){
	document.location.href = "?act=list&";
}
$(document).ready(function() {
	$(".enter-trigger-search").on("keydown", function(e) {
		if (e.which == 13) {
			filter_search();
		}
	});
});
</script>
{/literal}
<div class="row-fluid">
	<div class="box span12">
        <div class="box-header" data-original-title>
            <h2><i class="halflings-icon white search"></i><span class="break"></span>Search tools</h2>
            <div class="box-icon">
                <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
            </div>
        </div>
        <div class="box-content" {if $smarty.get.email eq '' && $smarty.get.username eq '' && $smarty.get.status eq ''}style="display:none"{/if}>
            <table class="table table-striped table-bordered">
              <thead>
                  <tr>
                      <th width="25%"><label>Email<input class="field-search enter-trigger-search" type="text" id="email" name="email" value="{$smarty.get.email}" /></label> </th>
                      <th width="25%"><label>Username<input class="field-search enter-trigger-search" type="text" id="username" name="username" value="{$smarty.get.username}" /></label></th>
                      <th width="25%">
                          <label>Status
                              <select class="field-search enter-trigger-search" id="status" name="status">
                                    <option value="">N/A</option>
                                    <option value="Y" {if $smarty.get.status eq "Y"} selected="selected"{/if}>Active</option>
                                    <option value="N" {if $smarty.get.status eq "N"} selected="selected"{/if}>Inactive</option>
                              </select>
                          </label>
                      </th>
                      <th width="25%"></th>
                  </tr>
              </thead>  
              <thead>
                  <tr>
                      <th colspan="4">
                        <div class="form-actions">
                            {include btn_onclick="filter_search()" btn_size="btn-medium" btn_type="btn-primary" btn_title="Search" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
                            {include btn_onclick="filter_reset()" btn_size="btn-medium" btn_type="btn-danger" btn_title="Reset" file="`$smarty.const._TPL_BACKEND_DIR_`common_templates/buttons.tpl"}
                       </div>
                      </th>
                  </tr>
              </thead> 
          </table>            
        </div>
    </div><!--/span-->
</div>