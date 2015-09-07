<?php
class images{
	//
	// Get image
	//
	function fn_get_image($id, $type, $name = ''){
		global $db;
		
		if ($type == 'pet'){
			$table = '?:pets';
			$key = 'pet_id';
		}elseif ($type == 'post'){
			$table = '?:posts';
			$key = 'post_id';
		}elseif ($type == 'vet'){
			$table = '?:vets';
			$key = 'vet_id';
		}elseif($type == 'merchants'){
			$table = '?:merchants';
			$key = 'merchant_id';	
		}else{
			return false;
		}
		
		if (empty($name)){	
			$name = $db->db_get_field("SELECT image FROM $table WHERE $table.$key = ?i", $id);
		}
	
		if (empty($name)){
			return false;
		}
		
		$image_data = array();
		$image_data['name'] = $name;
		$image_data['id'] = $id;
		
		$this->fn_attach_absolute_image_paths($image_data, $type);
	
		return $image_data;
	}
	
	//
	// Attach image paths
	//
	function fn_attach_absolute_image_paths(&$image_data, $type){
	
		$path = $type . "/" . floor($image_data['id'] / _MAX_FILES_IN_DIR_);
	
		//$image_data['http_image_path'] = Registry::get('config.http_images_path') . $path . '/' . $image_name;
		$image_data['absolute_path'] = _UPLOADS_IMAGES_DIR_ . $path . '/' . $image_data['name'];
		$image_data['image_path'] = '/'._UPLOADS_IMAGES_URL_ . $path . '/' . $image_data['name'];
	
		return $image_data;
	}
	
	function fn_generate_thumbnail($type, $image_path, $width, $height = 0, $make_box = false)
	{
		global $filesystem;
		if (empty($image_path)) {
			return '';
		}
		
		if (strpos($image_path, '://') === false) {
			if (strpos($image_path, '/') !== 0) { // relative path
				$image_path = '/' . _SITE_EXTRA_DIR_ . $image_path;
			}
			$image_path = _SITE_URL_ . str_replace('/' . _SITE_EXTRA_DIR_,'',$image_path);
		}
	
		$_path = str_replace(_SITE_URL_ , '', $image_path);
	
		$image_url = explode('/', $_path);
		$image_name = array_pop($image_url);
		$image_dir = array_pop($image_url);
		$image_dir .= '/' . $width . (empty($height) ? '' : '/' . $height);
		$filename = $image_dir . '/' . $image_name;
		$real_path = htmlspecialchars_decode(_SITE_DIR_ . $_path, ENT_QUOTES);
		$th_path = htmlspecialchars_decode(_UPLOADS_THUMBNAILS_DIR_ . $type . '/' . $filename, ENT_QUOTES);
	
		if (!$filesystem->fn_mkdir(_UPLOADS_THUMBNAILS_DIR_ . $type . '/' . $image_dir)) {
			return '';
		}
	
		if (!file_exists($th_path)) {
			if ($this->fn_get_image_size($real_path)) {
				$image = $filesystem->fn_get_contents($real_path);
				$filesystem->fn_put_contents($th_path, $image);
				/* Max . replaced GD with imagick */
				$this->fn_resize_image($th_path, $th_path, $width, $height, $make_box, '#ffffff');
				#$this->fn_resize_image_imagick($th_path, $th_path, $width, $height, $make_box, '#ffffff');
				$filename_info = pathinfo($filename);
				$th_path_info = pathinfo($th_path);
				$filename = $filename_info['dirname'] . '/' . $th_path_info['basename'];
			} else {
				return '';
			}
		}
	
		return ('/'._UPLOADS_THUMBNAILS_URL_ . $type . '/'. $filename);
	}
	
