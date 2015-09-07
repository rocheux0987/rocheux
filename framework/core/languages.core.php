<?php

class languages_core {
	
	var $supported_languages = array();
	var $principal_language = "";
	var $table = "?:languages";
	var $table_languages = "?:languages_supported";
	var $langs = array();
	
	function __construct(){		
		$this->supported_languages = $this->get_supported_languages();
		$this->principal_language = "EN";
		$this->expiration_check();
		
		if (_AREA_ == _AREA_BACKEND_){
			$this->set_language();
		}
	}
	
	function get_language_by_code($lang_code){
		global $db, $languages;
		return $db->db_get_row("SELECT * FROM ".$this->table_languages." WHERE lang_code = ?s", $lang_code);
	}
	
	function get_supported_languages($descriptions = false){
		global $db;
		
		if ($descriptions){
			return $db->db_get_array("SELECT * FROM ".$this->table_languages." WHERE status = ?i ORDER BY name ASC ", 1);
		}
		
		$supported_languages_tmp = $db->db_get_hash_array("SELECT lang_code FROM ".$this->table_languages." WHERE status = ?i", "lang_code", 1);
		foreach($supported_languages_tmp as $key => $value){
			$supported_languages[] = $key;
		}
		return $supported_languages;
	}
	
	function clear_cache(){
		foreach ($this->supported_languages as $k => $v){
			$this->cache_set($v);
			$this->langs[$v] = $this->read_file($v);
		}
	}
	
	function language_exists($lang_code, $lowercase = false){
		foreach ($this->supported_languages as $k => $v){
			if ($v == $lang_code || ($lowercase == true && strtolower($v) == $lang_code)){
				return true;
			}
		}
		return false;
	}
	
	function get_language(){
		$geopip_country = $this->get_country_by_ip();
		
		if ($geopip_country == 'IE' || $geopip_country == 'GB'){
			$lang_code = 'EN';
		}elseif($geopip_country == 'AU'){
			$lang_code = 'AU';
		}elseif($geopip_country == 'US'){
			$lang_code = 'US';
		}elseif($geopip_country == 'FR'){
			$lang_code = 'FR';
		}elseif($_country == 'DE'){
			$lang_code = 'DE';
		}elseif($browser_language = $this->get_browser_language($this->supported_languages)) {
			$lang_code = $browser_language;
		}else{
			$lang_code = $this->principal_language;
		}
		return $lang_code;
	}
	
	function set_language($forced_language = false){
		
		if ($forced_language){
			$lang_code = $forced_language;
			define(_CLIENT_LANGUAGE_ , $lang_code);
			setcookie("language", _CLIENT_LANGUAGE_, time()+_COOKIE_LANG_EXPIRATION_, "/", _SITE_COOKIE_DOMAIN_);
			$_COOKIE["language"] = _CLIENT_LANGUAGE_;
			return true;
		}
		
		if (!empty($_GET["sl"]) && $this->language_exists($_GET["sl"])){
			define(_CLIENT_LANGUAGE_ , $_GET["sl"]);
			setcookie("language", _CLIENT_LANGUAGE_, time()+_COOKIE_LANG_EXPIRATION_, "/", _SITE_COOKIE_DOMAIN_);
			$_COOKIE["language"] = _CLIENT_LANGUAGE_;
			return true;
		}
		
		if (isset($_COOKIE["language"])){
			if (!$this->language_exists($_COOKIE["language"])){
				define(_CLIENT_LANGUAGE_ , $this->principal_language);
				setcookie("language", _CLIENT_LANGUAGE_, time()+_COOKIE_LANG_EXPIRATION_, "/", _SITE_COOKIE_DOMAIN_);
				$_COOKIE["language"] = _CLIENT_LANGUAGE_;
			}
			define(_CLIENT_LANGUAGE_ , $_COOKIE["language"]);
			return true;
		}
		$geopip_country = $this->get_country_by_ip();
		
		if ($geopip_country == 'IE' || $geopip_country == 'GB'){
			define(_CLIENT_LANGUAGE_ , 'EN');
		}elseif($geopip_country == 'AU'){
			define(_CLIENT_LANGUAGE_ , 'AU');
		}elseif($geopip_country == 'US'){
			define(_CLIENT_LANGUAGE_ , 'US');
		}elseif($geopip_country == 'FR'){
			define(_CLIENT_LANGUAGE_ , 'FR');
		}elseif($_country == 'DE'){
			define(_CLIENT_LANGUAGE_ , 'DE');
		}elseif($browser_language = $this->get_browser_language($this->supported_languages)) {
			define(_CLIENT_LANGUAGE_ , $browser_language);
		}else{
			define(_CLIENT_LANGUAGE_ , $this->principal_language);
		}
		setcookie("language", _CLIENT_LANGUAGE_, time()+_COOKIE_LANG_EXPIRATION_, "/", _SITE_COOKIE_DOMAIN_);
	}
	
	function get_browser_language($languages = array()){
		if (empty($languages)) {
			return false;
		}

		$browser_language = false;
		
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$preg_string = strtolower(implode('|' , $languages));
			if (preg_match("/($preg_string)+(-|;|,)?(.*)?/", $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches)) {
				$browser_language = strtoupper($matches[1]);
			}
		}

