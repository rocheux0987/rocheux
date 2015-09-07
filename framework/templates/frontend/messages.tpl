{literal}
<script type="text/javascript">
    $(document).on('click', '#button-sendmsg', function() {

    });

    $(document).on('click', '#message-list > li', function() {
        var e = $(this);
        var sender_id = e.data('sender-id');
        var info = 'sender_id=' + sender_id;
        $.ajax({
            url: '{/literal}{"messages.php"|seo_url}{literal}/?act=read_message',
            type: 'GET',
            data: info,
            success: function(response) {
                $('#message-content').html(response);
                $('#message-wrapper-inner').show();
                $('#message-input').focus();

                // update reply-to-id so the replies are sent to the appropriate user
                var z1 = $('#button-reply');
                z1.data("reply-to-id",sender_id);

                var z2 = $('#button-delete-thread');
                z2.data("sender-id",sender_id);
            }
        });
    });

    $(document).on('click', '#button-reply', function() {
        var e = $(this);
        var message = encodeURIComponent($('#message-input').val());
        var reply_to_id = e.data('reply-to-id');
        var info = 'recipient_id=' + reply_to_id + '&message=' + message;
        $.ajax({
            url: '{/literal}{"messages.php"|seo_url}{literal}/?act=send_message',
            type: 'GET',
            data: info,
            success: function(response) {
                var j = $.parseJSON(response);
                //alert(j.notify);
                $('#message-thread').append(j.message);
                $('#message-input').val('').focus();
            }
        });
    });

    $(document).on('click', '#button-delete-messages', function() {
        $('.message-check').show();
        $('#message-reply').hide();
        $('#message-options').show();
    });

    $(document).on('click', '#button-cancel', function() {
        $('.message-check').hide();
        $('#message-options').hide();
        $('#message-reply').show();
    });

    $(document).on('click', '#button-delete', function(i) {
        var cb = $('input[type=checkbox]:checked');
        var items = [];
        cb.each(function() {
            var i = $(this).val();
            items.push($(this).val());
            var e = $(this).parent().parent().data('message-id');
            $('#message-thread > li[data-message-id="' + e + '"').remove();
        });
        var info = 'items=' + items;
        $.ajax({
            url: '{/literal}{"messages.php"|seo_url}{literal}/?act=delete_messages',
            type: 'GET',
            data: info,
            success: function(response) {
                $('.message-check').hide();
                $('#message-options').hide();
                $('#message-reply').show();
            }
        });
    });

    $(document).on('click', '#button-delete-thread', function() {
        if(confirm("Are you sure you want to delete this entire conversation?"))
        {
            var e = $(this);
            var sender_id = e.data('sender-id');
            var info = 'sender_id=' + sender_id;
            $("message-list > li[data-sender-id=" + sender_id + "]").remove();
            $.ajax({
                url: '{/literal}{"messages.php"|seo_url}{literal}/?act=delete_thread',
                type: 'GET',
                data: info,
                success: function(response) {
                    alert(response);
                    $('#message-wrapper-inner').hide();
                    $("message-list > li[data-sender-id=" + sender_id + "]").remove();
                }
            });
        }
    });
</script>
{/literal}
<div class="mid">
    <h1>{$section_title}</h1>
    {if !empty($messages)}
    <div class="col-md-5">
        <ul id="message-list">
            {foreach from="$messages" item="message"}
                <li data-sender-id="{$message.sender_id}"><div class="sender-thumb"><a href="javascript:;"><img src="{$message.thumb}" alt="{$message.name} {$message.lastname}" /></a></div><h4><a href="javascript:;">{$message.name} {$message.lastname}</a></h4><p>{$message.message}</p></p></li>
            {/foreach}
        </ul>
    </div>
    <div class="col-md-7" id="message-wrapper">
        <div id="message-wrapper-inner">
            <div id="message-toolbar"><button id="button-delete-messages" class="button-orange" data-sender-id="{$data.sender_id}">Delete Selected Messages</button><button id="button-delete-thread" class="button-orange" data-sender-id="{$data.sender_id}">Delete Entire Conversation</button></div>
            <hr />
            <div id="message-content"></div>
            <hr />
            <div id="message-reply">
                <textarea name="message-input" id="message-input"></textarea>
                <button class="button-orange" id="button-reply" data-reply-to-id="{$message.sender_id}">Reply</button>
            </div>
            <div id="message-options">
                <button class="button-orange" id="button-cancel"">Cancel</button>&nbsp;&nbsp;&nbsp;<button class="button-orange" id="button-delete"">Delete</button>
            </div>
        </div>
    </div>
    {else}
        <p class="no-messages">You don't have any messages yet.</p>
    {/if}
</div>