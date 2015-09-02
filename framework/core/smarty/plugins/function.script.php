<?php
function smarty_function_script($params, &$smarty){

	//example: $version='?v1';
	$version='';
	
	$scripts[$params['src']] = '<script type="text/javascript"' . ' src="' . _SCRIPTS_URL_ . $params['src'] . $version. '"></script>';

	return $scripts[$params['src']];
}
?>