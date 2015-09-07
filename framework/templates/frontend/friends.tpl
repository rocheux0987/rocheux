{literal}
<script type="text/javascript">
    $(document).on('click', '#send_msg', function() {
        var h = $(document).height();
        $('#mask').css('height',h);
        $('#mask').show();
        $('#message-modal').fadeIn('fast');
        $('#message').focus();
    });

    $(document).on('click', '#button-closewin', function() {
        $('#message-modal').fadeOut('fast');
        $('#mask').hide();
    });

    $(document).on('click', '#button-sendmsg', function() {
        var message = $('#message').val();
        var pid = {/literal}{$profile.pet_id}{literal};
        alert(pid);
        return false;
        var info = 'pid=' + pid + '&message=' + encodeURIComponent(message);
        $.ajax({
            url: '/messages.php',
            type: 'POST',
            data: info,
            success: function(response) {
                $('#message').val('');
                $('#message-modal').fadeOut('fast');
                $('#mask').hide();
                alert(response);
            }
        });
    });

    $(document).on('click', '#mask', function() {
        $('.modal').fadeOut('fast');
        $('#mask').hide();
    });

    $(document).on('click', '#add-friend', function() {
        var e = $(this);
        //var obj = {'pid2': e.data('pet'), 'act':e.data('act')};
        //var info = JSON.stringify(obj);
        var info = 'pid2=' + e.data('pet') + '&act=' + e.data('act');
        $.ajax({
            url: '/functions.php',
            type: 'POST',
            data: info,
            success: function(response) {
                var j = $.parseJSON(response);
                alert(j.msg);
                if(j.code==1) {
                    location.reload();
                }
            }
        });
    });
</script>
{/literal}
<div class="mid">
    <div id="message-modal" class="modal">
        <textarea name="message" id="message"></textarea>
        <div class="buttons">
            <button id="button-sendmsg" class="button-primary">Send Message</button>&nbsp;&nbsp;
            <button id="button-closewin" class="button-primary">Close Window</button>
        </div>
    </div>

    <div class="user-details text-center">
        <a href="{"profile"|seo_url}/{$profile.pet_id}"><h3>{$profile.name} {$profile.lastname}</h3></a>
        <div class="prof-btns">
            {if $rel_code != 0}
                <a data-act="add_friend" data-pet="{$profile.pet_id}" route="{"profile"|seo_url}/{$profile.pet_id}/request" class="add-friend" id="add-friend" href="javascript:;">
                    {if $rel_code == 1}<i class="fa fa-user-plus"></i>{/if}
                    <span id="friendStatusText">{$rel_msg}</span>
                </a>
                <a class="pb" id="send_msg" href="javascript:;"><i class="fa fa-envelope-o"></i> Send a message</a>
            {/if}
            <a class="pb" href="{"profile"|seo_url}/{$profile.pet_id}/about"><i class="fa fa-user"></i> About</a>
            <a class="pb" href="{"profile"|seo_url}/{$profile.pet_id}/friends"><i class="fa fa-paw"></i> Friends</a>
            <a class="pb" href="{"profile"|seo_url}/{$profile.pet_id}/gallery"><i class="fa fa-picture-o"></i> Gallery</a>
        </div>
        <div class="row">
            <ul class="friends">
                {foreach from="$friends" item="friend"}
                    <li><div><a href="{"profile"|seo_url}/{$friend.pet_id2}"><img src="{$friend.thumb}" /></a></div><a href="{"profile"|seo_url}/{$friend.pet_id2}" class="friend-name">{$friend.name}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>