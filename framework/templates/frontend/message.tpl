<ul id="message-thread">
{foreach from="$message" item="data"}
    <li data-message-id="{$data.message_id}">
        <div class="message-check"><input type="checkbox" data-message-id="{$data.message_id}" value="{$data.message_id}" /></div>
        <div class="message-thumb"><a href="{"profile.php"|seo_url}/{$data.sender_id}"><img src="{$data.thumb}" alt="{$data.name} {$data.lastname}" /></a></div>
        <div class="message-body">
            <a href="{"profile.php"|seo_url}/{$data.sender_id}">{$data.name} {$data.lastname}</a>
            <div class="message-date">{$data.date|date_format:"%B %e, %Y"}, <span class="message-time">{$data.date|date_format:"%H:%M:%S%p"}</span></div>
            <p>{$data.message}</p>
        </div>
    </li>
{/foreach}
</ul>