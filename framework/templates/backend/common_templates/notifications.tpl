<script src="js/notifications.js"></script>

<div id="notifications-container" class="box-content alerts">
    <div id="notification-error" class="alert alert-error" style="display:none">
        <button type="button" class="close" data-dismiss="alert" onclick="$(this).parent().dequeue();$(this).parent().delay(0).fadeOut('slow');">×</button>
        <div id="notification-error-content"></div>
    </div>
    <div id="notification-success" class="alert alert-success" style="display:none">
        <button type="button" class="close" data-dismiss="alert" onclick="$(this).parent().dequeue();$(this).parent().delay(0).fadeOut('slow');">×</button>
        <div id="notification-success-content"></div>
    </div>
    <div id="notification-info" class="alert alert-info" style="display:none">
        <button type="button" class="close" data-dismiss="alert" onclick="$(this).parent().dequeue();$(this).parent().delay(0).fadeOut('slow');">×</button>
        <div id="notification-info-content"></div>
    </div>
    <div id="notification-warning" class="alert alert-block" style="display:none">
        <button type="button" class="close" data-dismiss="alert" onclick="$(this).parent().dequeue();$(this).parent().delay(0).fadeOut('slow');">×</button>
        <div id="notification-warning-content"></div>
    </div>
</div>