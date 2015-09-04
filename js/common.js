$(function() {
    $( ".datepicker" ).datepicker();
});

function popup_open(element_id){
	$(element_id).show('fast', function() {
		$(element_id).css({"opacity":"1"});
	});	
}
function popup_close(){
	$( ".modal-popup" ).animate({"opacity":"0"}, 400, function() {
		$(".modal-popup").hide();
	});	
}
