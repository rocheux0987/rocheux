<div class="row-fluid">
	<div class="box span12">
        <div class="box-header" data-original-title>
            <h2><i class="halflings-icon white search"></i><span class="break"></span>Search tools</h2>
            <div class="box-icon">
                <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
            </div>
        </div>
        
        <div class="box-content" {if $smarty.get.name eq ''}style="display:none"{/if}>
            <table class="table table-striped table-bordered">
              <thead>
                  <tr>
                      <th width="25%" ><label>Slider name<input class="field-search enter-trigger-search" type="text" id="name" name="name" value="{$smarty.get.name}" /></label> </th>
                      <th width="25%"></th>
                      <th width="25%"></th>
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