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



<form id="upload_form" method="post" enctype="multipart/form-data">
	<fieldset>
		<div class="popup-fields">
			<div class="popup-field-label">
				File
			</div>
			<div class="popup-field" id="file-container" >
				<input type="file" name="file[]" id="file" multiple/>
			</div>
		</div>
		<div class="popup-fields">
			<div class="button-primary" onclick="upload();">
				Upload
			</div>
		</div>
	</fieldset>
</form>