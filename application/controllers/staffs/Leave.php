<?php
if (!defined('BASEPATH'))
    die();
class Leave extends CI_Controller {
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        Parent::__construct();
        if ($this->session->userdata('user_type') !=2){
            $this->auth->logout();            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        
        //Loading company model
        $this->load->model('Leave_model');
        $this->load->model('User_model');
        $this->load->model('Unit_model');
        $this->load->model('Workschedule_model');
        $this->load->model('Rotaschedule_model');
        $this->load->helper('form');
    }
    public function listleaves() {
        $this->auth->restrict('Annual Leave.View');
        $result = array();
        $this->header['title'] = 'Apply Holiday';
        $id=$this->session->userdata('user_id');
        $this->load->view('includes/staffs/newstaff_headerrota');
        $data['user']=$this->User_model->findusers($id);  
        $id=$this->session->userdata('user_id'); 
        $data['user_id']=$id;
        $data['users']=$this->User_model->findholidays($id); 
        $current_date=date('Y-m-d'); 
        $leave_year=$this->checkLeaveyear($current_date);
        $data['leave_year']=$leave_year;
        $data['years']=$this->Leave_model->findyearbyuser($id);
        $annual_leave=$this->Leave_model->findAnnualHollidayAllowance($id);  //print_r($annual_leave);exit(); 
        $data['annual_holliday_allowance']=$annual_leave[0]['annual_holliday_allowance']; //print_r($actual_holiday_allowance);
        $data['actual_holiday_allowance']=$annual_leave[0]['actual_holiday_allowance'];
         
        // $leaveDataResult = $this->Leave_model->losOfPayChecking($this->session->userdata('user_id')); 
        // $annual_leave=$this->Leave_model->findAnnualHollidayAllowance($id);
        // //Edited by chinchu. Added annual holiday allowance
        // $annual_holliday_allowance==$annual_leave[0]['annual_holliday_allowance']; //print_r($actual_holiday_allowance);
        // $leave_remaining=$this->Leave_model->findleaveremainingbyuser($id);
        // $data['leave_remaining']=$leave_remaining[0]['remaining_holiday_allowance'];
        // //edited by chinchu
        // $data['applied_leave'] = $leaveDataResult['total_leave_applied'];
        // //$data['applied_leave']=$annual_holliday_allowance-$data['leave_remaining'];
        // if($data['leave_remaining'] == null){
        //     $data['leave_remaining'] = 0;
        // }
        // if($data['leave_remaining'] < 0) {
        //     $data['leave_remaining'] = 0;
        // }
        // $data['total_leave_applied']=$leaveDataResult['total_leave_applied']; 
        // $data['lop'] = $leaveDataResult['status'];  
        // $data['remaining_leave'] = $this->leaveBalanceCheck($leaveDataResult['total_leave_applied'],$leaveDataResult['status'],$leaveDataResult['annual_holliday_allowance']);
        // //changed in model from actual to annual holiday allowance
        // $data['annual_holliday_allowance'] = $leaveDataResult['annual_holliday_allowance'];
        $data['error'] =''; 
        $data['message'] ='';
        $this->load->view("staffs/leave/listleaves_mob",$data);
        // $jsfile['js_to_load'] = array('leave/staff_leave_mob.js');
        // $this->load->view('includes/staffs/footer',$jsfile);
    }
    public function index() {
        $this->auth->restrict('Annual Leave.View');
        $result = array();
        $this->header['title'] = 'Apply Holiday'; 
        $this->load->view('includes/staffs/header');
        $id=$this->session->userdata('user_id');
        $data['user_id']=$id;
        $data['user']=$this->User_model->findusers($id);   
        $data['users']=$this->User_model->findholidays($id);
        $current_date=date('Y-m-d'); 
        $leave_year=$this->checkLeaveyear($current_date);  
        $data['leave_year']=$leave_year;
        $data['years']=$this->Leave_model->findyearbyuser($id); 
        $annual_leave=$this->Leave_model->findAnnualHollidayAllowance($id);  //print_r($annual_leave);exit(); 
        $data['annual_holliday_allowance']=$annual_leave[0]['annual_holliday_allowance']; //print_r($actual_holiday_allowance);
        $data['actual_holiday_allowance']=$annual_leave[0]['actual_holiday_allowance'];
        

        // $leave_remaining=$this->Leave_model->findleaveremainingbyuser($id);
        // $data['leave_remaining']=sprintf ("%.2f",$leave_remaining[0]['remaining_holiday_allowance']);
        // if($data['leave_remaining'] == null){
        //     $data['leave_remaining'] = 0;
        // }
        // if($data['leave_remaining'] < 0) {
        //     $data['leave_remaining'] = 0;
        // }
        //edited by chinchu
        //$data['applied_leave'] = sprintf ("%.2f", $leaveDataResult['total_leave_applied']); 
        //print_r($data['applied_leave']); exit();
        //$data['applied_leave']=$annual_holliday_allowance-$data['leave_remaining'];
        // $data['total_leave_applied']=$leaveDataResult['total_leave_applied']; 
        // $data['lop'] = $leaveDataResult['status'];  
        // $data['remaining_leave'] = $this->leaveBalanceCheck($leaveDataResult['total_leave_applied'],$leaveDataResult['status'],$leaveDataResult['annual_holliday_allowance']);
        // //changed in model from actual to annual holiday allowance 
        // $data['annual_holliday_allowance'] = $leaveDataResult['annual_holliday_allowance'];
        $data['error'] =''; 
        $data['message'] ='';
        $this->load->view("staffs/leave/listleaves",$data);
        $jsfile['js_to_load'] = array('leave/staff_leave.js');
        $this->load->view('includes/staffs/footer',$jsfile);
    }
    public function leaveBalanceCheck($total_leave_applied,$lop_status,$total_allowance){
        if(intval($lop_status) == 1){
            return 0;
        }else{
            return $total_allowance - $total_leave_applied;
        }
    }
    public function leaverequest(){
        $status = 0;
        $user_ids = array();
        $this->load->view('includes/staffs/header');
        $leaveDataResult = $this->Leave_model->losOfPayChecking($this->session->userdata('user_id'));
        $offday = $this->Workschedule_model->getUserworkschedule($this->session->userdata('user_id'));
        array_push($user_ids,$this->session->userdata('user_id'));
        $holidayDates = $this->Rotaschedule_model->getHolidayDates($user_ids);
        $holidyTypeDetails = array();
        $holidyTypeDetails['values']=$this->Leave_model->getHolidayTypes(); 
        $holidyTypeDetails['user']=$this->User_model->findusers($this->session->userdata('user_id')); 
        $holidyTypeDetails['allowance']=$this->Leave_model->AnnualHolidayAllowance($this->session->userdata('user_id'));
        $holidyTypeDetails['total_days']=$this->Leave_model->totalLeaveApplied($this->session->userdata('user_id'));  
        //print_r($holidyTypeDetails['allowance']);exit(); 
        $holidyTypeDetails['lop'] = $leaveDataResult['status'];   
        $holidyTypeDetails['annual_allowance'] = $leaveDataResult['annual_holliday_allowance'];
        $holidyTypeDetails['user_offday'] = json_encode($offday);
        $holidyTypeDetails['holidayDates']=json_encode($holidayDates); 
        $holidyTypeDetails['message'] =''; 
        $holidyTypeDetails['error'] =''; 
        $holidyTypeDetails['total_leave_applied'] = $leaveDataResult['total_leave_applied'];
        $selected_year=date('Y', strtotime('+1 years'));
        $holidyTypeDetails['selected_date']=$selected_year.'/'.'08'.'/'.'31';
        $this->load->view("staffs/leave/leaverequest",$holidyTypeDetails);
        //$jsfile = array();
        $jsfile['js_to_load'] = array('leave/staff_leave.js');
        $this->load->view('includes/staffs/footer',$jsfile);
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
    // Declare an empty array 
    $array = array(); 
      
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $date) {                  
        $array[] = $date->format($format);  
    } 
  
