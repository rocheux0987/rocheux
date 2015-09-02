<html lang="en">
    <head>
        {include file="`$smarty.const._TPL_FRONTEND_DIR_`common_templates/meta.tpl"}
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>{$section_title}</title>
        <link rel="shortcut icon" href="{$smarty.const._IMAGES_URL_}favicon.png">
        {style src="style.css"}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
        {script src="jquery.js"}
        {script src="common.js"}
        {script src="locationpicker.jquery.min.js"}
    </head>
<body>
<!-- TOP -->
{include file="`$smarty.const._TPL_FRONTEND_DIR_`top.tpl"}
<!-- END TOP -->

<!-- SLIDER -->
{if $section_template == 'home.tpl' && !isset($user_data) }
    {include file="`$smarty.const._TPL_FRONTEND_DIR_`slider.tpl"}
{/if}
<!-- END SLIDER -->

<!-- MAIN -->
<div class="main-content">
    <!-- LEFT NAV -->
    {if $reg eq false}
        {include file="`$smarty.const._TPL_FRONTEND_DIR_`column_left.tpl"}
    {/if}
    <!-- END LEFT NAV -->
    
    <!-- MID (ARTICLE) -->
    {include file="`$smarty.const._TPL_FRONTEND_DIR_``$section_template`"}
    <!-- END MID (ARTICLE) -->
    
    <!-- RIGHT NAV -->
    {if $reg eq false}
        {include file="`$smarty.const._TPL_FRONTEND_DIR_`column_right.tpl"}
    {/if}
    <!-- END RIGHT NAV -->
    <br clear="all"/>
</div>
<!-- END MAIN CONTENT -->
<!-- FOOTER -->
{include file="`$smarty.const._TPL_FRONTEND_DIR_`footer.tpl"}
<!-- END FOOTER -->
</body>
</html>