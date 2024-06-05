<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function get_permission_status($id,$user_type)
{
  $status = 1;
  $CI =& get_instance();
  $CI->db->select('status');
  $CI->db->from('employee_permissions');
  $CI->db->where('id', $id);
  $result = $CI->db->get();
  $result = $result->result_array();
  if($user_type != 1){
    if($result[0]['status'] == 1){
      $status = 0;
    }
  }
  return $status;
}
function get_hours($user_shifts){
    $sum = 0;
    $suminit = 0;
    $sum2 = 0;
    $unpaid_breakhours = 0;
    $totalH = 0;
    $totalM = 0;
    $newSum = 0;
    $newSum2 = 0;
    $t_break_hour = 0;
    $t_break_minutes = 0;
    $total_break_hours = 0;
    $total_break_hours2 = 0;
    $overtime = 0;
    for ($i = 0; $i < count($user_shifts); $i++) {
        $suminit = $user_shifts[$i]['targeted_hours'];
        $unpaid_breakhours = $user_shifts[$i]['unpaid_break_hours'];

        $sumtime = explode(':', strval($suminit));
        $unpaid_breakhours_sum = explode(':', strval($unpaid_breakhours));

        $totalH += intval($sumtime[0]);
        if (isset($sumtime[1])) {
            $totalM += intval($sumtime[1]);
        }
        $t_break_hour += intval($unpaid_breakhours_sum[0]);
        if (isset($unpaid_breakhours_sum[1])) {
            $t_break_minutes += intval($unpaid_breakhours_sum[1]);
        }

        // If the minutes exceed 60
        if ($totalM >= 60) {
            // Divide minutes by 60 and add result to hours
            $totalH += floor($totalM / 60);
            // Add remainder of totalM / 60 to minutes
            $totalM = floor($totalM % 60);
        }
        
        if ($t_break_minutes >= 60) {
            // Divide minutes by 60 and add result to hours
            $t_break_hour += floor($t_break_minutes / 60);
            // Add remainder of totalM / 60 to minutes
            $t_break_minutes = floor($t_break_minutes % 60);
        }

        $sum = $totalH . "." . $totalM;
        $sum2 = floatval($sum);
        $newSum = ($totalH * 60 + $totalM) / 60;
        $newSum2 = floatval($newSum);
        $total_break_hours = ($t_break_hour * 60 + $t_break_minutes) / 60;
        $total_break_hours2 = floatval($total_break_hours);
        $overtime = $newSum2 - $total_break_hours2;
    }

    if($newSum2==0){
        $user_hours = $newSum2;
    }
    else
    {
        $user_hours = $newSum2 - $total_break_hours2;
    }
    // Check if the value has a decimal part
    if (floor($user_hours) == $user_hours && $user_hours != 0) {
        // Add .0 to the value
        $formatted_value = $user_hours . ".0";
    } else {
        // Keep the original value
        $formatted_value = $user_hours;
    }
    return $formatted_value;
}
function get_group_permission_status($group_id,$name)
{
  $status = 0;
  $CI =& get_instance();
  $CI->db->select('id');
  $CI->db->from('permissions');
  $CI->db->where('name', $name);
  $result = $CI->db->get();
  $result = $result->result_array();
  if(count($result) > 0){
    $CI->db->select('*');
    $CI->db->from('group_permissions');
    $CI->db->where('group_id', $group_id);
    $CI->db->where('permission_id', $result[0]['id']);
    $g_result = $CI->db->get();
    $g_result = $g_result->result_array();
    if(count($g_result) > 0){
      $status = 1;
    }
  }
  return $status;
}
function getShiftIdByUser($user_id,$date)
{ 
  $shift_id=array('3','4','93');  
    
     $result = array();
      $CI =& get_instance();
      $CI->db->select('*');
      $CI->db->from('rota_schedule');  
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('date', $date); 

      $CI->db->where_in('shift_id',$shift_id);
      //$CI->db->order_by('history_rota_schedule.updation_date', 'desc');
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $result = $result->result_array(); 
      if(count($result)>0)
      {
            $results='true';
      } 
      else
      {
            $results='false';
      }
      return $results;

}


function getParentId($unit)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('parent_unit');
      $CI->db->from('unit');
      $CI->db->where('id', $unit);
      //$CI->db->order_by('history_rota_schedule.updation_date', 'desc');
      $result = $CI->db->get();
      // print $CI->db->last_query();
      // exit();
      $result = $result->result_array(); //print_r($result[0]['parent_unit']);exit();
      if(count($result)<0)
      {
      $results=0;
      }
      else
      {
      $results=$result[0]['parent_unit'];
      }
      return $results;
}

function Checkparent($user_id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('id');
      $CI->db->from('unit');
      $CI->db->where('parent_unit',$user_id);
      $result = $CI->db->get();
      //echo $CI->db->last_query();exit;
      $result = $result->result_array();
      if(empty($result))
      {
      $results=0;
      }
      else
      {
      $results=$user_id;
      }
      return $results;

}

function getTrainingTimes($date,$user_id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('master_training.time_from,master_training.time_to');
      $CI->db->from('master_training'); 
      $CI->db->join('training_staff', 'training_staff.training_id = master_training.id');
      $CI->db->group_start();
      $CI->db->where('master_training.date_from <=', $date); 
      $CI->db->where('master_training.date_to >=', $date);
      $CI->db->group_end();
      $CI->db->where('training_staff.user_id', $user_id);  
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $result = $result->result_array(); 
      //print_r($result);exit();
      if(count($result)>0)
      {
            $results=$result[0];
      } 
      else
      {
            $results='';
      }
      return $results;
}

function getOriginalShiftNAme($user_id,$date)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('shift_name as original_shift');
      $CI->db->from('history_rota_schedule'); 
      $CI->db->join('master_shift', 'master_shift.id = history_rota_schedule.shift_id');
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('date', $date); 
      $CI->db->order_by('history_rota_schedule.updation_date', 'desc');
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $result = $result->result_array(); 
      if(count($result)>0)
      {
            $results=$result[0]['original_shift'];
      } 
      else
      {
            $results='';
      }
      return $results;
}

function getshiftcheckinDetails($date,$user_id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('id,time_from,date');
      $CI->db->from('time_log'); 
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('date', $date); 
      $CI->db->where('status', 1); 
      $CI->db->where('time_from !=', '00:00:00');  
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array(); 
      return $result;
}

function Checkbankholiday($date)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('*');
      $CI->db->from('bank_holiday'); 
      $CI->db->where('date', $date);  
      $CI->db->where('status', 1);  
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $result = $result->result_array(); 
      if(empty($result))
      {
        $results='false';
      }
      else
      {
        $results='true';
      }
      return $results;
}

function checkbuttonaccess($message,$user_group)
{
      $CI =& get_instance();
      $CI->db->select('*');
      $CI->db->from('group_permissions');
      $CI->db->join('permissions', 'permissions.id = group_permissions.permission_id');
      $CI->db->where('permissions.name', $message); 
      $CI->db->where('group_permissions.group_id', $user_group);
      $result = $CI->db->get();
       // print $CI->db->last_query();
       //   exit();

      $result = $result->result_array();
      if(count($result)>0)
      {
            $status='true';
      } 
      else
      {
            $status='false';
      }
      return $status;
}

function getNightshiftChekoutDetails2($date,$user_id)
{

     $tomorrow = date('Y-m-d',strtotime($date . "+1 days"));  
      $result = array();
      $CI =& get_instance();
      $CI->db->select('id,time_to,date');
      $CI->db->from('time_log'); 
      $CI->db->where('user_id', $user_id); 
      //$CI->db->group_start();
      //$CI->db->where('date', $date);
      $CI->db->where('date', $tomorrow);
      $CI->db->or_where('date', $date);
      //$CI->db->group_end();
      $CI->db->where('time_to !=', '00:00:00');     
      $CI->db->order_by('date',"asc");
      $result = $CI->db->get();
       // print $CI->db->last_query();
       //   exit();

      $result = $result->result_array(); 
      return $result;
}
function getNightshiftcheckinDetails($date,$user_id)
{
    
    $tomorrow = date('Y-m-d',strtotime($date . "+1 days"));
    $result = array();
    $CI =& get_instance();
    
     $query = "  SELECT `id`, `time_from`, `date`,user_id FROM `time_log` WHERE 
                (`date` = '".$tomorrow."' and time_from!='00:00:00' and time_from <='07:30:00') and `user_id` = '".$user_id."' and `status` = '1' 
                UNION
                SELECT `id`, `time_from`, `date`,user_id FROM `time_log` WHERE   
                (`date` = '".$date."' and   time_from >='18:00:00'  )  and `user_id` = '".$user_id."' and `status` = '1'  ORDER BY `id` ASC, `date` ASC";
   $data = $CI->db->query($query);
    
   $result = $data->result_array(); 
 
   // print $CI->db->last_query();
   // exit();
 
    return $result;
}

function getNightshiftChekoutDetails($date,$user_id)
{
    
    $tomorrow = date('Y-m-d',strtotime($date . "+1 days"));
    $result = array();
    $CI =& get_instance();
    
     $query = "  SELECT `id`, `time_to`, `date`,user_id FROM `time_log` WHERE 
                (`date` = '".$tomorrow."' and time_to!='00:00:00') and `user_id` = '".$user_id."' and `status` = '0' 
                UNION
                SELECT `id`, `time_to`, `date`,user_id FROM `time_log` WHERE   
                (`date` = '".$date."' and   time_to >='18:00:00'  )  and `user_id` = '".$user_id."' and `status` = '0'  ORDER BY  `id` ASC,`date` ASC";
   $data = $CI->db->query($query);
    
   $result = $data->result_array(); 
 
   // print $CI->db->last_query();
   // exit();
 
    return $result;
}

function getDayshiftChekoutDetails($date,$user_id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('id,time_to,date');
      $CI->db->from('time_log'); 
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('date', $date);  
      $CI->db->where('status', 0);
      $CI->db->where('time_to !=', '00:00:00');   
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array(); 
      return $result;
}
function getLeaveFormat($time)  //helper to change time if minute greater than 60
{
   list($hour, $minute) = explode('.', $time);
   if($minute==''){$minute='00';}
    $minutes += $hour * 60;
    $minutes += $minute;
    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;
    $arr = str_split($minutes);
    $first=reset($arr); // 2 
    $last=end($arr);
    $abc=$first.''.$last; 
    $result=$hours.':'.$abc;
    return $result;
}
function getshiftcategoryofUser($user_id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('master_shift.shift_category');
      $CI->db->from('users');
      $CI->db->join('master_shift', 'master_shift.id = users.default_shift');  
      $CI->db->where('users.id', $user_id);   
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit(); 
      $results = $result->result_array(); 
      return $results;
}