	//
	// Create/Update image
	//
	function fn_update_image($image_data, $id, $type)
	{
		global $db, $filesystem;
		
		if((!empty($image_data['error']) && $image_data['error'] <> 0) || empty($image_data)){
			return false;
		}
		
		if ($type == 'pet'){
			$table = '?:pets';
			$key = 'pet_id';
		}elseif ($type == 'post'){
			$table = '?:posts';
			$key = 'post_id';
		}elseif ($type == 'vet'){
			$table = '?:vets';
			$key = 'vet_id';
		}elseif($type == 'vet_images'){
			$table = '?:vet_images';
			$key = 'vet_id';
		}elseif($type == 'foundations'){
			$table = '?:foundations';
			$key = 'user_id';
		}elseif($type == 'foundation_image'){
			$table = '?:foundation_images';
			$key = 'image_id';
		}else{
			return false;
		}
		
		$images_path = $type . '/';
	
		$_data = array();
	
		if (empty($id)) {
			return false;
		} else {
			$images_path .= floor($id / _MAX_FILES_IN_DIR_) . "/";
		}
	
		if (!$filesystem->fn_mkdir(_UPLOADS_IMAGES_DIR_ . $images_path)) {
			return false;
		}
	
		list($_data['image_x'], $_data['image_y'], $mime_type) = $this->fn_get_image_size($image_data['path']);
	
		// Get the real image type
		$ext = $this->fn_get_image_extension($mime_type);
		if (strpos($image_data['name'], '.') !== false) {
			$image_data['name'] = substr_replace($image_data['name'], $ext, strrpos($image_data['name'], '.') + 1);
		} else {
			$image_data['name'] .= '.' . $ext;
		}
	
		$fd = fopen($image_data['path'], "rb", true);
		if (!empty($fd)) {
			// Check if image path already set
			$image_path = $db->db_get_field("SELECT image FROM $table WHERE $key = ?i", $id);
	
			if (!empty($image_path)){
				// Delete image file if already exists
				$filesystem->fn_delete_file(_UPLOADS_IMAGES_DIR_ . $images_path . $image_path);
				// Clear all existing thumbnails
				$this->fn_delete_file_thumbnails($image_path, $id, $type);
			}
			
			// Generate new filename
			$image_data['name'] = substr_replace($image_data['name'], uniqid(time()) . '.', strrpos($image_data['name'], '.'), 1);
			fclose($fd);
			$_data['image_path'] = $image_data['name'];
			if ($filesystem->fn_rename($image_data['path'], _UPLOADS_IMAGES_DIR_ . $images_path . $image_data['name']) == false) {
				$filesystem->fn_copy($image_data['path'], _UPLOADS_IMAGES_DIR_ . $images_path . $image_data['name']);
				@unlink($image_data['path']);
			}
		}
	
		$_data['image'] = $image_data['name'];
	
		if (!empty($id)) {
			$db->db_query("UPDATE $table SET ?u WHERE $key = ?i", $_data, $id);
		}
	
		return $id;
	}
	
	//
	// Delete image
	//
	function fn_delete_image($id, $type)
	{
		global $db,$filesystem;
		
		if ($type == 'pet'){
			$table = '?:pets';
			$key = 'pet_id';
		}elseif ($type == 'post'){
			$table = '?:posts';
			$key = 'post_id';
		}elseif ($type == 'vet'){
			$table = '?:vets';
			$key = 'vet_id';
		}else{
			return false;
		}
		
		$path = _UPLOADS_IMAGES_DIR_ . $type . '/';
	
		$path .= floor($image_id / _MAX_FILES_IN_DIR_) . "/";
	
		$_image_file = $db->db_get_field("SELECT image FROM $table WHERE $key = ?i", $id);
	
		if (!empty($_image_file)) {
			$filesystem->fn_delete_file($path . $_image_file);
		}
		$dir_content = $filesystem->fn_get_dir_contents($path, true, true);
		if (empty($dir_content)) {
			$filesystem->fn_rm($path);
		}
	
		$db->db_query("UPDATE $table SET image = '' WHERE $key = ?i", $id);
	
		$this->fn_delete_file_thumbnails($_image_file, $id, $type);
			
		return true;
	}
	
	//
	// Delete thumbnails for a file.
	//
	function fn_delete_file_thumbnails($filename, $id, $type, $dir = '', $recursive = true)
	{
		global $filesystem;
		
		if (empty($dir)) {
			$dir = realpath(_UPLOADS_THUMBNAILS_DIR_ . '/' . $type);
		}
		
		$filesystem->fn_rm(realpath($dir. '/' . $filename));
		
		if ($recursive) {
			$content = $filesystem->fn_get_dir_contents($dir);
			if (!empty($content)) {
				foreach ($content as $subdir) {
					$this->fn_delete_file_thumbnails($filename, $id, $type, $dir . '/' . $subdir);
				}
			}
		}
		
		return true;
	}
	
	// ----------- Utility functions -----------------
	
