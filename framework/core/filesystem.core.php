<?php
//
// File system functions definitions
//

class filesystem {
	
	public $allowed_extensions = array("jpg" => true, "jpeg" => true, "gif" => true, "png" => true);
	#Max file size (in MB) for uploads
	public $max_file_size = 2;
	
	function fn_upload($file, $upload_path = _UPLOADS_TMP_DIR_){
		global $common;
		
		$max_file_size = $this->max_file_size * 1048576;
		
		#Error 01 . Empty variable
		if (empty($file["name"])){
			return array("error" => 1, "error_description" => "Empty file");
		}
		
		$extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
		
		#Error 02 . Extension
		if (!$this->allowed_extensions[$extension]){
			return array("error" => 2, "error_description" => "File type (".$extension.") not allowed");
		}
		
		#Error 03 . Max file size
		if ($file["size"] > $max_file_size){
			return array("error" => 3, "error_description" => "Max file size reached.");
		}
		
		#Error 04 . errors image
		if ($file["error"] > 0){
			return array("error" => 4, "error_description" => "Upload internal error.");
		}
		
		if (empty($upload_path) || $upload_path == '/' || $upload_path == _SITE_DIR_){
			$upload_path = _UPLOADS_TMP_DIR_;
		}
		
		if (!$this->fn_mkdir($upload_path)) {
			return false;
		}
		
		$filename_original = strtolower($file["name"]);
		$file_name = $filename_original;
		
		while (file_exists($upload_path.$file_name)){
			$file_name = time()."_".$filename_original;
		}
		
		move_uploaded_file($file["tmp_name"], $upload_path.$file_name);
		
		return array("file" => $file_name, "upload_path" => $upload_path,"name" => $file_name, "path" => $upload_path.$file_name, "link" => $upload_path.$file_name, "path" => $upload_path.$file_name);
	}
	
	/**
	 * Delete file function
	 *
	 * @param string $file_path file location
	 * @return bool true
	 */
	function fn_delete_file($file_path)
	{
		if (!empty($file_path)) {
			if (is_file($file_path)) {
				@unlink($file_path);
			}
		}

		return true;
	}

	/**
	 * Normalize path: remove "../", "./" and duplicated slashes
	 *
	 * @param string $path
	 * @param string $separator
	 * @return string normilized path
	 */
	function fn_normalize_path($path, $separator = '/')
	{
		global $common;
		$result = array();
		$path = preg_replace("/[\\\\\/]+/S", $separator, $path);
		$path_array = explode($separator, $path);
		if (!$path_array[0])  {
			$result[] = '';
		}

		foreach ($path_array as $key => $dir) {
			if ($dir == '..') {
				if (end($result) == '..') {
				   $result[] = '..';
				} elseif (!array_pop($result)) {
				   $result[] = '..';
				}
			} elseif ($dir != '' && $dir != '.') {
				$result[] = $dir;
			}
		}

		if (!end($path_array)) {
			$result[] = '';
		}

		return $common->fn_is_empty($result) ? '' : implode($separator, $result);
	}

	/**
	 * Create directory wrapper. Allows to create included directories
	 *
	 * @param string $dir
	 * @param int $perms permission for new directory
	 */
	function fn_mkdir($dir, $perms = _DEFAULT_DIR_PERMISSIONS_)
	{
		$result = false;

		// Truncate the full path to related to avoid problems with
		// some buggy hostings
		if (strpos($dir, _SITE_DIR_) === 0) {
			$dir = './' . substr($dir, strlen(_SITE_DIR_));
			$old_dir = getcwd();
			chdir(_SITE_DIR_);
		}

		if (!empty($dir)) {
			clearstatcache();
			if (@!is_dir($dir)) {
				$dir = $this->fn_normalize_path($dir, '/');
				$path = '';
				$dir_arr = array();
				if (strstr($dir, '/')) {
					$dir_arr = explode('/', $dir);
				} else {
					$dir_arr[] = $dir;
				}

				foreach ($dir_arr as $k => $v) {
					$path .= (empty($k) ? '' : '/') . $v;
					clearstatcache();
					if (!@file_exists($path)) {
						umask(0);
						$result = @mkdir($path, $perms);
						if (!$result) {
							$parent_dir = dirname($path);
							$parent_perms = fileperms($parent_dir);
							@chmod($parent_dir, 0777);
							$result = @mkdir($path, $perms);
							@chmod($parent_dir, $parent_perms);
							if (!$result) {
								break;
							}
						}
					}
				}
			} else {
				$result = true;
			}
		}

		if (!empty($old_dir)) {
			@chdir($old_dir);
		}
		return $result;
	}