function getActualShift($user_id,$date)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('master_shift.shift_name,rota_schedule.shift_id,rota_schedule.date');
      $CI->db->from('rota_schedule');
      $CI->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');  
      $CI->db->where('rota_schedule.user_id', $user_id);  
      $CI->db->where('rota_schedule.date', $date); 
      $CI->db->order_by('master_shift.shift_type', 'desc');
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit(); 
      $results = $result->result_array(); 
      if(count($results)>0)
      {
         $result1=$results[0]['shift_name'];
      }
      else
      {
          $result1='';
      }
      
      return $result1;

}

function getCheckoutDetails($current,$newtime)
{  

  $time1 = strtotime($current);
  $time2 = strtotime($newtime);
  $difference = round(abs($time2 - $time1) / 3600,2);
  return $difference;


}

function InsertEditedData($data,$title)
{

      $result = array();
      $CI =& get_instance();

        $abc=implode(',', $data);
        $data_edited=array(
             'description'=>$abc,
             'type'=>$title,
             'created_by'=>$CI->session->userdata('user_id'),
             'creation_date'=>date('Y-m-d H:i:s'),
        );

        $CI->db->insert("History_post_data", $data_edited);

         //print $CI->db->last_query();
      //exit(); 
}

function getCountofLeave($week,$unit,$jobrole)
{   
   for ($i=0; $i<count($week); $i++) { 

    $date_array = getStartAndEndDates($week[$i]);
    $hour=getWeeklyLeavesbyStartandEndDate($date_array['start_date'],$date_array['end_date'],$unit,$jobrole); 
    $count[]=array('count'=>count($hour));
   } 

return $count;
}

function findnextyear($current)
{ 
  $hms = explode("-", $current);
  $new_year1=$hms[0]+1;
  $new_year2=$hms[1]+1;
  $new_year=$new_year1.'-'.$new_year2;
  return $new_year;
}

function getTotalLeavehours($user_id,$year,$annual)
{  
  //user_id='1154';
  //$year='2020-2021';
  //$annual='47.77';
  $result = array(); 
  $new_result=array();
  $CI =& get_instance();
  $CI ->load->model('Reports_model');
  $result=$CI->Reports_model->getHoursWorkedbyUser($user_id,$year); //print_r($result); print '<br>';
  $sum='00.00';
  $remaining='00.00';
  if($result!=0)
  {  
      foreach ($result as $hour) {
      $abc=str_replace(':', '.', $hour['hours']);
      $sum=$sum+settimeformat(getPayrollformatannualleaveplanner($abc));
      }
      $remaining=$annual-$sum;  
  }
  else
  {  

    $sum=$sum;
    $remaining=$annual;
  }

  $new_result=array('total'=>number_format($sum,2),'balance'=>number_format($remaining,2)); //print_r($new_result);exit();
  return $new_result;

}
function getWeeklyLeavesbyStartandEndDate($start_date,$end_date,$unit,$jobrole)
{
  $result = array(); 
  $CI =& get_instance();
  $CI ->load->model('Reports_model');
  $result=$CI->Reports_model->findholiday($start_date,$end_date,$unit,$jobrole);
  // print_r($result);
  // exit();
  return $result;
}

