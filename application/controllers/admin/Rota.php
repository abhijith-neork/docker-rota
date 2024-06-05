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
        $this->load->model('Leave_model');
        $this->load->model('Rota_model');
        $this->load->model('Activity_model');
        $this->load->model('Training_model');
        $this->load->model('Agency_model');
        $this->load->model('Reports_model');
        $this->load->helper('form');
        $this->load->helper('name');
        $this->load->helper('settings');
        $this->load->model('Designation_model');
    }
  
    public function index()
  {
    $this->Rota_model->findUnitShift('',''); 
    $this->auth->restrict('Admin.Rota.Create');
    $data = array(); 
    $result=array();
    $this->load->helper('user'); 
    $data['error']='';
    $this->load->helper('form');
    
    // print("<pre>".print_r($data,true)."</pre>");exit();
            $this->load->library('form_validation');
            //Validating all required fields
            $this->form_validation->set_rules('wunit2', 'Unit', 'required');
            $data = array();
            $this->load->view('includes/home_header');
            if ($this->form_validation->run() == FALSE)
            {
                  $data['error']='';
                  $data['shifts']=array();
                  // if($this->session->userdata('user_type')==1) //super admn access
                  // { 
                  //   $data['unit'] = $this->User_model->fetchCategoryTree('');  
                  //  //$data['unit'] = $this->User_model->fetchunit('');  
                  // }
                  // else
                  // {
                  //  $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                  // }

                  $u_id=$this->session->userdata('user_id');  
                  $sub=$this->User_model->CheckuserUnit($u_id);
                  $unit=$this->User_model->findunitofuser($u_id); 
                  $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                  //if($this->session->userdata('user_type')==1) //all super admin can access
                  if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || in_array($this->session->userdata('user_type'),$this->config->item('unit_group_id')))
                  {
                    $data['unit'] = $this->User_model->fetchCategoryTree();  
                  }
                  // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
                  else if($this->session->userdata('subunit_access')==1)
                  { //if unit administrator
                    if($sub!=0 || $parent!=0) //unit administrator in sub unit
                    {   
                        if($sub==0)
                        {
                          $sub=$parent;
                        }
                        else
                        {
                          $sub=$sub;
                        }
                        $data['unit'] = $this->User_model->fetchSubTree($sub);  
                    }
                    else
                    {    
                        $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                    }

                  }
                  else
                  {
                     $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                    
                  }
                  

                  //print_r($data['unit']); 
                   $data['unit_select'] = 0;
                   $data['options']=1;
                   $data['session_id'] = $this->session->userdata('user_id');
                  $this->load->view("admin/rota/createschedule",$data); 
            }
            else
            {
                    $unit_id=$this->input->post('wunit2');
                    $this->load->model('Dashboard_model');

                    //check if selected unit is parent
                      $haveSubunits=$this->Dashboard_model->findBranches($unit_id);

                    if(count($haveSubunits) >0 ){
                      $data['unit_select'] ="";
      
                       $this->session->set_flashdata('message','You cannot select a unit having sub units.');



                          // if($this->session->userdata('user_type')==1) //super admn access
                          // {
                          //   $data['unit'] = $this->User_model->fetchCategoryTree('');  
                          //   //$data['unit'] = $this->User_model->fetchunit('');     
                          // }
                          // else
                          // {
                          //  $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                          // }

                          $u_id=$this->session->userdata('user_id');  
                          $sub=$this->User_model->CheckuserUnit($u_id);
                          $unit=$this->User_model->findunitofuser($u_id); 
                          $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                          //if($this->session->userdata('user_type')==1) //all super admin can access
                          if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || in_array($this->session->userdata('user_type'),$this->config->item('unit_group_id')))
                          {
                            $data['unit'] = $this->User_model->fetchCategoryTree();  
                          }
                          // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
                          else if($this->session->userdata('subunit_access')==1)
                          { //if unit administrator
                            if($sub!=0 || $parent!=0) //unit administrator in sub unit
                            {   
                                if($sub==0)
                                {
                                  $sub=$parent;
                                }
                                else
                                {
                                  $sub=$sub;
                                }
                                $data['unit'] = $this->User_model->fetchSubTree($sub);  
                            }
                            else
                            {    
                                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                            }

                          }
                          else
                          {
                             $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
                          } 
                          //print_r($data['unit']); exit();
                          // $data['shifts']=$this->User_model->findstaff($unit_id);
                          $data['shifts']=$this->Shift_model->getShiftwithoutOffday();  
                          $date=date('Y-m-d');
                          $data['start']=date('Y-m-d', strtotime($date.'last sunday'));
                          $data['end']=date('Y-m-d', strtotime($date.'next saturday')); 
                          //print_r($data['date']);exit(); 
                          //print_r($data['unit_select']);exit();
                          $data['options']=1;
                          $data['session_id'] = $this->session->userdata('user_id');
                    }
                    else{
                         $data['unit_select'] = $unit_id;
                   


                          // if($this->session->userdata('user_type')==1) //super admn access
                          // {
                          //   $data['unit'] = $this->User_model->fetchCategoryTree('');  
                          //   //$data['unit'] = $this->User_model->fetchunit('');     
                          // }
                          // else
                          // {
                          //  $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                          // }

                         $u_id=$this->session->userdata('user_id');  
                          $sub=$this->User_model->CheckuserUnit($u_id);
                          $unit=$this->User_model->findunitofuser($u_id); 
                          $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                          //if($this->session->userdata('user_type')==1) //all super admin can access
                          if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || in_array($this->session->userdata('user_type'),$this->config->item('unit_group_id')))
                          {
                            $data['unit'] = $this->User_model->fetchCategoryTree();  
                          }
                          // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
                          else if($this->session->userdata('subunit_access')==1)
                          { //if unit administrator
                            if($sub!=0 || $parent!=0) //unit administrator in sub unit
                            {   
                                if($sub==0)
                                {
                                  $sub=$parent;
                                }
                                else
                                {
                                  $sub=$sub;
                                }
                                $data['unit'] = $this->User_model->fetchSubTree($sub);  
                            }
                            else
                            {    
                                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                            }

                          }
                          else
                          {
                             $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
                          }

                          //print_r($data['unit']); exit();
                          // $data['shifts']=$this->User_model->findstaff($unit_id);
                          $data['shifts']=$this->Shift_model->getShiftwithoutOffday();  
                          $date=date('Y-m-d');
                          $data['start']=date('Y-m-d', strtotime($date.'last sunday'));
                          $data['end']=date('Y-m-d', strtotime($date.'next saturday')); 
                          //print_r($data['date']);exit(); 
                          //print_r($data['unit_select']);exit();
                          $data['options']=1;
                          $data['session_id'] = $this->session->userdata('user_id');
                    }
                    $this->load->view("admin/rota/createschedule",$data); 
            }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/  
    $result['js_to_load'] = array('rota/users.js','rota/jquery.redirect.js');
    $this->load->view('includes/footer_rota',$result);
  }
  public function rota()
  { 
    //$this->auth->restrict('Admin.Rota.Add');
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
    
    $this->load->model('Dashboard_model');

    //check if selected unit is parent
     $haveSubunits=$this->Dashboard_model->findBranches($user);
    
     if(count($haveSubunits) >0 ){
         $data['unit_select'] ="";
         
         $this->session->set_flashdata('message','You cannot select a unit having sub units.');
         redirect("admin/rota");
      }


    if($date_daily!='')
    {
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $end_date=$new_data;
    }
    $action = $this->input->post('action');
    $up_rota_id = $this->input->post('up_rota_id');
    $rota_lock_status = $this->checkRotaLock($up_rota_id);

    $payroll_user = $this->checkPayrollUser();
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
                     "gender"            =>   $unit_ids[7],
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
            $shiftSelect = '<select  class="eventcls" id="shift_'.$unit_ids[0].'" style="color:'.$text_color.'" data-shiftcode="'.$unit_ids[6].'" data-descode="'.$unit_ids[5].'" data-sortoder="'.$result[0]['sort_order'].'" data-gender="'.$result[0]['gender'].'">'; 
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
              
                    $shiftSelect .= '<option '.$offselect.' data-stime='.$shift['start_time'].' style="color:'.$text_color.'" data-etime='.$shift['end_time'].' data-hours='.$shift['targeted_hours'].' '.$selected.' data-partnumber="'.$shift['part_number'].'" value="'.$shift['id'].'" data-shifttype="'.$shift['shift_type'].'" data-breakhours='.$shift['unpaid_break_hours'].'>'.$shift['shift_shortcut'].'</option>';
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
    // $parent_unit=$this->Rotaschedule_model->findparentunit($unitId);
    // //print_r($parent_unit);exit();
    // if($parent_unit==0)
    // {
      $response['unitID']=$unitId; //unit id
    // }
    // else
    // {
    //   $response['unitID']=$parent_unit; //unit id
    // }
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
    $absent_shifts = $this->Shift_model->getAbsentSHifts();
    $response['absent_shifts']=json_encode($absent_shifts);
    $response['payroll_user'] = $payroll_user;
    $response['up_rota_id'] = $up_rota_id;
    $response['rota_lock_status'] = $rota_lock_status;
    $this->load->view("admin/rota/managerota",$response); 
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/rota.js');
    $this->load->view('includes/footer_rota',$result);
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
  public function findAllDesignation(){
    $designations = $this->Designation_model->alldesignation();
    $des_names = array();
    foreach ($designations as $designation) {
      $des_names[$designation['id']]=$designation["designation_name"].'['.$designation["designation_code"].']';
    }
    return $des_names;
  }
  public function findAllUnitsAndSubUnits(){
    $all_units = $this->Unit_model->findUnits();
    $unit_ids = array();
    foreach ($all_units as $unit) {
      $units = array();
      $unit_id = $unit['id'];
      array_push($units, (int)$unit_id);
      $sub_units = $this->Unit_model->findsubunits($unit_id);
      if(count($sub_units) > 0){
        for($i=0;$i<count($sub_units);$i++){
          $unit_ids[$sub_units[$i]['id']]=[(int)$sub_units[$i]['id']];
          array_push($units, (int)$sub_units[$i]['id']);
        }
      }
      $unit_ids[$unit_id]=$units;
    }
    return $unit_ids;
  }
 
  
  public function updateUsers(){ 
      $this->auth->restrict('Admin.Rota.Edit Max/Min');
      $data = array(); 
      $this->load->model('Rotaschedule_model');
      $this->load->view('includes/home_header');
      $rota_id = $this->uri->segment(4); 
      $rota_lock_status = $this->checkRotaLock($rota_id);
      $payroll_user = $this->checkPayrollUser();
      $unit_id = $this->uri->segment(5);
      $session_id = $this->uri->segment(6);
      $user_id = $this->session->userdata('user_id');
      $data['unit']=$this->Unit_model->findunit($unit_id);  
      $data['shifts']=$this->Shift_model->getShiftwithoutOffday();
      if($user_id ==1){//admin--admin can see all users under parent unit
        $assigned_users=$this->Rotaschedule_model->getAlreadyAssignedUsers($rota_id,'');
      }else{
        //manager. bcz manager can only see users who belongs to his unit if it is a subunit
        $assigned_users=$this->Rotaschedule_model->getAlreadyAssignedUsers($rota_id,$unit_id);
      }
      $data["assigned_usersid"] = json_encode($assigned_users['user_ids']); 
      $rota_settings = $this->Rotaschedule_model->getRotaSettings($rota_id);  
      $data['rota_settings'] = $rota_settings;
      $data['rota_id'] = $rota_id;
      // if($assigned_users['from_unit']==''){ $assigned_users['from_unit']=0;}
      // $data['from_unit'] = $assigned_users['from_unit'];
      //print_r($data['rota_settings']);exit();
      $data['session_id'] = $session_id; 
      $data['unit_select'] = $unit_id;
      $absent_shifts = $this->Shift_model->getAbsentSHifts();
      $data['absent_shifts']=json_encode($absent_shifts);
      $data['payroll_user'] = $payroll_user;
      $data['rota_lock_status'] = $rota_lock_status;
      $this->load->view("admin/rota/update_schedule",$data); 
      $result['js_to_load'] = array('rota/users.js','rota/jquery.redirect.js');
      $this->load->view('includes/footer_rota',$result);
    }

   public function editrotaview(){ //editrotaview rotasettings page loading

      // $this->auth->restrict('Admin.Rota.Update');
      $data = array(); 
      $this->load->model('Rotaschedule_model');
      $this->load->view('includes/home_header');
      $unit_id = $this->uri->segment(4);  
      $data['start_date'] = $this->uri->segment(5);   
      $data['end_date'] = $this->uri->segment(6);  
      $rota_id = $this->uri->segment(7); //print_r($rota_id);exit();  
      //$user_id = $this->session->userdata('user_id');
      $data['unit']=$this->Unit_model->findunit($unit_id);  
      $data['shifts']=$this->Shift_model->getShiftwithoutOffday();  
      $assigned_users=$this->Rotaschedule_model->getAlreadyAssignedUsers($rota_id,''); 
      $data["assigned_usersid"] = json_encode($assigned_users['user_ids']); 
      $rota_settings = $this->Rotaschedule_model->getRotaSettings($rota_id);   
      $data['rota_settings'] = $rota_settings;
      $data['rota_id'] = $rota_id;
      // if($assigned_users['from_unit']==''){ $assigned_users['from_unit']=0;}
      // $data['from_unit'] = $assigned_users['from_unit'];
      //print_r($data['rota_settings']);exit();
      $data['unit_select'] = $unit_id; 
      $this->load->view("admin/rota/editsettings",$data); 
      $result['js_to_load'] = array('rota/editrotasettings.js','rota/jquery.redirect.js');
      $this->load->view('includes/footer_rota',$result);
    }
    public function updateRotaLockStatus(){
      $status = $this->input->post("status");
      $rota_id = $this->input->post("rota_id");
      $result = $this->Rota_model->updateRotaLockStatus($status,$rota_id);
      echo json_encode($result);
      return;
    }
    public function checkRotaLock($rota_id){
      $result = $this->Rota_model->checkRotaLock($rota_id);
      if($result[0]['lock_status'] == 0){
        return true;
      }else{
        return false;
      }
    }
    public function checkPayrollUser(){
      if(in_array($this->session->userdata('email'),$this->config->item('lock_rota_emails')))
      {
        return true;
      }else{
        return false;
      }
    }
    public function editrotaviewbysettings()   ////loading editrota after rota settings editing
    {
      //print $this->session->userdata('email');exit;
     //print_r($this->input->post());exit();
     //$this->auth->restrict('Admin.Rota.Create');
     $this->load->view('includes/home_header');
     $this->load->model('Rotaschedule_model');
     $response=array();
     $params=array();
     $rotas=array();
     $shift_user_ids=array();
     $user_offday = array();
     $female_nurse_count = 0;
     $male_nurse_count = 0;
     $params['user_id']='';
     $params['unit_id']=$this->uri->segment(4);
     $params['start_date']=$this->uri->segment(5); 
     $params['end_date']=$this->uri->segment(6);
     $rota_id=$this->uri->segment(7);
     $rota_lock_status = $this->checkRotaLock($rota_id);
     $payroll_user = $this->checkPayrollUser();
     $session_id=$this->uri->segment(8);

      $rotas['day_shift_min']=$this->input->post('day_shift_min');
      $rotas['day_shift_max']=$this->input->post('day_shift_max');
      $rotas['night_shift_min']=$this->input->post('night_shift_min');
      $rotas['night_shift_max']=$this->input->post('night_shift_max');
      $rotas['num_patients']=$this->input->post('num_patients');
      $rotas['designation']=$this->input->post('designation'); 
      $rotas['nurse_day_count']=$this->input->post('nurse_day_count');
      $rotas['nurse_night_count']=$this->input->post('nurse_night_count'); 
      $rota_settings=$this->input->post('rota_settings'); 
      $rotas['action']= $this->input->post('action');
      $rotas['up_rota_id']= $this->input->post('up_rota_id');
      $update_result = $this->Rota_model->UpdateRotaSettings($rotas,$rota_settings);
      // print_r($update_result);exit();
     //print_r($params);exit();
     // $params['end_date']=date('Y-m-d', strtotime($this->uri->segment(6) . '-1 day'));; 
     //Added by chinchu
    $response['start_date'] = $this->uri->segment(5);
    $response['end_date'] = $this->uri->segment(6);
    $prev_date = date('Y-m-d', strtotime($response['start_date'] .' -1 day'));
    $next_date = date('Y-m-d', strtotime($response['end_date'] .' +1 day'));
    $rota_details = $this->Rota_model->getRota($rota_id);
    $response['published'] = $rota_details[0]['published'];
    if(count($rota_details) > 0 ){
      $rota_settings_id = $rota_details[0]['rota_settings'];
      $rota_settings_details = $this->Rota_model->getRotaSettings($rota_settings_id); 
    }
    /*Newly added for edit rota lock - Chinchu - 13-09-2023*/
    $dataform=array(
      'user_id'=>$this->session->userdata('user_id'),
      'unit_id'=>$params['unit_id'],
      'start_date'=>$params['start_date'],
      'end_date'=>$params['end_date'],
      'creation_date' => date('Y-m-d H:s:i'),
    );
    $existing_rota_lock = $this->Rota_model->checkRotaLockExist($dataform);
    $response['rota_edit_user'] = '';
    if(count($existing_rota_lock) > 0){
      if($existing_rota_lock[0]['user_id'] != $this->session->userdata('user_id')){
        $use_details = $this->User_model->getPersonalDetails($existing_rota_lock[0]['user_id']);
        $response['rota_edit_user'] = $use_details['fname'].' '.$use_details['lname'];
        $response['edit_permission'] = false;
      }else{
        $response['edit_permission'] = true;
      }
    }else{
      $rota_lock_result = $this->Rota_model->InsertRotaLockData($dataform);
      $response['edit_permission'] = true;
    }
    /*End*/
    /* newly added on june10th 2021 */

    $userdata=$this->Shift_model->GetUserIdMissingRotaEntry($params); //geting the userid's missing rotas
    $dates=GetDatesBetweenDates($params['start_date'],$params['end_date']); //dates btw start and end dates
    if(!empty($userdata))
    {
      foreach ($userdata as $user_id) {
      //print_r('<pre>');
          $rota_details=$this->Shift_model->getWeeklyShiftsByUser($params,$user_id); //rota details already in rota

          $rota_id=$rota_details[0]['rota_id'];


          if($rota_details[0]['from_unit']=='')
          {
             $from_unit=NULL;
          }
          else
          {
             $from_unit=$rota_details[0]['from_unit'];
          }

          if($rota_details[0]['designation_id']=='')
          {
             $designation_id=NULL;
          }
          else
          {
             $designation_id=$rota_details[0]['designation_id'];
          }

          for($i=0;$i<count($dates);$i++)
          {

            $rota=$this->Shift_model->getRotaDataByUser($user_id,$dates[$i],$params['unit_id']);

            if($rota=='1')
            {
                  //print_r($dates[$i]); print '<br>';
                  $timestamp = strtotime($dates[$i]);

                  $day = date('D', $timestamp);
                   //print_r($day); print '<br>';//exit();

                  $rotaschedule_data = array(
                                                'user_id'  => $user_id,
                                                'shift_id' => 0,
                                                'shift_hours' => 0,
                                                'additional_hours' =>NULL,
                                                'day_additional_hours' =>NULL,
                                                'night_additional_hours' =>NULL,
                                                'additinal_hour_timelog_id'=>NULL,
                                                'comment'=>NULL,
                                                'from_unit'=>$from_unit,
                                                'unit_id'=>$params['unit_id'],
                                                'rota_id'=>$rota_id,
                                                'date'=>$dates[$i],
                                                'status'=>1,
                                                'creation_date'=>date('Y-m-d H:i:s'),
                                                'created_userid'=>$params['created_userid'],
                                                'updation_date'=>date('Y-m-d H:i:s'),
                                                'updated_userid'=>$params['created_userid'],
                                                'day'=>$day,
                                                'designation_id'=>$designation_id,
                                                'shift_category'=>0,
                                                'from_userid'=>NULL,
                                                'from_rotaid'=>NULL,
                                                'request_id'=>NULL,
                                                'auto_insert'=>1
                    );
                  //print_r($rotaschedule_data); print '<br>';

                $rota_status=$this->Shift_model->inserRotascheduleDetails($rotaschedule_data);
            }
          }

        //print_r($user_id); print '<br>';
      }
    }

    //exit();
    
     $response['schedule']=$this->Shift_model->getWeeklyShifts($params);  
     $unitDetails = $this->Unit_model->findunit($this->uri->segment(4));
     if(count($unitDetails)>0){
      $response['staff_limit']=$unitDetails[0]['staff_limit']; 
    }else{
      $response['staff_limit']=0;
    }
    
     // print("<pre>".print_r($response['schedule'],true)."</pre>");exit();
     if(count($response['schedule'])==0)
     {
      $this->session->set_flashdata('message', 'Please select a scheduled unit.'); 
       redirect('admin/Rota/viewrota');
     }
     else
     {
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
    $designationStatus = $this->Shift_model->designationStatus($rota['user_id'],$this->uri->segment(4));
    /*$assigned_status = $designationStatus['status'];
    data-status="'.$assigned_status.'"*/
      $date_prev=date('Y-m-d', strtotime('-1 day', strtotime($rota['date'])));
      $end=$this->Shift_model->FindShiftendtime($date_prev,$rota['user_id']);
      $userDefaultShift = $rota['shift_id'];
      $shifts=$this->Shift_model->getShift();    
            $shiftSelect = '<select  class="eventcls"  id="shift_'.$rota['user_id'].'" style="color:'.$text_color.';" data-shiftcode="'.$rota['shift_name'].'" data-descode="'.$designationStatus['des_code'].'" data-sunitid="'.$rota['unit_id'].'" data-sortoder="'.$designationStatus['sort_order'].'" data-gender="'.$designationStatus['gender'].'" data-dateprev="'.$date_prev.'" data-timeprev="'.$end.'">';
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
                
                    if($userDefaultShift==''){ //fix for 10.4 issue added on May01-Siva
                        if($shift['id']==0)
                            $selected = 'selected="selected"';
                        else
                            $selected ="";
                    }
                     
                    
                
                    $shiftSelect .= '<option data-partnumber='.$shift['part_number'].' style="color:'.$text_color.';" data-breakhours='.$shift['unpaid_break_hours'].' data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' data-shifttype='.$shift['shift_type'].' data-hours='.$shift['targeted_hours'].' '.$selected.' value="'.$shift['id'].'">'.$shift['shift_shortcut'].'</option>';
            }
    $shiftSelect .= '</select>';
     //print_r($end);exit();
    $posts[] = array(
                    "resourceId"       =>  $rota['user_id'], 
                    "unit_id"          =>  $rota['unit_id'],
                    "title"            =>  $shiftSelect,
                    "prev_date"        =>  $date_prev,
                    "prev_time"        =>  $end,
                    "start"            =>  $rota['date'], 
                    "from_unit"        =>  $rota['from_unit'],
                    "end"              =>  $rota['date']

        
                    );  
    
    $shiftSelect="";
   }
/*    print "<pre>";
   print_r($posts);exit(); */
     $response['userShifts']=json_encode($posts);
     $response['resources']=$this->Shift_model->getStaffsUserid($params); 
     $prev_and_next_shifts = array();
     $all_previous_shifts = array();
     foreach($response['resources'] as $res)
     {
        $prev_next_shifts = $this->Shift_model->getPrevNextShift($prev_date,$next_date,$res['user_id']);
        $previous_shifts = $this->Shift_model->getPrevNextShiftForHighlightingColor($prev_date,$res['user_id']);
        if(count($prev_next_shifts) > 0){
          foreach($prev_next_shifts as $prev_next_shift){
            array_push($prev_and_next_shifts, $prev_next_shift);
          }
        }
        if(count($previous_shifts) > 0){
          foreach($previous_shifts as $previous_shift){
            array_push($all_previous_shifts, $previous_shift);
          }
        }
        $response['resource']=$this->Shift_model->getShiftsStaffs($res['user_id']);
        array_push($shift_user_ids, $res['user_id']);

        if($res['from_unit']=='')
        {
            foreach ($response['resource'] as $resources) {
              //get off day of a user
            $this->load->model('Workschedule_model');
            $offday = $this->Workschedule_model->getUserworkschedule($resources['id']);
            $u_offday = array('user_id' => $resources['id'],'offday'=>$offday);
            array_push($user_offday, $u_offday);
              if($resources['gender'] == "F" || $resources['gender'] == "f"){
                $female_nurse_count++;
              }else{
                $male_nurse_count++;
              }
            $users[] = array(
                      "id"                =>  $resources['id'], 
                      //"title"             =>  $resources['gender'].'-'.$resources['fname'].' '.$resources['lname'].'('.$resources['designation_code'].')'.'('.$resources['shift_shortcut'].')',
                       "title"       =>$resources['fname'].' '.$resources['lname'],
                       "gender"       =>$resources['gender'],
                      "unit_color"        =>  $resources['color_unit'],
                      "sort_order"        =>  $resources['sort_order'],
                      "shift_category"    =>  $resources['shift_category'],
                      "totalhours"        =>  "(".$resources['weekly_hours'].")",
                      "user_weeklyhours"  => $resources['weekly_hours'],
                      "designation_code"  => $resources['group'],
                      "shift_shortcode"   => $resources['shift_shortcut'],
                      "shift_id"          => $resources['shift_id'],
                      "designation_id"    => $resources['designation_id']
                            );
            }
        }   
        else
        {
            foreach ($response['resource'] as $resources) {
                //get off day of a user
              $this->load->model('Workschedule_model');
              $offday = $this->Workschedule_model->getUserworkschedule($resources['id']);
              $u_offday = array('user_id' => $resources['id'],'offday'=>$offday);
              array_push($u_offday,$resources['id']);
              // print("<pre>".print_r($resources,true)."</pre>");
              if($resources['gender'] == "F" || $resources['gender'] == "f"){
                $female_nurse_count++;
              }else{
                $male_nurse_count++;
              }
            $users[] = array(
                      "id"                 =>  $resources['id'], 
                      //"title"              =>  $resources['gender'].'-'.$resources['fname'].' '.$resources['lname'].'('.$resources['designation_code'].')'.'('.$resources['shift_shortcut'].')'.'('.$resources['unit_shortname'].')',
                       "title"       =>$resources['fname'].' '.$resources['lname'],
                       "gender"       =>$resources['gender'],
                        "unit_color"         =>  $resources['color_unit'],
                        "sort_order"        =>  $resources['sort_order'],
                        "shift_category"    =>  $resources['shift_category'],
                        "totalhours"         =>  "(".$resources['weekly_hours'].")",
                        "user_weeklyhours"   =>  $resources['weekly_hours'],
                        "designation_code"  => $resources['group'],
                        "shift_shortcode"   => $resources['shift_shortcut'],
                        "shift_id"          => $resources['shift_id'],
                        "designation_id"    => $resources['designation_id']
                            );
            }
        }
        
     }
      $sort_users = array(); 
     foreach ($users as $key => $row)
      {
          $sort_users['sort_order'][$key] = $row['sort_order'];
          $sort_users['shift_category'][$key] = $row['shift_category'];
          $sort_users['designation_id'][$key] = $row['designation_id'];
      }
     // array_multisort($sort_users, SORT_ASC, $posts);
    array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$users);
      $response['posts']=json_encode($users);  
      $response['rota_id']=$rota_id;
      $response['female_nurse_count']=$female_nurse_count;
      $response['male_nurse_count']=$male_nurse_count;
      if(count($rota_settings_details) > 0){
      $response['day_shift_min']=$rota_settings_details[0]['day_shift_min'];
      $response['day_shift_max']=$rota_settings_details[0]['day_shift_max'];
      $response['night_shift_min']=$rota_settings_details[0]['night_shift_min'];
      $response['night_shift_max']=$rota_settings_details[0]['night_shift_max'];
      $response['num_patients']=$rota_settings_details[0]['num_patients'];
      $response['designation']=$rota_settings_details[0]['one_one_patients'];
      $response['nurse_night_count']=$rota_settings_details[0]['nurse_night_count'];
      $response['nurse_day_count']=$rota_settings_details[0]['nurse_day_count'];
      $response['rota_settings']=$rota_settings_details[0]['id']; 
     }
     $shift_cats = $this->getShiftDatas();
     $response['shift_cats']=json_encode($shift_cats);
     $response['unit']=$this->User_model->findunitname($params['unit_id']);
     $response['unit_id'] = $params['unit_id'];
     $zero_targeted_hours_shifts = json_encode($this->findAllZeroHoursShifts());
     $response['zero_targeted_hours_shifts']=json_encode($zero_targeted_hours_shifts);
     $shift_gap=$this->Rota_model->getShiftgaphours();  
     $response['shift_hours']=json_encode($shift_gap); 
     $response['prev_and_next_shifts']=json_encode($prev_and_next_shifts); 
     $response['all_previous_shifts']=json_encode($all_previous_shifts); 
     $designaton_names = $this->findAllDesignation();
     $response['designaton_names']=json_encode($designaton_names);
     $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
     $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
     $response['holidayDates']=json_encode($holidayDates);
     $response['trainingDates']=json_encode($trainingDates);
     $response['session_id']=$session_id;
     $response['user_offday']=json_encode($user_offday);
     $response['payroll_user'] = $payroll_user;
     $response['rota_lock_status'] = $rota_lock_status;
     /*$rota_lock_status = $this->checkRotaLock($rota_id);
     $payroll_user = $this->checkPayrollUser();*/

     $absent_shifts = $this->Shift_model->getAbsentSHifts();
     $response['absent_shifts']=json_encode($absent_shifts);
     $this->load->view("admin/rota/editrotaview",$response);
     $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/moment-timezone-with-data.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/editrota.js');
     $this->load->view('includes/footer_rota',$result);
     }
    }

    public function updaterotabyuser()   ////loading update rota for UM & SM after rota settings editing
    {
     //print_r($this->input->post());exit();
     //$this->auth->restrict('Admin.Rota.Create');
     $this->load->view('includes/home_header');
     $this->load->model('Rotaschedule_model');
     $response=array();
     $params=array();
     $rotas=array();
     $shift_user_ids=array();
     $user_offday = array();
     $female_nurse_count = 0;
     $male_nurse_count = 0;
     $params['user_id']='';
     $params['unit_id']=$this->uri->segment(4);
     $params['start_date']=$this->uri->segment(5); 
     $params['end_date']=$this->uri->segment(6);
     $rota_id=$this->uri->segment(7);
     $session_id=$this->uri->segment(8);

      $rotas['day_shift_min']=$this->input->post('day_shift_min');
      $rotas['day_shift_max']=$this->input->post('day_shift_max');
      $rotas['night_shift_min']=$this->input->post('night_shift_min');
      $rotas['night_shift_max']=$this->input->post('night_shift_max');
      $rotas['num_patients']=$this->input->post('num_patients');
      $rotas['designation']=$this->input->post('designation'); 
      $rotas['nurse_day_count']=$this->input->post('nurse_day_count');
      $rotas['nurse_night_count']=$this->input->post('nurse_night_count'); 
      $rota_settings=$this->input->post('rota_settings'); 
      $rotas['action']= $this->input->post('action');
      $rotas['up_rota_id']= $this->input->post('up_rota_id');
      $update_result = $this->Rota_model->UpdateRotaSettings($rotas,$rota_settings);
      // print_r($update_result);exit();
     //print_r($params);exit();
     // $params['end_date']=date('Y-m-d', strtotime($this->uri->segment(6) . '-1 day'));; 
     //Added by chinchu
    $response['start_date'] = $this->uri->segment(5);
    $response['end_date'] = $this->uri->segment(6);
    $prev_date = date('Y-m-d', strtotime($response['start_date'] .' -1 day'));
    $next_date = date('Y-m-d', strtotime($response['end_date'] .' +1 day'));
    $rota_details = $this->Rota_model->getRota($rota_id);
    $response['published'] = $rota_details[0]['published'];
    if(count($rota_details) > 0 ){
      $rota_settings_id = $rota_details[0]['rota_settings'];
      $rota_settings_details = $this->Rota_model->getRotaSettings($rota_settings_id); 
    }
     $response['schedule']=$this->Shift_model->getWeeklyShifts($params);  
     $unitDetails = $this->Unit_model->findunit($this->uri->segment(4));
     if(count($unitDetails)>0){
      $response['staff_limit']=$unitDetails[0]['staff_limit']; 
    }else{
      $response['staff_limit']=0;
    }
    
     if(count($response['schedule'])==0)
     {
      $this->session->set_flashdata('message', 'Please select a scheduled unit.'); 
       redirect('admin/Rota/viewrota');
     }
     else
     {
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
    $designationStatus = $this->Shift_model->designationStatus($rota['user_id'],$this->uri->segment(4));
    /*$assigned_status = $designationStatus['status'];
    data-status="'.$assigned_status.'"*/
      $date_prev=date('Y-m-d', strtotime('-1 day', strtotime($rota['date'])));
      $end=$this->Shift_model->FindShiftendtime($date_prev,$rota['user_id']);
      $userDefaultShift = $rota['shift_id'];
      $shifts=$this->Shift_model->getShift();    
            $shiftSelect = '<select  class="eventcls"  id="shift_'.$rota['user_id'].'" style="color:'.$text_color.';" data-shiftcode="'.$rota['shift_name'].'" data-descode="'.$designationStatus['des_code'].'" data-sunitid="'.$rota['unit_id'].'" data-sortoder="'.$designationStatus['sort_order'].'" data-gender="'.$designationStatus['gender'].'" data-dateprev="'.$date_prev.'" data-timeprev="'.$end.'">';
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
                
                    if($userDefaultShift==''){ //fix for 10.4 issue added on May01-Siva
                        if($shift['id']==0)
                            $selected = 'selected="selected"';
                        else
                            $selected ="";
                    }
                     
                    
                
                    $shiftSelect .= '<option data-partnumber='.$shift['part_number'].' style="color:'.$text_color.';" data-breakhours='.$shift['unpaid_break_hours'].' data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' data-shifttype='.$shift['shift_type'].' data-hours='.$shift['targeted_hours'].' '.$selected.' value="'.$shift['id'].'">'.$shift['shift_shortcut'].'</option>';
            }
    $shiftSelect .= '</select>';
     //print_r($end);exit();
    $posts[] = array(
                    "resourceId"       =>  $rota['user_id'], 
                    "unit_id"          =>  $rota['unit_id'],
                    "title"            =>  $shiftSelect,
                    "prev_date"        =>  $date_prev,
                    "prev_time"        =>  $end,
                    "start"            =>  $rota['date'], 
                    "from_unit"        =>  $rota['from_unit'],
                    "end"              =>  $rota['date']

        
                    );  
    
    $shiftSelect="";
   }
