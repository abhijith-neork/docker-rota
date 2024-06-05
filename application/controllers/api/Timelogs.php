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
class Timelogs extends Restapi {
 
	public $rest_format = 'json';
	public $user =  array();

	public function __construct()
	{
	    parent::__construct();
	    
	}
	
	public function index()
	{
	    $this->response(array('status'=>'false', 'message' => 'Unauthorized'), 201);
	    
	}
	 
	 
	
}
