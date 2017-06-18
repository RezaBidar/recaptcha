<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * analytics table model
 * 
 * @package CaptchaWS       
 */
class m_analytics extends My_Model{

	const COLUMN_ID = 'aly_id' ;
	const COLUMN_PAGE = 'aly_page' ;
	const COLUMN_REQUEST = 'aly_request' ;
	const COLUMN_ACCEPTED = 'aly_accepted' ;
	const COLUMN_FAILED = 'aly_failed' ;
	const COLUMN_API_ID = 'aly_api_id' ;
	
	protected $_table_name = 'analytics';
	protected $_primary_key = 'aly_id';

	/**
	 * return analytics table of specific api 
	 * 
	 * @param  int 		$api_id 
	 * @return string         
	 */
	public function get_table($api_id)
	{
		$this->db->limit(0) ;
		$data = $this->get_by(array(self::COLUMN_API_ID => $api_id)) ;

		$thead = array('PAGE', 'REQUEST', 'FAILED', 'ACCEPTED');
		$tbody = array(self::COLUMN_PAGE, self::COLUMN_REQUEST, self::COLUMN_FAILED, self::COLUMN_ACCEPTED);
		
		$msg = "<thead>";
        $msg .= "<tr>";
        foreach ($thead as $td)
        {
            $msg .= "<td>" . $td . "</td>";
        }
        $msg .= "</tr>";
        $msg .= "</thead>";
        $msg .= "<tbody>";
    
        foreach ($data as $tr => $td)
        {
            $msg .= "<tr>";
            foreach ($tbody as $cell_name)
                $msg .= "<td>". $td->{$cell_name}."</td>";
            $msg .= "</tr>";
        }
    
        $msg .= "</tbody>";
        return $msg;
	}
}

?>