/*    print "<pre>";
   print_r($posts);exit(); */
     $response['userShifts']=json_encode($posts);
     $response['resources']=$this->Shift_model->getStaffsUserid($params); 
     $prev_and_next_shifts = array();
     $all_previous_shifts = array();
     foreach($response['resources'] as $res)
     {
        $prev_next_shifts = $this->Shift_model->getPrevNextShift($prev_date,$next_date,$res['user_id']);
        $previous_shifts = $this->Shift_model->getPrevNextShiftForHighlightingColor($prev_date,$res['user_id']);
        if(count($prev_next_shifts) > 0){
          foreach($prev_next_shifts as $prev_next_shift){
            array_push($prev_and_next_shifts, $prev_next_shift);
          }
        }
        if(count($previous_shifts) > 0){
          foreach($previous_shifts as $previous_shift){
            array_push($all_previous_shifts, $previous_shift);
          }
        }
        $response['resource']=$this->Shift_model->getShiftsStaffs($res['user_id']);
        array_push($shift_user_ids, $res['user_id']);

        if($res['from_unit']=='')
        {
            foreach ($response['resource'] as $resources) {
              //get off day of a user
            $this->load->model('Workschedule_model');
            $offday = $this->Workschedule_model->getUserworkschedule($resources['id']);
            $u_offday = array('user_id' => $resources['id'],'offday'=>$offday);
            array_push($user_offday, $u_offday);
              if($resources['gender'] == "F" || $resources['gender'] == "f"){
                $female_nurse_count++;
              }else{
                $male_nurse_count++;
              }
            $users[] = array(
                      "id"                =>  $resources['id'], 
                      //"title"             =>  $resources['gender'].'-'.$resources['fname'].' '.$resources['lname'].'('.$resources['designation_code'].')'.'('.$resources['shift_shortcut'].')',
                       "title"       =>$resources['fname'].' '.$resources['lname'],
                       "gender"       =>$resources['gender'],
                      "unit_color"        =>  $resources['color_unit'],
                      "sort_order"        =>  $resources['sort_order'],
                      "shift_category"    =>  $resources['shift_category'],
                      "totalhours"        =>  "(".$resources['weekly_hours'].")",
                      "user_weeklyhours"  => $resources['weekly_hours'],
                      "designation_code"  => $resources['group'],
                      "shift_shortcode"   => $resources['shift_shortcut'],
                      "shift_id"          => $resources['shift_id'],
                      "designation_id"    => $resources['designation_id']
                            );
            }
        }   
        else
        {
            foreach ($response['resource'] as $resources) {
                //get off day of a user
              $this->load->model('Workschedule_model');
              $offday = $this->Workschedule_model->getUserworkschedule($resources['id']);
              $u_offday = array('user_id' => $resources['id'],'offday'=>$offday);
              array_push($resources['id'], $u_offday);
              // print("<pre>".print_r($resources,true)."</pre>");
              if($resources['gender'] == "F" || $resources['gender'] == "f"){
                $female_nurse_count++;
              }else{
                $male_nurse_count++;
              }
            $users[] = array(
                      "id"                 =>  $resources['id'], 
                      //"title"              =>  $resources['gender'].'-'.$resources['fname'].' '.$resources['lname'].'('.$resources['designation_code'].')'.'('.$resources['shift_shortcut'].')'.'('.$resources['unit_shortname'].')',
                       "title"       =>$resources['fname'].' '.$resources['lname'],
                       "gender"       =>$resources['gender'],
                        "unit_color"         =>  $resources['color_unit'],
                        "sort_order"        =>  $resources['sort_order'],
                        "shift_category"    =>  $resources['shift_category'],
                        "totalhours"         =>  "(".$resources['weekly_hours'].")",
                        "user_weeklyhours"   =>  $resources['weekly_hours'],
                        "designation_code"  => $resources['group'],
                        "shift_shortcode"   => $resources['shift_shortcut'],
                        "shift_id"          => $resources['shift_id'],
                        "designation_id"    => $resources['designation_id']
                            );
            }
        }
        
     }
      $sort_users = array(); 
     foreach ($users as $key => $row)
      {
          $sort_users['sort_order'][$key] = $row['sort_order'];
          $sort_users['shift_category'][$key] = $row['shift_category'];
          $sort_users['designation_id'][$key] = $row['designation_id'];
      }
     // array_multisort($sort_users, SORT_ASC, $posts);
    array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$users);
      $response['posts']=json_encode($users);  
      $response['rota_id']=$rota_id;
      $response['female_nurse_count']=$female_nurse_count;
      $response['male_nurse_count']=$male_nurse_count;
      if(count($rota_settings_details) > 0){
      $response['day_shift_min']=$rota_settings_details[0]['day_shift_min'];
      $response['day_shift_max']=$rota_settings_details[0]['day_shift_max'];
      $response['night_shift_min']=$rota_settings_details[0]['night_shift_min'];
      $response['night_shift_max']=$rota_settings_details[0]['night_shift_max'];
      $response['num_patients']=$rota_settings_details[0]['num_patients'];
      $response['designation']=$rota_settings_details[0]['one_one_patients'];
      $response['nurse_night_count']=$rota_settings_details[0]['nurse_night_count'];
      $response['nurse_day_count']=$rota_settings_details[0]['nurse_day_count'];
      $response['rota_settings']=$rota_settings_details[0]['id']; 
     }
     $shift_cats = $this->getShiftDatas();
     $response['shift_cats']=json_encode($shift_cats);
     $response['unit']=$this->User_model->findunitname($params['unit_id']);
     $response['unit_id'] = $params['unit_id'];
     $zero_targeted_hours_shifts = json_encode($this->findAllZeroHoursShifts());
     $response['zero_targeted_hours_shifts']=json_encode($zero_targeted_hours_shifts);
     $shift_gap=$this->Rota_model->getShiftgaphours();  
     $response['shift_hours']=json_encode($shift_gap); 
     $response['prev_and_next_shifts']=json_encode($prev_and_next_shifts); 
     $response['all_previous_shifts']=json_encode($all_previous_shifts); 
     $designaton_names = $this->findAllDesignation();
     $response['designaton_names']=json_encode($designaton_names);
     $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
     $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
     $response['holidayDates']=json_encode($holidayDates);
     $response['trainingDates']=json_encode($trainingDates);
     $response['session_id']=$session_id;
     $response['user_offday']=json_encode($user_offday);
     $absent_shifts = $this->Shift_model->getAbsentSHifts();
     $response['absent_shifts']=json_encode($absent_shifts);
     $this->load->view("admin/rota/updaterotaview",$response);
     $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/moment-timezone-with-data.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/updaterota.js');
     $this->load->view('includes/footer_rota',$result);
     }
    }


    public function findAllZeroHoursShifts(){
    $shifts = $this->Shift_model->zeroTargetedHoursShifts();
    $zero_shifts = array();
    foreach ($shifts as $shift) {
      array_push($zero_shifts, $shift["id"]);
    }
    return $zero_shifts;
  }

    public function findUsersOnOtherUnit(){
      $this->load->model('Rotaschedule_model');
      $users_frm_other_unit = json_decode($this->input->post("users_frm_other_unit")); 
      $count = count($users_frm_other_unit);   
      $final_array = array();
      
      for($i=0;$i<$count;$i++){
        $result = $this->Rotaschedule_model->getUserDetails($users_frm_other_unit[$i]->user_id);  
        $unit_color=$this->Unit_model->getUnitcolor($users_frm_other_unit[$i]->from_unit);
        $data = array(
          'color_unit' => $unit_color,
          'result' => $result,
          'from_unit' => $users_frm_other_unit[$i]->from_unit 
        );
        array_push($final_array, $data);
      }
      header('Content-Type: application/json');
      echo json_encode(array('final_result' => $final_array));
      exit();
  }
  public function viewrota() //edit rota data loading
  {
    $this->auth->restrict('Admin.Rota.Edit');
    $this->load->model('Rotaschedule_model');
    $response = array();
    $posts = array();
    $shift_user_ids=array();
    $header['title'] = 'Add'.'shifts';
    $this->load->view('includes/home_header',$header); 
    $response['error']='';
    $response['unit_select'] = 0;
    $response['unit'] ='';
    $resources=array();
    // if($this->session->userdata('user_type')==1) //super admn access
    // {
    //   $response['units'] = $this->User_model->fetchCategoryTree();   
    //   //$response['units'] = $this->User_model->fetchunit();
    // }
    // else
    // {
    //   $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
    // }

    $u_id=$this->session->userdata('user_id');
    $is_supervisor = $this->User_model->checkUserIsSupervisor($u_id); 
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id);
     $parent = 0;
     if(count($unit) > 0){
      $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
     }
    //if($this->session->userdata('user_type')==1) //all super admin can access
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || in_array($this->session->userdata('user_type'),$this->config->item('unit_group_id')))
    {
      $response['units'] = $this->User_model->fetchCategoryTree();  
    }
    // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
    else if($this->session->userdata('subunit_access')==1)
    { //if unit administrator
      if($sub!=0 || $parent!=0) //unit administrator in sub unit
      {   
          if($sub==0)
          {
            $sub=$parent;
          }
          else
          {
            $sub=$sub;
          }
          $response['units'] = $this->User_model->fetchSubTree($sub);  
      }
      else
      {    
          $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
      }

    }
    else
    {
       $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
      
    }

    $response['posts'] = '';
    $response['rotaId'] ='';
    //print_r($this->input->post());
    $this->load->model('Shift_model');
    $this->load->model('Unit_model');
     $params=array();
    $posts=array();
    $user_offday=array();
    $params['user_id']='';
    $params['unit_id']=$this->input->post("unit_id"); 
    $params['start_date']=$this->input->post("start"); 
    $params['end_date']=$this->input->post("end");
    //march7
    $params['end_date'] = date('Y-m-d', strtotime('1 day', strtotime($this->input->post("end"))));
     //print_r($params);exit();
   // print_r($this->input->post("end"));
    if($this->input->post("unit_id")>0)
    {
        $unitDetails = $this->Unit_model->findunit($this->input->post("unit_id"));  
        $response['unit_name']=$unitDetails[0]['unit_name'];
        $response['staff_limit']=$unitDetails[0]['staff_limit'];
    }
    else
    {
        $response['unit_name']='';
        $response['staff_limit']=0;
    }
    
    
    if($this->input->post("start")==""){
        
        $day = date('w');
        $day= $day;
        $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
        $params['start_date']=$week_start;
        $response['start_date']=json_encode($week_start);
    }
    else
    {
      $response['start_date']=json_encode($this->input->post("start"));
    }
    if($this->input->post("end")==""){
        $day = date('w');
       // $day= $day-1; march7
        $day= $day+1;
        $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
        $params['end_date']=$week_end;
    }
     
