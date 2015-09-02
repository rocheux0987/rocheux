{if $paginator.total_pages > 1}
{assign var="show_pages_separator" value=true}
{assign var="show_last_pages" value=2}
<div class="pagination">
   <ul>
      {if $paginator.pager.back_url ne ''}<li><a href="{$paginator.pager.back_url}">Prev</a></li>{/if}
      {if $paginator.page > 1}<li><a href="{$paginator.pager.first_url}">First</a></li>{/if}
      {foreach name="pages" from=$paginator.pager.pages item=page}
        {if $smarty.foreach.pages.total > 8}
            {*if $smarty.foreach.pages.iteration <8*}
            {if $page.page >= $paginator.page && $page.page<($paginator.page+8)}
                <li {if $page.selected}class="active"{/if}><a href="{$page.url}">{$page.page}</a></li>
            {else}
                {if $page.page>($paginator.page+8)}
                    {if $show_pages_separator}
                    <li {if $page.selected}class="active"{/if}><a href="{$page.url}">...</a></li>
                    {assign var="show_pages_separator" value=false}
                    {/if}
                {/if}
                {if $smarty.foreach.pages.iteration > ($smarty.foreach.pages.total-$show_last_pages)}
                    <li {if $page.selected}class="active"{/if}><a href="{$page.url}">{$page.page}</a></li>
                {/if}
            {/if}
        {else}
            <li {if $page.selected}class="active"{/if}><a href="{$page.url}">{$page.page}</a></li>
        {/if}
      {/foreach}
      {if $paginator.pager.next_url ne ''}<li><a href="{$paginator.pager.next_url}">Next</a></li>{/if}
   </ul>
</div>
{/if}