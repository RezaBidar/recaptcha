<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * domains table model
 * 
 * @package CaptchaWS       
 */
class m_domains extends My_Model{

	const COLUMN_ID = 'dmn_id' ;
	const COLUMN_NAME = 'dmn_name' ;
	const COLUMN_API_ID = 'dmn_api_id' ;
	
	protected $_table_name = 'domains';
	protected $_primary_key = 'dmn_id';

	/**
	 * Save an array of domains
	 * @param  array 	$domains 	list of domains
	 * @param  int 		$api_id 	api id
	 * @return bool      		   	Number of rows inserted or FALSE on failure
	 */
	public function save_batch($domains , $api_id)
	{
		$data = array() ;
		if(sizeof($domains) == 0) return FALSE ;
		foreach ($domains as $key => $domain) 
		{
			$data[$key][self::COLUMN_NAME] = $domain ;
			$data[$key][self::COLUMN_API_ID] = $api_id ;
		}
		return $this->db->insert_batch($this->_table_name , $data) ;
	}

	/**
	 * Delete a batch of domains
	 *
	 * delete all IDs array or (domains array with the api_id)
	 * 
	 * @param  array 	$ids     An array of id
	 * @param  array 	$domains An array of domain
	 * @param  int 		$api_id  API id
	 */
	public function delete_batch($ids = NULL, $domains = NULL , $api_id = NULL)
	{
		if($ids !== NULL)
		{
			if(sizeof($ids) == 0) return FALSE ;
			$this->db->where_in(self::COLUMN_ID , $ids);

		}
		else if ($domains !== NULL && $api_id !== NULL)
		{
			if(sizeof($domains) == 0) return FALSE ;
			$this->db->where(self::COLUMN_API_ID , $api_id);
			$this->db->where_in(self::COLUMN_NAME , $domains);	
		}

		$this->db->limit(0);
		$this->db->delete($this->_table_name);
	}

	/**
	 * Update domain list of specific api
	 * 
	 * @param  array 	$new_domains all new domains
	 * @param  int 		$api_id      api id
	 */
	public function update_batch($new_domains, $api_id)
	{
		$old_domains_obj = $this->get_by(array(self::COLUMN_API_ID => $api_id)) ;

		$old_domains = array() ;
		$delete_id_arr = array() ; // old_domains - new_domains
		$add_arr = array() ; // new_domains - old_domains
		foreach ($old_domains_obj as $domain) 
		{
			array_push($old_domains , $domain->{self::COLUMN_NAME}) ;
			in_array($domain->{self::COLUMN_NAME} , $new_domains) OR array_push($delete_id_arr , $domain->{self::COLUMN_ID}) ;
		}

		foreach ($new_domains as $domain) 
		{
			in_array($domain, $old_domains) OR array_push($add_arr , $domain) ;
		}

		$this->delete_batch($delete_id_arr) ;
		$this->save_batch($add_arr , $api_id) ;

	}

	/**
	 * explode dash delimated domains to array
	 * @example fanavard.ir - blog.fanavard.ir - contest.fanavard.ir 
	 * @param  string $domains dash delimated domains
	 * @return array 
	 */
	public function explode_dash($domains)
	{
		$domain_array = explode('-' , $domains) ;
		$output_array = array() ;
		foreach ($domain_array as $domain) 
		{
			$domain = $this->remove_www($domain);
			$domain = str_replace(' ','',$domain);
			if(preg_match('/([a-zA-Z0-9\-_]+\.)?[a-zA-Z0-9\-_]+\.[a-zA-Z]{2,5}/',$domain))
			{
				array_push($output_array , $domain);
			}
		}
		return array_unique($output_array) ;
	}


	/**
	 * implode domains with dash
	 *
	 * first get domians from database
	 * and implode them with dash( - ) 
	 * 
	 * @param  int 		$api_id Api id
	 * @return string   	    
	 */
	public function implode_dash($api_id)
	{
		$this->db->where(self::COLUMN_API_ID , $api_id);
		$domain_array = $this->get();
		$domains = array() ;
		foreach ($domain_array as $domain) 
		{
			array_push($domains , $domain->{self::COLUMN_NAME}) ;
		}
		return implode(' - ' , $domains) ;
	}

	/**
	 * remove first www from domain
	 * @param  string $domain 
	 * @return string         
	 */
	public function remove_www($domain)
	{
		return preg_replace('#^www\.(.+\.)#i', '$1', $domain) ;
	}
}


?>