/*     $params['unit_id']=1;
    $params['start_date']='2019-08-05';
    $params['end_date']='2019-08-12'; */
    // print_r($params);

    
    $paramsrota = array();
    // $findparent=$this->Rotaschedule_model->findparentunit($this->input->post("unit_id"));
    // if($findparent==0)
    // {
      $paramsrota['unit_id']=$this->input->post("unit_id");
    // }
    // else
    // {
    //   $paramsrota['unit_id']=$findparent;
    // }

    $paramsrota['start_date']=$this->input->post("start"); 
    // march7 $paramsrota['end_date']= date('Y-m-d', strtotime('-1 day', strtotime($this->input->post("end"))));
    $paramsrota['end_date']= $this->input->post("end");
    
   //print_r($paramsrota); exit;
    $rotaDetails=$this->Shift_model->getRota($paramsrota);
    if(count($rotaDetails) > 0){
      $response['rota_status']=$rotaDetails[0]['published'];
    }
    
    
    $response['schedule']=$this->Shift_model->getWeeklyShifts($params);    //print_r($response['schedule']);exit(); 
    $resource=$this->Shift_model->getWeeklyShiftsStaffs($params);
    if($resource!='')
    { 
      foreach ($resource as $source) 
      {
          //get off day of a user
          $this->load->model('Workschedule_model');
          $offday = $this->Workschedule_model->getUserworkschedule($source['id']);
          $u_offday = array('user_id' => $source['id'],'offday'=>$offday);
          array_push($user_offday, $u_offday);  
            if($source['from_unit']=='')
            { 
              $resources[]=array(
                    "title"       =>  $source['title'],
                    "sort_order"  =>  $source['sort_order'],
                    "shift_category" => $source['shift_category'],
                    "id"          =>  $source['id'],
                    "from_unit"   =>  $source['from_unit'], 
                    "designation_code" => $source['group'],
                    "shift_id" => $source['default_shift'], 
                    "designation_id" => $source['designation_id'], 
                ); 
            }
            else
            { 
              $unit_shortname=$this->Unit_model->getShiftsUnitShortname($source['from_unit']);   
              $resources[]=array(
                    "title"       =>  $source['title'].' '.'('.$unit_shortname[0]['unit_shortname'].')',
                    "sort_order"  =>  $source['sort_order'],
                    "shift_category" => $source['shift_category'],
                    "id"          =>  $source['id'],
                    "designation_code" => $source['group'],
                    "shift_id" => $source['default_shift'],
                    "designation_id" => $source['designation_id'],  
                    ""
                );
            } 
      }  
    }
    if($response['schedule']!='')
    {
    foreach ($response['schedule'] as $rota)
    { 
      //print '<pre>';
      array_push($shift_user_ids, $rota['user_id']);
      $color = $rota['background_color'];
      $shift_color=$this->Shift_model->shift_color($rota['shift_id']);
      if(empty($shift_color[0]['color_unit']))
      {  
        $text_color="#000";
      }
      else
      {  
        $text_color=$shift_color[0]['color_unit'];
      }
      // echo date('l', strtotime($date));

      if($rota['from_unit']>0 && $rota['shift_id'] < 4){ // if user original unit have schedule get that
                
          //print '<pre>';

          $prms['unit_id']= $rota['from_unit'];
          $prms['user_id']= $rota['user_id'];
          $prms['date']= $rota['date'];
         // print_r($prms); print '<br>';
          $getDayShift=$this->Shift_model->getDayRotaByUserandUnit($prms); 
          if($getDayShift)
          {

             /*    if($getDayShift[0]['user_id']==211){
                    print_r($getDayShift);
                    exit;
                }  
                 */
                $shift_color_main=$this->Shift_model->shift_color($getDayShift[0]['shift_id']);
                if(empty($shift_color_main[0]['color_unit']))
                {
                    $text_color="#000";
                }
                else
                {
                    $text_color=$shift_color_main[0]['color_unit'];
                }
                //get unit name
                if($getDayShift[0]['unit_id']>0)
                $unit_shortname=$this->Unit_model->getShiftsUnitShortname($getDayShift[0]['unit_id']);
                
                $title_day = '<span class="shift_'.$getDayShift[0]['user_id'].'_'.$getDayShift[0]['date'].' show_count select_offday_'.$getDayShift[0]['user_id'].'_'.strtolower(date('l', strtotime($getDayShift[0]['date']))).'"  data-shiftname="'.$getDayShift[0]['shift_shortcur'].'" style="color:'.$text_color.'" data-shiftid="'.$getDayShift[0]['shift_id'].'" data-date="'.$getDayShift[0]['date'].'" data-color="'.$color.'" data-rotaid="'.$getDayShift[0]['rota_id'].'" data-resourceid="'.$getDayShift[0]['user_id'].'" data-unitid="'.$getDayShift[0]['unit_id'].'">'.$unit_shortname[0]['unit_shortname']."-".$getDayShift[0]['shift_shortcut'].'<span>';
               
                if($getDayShift[0]['date']!='' && $getDayShift[0]['user_id']>0){
                    $posts[] = array(
                        "resourceId"       =>  $getDayShift[0]['user_id'],
                        "unit_id"          =>  $getDayShift[0]['unit_id'],
                        "title"            =>  $title_day,
                        "start"            =>  $getDayShift[0]['date'],
                        "end"              =>  $getDayShift[0]['date'],
                        "text_color"       =>  $text_color,
                        "color"            =>  $color
                    ); 
                }
            }
            else
            {

                $title = '<span class="shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'"  data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-color="'.$color.'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'<span>';
                
                if($rota['date']!='' && $rota['user_id']>0){
                    $posts[] = array(
                        "resourceId"       =>  $rota['user_id'],
                        "unit_id"          =>  $rota['unit_id'],
                        "title"            =>  $title,
                        "start"            =>  $rota['date'],
                        "end"              =>  $rota['date'],
                        "text_color"       =>  "red",
                        "color"            =>  $color
                        
                    ); 
                }

            }
          
      }else{
          $title = '<span class="shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'"  data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-color="'.$color.'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'<span>';
          
          if($rota['date']!='' && $rota['user_id']>0){
              $posts[] = array(
                  "resourceId"       =>  $rota['user_id'],
                  "unit_id"          =>  $rota['unit_id'],
                  "title"            =>  $title,
                  "start"            =>  $rota['date'],
                  "end"              =>  $rota['date'],
                  "text_color"       =>  "red",
                  "color"            =>  $color
                  
              ); 
          }
      }
        

        //$response['rotaId'] = $rota['rota_id'];
    }
    // print_r($posts);
    // exit();
  }
  if(count($rotaDetails) > 0){
    $response['rotaId'] = $rotaDetails[0]['id'];
  }
  
    $response['weekEvents']=json_encode($posts);
    if(!empty($resources))
    { 
      $sort_users = array();
      foreach ($resources as $key => $row)
      {
          $sort_users['sort_order'][$key] = $row['sort_order'];
          $sort_users['shift_category'][$key] = $row['shift_category'];
          $sort_users['designation_id'][$key] = $row['designation_id'];
      } 
      array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$resources); 
      //print_r($resources);exit();
    }
    else
    {
      $resources=$resources;
    } 
    $response['resources']=json_encode($resources);
    $designaton_names = $this->findAllDesignation();
    $response['designaton_names']=json_encode($designaton_names);
    $response['session_id']=$this->session->userdata('user_id');
    $response['is_supervisor']=$is_supervisor;
    if(count($shift_user_ids) > 0){
      $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
      $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
      $response['holidayDates']=json_encode($holidayDates);
      $response['trainingDates']=json_encode($trainingDates);
    }else{
      $response['holidayDates']=[];
      $response['trainingDates']=[];
    }
    $response['user_offday']=json_encode($user_offday);
    $absent_shifts = $this->Shift_model->getAbsentSHifts();
    $response['absent_shifts']=json_encode($absent_shifts);

    //print_r($response);exit(); 
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $this->load->view("admin/rota/viewrota",$response);
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/viewrota.js');
    $this->load->view('includes/footer_rota',$result);
  }
  public function getweekData()
  {
   $this->load->model('Shift_model');
   $params=array();
   $posts=array();
   $params['user_id']='';
   $rotaId="";
   $params['unit_id']=$this->input->post("unit_id");  
   $params['start_date']=$this->input->post("start_date");  
   $params['end_date']=$this->input->post("end_date");  
   //print_r($params); exit();
   $response['schedule']=$this->Shift_model->getWeeklyShifts($params); //print_r($response['schedule']);exit();
   //print_r($response['schedule']);exit();
   //Edited by chinchu 
   // $resource=$this->Shift_model->getWeeklyShiftsStaffs($params);
   $resource=$this->Shift_model->getWeeklyShiftsOfStaffs($params); 
   //print_r($resource);exit();
    foreach ($resource as $source) 
    {  
      //Edited by chinchu 
      // $title = $source['gender'].'-'.$source['fname'].' '.$source['lname'].'['.$source['weekly_hours'].']'.'['.$source['designation_code'].']'.'['.$source['shift_shortcut'].']';
      $title = $source['fname'].' '.$source['lname'];
          if($source['from_unit']=='')
          { 
            $resources[]=array(
                  "title"       =>  $title,//Edited by chinchu 
                  "sort_order"  =>  $source['sort_order'],
                  "shift_category" => $source['shift_category'],
                  "id"          =>  $source['id'],
                   "designation_code" => $source['group'],
                  "shift_id" => $source['default_shift'],
                  "designation_id" => $source['designation_id']
       
              ); 
          }
          else
          { 
            $unit_shortname=$this->Unit_model->getShiftsUnitShortname($source['from_unit']); 
            $resources[]=array(
                  "title"       =>  $title,//Edited by chinchu 
                  "sort_order"  =>  $source['sort_order'],
                  "shift_category" => $source['shift_category'],
                  "id"          =>  $source['id'], 
                  "from_unit"   =>  $source['from_unit'],
                  "designation_code" => $source['group'],
                  "shift_id" => $source['default_shift'],
                  "designation_id" => $source['designation_id']

              );
          } 
    }  
   
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
      $title = '<span class="show_count" data-color="'.$text_color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'<span>';

        $posts[] = array(
            "resourceId"       =>  $rota['user_id'],
            "unit_id"          =>  $rota['unit_id'],
            "title"            =>  $title, 
            "start"            =>  $rota['date'],
            "end"              =>  $rota['date'],
            "text_color"       =>  "red", 
            
        ); 
       $rotaId = $rota['rota_id']; 
   }
 
   if(!empty($resources))
    {  
      $sort_users = array();
      foreach ($resources as $key => $row)
      {
          $sort_users['sort_order'][$key] = $row['sort_order'];
          $sort_users['shift_category'][$key] = $row['shift_category'];
          $sort_users['designation_id'][$key] = $row['designation_id'];
      } 
      array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$resources); 
      //print_r($resources);exit();
    }
    else
    {  
      $resources='';
    } 
    
    //$response['resources']=json_encode($resources);  
    // $response['resources']=json_encode($ordered_user);
   // $response['userShifts']=json_encode($posts); 
   header('Content-Type: application/json');
   echo json_encode(array("resources"=>$resources,"events"=>$posts,'rotaId'=>$rotaId));
        exit(); 
    
}



/* function to get resources only for a week
Author: Siva */
public function getweekResources()
{
    $this->load->model('Shift_model');
    $params=array();
    $posts=array();
    $resource=array();
    $resources=array();
    $user_offday=array();
    $params['user_id']='';
    $rotaId="";
    $params['unit_id']=$this->input->post("unit_id");
    $params['start_date']=$this->input->post("start_date");
    $params['end_date']=$this->input->post("end_date");
    //print_r($params); exit();
     //print_r($response['schedule']);exit();
    //Edited by chinchu
    // $resource=$this->Shift_model->getWeeklyShiftsStaffs($params);
    if($this->input->post("unit_id")!=''){
        
    
        $resource=$this->Shift_model->getWeeklyShiftsOfStaffs($params);
       
        if(count($resource)>0){
            foreach ($resource as $source)
            {
              //get off day of a user
              $this->load->model('Workschedule_model');
              $offday = $this->Workschedule_model->getUserworkschedule($source['id']);
              $u_offday = array('user_id' => $source['id'],'offday'=>$offday);
              array_push($user_offday, $u_offday);
                //Edited by chinchu
                // $title = $source['gender'].'-'.$source['fname'].' '.$source['lname'].'['.$source['weekly_hours'].']'.'['.$source['designation_code'].']'.'['.$source['shift_shortcut'].']';
                $title = $source['fname'].' '.$source['lname'];
                if($source['from_unit']=='')
                {
                    $resources[]=array(
                        "title"       =>  $title,//Edited by chinchu
                        "sort_order"  =>  $source['sort_order'],
                        "shift_category" => $source['shift_category'],
                        "id"          =>  $source['id'],
                        "designation_code" => $source['group'],
                        "shift_id" => $source['default_shift'],
                        "designation_id" => $source['designation_id']
                        
                    );
                }
                else
                {
                    $unit_shortname=$this->Unit_model->getShiftsUnitShortname($source['from_unit']);
                    $resources[]=array(
                        "title"       =>  $title,//Edited by chinchu
                        "sort_order"  =>  $source['sort_order'],
                        "shift_category" => $source['shift_category'],
                        "id"          =>  $source['id'],
                        "from_unit"   =>  $source['from_unit'],
                        "designation_code" => $source['group'],
                        "shift_id" => $source['default_shift'],
                        "designation_id" => $source['designation_id']
                        
                    );
                }
            }   
        }
     
        
        if(!empty($resources))
                {
            $sort_users = array();
            foreach ($resources as $key => $row)
            {
                $sort_users['sort_order'][$key] = $row['sort_order'];
                $sort_users['shift_category'][$key] = $row['shift_category'];
                $sort_users['designation_id'][$key] = $row['designation_id'];
            }
            array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$resources);
            //print_r($resources);exit();
        }
        else
        {
            $resources='';
        }   
    }
    //$response['resources']=json_encode($resources);
    // $response['resources']=json_encode($ordered_user);
    // $response['userShifts']=json_encode($posts);
    header('Content-Type: application/json');
    echo json_encode(array("resources"=>$resources,"events"=>$posts,'rotaId'=>$rotaId,"user_offday"=>$user_offday));
    exit();
    
}
/* function to get week schedule only for a week
 Author: Siva */
