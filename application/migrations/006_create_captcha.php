<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_captcha extends CI_Migration{

	

	public function up()
	{
		$table_name = $this->db->dbprefix('captcha') ;
		$query = 
			'CREATE TABLE '. $table_name .' (
		        cp_id bigint(13) unsigned NOT NULL auto_increment,
		        cp_time int(10) unsigned NOT NULL,
		        cp_ip_address varchar(45) NOT NULL,
		        cp_word varchar(20) NOT NULL,
		        cp_page varchar(255) NOT NULL,
		        cp_hash_id varchar(32) NOT NULL,
		        cp_lang INT(2) DEFAULT 1,
		        PRIMARY KEY `cp_id` (`cp_id`),
		        KEY `cp_word` (`cp_word`),
		        KEY `cp_hash_id` (`cp_hash_id`)
			) ENGINE=INNODB
			DEFAULT CHARSET = utf8  
			DEFAULT COLLATE = utf8_unicode_ci
				  ;'
		;
		$this->db->query($query);
		

	}

	public function down()
	{
		$table_name = $this->db->dbprefix('captcha') ;
		$query = 'DROP TABLE IF EXISTS `' . $table_name . '` ' ;
		$this->db->query($query) ;
	}
}