	/**
	 * Compress files with Tar archiver
	 *
	 * @param string $archive_name - name of the compressed file will be created
	 * @param string $file_list - list of files to place into archive
	 * @param string $dirname - directory, where the files should be get from
	 * @return bool true
	 */
	 /*
	function fn_compress_files($archive_name, $file_list, $dirname = '')
	{
		include_once(DIR_LIB . 'tar/tar.php');

		$tar = new Archive_Tar($archive_name, 'gz');

		if (!is_object($tar)) {
			fn_error(debug_backtrace(), 'Archiver initialization error', false);
		}

		if (!empty($dirname) && is_dir($dirname)) {
			chdir($dirname);
			$tar->create($file_list);
			chdir(_SITE_DIR_);
		} else {
			$tar->create($file_list);
		}

		return true;
	}
	*/
	/**
	 * Extract files with Tar archiver
	 *
	 * @param $archive_name - name of the compressed file will be created
	 * @param $file_list - list of files to place into archive
	 * @param $dirname - directory, where the files should be extracted to
	 * @return bool true
	 */
	 /*
	function fn_decompress_files($archive_name, $dirname = '')
	{
		include_once(DIR_LIB . 'tar/tar.php');

		$tar = new Archive_Tar($archive_name, 'gz');

		if (!is_object($tar)) {
			fn_error(debug_backtrace(), 'Archiver initialization error', false);
		}

		if (!empty($dirname) && is_dir($dirname)) {
			chdir($dirname);
			$tar->extract('');
			chdir(_SITE_DIR_);
		} else {
			$tar->extract('');
		}

		return true;
	}
	*/

	/**
	 * Get mime type by the file name
	 *
	 * @param string $filename
	 * @return string $file_type
	 */
	function fn_get_file_type($filename)
	{
		$file_type = 'application/octet-stream';

		static $types = array (
			'zip' => 'application/zip',
			'tgz' => 'application/tgz',
			'rar' => 'application/rar',

			'exe' => 'application/exe',
			'com' => 'application/com',
			'bat' => 'application/bat',

			'png' => 'image/png',
			'jpg' => 'image/jpeg',
			'jpeg' => 'jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'swf' => 'application/x-shockwave-flash',

			'csv' => 'text/csv',
			'txt' => 'text/plain',
			'doc' => 'application/msword',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'pdf' => 'application/pdf'
		);

		$ext = substr($filename, strrpos($filename, '.') + 1);

		if (!empty($types[$ext])) {
			$file_type = $types[$ext];
		}

		return $file_type;
	}

