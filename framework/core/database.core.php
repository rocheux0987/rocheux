<?php
class database{

	public $db_link;
	public $db_name;
	
	#Class constructor
	public function __construct($host=false , $user=false , $pass=false , $db=false){
		if (!$host || !$user || !$pass || !$db){
			$this->db_initiate(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_NAME_);
			$this->db_name = _DB_NAME_;
		}
		else {
			$this->db_initiate($host, $user, $pass, $db);
			$this->db_name = $db;
		}
	}
	
	#Class destructor.
	function __destruct(){
		$this->driver_db_close();	
	}
	
	#Connection to database
	public function db_initiate($host, $user, $pass, $db){
		$this->db_link = mysqli_connect($host, $user, $pass, $db) or die("Error connecting to database (01)");
		mysqli_select_db($this->db_link, $db) or die("Error connecting to database (02)");
		mysqli_set_charset($this->db_link, "utf8") or die("Error seting charset (03)");
		return true;
	}

	#Driver methods
	function driver_db_fetch_array($result){
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	}
	function driver_db_free_result($result){
		mysqli_free_result($result);
	}
	function driver_db_fetch_row($result){
		return mysqli_fetch_row($result);
	}
	function driver_db_insert_id(){
		$db_conn = $this->db_link;
		return mysqli_insert_id($db_conn);
	}
	function driver_db_query($query, $db = false)
	{
		if (!$db){
			$db = $this->db_name;
		}
		$db_conn = $this->db_link;
		
		static $reconnect_attempts = 0;

		$result = mysqli_query($db_conn, $query);

		if (empty($result)) {
			// Lost connection, try to reconnect (max - 3 times)
			if (($this->driver_db_errno() == 2013 || $this->driver_db_errno() == 2006) && $reconnect_attempts < 3) {
				$this->driver_db_close();
				$db_conn = $this->db_initiate(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_NAME_);
				$reconnect_attempts++;
				$result = $this->driver_db_query($query, $db);

			// Assume that the table is broken
			// Try to repair
			} elseif (preg_match("/'(\S+)\.(MYI|MYD)/", $this->driver_db_error(), $matches)) {
				$result = mysqli_query("REPAIR TABLE $matches[1]");
			}
		}

		return $result;
	}
	function driver_db_errno(){
		$db_conn = $this->db_link;

		static $skip_error_codes = array (
			1091, // column exists/does not exist during alter table
			1176, // key does not exist during alter table
			1050, // table already exist 
			1060  // column exists
		);

		$errno = mysqli_errno($db_conn);

		return in_array($errno, $skip_error_codes) ? 0 : $errno;
	}
	function driver_db_error($db = ''){
		$db_conn = $this->db_link;
		return mysqli_error($db_conn);
	}
	function driver_db_close($db = ''){
		$db_conn = $this->db_link;
		return @mysqli_close($db_conn);
	}	
	
	
	/**
	 * Execute query and format result as associative array with column names as keys
	 *
	 * @param string $query unparsed query
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_array($query){
		$args = func_get_args();
		
		if ($_result = call_user_func_array(array($this, 'db_query'), $args)) {

			while ($arr = $this->driver_db_fetch_array($_result)) {
				$result[] = $arr;
			}

			$this->driver_db_free_result($_result);
		}

		return !empty($result) ? $result : array();
	}

	/**
	 * Execute query and format result as associative array with column names as keys and index as defined field
	 *
	 * @param string $query unparsed query
	 * @param string $field field for array index
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_hash_array($query, $field)
	{
		$args = array_slice(func_get_args(), 2);
		array_unshift($args, $query);

		if ($_result = call_user_func_array(array($this, 'db_query'), $args)) {
			while ($arr = $this->driver_db_fetch_array($_result)) {
				if (isset($arr[$field])) {
					$result[$arr[$field]] = $arr;
				}
			}

			$this->driver_db_free_result($_result);
		}

		return !empty($result) ? $result : array();
	}

	/**
	 * Execute query and format result as associative array with column names as keys and then return first element of this array
	 *
	 * @param string $query unparsed query
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_row($query)
	{
		$args = func_get_args();

		if ($_result = call_user_func_array(array($this, 'db_query'), $args)) {
			$result = $this->driver_db_fetch_array($_result);
			$this->driver_db_free_result($_result);
		}

		return is_array($result) ? $result : array();
	}

	/**
	 * Execute query and returns first field from the result
	 *
	 * @param string $query unparsed query
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_field($query)
	{
		$args = func_get_args();
		if ($_result = call_user_func_array(array($this, 'db_query'), $args)) {
			$result = $this->driver_db_fetch_row($_result);
			$this->driver_db_free_result($_result);
		}
		return is_array($result) ? $result[0] : NULL;
	}

	/**
	 * Execute query and format result as set of first column from all rows
	 *
	 * @param string $query unparsed query
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_fields($query)
	{
		$args = func_get_args();

		if ($__result = call_user_func_array(array($this, 'db_query'), $args)) {

			$_result = array();
			while ($arr = $this->driver_db_fetch_array($__result)) {
				$_result[] = $arr;
			}

			$this->driver_db_free_result($__result);

			if (is_array($_result)) {
				$result = array();
				foreach ($_result as $k => $v) {
					array_push($result, reset($v));
				}
			}
		}

		return is_array($result) ? $result : array();
	}

	/**
	 * Execute query and format result as one of: field => array(field_2 => value), field => array(field_2 => row_data), field => array([n] => row_data)
	 *
	 * @param string $query unparsed query
	 * @param array $params array with 3 elements (field, field_2, value)
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_hash_multi_array($query, $params)
	{
		@list($field, $field_2, $value) = $params;

		$args = array_slice(func_get_args(), 2);
		array_unshift($args, $query);

		if ($_result = call_user_func_array(array($this, 'db_query'), $args)) {
			while ($arr = $this->driver_db_fetch_array($_result)) {
				if (!empty($field_2)) {
					$result[$arr[$field]][$arr[$field_2]] = !empty($value) ? $arr[$value] : $arr;
				} else {
					$result[$arr[$field]][] = $arr;
				}
			}

			$this->driver_db_free_result($_result);

		}

		return !empty($result) ? $result : array();
	}

	/**
	 * Execute query and format result as key => value array
	 *
	 * @param string $query unparsed query
	 * @param array $params array with 2 elements (key, value)
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return array structured data
	 */
	public function db_get_hash_single_array($query, $params)
	{
		@list($key, $value) = $params;

		$args = array_slice(func_get_args(), 2);
		array_unshift($args, $query);

		if ($_result = call_user_func_array(array($this, 'db_query'), $args)) {
			while ($arr = $this->driver_db_fetch_array($_result)) {
				$result[$arr[$key]] = $arr[$value];
			}

			$this->driver_db_free_result($_result);
		}

		return !empty($result) ? $result : array();
	}