    // Return the array elements 
    return $array; 
} 
  
// Function call with passing the start date and end date 


    public function findshifthours()
    {
     $params=array();
     $params['user_id']=$this->input->post('user_id');
     //Added by chinchu. New array with no offdays and holidays
     $array_without_offday_holiday=$this->input->post('array_without_offday_holiday'); //print_r($array_without_offday_holiday);exit();
     if($this->input->post('start_date')!='' && $this->input->post('end_date')!='')
     {

        $params['start_date']=$this->formatDate($this->input->post('start_date'));
        $params['end_date'] =$this->formatDate($this->input->post('end_date'));
        // $params['end_date']=$this->input->post('end_date');  
         //$diff = date_diff($params['start_date'],$params['end_date']);
         $Date = getDatesFromRange($params['start_date'], $params['end_date']); 
         //var_dump($Date); 
         //$days=$diff->format("%a");
         //print_r($days);
         //exit();
         // $date = strtotime("+1 day", strtotime($params['start_date']));
         // echo date("Y-m-d", $date);

        //print_r($Date);exit();
         $sum=0;$minutes = 0;$hour=0;$minute=0;
         foreach($Date as $day)
         {  
            //$status=$this->Leave_model->
             if($day<=$params['end_date'])
             {   
                 $shift=$this->Leave_model->findshifthours($params['user_id'],$day); //print_r($shift);
                 //added by chinchu. If there is no data in rotaschedule table $shift will be a null array then $shift[0] will return error. 
                 if(count($shift) == 0)  
                 { //print_r('hello');exit();
                    //Added by chinchu. Check each date with new array. if date is 
                    //inside new array then only need to calculate the hours
                   
                    if (in_array($day, $array_without_offday_holiday)) { //print "sdfgs";exit();
                        $targeted_hours=$this->Leave_model->findtargetedhours($params['user_id']); 
                        // print_r($targeted_hours[0]['targeted_hours']);print_r("targeted-");
                        $abc= str_replace(':','.',$targeted_hours[0]['targeted_hours']); 
                        $shift_hours=$abc;
                        list($hour, $minute) = explode(':', $targeted_hours[0]['targeted_hours']); 
                         if($minute==''){$minute='00';}
                        $minutes += $hour * 60;
                        $minutes += $minute;
                    }
                 }
                 else
                 {  //print_r('hii');exit();
                 //print_r($day); print_r($array_without_offday_holiday);
                    if (in_array($day, $array_without_offday_holiday)) {
                   //print_r("shift-");
                    $shift_hours=$shift[0]['shift_hours'];  //print_r($day.' '.$shift_hours);//exit();
                    list($hour, $minute) = explode(':', $shift_hours); 
                    //print_r('hour-'.$hour); 
                    if($minute=='')
                    {$minute='00';}
                    $minutes += $hour * 60; //exit();
                    $minutes += $minute; //print_r('minutes-'.$minutes);
                }

                 }
             }
            

                 
            $sum=$sum+$shift_hours; //print_r($sum);
         }  
          //exit();
            $hours = floor($minutes / 60);
            //print $hours."H--";
            $minutes -= $hours * 60;
            $arr = str_split($minutes); // convert string to an array
            $first=reset($arr); // 2 
            $last=end($arr);
            $abc=$first.''.$last;
                   // print $minutes."M";
            header('Content-Type: application/json');
            echo json_encode($hours.'.'.$abc);
            exit();
    }

    }

    function findremainingleave()
    {
         $user_id=$this->input->post('user_id');
         $start_date=$this->formatDate($this->input->post('start_date'));
         $annual=$this->Leave_model->findAnnualHollidayAllowance($user_id);
         $leaveyear = $this->checkLeaveyear($start_date); //print_r($leaveyear);exit();
         $leave=getHoursByUser($user_id,$leaveyear);
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
        $CYear = $currentyear."-".$nxtyear; //print_r($CYear);
        if($leaveyear==$CYear) //nextyear
        {   //print_r('hii');exit();
            $leave_remaining=$annual[0]['annual_holliday_allowance']-$leave;
        } 
        else 
        {  //print_r('hello');exit();
            $leave_remaining=$annual[0]['actual_holiday_allowance']-$leave; 
        } 

       if($leave_remaining == 0)
        {
            $leaves='00:00';
            $leavesum='0.00';
        }
        else
        {
            if(number_format($leave_remaining,2) < 0)
            {
                $leavesum='00.00';
                $leaves='00:00';
            }
            else
            {

                $leaves=$this->convertTime(number_format($leave_remaining,2),1); 
                $leavesum=$this->convertTime(number_format($leave_remaining,2),2); 
            }
            
        }
        $new=array('leave_remaining'=>$leaves,'leaves'=>$leavesum);
        
     //   print "<br>-------------<br>";  
            header('Content-Type: application/json');
            echo json_encode($new);
            exit(); 
    }

    function convertTime($dec,$status)
    {  //print_r($dec);
        // start by converting to seconds
        $seconds = ($dec * 3600); //print_r($seconds);
        // we're given hours, so let's get those the easy way
        $hours = floor($dec); //print_r($hours);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600; //print_r($seconds);
        // calculate minutes left
        $minutes = floor($seconds / 60); //print_r($minutes);
        // remove those from seconds as well
        $seconds -= $minutes * 60; // print_r($seconds); exit();

        if($status==1)
        {
            return $this->lz($hours).":".$this->lz($minutes);
        }
        else
        {
            return $this->lz($hours).".".$this->lz($minutes);
        }
        // return the time formatted HH:MM:SS
        
    }

    // lz = leading zero
    function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }



    function formatDate($date){
        $date_array  = explode("/",$date);
        $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        return $actual_date;
    }

    // function checkLeaveyear($date){  
    // $time=strtotime($date); 
    // $month=date("m",$time);// print_r("month".' '.$month); print "<br>";
    // $year=date("Y",$time); // print_r("year".' '.$year); print "<br>";

    // if ($month > 3 && $year>=date('Y')) 
    // {// print_r('hiii');exit();
    // $year = date('Y')."-".(date('Y') +1) ;
    // }
    // else 
    // { //print_r('hello');exit();
    //      if($year>date('Y'))
    //      {
    //     $year = ($year-1)."-".$year;
    //      }
    //      else
    //      {
    //     $year = (date('Y')-1)."-".date('Y');
    //     }
    // } 
    // return $year; 
    // }
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
        $id=$this->session->userdata('user_id');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $leaveDataResult = $this->Leave_model->losOfPayChecking($this->session->userdata('user_id'));
        $holidyTypeDetails = array();
        $holidyTypeDetails['values']=$this->Leave_model->getHolidayTypes();  
        $holidyTypeDetails['lop'] = $leaveDataResult['status'];
        $holidyTypeDetails['total_leave_applied'] = $leaveDataResult['total_leave_applied'];
        $userUnitDetails['values'] = $this->Leave_model->getUnitIdOfUser($id);
        $holidyTypeDetails['user']=$this->User_model->findusers($this->session->userdata('user_id')); 

        $this->form_validation->set_rules('start_date', 'start date', 'required');
        $this->form_validation->set_rules('end_date', 'end date', 'required');
        $this->form_validation->set_rules('total_days', 'total days', 'required');
        $this->form_validation->set_rules('message', 'message', 'required');
        $this->load->view('includes/staffs/header',$result);
    
        if ($this->form_validation->run() == FALSE)
        {
            $allowance=$this->Leave_model->AnnualHolidayAllowance($this->session->userdata('user_id'));
            $holidyTypeDetails['annual_allowance']=$allowance[0]['annual_holliday_allowance'];
            $holidyTypeDetails['total_days']=$this->Leave_model->totalLeaveApplied($this->session->userdata('user_id'));
            $holidyTypeDetails['user']=$this->User_model->findusers($this->session->userdata('user_id'));
            $offday = $this->Workschedule_model->getUserworkschedule($this->session->userdata('user_id'));
            array_push($user_ids,$this->session->userdata('user_id'));
            $holidayDates = $this->Rotaschedule_model->getHolidayDates($user_ids);
            $holidyTypeDetails['user_offday'] = json_encode($offday);
            $holidyTypeDetails['holidayDates']=json_encode($holidayDates);
            $selected_year=date('Y', strtotime('+1 years'));
            $holidyTypeDetails['selected_date']=$selected_year.'/'.'08'.'/'.'31';
            $holidyTypeDetails['error']='';
            $holidyTypeDetails['message'] =''; 
            $this->load->view("staffs/leave/leaverequest",$holidyTypeDetails);
        }
        else 
        { 
            $selected_units = [$userUnitDetails['values'][0]['unit_id']];
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
                redirect('staffs/leave/leaverequest');
            }  
            $user_type = $this->session->userdata('user_id');  
            $designation_id=$this->Unit_model->SelectDesignationCode($user_type);
            $designation=$designation_id[0]['designation_id'];
            $annual_allowance_type=$designation_id[0]['annual_allowance_type']; 
            $max_leave=$this->Unit_model->findMaxleaveByDesignation($user_type,$designation,$annual_allowance_type); 
            //changed june12
            $days = $this->input->post('total_days');
            //$new_time=explode(":",$day);
            //$days=number_format($this->convert($new_time[0],$new_time[1]),2); 
            $array_without_offday_holiday=$this->input->post('without_offday_holiday');  //print_r($array_without_offday_holiday); print "<br>";  
            $new_array = [];
            $without_offday_holiday = explode(",", $array_without_offday_holiday[0]);
            
