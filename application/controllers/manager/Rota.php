<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Rota extends CI_Controller {
   
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
        $this->load->model('User_model');
        $this->load->model('Unit_model');
        $this->load->model('Shift_model');
        $this->load->model('Training_model');
        $this->load->model('Rotaschedule_model');
        $this->load->model('Rota_model');
        $this->load->model('Staffrota_model');
        $this->load->model('Leave_model');
        $this->load->helper('form');
        $this->load->helper('name');
    }
 

  public function managerviewrota() //mobile view manager view rota
  {
    $this->auth->restrict('Rota.View');
    $response = array();
    $posts = array();
    $header['title'] = 'View'.'shifts';
     $this->load->view('includes/managerheaderrota');
    $response['error']='';
    $response['unit_select'] = 0;
    $response['unit'] ='';
     
    $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
 
    $response['default_date']=date('Y-m-d');
    $response['posts'] = '';
    $response['rotaId'] ='';
    $response['trainings'] = '';
    $response['holiday'] = '';
    //print_r($this->input->post());
    $this->load->model('Shift_model');
    $this->load->model('Unit_model');
    $params=array();
    $posts=array();
    $training=array();
    $holiday=array();
    $params['user_id']='';
    $params['unit_id']=$this->input->post("unit_id");
    $params['start_date']=$this->input->post("start");
    $params['end_date']=$this->input->post("end");
    
    if($this->input->post("unit_id")>0){
        $unitDetails = $this->Unit_model->findunit($this->input->post("unit_id"));
        $response['unit_name']=$unitDetails[0]['unit_name'];
    }
    else{
        $response['unit_name']='';
    }
    
    
    if($this->input->post("start")==""){
        
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        
        $week_start = $first_day_this_month;
        $params['start_date']=$week_start;
    }
    if($this->input->post("end")==""){
        
        $last_day_this_month  = date('Y-m-t');
        $params['end_date']=$last_day_this_month;
    }
    //$params['unit_id']=1;
 
    $params['user_id']=$this->session->userdata('user_id');
     $response['training']=$this->Training_model->getWeeklyTraining($params); 
    foreach ($response['training'] as $training) {
      $training[]= array(
        "training_id"  => $training['id'],
        "title"  => $training['title'],
        "description"  => $training['description'],
        "address" => $training['address']
      );
   }  

   $response['holiday']=$this->Leave_model->getWeeklyHoliday($params);
   foreach ($response['holiday'] as $holiday) {
    $holiday[]=array(
       "holiday_id" => $holiday['id'],
       "title"      => 'Leave',
       "description" => $holiday['comment']
    ); 
     # code...
   }
 
// print_r($params);
    $response['schedule']=$this->Shift_model->getWeeklyShiftsbyStaffs($params); 
    $resources=$this->Shift_model->getWeeklyShiftsResourcesbyStaffs($params);
    
    
    
     foreach ($response['schedule'] as $rota)
    { 
        $shift_color=$this->Shift_model->shift_color($rota['shift_id']); 
             if(empty($shift_color[0]['color_unit']))
              {  
                $text_color="#000";
              }
              else
              {  
                $text_color=$shift_color[0]['color_unit'];
              }
   
        $unitDetails = $this->Unit_model->findunit($rota['unit_id']);
        
        if($rota['shift_name']=='X')
            $time = 'Offday';
        else
            $time = $rota['start_time']."-".$rota['end_time'];
        
        $posts[] = array(
            "unit_id"          =>  $rota['unit_id'],
            "title"            =>  $rota['shift_name'], 
            "title_color"      =>  $text_color,
            "unit_name"        =>  $unitDetails[0]['unit_name'],
            "start"            =>  $rota['date']."T".date("H:i", strtotime($rota['start_time'])),
            "end"              =>  $rota['date']."T".date("H:i", strtotime($rota['end_time'])),
            "stime"=>$rota['start_time'],"etime"=>$rota['end_time'],"time"=>$time,
            "unit_color"=>$unitDetails[0]['color_unit']
            
        );
        
        $response['rotaId'] = $rota['rota_id'];
    }
    //print_r($posts);exit();
    $response['default_date']=date('Y-m-d');  
    $response['weekEvents']=json_encode($posts); 
    $response['trainings']=json_encode($training); 
    $response['holiday']=json_encode($holiday);
    $this->load->view("manager/rota/managerviewrota",$response);
    // $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar_staff.js','rota/managerrota.js');
    // $this->load->view('includes/manager_footer',$result);
  }

  public function managercreaterota(){
    $this->auth->restrict('Rota.Availability');
      $response = array();
      $posts = array();
      $result = array();
      $shift_array = array();
      $header['title'] = 'Create'.'shifts';
       $this->load->view('includes/managerheaderrota');
      $user_id = $this->session->userdata('user_id'); 
      $user_unit_details = $this->Unit_model->getUserUnitDetails($user_id);
      $user_availability_request = $user_unit_details[0]['availability_requests'];
      if(count($user_unit_details) > 0){
        $unit_id = $user_unit_details[0]['unit_id'];
        $unit_name = $user_unit_details[0]['unit_name'];
        $color_unit = $user_unit_details[0]['color_unit'];
        $shift_id = $user_unit_details[0]['default_shift'];
        $fname = $user_unit_details[0]['fname'];
        $lname =  $user_unit_details[0]['lname'];
        $designation_code = $user_unit_details[0]['designation_code'];
        $shift_name = $user_unit_details[0]['shift_shortcut'];
        $user_name = $fname.' '.$lname;
        $color = 'style="border:1px solid '.$color_unit.'; width:90%;"';
        $user_default_shift = $user_unit_details[0]['default_shift'];
        $user_weekly_hours = $user_unit_details[0]['weekly_hours'];
        $posts[] = array(
          "id"                 =>  $user_id,
          "shift_id"           =>  $shift_id,
          "title"              =>  $user_name.' '.'('.$designation_code.')'.' '.'('.$shift_name.')',
          "unit_color"         =>  $color_unit,
          "totalhours"         =>  '('.$user_weekly_hours.')',
          "user_weeklyhours"   =>  $user_weekly_hours
        );
        //get off day of a user
        $this->load->model('Workschedule_model');
        // $data['userWorkschedule']=$this->Workschedule_model->getUserworkschedule($user_id);
        $offday=$this->Workschedule_model->getUserworkschedule($user_id);
        /*if(count($data['userWorkschedule']) > 0) 
        {
            $offday = array_search (1, $data['userWorkschedule'][0]);  
        }
        else
        {
            $offday = '';
        } 
        if($offday=='monday') $offday=1;  
        if($offday=='tuesday') $offday=2;
        if($offday=='wednesday') $offday=3;
        if($offday=='thursday') $offday=4;
        if($offday=='friday') $offday=5;
        if($offday=='saturdy') $offday=6;
         
        if($offday=='' || $offday=='sunday') $offday='';
        if($offday){
          $response['offday']=$offday;
        }else{
          $response['offday']=0;
        }*/
        $response['offday']=json_encode($offday);
        //get all shifts
        $shifts = $this->Shift_model->getShift();
        // $shifts = $this->Shift_model->getRequestShift($user_default_shift);
        $available_shift = $this->Shift_model->getAvailableShifts();
        $useravl_defaultshift = $this->Shift_model->getUserAvlDefaultShift($user_default_shift);
        $response['user_default_shift']= json_encode($this->Shift_model->findshift($user_default_shift));
        $shiftSelect = '<select  class="eventcls"  id="shift_'.$user_id.'" >'; 
        foreach ($shifts as $shift) {
          if($shift['shift_category'] != null){
            array_push($shift_array, $shift);
          }
          if($user_default_shift==$shift['id']){
            $selected = 'selected="selected"';
          }else{
            $selected ="";
          }
          $shiftSelect .= '<option data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' '.$selected.' data-hours='.$shift['targeted_hours'].' value="'.$shift['id'].'" data-breakhours='.$shift['unpaid_break_hours'].'>'.$shift['shift_shortcut'].'</option>';

          
               /* $shiftSelect .= '<option '.$offselect.' data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' data-hours='.$shift['targeted_hours'].' '.$selected.' value="'.$shift['id'].'" data-breakhours='.$shift['unpaid_break_hours'].'>'.$shift['shift_shortcut'].'</option>';*/
        }
        $shiftSelect .= '</select>'; 
        $userShifts[] = array(
            "resourceId" =>  $user_id,
            "unitId"     =>  $unit_id,
            "offDay"     =>  $offday,
            "title"      =>  $shiftSelect,
            "dow"        =>  '[ 0,1,2,3,4,5,6,7 ]',
            "from_unit"  =>  null
        );
        $shift_user_ids = array($user_id);
        $response['userShifts']=json_encode($userShifts);
        $response['available_shift']=json_encode($available_shift);
        $response['useravl_defaultshift']=json_encode($useravl_defaultshift);
        $response['unit']=$this->User_model->findunitname($unit_id);  
        $response['posts'] = json_encode($posts); 
        $response['unitID']=$unit_id;
        $response['user_availability_request']=$user_availability_request;
        $response['user_id']=$this->session->userdata('user_id');
        $response['options']=1;
        $response['shift_array'] = json_encode($shift_array);
        $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
        $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
        $shiftsdata = $this->Shift_model->getShift();
        $response['holidayDates']=json_encode($holidayDates);
        $response['trainingDates']=json_encode($trainingDates);
        $shift_gap=$this->Rota_model->getShiftgaphours();  
        $response['shift_hours']=json_encode($shift_gap); 
        $response['main_unit_id']=$unit_id;
        $response['user_default_shift_id']=$shift_id;
        $zero_targeted_hours_shifts = json_encode($this->findAllZeroHoursShifts());
        $response['zero_targeted_hours_shifts']=json_encode($zero_targeted_hours_shifts);
        $this->load->view("manager/rota/manageavailabilty",$response);
        // $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','fullcalendar/sweetalert2.min.js','manager_rota/rota.js');
        }
        // $this->load->view('includes/manager_footer',$result);
      }

   
   
  public function index()
  {
    $this->auth->restrict('Rota.View');
    $response = array();
    $posts = array();
    $header['title'] = 'View'.'shifts';
    $this->load->view('includes/manager_header');
    $response['error']='';
    $response['unit_select'] = 0;
    $response['unit'] ='';
     
    $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
 
    $response['default_date']=date('Y-m-d');
    $response['posts'] = '';
    $response['rotaId'] ='';
    $response['trainings'] = '';
    $response['holiday'] = '';
    //print_r($this->input->post());
    $this->load->model('Shift_model');
    $this->load->model('Unit_model');
    $params=array();
    $posts=array();
    $training=array();
    $holiday=array();
    $params['user_id']='';
    $params['unit_id']=$this->input->post("unit_id");
    $params['start_date']=$this->input->post("start");
    $params['end_date']=$this->input->post("end");
    
    if($this->input->post("unit_id")>0){
        $unitDetails = $this->Unit_model->findunit($this->input->post("unit_id"));
        $response['unit_name']=$unitDetails[0]['unit_name'];
    }
    else{
        $response['unit_name']='';
    }
    
    
    if($this->input->post("start")==""){
        
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        
        $week_start = $first_day_this_month;
        $params['start_date']=$week_start;
    }
    if($this->input->post("end")==""){
        
        $last_day_this_month  = date('Y-m-t');
        $params['end_date']=$last_day_this_month;
    }
    //$params['unit_id']=1;
 
    $params['user_id']=$this->session->userdata('user_id');
     $response['training']=$this->Training_model->getWeeklyTraining($params); 
    foreach ($response['training'] as $training) {
      $training[]= array(
        "training_id"  => $training['id'],
        "title"  => $training['title'],
        "description"  => $training['description'],
        "address" => $training['address']
      );
   }  

   $response['holiday']=$this->Leave_model->getWeeklyHoliday($params);
   foreach ($response['holiday'] as $holiday) {
    $holiday[]=array(
       "holiday_id" => $holiday['id'],
       "title"      => 'Leave',
       "description" => $holiday['comment']
    ); 
     # code...
   }
 
// print_r($params);
    $response['schedule']=$this->Shift_model->getWeeklyShiftsbyStaffs($params); 
    $resources=$this->Shift_model->getWeeklyShiftsResourcesbyStaffs($params);
    
    
    
     foreach ($response['schedule'] as $rota)
    { 
        $shift_color=$this->Shift_model->shift_color($rota['shift_id']); 
             if(empty($shift_color[0]['color_unit']))
              {  
                $text_color="#000";
              }
              else
              {  
                $text_color=$shift_color[0]['color_unit'];
              }
   
        $unitDetails = $this->Unit_model->findunit($rota['unit_id']);
        
        if($rota['shift_name']=='X')
            $time = 'Offday';
        else
            $time = $rota['start_time']."-".$rota['end_time'];
        
        $posts[] = array(
            "unit_id"          =>  $rota['unit_id'],
            "title"            =>  $rota['shift_name'], 
            "title_color"      =>  $text_color,
            "unit_name"        =>  $unitDetails[0]['unit_name'],
            "start"            =>  $rota['date']."T".date("H:i", strtotime($rota['start_time'])),
            "end"              =>  $rota['date']."T".date("H:i", strtotime($rota['end_time'])),
            "stime"=>$rota['start_time'],"etime"=>$rota['end_time'],"time"=>$time,
            "unit_color"=>$unitDetails[0]['color_unit']
            
        );
        
        $response['rotaId'] = $rota['rota_id'];
    }
    //print_r($posts);exit();
    $response['default_date']=date('Y-m-d');  
    $response['weekEvents']=json_encode($posts); 
    $response['trainings']=json_encode($training); 
    $response['holiday']=json_encode($holiday);
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/ 
    $this->load->view("manager/rota/viewrota",$response);
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar_staff.js','rota/managerrota.js');
    $this->load->view('includes/manager_footer',$result);
  }
  public function getTrainingDates($t_array){
      $t_dates = array();
      foreach ($t_array as $training) {
        $todate = date('Y-m-d',strtotime($training['date_to'] . "+1 days"));
        $begin = new DateTime($training['date_from']);
        $end = new DateTime($todate);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
          array_push($t_dates, $dt->format("Y-m-d"));
        }
      }
      return $t_dates;
    }
    public function getHolidayDates($h_array){
      $h_dates = array();
      foreach ($h_array as $holiday) {
        $todate = date('Y-m-d',strtotime($holiday['to_date'] . "+1 days"));
        $begin = new DateTime($holiday['from_date']);
        $end = new DateTime($todate);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
          array_push($h_dates, $dt->format("Y-m-d"));
        }
      }
      return $h_dates;
    }
  public function getRota(){
    $this->load->model('Shift_model');
    $this->load->model('Training_model');
    $this->load->model('Leave_model');
    $params=array();
    $posts=array();
    $training=array();
    $holiday=array();
    $params['unit_id']=''; 
    $params['user_id']=$this->session->userdata('user_id'); 
    $rotaId="";
    $params['start_date']=$this->input->post("start_date");  
    $params['end_date']=$this->input->post("end_date"); 
    $response['schedule']=$this->Shift_model->getWeeklyShiftsbyStaffs($params);
    $response['training']=$this->Training_model->getWeeklyTraining($params);
    $t_dates = $this->getTrainingDates($response['training']);
    $response['holiday']=$this->Leave_model->getWeeklyHoliday($params);
    $h_dates = $this->getHolidayDates($response['holiday']);
    $rota_dates = array();
    foreach ($response['schedule'] as $rota)
    {
      $shift_color=$this->Shift_model->shift_color($rota['shift_id']);
      $shift_back_color=$this->Shift_model->shift_back_color($rota['shift_id']); 
      if(empty($shift_color[0]['color_unit']))
      {  
        $text_color="#000";
      }
      else
      {  
        $text_color=$shift_color[0]['color_unit'];
      }
       if(empty($shift_back_color[0]['background_color']))
      {  
        $text_back_color="#0000";
      }
      else
      {  
        $text_back_color=$shift_back_color[0]['background_color'];
      }
      $unitDetails = $this->Unit_model->findunit($rota['unit_id']);
       
       if($rota['shift_name']=='X')
           $time = 'Offday';
           else
               $time = $rota['start_time']."-".$rota['end_time'];
               
      if (!in_array($rota['date'], $t_dates))     {
        if(in_array($rota['date'], $h_dates)){
          if($rota['shift_id'] != 0){
            array_push($rota_dates, $rota['date']);
            $posts[] = array(
                "unit_id"          =>  $rota['unit_id'],
                "title"            =>  $rota['shift_name'],
                "title_color"      =>  $text_color,
                "text_back_color"  =>  $text_back_color,
                "unit_name"        =>  $unitDetails[0]['unit_name'],
                "start"            =>  $rota['date']."T".date("H:i", strtotime($rota['start_time'])),
                "end"              =>  $rota['date']."T".date("H:i", strtotime($rota['end_time'])),
                "stime"=>$rota['start_time'],"etime"=>$rota['end_time'],"time"=>$time,
                "unit_color"=>$unitDetails[0]['color_unit'],
                "date"=>$rota['date'],
                "shift_id"=>$rota['shift_id'],
            ); 
          }
        }else{
          array_push($rota_dates, $rota['date']);
          $posts[] = array(
              "unit_id"          =>  $rota['unit_id'],
              "title"            =>  $rota['shift_name'],
              "title_color"      =>  $text_color,
              "text_back_color"  =>  $text_back_color,
              "unit_name"        =>  $unitDetails[0]['unit_name'],
              "start"            =>  $rota['date']."T".date("H:i", strtotime($rota['start_time'])),
              "end"              =>  $rota['date']."T".date("H:i", strtotime($rota['end_time'])),
              "stime"=>$rota['start_time'],"etime"=>$rota['end_time'],"time"=>$time,
              "unit_color"=>$unitDetails[0]['color_unit'],
              "date"=>$rota['date'],
              "shift_id"=>$rota['shift_id'],
          ); 
        }
      }     
    }
    foreach ($response['training'] as $training) { 
      // print_r($holiday);
      $time = $training['date_from']."-".$training['date_to'];
      $unitDetails = $this->Unit_model->findunit($training['unit']);

      $todate = date('Y-m-d',strtotime($training['date_to'] . "+1 days"));
      $begin = new DateTime($training['date_from']);
      $end = new DateTime($todate);
      $interval = DateInterval::createFromDateString('1 day');
      $period = new DatePeriod($begin, $interval, $end);
      foreach ($period as $dt) {
        $posts[] = array(
          "unit_id"          =>  $training['unit'],
          "title"            =>  'Training', 
          "unit_name"        =>  $unitDetails[0]['unit_name'],
          "start"            =>  $dt->format("Y-m-d")."T".date("H:i", strtotime($training['time_from'])),
          "end"              =>  $dt->format("Y-m-d")."T".date("H:i", strtotime($training['time_to'])),
          "stime"=>$dt->format("Y-m-d"),"etime"=>$dt->format("Y-m-d"),"time"=>'Title' .'-'. $training['title'],
          "unit_color"=>$unitDetails[0]['color_unit'],
          "date"=>$dt->format("Y-m-d"),
          "shift_id"=>2
        );
      }
    }
    foreach ($response['holiday'] as $holiday) {
      if($holiday['status']==1){$test="Approved";}elseif($holiday['status']==2){$test="Rejected";}else{$test="Pending";}
      $unitDetails = $this->Unit_model->findunit($holiday['unit_id']);
      $todate = date('Y-m-d',strtotime($holiday['to_date'] . "+1 days"));
      $begin = new DateTime($holiday['from_date']);
      $end = new DateTime($todate);
      $interval = DateInterval::createFromDateString('1 day');
      $period = new DatePeriod($begin, $interval, $end);
      foreach ($period as $dt) {
        if(!in_array($dt->format("Y-m-d"), $rota_dates))
        //  print_r($dt->format("Y-m-d"));
        {
          $posts[]=array(
            "unit_id"          =>  $holiday['unit_id'],
            "title"            =>  'Annual Leave',
            "unit_name"        =>  $unitDetails[0]['unit_name'],
            "start"            =>  $dt->format("Y-m-d"),
            "end"              =>  $dt->format("Y-m-d"),
            "time"=>'Status' .'-'. $test,
            "unit_color"=>$unitDetails[0]['color_unit'],
            "date"=>$dt->format("Y-m-d"),
            "shift_id"=>1
          );
        }
      }
    }
    $final_result = $this->removeDuplicates($posts);
    header('Content-Type: application/json');
    echo json_encode($final_result); 
    exit(); 
  }
 
   function removeDuplicates($posts){
     $outputArray = array();
     $count = 0;
     $start = false;
     for($j=0;$j<count($posts);$j++){
       for ($k = 0; $k < count($outputArray); $k++) {
         if ( $posts[$j]['date'] == $outputArray[$k]['date'] ) {
           if($posts[$j]['shift_id'] > $outputArray[$k]['shift_id']){
             unset($outputArray[$k]);
             $start == false;
           }else{
             $start = true;
           }
         } 
       }
       $count++; 
       if ($count == 1 && $start == false) {
         array_push($outputArray, $posts[$j]);
       } 
       $start = false; 
       $count = 0; 
     }
     return $outputArray;
   }
    public function findStaffOnShift(){
        $unit_id = $this->input->post("unit_id");
        $shift_id = $this->input->post("shift_id");
        $staff_details = array();
        $staff_details=$this->Unit_model->getstaffsbyunitShift($shift_id,$unit_id);
        header('Content-Type: application/json');
        echo json_encode(array($staff_details));
        exit();
    }
    public function findUsersOnOtherLocation(){
        $unit_id = $this->input->post("unit_id");
        $staff_details = array();
        $staff_details=$this->Shift_model->getShift();
        header('Content-Type: application/json');
        echo json_encode(array($staff_details));
        exit();
    }
    public function getNonAssignedUnitStaffs(){
        $unit_id = $this->input->post("unit_id");
        $non_staff_details = array();
        $non_staff_details = $this->User_model->getNotAssignedStaffs($unit_id);
        header('Content-Type: application/json');
        echo json_encode(array($non_staff_details));
        exit();
    }
    public function checkAvailability(){    
      $unit_id = $this->input->post("unit_id");   
      $user_id = $this->input->post("user_id");   
      $date = $this->input->post("date");   
      $result = $this->Staffrota_model->checkAvailability($unit_id,$user_id,$date);   
      header('Content-Type: application/json');   
      echo json_encode(array($result));   
      exit();   
    }
    public function findAllZeroHoursShifts(){
      $shifts = $this->Shift_model->zeroTargetedHoursShifts();
      $zero_shifts = array();
      foreach ($shifts as $shift) {
        array_push($zero_shifts, $shift["id"]);
      }
      return $zero_shifts;
    }
    public function createrota(){
      $this->auth->restrict('Rota.Availability');
      $response = array();
      $posts = array();
      $result = array();
      $shift_array = array();
      $header['title'] = 'Create'.'shifts';
      $this->load->view('includes/manager_header');
      $user_id = $this->session->userdata('user_id'); 
      $user_unit_details = $this->Unit_model->getUserUnitDetails($user_id);
      // $user_availability_request = $user_unit_details[0]['availability_requests'];
      $user_weeklyhours = $user_unit_details[0]['weekly_hours'];
      $user_weeklyhours_array = explode(':', $user_weeklyhours);
      $weekly_hours = (int)$user_weeklyhours_array[0];
      if($weekly_hours == 0){
        $user_availability_request = 7;
      }else if($weekly_hours >= 1 && $weekly_hours <= 26){
        $user_availability_request = 4;
      }else if($weekly_hours >= 27 && $weekly_hours <= 30){
        $user_availability_request = 3;
      }else{
        $user_availability_request = 2;
      }
      if(count($user_unit_details) > 0){
        $unit_id = $user_unit_details[0]['unit_id'];
        $unit_name = $user_unit_details[0]['unit_name'];
        $color_unit = $user_unit_details[0]['color_unit'];
        $shift_id = $user_unit_details[0]['default_shift'];
        $fname = $user_unit_details[0]['fname'];
        $lname =  $user_unit_details[0]['lname'];
        $designation_code = $user_unit_details[0]['designation_code'];
        $shift_name = $user_unit_details[0]['shift_shortcut'];
        $user_name = $fname.' '.$lname;
        $color = 'style="border:1px solid '.$color_unit.'; width:90%;"';
        $user_default_shift = $user_unit_details[0]['default_shift'];
        $user_weekly_hours = $user_unit_details[0]['weekly_hours'];
        $posts[] = array(
          "id"                 =>  $user_id,
          "shift_id"           =>  $shift_id,
          "title"              =>  $user_name.' '.'('.$designation_code.')'.' '.'('.$shift_name.')',
          "unit_color"         =>  $color_unit,
          "totalhours"         =>  '('.$user_weekly_hours.')',
          "user_weeklyhours"   =>  $user_weekly_hours
        );
        //get off day of a user
        $this->load->model('Workschedule_model');
        // $data['userWorkschedule']=$this->Workschedule_model->getUserworkschedule($user_id);
        $offday=$this->Workschedule_model->getUserworkschedule($user_id);
        /*if(count($data['userWorkschedule']) > 0) 
        {
            $offday = array_search (1, $data['userWorkschedule'][0]);  
        }
        else
        {
            $offday = '';
        } 
        if($offday=='monday') $offday=1;  
        if($offday=='tuesday') $offday=2;
        if($offday=='wednesday') $offday=3;
        if($offday=='thursday') $offday=4;
        if($offday=='friday') $offday=5;
        if($offday=='saturdy') $offday=6;
         
        if($offday=='' || $offday=='sunday') $offday='';
        if($offday){
          $response['offday']=$offday;
        }else{
          $response['offday']=0;
        }*/
        $response['offday']=json_encode($offday);
        //get all shifts
        $shifts = $this->Shift_model->getShift();
        // $shifts = $this->Shift_model->getRequestShift($user_default_shift);
        $available_shift = $this->Shift_model->getAvailableShifts();
        $useravl_defaultshift = $this->Shift_model->getUserAvlDefaultShift($user_default_shift);
        $response['user_default_shift']= json_encode($this->Shift_model->findshift($user_default_shift));
        $shiftSelect = '<select  class="eventcls"  id="shift_'.$user_id.'" >'; 
        foreach ($shifts as $shift) {
          if($shift['shift_category'] != null){
            array_push($shift_array, $shift);
          }
          if($user_default_shift==$shift['id']){
            $selected = 'selected="selected"';
          }else{
            $selected ="";
          }
          $shiftSelect .= '<option data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' '.$selected.' data-hours='.$shift['targeted_hours'].' value="'.$shift['id'].'" data-breakhours='.$shift['unpaid_break_hours'].'>'.$shift['shift_shortcut'].'</option>';

          
               /* $shiftSelect .= '<option '.$offselect.' data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' data-hours='.$shift['targeted_hours'].' '.$selected.' value="'.$shift['id'].'" data-breakhours='.$shift['unpaid_break_hours'].'>'.$shift['shift_shortcut'].'</option>';*/
        }
        $shiftSelect .= '</select>'; 
        $userShifts[] = array(
            "resourceId" =>  $user_id,
            "unitId"     =>  $unit_id,
            "offDay"     =>  $offday,
            "title"      =>  $shiftSelect,
            "dow"        =>  '[ 0,1,2,3,4,5,6,7 ]',
            "from_unit"  =>  null
        );
        $shift_user_ids = array($user_id);
        $response['userShifts']=json_encode($userShifts);
        $response['available_shift']=json_encode($available_shift);
        $response['useravl_defaultshift']=json_encode($useravl_defaultshift);
        $response['unit']=$this->User_model->findunitname($unit_id);  
        $response['posts'] = json_encode($posts); 
        $response['unitID']=$unit_id;
        $response['user_availability_request']=$user_availability_request;
        $response['user_id']=$this->session->userdata('user_id');
        $response['options']=1;
        $response['shift_array'] = json_encode($shift_array);
        $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
        $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
        $shiftsdata = $this->Shift_model->getShift();
        $response['holidayDates']=json_encode($holidayDates);
        $response['trainingDates']=json_encode($trainingDates);
        $shift_gap=$this->Rota_model->getShiftgaphours();  
        $response['shift_hours']=json_encode($shift_gap); 
        $response['main_unit_id']=$unit_id;
        $response['user_default_shift_id']=$shift_id;
        $zero_targeted_hours_shifts = json_encode($this->findAllZeroHoursShifts());
        $response['zero_targeted_hours_shifts']=json_encode($zero_targeted_hours_shifts);
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/ 
        $this->load->view("manager/rota/managerota",$response);
        $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','fullcalendar/sweetalert2.min.js','staff_rota/availability.js');
        }
        $this->load->view('includes/manager_footer',$result);
      }
      public function saveBulkDataToRotaSchedule($dates,$shift_id,$shift_hours,$unit_id,$rota_id)
      {
        $count = count($dates);
        for ($i = 0; $i < $count; $i++)
        {
          $shift_data = array(
            'user_id'=>$this->session->userdata('user_id'),
            'shift_id'=>$shift_id,
            'unit_id'=>$unit_id,
            'shift_hours'=>$shift_hours,
            'status'=>1,
            'rota_id'=>$rota_id,
            'date'=>$dates[$i],
            'creation_date'=>date('Y-m-d H:i:s'),
            'updation_date'=>date('Y-m-d H:i:s')
          );
          $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
        }
      }
      public function saveDataToRotaSchedule($shift_details_array,$rota_id){
        $count = count($shift_details_array);
        for ($i = 0; $i < $count; $i++)
        {
          if (isset($shift_details_array[$i]["shift_id"])) {
            $shift_id = $shift_details_array[$i]["shift_id"];
          }else{
            $shift_id = null;
          }        
          if (isset($shift_details_array[$i]["shift_hours"])) {
            $shift_hours = $shift_details_array[$i]["shift_hours"];
          }else{
            $shift_hours = null;
          }
          $shift_data = array(
            'user_id'=>$shift_details_array[$i]["user_id"],
            'shift_id'=>$shift_id,
            'unit_id'=>$shift_details_array[$i]["unit_id"],
            'shift_hours'=>$shift_hours,
            'status'=>1,
            'rota_id'=>$rota_id,
            'date'=>$shift_details_array[$i]["start"],
            'creation_date'=>date('Y-m-d H:i:s'),
            'updation_date'=>date('Y-m-d H:i:s')
          );
          $result_array = $this->Staffrota_model->checkUserExists(
            $shift_details_array[$i]["start"],
            $shift_details_array[$i]["user_id"],
            $shift_details_array[$i]["unit_id"]
          );
          if(count($result_array) > 0){
            $shift_key = $result_array[0]['id'];
            $this->Staffrota_model->deleteUserSchedule($shift_key);
            $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
          }else{
            $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
          }
        }
      }
      public function markAvailability(){
        $dates = $this->input->post("dates");
        $shift_id = $this->input->post("shift_id");
        $shift_hours = $this->input->post("shift_hours");
        $unit_id = $this->input->post("unit_id");
        $user_id = $this->input->post("user_id");
        $start_date = $this->input->post("start_date");
        $end_date = $this->input->post("end_date");
        $user_id = $this->session->userdata('user_id');
        $rota_data    = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'unit_id' => $unit_id,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'published' => 0
        );
        //get off day of a user
        $this->load->model('Workschedule_model');
        $staff_offday=$this->Workschedule_model->getUserworkschedule($user_id);
        $user_default_shift=$this->User_model->getDefaultShift($user_id);
        /*if(count($data)>0) 
        {  
            $offday = array_search (1, $data[0]);  
        }
        else
        {  
            $offday = ''; 
        }*/
        $user_offday = $staff_offday;
        $shift_user_ids = array($user_id);
        $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
        $all_holiday_dates = [];
        foreach ($holidayDates as $holidayDate){
          $all_dates = $this->findDatesBtwnDates($holidayDate['from_date'],$holidayDate['to_date']);
          for($i=0;$i<count($all_dates);$i++) {
            array_push($all_holiday_dates,$all_dates[$i]);
          }  
        }
        $result = $this->Staffrota_model->checkRotaDataWithStartAndEndDate($start_date,$end_date,$user_id);
        if($result['status'] == 2){
          $save_result = $this->Staffrota_model->addRotaDetails($rota_data);
          $rota_id = $save_result['rota_id'];
          if($rota_id)
          {
            $avl_dates = $this->saveToRotaSchedule($dates,$shift_id,$shift_hours,$unit_id,$rota_id,$user_offday,$all_holiday_dates,$user_default_shift);
          }
        }else{
          $rota_id = $result['result'][0]['id'];
          // $this->Staffrota_model->deleteShifts($rota_id);
          $avl_dates = $this->saveToRotaSchedule($dates,$shift_id,$shift_hours,$unit_id,$rota_id,$user_offday,$all_holiday_dates,$user_default_shift);
        }        
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'message' => "Success",'avl_dates'=>$avl_dates,'user_id'=>$user_id));
        exit();
      }
      public function saveToRotaSchedule($dates,$shift_id,$shift_hours,$unit_id,$rota_id,$user_offday,$all_holiday_dates,$user_default_shift)
      {
        $user_id = $this->session->userdata('user_id');
        $count = count($dates);
        $avl_dates = array();
        for ($i = 0; $i < $count; $i++)
        {
          $date = $dates[$i];
          $nameOfDay = strtolower(date('l', strtotime($date)));
          if (in_array($nameOfDay, $user_offday)){
            $new_shift_id = $shift_id;
            $new_shift_hours= $shift_hours;
          }else{
            if (in_array($dates[$i], $all_holiday_dates)){
              $new_shift_id = $shift_id;
              $new_shift_hours= $shift_hours;
            }else{
              $new_shift_id = null;
              $new_shift_hours= null;
            }
          }
          if($new_shift_id != null){
            array_push($avl_dates, $dates[$i]);
            $shift_data = array(
              'user_id'=>$this->session->userdata('user_id'),
              'shift_id'=>$new_shift_id,
              'unit_id'=>$unit_id,
              'shift_hours'=>$new_shift_hours,
              'status'=>1,
              'rota_id'=>$rota_id,
              'date'=>$dates[$i],
              'creation_date'=>date('Y-m-d H:i:s'),
              'updation_date'=>date('Y-m-d H:i:s')
            );
            $result_array = $this->Staffrota_model->checkUserExists($dates[$i],$user_id,$unit_id);
            if(count($result_array) > 0){
              if($user_offday == $nameOfDay || in_array($dates[$i], $all_holiday_dates)){
                $shift_key = $result_array[0]['id'];
                $this->Staffrota_model->deleteUserSchedule($shift_key);
                $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
              }
            }else{
              $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
            }
          }
        }
        return $avl_dates;
      }
      public function findDatesBtwnDates($start_date,$end_date){
        $date_array = array();
        // Specify the start date. This date can be any English textual format  
        $date_from = $start_date;   
        $date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
          
        // Specify the end date. This date can be any English textual format  
        $date_to = $end_date;  
        $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
          
        // Loop from the start date to end date and output all dates inbetween  
        for ($i=$date_from; $i<=$date_to; $i+=86400) {  
          array_push($date_array, date("Y-m-d", $i));
        }
        return $date_array;
      }
      public function saveBeforePublish(){
        $shift_details = $this->input->post("shiftDetails");
        $shift_details_array = json_decode($shift_details,true);
        $count = count($shift_details_array);
        $unit_id = $shift_details_array[0]["unit_id"];
        $user_id = $this->session->userdata('user_id');
        $start_date = $shift_details_array[0]["start"];
        $end_date = $shift_details_array[6]["start"];
        $rota_data    = array(
            'start_date' => $shift_details_array[0]["start"],
            'end_date' => $shift_details_array[6]["start"],
            'unit_id' => $shift_details_array[0]["unit_id"],
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
            'user_id' => $this->session->userdata('user_id'),
            'published' => 0
        );
        $result = $this->Staffrota_model->checkRotaDataWithStartAndEndDate($start_date,$end_date,$user_id);
        if($result['status'] == 2){
          $save_result = $this->Staffrota_model->addRotaDetails($rota_data);
          $rota_id = $save_result['rota_id'];
          if($rota_id)
          {
            $this->saveDataToRotaSchedule($shift_details_array,$rota_id);
          }
        }else{
          $rota_id = $result['result'][0]['id'];
          // $this->Staffrota_model->deleteShifts($rota_id);
          $this->saveDataToRotaSchedule($shift_details_array,$rota_id);
        }        
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'message' => "Success"));
        exit();
      }
    public function getScheduleData(){
        $this->load->model('Staffrota_model');
        $this->load->model('Workschedule_model');
        $params=array();
        $posts=array();
        $params['user_id']=$this->session->userdata('user_id');
        $rotaId="";
        $params['unit_id']=$this->input->post("unit_id");
        $params['start_date']=$this->input->post("start_date");
        $params['end_date']=date('Y-m-d', strtotime($this->input->post("end_date") . ' - 1 day'));
        $schedules=$this->Staffrota_model->getWeeklyShifts($params);
        $offday_dates = $this->Rotaschedule_model->getPublishedRotaOfUser($params,[]);
        $offday=$this->Workschedule_model->getUserworkschedule($params['user_id']);
        $user=0;
      //  print_r($params);
        $selectedShifts = array();
        foreach ($schedules as $schedule){
            // print_r($schedule);
            if($user!=$schedule["user_id"])
                $i=0;
                $selectedShifts['shift_'.$schedule["user_id"].'_'.$schedule["date"]]=$schedule["shift_id"];
                $i++;
                $user = $schedule["user_id"];
                 
        }
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'message' => "Success",'selectedShifts'=>$selectedShifts,'offday_dates'=>$offday_dates,'offday'=>$offday));
        exit();
    }
    public function openShift(){
      $response = array();
      $result = array();
      $this->load->view('includes/manager_header');
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
      $this->load->view("manager/rota/openshift",$response);
      $result['js_to_load'] = array('manager_rota/openshift.js');
      $this->load->view('includes/manager_footer',$result);
    }
    public function acceptRequest(){
      $params = array();
      $params['unit_id']=$this->input->post("unit_id");
      $params['date']=$this->input->post("date");
      $params['shift_id']=$this->input->post("shift_id");
      $params['user_id']=$this->input->post("user_id");
      $params['user_unit_id']=$this->input->post("user_unit_id");
      $date_array =$this->input->post("date_array");
      $result=$this->Rotaschedule_model->acceptRequest($params);
      $shift_details = $this->Shift_model->findshift($params['shift_id']);
      if($result['status'] == "true"){
        $rota_id = $this->Rotaschedule_model->getRotaId($params);
        if($params['unit_id'] == $params['user_unit_id']){
          $from_unit = null;
          $status = 1;
        }else{
          $status = 0;
          $from_unit = $params['user_unit_id'];
        }
        for($i=0;$i<count($date_array);$i++){
          $day_name = date('D', strtotime($date_array[$i]));
          $day = mb_substr($day_name, 0, -1);
          if($date_array[$i] == $params['date']){
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
            'date'=>$date_array[$i],
            'creation_date'=>date('Y-m-d H:i:s'),
            'created_userid'=>$this->session->userdata('user_id'),
            'updation_date'=>date('Y-m-d H:i:s'),
            'day'=>$day,
            'shift_category'=>$shift_category
          );
          $save_details = $this->Rotaschedule_model->addShiftDetails($shift_data);
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
                'type' => 'Manager/Supervisor-Open slot request accepted',
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
        header('Content-Type: application/json');
        echo json_encode(array('status' => $result['status'],'message' => $result['message']));
        exit();
      }else{
        header('Content-Type: application/json');
        echo json_encode(array('status' => $result['status'],'message' => $result['message']));
        exit();
      }
    }
    public function rejectRequest(){
      $params = array();
      $params['unit_id']=$this->input->post("unit_id");
      $params['date']=$this->input->post("date");
      $params['shift_id']=$this->input->post("shift_id");
      $params['user_id']=$this->input->post("user_id");
      $params['user_unit_id']=$this->input->post("user_unit_id");
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
              'type' => 'Manager/Supervisor-Open slot request rejected',
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
    header('Content-Type: application/json');
    echo json_encode(array('status' => $result['status'],'message' => $result['message']));
    exit();
  }
  public function getShiftId(){
    $params = array();
    $params['unit_id'] = $this->input->post("unit_id");
    $params['user_id'] = $this->input->post("user_id");
    $params['date']    = $this->input->post("date");
    $shift = $this->Staffrota_model->getShiftId($params);
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'result'=> $shift));
    exit();
  }
  public function saveAvailabilityToRotaSchedule($shift_details_array,$rota_id,$user_id){
        $count = count($shift_details_array);
        for ($i = 0; $i < $count; $i++)
        {
          $shift_data = array(
            'user_id'=>$user_id,
            'shift_id'=>$shift_details_array[$i]['shift_id'],
            'unit_id'=>$shift_details_array[$i]["unit_id"],
            'shift_hours'=>$shift_details_array[$i]["shift_hours"],
            'status'=>1,
            'rota_id'=>$rota_id,
            'date'=>$shift_details_array[$i]['date'],
            'creation_date'=>date('Y-m-d H:i:s'),
            'updation_date'=>date('Y-m-d H:i:s')
          );
          $result_array = $this->Staffrota_model->checkUserExists(
            $shift_details_array[$i]['date'],
            $user_id,
            $shift_details_array[$i]["unit_id"]
          );
          if(count($result_array) > 0){
            $shift_key = $result_array[0]['id'];
            $this->Staffrota_model->deleteUserSchedule($shift_key);
            $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
          }else{
            $save_details = $this->Staffrota_model->addShiftDetails($shift_data);
          }     
        }
      }
      public function saveAvailability(){
        $shift_details = $this->input->post("shiftDetails");
        $shift_details_array = json_decode($shift_details,true);
        $user_id = $this->session->userdata('user_id');
        $count = count($shift_details_array);
        $unit_id = $shift_details_array[0]["unit_id"];
        $start_date = $shift_details_array[0]["date"];
        $end_date = $shift_details_array[$count-1]["date"];
        $rota_data    = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'unit_id' => $unit_id,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'published' => 0
        );
        $result = $this->Staffrota_model->checkRotaDataWithStartAndEndDate($start_date,$end_date,$user_id);
        if($result['status'] == 2){
          $save_result = $this->Staffrota_model->addRotaDetails($rota_data);
          $rota_id = $save_result['rota_id'];
          if($rota_id)
          {
            $this->saveAvailabilityToRotaSchedule($shift_details_array,$rota_id,$user_id);
          }
        }else{
          $rota_id = $result['result'][0]['id'];
          $this->saveAvailabilityToRotaSchedule($shift_details_array,$rota_id,$user_id);
        }        
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'message' => "Success"));
        exit();
      }
      function getUserRequestCount(){
        $params = array();
        $params['user_id']   = $this->input->post("user_id");
        $params['shift_id']= $this->input->post("shift_id");
        $request_dates = $this->input->post("request_array");
        $result = $this->Staffrota_model->getUserRequestCount($params,$request_dates);
        header('Content-Type: application/json');
        echo json_encode(array('result'=> $result));
        exit();
      }
}
?>