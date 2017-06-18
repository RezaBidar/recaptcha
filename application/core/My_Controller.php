<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * My Base Controller
 *
 * All controller extends this controller
 * Every code whitin this class will run in all controller
 * 
 * @package  CaptchaWS
 */
class My_Controller extends CI_Controller{

	public $data = array() ; // load in layout view
	

	public function __construct()
	{
		parent::__construct();

		$this->data['c_data'] = array() ; // load in to content view

		//set language from session if exists
		// if($this->session->userdata('language'))
		// {
		// 	$config['language'] = $this->session->userdata('language');
		// }

	}
}