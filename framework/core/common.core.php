<?php
class common{
	
	function clear_cache(){
		global $filesystem;
		$filesystem->fn_rm(_TMP_DIR_);
	}
	
	#Clean content for URLs
	function sanitize_content($string){
		$string = utf8_decode($string);
		$string = strtolower($string);
		$string = strip_tags($string);
		$string = preg_replace("/ /", "-", $string);
		$string = stripslashes($string);
		$string = preg_replace('/"/', '', $string);
		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
		$repl = array('a', 'e', 'i', 'o', 'u', 'n');
		$string = str_replace ($find, $repl, $string);
		return $string;
	}
	
	#Redirect
	function redirect($url, $force_js = false) {
		if ($force_js){
			echo '<script languaje="text/javascript">document.location.href="'.$url.'";</script>';
			return;
		}

		if (headers_sent()) {
			echo '<script languaje="text/javascript">document.location.href="'.$url.'";</script>';
		}else{
			header ('Location:' . $url);
		}
	}
	
	/**
	 * Retrieve parameter from php options
	 *
	 * @param string $param parameter to get value for
	 * @param boolean $get_value if true, get value, otherwise return true if parameter enabled, false if disabled
	 * @return mixed parameter value
	 */
	function fn_get_ini_param($param, $get_value = false){
		$res = ini_get($param);
		if ($get_value == true) {
			return $res;
		} else {
			return (intval($res) || !strcasecmp($res, 'on')) ? true : false;
		}
	}
	
	/**
	 * Strip slashes
	 *
	 * @param mixed $var variable to strip slashes from
	 * @return mixed filtered variable
	 */
	function fn_strip_slashes($var)
	{
		if (is_array($var)) {
			$var = array_map(array($this,'fn_strip_slashes'), $var);
			return $var;
		}
	
		return (strpos($var, '\\\'') !== false || strpos($var, '\\\\') !== false || strpos($var, '\\"') !== false) ? stripslashes($var) : $var;
	}
	
	//
	// Advanced checking for variable emptyness
	//
	function fn_is_empty($var){
		if (!is_array($var)) {
			return (empty($var));
		} else {
			foreach ($var as $k => $v) {
				if (empty($v)) {
					unset($var[$k]);
					continue;
				}
	
				if (is_array($v) && $thid->fn_is_empty($v)) {
					unset($var[$k]);
				}
			}
			return (empty($var)) ? true : false;
		}
	}
	
	function fn_is_not_empty($var){
		return !$thid->fn_is_empty($var);
	}
	
	/**
	 * Merge several arrays preserving keys (recursivelly!) or not preserving
	 *
	 * @param array ... unlimited number of arrays to merge
	 * @param bool ... if true, the array keys are preserved
	 * @return array merged data
	 */
	function fn_array_merge(){
		$arg_list = func_get_args();
		$preserve_keys = true;
		$result = array();
		if (is_bool(end($arg_list))) {
			$preserve_keys = array_pop($arg_list);
		}
	
		foreach ((array)$arg_list as $arg) {
			foreach ((array)$arg as $k => $v) {
				if ($preserve_keys == true) {
					$result[$k] = !empty($result[$k]) && is_array($result[$k]) ? $thid->fn_array_merge($result[$k], $v) : $v;
				} else {
					$result[] = $v;
				}
			}
		}
	
		return $result;
	}
	
	function fn_array_multimerge($array1, $array2, $name){
		if (is_array($array2) && count($array2)) {
			foreach ($array2 as $k => $v) {
				if (is_array($v) && count($v)) {
					$array1[$k] = $thid->fn_array_multimerge(@$array1[$k], $v, $name);
				} else {
					$array1[$k][$name] = ($name == 'error') ? 0 : $v;
				}
			}
		} else {
			$array1 = $array2;
		}
	
		return $array1;
	}

	//
	// fn_print_r wrapper
	// outputs variables data and dies
	//
	function fn_print_die(){
		$args = func_get_args();
		call_user_func_array(array($this,'fn_print_r'), $args);
		die();
	}
	
	//
	//  print_r wrapper
	//
	function fn_print_r(){
		static $count = 0;
		$args = func_get_args();
	
		if (!empty($args)) {
			echo '<ol style="font-family: Courier; font-size: 12px; border: 1px solid #dedede; background-color: #efefef; float: left; padding-right: 20px;">';
			foreach ($args as $k => $v) {
				$v = htmlspecialchars(print_r($v, true));
				if ($v == '') {
					$v = '    ';
			}
	
				echo '<li><pre>' . $v . "\n" . '</pre></li>';
			}
			echo '</ol><div style="clear:left;"></div>';
		}
		$count++;
	}
	
	/**
	* Sort array by key 
	*
	* @param array $array - array for sorting
	* @param string $key - key to sort by
	* @param const $order - sort order (SORT_ASC/SORT_DESC)
	* @return sorted array
	*/
	function fn_sort_array_by_key($array, $key, $order = SORT_ASC){
		uasort($array, create_function('$a, $b', "\$r = strnatcasecmp(\$a['$key'], \$b['$key']); return ($order == SORT_ASC) ? \$r : 0 - \$r ;"));
		return $array;
	}
	
	//
	// Define and assign pages
	//
	/*function fn_paginate($page = 1, $total_items = 10, $items_per_page = 10, $get_limit = false){
	
		// Avoid meaningless string and zero values 
		$items_per_page = intval($items_per_page);
		if (empty($items_per_page)) {
			$items_per_page = 10;
		}
	
		$deviation = 7;
		$max_pages = $per_page = 10;
		$original_ipp = $items_per_page;
		$navi_ranges = array();
	
		if (!empty($_REQUEST['items_per_page'])) {
			$_SESSION['items_per_page'] = $_REQUEST['items_per_page'] > 0 ? $_REQUEST['items_per_page'] : 1;
		}
	
		if (!empty($_SESSION['items_per_page'])) {
		   $items_per_page = $_SESSION['items_per_page'];
		}
		
		$items_per_page = empty($items_per_page) ? $per_page : (int)$items_per_page;
		$total_pages = ceil((int)$total_items / $items_per_page);
	
		$page = (int) $page;
		if ($page < 1) {
			$page = 1;	
		}
		
		if ($get_limit == false) {
			if ($total_items == 0 || $page == 'full_list') {
				return '';
			}
	
			if ($page > $total_pages) {
				$page = 1;
			}
				
			// Pagination in other areas displayed as in any search engine
			$page_from = ($page - $deviation < 1) ? 1 : $page - $deviation;
			$page_to = ($page + $deviation > $total_pages) ? $total_pages : $page + $deviation;
	
			$pagination = array (
				'navi_pages' => range($page_from, $page_to),
				'prev_range' => ($page_from > 1) ? $page_from - 1 : 0,
				'next_range' => ($page_to < $total_pages) ? $page_to + 1: 0,
				'current_page' => $page,
				'prev_page' => ($page > 1) ? $page - 1 : 0,
				'next_page' => ($page < $total_pages) ? $page + 1 : 0,
				'total_pages' => $total_pages,
				'total_items' => $total_items,
				'navi_ranges' => $navi_ranges,
				'items_per_page' => $items_per_page,
				'per_page_range' => range($per_page, $per_page * $max_pages, $per_page)
			);
	
			if (!in_array($original_ipp, $pagination['per_page_range'])) {
				$pagination['per_page_range'][] = $original_ipp;
				sort($pagination['per_page_range']);
			}
	
			Registry::get('view')->assign('pagination', $pagination);
		}
	
		return 'LIMIT ' . (($page - 1) * $items_per_page) . ", $items_per_page";
	}*/
}
?>
