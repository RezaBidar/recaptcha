<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * word table model
 * 
 * @package CaptchaWS       
 */
class m_words extends My_Model{


	const COLUMN_ID = 'wrd_id' ;
	const COLUMN_WORD = 'wrd_word' ;
	const COLUMN_LANG = 'wrd_lang' ;
	const COLUMN_API_ID = 'wrd_api_id' ;
	
	protected $_table_name = 'words';
	protected $_primary_key = 'wrd_id';

	const LANG_PERSIAN = 2 ;
	const LANG_ENGLISH = 1 ;
	const WORD_MAX_LENGTH = 6 ;
	const WORD_MIN_LENGTH = 4 ;

	/**
	 * get from database
	 *
	 * it override parent get function with modifying in limitation 
	 * @param  int $id     Id of row
	 * @param  boolean $single Result be sigle row or not
	 * @return arrya         
	 */
	public function get($id=NULL, $single=NULL)
	{
		$this->db->limit(0);
		return parent::get($id,$single);
	}

	/**
	 * save an array of words
	 * @param  array $words list of words
	 * @param  int $api_id  api id
	 * @param  int $lang word language code . for example : 1 
	 * @return bool         Number of rows inserted or FALSE on failure
	 */
	public function save_batch($words , $api_id , $lang)
	{
		$data = array() ;
		if(sizeof($words) == 0) return FALSE ;
		foreach ($words as $key => $word) 
		{
			$data[$key][self::COLUMN_WORD] = $word ;
			$data[$key][self::COLUMN_LANG] = $lang ;
			$data[$key][self::COLUMN_API_ID] = $api_id ;
		}
		return $this->db->insert_batch($this->_table_name , $data) ;
	}

	/**
	 * delete a batch of data
	 *
	 * delete $ids array or ($words with the $api_id) from database
	 * @param  array 	$ids 	An array contain id of rows
	 * @param  array 	$words  An array contain words
	 * @param  int 		$api_id API id
	 */
	public function delete_batch($ids = NULL, $words = NULL , $api_id = NULL)
	{
		if($ids != NULL)
		{
			if(sizeof($ids) == 0) return FALSE ;
			$this->db->where_in(self::COLUMN_ID , $ids);
			$this->db->limit(0);
			$this->db->delete($this->_table_name);	

		}
		else if ($words != NULL && $api_id != NULL)
		{
			if(sizeof($words) == 0) return FALSE ;
			$this->db->where(self::COLUMN_API_ID , $api_id);
			$this->db->where_in(self::COLUMN_WORD , $words);	
			$this->db->limit(0);
			$this->db->delete($this->_table_name);	
		}
		

	}

	/**
	 * Update word list of an API 
	 *
	 * @param  array 	$new_words API word list
	 * @param  int 		$api_id    API Id
	 * @param  int 		$lang      Which language should be updated
	 */
	public function update_batch($new_words, $api_id , $lang)
	{
		$old_words_obj = $this->get_by(array(
			self::COLUMN_API_ID => $api_id ,
			self::COLUMN_LANG => $lang ,
			)
		) ;

		$old_words = array() ;
		$delete_id_arr = array() ; // old_words - new_words
		$add_arr = array() ; // new_words - old_words
		foreach ($old_words_obj as $word) 
		{
			array_push($old_words , $word->{self::COLUMN_WORD}) ;
			in_array($word->{self::COLUMN_WORD} , $new_words) OR array_push($delete_id_arr , $word->{self::COLUMN_ID}) ;
		}

		foreach ($new_words as $word) {
			in_array($word, $old_words) OR array_push($add_arr , $word) ;
		}

		$this->delete_batch($delete_id_arr) ;
		$this->save_batch($add_arr , $api_id , $lang) ;

	}

	/**
	 * explode dash delimated words to array
	 *
	 * Notice : only word with valid length will be add to array ;
	 * @example word1 - word2 - word3
	 * @param string $words dash delimated words
	 * @return array 
	 */
	public function explode_dash($words)
	{
		$word_array = explode('-' , $words) ;
		$output_array = array() ;
		foreach ($word_array as $word) 
		{

			$word = str_replace(' ','',$word);
			if(size_limit($word,self::WORD_MAX_LENGTH,self::WORD_MIN_LENGTH))
				array_push($output_array, $word);
		}
		return array_unique($output_array) ;
	}

	/**
	 * implode words with dash
	 *
	 * first get words from database
	 * and implode them with dash( - ) 
	 * 
	 * @param  int 		$api_id Api id
	 * @param  string 	$lang   Persian or English
	 * @return string   	    
	 */
	public function implode_dash($api_id , $lang)
	{
		$this->db->where(self::COLUMN_API_ID , $api_id);
		$this->db->where(self::COLUMN_LANG , $lang);
		$word_array = $this->get();
		$words = array() ;
		foreach ($word_array as $word) 
		{
			array_push($words , $word->{self::COLUMN_WORD}) ;
		}
		return implode(' - ' , $words) ;
	}

	/**
	 * return a random word from user database
	 * 
	 * @param  int 		$api_id API id
	 * @param  int 		$lang   Persian Or English
	 * @return string         	Word
	 */
	public function db_random_word($api_id,$lang = self::LANG_ENGLISH)
	{
		
		$this->db->where(self::COLUMN_API_ID , $api_id) ;
		$this->db->where(self::COLUMN_LANG , $lang) ;
		$this->db->order_by(self::COLUMN_ID, 'RANDOM');
		$this->db->limit(1);
		$word = $this->get(NULL,TRUE) ;
		if($word == NULL)
			return NULL ;
		return $word->{self::COLUMN_WORD} ;
	}

}

 ?>