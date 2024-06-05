<?php
class Rotaschedule_model extends CI_Model 
{

  public function getRotaschedules($rota_id)
      {
        $this->db->select('*');
    $this->db->from('rota_schedule');
    $this->db->where('rota_id', $rota_id);
    $this->db->order_by('user_id', 'asc');
    $this->db->order_by('date', 'asc');
    $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
      public function checkUserHasRota($params){
    $this->db->select('*');
    $this->db->from('rota_schedule');
    $this->db->where('user_id', $params['user_id']);
    $this->db->where('date', $params['date']);
    $this->db->where('shift_id >', 4);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }

    public function getOffDaysOfUser($params)
    {
      $this->db->select('rota_schedule.date');
      $this->db->from('rota_schedule');
      $this->db->where('user_id', $params['user_id']);
      $this->db->where('shift_id', 0);
      $this->db->where('date >=', $params['start_date']);
      $this->db->where('date <=', $params['end_date']);
      $this->db->order_by('user_id', 'asc');
      $this->db->order_by('date', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function getScheduledataByDate($user_id,$unit_ids,$date){
      $this->db->select('rota_schedule.*,master_shift.shift_name');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
      $this->db->where('rota_schedule.date', $date);
      $this->db->where('rota_schedule.user_id', $user_id);
      $this->db->where_in('rota_schedule.unit_id', $unit_ids);
      $this->db->order_by('rota_schedule.shift_id', 'desc');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result[0];
    }
    public function findrota($params)
    {
      $this->db->select('*');
      $this->db->from('rota_schedule');
      $this->db->where('rota_schedule.date', $params['date']);
      $this->db->where('rota_schedule.user_id', $params['user_id']);
      $this->db->where_in('rota_schedule.unit_id', $params['unit_id']);
      $this->db->order_by('rota_schedule.date', 'asc');
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result[0];
    }

    public function getshiftDatas($id)
    {
      $this->db->select('*');
      $this->db->from('master_shift');
      $this->db->where('master_shift.id', $id);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result[0];

    }
    public function getPublishedRotaOfUser($params,$request_dates){
      $this->db->select('rota.id,rota_schedule.date,rota_schedule.shift_id');
      $this->db->from('rota');
      $this->db->join('rota_schedule', 'rota_schedule.rota_id = rota.id');
      $this->db->where('rota.start_date', $params['start_date']);
      $this->db->where('rota.end_date', $params['end_date']);
      $this->db->where('rota.published', 1);
      $this->db->where('rota_schedule.user_id', $params['user_id']);
      $this->db->where('rota_schedule.shift_id !=', null);
      if(count($request_dates) > 0){
        $this->db->where_in('rota_schedule.date', $request_dates);
      }
      $this->db->order_by('rota_schedule.date', 'asc');
      $this->db->order_by('rota_schedule.shift_id', 'desc');
      $query = $this->db->get();
      $result = $query->result_array();
      $final_result = $this->removeDuplicates($result);
      return $final_result;
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
    public function markSickOrAbsent($params)
    {
      $updateData = array(
        'shift_id'=>$params['shift_id'],
        'shift_hours'=>0,
        'shift_category'=>0
      );
      $this->db->where('user_id', $params['user_id']);
      //$this->db->where('unit_id', $params['unit_id']);
      $this->db->where('date', $params['date']);
      $this->db->update('rota_schedule', $updateData);
      if($this->db->affected_rows() > 0 ){
        return "true";
      }else{
        return "flase";
      }
    }
    public function checkDataExistWithSameDate($params,$unit_id){
        $this->db->select('*');
        $this->db->from('rota_schedule');
        $this->db->where('date', $params['date']);
        $this->db->where('user_id', $params['user_id']);
        $this->db->where('unit_id', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
      }
    public function findRotaIds($params,$unit_id)
      {
        $this->db->select('rota_schedule.rota_id');
        $this->db->from('rota_schedule');
        $this->db->where('date', $params['date']);
        $this->db->where('unit_id', $unit_id);
        // $this->db->where('shift_id = 3');
        // $this->db->or_where('shift_id = 4');
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
          $result = $query->result_array();
          $this->db->select('rota_schedule.date');
          $this->db->from('rota_schedule');
          $this->db->where('rota_id', $result[0]['rota_id']);
          $query1 = $this->db->get();
          $date_array = $query1->result_array();
          $rota_id = $result[0]['rota_id'];
          return array(
            'date_array' => $date_array,
            'rota_id' => $rota_id
          );
        }
        else{
          return array(
            'date_array' => [],
            'rota_id' => ""
          );
        }
      }
    public function updateUnitIdOfUser($user_id,$unit_id){
      $updateData = array(
        'unit_id'=>$unit_id
      );
      $this->db->where('user_id', $user_id);
      $this->db->update('rota_schedule', $updateData);
      if($this->db->affected_rows() > 0 ){
        return "true";
      }else{
        return "flase";
      }
    }
    public function updateSingleScheduleEntry($params,$id){
      $updateData = array(
        'shift_id'=>$params['shift_id'],
        'shift_hours'=>$params['targeted_hours'],
        'shift_category'=>$params['shift_category'],
        'from_unit'=>$params['from_unit'],
        'unit_id'=>$params['unit_id'],
        'request_id'=>$params['request_id']
      );
      $this->db->where('id', $id);
      $this->db->update('rota_schedule', $updateData);
      if($this->db->affected_rows() > 0 ){
        return "true";
      }else{
        return "flase";
      }
    }
    public function updateSingleEntry($params){
      $updateData = array(
        'shift_id'=>$params['shift_id'],
        'shift_hours'=>$params['shift_hours'],
        'shift_category'=>$params['shift_category']
      );
      $this->db->where('user_id', $params['user_id']);
      $this->db->where('date', $params['date']);
      $this->db->update('rota_schedule', $updateData);
      if($this->db->affected_rows() > 0 ){
        return "true";
      }else{
        return "flase";
      }
    }

    public function updateadditionalhours($params)
    {

      $updatehours=array(

        'additional_hours'=>$params['hours'],
        'day_additional_hours'=>$params['day_additional_hours'],
        'night_additional_hours'=>$params['night_additional_hours'],
        'additinal_hour_timelog_id'=>$params['timelogid'],
        'comment'=>$params['comment'],
        'updation_date'=>date('Y-m-d H:i:s'),
        'updated_userid'=>$params['upated_userid']);

     // $this->db->where('shift_id', $params['shift_id']);
      $this->db->where('date', $params['date']);
      $this->db->where('user_id', $params['user_id']);
      //$this->db->where('unit_id', $params['unit_id']);
      $this->db->update('additional_hours', $updatehours);
      //echo $this->db->last_query();exit;
      if($this->db->affected_rows() > 0 )
      {
        return "true";
      }
      else
      {
        return "false";
      }

    }
    public function insertadditionalhours($params)
    {

      $details=array(
        'date'=>$params['date'],
        'user_id'=>$params['user_id'],
        'unit_id'=>$params['unit_id'],
        'additional_hours'=>$params['hours'],
        'day_additional_hours'=>$params['day_additional_hours'],
        'night_additional_hours'=>$params['night_additional_hours'],
        'additinal_hour_timelog_id'=>$params['timelogid'],
        'comment'=>$params['comment'],
        'creation_date'=>date('Y-m-d H:i:s'),
        'created_userid'=>$params['upated_userid'],
        'updation_date'=>date('Y-m-d H:i:s'),
        'updated_userid'=>$params['upated_userid']);

      $this->db->insert('additional_hours',$details); 
      //echo $this->db->last_query();exit;
      if($this->db->affected_rows() > 0 )
      {
        return "true";
      }
      else
      {
        return "false";
      }

    }


    public function gethours($params)
    {
      $this->db->select('additional_hours,comment,day_additional_hours,night_additional_hours');
      $this->db->from('additional_hours');
      //$this->db->where('shift_id', $params['shift_id']);
      $this->db->where('date', $params['date']);
      $this->db->where('user_id', $params['user_id']);
      // if($params['timelogid']!='')
      // {
      // $this->db->where('additinal_hour_timelog_id', $params['timelogid']); 
      // }
      //$this->db->where('unit_id', $params['unit_id']); 
      $query = $this->db->get();
    // echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
     
    }
    public function findparentunit($unit)
    {
      $this->db->select('unit.parent_unit');
      $this->db->from('unit');
      $this->db->where('id', $unit); 
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array(); 
      if(count($result) > 0){
            $parent_unit=$result[0]['parent_unit'];
            // print_r($parent_unit);exit();
            if($parent_unit==0)
            {
              return 0;
            }
            else
            {
              return $parent_unit;
            }}
            else{
              return 0;
            }
      
      
    }
    
    public function addDataToLeavelog($params){
      $details = array(
        'shift_id' => $params['old_shiftid'],
        'user_id'  => $params['user_id'],
        'unit_id'  => $params['unit_id'],
        'date'     => $params['date'],
        'created_date' => date('Y-m-d H:i:s'),
        'shift_name' => $params['shift_name'],
        'leave_type' => $params['leave_type']
      );
      $query = $this->db->insert('leave_log',$details);
    }
    public function checkLeaveCount($unit_id,$actual_date){
      $this->db->select('
        rota_schedule.user_id,
        rota_schedule.shift_id,
        rota_schedule.unit_id,
        rota_schedule.rota_id,
        rota_schedule.date,
        rota_schedule.shift_category,
        rota_settings.night_shift_min,
        rota_settings.day_shift_min'
      );
      $this->db->from('rota_schedule');
      $this->db->join('rota', 'rota_schedule.rota_id = rota.id');
      $this->db->join('rota_settings', 'rota.rota_settings = rota_settings.id');
      $this->db->where('rota_schedule.unit_id', $unit_id);
      $this->db->where('rota_schedule.date', $actual_date);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        // $leave_array = array(0,1, 2, 3, 4); 
        $leave_array = array(3, 4); 
        $result = $query->result_array();
        $total_staffs = count($result);
        $day_shift_min = $result[0]['day_shift_min'];
        $night_shift_min = $result[0]['night_shift_min'];
        $staffs_on_leave = 0;
        $staffs_on_night = 0;
        $staffs_on_day = 0;
        for($i=0;$i<$total_staffs;$i++){
          if (in_array($result[$i]['shift_id'], $leave_array)){
            $staffs_on_leave++;
          }
          if($result[$i]['shift_category'] == 1){
            $staffs_on_day++;
          }else if($result[$i]['shift_category'] == 2){
            $staffs_on_night++;
          }else{
            //Do nothing
          }
        }
        return array(
          // 'staff_details' => $result,
          'total_staffs' => $total_staffs,
          'staffs_on_leave' => $staffs_on_leave,
          'staffs_on_night' => $staffs_on_night,
          'staffs_on_day' => $staffs_on_day,
          'day_shift_min' => $day_shift_min,
          'night_shift_min' => $night_shift_min
        );
      }else{
        return array(
          // 'staff_details' => [],
          'total_staffs' => 0,
          'staffs_on_leave' => 0,
          'staffs_on_night' => 0,
          'staffs_on_day' => 0,
          'day_shift_min' => 0,
          'night_shift_min' => 0
        );
      }
    }
    public function addShiftDetails($shiftDetails){
      $query = $this->db->insert('rota_schedule',$shiftDetails);
    }
      public function deleteShifts($rota_id){
          $this->db->delete('rota_schedule', array('rota_id' => $rota_id));
      }

      public function findrotadetails($start_date,$end_date,$user_id,$unit,$selected_unit)
      {  // print_r($unit);exit();

    //     if($selected_unit!=='')
    //   {
    //   $this->db->select('id');
    //   $this->db->from('unit'); 
    //   $this->db->where('parent_unit', $selected_unit);
    //   $query = $this->db->get();
    //   $result = $query->result_array();
    // }
    //print_r($result);exit();

        $data=array('rota_schedule.user_id','rota_schedule.unit_id','rota_schedule.from_unit','unit.unit_shortname','master_shift.shift_shortcut');
        $this->db->select('*');
        $this->db->from('rota_schedule');
        $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->where('user_id', $user_id);
        $this->db->group_start();
        $this->db->where('from_unit', $unit);
        $this->db->or_where('rota_schedule.unit_id', $unit);
        // if(!empty($result))
        // {
        //   $this->db->group_start();
        //   $this->db->where('rota_schedule.unit_id!=', $selected_unit);
        //   foreach ($result as $value) 
        //   {
        //        $this->db->or_where('rota_schedule.unit_id!=',$selected_unit);
        //   }
        $this->db->group_end();
        // }
        // else
        // {
          $this->db->where('rota_schedule.unit_id!=', $selected_unit);
        // }
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=',  $end_date);
        $query = $this->db->get();
 
          //echo $this->db->last_query();//exit;
 
        $result = $query->result_array();
        if(count($result) > 0)
          {
              return $result;
          }
          else{
              return 0;
          }
      }
      public function findrotadetailsV2 ($start_date,$end_date,$user_id,$unit,$selected_unit)
      {  // print_r($unit);exit();
          
          if($selected_unit!=='')
          {
              $this->db->select('id');
              $this->db->from('unit');
              $this->db->where('parent_unit', $selected_unit);
              $query = $this->db->get();
              $result = $query->result_array();
          }
          //print_r($result);exit();
          
          $data=array('rota_schedule.user_id','rota_schedule.unit_id','rota_schedule.from_unit','unit.unit_shortname','master_shift.shift_shortcut,rota_schedule.shift_id');
          $this->db->select('*');
          $this->db->from('rota_schedule');
          $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
          $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
          $this->db->where('user_id', $user_id);
 
        /*   $this->db->where('from_unit', $unit); removed in V2  april 7*/
 
          if(!empty($result))
          {
              $this->db->group_start();
              $this->db->where('rota_schedule.unit_id!=', $selected_unit);
              foreach ($result as $value)
              {
                  $this->db->or_where('rota_schedule.unit_id!=',$selected_unit);
              }
              $this->db->group_end();
          }
          else
          {
              $this->db->where('rota_schedule.unit_id!=', $selected_unit);
          }
          $this->db->where('date >=', $start_date);
          $this->db->where('date <=',  $end_date);
          $query = $this->db->get();
 
              $result = $query->result_array();
              if(count($result) > 0)
              {
                  return $result;
              }
              else{
                  return 0;
              }
 
      }
      public function checkassigned($params = array())
      {
          $this->db->select('*');
          $this->db->from('rota_schedule');
          $this->db->where('user_id', $params['user_id']);
          $this->db->where('date', $params['date']);
          $this->db->where('shift_id !=0');
    
          $query = $this->db->get();
           // echo $this->db->last_query();exit;
          $result = $query->result_array();
          if(count($result) > 0)
          {
              return $result[0]['unit_id'];
          }
          else{
              return 0;
          }
      }
      public function checkUnitidIsSubUnitOfMainUnit($id){
        $sub_unit_ids = array();
        $this->db->select('id'); 
        $this->db->from('unit');
        $this->db->where('parent_unit', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        for($i=0;$i<count($result);$i++){
          array_push($sub_unit_ids, $result[$i]['id']);
        }
        return $sub_unit_ids;
      }
      public function checkUserinRotaSchedule($userid, $rotaid)
      {
          $this->db->select('shift_id');
          $this->db->from('rota_schedule');
          $this->db->where('user_id', $userid);
          $this->db->where('rota_id', $rotaid);
          
          $query = $this->db->get();
          // echo $this->db->last_query();exit;
          $result = $query->result_array();
          $shifts = array();
          if(count($result) > 0)
          {   $i=0;
              foreach ($result as $shift) {
                  $shifts[$userid][$i] = $shift['shift_id'];
                  $i++;
              }
              
        
              return $shifts;
          }
          else{
              return array();
          }
      }
      public function getAlreadyAssignedUsers($rota_id,$unit_id){
        $data = array(
          'rota_schedule.user_id',
          'rota_schedule.date',
          'rota_schedule.from_unit',
          'users.email',
          'users.default_shift',
          'personal_details.fname',
          'personal_details.lname'
        );
        $this->db->select($data);
        $this->db->from('rota_schedule');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
        $this->db->join('users', 'users.id = personal_details.user_id');
        $this->db->where('rota_schedule.rota_id', $rota_id);
        if($unit_id != ''){
          $this->db->where('rota_schedule.unit_id', $unit_id);
        }
        $this->db->where('users.status', 1);
        $this->db->group_by('rota_schedule.user_id'); 
        $final_result = array();
        $array_count = 0;
        $query = $this->db->get();
       // echo $this->db->last_query();exit();
        if($query->num_rows() > 0){
          $count = count($query->result_array());
          $result = $query->result_array();
          for($i=0;$i<$count;$i++){
            $details = array(
              'user_id'  => $result[$i]['user_id'],
              'from_unit'=> $result[$i]['from_unit'],
              'shift_id' => $result[$i]['default_shift']
            );
            array_push($final_result, $details);
          }
          return array(
            'user_ids' => $final_result,
          );
        }else{
          return array(
            'user_ids' => $final_result,
          );
        }
      }
      public function getUserDetails($user_id){

        $data = array(
          'users.id',
          'users.default_shift',
          'personal_details.fname',
          'personal_details.lname',
          'master_designation.designation_code',
          'master_shift.shift_name',
          'master_shift.shift_shortcut',
          'personal_details.gender'
        );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
        $this->db->where('users.id', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
      }
      public function getHolidayDates($user_ids){
        $this->db->select('*');
        $this->db->from('holliday');
        $this->db->where_in('user_id', $user_ids);
        $this->db->where('status', 1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
 
        if($query->num_rows() > 0){
          $result = $query->result_array();
          return $result;
        }else{
          return [];
        }
      }
      public function getTrainingTitle($date){
        $data = array(
          'master_training.title'
        );
        $this->db->select($data);
        $this->db->from('master_training');
        $this->db->where('date_from >=', $date);
        $this->db->where('date_to <=', $date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['title'];
      }
      public function getTrainingDates($user_ids){
        $data = array(
          'training_staff.training_id',
          'training_staff.user_id',
          'master_training.date_from',
          'master_training.date_to',
          'master_training.title'
        );

        $this->db->select($data);
        $this->db->from('training_staff');
        $this->db->join('master_training','master_training.id = training_staff.training_id');
        $this->db->where_in('training_staff.user_id', $user_ids);
        $query = $this->db->get();
        if($query->num_rows() > 0){
          $result = $query->result_array();
          return $result;
        }else{
          return [];
        }
      }

      public function getOtherUnitshiftByDate($user_id,$unit_id)
      {
        $data=array('rota_schedule.user_id','rota_schedule.unit_id','rota_schedule.from_unit','unit.unit_shortname','master_shift.shift_shortcut','rota_schedule.shift_id','rota_schedule.date');
        $this->db->select($data);
        $this->db->from('rota_schedule');
        $this->db->join('unit','unit.id = rota_schedule.unit_id');
        $this->db->join('master_shift','master_shift.id = rota_schedule.shift_id');
        $this->db->where_in('rota_schedule.user_id', $user_id);
        $this->db->where('rota_schedule.unit_id !=', $unit_id);
        $this->db->where('rota_schedule.shift_id >', 3);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        if($query->num_rows() > 0){
          $result = $query->result_array();
          return $result;
        }else{
          return [];
        }

      }

      public function getRotaSettings($rota_id){
        $data = array(
          'rota.rota_settings',
          'rota_settings.*'
        );
        $this->db->select($data);
        $this->db->from('rota');
        $this->db->join('rota_settings','rota_settings.id = rota.rota_settings');
        $this->db->where('rota.id',$rota_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        if($query->num_rows() > 0){
          $result = $query->result_array();
          return $result;
        }else{
          return [];
        }
      }
    public function getAvailableStaffs($params){ 
      //If unit_type is selected then have to find all the unit of
      //specified type. And make an array of unit ids
      $unit_id_array = array();
      if($params['unittype'] != 0){
        $this->load->model('User_model');
        $unit_data=$this->User_model->fetchCategoryTreeforavailability(' ',' ',' ',$params['unittype']); 
        foreach ($unit_data as $unit) {
          array_push($unit_id_array, $unit['id']);
        }
      }
      $final_result = array();
      if($params["date"] != "" && $params["shift_id"] != ""){
        $shift_ids = array();
        array_push($shift_ids, $params["shift_id"]);
        //new addition. For returning the EL shift if user seraches E or L
        if($params["shift_id"] == '1008' || $params["shift_id"] == '1009'){
          array_push($shift_ids, '1010');
        }
        if($params['unit'] != '' && $params['unit']!= 0 ){
          $this->db->select('id');
          $this->db->from('unit'); 
          $this->db->where('parent_unit', $params['unit']);
          $query = $this->db->get();
          $result = $query->result_array();
        }else{
          $result = [];
        }
        $this->db->select("staffrota_schedule.*,users.email,master_designation.designation_name,personal_details.mobile_number,personal_details.fname,personal_details.lname,users.id as user_id,user_unit.unit_id as user_unitid,master_shift.shift_shortcut,master_shift.start_time,master_shift.end_time,master_shift.id as master_shiftid");
        $this->db->from('staffrota_schedule');
        $this->db->join('users','staffrota_schedule.user_id = users.id');
        $this->db->join('user_unit','user_unit.user_id = users.id');
        $this->db->join('personal_details','personal_details.user_id = users.id');
        $this->db->join('master_designation','master_designation.id = users.designation_id');
        $this->db->join('available_master_shift','staffrota_schedule.shift_id = available_master_shift.id');
        $this->db->join('master_shift','available_master_shift.parent_id = master_shift.id');
        $this->db->where('staffrota_schedule.date = ',$params["date"]);
        // $this->db->where('staffrota_schedule.user_id = ',74);
        $this->db->where_in('staffrota_schedule.shift_id',$shift_ids);
        if(count($unit_id_array) > 0){
          $this->db->where_in('staffrota_schedule.unit_id',$unit_id_array);
        }else{
          if(empty($result))
          {
            $this->db->where('staffrota_schedule.unit_id = ',$params["unit"]);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('staffrota_schedule.unit_id = ',$params["unit"]);
            foreach ($result as $value) {
              $this->db->or_where('staffrota_schedule.unit_id = ',$value['id']);
             }
            $this->db->group_end();
          } 
        }
        $query = $this->db->get();
        // print_r($this->db->error());exit(); 
        // echo $this->db->last_query();exit();
        if($query->num_rows() > 0)
        {
          $result = $query->result_array();
          // print("<pre>".print_r($result,true)."</pre>");exit();
          for($i=0;$i<count($result);$i++){
            $rota_result = $this->checkRotaExists($result[$i]['user_id'],$result[$i]['date']);
            if($rota_result['status'] == true)
            {
              $result[$i]['rota_status'] = 1;
              // array_push($final_result, $result[$i]);
            }
            else
            {
              $result[$i]['rota_status'] = 2;
              $result[$i]['rota_shift_id'] = $rota_result['shift_id'];
              $result[$i]['unit_shortname'] = $rota_result['unit_shortname'];
              $result[$i]['unit_name'] = $rota_result['unit_name'];
            }
          }
          $final_result_array = $this->checkHoursGap($result,$params['date']);
          return $final_result_array;
        }
        else
        {
          return [];
        }
      }
      else
      {
        return [];
      }
    }
    public function checkHoursGap($result,$date){
      $this->load->model('Rota_model');
      $shift_gap_result = $this->Rota_model->getShiftgaphours();
      $new_results_array = array();
      $next_date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
      $prev_date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
      $this->load->model('Shift_model');
      $shift_gap = $shift_gap_result[0]['shift_gap'];
      for($i=0;$i<count($result);$i++){
        $prev_next_shifts = $this->Shift_model->getPrevNextShift($prev_date,$next_date,$result[$i]['user_id']);
        if(count($prev_next_shifts) > 0){
          $params['shift_starttime'] = $result[$i]['start_time'];
          $params['shift_endtime'] = $result[$i]['end_time'];
          $params['next_date'] = $next_date;
          $params['prev_date'] = $prev_date;
          $params['date'] = $date;
          $status = $this->checkTimeDifference($prev_next_shifts,$params);
          if($status == false){
            $result[$i]['time_gap'] = 'There is no '.$shift_gap.' hours difference between shifts.';
          }
        }
      }
      return $result;
    }
    public function checkTimeDifference($prev_next_shifts,$params){
      $this->load->model('Rota_model');
      $shift_gap_result = $this->Rota_model->getShiftgaphours();
      $shift_gap = $shift_gap_result[0]['shift_gap'];
      $c_shift_starttime = $params['shift_starttime'];
      $c_shift_endtime = $params['shift_endtime'];
      $c_date = $params['date'];
      $p_date = $params['prev_date'];
      $n_date = $params['next_date'];
      $checking_time = strtotime('12:00:00');
      $p_shift_starttime = "00:00:00";
      $p_shift_endtime = "00:00:00";
      $n_shift_starttime = "00:00:00";
      $n_shift_endtime = "00:00:00";
      foreach ($prev_next_shifts as $prev_next_shift) {
        if($prev_next_shift['date'] == $params['prev_date']){
          $p_shift_starttime = $prev_next_shift['start_time'];
          $p_shift_endtime = $prev_next_shift['end_time'];
        }
        if($prev_next_shift['date'] == $params['next_date']){
          $n_shift_starttime = $prev_next_shift['start_time'];
          $n_shift_endtime = $prev_next_shift['end_time'];
        }
      }
      if (strtotime($p_shift_endtime) < $checking_time) {
        $p_day_endtime_date = date('Y-m-d', strtotime('+1 day', strtotime($p_date))).' '.$p_shift_endtime;
      }else{
        $p_day_endtime_date = $p_date.' '.$p_shift_endtime;
      }
      if (strtotime($c_shift_endtime) < $checking_time) {
        $c_day_endtime_date = date('Y-m-d', strtotime('+1 day', strtotime($c_date))).' '.$c_shift_endtime;
      }else{
        $c_day_endtime_date = $c_date.' '.$c_shift_endtime;
      }
      $c_day_starttime_date = $c_date.' '.$c_shift_starttime;
      $n_day_starttime_date = $n_date.' '.$n_shift_starttime;

      $p_day_difference = $this->findTimeDifference($p_day_endtime_date,$c_day_starttime_date);
      $n_day_difference = $this->findTimeDifference($c_day_endtime_date,$n_day_starttime_date);
  
      $p_status = $this->checkZeroHours($p_shift_endtime,$c_shift_starttime);
      $n_status = $this->checkZeroHours($c_shift_endtime,$n_shift_starttime);
      /*print $p_day_difference;echo "<br>";
      print $n_day_difference;echo "<br>";
      print $p_status;echo "<br>";
      print $n_status;echo "<br>";
      exit();*/
      $final_status = true;
      if($p_status == true && $n_status==true){
      }else if($p_status == false && $n_status == false){
        if($p_day_difference < $shift_gap || $n_day_difference < $shift_gap){
          $final_status = false;
        }
      }else if($p_status == false && $n_status == true){
        if($p_day_difference < $shift_gap){
          $final_status = false;
        }
      }else if($n_status == false && $p_status == true){
        if($n_day_difference < $shift_gap){
          $final_status = false;
        }
      }else{
      }
      return $final_status;
    }
    public function findTimeDifference($start_time,$end_time){
      $timestamp1 = strtotime($start_time);
      $timestamp2 = strtotime($end_time);
      $hour = abs($timestamp2 - $timestamp1)/(60*60);
      return $hour;
    }
    public function checkZeroHours($start_time,$end_time){
      if(explode(":", $start_time)[0] == "00" || explode(":", $end_time)[0] == "00"){
        return true;
      }else{
        return false;
      }
    }
    public function checkRotaExists($id,$date){
      $this->db->select('rota_schedule.shift_id,master_shift.shift_shortcut,unit.unit_name,unit.unit_shortname');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
      $this->db->join('unit', 'rota_schedule.unit_id = unit.id');
      $this->db->where('rota_schedule.user_id',$id);
      $this->db->where('rota_schedule.date',$date);
      $this->db->where('rota_schedule.shift_id !=',null);
      $this->db->where('rota_schedule.shift_id !=',0);
      $this->db->where('rota_schedule.shift_id !=',1);
      $result = $this->db->get();
      $data = $result->result_array();
      if(count($data) > 0){
        if($data[0]['shift_id'] > 1){
          return array(
            'status'  => false,
            'shift_id' => $data[0]['shift_shortcut'],
            'unit_name' => $data[0]['unit_name'],
            'unit_shortname' => $data[0]['unit_shortname'],
          );
        }else{
          return array(
            'status'  => true,
            'shift_id' => ''
          );
        }
      }else{
        return array(
          'status'  => true,
          'shift_id' => ''
        );
      }
   }
  public function updateStatus($params,$status){
    $this->db->select('available_requests.id');
    $this->db->from('available_requests');
    $this->db->where('available_requests.date',$params['date']);
    $this->db->where('available_requests.shift_id',$params['shift_id']);
    $this->db->where('available_requests.to_unitid',$params['unit_id']);
    $this->db->where('available_requests.from_unitid',$params['from_unitid']);
    $result = $this->db->get();
    $data = $result->result_array();
    $avl_request_id = $data[0]['id'];
    //Added new function for checking whether the request is in delete status.
    //No updation on deleted status requets, send a false status
    $this->db->select('status');
    $this->db->from('available_requested_users'); 
    $this->db->where('user_id', $params['user_id']);
    $this->db->where('avialable_request_id', $avl_request_id);
    $data_result = $this->db->get();
    $data_result_array = $data_result->result_array();
    if($data_result_array[0]['status'] == 3){
      return "deleted_request";
    }else{
      $updateData = array(
        'status'=>$status
      );
      $this->db->where('user_id', $params['user_id']);
      $this->db->where('avialable_request_id', $avl_request_id);
      $this->db->update('available_requested_users', $updateData);
      if($this->db->affected_rows() > 0 ){
        return "true";
      }else{
        return "false";
      }
    }
  }
  public function showUserStatus($params){
    $unit_id_array = array();
    if($params['unittype'] != 0){
      $this->load->model('User_model');
      $unit_data=$this->User_model->fetchCategoryTreeforavailability(' ',' ',' ',$params['unittype']); 
      foreach ($unit_data as $unit) {
        array_push($unit_id_array, $unit['id']);
      }
    }
    $this->db->select('available_requests.id,personal_details.user_id,personal_details.fname,personal_details.lname,available_requested_users.status,available_requests.request_count');
    $this->db->from('available_requests');
    $this->db->join('available_requested_users', 'available_requested_users.avialable_request_id = available_requests.id');
    $this->db->join('personal_details', 'personal_details.user_id = available_requested_users.user_id');
    $this->db->where('available_requests.date',$params['date']);
    $this->db->where('available_requests.shift_id',$params['parent_shift_id']);
    $this->db->where('available_requests.to_unitid',$params['user_unit_for']);
    if($params['unit_id_in']){
      $this->db->where('available_requests.from_unitid',$params['unit_id_in']);
    }else if(count($unit_id_array) > 0){
      $this->db->where_in('available_requested_users.unit_id',$unit_id_array);
    }else{
      //nothing
    }
    $query = $this->db->get();
    $result = $query->result_array();
    for($i=0;$i<count($result);$i++){
      $avalibility_result = $this->getUserAvailability($result[$i]['user_id'],$params['date'],$params['shift_id']);
      if($avalibility_result['status'] == false){
        $result[$i]['message'] = "User changed availability to ".$avalibility_result['shift_id'];
      }
    }
    return $result;
  }
  function getUserAvailability($user_id,$date,$shift_id){
    $this->db->select("staffrota_schedule.shift_id,staffrota_schedule.date,master_shift.shift_shortcut");
    $this->db->from('staffrota_schedule');
    $this->db->join('available_master_shift', 'available_master_shift.id = staffrota_schedule.shift_id');
    $this->db->join('master_shift', 'master_shift.id = available_master_shift.parent_id');
    $this->db->where('staffrota_schedule.date = ',$date);
    $this->db->where('staffrota_schedule.shift_id >= ',1000);
    $this->db->where_in('staffrota_schedule.user_id',$user_id);
    $query = $this->db->get();
    $result = $query->result_array();
    if($result[0]['shift_id'] == $shift_id){
      return array(
        'status' => true,
        'shift_id' => $result[0]['shift_shortcut']
      );
    }else{
      return array(
        'status' => false,
        'shift_id' => $result[0]['shift_shortcut']
      );
    }
  }
  public function deleteAllRequests($params){
    $this->db->select('available_requests.id');
    $this->db->from('available_requests');
    $this->db->where('available_requests.date',$params['date']);
    $this->db->where('available_requests.shift_id',$params['parent_shift_id']);    
    $this->db->where('available_requests.to_unitid',$params['user_unit_for']);
    if($params['unit_id_in']){
      $this->db->where('available_requests.from_unitid',$params['unit_id_in']);
    }
    // $this->db->where('available_requested_users.user_id',$params['user_id']);
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0 ){
      $avl_request_id = $result[0]['id'];
      $updateData = array(
        'status'=>3
      );
      // $this->db->where('user_id', $params['user_id']);
      $this->db->where('avialable_request_id', $avl_request_id);
      // $this->db->where('status', 0);
      $this->db->update('available_requested_users', $updateData);
      if($this->db->affected_rows() > 0 ){
        $rt_updateData = array(
          'shift_id' => 0,
          'shift_hours' => 0,
          'shift_category' => 0
        );
        $this->db->where('rota_schedule.request_id', $avl_request_id);
        $this->db->where('rota_schedule.date', $params['date']);
        $this->db->where('rota_schedule.unit_id', $params['user_unit_for']);
        $this->db->update('rota_schedule', $rt_updateData);
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
  public function deleteRequest($params){
    $this->db->select('available_requests.id');
    $this->db->from('available_requests');
    $this->db->where('available_requests.date',$params['date']);
    $this->db->where('available_requests.shift_id',$params['parent_shift_id']);
    $this->db->where('available_requests.to_unitid',$params['user_unit_for']);
    if($params['unit_id_in']){
      $this->db->where('available_requests.from_unitid',$params['unit_id_in']);
    }
    // $this->db->where('available_requested_users.user_id',$params['user_id']);
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0 ){
      $avl_request_id = $result[0]['id'];
      $updateData = array(
        'status'=>3
      );
      $this->db->where('user_id', $params['user_id']);
      $this->db->where('avialable_request_id', $avl_request_id);
      $this->db->where('status', 0);
      $this->db->update('available_requested_users', $updateData);
      if($this->db->affected_rows() > 0 ){
        /*$rt_updateData = array(
          'shift_id' => 0,
          'shift_hours' => 0,
          'shift_category' => 0
        );
        $this->db->where('rota_schedule.request_id', $avl_request_id);
        $this->db->where('rota_schedule.date', $params['date']);
        $this->db->where('rota_schedule.unit_id', $params['user_unit_for']);
        $this->db->where('rota_schedule.user_id', $params['user_id']);
        $this->db->update('rota_schedule', $rt_updateData);*/
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
  public function rejectRequest($params)
  {
    $this->db->select('*');
    $this->db->from('available_requests');
    $this->db->join('available_requested_users', 'available_requested_users.avialable_request_id = available_requests.id');
    $this->db->where('available_requests.date',$params['date']);
    $this->db->where('available_requests.shift_id',$params['shift_id']);
    $this->db->where('available_requests.to_unitid',$params['unit_id']);
    $this->db->where('available_requests.from_unitid',$params['from_unitid']);
    $this->db->where('available_requested_users.status',0);
    $this->db->where('available_requested_users.user_id',$params['user_id']);

    $request_query = $this->db->get();
    $result = $request_query->result_array();
    if($request_query->num_rows() > 0){
      $update_result = $this->updateStatus($params,2);
      if($update_result == "true"){
        return array(
          'status'  => $update_result,
          'message' => "Rejected request"
        );
      }else if($update_result == "false"){
        return array(
          'status'  => $update_result,
          'message' => "Already rejected request"
        );
      }else{
        return array(
          'status'  => $update_result,
          'message' => "No request found"
        );
      }
    }else{
      return array(
        'status'  => "false",
        'message' => "No request available for rejection"
      );
    }
  }
  public function getRequestId($params){
    $this->db->select('id');
    $this->db->from('available_requests');
    $this->db->where('date', $params['date']);
    $this->db->where('shift_id',$params['shift_id']);
    $this->db->where('to_unitid',$params['unit_id']);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['id'];
  }
  public function acceptRequest($params)
  {
    $this->db->select('*');
    $this->db->from('available_requests');
    $this->db->where('date', $params['date']);
    $this->db->where('shift_id',$params['shift_id']);
    $this->db->where('to_unitid',$params['unit_id']);
    $this->db->where('from_unitid',$params['from_unitid']);
    $query = $this->db->get();
    if($query->num_rows() > 0 ){
      $req_count_result = $query->result_array();
      $leave_log_count = $req_count_result[0]['request_count'];
      $this->db->select('*');
      $this->db->from('available_requests');
      $this->db->join('available_requested_users', 'available_requested_users.avialable_request_id = available_requests.id');
      $this->db->where('available_requests.date',$params['date']);
      $this->db->where('available_requests.shift_id',$params['shift_id']);
      $this->db->where('available_requests.to_unitid',$params['unit_id']);
      $this->db->where('available_requests.from_unitid',$params['from_unitid']);
      $this->db->where('available_requested_users.status',1);
      $request_query = $this->db->get();
      if($request_query->num_rows() > 0){
        $request_count = $request_query->num_rows();
        if($leave_log_count > $request_count){
          $update_result = $this->updateStatus($params,1);
          if($update_result == "true"){
            return array(
              'status'  => $update_result,
              'message' => "Request Accepted"
            );
          }else if($update_result == "false"){
            return array(
              'status'  => $update_result,
              'message' => "Already accepted request"
            );
          }else{
            return array(
              'status'  => "false",
              'message' => "No request found"
            );
          }
        }else{
          return array(
            'status' => "false",
            'message' => "No open slot found."
          );
        }
      }else{
        if($leave_log_count > 0){
          $update_result = $this->updateStatus($params,1);
          if($update_result == "true"){
            return array(
              'status'  => $update_result,
              'message' => "Request Accepted"
            );
          }else if($update_result == "false"){
            return array(
              'status'  => $update_result,
              'message' => "Already accepted request"
            );
          }else{
            return array(
              'status'  => "false",
              'message' => "No request found"
            );
          }
        }else{
          return array(
            'status'  => $update_result,
            'message' => "Request count is zero so cant accept the shift"
          );
        }        
      }
    }else{
      return array(
        'status' => "false",
        'message' => "There is no open shift on this date."
      );
    }
  }
  public function getRotaId($params)
  {
    $this->db->select('rota_schedule.rota_id');
    $this->db->from('rota_schedule');
    $this->db->where('date', $params['date']);
    $this->db->where('unit_id', $params['unit_id']);
    // $this->db->where('shift_id = 3');
    // $this->db->or_where('shift_id = 4');
    $query = $this->db->get();
    if($query->num_rows() > 0)
    {
      $result = $query->result_array();
      $this->db->select('rota_schedule.date');
      $this->db->from('rota_schedule');
      $this->db->where('rota_id', $result[0]['rota_id']);
      $query1 = $this->db->get();
      $date_array = $query1->result_array();
      $rota_id = $result[0]['rota_id'];
      return array(
        'date_array' => $date_array,
        'rota_id' => $rota_id
      );
    }
    else{
      return array(
        'date_array' => [],
        'rota_id' => ""
      );
    }
  }
  public function getUserIdsFromRotSchedule(){
    $this->db->select('rota_schedule.user_id');
    $this->db->from('rota_schedule');
    $this->db->group_by('rota_schedule.user_id');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function findSchedule($id,$date){
    $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,rota_schedule.date,master_shift.shift_shortcut,personal_details.fname,personal_details.lname');
    $this->db->from('rota_schedule');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
    $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
    $this->db->where('rota_schedule.rota_id', $id);
    $this->db->where('rota_schedule.date', $date);
    $this->db->group_by('rota_schedule.user_id');
    $this->db->order_by('rota_schedule.date', 'asc');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function checkParentUnit($user_unit_id){
    $unit_ids = array();
    array_push($unit_ids, $user_unit_id);
    $this->db->select('*');
    $this->db->from('unit'); 
    $this->db->where('id', $user_unit_id);
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0){
      if($result[0]['parent_unit'] == 0){
        $this->db->select('*');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $user_unit_id);
        $query1 = $this->db->get();
        $sub_units = $query1->result_array();
        for($i=0;$i<count($sub_units);$i++){
          array_push($unit_ids, $sub_units[$i]['id']);
        }
      }
    }
    return $unit_ids;
  }
  public function getArrayUsers($rota_id,$jobe_roles,$unit_id){
    $unit_ids = $this->checkParentUnit($unit_id);
    $this->db->select('rota_schedule.user_id,user_unit.unit_id');
    $this->db->from('rota_schedule');
    $this->db->join('users', 'users.id = rota_schedule.user_id');
    $this->db->join('user_unit', 'users.id = user_unit.user_id');
    $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
    $this->db->join('master_designation', 'users.designation_id = master_designation.id');
    $this->db->where('rota_schedule.rota_id', $rota_id);
    $this->db->where_in('rota_schedule.unit_id', $unit_ids);
    if(count($jobe_roles) > 0){
      $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);
      //$this->db->where_in('users.designation_id',$jobe_roles);
    }
    $this->db->group_by('rota_schedule.user_id');
    $this->db->order_by('master_designation.sort_order', 'asc');
    $this->db->order_by('master_designation.designation_name', 'asc');
    $this->db->order_by('personal_details.fname', 'asc');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
   public function getShiftsWithDateForSameUnit($params,$user_id,$date){
    $this->db->select('
      rota_schedule.user_id,
      rota_schedule.shift_id,
      rota_schedule.date,
      master_shift.shift_shortcut,
      personal_details.fname,
      personal_details.lname,
      rota_schedule.rota_id,
      user_unit.unit_id as user_unit_id'
    );
    $this->db->from('rota_schedule');
    $this->db->join('personal_details', 'rota_schedule.user_id = personal_details.user_id');
    $this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id','left');
    $this->db->where('rota_schedule.date', $date);
    $this->db->where('rota_schedule.user_id', $user_id);
    $this->db->group_start();
    $this->db->where('rota_schedule.unit_id',$params['selected_unitid']);
    $this->db->or_where('rota_schedule.from_unit',$params['selected_unitid']);
    $this->db->group_end();
    $this->db->order_by('rota_schedule.date', 'asc');
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0){
      $final_result = $this->removeDuplicates($result);
      return $this->makeArray($final_result);
    }else{
      return $result;
    }
  }
  public function sortResults($rt_ids){
    $this->db->select('
      rota_schedule.user_id,
      rota_schedule.shift_id,
      rota_schedule.date,
      master_shift.shift_shortcut,
      personal_details.fname,
      personal_details.lname,
      user_unit.unit_id as user_unit_id,
      master_shift.targeted_hours,
      master_shift.unpaid_break_hours'
    );
    $this->db->from('rota_schedule');
    $this->db->join('personal_details', 'rota_schedule.user_id = personal_details.user_id');
    $this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id','left');
    $this->db->where_in('rota_schedule.id', $rt_ids);
    $this->db->order_by('rota_schedule.date', 'asc');
    $query=$this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function getShiftsForSameUnitUser($params,$user_id){
    $this->db->select('
      rota_schedule.id as rotaschedule_id,
      rota_schedule.user_id,
      rota_schedule.shift_id,
      rota_schedule.date,
      master_shift.shift_shortcut,
      personal_details.fname,
      personal_details.lname,
      user_unit.unit_id as user_unit_id,
      master_shift.targeted_hours,
      master_shift.unpaid_break_hours'
    );
    $this->db->from('rota_schedule');
    $this->db->join('personal_details', 'rota_schedule.user_id = personal_details.user_id');
    $this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id','left');
    $this->db->where('rota_schedule.user_id', $user_id);
    $this->db->group_start();
    $this->db->where('rota_schedule.unit_id',$params['selected_unitid']);
    $this->db->or_where('rota_schedule.from_unit',$params['selected_unitid']);
    $this->db->group_end();
    
    $this->db->group_start();
    $this->db->where('rota_schedule.date >=', $params['new_start_date']);
    $this->db->where('rota_schedule.date <=',  $params['new_end_date']);
    $this->db->group_end();
    $this->db->where('rota_schedule.shift_id !=', null);
    $query=$this->db->get();
    $result = $query->result_array();
    if(count($result) > 0){
      $final_result = $this->removeDuplicates($result);
      $rt_ids = $this->getRotaIds($final_result);
      $sorted_result = $this->sortResults($rt_ids);
      return $sorted_result;
    }else{
      return $result;
    }
  }
  public function makeArray($final_result){
    $new_array = array();
    foreach ($final_result as $value) {
      array_push($new_array, $value);
    }
    return $new_array;
  }
  public function getRotaIds($final_results){
    $new_array = array();
    foreach ($final_results as $value) {
      array_push($new_array, $value['rotaschedule_id']);
    }
    return $new_array;
  }
  public function getArrayUsersHorizontal($rota_id,$jobe_roles,$unit_id){
    $unit_ids = $this->checkParentUnit($unit_id);
    $this->db->select('rota_schedule.user_id,rota_schedule.rota_id,user_unit.unit_id');
    $this->db->from('rota_schedule');
    $this->db->join('users', 'users.id = rota_schedule.user_id');
    $this->db->join('user_unit', 'users.id = user_unit.user_id');
    $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
    $this->db->join('master_designation', 'users.designation_id = master_designation.id');
    $this->db->where_in('rota_schedule.rota_id', $rota_id);
    $this->db->where_in('rota_schedule.unit_id', $unit_ids);
    if($jobe_roles && count($jobe_roles) > 0){
      $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);
      //$this->db->where_in('users.designation_id',$jobe_roles);
    }
    $this->db->group_by('rota_schedule.user_id');
    $this->db->order_by('master_designation.sort_order', 'asc');
    $this->db->order_by('master_designation.designation_name', 'asc');
    $this->db->order_by('personal_details.fname', 'asc');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function getShiftsWithDate($rota_id,$user_id,$date){
    $this->db->select('
      rota_schedule.user_id,
      rota_schedule.shift_id,
      rota_schedule.date,
      master_shift.shift_shortcut,
      personal_details.fname,
      personal_details.lname,
      rota_schedule.rota_id,
      user_unit.unit_id as user_unit_id'
    );
    $this->db->from('rota_schedule');
    $this->db->join('personal_details', 'rota_schedule.user_id = personal_details.user_id');
    $this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id','left');
    if(count($rota_id) > 0){
      $this->db->where_in('rota_schedule.rota_id', $rota_id);
    }else{
      $this->db->where('rota_schedule.rota_id', $rota_id);
    }
    $this->db->where('rota_schedule.date', $date);
    $this->db->where('rota_schedule.user_id', $user_id);
    $this->db->order_by('rota_schedule.date', 'asc');
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0){
      $final_result = $this->removeDuplicates($result);
      return $this->makeArray($final_result);
    }else{
      return $result;
    }
  }
  public function getShifts($rota_id,$user_id){
    $this->db->select('
      rota_schedule.user_id,
      rota_schedule.shift_id,
      rota_schedule.date,
      master_shift.shift_shortcut,
      personal_details.fname,
      personal_details.lname,
      user_unit.unit_id as user_unit_id
      master_shift.targeted_hours,
      master_shift.unpaid_break_hours'
    );
    $this->db->from('rota_schedule');
    $this->db->join('personal_details', 'rota_schedule.user_id = personal_details.user_id');
    $this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id','left');
    if(is_array($rota_id)){
      $this->db->where_in('rota_schedule.rota_id', $rota_id);
    }else{
      $this->db->where('rota_schedule.rota_id', $rota_id);
    }
    $this->db->where('rota_schedule.user_id', $user_id);
    $this->db->order_by('rota_schedule.date', 'asc');
    $query = $this->db->get();
    if (!$query) {
        return array(); // Return false or handle the error as needed
    }

    $result = $query->result_array();
    return $result;
  }
  public function getEarlyShiftCount($id,$date,$users,$rota_id){
    $user_ids = array();
    for($i=0;$i<count($users);$i++){
      array_push($user_ids, $users[$i]['user_id']);
    }
    $this->db->select('
      master_shift.shift_shortcut,
      master_shift.shift_category,
      master_shift.part_number,
      master_shift.shift_type'
    );
    $this->db->from('rota_schedule');
    $this->db->join('users', 'rota_schedule.user_id = users.id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
    if(is_array($rota_id)){
      $this->db->where_in('rota_schedule.rota_id', $rota_id);
    }else{
      $this->db->where('rota_schedule.rota_id', $rota_id);
    }
    $this->db->where('rota_schedule.date', $date);
    $this->db->where_in('rota_schedule.user_id', $user_ids);
    $this->db->where('users.designation_id', $id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function getTrainingShiftDetails($id){
    $this->db->select('*');
    $this->db->from('master_shift');
    $this->db->where('master_shift.id', $id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function get_user_shifts_on_dates($user_id,$dates){
    // Build the query
    $this->db->select('
      rota_schedule.date,
      rota_schedule.shift_id,
      rota_schedule.user_id,
      master_shift.shift_name,
      master_shift.targeted_hours,
      master_shift.unpaid_break_hours
    ');
    $this->db->from('rota_schedule');
    $this->db->join('users', 'rota_schedule.user_id = users.id');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
    $this->db->where('rota_schedule.user_id', $user_id);
    $this->db->where_in('rota_schedule.date', $dates);
    $this->db->order_by('rota_schedule.creation_date', 'DESC');
    $this->db->group_by('DATE(rota_schedule.date)'); // Group by date to get the latest row for each date

    // Execute the query
    $query = $this->db->get();
    // echo $this->db->last_query();exit;
    // Get the result
    $result = $query->result_array();
    return $result;
  }
}
?>