	//
	// Resize image
	//
	function fn_resize_image($src, &$dest, $new_width = 0, $new_height = 0, $make_box = false, $bg_color = '#ffffff', $save_original = false)
	{
	
		global $filesystem;
		
		static $notification_set = false;
		//static $gd_settings = array();
	
		if (file_exists($src) && !empty($dest) && (!empty($new_width) || !empty($new_height)) && extension_loaded('gd')) {
	
			$img_functions = array(
				'jpg' => function_exists('imagejpeg'),
				'png' => function_exists('imagepng'),
				'gif' => function_exists('imagegif'),
			);
			
			//if (empty($gd_settings)) {
			//	$gd_settings = fn_get_settings('Thumbnails');
			//}
	
			$dst_width = $new_width;
			$dst_height = $new_height;
	
			list($width, $height, $mime_type) = $this->fn_get_image_size($src);
			if (empty($width) || empty($height)) {
				return false;
			}
	
			if ($width < $new_width) {
				$new_width = $width;
			}
			if ($height < $new_height) {
				$new_height = $height;
			}
	
			if ($dst_height == 0) { // if we passed width only, calculate height
				$new_height = $dst_height = ($height / $width) * $new_width;
	
			} elseif ($dst_width == 0) { // if we passed height only, calculate width
				$new_width = $dst_width = ($width / $height) * $new_height;
	
			} else { // we passed width and height, limit image by height! (hm... not sure we need it anymore?)
				if ($new_width * $height / $width > $dst_height) {
					$new_width = $width * $dst_height / $height;
				}
				$new_height = ($height / $width) * $new_width;
				if ($new_height * $width / $height > $dst_width) {
					$new_height = $height * $dst_width / $width;
				}
				$new_width = ($width / $height) * $new_height;
			}
	
			$w = number_format($new_width, 0, ',', '');
			$h = number_format($new_height, 0, ',', '');
	
			$ext = $this->fn_get_image_extension($mime_type);
	
			if (!empty($img_functions[$ext])) {
				if ($make_box) {
					$dst = imagecreatetruecolor($dst_width, $dst_height);
				} else {
					$dst = imagecreatetruecolor($w, $h);
				}
				if (function_exists('imageantialias')) {
					imageantialias($dst, true);
				}
			} elseif ($notification_set == false) {
				//$msg = fn_get_lang_var('error_image_format_not_supported');
				//$msg = str_replace('[format]', $ext, $msg);
				//fn_set_notification('E', fn_get_lang_var('error'), $msg);
				//$notification_set = true;
				return false;
			}
	
			if ($ext == 'gif' && $img_functions[$ext] == true) {
				$new = imagecreatefromgif($src);
			} elseif ($ext == 'jpg' && $img_functions[$ext] == true) {
				$new = imagecreatefromjpeg($src);
			} elseif ($ext == 'png' && $img_functions[$ext] == true) {
				$new = imagecreatefrompng($src);
			} else {
				return false;
			}
	
			// Set transparent color to white
			// Not sure that this is right, but it works
			// FIXME!!!
			// $c = imagecolortransparent($new);
	
			list($r, $g, $b) = $this->fn_parse_rgb($bg_color);
			$c = imagecolorallocate($dst, $r, $g, $b);
			//imagecolortransparent($dst, $c);
			if ($make_box) {
				imagefilledrectangle($dst, 0, 0, $dst_width, $dst_height, $c);
				$x = number_format(($dst_width - $w) / 2, 0, ',', '');
				$y = number_format(($dst_height - $h) / 2, 0, ',', '');
			} else {
				imagefilledrectangle($dst, 0, 0, $w, $h, $c);
				$x = 0;
				$y = 0;
			}
			imagecopyresampled($dst, $new, $x, $y, 0, 0, $w, $h, $width, $height);
	
			//if ($gd_settings['convert_to'] == 'original') {
				$convert_to = $ext;
			//} else {
			//	$convert_to = $gd_settings['convert_to'];
			//}
			
			if (empty($img_functions[$convert_to])) {
				foreach ($img_functions as $k => $v) {
					if ($v == true) {
						$convert_to = $k;
						break;
					}
				}
			}
	
			$pathinfo = pathinfo($dest);
			$new_filename = $pathinfo['dirname'] . '/' . basename($pathinfo['basename'], empty($pathinfo['extension']) ? '' : '.' . $pathinfo['extension']);
			
			// Remove source thumbnail file
			if (!$save_original) {
				$filesystem->fn_rm($src);
			}
	
			switch ($convert_to) {
				case 'gif':
					$new_filename .= '.gif';
					imagegif($dst, $new_filename);
					break;
				case 'jpg':
					$new_filename .= '.jpg';
					imagejpeg($dst, $new_filename, 100/*$gd_settings['jpeg_quality']*/);
					break;
				case 'png':
					$new_filename .= '.png';
					imagepng($dst, $new_filename);
					break;
			}
			
			$dest = $new_filename;
			@chmod($dest, _DEFAULT_FILE_PERMISSIONS_);
	
			return true;
		}
	
		return false;
	}
	
	//
	// Check supported GDlib formats
	//
	function fn_check_gd_formats()
	{
	
		if (function_exists('imagegif')) {
			$avail_formats['gif'] = 'GIF';
		}
		if (function_exists('imagejpeg')) {
			$avail_formats['jpg'] = 'JPEG';
		}
		if (function_exists('imagepng')) {
			$avail_formats['png'] = 'PNG';
		}
	
		return $avail_formats;
	}
	
