{* btn_size = btn-large | btn-medium | btn-small | btn-mini *}
{* btn_type = btn-primary | btn-danger | btn-warning | btn-success | btn-info |	btn-inverse *}
{* btn_class = classnames separated by 1 space *}
{* btn_id = element id *}

{if $btn_size eq ''}
	{assign var="btn_size" value="btn-medium"}
{/if}

{if $btn_type eq ''}
	{assign var="btn_type" value="btn-primary"}
{/if}

<button type="button" class="btn {$btn_size} {$btn_type} {$btn_class}" onclick="{$btn_onclick}" {if $btn_id}id="{$btn_id}"{/if}>{$btn_title}</button>