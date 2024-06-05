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
        $this->load->model('User_model');
        $this->load->model('Leave_model');
        $this->load->model('Unit_model');
        $this->load->model('Reports_model');
        $this->load->model('Activity_model'); 
        $this->load->helper('form');
        $this->load->helper('user');
    }


  public function holiday_applied(){

    $result=$this->Leave_model->findholiday_db();  
    foreach ($result as $value) {
        $leaveyear = $this->checkLeaveyear($value['from_date']);//print_r($value['from_date']); print_r('<br>'); print_r($leaveyear);
        $day=number_format($value['days'], 2);
        $abc= str_replace('.',':',$day); 
        $dataform=array(
            'user_id'=>$value['user_id'],
            'year'=>$leaveyear,
            'holiday_id'=>$value['id'],
            'hours'=>$abc,
            'calculated_hours'=>0,
            'creation_date'=>$value['creation_date']);
        //print "<pre>"; 
        //print_r($dataform);
        $result=$this->Leave_model->InsertLeaveYear($dataform);
    } 
}


  public function index()
  {
    $this->auth->restrict('Admin.Annual Leave.View');
    $result = array(); 
    $this->load->helper('user'); 
    $this->load->view('includes/home_header'); 
    $data   =array();
    $params =array();
    $id = '';
    $jobe_roles=array();
    $user_id=$this->session->userdata('user_id');     
    $sub=$this->User_model->CheckuserUnit($user_id);
    $unit=$this->User_model->findunitofuser($user_id); 
    $parent = 0;
    if(count($unit) > 0){
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']);  
    }
    

    // changed by swaraj on nov 17 2021

     $params['start_date'] = date('Y').'-'.date("m").'-'.'01';
     $params['end_date'] =date('Y-m-d'); //print_r($params); exit();


     $data['start_date'] =  '01'.'/'.date("m").'/'.date("Y");
     $data['end_date'] =date('d/m/Y'); //print_r($params); exit();

     

    //if($this->session->userdata('user_type')==1) //all super admin can access
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
    {  
        $data['user']=$this->Leave_model->findholidaydetails('',$id,'',$params);
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
            $data['user']=$this->Leave_model->findholidaydetails($sub,$user_id,'',$params);
            $data['unit'] = $this->User_model->fetchSubTree($sub); 
        }
        else
        {   
            $userUnits['units'] = $this->Unit_model->findunitidofuser($user_id);  //=print_r($userUnits['units']);exit(); 
            if(empty($userUnits['units']))
            { 
              $data['user']=array();
            }
            else
            { 

              foreach ($userUnits['units'] as $value) 
              { 
              $data['user']=$this->Leave_model->findallholidaydetails($value['unit_id'],$user_id,$params);  //print_r($data['user']);exit();
                  
              }  
            }  
            $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
        }
    }
    else
    { 
           $userUnits['units'] = $this->Unit_model->findunitidofuser($user_id);  //print_r($userUnits['units']);exit(); 
            if(empty($userUnits['units']))
            { 
              $data['user']=array();
            }
            else
            { 

              foreach ($userUnits['units'] as $value) 
              { 
              $data['user']=$this->Leave_model->findallholidaydetails($value['unit_id'],$user_id,$params);  //print_r($data['user']);exit();
                  
              }  
            } 
            $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
    }