	/**
	 * Execute query
	 *
	 * @param string $query unparsed query
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return boolean always true, dies if problem occured
	 */

	public function db_query($query)
	{
		$args = func_get_args();
		$query = $this->db_process($query, array_slice($args, 1), true);

		if (empty($query)) {
			return false;
		}
		
		$result = $this->driver_db_query($query, $dbc_name);

		if ($result === true) { // true returns for success insert/update/delete query
		
			// Check if it was insert statement with auto_increment value
			if ($i_id = $this->driver_db_insert_id()) {
				return $i_id;
			}
		}

		$this->db_error($result, $query, $dbc_name);

		return $result;
	}

	/**
	 * Parse query and replace placeholders with data
	 *
	 * @param string $query unparsed query
	 * @param mixed ... unlimited number of variables for placeholders
	 * @return parsed query
	 */
	public function db_quote(){
		$args = func_get_args();
		$pattern = array_shift($args);
		return $this->db_process($pattern, $args, false);
	}

	/**
	 * Parse query and replace placeholders with data
	 *
	 * @param string $query unparsed query
	 * @param array $data data for placeholders
	 * @param string $dbc_name database connection name
	 * @return parsed query
	 */
	public function db_process($pattern, $data = array(), $replace = true)
	{
		static $session_vars_updated = false;
		$command = 'get';
		$group_concat_len = 3000; // 3Kb

		// Check if query updates data in the database
		if (preg_match("/^(UPDATE|INSERT INTO|REPLACE INTO|DELETE FROM) \?\:(\w+) /", $pattern, $m)) {
			$table_name = $m[2];
			$command = ($m[1] == 'DELETE FROM') ? 'delete' : 'set';
		}

		if (strpos($pattern, 'GROUP_CONCAT(') !== false && $session_vars_updated == false) {
			$this->db_query('#SET SESSION group_concat_max_len = ?i', $group_concat_len);
			$session_vars_updated = true;
		}
		
		// Replace table prefixes
		if ($replace) {
			$pattern = str_replace('?:', _TABLE_PREFIX_, $pattern);
		}
		
		if (!empty($data) && preg_match_all("/\?(i|s|l|d|a|n|u|e|p|w|f)+/", $pattern, $m)) {
			$offset = 0;
			foreach ($m[0] as $k => $ph) {
				if ($ph == '?u' || $ph == '?e') {
					$data[$k] = $this->fn_check_table_fields($data[$k], $table_name);

					if (empty($data[$k])) {
						return false;
					}
				}

				if ($ph == '?i') { // integer
					$pattern = $this->db_str_replace($ph, $this->db_intval($data[$k]), $pattern, $offset); // Trick to convert int's and longint's

				} elseif ($ph == '?s') { // string
					$pattern = $this->db_str_replace($ph, "'" . addslashes($data[$k]) . "'", $pattern, $offset);

				} elseif ($ph == '?l') { // string for LIKE operator
					$pattern = $this->db_str_replace($ph, "'" . addslashes(str_replace("\\", "\\\\", $data[$k])) . "'", $pattern, $offset);

				} elseif ($ph == '?d') { // float
					$pattern = $this->db_str_replace($ph, sprintf('%01.2f', $data[$k]), $pattern, $offset);

				} elseif ($ph == '?a') { // array FIXME: add trim
					$data[$k] = !is_array($data[$k]) ? array($data[$k]) : $data[$k];
					$pattern = $this->db_str_replace($ph, "'" . implode("', '", array_map('addslashes', $data[$k])) . "'", $pattern, $offset);

				} elseif ($ph == '?n') { // array of integer FIXME: add trim
					$data[$k] = !is_array($data[$k]) ? array($data[$k]) : $data[$k];
					$pattern = $this->db_str_replace($ph, !empty($data[$k]) ? implode(', ', array_map(array($this, 'db_intval'), $data[$k])) : "''", $pattern, $offset);

				} elseif ($ph == '?u' || $ph == '?w') { // update/condition with and
					$q = '';
					$clue = ($ph == '?u') ? ', ' : ' AND ';
					foreach($data[$k] as $field => $value) {
						$q .= ($q ? $clue : '') . '`' . $this->db_field($field) . "` = '" . addslashes($value) . "'";
					}
					$pattern = $this->db_str_replace($ph, $q, $pattern, $offset);

				} elseif ($ph == '?e') { // insert
					$pattern = $this->db_str_replace($ph, '(`' . implode('`, `', array_map('addslashes', array_keys($data[$k]))) . "`) VALUES ('" . implode("', '", array_map('addslashes', array_values($data[$k]))) . "')", $pattern, $offset);

				} elseif ($ph == '?f') { // field/table/database name
					$pattern = $this->db_str_replace($ph, $this->db_field($data[$k]), $pattern, $offset);

				} elseif ($ph == '?p') { // prepared statement
	//				$pattern = db_str_replace($ph, $data[$k], $pattern, $offset);
					$pattern = $this->db_str_replace($ph, $data[$k], $pattern, $offset);
				}
			}
		}

		return $pattern;
	}

