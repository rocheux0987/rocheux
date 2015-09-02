CREATE TABLE IF NOT EXISTS `rck_emails_templates_lang` (
`email_id` smallint(5) unsigned NOT NULL,
  `email_template_id` smallint(5) NOT NULL,
  `lang_code` varchar(3) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `body_html` mediumtext NOT NULL,
  `body_txt` mediumtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rck_emails_templates` (
`email_id` smallint(5) unsigned NOT NULL,
  `description` varchar(100) NOT NULL,
  `user_type` char(1) NOT NULL,
  `status` char(1) NOT NULL COMMENT 'A = Active, I = Inactive, H = Hidden'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;