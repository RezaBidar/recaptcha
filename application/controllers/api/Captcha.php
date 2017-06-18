<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha extends CI_Controller{

	public function __Construct()
	{
		parent::__construct();

		$this->load->model('m_api');
		$this->load->model('m_domains');
		$this->load->model('m_words');
		$this->load->model('m_analytics');
		$this->load->model('m_captcha');
		$this->m_captcha->delete_expire();
	}

	/**
	 * Send captcha form for endpoint user
	 *
	 * it use in ajax
	 */
	public function html()
	{

		if(!isset($_SERVER['HTTP_REFERER'])) 
			die('');

		$word_method = $this->input->post('word_method');
		$site_key = $this->input->post('site_key');
		$lang_str = $this->input->post('lang');
		$lang = (($lang_str == 'fa')? m_words::LANG_PERSIAN : m_words::LANG_ENGLISH) ;

		//get pure domain
		$domain = $this->m_domains->remove_www(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)); 
		$page = $domain . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ;

		// $domain = 'localhost' ;
		// $page = 'localhost/reading' ;
		// $site_key = 'add318d55c3eb8a03ee0149f719b4abb' ;

		$api_id = $this->m_api->site_access($site_key, $domain) ;
		if($api_id == NULL)
			die ;

		$word = '' ;
		if($word_method == 'self')
		{
			$word = $this->m_words->db_random_word($api_id , $lang) ;
		}

		if($api_id != NULL){
			echo $this->m_captcha->get_captcha_tag($word,$lang,$api_id,$page);
		}
		
	}

	/**
	 * Validate user captcha response
	 *
	 * if user response was correct return "yes"
	 * otherwise return no
	 */
	public function siteverify()
	{

		$secret_key = $this->input->get('secret');
		$response = $this->input->get('response');
		$captcha_id = $this->input->get('id');

		$api_id = $this->m_api->secret_access($secret_key) ;
		if($api_id == NULL)
			die ;

		$result = $this->m_captcha->verify($api_id, $response , $captcha_id) ;
		
		
		if($result)
		{
			echo 'yes' ;
		}
		else 
		{
			echo 'no' ;
		}
	}

	/**
	 * java script
	 * this javascript load in user browser
	 * and get captcha from captcha/html controller
	 */
	public function js()
	{
		echo "
			function refresh_captcha()
			{
				var site_key_val  = $('.cp-div').data('sitekey');
				var lang_val  = $('.cp-div').data('lang');
				var word_method_val  = $('.cp-div').data('method');
				$.ajax({
							url : '". site_url('api/captcha/html'). "' ,
							method : 'POST' ,
							data : { site_key: site_key_val, lang : lang_val , word_method : word_method_val} ,
							success : function(result){
								$('.cp-div').html(result) ;
							}
				}) ;
			}
			$(document).ready(function(){ 
				refresh_captcha();	
			});" ;
	}

}