	/**
	 * Get the EDP downloaded
	 *
	 * @param string $path path to the file
	 * @param string $filename file name to be displayed in download dialog
	 *
	 */
	function fn_get_file($filepath, $filename = '')
	{
		$fd = @fopen($filepath, 'rb');
		if ($fd) {
			$fsize = filesize($filepath);
			$ftime = date('D, d M Y H:i:s T', filemtime($filepath)); // get last modified time

			if (isset($_SERVER['HTTP_RANGE'])) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 206 Partial Content');
				$range = $_SERVER['HTTP_RANGE'];
				$range = str_replace('bytes=', '', $range);
				list($range, $end) = explode('-', $range);

				if (!empty($range)) {
					fseek($fd, $range);
				}
			} else {
				header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
				$range = 0;
			}

			if (empty($filename)) {
				// Non-ASCII filenames containing spaces and underscore characters are chunked if no locale is provided
				setlocale(LC_ALL, 'en_US.UTF8');
				$filename = basename($filepath);
			}

			// Browser bug workaround: Filenames can't be sent to IE if there is any kind of traffic compression enabled on the server side 
			//TODO: Revisar este caso
			/*
			if (USER_AGENT == 'ie') {
				if (function_exists('apache_setenv')) {
					apache_setenv('no-gzip', '1');
				}

				ini_set("zlib.output_compression", "Off");

				// Browser bug workaround: During the file download with IE, non-ASCII filenames appears with a broken encoding 
				$filename = rawurlencode($filename);
			} 
			*/
			
			header("Content-disposition: attachment; filename=\"$filename\"");
			header('Content-type: ' . $this->fn_get_file_type($filepath));
			header('Last-Modified: ' . $ftime);
			header('Accept-Ranges: bytes');
			header('Content-Length: ' . ($fsize - $range));
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private', false);

			if ($range) {
				header("Content-Range: bytes $range-" . ($fsize - 1) . '/' . $fsize);
			}

			$result = fpassthru($fd);
			if ($result == false) {
				fclose($fd);
				return false;
			} else {
				fclose($fd);
				exit;
			}
		}