	/**
	 * Placeholder replace helper
	 *
	 * @param string $needle string to replace
	 * @param string $replacement replacement
	 * @param string $subject string to search for replace
	 * @param int $offset offset to search from
	 * @return string with replaced fragment
	 */
	public function db_str_replace($needle, $replacement, $subject, &$offset){
		$pos = strpos($subject, $needle, $offset);
		$offset = $pos + strlen($replacement);
		return substr_replace($subject, $replacement, $pos, 2);
	}

	/**
	 * Convert variable to int/longint type
	 *
	 * @param mixed $int variable to convert
	 * @return mixed int/intval variable
	 */
	public function db_intval($int){
		return $int + 0;
	}

	/**
	 * Check if variable is valid database table name, table field or database name
	 *
	 * @param mixed $int variable to convert
	 * @return mixed int/intval variable
	 */
	public function db_field($field){
		if (preg_match("/([\w]+)/", $field, $m) && $m[0] == $field) {
			return $field;
		}

		return '';
	}

	/**
	 * Get column names from table
	 *
	 * @param string $table_name table name
	 * @param array $exclude optional array with fields to exclude from result
	 * @param boolean $wrap_quote optional parameter, if true, the fields will be enclosed in quotation marks
	 * @param string $dbc_name database connection name
	 * @return array columns array
	 */
	public function fn_get_table_fields($table_name, $exclude = array(), $wrap = false){	
		static $table_fields_cache = array();
		
		if (!isset($table_fields_cache[$table_name])) {
			$table_fields_cache[$table_name] = $this->db_get_fields("SHOW COLUMNS FROM ?:$table_name");
		}
		
		$fields = $table_fields_cache[$table_name];
		if (!$fields) {
			return false;
		}
		
		if ($exclude) {
			$fields = array_diff($fields, $exclude);	
		}
		
		if ($wrap) {
			foreach($fields as &$v) {
				$v = "`$v`";
			}
		}
		
		return $fields;
	}

