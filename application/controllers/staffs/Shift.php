<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Shift extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct();        
        $this->load->model('Unit_model');
        $this->load->model('Shift_model');
        $this->load->model('Rotaschedule_model');
        $this->load->model('User_model');
        $this->load->helper('form');
        $this->load->helper('name');
    }
    public function openShift(){
      /*$staff_name = "Chinchu";//$value['name'];
            $user_id = 294;//$value['user_id'];
            $unit_for_name = "tse";
            $supervisor_name = "siva";
            $parent_shift_name = "TH";
            $date = "2019-11-12";
            $unit_for_name = "lllllll";
            $parent_shift_id = 16;
            $unit_id = 6;
            $unit_for_id = 3;
            $actual_date = "2019-11-12";
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $subject = $site_title. ' Open slot available at '.$unit_for_name.' , '.$parent_shift_name.' , '.$date;
            $body = 'There is an open shift that matches your availability schedule, if you are interested please accept the shift ' .$parent_shift_name. ' on '.$unit_for_name.' at '.$date; 
            $note = 'Note: The first one who accepts will be allocated to the shift';
            $recover_url = $this->config->item('base_url').'staffs/shift/processRequest/'. $user_id.'/'.$parent_shift_id.'/'.$unit_id.'/'.$unit_for_id.'/'.$actual_date;
            $details = $user_id.'/'.$parent_shift_id.'/'.$unit_id.'/'.$unit_for_id.'/'.$actual_date;
            $admin_email=$this->config->item('email');
            $emailSettings = array();
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => "chinchugopi89@gmail.com",
                'type' => 'Shift Allocation',
                'staff_name' => $staff_name,
                'supervisor_name'=>$supervisor_name,
                'subject' => $subject,
                'data' => $body,
                'note' => $note,
                'content_title'=>'We are glad to have you!',
                'recover_url' => $recover_url,
                'user_id'=>$user_id,
                'details' => $details,
                'base_url' => $this->config->item('base_url')
            );
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/open_shift_request', $emailSettings, true);
            
            die($htmlMessage);
            exit();*/


  		$response = array();
  		$result = array();
  		$this->load->view('includes/staffs/header_rota');
  		$user_id  = $this->uri->segment(4);
  		$shift_id = $this->uri->segment(5);
  		$user_unit_id  = $this->uri->segment(6);
  		$main_unitid = $this->uri->segment(7);
  		$date     = $this->uri->segment(8);
  		$response['shift_data'] = $this->Shift_model->findshift($shift_id);
  		$response['unit_data']  = $this->Unit_model->findunit($main_unitid);
  		$response['user_unit_id']  = $user_unit_id;
  		$response['date']  = $date;
  		$response['user_id']  = $user_id;
  		$this->load->view("staffs/rota/openshift",$response);
  		$result['js_to_load'] = array('staff_rota/openshift.js');
  		$this->load->view('includes/staffs/footer_rota',$result);
    }
    public function processRequest(){
      $action = $this->uri->segment(10);
      if($action == 2){
        $this->rejectRequest();
      }else if($action == 1){
        $this->acceptRequest();
      }else{
        //nthng
      }
    }
    public function expireTimeAndRotaExistValidation($params){
      $now = date("Y-m-d H:i:s");
      $shift_details = $this->Shift_model->findshift($params['shift_id']);
      $shift_start_time = $shift_details[0]['start_time'];
      $start = $params['date'].' '.$shift_start_time;
      $requested_time = date('Y-m-d H:i:s',strtotime('+6 hours',strtotime($start)));
      if($now > $requested_time){
        return array(
          'status'  => false,
          'message' => 'Sorry, the link has expired.'
        );
      }else{
        $user_has_rota = $this->Rotaschedule_model->checkUserHasRota($params);
        if(count($user_has_rota) > 0){
          return array(
            'status'  => false,
            'message' => "You have already been assigned a shift on this date"
          );
        }else{
          return array(
            'status'  => true,
            'message' => ""
          );
        }
      }
    }
	public function acceptRequest(){
        $params = array();
        $result = array();
        $params['unit_id']=$this->uri->segment(7);
        $params['date']=ltrim($this->uri->segment(8),"--");
        $params['shift_id']=$this->uri->segment(5);
        $params['unit_type']=$this->uri->segment(9);
        $params['user_id']=$this->uri->segment(4);
        $params['user_unit_id']=$this->uri->segment(6);
        $data_result = $this->expireTimeAndRotaExistValidation($params);
        if($params['unit_type'] != 0){
          $params['from_unitid'] = 0;
        }else{
          $params['from_unitid'] = $params['user_unit_id'];
        }
        if($data_result['status'] == false){
          $response['message'] = $data_result['message'];
          $this->load->view('includes/staffs/shift_header_rota');
          $this->load->view("staffs/rota/openshift",$response);
          $this->load->view('includes/staffs/footer_rota',$result);
        }else{
          if($params['unit_type'] != 0){
            $this->load->model('User_model');
            $unit_data=$this->User_model->fetchCategoryTreeforavailability(' ',' ',' ',$params['unit_type']); 
            foreach ($unit_data as $unit) {
              array_push($unit_id_array, $unit['id']);
            }
          }else{
            $unit_ids = $this->Unit_model->returnMainAndSubUnitsIds($params['unit_id']);
          }
          // $date_array =$this->input->post("date_array");
          
          $result=$this->Rotaschedule_model->acceptRequest($params);
          $request_id=$this->Rotaschedule_model->getRequestId($params);
          $shift_details = $this->Shift_model->findshift($params['shift_id']);
          if($result['status'] == "true"){
            $rota_result = $this->Rotaschedule_model->findRotaIds($params,$params['unit_id']);
            $rota_id = $rota_result['rota_id'];
            $date_array = $rota_result['date_array'];
            if($params['unit_id'] == $params['user_unit_id']){
              $from_unit = null;
              $status = 1;
            }else{
              $status = 0;
              $from_unit = $params['user_unit_id'];
            }
            $status_result = $this->Rotaschedule_model->checkDataExistWithSameDate($params,$params['unit_id']);
            // print("<pre>".print_r($status_result,true)."</pre>");exit();
            if(count($status_result) > 0){
              $new_params = array();
              $new_params['shift_id'] = $params['shift_id'];
              $new_params['from_unit'] = $from_unit;
              $new_params['unit_id'] = $params['unit_id'];
              $new_params['targeted_hours'] = $shift_details[0]['targeted_hours'];
              $new_params['shift_category'] = $shift_details[0]['shift_category'];
              $new_params['request_id'] = $request_id;
              $update_result = $this->Rotaschedule_model->updateSingleScheduleEntry($new_params,$status_result[0]['id']);
            }else{
              for($i=0;$i<7;$i++){
                $day_name = date('D', strtotime($date_array[$i]['date']));
                $day = mb_substr($day_name, 0, -1);
                if($date_array[$i]['date'] == $params['date']){
                  $shift_id = $params['shift_id'];
                  $shift_hours = $shift_details[0]['targeted_hours'];
                  $shift_category = $shift_details[0]['shift_category'];
                }else{
                  $shift_id = 0;
                  $shift_hours = 0;
                  $shift_category = 0;
                }
                $shift_data = array(
                  'user_id'=>$params['user_id'],
                  'shift_id'=>$shift_id,
                  'unit_id'=>$params['unit_id'],
                  'from_unit'=>$from_unit,
                  'shift_hours'=>$shift_hours,
                  'status'=>$status,
                  'rota_id'=>$rota_id,
                  'date'=>$date_array[$i]['date'],
                  'creation_date'=>date('Y-m-d H:i:s'),
                  'created_userid'=>$params['user_id'],
                  'updation_date'=>date('Y-m-d H:i:s'),
                  'day'=>$day,
                  'shift_category'=>$shift_category,
                  'request_id' => $request_id
                );
                $save_details = $this->Rotaschedule_model->addShiftDetails($shift_data);
              }
            }
            if($result['status'] == "true"){
              $unit_supervisor = $this->User_model->getUnitManger($params['unit_id']);
              $unit_user = $this->User_model->getSingleUser($params['user_id']);
              if(count($unit_supervisor)>0){
                $supervisor_name = $unit_supervisor[0]['fname'].' '.$unit_supervisor[0]['lname'];
                $supervisor_email = $unit_supervisor[0]['email'];
                $user_name = $unit_user[0]['fname'].' '.$unit_user[0]['lname'];
                $shift_name = $shift_details[0]['shift_name'];
                $site_title = 'St Matthews Healthcare - SMH Rota';
                $subject = $site_title. ' Open slot request accepted';
                $body = $user_name.' has accepted the open slot request for ' .$shift_name. ' on '.$params['date'];
                $admin_email=getCompanydetails('from_email');
                $emailSettings = array();
                $emailSettings = array(
                    'from' => $admin_email,
                    'site_title' => $site_title,
                    'site_url' => $this->config->item('base_url'),
                    'to' => $supervisor_email,
                    'type' => 'Open slot request-accept',
                    'staff_name' => $user_name,
                    'supervisor_name'=>$supervisor_name,
                    'subject' => $subject,
                    'data' => $body,
                    'content_title'=>'We are glad to have you!',
                );
                $this->load->library('parser');
                $htmlMessage = $this->parser->parse('emails/openslot_accept', $emailSettings, true);
                $this->load->helper('mail');
                sendMail($emailSettings, $htmlMessage);
              }
            }
            $response['message'] = $result['message'];
            $this->load->view('includes/staffs/shift_header_rota');
            $this->load->view("staffs/rota/openshift",$response);
            $this->load->view('includes/staffs/footer_rota',$result);
          }else{
            $response['message'] = $result['message'];
            $this->load->view('includes/staffs/shift_header_rota');
            $this->load->view("staffs/rota/openshift",$response);
            $this->load->view('includes/staffs/footer_rota',$result);
          }
        }
      }
      public function rejectRequest(){
        $params = array();
        $result = array();
        $response = array();
        $params['unit_id']=$this->uri->segment(7);
        $params['date']=ltrim($this->uri->segment(8),"--");
        $params['shift_id']=$this->uri->segment(5);
        $params['user_id']=$this->uri->segment(4);
        $params['user_unit_id']=$this->uri->segment(6);
        $params['unit_type']=$this->uri->segment(9);
        if($params['unit_type'] != 0){
          $params['from_unitid'] = 0;
        }else{
          $params['from_unitid'] = $params['user_unit_id'];
        }
        /*$params['unit_id']=$this->input->post("unit_id");
        $params['date']=$this->input->post("date");
        $params['shift_id']=$this->input->post("shift_id");
        $params['user_id']=$this->input->post("user_id");
        $params['user_unit_id']=$this->input->post("user_unit_id");*/
        $date_array =$this->input->post("date_array");
        $result=$this->Rotaschedule_model->rejectRequest($params);
        $shift_details = $this->Shift_model->findshift($params['shift_id']);
        if($result['status'] == "true"){
          $unit_supervisor = $this->User_model->getUnitManger($params['unit_id']);
          $unit_user = $this->User_model->getSingleUser($params['user_id']);
          if(count($unit_supervisor)>0){
            $supervisor_name = $unit_supervisor[0]['fname'].' '.$unit_supervisor[0]['lname'];
            $supervisor_email = $unit_supervisor[0]['email'];
            $user_name = $unit_user[0]['fname'].' '.$unit_user[0]['lname'];
            $shift_name = $shift_details[0]['shift_name'];
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $subject = $site_title. ' Open slot request accepted';
            $body = $user_name.' has rejected the open slot request for ' .$shift_name. ' on '.$params['date'];
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array();
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => $supervisor_email,
                'type' => 'Open slot request-reject',
                'staff_name' => $user_name,
                'supervisor_name'=>$supervisor_name,
                'subject' => $subject,
                'data' => $body,
                'content_title'=>'We are glad to have you!',
            );
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/openslot_accept', $emailSettings, true);
            $this->load->helper('mail');
            sendMail($emailSettings, $htmlMessage);
        }
      }
      $response['message'] = $result['message'];
      $this->load->view('includes/staffs/shift_header_rota');
      $this->load->view("staffs/rota/openshift",$response);
      $this->load->view('includes/staffs/footer_rota',$result);
    }
}?>