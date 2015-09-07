<?php
class EmailBuilder
{

	private $site_labels = [];
	
	private $db;
	
	private $prim_table = '?:emails_templates';
	
	private $sec_table = '?:emails_templates_lang';
	
	private $collection;
	
	private $l10n;
	
	private $prim;
	
	private $sec;
	
	private $template;

	public function __construct()
	{
		global $db;
		
		$this->db = $db;
		
		$this->site_labels['module'] = "E-mails";

		if($_POST) {
				
		} else {
			switch($_GET['act']) {
				case 'edit':
					$this->site_labels['action'] = 'Edit';
					$this->site_labels['action_key'] = '';
					break;
				case 'add':
					$this->site_labels['action'] = 'Add';
					$this->site_labels['action_key'] = '';
					break;
				default:
					$this->site_labels['action'] = 'Main';
					$this->site_labels['action_key'] = '#';
					break;
			}
		}
	}
	
	public function cprint($var)
	{
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	public function siteLabels($key)
	{
		if(!is_null($this->site_labels[$key])) {
			return $this->site_labels[$key];
		}
		return false;
	}
	
	private function getTemplateIds($result)
	{ 
		foreach($result as $single) {
			$ids[] = $single['email_id'];
		}		
		return $ids;
	}
	
	private function getLocalizedTexts($template_id, $l10n)
	{
		foreach($this->l10n as $single) {
			if($single['email_template_id'] === $template_id) {
				return $single;
			}
		}		
		return null;
	}

	public function collectTemplates($l10n = 'en')
	{
		$temp = $this->collection =  $this->db->db_query(
					"SELECT * FROM  $this->prim_table WHERE status IN ('A','I')", 
					$params
				);
		
		unset($this->collection);

		$this->l10n = $this->db->db_query(
						"SELECT email_id, email_template_id, subject FROM $this->sec_table WHERE email_template_id IN (?a)", 
						$this->getTemplateIds($temp)
						);
		
		foreach($temp as $single) {			
			$this->collection[$single['email_id']] = $single;
			$this->collection[$single['email_id']]['text'] = $this->getLocalizedTexts($single['email_id'], $l10n);
		}
		
		return $this->collection;
	}
	
	public function getEdit($id)
	{
		$template['main'] = $this->db->driver_db_fetch_array(
								$this->db->db_query(
										"SELECT * FROM $this->prim_table WHERE email_id = ?i", 
										$id
								)
							);
		$l10n = $this->db->db_query(
					"SELECT lang_code FROM " . $this->sec_table . " WHERE email_template_id = ?i", 
					$id
				);
		
		foreach($l10n as $single) {
			$template['l10n_keys'][] = $single;
		}
		
		return $template;
	}
	
	public function getL10n($id, $l10n)
	{
		return $this->db->driver_db_fetch_array(
					$this->db->db_query(
							"SELECT * FROM $this->sec_table WHERE email_template_id = ?i AND lang_code = ?s", 
							$id, 
							$l10n
					)
				);
	}
	
	public function updateTemplateDesc($postParams)
	{
		$this->cprint($postParams); die();
		$this->db->db_query(
				"UPDATE $this->prim_table SET description = ?s, status = ?s WHERE email_id = ?i", 
				$postParams['tp_desc'], 
				$postParams['tp_status'], 
				$postParams['tp_id']
		);
	}
	
	public function updateL10n($postParams)
	{
		$prev = $this->db->db_query(
					"SELECT email_id FROM $this->sec_table WHERE email_template_id = ?i AND lang_code = ?s", 
					$postParams['id'], 
					$postParams['l10n']
				);
		
		if($prev->num_rows === 0) {
			$this->db->db_query(
					"INSERT INTO $this->sec_table (email_template_id, lang_code, subject, body_html, body_txt) 
					VALUES (?i, ?s, ?s, ?s, ?s)", 
					$postParams['id'], 
					$postParams['l10n'], 
					$postParams['tp_l10n_subj'], 
					$postParams['tp_l10n_html'], 
					"a"
			);
		} else {
			$this->db->db_query(
					"UPDATE $this->sec_table SET 
					subject = ?s, 
					body_html = ?s,
					body_txt = ?s 
					WHERE email_template_id = ?i AND lang_code = ?s", 
					$postParams['tp_l10n_subj'], 
					$postParams['tp_l10n_html'], 
					$postParams['tp_l10n_txt'],
					$postParams['id'], 
					$postParams['l10n']
			);
		}
	}
	
	public function hideTemplate($id)
	{
		$this->db->db_query(
			"UPDATE $this->prim_table SET status = 'H' WHERE email_id = ?i", 
			$id
		);
	}
	
	public function addTemplate($postParams, $l10n)
	{
		$prim = $this->db->db_query(
					"INSERT INTO $this->prim_table  (description, status) VALUES (?s, ?s)", 
					$postParams['tp_desc'], 
					$postParams['tp_status']
				);
		
		$sec = $this->db->db_query(
					"INSERT INTO $this->sec_table (email_template_id, lang_code, subject, body_html, body_txt) 
					VALUES (?i, ?s, ?s, ?s, ?s)", 
					$prim, 
					$l10n, 
					$postParams['tp_l10n_subj'], 
					$postParams['tp_l10n_html'], 
					$postParams['tp_l10n_txt']
				);
		
		return $prim;
	}
	
	public function setTemplate($id, $l10n)
	{
		$this->prim = $this->db->db_get_array(
						"SELECT * FROM $this->prim_table WHERE email_id = ?i", 
						$id
					);
		
		$this->sec = $this->db->db_get_array(
						"SELECT * FROM $this->sec_table WHERE email_template_id = ?i AND lang_code = ?s", 
						$id, 
						$l10n
					);
		
		$this->sec = $this->sec[0];
		
		$this->template = $this->sec['body_html'];
		
		return $this;
	}
	
	public function replace($key, $value)
	{		
		$this->template = str_ireplace("{$key}", $value, $this->template);
		
		return $this;
	}
	
	public function getSubject()
	{
		if($this->sec['subject']) {
			return $this->sec['subject'];
		}
		
		return null;
	}
	
	public function getTemplate()
	{
		return $this->template;
	}
}