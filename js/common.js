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
function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}