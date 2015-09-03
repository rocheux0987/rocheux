<?php
function smarty_modifier_get_lang($name)
{
	global $languages;
	$result = $languages->langs[_CLIENT_LANGUAGE_][$name];
	if (empty($result)){
		$result = '_'.$name;
	}
	return $result;
	
}
?>
