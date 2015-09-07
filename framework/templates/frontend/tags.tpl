{literal}
<script type="text/javascript">
    function get_comments(pid) {
        var info = 'pid=' + pid;
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            beforeSend: function() {
                //$('#comment-area-' + pid + ' > .comment_loading_area').hide().html('Loading...').fadeIn();
                //$('#comment-area-' + pid + ' > .comment_loading_area').show().html('Loading...');
            },
            success: function(response) {
                $('#comment_loading_area-' + pid).html(response);
                //$('#comment-area-' + pid).slideDown('slow');
                $('#comment-area-' + pid).slideToggle('fast');
            }
        });
    }

    $(document).on('click' , '.comment-down' , function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var pid = tmp[1];
        get_comments(pid);
    });

    $(document).on('click', '.button-comment', function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var pid = tmp[2];
        var comment = $('#comment-field-' + pid).val();

        var info = 'pid=' + pid + '&act=post' + '&comment=' + comment;
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            beforeSend: function() {
                //$('#comment_loading_area-' + pid).hide().html('Loading...').fadeIn();
            },
            success: function(response) {
                $('#comment_loading_area-' + pid).prepend(response).hide().fadeIn('slow');
                $('#comment-field-' + pid).val('');
            }
        });
    });

    $(document).on('click', '.button-reply', function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var cid = tmp[2];
        $('#comcom-reply-' + cid).show('fast');
        $('#comcom-field-' + cid).focus();
    });

    $(document).on('click', '.button-comreply', function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var cid = tmp[2];
        var pid = tmp[3];
        var comment = $('#comcom-field-' + cid).val();

        var info = 'pid=' + pid + '&cid=' + cid + '&act=reply' + '&comment=' + comment;
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            beforeSend: function() {
                //$('#comcom_loading_area-' + pid).hide().html('Loading...').fadeIn();
            },
            success: function(response) {
                $('#comcom_loading_area-' + cid).prepend(response).hide().fadeIn('slow');
                $('#comcom-field-' + cid).val('');
            }
        });
    });

    $(document).on('click', '.flag-post', function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var pid = tmp[1];
        var info = 'pid=' + pid + '&act=flag';
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            success: function(response) {
                $('#flag-' + pid).removeClass("Like Unlike").addClass(response);
            }
        });
    });

    $(document).on('click', '.report-post', function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var pid = tmp[2];
        var info = 'pid=' + pid + '&act=report_post';
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            success: function(response) {
                alert(response);
            }
        });
    });

    $(document).on('click', '.delete-post', function() {
        if(confirm("Are you sure you want to delete this post?")) {
            var tmp = $(this).attr('id');
            var tmp = tmp.split('-');
            var pid = tmp[2];
            var info = 'pid=' + pid + '&act=delete';
            $.ajax({
                url: '{/literal}{"comments.php"|seo_url}{literal}',
                type: 'POST',
                data: info,
                success: function (response) {
                    //alert(response);
                    $('#newsfeed-' + pid).remove();
                }
            });
        }
    });

    $(document).on('click', '.button-remove', function() {
        var e = $(this);
        var cid = e.data('comment-id');
        var info = 'cid=' + cid + '&act=' + e.data('act');
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            success: function(response) {
                $('#comment-container-' + cid).remove();
            }
        });
    });

    $(document).on('click', '.button-report', function() {
        var tmp = $(this).attr('id');
        var tmp = tmp.split('-');
        var cid = tmp[2];
        var info = 'cid=' + cid + '&act=report_comment';
        $.ajax({
            url: '{/literal}{"comments.php"|seo_url}{literal}',
            type: 'POST',
            data: info,
            success: function(response) {
                alert(response);
            }
        });
    });
</script>
{/literal}

<div class="mid">
    {foreach from="$posts" item="post"}
        <div class="newsfeed whitebox" id="newsfeed-{$post.post_id}">
            <div class="newsfeed-top">
                <h5>
                    <a href="{"profile"|seo_url}/{$post.pet_id}"><img src="{$post.thumb}" class="profile-pic"></a>
                    <a href="{"profile"|seo_url}/{$post.pet_id}"><span>{$post.name} {$post.lastname}</span></a>
                    <small>{$misc->fn_time_diff($post.date)}</small>
                </h5>
            </div>
            <div class="newsfeed-post">
                <p>{$post.text}</p>
                <div class="image-content">
                    {if $post.image.image_path}<img src="{$post.image.image_path}" />{/if}
                </div>
                <div class="tags">Tags: {$post.tags}</div>
            </div>
            <div class="row newsfeed-bottom">
                <div>
                    <a href="javascript:void(0);" class="flag-post {$post.liked} left" id="flag-{$post.post_id}" title="{$post.liked} this Post"><span>Flag</span></a>
                </div>
                <div>
                    <a href="javascript:void(0);" class="comment-down left" id="comment-{$post.post_id}" title="Comment on this Post"><span>Comment</span></span></a>
                </div>
                <h5 class="right">
                    {$post.button}
                    <div><a href="javascript:void(0);" class="report-post" id="report-post-{$post.post_id}" title="Report this Post"><span>Report</span></a></div>
                </h5>
                <br clear="all"/>
            </div>
        </div>

        <div class="comment-area" id="comment-area-{$post.post_id}">
            <div class="text-left loading-dots hidden">
                <!--<a href="#"><span>...</span></a>--><br />
            </div>
            <div class="comment-field-box">
                <textarea name="comment-field-{$post.post_id}" class="comment-field" id="comment-field-{$post.post_id}"></textarea>
                <button class="button-comment" id="button-comment-{$post.post_id}"><span>Comment on this Post</span></button>
            </div>
            <ul class="comment_loading_area" id="comment_loading_area-{$post.post_id}" style="display: block;">
            </ul>
        </div>
    {/foreach}
</div>