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

class Units extends Restapi {
 
	public $rest_format = 'json';
	public $user =  array();

	public function __construct()
	{
		parent::__construct();
	
	}
	
	
	function index_get()
	{
	    $this->load->model('Unit_model');
	    $units=$this->Unit_model->getallunits();
	     
	    $this->response(array('status'=>'true', 'message' => 'All units','data'=>$units), 202);
	 
	    
	    
	     
	}
	
}
