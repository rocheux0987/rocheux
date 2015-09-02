{literal}
<script type="text/javascript">
function get_ajax_data(){
$.ajax({
	url: '?act=get_ajax_data',
	type:'POST',
	beforeSend: function(){
		$('body, html, a').css({'cursor':'wait'});
	},
	success: function(response){	
		//Parse JSON data
		response_data = jQuery.parseJSON(response);
		var html_content = '';
		console.log(response_data);
		html_content+='<table>';
		html_content+='<tr><td>ID</td><td>Title</td><td>Description</td></tr>';
		
		//Loop the AJAX response
		for (i = 0; i < response_data.length; i++) { 
			html_content+='<tr><td>'+response_data[i]["id"]+'</td><td>'+response_data[i]["title"]+'</td><td>'+response_data[i]["description"]+'</td></tr>';
		}
		html_content+='</table>';
		
		//Show contents
		$(".ajax-response").html(html_content);
		
		$('body, html, a').css({'cursor':'default'});
		$('a').css({'cursor':'pointer'});
	}	
});		
}
</script>
{/literal}

<a href="javascript:void(0)" onclick="get_ajax_data();">Clickme (Ajax call)</a>

<div class="ajax-response"></div>