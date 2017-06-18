<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_words extends CI_Migration {
	
	public function up()
	{
		$prefix = "wrd" ;
		$table_name = $this->db->dbprefix("words");	
		$api_prefix = "api" ;
		$api_table = $this->db->dbprefix("api") ;
		// lang > 1 = english , 2 = farsi
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(15) NOT NULL AUTO_INCREMENT ,
				{$prefix}_word VARCHAR(100) NULL ,
				{$prefix}_lang INT(2) DEFAULT 1 , 
				{$prefix}_api_id INT(15) NULL , 
				CONSTRAINT wrd_pk PRIMARY KEY ({$prefix}_id) ,
				CONSTRAINT wrd_user_fk FOREIGN KEY ({$prefix}_api_id) REFERENCES {$api_table} ({$api_prefix}_id) ON DELETE RESTRICT ON UPDATE CASCADE   
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('words');
	}

}