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
 <div class="modal-popup" id="popup-login">
        <div class="modal-popup-overlay"></div>
        <div class="modal-popup-inner">
            <h2>Upload</h2>
            <div id="response" style="font-family:'Century Gothic',CenturyGothic,AppleGothic,sans-serif; {if $upload_result_type eq 'success'}color:green;{else}color:red;{/if} text-align:center;">{$upload_result_msg}</div>
            <form id="upload_form" method="post" enctype="multipart/form-data">
            <fieldset>
            	<div class="popup-fields">
                	<div class="popup-field-label">
                    File
                    </div>
                	<div class="popup-field" id="file-container">
                    <input type="file" name="file" id="file" />
                    </div>
                </div>
                <div class="popup-fields">
                	<div class="button-primary" onclick="upload();">
                    	Upload
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>