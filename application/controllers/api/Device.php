<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Device
 *
 * This is  user interaction methods you could use
 * for get supervisor login 
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Sivaprasad, Neork Technologies
 * @link		 
 */

require APPPATH.'/controllers/api/Restapi.php';

class Device extends Restapi {
 
	public $rest_format = 'json';
	public $user =  array();

	public function __construct()
	{
		parent::__construct();
	
	}
	
	
	function assignunit_post()
	{
	    $jsonArray = json_decode(file_get_contents('php://input'),true);
 
	    $tmp = array();
	    $data = array();
	    $params = array();
	    
	    $tmp['device_id'] = trim($jsonArray['device']['device_id']);
	    $tmp['unit_id'] = trim($jsonArray['device']['unit_id']);
	    $tmp['user_id'] = trim($jsonArray['device']['user_id']);
	    
	    $params = array('device_id' => $tmp['device_id'],'user_id' => $tmp['user_id'],'unit_id' => $tmp['unit_id'],'creation_date'=>date('Y-m-d H:i:s'));
 
	    if ($tmp['device_id']=='' ||  $tmp['unit_id']=='' || $tmp['user_id']=='')
	    {
	        $this->response(array('status'=>'false', 'message' => 'Invalid details','data'=>$data), 202);
	        
	        
	    }else{
	        $this->load->model('Device_model');
	        $this->Device_model->insert($params);
	        // free array
	        unset($tmp);
	        unset($params);
	        $this->response(array('status'=>'true', 'message' => 'Device added','data'=>$data), 200);
	        
	    }
	    
	    
	     
	}
	
}