public function getweekSchedule()
{
    $this->load->model('Shift_model');
    $params=array();
    $posts=array();
    $resources=array();
    $params['user_id']='';
    $rotaId="";
    $params['unit_id']=$this->input->post("unit_id");
    $params['start_date']=$this->input->post("start_date");
    $params['end_date']=$this->input->post("end_date");
    //print_r($params); exit();
    $response['schedule']=$this->Shift_model->getWeeklyShifts($params);

    $u_id=$this->session->userdata('user_id');
    $is_supervisor = $this->User_model->checkUserIsSupervisor($u_id); 
    $user_group=$this->session->userdata('user_type'); 
    $permission=checkbuttonaccess('Admin.Editrota.Edit/Update Unpublished',$user_group); //print_r($abc);exit();

    foreach ($response['schedule'] as $rota)
    {
       $color=$rota['background_color'];
       $shift_color=$this->Shift_model->shift_color($rota['shift_id']);
        if(empty($shift_color[0]['color_unit']))
        {
            $text_color="#000";
        }
        else
        {
            $text_color=$shift_color[0]['color_unit'];
        }
        $title = '<span class="shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$text_color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-rotaid="'.$rota['rota_id'].'" data-color="'.$color.'" data-resourceid="'.$rota['user_id'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'<span>';
        
        $posts[] = array(
            "resourceId"       =>  $rota['user_id'],
            "unit_id"          =>  $rota['unit_id'],
            "title"            =>  $title,
            "start"            =>  $rota['date'],
            "end"              =>  $rota['date'],
            "text_color"       =>  "red",
            "color"            =>  $color
            
        );
        $status=$rota['published'];
        $rotaId = $rota['rota_id'];
    }
 
    header('Content-Type: application/json');
    echo json_encode(array("events"=>$posts,'rotaId'=>$rotaId,'status'=>$status,'supervisor'=>$is_supervisor,'permission'=> $permission));
    exit();
    
}
public function getRotaId()
{ 
   $this->load->model('Shift_model');
   $params['unit_id']=$this->input->post("unit_id"); 
   $params['start_date']=$this->input->post("start_date");  
   $params['end_date']=$this->input->post("end_date");  
   $date =$params['end_date']; // For today/now, don't pass an arg.
   $params['end_date']= date( "Y-m-d", strtotime( $date . "-1 day"));
   $rota=$this->Shift_model->FindRotaId($params);
   //print_r($rota);exit();
   $rota_id=$rota[0]['id'];
   header('Content-Type: application/json');
   echo json_encode($rota_id);
        exit();
}
public function getRota()
  {
   $this->load->model('Shift_model');
   $params=array();
   $posts=array();
   $params['user_id']='';
   
   $params['unit_id']=$this->input->post("unit_id");
   $rotaId="";
   $params['start_date']=$this->input->post("start_date");  
   $params['end_date']=$this->input->post("end_date");   
   $response['schedule']=$this->Shift_model->getWeeklyShifts($params); 
   foreach ($response['schedule'] as $rota)
   {
     
        
       $unitDetails = $this->Unit_model->findunit($rota['unit_id']);
       $userDetails = $this->User_model->findusername($rota['user_id']); 
       foreach ($userDetails as $value) {
       
       if($rota['shift_name']=='X')
           $time = 'Offday';
           else
               $time = $rota['start_time']."-".$rota['end_time'];
               
               $posts[] = array(
                   "unit_id"          =>  $rota['unit_id'],
                   "title"            =>  $rota['shift_name'],
                   "unit_name"        =>  $unitDetails[0]['unit_name'],
                   "name"             =>  $value['fname'].' '.$value['lname'],
                   "start"            =>  $rota['date']."T".date("H:i", strtotime($rota['start_time'])),
                   "end"              =>  $rota['date']."T".date("H:i", strtotime($rota['end_time'])),
                   "stime"            =>  $rota['start_time'],
                   "etime"            =>  $rota['end_time'],  
                   "time"             =>  $time,
                   "unit_color"       =>  $unitDetails[0]['color_unit']
                   
                   
               );
       }
              
   }
   // $response['userShifts']=json_encode($posts); 
   header('Content-Type: application/json');
   echo json_encode($posts); 
   exit(); 
    
}
public function findAvlShift($avl_shifts,$shift_id){
  $shift_name = '' ;
  $count = count($avl_shifts);
  for($i=0;$i<$count;$i++){
    if($avl_shifts[$i]['id'] == $shift_id){
      $shift_name = $avl_shifts[$i]['shift_shortcut'];
    }
  }
  return $shift_name;
}
public function staffs()
  {
    $this->auth->restrict('Admin.Report.Availability');
    $response = array();
    $posts = array();
    $header['title'] = 'Add'.'shifts';
    $this->load->view('includes/home_header',$header); 
    $response['error']='';
    $response['unit_select'] = 0;
      
    $resources=array();

    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
    {
              $response['units'] = $this->User_model->fetchCategoryTree();  
    }
    // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
    else if($this->session->userdata('subunit_access')==1)
    { //if unit administrator
    if($sub!=0 || $parent!=0) //unit administrator in sub unit
    {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
               $response['units'] = $this->User_model->fetchSubTree($sub);  
    }
    else
             {    
                $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

   }
    else
    {
           $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
    }

    // if($this->session->userdata('user_type')==1) //super admn access
    // {
    //   $response['units'] = $this->User_model->fetchCategoryTree();  
    // }
    // else
    // {
    //   $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
    // }
    $response['jobrole']=$this->Reports_model->fetchjobrole(); 
    $response['posts'] = '';
    $response['rotaId'] =''; 
    $this->load->model('Shift_model');
    $this->load->model('Unit_model');
    $this->load->model('Staffrota_model');
    $params=array();
    $posts=array();
    $param=array();
    $unit_id_array = array();
    array_push($unit_id_array, $this->input->post("unit"));
    $params['user_id']=''; 
    $params['unit_id']=$this->input->post("unit");
    $params['jobrole']=$this->input->post("jobrole"); 
   
    if($this->input->post("year"))
        $start_year=$this->input->post("year");  
    else
        $start_year=date("Y");
    if($this->input->post("month"))
    $start_month=$this->input->post("month"); 
    else
        $start_month=date("m");
    //print_r(date("Y"));
    if($this->input->post("year")=='')
    {
      $response['start_year']=date("Y");
    }
    else
    {
      $response['start_year']=$this->input->post("year"); 
    } 
    if($this->input->post('month')=='')
        {
            $response['month']=date('m');
        }
        else
        {
             $response['month']=$this->input->post('month');
        }
    $params['month']=$this->input->post("month"); 
    $param['start_date']=$start_year.'-'.$start_month.'-'.'01';  //print_r($param['start_date']);exit();
    $param['end_date'] = date("Y-m-t", strtotime($param['start_date']));  //print_r($param['end_date']);exit();
    $response['start_date']=date("d-m-Y", strtotime($param['start_date'])); 
    $response['end_date']=date("d-m-Y", strtotime($param['end_date']));
    $date1=date_create($response['end_date']);
    $date2=date_create($response['start_date']);
    $diff = date_diff($date1,$date2);
    $days = $diff->format("%a")+1;
    $avl_shifts = $this->Shift_model->getAvlShifts();
    if($days>=28)
    {
      $response['week']=5;
    }
    else
    {
      $response['week']=4;
    }
    $response['default_date']=$param['start_date'];  
    if($this->input->post("unit_id")>0)
    {
        $unitDetails = $this->Unit_model->findunit($this->input->post("unit_id"));
        $response['unit_name']=$unitDetails[0]['unit_name'];
        $response['staff_limit']=$unitDetails[0]['staff_limit']; 
    }
    else
    {
        $response['unit_name']='';
        $response['staff_limit']=0;
    }
    if($this->input->post("start")==""){ 
        $day = date('w');
        $day= $day;
        $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
        $param['start_date']=$week_start;
    }
    if($this->input->post("end")==""){
        $day = date('w');
        $day= $day-1;
        $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
        $param['end_date']=$week_end;
    }
    //print_r($param['start_date']);
    //print_r($param['end_date']);exit();
    $branch['unit']=$this->Unit_model->findUnitBranch($params['unit_id']);
    $count =  count($branch['unit']);
    if($count > 0) {
      for($i=0;$i<$count;$i++){
        array_push($unit_id_array, $branch['unit'][$i]['id']);
      }      
    }
    $date_start=$start_year.'-'.$start_month.'-'.'01'; ///print_r($date_start);exit();
        $date = new DateTime($date_start);
        $new_startdate=$date->modify('-1 week');
        $params['s_date']=$new_startdate->format('Y-m-d'); 
    $date_end = date("Y-m-t", strtotime($date_start)); //print_r($date_end);exit();
        $date = new DateTime($date_end);
        $new_enddate=$date->modify('+1 week');
        $params['e_date']=$new_enddate->format('Y-m-d'); 

    for($i=0;$i<count($unit_id_array);$i++)
    {
      $params['unit_id']=$unit_id_array[$i];
      //print_r($params);exit();
      $response['schedule']=$this->Staffrota_model->getWeeklyShiftsView($params); //print_r($response['schedule']);exit();
      $resource=$this->Staffrota_model->getWeeklyShiftsStaffsView($params);  
      foreach ($resource as $source) 
      {  
        $resources[]=array(
          "title"       =>  $source['title'],
          "id"          =>  $source['id']
        ); 
      }
      foreach ($response['schedule'] as $rota)
      { 
        if($rota['shift_name']){
          $title = $rota['shift_name'];
        }else{
          $title = $this->findAvlShift($avl_shifts,$rota['shift_id']);
        }
        $posts[] = array(
          "resourceId"       =>  $rota['user_id'],
          "unit_id"          =>  $rota['unit_id'],
          "title"            =>  $title, 
          "start"            =>  $rota['date'],
          "end"              =>  $rota['date']
        ); 
        $response['rotaId'] = $rota['rota_id']; 
      }      
    }   
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/ 
    $response['weekEvents']=json_encode($posts);  //print_r($response['weekEvents']);exit();
    $response['resources']=json_encode($resources);
    $this->load->view("admin/rota/staff_availability",$response);
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/staff_availability.js');
    $this->load->view('includes/footer_rota',$result);
  }
  public function excelView(){
    $response = array();
    $posts = array();
    $result = array();
    $response = array();
    $params = array();
    $response['rota'] = array();
    $jobrole_ids_array = array();
    $export_type = $this->uri->segment(4);
    $params['year']=$this->uri->segment(5);
    $params['month']=$this->uri->segment(6);
    $params['unit_id']=$this->uri->segment(7);
    $jobrole_ids = $this->uri->segment(8);
    $dateObj   = DateTime::createFromFormat('!m',$params['month']);
    $monthName = $dateObj->format('F');
    $new_start_date = date('Y-m-d', strtotime('First Sunday Of'.' '.$monthName.' '.$params['year']));
    $date_array = $this->weekOfMonthFromDate($params['year'].'-'.$params['month']);
    $date_array_count = count($date_array);
    $month_end_week = $date_array[$date_array_count-1];
    $new_end_date_array = explode('_',$month_end_week);
    $new_end_date = $new_end_date_array[1];
    $response['new_start_date'] = $new_start_date;
    $response['new_end_date'] = $new_end_date;
    if($jobrole_ids){
      $jobrole_ids = urldecode($jobrole_ids);
      $jobrole_ids_array = explode(",", $jobrole_ids);
    }
    $result = $this->Rota_model->gerRotaDetailsForPrintOption($params);
    $response['rota'] = $result;
    $response['job_ids'] = $jobrole_ids_array;
    $response['unit_id'] = $this->uri->segment(7);
    $response['month'] = $this->uri->segment(6);
    $response['year'] = $this->uri->segment(5);
    $response['export_type'] = $export_type;
    $response['unit_details'] = $this->Unit_model->findunit($params['unit_id']);
    $header['title'] = 'View'.'Print';
    $this->load->view('includes/home_header',$header);
    if($export_type == 1){
      $this->load->view("admin/rota/print_view",$response);
    }else{
      $this->load->view("admin/rota/print_view_horizontal",$response);
    }
    $this->load->view('includes/footer_rota',$result);
  }
  public function printReport(){
    $response = array();
    $posts = array();
    $result = array();
    $response = array();
    $params = array();
    $response['rota'] = array();
    $jobrole_ids_array = array();
    $params['unit_id']=$this->uri->segment(6);
    $params['month']=$this->uri->segment(5);
    $params['year']=$this->uri->segment(4);
    $jobrole_ids = $this->uri->segment(7);
    if($jobrole_ids){
      parse_str($jobrole_ids,$jobrole_ids_array);
    }
    $dateObj   = DateTime::createFromFormat('!m',$params['month']);
    $monthName = $dateObj->format('F');
    $new_start_date = date('Y-m-d', strtotime('First Sunday Of'.' '.$monthName.' '.$params['year']));
    $date_array = $this->weekOfMonthFromDate($params['year'].'-'.$params['month']);
    $date_array_count = count($date_array);
    $month_end_week = $date_array[$date_array_count-1];
    $new_end_date_array = explode('_',$month_end_week);
    $new_end_date = $new_end_date_array[1];
    $response['new_start_date'] = $new_start_date;
    $response['new_end_date'] = $new_end_date;
    $result = $this->Rota_model->gerRotaDetailsForPrintOption($params);
    $response['rota'] = $result;
    $response['job_ids'] = $jobrole_ids_array;
    $response['unit_id'] = $this->uri->segment(6);
    $response['month'] = $this->uri->segment(5);
    $response['year'] = $this->uri->segment(4);
    $response['unit_details'] = $this->Unit_model->findunit($params['unit_id']);
    $response['export_type'] = '0';
    $header['title'] = 'View'.'Print';
    $this->load->view('includes/home_header',$header);
    $this->load->view("admin/rota/print_view",$response);
    //$result['js_to_load'] = array('rota/print_view.js');
    $this->load->view('includes/footer_rota',$result);
  }
  public  function multi_array_search($array, $search)
  {
      
      // Create the result array
      $result = array();
      
      // Iterate over each array element
      foreach ($array as $key => $value)
      {
          
          // Iterate over each search condition
          foreach ($search as $k => $v)
          {
              
              // If the array element does not meet the search condition then continue to the next element
              if (!isset($value[$k]) || $value[$k] != $v)
              {
                  continue 2;
              }
              
          }
          
          // Add the array element's key to the result array
          $result[$key] = $array['shift_id'];
          
      }
      
      // Return the result array
      return $result;
      
  }
  public function returnUserUnit($user_id){
    $unit_id = $this->Shift_model->returnUserUnit($user_id);
    return $unit_id;
  }
 public function editrota()  //view rota
  {
    $this->auth->restrict('Admin.Rota.View');
    $response = array();
    $posts = array();
    $header['title'] = 'Add'.'shifts';
    $this->load->view('includes/home_header',$header); 
    $response['error']='';
    $response['unit_select'] = 0;
      
    $resources=array();
    $u_id=$this->session->userdata('user_id');
    $login_user_designation = $this->User_model->finduserDesignation($u_id); 
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent = 0;
    if(count($unit) > 0){
      $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    }
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18 || in_array($this->session->userdata('user_type'),$this->config->item('unit_group_id'))) //all super admin can access
    {
      $response['units'] = $this->User_model->fetchCategoryTree();  
    }
    // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==10 || $this->session->userdata('user_type')==9)
    else if($this->session->userdata('subunit_access')==1)
    { //if unit administrator,unit manager,unit supervisor
      if($sub!=0 || $parent!=0) //in sub unit or in parent unit
      {   
          if($sub==0)
          {
            $sub=$parent;
          }
          else
          {
            $sub=$sub;
          }
          $response['units'] = $this->User_model->fetchSubTree($sub);  
      }
      else
      {    
          $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
      }

    }
    else
    {
       $response['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
      
    }  
    $response['posts'] = '';
    $response['rotaId'] =''; 
    $this->load->model('Shift_model');
    $this->load->model('Unit_model');
    $this->load->model('Designation_model');
    $this->load->model('Rotaschedule_model');
    $params=array();
    $posts=array();
    $param=array();
    $jobe_roles=array();
    $rota_dates= array();
    $shift_user_ids= array();
    $params['user_id']=''; 
    $user_offday = array();
    $params['unit_id']=$this->input->post("unit");
    
    if($this->input->post("year")=='')
    {
      $response['start_year']=date("Y");
    }
    else
    {
      $response['start_year']=$this->input->post("year"); 
    }
    
    if($this->input->post("month")=='')
    {
      $response['smonth']=date("m");
    }
    else
    {
      $response['smonth']=$this->input->post("month"); 
    } 
    
    $start_year=$this->input->post("year");
    $start_month=$this->input->post("month");
    $params['month']=$this->input->post("month");
    $params['job_role_id'] = $this->input->post("jobrole"); 
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");   
    $params['year'] = $this->input->post("year");
    $param['start_date']=$start_year.'-'.$start_month.'-'.'01';  
    $param['end_date'] = date("Y-m-t", strtotime($param['start_date']));
    $response['start_date']=date("d-m-Y", strtotime($param['start_date'])); 
    $response['end_date']=date("d-m-Y", strtotime($param['end_date']));
    $rota_dates = $this->Rota_model->getDates($params);
    //print_r($rota_dates);exit();
    $response['rota_dates']=json_encode($rota_dates);
    
    $date1=date_create($response['end_date']);
    $date2=date_create($response['start_date']);
    $diff = date_diff($date1,$date2);
    $days = $diff->format("%a")+1; 
    
    
    
    if($days>=28)
    {
      $response['week']=5;
    }
    else
    {
      $response['week']=4;
    }
    
    if($this->input->post("year")=='')
    {
      $response['default_date']=date('Y-m-d');
    } 
    else
    {
      $response['default_date']=$param['start_date'];  
    }
    
    
    if($this->input->post("unit_id")>0)
    {
        $unitDetails = $this->Unit_model->findunit($this->input->post("unit_id"));
        $response['unit_name']=$unitDetails[0]['unit_name'];
        $response['staff_limit']=$unitDetails[0]['staff_limit'];
        
    }
    else
    {
        $response['unit_name']='';
        $response['staff_limit']=0;
    }
    
    //print_r($response);exit();
    $response['unit_id']=$this->input->post("unit");
    if($this->input->post("start")==""){ 
        $day = date('w');
        $day= $day;
        $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
        $param['start_date']=$week_start;
    }
    if($this->input->post("end")==""){
        $day = date('w');
        $day= $day-1;
        $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
        $param['end_date']=$week_end;
    }
    $branch['unit']=$this->Unit_model->findUnitBranch($params['unit_id']); 
    //Added by chinchu. Which find the first sunday by specifying the selected month and year
    if(!$start_month){
      $start_month = date('m');
    }
    if(!$start_year){
      $start_year = date('Y');
    }
    $dateObj   = DateTime::createFromFormat('!m', $start_month);
    $monthName = $dateObj->format('F');
    $new_start_date = date('Y-m-d', strtotime('First Sunday Of'.' '.$monthName.' '.$start_year));
    $date_array = $this->weekOfMonthFromDate($start_year.'-'.$start_month);
    $date_array_count = count($date_array);
    $month_end_week = $date_array[$date_array_count-1];
    $new_end_date_array = explode('_',$month_end_week);
    $new_end_date = $new_end_date_array[1];

    $datediff = strtotime($new_end_date) - strtotime($new_start_date);
    $totalDays =  round($datediff / (60 * 60 * 24))+1;
    
    // //print_r($branch['unit']);exit();
     if(empty($branch['unit'])) //no branch
    {    
       
        $sdate = strtotime($response['start_date']);
        $sdate = strtotime("-7 day", $sdate);
        $params['start_date'] = date("Y-m-d", $sdate);  
        $edate = strtotime($response['end_date']);
        $edate = strtotime("+7 day", $edate);
        $params['end_date'] = date("Y-m-d", $edate);
        //Added by chinchu. Start date become the date of first sunday of the specified month and year
        $params['start_date'] = $new_start_date;
        $params['end_date'] = $new_end_date;
        $response['schedule']=$this->Shift_model->getWeeklyShiftsOfEmptyBranch($params,$jobe_roles);
      
       // print_r($response['schedule']);exit();
        // $count=0;
          
        $resource=$this->Shift_model->getWeeklyShiftsViewOfEmptyBranch($params,$jobe_roles);    
        
          foreach ($resource as $source)
          {  
            array_push($shift_user_ids, $source['user_id']);
            //get off day of a user
            $this->load->model('Workschedule_model');
            $offday = $this->Workschedule_model->getUserworkschedule($source['user_id']);
            $u_offday = array('user_id' => $source['user_id'],'offday'=>$offday);
            array_push($user_offday, $u_offday);
            if($source['from_unit']=='')
            { 
              $resources[]=array(
                    //"title"       =>  $source['gender'].'-'.$source['fname'].' '.$source['lname'].'['.$source['weekly_hours'].']'.'['.$source['shift_shortcut'].']',
                    "title"       =>$source['fname'].' '.$source['lname'],
                    "sort_order"  =>  $source['sort_order'],
                    "shift_category" => $source['shift_category'],
                    "id"          =>  $source['user_id'],
                    "from_unit"   =>  $source['from_unit'], 
                    "unit_id"   =>  $source['unit_id'],
                    "designation_code" =>   $source['group'], 
                    "shift_id" =>   $source['default_shift'],
                    "designation_id"=>$source['designation_id'],
                    "totalhours"        =>  "(".$source['weekly_hours'].")",
                    "user_weeklyhours"  => $source['weekly_hours'],
                    'user_id' => $source['user_id']
                ); 
            }
            else
            {
              $unit_shortname=$this->Unit_model->getShiftsUnitShortname($source['from_unit']);  
              $resources[]=array(
                   // "title"       =>  $source['gender'].'-'.$source['fname'].' '.$source['lname'].'['.$source['weekly_hours'].']'.'['.$source['shift_shortcut'].']'.'['.$unit_shortname[0]['unit_shortname'].']',
                    "title"       =>$source['fname'].' '.$source['lname'],
                    "id"          =>  $source['user_id'], 
                    "sort_order"  =>  $source['sort_order'],
                    "shift_category" => $source['shift_category'],
                    "unit_id"   =>  $source['unit_id'],
                    "designation_code" =>   $source['group'], 
                    "shift_id" =>   $source['default_shift'],
                    "designation_id"=>$source['designation_id'],
                    "totalhours"        =>  "(".$source['weekly_hours'].")",
                    "user_weeklyhours"  => $source['weekly_hours'],
                    'user_id' => $source['user_id']
                );
            } 
          }
          // print_r($response['schedule']);exit();
          foreach ($response['schedule'] as $rota)
              {  
                  if($rota['from_unit']!='')
                  {
                    if($rota['unit_id']!=$params['unit_id']){
                      $shift_shortcut=$this->Unit_model->findShiftsUnitShortname($rota['unit_id']); 
                      $shift_shortcutBranch = $shift_shortcut[0]['unit_shortname']."-";

                    }
                    else
                    {
                      $shift_shortcutBranch="";
                    }
                  } 
                  else{
                    $shift_shortcutBranch="";
                  }
                 
                  $daily_sense=$this->Shift_model->daily_sense($rota['user_id'],$rota['date']);  //print_r("<pre>"); print_r($rota['user_id']); print_r($daily_sense);
                  if(!empty($daily_sense))
                  {
                    $c=1;
                    foreach ($daily_sense as $sense) { 
                      $comment.= $c.". ".$sense['comment']."<br>"; 
                      $c++;
                    }
                    
                  }
                  else
                  {
                      $comment='';
                  }
                  
                  $shift_color=$this->Shift_model->shift_color($rota['shift_id']);
                   if(empty($shift_color[0]['color_unit']))
                  {  
                     $text_color="#000";
                  }
                  else
                  {  
                     $text_color=$shift_color[0]['color_unit'];
                  }
                    if($rota['published']==1)
                    {                      //AWOL                     //S
                      if($rota['shift_id'] == 4 || $rota['shift_id'] == 3 || $rota['shift_id'] == 93){  
                      // if($rota['shift_name'] == "AWOL" || $rota['shift_name'] == "S"){

                        /*if($rota['shift_id'] == 4){
                          $color = "#FF0000";
                        }else if($rota['shift_id'] == 3){
                          $color = "#adf802";
                        }else{
                          $color = "#DB7093";
                        }*/
                        $color = $rota['background_color'];
                        $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        $user_status=$this->Agency_model->checkAgencyStaffAssigned($rota['user_id'],$rota['unit_id'],$rota['date']);
                        $staff_user_status=$this->Agency_model->checkSystemStaffAssigned($rota['unit_id'],$rota['date']);
                        if($user_status == "true")
                        {
                          if($staff_user_status == "true"){
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }else{
                            $color = "#008000";
                            // $title = $rota['shift_name'];
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                          }
                        }else{
                          $color = "#008000";
                          // $title = $rota['shift_name'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                        }
                      }else{                 //X                       //H  
                        if($rota['shift_id'] == 0 || $rota['shift_id'] == 1 || $rota['shift_id'] == 2){//T
                        // if($rota['shift_name'] == "X" || $rota['shift_name'] == "H" || $rota['shift_name'] == "T"){
                          // $color = "#ffff1";
                          $color = $rota['background_color'];
                          if($rota['shift_id'] == 2){
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_shift show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }else{
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }
                          
                        }else{
                          $agency_status=$this->Agency_model->checkUserIsAgencyStaff($rota['user_id']);
                          if($agency_status == "true")
                          {
                            // $color = "#ffff2";
                            $color = $rota['background_color'];
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }else{
                            // $color = "#ffff3";
                            $color = $rota['background_color'];
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_shift show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$shift_shortcutBranch.$rota['shift_name'].'</span>';
                          }
                          
                        }
                      } 
                      /*if($rota['shift_name'] == "AWOL" || $rota['shift_name'] == "S"){
                        $color = "#FF0000";
                      }else{
                        $color = "#90ee90";
                      }*/
                   $s =$this->multi_array_search($posts, array('start' =>$rota['date'], 'resourceId' => $rota['user_id']));
                      if(count($s)>0){
                          if($s['shift_id']<$rota['shift_id']){
                              $keyS = array_search ($s['shift_id'], $s);
                              //print $keyS." ---KEY";
                               unset($posts[$keyS]); 
                               $posts = array_values($posts); 
                          }
                      }  
                      $posts[] = array(
                          "shift_id"         =>  $rota['shift_id'],
                          "resourceId"       =>  $rota['user_id'],
                          "unit_id"          =>  $rota['unit_id'],
                          "title"            =>  $title, 
                          "tooltip"          =>  $comment,
                          "start"            =>  $rota['date'],
                          "end"              =>  $rota['date'],
                          "color"            =>  $color,
                          "from_unit"        =>  $rota['from_unit'],
                          "user_unit_id"     => $this->returnUserUnit($rota['user_id'])
                      );
                    }
                    else
                    {
                      if($rota['shift_id'] == 4 || $rota['shift_id'] == 3 || $rota['shift_id'] == 93){
                      /*if($rota['shift_id'] == 4){
                      // if($rota['shift_name'] == "AWOL" || $rota['shift_name'] == "S"){
                      //   if($rota['shift_name'] == "AWOL"){
                          $color = "#FF0000";
                        }else if($rota['shift_id'] == 3){
                          $color = "#adf802";
                        }else{
                          $color = "#DB7093";
                        }*/
                        $color = $rota['background_color'];
                        // $color = "#FF0000";
                        $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        $user_status=$this->Agency_model->checkAgencyStaffAssigned($rota['user_id'],$rota['unit_id'],$rota['date']);
                        $staff_user_status=$this->Agency_model->checkSystemStaffAssigned($rota['unit_id'],$rota['date']);
                        if($user_status == "true")
                        {
                          if($staff_user_status == "true"){
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }else{
                            $color = "#008000";
                            // $title = $rota['shift_name'];
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                          }
                        }else{
                          $color = "#008000";
                          // $title = $rota['shift_name'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                        }
                      }else{
                        if($rota['shift_id'] == 0 || $rota['shift_id'] == 1 || $rota['shift_id'] == 2){//T
                        // if($rota['shift_name'] == "X" || $rota['shift_name'] == "H" || $rota['shift_name'] == "T"){
                          $color = "#add8e6";
                          // $color = $rota['background_color'];
                          if($rota['shift_id'] == 2){
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_shift show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }else{
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }
                          
                        }else{
                          $agency_status=$this->Agency_model->checkUserIsAgencyStaff($rota['user_id']);
                          if($agency_status == "true")
                          {
                            $color = "#add8e6";
                            // $color = $rota['background_color'];
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-shifttype="'.$rota['shift_type'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                          }else{
                            $color = "#add8e6";
                            // $color = $rota['background_color'];
                            $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_shift show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$shift_shortcutBranch.$rota['shift_name'].'</span>';
                          }
                        }
                      }
                       $t =$this->multi_array_search($posts, array('start' =>$rota['date'], 'resourceId' => $rota['user_id']));
                      if(count($t)>0){
                          if($t['shift_id']<$rota['shift_id']){
                              $keyT = array_search ($t['shift_id'], $t);
                              //print $keyS." ---KEY";
                              unset($posts[$keyT]);
                              $posts = array_values($posts);
                          }
                      }  
                      $posts[] = array(
 
                          "shift_id"         =>  $rota['shift_id'],
                          "resourceId"       =>  $rota['user_id'],
                          "unit_id"          =>  $rota['unit_id'],
                          "title"            =>  $title, 
                          "tooltip"          =>  $comment,
                          "start"            =>  $rota['date'],
                          "end"              =>  $rota['date'],
                          "color"            =>  $color,
                          "from_unit"        =>  $rota['from_unit'],
                          "user_unit_id"     => $this->returnUserUnit($rota['user_id'])
 
                    ); 
                  }
                  
                  $response['rotaId'] = $rota['rota_id'];  
                 
                  $comment='';
                  
          }    //print_r("<pre>");print_r($posts);  print count($posts);
          //exit();

       $response['weekEvents']=json_encode($posts); 
         if(!empty($resources))
      { 
        $sort_users = array();
        foreach ($resources as $key => $row)
        {
            $sort_users['sort_order'][$key] = $row['sort_order'];
            $sort_users['shift_category'][$key] = $row['shift_category'];
            $sort_users['designation_id'][$key] = $row['designation_id'];
        } 
        array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$resources); 
        //print_r($resources);exit();
      }
      else
      {
        $resources=$resources;
      }  
         $response['resources']=json_encode($resources);
              
    }
    else
    {  //branch
      //print_r($branch['unit']);exit();
            // foreach ($branch['unit'] as $value)
            // {  
                 //$params['unit']=$params['unit_id'];
                 //$params['unit_from_sub']=$value['id'];
                 $sdate = strtotime($response['start_date']);
                 $sdate = strtotime("-7 day", $sdate);
                 $params['start_date'] = date("Y-m-d", $sdate);
                 $edate = strtotime($response['end_date']);
                 $edate = strtotime("+7 day", $edate);
                 $params['end_date'] = date("Y-m-d", $edate); 
                //Added by chinchu. Start date become the date of first sunday of the specified month and year
                $params['start_date'] = $new_start_date;
                $params['end_date'] = $new_end_date;
                 $response['schedule']=$this->Shift_model->getWeeklyShiftsView($params,$jobe_roles);  
                 $scheduleCount=$this->Shift_model->getWeeklyShiftsViewCount($params,$jobe_roles);
                 
                 foreach ($scheduleCount as $sCount){
                     
                     if($sCount['rcount'] > $totalDays || $sCount['rcount'] < $totalDays ){
                         
                         $rC[] = $sCount['user_id'];
                     }
                 }
                 
                 $resource=$this->Shift_model->getWeeklyShiftsStaffsView($params,$jobe_roles);   
                 //$short_name=$value['unit_shortname']; 
                   foreach ($resource as $source) 
                 {  
                  array_push($shift_user_ids, $source['user_id']);
                  //get off day of a user
                  $this->load->model('Workschedule_model');
                  $offday = $this->Workschedule_model->getUserworkschedule($source['user_id']);
                  $u_offday = array('user_id' => $source['user_id'],'offday'=>$offday);
                  array_push($user_offday, $u_offday);
                   $resources[]=array(
                        //"title"       =>   $source['gender'].'-'.$source['fname'].' '.$source['lname'].'['.$source['weekly_hours'].']'.'['.$source['shift_shortcut'].']'.'['.$source['unit_shortname'].']',
                        "title"       =>$source['fname'].' '.$source['lname'],
                        "id"          =>  $source['user_id'],
                        "sort_order"  =>  $source['sort_order'],
                        "shift_category" => $source['shift_category'],
                        "from_unit"   =>  $source['from_unit'],
                        "unit_id"     =>  $source['unit_id'],
                        "designation_code" =>   $source['group'], 
                      "shift_id" =>   $source['default_shift'],
                      "designation_id"=>$source['designation_id'],
                      "totalhours"        =>  "(".$source['weekly_hours'].")",
                      "user_weeklyhours"  => $source['weekly_hours'],
                      'user_id' => $source['user_id']
                    ); 
                  }
                  foreach ($response['schedule'] as $rota)
                  { 
                  if($rota['from_unit']!='')
                  {
                    if($rota['unit_id']!=$params['unit_id']){
                      $shift_shortcut=$this->Unit_model->findShiftsUnitShortname($rota['unit_id']); 
                      $shift_shortcutBranch = $shift_shortcut[0]['unit_shortname']."-";

                    }
                    else
                    {
                      $shift_shortcutBranch="";
                    }
                  } 
                  else{
                    $shift_shortcutBranch="";
                  }
                    $daily_sense=$this->Shift_model->daily_sense($rota['user_id'],$rota['date']);  
                    if(!empty($daily_sense[0]['comment']))
                    {
                      $c=1;
                      foreach ($daily_sense as $sense) { 
                        $comment.= $c.". ".$sense['comment']."<br>"; 
                        $c++;
                      }
                      
                    }
                    else
                    {
                        $comment='';
                    }
                    $shift_color=$this->Shift_model->shift_color($rota['shift_id']);
                     if(empty($shift_color[0]['color_unit']))
                    {  
                       $text_color="#000";
                    }
                    else
                    {  
                       $text_color=$shift_color[0]['color_unit'];
                    }
                    if($rota['published']==1)
                  {
                    if($rota['shift_id'] == 4 || $rota['shift_id'] == 3 || $rota['shift_id'] == 93){
                      /*if($rota['shift_id'] == 4){
                      // if($rota['shift_name'] == "AWOL" || $rota['shift_name'] == "S"){
                      //   if($rota['shift_name'] == "AWOL"){
                        $color = "#FF0000";
                      }else if($rota['shift_id'] == 3){
                          $color = "#adf802";
                        }else{
                          $color = "#DB7093";
                        }*/
                      // $color = "#FF0000";
                        $color = $rota['background_color'];
                      $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                      $user_status=$this->Agency_model->checkAgencyStaffAssigned($rota['user_id'],$rota['unit_id'],$rota['date']);
                      $staff_user_status=$this->Agency_model->checkSystemStaffAssigned($rota['unit_id'],$rota['date']);
                      if($user_status == "true")
                      {
                        if($staff_user_status == "true"){
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }else{
                          $color = "#008000";
                          // $title = $rota['shift_name'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                        }
                      }else{
                        $color = "#008000";
                        // $title = $rota['shift_name'];
                        $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                      }
                    }
                    else
                    {
                      if($rota['shift_id'] == 0 || $rota['shift_id'] == 1 || $rota['shift_id'] == 2){//T
                      // if($rota['shift_name'] == "X" || $rota['shift_name'] == "H" || $rota['shift_name'] == "T"){
                        // $color = "#ffff4";
                        $color = $rota['background_color'];
                        if($rota['shift_id'] == 2){
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_shift select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }else{
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'"  data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }
                        
                      }else{
                       $agency_status=$this->Agency_model->checkUserIsAgencyStaff($rota['user_id']);
                        if($agency_status == "true")
                        {
                          // $color = "#ffff5";
                          $color = $rota['background_color'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }else{
                          // $color = "#ffff6";
                          $color = $rota['background_color'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_shift show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$shift_shortcutBranch.$rota['shift_name'].'</span>';
                        }
                      }
                    }
                    
                    //$days
                    if(in_array($rota['user_id'], $rC)){
                     $s =$this->multi_array_search($posts, array('start' =>$rota['date'], 'resourceId' => $rota['user_id']));
                        if(count($s)>0){
                            if($s['shift_id']<$rota['shift_id']){
                                $keyS = array_search ($s['shift_id'], $s);
                                //print $keyS." ---KEY";
                                 unset($posts[$keyS]); 
                                 $posts = array_values($posts); 
                            }
                        }  
                    }
                    $posts[] = array(
                        "resourceId"       =>  $rota['user_id'],
                        "unit_id"          =>  $rota['unit_id'],
                        "title"            =>  $title, 
                        "tooltip"          =>  $comment,
                        "start"            =>  $rota['date'],
                        "end"              =>  $rota['date'],
                        "color"            =>  $color,
                        "rota_id"          =>  $rota['rota_id'],
                        "from_unit"        =>  $rota['from_unit'],
                        "user_unit_id"     => $this->returnUserUnit($rota['user_id'])
                    ); 
                  }
                  else
                  {
                    if($rota['shift_id'] == 4 || $rota['shift_id'] == 3 || $rota['shift_id'] == 93){
                      /*if($rota['shift_id'] == 4){
                      // if($rota['shift_name'] == "AWOL" || $rota['shift_name'] == "S"){
                      //   if($rota['shift_name'] == "AWOL"){
                        $color = "#FF0000";
                      }else if($rota['shift_id'] == 3){
                          $color = "#adf802";
                        }else{
                          $color = "#DB7093";
                        }*/
                      // $color = "#FF0000";
                        $color = $rota['background_color'];
                      $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                      $user_status=$this->Agency_model->checkAgencyStaffAssigned($rota['user_id'],$rota['unit_id'],$rota['date']);
                      $staff_user_status=$this->Agency_model->checkSystemStaffAssigned($rota['unit_id'],$rota['date']);
                      if($user_status == "true")
                      {
                        if($staff_user_status == "true"){
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_user show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }else{
                          $color = "#008000";
                          // $title = $rota['shift_name'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                        }
                      }else{
                        $color = "#008000";
                        // $title = $rota['shift_name'];
                        $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-shiftid="'.$rota['shift_id'].'">'.$rota['shift_name'].'</span>';
                      }
                    }else{
                      if($rota['shift_id'] == 0 || $rota['shift_id'] == 1 || $rota['shift_id'] == 2){
                      // if($rota['shift_id'] == 0 || $rota['shift_id'] == 1 || $rota['
                      //   shift_id'] == 2){//T
                      // if($rota['shift_name'] == "X" || $rota['shift_name'] == "H" || $rota['shift_name'] == "T"){
                        $color = "#add8e6";
                        // $color = $rota['background_color'];
                        if($rota['shift_id'] == 2){
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_shift select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }else{
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }
                        
                      }else{
                        $agency_status=$this->Agency_model->checkUserIsAgencyStaff($rota['user_id']);
                        if($agency_status == "true")
                        {
                          $color = "#add8e6";
                          // $color = $rota['background_color'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-rstatus="'.$rota['published'].'" data-unitid="'.$rota['unit_id'].'">'.$rota['shift_name'].'</span>';
                        }else{
                          $color = "#add8e6";
                          // $color = $rota['background_color'];
                          $title = '<span class="ind_shift_'.$rota['user_id'].'_'.$rota['date'].' shift_'.$rota['user_id'].'_'.$rota['date'].' select_shift show_count select_offday_'.$rota['user_id'].'_'.strtolower(date('l', strtotime($rota['date']))).'" data-color="'.$color.'" data-shiftname="'.$rota['shift_name'].'" style="color:'.$text_color.'" data-shiftid="'.$rota['shift_id'].'" data-date="'.$rota['date'].'" data-partnumber="'.$rota['part_number'].'"  data-shifttype="'.$rota['shift_type'].'" data-rotaid="'.$rota['rota_id'].'" data-resourceid="'.$rota['user_id'].'" data-rstatus="'.$rota['published'].'" data-hours="'.$rota['targeted_hours'].'" data-breakhours="'.$rota['unpaid_break_hours'].'" data-unitid="'.$rota['unit_id'].'">'.$shift_shortcutBranch.$rota['shift_name'].'</span>';
                        }
                      }
                    }
               /*      $t =$this->multi_array_search($posts, array('start' =>$rota['date'], 'resourceId' => $rota['user_id']));
                    if(count($t)>0){
                        if($t['shift_id']<$rota['shift_id']){
                            $keyT = array_search ($t['shift_id'], $t);
                            //print $keyS." ---KEY";
                            unset($posts[$keyT]);
                            $posts = array_values($posts);
                        }
                    } */
                    //$days
                    if(in_array($rota['user_id'], $rC)){
                        $s =$this->multi_array_search($posts, array('start' =>$rota['date'], 'resourceId' => $rota['user_id']));
                        if(count($s)>0){
                            if($s['shift_id']<$rota['shift_id']){
                                $keyS = array_search ($s['shift_id'], $s);
                                //print $keyS." ---KEY";
                                unset($posts[$keyS]);
                                $posts = array_values($posts);
                            }
                        }
                    }
                      $posts[] = array(
                      "resourceId"       =>  $rota['user_id'],
                      "unit_id"          =>  $rota['unit_id'],
                      "title"            =>  $title,
                      "tooltip"          =>  $comment, 
                      "start"            =>  $rota['date'],
                      "end"              =>  $rota['date'],
                      "color"            =>  $color,
                      "rota_id"          =>  $rota['rota_id'],
                      "from_unit"        =>  $rota['from_unit'],
                      "user_unit_id"     => $this->returnUserUnit($rota['user_id'])
                  ); 
                  }
                    
                    $response['rotaId'] = $rota['rota_id']; 
                    $comment='';
            
           }
            $response['weekEvents']=json_encode($posts);  
            if(!empty($resources))
            { 
              $sort_users = array();
              foreach ($resources as $key => $row)
              {
                  $sort_users['sort_order'][$key] = $row['sort_order'];
                  $sort_users['shift_category'][$key] = $row['shift_category'];
                  $sort_users['designation_id'][$key] = $row['designation_id'];
              } 
              array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$resources); 
              //print_r($resources);exit();
            }
            else
            {
              $resources=$resources;
            } 
 
          
          $response['resources']=json_encode($resources);  
    
    }
    $response['job_roles'] = $this->Designation_model->alldesignation();
    $response['job_role_group'] = $this->Designation_model->alldesignationgroups();
    $response['unit_ids'] = json_encode($this->findAllUnitsAndSubUnits());
    $absent_shifts = $this->Shift_model->getAbsentSHifts();
    $agency_staffs = $this->User_model->getAgencyStaffs();
    $response['absent_shifts']=json_encode($absent_shifts);
    $response['agency_staffs']=json_encode($agency_staffs);
    $response['jobe_roles']=json_encode($jobe_roles); //print_r($jobe_roles);exit();
    $response['default_date']=$new_start_date;
    $designaton_names = $this->findAllDesignation();
    $response['designaton_names']=json_encode($designaton_names);
    $response['user_offday']=json_encode($user_offday);
    $response['login_user_designation']=$login_user_designation;
    if(count($shift_user_ids) > 0){
      $holidayDates = $this->Rotaschedule_model->getHolidayDates($shift_user_ids);
      $trainingDates = $this->Rotaschedule_model->getTrainingDates($shift_user_ids);
      $UserOtherUnits = $this->Rotaschedule_model->getOtherUnitshiftByDate($shift_user_ids,$this->input->post("unit"));
      $response['holidayDates']=json_encode($holidayDates);
      $response['trainingDates']=json_encode($trainingDates);
      $response['UserOtherUnits']=json_encode($UserOtherUnits);
    }else{
      $response['holidayDates']=[];
      $response['trainingDates']=[];
      $response['UserOtherUnits']=[];
    }
    //print_r( $response['rota_dates']);exit();
    $shift_cats = $this->getShiftDatas();
    $training_shift_details = $this->Rotaschedule_model->getTrainingShiftDetails(2);
    $response['shift_cats']=json_encode($shift_cats);
    $response['training_shift_details']=json_encode($training_shift_details);
    $zero_targeted_hours_shifts = json_encode($this->findAllZeroHoursShifts());
    $response['zero_targeted_hours_shifts']=json_encode($zero_targeted_hours_shifts);
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/  
    $this->load->view("admin/rota/viewdatabysubunit",$response);
    $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/editrotaview.js');
    $this->load->view('includes/footer_rota',$result);
  }
  function weekOfMonthFromDate($string) {
    $sunday_array = array();
    $full_week_array = array();
    $year_string_array = explode('-',$string);
    $first_date_of_month = $year_string_array[0].'-'.$year_string_array[1].'-01';
    $last_date_of_month = date("Y-m-t", strtotime($first_date_of_month));
    $begin  = new DateTime($first_date_of_month);
    $end    = new DateTime($last_date_of_month);
    while ($begin <= $end) // Loop will work begin to the end date 
    {
      if($begin->format("D") == "Sun") //Check that the day is Sunday here
      {
        array_push($sunday_array, $begin->format("Y-m-d"));
      }
      $begin->modify('+1 day');
    }
    foreach ($sunday_array as $sun) 
    {
      $next_sat = date('Y-m-d', strtotime($sun . ' + 6 day'));
      $week_string = $sun.'_'.$next_sat;
      array_push($full_week_array, $week_string);
    }
    return $full_week_array;
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
  public function markSickOrAbsent(){
    $this->load->model('Rotaschedule_model');
    $params=array();
    $changed_dates=array();
    $rota_history=array();
    $params['user_id'] = $this->input->post("user_id");
    $params['unit_id'] = $this->input->post("unit_id");
    $params['date'] = $this->input->post("date");    
    $params['shift_id'] = $this->input->post("shift_id");
    $params['old_shiftid'] = $this->input->post("old_shiftid");
    $params['shift_name'] = $this->input->post("shift_name");
    $params['leave_type'] = $this->input->post("leave_type");
    $newUserids=array($params['user_id']);
    $changed_dates[$params['user_id']] = array($params['date']);
    $rota_previous=$this->Rotaschedule_model->findrota($params);

    $result=$this->Rotaschedule_model->markSickOrAbsent($params);
    $rota_new=$this->Rotaschedule_model->findrota($params);
    $rota_id=$rota_new['rota_id']; 

    $shift_datas = $this->Rotaschedule_model->getshiftDatas($this->input->post("shift_id")); //print_r($shift_datas);exit();
    $rota_history[0]=$rota_previous; //print_r($rota_history);exit();
    $this->saveHistory($newUserids,$rota_id,$rota_history,'Mark Employee As Sick Or Absent',$params['unit_id'],$changed_dates,$params['unit_id']);
    if($result == "true"){
      $result=$this->Rotaschedule_model->addDataToLeavelog($params);
      header('Content-Type: application/json');
      echo json_encode(array('status' => 1,'result'=> $result,'shift_id'=>$this->input->post("shift_id"),'shift_name'=>$shift_datas['shift_shortcut'],'part_number'=>$shift_datas['part_number'],'shift_type'=>$shift_datas['shift_type']));
      exit();
    }else{
      header('Content-Type: application/json');
      echo json_encode(array('status' => 2,'result'=> $result,'shift_id'=>$this->input->post("shift_id"),'shift_name'=>$shift_datas['shift_shortcut'],'part_number'=>$shift_datas['part_number'],'shift_type'=>$shift_datas['shift_type']));
      exit();
    }
  }


  public function editrotaviewloadtest(){
      
      
      
      $this->load->view('includes/home_header');
      $response=array();
      $params=array();
      $female_nurse_count = 0;
      $male_nurse_count = 0;
      $params['user_id']='';
      $params['unit_id']=$this->uri->segment(4);
      $params['start_date']=$this->uri->segment(5);
      $params['end_date']=$this->uri->segment(6);
      $rota_id=$this->uri->segment(7);
      //print_r($params);exit();
      // $params['end_date']=date('Y-m-d', strtotime($this->uri->segment(6) . '-1 day'));;
      //Added by chinchu
      //print "<pre>";
      $response['start_date'] = $this->uri->segment(5);
      $response['end_date'] = $this->uri->segment(6);
      $rota_details = $this->Rota_model->getRota($rota_id);
      $response['published'] = $rota_details[0]['published'];
      if(count($rota_details) > 0 ){
          $rota_settings_id = $rota_details[0]['rota_settings'];
          $rota_settings_details = $this->Rota_model->getRotaSettings($rota_settings_id);
      }
      $response['schedule']=$this->Shift_model->getWeeklyShifts($params);
      
     // print_r($response['schedule']);exit;
      $unitDetails = $this->Unit_model->findunit($this->uri->segment(4));
      if(count($unitDetails)>0){
          $response['staff_limit']=$unitDetails[0]['staff_limit'];
      }else{
          $response['staff_limit']=0;
      }
      
      if(count($response['schedule'])==0)
      {
          $this->session->set_flashdata('message', 'Please select a scheduled unit.');
          redirect('admin/Rota/viewrota');
      }
      else
      {
          $shifts=$this->Shift_model->getShift();
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
        
                      
                      $shiftSelect .= '<option data-partnumber='.$shift['part_number'].' style="color:'.$text_color.';" data-breakhours='.$shift['unpaid_break_hours'].' data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' data-hours='.$shift['targeted_hours'].' '.$selected.' value="'.$shift['id'].'">'.$shift['shift_shortcut'].'</option>';
          }
          
          
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
              $designationStatus = $this->Shift_model->designationStatus($rota['user_id'],$this->uri->segment(4));
              /*$assigned_status = $designationStatus['status'];
               data-status="'.$assigned_status.'"*/
              $userDefaultShift = $rota['shift_id'];
           
             
             // print_r($shifts); exit;
            $shiftSelect1 = '<select  class="eventcls"  id="shift_'.$rota['user_id'].'" style="color:'.$text_color.';" data-shiftcode="'.$rota['shift_name'].'" data-descode="'.$designationStatus['des_code'].'" data-sortoder="'.$designationStatus['sort_order'].'" data-gender="'.$designationStatus['gender'].'">';
            /*  foreach ($shifts as $shift) {
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
                          
                          $shiftSelect .= '<option data-partnumber='.$shift['part_number'].' style="color:'.$text_color.';" data-breakhours='.$shift['unpaid_break_hours'].' data-stime='.$shift['start_time'].' data-etime='.$shift['end_time'].' data-hours='.$shift['targeted_hours'].' '.$selected.' value="'.$shift['id'].'">'.$shift['shift_shortcut'].'</option>';
              } */
              $shiftSelect2 = '</select>';
              
            
                  
              $posts[] = array(
                  "resourceId"       =>  $rota['user_id'],
                  "unit_id"          =>  $rota['unit_id'],
                  "default_shift_user" => $userDefaultShift,
                  "title"            =>  $shiftSelect1.$shiftSelect.$shiftSelect2,
                  "start"            =>  $rota['date'],
                  "from_unit"        =>  $rota['from_unit'],
                  "end"              =>  $rota['date']
                  
              );
              //$shiftSelect="";
          }
          $response['userShifts']=json_encode($posts);
         // print_r($posts); exit;
          $response['resources']=$this->Shift_model->getStaffsUseridloadtest($params);
          foreach($response['resources'] as $res)
          {
              
              $response['resource']=$this->Shift_model->getShiftsStaffs($res['user_id']);
              if($res['from_unit']=='')
              {
                  foreach ($response['resource'] as $resources) {
                      if($resources['gender'] == "F" || $resources['gender'] == "f"){
                          $female_nurse_count++;
                      }else{
                          $male_nurse_count++;
                      }
                      $users[] = array(
                          "id"                =>  $resources['id'],
                          //"title"             =>  $resources['gender'].'-'.$resources['fname'].' '.$resources['lname'].'('.$resources['designation_code'].')'.'('.$resources['shift_shortcut'].')',
                          "title"       =>$source['fname'].' '.$source['lname'],
                          "unit_color"        =>  $resources['color_unit'],
                          "sort_order"        =>  $resources['sort_order'],
                          "shift_category"    =>  $resources['shift_category'],
                          "totalhours"        =>  "(".$resources['weekly_hours'].")",
                          "user_weeklyhours"  => $resources['weekly_hours'],
                          "designation_code"  => $resources['group'],
                          "shift_shortcode"   => $resources['shift_shortcut'],
                          "shift_id"          => $resources['shift_id'],
                          "designation_id"    => $resources['designation_id']
                      );
                  }
              }
              else
              {
                  foreach ($response['resource'] as $resources) {
                      // print("<pre>".print_r($resources,true)."</pre>");
                      if($resources['gender'] == "F" || $resources['gender'] == "f"){
                          $female_nurse_count++;
                      }else{
                          $male_nurse_count++;
                      }
                      $users[] = array(
                          "id"                 =>  $resources['id'],
                         // "title"              =>  $resources['gender'].'-'.$resources['fname'].' '.$resources['lname'].'('.$resources['designation_code'].')'.'('.$resources['shift_shortcut'].')'.'('.$resources['unit_shortname'].')',
                          "title"       =>$source['fname'].' '.$source['lname'],
                          "unit_color"         =>  $resources['color_unit'],
                          "sort_order"        =>  $resources['sort_order'],
                          "shift_category"    =>  $resources['shift_category'],
                          "totalhours"         =>  "(".$resources['weekly_hours'].")",
                          "user_weeklyhours"   =>  $resources['weekly_hours'],
                          "designation_code"  => $resources['group'],
                          "shift_shortcode"   => $resources['shift_shortcut'],
                          "shift_id"          => $resources['shift_id'],
                          "designation_id"    => $resources['designation_id']
                      );
                  }
              }
              
          }
          $sort_users = array();
          foreach ($users as $key => $row)
          {
              $sort_users['sort_order'][$key] = $row['sort_order'];
              $sort_users['shift_category'][$key] = $row['shift_category'];
              $sort_users['designation_id'][$key] = $row['designation_id'];
          }
          // array_multisort($sort_users, SORT_ASC, $posts);
          //array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['designation_id'], SORT_ASC,$users);
         // array_multisort($sort_users['sort_order'], SORT_ASC, $sort_users['shift_category'], SORT_ASC,$users);
          
          $response['posts']=json_encode($users);
          $response['rota_id']=$rota_id;
          $response['female_nurse_count']=$female_nurse_count;
          $response['male_nurse_count']=$male_nurse_count;
          if(count($rota_settings_details) > 0){
              $response['day_shift_min']=$rota_settings_details[0]['day_shift_min'];
              $response['day_shift_max']=$rota_settings_details[0]['day_shift_max'];
              $response['night_shift_min']=$rota_settings_details[0]['night_shift_min'];
              $response['night_shift_max']=$rota_settings_details[0]['night_shift_max'];
              $response['num_patients']=$rota_settings_details[0]['num_patients'];
              $response['designation']=$rota_settings_details[0]['one_one_patients'];
              $response['nurse_night_count']=$rota_settings_details[0]['nurse_night_count'];
              $response['nurse_day_count']=$rota_settings_details[0]['nurse_day_count'];
              $response['rota_settings']=$rota_settings_details[0]['id'];
          }
          $shift_cats = $this->getShiftDatas();
          $response['shift_cats']=json_encode($shift_cats);
          $response['unit']=$this->User_model->findunitname($params['unit_id']);
          $response['unit_id'] = $params['unit_id'];
          $designaton_names = $this->findAllDesignation();
          $response['designaton_names']=json_encode($designaton_names);
          $this->load->view("admin/rota/editrotaview",$response);
          $result['js_to_load'] = array('fullcalendar/bootstrap.min.js','fullcalendar/moment.js','fullcalendar/fullcalendar.js','fullcalendar/scheduler.js','rota/editrotaloadtest.js');
          $this->load->view('includes/footer_rota',$result);
      }
  } 

    // public function orderUsers($users){
    //   $administrators = array();
    //   $unit_managers = array();
    //   $therapists = array();
    //   $day_shift_staffs = array();
    //   $night_shift_staffs = array();
    //   $not_assigned_staffs = array();
    //   $hr_managers = array();
    //   $day_shift_carers = array();
    //   $night_shift_carers = array();
    //   $other_stafffs = array();
    //   $new_array = array();
    //   // print("<pre>".print_r($users,true)."</pre>");exit();
    //   foreach ($users as $user) {
    //     if($user['designation_code'] == 1){
    //       array_push($administrators, $user);
    //     }else if($user['designation_code'] == 2){
    //       array_push($unit_managers, $user);
    //     }else if($user['designation_code'] == 3){
    //       $shift_category = $this->findShiftCategory($user['shift_id']);
    //       if($shift_category == 1){
    //         array_push($day_shift_staffs, $user);
    //       }else if($shift_category == 2){
    //         array_push($night_shift_staffs, $user);
    //       }else{
    //         array_push($not_assigned_staffs, $user);
    //       }
    //     }else if($user['designation_code'] == 4){
    //       array_push($therapists, $user);
    //     }else if($user['designation_code'] == 5){
    //       array_push($hr_managers, $user);
    //     }else if($user['designation_code'] == 6){
    //       $shift_category = $this->findShiftCategory($user['shift_id']);
    //       if($shift_category == 1){
    //         array_push($day_shift_carers, $user);
    //       }else if($shift_category == 2){
    //         array_push($night_shift_carers, $user);
    //       }else{
    //         //do nothing
    //       }
    //     }else if($user['designation_code'] == 7){
    //       array_push($other_stafffs, $user);
    //     }else{
    //       //do nothing
    //     }
    //   }
    //   $new_array = array_merge(
    //     $administrators,
    //     $unit_managers, 
    //     $day_shift_staffs, 
    //     $night_shift_staffs,
    //     $not_assigned_staffs,
    //     $therapists,
    //     $hr_managers,
    //     $day_shift_carers,
    //     $night_shift_carers,
    //     $other_stafffs
    //   );
    //   return $new_array;
    // }
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
        $staff_details=$this->Shift_model->getShiftwithoutOffday(); 
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
    /**
     * Pass Shift array and rota Id,
     * checks if the shift id is changed.
     *
     * @return array with changed users and new users
     */
    public function getRescheduledUsers($shift_details_array, $rotaid){
      $user='';
      $i='';
      foreach ($shift_details_array as $value) {
        if($user!=$value['user_id']) {
            $user= $value['user_id'];
            $i=0;
        }
        $userShift[$user][$i]=$value['shift_id'];
        $i++;
        $user= $value['user_id'];
      }
      $userids = array();
      $userids = array_keys($userShift);
      $userIdswithdchangeInShifts = array();
      $newUserids = array();
      $newUsers = array();
      $newUseridsOtherUnit = array();
      $newUseridsOther = array();
      $count = count($shift_details_array); 
      $this->load->model('Rotaschedule_model');
      foreach($userids as $userid){
        $shiftLength=0;
        //check if user id is in rota
        $result = $this->Rotaschedule_model->checkUserinRotaSchedule($userid,$rotaid);
        if(count($result)>0){
          //if yes compare new shift ids with old shift ids in table, if not found skip that user
          $shiftLength = count($this->array_intersects($userShift[$userid],$result[$userid]));
          if($shiftLength!=7){ //if changed add to rescheduled user array
              $userIdswithdchangeInShifts[] = $userid;
          }              
        }else{//new user
          $new_user = array();
          for($i=0;$i<$count;$i+=7){
            if($shift_details_array[$i]['user_id'] == $userid){
              $new_user = $shift_details_array[$i];
            }
          }
          if($new_user['from_unit'] != null){
            $newUseridsOtherUnit[] = $userid;
            $object = array('user_id'=>$userid);
            array_push($newUseridsOther, $object);
          }else{
            $newUserids[] = $userid;
            $object = array('user_id'=>$userid);
            array_push($newUsers, $object);
          } 
        }
      }
      return array(
        'changed_users'=>$userIdswithdchangeInShifts,
        'new_users'=>$newUserids,
        'newUseridsOtherUnit' => $newUseridsOtherUnit,
        'newUsers'=>$newUsers,
        'newUseridsOther'=>$newUseridsOther
      ); //return user id wit change in shifts
    }
    public function array_intersects($a, $b) 
    { 
      $result = array_intersect_assoc($a, $b); 
      return($result);
    } 
    public function saveShift(){  //edit rota

      //print_r($this->input->post());exit();
      $shift_details = $this->input->post("shiftDetails");  //print_r($shift_details);exit();
      $rota_id = $this->input->post("rota_id"); 
      $rota_settings= $this->input->post("rota");
      $session_id= $this->input->post("session_id");
      $shift_details_array = json_decode($shift_details,true);
      $count = count($shift_details_array); 
      $dateValue = strtotime($shift_details_array[6]["start"]); 
      $year = date('Y',$dateValue);
      $monthNo = date('m',$dateValue);
      $unitId = $this->input->post("unit_id");
      $current_date = $this->input->post("current_date");
      $eDate = $this->input->post("eDate");
      $idslog = " ( Rota#".$rota_id." Session#".$session_id." Unit#".$unitId." Year#".$year." Month#".$monthNo." Date:".$dateValue."".$rota_settings.")";
      $cur_date=strtotime($current_date);
      $rt_end_date=strtotime($eDate);
      $email_sms_status = true;
      //check whether the rota end date is less than current date.
      if($rt_end_date < $cur_date)
      {
        $email_sms_status = false;
      }

      /*$rota_data    = array(
          'rota_settings'=> $rota_settings,
          'start_date' => $shift_details_array[0]["start"],
          'end_date' => $shift_details_array[6]["start"],
          'unit_id' => $shift_details_array[0]["unit_id"],
          'created_date' => date('Y-m-d H:i:s'),
          'updated_date' => date('Y-m-d H:i:s'),
          'created_user_id' => $this->session->userdata('user_id'),
          'published' => 0,
          'month' => $monthNo,
          'year' => $year
      );   */
        $user_name=$this->User_model->findusername($session_id);          
        // $save_result = $this->User_model->addRotaDetails($rota_data);
        // $rota_result = $this->Rota_model->getRota($save_result['rota_id']); 
        $rota_unit= $this->Rota_model->findRotaUnit($shift_details_array[0]["unit_id"]);    
        $rota_setting= $this->Rota_model->findSettings($rota_settings);
        if(count($user_name) > 0){
          $description= $user_name[0]['fname'].' '.$user_name[0]['lname'].' '.'has edited and a rota for '.$rota_unit[0]['unit_name'].' '.'for the week'.' '.$shift_details_array[0]["start"].' '.'to'.' '.$shift_details_array[6]["start"].' '.'with settings: day shift minimum:'.$rota_setting[0]['day_shift_min'].','.' '.'day shift maximum:'.$rota_setting[0]['day_shift_max'].','.' '.'night shift maximum:'.$rota_setting[0]['night_shift_min'].','.' '.'night shift maximum:'.$rota_setting[0]['night_shift_max'].','.' '.'number of patients:'.$rota_setting[0]['num_patients'].','.' '.'1:1 patients:'.$rota_setting[0]['one_one_patients'].','.' '.'nurse_day_count:'.$rota_setting[0]['nurse_day_count'].','.' '.'nurse_night_count:'.$rota_setting[0]['nurse_night_count'];  
          $log=array( 
              'description'   => $description.$idslog,  
            'activity_date' => date('Y-m-d H:i:s'), 
            'activity_by'   => $this->session->userdata('user_id'),  
            'add_type'      => 'Edit Rota', 
            'user_id'       => ' ', 
            'primary_id'    => $rota_settings ,
            'creation_date' => date('Y-m-d H:i:s')
          ); 
          $this->Activity_model->insertactivity($log);
        }  
      $unit_id = $shift_details_array[0]["unit_id"]; 
      $users_for_mailing = $this->getRescheduledUsers($shift_details_array,$rota_id); //print_r($users_for_mailing);exit(); 
      $newUserids = $users_for_mailing['new_users'];
      $changed_users = $users_for_mailing['changed_users'];
      $newUsersOtherUnit = $users_for_mailing['newUseridsOtherUnit'];
      $rota_result = $this->Rota_model->getRota($rota_id);
      $published_status = $rota_result[0] ['published'];
      $rota_unitid = $rota_result[0]['unit_id'];
      $rota_schedule_history = array();
      $changed_dates = array();
      $new_shift_array_details = array();
      if(count($changed_users)>0){
        for($i=0;$i<count($changed_users);$i++)  
        {
          for($x=0;$x<count($shift_details_array);$x++)  
          {
            if($shift_details_array[$x]['user_id'] == $changed_users[$i]){
              array_push($new_shift_array_details, $shift_details_array[$x]);
            }
          }
        }
      }
      if(count($newUserids)>0){
        for($i=0;$i<count($newUserids);$i++)  
        {
          for($x=0;$x<count($shift_details_array);$x++)  
          {
            if($shift_details_array[$x]['user_id'] == $newUserids[$i]){
              array_push($new_shift_array_details, $shift_details_array[$x]);
            }
          }
        }
      }
      if(count($newUsersOtherUnit)>0){
        for($i=0;$i<count($newUsersOtherUnit);$i++)  
        {
          for($x=0;$x<count($shift_details_array);$x++)  
          {
            if($shift_details_array[$x]['user_id'] == $newUsersOtherUnit[$i]){
              array_push($new_shift_array_details, $shift_details_array[$x]);
            }
          }
        }
      }
      if(count($changed_users)>0){
        for($i=0;$i<count($changed_users);$i++)  
        {
          $user_shifts = array();
          $dates_array = array();
          for($x=0;$x<count($shift_details_array);$x++)  
          {

            if($shift_details_array[$x]['user_id'] == $changed_users[$i]){
              array_push($user_shifts, $shift_details_array[$x]);
            }
          }
          $unit_ids = $this->Unit_model->returnMainAndSubUnitsIds($unitId);
          for($j=0;$j<count($user_shifts);$j++)
          {
            $date = $user_shifts[$j]['start'];
            $data_value = $this->Rotaschedule_model->getScheduledataByDate($changed_users[$i],$unit_ids,$date);
            if($user_shifts[$j]['start'] == $data_value['date']){
              if($user_shifts[$j]['c_shift_id'] != $data_value['shift_id']){
                array_push($rota_schedule_history, $data_value);
                array_push($dates_array, $date);
              }
            }
          }
          $changed_dates[$changed_users[$i]] = $dates_array;
        }
      }
      // $this->saveDataToRotaSchedule($shift_details_array,,$rota_id,$session_id);
      $this->saveDataToRotaSchedule($new_shift_array_details,$rota_id,$session_id);
      if(count($newUserids)>0){
        $this->saveHistory($newUserids,$rota_id,array(),'Update Users - New employee',$rota_unitid,array(),$unitId);
      }
      if(count($changed_users)>0){
        $this->saveHistory($changed_users,$rota_id,$rota_schedule_history,'Edit Users - Edit Shift',$rota_unitid,$changed_dates,$unitId);
      }
      if(count($newUsersOtherUnit)>0){
        $this->saveHistory($newUsersOtherUnit,$rota_id,array(),'Update Users - New employee from other unit',$rota_unitid,array(),$unitId);
      }
      if($published_status == 1){
        if(count($newUserids)>0){
          $this->notifyUserWithShiftScheduleAndReschedule($newUserids,$unit_id,1,$rota_id,'Edit - New employee',[]);
        }
        if(count($changed_users)>0){
          //New updation. No need to send sms or email when the rota is
          //in previous date.
          if($email_sms_status == true)
          {$this->notifyUserWithShiftScheduleAndReschedule($changed_users,$unit_id,2,$rota_id,'Edit - Edit shift',$changed_dates);}
        }
        if(count($newUsersOtherUnit)>0){
          $this->notifyOtherUnitNewUser($newUsersOtherUnit,$unit_id,'Edit - New employee from other unit');
        }
      }
      header('Content-Type: application/json');
      echo json_encode(array(
        'status'  => 2,
        'message' => "Success",
        'rota_id' => $rota_id,
        'result'     => $rota_result,
        'newUserids' => $newUserids,
        'changed_users' =>$changed_users,
        'newUsersOtherUnit'=>$newUsersOtherUnit
      ));
      exit();
    }
    public function getAllUnPublishedRota(){
      $unit_id = $this->input->post("unit_id");
      $result  = $this->Rota_model->getAllUnPublishedRota($unit_id);
      header('Content-Type: application/json');
      echo json_encode(array('status' => $result['status'],'message' => "Success",'result'=>$result['result']));
      exit();
    }
    public function saveBeforePublish(){ //create and update users
      $shift_details = $this->input->post("shiftDetails"); 
      $rota_settings= $this->input->post("rota"); //rotasettings
      $selected_year= $this->input->post("selected_year");
      $selected_month= $this->input->post("selected_month");
      $session_id= $this->input->post("session_id");
      $new_status=$this->input->post("new_status"); //print_r($new_status);exit();
      $userid_array_fordelete = $this->input->post('userid_array_fordelete');
      $shift_details_array = json_decode($shift_details,true); 
      $count = count($shift_details_array);
      $unit_id = $shift_details_array[0]["unit_id"];
      $start_date = $shift_details_array[0]["start"];  
      $end_date = $shift_details_array[6]["start"];  
      $unitId= $this->input->post("unit_id"); //added by siva dec 11
      //print_r($unitId);exit();
      $rota_data    = array(
          'rota_settings'=> $rota_settings,
          'start_date' => $shift_details_array[0]["start"],
          'end_date' => $shift_details_array[6]["start"],
          'unit_id' => $unitId,
          'created_date' => date('Y-m-d H:i:s'),
          'updated_date' => date('Y-m-d H:i:s'),
          'created_user_id' => $this->session->userdata('user_id'),
          'published' => 0,
          'month' => $selected_month,
          'year'  => $selected_year
      );  
      //$result = $this->Shift_model->checkRotaDataWithStartAndEndDate($start_date,$end_date,$unit_id);  
      $result = $this->Shift_model->checkRotaDataWithStartAndEndDate($start_date,$end_date,$unitId); //added by siva dec 11
     // print_r($result);exit();
      if($result['status'] == 3)
      {      //if status =3 ,rota is not in the rota table,so creation/insertion of shifts
        $user_name=$this->User_model->findusername($session_id);          
        $save_result = $this->User_model->addRotaDetails($rota_data);
        $rota_result = $this->Rota_model->getRota($save_result['rota_id']);        
        $rota_unit= $this->Rota_model->findRotaUnit($rota_result[0]['unit_id']);    
        $rota_setting= $this->Rota_model->findSettings($rota_result[0]['rota_settings']);
        if(count($user_name) > 0){
          $description= $user_name[0]['fname'].' '.$user_name[0]['lname'].' '.'has created a rota for '.$rota_unit[0]['unit_name'].' '.'for the week'.' '.$rota_result[0]['start_date'].' '.'to'.' '.$rota_result[0]['end_date'].' '.'with settings: day shift minimum:'.$rota_setting[0]['day_shift_min'].','.' '.'day shift maximum:'.$rota_setting[0]['day_shift_max'].','.' '.'night shift maximum:'.$rota_setting[0]['night_shift_min'].','.' '.'night shift maximum:'.$rota_setting[0]['night_shift_max'].','.' '.'number of patients:'.$rota_setting[0]['num_patients'].','.' '.'1:1 patients:'.$rota_setting[0]['one_one_patients'].','.' '.'nurse_day_count:'.$rota_setting[0]['nurse_day_count'].','.' '.'nurse_night_count:'.$rota_setting[0]['nurse_night_count'];  
          $log=array( 
            'description'   => $description,  
            'activity_date' => $rota_result[0]['created_date'], 
            'activity_by'   => $rota_result[0]['created_user_id'],  
            'add_type'      => 'Create New Rota', 
            'user_id'       => ' ', 
            'primary_id'    => $rota_result[0]['rota_settings'],
            'creation_date' => date('Y-m-d H:i:s') 
          );  
          $this->Activity_model->insertactivity($log);
        }
        $rota_id = $save_result['rota_id'];
        if($rota_id)
        {
          $this->saveDataToRotaSchedule($shift_details_array,$rota_id,$session_id);
          $scheduled_user_count = $this->Shift_model->getScheduledUserCount($rota_id);
          header('Content-Type: application/json');
          echo json_encode(array('status' => 1,'message' => "Success",'rota_id' => $save_result['rota_id'],'result'=>$rota_result,'scheduled_user_count'=>$scheduled_user_count));
          exit();
        }
      }
      else
      {  //update users
        $rota_id = $result['result'][0]['id'];
        $rota_unitid = $result['result'][0]['unit_id'];
        $rota_schedule_history = array();
        $changed_dates = array();
        $published_status = $result['result'][0]['published']; 
        if($new_status==2)
        {   // if status =2 ,rota is already published.update users
           
            $user_name=$this->User_model->findusername($session_id);          
            $save_result = $this->User_model->addRotaDetails($rota_data);
            $rota_result = $this->Rota_model->getRota($save_result['rota_id']); 
            
            $rota_unit= $this->Rota_model->findRotaUnit($rota_result[0]['unit_id']);  
            $rota_setting= $this->Rota_model->findSettings($rota_result[0]['rota_settings']);
            if(count($user_name) > 0){
              $description= $user_name[0]['fname'].' '.$user_name[0]['lname'].' '.'has updated users of a rota for '.$rota_unit[0]['unit_name'].' '.'for the week'.' '.$rota_result[0]['start_date'].' '.'to'.' '.$rota_result[0]['end_date'];  
              $log=array( 
                'description'   => $description,  
                'activity_date' => date('Y-m-d H:i:s'), 
                'activity_by'   => $rota_result[0]['created_user_id'],  
                'add_type'      => 'Update Users', 
                'user_id'       => ' ', 
                'primary_id'    => $rota_result[0]['rota_settings'],
                'creation_date' => date('Y-m-d H:i:s') 
              );  
              $this->Activity_model->insertactivity($log);
            }
         } 

          $users_for_mailing = $this->getRescheduledUsers($shift_details_array,$rota_id); 
          
          $newUserids = $users_for_mailing['new_users']; 
          $changed_users = $users_for_mailing['changed_users'];
          $newUsersOtherUnit = $users_for_mailing['newUseridsOtherUnit'];
          $newUsers = $users_for_mailing['newUsers']; 
          $newUseridsOther = $users_for_mailing['newUseridsOther'];
          $merged_array = array_merge($users_for_mailing['changed_users'], $users_for_mailing['new_users'], $users_for_mailing['newUseridsOtherUnit']);
          $new_merged_shift_array = array();
          if(count($merged_array) > 0){
            for($x=0;$x<count($merged_array);$x++){
              for($y=0;$y<count($shift_details_array);$y++)  
              {
                if($shift_details_array[$y]['user_id'] == $merged_array[$x]){
                  array_push($new_merged_shift_array, $shift_details_array[$y]);
                }
              }
            }  
          }
          if(count($changed_users) > 0){  
            for($i=0;$i<count($changed_users);$i++)  
            {
              $user_shifts = array();
              $dates_array = array();
              for($x=0;$x<count($shift_details_array);$x++)  
              {
                if($shift_details_array[$x]['user_id'] == $changed_users[$i]){
                  array_push($user_shifts, $shift_details_array[$x]);
                }
              }

              $user_unit = $this->Unit_model->findunitidofuser($changed_users[$i]);
              //print_r("<pre>");
              //print_r($user_shifts); print '<br>';
              $unit_ids = $this->Unit_model->returnMainAndSubUnitsIds($unitId);
              $user_unit_id = $user_unit[0]['unit_id'];
              for($j=0;$j<count($user_shifts);$j++)
              {
                $unit_new_id=$user_shifts[$j]['unit_id']; // unit id of user - added by swaraj sep 03
                $date = $user_shifts[$j]['start'];
                $data_value = $this->Rotaschedule_model->getScheduledataByDate($changed_users[$i],$unit_ids,$date);
                if($user_shifts[$j]['start'] == $data_value['date']){
                  if($user_shifts[$j]['c_shift_id'] != $data_value['shift_id']){
                    array_push($rota_schedule_history, $data_value);
                    array_push($dates_array, $date);
                  }
                }
                $this->User_model->deleteShiftsbyunit($changed_users[$i],$unit_new_id,$date);
              }
              $changed_dates[$changed_users[$i]] = $dates_array;
            }
          } 
          //exit();
          if($userid_array_fordelete){
            if(count($userid_array_fordelete) > 0){
              for($i=0;$i<count($userid_array_fordelete);$i++)  
              {
                $user_unit = $this->Unit_model->findunitidofuser($userid_array_fordelete[$i]);
                $user_unit_id = $user_unit[0]['unit_id'];
                //$unit_ids = $this->Unit_model->returnMainAndSubUnitsIds($unitId);
                $unit_ids = $unitId;
                for($j=0;$j<7;$j++)  
                {
                  $date = $shift_details_array[$j]['start'];
                  $this->Unit_model->deleteShiftsbyunitandsubunit($userid_array_fordelete[$i],$unit_ids,$date);
                }
              }
            }
          }
          
        // $this->saveDataToRotaSchedule($shift_details_array,$rota_id,$session_id);
        $this->saveDataToRotaSchedule($new_merged_shift_array,$rota_id,$session_id);
        $scheduled_user_count = $this->Shift_model->getScheduledUserCount($rota_id);      
        
        $rota_id_array = array();
        array_push($rota_id_array, $rota_id);
        if(count($newUserids)>0){
          $this->saveHistory($newUserids,$rota_id,array(),'Update Users - New employee',$rota_unitid,array(),$unitId);
        }
        if(count($changed_users)>0){
          $this->saveHistory($changed_users,$rota_id,$rota_schedule_history,'Update Users - Update Shift',$rota_unitid,$changed_dates,$unitId);
        }
        if(count($newUsersOtherUnit)>0){
          $this->saveHistory($newUsersOtherUnit,$rota_id,array(),'Update Users - New employee from other unit',$rota_unitid,array(),$unitId);
        }
        if($published_status == 1){
          if(count($newUserids)>0){
            // print $rota_id;exit();
            $this->notifyUserWithShiftScheduleAndReschedule($newUserids,$unit_id,1,$rota_id,'Update Users - New employee',[]);
            // $this->sendMailAboutTraining($newUsers,$rota_id_array);
          }
          if(count($changed_users)>0){
            $this->notifyUserWithShiftScheduleAndReschedule($changed_users,$unit_id,2,$rota_id,'Update Users - Update Shift',$changed_dates);
          }
          if(count($newUsersOtherUnit)>0){
            $this->notifyOtherUnitNewUser($newUsersOtherUnit,$unit_id,'Update Users - New employee from other unit');
            // $this->sendMailAboutTraining($newUseridsOther,$rota_id_array);
          }
        }
        header('Content-Type: application/json');
        echo json_encode(array('status' => 2,'message' => "Success",'result'=>$result['result'],'scheduled_user_count'=>$scheduled_user_count));
        exit();
      }
    }
    public function publishSavedRota(){
      $unit_id = $this->input->post("unit_id");
      $rota_id = $this->input->post("rota_id");
      $session_id = $this->input->post("session_id");
      $publish_result = $this->Rota_model->publishSavedRota($unit_id,$rota_id);
      $rota_ids = $publish_result['rota_ids'];
      $rota_unit= $this->Rota_model->findRotaUnit($unit_id);
      $user_name=$this->User_model->findusername($session_id);
      
      $rota_setting= $this->Rota_model->findSettings($rota_ids[0]['rota_settings']);
      if(count($user_name) > 0){ 
        $description=$user_name[0]['fname'].' '.$user_name[0]['lname'].' '. 'has published a rota for '.$rota_unit[0]['unit_name'].' '.'for the week'.' '.$rota_ids[0]['start_date'].' '.'to'.' '.$rota_ids[0]['end_date']; 
        $creation_date=date("Y-m-d H:i:s");  //changed by swaraj june5
        $log=array( 
          'description'   => $description,  
          'activity_date' => $creation_date, 
          'activity_by'   => $rota_ids[0]['created_user_id'],  
          'add_type'      => 'Publish Rota',  
          'user_id'       => ' ', 
          'primary_id'    => $rota_ids[0]['rota_settings'],
          'creation_date' => date('Y-m-d H:i:s') 
        );  
        $this->Activity_model->insertactivity($log); 
      }
      $result = $this->Rota_model->getUsersForNofifyShift($rota_ids);
      $user_ids = $result['user_ids'];
      $rotaIds  = $result['rota_ids'];
      // $this->sendMailAboutTraining($user_ids,$rotaIds);
      $this->sendMailAboutShift($user_ids,$rotaIds,'Create rota',$unit_id);
      header('Content-Type: application/json');
      echo json_encode(array('status' => $publish_result['status'],"message"=>$publish_result['message']));
      exit();
    }
    public function publishData(){ 
      $unit_id =$this->input->post("unit_id");
      $r_ids =$this->input->post("rota_ids");
      $session_id =$this->input->post("session_id");
      $publish_result = $this->Rota_model->publishData($r_ids);
      $rota_unit= $this->Rota_model->findRotaUnit($unit_id);
      $rota_ids = $publish_result['rota_ids'];  
      $user_name=$this->User_model->findusername($session_id);  
        
      for($i=0;$i<count($rota_ids);$i++)  
      {   
        $rota_setting= $this->Rota_model->findSettings($rota_ids[$i]['rota_settings']);
        if(count($user_name) > 0){
          $description=$user_name[0]['fname'].' '.$user_name[0]['lname'].' '. 'has published a rota for '.$rota_unit[0]['unit_name'].' '.'for the week'.' '.$rota_ids[$i]['start_date'].' '.'to'.' '.$rota_ids[$i]['end_date']; 
          $creation_date=date("Y-m-d H:i:s");   //changed by swaraj june5
          $log=array( 
            'description'   => $description,  
            'activity_date' => $creation_date, 
            'activity_by'   => $rota_ids[$i]['created_user_id'],  
            'add_type'      => 'Publish Rota',  
            'user_id'       => ' ', 
            'primary_id'    => $rota_ids[$i]['rota_settings'],
            'creation_date' => date('Y-m-d H:i:s') 
          );  
          $this->Activity_model->insertactivity($log);
        }
      } 
      $result = $this->Rota_model->getUsersForNofifyShift($rota_ids);
      $user_ids = $result['user_ids'];
      $rotaIds  = $result['rota_ids'];
      // $this->sendMailAboutTraining($user_ids,$rotaIds);
      $this->sendMailAboutShift($user_ids,$rotaIds,'Create rota',$unit_id);
      header('Content-Type: application/json');
      echo json_encode(array('status' => $publish_result['status'],"message"=>$publish_result['message']));
      exit();
    }
    public function saveDataToRotaSchedule($shift_details_array,$rota_id,$session_id){
        $count = count($shift_details_array);
        for ($i = 0; $i < $count; $i++)
        {
            $updated_id='';
            if($session_id){
                $created_userid = $session_id;
                $updated_userid = $session_id;
            }else{
                $created_userid = $this->session->userdata('user_id');
                $updated_userid = $this->session->userdata('user_id');
            }
            //status 0 in case of users from other unit.otherwise 1
            //this status will update as 1 or 2 when other unit user approve or reject request
            if($shift_details_array[$i]["from_unit"]){
                $status = 0;
                $other_unit_id = $shift_details_array[$i]["unit_id"];
            }else{
                $status = 1;
            }
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
            if (isset($shift_details_array[$i]["shift_dayname"])) {
                $day = $shift_details_array[$i]["shift_dayname"];
            }else{
                $day = null;
            }
            if (isset($shift_details_array[$i]["shiftcat"])) {
                $shift_category = $shift_details_array[$i]["shiftcat"];
            }else{
                $shift_category = null;
            }
            if($shift_details_array[$i]["saved_unitid"]){
                $unit = $shift_details_array[$i]["saved_unitid"];
            }else{
                //print_r($shift_details_array[$i]["unit_id"]);exit();
                $parent_unit=$this->Unit_model->findsubunits($shift_details_array[$i]["unit_id"]);
                if(empty($parent_unit))
                {
                    $unit=$shift_details_array[$i]["unit_id"];
                }
                else
                {
                    $user_unit=$this->Unit_model->findunitidofuser($shift_details_array[$i]["user_id"]);
                    $unit=$user_unit[0]['unit_id'];
                    if($unit==$shift_details_array[$i]["from_unit"])
                    {
                        $unit=$shift_details_array[$i]["unit_id"];
                    }
                }
            }
            
            $designation_id=$this->Designation_model->finduserDesignation($shift_details_array[$i]["user_id"]);
            $auto_insert=$this->Shift_model->findautoinsertbyDate($shift_details_array[$i]["user_id"],$shift_details_array[$i]["unit_id"],$shift_details_array[$i]["start"]);
            
            $this->User_model->deleteShiftsbyunit($shift_details_array[$i]["user_id"],$unit,$shift_details_array[$i]["start"]);
            
            //print_r($rota_id);exit();
            $shift_data = array(
                'user_id'=>$shift_details_array[$i]["user_id"],
                'shift_id'=>$shift_id,
                'unit_id'=>$unit,
                'from_unit'=>$shift_details_array[$i]["from_unit"],
                'shift_hours'=>$shift_hours,
                'status'=>$status,
                'rota_id'=>$rota_id,
                'date'=>$shift_details_array[$i]["start"],
                'creation_date'=>date('Y-m-d H:i:s'),
                'created_userid'=>$created_userid,
                'updation_date'=>date('Y-m-d H:i:s'),
                'day'=>$day,
                'designation_id'=>$designation_id,
                'shift_category'=>$shift_category,
                'updated_userid'=>$created_userid,
                'auto_insert'=>$auto_insert
            );
            if($shift_details_array[$i]["from_unit"]!=''){ //if user from other unit then it should be real shift
                
                // if($shift_id ==0 ||  $shift_id > 4){
                $save_details = $this->User_model->addShiftDetails($shift_data);
                // }
                
            }
            else{
                $save_details = $this->User_model->addShiftDetails($shift_data);
            }
            
        }
    }
    /* public function saveDataToRotaSchedule($shift_details_array,$rota_id,$session_id){ 
      $count = count($shift_details_array);
      for ($i = 0; $i < $count; $i++)
      {
        $updated_id='';
        if($session_id){
          $created_userid = $session_id;
          $updated_userid = $session_id;
        }else{
          $created_userid = $this->session->userdata('user_id');
          $updated_userid = $this->session->userdata('user_id');
        }
        //status 0 in case of users from other unit.otherwise 1
        //this status will update as 1 or 2 when other unit user approve or reject request
          if($shift_details_array[$i]["from_unit"]){
              $status = 0;
              $other_unit_id = $shift_details_array[$i]["unit_id"];
          }else{
              $status = 1;
          }
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
          if (isset($shift_details_array[$i]["shift_dayname"])) {
            $day = $shift_details_array[$i]["shift_dayname"];
          }else{
            $day = null;
          }
          if (isset($shift_details_array[$i]["shiftcat"])) {
            $shift_category = $shift_details_array[$i]["shiftcat"];
          }else{
            $shift_category = null;
          }
          if($shift_details_array[$i]["saved_unitid"]){
            $unit = $shift_details_array[$i]["saved_unitid"];
          }else{
            //print_r($shift_details_array[$i]["unit_id"]);exit();
            $parent_unit=$this->Unit_model->findsubunits($shift_details_array[$i]["unit_id"]);
            if(empty($parent_unit))
            { 
              $unit=$shift_details_array[$i]["unit_id"];
            }
            else
            { 
              $user_unit=$this->Unit_model->findunitidofuser($shift_details_array[$i]["user_id"]);
              $unit=$user_unit[0]['unit_id'];
              if($unit==$shift_details_array[$i]["from_unit"])
              {
                $unit=$shift_details_array[$i]["unit_id"];
              }
            }
          }
          $designation_id=$this->Designation_model->finduserDesignation($shift_details_array[$i]["user_id"]);
          
         $this->User_model->deleteShiftsbyunit($shift_details_array[$i]["user_id"],$unit,$shift_details_array[$i]["start"]);
          //print_r($rota_id);exit();
          $shift_data = array(
            'user_id'=>$shift_details_array[$i]["user_id"],
            'shift_id'=>$shift_id,
            'unit_id'=>$unit,
            'from_unit'=>$shift_details_array[$i]["from_unit"],
            'shift_hours'=>$shift_hours,
            'status'=>$status,
            'rota_id'=>$rota_id,
            'date'=>$shift_details_array[$i]["start"],
            'creation_date'=>date('Y-m-d H:i:s'),
            'created_userid'=>$created_userid,
            'updation_date'=>date('Y-m-d H:i:s'),
            'day'=>$day,
            'designation_id'=>$designation_id,
            'shift_category'=>$shift_category,
            'updated_userid'=>$created_userid
        );
            if($shift_details_array[$i]["from_unit"]!=''){ //if user from other unit then it should be real shift
                         
                // if($shift_id ==0 ||  $shift_id > 4){
                           $save_details = $this->User_model->addShiftDetails($shift_data);
                          // }
            } 
            else{
              $save_details = $this->User_model->addShiftDetails($shift_data);
            }
       
      }
    } */
    public function notifyOtherUnitNewUser($userids,$unit_id,$type){  //sending email to users from other unit added to rota by update users
      $unit_id = $unit_id;
      $count = count($userids);
     // $unit_supervisor_other_unit = $this->Leave_model->getUsersFromUnit($unit_id);
      $unit_supervisor_other_unit = $this->User_model->getSingleUser($this->session->userdata('user_id'));

      $findunitname=$this->Unit_model->findunit($unit_id);
      if(count($unit_supervisor_other_unit)>0){
        $supervisor_name = $unit_supervisor_other_unit[0]['fname'].' '.$unit_supervisor_other_unit[0]['lname'];
      }else{
        $supervisor_name = '';
      }
      $unit_name = $findunitname[0]['unit_name'];
      for ($i = 0; $i < $count; $i++)
      {
        $users_from_other_unit = $this->User_model->getSingleUser($userids[$i]);
        $subject = 'You have assigned shift for '.$unit_name. ' for the following days';
        $staff_name = $users_from_other_unit[$i]['fname']." ".$users_from_other_unit[$i]['lname'];
        $user_shift_details['user_details'] = $this->Shift_model->getOtherUnitUserShiftDetails($userids[$i],$unit_id);
        if(count($user_shift_details['user_details']) > 0)
        {$site_title = 'St Matthews Healthcare - SMH Rota';
                  $admin_email=getCompanydetails('from_email');
                  $emailSettings = array();
                  $emailSettings = array(
                      'from' => $admin_email,
                      'site_title' => $site_title,
                      'site_url' => $this->config->item('base_url'),
                      'to' => $users_from_other_unit[$i]['email'],
                      'type' => $type,
                      'staff_name' => $staff_name,
                      'supervisor_name'=>$supervisor_name,
                      'subject' => $subject,
                      'data' => $user_shift_details['user_details'],
                      'content_title'=>'We are glad to have you!',
                      'recover_url' => $this->config->item('base_url').'admin/rota/shiftAprove/'. $userids[$i]
                  );
                  //print_r($emailSettings);exit();
                  $this->load->library('parser');
                  $htmlMessage = $this->parser->parse('emails/staff_notify_email', $emailSettings, true);
                  // die($htmlMessage);exit();
                  $this->load->helper('mail');
                  sendMail($emailSettings, $htmlMessage);}  
      } 
    }
    public function notifyOtherUsersAboutShift($unit_id){//unit_id
        $unit_id = $unit_id;
        $staff_names = "";
        $users_from_other_unit = $this->Shift_model->getUsersFromOtherUnit();
        $count = count($users_from_other_unit);
        //$unit_supervisor_other_unit = $this->Leave_model->getUsersFromUnit($unit_id);
        //$supervisor_name = $unit_supervisor_other_unit[0]['fname'].' '.$unit_supervisor_other_unit[0]['lname'];
        $unit_supervisor_other_unit = $this->User_model->getSingleUser($this->session->userdata('user_id'));
        if(count($unit_supervisor_other_unit)>0)
        {
          $supervisor_name = $unit_supervisor_other_unit[0]['fname'].' '.$unit_supervisor_other_unit[0]['lname'];
        }
        else
        {
          $supervisor_name = '';
        }
        $findunitname=$this->Unit_model->findunit($unit_id);
        $unit_name = $findunitname[0]['unit_name'];

        for ($i = 0; $i < $count; $i++)
        {
            $staff_names .= $users_from_other_unit[$i]['fname'] ." ".$users_from_other_unit[$i]['lname'] . ", ";
            $subject = 'You have assigned shift for '.$unit_name. ' for the following days';
            $staff_name = $users_from_other_unit[$i]['fname']." ".$users_from_other_unit[$i]['lname'];
            $user_shift_details['user_details'] = $this->Shift_model->getOtherUserShiftDetails($users_from_other_unit[$i]['user_id']);
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array();
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => $users_from_other_unit[$i]['email'],
                'type' => 'Shift Allocation',
                'staff_name' => $staff_name,
                'supervisor_name'=>$supervisor_name,
                'subject' => $subject,
                'data' => $user_shift_details['user_details'],
                'content_title'=>'We are glad to have you!',
                'recover_url' => $this->config->item('base_url').'admin/rota/shiftAprove/'. $users_from_other_unit[$i]['user_id']
            );
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/staff_notify_email', $emailSettings, true);
            //die($htmlMessage);exit();
            $this->load->helper('mail');
            sendMail($emailSettings, $htmlMessage);  
        } 
    }
    public function createRotaHtml($data){
      $count = count($data);
      $html = '';
      for($i=0;$i<$count;$i++){
        $week_count = count($data[$i]['Week']);
        $shift_data = $data[$i]['Week'];
        $html .= '<p style="text-align:center;font-weight:normal;font-family:sans-serif !important;">Week '.$this->weekOfMonth($data[$i]['rota']['start_date']).'</p>'.
          '<table  border="0" cellpadding="0" cellspacing="0"  style="margin: auto; width: 100%; font-weight: normal; font-family:sans-serif !important"><thead><tr><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Shift Name</th><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Date</th></tr></thead><tbody>';
        for($k=0;$k<$week_count;$k++){
          $result = $this->Rota_model->findUnitShift($shift_data[$k]['user_id'],$shift_data[$k]['date']);
          $html .= '<td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;">'.$shift_data[$k]['shift_name'].'</td>'.
            '<td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;">'.$this->corectDateFormat($shift_data[$k]['date']).'</td></tr>';
        }
        $html .= '</tbody></table>';
      }
      return $html;
    }
    public function saveHistory($newUserids,$rota_id,$rota_history,$type,$rota_unitid,$changed_dates,$unit_id){
      $user_count = count($newUserids);
      $rota_log_array = array();
      for ($i = 0; $i < $user_count; $i++)
      {
        if(count($changed_dates) > 0){
          $dates_array = $changed_dates[$newUserids[$i]];
          if(count($dates_array) > 0){
            $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetailsWithDate($newUserids[$i],$rota_id,$dates_array,$unit_id);
          }else{
            $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetails($newUserids[$i],$rota_id,[]);
          }
        }
        else
        {
          $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetails($newUserids[$i],$rota_id,[]);
        }
        //creating html for inserting rota log table
        $html = $this->createRotaHtml($user_shift_details['user_details']);
        //calling function to insert into rota_log array
        $rota_log_details = array(
          'rota_id' => $rota_id,
          'start_date' => $user_shift_details['user_details'][0]['rota']['start_date'],
          'end_date' => $this->addDays(6,$user_shift_details['user_details'][0]['rota']['start_date']),
          'user_id' => $newUserids[$i],
          'rota_details' => $html,
          'updated_by' => $this->session->userdata('user_id'),
          'updated_datetime' => date('Y-m-d H:i:s'),
          'type' => $type,
          'unit_id' => $rota_unitid
        );
        $rota_log_id = $this->Rota_model->insertRotaLog($rota_log_details);
        $rota_log_array[$newUserids[$i]]=$rota_log_id;
      }
      for ($i = 0; $i < count($rota_history); $i++)
      {
        $rota_log_history_details = array(
          'user_id' => $rota_history[$i]['user_id'],
          'shift_id' => $rota_history[$i]['shift_id'],
          'shift_hours' => $rota_history[$i]['shift_hours'],
          'additional_hours' => $rota_history[$i]['additional_hours'],
          'comment' => $rota_history[$i]['comment'],
          'from_unit' => $rota_history[$i]['from_unit'],
          'unit_id' => $rota_history[$i]['unit_id'],
          'rota_id' => $rota_history[$i]['rota_id'],
          'date' => $rota_history[$i]['date'],
          'status' => $rota_history[$i]['status'],
          'creation_date' => $rota_history[$i]['creation_date'],
          'created_userid' => $rota_history[$i]['created_userid'],
          'updation_date' => $rota_history[$i]['updation_date'],
          'updated_userid' => $rota_history[$i]['updated_userid'],
          'day' => $rota_history[$i]['day'],
          'shift_category' => $rota_history[$i]['shift_category'],
          'from_userid' => $rota_history[$i]['from_userid'],
          'from_rotaid' => $rota_history[$i]['from_rotaid'],
          'rota_logid' => $rota_log_array[$rota_history[$i]['user_id']]
        ); 
        $rota_log_history_id = $this->Rota_model->insertHistoryRotaSchedule($rota_log_history_details);
      }
    }
    public function removeInactiveUserIdsFromMail($userids){
      $new_array = array();
      foreach ($userids as $user) 
      {
        $user_details = $this->User_model->findUserStatus($user);
        $status = $user_details[0]['status'];
        if($status == 1){
          array_push($new_array, $user);
        }
      }
      return $new_array;
    }
    public function notifyUserWithShiftScheduleAndReschedule($newUserids,$unit_id,$status,$rota_id,$type,$changed_dates){ //notify user with shift shedule and reschedule
      $newUserids = $this->removeInactiveUserIdsFromMail($newUserids);
      if(count($newUserids) > 0){
        
        $unit_id = $unit_id;
        $user_count = count($newUserids); 
        $unit_supervisor_same_unit = $this->Leave_model->getUsersFromUnit($unit_id); 
        $findunitname=$this->Unit_model->findunit($unit_id);
        if(count($unit_supervisor_same_unit)> 0){
          $supervisor_name = $this->session->userdata('full_name');
          $unit_name = $unit_supervisor_same_unit[0]['unit_name'];
        }
        else
        {
          $supervisor_name = $this->session->userdata('full_name');
          $unit_name = '';
        }
        $unit_name = $findunitname[0]['unit_name'];
        for ($i = 0; $i < $user_count; $i++)
        {
          $users_from_same_unit = $this->User_model->getSingleUser($newUserids[$i]);
          $user_parent_unit = $users_from_same_unit[0]['unit_id'];
          $dates_array = $changed_dates[$newUserids[$i]];
          $mobilenumber = $users_from_same_unit[0]['mobile_number'];
          $site_title = 'St Matthews Healthcare: Rota -';
          if($status ==1){
            $subject = $site_title.'Published'.'-'.$unit_name;
          }else{
            $subject = $site_title.'Rescheduled'.'-'.$unit_name;
          }
          $staff_name = $users_from_same_unit[0]['fname']." ".$users_from_same_unit[0]['lname'];
          if($user_parent_unit == $unit_id){
            $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetails($newUserids[$i],$rota_id,[]);
          }else{
            $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetails($newUserids[$i],$rota_id,$dates_array);
          }
          //print_r($user_shift_details['user_details']);exit();
          $weeks = '';
          for($k=0;$k<count($user_shift_details['user_details']);$k++){
            $weeks .= " week ".$this->weekOfMonth($user_shift_details['user_details'][$k]['rota']['start_date'])." : ".$this->corectDateFormat($user_shift_details['user_details'][$k]['rota']['start_date']).' to '.$this->corectDateFormat($this->addDays(6,$user_shift_details['user_details'][$k]['rota']['start_date']))."\n";
            // print $user_shift_details['user_details'][$i]['rota']['start_date'];
          }
          if($mobilenumber){
            //sms integration
            $message = "Your rota is rescheduled, for the following week.\n".$weeks."\n".$this->config->item('base_url');
            $this->load->model('AwsSnsModel');
            $sender_id="01";
            $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $message);
          }
          $admin_email=getCompanydetails('from_email');
          $emailSettings = array();
          $emailSettings = array(
              'from' => $admin_email,
              'site_title' => $site_title,
              'site_url' => $this->config->item('base_url'),
              'to' => $users_from_same_unit[0]['email'],
              'type' => $type,
              'staff_name' => $staff_name,
              'supervisor_name'=>$supervisor_name,
              'data' => $user_shift_details['user_details'],
              'subject' => $subject,
              'content_title'=>'We are glad to have you!',
          );
         // print_r($emailSettings);exit();
          $this->load->library('parser');
          $htmlMessage = $this->parser->parse('emails/shift_notify_email', $emailSettings, true);
          // die($htmlMessage);exit();
          $this->load->helper('mail');
          sendMail($emailSettings, $htmlMessage);
        }
      }
    }
    public function notifySameUsersAboutShift($unit_id){
        $unit_id = $unit_id;
        $users_from_same_unit = $this->Shift_model->getUsersFromSameUnit();
        $user_count = count($users_from_same_unit);
        //$unit_supervisor_same_unit = $this->Leave_model->getUsersFromUnit($unit_id);
        //$supervisor_name = $unit_supervisor_same_unit[0]['fname'].' '.$unit_supervisor_same_unit[0]['lname'];

        $unit_supervisor_same_unit = $this->User_model->getSingleUser($this->session->userdata('user_id'));
        if(count($unit_supervisor_same_unit)>0)
        {
          $supervisor_name = $unit_supervisor_same_unit[0]['fname'].' '.$unit_supervisor_same_unit[0]['lname'];
        }
        else
        {
          $supervisor_name = '';
        }
        $findunitname=$this->Unit_model->findunit($unit_id);
        $unit_name = $findunitname[0]['unit_name'];


        for ($i = 0; $i < $user_count; $i++)
        {
            $start_date = date('F d Y ', strtotime($users_from_same_unit[$i]['date']));
            $date = strtotime("+6 days", strtotime($users_from_same_unit[$i]['date']));
            $end_date = date("Y-m-d", $date);
            $last_date = date('F d Y ', strtotime($end_date));
            $subject = 'You are assigned shift for ' .$unit_name. ' for the following days';
            $staff_name = $users_from_same_unit[$i]['fname']." ".$users_from_same_unit[$i]['lname'];
            $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetails($users_from_same_unit[$i]['user_id']);
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array();
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => $users_from_same_unit[$i]['email'],
                'type' => 'Shift Allocation',
                'staff_name' => $staff_name,
                'supervisor_name'=>$supervisor_name,
                'data' => $user_shift_details['user_details'],
                'subject' => $subject,
                'content_title'=>'We are glad to have you!',
            );
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/shift_notify_email', $emailSettings, true);
            $this->load->helper('mail');
            sendMail($emailSettings, $htmlMessage);  
        }
    }
    public function shiftAprove(){
        $user_id =$this->uri->segment(4);
        $result = array();
        $this->load->view('includes/home_header');
        $user_shift_details['user_details'] = $this->Shift_model->getOtherUserShiftDetails($user_id);
        $user_shift_details['user_id']= $user_id;
        $user_shift_details['unit_id'] = $user_shift_details['user_details'][0]['unit_id'];
        $user_shift_details['unit_name'] = $user_shift_details['user_details'][0]['unit_name'];
        $user_shift_details['from_unit'] = $user_shift_details['user_details'][0]['from_unit'];
        $this->load->view("admin/rota/list_rota",$user_shift_details);
        $result['js_to_load'] = array('rota/users.js');
        $this->load->view('includes/footer_rota',$result);
    }
    public function manageShift(){
        $user_id = $this->input->post("user_id");
        $status  = $this->input->post("status");
        $unit_id = $this->input->post("unit_id");
        $from_unit = $this->input->post("from_unit");
        if($status == 1){
            $message = "approved";
        }else{
            $message = "rejected";
        }
        $actionStatus = $this->Shift_model->approveRejectShift($user_id,$status);
        $user_shift_details = $this->Shift_model->getOtherUserShiftDetails($user_id);
        $staff_name = $user_shift_details[0]['fname'].' '.$user_shift_details[0]['lname'];
        $unit_supervisor_same_unit = $this->Leave_model->getUsersFromUnit($unit_id);
        if(count($unit_supervisor_same_unit)>0){
          $supervisor_name = $unit_supervisor_same_unit[0]['fname'].' '.$unit_supervisor_same_unit[0]['lname'];
          $supervisor_email = $unit_supervisor_same_unit[0]['email'];
          $unit_supervisor_other_unit = $this->Leave_model->getUsersFromUnit($from_unit);
          if(count($unit_supervisor_other_unit)>0){
            $other_supervisor_email = $unit_supervisor_other_unit[0]['email'];
          }else{
            $other_supervisor_email = '';
          }
          $subject = $staff_name.' has ' .$message. ' shift for the following days';
          $site_title = 'St Matthews Healthcare - SMH Rota';
          $admin_email=getCompanydetails('from_email');
          $emailSettings = array();
          $emailSettings = array(
              'from' => $admin_email,
              'site_title' => $site_title,
              'site_url' => $this->config->item('base_url'),
              'to' => $supervisor_email,
              'cc'=> $other_supervisor_email,
              'type' => 'Shift Allocation',
              'staff_name' => $staff_name,
              'supervisor_name' => $supervisor_name,
              'data' => $user_shift_details,
              'subject' => $subject,
              'content_title'=>'We are glad to have you!',
          );
          $this->load->library('parser');
          $htmlMessage = $this->parser->parse('emails/manager_notify_email', $emailSettings, true);
          $this->load->helper('mail');
          sendMail($emailSettings, $htmlMessage);
          header('Content-Type: application/json');
          echo json_encode(array('status' => 1,'message' => "Mail sending successfull"));
            exit();
        }else{
          header('Content-Type: application/json');
          echo json_encode(array('status' => 1,'message' => "Mail sending failed"));
              exit();
        }
    }
/*     function to copy week shcedule
 */   
 public function copyShift(){ 
     
     
        $rota_id = $this->input->post("rota_id");
        $session_id = $this->input->post("session_id");
        $rota_settings = $this->input->post("rota_settings");
        $this->load->model('Rota_model');
        $this->load->model('Rotaschedule_model');
        $result = $this->Rota_model->getRota($rota_id);
        $unitId = $result[0]['unit_id'];
        $startDate = $result[0]['start_date'];
        $endDate = $result[0]['end_date'];
        
        $nextweek_startDate = date('Y-m-d', strtotime($endDate . ' +1 day'));
        $nextweek_endtDate = date('Y-m-d', strtotime($endDate . ' +7 day'));
        $prev_date = date('Y-m-d', strtotime($nextweek_startDate .' -1 day'));
        $next_date = date('Y-m-d', strtotime($nextweek_endtDate .' +1 day'));
        $prev_and_next_shifts = array();
        $all_previous_shifts = array();
        $user_id_arrays = json_decode($this->input->post("user_ids"));
        $dateValue = strtotime($nextweek_endtDate);
        $year = date('Y',$dateValue);
        $monthNo = date('m',$dateValue);
        $rota_data    = array(
            'rota_settings'=> $rota_settings,
            'start_date' => $nextweek_startDate,
            'end_date' => $nextweek_endtDate,
            'unit_id' => $unitId,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->userdata('user_id'),
            'published' => 0,
            'month' => $monthNo,
            'year' => $year
        ); 
        
        $result = $this->Rota_model->addRotaDetails($rota_data); 
        $rotaId = $result['rota_id'];
        $user_name=$this->User_model->findusername($session_id);
        $rota_details = $this->Rota_model->getRota($rotaId);        
        $rota_unit= $this->Rota_model->findRotaUnit($rota_details[0]['unit_id']);  
        $rota_setting= $this->Rota_model->findSettings($rota_details[0]['rota_settings']);
        if(count($user_name) > 0){
          $description=$user_name[0]['fname'].' '.$user_name[0]['lname'].' '.'has created a rota(copied from previous week) for '.$rota_unit[0]['unit_name'].' '.'for the week'.' '.$rota_details[0]['start_date'].' '.'to'.' '.$rota_details[0]['end_date'].' '.'with settings: day shift minimum:'.$rota_setting[0]['day_shift_min'].','.' '.'day shift maximum:'.$rota_setting[0]['day_shift_max'].','.' '.'night shift maximum:'.$rota_setting[0]['night_shift_min'].','.' '.'night shift maximum:'.$rota_setting[0]['night_shift_max'].','.' '.'number of patients:'.$rota_setting[0]['num_patients'].','.' '.'1:1 patients:'.$rota_setting[0]['one_one_patients'].','.' '.'nurse_day_count:'.$rota_setting[0]['nurse_day_count'].','.' '.'nurse_night_count:'.$rota_setting[0]['nurse_night_count'];
          $log=array(
            'description'   => $description,
            'activity_date' => $rota_details[0]['created_date'],
            'activity_by'   => $rota_details[0]['created_user_id'],
            'add_type'      => 'Create New Rota',
            'user_id'       => ' ',
            'primary_id'    => $rota_details[0]['rota_settings'],
            'creation_date' => date('Y-m-d H:i:s')
          );
          $this->Activity_model->insertactivity($log);
        }
        if($result['status']==1)
            $this->Rotaschedule_model->deleteShifts($rotaId);
            $schedules = $this->Rotaschedule_model->getRotaschedules($rota_id);
            $user=0;
            $selectedShifts = array();
            foreach ($schedules as $schedule){ //print_r($schedule);
              /*if(!in_array($schedule["user_id"], $user_id_arrays, true)){
                array_push($user_id_arrays, $schedule["user_id"]);
              }*/
              $date =  date('Y-m-d', strtotime($schedule["date"] . ' + 7 day'));
              //print_r($date);

              if($schedule["shift_id"]== 1 || $schedule["shift_id"]== 2)
              {
                $shift=$this->findUserShifts($schedule["user_id"],$date);
                $shift_id=$shift['shift_id'];
                $shift_hour=$shift['shift_hours'];
              }
              else
              {
                $shift_id=$schedule["shift_id"];
                $shift_hour=$schedule["shift_hours"];
              }
              $nameOfDay = date('D', strtotime($date));
              //echo $nameOfDay; exit();
               $designation_id=$this->Designation_model->finduserDesignation($schedule["user_id"]);

                if($user!=$schedule["user_id"])
                    $i=0;
                
                $shift_data = array(
                    'user_id'=>$schedule["user_id"],
                    'shift_id'=>$shift_id,
                    'unit_id'=>$schedule["unit_id"],
                    'from_unit'=>$schedule["from_unit"],
                    'shift_hours'=>$shift_hour,
                    'day'=>$nameOfDay,
                    'designation_id'=>$designation_id,
                    'rota_id'=>$rotaId,
                    'date'=>$date,
                    'status'=>$schedule["status"],
                    'creation_date'=>date('Y-m-d H:i:s'),
                    'created_userid'=>$this->session->userdata('user_id'),
                    'updation_date'=>date('Y-m-d H:i:s')
                    
                );  //print_r($shift_data);
              
                $selectedShifts['shift_'.$schedule["user_id"].'_'.$i]=$shift_id; //print_r($selectedShifts['shift_'.$schedule["user_id"].'_'.$i]);
                $i++;
                $user = $schedule["user_id"];
                $save_details = $this->Rotaschedule_model->addShiftDetails($shift_data);
                
            } //exit();
            $scheduled_user_count = $this->Shift_model->getScheduledUserCount($rotaId);
            foreach ($user_id_arrays as $user_id){
              $prev_next_shifts = $this->Shift_model->getPrevNextShift($prev_date,$next_date,$user_id);
              $previous_shifts = $this->Shift_model->getPrevNextShiftForHighlightingColor($prev_date,$user_id);
              if(count($prev_next_shifts) > 0){
                foreach($prev_next_shifts as $prev_next_shift){
                  array_push($prev_and_next_shifts, $prev_next_shift);
                }
              }
              if(count($previous_shifts) > 0){
                foreach($previous_shifts as $previous_shift){
                  array_push($all_previous_shifts, $previous_shift);
                }
              }
            }
            header('Content-Type: application/json');
            echo json_encode(array('status' => 1,'message' => "Success",'rota_id' => $rotaId,'selectedShifts'=>$selectedShifts,'result'=>$rota_details,'scheduled_user_count'=>$scheduled_user_count,'prev_and_next_shifts' => $prev_and_next_shifts,'all_previous_shifts' => $all_previous_shifts));
            exit();
            
            
  }

  public function findUserShifts($user_id,$date)
  {
    $this->load->model('Workschedule_model');
    $Workschedule=$this->Workschedule_model->getUserworkschedule($user_id);  
    $nameOfDay = date('D', strtotime($date)); 
    if($nameOfDay=="Sun"){ $day="sunday";}
    if($nameOfDay=="Mon"){ $day="monday";}
    if($nameOfDay=="Tue"){ $day="tuesday";}
    if($nameOfDay=="Wed"){ $day="wednesday";}
    if($nameOfDay=="Thu"){ $day="thursday";}
    if($nameOfDay=="Fri"){ $day="friday";}
    if($nameOfDay=="Sat"){ $day="saturdy";}
    if( in_array( $day ,$Workschedule ) )
    { 
        $result=array('shift_id'=>"0",'shift_hours'=>"0");
    }
    else
    {
          $default_shift=$this->Rota_model->finddefaultshiftByuser($user_id);
          $result=array('shift_id'=>$default_shift[0]['default_shift'],'shift_hours'=>$default_shift[0]['total_targeted_hours']);
    }
   return $result;
  }


  public function getScheduleData(){
      $this->load->model('Shift_model');
      $this->load->model('Rotaschedule_model');
      $this->load->model('Staffrota_model');
      
      $params=array();
      $posts=array();
      $params['user_id']='';
      $rotaId="";
      $unit_id = $this->input->post("unit_id");
      $params['unit_id']=$this->input->post("unit_id");
      $user_from_other_unit=$this->input->post("user_from_other_unit");
      $start_date=$this->input->post("start_date");
      $new_date=explode('-',$start_date);
      if($this->count_digit($new_date[1])==1)
      {
         $start_month='0'.$new_date[1];
      }
      else
      {
        $start_month=$new_date[1];
      }
      if($this->count_digit($new_date[2])==1)
      {
        $start_day='0'.$new_date[2];
      }
      else
      {
        $start_day=$new_date[2];
      }

      $params['start_date']=$new_date[0].'-'.$start_month.'-'.$start_day;

      $params['end_date']=date('Y-m-d', strtotime($this->input->post("end_date") . ' - 1 day'));
      //print_r($params);exit();
      $prev_date = date('Y-m-d', strtotime($params['start_date'] .' -1 day'));
      $next_date = date('Y-m-d', strtotime($params['end_date'] .' +1 day'));
      $prev_and_next_shifts = array();
      $all_previous_shifts = array();
      $user_id_arrays = json_decode($this->input->post("user_ids"));
      $schedules=$this->Shift_model->getWeeklyShifts($params);
      $staff_availability=$this->Staffrota_model->getWeeklyShiftsForAdmin($params,$user_from_other_unit);
      $rota_details = $this->Shift_model->getRota($params); 
      if(empty($rota_details))
      {
        $scheduled_user_count=array();
      }
      else
      {
      $scheduled_user_count = $this->Shift_model->getScheduledUserCount($rota_details[0]['id']); 
      }
      $unit_details = $this->Unit_model->findunit($unit_id);
      $staff_limit  = $unit_details[0]['staff_limit'];
      $user=0;
    //  print_r($params);
      $selectedShifts = array();
      $selectedStaffShifts = array();
      foreach ($schedules as $schedule){
          /*if(!in_array($schedule["user_id"], $user_id_arrays, true)){
            array_push($user_id_arrays, $schedule["user_id"]);
          }*/
          // print_r($schedule);
          if($user!=$schedule["user_id"])
              $i=0;
              $selectedShifts['shift_'.$schedule["user_id"].'_'.$i]=$schedule["shift_id"].'_'.$schedule["unit_id"];
              $i++;
              $user = $schedule["user_id"];
               
      }
      foreach ($staff_availability as $schedule){
        $userDetails=$this->User_model->findusers($schedule["user_id"]);   
          // print_r($schedule);
          if($user!=$schedule["user_id"])
              $i=0;
              $selectedStaffShifts['shift_'.$schedule["user_id"].'_'.$schedule["date"]]=$schedule["shift_id"].'_'.$userDetails[0]['default_shift'].'_'.$schedule["shift_shortcut"];
              $i++;
              $user = $schedule["user_id"];
               
      }
      foreach ($user_id_arrays as $user_id){
        $prev_next_shifts = $this->Shift_model->getPrevNextShift($prev_date,$next_date,$user_id);
        $previous_shifts = $this->Shift_model->getPrevNextShiftForHighlightingColor($prev_date,$user_id);
        if(count($prev_next_shifts) > 0){
          foreach($prev_next_shifts as $prev_next_shift){
            array_push($prev_and_next_shifts, $prev_next_shift);
          }
        }
        if(count($previous_shifts) > 0){
          foreach($previous_shifts as $previous_shift){
            array_push($all_previous_shifts, $previous_shift);
          }
        }
      }
      header('Content-Type: application/json');
      echo json_encode(array('status' => 1,'message' => "Success",'selectedShifts'=>$selectedShifts,'selectedStaffShifts'=>$selectedStaffShifts,'result'=>$rota_details,'staff_limit'=>$staff_limit,'scheduled_user_count'=>$scheduled_user_count,'prev_and_next_shifts' => $prev_and_next_shifts,'all_previous_shifts' => $all_previous_shifts));
      exit();
  }

  function count_digit($number) {
  return strlen($number);
}
  public function findShiftCat(){
    $shift_id     = $this->input->post("shift_id");
    $shift_etails = $this->Shift_model->findshift($shift_id);
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'result'=> $shift_etails));
    exit();
  }
  public function findUnpublishedShiftUsers(){
    $start_date = $this->input->post("start_date");
    $end_date  = $this->input->post("end_date");
    $unit_id = $this->input->post("unit_id");
    $shift_id = $this->input->post("shift_id");
    $details = array(
      'start_date' => $start_date,
      'end_date' => $end_date,
      'unit_id' => $unit_id,
      'shift_id' => $shift_id
    );
    $result = $this->Shift_model->findUnpublishedShiftUsers($details);
    header('Content-Type: application/json');
    echo json_encode(array($result));
    exit();
  }
  public function checkassigned(){
      
      $this->load->model('Rotaschedule_model');
      $this->load->model('Unit_model');
      $params = array();
      $params['user_id']=$this->input->post("user_id");
      $params['date']=$this->input->post("_date");
      $main_unit_id = $this->input->post("unit_id");
      
      $unitid=$this->Rotaschedule_model->checkassigned($params);
      $sub_unitids = $this->Rotaschedule_model->checkUnitidIsSubUnitOfMainUnit($main_unit_id);
      if(count($sub_unitids)>0){
        if (in_array($unitid, $sub_unitids)){
          $flag = 1;
        }else{
          $flag = 2;
        }
      }else{
        $flag = 2;
      }
      //get unit name
      
      $unitDetails=$this->Unit_model->findunit($unitid);
      header('Content-Type: application/json');
      echo json_encode(array('status' => $unitid,'unit_id' => $unitid,'unit_name' => $unitDetails[0]['unit_name'],'unit_color'=>$unitDetails[0]['color_unit'],'flag'=>$flag));
      exit();
  }
  //Added by chinchu for fixing issue --
  //Staff on their own rota show as Avenue but when adding to a different rota show as Day off.
  //Staff Naem – Sandhya Tamang for the 18th.
  public function getRotadetailsV2()
  {
    $this->load->model('Rotaschedule_model');
    $this->load->model('Unit_model');
    $date=$this->input->post('date'); 
    $selected_unit=$this->input->post('unit_id');
    $nameOfDay = date('D', strtotime($date));  
    if($nameOfDay=='Sun')
    {
      $start_date=$date; 
      $date = strtotime($start_date);
      $date1 = strtotime("+6 day", $date);
      $end_date = date('Y-m-d', $date1);
    }
    else
    {
      $start_date=date('Y-m-d', strtotime($date.'last sunday'));
      $date = strtotime($start_date);
      $date1 = strtotime("+6 day", $date);
      $end_date = date('Y-m-d', $date1);
    }
    $users=json_decode($this->input->post('rotabc'),true);
    foreach ($users as $value) {
      $unit=$this->Unit_model->findunitidofuser($value['id']);
      $unit_id=$unit[0]['unit_id'];
      $rota_data=$this->Rotaschedule_model->findrotadetailsV2($start_date,$end_date,$value['id'],$unit_id,$selected_unit);
      foreach ($rota_data as $rota_value) {
        if($rota_value['shift_id']>4){
          $Day = date('D', strtotime($rota_value['date'])); 
          if($Day=='Sun') $Weekday=0;
          if($Day=='Mon') $Weekday=1;
          if($Day=='Tue') $Weekday=2;
          if($Day=='Wed') $Weekday=3;
          if($Day=='Thu') $Weekday=4;
          if($Day=='Frd') $Weekday=5;
          if($Day=='Sat') $Weekday=6;
          $resource[] = array(
            "id"               =>  $rota_value['user_id'], 
            "title"            =>  $rota_value['unit_shortname'].'-'.$rota_value['shift_shortcut'],
            "date"             =>  $rota_value['date'],
            "Weekday"          =>  $Weekday,
            "start_time"       =>  $rota_value['start_time'],
            "end_time"         =>  $rota_value['end_time'],
            "shift_id"         =>  $rota_value['shift_id']
          );
        }
      }
    }
    header('Content-Type: application/json');
    echo json_encode($resource); 
    exit();
  }
  public function getRotadetails()
  {  //print_r('hii');exit();
    //print_r($this->input->post());exit();
    $this->load->model('Rotaschedule_model');
    $this->load->model('Unit_model');
    $date=$this->input->post('date'); 
    $selected_unit=$this->input->post('unit_id');
    $nameOfDay = date('D', strtotime($date));  
    if($nameOfDay=='Sun')
    {
      $start_date=$date; 
      $date = strtotime($start_date);
      $date1 = strtotime("+6 day", $date);
      $end_date = date('Y-m-d', $date1);
    }
    else
    {
      $start_date=date('Y-m-d', strtotime($date.'last sunday'));
      $date = strtotime($start_date);
      $date1 = strtotime("+6 day", $date);
      $end_date = date('Y-m-d', $date1);
    } 
    // print_r('start date-'.$start_date);
    // print_r('end date-'.$end_date);
    //  exit();
    //$end_date=$this->input->post('end_date');
    $users=$this->input->post('rotabc'); //print_r($users);exit();

    foreach ($users as $value) { //print_r($value);exit();
     $unit=$this->Unit_model->findunitidofuser($value['id']);  //print_r($unit); exit();
     $unit_id=$unit[0]['unit_id'];
     $rota_data=$this->Rotaschedule_model->findrotadetailsV2($start_date,$end_date,$value['id'],$unit_id,$selected_unit); //print_r($rota_data); //find rota
     foreach ($rota_data as $rota_value) { //print_r($rota_value);
       if($rota_value['shift_id']>4){
          $Day = date('D', strtotime($rota_value['date'])); 
          if($Day=='Sun') $Weekday=0;
          if($Day=='Mon') $Weekday=1;
          if($Day=='Tue') $Weekday=2;
          if($Day=='Wed') $Weekday=3;
          if($Day=='Thu') $Weekday=4;
          if($Day=='Frd') $Weekday=5;
          if($Day=='Sat') $Weekday=6;

           $resource[] = array(
                            "id"               =>  $rota_value['user_id'], 
                            "title"            =>  $rota_value['unit_shortname'].'-'.$rota_value['shift_shortcut'],
                            "date"             =>  $rota_value['date'],
                            "Weekday"          =>  $Weekday,
                            "start_time"       =>  $rota_value['start_time'],
                            "end_time"         =>  $rota_value['end_time'],
                            "shift_id"         =>  $rota_value['shift_id']
                                );
         }
       
       }
    //print_r($resource);
    }
   //exit();
      header('Content-Type: application/json');
      echo json_encode($resource); 
     exit();

  }


  public function corectDateFormat($date){
    $my_str = $date;
    $date_array = array();
    $date_array = explode("-", $my_str);
    $date_with_slash = $date_array[2]."/".$date_array[1]."/".$date_array[0];
    return $date_with_slash;
  }
  public function sendMailAboutTraining($userids,$rotaIds){
    $userids = $this->removeInactiveUsersFromMail($userids);
    $user_ids = array();
    $params = array();
    for ($j = 0; $j <count($userids); $j++){
      array_push($user_ids, $userids[$j]['user_id']);
    }
    $rota_details=$this->Rota_model->getDateDetails($rotaIds);
    if(count($rota_details) > 0){
      $count = count($rota_details);
      for ($i = 0; $i < $count; $i++){
        $start_date = $rota_details[$i]['start_date'];
        $end_date   = $rota_details[$i]['end_date'];
        $params['from_date'] = $start_date;
        $params['to_date'] = $end_date;
        $params['users'] = $user_ids;
        $training_details = $this->Training_model->getTrainingDetails($params);
        $t_count = count($training_details);
        for ($k = 0; $k < $t_count; $k++){
          $site_title = 'St Matthews Healthcare - SMH Rota';
          $admin_email=getCompanydetails('from_email');
          $emailSettings = array();
          $emailSettings = array(
            'from' => $admin_email,
            'site_title' => $site_title,
            'site_url' => $this->config->item('base_url'),
            'to' => $training_details[$k]['email'],
            'type' => 'Rota - Training details',
            'user_name'=> $training_details[$k]['fname'].' '. $training_details[$k]['lname'],
            'fromdate'=> corectDateFormat($training_details[$k]['date_from']),
            'todate'=>corectDateFormat($training_details[$k]['date_to']),
            'time_from'=>$training_details[$k]['time_from'],
            'time_to'=>$training_details[$k]['time_to'],
            'place'=>$training_details[$k]['place'],
            'address'=>$training_details[$k]['address'],
            'title'=>$training_details[$k]['title'],
            'comments'=>$training_details[$k]['description'],
            'point_of_person'=>$training_details[$k]['point_of_person'],
            'contact_num'=>$training_details[$k]['contact_num'],
            'contact_email'=>$training_details[$k]['contact_email'],
            'subject' => $site_title. '- Training: '.$training_details[$k]['title'],
          );
          $this->load->library('parser');
          $htmlMessage = $this->parser->parse('emails/training', $emailSettings, true);
          $result = $this->Training_model->updatePublishedStatus($training_details[$k]['user_id'],$training_details[$k]['training_id']);
          $this->load->helper('mail');
          sendMail($emailSettings, $htmlMessage);
        }
      }
    }
  }
  public function weekOfMonth($qDate) {
    $dt = strtotime($qDate);
    $day  = date('j',$dt);
    $month = date('m',$dt);
    $year = date('Y',$dt);
    $totalDays = date('t',$dt);
    $weekCnt = 0;
    $retWeek = 0;
    for($i=1;$i<=$totalDays;$i++) {
        $curDay = date("N", mktime(0,0,0,$month,$i,$year));
        if($curDay==7) {
            if($i==$day) {
                $retWeek = $weekCnt+1;
            }
            $weekCnt++;
        } else {
            if($i==$day) {
                $retWeek = $weekCnt;
            }
        }
    }
    return $retWeek;
  }
  public function addDays($day,$date){
    return date('Y-m-d', strtotime($date. ' + 6 days'));
  }
  public function removeInactiveUsersFromMail($userids){
    $new_array = array();
    foreach ($userids as $user) 
    {
      $user_details = $this->User_model->findUserStatus($user['user_id']);
      $status = $user_details[0]['status'];
      if($status == 1){
        array_push($new_array, $user);
      }
    }
    return $new_array;
  }
  public function sendMailAboutShift($userids,$rotaIds,$type,$unit_id){
    $userids = $this->removeInactiveUsersFromMail($userids);
    $count = count($userids);
    for ($i = 0; $i < $count; $i++)
    {
      $unit_id   = $userids[$i]['unit_id'];
      $from_unit = $userids[$i]['from_unit'];
      $user_id   = $userids[$i]['user_id'];
      $rota_id   = $userids[$i]['rota_id'];
      $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));
      $unit_and_user_details = $this->Leave_model->getUsersFromUnit($unit_id);
      $unit_name =$this->Unit_model->findunit($unit_id)[0]['unit_name'];
      if(count($session_user)>0){
        $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
      }else{
        $unit_supervisor_name = '';
      }
      /*if($from_unit){
        $query = http_build_query($rotaIds);
        $recover_url = $this->config->item('base_url').'users/shiftAprove/'. $user_id.'/'.$query;
      }else{
        $recover_url = null;
      }*/
      $user_details = $this->User_model->getSingleUser($user_id);
      $mobilenumber = $user_details[0]['mobile_number'];
      $subject = 'Your upcoming rota at '.$unit_name;
      $staff_name = $user_details[0]['fname']." ".$user_details[0]['lname'];
      $user_parent_unit = $user_details[0]['unit_id'];

      if($user_parent_unit == $unit_id){
        $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetailsForMail($user_id,$rotaIds);
      }else{
        $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetailsForMailOtherUnit($user_id,$rotaIds,$unit_id);
      }
      $weeks = '';
      for($k=0;$k<count($user_shift_details['user_details']);$k++){
        $weeks .= " week ".$this->weekOfMonth($user_shift_details['user_details'][$k]['rota']['start_date'])." : ".$this->corectDateFormat($user_shift_details['user_details'][$k]['rota']['start_date']).' to '.$this->corectDateFormat($this->addDays(6,$user_shift_details['user_details'][$k]['rota']['start_date']))."\n";
        // print $user_shift_details['user_details'][$i]['rota']['start_date'];
      }
      if(count($user_shift_details['user_details']) > 0){
        if($mobilenumber){
          //sms integration
          $message = "Your rota for next month is ready, for the following week(s).\n".$weeks."\n".$this->config->item('base_url');
          $this->load->model('AwsSnsModel');
          $sender_id="01";
          $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $message);
        }
        $site_title = 'St Matthews Healthcare - SMH Rota';
        $admin_email=getCompanydetails('from_email');
        $emailSettings = array();
        $emailSettings = array(
            'from' => $admin_email,
            'site_title' => $site_title,
            'site_url' => $this->config->item('base_url'),
            'to' => $user_details[0]['email'],
            'type' => $type,
            'staff_name' => $staff_name,
            'supervisor_name'=>$unit_supervisor_name,
            'subject' => $subject,
            'data' => $user_shift_details['user_details'],
            'content_title'=>'We are glad to have you!',
            'recover_url' => null,
            'user_id'=>$user_id
        );
        $this->load->library('parser');
        $htmlMessage = $this->parser->parse('emails/new_shift_mail', $emailSettings, true);
        // die($htmlMessage);exit();
        $this->load->helper('mail');
        sendMail($emailSettings, $htmlMessage);
      } 
    }
  }
  public function publishCopiedData(){
    $params = array();
    $params['unit_id']=$this->input->post("unit_id");
    $params['start_date']=$this->input->post("start_date");
    $params['end_date']=$this->input->post("end_date");
    $result = $this->Shift_model->publishCopiedData($params);
    if($result['status'] == 1){
      $this->notifyOtherUsersAboutShift($params['unit_id']);
      $this->notifySameUsersAboutShift($params['unit_id']);
      header('Content-Type: application/json');
      echo json_encode(array('status' => 1,'result'=> $result['rota_data']));
      exit();
    }else{
      header('Content-Type: application/json');
      echo json_encode(array('status' => 2,'result'=> []));
      exit();
    }
  }
  public function checkShiftExist(){
    $params['unit_id']   = $this->input->post("unit_id");
    $params['start_date']= $this->input->post("start_date");
    $params['end_date']  = $this->input->post("end_date");
    $rota_details = $this->Shift_model->getRota($params);
    $scheduled_user_count = $this->Shift_model->getScheduledUserCount($rota_details[0]['id']);
    //print_r($scheduled_user_count);exit();
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'result'=> $rota_details,'scheduled_user_count'=>$scheduled_user_count));
    exit();
  }
  public function getNextMonthRota(){
    $params = array();
    $params['month']   = $this->input->post("month");
    $params['unit_id']   = $this->input->post("unit_id"); // added by siva date 11 dec
    $params['year']    = $this->input->post("year");
    $rota_details = $this->Rota_model->getNextMonthRota($params);
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'result'=> $rota_details));
    exit();
  }
  public function getShiftId(){
    $params = array();
    $params['unit_id'] = $this->input->post("unit_id");
    $params['user_id'] = $this->input->post("user_id");
    $params['date']    = $this->input->post("date");
    $shift = $this->Rota_model->getShiftId($params);
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'result'=> $shift));
    exit();
  }
  public function getUserName(){
    $params = array();
    $user_id = $this->input->post("user_id");
    $user = $this->User_model->getSingleUser($user_id);
    $user_name = $user[0]['fname'].' '.$user[0]['lname'];
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'name'=> $user_name));
    exit();
  }
  public function validateRota()
  {  //print_r('hii');exit();
      //print_r($this->input->post());exit();
      if (!$this->session->userdata('user_id')) {
        // Session has expired
        echo json_encode(array('status' => 'error', 'message' => 'Session expired','url' => base_url()));
        return;
      }
       $this->load->model('Rota_model');
       $sDate=$this->input->post('sDate');
       $eDate=$this->input->post('eDate');
       $unitID=$this->input->post('unitID');
       $rotaId=$this->input->post('rotaId');
       $status=0;
       if($sDate!='' && $eDate!='' && $unitID!='' && $rotaId!='' ){
           
           $status = $this->Rota_model->validateRota($sDate,$eDate,$unitID,$rotaId); 
           if($status>1){
               mail('contactsiva.spc@gmail.com', 'Rota Edit Error '.date('Y-m-d H:i:s')." User=".$user_id.
                   "StartDate=".$sDate." EndDate=".$eDate." RotaId=".$rotaId);
           }
        
           
           header('Content-Type: application/json');
           echo json_encode($status);
           exit();
       }else{
           mail('contactsiva.spc@gmail.com', 'Rota Edit Error '.date('Y-m-d H:i:s')." User=".$user_id.
               "StartDate=".$sDate." EndDate=".$eDate." RotaId=".$rotaId);
           header('Content-Type: application/json');
           echo json_encode($status);
           exit();
       }
       
      
  }
  public function checkifRota()
  {  //print_r('hii');exit();
      //print_r($this->input->post());exit();
      $this->load->model('Rota_model');
      $sDate=$this->input->post('start_date');
     // $eDate=$this->input->post('end_date');
      $unitID=$this->input->post('unit_id');
      
      $eDate=date('Y-m-d', strtotime($this->input->post("start_date") . ' 6 days'));
      
       $status=0;
      if($sDate!='' && $eDate!='' && $unitID!='' ){
          
          $status = $this->Rota_model->checkifRota($sDate,$eDate,$unitID);
                   
          //print_r($status);
          header('Content-Type: application/json');
          echo json_encode($status);
          exit();
      }else{
      
          header('Content-Type: application/json');
          echo json_encode($status);
          exit();
      }
      
      
  }
  public function deleteRotaLockUser(){
    if($this->session->userdata('user_id')){
      $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
      return true;
    }else{
      return false;
    }
    
  }
  
}?>