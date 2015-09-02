<?php
class notifications
{
	function set($message, $type, $autoclose){
		$next_index = count($_SESSION["notifications"]);
		$_SESSION["notifications"][$next_index]["message"]=$message;
		$_SESSION["notifications"][$next_index]["type"]=$type;
		$_SESSION["notifications"][$next_index]["autoclose"]=$autoclose;		
		return true;
	}
	
	function get(){
		return $_SESSION["notifications"];
	}
	
	function delete(){
		unset($_SESSION["notifications"]);
		return true;
	}

	function assign(){
		global $smarty;
		$notifications = $_SESSION["notifications"];
		if (count($notifications)>0){
			$this->delete();
			$smarty->assign("notifications", $notifications);
			return true;
		}
		return false;
	}

}
?>