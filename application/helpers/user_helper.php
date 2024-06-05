<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 
function getSchedule($date, $userid){
    $result = array();
        $CI =& get_instance();
        $CI->db->select('unit.unit_name,master_shift.id,master_shift.shift_name,master_shift.total_targeted_hours as shift_hours,master_shift.start_time,master_shift.end_time,master_shift.targeted_hours,master_shift.shift_category');
      $CI->db->from('rota_schedule');
      $CI->db->join('unit', 'rota_schedule.unit_id = unit.id');
      $CI->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
      
      $CI->db->where('rota_schedule.user_id', $userid);
      $CI->db->where('rota_schedule.date', $date);
      $result = $CI->db->get();
    // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
      if(count($result)>1)
      {   
         $result_array[]=array('');
          foreach ($result as $value) {
            if($value['shift_name']=='Offday')
            {
              unset($result_array);
            }
            else
            {
             $result_array[0]=$value;
            }
          }
          $new_result=$result_array;
      }
      else
      {
          $new_result=$result;
      }
    //print "<pre>";  print_r($new_result); print '<br>';
      //exit();
    return $new_result;
}
function getId()
{
  $CI = & get_instance();  //get instance, access the CI superobject
  $user_id = $CI->session->userdata('user_id');
  return $user_id;
}

function getDateAndTime($date,$user_id)
{   
      $result = array();
      $CI =& get_instance();
      $CI->db->select('time_to,time_from');
      $CI->db->from('time_log');       
      $CI->db->where('user_id', $user_id);
      $CI->db->where('date', $date);
      $result = $CI->db->get();
    // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
      //print_r($result);exit();
    return $result;

}

function getLogedUnit($unit)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('unit.unit_name');
      $CI->db->from('unit');       
      $CI->db->where('id', $unit); 
      $result = $CI->db->get();
      // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
    return $result;

}

function getActualunit($user_id)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('unit.unit_name');
      $CI->db->from('unit'); 
      $CI->db->join('user_unit', 'user_unit.unit_id = unit.id');    
      $CI->db->where('user_unit.user_id', $user_id); 
      $result = $CI->db->get();
      // print $CI->db->last_query();
      // exit();
      $result = $result->result_array();
    return $result;

}

function GetCensus($date,$user_id)
{
     $result = array();
      $CI =& get_instance();
      $CI->db->select('comment');
      $CI->db->from('daily_senses'); 
      $CI->db->where('user_id', $user_id); 
      $CI->db->where('date', $date);   
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();

      $result = $result->result_array();   
      return $result;

}

function getTrainingtime($userid,$date){
    $result = array();
        $CI =& get_instance();
        $CI->db->select('master_training.time_from,master_training.time_to');
      $CI->db->from('master_training');
      $CI->db->join('training_staff', 'training_staff.training_id = master_training.id');  
      $CI->db->group_start();
      $CI->db->where('master_training.date_from >=', $date);
      $CI->db->where('master_training.date_to <=', $date);
      $CI->db->group_end();
      $CI->db->where('training_staff.user_id', $userid);
      $result = $CI->db->get();
    // print $CI->db->last_query();
    //   exit();
      $result = $result->result_array();
    return $result;
}

function gettimeDiffcheckin($time,$time_to, $from, $to, $rotadate){  //print_r($time);

    $CURRENTTIME = new DateTime(date('Y-m-d H:i:s'));  
     $CURRENT=strtotime($CURRENTTIME->format('Y-m-d H:i:s')); //print_r($CURRENT);
     $SHIFT_TIME  = strtotime($rotadate." ".$time); //print_r($SHIFT_TIME);exit();
//$CURRENT = '1582658000';
  //$from='19:22';
    if (($CURRENT  >= $SHIFT_TIME ) && $from=='') 
    {
       $status='True';
    }  
    elseif(($CURRENT  < $SHIFT_TIME) && $from=='')
    {
      $status='None';
    }
    else
    {
      $status='False';
    }
    $result=array('check'=>'IN','status'=>$status,'shift_time'=>$SHIFT_TIME,'from'=>$from,"currenttime"=> $CURRENT);
      return $result;

}

function gettimeDiffcheckout($time_from,$shifttime,$from, $to, $rotadate,$shiftid){  
     $time_new=date('H:i:s');  
     $CURRENTTIME = new DateTime(date('Y-m-d H:i:s')); 
     
     if($shiftid==16)
     {
         $tomorrow = date('Y-m-d',strtotime($rotadate . "+1 days"));  
         $SHIFT_TIME  = strtotime($tomorrow." ".$shifttime); 
         $TO_TIME=strtotime($tomorrow." ".$to); 
         $CURRENT=strtotime($tomorrow." ".$time_new);
         
     }
     else
     {
         $SHIFT_TIME  = strtotime($rotadate." ".$shifttime);
         $TO_TIME=strtotime($rotadate." ".$to);
         $CURRENT=strtotime($CURRENTTIME->format('Y-m-d H:i:s')); 
         
     }
     $SHIFT_START_TIME  = strtotime($rotadate." ".$time_from); //print 'shift_start time-'; print_r($SHIFT_START_TIME); print '<br>';
     $FROM_TIME=strtotime($rotadate." ".$from);// print 'login_start time-'; print_r($FROM_TIME); print '<br>';
 
  if($to=='00:00:00')
   $to='';

    if(($CURRENT  >= $SHIFT_START_TIME ) && $FROM_TIME>$SHIFT_START_TIME)
    { 
       $status='True1';
    }
    else if (($CURRENT  >= $SHIFT_TIME ) && $to=='') 
    { 
       $status='True';
    } 
    else if (($CURRENT  >= $SHIFT_TIME ) && $TO_TIME<$SHIFT_TIME) 
    { 
      $status='True1';
    }
    else
    {
      $status='False';
    }

    $result=array('check'=>'OUT','TO'=>$to,'status'=>$status,'to'=>$to,
        'shift_time'=>date("d/m/Y H:i:s",$SHIFT_TIME),
        "currenttime"=> $CURRENTTIME->format('d/m/Y H:i:s'),
        'shift_TIME'=>$SHIFT_TIME,
        "current_TIME"=> $CURRENT,'data_time'=>$data['current_time']
    );
      return $result;

}
function getUserCheckInTime($user_id,$date){
    $result = array();
    $CI =& get_instance();
    $CI->db->select('time_from');
    $CI->db->from('time_log'); 
    $CI->db->where('date', $date); 
    $CI->db->where('user_id', $user_id); 
    $CI->db->order_by("creation_date", "asc");
    $result = $CI->db->get();
    $result = $result->result_array();
    $checkin_time = '';
    if(count($result)>0)
    {
        $checkin_time = $result[0]['time_from'];
    }
    return $checkin_time;
}
function CheckPermission($user_type,$status)
{
      $result = array();
      $CI =& get_instance();
      $CI->db->select('group_permissions.status');
      $CI->db->from('permissions'); 
      $CI->db->join('group_permissions', 'group_permissions.permission_id = permissions.id'); 
      $CI->db->where('permissions.name', $status); 
      $CI->db->where('group_permissions.group_id', $user_type); 
      $result = $CI->db->get();
      // print $CI->db->last_query();
      //   exit();
      $result = $result->result_array(); 
      //print_r(count($result));exit();
      if(count($result)>0)
      {
            $results='True';
      } 
      else
      {
            $results='False';
      }

      return $results;
}


?>