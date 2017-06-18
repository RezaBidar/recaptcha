<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_domains extends CI_Migration {
	
	public function up()
	{
		$prefix = "dmn" ;
		$table_name = $this->db->dbprefix("domains");	
		$api_prefix = "api" ;
		$api_table = $this->db->dbprefix("api") ;
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(15) NOT NULL AUTO_INCREMENT ,
				{$prefix}_name VARCHAR(255) NULL ,
				{$prefix}_api_id INT(15) NULL ,
				CONSTRAINT dmn_pk PRIMARY KEY ({$prefix}_id) ,
				CONSTRAINT dmn_user_fk FOREIGN KEY ({$prefix}_api_id) REFERENCES {$api_table} ({$api_prefix}_id) ON DELETE RESTRICT ON UPDATE CASCADE   
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('domains');
	}

}