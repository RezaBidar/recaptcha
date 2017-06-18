<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * api table model
 * 
 * @package CaptchaWS       
 */
class m_api extends My_Model{

	const COLUMN_ID = 'api_id' ;
	const COLUMN_LABEL = 'api_label' ;
	const COLUMN_SITE_KEY = 'api_site_key' ;
	const COLUMN_SECRET_KEY = 'api_secret_key' ;
	const COLUMN_USER_ID = 'api_user_id' ;
	
	protected $_table_name = 'api';
	protected $_primary_key = 'api_id';


	/**
	 * generate random key for secret and sit key
	 * @return string md5 hash key
	 */
	public function generate_key()
	{
		$CI =& get_instance();
		$CI->load->helper('string') ;
		return random_string('unique') ;
	}

	/**
	 * return information of specific api
	 * @param  int 		$api_id 
	 * @return array         
	 */
	public function get_information($api_id)
	{
		$CI =& get_instance();
		$CI->load->model('m_domains') ;
		$CI->load->model('m_words') ;
		$api = $this->get($api_id , TRUE) ;
		if($api == NULL) return NULL ;
		
		$domains = $CI->m_domains->implode_dash($api_id) ;
		$en_words = $CI->m_words->implode_dash($api_id , m_words::LANG_ENGLISH) ;
		$fa_words = $CI->m_words->implode_dash($api_id , m_words::LANG_PERSIAN) ;

		$data = array(
				'label' => $api->{self::COLUMN_LABEL} ,
				'site_key' => $api->{self::COLUMN_SITE_KEY} , 
				'secret_key' => $api->{self::COLUMN_SECRET_KEY} , 
				'user_id' => $api->{self::COLUMN_USER_ID} , 
				'domains' => $domains , 
				'en_words' => $en_words , 
				'fa_words' => $fa_words , 
			);

		return $data ;
	}

	/**
	 * generate a table of current user APIs
	 * 
	 * @param  int 	 	$user_id 
	 * @return string          
	 */
	public function get_table($user_id){
		$this->db->where(self::COLUMN_USER_ID , $user_id) ;
		$data = $this->get() ;
		if(sizeof($data) == 0)
		{
			return '<div class="well">No API created yet. <a href="'. site_url('panel/dashboard/api_add') .'"> click here to create your first api </a></div>';
		}

		$thead = array('Label');
		$tbody = array(self::COLUMN_LABEL);
		$select_url = 'panel/dashboard/api_show' ;
		$update_url = 'panel/dashboard/api_edit' ; 
		$delete_url = 'panel/dashboard/api_delete' ;
		
		$msg = "<thead>";
        $msg .= "<tr>";
        foreach ($thead as $td)
        {
            $msg .= "<td>" . $td . "</td>";
        }
        if($select_url != NULL)
            $msg .= "<td>Details</td>";
        if($update_url != NULL)
            $msg .= "<td>Edit</td>";
        if($delete_url != NULL)
            $msg .= "<td>Remove</td>";
        $msg .= "</tr>";
        $msg .= "</thead>";
        $msg .= "<tbody>";
    
        foreach ($data as $tr => $td)
        {
            $msg .= "<tr>";
            foreach ($tbody as $cell_name)
                $msg .= "<td>". $td->{$cell_name}."</td>";
            if($select_url != NULL)
                $msg .= "<td><a href=\"". site_url($select_url . '/' . $td->{self::COLUMN_ID}) ."\" class=\"glyphicon glyphicon-ok-sign enter_icon \" ></a></td>";
            if($update_url != NULL)
                $msg .= "<td><a href=\"". site_url($update_url . '/' . $td->{self::COLUMN_ID}) ."\" class=\"glyphicon glyphicon-pencil edit_icon \" ></a></td>";
            if($delete_url != NULL)
                $msg .= "<td><a href=\"". site_url($delete_url . '/' . $td->{self::COLUMN_ID}) ."\" onclick=\"return confirm('Are you sure ?'); \" class=\"glyphicon glyphicon-remove remove_icon\" ></a></td>";
            $msg .= "</tr>";
        }
    
        $msg .= "</tbody>";
        return $msg;
	}


	/**
	 * Site key validation
	 * 
	 * check this site key with this domain is in database or not
	 * Notice : localhost is free to use all valid site keys
	 * 
	 * @param  string $site_key 
	 * @param  string $domain   
	 * @return complex			if sitekey be valid return api_id otherwise return null          
	 */
	public function site_access($site_key, $domain)
	{
		$CI =& get_instance();
		$CI->load->model('m_domains');
		//it always access localhost without being in domain list
		if($domain != 'localhost'){
			$this->db->join($CI->m_domains->get_table_name() , m_domains::COLUMN_API_ID . ' = ' . self::COLUMN_ID);
			$this->db->where(m_domains::COLUMN_NAME , $domain);
		}
		$this->db->where(self::COLUMN_SITE_KEY , $site_key);
		$api = $this->get(NULL ,TRUE);
		if($api != NULL) 
			return $api->{self::COLUMN_ID} ;
		else 
			return NULL ;
	}	

	/**
	 * Secret key validation
	 *
	 * check this secret key is in database of not
	 * if secret key be valid it return api id otherwise return false
	 * 
	 * @param  string 	$secret_key 
	 * @return complex              
	 */
	public function secret_access($secret_key)
	{
		$api = $this->get_by(array(self::COLUMN_SECRET_KEY => $secret_key) , TRUE) ;
		if($api != NULL)
			return $api->{self::COLUMN_ID} ;
		else
			return NULL ;
	}

	
}

?>