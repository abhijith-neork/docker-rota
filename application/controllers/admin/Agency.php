<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Agency extends CI_Controller {
        
       
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
            $this->load->model('Agency_model');
            $this->load->model('Shift_model');
            $this->load->helper('form');
            $this->load->helper('name');
        }
        public function getAgencyStaff(){
            $params = array();
            $date = $this->input->post("date");
            $agency_details = $this->Agency_model->getAgencyStaff($date);
            header('Content-Type: application/json');
            echo json_encode(array('status' => 1,'result'=> $agency_details));
            exit();
        }
        public function getShiftDetailsOfAbsentUser(){
           $params=array();
           $params['user_id'] = $this->input->post("user_id");
           $params['unit_id'] = $this->input->post("unit_id");
           $params['date'] = $this->input->post("date");
           $shift_result=$this->Agency_model->getShiftDetailsOfAbsentUser($params);
           header('Content-Type: application/json');
           echo json_encode(array('result'=> $shift_result));
           exit();
        }
        public function getDates($date){    
          $date_array = array();
          // parse about any English textual datetime description into a Unix timestamp
          $ts = strtotime($date);
          // calculate the number of days since Sunday
          $dow    = date('w', $ts);
          $offset = $dow;
          if ($offset < 0) {
          $offset = 6;
          }
          // calculate timestamp for the Sunday
          $ts = $ts - $offset * 86400;
          // loop from Sunday till Saturday
          for ($i = 0; $i < 7; $i++, $ts += 86400) {
            $dates = date("Y-m-d", $ts);
            array_push($date_array, $dates);
          }
          return $date_array;
        }
        public function addAgencyUserDetails(){
            $this->load->model('Rotaschedule_model');
            $params = array();
            $week_day_names = ["Su","Mo","Tu","We","Th","Fr","Sa"];
            $user_id = $this->input->post("user_id");
            $old_user_id = $this->input->post("old_user_id");
            $unit_id = $this->input->post("unit_id");
            $shift_id = $this->input->post("shift_id");
            $date = $this->input->post("date");
            $rota_id = $this->input->post("rota_id");
            $from_unit = $this->input->post("new_user_unitid");
            $date_array = $this->getDates($date);
            $count = count($date_array);
            $params['user_id'] = $user_id;
            $params['start_date'] = $date_array[0];
            $params['end_date'] = $date_array[6];
            $params['date'] = $date;
            $params['shift_id'] = $this->input->post("shift_id");
            $params['shift_hours'] = $this->input->post("shift_hours");
            $params['shift_category'] = $this->input->post("shift_category");
            // $agency_staff_data=$this->Shift_model->checkAgencyStaffExists($params);

            $agency_staff_data =  array(
                'user_id' => $old_user_id,
                'agency_staffid' => $user_id,
                'date' => $date,
                'unit_id' => $unit_id,
                'shift_id' => $shift_id,
                'created_date' => date('Y-m-d H:i:s')
            );
            $result = $this->Agency_model->addAgencyData($agency_staff_data);
            for ($i = 0; $i < $count; $i++)
            {
                if($date != $date_array[$i]){        
                    $shift_id = 0;
                    $shift_hours = 0;
                    $shift_category = 0;
                }else{
                    $shift_id = $this->input->post("shift_id");
                    $shift_hours = $this->input->post("shift_hours");
                    $shift_category = $this->input->post("shift_category");
                }
                $shift_data = array(
                    'user_id'=>$user_id,
                    'shift_id'=>$shift_id,
                    'unit_id'=>$unit_id,
                    'from_unit'=>$from_unit,
                    'shift_hours'=>$shift_hours,
                    'status'=>0,
                    'rota_id'=>$rota_id,
                    'date'=>$date_array[$i],
                    'creation_date'=>date('Y-m-d H:i:s'),
                    'created_userid'=>$this->session->userdata('user_id'),
                    'updation_date'=>date('Y-m-d H:i:s'),
                    'day'=>$week_day_names[$i],
                    'shift_category'=>$shift_category,
                );
                $save_details = $this->User_model->addShiftDetails($shift_data);
            }

            // print("<pre>".print_r($agency_staff_data,true)."</pre>");exit();
            /*if(count($agency_staff_data) > 0){
              $flag = 0;
              for ($i = 0; $i < count($agency_staff_data); $i++){
                if($date == $agency_staff_data[$i]['date']){
                  if($agency_staff_data[$i]['shift_id'] != 0){
                    $flag++;
                  }
                }
              }
              if($flag == 0){
                $result=$this->Rotaschedule_model->updateSingleEntry($params);
                if($result == "true"){
                  header('Content-Type: application/json');
                  echo json_encode(array('status' => 1));
                  exit();
                }else{
                  header('Content-Type: application/json');
                  echo json_encode(array('status' => 2,'message'=> 'Shift allocation failed'));
                  exit();
                }
              }else{
                header('Content-Type: application/json');
                echo json_encode(array('status' => 2,'message'=>'Already assigned user for this date'));
                exit();
              }
            }else{
              for ($i = 0; $i < $count; $i++)
              {
                if($date != $date_array[$i]){        
                  $shift_id = 0;
                  $shift_hours = 0;
                  $shift_category = 0;
                }else{
                  $shift_id = $this->input->post("shift_id");
                  $shift_hours = $this->input->post("shift_hours");
                  $shift_category = $this->input->post("shift_category");
                }
                $shift_data = array(
                  'user_id'=>$user_id,
                  'shift_id'=>$shift_id,
                  'unit_id'=>$unit_id,
                  'from_unit'=>$from_unit,
                  'shift_hours'=>$shift_hours,
                  'status'=>0,
                  'rota_id'=>$rota_id,
                  'date'=>$date_array[$i],
                  'creation_date'=>date('Y-m-d H:i:s'),
                  'created_userid'=>$this->session->userdata('user_id'),
                  'updation_date'=>date('Y-m-d H:i:s'),
                  'day'=>$week_day_names[$i],
                  'shift_category'=>$shift_category,
                );
                $save_details = $this->User_model->addShiftDetails($shift_data);
              }
            } */   
            header('Content-Type: application/json');
            echo json_encode(array('status' => 1));
            exit();
        }
    }
?>