{include file="`$smarty.const._TPL_BACKEND_DIR_`administrators_search.tpl"}
<div class="row-fluid">            
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Details</h2>
		</div>
		<div class="box-content">
			<form method="post" name="frm_main" action="#">
				<input type="hidden" name="action" value="add_template">
				<input type="hidden" name="l10n" value="{$current_l10n}">
				<div class="span12 hidden-phone hidden-tablet hidden-desktop"></div>
				<div class="span12">
					<label class="span3" for="tp_status">Status:</label>
					<select name="tp_status" class="span2">
						<option value="A" {if $template.main.status eq A} selected {/if}>Active</option>
						<option value="I" {if $template.main.status eq I} selected {/if}>Inactive</option>
						<option value="H" {if $template.main.status eq H} selected {/if}>Hidden</option>
					</select>
				</div>
				<div class="span12">
					<label class="span3" for="tp_desc">Description:</label>
					<input class="span6" name="tp_desc" type="text" value="{$template.main.description}">
				</div>
				<div class="span12">
					<label class="span3" for="tp_l10n_subj">Subject:</label>
					<input class="span6" name="tp_l10n_subj" value="{$l10n_vars.subject}">
				</div>
				<div class="span12">
					<div class="span3">
						<label for="tp_l10n_html">Body</label>
						<br/><br/>
						<p>Note: each variable should be enclosed in curly braces. Eg: {literal}{name}{/literal}</p>
					</div>					
					<div class="span6" style="margin-left:0px;">
						<textarea id="tp_l10n_html" name="tp_l10n_html" rows="24">
							{$l10n_vars.body_html}
						</textarea>
					</div>	
				</div>
				<div class="span12" style="margin-top: 15px;">
					<div class="span3">
						<label for="tp_l10n_html">Body (Text)</label>
					</div>
					<div class="span6" style="margin-left:0px;">
						<textarea id="tp_l10n_txt" name="tp_l10n_txt" row="24" style="width:100%; min-height:150px;"></textarea>
					</div>
				</div>
				<p>Note: This template will be saved under {$language} l10n</p>
				<input class="btn btn-medium btn-primary" type="submit" value="Save">
				<a href="{$cancel_link}" class="btn btn-medium btn-danger">Cancel</a>
			</form>
		</div>
	</div>
</div>
<script src="js/tinymce/tinymce.min.js"></script>
{literal}
<script>
tinymce.init({
	selector: "#tp_l10n_html",
	width: 650,
	plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern imagetools"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
});
</script>
{/literal}