// print "<pre>";
// print_r($without_offday_holiday);

//             exit();

            $from_date     = $this->formatDate($this->input->post('start_date'));
            $to_date       = $this->formatDate($this->input->post('end_date')); 
            $start_time    = $this->input->post('start_time').':'.$this->input->post('start_time1');
            $end_time      = $this->input->post('end_time').':'.$this->input->post('end_time1');  
            $comment       = $this->input->post('message');
            $user_id           = $id;
            $leave_remaining=$this->Leave_model->checkLeaveremaining($id); //print_r($leave_remaining);exit();
                //edited by chinchu. Changed actual to annual holiday allowance
            $annual_holiday_allowance=$this->Leave_model->getActualholidayallowance($id); 
            $allowance=$annual_holiday_allowance[0]['annual_holliday_allowance'];
            if(count($leave_remaining)==0)
                {
                    $leaveDetails = array(
                    'user_id'=>$user_id,
                    'from_date'=>$from_date,
                    'to_date'=>$to_date,
                    'start_time'=>$start_time,
                    'end_time'=>$end_time,
                    'comment'=>$comment,
                    'unit_id'=>$userUnitDetails['values'][0]['unit_id'],
                    'status'=>0,
                    'creation_date'=>date('Y-m-d H:i:s'),
                    'days'=>$days,
                    'leave_remaining'=>$allowance,
                    'updation_date'=>date('Y-m-d H:i:s')
                    );
                   // $this->Leave_model->updateRemainingAllowanceByuser($user_id,$allowance);
                }
                else
                {
                    $leaveDetails = array(
                    'user_id'=>$user_id,
                    'from_date'=>$from_date,
                    'to_date'=>$to_date,
                    'start_time'=>$start_time,
                    'end_time'=>$end_time,
                    'comment'=>$comment,
                    'unit_id'=>$userUnitDetails['values'][0]['unit_id'],
                    'status'=>0,
                    'creation_date'=>date('Y-m-d H:i:s'),
                    'days'=>$days,  
                    'updation_date'=>date('Y-m-d H:i:s')
                    );
                }   

                $leaveEXistCheck = $this->Leave_model->losOfPay($from_date,$to_date,$start_time,$end_time,$user_id);
                //print_r($leaveEXistCheck);exit();
                if($leaveEXistCheck == 1)
                {
                    //print_r($leaveDetails);exit();
                    $save_details = $this->Leave_model->insertLeaveDetails($leaveDetails);
                    if($save_details)
                    {   

                        $title='Apply Leave - staffs';
                        InsertEditedData($leaveDetails,$title);

                        $begin     = $this->formatDate($this->input->post('start_date'));  //start date 
                        $end       = $this->formatDate($this->input->post('end_date')); //end date
                        // $date = new DateTime($to_date); 
                        // $date->modify('+1 day');
                        // $end=$date->format('Y-m-d');     
                        $Period = getDatesFromRange($begin,$end); //date array
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
                            $leaveyear = $this->checkLeaveyear($dt); //leave year 
                            $shift=$this->Leave_model->findshifthoursbyid($user_type,$dt); 
                            if(count($shift)==0) 
                             { //if there is no rota find default shift hours
                               // print "default";
                                   if (in_array($dt, $without_offday_holiday)) { 
                                    $calculated_hours=0;
                                        $targeted_hours=$this->Leave_model->findtargetedhoursbyuser($user_type); 
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
        */                                          $sum=$sum+$calculated_hours;
                                    }
                                     
                                    
                             }
                             else
                             { //shift hours
                        //         print "shift";
                        //         print "<pre>";
                        // print_r($dt);
                        // print_r($without_offday_holiday);
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
                                                'user_id'=>$user_type,
                                                'year'=>$leaveyear,
                                                'holiday_id'=>$save_details,
                                                'hours'=>$days,
                                                'calculated_hours'=>$sum,
                                                'creation_date'=>date('Y-m-d H:i:s'));
                        //print_r($data);exit();
                        $status=$this->Leave_model->InsertLeaveYear($data);
                        //exit();
                        $holidyTypeDetails['message']='Leave applied successfully.';
                        if($userUnitDetails['values'])
                        { 
                            // print_r($userUnitDetails['values'][0]['user_id']);exit();
                            $user_unit_id = $userUnitDetails['values'][0]['unit_id'];
                            $userUnitDetails['user_values'] = $this->Leave_model->getUsersFromUnit($userUnitDetails['values'][0]['user_id']);                       
                            if($userUnitDetails['user_values'])
                            {  
                                $to_email = $userUnitDetails['user_values'][0]['email'];
                                // $to_email = "chinchugopi89@gmail.com";
                                $supervisor_id = $userUnitDetails['user_values'][0]['unit_id'];    //print_r($supervisor_id);exit();   
                                $staff_name = $this->session->userdata('full_name');
                                $group_id = $userUnitDetails['user_values'][0]['group_id'];
                                $is_subunit = $this->Unit_model->checkIsSubUnit($user_unit_id);
                                $leave_apply_user_id = $this->session->userdata('user_id');
                                $supervisorDetails['values'] = getManagersSupervisors($group_id,$user_unit_id,$is_subunit,'',$leave_apply_user_id);
                                //print_r($supervisorDetails['values']);exit();

                                // $supervisorDetails['values'] = $this->Leave_model->getUnitManagerDetails($supervisor_id);
                                // print_r($supervisorDetails['values'][0]['email']);exit();
                                $rota_schedule['rota']=$this->Leave_model->findrotaschedule($user_id,$from_date,$to_date);
                                //print_r($rota_schedule['rota']);exit();
                                 //print_r($from_date);exit();

                                 $holiday_count=GetAllholidayDetailsbydateAndUnit($user_id,$user_unit_id,$from_date,$to_date);

                                 $old_date = explode('-', $from_date); 
                                 $new_data = $old_date[2].'/'.$old_date[1].'/'.$old_date[0];
                                 $start_date=$new_data;

                                 $old_date1 = explode('-', $to_date); 
                                 $new_data1 = $old_date1[2].'/'.$old_date1[1].'/'.$old_date1[0];
                                 $end_date=$new_data1;

                                 $already_approved_count='Number of staff having holidays between '.$start_date.' '.'and '.$end_date.' '.'='.' '.$holiday_count.'.';
                                 // print("<pre>".print_r($supervisorDetails['values'],true)."</pre>");exit();
                                 //print_r($start_date); print_r($end_date);exit();
                                if(count($supervisorDetails['values']) > 0)
                            {
                                for($i=0;$i<count($supervisorDetails['values']);$i++){
                                    $supervisoremail=$supervisorDetails['values'][$i]['email'];
                                    $supervisor_name = $supervisorDetails['values'][$i]['fname'].' '.$supervisorDetails['values'][$i]['lname'];
                                    $subject = $staff_name. ' has applied a leave from '.$start_date.' '.'to '.$end_date.'.';
                                    if($supervisorDetails['values'][$i]['mobile_number'])
                                    {    
                                        //sms integration
                                        // $message = $staff_name. ' has applied a leave from '.$start_date.' '.'to '.$end_date.'.';
                                        // // print_r($message);exit();
                                        // $this->load->model('AwsSnsModel');
                                        // $sender_id="SMHApplyLeave";
                                        // $result = $this->AwsSnsModel->SendSms($supervisorDetails['values'][$i]['mobile_number'], $sender_id, $message);
                                        //print_r($result);exit();
                                    }
                                    $site_title = 'St Matthews Healthcare - SMH Rota';
                                    $admin_email=getCompanydetails('from_email');
                                    $emailSettings = array();
                                    $emailSettings = array(
                                        'from' => $admin_email,
                                        'site_title' => $site_title,
                                        'site_url' => $this->config->item('base_url'),
                                        'to' => $supervisoremail,$admin_email,
                                        'type' => 'Staff-apply leave',
                                        'user_name' => $supervisor_name,
                                        'staff_name' => $staff_name,
                                        'data' => $rota_schedule['rota'],
                                        'staff_count' => $already_approved_count,
                                        'subject' => $subject,
                                        'content_title'=>'We are glad to have you!',
                                    );
                                    //print_r($emailSettings);exit();
                                    $this->load->library('parser');
                                    $htmlMessage = $this->parser->parse('emails/applyleave', $emailSettings, true);
                                    //die($htmlMessage);exit();
                                    $this->load->helper('mail');
                                    sendMail($emailSettings, $htmlMessage);
                                }
                                }
                            }                        
                        }
                        $allowance=$this->Leave_model->AnnualHolidayAllowance($this->session->userdata('user_id'));
                        $holidyTypeDetails['annual_allowance']=$allowance[0]['annual_holliday_allowance'];
                        $holidyTypeDetails['total_days']=$this->Leave_model->totalLeaveApplied($this->session->userdata('user_id')); 
                        $holidyTypeDetails['error']='';
                        $selected_year=date('Y', strtotime('+1 years'));
                        $holidyTypeDetails['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                        $holidyTypeDetails['user']=$this->User_model->findusers($this->session->userdata('user_id'));
                        $offday = $this->Workschedule_model->getUserworkschedule($this->session->userdata('user_id'));
                        array_push($user_ids,$this->session->userdata('user_id'));
                        $holidayDates = $this->Rotaschedule_model->getHolidayDates($user_ids);
                        $holidyTypeDetails['user_offday'] = json_encode($offday);
                        $holidyTypeDetails['holidayDates']=json_encode($holidayDates);
                        $this->session->set_flashdata('message', 'Leave applied successfully.'); 
                        redirect("staffs/Leave");

                    }
                    else
                    {
                        $allowance=$this->Leave_model->AnnualHolidayAllowance($this->session->userdata('user_id'));
                        $holidyTypeDetails['annual_allowance']=$allowance[0]['annual_holliday_allowance'];
                        $holidyTypeDetails['total_days']=$this->Leave_model->totalLeaveApplied($this->session->userdata('user_id'));  
                        $holidyTypeDetails['error']='Leave cannot be applied due to db error.';
                        $holidyTypeDetails['message']='';
                        $selected_year=date('Y', strtotime('+1 years'));
                        $holidyTypeDetails['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                        $holidyTypeDetails['user']=$this->User_model->findusers($this->session->userdata('user_id'));
                        $offday = $this->Workschedule_model->getUserworkschedule($this->session->userdata('user_id'));
                        array_push($user_ids,$this->session->userdata('user_id'));
                        $holidayDates = $this->Rotaschedule_model->getHolidayDates($user_ids);
                        $holidyTypeDetails['user_offday'] = json_encode($offday);
                        $holidyTypeDetails['holidayDates']=json_encode($holidayDates);
                        $this->load->view("staffs/leave/leaverequest",$holidyTypeDetails);
                    }
                }
                else
                {
                    $allowance=$this->Leave_model->AnnualHolidayAllowance($this->session->userdata('user_id'));
                    $holidyTypeDetails['annual_allowance']=$allowance[0]['annual_holliday_allowance'];
                    $holidyTypeDetails['total_days']=$this->Leave_model->totalLeaveApplied($this->session->userdata('user_id'));  
                    $holidyTypeDetails['error']=$leaveEXistCheck;
                    $selected_year=date('Y', strtotime('+1 years'));
                    $holidyTypeDetails['selected_date']=$selected_year.'/'.'08'.'/'.'31';
                    $holidyTypeDetails['user']=$this->User_model->findusers($this->session->userdata('user_id'));
                    $offday = $this->Workschedule_model->getUserworkschedule($this->session->userdata('user_id'));
                    array_push($user_ids,$this->session->userdata('user_id'));
                    $holidayDates = $this->Rotaschedule_model->getHolidayDates($user_ids);
                    $holidyTypeDetails['user_offday'] = json_encode($offday);
                    $holidyTypeDetails['holidayDates']=json_encode($holidayDates);
                    $holidyTypeDetails['message']='';
                    $this->load->view("staffs/leave/leaverequest",$holidyTypeDetails);
                } 
        }
        // }
        // }
        $jsfile['js_to_load'] = array('leave/staff_leave.js');
        $this->load->view('includes/staffs/footer',$jsfile);
    }


    function convert($hours, $minutes) {
    return $hours + round($minutes / 60, 2);
   }

   public function cancelleave()
    { 
        try{
            $device=$this->uri->segment(4);  
            $id=$this->uri->segment(5);  

                    $this->Leave_model->cancelleave($id);
                   
                        $this->session->set_flashdata('error',"0");
                        $this->session->set_flashdata('message', 'Leave canceled successfully.');
                            if($device=='mob')
                            { 
                              redirect('staffs/leave/listleaves');
                            }
                            else
                            { 
                               redirect('staffs/Leave');
                            }
                
            
        }
        catch (Exception $e) {
            print_r($e->getMessage());
        }
        
    }


}