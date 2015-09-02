{if $breadcrumb}
<ul class="breadcrumb">
    {foreach name="breadcrumb_list" from=$breadcrumb item=option}
    <li>
        <!--i class="icon-home"></i!-->
        <a href="{$option.url}">{$option.title}</a>
        {if $smarty.foreach.breadcrumb_list.last eq false}<i class="icon-angle-right"></i>{/if}
    </li>
    {/foreach}
</ul>
{/if}