		return false;
	}

	/**
	 * Rebuild $_FILES array to more user-friendly view
	 *
	 * @param string $name
	 * @return array $rebuilt rebuilt file array
	 */
	function fn_rebuid_files($name)
	{
		global $common;
		$rebuilt = array();

		if (!is_array(@$_FILES[$name])) {
			return $rebuilt;
		}

		if (isset($_FILES[$name]['error'])) {
			if (!is_array($_FILES[$name]['error'])) {
				return $_FILES[$name];
			}
		} elseif ($common->fn_is_empty($_FILES[$name]['size'])) {
			return $_FILES[$name];
		}

		foreach ($_FILES[$name] as $k => $v) {
			if ($k == 'tmp_name') {
				$k = 'path';
			}
			$rebuilt = $common->fn_array_multimerge($rebuilt, $v, $k);
		}

		return $rebuilt;
	}

	/**
	 * Recursively copy directory (or just a file)
	 *
	 * @param string $source
	 * @param string $dest
	 * @param bool $silent
	 */
	function fn_copy($source, $dest, $silent = true)
	{
		// Simple copy for a file
		if (is_file($source)) {
			if (@is_dir($dest)) {
				$dest .= '/' . basename($source);
			}
			if (filesize($source) == 0) {
				$fd = fopen($dest, 'w');
				fclose($fd);
				$res = true;
			} else {
				$res = @copy($source, $dest);
			}
			@chmod($dest, _DEFAULT_FILE_PERMISSIONS_);
			return $res;
		}

		if (!@is_dir($dest)) {
			if ($this->fn_mkdir($dest) == false) {
				return false;
			}
		}

		// Loop through the folder
		if (@is_dir($source)) {
			$dir = dir($source);
			while (false !== $entry = $dir->read()) {
				// Skip pointers
				if ($entry == '.' || $entry == '..') {
					continue;
				}

				// Deep copy directories
				if ($dest !== $source . '/' . $entry) {
					if ($this->fn_copy($source . '/' . $entry, $dest . '/' . $entry, $silent) == false) {
						return false;
					}
				}
			}

			// Clean up
			$dir->close();

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Recursively remove directory (or just a file)
	 *
	 * @param string $source
	 * @param bool $delete_root
	 * @param string $pattern
	 * @return bool
	 */
	function fn_rm($source, $delete_root = true, $pattern = '')
	{
		// Simple copy for a file
		if (is_file($source)) {
			$res = true;
			if (empty($pattern) || (!empty($pattern) && preg_match('/' . $pattern . '/', basename($source)))) {
				$res = unlink($source);
			}
			return $res;
		}

		// Loop through the folder
		if (is_dir($source)) {
			$dir = dir($source);
			while (false !== $entry = $dir->read()) {
				// Skip pointers
				if ($entry == '.' || $entry == '..') {
					continue;
				}
				if ($this->fn_rm($source . '/' . $entry, true, $pattern) == false) {
					return false;
				}
			}
			// Clean up
			$dir->close();
			return ($delete_root == true && empty($pattern)) ? @rmdir($source) : true;
		} else {
			return false;
		}
	}

	/**
	 * Get file extension
	 *
	 * @param string $filename
	 */
	function fn_get_file_ext($filename)
	{
		$i = strrpos($filename, '.');
		if ($i === false) {
			return '';
		}

		return substr($filename, $i + 1);
	}

	/**
	 * Get directory contents
	 *
	 * @param string $dir directory path
	 * @param bool $get_dirs get sub directories
	 * @param bool $get_files
	 * @param mixed $extension allowed file extensions
	 * @param string $prefix file/dir path prefix
	 * @return array $contents directory contents
	 */
	function fn_get_dir_contents($dir, $get_dirs = true, $get_files = false, $extension = '', $prefix = '', $recursive = false)
	{
		global $common;
		
		$contents = array();
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {

				// $extention - can be string or array. Transform to array.
				$extension = is_array($extension) ? $extension : array($extension);

				while (($file = readdir($dh)) !== false) {
					if ($file == '.' || $file == '..' || $file{0} == '.') {
						continue;
					}

					if ($recursive == true && is_dir($dir . '/' . $file)) {
						$contents = $common->fn_array_merge($contents, $this->fn_get_dir_contents($dir . '/' . $file, $get_dirs, $get_files, $extension, $prefix . $file . '/', $recursive), false);
					}

					if ((is_dir($dir . '/' . $file) && $get_dirs == true) || (is_file($dir . '/' . $file) && $get_files == true)) {
						if ($get_files == true && !$common->fn_is_empty($extension)) {
							// Check all extentions for file
							foreach ($extension as $_ext) {
								if (substr($file, -strlen($_ext)) == $_ext) {
									$contents[] = $prefix . $file;
									break;
								}
							}
						} else {
							$contents[] = $prefix . $file;
						}
					}
				}
				closedir($dh);
			}
		}

		asort($contents, SORT_STRING);

		return $contents;
	}

	/**
	 * Get file contents from local or remote filesystem
	 *
	 * @param string $location file location
	 * @param string $base_dir
	 * @return string $result
	 */
	function fn_get_contents($location, $base_dir = '')
	{
		$result = '';
		$path = $base_dir . $location;

		if (!empty($base_dir) && !$this->fn_check_path($path)) {
			return $result;
		}

		// Location is regular file
		if (is_file($path)) {
			$result = @file_get_contents($path);
		} else {
			return false;
		}

		return $result;
	}

	/**
	 * Write a string to a file
	 *
	 * @param string $location file location
	 * @param string $content
	 * @param string $base_dir
	 * @return string $result
	 */
	function fn_put_contents($location, $content, $base_dir = '')
	{
		$result = '';
		$path = $base_dir . $location;

		if (!empty($base_dir) && !$this->fn_check_path($path)) {
			return false;
		}

		// Location is regular file
		$result = @file_put_contents($path, $content);
		if ($result !== false) {
			@chmod($path, _DEFAULT_FILE_PERMISSIONS_);
		}
		return $result; 
	}

	/**
	 * Finds the last key in the array and applies the custom function to it.
	 *
	 * @param array $arr
	 * @param string $fn
	 * @param bool $is_first
	 */
	function fn_get_last_key(&$arr, $fn = '', $is_first = false)
	{
		if (!is_array($arr)&&$is_first == true) {
			$arr = call_user_func(array($this, $fn), $arr);
			return;
		}

		foreach ($arr as $k => $v) {
			if (is_array($v) && count($v)) {
				$this->fn_get_last_key($arr[$k], $fn);
			}
			elseif (!is_array($v)&&!empty($v)) {
				$arr[$k] = call_user_func(array($this, $fn), $arr[$k]);
			}
		}
	}

	/**
	 * Create temporary file
	 *
	 * @return temporary file
	 */
	function fn_create_temp_file()
	{
		return tempnam(_TMP_DIR_, 'ztemp');
	}

	/**
	 * Returns correct path from url "path" component
	 *
	 * @param string $path
	 * @return correct path
	 */
	function fn_get_url_path($path)
	{
		$dir = dirname($path);

		if ($dir == '.' || $dir == '/') {
			return '';
		}

		return (_IIS_ == true) ? str_replace('\\', '/', $dir) : $dir;
	}

	/**
	 * Check path to file 
	 *
	 * @param string $path
	 * @return bool
	 */
	function fn_check_path($path)
	{
		$real_path = realpath($path);
		return str_replace('\\', '/', $real_path) == $path ? true : false;
	}

	/**
	 * Gets line from file pointer and parse for CSV fields 
	 *
	 * @param handle $f a valid file pointer to a file successfully opened by fopen(), popen(), or fsockopen().
	 * @param int $length maximum line length
	 * @param string $d field delimiter
	 * @param string $q the field enclosure character
	 * @return array structured data
	 */
	function fn_fgetcsv($f, $length, $d = ',', $q = '"') 
	{
		$list = array();
		$st = fgets($f, $length);
		if ($st === false || $st === null) {
			return $st;
		}

		if (trim($st) === '') {
			return array('');
		}
		
		$st = rtrim($st, "\n\r");
		if (substr($st, -strlen($d)) == $d){
			$st .= '""';
		}
		
		while ($st !== '' && $st !== false) {
			if ($st[0] !== $q) {
				// Non-quoted.
				list ($field) = explode($d, $st, 2);
				$st = substr($st, strlen($field) + strlen($d));
			} else {
				// Quoted field.
				$st = substr($st, 1);
				$field = '';
				while (1) {
					// Find until finishing quote (EXCLUDING) or eol (including)
					preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
					$part = $p[1];
					$partlen = strlen($part);
					$st = substr($st, strlen($p[0]));
					$field .= str_replace($q . $q, $q, $part);
					if (strlen($st) && $st[0] === $q) {
						// Found finishing quote.
						list ($dummy) = explode($d, $st, 2);
						$st = substr($st, strlen($dummy) + strlen($d));
						break;
					} else {
						// No finishing quote - newline.
						$st = fgets($f, $length);
					}
				}
			}

			$list[] = $field;
		}

		return $list;
	}

	/**
	 * Wrapper for rename with chmod
	 *
	 * @param string $oldname The old name. The wrapper used in oldname must match the wrapper used in newname. 
	 * @param string $newname The new name.
	 * @param resource $context Note: Context support was added with PHP 5.0.0. For a description of contexts, refer to Stream Functions.
	 * 
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	function fn_rename($oldname, $newname, $context = null)
	{
		$result = ($context === null) ? rename($oldname, $newname) : rename($oldname, $newname, $context);
		if ($result !== false) {
			@chmod($newname, _DEFAULT_FILE_PERMISSIONS_);
		}
		return $result; 
	}

	/**
	 * Create a new filename with postfix
	 *
	 * @param string $path
	 * @param string $file
	 * @return array ($full_path, $new_filename)
	 */
	function fn_generate_file_name($path, $file)
	{
		if (!file_exists($path . $file)) {
			return array($path . $file, $file);
		}
		
		$files = $this->fn_get_dir_contents($path, false, true);
		$num = 1;
		$found = false;
		$file_ext = $this->fn_get_file_ext($file);
		$file_name = basename($path . $file, '.' . $file_ext);
		
		while (!$found) {
			$new_filename = $file_name . '_' . $num . '.' . $file_ext;
			if (!in_array($new_filename, $files)) {
				break;
			}
			
			$num++;
		}
		
		
		return array($path . $new_filename, $new_filename);
	}

}

?>