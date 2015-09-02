<?php
class controllers_loader
{
	function load($controllers_list, $area = _AREA_){
		if (empty($controllers_list)){
			return false;
		}
		if (!preg_match("#,#", $controllers_list)){
			$controllers.=",";
		}
		$controllers = explode(",", str_replace(' ','',$controllers_list));
		for($i=0;$i<=count($controllers);$i++){
			if (!empty($controllers[$i])){
				$controllers[$i] = strtolower($controllers[$i]);
				if ($area == _AREA_BACKEND_){
					$controller_path = _CONTROLLERS_DIR_."backend/".$controllers[$i].".controller.php";
				}else{
					#TODO: Replace this with each _AREA_ 
					$controller_path = _CONTROLLERS_DIR_."frontend/".$controllers[$i].".controller.php";
				}
				/*
				if (!file_exists($controller_path)){
					$controller_path = _CONTROLLERS_DIR_."common/".$controllers[$i].".controller.php";
				}
				*/
				if (file_exists($controller_path)){
					require_once($controller_path);
					$instance = $controllers[$i]."_controller";
					$GLOBALS[$instance] = new $controllers[$i] ();
				}
			}
		}
	}
}
?>