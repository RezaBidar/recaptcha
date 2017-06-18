<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user table model
 * 
 * @package CaptchaWS       
 */
class m_user extends My_Model{


	const COLUMN_ID = 'usr_id' ;
	const COLUMN_EMAIL = 'usr_email' ;
	const COLUMN_PASSWORD = 'usr_password' ;
	
	protected $_table_name = 'user';
	protected $_primary_key = 'usr_id';

	/**
	 * login user
	 *
	 * get email and password in data array
	 * and check if user exist then set session
	 * @param  array 	$data 	it contains 
	 * @return bollean     		if data was correct and user login return true otherwise return false
	 */
	public function login($data)
	{
		$where = array(
			self::COLUMN_EMAIL => $data['email'],
			self::COLUMN_PASSWORD => $this->hash($data['password']),	
		);
		$user = $this->get_by($where,true);
		if($user !== NULL)
		{
			$session_data = array(
				"email" => $user->{self::COLUMN_EMAIL},
				"user_id" => $user->{self::COLUMN_ID} ,
				"logged_in" => TRUE ,
			);
			$this->session->set_userdata($session_data);
			return TRUE;
		}
		
		
	}

	/**
	 * save user to database
	 * @param  array 	$data_arr 	it contains email and password
	 * @return int      		    user id
	 */
	public function signup($data_arr)
	{
		var_dump($this->hash($data_arr['password'])) ;
		$data = array(
			self::COLUMN_EMAIL => $data_arr['email'] ,
			self::COLUMN_PASSWORD => $this->hash($data_arr['password']) 
		);
		return $this->save($data) ;
	}
	
	/**
	 * unset session data
	 */
	public function logout()
	{
		$session_data = array('email', 'logged_in', 'user_id');
		$this->session->unset_userdata($session_data);
	}
	

	/**
	 * check user logged in or not
	 * @return boolean 
	 */
	public function loggedin()
	{
		return (bool) $this->session->userdata('logged_in');
	}
	
	/**
	 * add salt and hash it
	 * @param  string $input 
	 * @return string        
	 */
	public function hash($input)
	{
		return md5($input . config_item('encryption_key'));
	}


}

?>