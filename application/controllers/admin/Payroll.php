<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Payroll extends CI_Controller {


    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type')==2)
        {
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        $this->load->model('Payroll_model');
        $this->load->model('Reports_model');
        $this->load->model('User_model');
        $this->load->model('Shift_model'); 
        $this->load->model('Rotaschedule_model');
        $this->load->model('Training_model');
        $this->load->model('Rota_model');
        $this->load->helper('form');
    }

    public function payroll()
    {
    $this->auth->restrict('Admin.Report.Payroll');
    $header = array();
    $header['headername']=" : Timesheet Summary for the Period"." ".$this->input->post('start_date').' '. "to".' '.$this->input->post('end_date');
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $params=array();
    $jobe_roles=array();
    if($this->input->post('start_date')=='')
    { 
        $data['start_date']=date('d/m/Y');  
    }
    else
    {
       $data['start_date']=$this->input->post('start_date');  
    }
    if($this->input->post('end_date')=='')
    { 
        $data['end_date']=date('d/m/Y');  
    }
    else
    {
       $data['end_date']=$this->input->post('end_date');  
    }
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
    //print_r($this->input->post());
    $params['status']=$this->input->post('status');
    $params['jobrole']= $this->input->post("jobrole");
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    //print_r($params);exit();
    //$params['start_date']=$this->input->post('start_date');
    if($this->input->post('start_date')=='')
    {
       $date_daily=date('d/m/y');
    }
    else
    {
    	$date_daily=$this->input->post('start_date'); 
    }
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;  

     if($this->input->post('end_date')=='')
    {
       $date_daily1=date('d/m/y');
    }
    else
    {
    	$date_daily1=$this->input->post('end_date'); 
    }
 
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;
    $params['unit_id']=$this->input->post('unitdata'); 
    
	
	$startDate = new DateTime($new_data);
	$interval = $startDate->diff(new DateTime($new_data1));
	$weeks=ceil(($interval->y * 365 + $interval->days) / 7); 

    if($params['unit_id']=='')
    {
    	$data['proll']=[];
    }
    else
    {
	    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
	    { 
	      if($params['unit_id']==0)
	      	{
	      		$params['unit_id']=$this->session->userdata('unit_id');
	      	}
	      	else
	      	{ 
	      		$params['unit_id']=$this->input->post('unitdata');
	      	} 
	      $data['payroll'] = $this->Payroll_model->GetPayrollreport($params);    
	    }
	    else
	    {
	      $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
	      $data['payroll'] = $this->Payroll_model->GetPayrollreport($params);   
	    }  

		$date = strtotime($params['start_date']);
		$date = strtotime("+7 day", $date);
		$data['month']=date('m', $date); 
		$data['year']=date('Y', $date); 
	    foreach ($data['payroll'] as $payroll) {
	    	$user_info=$this->Payroll_model->finduserdetails($payroll['id']); 
            $daysum=$this->getCalculatedhours($payroll['id'],$params,'day');
            $daysundaysum=$this->getCalculatedhours($payroll['id'],$params,'sunday');
            $daysaturday=$this->getCalculatedhours($payroll['id'],$params,'saturday'); 
	    	$days=$this->Payroll_model->finddays($payroll['id'],$params);
	    	$daysun=$this->Payroll_model->findsundays($payroll['id'],$params);
	    	$daysat=$this->Payroll_model->findsaturdays($payroll['id'],$params);
            $breakhour=$this->getBreakhours($payroll['id'],$params); 
	    	$training=$this->Payroll_model->findtraininghourbyuser($payroll['id'],$params);
	    	$holiday=$this->Payroll_model->findAnnualLeave($payroll['id'],$params);
	    	$bank_holiday=$this->Payroll_model->findbankholiday($payroll['id'],$params);
	    	$additional_hours_day=$this->Payroll_model->findAdditionlHoursByDay($payroll['id'],$params);
	    	$additional_hours_sunday=$this->Payroll_model->findAdditionlHoursBysunDay($payroll['id'],$params);
	    	$additional_hours_saturday=$this->Payroll_model->findAdditionlHoursBysaturDay($payroll['id'],$params);
	    	$nightshift=$this->findNightshiftDetails($payroll['id'],$params); 
	    	$additional_hour_byweekday=$this->Payroll_model->findAdditionlHoursByWeekday($payroll['id'],$params);
	    	$additional_hour_byweekend=$this->Payroll_model->findAdditionlHoursByWeekend($payroll['id'],$params);
            $additional_hour_bySick=$this->Payroll_model->findAdditionlHoursBySick($payroll['id'],$params); 
	    	$comment=$this->Payroll_model->Getcomment($payroll['id'],$data['month'],$data['year']); 
            if($user_info[0]['payment_type']!='Agency Payment')
            {
	    	  $proll[$payroll['id']]=array('user_details'=>$user_info,'days'=>$daysum,'daysun'=>$daysundaysum,'daysat'=>$daysaturday,'training'=>$training,'holiday'=>$holiday,'bank_holiday'=>$bank_holiday,'additional'=>$additional_hours_day,'additional_sun'=>$additional_hours_sunday,'additional_sat'=>$additional_hours_saturday,'nightshift'=>$nightshift,'additional_hour_byweekday'=>$additional_hour_byweekday,'additional_hour_byweekend'=>$additional_hour_byweekend,'comment'=>$comment,'break'=>$breakhour,'additional_Sick'=>$additional_hour_bySick);
            }
	    } 
	    $data['proll']=$proll;
	} 
	// print_r("<pre>");
 //   print_r($data['proll']); exit();
    if($weeks){ $data['weeks']=$weeks; }
   
    $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
    $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
    $this->load->view('admin/reports/payroll',$data);
    $result['js_to_load'] = array('reports/payroll.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function getCalculatedhours($user_id,$params,$day)
    { //print_r($day);exit();
        $sum='00.00.00';
        $hour=$this->Payroll_model->calculatepayrolldata($user_id,$params,$day);
         //print_r($hour);exit();
        if(count($hour)>0) 

            foreach ($hour as $day) {
                
                 if($day['logtime']!='00:00')
                 {
                    if($day['fromtime']!='00:00:00')
                    {
                        $hour=findDifference($day['logtime'],$day['break']); 
                    }
                    else
                    {
                        $hour='00.00.00';
                    }
                  // if($totaldays)
                     //$totaldays = AddTimes($totaldays,$sum);
//print "TF=".$totaldays."<br>--<br>";
                   //$totaldays=$totaldays+settimeformat($sum);
                   // if($total=='00:00')
                   // {
                   //  $total=$sum;
                   // }
                   // else
                   // {
                   //  $totaldays=AddTimes($total,$sum);
                   // } 
                   
                 }
                 else
                 {  
                   $hour='00.00.00';
                 }

                //  print_r("sum-".$sum."<br>");
                // print_r("hour-".$hour."<br>");

                

                // $sum="00.00.00";
                // $hour="-00.15.00";


                if(strpos($sum, "-") !== false){
                $ssum = explode(".",$sum);
                $sum = $ssum[0].".-".$ssum[1].".".$ssum[2];
                }

                if(strpos($hour, "-") !== false){ //print "hii";
                $shour = explode(".",$hour);
                $hour = $shour[0].".-".$shour[1].".".$shour[2]; //print_r($hour);
                }
                
               

                $sum =$this->time_to_decimal($sum);
                $b =$this->time_to_decimal($hour);   
                $sum= $sum + $b; 
                $sum=$this->decimal_to_time($sum);
                //print_r($sum.'----');
                $sums=explode('.', $sum);
                $sum=$sums[0].'.'.abs($sums[1]).'.'.$sums[2];
             


            } 
            // print "<br>";
            // print_r($sum); 
            // //print_r($totaldays."<br>"); 
            //    exit();
         
        return $sum;

    }


    public function findNightshiftDetails($user_id,$params)
    { 
    	$end=date('Y-m-d', strtotime("+1 day", strtotime($params['end_date'])));
    	//
    	$tmp=array('start_date'=>$params['start_date'],'end_date'=>$end);
    	//print_r($tmp);exit();
       $shift=$this->Payroll_model->findNightShiftdetails($user_id,$tmp); 
       //print_r($shift);exit();
       if(!empty($shift))
       {    
       	    $weekdaynight=0;
       	    $weekendnight=0;

	        for($i=0;$i<count($shift);$i++)
	        {
	        	if($shift[$i]['shift_id']=='16' || $shift[$i+1]['shift_id']=='16')
	        	{
		        	if($shift[$i]['time_to']=='00:00:00' && !empty($shift[$i+1]['time_to']))
	                    {
	                        $shift[$i]['time_to']= $shift[$i+1]['time_to'];
	                        $date=$shift[$i]['date'];
	                    }
	                    else
	                    {
	                        $shift[$i]['time_to']= $shift[$i]['time_to'];
	                        $date=$shift[$i]['date'];
	                    }
	                    if($shift[$i]['time_from']!='00:00:00')
	                    {
	                    	$dates=getShiftdetails($shift[$i]['user_id'],$shift[$i]['date']);
                            if($shift[$i]['time_from'] < $dates[0]['start_time']) 
                            {  
                                $stime=$dates[0]['start_time'];
                            }else{  
                                $stime=$shift[$i]['time_from'];
                            }

                            if($shift[$i]['time_to']>$dates[0]['end_time']) 
                            {
                                $etime=$dates[0]['end_time'];
                            }else{
                                $etime=$shift[$i]['time_to'];
                            }
                            // print_r("stime-".$stime);
                            // print_r("etime-".$etime."<br>");

	      //               	$to_time = strtotime($etime);
							// $from_time = strtotime($stime);
							// $time_diff = $to_time - $from_time; 
							// $time=gmdate('H:i', $time_diff);
                            $time=findDifference($stime,$etime);
                            // print_r($time);
                            $time_split = explode(":",$time); //print_r($time_split);
                            $time_new = $time_split[0].".".$time_split[1];
                             
							$shift_hour=getShiftHours($shift[$i]['user_id'],$shift[$i]['date']);
		                    //print_r("<pre>");
		                    // print_r("time_from-".$shift[$i]['time_from']." "."time_to-".$shift[$i]['time_to']."<br>");
		                    // print_r($time."<br>".$shift[$i]['day']."<br>");
                        //print_r($shift[$i]['date']."<br>");
		                    //print_r($shift_hour);
		                    if($shift[$i]['time_to']=='00:00:00')
	                    	{
	                    		$day='00.00';
                                $break='00.00.00';
	                    		
	                    	}
	                    	else
	                    	{ 
                                $break=$this->Payroll_model->getBreakhoursbynightshift($shift[$i]['user_id'],$shift[$i]['date']);
	                    		$day=$time_new;
                               
	                    	}

                            
                             //print_r($day.'-'.$shift_hour);

	                    	if($day>$shift_hour)
	                    	{
	                    		$hour=$shift_hour.".00";
	                    	}
	                    	else
	                    	{
	                    		$hour=$day.".00";
	                    	}
                            

                             $sum=$hour;
                             $hour=$break;
 


                            if(strpos($sum, "-") !== false){  
                                $ssum = explode(".",$sum);
                                $sum = $ssum[0].":-".$ssum[1].":".$ssum[2];
                                }

                                if(strpos($hour, "-") !== false){
                                $shour = explode(":",$hour);
                                $hour = $shour[0].":-".$shour[1].":".$shour[2];
                                }

                            $sum = $this->time_to_decimal($sum); //print_r($sum);
                            $b = $this->time_to_decimal($hour); //print_r($b);exit();
                            $sum= $sum - $b;
                            $total=$this->decimal_to_time($sum);
                            // print_r("<br>");
                            // print_r($total);


	                    	if($shift[$i]['day']=='Su' || $shift[$i]['day']=='Sa')
	                    	{
	                    		$weekendnight=$weekendnight+$total;
	                    		 //print_r('Saturdy or Sunday-'.$weekendnight);

	                    	}
	                    	else
	                    	{
	                    		$weekdaynight=$weekdaynight+$total;
	                    		//print_r('Week days-'.$weekdaynight);
	                    	}

	                    	//print_r($weektimes);
	                    	
		                     
		                 

	                	}
	            }
	        }
           // exit();
	         $result=array('weekday'=>$weekdaynight,'weekendnight'=>$weekendnight);
             //print_r($result);exit();
	     
  		}
          return $result;
    }

function findDifference($firsttime,$lasttime)
  {
        $ftime=date('H:i',strtotime($firsttime)); 
        $ltime=date('H:i',strtotime($lasttime));  

        $startTime = new DateTime($ftime);  
        $endTime = new DateTime($ltime);  
        $duration = $startTime->diff($endTime); //$duration is a DateInterval object
        $time2=$duration->format("%H:%I:%S");
        return $time2;
  }

public function time_to_decimal($time) { 
$timeArr = explode('.', $time);

$decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
//print_r($decTime);exit();

return $decTime;
}

public function decimal_to_time($decimal) {

if(strpos($decimal, "-") !== false){ //print "hii";
$hours = ceil((int)$decimal / 60); 
$minutes = floor((int)$decimal % 60);
$seconds = $decimal - (int)$decimal; 
$seconds = round($seconds * 60); 
}
else{
$hours = floor((int)$decimal / 60);
$minutes = floor((int)$decimal % 60);
$seconds = $decimal - (int)$decimal; 
$seconds = round($seconds * 60); 
}



return str_pad($hours, 2, "0", STR_PAD_LEFT) . "." . str_pad($minutes, 2, "0", STR_PAD_LEFT) . "." . str_pad($seconds, 2, "0", STR_PAD_LEFT);

}

    public function getBreakhours($user_id,$params)
    {
        //print_r($user_id);exit();
        $hour=$this->Payroll_model->getBreakhours($user_id,$params);
        // print_r($hour);
        // exit();
        $day_unpaid=0;
        $sun_unpaid=0;
        $sat_unpaid=0;
        $nightday_unpaid=0;
        $nightend_unpaid=0;

        foreach ($hour as  $h) {
            if($h['logid']!='')
            {
                if($h['shift_category']==2)
                {
                     if($h['day']=='Su' || $h['day']=='Sa')
                     {
                        $nightend_unpaid=$nightend_unpaid+settimeformat($h['unpaid_break_hours']);
                     }
                     else
                     {
                        $nightday_unpaid=$nightday_unpaid+settimeformat($h['unpaid_break_hours']);
                     }

                }
                else
                {
                        if($h['day']=='Su')
                        { 
                           $sun_unpaid=$sun_unpaid+settimeformat($h['unpaid_break_hours']);
                        }
                        else if($h['day']=='Sa')
                        { 
                           $sat_unpaid=$sat_unpaid+settimeformat($h['unpaid_break_hours']);
                        }
                        else
                        {
                           $day_unpaid=$day_unpaid+settimeformat($h['unpaid_break_hours']);
                        }

                }
            }
            
           
        }
        
        $result=array('day_unpaid'=>$day_unpaid,'sat_unpaid'=>$sat_unpaid,'sun_unpaid'=>$sun_unpaid,'nightday_unpaid'=>$nightday_unpaid,'nightend_unpaid'=>$nightend_unpaid);
         //print_r($result);

        return $result;
         
    }


    public function insertcomments()
    {

    	$params=array(); 
        $params['comment']=$this->input->post('comment');
        $params['user_id']=$this->input->post('user_id');
        $params['month']=$this->input->post('month');
        $params['year']=$this->input->post('year'); 
        $params['creation_date']=date('Y-m-d H:i:s');
        $params['created_by']=$this->session->userdata('user_id');
        $status=$this->Payroll_model->CheckforUserPayrollComment($params);
        //print_r($status);exit();
        if($status==0)
        { //print_r('hiii');exit();
        	//insert
        	 $result=$this->Payroll_model->insertcomments($params);
        }
        else
        { //print_r('helo');exit();
        	//updattion
        	 $result=$this->Payroll_model->updatecomments($params,$status);
        }

        header('Content-Type: application/json');
        echo json_encode(array('status' => $result));
        exit();
    } 

}
?>