//print_r($data['user']);exit();

    $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
    $data['job_roles']=json_encode($jobe_roles);
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->load->model('Rota_model');
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/   
    $this->load->view("admin/user/holiday",$data);
    $result['js_to_load'] = array('holiday/holiday.js');
    $this->load->view('includes/home_footer',$result);
  }
 
   public function addLeave()
   {
    //$this->auth->restrict('Admin.Annual Leave.Add');
    $result = array(); 
    $this->load->helper('user'); 
    $this->load->view('includes/home_header'); 

    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
    //if($this->session->userdata('user_type')==1) //all super admin can access
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
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
                  



    $data['error']='';
    $selected_year=date('Y', strtotime('+1 years'));
    $data['selected_date']=$selected_year.'/'.'08'.'/'.'31';
    $this->load->view("admin/user/addleave",$data);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
    {
        $result['js_to_load'] = array('holiday/manager_holiday.js');
    }
    else
    {
        $result['js_to_load'] = array('holiday/addleave.js');
    }
    
    $this->load->view('includes/home_footer',$result);

   }

    public function finduserdata()
    {
    $this->load->model('Reports_model');
    $unit_id=$this->input->post('unit_id'); 
    $status=$this->input->post('status');
    $unit_data =$this->Reports_model->finduserdata($unit_id,$status);   //print_r($unit_data);exit();
    // foreach ($unit_data as $value) {
    //    $data['name']=$value['fname'].' '.$value['lname'];
    //    $data['id']=$value['user_id'];
    // }   
    echo json_encode($unit_data);
    }

    public function findallowance()
    {
        //$this->load->model('Reports_model');
        $user_id=$this->input->post('user_id');  //print_r($user_id);exit();
        $remaining_holiday_allowance=$this->Leave_model->findleaveremainingbyuser($user_id); //print_r($remaining_holiday_allowance);
        $actual_holiday_allowance=$this->Leave_model->findAnnualHollidayAllowance($user_id); //print_r($actual_holiday_allowance);exit(); 
        $leave_remaining=$remaining_holiday_allowance[0]['remaining_holiday_allowance'];
        $actual_allowance=$actual_holiday_allowance[0]['annual_holliday_allowance']; 
        $data=array('leave_remaining'=>$leave_remaining,'actual_allowance'=>$actual_allowance);      
        echo json_encode($data);
    }

   /*  function checkLeaveyear($date){  ?? old version in manager section
        print_r($date);
    $time=strtotime($date); 
    $month=date("m",$time);  print_r($month); 
    $year=date("Y",$time);   print_r($year); 
    if ($month > 3 && $year>=date('Y')) {
    $year = date('Y')."-".(date('Y') +1) ;
    }
    else {
    $year = (date('Y')-1)."-".date('Y');
    }
    //print_r($year);exit();
    return $year; 
    } */
    function checkLeaveyear($date){  
    $time=strtotime($date); 
    $month=date("m",$time);//print_r("month".' '.$month); print "<br>";
    $year=date("Y",$time);  //print_r("year".' '.$year); print "<br>";

    //print_r(date('Y'));print "<br>";

    if ($month > 8 && $year>=date('Y')) 
    { //print_r('hiii');exit();
        if($year>date('Y'))
        {
            $year = (date('Y') +1)."-".(date('Y') +2) ;
        }
        else
        {
            $year = date('Y')."-".(date('Y') +1) ;
        }
    }
    else 
    {//print_r('hello');exit();
         if($year>date('Y'))
         {
        $year = ($year-1)."-".$year;
         }
         else
         {
        $year = (date('Y')-1)."-".date('Y');
        }
    } 
    return $year; 
    }

    public function applyLeave()
    { 
        $data = array(); 
        $user_ids = array();
        $status = 0;
        $result['error'] = $status;
        $id=$this->input->post('user'); //print_r($id);exit();\
        $user_type=$this->Leave_model->checkusertype($id);
        $unit_id=$this->input->post('unitdata'); //print_r($unit_id);exit();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $leaveDataResult = $this->Leave_model->losOfPayChecking($id); //print_r($leaveDataResult);exit();
        $holidyTypeDetails = array();
        $holidyTypeDetails['values']=$this->Leave_model->getHolidayTypes();  
        $holidyTypeDetails['lop'] = $leaveDataResult['status'];
        $holidyTypeDetails['total_leave_applied'] = $leaveDataResult['total_leave_applied'];
        $holidyTypeDetails['user']=$this->User_model->findusers($id); 

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id);
        if(count($unit) > 0){
            $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        }else{
            $parent=0;
        }
        

        
        $this->form_validation->set_rules('start_date', 'start date', 'required');
        $this->form_validation->set_rules('end_date', 'end date', 'required');
        $this->form_validation->set_rules('total_days', 'total days', 'required');
        $this->form_validation->set_rules('message', 'message', 'required');
        $this->load->view('includes/home_header',$result);
        if ($this->form_validation->run() == FALSE)
        {
 
                            // if($this->session->userdata('user_id')==1)
                            // { 
                            //     $data['unit'] = $this->User_model->fetchCategoryTree('');  
                            // }
                            // else
                            // {
                            //     $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); 
                            // }

                            //if($this->session->userdata('user_type')==1) //all super admin can access
                            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
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
                            $data['user']=$this->Reports_model->finduserdata($unit_id,$status);
                            $data['error']=$leaveEXistCheck;
                            $selected_year=date('Y', strtotime('+1 years'));
                            $data['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                            $data['annual_holliday_allowance']=$this->input->post('annual_holliday_allowance');
                            $designation_id=$this->Unit_model->SelectDesignationCode($id);  //print_r($designation_id);exit();
                            $designation=$designation_id[0]['designation_id'];
                            $annual_allowance_type=$designation_id[0]['annual_allowance_type']; 
                            $array_without_offday_holiday=$this->input->post('without_offday_holiday');
                            $data['array_without_offday_holiday']=$array_without_offday_holiday;
                            $this->load->view("admin/user/addleave",$data);
                            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
                            {
                                $result['js_to_load'] = array('holiday/manager_holiday.js');
                            }
                            else
                            {
                                $result['js_to_load'] = array('holiday/addleave.js');
                            }
                            $this->load->view('includes/home_footer',$result);
        }
        else 
        { 
        $selected_units = [$this->input->post('unitdata')];
        $from_date     = $this->formatDate($this->input->post('start_date'));    
        $to_date       = $this->formatDate($this->input->post('end_date')); 
        // array_push($selected_units,0);
        $this->load->model('Reports_model');
        $search_data = array(
           'unit_id' => $selected_units,
           'from_date' => $from_date,
           'to_date' => $to_date,
        );
        $result = $this->Reports_model->checkExistingLockedTimesheet($search_data); 
        if($result['status'] == false){
            $this->session->set_flashdata('error', $result['message']); 
            redirect('admin/Holiday/addLeave');
        }
                    $designation_id=$this->Unit_model->SelectDesignationCode($id); 
                    $designation=$designation_id[0]['designation_id'];
                    $annual_allowance_type=$designation_id[0]['annual_allowance_type']; 
                    $array_without_offday_holiday=$this->input->post('without_offday_holiday');
                    $new_array = [];
                    $without_offday_holiday = [];
                    if(count($array_without_offday_holiday) > 0){
                        $without_offday_holiday = explode(",", $array_without_offday_holiday[0]);
                    }
                     //print_r($without_offday_holiday);exit();
                    //$max_leave=$this->Unit_model->findMaxleaveByDesignation($user_type,$designation,$annual_allowance_type); 
                    $day = $this->input->post('total_days');
                    $from_date     = $this->formatDate($this->input->post('start_date'));    
                    $to_date       = $this->formatDate($this->input->post('end_date')); 
                    $start_time    = $this->input->post('start_time').':'.$this->input->post('start_time1');
                    $end_time      = $this->input->post('end_time').':'.$this->input->post('end_time1');  
                    $comment       = $this->input->post('message');
                    $user_id           = $id;
                    $day = $this->input->post('total_days'); 
                    //$new_time=explode(":",$day);
                    //$days=number_format($this->convert($new_time[0],$new_time[1]),2); 
                    $start=$this->input->post('start_date');
                   //$start_date=date('Y-m-d',strtotime($start));
                    //print_r(strtotime($from_date)); 
                    // print_r(strtotime($from_date));
                    // print_r('<br>');
                    // print_r(strtotime(date('Y/m/d'))); 
                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
                {  
                        $leave_remaining=$this->Leave_model->checkLeaveremaining($id); //print_r($leave_remaining);exit();
                        $annual_holliday_allowance=$this->Leave_model->getActualholidayallowance($id); //print_r($actual_holiday_allowance);exit(); 
                        $allowance=$annual_holliday_allowance[0]['annual_holliday_allowance'];//print_r($allowance);exit();
                        $remaining=$this->Leave_model->CheckRemainingHolliday($id); //print_r(count($remaining));exit();
                        $applied= str_replace(':','.',$days); //print_r($applied);exit();
                        //ret4rn the value itself. Not array. so count checking will cause error 
                        // if(count($remaining)==0)
                        //Edited by chinchu
                    
                            $leaveDetails = array(
                            'user_id'=>$user_id,
                            'from_date'=>$from_date,
                            'to_date'=>$to_date,
                            'start_time'=>$start_time,
                            'end_time'=>$end_time,
                            'comment'=>$comment,
                            'unit_id'=>$unit_id,
                            'status'=>1,
                            'creation_date'=>date('Y-m-d H:i:s'),
                            'days'=>$day,
                            'approved_date'=>date('Y-m-d H:i:s'),
                            'approved_by'=>$this->session->userdata('user_id'),
                            'updation_date'=>date('Y-m-d H:i:s'),
                            'updated_userid'=>$this->session->userdata('user_id')
                            );
                        $leaveEXistCheck = $this->Leave_model->losOfPayforadmins($from_date,$to_date,$start_time,$end_time,$user_id); //print_r($leaveEXistCheck);exit();
                        if($leaveEXistCheck == 1)
                        {   
                          
                            $save_details = $this->Leave_model->insertLeaveDetails($leaveDetails); //print_r($save_details);exit();
                            if($save_details!='0')
                            {
                               $title='Add Leave - admin side';
                               $abc=Array ( 
                                        'unitdata' => $this->input->post('unitdata'), 
                                        'user' => $this->input->post('user'),
                                        'start_date' => $this->input->post('start_date'),
                                        'end_date' => $this->input->post('end_date'),
                                        'total_days' => $this->input->post('total_days'),
                                        'calc_hours' => $this->input->post('calc_hours'),
                                        'message' => $this->input->post('message'),
                                        'annual_holliday_allowance' => $this->input->post('annual_holliday_allowance'),
                                        'total_leave_applied' => $this->input->post('total_leave_applied'),  
                                        'without_offday_holiday' => 'without_offday_holiday : '. implode(',',$this->input->post('without_offday_holiday')) );
                               //print_r($abc);exit();
                               InsertEditedData($abc,$title);
                            }
                            if($save_details)
                            {
                                $comments_details = array(
                                    'manager_id'=>$this->session->userdata('user_id'),
                                    'holiday_id'=>$save_details,
                                    'comment'=>"Approved",
                                    'status'=>1,
                                    'date'=>date('Y-m-d H:i:s'),
                                );
                                $save_comment_details = $this->Leave_model->insertCommentDetails($comments_details);

                                $user_leave_deatils = $this->Leave_model->getUserHolidayDetails($save_details,$user_id);  
                                $leaves_by_user=$this->Leave_model->findAnnualHollidayAllowance($user_id);
                                if(date("m") > 8)
                                {
                                                  $currentyear = date("Y");
                                                  $nxtyear = date("Y")+1;
                                }
                                else
                                {
                                                  $currentyear = date("Y")-1;
                                                  $nxtyear = date("Y");

                                }

                                // $currentyear=date('Y');
                                // $nxtyear = date("Y")+1;
                                $CYear = $currentyear."-".$nxtyear;
                                $total=$this->Leave_model->finddaysbyuser($save_details);
                                $total_by_user=str_replace(':', '.', $total);
                                $leave_year = $this->checkLeaveyear($from_date); //print_r($leave_year);exit();
                                //$new_leave_year=explode("-",$leave_year); 
                                $result=$this->Leave_model->findDays($user_id,$leave_year); //approved holiday,day and remaining leave 
                                $sum_days='00.00';
                                $remaining_leave='00.00'; 
                                if($result=='' || empty($result))
                                { 
                                    if($CYear==$leave_year)
                                    {

                                        $remaining=$leaves_by_user[0]['annual_holliday_allowance'];
                                    }
                                    else
                                    {
                                            //find actual leave
                                        $remaining=$leaves_by_user[0]['actual_holiday_allowance'];
                                    }

                                    $actual_leave_remaining=$remaining;  
                                    $actual_total=number_format((float)$actual_leave_remaining, 2, '.', '');

                                }
                                else
                                {  
                                        foreach ($result as $value) {
                                            $applieddays=str_replace(':', '.', $value['days']); 
                                            $applieddays1=number_format(getPayrollformat(number_format($applieddays,2),2),2); 
                                            $sum_days=$sum_days+$applieddays1;
                                            if($value['remaining_leave']=='')
                                            {
                                                $leave='0.0';
                                            }
                                            else
                                            {
                                                $leave=$value['remaining_leave'];
                                            }
                                            $remaining_leave=$remaining_leave+$leave;
                                        }  
                                        //$total_days=$sum_days+$total_by_user;
                                        $total_days=$sum_days;   
                                        $sum=number_format((float)$total_days, 2, '.', ''); 
                                        if($CYear==$leave_year)
                                        {
                                            //find annual leave
                                            $remaining=$leaves_by_user[0]['annual_holliday_allowance'];
                                        }
                                        else
                                        {
                                            //find actual leave
                                            $remaining=$leaves_by_user[0]['actual_holiday_allowance'];
                                        } 
                                        $actual_leave_remaining=$remaining-$sum;  
                                        $actual_total=number_format((float)$actual_leave_remaining, 2, '.', ''); 
                                }
                                
                                $this->Leave_model->insertremaining($actual_total,$save_details);
         

                                        $begin     = $this->formatDate($this->input->post('start_date'));  //start date 
                                        $end       = $this->formatDate($this->input->post('end_date')); //end date
                                        // $date = new DateTime($to_date); 
                                        // $date->modify('+1 day');
                                        // $end=$date->format('Y-m-d');     
                                        $Period = getDatesFromRange($begin,$end); //print_r($Period);exit(); //date array
                                        $leaveArray = array();
                                        $calculated_hours=0;
                                        $leave_year=0;
                                        $sum=0;$minutes = 0;$hour=0;$minute=0;
                                                                  //   print "<pre>";print_r($without_offday_holiday); //checking shift hours in rota schedule

                                        foreach($Period as $dt) // date array for loop
                                        { //print_r('hello');
                                        // print "<pre>";
                                        // print_r($dt); 
                                        /*if($dt<=$to_date)
                                        {*/ 
                                            $leaveyear = $this->checkLeaveyear($dt); 
                                        //    print_r($leaveyear); //exit(); //leave year 
                                            $shift=$this->Leave_model->findshifthoursbyid($user_id,$dt); //print_r($shift);exit(); 
                                            if(count($shift)==0) 
                                             { //if there is no rota find default shift hours
                                               // print "default";
                                                   if (in_array($dt, $without_offday_holiday)) { 
                                                    $calculated_hours=0;
                                                        $targeted_hours=$this->Leave_model->findtargetedhoursbyuser($user_id); 
                                                       // print_r($targeted_hours[0]['targeted_hours']);print_r("targeted-");
                                                        $abc= str_replace(':','.',$targeted_hours[0]['targeted_hours']); 
                                                        $calculated_hours=$abc;
                                                        if($targeted_hours[0]['id'])
                                                        {
                                                            $shift_id=$targeted_hours[0]['id'];
                                                        }
                                                        else
                                                        {
                                                            $shift_id=0;
                                                        }
                                                        $datahome=array(
                                                            'holiday_id' =>$save_details,
                                                            'shift_id'=>$shift_id,
                                                            'date'=>$dt,
                                                            'hour'=>$calculated_hours);
                                                        $result=$this->Leave_model->insertholidaydetailsbydate($datahome);
                /*                                         print($dt.$abc.' def <br>');
                */                                       $sum=$sum+$calculated_hours;
                                                    }
                                                     
                                                    
                                             }
                                             else
                                             { 
                                                if (in_array($dt, $without_offday_holiday)) {  //print_r('hii');
                                                $calculated_hours=0;
                                                $sh=str_replace(':','.',$shift[0]['shift_hours']); 
                                                if($sh>0)
                                                $calculated_hours=$sh; 
                                                $datahome=array(
                                                            'holiday_id' =>$save_details,
                                                            'shift_id'=>$shift[0]['shift_id'],
                                                            'date'=>$dt,
                                                            'hour'=>$calculated_hours);
                                                        $result=$this->Leave_model->insertholidaydetailsbydate($datahome);
                /*                                  print_r($dt.$sh.' sh<br>');//exit();
                */                                   $sum=$sum+$calculated_hours;
                                                 }
                                                
                                             }
                                        //}
                                        // print_r($calculated_hours);

                                            
                                        }
                                      
                                        $data=array(
                                                                'user_id'=>$user_id,
                                                                'year'=>$leaveyear,
                                                                'holiday_id'=>$save_details,
                                                                'hours'=>$day,
                                                                'calculated_hours'=>$sum,
                                                                'creation_date'=>date('Y-m-d H:i:s'));
                                        // print_r($data);exit();
                                        $status=$this->Leave_model->InsertLeaveYear($data); 
                     
                                    if($unit_id)
                                    {
                                        $user_unit_id = $unit_id;
                                        $userUnitDetails['user_values'] = $this->Leave_model->getUsersFromUnit($id); //print_r($userUnitDetails['user_values']);exit();
                                        
                                        if($userUnitDetails['user_values'])
                                        {
                                            $to_email = $userUnitDetails['user_values'][0]['email']; //
                                            // $to_email = "chinchugopi89@gmail.com";
                                            //$supervisor_id = $userUnitDetails['user_values'][0]['id'];       
                                            $staff_name = $this->session->userdata('full_name');
                                            $name = $userUnitDetails['user_values'][0]['fname']." ".$userUnitDetails['user_values'][0]['lname'];
                                            //print_r($name);exit();

                                            $fromdate = $from_date;
                                            $todate = $to_date;
                                                $days = $days;
                                                $supervisor_name = $this->session->userdata('full_name');
                                                $site_title = 'St Matthews Healthcare - SMH Rota';
                                                $admin_email=getCompanydetails('from_email');
                                                $emailSettings = array(
                                                    'from' => $admin_email,
                                                    'site_title' => $site_title,
                                                    'site_url' => $this->config->item('base_url'),
                                                    'to' => $to_email,
                                                    'type' => 'Admin-approve holiday',
                                                    'user_name'=> $name,
                                                    'fromdate'=> $fromdate,
                                                    'todate'=>$todate,
                                                    'days'=>$day,
                                                    'comments'=>$comment,
                                                    'supervisor_name'=>$supervisor_name,
                                                    'subject' => 'Your Holiday request has been approved!',
                                                     );
                                                 //print_r($emailSettings);exit();
                                                $this->load->library('parser');
                                                $htmlMessage = $this->parser->parse('emails/approve', $emailSettings, true);
                                                //die($htmlMessage);exit();
                                                $this->load->helper('mail'); 
                                                sendMail($emailSettings, $htmlMessage); 
                                                $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                                                redirect('admin/Holiday');
                                        } 
                                    }
                                    else
                                    {
                                        $this->session->set_flashdata('message', ' '); 
                                        redirect('admin/Holiday');
                                    }                    
                            }
                            else
                            {
                                $this->session->set_flashdata('message', 'Leave cannot be applied due to db error.'); 
                                redirect('admin/Holiday');
                            }
                        }
                        else
                        {

                            //if($this->session->userdata('user_type')==1) //all super admin can access
                            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
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
                            $data['user']=$this->Reports_model->finduserdata($unit_id,$status);
                            $data['error']=$leaveEXistCheck;
                            $selected_year=date('Y', strtotime('+1 years'));
                            $data['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                            $data['annual_holliday_allowance']=$this->input->post('annual_holliday_allowance');
                            $designation_id=$this->Unit_model->SelectDesignationCode($id);  //print_r($designation_id);exit();
                            $designation=$designation_id[0]['designation_id'];
                            $annual_allowance_type=$designation_id[0]['annual_allowance_type']; 
                            $array_without_offday_holiday=$this->input->post('without_offday_holiday');
                            $data['array_without_offday_holiday']=$array_without_offday_holiday;
                            $this->load->view("admin/user/addleave",$data);
                            if( in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
                            {
                                $result['js_to_load'] = array('holiday/manager_holiday.js');
                            }
                            else
                            {
                                $result['js_to_load'] = array('holiday/addleave.js');
                            }
                            $this->load->view('includes/home_footer',$result);


                        }
                         

                }
                else
                { 
                    if (strtotime($from_date)>strtotime(date('Y/m/d'))) 
                    {  //print_r('hii');exit();
                        $leave_remaining=$this->Leave_model->checkLeaveremaining($id); //print_r($leave_remaining);exit();
                        $annual_holliday_allowance=$this->Leave_model->getActualholidayallowance($id); //print_r($actual_holiday_allowance);exit(); 
                        $allowance=$annual_holliday_allowance[0]['annual_holliday_allowance'];//print_r($allowance);exit();
                        $remaining=$this->Leave_model->CheckRemainingHolliday($id); //print_r(count($remaining));exit();
                        $applied= str_replace(':','.',$days); //print_r($applied);exit();
                        //ret4rn the value itself. Not array. so count checking will cause error 
                        // if(count($remaining)==0)
                        //Edited by chinchu
                    
                            $leaveDetails = array(
                            'user_id'=>$user_id,
                            'from_date'=>$from_date,
                            'to_date'=>$to_date,
                            'start_time'=>$start_time,
                            'end_time'=>$end_time,
                            'comment'=>$comment,
                            'unit_id'=>$unit_id,
                            'status'=>1,
                            'creation_date'=>date('Y-m-d H:i:s'),
                            'days'=>$day,
                            'approved_date'=>date('Y-m-d H:i:s'),
                            'approved_by'=>$this->session->userdata('user_id'),
                            'updation_date'=>date('Y-m-d H:i:s'),
                            'updated_userid'=>$this->session->userdata('user_id')
                            );
                        $leaveEXistCheck = $this->Leave_model->losOfPay($from_date,$to_date,$start_time,$end_time,$user_id); 
                        if($leaveEXistCheck == 1)
                        {   
                          
                            $save_details = $this->Leave_model->insertLeaveDetails($leaveDetails); //print_r($save_details);exit();
                            if($save_details)
                            {


                               $title='Add Leave - admin side';
                               $abc=Array ( 
                                        'unitdata' => $this->input->post('unitdata'), 
                                        'user' => $this->input->post('user'),
                                        'start_date' => $this->input->post('start_date'),
                                        'end_date' => $this->input->post('end_date'),
                                        'total_days' => $this->input->post('total_days'),
                                        'calc_hours' => $this->input->post('calc_hours'),
                                        'message' => $this->input->post('message'),
                                        'annual_holliday_allowance' => $this->input->post('annual_holliday_allowance'),
                                        'total_leave_applied' => $this->input->post('total_leave_applied'),  
                                        'without_offday_holiday' => 'without_offday_holiday : '. implode(',',$this->input->post('without_offday_holiday')) );
                               //print_r($abc);exit();
                               InsertEditedData($abc,$title);


                                $comments_details = array(
                                    'manager_id'=>$this->session->userdata('user_id'),
                                    'holiday_id'=>$save_details,
                                    'comment'=>"Approved",
                                    'status'=>1,
                                    'date'=>date('Y-m-d H:i:s'),
                                );
                                $save_comment_details = $this->Leave_model->insertCommentDetails($comments_details);

                                $user_leave_deatils = $this->Leave_model->getUserHolidayDetails($save_details,$user_id); 
                                $leaves_by_user=$this->Leave_model->findAnnualHollidayAllowance($user_id);
                                if(date("m") > 8)
                                {
                                                  $currentyear = date("Y");
                                                  $nxtyear = date("Y")+1;
                                }
                                else
                                {
                                                  $currentyear = date("Y")-1;
                                                  $nxtyear = date("Y");

                                }
                                // $currentyear=date('Y');
                                // $nxtyear = date("Y")+1;
                                $CYear = $currentyear."-".$nxtyear;
                                $total=$this->Leave_model->finddaysbyuser($save_details);
                                $total_by_user=str_replace(':', '.', $total);
                                $leave_year = $this->checkLeaveyear($from_date); //print_r($leave_year);exit();
                                //$new_leave_year=explode("-",$leave_year); 
                                $result=$this->Leave_model->findDays($user_id,$leave_year); //approved holiday,day and remaining leave 
                                $sum_days='00.00';
                                $remaining_leave='00.00'; 
                                if($result=='' || empty($result))
                                { 
                                    if($CYear==$leave_year)
                                    {

                                        $remaining=$leaves_by_user[0]['annual_holliday_allowance'];
                                    }
                                    else
                                    {
                                            //find actual leave
                                        $remaining=$leaves_by_user[0]['actual_holiday_allowance'];
                                    }

                                    $actual_leave_remaining=$remaining;  
                                    $actual_total=number_format((float)$actual_leave_remaining, 2, '.', '');

                                }
                                else
                                {  
                                        foreach ($result as $value) {
                                            $applieddays=str_replace(':', '.', $value['days']); 
                                            $applieddays1=number_format(getPayrollformat(number_format($applieddays,2),2),2); 
                                            $sum_days=$sum_days+$applieddays1;  
                                            if($value['remaining_leave']=='')
                                            {
                                                $leave='0.0';
                                            }
                                            else
                                            {
                                                $leave=$value['remaining_leave'];
                                            }
                                            $remaining_leave=$remaining_leave+$leave;
                                        }  
                                        //$total_days=$sum_days+$total_by_user;
                                        $total_days=$sum_days;   
                                        $sum=number_format((float)$total_days, 2, '.', ''); 
                                        if($CYear==$leave_year)
                                        {
                                            //find annual leave
                                            $remaining=$leaves_by_user[0]['annual_holliday_allowance'];
                                        }
                                        else
                                        {
                                            //find actual leave
                                            $remaining=$leaves_by_user[0]['actual_holiday_allowance'];
                                        } 
                                        $actual_leave_remaining=$remaining-$sum;  
                                        $actual_total=number_format((float)$actual_leave_remaining, 2, '.', ''); 
                                }
                                $this->Leave_model->insertremaining($actual_total,$save_details);
         

                                        $begin     = $this->formatDate($this->input->post('start_date'));  //start date 
                                        $end       = $this->formatDate($this->input->post('end_date')); //end date
                                        // $date = new DateTime($to_date); 
                                        // $date->modify('+1 day');
                                        // $end=$date->format('Y-m-d');     
                                        $Period = getDatesFromRange($begin,$end); //print_r($Period);exit(); //date array
                                        $leaveArray = array();
                                        $calculated_hours=0;
                                        $leave_year=0;
                                        $sum=0;$minutes = 0;$hour=0;$minute=0;
                                                                  //   print "<pre>";print_r($without_offday_holiday); //checking shift hours in rota schedule

                                        foreach($Period as $dt) // date array for loop
                                        { //print_r('hello');
                                        // print "<pre>";
                                        // print_r($dt); 
                                        /*if($dt<=$to_date)
                                        {*/ 
                                            $leaveyear = $this->checkLeaveyear($dt); 
                                        //    print_r($leaveyear); //exit(); //leave year 
                                            $shift=$this->Leave_model->findshifthoursbyid($user_id,$dt); //print_r($shift);exit(); 
                                            if(count($shift)==0) 
                                             { //if there is no rota find default shift hours
                                               // print "default";
                                                   if (in_array($dt, $without_offday_holiday)) { 
                                                    $calculated_hours=0;
                                                        $targeted_hours=$this->Leave_model->findtargetedhoursbyuser($user_id); 
                                                       // print_r($targeted_hours[0]['targeted_hours']);print_r("targeted-");
                                                        $abc= str_replace(':','.',$targeted_hours[0]['targeted_hours']); 
                                                        $calculated_hours=$abc;
                                                        if($targeted_hours[0]['id'])
                                                        {
                                                            $shift_id=$targeted_hours[0]['id'];
                                                        }
                                                        else
                                                        {
                                                            $shift_id=0;
                                                        }
                                                        $datahome=array(
                                                            'holiday_id' =>$save_details,
                                                            'shift_id'=>$shift_id,
                                                            'date'=>$dt,
                                                            'hour'=>$calculated_hours);
                                                        $result=$this->Leave_model->insertholidaydetailsbydate($datahome);
                /*                                         print($dt.$abc.' def <br>');
                */                                       $sum=$sum+$calculated_hours;
                                                    }
                                                     
                                                    
                                             }
                                             else
                                             { 
                                                if (in_array($dt, $without_offday_holiday)) {  //print_r('hii');
                                                $calculated_hours=0;
                                                $sh=str_replace(':','.',$shift[0]['shift_hours']); 
                                                if($sh>0)
                                                $calculated_hours=$sh; 
                                                $datahome=array(
                                                            'holiday_id' =>$save_details,
                                                            'shift_id'=>$shift[0]['shift_id'],
                                                            'date'=>$dt,
                                                            'hour'=>$calculated_hours);
                                                        $result=$this->Leave_model->insertholidaydetailsbydate($datahome);
                /*                                  print_r($dt.$sh.' sh<br>');//exit();
                */                                   $sum=$sum+$calculated_hours;
                                                 }
                                                
                                             }
                                        //}
                                        // print_r($calculated_hours);

                                            
                                        }
                                      
                                        $data=array(
                                                                'user_id'=>$user_id,
                                                                'year'=>$leaveyear,
                                                                'holiday_id'=>$save_details,
                                                                'hours'=>$day,
                                                                'calculated_hours'=>$sum,
                                                                'creation_date'=>date('Y-m-d H:i:s'));
                                        // print_r($data);exit();
                                        $status=$this->Leave_model->InsertLeaveYear($data); 
                     
                                    if($unit_id)
                                    {
                                        $user_unit_id = $unit_id;
                                        $userUnitDetails['user_values'] = $this->Leave_model->getUsersFromUnit($id); //print_r($userUnitDetails['user_values']);exit();
                                        
                                        if($userUnitDetails['user_values'])
                                        {
                                            $to_email = $userUnitDetails['user_values'][0]['email']; //
                                            // $to_email = "chinchugopi89@gmail.com";
                                            //$supervisor_id = $userUnitDetails['user_values'][0]['id'];       
                                            $staff_name = $this->session->userdata('full_name');
                                            $name = $userUnitDetails['user_values'][0]['fname']." ".$userUnitDetails['user_values'][0]['lname'];
                                            //print_r($name);exit();

                                            $fromdate = $from_date;
                                            $todate = $to_date;
                                                //$days = $days;
                                                $supervisor_name = $this->session->userdata('full_name');
                                                $site_title = 'St Matthews Healthcare - SMH Rota';
                                                $admin_email=getCompanydetails('from_email');
                                                $emailSettings = array(
                                                    'from' => $admin_email,
                                                    'site_title' => $site_title,
                                                    'site_url' => $this->config->item('base_url'),
                                                    'to' => $to_email,
                                                    'type' => 'Admin-approve holiday',
                                                    'user_name'=> $name,
                                                    'fromdate'=> $fromdate,
                                                    'todate'=>$todate,
                                                    'days'=>$day,
                                                    'comments'=>$comment,
                                                    'supervisor_name'=>$supervisor_name,
                                                    'subject' => 'Your Holiday request has been approved!',
                                                     );
                                                 //print_r($emailSettings);exit();
                                                $this->load->library('parser');
                                                $htmlMessage = $this->parser->parse('emails/approve', $emailSettings, true);
                                                //die($htmlMessage);exit();
                                                $this->load->helper('mail'); 
                                                sendMail($emailSettings, $htmlMessage); 
                                                $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                                                redirect('admin/Holiday');
                                        } 
                                    }
                                    else
                                    {
                                        $this->session->set_flashdata('message', ' '); 
                                        redirect('admin/Holiday');
                                    }                    
                            }
                            else
                            {
                                $this->session->set_flashdata('message', 'Leave cannot be applied due to db error.'); 
                                redirect('admin/Holiday');
                            }
                        }
                        else
                        {   
                            $this->load->view('includes/home_header');
                            //if($this->session->userdata('user_type')==1) //all super admin can access
                            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
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
                            $data['user']=$this->Reports_model->finduserdata($unit_id,$status);
                            $data['error']=$leaveEXistCheck;
                            $selected_year=date('Y', strtotime('+1 years'));
                            $data['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                            $data['array_without_offday_holiday']=$array_without_offday_holiday[0];
                            $this->load->view("admin/user/addleave",$data);
                            $result['js_to_load'] = array('holiday/addleave.js');
                            $this->load->view('includes/home_footer',$result);

                        } 
                    }
                    else
                    {  //print_r('helloo');exit();
                        $this->load->view('includes/home_header');
                            //if($this->session->userdata('user_type')==1) //all super admin can access
                            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
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
                        $data['user']=$this->Reports_model->finduserdata($unit_id,$status);
                        //print_r($data['user']);exit();
                       // $data['$user_post']=$this->input->post('user');
                        //print_r($data['$user_post']);exit();
                        $selected_year=date('Y', strtotime('+1 years'));
                        $data['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                        $data['error']= 'Please select future dates';
                        $this->load->view("admin/user/addleave",$data);
                        $result['js_to_load'] = array('holiday/addleave.js');
                        $this->load->view('includes/home_footer',$result);

                    }


                }
        }


        
    }

   public function getLeave()
   {
    
    $user_id=$this->input->post('user_id');
    $year=$this->input->post('year');
    $leaves=getLeaves($user_id,$year); //print_r($leaves);exit();
    echo json_encode($leaves);
   }

    public function applyLeave_old()
    {

        //print_r($this->input->post());exit();
        $data = array(); 
        $user_ids = array();
        $status = 0;
        $result['error'] = $status;
        $id=$this->input->post('user'); //print_r($id);exit();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $leaveDataResult = $this->Leave_model->losOfPayChecking($id); //print_r($leaveDataResult);exit();
        $holidyTypeDetails = array();
        $holidyTypeDetails['values']=$this->Leave_model->getHolidayTypes();  
        $holidyTypeDetails['lop'] = $leaveDataResult['status'];
        $holidyTypeDetails['total_leave_applied'] = $leaveDataResult['total_leave_applied'];
        $userUnitDetails['values'] = $this->Leave_model->getUnitIdOfUser($id);
        $holidyTypeDetails['user']=$this->User_model->findusers($id);  
        $designation_id=$this->Unit_model->SelectDesignationCode($id); 
            $designation=$designation_id[0]['designation_id'];
            $annual_allowance_type=$designation_id[0]['annual_allowance_type']; 
            //$max_leave=$this->Unit_model->findMaxleaveByDesignation($user_type,$designation,$annual_allowance_type); 
            $days = $this->input->post('total_days');
            $from_date     = $this->formatDate($this->input->post('start_date'));    
            $to_date       = $this->formatDate($this->input->post('end_date')); 
            $start_time    = $this->input->post('start_time').':'.$this->input->post('start_time1');
            $end_time      = $this->input->post('end_time').':'.$this->input->post('end_time1');  
            $comment       = $this->input->post('message');
            $user_id           = $id;
            $days = $this->input->post('total_days'); 
            $start=$this->input->post('start_date');
           //$start_date=date('Y-m-d',strtotime($start));
            //print_r(strtotime($from_date)); 
            // print_r(strtotime($from_date));
            // print_r('<br>');
            // print_r(strtotime(date('Y/m/d'))); 
        if (strtotime($from_date)>strtotime(date('Y/m/d'))) 
        {  //print_r('hii');exit();
                $leave_remaining=$this->Leave_model->checkLeaveremaining($id); //print_r($leave_remaining);exit();
                $annual_holliday_allowance=$this->Leave_model->getActualholidayallowance($id); //print_r($actual_holiday_allowance);exit(); 
                $allowance=$annual_holliday_allowance[0]['annual_holliday_allowance'];//print_r($allowance);exit();
                $remaining=$this->Leave_model->CheckRemainingHolliday($id); //print_r(count($remaining));exit();
                $applied= str_replace(':','.',$days); //print_r($applied);exit();
                //ret4rn the value itself. Not array. so count checking will cause error 
                // if(count($remaining)==0)
                //Edited by chinchu
                if($remaining == 0)
                { //print_r('hiii');
                    $remaining_days=$allowance-$applied; 
                    
                }
                else
                { //print_r('hello');
                    $remaining_days=$remaining-$applied;
                    
                }
                //print_r((int)$remaining_days);exit();
                if((int)$remaining_days <= 0){ //print_r('hiii');exit();
                    $remaining_days = 0;
                }
                //exit();
                $allowance=$remaining_days;
                //print_r($remaining_days);exit();
                // if(count($leave_remaining)==0)
                // {
                    $leaveDetails = array(
                    'user_id'=>$user_id,
                    'from_date'=>$from_date,
                    'to_date'=>$to_date,
                    'start_time'=>$start_time,
                    'end_time'=>$end_time,
                    'comment'=>$comment,
                    'unit_id'=>$userUnitDetails['values'][0]['unit_id'],
                    'status'=>1,
                    'creation_date'=>date('Y-m-d H:i:s'),
                    'days'=>$days,
                    'leave_remaining'=>$remaining_days,
                    'approved_date'=>date('Y-m-d H:i:s'),
                    'approved_by'=>$this->session->userdata('user_id'),
                    'updation_date'=>date('Y-m-d H:i:s'),
                    'updated_userid'=>$this->session->userdata('user_id')
                    );
                    //print_r($leaveDetails);exit();
                    $this->Leave_model->updateRemainingAllowanceByuser($user_id,$allowance);
                // }
                // else
                // {
                //     $leaveDetails = array(
                //     'user_id'=>$user_id,
                //     'from_date'=>$from_date,
                //     'to_date'=>$to_date,
                //     'start_time'=>$start_time,
                //     'end_time'=>$end_time,
                //     'comment'=>$comment,
                //     'unit_id'=>$userUnitDetails['values'][0]['unit_id'],
                //     'status'=>0,
                //     'creation_date'=>date('Y-m-d H:i:s'),
                //     'days'=>$days,  
                //     'updation_date'=>date('Y-m-d H:i:s')
                //     );
                // }

            $save_details = $this->Leave_model->insertLeaveDetails($leaveDetails);
            if($save_details)
            {
              //$holidyTypeDetails['message']='Leave applied successfully.';
             
                    if($userUnitDetails['values'])
                    {
                        $user_unit_id = $userUnitDetails['values'][0]['unit_id'];
                        $userUnitDetails['user_values'] = $this->Leave_model->getUsersFromUnit($user_unit_id); //print_r($userUnitDetails['user_values']);exit();
                        
                        if($userUnitDetails['user_values'])
                        {
                            $to_email = $userUnitDetails['user_values'][0]['email']; //
                            // $to_email = "chinchugopi89@gmail.com";
                            //$supervisor_id = $userUnitDetails['user_values'][0]['id'];       
                            $staff_name = $this->session->userdata('full_name');
                            $name = $userUnitDetails['user_values'][0]['fname']." ".$userUnitDetails['user_values'][0]['lname'];
                            //print_r($name);exit();

                            $fromdate = $from_date;
                            $todate = $to_date;
                                $days = $days;
                                $supervisor_name = $this->session->userdata('full_name');
                                $site_title = 'St Matthews Healthcare - SMH Rota';
                                $admin_email=getCompanydetails('from_email');
                                $emailSettings = array(
                                    'from' => $admin_email,
                                    'site_title' => $site_title,
                                    'site_url' => $this->config->item('base_url'),
                                    'to' => $to_email,
                                    'type' => 'Admin-approve holiday',
                                    'user_name'=> $name,
                                    'fromdate'=> $fromdate,
                                    'todate'=>$todate,
                                    'days'=>$days,
                                    'comments'=>$comment,
                                    'supervisor_name'=>$supervisor_name,
                                    'subject' => 'Your Holiday request has been approved!',
                                     );
                                 //print_r($emailSettings);exit();
                                $this->load->library('parser');
                                $htmlMessage = $this->parser->parse('emails/approve', $emailSettings, true);
                                //die($htmlMessage);exit();
                                $this->load->helper('mail'); 
                                sendMail($emailSettings, $htmlMessage); 
                                $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                                redirect('admin/Holiday');
                           
 
                        }else{
                            $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                            redirect('admin/Holiday');
                        }                    
                    }else{
                        $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                        redirect('admin/Holiday');
                    }
            }else{
                $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                redirect('admin/Holiday');
            }
        }
        else
        {  //print_r('helloo');exit();
            $this->load->view('includes/home_header');
            if($this->session->userdata('user_id')==1)
            { 
                $data['unit'] = $this->User_model->fetchCategoryTree('');  
            }
            else
            {
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); 
            }  
            $data['user']=$this->Reports_model->finduserdata($userUnitDetails['values'][0]['unit_id'],$status);
            //print_r($data['user']);exit();
           // $data['$user_post']=$this->input->post('user');
            //print_r($data['$user_post']);exit();
            $data['error']= 'Please select future dates';
            $this->load->view("admin/user/addleave",$data);
            $result['js_to_load'] = array('holiday/addleave.js');
            $this->load->view('includes/home_footer',$result);

        }
    }
    function formatDate($date){
        $date_array  = explode("/",$date);
        $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        return $actual_date;
    }


   public function approveholiday()
   {   //print_r($this->input->post());exit();
        if($this->session->userdata('user_id')){
            $request_id = $this->input->post('holiday_id');
            $user_id = $this->input->post('user_id');
            $comment = $this->input->post('comment');
            $hour = $this->input->post('hour');

            $this->Leave_model->updateHolidayDaysbyUser($request_id,$hour);
            //new changes
            $user_leave_deatils = $this->Leave_model->getUserHolidayDetails($request_id,$user_id); //print_r($user_leave_deatils);exit();
            $year=$this->Leave_model->findyear($request_id); 
            $result=$this->Leave_model->findDays($user_id,$year); //print_r($result);exit();
            $sum_days='00.00';
            $remaining_leave='00.00';
            foreach ($result as $value) {
                $applieddays=str_replace(':', '.', $value['days']); 
                $applieddays1=number_format(getPayrollformat(number_format($applieddays,2),2),2); 
                $sum_days=$sum_days+$applieddays1; 
                if($value['remaining_leave']=='')
                {
                    $leave='0.0';
                }
                else
                {
                    $leave=$value['remaining_leave'];
                }
                $remaining_leave=$remaining_leave+$leave;
            }
            //print_r($remaining_leave.' '. $sum_days);exit();
            $total=$this->Leave_model->finddaysbyuser($request_id); //print_r($total);exit();
            $new_user_hour=str_replace(':', '.', $total);
            $applieddays1=number_format(getPayrollformat(number_format($new_user_hour,2),2),2);
            $total_days=$sum_days+$applieddays1;  
            $sum=number_format((float)$sum_days, 2, '.', '');  
            //print_r($sum);exit();
            if(date("m") > 8)
            {
                                                  $currentyear = date("Y");
                                                  $nxtyear = date("Y")+1;
            }
            else
            {
                                                  $currentyear = date("Y")-1;
                                                  $nxtyear = date("Y");

            }
            //$currentyear=date('Y');
            //$nxtyear = date("Y")+1;
            $CYear = $currentyear."-".$nxtyear;
            $leaves_by_user=$this->Leave_model->findAnnualHollidayAllowance($user_id); 
            if($CYear==$year)
            {
                //find annual leave
                $remaining=$leaves_by_user[0]['annual_holliday_allowance'];
            }
            else
            {
                //find actual leave
                $remaining=$leaves_by_user[0]['actual_holiday_allowance'];
            }
            $actual_leave_remaining=$remaining-$sum;  
            $actual_total=number_format((float)$actual_leave_remaining, 2, '.', ''); 
             //print_r($actual_total);exit();
 
            $name = $user_leave_deatils[0]['fname']." ".$user_leave_deatils[0]['lname'];
            $mobile_number = $user_leave_deatils[0]['mobile_number'];
            if($mobile_number)
            {    
                //sms integration
                $message = 'Your Holiday request has been approved';
                // print_r($message);exit();
                $this->load->model('AwsSnsModel');
                $sender_id="SMHApproveHoliday";
                $result = $this->AwsSnsModel->SendSms($mobile_number, $sender_id, $message);
                // print_r($result);exit();
            } 
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

            $leaveDataResult = $this->Leave_model->losOfPayChecking($user_id);    
            $data['total_leave_applied']=$leaveDataResult['total_leave_applied']; 
            $data['lop'] = $leaveDataResult['status'];  
            $remaining_leave = $this->leaveBalanceCheck($leaveDataResult['total_leave_applied'],$leaveDataResult['status'],$leaveDataResult['annual_holliday_allowance']);  //remaining leave//
            //find the number of days
            $remaining=$this->Leave_model->findleaveremainingbyuser($user_id); //print_r($remaining);exit();
            $num_days=$this->Leave_model->findnumberofDays($request_id);  //print_r($num_days);exit();
            $applieddays=str_replace(':', '.', $num_days[0]['days']); 
            $remaining_leave=$remaining[0]['remaining_holiday_allowance'];
            $leave=$remaining_leave-$applieddays; //print_r(int($leave));exit();
            if((int)$leave <= 0){
                $leave = 0;
            }
            $dataform=array(
                'status'=>1,
                'approved_date'=>date('Y-m-d H:i:s'),
                'remaining_leave'=>$actual_total,
                'leave_remaining'=>$leave,
                'approved_by'=>$this->session->userdata('user_id'),
                'updation_date'=>date('Y-m-d H:i:s'),
                'updated_userid'=>$this->session->userdata('user_id')
            );
            //print_r($dataform);print_r($request_id);exit();
            $activity_date= date('Y-m-d H:i:s');
            $activity_by=$this->session->userdata('user_id');
            $description= $name."'s"." "."Holiday request"." "."("." "."from"." ".$fromdate." "."to"." ".$todate." "."[".$days." "."hours"."]"." ".")"." "."has been approved.";

            $log=array( 
            'description'   => $description,  
            'activity_date' => $activity_date, 
            'activity_by'   => $activity_by,  
            'add_type'      => 'Approve_holiday', 
            'user_id'       => ' ', 
            'primary_id'    => ' ',
            'creation_date' => date('Y-m-d H:i:s') 
            );  
            $this->Activity_model->insertactivity($log);

            $this->User_model->updateholiday($request_id,$dataform);
            $this->Leave_model->updateRemainingAllowanceByuser($user_id,$leave); //update reamining leave in user table
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $admin_email=getCompanydetails('from_email');
           // $messages= " Your Holiday request"." "."("."from"." ".$fromdate." "."to"." ".$todate." "."[".$days." "."hours"."]".")"." "."has been approved.";
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => $to_email,
                'type' => 'Admin-approve holiday',
                'user_name'=> $name,
                'fromdate'=> $fromdate,
                'todate'=>$todate,
                'days'=>$days,
                'comments'=>$comment,
                'supervisor_name'=>$supervisor_name,
                'subject' => 'Your Holiday request has been approved!',
            );
            // print_r($emailSettings);exit();
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/approve', $emailSettings, true);
            //die($htmlMessage);
            $this->load->helper('mail'); 
            sendMail($emailSettings, $htmlMessage);  
            $new_days=number_format(getPayrollformat(number_format(settimeformat($hour),2),2),2); //print_r($new_days);exit();
            $json_data = array(
                                "total"   => $actual_total,
                                "days"    => $new_days
                            );
         
           echo json_encode($json_data);
 
            // $this->session->set_flashdata('message', 'Holiday request approved.'); 
            // redirect('admin/Holiday');
        }
        else
        {
            $this->auth->logout();
        }
   }
   public function rejectholiday()
   {
     //$this->auth->restrict('Admin.Annual Leave.Reject');
        if($this->session->userdata('user_id')){
            $request_id = $this->input->post('holiday_id');
            $user_id = $this->input->post('user_id');
            $comment = $this->input->post('comment');
            $status= $this->input->post('status');
            $hour = $this->input->post('hour');
            $remaining=$this->input->post('remaining'); //print_r($remaining);exit();

            $year=$this->Leave_model->findyear($request_id); 
            $result=$this->Leave_model->findDays($user_id,$year);
            $sum_days='00.00';
            foreach ($result as $value) {
                $applieddays=str_replace(':', '.', $value['days']);
                $applieddays1=number_format(getPayrollformat(number_format($applieddays,2),2),2); 
                $sum_days=$sum_days+$applieddays1;  
            } 

            $total=$this->Leave_model->finddaysbyuserforRejection($request_id); 
            //print_r($total);exit();
            if($total[0]['status']==1)
            {
                $new_user_hour=str_replace(':', '.', $total[0]['days']);
                $applieddays1=number_format(getPayrollformat(number_format($new_user_hour,2),2),2); 
                $total_days=$sum_days-$applieddays1; 
            }
            else
            {
                $total_days=$sum_days;
            }  
            //print_r($total_days);exit();
            $sum=number_format((float)$total_days, 2, '.', '');  
            if(date("m") > 8)
            {
                            $currentyear = date("Y");
                            $nxtyear = date("Y")+1;
            }
            else
            {
                            $currentyear = date("Y")-1;
                            $nxtyear = date("Y");

            }  
            //$currentyear=date('Y');
            //$nxtyear = date("Y")+1;
            $CYear = $currentyear."-".$nxtyear;

            $leaves_by_user=$this->Leave_model->findAnnualHollidayAllowance($user_id); 
            if($CYear==$year)
            {
                //find annual leave
                $remaining=$leaves_by_user[0]['annual_holliday_allowance'];
            }
            else
            {
                //find actual leave
                $remaining=$leaves_by_user[0]['actual_holiday_allowance'];
            } 

            $actual_leave_remaining=$remaining-$sum;  
            $actual_total=number_format((float)$actual_leave_remaining, 2, '.', '');  
            //print_r($actual_total);exit();

            $user_leave_deatils = $this->Leave_model->getUserHolidayDetails($request_id,$user_id);
            $name = $user_leave_deatils[0]['fname']." ".$user_leave_deatils[0]['lname'];
            $fromdate = $user_leave_deatils[0]['from_date'];
            $todate = $user_leave_deatils[0]['to_date'];
            $days = $user_leave_deatils[0]['days'];
            $to_email = $user_leave_deatils[0]['email'];
            $supervisor_name = $this->session->userdata('full_name');
            $mobile_number = $user_leave_deatils[0]['mobile_number'];
            if($mobile_number)
            {    
                //sms integration
                $message = 'Your Holiday request has been rejected';
                // print_r($message);exit();
                $this->load->model('AwsSnsModel');
                $sender_id="SMHRejectHoliday";
                $result = $this->AwsSnsModel->SendSms($mobile_number, $sender_id, $message);
                //print_r($result);exit();
            } 

            $comments_details = array(
                'manager_id'=>$this->session->userdata('user_id'),
                'holiday_id'=>$request_id,
                'comment'=>$comment,
                'status'=>2,
                'date'=>date('Y-m-d H:i:s'),
            );
            //print_r($hour); print_r($status);exit();
            $save_comment_details = $this->Leave_model->insertCommentDetails($comments_details); 
            $remaining_leave=str_replace(':', '.', $remaining); //print_r($remaining_leave);exit();
            $hours=str_replace(':', '.', $hour);
            if($status==1)
            {
              $leaves=$remaining_leave+$hours;
            }
            else
            {
              $leaves=$remaining_leave;
            } 
            $dataform=array(
                'leave_remaining'=>$leaves,
                'remaining_leave'=>$actual_total,
                'status'=>2,
                'approved_date'=>date('Y-m-d H:i:s'),
                'approved_by'=>$this->session->userdata('user_id'),
                'updation_date'=>date('Y-m-d H:i:s'),
                'updated_userid'=>$this->session->userdata('user_id')
            );
            $result = $this->User_model->updateholiday($request_id,$dataform);

            $activity_date= date('Y-m-d H:i:s');
            $activity_by=$this->session->userdata('user_id');
            $description= $name."'s"." "."Holiday request"." "."("." "."from"." ".$fromdate." "."to"." ".$todate." "."[".$days." "."hours"."]"." ".")"." "."has been rejected.";

            $log=array( 
            'description'   => $description,  
            'activity_date' => $activity_date, 
            'activity_by'   => $activity_by,  
            'add_type'      => 'Reject_holiday', 
            'user_id'       => ' ', 
            'primary_id'    => ' ',
            'creation_date' => date('Y-m-d H:i:s') 
            );  
            $this->Activity_model->insertactivity($log);

            $this->load->model('Rota_model');
            $this->Rota_model->removeHolidayFromRota($user_id,$fromdate,$todate); 
            $this->Leave_model->updateRemainingAllowanceByuser($user_id,$leaves); //update reamining leave in user table
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => $to_email,
                'type' => 'Admin-reject holiday',
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
            // $this->session->set_flashdata('message', 'Holiday request rejected.'); 
            // redirect('admin/Holiday');
            echo json_encode($actual_total);
        }else{
            $this->auth->logout();
        }
   }

   public function leaveBalanceCheck($total_leave_applied,$lop_status,$total_allowance){
        if(intval($lop_status) == 1)
        {
            return 0;
        }
        else
        {
            return $total_allowance - $total_leave_applied;
        }
    }

   public function findholiday()
   {
            $json = array();
            $user_id=$this->session->userdata('user_id');

            $date_daily=$this->input->post('from_date');  
            $old_date = explode('/', $date_daily); 
            $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $from_date=$new_data;

            $date_daily=$this->input->post('to_date');  
            $old_date = explode('/', $date_daily); 
            $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $to_date=$new_data1;
            $jobrole = $this->input->post('jobrole');
            $unit = $this->input->post('unit');
            $status = $this->input->post('status');
            $filter_status = $this->input->post('filter_status');
            if($unit!='')
            {
                $unit_id=$unit;
            }
            else
            {
                //if($this->session->userdata('user_type')==1)
                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                {
                    $unit_id='';
                }
                else
                {
                    $unit_id=$this->session->userdata('unit_id');
                }
            }


             //print_r($unit_id);exit();
              // if($this->session->userdata('user_type')==1)
              // {
              // $result=$this->Leave_model->findholiday($from_date,$to_date,$unit_id,'',$jobrole);
              // }
              // else
              // {
              // $result=$this->Leave_model->findholiday($from_date,$to_date,$unit_id,$user_id,$jobrole);
              // }


                $sub=$this->User_model->CheckuserUnit($user_id);
                $unit=$this->User_model->findunitofuser($user_id); 
                $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                //if($this->session->userdata('user_type')==1) //all super admin can access
                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                {
                    $result=$this->Leave_model->findholiday($from_date,$to_date,$unit_id,'',$jobrole,$status,$filter_status);
                }
                // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
                else if($this->session->userdata('subunit_access')==1)
                { //if unit administrator
                    // if($sub!=0 || $parent!=0) //unit administrator in sub unit
                    // {   
                    //     if($sub==0)
                    //     {
                    //         $sub=$parent;
                    //     }
                    //     else
                    //     {
                    //         $sub=$sub;
                    //     }
                    //     $result=$this->Leave_model->findholiday($from_date,$to_date,$sub,$user_id,$jobrole,$status);  
                    // }
                    // else
                    // {    
                        $result=$this->Leave_model->findholiday($from_date,$to_date,$unit_id,$user_id,$jobrole,$status,$filter_status);  
                    // }

                }
                else
                {
                    $result=$this->Leave_model->findholiday($from_date,$to_date,$unit_id,$user_id,$jobrole,$status,$filter_status);
                }
                    
                
              foreach ($result as $row)
              {  
                if($row['id']!='')
                {
                    $message=getMessage($row['id']);
                    $messages="";
                    $new_message1=""; 
                    foreach($message as $msg){ 
                        if($msg['holi_stat']!=0)
                        {  
                            if($msg['holi_stat']==3)
                            {
                               $messages= '<b>'."Cancelled by ".'</b>' . $row['fname'].' '.$row['lname'].''.'.'.' '; 
                               $new_message1.=$messages.'<br>'.'<br>';
                            }
                            else
                            {
                              if($msg['status']==1) 
                                {
                                    $messages= '<b>'."Approved by ".'</b>' . $msg['fname'].' '.$msg['lname'].''.':'.' ';   
                                }
                                else
                                {
                                    $messages= '<b>'."Rejected by " .'</b>'. $msg['fname'].' '.$msg['lname'].':'.' '; 
                                }
                                $new_message1.=$messages.' '.$msg['comment'].','.date("d/m/Y  H:i:s",strtotime($msg['date'])).'<br>'.'<br>';  
                                //print_r($new_message);
                            }
                        }  

                                                             
                    } //exit();

                    // print_r($new_message1);exit();

                }
                else
                {
                    $new_message='';
                }
                // print_r($new_message); print '<br>';
                 $new_message='<a class="Reject" data-container="body" title="" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" 
                data-content="'.$new_message1.'" data-original-title="Annual leave messages" >'.'<i class="fa fa-envelope"></i>'.'</a>';
                
                $leave_remaining=getLeaveRemainingbyuser($row['to_date'],$row['user_id']);
                //print_r($leave_remaining); exit();
                $leave_type=$this->Leave_model->findholidaybyuser($row['user_id']); 
                $year=getYear($row['year']);
               // print_r($row['remaining_leave']);
                if($row['remaining_leave']=='')
                {  
                    $Leaves=getLeaves($row['user_id'],$row['year']);
                }
                else
                {   
                     $Leaves=$row['remaining_leave'];
                } 

                $result=$Leaves.' '.'('.$year.')'; //print_r($result);exit();
                
                if(date("m") > 8)
                {
                            $currentyear = date("Y");
                            $nxtyear = date("Y")+1;
                }
                else
                {
                            $currentyear = date("Y")-1;
                            $nxtyear = date("Y");

                }

                //$currentyear = date("Y");
                //$nxtyear = date("Y")+1;
                $CYear = $currentyear."-".$nxtyear; 
                if($leave_remaining['year']==$CYear)
                {  
                    $leave_remaining=$leave_type[0]['annual_holliday_allowance']-$leave_remaining['hour'];  
                    if((int)$leave_remaining <= 0)
                    {
                        $leave_remaining='0.00';
                    }
                    else
                    {
                        $leave_remaining=$leave_remaining;
                    }
                }
                else
                {
                    $leave_remaining=$leave_type[0]['actual_holiday_allowance']-$leave_remaining['hour'];
                    if((int)$leave_remaining <= 0)
                    {
                        $leave_remaining='0.00';
                    }
                    else
                    {
                        $leave_remaining=$leave_remaining;
                    }
                } 
               
                $title=number_format($leave_remaining,2);

                if($row['status']==0)
                {  
                    $remaining=getRemainingLeave($row['user_id']);  
                    $rem_day= number_format($remaining, 2);
                }
                    
                    $days=str_replace(":",".",$row['days']); 


                if($row['status']==0)
                {
                    $leave = $remaining;
                    $leaves = str_replace(".",":",$rem_day);
                }
                else
                {
                    $leaves=str_replace(".",":",number_format($row['leave_remaining'],2)); 
                    if($leaves=='0:00')
                    {
                        $leave=0;
                    }
                }                               
                if($row['status']==1){ $status="Approved";}elseif($row['status']==2){$status="Rejected";}elseif($row['status']==0){$status="Pending";}else{$status="Cancelled";}
                $stat= "<span id='status_".$row['id']."'>".$status."<span>";
                $result_new="<span  id='leaves_".$row['id']."' >".$result."</span>";
                if($row['status']==3)
                {
                  $button_approve='';
                }
                else if($row['status']==2)
                { 

                  $button_approve="<a class='Approve_".$row['id']."' data-id='".$row['id']."' title='Approve' href='javascript:void(0);' onclick=approveFunction('".$row['id']."','".$row['from_date']."','".$row['to_date']."','".$row['unit_id']."','".$row['designation_id']."','".$row['user_id']."','".$row['year']."','".$row['remaining_leave']."','".$year."','".$row['days']."') value='".$row['user_id']."'  >"."<i class='fa fa-check-circle'></i>&nbsp";
                }
                else if($row['status']==1)
                { 

                  $button_approve="<a class='Reject_".$row['id']."' data-id='".$row['id']."' title='Reject' href='javascript:void(0);' onclick=rejectFunction('".$row['id']."','".$row['user_id']."','".$row['status']."','".$row['days']."','".$row['leaves']."','".$row['year']."','".$row['remaining_leave']."','".$year."') value='".$row['user_id']."'>"."<i class='fa fa-ban'></i>&nbsp"; 
                }
                else
                {
                  $button_approve="<a class='Approve_".$row['id']."' data-id='".$row['id']."' title='Approve' href='javascript:void(0);' onclick=approveFunction('".$row['id']."','".$row['from_date']."','".$row['to_date']."','".$row['unit_id']."','".$row['designation_id']."','".$row['user_id']."','".$row['year']."','".$row['remaining_leave']."','".$year."','".$row['days']."') value='".$row['user_id']."'  >"."<i class='fa fa-check-circle'></i>&nbsp"." "."<a class='Reject_".$row['id']."' data-id='".$row['id']."' title='Reject' href='javascript:void(0);' onclick=rejectFunction('".$row['id']."','".$row['user_id']."','".$row['status']."','".$row['days']."','".$row['leaves']."','".$row['year']."','".$row['remaining_leave']."','".$year."') value='".$row['user_id']."' >"."<i class='fa fa-ban'></i>&nbsp";
                }
                $from_date='<span style="display:none;">'.$row['from_date'].'</span>'.date("d/m/Y", strtotime($row['from_date'])); 
                $to_date='<span style="display:none;">'.$row['to_date'].'</span>'.date("d/m/Y", strtotime($row['to_date']));

                

                $days=str_replace(":",".",$row['days']); 
                $day=number_format(getPayrollformat(number_format($days,2),2),2);
                $days_day= "<span id='days_".$row['id']."'>".$day."<span>"; 
                if($row['leave_remaining']!=''){
                $leave=str_replace(".",":",$row['leave_remaining']);}else{ $leave="00:00";}
                $creation_date='<span style="display:none;">'.$row['creation_date'].'</span>'.date("d/m/Y H:i:s", strtotime($row['creation_date']));
                $json[] =array($row['user_id'],$row['fname']." ".$row['lname'],$row['unit_name'],$from_date,$to_date,$days_day,$row['comment'],$creation_date,$stat,$result_new,$button_approve.' '.$new_message);
              } 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
   }
    public function checkSessionExpired(){
        if($this->session->userdata('user_id')){
            header('Content-Type: application/json');
            echo json_encode(array('status' => 1));
            exit();
        }else{
            header('Content-Type: application/json');
            echo json_encode(array('status' => 2));
            exit();
        }
    }
    public function findholidaydetailsforapproval()
    {
        if($this->session->userdata('user_id')){
            $params=array();
            $params['from_date']=$this->input->post('from_date');
            $params['to_date']=$this->input->post('to_date');
            $params['unit_id']=$this->input->post('unit');
            $params['user_id']=$this->input->post('user_id');
            $params['designation_id']=$this->input->post('designation');
            $params['status']=1;
            $all_dates = $this->findDatesBtwnDates($params['from_date'],$params['to_date']);
            $result=$this->Leave_model->checkAlreadyApprovedLeaves($params,$all_dates);
            header('Content-Type: application/json');
            echo json_encode(array('status' => 1,'result'=> $result));
            exit();
        }
        else{
            header('Content-Type: application/json');
            echo json_encode(array('status' => 2,'result'=> []));
            exit();
        }
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
public function getUserHolidayAndOffday(){ 
    $this->load->model('Workschedule_model');
    $this->load->model('Rotaschedule_model');
    $user_id = $this->input->post('user_id');
    $user_ids=array();
    $user_offday=array();
    array_push($user_ids, $user_id);
    $holiday_dates = $this->Rotaschedule_model->getHolidayDates($user_ids);
    $offday = $this->Workschedule_model->getUserworkschedule($user_id); 
    header('Content-Type: application/json');
    echo json_encode(array('status' => 1,'holiday_dates'=> $holiday_dates,'offday'=>$offday));
    exit();

}

  function convert($hours, $minutes) {
    return $hours + round($minutes / 60, 2);
    }


}
?>