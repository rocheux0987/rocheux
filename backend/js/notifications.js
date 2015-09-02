function notification(message, type, autoclose){
	
	var autoclose_time = 5000;
	
	switch(type){
		case "error":
		notification_element = $("#notification-error");
		notification_content = $("#notification-error-content");
		break;
		case "warning":
		notification_element = $("#notification-warning");
		notification_content = $("#notification-warning-content");
		break;
		case "success":
		notification_element = $("#notification-success");
		notification_content = $("#notification-success-content");
		break;
		case "info":
		notification_element = $("#notification-info");
		notification_content = $("#notification-info-content");
		break;	
	}
	$(notification_content).html(message);
	$(notification_element).fadeIn();
	
	if (autoclose == true){
		$(notification_element).delay(autoclose_time).fadeOut('slow');
	}
}