	/**
	 * Check if passed data corresponds columns in table and remove unnecessary data
	 *
	 * @param array $data data for compare
	 * @param array $table_name table name
	 * @param string $dbc_name database connection name
	 * @return mixed array with filtered data or false if fails
	 */
	public function fn_check_table_fields($data, $table_name)
	{
		$_fields = $this->fn_get_table_fields($table_name, array(), false);
		if (is_array($_fields)) {
			foreach ($data as $k => $v) {
				if (!in_array($k, $_fields)) {
					unset($data[$k]);
				}
			}
			if (func_num_args() > 3) {
				for ($i = 3; $i < func_num_args(); $i++) {
					unset($data[func_get_arg($i)]);
				}
			}
			return $data;
		}
		return false;
	}

	/**
	 * Remove value from set (e.g. remove 2 from "1,2,3" results in "1,3")
	 *
	 * @param string $field table field with set
	 * @param string $value value to remove
	 * @return string database construction for removing value from set
	 */
	public function fn_remove_from_set($field, $value){
		return $this->db_quote("TRIM(BOTH ',' FROM REPLACE(CONCAT(',', $field, ','), CONCAT(',', ?s, ','), ','))", $value);
	}

	/**
	 * Add value to set (e.g. add 2 from "1,3" results in "1,3,2")
	 *
	 * @param string $field table field with set
	 * @param string $value value to add
	 * @return string database construction for add value to set
	 */
	public function fn_add_to_set($field, $value)
	{
		return $this->db_quote("TRIM(BOTH ',' FROM CONCAT_WS(',', ?p, ?s))", fn_remove_from_set($field, $value), $value);
	}

	/**
	 * Create set from php array
	 *
	 * @param array $set_data values array
	 * @return string database construction for creating set
	 */
	public function fn_create_set($set_data = array())
	{
		return empty($set_data) ? '' : implode(',', $set_data);
	}

	public function fn_find_array_in_set($arr, $set, $find_empty = false)
	{
		$conditions = array();
		if ($find_empty) {
			$conditions[] = "$set = ''";
		}
		if (!empty($arr)) {
			foreach ($arr as $val) {
				$conditions[] = $this->db_quote("FIND_IN_SET(?i, $set)", $val);
			}
		}

		return empty($conditions) ? '' : implode(' OR ', $conditions);
	}