	//
	// Get image extension by MIME type
	//
	function fn_get_image_extension($image_type)
	{
		static $image_types = array (
			'image/gif' => 'gif',
			'image/pjpeg' => 'jpg',
			'image/jpeg' => 'jpg',
			'image/png' => 'png',
		);
	
		return isset($image_types[$image_type]) ? $image_types[$image_type] : false;
	}
	
	//
	// Getimagesize wrapper
	// Returns mime type instead of just image type
	// And doesn't return html attributes
	function fn_get_image_size($file)
	{
		global $filesystem;
		
		// File is url, get it and store in temporary directory
		if (strpos($file, '://') !== false) {
			$tmp = $filesystem->fn_create_temp_file();
	
			if ($filesystem->fn_put_contents($tmp, $filesystem->fn_get_contents($file)) == 0) {
				return false;
			}
	
			$file = $tmp;
		}
	
		list($w, $h, $t, $a) = @getimagesize($file);
	
		if (empty($w)) {
			return false;
		}
	
		$t = image_type_to_mime_type($t);
	
		return array($w, $h, $t);
	}
	
	function fn_parse_rgb($color)
	{
		$r = hexdec(substr($color, 1, 2));
		$g = hexdec(substr($color, 3, 2));
		$b = hexdec(substr($color, 5, 2));
		return array($r, $g, $b);
	}
	
	function fn_convert_relative_to_absolute_image_url($image_path)
	{
		return _SITE_URL_ . $image_path;
	}
	
	/* Max . replaced GD with imagick */
	function fn_resize_image_imagick($src, &$dest, $new_width = 0, $new_height = 0, $make_box = false, $bg_color = '#ffffff', $save_original = false)
	{
		global $filesystem;
		static $notification_set = false;
		//static $gd_settings = array();
	
		if (file_exists($src) && !empty($dest) && (!empty($new_width) || !empty($new_height))) {
			
			$dst_width = $new_width;
			$dst_height = $new_height;
			
			list($width, $height, $mime_type) = $this->fn_get_image_size($src);
			if (empty($width) || empty($height)) {
				return false;
			}
	
			$img_functions = array(
				'png' => true,
				'jpg' => true,
				'gif' => true
			);
			
			if ($width < $new_width) {
				$new_width = $width;
			}
			if ($height < $new_height) {
				$new_height = $height;
			}
	
			if ($dst_height == 0) { // if we passed width only, calculate height
				$new_height = $dst_height = ($height / $width) * $new_width;
	
			} elseif ($dst_width == 0) { // if we passed height only, calculate width
				$new_width = $dst_width = ($width / $height) * $new_height;
	
			} else { // we passed width and height, limit image by height! (hm... not sure we need it anymore?)
				if ($new_width * $height / $width > $dst_height) {
					$new_width = $width * $dst_height / $height;
				}
				$new_height = ($height / $width) * $new_width;
				if ($new_height * $width / $height > $dst_width) {
					$new_height = $height * $dst_width / $width;
				}
				$new_width = ($width / $height) * $new_height;
			}
	
			$w = number_format($new_width, 0, ',', '');
			$h = number_format($new_height, 0, ',', '');
	
			$ext = $this->fn_get_image_extension($mime_type);
	
			if (empty($img_functions[$ext]) && $notification_set == false) 
			{
				/*$msg = fn_get_lang_var('error_image_format_not_supported');
				$msg = str_replace('[format]', $ext, $msg);
				fn_set_notification('E', fn_get_lang_var('error'), $msg);
				$notification_set = true;*/
				return false;
			}
			
			if ($make_box) 
			{
				$last_width = $dst_width;
				$last_height = $dst_height;
			} 
			else 
			{
				$last_width = $w;
				$last_height = $h;
			}
			
			$pathinfo = pathinfo($dest);
			$new_filename = $pathinfo['dirname'] . '/' . basename($pathinfo['basename'], empty($pathinfo['extension']) ? '' : '.' . $pathinfo['extension']);
			$new_filename.=".".$pathinfo['extension'];
			
			// Remove source thumbnail file
			$imagen = new Imagick($src);
			if (!$save_original) 
			{
				$filesystem->fn_rm($src);
			}
			
			$imagen->thumbnailImage($last_width, $last_height, true, true);
	//		$imagen->unsharpMaskImage(0 ,3 , 1,5 , 0.0196);
			$imagen->writeImage($new_filename);
			
			$dest = $new_filename;
			@chmod($dest, _DEFAULT_DIR_PERMISSIONS_);
	
			return true;
		}
	
		return false;
	}
}
?>
