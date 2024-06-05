<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User
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
class User extends Restapi {
 
	public $rest_format = 'json';
	public $user =  array();

	public function __construct()
	{
		parent::__construct();
	
	}
	public function index_post()
	{
	    $this->response(array('status'=>'false', 'message' => 'Unauthorized'), 201);
	    
	}
	
	public function login_post()
	{
	    $jsonArray = array();
	    $jsonArray = json_decode(file_get_contents('php://input'),true);
 
	    $tmp = array();
	    $params = array();
	    $data = array('user_id'=>"0");
	    
	    $tmp['email'] =  trim($jsonArray['user']['email']);
	    $tmp['password'] = md5($jsonArray['user']['password']);
	 
	    $params = array('email' => trim($tmp['email']),'password' => trim($tmp['password']));
	    
	    if ($this->auth->login($params))
	    {
 
	        if (in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
 
	        {
	            $data = array('user_id'=>$this->session->userdata('user_id'),'user_name'=>$this->session->userdata('full_name'));
	            
	            $this->response(array('status'=>'true', 'message' => 'Authorized. Welcome, '.$this->session->userdata('full_name'),'data'=>$data), 200);
	        }
	        else{
	            $this->response(array('status'=>'false', 'message' => 'Unauthorized','data'=>$data), 201);
	        }
	        
	    }else{
	        $this->response(array('status'=>'false', 'message' => 'Invalid username or password','data'=>$data), 202);
	    }
	    
	    
	     
	}
	public function timelog_post()
	{
	    $jsonArray = array();
	    $jsonArray = json_decode(file_get_contents('php://input'),true);
	    
	    $tmp = array();
	    $params = array();
	    
	    
	    $user_id =  trim($jsonArray['timelog']['user_id']);
	    $device_id = trim($jsonArray['timelog']['device_id']);
	    $unit_id = trim($jsonArray['timelog']['unit_id']);
	    $date = trim($jsonArray['timelog']['date']);
	    $status = trim($jsonArray['timelog']['status']);
	    $time=0;
	    $data = array('user_id'=>$user_id);
	    
	    if($status==1){
	        $time_from = trim($jsonArray['timelog']['time']);
	        $time=1;
	    }
	    if($status==2){
	        $time_to = trim($jsonArray['timelog']['time']);
	        $time=1;
	    }
	    
	    $params = array('user_id' => $user_id,'device_id' => $device_id,
	        'unit_id' => $unit_id, 'date' => $date,'status' => $status,'time_from' => $time_from
	        ,'time_to' => $time_to,'creation_date'=>date('Y-m-d H:i:s'));
	    
	    if ($user_id=='' ||  $device_id=='' ||  $unit_id=='' ||  $date=='' ||  $status=='' || $time==0)
	    {
	        $this->response(array('status'=>'false', 'message' => 'Invalid details','data'=>$data), 202);
	        
	        
	    }else{
	        $this->load->model('Timelog_model');
	        $this->Timelog_model->insert($params);
	        // free array
	        unset($params);
	        $this->response(array('status'=>'true', 'message' => 'Time Logged','data'=>$data), 200);
	        
	    }
	    
	}
	
}
