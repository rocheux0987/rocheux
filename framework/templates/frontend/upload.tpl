{literal}
<script type="text/javascript">
function upload(){
	if ($("#file").val() == ""){
		$("#file-container").addClass("field-error");
		$("#file").focus();
		return;
	}
	$("#file-container").removeClass("field-error");
	
	action = "{/literal}{$current_url}?act=upload{literal}";
	
	$("#upload_form").attr({"action" : action});
	document.getElementById("upload_form").action = action;
	$("#upload_form").submit();

	$.ajax({
		url: '{/literal}{$current_url}{literal}?act=create_post',
		type:'POST',
		data: '='+$('#user').val()+'&password='+$('#password').val(),
		beforeSend: function() {
			$('body, html, a').css({'cursor':'wait'});
		},
		success: function(response) {
			//Parse JSON data
			response_data = jQuery.parseJSON(response);
			$("#login-response").html(response_data["message"]);

			if (response_data["status"] == 1) {
				//Logged events, like redirect to protected area
				$("#login-response").css({'color':'#008000'});
			} else {
				$("#login-response").css({'color':'red'});
			}

			$("#user").val("");
			$("#password").val("");

			$('body, html, a').css({'cursor':'default'});
			$('a').css({'cursor':'pointer'});
		}
	});
}

function get_file_extension(filename){
	return filename.split('.').pop();	
}

function valid_file_extension(filename){
	extension = get_file_extension(filename);
	for(i=0;i<=allowed_extensions.length-1;i++){
		if (allowed_extensions[i] == extension.toLowerCase()){
			return true;	
		}
	}
	return false;
}

$(document).ready(function() {
	allowed_extensions = new Array("jpg", "jpeg", "png", "gif");
	
	//Remove file inputs that doesn't have a valid extension 
	$("input[type=file]").change(function() {
		if (!valid_file_extension($(this).val())){
			$(this).val("");
		}
    });
});
</script>
{/literal}



<div class="mid">
  <!-- <div class="modal-popup" id="popup-login">
        <div class="modal-popup-overlay"></div>
        <div class="modal-popup-inner"> -->
            <h2>Create Post</h2>
            <div id="response" style="font-family:'Century Gothic',CenturyGothic,AppleGothic,sans-serif; {if $upload_result_type eq 'success'}color:green;{else}color:red;{/if} text-align:center;">{$upload_result_msg}</div>
            <form id="upload_form" method="post" enctype="multipart/form-data">
            <fieldset>
            	<div class="popup-fields">
                	<div class="popup-field-label">Photo (Optional)</div>
                	<div class="popup-field" id="file-container"><input type="file" name="post_file" id="post_file" /></div>
                </div>
            	<div class="popup-fields">
                	<div class="popup-field-label">Description</div>
                	<div class="popup-field-label"><textarea name="post_desc" id="post_desc" cols="50"></textarea></div>
                </div>
            	<div class="popup-fields">
                	<div class="popup-field-label">Tags (Select one or more tags)</div>
                	<div class="popup-field-label">
                          <select name="post_tags[]" id="post_tags" multiple="multiple">
                            {foreach from=$tags item=tag}
                            <option value="{$tag.type_id}">{$tag.value}</option>
                            {/foreach}
                          </select>
                        </div>
                </div>
            	<div class="popup-fields">
                	<div class="popup-field-label">Post Privacy</div>
                	<div class="popup-field-label">
                          <select name="post_priv" id="post_priv">
                            <option value="F">Friends</option>
                            <option value="C">Community</option>
                            <option value="A">Public</option>
                          </select>
                        </div>
                </div>
                <div class="popup-fields buttons">
                	<div class="button-primary" onclick="upload();">Upload</div>
                </div>
            </fieldset>
            </form>
        <!--</div>
    </div>-->
</div>