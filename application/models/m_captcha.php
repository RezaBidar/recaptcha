<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * captcha table model
 * 
 * @package CaptchaWS       
 */
class m_captcha extends My_Model{

	const COLUMN_ID 	= 'cp_id' ;
	const COLUMN_TIME 	= 'cp_time' ;
	const COLUMN_IP 	= 'cp_ip_address' ;
	const COLUMN_WORD 	= 'cp_word' ;
	const COLUMN_LANG 	= 'cp_lang' ;
	const COLUMN_PAGE 	= 'cp_page' ;
	const COLUMN_HASH_ID = 'cp_hash_id' ;

	protected $_table_name = 'captcha';
	protected $_primary_key = 'cp_id';

	const LANG_ENGLISH = '1' ;
	const LANG_FARSI = '2' ;

	const WORD_MAX_LENGTH = 6 ;
	const CAPTCHA_EXPIRATION = 7200 ;
	const CAPTCHA_FOLDER = './cp/' ;

	/**
	 * delete expired captcha from databse
	 */
	public function delete_expire()
	{
		$expiration = time() - self::CAPTCHA_EXPIRATION; // Two hour limit
		$this->db->where(self::COLUMN_TIME . ' < ', $expiration)->delete($this->_table_name);

	}

	/**
	 * create captcha and return captcha tag
	 *
	 * if word doesnt create then it create random word based on its language
	 * create captcha and generate endpoint user captcha form include :
	 * 		captcha hash id
	 * 	 	captcha image tag
	 * 	 	captcha refresh button
	 * 	 	captcha input text
	 * and finally insert captcha information in database
	 * 
	 * @param  	string 	$word 
	 * @param  	int 	$lang 
	 * @param 	int 	$api_id 	API id
	 * @param  	string 	$page 		which page request captcha ( for analytics table )
	 * @return 	string       
	 */
	public function get_captcha_tag($word = '', $lang = self::LANG_ENGLISH , $api_id, $page = '')
	{
		$CI =& get_instance();
		$CI->load->model('m_analytics');
		$CI->load->helper('captcha');
		$CI->load->helper('path');
		$CI->load->helper('string');
		$CI->load->model('m_captcha');

		$lang = ($lang == self::LANG_FARSI) ? $lang : self::LANG_ENGLISH ;

		$unique_id = random_string('md5');
		
		if($lang == self::LANG_FARSI)
		{
			if($word == '')
			{
				// generate word
				$word = generate_persian_str(self::WORD_MAX_LENGTH) ;
			}
			else
			{
				$word = persian_strrev($word) ;
			}
		}
		else if($lang == self::LANG_ENGLISH)
		{
			if($word == '')
			{
				$word = random_string('alnum' , self::WORD_MAX_LENGTH) ;
			}
		}
		
		
		$vals = array(
	        'word'          => $word,
	        'img_path'      => self::CAPTCHA_FOLDER,
	        'img_url'       => base_url() . self::CAPTCHA_FOLDER,
	        'font_path'     => './fonts/arial.ttf',
	        'img_width'     => '255',
	        'img_height'    => 80,
	        'expiration'	=> self::CAPTCHA_EXPIRATION,
			'word_length'	=> self::WORD_MAX_LENGTH,
	        'font_size'     => '34',
	        'colors'        => array(
	                'background' => array(100, 255, 255),
	                'border' => array(255, 255, 255),
	                'text' => array(0, 0, 0),
	                'grid' => array(255, 40, 40)
	        )
		);

		$cap = create_captcha($vals,'','','',$lang);
		$output = $cap['image'] ;
		$output .= '<br/><input type="text" class="cp-response" name="cp-response"/>' ;
		$output .= ' <a class="cp-refresh" href="#" onclick="refresh_captcha()"><img src="' . site_url('img/refresh_btn.png') . '" /></a>' ;
		$output .= '<input type="hidden" class="cp-id" name="cp-id" value="'. $unique_id .'" />' ;

		$data = array(
	        self::COLUMN_TIME  => $cap['time'],
	        self::COLUMN_IP    => $this->input->ip_address(),
	        self::COLUMN_WORD  => ($lang == self::LANG_FARSI) ? persian_strrev($cap['word']) : $cap['word'] ,
	        self::COLUMN_LANG  => $lang ,
	        self::COLUMN_PAGE  => $page ,
	        self::COLUMN_HASH_ID => $unique_id ,
		);
		$this->delete_expire();
		$this->m_captcha->save($data);

		//add to analytics database
		$a_data = array(
			m_analytics::COLUMN_API_ID => $api_id ,
			m_analytics::COLUMN_PAGE => $page	,
		) ;
		$analytics = $CI->m_analytics->get_by($a_data,TRUE) ;
		//update analytics
		if($analytics == NULL)
		{
			$a_data[m_analytics::COLUMN_REQUEST] = '1' ;
			$CI->m_analytics->save($a_data);
		}
		else
		{
			$a_data = array(m_analytics::COLUMN_REQUEST => ($analytics->{m_analytics::COLUMN_REQUEST} + 1) );
			$CI->m_analytics->save($a_data, $analytics->{m_analytics::COLUMN_ID});
		}
	

		return $output ;

	}

	/**
	 * check response is correct or not
	 *
	 * it compare user response with correct word 
	 * return true if they are same 
	 * otherwise return false
	 * and update analytics table
	 * 
	 * @param  int 		$api_id     
	 * @param  string 	$response   User response
	 * @param  string 	$captcha_id Captcha hash id
	 * @return boolean              
	 */
	public function verify($api_id ,$response , $captcha_id)
	{
		$CI =& get_instance();
		$CI->load->model('m_analytics');

		/// get captcha word from database 
		$captcha = $this->get_by(array(self::COLUMN_HASH_ID => $captcha_id) , TRUE) ;
		if($captcha == NULL)
			return NULL ;
		$word = $captcha->{self::COLUMN_WORD} ;

		if($captcha->{self::COLUMN_LANG} == self::LANG_ENGLISH)
		{
			$word = strtolower($word) ;
			$response = strtolower($response) ;
		}
		
		//add to analytics database
		$data = array(
			m_analytics::COLUMN_API_ID => $api_id ,
			m_analytics::COLUMN_PAGE => $captcha->{self::COLUMN_PAGE}	,
		) ;
		$analytics = $CI->m_analytics->get_by($data,TRUE) ;

		//delete row
		$this->delete($captcha->{self::COLUMN_ID}) ;

		if($response == $word)
		{	
			//update analytics
			if($analytics == NULL)
			{
				$data[m_analytics::COLUMN_ACCEPTED] = '1' ;
				$CI->m_analytics->save($data);
			}
			else
			{
				$data = array(m_analytics::COLUMN_ACCEPTED => ($analytics->{m_analytics::COLUMN_ACCEPTED} + 1) );
				$CI->m_analytics->save($data, $analytics->{m_analytics::COLUMN_ID});
			}
			
			return TRUE ;
		}
		else
		{
			//update analytics
			if($analytics == NULL)
			{
				$data[m_analytics::COLUMN_FAILED] = '1' ;
				$CI->m_analytics->save($data);
			}
			else
			{
				$data = array(m_analytics::COLUMN_FAILED => ($analytics->{m_analytics::COLUMN_FAILED} + 1) );
				$CI->m_analytics->save($data, $analytics->{m_analytics::COLUMN_ID});
			}

			return FALSE ;
		}
	}

	

}

?>