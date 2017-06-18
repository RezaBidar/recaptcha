<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_analytics extends CI_Migration {
	
	public function up()
	{
		$prefix = "aly" ;
		$table_name = $this->db->dbprefix("analytics");	
		$api_prefix = "api" ;
		$api_table = $this->db->dbprefix("api") ;
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(15) NOT NULL AUTO_INCREMENT ,
				{$prefix}_page VARCHAR(100) NULL ,
				{$prefix}_request INT DEFAULT 0 ,
				{$prefix}_accepted INT DEFAULT 0 ,
				{$prefix}_failed INT DEFAULT 0 ,
				{$prefix}_api_id INT(15) NULL ,
				CONSTRAINT aly_pk PRIMARY KEY ({$prefix}_id) ,
				CONSTRAINT aly_user_fk FOREIGN KEY ({$prefix}_api_id) REFERENCES {$api_table} ({$api_prefix}_id) ON DELETE RESTRICT ON UPDATE CASCADE   
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('analytics');
	}

}