	/**
	 * Display database error
	 *
	 * @param resource $result result, returned by database server
	 * @param string $query SQL query, passed to server
	 * @param string $dbc_name database connection name
	 * @return mixed false if no error, dies with error message otherwise
	 */
	public function db_error($result, $query)
	{
		if (!empty($result) || $this->driver_db_errno() == 0) {
			// it's ok
		} else {
			$error = array (
				'message' => $this->driver_db_error() . ' <b>(' . $this->driver_db_errno() . ')</b>',
				'query' => $query,
			);
			#Errors only need to show in dev mode or backend access.
			if (_AREA_ != 'A' && _DEV_MODE_ == false){
				die("Custom frontend database error.");
			}
			die("Database error: <pre>".print_r($error, true)."</pre><br>".debug_backtrace());
		}

		return false;
	}

	/**
	 * Get the number of found rows from the last query
	 * 
	 */
	public function db_get_found_rows()
	{
		$count = $this->db_get_field("SELECT FOUND_ROWS()");
		return $count;
	}

	/**
	 * Fuctnions parses SQL file and import data from it
	 *
	 * @param string $file File for import
	 * @param integer $buffer Buffer size for fread function
	 * @param booleand $show_status Show or do not show process by printing ' .'
	 * @param integer $show_create_table 0 - Do not print the name of created table, 1 - Print name and get lang_var('create_table'), 2 - Print name without getting lang_var
	 * @param boolean $check_prefix Check table prefix and replace it with the installed in config.php
	 * @param boolean $track Use queries cache. Do not execute queries that already are executed.
	 * @return false, if file is not accessible
	 */
	 /*
	public function db_import_sql_file($file, $buffer = 16384, $show_status = true, $show_create_table = 1, $check_prefix = false, $track = false, $skip_errors = false)
	{
		if (file_exists($file)) {
			
			$path = dirname($file);
			$file_name = basename($file);
			$tmp_file = $path . "/$file_name.tmp";

			$executed_queries = array();
			if ($track && file_exists($tmp_file)) {
				$executed_queries = unserialize(fn_get_contents($tmp_file));
			}

			if ($skip_errors) {
				$_skip_errors = Registry::get('runtime.database.skip_errors');
				Registry::set('runtime.database.skip_errors', true);
			}

			$fd = fopen($file, 'r');
			if ($fd) {
				$ret = array();
				$rest = '';
				$fs = filesize($file);

				fn_set_progress('total', ceil($fs / $buffer));
				
				$br = (defined('CONTROLLER') && CONTROLLER == 'upgrade_center') ? '<br />' : '';

				while (!feof($fd)) {
					$str = $rest.fread($fd, $buffer);
					$rest = fn_parse_queries($ret, $str);
					fn_set_progress('echo', $br . fn_get_lang_var('importing_data'));

					if (!empty($ret)) {
						foreach ($ret as $query) {
							if (!in_array($query, $executed_queries)) {
								if ($show_create_table && preg_match('/CREATE\s+TABLE\s+`?(\w+)`?/i', $query, $matches)) {
									if ($show_create_table == 1) {
										$_text = fn_get_lang_var('creating_table');
									} elseif ($show_create_table == 2) {
										$_text = 'Creating table';
									}
									$table_name = $check_prefix ? fn_check_db_prefix($matches[1]) : $matches[1];
									fn_set_progress('echo', $br . $_text . ': <b>' . $table_name . '</b>', false);
								}

								if ($check_prefix) {
									$query = fn_check_db_prefix($query);
								}
								db_query($query);

								if ($track) {
									$executed_queries[] = $query;
									fn_put_contents($tmp_file, serialize($executed_queries));
								}

								if ($show_status) {
									fn_echo(' .');
								}
							}
						}
						$ret = array();
					}
				}

				fclose($fd);
				return true;
			}

			if ($skip_errors) {
				Registry::set('runtime.database.skip_errors', $_skip_errors);
			}
		}

		return false;
	}
	*/

	/**
	 * Get auto increment value for table
	 *
	 * @param string $table - database table
	 * @return integer - auto increment value
	 */
	public function db_get_next_auto_increment_id($table){
		$table_status = $this->db_get_row("SHOW TABLE STATUS LIKE '?:$table'");
		return !empty($table_status['Auto_increment'])? $table_status['Auto_increment'] : $table_status['AUTO_INCREMENT'];
	}

}

?>