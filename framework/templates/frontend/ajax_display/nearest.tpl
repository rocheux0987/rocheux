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
{foreach name="results" from=$data item=row}
<!-- NEWSFEED AREA-->
<div class="newsfeed whitebox">
  <!-- NEWSFEED CONTENT -->
  <div class="newsfeed-content">
    <div class="row nearest-content">
      <div class="map-image left"><a href="#"><img src="{$row.image.image_path}" /></a></div>
      <div class="nearest-infos right">
          <div class="map-canvas" id="map_{$row.vet_id}"></div>
          {literal}
          <script type="text/javascript">
            initMap({/literal}{$row.lat}{literal}, {/literal}{$row.lon}{literal} , 'map_{/literal}{$row.vet_id}{literal}');
          </script>
          {/literal}
          <div>
            <div class="naddress left" style="padding-top:10px;"><p>{$row.address}</p></div>
            <div class="nother-infos right">
              <a href="tel:{$row.contact_number}"><i class="fa fa-phone"></i> <span>{$row.contact_number}</span></a>
              <br />
              <a href="mailto:{$row.email}"><i class="fa fa-envelope"></i> <span>{$row.email}</span></a>
            </div>
          <br clear="all"/>
        </div>
      </div>
    </div>
  </div>
<!-- END NEWSFEED CONTENT -->

  <!-- COMMENT SECTION -->
  <div class="row newsfeed-bottom">
    <div>
      <a href="javascript:void(0);" class="flag-post {$post.liked} left" id="flag-{$post.post_id}" title="{$post.liked} this Post"><span>Flag</span></a>
    </div>
    <div>
      <a href="javascript:void(0);" class="comment-down left" id="comment-{$post.post_id}" title="Comment on this Post"><span>Comment</span></span></a>
    </div>
    <br clear="all"/>
  </div>
  <!-- END COMMENT SECTION -->
</div>
<div class="whitebox comment-area" id="comment-area-{$post.post_id}">
  <div class="commentarea-cont">
    <div class="text-left loading-dots hidden">
      <!--<a href="#"><span>...</span></a>--><br />
    </div>
    <div class="comment-field-box">
      <textarea name="comment-field-{$post.post_id}" class="comment-field" id="comment-field-{$post.post_id}"></textarea>
      <button class="button-comment" id="button-comment-{$post.post_id}"><span>Comment on this Post</span></button>
    </div>
    <ul class="comment_loading_area" id="comment_loading_area-{$post.post_id}" style="display: block;"></ul>
  </div>
</div>
  <br clear="all" />
{foreachelse}
  <p class="text-center">No Results...</p>
{/foreach}