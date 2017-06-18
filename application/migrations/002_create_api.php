<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_api extends CI_Migration {
	
	public function up()
	{
		$prefix = "api" ;
		$table_name = $this->db->dbprefix("api");	
		$user_prefix = "usr" ;
		$user_table = $this->db->dbprefix("user") ;
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(15) NOT NULL AUTO_INCREMENT ,
				{$prefix}_label VARCHAR(100) NULL ,
				{$prefix}_site_key VARCHAR(100) NULL ,
				{$prefix}_secret_key VARCHAR(100) NULL ,
				{$prefix}_user_id INT(15) NULL ,
				CONSTRAINT api_pk PRIMARY KEY ({$prefix}_id) ,
				CONSTRAINT api_user_fk FOREIGN KEY ({$prefix}_user_id) REFERENCES {$user_table} ({$user_prefix}_id) ON DELETE RESTRICT ON UPDATE CASCADE   
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('api');
	}

}