<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     seo_url<br>
 * Date:     20150730
 * Purpose:  convert \r\n, \r or \n to <<br>>
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {"home.php"|seo_url}
 * @version  1.0
 * @param string
 * @return string
 */
function smarty_modifier_seo_url($string)
{
	global $friendly;
	return $friendly->get_seourl_by_module($string, true);
}

/* vim: set expandtab: */

?>
