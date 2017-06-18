<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Panel_Controller{

	function __construct() 
	{
		parent::__construct() ;

		$this->load->model('m_user');
		$this->load->model('m_api');
		$this->load->model('m_domains');
		$this->load->model('m_words');
		$this->load->model('m_analytics');
		
		$this->load->helper('btform') ;
		
		 	
	} 

	public function index()
	{
		redirect('panel/dashboard/api_list');
	}

	/**
	 * show api list of user
	 */
	public function api_list()
	{
		
		// get a table
		$this->data['c_data']['table'] = $this->m_api->get_table($this->session->userdata('user_id')) ;
		$this->data['content_view'] = 'api_list' ;
		$this->load->view('layouts/panel_layout', $this->data);

	}

	/**
	 * show api information + analytics table
	 * @param  int 	$id 	API id
	 */
	public function api_show($id)
	{
		if(!is_numeric($id)) 
		{
			return $this->load->view('errors/html/error_404' , 
				array(
					'heading' => '404 Page Not Found' ,
					'message' => '<p>The page you requested was not found.</p>'
					)
				);
		}

		$api = $this->m_api->get_information($id) ;
		if($api === NULL OR $this->session->userdata('user_id') !== $api['user_id']) die('Call Administrator') ;

		$this->data['c_data']['api'] = $api ;
		$this->data['c_data']['table'] = $this->m_analytics->get_table($id) ;
		$this->data['content_view'] = 'api_show' ;
		$this->load->view('layouts/panel_layout', $this->data);


	}


	/**
	 * Add api form
	 */
	public function api_add()
	{
		

		//form validation rules
		$rule = array(
	            "label" => array( "field" => "label" , "label" => $this->lang->line('app_form_label') , "rules" => "required|trim" ),
	            "domains" => array( "field" => "domains" , "label" => $this->lang->line('app_form_domains') , "rules" => "trim" ) ,
	            "en-words" => array( "field" => "en-words" , "label" => $this->lang->line('app_form_english_words') , "rules" => "trim" ) ,
	            "fa-words" => array( "field" => "fa-words" , "label" => $this->lang->line('app_form_persian_words') , "rules" => "trim" ) ,
      		);

		$this->form_validation->set_rules($rule) ;
		if($this->form_validation->run() == TRUE)
		{

			$data = array(
					m_api::COLUMN_LABEL => $this->input->post('label') ,
					m_api::COLUMN_SITE_KEY => $this->m_api->generate_key() ,
					m_api::COLUMN_SECRET_KEY => $this->m_api->generate_key() ,
					m_api::COLUMN_USER_ID => $this->session->userdata('user_id') ,
			);

			$en_words = $this->m_words->explode_dash($this->input->post('en-words')) ;
			$fa_words = $this->m_words->explode_dash($this->input->post('fa-words')) ;
			$domains = $this->m_domains->explode_dash($this->input->post('domains')) ;

			$this->db->trans_start() ;
			//add api
			$api_id = $this->m_api->save($data);
			//add domains
			if(count($domains) > 0) $this->m_domains->save_batch($domains , $api_id) ;

			//add words
			if(count($en_words) > 0) $this->m_words->save_batch($en_words , $api_id , m_words::LANG_ENGLISH) ;
			if(count($fa_words) > 0) $this->m_words->save_batch($fa_words , $api_id , m_words::LANG_PERSIAN) ;

			$this->db->trans_complete();
			$this->session->set_flashdata('success', 'Api added successfully');

			redirect('panel/dashboard/api_show/'.$api_id);
		}
		else
		{
			$this->session->set_flashdata('error', validation_errors('<span>','</span>'));
			
		}
		//show add_api form
		$this->data['content_view'] = 'api_add' ;
		$this->load->view('layouts/panel_layout', $this->data);
	}

	/**
	 * Edit Api form
	 * @param  int $id   Api id
	 */
	public function api_edit($id='')
	{
		if(!is_numeric($id)) 
		{
			return $this->load->view('errors/html/error_404' , 
				array(
					'heading' => '404 Page Not Found' ,
					'message' => '<p>The page you requested was not found.</p>'
					)
				);
		}

		//form validation rules
		$rule = array(
	            "label" => array( "field" => "label" , "label" => $this->lang->line('app_form_label') , "rules" => "required|trim" ),
	            "domains" => array( "field" => "domains" , "label" => $this->lang->line('app_form_domains') , "rules" => "trim" ) ,
	            "en-words" => array( "field" => "en-words" , "label" => $this->lang->line('app_form_english_words') , "rules" => "trim" ) ,
	            "fa-words" => array( "field" => "fa-words" , "label" => $this->lang->line('app_form_persian_words') , "rules" => "trim" ) ,
      		);

		$this->form_validation->set_rules($rule) ;
		if($this->form_validation->run() == TRUE)
		{

			$data = array(
					m_api::COLUMN_LABEL => $this->input->post('label') ,
					m_api::COLUMN_USER_ID => $this->session->userdata('user_id') ,
			);

			$en_words = $this->m_words->explode_dash($this->input->post('en-words')) ;
			$fa_words = $this->m_words->explode_dash($this->input->post('fa-words')) ;
			$domains = $this->m_domains->explode_dash($this->input->post('domains')) ;

			$this->db->trans_start() ;
			//add api
			$api_id = $this->m_api->save($data , $id);
			
			//add domains
			$this->m_domains->update_batch($domains , $api_id) ;

			//add words
			$this->m_words->update_batch($en_words , $api_id , m_words::LANG_ENGLISH) ;
			$this->m_words->update_batch($fa_words , $api_id , m_words::LANG_PERSIAN) ;

			$this->db->trans_complete();
			
			$this->session->set_flashdata('success', 'Api updated successfully');

			redirect('panel/dashboard/api_show/' . $id);
		}
		else
		{
			$this->session->set_flashdata('error', validation_errors('<span>','</span>'));	
		}

		$api = $this->m_api->get_information($id) ;
		if($api === NULL OR $this->session->userdata('user_id') !== $api['user_id']) 
			die('Call Administrator') ;

		//edit api form
		$this->data['content_view'] = 'api_edit' ;
		$this->data['c_data']['api'] = $api ;
		$this->load->view('layouts/panel_layout', $this->data);
	}


	/**
	 * Delete api
	 *
	 * it removes all word and domians of this api
	 * and next remove api
	 * 
	 * @param  int 	$id 	Api id
	 */
	public function api_delete($id='')
	{
		if(!is_numeric($id)) 
		{
			return $this->load->view('errors/html/error_404' , 
				array(
					'heading' => '404 Page Not Found' ,
					'message' => '<p>The page you requested was not found.</p>'
					)
				);
		}

		$api = $this->m_api->get_information($id) ;
		if($api === NULL OR $this->session->userdata('user_id') !== $api['user_id']) 
			die('Call Administrator') ;

		// delete and redirect to api_list
		$this->db->trans_start() ;

		$this->m_words->delete(array(m_words::COLUMN_API_ID => $id)) ;
		$this->m_domains->delete(array(m_domains::COLUMN_API_ID => $id)) ;
		$this->m_analytics->delete(array(m_analytics::COLUMN_API_ID => $id)) ;
		$this->m_api->delete($id) ;

		$this->db->trans_complete() ;
		
		$this->session->set_flashdata('success', 'Api deleted successfully');

		redirect('panel/dashboard/api_list') ;
	}

	


}