		return $browser_language;
	}
	
	function get_country_by_ip($ip = false){
		if (!$ip){
			$tmp_ip = $this->get_ip(true);
			$ip = $tmp_ip['host'];
		}
		$gi = geoip_open(_CORE_DIR_."geoip/geoip.dat", GEOIP_STANDARD);
		$code = geoip_country_code_by_addr($gi, long2ip($ip));
		geoip_close($gi);
		return $code;
	}
	
	function get_ip($return_int = false){
		$forwarded_ip = '';
		$fields = array(
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'HTTP_forwarded_ip',
			'HTTP_X_COMING_FROM',
			'HTTP_COMING_FROM',
			'HTTP_CLIENT_IP',
			'HTTP_VIA',
			'HTTP_XROXY_CONNECTION',
			'HTTP_PROXY_CONNECTION');

		$matches = array();
		foreach ($fields as $f) {
			if (!empty($_SERVER[$f])) {
				preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $_SERVER[$f], $matches);
				if (!empty($matches) && !empty($matches[0]) && $matches[0] != $_SERVER['REMOTE_ADDR']) {
					$forwarded_ip = $matches[0];
					break;
				}
			}
		}

		$ip = array('host' => $forwarded_ip, 'proxy' => $_SERVER['REMOTE_ADDR']);

		if ($return_int) {
			foreach ($ip as $k => $_ip) {
				$ip[$k] = empty($_ip) ? 0 : sprintf("%u", ip2long($_ip));
			}
		}

		if (empty($ip['host']) || !fn_is_inet_ip($ip['host'], $return_int)) {
			$ip['host'] = $ip['proxy'];
			$ip['proxy'] = $return_int ? 0 : '';
		}

		return $ip;
	}
	
	function expiration_check(){
		foreach ($this->supported_languages as $k => $v){
			if (empty($this->langs[$v])){
				$this->cache_set($v);
				$this->langs[$v] = $this->read_file($v);
			}
			$expiration = $this->read_file($v, true);
			
			if (time()>$expiration){
				$this->cache_set($v);
				$this->langs[$v] = $this->read_file($v);
			}
		}
	}
	
	function init(){
		global $smarty;
		
		foreach ($this->supported_languages as $k => $v){
			$this->cache_set($v);
			$this->langs[$v] = $this->read_file($v);
		}
	}
	
	function update($name, $value, $lang_code){
		global $db;
		$db->db_query("UPDATE ".$this->table." SET value = ?s WHERE lang_code = ?s AND name = ?s ", $value, $lang_code, $name);
		$this->cache_set($lang_code);
	}
	function update_multiple($names, $lang_code){
		global $db;
		foreach($names as $name => $value){
			$db->db_query("UPDATE ".$this->table." SET value = ?s WHERE lang_code = ?s AND name = ?s ", $value, $lang_code, $name);
		}
		$this->cache_set($lang_code);
	}
	
	function exists($name, $lang_code){
		global $db;
		$exists = $db->db_get_field("SELECT COUNT(1) FROM ".$this->table." WHERE name = ?s AND lang_code = ?s LIMIT 1", $name, $lang_code);
		if ($exists>0){
			return true;
		}
		return false;
	}
	
	function set($name, $value, $lang_code){
		global $db;
		
		if (!$this->exists($name, $lang_code)){
			foreach ($this->supported_languages as $k => $v){
				$db->db_query("INSERT INTO ".$this->table." SET value = ?s, lang_code = ?s, name = ?s", $value, $v, $name);
			}
		}else{
			return false;
		}	
		$this->cache_set();
	}
	
	function get($name, $lang_code){
		return $this->langs[$lang_code][$name];
	}
	
	function get_all($lang_code){
		return $this->langs[$lang_code];
	}
	
	function delete($name){
		global $db;
		$db->db_query("DELETE FROM ".$this->table." WHERE name = ?s", $name);
		return true;
	}
	
	function cache_set($lang_code=false){
		global $db;
		if ($lang_code){
			$this->cache_delete($lang_code);
			$langs = $db->db_get_hash_array("SELECT name, value FROM ".$this->table." WHERE lang_code = ?s", "name", $lang_code);
			$this->save_file($langs, $lang_code);
		}else{
			$this->cache_delete();
			foreach ($this->supported_languages as $k => $v){
				$langs = $db->db_get_hash_array("SELECT name, value FROM ".$this->table." WHERE lang_code = ?s", "name", $v);
				$this->save_file($langs, $v);
			}
		}
	}
	
	function cache_delete($lang_code = false){
		if ($lang_code){
			$file = _TMP_LANG_CACHE_DIR_.$lang_code.".csc";
			if (file_exists($file)){
				unlink($file);
			}
		}else{
			foreach ($this->supported_languages as $k => $v){
				$file = _TMP_LANG_CACHE_DIR_.$v.".csc";
				unlink($file);
			}
		}
		return true;
	}
	
	function save_file($value, $lang_code){
		
		if (!is_dir(_TMP_LANG_CACHE_DIR_)){
			mkdir(_TMP_LANG_CACHE_DIR_, 0700);
		}
		$file = _TMP_LANG_CACHE_DIR_.$lang_code.".csc";
		$value = $this->replace_keys($value);
		$data = serialize(array('data' => $value, 'expiration' => (time()+_TMP_LANG_CACHE_EXPIRATION_)));
		@file_put_contents($file, $data);
		@chmod($file, 0700);
	}
	
	function read_file($lang_code, $get_expiration=false){
		$file = _TMP_LANG_CACHE_DIR_.$lang_code.".csc";
		$data = unserialize(file_get_contents($file));
		if ($get_expiration){
			return $data["expiration"];
		}
		return $data["data"];
	}
	
	function replace_keys($array){
		foreach($array as $k => $v){
			$reorder_array[$k] = $v["value"];
		}
		unset($array);
		return $reorder_array;
	}
}

?>