function getRealUnitname($unit_id)
{
     $result = array();
      $CI =& get_instance();
      $CI->db->select('unit.unit_name');
      $CI->db->from('unit'); 
      $CI->db->where('id', $unit_id);    
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $results = $result->result_array();  
      if(count($results)>0)
      {
        $result1=$results[0]['unit_name'];
      }
      else
      {
        $result1='';
      }
      return $result1;
}
function GetAllholidayDetailsbydateAndUnit($user_id,$unit_id,$from_date,$to_date)
{
  $params=array();
  $CI =& get_instance();
  $CI ->load->model('Leave_model');
  $params['from_date']=$from_date;
  $params['to_date']=$to_date;
  $params['unit_id']=$unit_id;
  $params['user_id']=$user_id;
  $params['status']=1;
  $all_dates = findDatesBtwnDates($params['from_date'],$params['to_date']);
 // print_r($all_dates);exit();
  $result=$CI->Leave_model->GetAlreadyApprovedLeaves($params,$all_dates);
  if(count($result>0))
  {
     return count($result);
  }
  else
  {
     return 0;
  }
  
}
 function findDatesBtwnDates($start_date,$end_date){ 
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
function getWeeklyLeaves($user_id,$start_date,$end_date)
{
  $result = array(); 
  $CI =& get_instance();
  $CI ->load->model('Reports_model');
  $result=$CI->Reports_model->getUserWeeklyLeaves($user_id,$start_date,$end_date); 
  $week=getWeeknumber($start_date); //print_r($week); print '<br>';
  if(count($result) > 0)
  {  
    $results=getUserWeeklyLeaves($result,$week); 
    $result_hour=$results;
  }
  else
  {
    $result_hour=$result;
  } 
 // print_r($result_hour);//exit();
  return  $result_hour;
}

function getHoursFromWeekNumber($leaves,$week_number,$os){    
  $hours  = '';
  // $rejected = '';
  // $pending = '';
  // $status = '';
  // $pending_status='';
  // $reject_status=''; 
  $div ="";
  $count=0;
  foreach ($leaves as $leave) { 
    if($leave[0]['week'] == $week_number){
      if($leave[0]['hour_stat']==1)
      {
         $hour_1=number_format($leave[0]['hour'],2); 
         $hours=settimeformat(getPayrollformatannualleaveplanner($hour_1));
      }
      else
      {
          $hours=number_format($leave[0]['hour'],2); 
      }
      
      if($leave[0]['status']==1)
      {
        if($hours!='0.00')
        { 
          if($os=='Linux')
          {
            $div1 = '<div style="background-color: green;text-align: center;padding-top: 5px;width:100%;" class="green" ><div style="display:none">A:</div>'.$hours.'</div>';
          }
          else
          {
            $div1 = '<div style="background-color: green;text-align: center;padding-top: 5px;width:100%;" class="green" ><div style="display:none"></div>'.$hours.'</div>';
          }
          $count=$count+1;
          $week=$leave[0]['week'];
        }
      }
      elseif($leave[0]['status']==0)
      {
        if($hours!='0.00')
        {
          if($os=='Linux')
          {
              $div2 .= '<div style="background-color: orange;text-align: center;padding-top: 5px;width:100%;" class="orange" >&nbsp<div style="display:none">P:</div>'.$hours.'</div>';
          }
          else
          {
              $div2 .= '<div style="background-color: orange;text-align: center;padding-top: 5px;width:100%;" class="orange" ><div style="display:none"></div>'.$hours.'</div>';
          }
          $count=0;
          $week=$leave[0]['week'];
        }
      }
      // else
      // {
      //   if($hours!='0.00')
      //   {
      //   $div3 .= '<div style="background-color: lightblue;width:100%;height:100%;" >'.$hours.'</div>';
      //   }
      // }

      $div=$div1.$div2;
      
    } 
  }  

  $result=array(
    'div' => $div,
    'count' => $count,
    'week' => $week
  );
 // print_r($result); 
  return $result;
}

function getUserWeeklyLeaves($result,$week)
{  
  $week_num=$week;  
  foreach ($result as $value) {   
    $total_hours=$value['days']; 
    $tot=str_replace(':', '.', $total_hours);   //print_r($tot);

    $abc=explode('.',$tot); //(if tot ==39.00 then change to 38.60)
    if($abc[1]=='00')
    {
      $status_hour=2;
    }
    else
    {
      $status_hour=1;
    }


    $beginday = $value['from_date'];
    $lastday  = $value['to_date'];  
    $nr_work_days = getWorkingDays($beginday, $lastday);  //count working days
    //print "days-"; print_r($nr_work_days); print '<br>';
    if($nr_work_days==0)
    {
      $days_hour=$tot;
    }
    else
    {
      $days_hour=$tot/$nr_work_days;
    } //find hour per day 

    //print "dayhour-"; print_r($days_hour); print '<br>';
    $dates=GetDatesBetweenDates($beginday,$lastday);  //date between two date 
    $dayname = date('D', strtotime($dates[0])); 
    if(count($dates)>1  || $dayname!='Sun')
    {  //count of dates greater than 0ne and day not sunday
        foreach ($dates as $date_new) {  //print_r($date_new);
        $weekend=isWeekend($date_new);  //checking weekend or not
        //print_r($weekend); print_r('<br>');
        if($weekend!='true')
        {
          $week=getWeeksnumber($date_new);  //week number 
          //print_r($week);
          if(strlen($week)==1)
          {
            $week='0'.$week;
          }
          else
          {
            $week=$week;
          }
          $cart[]= array('week'=>$week,'status'=>$value['status'],'hour'=>$days_hour,'hour_stat'=>$status_hour);
        }
        else
        { //print 'hello';
          $week=getWeeksnumbernewSunday($date_new);  //week number 
          $cart[]= array('week'=>$week,'status'=>$value['status'],'hour'=>$days_hour,'hour_stat'=>$status_hour);
        }
      }  
    }
    else
    {  
         $date_new=$dates[0];
         $week=getWeeknumber($dates[0]); //week number 
         $cart[]= array('week'=>$week,'status'=>$value['status'],'hour'=>$days_hour,'hour_stat'=>$status_hour);
    }
     
  } 
  //print_r($cart);
  //print_r('<br>');
  $new_array = array();
  $new_array_reject=array();
  $new_array_pending=array();
  $new_array_canceled=array();
  $week_groups = group_by("week", $cart);   
  foreach ($week_groups as $week_group) {   //print_r($week_group); print_r($week_group[0]['hour_stat']); print '<br>';
    $leave_hours = 0;
    $leave_hours_rejected=0;
    $leave_hours_pending=0; 
    $leave_hours_canceled=0;   
    $hour_stat=$week_group[0]['hour_stat'];
    if($week_num==$week_group[0]['week'])
    {
      foreach ($week_group as $group) {  
     
          if($group['status']==1)
          {
            $leave_hours = $leave_hours + $group['hour'];
          }
          else if($group['status']==0)
          {
            $leave_hours_pending = $leave_hours_pending + $group['hour'];
          }
          else if($group['status']==3)
          {
            $leave_hours_canceled = $leave_hours_canceled + $group['hour'];
          }
          else
          {
            $leave_hours_rejected = $leave_hours_rejected + $group['hour'];
          }
      
      }  
      $new_array[]= array('week'=>$group['week'],'status'=>1,'hour'=>$leave_hours,'hour_stat'=>$hour_stat);
      $new_array_reject[]= array('week'=>$group['week'],'status'=>2,'hour'=>$leave_hours_rejected,'hour_stat'=>$hour_stat);
      $new_array_pending[]= array('week'=>$group['week'],'status'=>0,'hour'=>$leave_hours_pending,'hour_stat'=>$hour_stat);
      $new_array_canceled[]= array('week'=>$group['week'],'status'=>3,'hour'=>$leave_hours_canceled,'hour_stat'=>$hour_stat);
      $result=array(
        'approved'=>$new_array,
        'rejected'=>$new_array_reject,
        'pending'=>$new_array_pending,
        'canceled'=>$new_array_canceled
      );
     
    }

  }
  return $result;

}
/**
 * Function that groups an array of associative arrays by some key.
 * 
 * @param {String} $key Property to sort by.
 * @param {Array} $data Array that stores multiple associative arrays.
 */
function group_by($key, $data) {
  $result = array();
  foreach($data as $val) {
    if(array_key_exists($key, $val)){
      $result[$val[$key]][] = $val;
    }else{
      $result[""][] = $val;
    }
  }
  return $result;
}

function getWeeksnumbernewSunday($ddate){    
$date = new DateTime($ddate);
$week = $date->format("W");  
$week=$week; 

return $week;
}


function getWeeksnumber($ddate){   //print_r($ddate); print '<br>';
$start_month=date('Y-m-01', strtotime($ddate)); //print_r($start_month); print '<br>';
$dayname = date('D', strtotime($start_month)); //print_r($dayname); print '<br>';
$date = new DateTime($ddate);
$week = $date->format("W");  //print "---";print_r($week);
  
  if($dayname='Sun')
  {
    $week=$week-1;
  }
  else
  {
    $week=$week;
  }
  if($week==0)
  { //

    $custom_date = strtotime($ddate);
    $end_date = strtotime("-7 day", $custom_date);
    $new_date= date('Y-m-d', $end_date);
    //$new_date=date("Y-m-d", strtotime("-7 days", $ddate)); print_r($new_date); print '<br>';
    $date = new DateTime($new_date); //print_r($date); print '<br>';
    $week = $date->format("W"); //print_r($week); print '<br>';
  }
  else
  {
    $week=$week;
  }
  //print_r("new"); print_r($week); print '<br>';
return $week;
}

function getWeeknumber($ddate){   
$start_month=date('Y-m-01', strtotime($ddate));
$dayname = date('D', strtotime($start_month)); //print_r($dayname);
$date = new DateTime($ddate);
$week = $date->format("W"); //print_r($week);
// if($dayname!='Sun')
// {
//   $week=$week-1;
// }
// else
// {
  $week=$week;
// } 
return $week;
}

function getWeeknumber1($ddate){   
$start_month=date('Y-m-01', strtotime($ddate));
$dayname = date('D', strtotime($start_month));
$date = new DateTime($ddate);
$week = $date->format("W");
/*if($dayname!='Sun')
{
  $week=$week-1;
}
else
{
  $week=$week;
} */
return $week;
}

function isWeekend($date) { 
    $weekDay = date('w', strtotime($date));  
    if($weekDay == 0)
    {
      $status='true';
    }
    else
    {
      $status='false';
    }
    return $status;
}

function GetDatesBetweenDates($start, $end, $format = 'Y-m-d'){
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


function getWorkingDays($startDate, $endDate)
{ 
  $new_end=explode('-',$endDate);

  $end_month=$new_end[1];
  $end_date=$new_end[2];


            $begin = strtotime($startDate);  //print_r($startDate);print '<br>';
            $end   = strtotime($endDate); //print_r($end);print '<br>';
            if ($begin > $end)
            {
                echo "startdate is in the future! <br />";
                return 0;
            } 
            else 
            {
                $no_days  = 0;
                $weekends = 0;
                while ($begin <= $end) { 
                    $no_days++; // no of days in the given interval
                    $what_day = date("N", $begin);
                    if ($what_day > 5) { // 6 and 7 are weekend days
                        $weekends++;
                    };
                    $begin += 86400; // +1 day
                };
                //$working_days = $no_days - $weekends; 
                if($end_month=='08' && $end_date=='31')
                { // month july and date 31 add count +1 
                    $working_days = $no_days+1;
                }
                else
                {
                   $working_days = $no_days;
                }
               
                //print_r($working_days); 
                return $working_days;
            }
}

function getShiftHours($user_id,$date)
{ 
      $result = array();
      $CI =& get_instance();
      $CI->db->select('shift_hours');
      $CI->db->from('rota_schedule'); 
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('date', $date);   
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array();  
     // print_r(count($result));
      if(count($result)==0)
      {
        $results='00:00';
      }
      else
      { 
        $results=$result[0]['shift_hours'];
      } 
       $hours = str_replace(':', '.', $results);
      return $hours;

}

function getYear($date)
{
  $hms = explode("-", $date);
  $year1 = substr( $hms[0], -2);
  $year2 = substr( $hms[1], -2);
  $new_date=$year1.'-'.$year2;
  return $new_date;
}

function checkLeaveyear($date){  
        $time=strtotime($date);
        $month=date("m",$time);// print_r("month".' '.$month); print "<br>";
        $year=date("Y",$time);  //print_r("year".' '.$year); print "<br>";
        
        if ($month > 8 && $year>=date('Y'))
        {// print_r('hiii');exit();
            $year = date('Y')."-".(date('Y') +1) ;
        }
        else
        { //print_r('hello');exit();
            if($year>date('Y'))
            {
                $year = ($year-1)."-".$year;
            }
            else
            {
                $year = (date('Y')-1)."-".date('Y');
            }
        } 

         $new= explode("-", $year); 
         $start=$new[0]-5;
         $end=$new[1]-5;
         $latest_year=$start.'-'.$end; 
         $cart = array(); 
         

         for ($i = 0; $i < 10; $i++) {
          $splited_year= explode("-", $latest_year); 
          $first_year=$splited_year[0]+1;
          $second_year=$splited_year[1]+1;
          $new_year=$first_year.'-'.$second_year;
          array_push($cart,$new_year);
          $latest_year=$new_year;
}
 
        return $cart;
    }
    

function getLeaves($user_id,$year)
{  
      $result = array();
      $CI =& get_instance();
      $CI->db->select('holiday_applied.hours,users.annual_holliday_allowance,users.actual_holiday_allowance,holiday_applied.year');
      $CI->db->from('holiday_applied');
      $CI->db->join('holliday', 'holliday.id = holiday_applied.holiday_id'); 
      $CI->db->join('users', 'users.id = holliday.user_id'); 
      $CI->db->where('holliday.user_id', $user_id); 
      $CI->db->where('holliday.status', 1);
      $CI->db->where('holiday_applied.year', $year);    
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $results = $result->result_array();  
      if(empty($results))
      {
          $CI->db->select('users.annual_holliday_allowance,users.actual_holiday_allowance');
          $CI->db->from('users'); 
          $CI->db->where('id', $user_id);  
          $result = $CI->db->get(); 
          // print $CI->db->last_query();
          //   exit();
          $result = $result->result_array(); 
          $current_year=date('Y');
          $nxtyear = date("Y")+1;
          $CYear = $current_year."-".$nxtyear; 
          if(empty($result))
          {
            $leaves='00.00';
          }
          else
          {
            if($CYear==$year)
            {
             $leaves=$result[0]['annual_holliday_allowance'];
            }
            else
            {
             $leaves=$result[0]['actual_holiday_allowance'];
            }
          } 
          
      }
      else
      {   
        $sum_days='00.00';
        $remaining_leave='00.00';
        foreach ($results as $value) { 

          $applieddays=str_replace(':', '.', $value['hours']);   
          $applieddays1=number_format(getPayrollformat(number_format($applieddays,2),2),2);
          $sum_days=$sum_days+$applieddays1; 
          $annual=$value['annual_holliday_allowance'];
          $actual=$value['actual_holiday_allowance'];
           # code...
         }
         $sum=number_format((float)$sum_days, 2, '.', '');
         $current_year=date('Y');
         $nxtyear = date("Y")+1;
         $CYear = $current_year."-".$nxtyear;  
         if($CYear==$year)
         {
           $leaves=$annual-$sum;
         }
         else
         {
           $leaves=$actual-$sum;
         } 
      }  

      $sum_leaves=number_format((float)$leaves, 2, '.', '');
      //print_r($sum_leaves);exit();
      return $sum_leaves;

}

function getShiftdetailsnewtransfer($user_id,$date,$unit_id,$parent,$at_unit)
{ //print_r($at_unit);
      $result = array();
      $CI =& get_instance();
      if($at_unit!=0)
      {
      $CI->db->select('id');
      $CI->db->from('unit'); 
      $CI->db->where('parent_unit', $at_unit);
      $query = $CI->db->get();
      $result = $query->result_array();
      }

      //print_r($result);exit();

     
      $CI->db->select('
        master_shift.start_time,
        master_shift.end_time,
        master_shift.unpaid_break_hours,
        master_shift.shift_name,
        master_shift.shift_category,
        unit.parent_unit,
        rota_schedule.from_unit,
        rota_schedule.unit_id as at_unit,
        rota_schedule.user_id,
        rota_schedule.date,
        master_shift.targeted_hours'
      );
      $CI->db->from('rota_schedule');
      $CI->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $CI->db->join('unit', 'unit.id = rota_schedule.unit_id');
      if($unit_id==0 || $unit_id=='')
      {
           $CI->db->where('rota_schedule.from_unit !=', NULL); 
      }
      else
      {
          $CI->db->where('rota_schedule.from_unit', $unit_id); 
      }
    
      if($at_unit!=0)
      {
          if(!empty($result))
          {
            $CI->db->group_start();
            $CI->db->where('rota_schedule.unit_id', $at_unit);
            foreach ($result as $value) {
               $CI->db->or_where('rota_schedule.unit_id',$value['id']);
            }
            $CI->db->group_end();
          }
          else
          {
            $CI->db->where('rota_schedule.unit_id', $at_unit);
          }
      }
      $CI->db->where('rota_schedule.user_id', $user_id); 
      $CI->db->where('rota_schedule.date', $date);
      $CI->db->where('master_shift.shift_category !=', NULL);
      if($parent!=0)
      {
         $CI->db->where('unit.parent_unit !=', $parent); 
      }
      $CI->db->order_by('master_shift.start_time', 'desc');  
      $result = $CI->db->get();
      //print $CI->db->last_query();
      // exit();
      $result = $result->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');

      if($result[0]=='')
      {
        return 0;
      }
      else
      {
        return $result[0];
      }
      

}

function getShiftdetailsnew($user_id,$date)
{ 
      $result = array();
      $CI =& get_instance();
      $CI->db->select('master_shift.start_time,master_shift.end_time,master_shift.unpaid_break_hours,master_shift.shift_name,master_shift.shift_category,master_shift.targeted_hours,master_shift.total_targeted_hours');
      $CI->db->from('rota_schedule');
      $CI->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $CI->db->where('rota_schedule.user_id', $user_id); 
      $CI->db->where('rota_schedule.date', $date); 
      $CI->db->order_by('master_shift.start_time', desc);  
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $result = $result->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result[0];

}

function getShiftdetails($user_id,$date)
{ 
      $result = array();
      $CI =& get_instance();
      $CI->db->select('master_shift.start_time,master_shift.end_time,master_shift.unpaid_break_hours,master_shift.shift_name,master_shift.shift_category');
      $CI->db->from('rota_schedule');
      $CI->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $CI->db->where('rota_schedule.user_id', $user_id); 
      $CI->db->where('rota_schedule.date', $date);   
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array();   
      return $result;

}

function getCommentByUser($user_id,$month,$year)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('comment');
      $CI->db->from('payroll_comment'); 
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('month', $month);
      $CI->db->where('year', $year);     
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array();  
     //print_r(count($result));exit();
      if(count($result)==0)
      {
        $results='0';
      }
      else
      { 
        $results=$result[0]['comment'];
      }  
      return $results;

}

function getPayrollformatannualleaveplanner($time)
{  

  // $hms = "48.90";
 // print "<br>--".$time."--";
  $hms = explode(".", $time); 
  $new_hms=str_replace(":",".", $time); 


  if($new_hms>=0)
  {  
    $sum=$hms[0] + ($hms[1]/60); 
    $hms_new =str_replace(".",":", number_format($sum,2));
  }
  else
  {    
    $num = abs($hms[0]);
    $sum=$num + ($hms[1]/60);
    $hms_new =str_replace(".",":", number_format($sum,2));
    $hms_new='-'.$hms_new;

  } 
  return $hms_new;
}

function getPayrollformat1($time)
{  

  // $hms = "48.90";
 // print "<br>--".$time."--";
  $hms = explode(":", $time); 
  $new_hms=str_replace(":",".", $time); 


  if($new_hms>=0)
  {  
    $sum=$hms[0] + ($hms[1]/60); 
    $hms_new =str_replace(".",":", number_format($sum,2));
  }
  else
  {    
    $num = abs($hms[0]);
    $sum=$num + ($hms[1]/60);
    $hms_new =str_replace(".",":", number_format($sum,2));
    $hms_new='-'.$hms_new;

  } 
  return $hms_new;
}

function getPayrollformat($time)
{  
  // $hms = "48.90";
 // print "<br>--".$time."--";
  $hms = explode(".", $time);
  return ($hms[0] + ($hms[1]/60));
}

  
  function getUnit($cat_id){
 
    $CI =& get_instance();
    $CI ->load->model('Training_model');
    $name = $CI->Training_model->getunitname($cat_id);
    //$List = implode(', ', $name); 
    //print_r($name); 
    // return $name[0]['unit_name'];
     return $name;

  } 
  
  
  function findBranches($unit){
      
      $CI =& get_instance();
      $CI->load->model('Dashboard_model');
      $count =   $CI->Dashboard_model->findBranches($unit);
      //$List = implode(', ', $name);
      //print_r($name);
      // return $name[0]['unit_name'];
      return $count;
      
  } 

  
  
  
  function findDatesBetnDates($start, $end, $format = 'd/m/Y'){
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
  function removeYear($date){
    $date_array  = explode("/",$date);
    $actual_date = $date_array[0]."/".$date_array[1];
    return $actual_date;
  }
  function findSchedule($id,$date){
    if($date){
      $date_array  = explode("/",$date);
      $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
    }
    $CI =& get_instance();
    $CI ->load->model('Rotaschedule_model');
    $result = $CI->Rotaschedule_model->findSchedule($id,$actual_date);
    return $result;
  }
  function getUsers($unit)
{
    $CI =& get_instance();
    $CI ->load->model('Dailysenses_model');
    $result = $CI->Dailysenses_model->finduserdata($unit);
     //print_r($result);exit();
    return $result;
}
function getArrayUsers($rota_id,$job_ids,$unit_id){
  $CI =& get_instance();
  $CI ->load->model('Rotaschedule_model');
  $data = $CI->Rotaschedule_model->getArrayUsers($rota_id,$job_ids,$unit_id);
  // print("<pre>".print_r($data,true)."</pre>");exit();
  return $data;
}
function getArrayUsersHorizontal($rota_id,$job_ids,$unit_id){
  $CI =& get_instance();
  $CI ->load->model('Rotaschedule_model');
  $data = $CI->Rotaschedule_model->getArrayUsersHorizontal($rota_id,$job_ids,$unit_id);
  // print("<pre>".print_r($data,true)."</pre>");exit();
  return $data;
}
function getShifts($rota_id,$user_id,$params){
  $CI =& get_instance();
  $CI ->load->model('Rotaschedule_model');
  if($params['selected_unitid'] == $params['user_unitid']){
    $data = $CI->Rotaschedule_model->getShiftsForSameUnitUser($params,$user_id);
  }else{
    $data = $CI->Rotaschedule_model->getShifts($rota_id,$user_id);
  }
  // print("<pre>".print_r($data,true)."</pre>");exit();
  return $data;
}
function getShiftsWithDate($rota_id,$user_id,$date,$params){
  $CI =& get_instance();
  if($date){
    $date_array  = explode("/",$date);
    $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
  }
  $CI ->load->model('Rotaschedule_model');
  if($params['selected_unitid'] == $params['user_unitid']){
    $data = $CI->Rotaschedule_model->getShiftsWithDateForSameUnit($params,$user_id,$actual_date);
  }else{
    $data = $CI->Rotaschedule_model->getShiftsWithDate($rota_id,$user_id,$actual_date);
  }
  // print("<pre>".print_r($data,true)."</pre>");exit();
  return $data;
}
function getHolidayDates($user_ids){
  $CI =& get_instance();
  $CI ->load->model('Rotaschedule_model');
  $holidayDates = array();
  if($user_ids > 0){
    $holidayDates = $CI->Rotaschedule_model->getHolidayDates($user_ids);
    return $holidayDates;
  }else{
    return $holidayDates;
  }
}
function getTrainingDates($user_ids){
  $CI =& get_instance();
  $CI ->load->model('Rotaschedule_model');
  $trainingDates = array();
  if($user_ids > 0){
    $trainingDates = $CI->Rotaschedule_model->getTrainingDates($user_ids);
    return $trainingDates;
  }else{
    return $trainingDates;
  }
}

function checkUserTraining($user_id,$date){
  $user_id_array = array();
  array_push($user_id_array, $user_id);
  $trainingDates =  array();
  $all_training_dates = array();
  $trainingDates = getTrainingDates($user_id_array);
  $t_count = count($trainingDates);
  for ($i = 0; $i < $t_count; $i++)
  {
    $dates = getDatesFromRange($trainingDates[$i]['date_from'],$trainingDates[$i]['date_to']);
    for ($j = 0; $j < count($dates); $j++)
    {
      array_push($all_training_dates, $dates[$j]);
    }
  }
  if (in_array($date, $all_training_dates)){
    return true;
  }else{
    return false;
  }
}
function checkUserHoliday($user_id,$date){
  $user_id_array = array();
  array_push($user_id_array, $user_id);
  $holidayDates =  array();
  $all_holiday_dates = array();
  $holidayDates = getHolidayDates($user_id_array);
  $h_count = count($holidayDates);
  for ($i = 0; $i < $h_count; $i++)
  {
    $dates = getDatesFromRange($holidayDates[$i]['from_date'],$holidayDates[$i]['to_date']);
    for ($j = 0; $j < count($dates); $j++)
    {
      array_push($all_holiday_dates, $dates[$j]);
    }
  }
  if (in_array($date, $all_holiday_dates)){
    return true;
  }else{
    return false;
  }
}
function changeDateFormat($date){
  if($date){
    $date_array  = explode("-",$date);
    $actual_date = $date_array[2]."/".$date_array[1]."/".$date_array[0];
  }
  return $actual_date;
}
function getELNShiftCount($id,$date,$users,$rota_id){
  if($date){
    $date_array  = explode("/",$date);
    $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
  }
  $early_shift_count = 0;
  $late_shift_count = 0;
  $night_shift_count = 0;

  $CI =& get_instance();
  $CI ->load->model('Rotaschedule_model');
  $data = $CI->Rotaschedule_model->getEarlyShiftCount($id,$actual_date,$users,$rota_id);
  if(count($data) > 0){
    for($i=0;$i<count($data);$i++){
      $shift_type = $data[$i]['shift_type'];
      $part_number = $data[$i]['part_number'];
      $shift_category = $data[$i]['shift_category'];
      if($part_number == 1){
        if($shift_category == 1){
          $early_shift_count++;
          $late_shift_count++;
        }else if($shift_category == 2){
          $night_shift_count++;
        }else if($shift_category == 3){
          $early_shift_count++;
        }else if($shift_category == 4){
          $late_shift_count++;
        }else{
          //nthng
        }
      }
    }

    return array(
      'early_shift' => $early_shift_count,
      'late_shift' => $late_shift_count,
      'night_shift' => $night_shift_count
    );
  }else{
    return array(
      'early_shift' => $early_shift_count,
      'late_shift' => $late_shift_count,
      'night_shift' => $night_shift_count
    );
  }
  return count($data);
}
function getUserDetails($id){
  $CI =& get_instance();
  $CI ->load->model('User_model');
  $data = $CI->User_model->getSingleUser($id);
  // print("<pre>".print_r($data,true)."</pre>");exit();
  return $data;
}
function get_user_shifts_on_dates($user_id,$dates){
    $CI =& get_instance();
    $CI ->load->model('Rotaschedule_model');
    $convertedDates = [];
    foreach ($dates as $date) {
        $convertedDate = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        $convertedDates[] = $convertedDate;
    }
    $data = $CI->Rotaschedule_model->get_user_shifts_on_dates($user_id,$convertedDates);

    foreach ($data as $key => &$value) {
        // Check the condition for removal (example condition)
        $status = checkUserTraining($value['user_id'], $value['date']);
        if ($status) {
            // Remove the element from the array if the condition is true
            unset($data[$key]);
        }
    }

    foreach ($data as $key => &$value) {
        // Check the condition for removal (example condition)
        $status = checkUserHoliday($value['user_id'], $value['date']);
        if ($status) {
            // Remove the element from the array if the condition is true
            unset($data[$key]);
        }
    }
    return $data;
}
function getHoursByUser($user_id,$year)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('hours');
      $CI->db->from('holiday_applied');
      $CI->db->join('holliday', 'holliday.id = holiday_applied.holiday_id'); 
      $CI->db->where('holiday_applied.user_id', $user_id); 
      $CI->db->where('holiday_applied.year', $year); 
      $CI->db->where('holliday.status', 1);   
      $result = $CI->db->get();
       // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array(); 
     // print_r($result);exit();
      if(count($result)==0)
      {
        $results='00:00';
      }
      else
      {
        $sum=0; $calculated_hours=0;
       foreach ($result as  $value) { 
                $abc= str_replace(':','.',$value['hours']); 
                $calculated_hours=number_format(getPayrollformat(number_format($abc,2),2),2); 
                $sum=$sum+$calculated_hours;                                
            } 
            $results=$sum;
      }
      //print_r($results);
    //exit();
      return $results;

}
function weekOfMonth($qDate) {
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
function getSitetitle()
{
    $CI =& get_instance();
    $CI ->load->model('Company_model');
    $result = $CI->Company_model->findsitetitle();
    //print_r($result);exit();
    return $result;
}

function GetPermission($type,$permissions)
{
 //print_r($type);print_r($permissions);exit();
    $result = array();
    $CI =& get_instance();
    $CI->db->select('COUNT(*) as result');
    $CI->db->from('permissions');
    $CI->db->join('group_permissions', 'group_permissions.permission_id = permissions.id'); 
    $CI->db->where('permissions.name', $permissions);
    $CI->db->where('group_permissions.group_id', $type); 
    $result = $CI->db->get();
    // print $CI->db->last_query();
    //   exit();
    $result = $result->result_array();
    return $result;
}

// function GetPermissionEditRota($type,$permissions)
// {
//     //print_r($type);print_r($permissions);exit();
//     $result = array();
//     $CI =& get_instance();
//     $CI->db->select('COUNT(*) as result');
//     $CI->db->from('permissions');
//     $CI->db->join('group_permissions', 'group_permissions.permission_id = permissions.id'); 
//     $CI->db->where('permissions.name', $permissions);
//     $CI->db->where('group_permissions.group_id', $type); 
//     $result = $CI->db->get();
//     // print $CI->db->last_query();
//     //   exit();
//     $result = $result->result_array();
//     return $result;
// }

// function GetPermissionViewRota($type,$permissions)
// {
//     //print_r($type);print_r($permissions);exit();
//     $result = array();
//     $CI =& get_instance();
//     $CI->db->select('COUNT(*) as result');
//     $CI->db->from('permissions');
//     $CI->db->join('group_permissions', 'group_permissions.permission_id = permissions.id'); 
//     $CI->db->where('permissions.name', $permissions);
//     $CI->db->where('group_permissions.group_id', $type); 
//     $result = $CI->db->get();
//     // print $CI->db->last_query();
//     //   exit();
//     $result = $result->result_array();
//     return $result;

// }

function getDesignationold($id){
    $result = array();
        $CI =& get_instance();
        $CI->db->select('designation_name');
      $CI->db->from('master_designation');  
      $CI->db->where('id', $id);
      $result = $CI->db->get();
    // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
    return $result;
}
function getLeaveremaining($id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('leave_remaining');
      $CI->db->from('holliday');  
      $CI->db->where('user_id', $id);
      $CI->db->where('status', 1);
      $CI->db->order_by('id', "desc");
      $CI->db->limit('1');
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array(); 
      if(count($result)==0)
      {
        $results='00:00';
      }
      else
      {
        $results=$result[0]['leave_remaining'];
      }
     // print_r($results);exit();
      return $results;

}

function getRemainingLeave($id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('remaining_holiday_allowance');
      $CI->db->from('users');  
      $CI->db->where('id', $id);
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array();  //print_r($result);exit();
      if(count($result)==0)
      {
        $results='00:00';
      }
      else
      {
        $results=$result[0]['remaining_holiday_allowance'];
      }
    //print_r($results);exit();
      return $results;

}
function getUnitold($id)
{
  $result = array();
        $CI =& get_instance();
        $CI->db->select('unit_name');
      $CI->db->from('unit');  
      $CI->db->where('id', $id);
      $result = $CI->db->get();
    // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
    return $result;

}
function getMonthName($monthNum){
  // $monthNum = 9;
  $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
  echo $monthName; // Output: May
}

function getNameOfMonth($month)
{ 
  $fields =explode("-", $month);
  $monthNum=$fields[1];
  $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
  return $monthName; 
}
 
 function getUpdateduser($id)
 {
   $result = array();
        $CI =& get_instance();
        $CI->db->select('personal_details.fname,personal_details.lname');
      $CI->db->from('users');
      $CI->db->join('personal_details', 'personal_details.user_id = users.id'); 
      $CI->db->where('users.id', $id); 
      $result = $CI->db->get();
    // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
    return $result;
 }

  function getCreator($cat_id)
  {
    $CI =& get_instance();
    $CI ->load->model('Training_model');
    $name = $CI->Training_model->getUser($cat_id);
     //$List = implode(', ', $name); 
     //print_r($name[0]['fname'].''.$name[0]['lname']);  
    // return $name[0]['unit_name'];
     return $name[0]['fname'].' '.$name[0]['lname'];

  }
  function checkHoliday($date)
  {
    $CI =& get_instance();
    $CI ->load->model('Bankholiday_model');
    $result = $CI->Bankholiday_model->checkHoliday($date);
    return $result['status'];
  }

  function getfeedback($user_id,$trainingid)
  {
    $CI =& get_instance();
    $CI ->load->model('Training_model'); 
    $comments = $CI->Training_model->getfeedback($user_id, $trainingid);  
    // print_r($comments);exit();
 
    return $comments;


  }

   function getupdatedusernote($id)
  { 

    $CI =& get_instance();
    $CI ->load->model('Note_model'); 
    $comments = $CI->Note_model->getupdatedusernote($id);  
    //print_r($comments);exit();
 
    return $comments;
  }

  function getHour($user_id,$shift_id,$date,$unit_id,$timelog_id)
  {

    $params=array(); 
    $params['user_id']=$user_id;
    $params['date']=$date;
    $params['shift_id']=$shift_id;
    $params['unit_id']=$unit_id;
    $params['timelogid']=$timelog_id;
    //print_r($params);exit();
    $CI =& get_instance();
    $CI ->load->model('Rotaschedule_model'); 
    $comments = $CI->Rotaschedule_model->gethours($params);    
 
    return $comments;
  }

  function getshiftcategory($userid)
  {

      $result = array();
      $CI =& get_instance();
      $CI->db->select('master_shift.shift_category');
      $CI->db->from('users');
      $CI->db->join('master_shift', 'master_shift.id = users.default_shift'); 
      $CI->db->where('users.id', $userid); 
      $result = $CI->db->get();
    // print $CI->db->last_query();
      // exit();
      $result = $result->result_array(); 
      if(empty($result[0]['shift_category']))
      { 
        $new_result=1;
      }
      else
      {
        $new_result=$result[0]['shift_category'];
      }

    return $new_result;     
  }

  function getLeaveRemainingbyuser($date,$user_id)
  {
    //print_r($date);
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
    
     if($year)
     {
        $result = array();
        $CI =& get_instance();
        $CI->db->select('hours');
        $CI->db->from('holiday_applied');
        $CI->db->join('holliday', 'holliday.id = holiday_applied.holiday_id'); 
        $CI->db->where('holiday_applied.user_id', $user_id); 
        $CI->db->where('holiday_applied.year', $year); 
        $CI->db->where('holliday.status', 1);   
        $result = $CI->db->get();
        // print $CI->db->last_query();
        //   exit();

        $result = $result->result_array(); 
       // print_r($result);exit();
        if(count($result)==0)
        {
          $results='00:00';
        }
        else
        {
          $sum=0; $calculated_hours=0;
         foreach ($result as  $value) {
                $abc= str_replace(':','.',$value['hours']); 
                $calculated_hours=number_format(getPayrollformat(number_format($abc,2),2),2); 
                $sum=$sum+$calculated_hours;                      
              } 
              $results=$sum;
        }
        $result1=array('hour'=>$results,'year'=>$year);
        //print_r($result1);exit();
        return $result1;
      }


  }

  
  function getExtrahour($start_time,$end_time,$hours,$checkin,$checkout)
  {
    // $params=array(); 
    // $params['start_time']=$start_time;
    // $params['end_time']=$end_time;
    // $params['checkin']=$checkin;
    // $params['checkout']=$checkout;
    //print_r($params);exit();
  //   print_r("<pre>");
  //  print_r('st-'.$start_time); print_r("<br>");
  // print_r('en-'.$end_time); print_r("<br>");
  // print_r('chin-'.$checkin); print_r("<br>");
  // print_r('chout-'.$checkout); print_r("<br>");
  //  print_r('hours-'.$hours); print_r("<br>");

    $to_time = strtotime($end_time);
    $from_time = strtotime($start_time);
    $time_diff = $to_time - $from_time;
    $time1= gmdate('H:i:s', $time_diff);   
// print_r($time1);
    $checkin_time = strtotime($checkin);
    $checkout_time = strtotime($checkout);
    $time_diff1 = $checkout_time - $checkin_time;
    $time2= gmdate('H:i:s', $time_diff1);   
   //print_r($time2);
    if($time2>$time1)
    {
    $shift_time = strtotime($time1);
    $worked_time = strtotime($time2);
    $extra = $worked_time - $shift_time;
    $extra_hour= gmdate('H:i:s', $extra);
    }
    else
    {
    $extra_hour="00:00:00";
    } 
    
    // print_r($extra_hour);
    return $extra_hour;

  }

  function getTime($firsttime,$lasttime)
  { 
      //$firsttime='13:26'; $lasttime="1.30";
      $ftime=date('H:i',strtotime($firsttime));
      $ltime=date('H:i',strtotime($lasttime));

      $startTime = new DateTime($ftime);
      $endTime = new DateTime($ltime);
      $duration = $startTime->diff($endTime); //$duration is a DateInterval object
      $time2=$duration->format("%H.%I");
      // print_r($time2);exit();
      return $time2;
  }

   function getPayrollExtrahour($checkin,$checkout,$date_to,$date_from,$start_time,$end_time,$break,$break_status,$break_status_new)
  { //print_r('br-r'.$break);
   $start_time=str_replace(':', '.', $start_time); 
    //print_r($start_time); print '<br>';
    //print_r($checkin); print '<br>';
   $end_time=str_replace(':', '.', $end_time); 
    //print_r($end_time); print '<br>';  
    //print_r($checkout); print '<br>';
    //$checkin='20:12:45';
    //$checkout='07:30:37';
    if($checkin<$start_time)
    { //check if checkin time less than shift start time 
       $firsttime=$start_time;
    }
    else
    { 
       $firsttime=$checkin;
    } 
    if($checkout>$end_time)
    { //check if checkout time less than shift end time
       $lasttime=$end_time;
       }
       else
       {
       $lasttime=$checkout;
    }
//print_r($lasttime); print '<br>';
   //print_r("<pre>");  

   if($date_to==$date_from)
   { //print 'hii';
    if($firsttime<$lasttime)
    { 
      //print date ('H:i',strtotime($firsttime))." - ".$lasttime."<br>";
      $ftime=date('H:i',strtotime($firsttime));
      $ltime=date('H:i',strtotime($lasttime));

      $startTime = new DateTime($ftime);
      $endTime = new DateTime($ltime);
      $duration = $startTime->diff($endTime); //$duration is a DateInterval object
      $time2=$duration->format("%H:%I");  

    }
    else
    {   
      $time2="00:00";
    } 
  }
  else
  { //print 'hello';
      if($firsttime=='00:00:00' || $lasttime=='00:00:00')
      {  
         $time2="00:00";

      }
      else
      { 
        $ftime=date('H:i',strtotime($firsttime));  //print_r($ftime); print_r('<br>');
        $ltime=date('H:i',strtotime($lasttime));   //print_r($ltime);  print_r('<br>');
        $from_time1=$date_from.' '.$ftime;  
        $to_time1=$date_to.' '.$ltime;  

        $date_a = new DateTime($from_time1);
        $date_b = new DateTime($to_time1);

        $interval = date_diff($date_a,$date_b);

        $time2=$interval->format('%h:%i'); 
      }
  
  }
  //print_r("time-".$time2);
  //exit();

  //print_r($break_status); print '<br>';
  //print_r($break_status_new); print '<br>';
  //print '-------'; print '<br>';
  if($time2!='00:00')
  {  //print_r("br-".$break);
    if($break!=' ')
    {
      $break_hour=date('H:i',strtotime($break));  //exit();
      $DHours = new DateTime($time2); //print_r($DHours);
      $bHours = new DateTime($break_hour); //print_r($bHours);print "------<br>";
      $duration = $bHours->diff($DHours); //$duration is a DateInterval object
      $hours=$duration->format("%r%H:%I"); 
     }
     else
     {
       $hours=$time2;
    } 

  }
  else
  {  
     $hours=$time2;
  } 
  //print_r($hours); 
    return array('hour'=>$hours,'time'=>$time2);

  }

  function getPayrollExtrahournewforDayHours($checkin,$checkout,$start_time,$end_time)
  {

    $start_time=str_replace(':', '.', $start_time);  
    $end_time=str_replace(':', '.', $end_time); 
      
     
    $check_in=explode(" ",$checkin); //print_r($check_in); print '<br>';
    $check_out=explode(" ",$checkout); //print_r($check_out); print '<br>';exit();

    $checkin=str_replace(':', '.', $check_in[1]);//print_r($checkin);exit();
    $date_from=$check_in[0];

    $checkout=str_replace(':', '.', $check_out[1]); 
    $date_to=$check_out[0];

    if($checkin<$start_time)
    { //check if checkin time less than shift start time 
        $firsttime=$start_time;
    }
    else
    { 
        $firsttime=$checkin;
    } 
        // print_r($firsttime); 
    if($checkout>$end_time)
    { //check if checkout time less than shift end time
        $lasttime=$end_time;
    }
    else
    {
        $lasttime=$checkout;
    }


        if($date_to==$date_from)
       { //print 'hii';
          if($firsttime<$lasttime)
          { 
            //print date ('H:i',strtotime($firsttime))." - ".$lasttime."<br>";
            $ftime=date('H:i',strtotime($firsttime));
            $ltime=date('H:i',strtotime($lasttime));

            $startTime = new DateTime($ftime);
            $endTime = new DateTime($ltime);
            $duration = $startTime->diff($endTime); //$duration is a DateInterval object
            $time2=$duration->format("%H:%I");  

          }
          else
          {   
            $time2="00:00";
          } 
      }
      else
      { //print 'hello';
          if($firsttime=='00:00:00' || $lasttime=='00:00:00')
          {  
             $time2="00:00";

          }
          else
          { 
            $ftime=date('H:i',strtotime($firsttime));  //print_r($ftime); print_r('<br>');
            $ltime=date('H:i',strtotime($lasttime));   //print_r($ltime);  print_r('<br>');
            $from_time1=$date_from.' '.$ftime;  
            $to_time1=$date_to.' '.$ltime;  

            $date_a = new DateTime($from_time1);
            $date_b = new DateTime($to_time1);

            $interval = date_diff($date_a,$date_b);

            $time2=$interval->format('%h:%i'); 
          }
      
      }

      $result=array('time'=>$time2);
       // print_r($result);exit();
      return $result;



  }


   function getPayrollExtrahournew($checkin,$checkout,$start_time,$end_time,$break,$shift_category,$day_date)
  {  
    // print_r($day_date);
    // $checkin='2020-08-15 02:35:33';
    // $checkout='2020-08-15 07:30:33';
    // $start_time='19:30';
    // $end_time='07:30';
    // $break='00:00'; 



   $start_time=str_replace(':', '.', $start_time);  
   $end_time=str_replace(':', '.', $end_time); 
      
     
     $check_in=explode(" ",$checkin); //print_r($check_in); print '<br>';
     $check_out=explode(" ",$checkout); //print_r($check_out); print '<br>';exit();

     $checkin=str_replace(':', '.', $check_in[1]);//print_r($checkin);exit();
     $date_from=$check_in[0];

     $checkout=str_replace(':', '.', $check_out[1]); 
     $date_to=$check_out[0];
    //$checkin='20:12:45';
    //$checkout='07:30:37';

     if($shift_category==2)
     { // night shift case
           if($date_to==$date_from) //checkin date and checkout date equal
           {  //print 'hii';  

                if($day_date==$date_from) //current date and checkin date equal
                {

                    if($checkin<$start_time)
                    { //check if checkin time less than shift start time 
                      //print 'hii';
                       $firsttime=$start_time;
                    }
                    else
                    { //print 'hello';
                       $firsttime=$checkin;
                    } 
                     
                     $lasttime=$checkout;

                }
                else
                { 

                  $firsttime=$checkin;
                  $lasttime=$checkout; 
                }


           }
           else
           { //print 'hello';
                  if($checkin<$start_time)
                  { //check if checkin time less than shift start time 
                     $firsttime=$start_time;
                  }
                  else
                  { 
                     $firsttime=$checkin;
                  } 
              // print_r($firsttime); 
                  if($checkout>$end_time)
                  { //check if checkout time less than shift end time
                     $lasttime=$end_time;
                     }
                     else
                     {
                     $lasttime=$checkout;
                  }
                


           }



     }
     else
     { // othershifts

     //print_r($checkin); print '<br>';
    // print_r($checkout); print '<br>'; exit();
            if($checkin<$start_time)
            { //check if checkin time less than shift start time 
               $firsttime=$start_time;
            }
            else
            { 
               $firsttime=$checkin;
            } 
        // print_r($firsttime); 
            if($checkout>$end_time)
            { //check if checkout time less than shift end time
               $lasttime=$end_time;
               }
               else
               {
               $lasttime=$checkout;
            }
      }
 // print_r($firsttime); print '<br>';
  //print_r($lasttime); print '<br>';exit();
   //print_r("<pre>");  

   if($date_to==$date_from)
   { //print 'hii';
    if($firsttime<$lasttime)
    { 
      //print date ('H:i',strtotime($firsttime))." - ".$lasttime."<br>";
      $ftime=date('H:i',strtotime($firsttime));
      $ltime=date('H:i',strtotime($lasttime));

      $startTime = new DateTime($ftime);
      $endTime = new DateTime($ltime);
      $duration = $startTime->diff($endTime); //$duration is a DateInterval object
      $time2=$duration->format("%H:%I");  

    }
    else
    {   
      $time2="00:00";
    } 
  }
  else
  { //print 'hello';
      if($firsttime=='00:00:00' || $lasttime=='00:00:00')
      {  
         $time2="00:00";

      }
      else
      { 
        $ftime=date('H:i',strtotime($firsttime));  //print_r($firsttime); print_r('<br>');
        $ltime=date('H:i',strtotime($lasttime));   //print_r($lasttime);  print_r('<br>');
        $from_time1=$date_from.' '.$ftime;  
        $to_time1=$date_to.' '.$ltime;  

        //$date_a = new DateTime($from_time1);
        //$date_b = new DateTime($to_time1);

        //$interval = date_diff($date_a,$date_b);

//changed on march 31

        $zone = new DateTimeZone('UTC');
        $start = new DateTime($from_time1, $zone);
        $end = new DateTime($to_time1, $zone);

        $start->setTimeZone(new DateTimeZone('UTC')); //print_r($start); print '<br>';
        $end->setTimeZone(new DateTimeZone('UTC')); //print_r($end); print '<br>';

        $difference = $start->diff($end); //print_r($difference); print '<br>';
        $time2=$difference->format('%h:%i');


        //$time2=$interval->format('%h:%i'); 
      }
  
  }
  //print_r("time-".$time2);
  //exit();

  //print_r($break_status); print '<br>';
  //print_r($break_status_new); print '<br>';
  //print '-------'; print '<br>';
  if($time2!='00:00')
  {  //print_r("br-".$break);
    if($break!=' ')
    {
      $break_hour=date('H:i',strtotime($break));  //exit();
      $DHours = new DateTime($time2); //print_r($DHours);
      $bHours = new DateTime($break_hour); //print_r($bHours);print "------<br>";
      $duration = $bHours->diff($DHours); //$duration is a DateInterval object
      $hours=$duration->format("%r%H:%I"); 
     }
     else
     {
       $hours=$time2;
    } 

  }
  else
  {  
     $hours=$time2;
  } 
   
  $result=array('hour'=>$hours,'time'=>$time2,'date'=>$day_date);
 // print_r($result);exit();
    return $result;

  }

function getSHifthourstotal($time2,$break)
{
  //print_r($time2); print '<br>';
  //print_r($break); print '<br>';
      $break_hour=date('H:i',strtotime($break));  //exit();
      $DHours = new DateTime($time2); //print_r($DHours);
      $bHours = new DateTime($break_hour); //print_r($bHours);print "------<br>";
      $duration = $bHours->diff($DHours); //$duration is a DateInterval object
      $hours=$duration->format("%r%H:%I");
      return $hours;
     // print_r($hours);print '<br>';
}

  function findtimenew($stime)
  {
     $s= str_replace('.', ':',$stime); //print_r($s);exit();
     $time2=date('H.i',strtotime($s));
     return $time2;
  }

  function findDifference($time2,$break)
  { 
     $break_hour=date('H:i',strtotime($break)); //print_r("br-".$break_hour); //exit();

      $DHours = new DateTime($time2); //print_r($DHours);
      $bHours = new DateTime($break_hour); //print_r($bHours);print "------<br>";
      $duration = $bHours->diff($DHours); //$duration is a DateInterval object
      $hours=$duration->format("%r%H.%I");
      return $hours;
  }

  function findDiff($time2,$break)
  {  //print_r($time2);exit();
      $s= str_replace('.', ':',$time2); //print_r($s);exit();
      $time2=date('H:i',strtotime($s)); //print_r($time2); exit();
      $break_hour=date('H:i',strtotime($break)); //print_r("br-".$break_hour); exit();

      $DHours = new DateTime($time2); //print_r($DHours);
      $bHours = new DateTime($break_hour); //print_r($bHours);print "------<br>";
      $duration = $bHours->diff($DHours); //$duration is a DateInterval object
      $hours=$duration->format("%H.%I");
      //print_r($hours);exit();
      return $hours;
  }

 function settimeformat($time)
 { //print_r($time);
   $hour = str_replace(':', '.',$time);
   return $hour;
 }

  function settimeformat_new($time)
 { //print_r($time);
   $hour = str_replace('.', ':',$time);
   return $hour;
 }

function ChangesignforHour($hour)
{  
          $shour = explode(":",$hour); //print_r($shour);
            if($shour[0]<1)
            {
              $hour1=$shour[0]*-1;
              $status=1;
            }
            else
            {
              $hour1=$shour[0];
              $status=2;
            } 
            return $shour;
}

 function AddTimesnew1($intime,$additionalhour)
 {

  $ssum = explode(":",$intime); //print_r($ssum);
  $intime = $ssum[0].'.'.$ssum[1];

  $additional = explode(":",$additionalhour); //print_r($additionalhour);
  $additionalhour = $additional[0].'.'.$additional[1];


  $result=$intime+$additionalhour;

  return $result;


 }


 function AddTimesnew($intime,$additionalhour)
 {
      $sum1=$intime.":00";
      $hour=$additionalhour.":00";
      
      if(strpos($sum1, "-") !== false)
      { // print_r('hiii');exit();
        $ssum = explode(".",$sum1); 
        $sum1 = $ssum[0].":-".$ssum[1].":".$ssum[2];
      }
      //print_r($sum1);exit();

      if(strpos($hour, "-") !== false)
      {
        $shour = explode(":",$hour);
        $hour = $shour[0].":-".$shour[1].":".$shour[2];
      }

      //print_r($hour);exit();

        $sum_hour = time_to_decimal($sum1); //print_r($sum_hour); 
        $b_hour = time_to_decimal($hour);  //print_r($b);exit();

        $sum_val = time_to_decimal($sum1); //print_r($sum_val); 
        $b_val = time_to_decimal($hour); //print_r($b_val);exit();
        if(strpos($sum1, "-") !== false)
        {
          $sum_hour= $sum_hour - $b_hour;
          //print_r($sum);exit();
        }
        else
        {
          $sum_hour= $sum_hour + $b_hour;
        } 
        $total=decimal_to_time($sum_hour); //
        $totalhour = explode(":",$total);

        if(strpos($totalhour[0], "-") !== false)
        {
          $result=abs($totalhour[0]).":".abs($totalhour[1]);  
        }
        else
        {
          $result=$total;
        } 

          //print_r($result);exit();

        if(strpos($sum1, "-") !== false)
        {
          if($sum_val>=$b_val)
          {
            $result= "-".$result;
          }
          else
          {
            $result= $result;
          }
           
        }
        else
        {
          $result= $result;
        }

        //print_r($result);exit();

        return $result;



 }

function AddTimes($intime,$additionalhour,$break_status,$break_status_new)
{
  // print_r($intime);
  // print_r($additionalhour);
   //$sum1="-00:15:00"; $hour="24:10:00";
if($break_status!=$break_status_new)
{
      $sum1=$intime.":00";
      $hour=$additionalhour.":00";
      
      if(strpos($sum1, "-") !== false)
      { // print_r('hiii');exit();
        $ssum = explode(".",$sum1); 
        $sum1 = $ssum[0].":-".$ssum[1].":".$ssum[2];
      }
      //print_r($sum1);exit();

      if(strpos($hour, "-") !== false)
      {
        $shour = explode(":",$hour);
        $hour = $shour[0].":-".$shour[1].":".$shour[2];
      }

      //print_r($hour);exit();

        $sum_hour = time_to_decimal($sum1); //print_r($sum_hour); 
        $b_hour = time_to_decimal($hour);  //print_r($b);exit();

        $sum_val = time_to_decimal($sum1); //print_r($sum_val); 
        $b_val = time_to_decimal($hour); //print_r($b_val);exit();
        if(strpos($sum1, "-") !== false)
        {
          $sum_hour= $sum_hour - $b_hour;
          //print_r($sum);exit();
        }
        else
        {
          $sum_hour= $sum_hour + $b_hour;
        } 
        $total=decimal_to_time($sum_hour); //
        $totalhour = explode(":",$total);

        if(strpos($totalhour[0], "-") !== false)
        {
          $result=abs($totalhour[0]).":".abs($totalhour[1]);  
        }
        else
        {
          $result=$total;
        } 

          //print_r($result);exit();

        if(strpos($sum1, "-") !== false)
        {
          if($sum_val>=$b_val)
          {
            $result= "-".$result;
          }
          else
          {
            $result= $result;
          }
           
        }
        else
        {
          $result= $result;
        }

        return $result;

}
else
{
   $result=$intime;
   return $result;

}

    
}

function time_to_decimal($time) { //print_r($time);exit();
//$time="24.00.00";
$timeArr = explode(':', $time); //print_r($timeArr);exit();

$decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
//print_r($decTime);exit();

return $decTime;
}

function decimal_to_time($decimal) {

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



return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT);

}


//  function AddTimes($intime,$additionalhour) { //print_r("intime-".$intime."<br>"); print_r("addi-".$additionalhour);

//     $intime = "-00:15";
//     $additionalhour = "00:10";

//     $s = explode(":",$intime);
    

//     if(strpos($s[0], "-") !== false)
//     {   
//       // if(date('H:i:s',strtotime($intime.':00'))<date('H:i:s',strtotime($additionalhour.':00')))
//       // {
//       //   $sign="-";
//       // }
//       // else
//       // {
//       //   $sign="";
//       // } 
//  $sign="-";
//       print_r(date('H:i',strtotime($s[0].' hour '.$sign.$s[1].' minutes')));
//       print_r(date('H:i',strtotime($additionalhour)));
     
//       $result= $sign.date('H:i',strtotime($s[0].' hour '.$sign.$s[1].' minutes',strtotime($additionalhour)));
//     }
//     else
//     {  
//     $result= date('H:i',strtotime($s[0].' hour '.$s[1].' minutes',strtotime($additionalhour)));
//     }
//     exit();
//    return $result;
// }
 



  function get_week($from_date) {
        $start = strtotime($from_date) - strftime('%w', $from_date) * 24 * 60 * 60;
        $end = $start + 6 * 24 * 60 * 60;
        return array('start' => strftime('%Y-%m-%d', $start),
                     'end' => strftime('%Y-%m-%d', $end));
}

 function getImage($id)
 {
    $CI =& get_instance();
    $CI ->load->model('Leave_model'); 
    $comments = $CI->Leave_model->getImage($id);  
     //print_r($comments[0]['status'].':'.$comments[0]['comment'].','.$comments[0]['date']); 
 
    return $comments;

 }
 
 
 function getMessage($id)
  {
    $CI =& get_instance();
    $CI ->load->model('Leave_model'); 
    $comments = $CI->Leave_model->getMessage($id);  
     //print_r($comments[0]['status'].':'.$comments[0]['comment'].','.$comments[0]['date']); 
 
     return $comments;
  } 

  function getTrainingStaff($id)
  {
    $CI =& get_instance();
    $CI ->load->model('Training_model'); 
    $comments = $CI->Training_model->getStafflist($id);  
     //print_r($comments[0]['status'].':'.$comments[0]['comment'].','.$comments[0]['date']); 
 
    return $comments;
  }

  function getComment($id)
  {
    $CI =& get_instance();
    $CI ->load->model('Training_model'); 
    $comments = $CI->Training_model->getComment($id);  
     //print_r($comments[0]['status'].':'.$comments[0]['comment'].','.$comments[0]['date']); 
     //print_r($comments);exit();
     return $comments;


  }
 
  function getStaffs($shift, $unitid)
  {  
    $CI =& get_instance();
    $CI ->load->model('Unit_model'); 
    $comments = $CI->Unit_model->getstaffsbyunitShift($shift,$unitid);  
    if(count($comments)>0)
    {

      foreach ($comments as $value) {
        # code...
      }
    }
 
     return $comments;
  }
  function findotherunit($unitid)
  {
    $CI =& get_instance();
    $CI ->load->model('User_model'); 
    $comments = $CI->User_model->findunit($unitid);  
    //print_r($comments); 
  
     return $comments;
  }
  function findUnitHaveBranches($unitid)
  {
    $CI =& get_instance();
    $CI ->load->model('Dashboard_model'); 
    $comments = $CI->Dashboard_model->findBranches($unitid);  
    //print_r($comments); 
  
     return $comments;
  }

  function findshift($id)
  {
    $CI =& get_instance();
    $CI ->load->model('Shift_model'); 
    $result = $CI->Shift_model->findshift($id);
    return $result[0]['shift_name'];
  }

  function getContractHours($weeks,$hours)
  {
    $num=$weeks;
    $timeUnit=$hours;
    $newtime=str_replace(':', '.', $timeUnit);
    $total=$newtime*$weeks;
    return $total;
  }

  function getNotAssignedStaffs($unitid)
  {
    $CI =& get_instance();
    $CI ->load->model('User_model'); 
    $comments = $CI->User_model->getNotAssignedStaffs($unitid);  
   // print_r($comments); exit();
 
     return $comments;
  }
  function getRandomStrig(){
    $CI =& get_instance();
    $CI->load->helper('string');
    return random_string('alnum',20);
  }
  function corectDateFormat($date){  
    if($date)
    {
      $my_str = $date;
      $date_array = array();
      $date_array = explode("-", $my_str);
      $date_with_slash = $date_array[2]."/".$date_array[1]."/".$date_array[0];
      return $date_with_slash;
    }
  }
  function formatDate($date){  
    if($date){
      $date_array  = explode("/",$date);
      $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
      return $actual_date;
    }
  }
  function getDatesFromRange($start, $end, $format = 'Y-m-d'){
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
  function findUnitShift($user_id,$date){
    $CI =& get_instance();
    $CI ->load->model('Rota_model');
    $result =$CI->Rota_model->findUnitShift($user_id,$date);
    return array(
      'shift_name' => $result[0]['shift_name'],
      'message'    => "shift assigned on".$result[0]['unit_name']
    );
  }
  function getLeave($from_date,$to_date,$user_id){
    $CI =& get_instance();
    $CI ->load->model('Reports_model');
    $message = '';
    if($from_date && $to_date && $user_id){
      $result =$CI->Reports_model->getLeave($from_date,$to_date,$user_id);
      $dates = '';
      if(count($result)>0){
        if(count($result) == 1){
          $message = "You are taken sick leave on ".corectDateFormat($result[0]['date']);
        }else{
          for ($k = 0; $k < count($result); $k++){

            $dates = $dates.' , '.corectDateFormat($result[$k]['date']);
          }
          $message = "You are taken sick leave on ".$dates;
        }
      }
      return $message;
    }else{
      return $message;
    }
  }
  function checkAllShift($data){
    $offdays = 0;
    $count = count($data);
    for($i=0;$i<$count;$i++) {
      if($data[$i]['shift_id'] == 0){
        $offdays++;
      }
    }
    return $offdays;
  }
  function findTrainigHolidayStatus($date,$user_id,$shift_id){
      $CI =& get_instance();
      $CI ->load->model('Rotaschedule_model');
      $user_id_array = explode(',', $user_id);
      $holidayDates = $CI->Rotaschedule_model->getHolidayDates($user_id_array);
      $trainingDates = $CI->Rotaschedule_model->getTrainingDates($user_id_array);
      $holiday_count = count($holidayDates);
      $training_count = count($trainingDates);
      $all_holiday_dates = array();
      $all_training_dates = array();
      if($holiday_count > 0){
        for ($i = 0; $i < $holiday_count; $i++)
        {
          $dates = getDatesFromRange($holidayDates[$i]['from_date'],$holidayDates[$i]['to_date']);
          for ($j = 0; $j < count($dates); $j++)
          {
            if(!in_array($dates[$j], $all_holiday_dates)){
              array_push($all_holiday_dates, $dates[$j]);
            }
          }
        }
      }
      if($training_count > 0){
        for ($k = 0; $k < $training_count; $k++)
        {
          $tdates = getDatesFromRange($trainingDates[$k]['date_from'],$trainingDates[$k]['date_to']);
          for ($s = 0; $s < count($tdates); $s++)
          {
            if(!in_array($tdates[$s], $all_training_dates)){
              array_push($all_training_dates, $tdates[$s]);
            }
          }
        }
      }
      if(in_array($date,$all_holiday_dates)){
        if($shift_id == 0){
          $shift_id = 1;
        }
      }
      if(in_array($date,$all_training_dates)){
        $shift_id = 2;
      }
      return $shift_id;
    }
    function showNoteWithTrainingHoliday($date,$shift_id,$user_id){
      $training_status = 0;
      $holiday_status  = 0;
      $CI =& get_instance();
      $CI ->load->model('Rotaschedule_model');
      $user_id_array = explode(',', $user_id);
      $holidayDates = $CI->Rotaschedule_model->getHolidayDates($user_id_array);
      $trainingDates = $CI->Rotaschedule_model->getTrainingDates($user_id_array);
      $message = "";      
      $all_holiday_dates = array();
      $all_training_dates = array();    
      $holiday_count = count($holidayDates);
      $training_count = count($trainingDates);
      $training_titles_array = array();
      if($holiday_count > 0){
        for ($i = 0; $i < $holiday_count; $i++)
        {
          $dates = getDatesFromRange($holidayDates[$i]['from_date'],$holidayDates[$i]['to_date']);
          for ($j = 0; $j < count($dates); $j++)
          {
            if(!in_array($dates[$j], $all_holiday_dates)){
              array_push($all_holiday_dates, $dates[$j]);
            }
          }
        }
      }
      if (in_array($date, $all_holiday_dates))
      {
        $message = "Applied for holiday";
        $holiday_status++;
      }
      if($training_count > 0){
        for ($k = 0; $k < $training_count; $k++)
        {
          $tdates = getDatesFromRange($trainingDates[$k]['date_from'],$trainingDates[$k]['date_to']);
          $title = $trainingDates[$k]['title'];
          for ($s = 0; $s < count($tdates); $s++)
          {
            if(!in_array($tdates[$s], $all_training_dates)){
              $training_titles_array[$tdates[$s]]=$title;
              array_push($all_training_dates, $tdates[$s]);
            }
          }
        }
      }
      if (in_array($date, $all_training_dates))
      {
        $message = $training_titles_array[$date];
        $training_status++;
      }
      return array(
        'message' => $message,
        'training_status' => $training_status,
        'holiday_status' => $holiday_status
      );
    }
  function checkUserStatus($date,$user_id,$unit_id_for,$parent_shift_id){
    $CI =& get_instance();
    $CI ->load->model('Shift_model');
    if($date){
      $date_array  = explode("/",$date);
      $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
    }
    $result =$CI->Shift_model->checkUserStatus($actual_date,$user_id,$unit_id_for,$parent_shift_id);
    // print("<pre>".print_r($result,true)."</pre>");exit();
    $count = count($result);
    if($count > 0){
      if($result[0]['status'] == 0){
        $status = "Sent";
      }else if($result[0]['status'] == 1){
        $status = "Accepted";
      }else if($result[0]['status'] == 2){
        $status = "Rejected";
      }else{
        $status = "Deleted";
      }
    }else{
      $status = "true";
    }
    return $status;
  }
  function checkRotaStatus($date,$unit_id){
    if($date){
      $date_array  = explode("/",$date);
      $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
    }
    $CI =& get_instance();
    $CI ->load->model('Rota_model');
    $result =$CI->Rota_model->checkRotaStatus($actual_date,$unit_id);
    $count = count($result);
    if($count > 0){
      return "true";
    }else{
      return "false";
    }
  }

  function Checktrainingdates($t_id)
  {
     $CI =& get_instance();
     $CI ->load->model('Training_model');
     $result =$CI->Training_model->getTrainingDatesByid($t_id);
     $current_date=date('d-m-Y'); //print_r($current_date);exit();

    $curdate=new DateTime($current_date); //print_r($curdate);
    $mydate=new DateTime($result);  //print_r($mydate);

    if($curdate > $mydate)
    {
        $result='false';
    }
    else
    {
        $result='true';
    } 
    return $result;
  }
  function getTrainingStaffs($t_id){
    $CI =& get_instance();
    $CI ->load->model('Training_model');
    $result =$CI->Training_model->getTrainingStaffs($t_id);
    $count = count($result);
    $status = 0;
    for($i=0;$i<$count;$i++) {
      if($result[$i]['published'] == 1){
        $status++;
      }
    }
    if($count == $status){
      return "true";
    }else{
      return "false";
    }
  }
  function countDayShiftStaffs($date,$unit_ids){
    $CI =& get_instance();
    $CI ->load->model('Reports_model');
    $result =$CI->Reports_model->countShiftStaffs($date,$unit_ids,1);
    return $result;
  }
  function countNightShiftStaffs($date,$unit_ids){
    $CI =& get_instance();
    $CI ->load->model('Reports_model');
    $result =$CI->Reports_model->countShiftStaffs($date,$unit_ids,2);
    return $result;
  }
  function findUnitName($id){
    $CI =& get_instance();
    $CI ->load->model('Unit_model');
    $result =$CI->Unit_model->findunit($id);
    return $result[0]['unit_name'];
  }
  function createMonthArray($year_string){
    $year_string_array = explode('-',$year_string);
    return array($year_string_array[0]."-09",$year_string_array[0]."-10",$year_string_array[0]."-11",$year_string_array[0]."-12",$year_string_array[1]."-01",$year_string_array[1]."-02",$year_string_array[1]."-03",$year_string_array[1]."-04",$year_string_array[1]."-05",$year_string_array[1]."-06",$year_string_array[1]."-07",$year_string_array[1]."-08");
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
  function getDateOnSUnday($date){
    $array = explode('_',$date);
    $start_date = $array[0];
    $date_array = explode('-',$start_date);
    return $date_array[2];
  }
  function getStartAndEndDates($year_string){
    $year_string_array = explode('_',$year_string);
    return array(
      'start_date' => $year_string_array[0],
      'end_date'   => $year_string_array[1]
    );
  }
  function getTotalUsersOnLeave($year_string,$selected_unit_id){
    $year_string_array = explode('_',$year_string);
    $start_date = $year_string_array[0];
    $end_date = $year_string_array[1];
    $result = array(); 
    $CI =& get_instance();
    $CI ->load->model('Reports_model');
    $result=$CI->Reports_model->getTotalUsersOnLeave($start_date,$end_date,$selected_unit_id);
    return count($result);
  }
  function showUserStatus($available_requestid){
    $CI =& get_instance();
    $CI ->load->model('Reports_model');
    $result =$CI->Reports_model->showUserStatus($available_requestid);
    $users = $result;
    $rejected_users = '';
    $accepted_users = '';
    $pending_users = '';
    $deleted_users = '';
    $total_requets = 0;
    $a_count = 0;
    $r_count = 0;
    $p_count = 0;
    $d_count = 0;
    $html = '';
    // print("<pre>".print_r($result,true)."</pre>");exit();
    if(count($users) > 0){
      $total_requets = count($users);
      for ($i = 0; $i < count($users); $i++) {
        if($users[$i]['status'] == 1){
          $a_count++;
          if($accepted_users ==''){
            $accepted_users .= $users[$i]['fname'] .' '.$users[$i]['lname']; 
          }else{
            $accepted_users .= ','.$users[$i]['fname'] .' '.$users[$i]['lname'];
          }
          if($users[$i]['message']){
            $accepted_users .= $accepted_users .'('.$users[$i]['message'].')';
          }              
        }else if($users[$i]['status'] == 2){
          $r_count++;
          if($rejected_users ==''){
            $rejected_users .= $users[$i]['fname'] .' '.$users[$i]['lname']; 
          }else{
            $rejected_users .= ','.$users[$i]['fname'] .' '.$users[$i]['lname']; 
          }
          if($users[$i]['message']){
            $rejected_users .= $rejected_users .'('.$users[$i]['message'].')';
          }   
        }else if($users[$i]['status'] == 0){
          $p_count++;
          if($pending_users ==''){
            $pending_users .= $users[$i]['fname'] .' '.$users[$i]['lname']; 
          }else{
            $pending_users .= ','.$users[$i]['fname'] .' '.$users[$i]['lname']; 
          }
          if($users[$i]['message']){
            $pending_users .= $pending_users .'('.$users[$i]['message'].')';
          } 
        }else{
          $d_count++;
          if($deleted_users ==''){
            $deleted_users .= $users[$i]['fname'] .' '.$users[$i]['lname']; 
          }else{
            $deleted_users .= ','.$users[$i]['fname'] .' '.$users[$i]['lname']; 
          }
          if($users[$i]['message']){
            $deleted_users .= $deleted_users .'('.$users[$i]['message'].')';
          } 
        }
      }
      if(substr($accepted_users,1) == ''){
        $accepted_users = 'No records ';
      }
      if(substr($rejected_users,1) == ''){
        $rejected_users = 'No records';
      }
      if(substr($pending_users,1) == ''){
        $pending_users = 'No records';
      }
      if(substr($deleted_users,1) == ''){
        $deleted_users = 'No records';
      }
    }else{
      $accepted_users = 'No records';
      $rejected_users = 'No records';
      $pending_users  = 'No records';
      $deleted_users  = 'No records';
    }
    $html .= '<label style="font-weight: bold;text-decoration:underline;">Accepted'.'('.$a_count.')'.'</label><br>';
    $html .= "<p class='accepted_users'>".$accepted_users."</p><br>";
    $html .= '<label style="font-weight: bold;text-decoration:underline;">Rejected'.'('.$r_count.')'.'</label>';
    $html .= "<p class='rejected_users'>".$rejected_users."</p><br>";
    $html .= '<label style="font-weight: bold;text-decoration:underline;">Deleted'.'('.$d_count.')'.'</label>';
    $html .= "<p class='deleted_users'>".$deleted_users."</p><br>";
    $html .= '<label style="font-weight: bold;text-decoration:underline;">Pending'.'('.$p_count.')'.'</label><br>';
    $html .= "<p class='pending_users'>".$pending_users."</p><br>";
    return $html;
  }
?>
