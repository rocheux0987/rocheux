<?php

class session{
	
	var $session_lifetime = "";
	
	public function set_handlers(){
		
		session_set_save_handler(
			array($this, 'open'),
			array($this, 'close'),
			array($this, 'read'),
			array($this, 'write'),
			array($this, 'destroy'),
			array($this, 'gc')
		);
		
	}
	
	public function init(){
		$this->set_params();
		$this->set_handlers();
		$this->start();
		register_shutdown_function('session_write_close');
	}
	
	public function set_params(){
		$host = _SITE_COOKIE_DOMAIN_;
		
		if (strpos($host, '.') !== false) {
			// Check if host has www prefix and remove it
			$host = strpos($host, 'www.') === 0 ? substr($host, 3) : '.' . $host;
		} else {
			// For local hosts set this to empty value
			$host = '';
		}

		ini_set('session.cookie_lifetime', _FRONTEND_INACTIVIY_MAXTIME_);
		if (!preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $host, $matches)) {
			ini_set('session.cookie_domain', $host);
		}
		ini_set('session.cookie_path', '/');
		ini_set('session.gc_divisor', 10); // probability is 10% that garbage collector starts
	}
	
	public function get_session_id(){
		return session_id();
	}

	public function set_session_id($id){
		return session_id($id);
	}

	public function reset_session_id($id = null){
		if ($id == $this->get_session_id()) {
			return $id;
		}

		session_destroy();
		// session_destroy kills our handlers,
		// http://bugs.php.net/bug.php?id=32330
		// so we set them again
		$this->set_handlers();
		if (!empty($id)) {
			$this->set_session_id($id);
		}

		$this->start();
		return $this->set_session_id();
	}
	
	public function start(){
		// Force transfer session id to cookies if it passed via url
		if (!empty($_REQUEST[_FRONTEND_SESSION_NAME_])) {
			$this->set_session_id($_REQUEST[_FRONTEND_SESSION_NAME_]);
		}

		session_name(_FRONTEND_SESSION_NAME_);
		session_start();

		// Validate session
		if (_FRONTEND_SESSIONS_VALIDATE_ == true) {
			$validator_data = $this->get_validator_data();
			if (!isset($_SESSION['_validator_data'])) {
				$_SESSION['_validator_data'] = $validator_data;
			} else {
				if ($_SESSION['_validator_data'] != $validator_data) {
					//$this->session_regenerate_id();
					$_SESSION = array();
				}
			}
		}
	}
	
	public function get_validator_data(){
		global $languages;
		$data = array();
		
		if (_FRONTEND_SESSIONS_VALIDATE_IP_ == true) {
			$ip = $languages->get_ip();
			$data['ip'] = $ip['host'];
		}

		if (_FRONTEND_SESSIONS_VALIDATE_UA_ == true) {
			$data['ua'] = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		}

        return $data;
    }
	
	public function open(){
		$this->session_lifetime = _FRONTEND_INACTIVIY_MAXTIME_;
	}
	
	public function close(){
		return true;
	}
	
	public function read($id){
		global $db;
		$session_id = mysqli_real_escape_string($db->db_link,$id);
		$session_table = $this->get_session_table($session_id);
		$session = $db->db_get_row('SELECT * FROM '.$session_table.' WHERE session_id = ?s AND area = ?s', $session_id, _AREA_);

		if (empty($session)){
			return false;
		}elseif($session['expiry'] < time()){
			$db->db_query('DELETE FROM '.$session_table.' WHERE session_id = ?s AND area = ?s', $session_id, _AREA_);
			$session = $this->decode($session['data']);
			return $this->encode(array ('settings' => !empty($session['settings']) ? $session['settings'] : array()));
		}else{
			return $session['data'];
		}

		return false;
	}
	
	public function write($id, $data, $area = _AREA_){
		global $db;
		static $saved = false;

		if ($saved == true) {
			return true;
		}

		// if used not by standard session handler, can accept data in array, not in serialized array
		if (is_array($data)) {
			$data = $this->encode($data);
		}

		// new session-expire-time
		$new_expire = time() + $this->session_lifetime;
		$session_table = $this->get_session_table($id);
		
		#$db->db_query("REPLACE INTO ".$session_table." ?e", $_row);
		$db->db_query("REPLACE INTO ".$session_table." (session_id, area, expiry,data) VALUES(?s, ?s, ?i, ?s)", $id, $area, $new_expire, $data);
		$saved = true;
		return $saved;
	}
	
	public function destroy($id){
		global $db;
		$session_table = $this->get_session_table($id);
		$db->db_query("DELETE FROM ".$session_table." WHERE session_id = ?s", $id);
		return true;
	}
	
	public function clean(){
		global $db;
		
		$timestamp_expired = time() - $this->session_lifetime;
		$session_tables = $this->get_session_tables();
		
		foreach($session_tables as $k => $v){
			$db->query("DELETE FROM ".$v." WHERE expiry < ?i", $timestamp_expired);
		}
		
		return true;
	}
	
	private function decode($string){
		$data = array ();
	    $vars = preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    	for ($i = 0; !empty($vars[$i]); $i++) {
    		$data[$vars[$i++]] = unserialize($vars[$i]);
    	}
    	return $data;
	}
	
	private function encode($data){

		$raw = '' ;
		$line = 0 ;
		$keys = array_keys($data) ;

		foreach ($keys as $key) {
			$value = $data[$key] ;
			$line++;

			$raw .= $key . '|' . serialize($value);

		}

		return $raw ;
	}
	
	public function get_session_table($sess_id){
		if (substr($sess_id,0,1) <= '7'){
			$session_table = '?:sessions_1';
		}elseif (substr($sess_id,0,1) >= '8' && substr($sess_id,0,1) <= 'f'){
			$session_table = '?:sessions_2';
		}elseif (substr($sess_id,0,1) >= 'g' && substr($sess_id,0,1) <= 'n'){
			$session_table = '?:sessions_3';
		}else{
			$session_table = '?:sessions_4';
		}
		return $session_table;
	}
	
	public function get_session_tables(){
		return array("sessions_1", "sessions_2", "sessions_3", "sessions_4");
	}
	
	public function gc($max_lifetime){
		return true;
	}
	
}

?>