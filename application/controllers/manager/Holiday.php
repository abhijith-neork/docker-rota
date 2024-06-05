<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Holiday extends CI_Controller {
	/**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() { 
        Parent::__construct();
        if ($this->session->userdata('user_type') ==2){
            $this->auth->logout();            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        //Loading models
        $this->load->model('Leave_model');
        $this->load->model('User_model');
        $this->load->helper('form');
    }
    public function index()
    {
       $this->auth->restrict('Admin.Annual Leave.View');
       $result = array();
       $user_unit_id=array();
       $result['error'] ='';
       $data=array();
       $login_user_id = $this->session->userdata('user_id');
       $user_unit_id = $this->Leave_model->getUnitIdOfUserAsArray($login_user_id);
       $this->load->helper('user');
       $this->load->view('includes/home_header',$result);
       $login_user_id = $this->session->userdata('user_id');


      $params['start_date'] = date('Y').'-'.date("m").'-'.'01';
      $params['end_date'] =date('Y-m-d'); //print_r($params); exit();


       $data['user']=$this->Leave_model->findholidaydetails($user_unit_id,$login_user_id,$params);
       /*print("<pre>".print_r($this->session->userdata,true)."</pre>");
       exit();*/
       $this->load->view("manager/leave/holidayrequest",$data);
       $jsfile['js_to_load'] = array('leave/leave.js');
       $this->load->view('includes/home_footer',$jsfile);
    }
    public function approveHoliday()
    {
      $this->auth->restrict('Admin.Annual Leave.Approve');
      $request_id = $this->input->post('holiday_id');
      $user_id = $this->input->post('user_id');
      $comment = $this->input->post('comment');
      // $request_id = $this->uri->segment(4);
      // $user_id = $this->uri->segment(5);
      $user_leave_deatils = $this->Leave_model->getUserHolidayDetails($request_id,$user_id);
      $name = $user_leave_deatils[0]['fname']." ".$user_leave_deatils[0]['lname'];
      $fromdate = $user_leave_deatils[0]['from_date'];
      $todate = $user_leave_deatils[0]['to_date'];
      $days = $user_leave_deatils[0]['days'];
      $to_email = $user_leave_deatils[0]['email'];
      $supervisor_name = $this->session->userdata('full_name');


      $comments_details = array(
        'manager_id'=>$this->session->userdata('user_id'),
        'holiday_id'=>$request_id,
        'comment'=>$comment,
        'status'=>1,
        'date'=>date('Y-m-d H:i:s'),
      );
      $save_comment_details = $this->Leave_model->insertCommentDetails($comments_details);

  		$dataform = array(
  			'status'=>1,
  			'approved_date'=>date('Y-m-d H:i:s'),
  			'approved_by'=>$this->session->userdata('user_id')
  		);
		  $result     = $this->User_model->updateholiday($request_id,$dataform);

     	$site_title = 'St Matthews Healthcare - SMH Rota';
      $admin_email=getCompanydetails('from_email');
     	$emailSettings = array(
  			'from' => $admin_email,
  			'site_title' => $site_title,
  			'site_url' => $this->config->item('base_url'),
  			'to' => $to_email,
  			'type' => 'Manager/Supervisor-approve holliday',
  			'user_name'=> $name,
  			'fromdate'=> $fromdate,
  			'todate'=>$todate,
  			'days'=>$days,
        'comments'=>$comment,
        'supervisor_name'=>$supervisor_name,
  			'subject' => 'Your Holiday request has been approved!',
		);
  		$this->load->library('parser');
  		$htmlMessage = $this->parser->parse('emails/approve', $emailSettings, true);
  		//die($htmlMessage);
  		$this->load->helper('mail');
  		sendMail($emailSettings, $htmlMessage); 
  		$this->session->set_flashdata('message', 'Holiday request approved.'); 
  		redirect('manager/holiday');
    }
    public function rejectHoliday()
    {
      $this->auth->restrict('Admin.Annual Leave.Reject');
    	$request_id = $this->input->post('holiday_id');
      $user_id = $this->input->post('user_id');
      $comment = $this->input->post('comment');


    	$user_leave_deatils = $this->Leave_model->getUserHolidayDetails($request_id,$user_id);
    	$name = $user_leave_deatils[0]['fname']." ".$user_leave_deatils[0]['lname'];
    	$fromdate = $user_leave_deatils[0]['from_date'];
    	$todate = $user_leave_deatils[0]['to_date'];
    	$days = $user_leave_deatils[0]['days'];
    	$to_email = $user_leave_deatils[0]['email'];
      $supervisor_name = $this->session->userdata('full_name');

      $comments_details = array(
        'manager_id'=>$this->session->userdata('user_id'),
        'holiday_id'=>$request_id,
        'comment'=>$comment,
        'status'=>2,
        'date'=>date('Y-m-d H:i:s'),
      );
      $save_comment_details = $this->Leave_model->insertCommentDetails($comments_details);

    	$dataform   = array(
    		'status'=>2,
    		'approved_date'=>date('Y-m-d H:i:s'),
    		'approved_by'=>$this->session->userdata('user_id')
    	);

     	$result = $this->User_model->updateholiday($request_id,$dataform);
     	$site_title = 'St Matthews Healthcare - SMH Rota';
      $admin_email=getCompanydetails('from_email');
     	$emailSettings = array(
        'from' => $admin_email,
        'site_title' => $site_title,
        'site_url' => $this->config->item('base_url'),
        'to' => $to_email,
        'type' => 'Manager/Supervisor-reject holiday',
        'user_name'=> $name,
        'fromdate'=> $fromdate,
        'todate'=>$todate,
        'days'=>$days,
        'comments'=>$comment,
        'supervisor_name'=>$supervisor_name,
        'subject' => 'Your Holiday request has been rejected!',
      );
  		$this->load->library('parser');
  		$htmlMessage = $this->parser->parse('emails/approve', $emailSettings, true);
  		//die($htmlMessage);
  		$this->load->helper('mail'); 
  		sendMail($emailSettings, $htmlMessage); 
  		$this->session->set_flashdata('message', 'Holiday request rejected.'); 
  		redirect('manager/holiday');
    }
}
?>