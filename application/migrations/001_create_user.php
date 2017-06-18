<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_user extends CI_Migration{

	public function up()
	{
		$prefix = "usr" ;
		$table_name = $this->db->dbprefix("user");
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(15) NOT NULL AUTO_INCREMENT ,
				{$prefix}_email VARCHAR(100) NULL ,
				{$prefix}_password VARCHAR(255) NOT NULL ,
				CONSTRAINT user_pk PRIMARY KEY ({$prefix}_id) ,
				UNIQUE ({$prefix}_email) 
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('user');
	}

}