<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Test_rota extends CI_Controller {
   
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
        $this->load->model('Leave_model');
        $this->load->model('Rota_model');
        $this->load->model('Activity_model');
        $this->load->model('Training_model');
        $this->load->model('Agency_model');
        $this->load->model('Reports_model');
        $this->load->helper('form');
        $this->load->helper('name');
        $this->load->model('Designation_model');
    }
    public function findShiftCategory($shift_id){
      $shift_category = '' ;
      $shift_cats = $this->getShiftDatas();
      foreach ($shift_cats as $key => $value)
      {
        if($key == $shift_id){
          $shift_category = $value;
        }
      }
      return $shift_category;
    }
    public function rota()
  { 
    $this->auth->restrict('Admin.Rota.Add');
    $this->load->model('Rotaschedule_model');
    $response = array();
    $posts = array();
    $male_nurse_count = 0;
    $female_nurse_count = 0;
    $header['title'] = 'Add'.'shifts';
    $this->load->view('includes/home_header',$header);
    $main_unit_id = $this->input->post('unit_id');
    $user=$this->input->post('unit_id'); 
    $users=$this->input->post('users');
    $shift_user_ids = array();
    $from_unit=$this->input->post('from_unit');
    $day_shift_min=$this->input->post('day_shift_min');
    $day_shift_max=$this->input->post('day_shift_max');
    $night_shift_min=$this->input->post('night_shift_min');
    $night_shift_max=$this->input->post('night_shift_max');
    $num_patients=$this->input->post('num_patients');
    $designation=$this->input->post('designation'); 
    $nurse_day_count=$this->input->post('nurse_day_count');
    $nurse_night_count=$this->input->post('nurse_night_count');
    $date_daily=$this->input->post('end_date');
    $session_id=$this->input->post('session_id');
    $end_date = '';
    if($date_daily!='')
    {
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $end_date=$new_data;
    }
    $action = $this->input->post('action');
    $up_rota_id = $this->input->post('up_rota_id');
    $userid_array_fordelete = $this->input->post('userid_array_fordelete');
    $user_from_other_unit = $this->input->post('user_from_other_unit');
    $new_scheduled_user_count = array();
    $user_offday = array();
    if($users!='')
    {
      /*if($day_shift_min && $day_shift_max && $night_shift_min && $night_shift_max && $num_patients && $designation){*/
        if(is_numeric($day_shift_min) && is_numeric($day_shift_max) && is_numeric($night_shift_min) && is_numeric($night_shift_max) && is_numeric($num_patients) && is_numeric($designation)){
          if($day_shift_min < $day_shift_max){
            if($night_shift_min < $night_shift_max){
            foreach ($users as $value) 
            {
                // print $value."<br>";exit();
                $unit_ids = explode("_", $value);
                 //print_r($unit_ids);exit();
                if($unit_ids[7] == "F" || $unit_ids[7] == "f"){
                  $female_nurse_count++;
                }else{
                  $male_nurse_count++;
                }
              // print("<pre>".print_r($unit_ids,true)."</pre>");exit();
                //get unit details
                array_push($shift_user_ids, $unit_ids[0]);
                array_push($new_scheduled_user_count, $unit_ids[0]);
                $unitId=$this->input->post('unit_id'); 
                if($unit_ids['4']){
                  $unitColor=$this->Unit_model->getUnitcolor($unit_ids['4']);
                }else{
                  $unitColor=$this->Unit_model->getUnitcolor($unit_ids['3']);
                }
                
                $color='style="border:1px solid '.$unitColor['color_unit'].'; width:90%;"'; 
                
                // get user default shift
                $userDetails=$this->User_model->findusers($unit_ids[0]);   
                if($unit_ids[4]){
                  $unit_shortname=$this->Unit_model->getShiftsUnitShortname($unit_ids[4]);                  
                  $other_unit = $unit_ids[4];
                  $unit_short = $unit_shortname[0]['unit_shortname'];
                  //$title = $unit_ids[7].'-'.$unit_ids[2].' '.'('.$unit_ids[5].')'.' '.'('.$unit_ids[6].')'.'('.$unit_short.')';
                  $title=$unit_ids[2];
                }else{
                  $other_unit = null;
                  //$title = $unit_ids[7].'-'.$unit_ids[2].' '.'('.$unit_ids[5].')'.' '.'('.$unit_ids[6].')';
                  $title=$unit_ids[2];
                }
                //print_r($title);exit();
                if($userDetails[0]['default_shift']=='')
                {
                  $userDefaultShift = 0;
                }
                else
                {
                   $userDefaultShift = $userDetails[0]['default_shift'];
                } 
                //print_r($userDefaultShift);
                $userWeeklyHours = $userDetails[0]['weekly_hours'];
                if($userWeeklyHours == null){
                  $userWeeklyHours = 0;
                }
                $shift_category = $this->findShiftCategory($unit_ids[1]);  
                $posts[] = array(
                    "id"                 =>  $unit_ids[0],
                    "shift_id"           =>  $unit_ids[1],
                    "shift_category"    =>  $shift_category,
                    "title"              =>  nl2br($title),
                    "unit_color"         =>  $unitColor['color_unit'],
                    "totalhours"         =>  '('.$userWeeklyHours.')',
                    "user_weeklyhours"   =>  $userWeeklyHours,
                    "designation_code"  => $userDetails[0]['group'],
                    "sort_order"  => $userDetails[0]['sort_order'],
                    "shift_shortcode"   => $unit_ids[6],
                    "shift_id"          => $unit_ids[1],
                    "designation_id"=>$userDetails[0]['designation_id']
                        );
               //print_r($posts);
            
          
            
            //get off day of a user
            $this->load->model('Workschedule_model');
            $offday = $this->Workschedule_model->getUserworkschedule($unit_ids[0]);
            $u_offday = array('user_id' => $unit_ids[0],'offday'=>$offday);
            array_push($user_offday, $u_offday); 
            /*$data['userWorkschedule']=$this->Workschedule_model->getUserworkschedule($unit_ids[0]);  
            if(count($data['userWorkschedule'])>0) 
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
            $response['offday']=$offday;  */
            /*if($unit_ids[4]){
              $other_unit = $unit_ids[4];
            }else{
              $other_unit = null;
            }*/
            /*$designationStatus = $this->Shift_model->designationStatus($unit_ids[0],$unitId);
            $assigned_status = $designationStatus['status'];
            data-status="'.$assigned_status.'"*/
            //get all shifts
            $result = $this->User_model->getSortOrder($unit_ids[0]);
            $shifts=$this->Shift_model->getShift(); 
            //print_r($unit_ids);exit();
            $shift_color=$this->Shift_model->findshiftcolor($unit_ids[6]);  
             if(empty($shift_color[0]['color_unit']))
              {  
                $text_color="#000";
              }
              else
              {  
                $text_color=$shift_color[0]['color_unit'];
              } 
            $shiftSelect = '<select  class="eventcls">'; 
            foreach ($shifts as $shift) {  
             $shift_color=$this->Shift_model->shift_color($shift['id']); 
             if(empty($shift_color[0]['color_unit']))
              {  
                $text_color="#000";
              }
              else
              {  
                $text_color=$shift_color[0]['color_unit'];
              }
                if($userDefaultShift==$shift['id'])
                    $selected = 'selected="selected"';
                else 
                    $selected =""; 
               if($shift['id']==0)
                    $offselect = 'offselect="1"'; else $offselect=''; 
              
                    $shiftSelect .= '<option>'.$shift['shift_shortcut'].'</option>';
            } 
            //$shiftSelect .= '<option data-hours="0" offselect="1" value="0">x</option>'; 
            $shiftSelect .= '</select>'; 
            $userShifts[] = array(
                "resourceId" =>  $unit_ids[0],
                "unitId"     =>  $unitId,
                "offDay"     =>  $offday,
                "title"      =>  $shiftSelect,
                "dow"        =>  '[ 0,1,2,3,4,5,6,7 ]',
                "from_unit"  =>  $other_unit
            ); 
           //print_r($userShifts); 
            $shiftSelect="";
            }
            $response['userShifts']=json_encode($userShifts);  
          }else{
            $this->session->set_flashdata('message','Maximum night count must be higher than minimum night count');
            redirect("admin/rota");
          }
          }else{
            $this->session->set_flashdata('message','Maximum day count must be higher than minimum day count');
            redirect("admin/rota");
          }
        }else{
          $this->session->set_flashdata('message','Please enter numeric values');
          redirect("admin/rota");
        }      
      /*}else{
        $this->session->set_flashdata('message','Please fill all mandatory fields');
        redirect("admin/rota");
      }*/
    }
    else
    { 
            $this->session->set_flashdata('message','Please select the unit or check the shift');
            redirect("admin/rota");
    }
    $units=$this->input->post('unit_id');
    $response['unit']=$this->User_model->findunitname($units); 
    $response['female_nurse_count']=$female_nurse_count; 
    $response['male_nurse_count']=$male_nurse_count; 
   
      $sort_users = array();
      foreach ($posts as $key => $row)
      {
          $sort_users['sort_order'][$key] = $row['sort_order'];
          $sort_users['shift_category'][$key] = $row['shift_category'];
          $sort_users['designation_id'][$key] = $row['designation_id'];
      }
     // array_multisort($sort_users, SORT_ASC, $posts);
    array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$posts);
        
    $response['posts'] = json_encode($posts);
    $parent_unit=$this->Rotaschedule_model->findparentunit($unitId);
    //print_r($parent_unit);exit();
    if($parent_unit==0)
    {
      $response['unitID']=$unitId; //unit id
    }
    else
    {
      $response['unitID']=$parent_unit; //unit id
    }
    //print_r($response['unitID']);exit();
    $response['shift_user_ids']=json_encode($shift_user_ids);
    $response['new_scheduled_user_count'] = json_encode($new_scheduled_user_count);
    $response['options']=1;
    $response['user_offday'] = json_encode($user_offday);
    //echo json_encode($response,TRUE);
    $zero_targeted_hours_shifts = json_encode($this->findAllZeroHoursShifts());
    $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
    $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
    $shiftsdata = $this->Shift_model->getShift();
    $response['holidayDates']=json_encode($holidayDates);
    $response['trainingDates']=json_encode($trainingDates);
    $response['main_unit_id']=$main_unit_id;
    $response['day_shift_min']=$this->input->post('day_shift_min');
    $response['day_shift_max']=$this->input->post('day_shift_max');
    $response['night_shift_min']=$this->input->post('night_shift_min');
    $response['night_shift_max']=$this->input->post('night_shift_max');
    $response['num_patients']=$this->input->post('num_patients');
    $response['designation']=$this->input->post('designation');  //1:1 patients
    $response['nurse_day_count']=$this->input->post('nurse_day_count');
    $response['nurse_night_count']=$this->input->post('nurse_night_count');

    $saved_rotas = $this->Rota_model->getUnpublishedDatesOfSingleUnit($unitId);
    $published_rotas = $this->Rota_model->getDatesOfSingleUnit($unitId);
    $response['saved_rotas']=json_encode($saved_rotas);
    //print_r($end_date);exit();
    //$selected_date=arra()
    if($end_date)
    {
     $response['selected_date']=json_encode($end_date); 
    }
    else
    {
      $response['selected_date']=array(); 
    }
    //print_r($response['end_date']);exit();
    $shift_gap=$this->Rota_model->getShiftgaphours();  
    $response['shift_hours']=json_encode($shift_gap); 
    $response['published_rotas']=json_encode($published_rotas); 
    if($up_rota_id){
      $updated_rota = $this->Rota_model->getRota($up_rota_id);
      $response['updated_rota']=json_encode($updated_rota);
    }else{
      $response['updated_rota']=[];
    }
    if($this->input->post('rota_settings')=='')
    { 
      $response['rota_settings']=$this->Rota_model->InsertRotaSettings($response);
    }
    else
    {
      $rs_id = $this->input->post('rota_settings');
      $update_result = $this->Rota_model->UpdateRotaSettings($response,$rs_id);
      $response['rota_settings']=$this->input->post('rota_settings');
    } 
    $response['new_status']=$this->input->post('new_status'); 
    $shift_cats = $this->getShiftDatas();
    $response['shift_cats']=json_encode($shift_cats);
    $response['action'] = $action;
    $designaton_names = $this->findAllDesignation();
    $response['designaton_names']=json_encode($designaton_names);
    $response['zero_targeted_hours_shifts']=json_encode($zero_targeted_hours_shifts);
    $response['userid_array_fordelete']=json_encode($userid_array_fordelete);
    $response['user_from_other_unit']=json_encode($user_from_other_unit);
    $response['session_id']=$session_id;
    $this->load->view("admin/rota/managerota",$response);   
    
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/test_fullcalendar.js','fullcalendar/scheduler.js','rota/test_rota.js');
    $this->load->view('includes/footer_rota',$result);
  }
  public function findAllZeroHoursShifts(){
    $shifts = $this->Shift_model->zeroTargetedHoursShifts();
    $zero_shifts = array();
    foreach ($shifts as $shift) {
      array_push($zero_shifts, $shift["id"]);
    }
    return $zero_shifts;
  }
  public function findAllDesignation(){
    $designations = $this->Designation_model->alldesignation();
    $des_names = array();
    foreach ($designations as $designation) {
      $des_names[$designation['id']]=$designation["designation_name"].'['.$designation["designation_code"].']';
    }
    return $des_names;
  }
  public function getShiftDatas(){
    $shifts =$this->Shift_model->getShift();
    $shift_cats = array();
    $break_hours = array();
    foreach ($shifts as $shift) {
      $shift_cats[$shift['id']]=$shift["shift_category"];
    }
    return $shift_cats;
  }
}?>