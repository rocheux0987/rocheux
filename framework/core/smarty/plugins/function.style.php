<?php
function smarty_function_style($params, &$smarty){

	//example: $version='?v1';
	$version='';
	
	$style[$params['src']] = '<link rel="stylesheet" type="text/css" href="' . _CSS_URL_ . $params['src'] . $version. '">';

	return $style[$params['src']];
}
?>