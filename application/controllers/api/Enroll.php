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

class Enroll extends Restapi {
 
	public $rest_format = 'json';
	public $user =  array();

	public function __construct()
	{
		parent::__construct();
	
	}
	 	
	function staff_post()
	{
	    try{
	    $jsonArray = json_decode(file_get_contents('php://input'),true);
 
	    $tmp = array();
	    $data = array('payroll_id'=>"0");
	    $params = array();
	    $response_status=1;
	    $message='Sorry somthing went wrong.';
	    $tmp['device_id'] = trim($jsonArray['enroll']['device_id']);
	    $tmp['unit_id'] = trim($jsonArray['enroll']['unit_id']);
	    $tmp['user_id'] = trim($jsonArray['enroll']['user_id']);
	    $tmp['payroll_id'] = trim($jsonArray['enroll']['payroll_id']);
	    $tmp['thumbnail'] = trim($jsonArray['enroll']['thumbnail']);
  	    
 	    $params = array('thumbnail' => $tmp['thumbnail'],'updation_date'=>date('Y-m-d H:i:s'));
 	    $code= 200;
 	    $rstatus =true;
 	    
 	    if ($tmp['thumbnail']=='' ||  $tmp['payroll_id']=='')
	    {
	        //mail('contactsiva.spc@gmail.com', 'ENROLL ERROR',  "ENROLL Invalid details");
 	        $message = 'Invalid details, please try again.';
	        $response_status=0;
	        $rstatus=false;
	       
	    }else{
	        
	        $this->load->model('User_model');
	        $this->load->model('Payroll_model');
	        //check if user with payrollid
	         $paramsf['payroll_id']= '';
	         //$params['unit_id']= $tmp['unit_id'];
	         $paramsf['id']=$tmp['payroll_id'];
	        $result = $this->Payroll_model->finduserwithunit($paramsf);
	        //print count($result) ."".$response_status;
	        if(count($result)>0 && $response_status==1){
 	           $status = $this->User_model->updatethumbnail($tmp['payroll_id'],$params);
	           unset($params);
	           unset($data);
	        
	           $user_name= $result[0]['fname']." ".$result[0]['lname'];
	           $data = array('payroll_id'=>$tmp['payroll_id'],'user_id'=>$result[0]['id']
	               ,'user_name'=>$user_name
	              
	           );
	           unset($tmp);
	          // mail('contactsiva.spc@gmail.com', 'ENROLL SUCCESS',  "ENROLL Enrolled".$status);
 	           $message= $user_name.', enrolled successfully.';
	           $rstatus=true;
	           $code= 200;
	        }
	        else{
	           // mail('contactsiva.spc@gmail.com', 'ENROLL ERROR',  "ENROLL Invalid payroll id");
 	            if($response_status==0){
	                $message = $message;
	            }
	            else{
	                $message ='Invalid user id, please check your user id.';
	            }
	            
	            $data = array('payroll_id'=>$tmp['payroll_id']);
	            $rstatus=false;
	            $code= 202;
	        }
	        
	       
	    }
	    $this->response(array('status'=>$rstatus, 'message' => $message,'data'=>$data), $code);
	    unset($data);
	    } catch (Exception $e) {
 	        mail('contactsiva.spc@gmail.com', 'Rota Log',  "Exception ".$e->getMessage());
	        $this->response(array('status'=>'false', 'message' => "Enroll Exception ".$e->getMessage(),'data'=>$data), 202);
	        
	    }
	     
	}
	function sync_post()
	{
	    try{
	        $jsonArray = json_decode(file_get_contents('php://input'),true);
	        
	        $tmp = array();
	        $data = array('payroll_id'=>"0");
	        $params = array();
	        $response_status=1;
	        $message='Successfully synced.';
	        $device_id = trim($jsonArray['enroll']['device_id']);
	        $tmp['users'] = $jsonArray['enroll']['users'];
	    
  	        $code= 200;
	        $rstatus =true;
	        
/* 	        if(count($tmp['users'])==0){
	            
	            $enrolldata[]['enroll']= array("device_id"=> "sync",
	                "unit_id"=> '',
	                "user_id"=> '',
	                "payroll_id"=>'',
	                "thumbnail"=>'');
	            $message='Already synced.';
	        }
	        else{ */
	            
	        $this->load->model('User_model');
	        $thumbnails = $this->User_model->getUsersThmbnails($tmp);
	        
	     /*    "enroll": {
	        "device_id": "1",
	        "unit_id": "7",
	        "user_id": "743",
	        "payroll_id":"743",
	        "thumbnail":"22"
	            
	        } */
	        $i=0;
	        foreach($thumbnails as $usr){
	            
	         /*    $enrolldata[]['enroll']= array("device_id"=> "sync",
	                "unit_id"=> $usr['unit_id'],
	                "user_id"=> $usr['user_id'],
	                "payroll_id"=>$usr['payroll_id'],
	                "thumbnail"=>json_decode($usr['thumbnail'])); */
	            
	            $enrolldata[]['enroll']= array("id"=> $i,"device_id"=> "sync",
	                "unit_id"=> $usr['unit_id'],
	                "user_id"=> $usr['user_id'],
	                "payroll_id"=>$usr['user_id'],
	                "fullname"=>$usr['fname']." ".$usr['lname'],	                
	                "thumbnail"=>$usr['thumbnail'],
	                "pass_enable"=>$usr['pass_enable'],
	                "password"=>$usr['app_pass']
	            );
	            $i++;
	        }
	        //}
	        $this->response(array('status'=>$rstatus, 'message' => $message,'data'=>$enrolldata), $code);
	        unset($data);
	    } catch (Exception $e) {
	        mail('contactsiva.spc@gmail.com', 'Rota Sync',  "Exception ".$e->getMessage());
	        $this->response(array('status'=>'false', 'message' => "Enroll Exception ".$e->getMessage(),'data'=>$data), 202);
	        
	    }
	    
	}
	function getusername_post()
	{
	    try{
	        $jsonArray = json_decode(file_get_contents('php://input'),true);
	        
	        $tmp = array();
	        $data = array('payroll_id'=>"0");
	        $params = array();
	        $response_status=1;
	        $message='User found';
	        $device_id = trim($jsonArray['enroll']['device_id']);
	        $id = $jsonArray['enroll']['id'];
	        
	        $code= 200;
	        $rstatus =true;
	        
	        
	        
	        $this->load->model('User_model');
	        $users = $this->User_model->getSingleUser($id);
	        if(count($users)>0){
	        $name = $users[0]['fname']." ".$users[0]['lname'];
	        $namearr = array("full_name"=>$name);
	        }
	        else{
	            $code= 202;
	            $message='User not found';
	            $namearr = array("full_name"=>'User not found.');
	        }
	        $this->response(array('status'=>$rstatus, 'message' => $message,'data'=>$namearr), $code);
	        unset($data);
	    } catch (Exception $e) {
	        mail('contactsiva.spc@gmail.com', 'Rota Sync',  "Exception ".$e->getMessage());
	        $this->response(array('status'=>'false', 'message' => "Enroll Exception ".$e->getMessage(),'data'=>$data), 202);
	        
	    }
	    
	}
	function getthumb_post()
	{
	    try{
	        $jsonArray = json_decode(file_get_contents('php://input'),true);
	        
	        $tmp = array();
	        $data = array('payroll_id'=>"0");
	        $params = array();
	        $response_status=1;
	        $message='User found';
	        $device_id = trim($jsonArray['enroll']['device_id']);
	        $id = trim($jsonArray['enroll']['user_id']);
	        
	        $code= 200;
	        $rstatus =true;
	        
	        
	        
	        $this->load->model('User_model');
	        $users = $this->User_model->getSingleUserThumb($id);
	   
	        if(count($users)>0){
	            $thumbnail= $users[0]['thumbnail'];
	            $message='User found';
	            $namearr = array("thumbnail"=>$thumbnail);
	            $rstatus =false;
	        }
	        else{
	            $code= 202;
	            $message='User not found';
	            $namearr = array("thumbnail"=>'User not found.');
	        }
	        $this->response(array('status'=>$rstatus, 'message' => $message,'data'=>$namearr), $code);
	        unset($data);
	    } catch (Exception $e) {
	        mail('contactsiva.spc@gmail.com', 'No thumb',  "Exception ".$e->getMessage());
	        $this->response(array('status'=>'false', 'message' => "Enroll Exception ".$e->getMessage(),'data'=>$data), 202);
	        
	    }
	    
	}
}
