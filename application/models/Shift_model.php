<?php
class Shift_model extends CI_Model 
{
  
  public function insertshift($dataform)
  { 
    $this->db->insert("master_shift", $dataform);
    $insertId = $this->db->insert_id();
    return  $insertId;
  }
  public function insertAvlshift($avldataform){
    $this->db->insert("available_master_shift", $avldataform);
  }
  public function insertToAvailableRequests($available_requests)
  {
    $this->db->select('available_requests.id,available_requests.request_count');
    $this->db->from('available_requests');
    $this->db->where('to_unitid',$available_requests['to_unitid']);
    $this->db->where('shift_id',$available_requests['shift_id']);
    $this->db->where('date',$available_requests['date']);
    if($available_requests['from_unitid']){
      $this->db->where('from_unitid',$available_requests['from_unitid']);
    }
    $query = $this->db->get();
    if($query->num_rows() >0){
      $result = $query->result_array();
      $insert_id = $result[0]['id'];
      $old_request_count =  $result[0]['request_count'];
      $new_request_count = $available_requests['request_count'];
      $request_count = $old_request_count + $new_request_count;
      $updateData = array(
        'request_count'=>$request_count
      );
      $this->db->where('id', $insert_id);
      $this->db->update('available_requests', $updateData);
      return $insert_id;
    }else{
      $this->db->insert("available_requests", $available_requests);
      $insert_id = $this->db->insert_id();
      return $insert_id;
    }
  }
  public function insertToAvailableRequestedUsers($available_requested_users)
  {
    $this->db->insert("available_requested_users", $available_requested_users);
  }
  public function getShift()
  {
    $this->db->select('*');
    $this->db->from('master_shift');
    $this->db->order_by('shift_shortcut','asc');
    $this->db->where('status', 1); 
    /*$this->db->where('shift_shortcut !=','AWOL');
    $this->db->where('shift_shortcut !=','S');*/
    $query = $this->db->get(); 
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
  }

  public function FindShiftendtime($date,$user_id)
  {  //$date='2021-05-05'; $user_id='677';
    $this->db->select('master_shift.end_time');
    $this->db->from('rota_schedule');
    $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
    $this->db->where('rota_schedule.date', $date); 
    $this->db->where('rota_schedule.user_id',$user_id);
    //$this->db->where('shift_shortcut !=','S');
    $query = $this->db->get(); 
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         $results='00:00:00';
         if(count($result) > 0){
           //print_r($result);exit();
          if($result[0]['end_time']=='')
          {
            $results='00:00:00';
          }
          else
          {
           $results=$result[0]['end_time'];
          }
         }
          
         return $results;

  }

  public function zeroTargetedHoursShifts(){
    $this->db->select('master_shift.id');
    $this->db->from('master_shift');
    $this->db->where('targeted_hours', 0); 
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function shift_color($shift_id)
  {  
    $this->db->select('color_unit');
    $this->db->from('master_shift');
    $this->db->where('id',$shift_id); 
    /*$this->db->where('shift_shortcut !=','AWOL');
    $this->db->where('shift_shortcut !=','S');*/
    $query = $this->db->get(); 
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }
  public function shift_back_color($shift_id)
  {  
    $this->db->select('background_color');
    $this->db->from('master_shift');
    $this->db->where('id',$shift_id); 
    /*$this->db->where('shift_shortcut !=','AWOL');
    $this->db->where('shift_shortcut !=','S');*/
    $query = $this->db->get(); 
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }

  public function daily_sense($user_id,$date)
  {
    $this->db->select('comment');
    $this->db->from('daily_senses');
    $this->db->where('user_id',$user_id); 
    $this->db->where('date',$date); 
    $this->db->where('status',1); 
    /*$this->db->where('shift_shortcut !=','AWOL');
    $this->db->where('shift_shortcut !=','S');*/
    $query = $this->db->get(); 
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

  }

  public function findshiftcolor($shift)
  {
    $this->db->select('color_unit');
    $this->db->from('master_shift');
    $this->db->where('shift_shortcut',$shift); 
    /*$this->db->where('shift_shortcut !=','AWOL');
    $this->db->where('shift_shortcut !=','S');*/
    $query = $this->db->get(); 
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

  }

  public function getRequestShift($shift_id){
    if($shift_id){
      $this->db->select('*');
      $this->db->from('master_shift');
      $this->db->where('status', 1);
      $this->db->where('id', $shift_id);
      $this->db->or_where('id', 0);
      $this->db->or_where('id', 1);
      $this->db->or_where('id', 2);
      $this->db->order_by('id DESC');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }else{
      $this->db->select('*');
      $this->db->from('master_shift');
      $this->db->where('id', 0);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
  }
  public function getAvlShifts()
  {
    $this->db->select('available_master_shift.id,available_master_shift.shift_shortcut');
    $this->db->from('available_master_shift');
    $query = $this->db->get(); 
    $result = $query->result_array();
    return $result;
  }
  public function getAbsentSHifts()
  {
    $this->db->select('*');
    $this->db->from('master_shift');
    $this->db->where('shift_shortcut', 'S');
    $this->db->or_where('shift_shortcut', 'AWOL');
    $this->db->or_where('shift_shortcut', 'ABS');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function getAvailableShift()
  {
    $this->db->select('*');
    $this->db->from('master_shift');
    $this->db->where('shift_shortcut', 'AL');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function FindRotaId($params)
  {
    $this->db->select('id');
    $this->db->from('rota');
    $this->db->where('start_date', $params['start_date']);
    $this->db->where('end_date', $params['end_date']);
    $this->db->where('unit_id', $params['unit_id']);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }

  public function findshiftbystatus($status)
  {
    $this->db->select('*');
    $this->db->from('master_shift');
    if(!empty($status))
    {
    $this->db->where_in('status', $status);
    }
    else
    {
     $this->db->where_in('status', "1"); 
    }
    $query = $this->db->get();
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result; 

  }
  public function findshiftname($status)
  {
    $this->db->select('*');
    $this->db->from('master_shift');
    $this->db->where('status', $status);
    $this->db->where('shift_category', 1);
    $this->db->or_where('shift_category', 2);
    $query = $this->db->get();
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result; 

  }
  public function findshiftbyShiftShortcut($shift_shortcut){
    $this->db->select('master_shift.id,master_shift.targeted_hours,master_shift.shift_category');
    $this->db->from('master_shift');
    $this->db->where('shift_shortcut', $shift_shortcut);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }

  public function designationStatus($id,$unit_id){
    $this->db->select('users.id,users.designation_id,master_designation.id as des_id,master_designation.part_number,user_unit.unit_id,master_designation.designation_code,master_designation.group,master_designation.nurse_count as sort_order ,personal_details.gender');
    $this->db->from('users');
    $this->db->join('master_designation', 'users.designation_id = master_designation.id');
    $this->db->join('user_unit', 'users.id = user_unit.user_id');
    $this->db->join('personal_details', 'users.id = personal_details.user_id');
    $this->db->where('users.id', $id);
    // $this->db->where('users.designation_id', 1);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $result = $query->result_array();
      if($result[0]['part_number'] == 0){
        if($result[0]['unit_id'] == $unit_id ){
          $status = "false";
        }else{
          $status = "true";
        }
      }else{
        $status = "true";
      }
      return array(
        'status' => $status,
        'des_code' => $result[0]['designation_code'],
        'sort_order' => $result[0]['sort_order'],
        'gender' => $result[0]['gender'],
      );
    }else{
      return array(
        'status' => "true",
        'des_code' => "",
        'sort_order' => "",
        'gender' => ""
      );
    }
  }
  public function getShiftwithoutOffday()
  {
      $this->db->select('*');
      $this->db->from('master_shift');
      $this->db->where('id > 0');
      $this->db->where('status', 1);
      $this->db->where('shift_category > 0');
      $this->db->order_by('id','desc');
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
  }
     public function deleteshift($id)
      {

        $status=array('status'=>3);
        $this->db->where('id', $id);
        $status=$this->db->update('master_shift',$status);
         if($status==true)
         { 
             return 1;
         }
         else
         { 
            $mysqlerror = $this->db->error();  
            return $mysqlerror['code'];
          }
          
      }
      public function findshift($id)
      {
        $this->db->select('*');
    $this->db->from('master_shift');
    $this->db->where('id', $id);
    $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
      public function findavailableshift($id)
      {
        $this->db->select('shift_shortcut');
    $this->db->from('available_master_shift');
    $this->db->where('id', $id);
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
         $result = $query->result_array();
         if(count($result)>0)
         {
            return $result[0]['shift_shortcut'];
         }
         else
         {
            return null;
         }
         
      }
      
      public function findshiftbyName($shortname)
      {   
          $result = array();
   
          if($shortname==''){
              
              return $result;
          }
      
          else{
    
          $this->db->select('*');
          $this->db->from('master_shift');
          $this->db->where('shift_name', $shortname);
          $query = $this->db->get();
         // echo $this->db->last_query(); 
          $result = $query->result_array();
          return $result;
          }
  
          
      }
      public function findtime($id)
      {
        $this->db->select('start_time,end_time');
        $this->db->from('master_shift');
        $this->db->where('id', $id);
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
    
      public function update($id,$dataform){
         
         $this->db->where('id', $id);
         $result=$this->db->update('master_shift',$dataform);
         return $result;
         }
         public function updateAvlShift($id,$dataform){
         
         $this->db->where('parent_id', $id);
         $this->db->update('available_master_shift',$dataform);
         }
 
   public function getshiftDetails($params)
      {
          $this->db->select('*');
          $this->db->from('master_shift');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['shift_name'])
               $this->db->where('shift_name', $params['shift_name']);
          
          $query = $this->db->get();
         // echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
      }
   public function getRotaByUnit($unit_id)
  {
    $this->db->select('*');
    $this->db->from('rota_schedule'); 
    $this->db->where('unit_id', $unit_id);
    $query=$this->db->get();
      // print $this->db->last_query();
      // exit();
    $result = $query->result_array(); 
    return $result;
  }
//   public function findschedule($params)
//   {
//   $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,
// rota_schedule.unit_id,rota_id,
// master_shift.shift_name');
//   $this->db->from('rota_schedule');
//   $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
//   $this->db->join('rota', 'rota.id = rota_schedule.rota_id'); 
//   $this->db->where('date >=', $params['start_date']);
//   $this->db->where('date <=',  $params['end_date']);
//   $this->db->or_where('rota_schedule.unit_id', $params['unit_id']);
//    $query=$this->db->get();
//       // print $this->db->last_query();
//       // exit();
//     $result = $query->result_array(); 
//     return $result;
//  }
  public function getScheduledUserCount($rota_id){
    $user_ids = array();
    $this->db->select('*');
    $this->db->from('rota_schedule');
    $this->db->where('rota_schedule.rota_id', $rota_id);
    $this->db->group_by('rota_schedule.user_id');
    $query = $this->db->get();
    if(count($query->result_array()) > 0){
      $result  = $query->result_array();
      for($i=0;$i<count($result);$i++){
        array_push($user_ids, $result[$i]['user_id']);
      }
      return $user_ids;
    }else{
      return [];
    }
  }
  
 public function getRota($rotaDetails){  //print_r($rotaDetails);exit();
  $this->load->model('Unit_model');
  //$rotaDetails['unit_id'] = $this->Unit_model->returnParentUnit($rotaDetails['unit_id']);
        $array = array(
            'start_date' => $rotaDetails['start_date'],
            'end_date' => $rotaDetails['end_date'],
            'unit_id' => $rotaDetails['unit_id']
        );
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where($array);
        $query = $this->db->get();
         // print $this->db->last_query();
         // exit();
        if(count($query->result_array()) > 0)
        {
          $result  = $query->result_array();
          return $result;
        }
        else
        {
            return [];
        }
      }
      
public function getDayRota($params){
          $this->db->select('*');
          $this->db->from('rota_schedule');
          $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
          $this->db->where('user_id', $params['user_id']);
          $this->db->where('date =', $params['date']);
          $query = $this->db->get();
          //print $this->db->last_query();
          //  exit();
          if(count($query->result_array()) > 0)
          {
              $result  = $query->result_array();
              return $result;
          }
          else
          {
              return [];
          }
}
 
//siva on march 7 other unit fix
 public function getDayRotaByUserandUnit($params){
    $this->db->select('*');
    $this->db->from('rota_schedule');
    $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
    
    $this->db->where('user_id', $params['user_id']);
    $this->db->where('unit_id', $params['unit_id']);
    $this->db->where('date =', $params['date']);
    $query = $this->db->get();
    //print $this->db->last_query();
    //  exit();
    if(count($query->result_array()) > 0)
    {
        $result  = $query->result_array();
        return $result;
    }
    else
    {
        return [];
    }
}

 public function publishCopiedData($rotaDetails){
        $array = array(
            'start_date' => $rotaDetails['start_date'],
            'end_date' => $rotaDetails['end_date'],
            'unit_id' => $rotaDetails['unit_id']
        );
        $data = array('published'=>1);
        $this->db->where($array);
        $this->db->update('rota',$data);
        if($this->db->affected_rows() > 0 ){
          $rota_data = $this->getRota($rotaDetails);
          return(array(
            'status' => 1,
            'rota_data' => $rota_data
          ));
        }else{
          return(array(
            'status' => 2,
            'rota_data' => []
          ));
        }
      }
      public function checkAgencyStaffExists($params){
        $this->db->select('*');
        $this->db->from('rota_schedule');
        $this->db->where('user_id', $params['user_id']);
        $this->db->where('date >=', $params['start_date']);
        $this->db->where('date <=',  $params['end_date']);
        $query=$this->db->get();
        if($query->num_rows() > 0 ){
          $result = $query->result_array();
          return $result;
        }else{
          return array();
        }
      }

   /* find userid in rotaschedule where count of dates less than 7 */
   public function GetUserIdMissingRotaEntry($params)
   {
        $this->db->select('rota_schedule.user_id');    
        $this->db->from('rota_schedule');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        if($this->session->userdata('user_type')==5) 
        {
           $this->db->where('rota.published',1);
        }
        
        if($params['unit_id']>0)
        {  
          $this->db->where('rota_schedule.unit_id',$params['unit_id']);
        }
        
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
          $this->db->group_by('rota_schedule.user_id');
          $query=$this->db->get();
           //  print $this->db->last_query();
           // exit();
          $result = $query->result_array();  /* user_id between dates and unit */
          $new_result=array();

          if(!empty($result))
          {
             foreach ($result as $users) { /* count of rota in a weerk by userid,date,unit */
               //print_r($users); print '<br>'; 
                  $this->db->select('COUNT(rota_schedule.date) as user_count');    
                  $this->db->from('rota_schedule');
                  $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
                  if($this->session->userdata('user_type')==5) 
                  {
                     $this->db->where('rota.published',1);
                  }
                  
                  $this->db->where('rota_schedule.user_id',$users['user_id']); 
                  $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                  $this->db->where('date >=', $params['start_date']);
                  $this->db->where('date <=',  $params['end_date']);
                  $query=$this->db->get();
                   //  print $this->db->last_query();
                   // exit();
                  $result = $query->result_array(); 
                  if($result[0]['user_count']!=7)
                  {
                    $new_result[]=$users['user_id'];
                  }
                  
              }

              return $new_result;

          }
          else
          {
              return $new_result;
          }
          
   }

   public function getRotaDataByUser($user_id,$date,$unit_id)
   {
      $this->db->select('*');    
      $this->db->from('rota_schedule');
      $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
      if($this->session->userdata('user_type')==5) 
      {
          $this->db->where('rota.published',1);
      }
      $this->db->where('rota_schedule.user_id',$user_id); 
      $this->db->where('rota_schedule.unit_id',$unit_id);
      $this->db->where('rota_schedule.date', $date);
      $query=$this->db->get();
                   //  print $this->db->last_query();
                   // exit();
      $result = $query->result_array(); 
      if(empty($result))
      {
        return '1';
      }
      else
      {
         return '0';
      }
     
   }

   public function getWeeklyShiftsByUser($params,$user_id)  
{ 
        $result = array(); 

        $this->db->select('rota_schedule.rota_id,rota_schedule.from_unit,users.designation_id');
        
        $this->db->from('rota_schedule');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        if($this->session->userdata('user_type')==5) 
        {
           $this->db->where('rota.published',1);
        }
        if($user_id>0)
        $this->db->where('user_id', $user_id);
        
       if($params['unit_id']>0)
        {  
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
        }
        
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
          $this->db->group_by('rota_schedule.user_id');
          $query=$this->db->get();
           //  print $this->db->last_query();
           // exit();
          $result = $query->result_array(); 
          return $result;

}

  public function inserRotascheduleDetails($rota)
  {
    $status=$this->db->insert('rota_schedule',$rota);
    return $status;
  }





public function getWeeklyShifts($params = array()) //admin edity rota 
{ 
   //   if($params['unit_id']>0)
   // {
   //    $this->db->select('id');
   //    $this->db->from('unit'); 
   //    $this->db->where('parent_unit', $params['unit_id']);
   //    $query = $this->db->get();
   //    $results = $query->result_array();
   //    //print_r($result);exit();
   // }
//print_r($result);
        $result = array(); 
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id,rota_schedule.shift_id, rota_schedule.from_unit,
        rota_schedule.unit_id,rota_id,rota.published,date,from_unit,
        master_shift.shift_shortcut as shift_name
        ,master_shift.start_time,master_shift.end_time,master_shift.shift_category,master_shift.shift_type,master_shift.background_color');
        
        $this->db->from('rota_schedule');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        if($this->session->userdata('user_type')==5) //newly added on nov 13 by swaraj
        {
           $this->db->where('rota.published',1);
        }
        if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        
       if($params['unit_id']>0)
        {  
          // if(!empty($results))
          // {  
          //   $this->db->group_start();
          //   $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          //   foreach ($results as $value) 
          //   {  
          //      $this->db->or_where('rota_schedule.unit_id',$value['id']);
          //   }
          //   $this->db->group_end();
            
          // }
          // else
          // {  
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          // }
        }
        
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
          $this->db->order_by('rota_schedule.user_id','asc');
          $this->db->order_by('date','asc');
          $query=$this->db->get();
           //  print $this->db->last_query();
           // exit();
          $result = $query->result_array(); 
          return $result;

}
public function getWeeklyShiftsbyStaffs($params = array()) //manager view rota,staff view rota
{ 
     if($params['unit_id']>0)
   {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $results = $query->result_array();
      //print_r($result);exit();
   }
//print_r($result);
        $result = array(); 
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,
        rota_schedule.unit_id,rota_id,date,from_unit,
        master_shift.shift_shortcut as shift_name
        ,master_shift.start_time,master_shift.end_time,master_shift.shift_category,master_shift.shift_type');
        
        $this->db->from('rota_schedule');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->where('rota.published',1);
        if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        
       if($params['unit_id']>0)
        {  
          if(!empty($results))
          {  
            $this->db->group_start();
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            foreach ($results as $value) 
            {  
               $this->db->or_where('rota_schedule.unit_id',$value['id']);
            }
            $this->db->group_end();
            
          }
          else
          {  
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          }
        }
        
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
         $query=$this->db->get();
            // print $this->db->last_query();
            //   exit();
          $result = $query->result_array(); 
          return $result;

}
public function getWeeklyShiftsOfStaffs($params = array())
{
    //print_r($params);
        
 // if($params['unit_id']!=='')
 //   {
 //      $this->db->select('id');
 //      $this->db->from('unit'); 
 //      $this->db->where('parent_unit', $params['unit_id']);
 //      $query = $this->db->get();
 //      $result = $query->result_array();
 //      //print_r($result);exit();
 //   }

        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id as id,personal_details.gender, 
        personal_details.fname,personal_details.lname,users.weekly_hours,master_shift.shift_category,master_shift.shift_type,master_designation.sort_order,master_designation.designation_code,master_designation.group,master_shift.shift_shortcut,rota_schedule.from_unit,users.default_shift,master_designation.id as designation_id');
        
        $this->db->from('rota_schedule');
        
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
        $this->db->join('users', 'users.id = personal_details.user_id');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        
        $this->db->group_by('rota_schedule.user_id');
        if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
        {
           $this->db->where('rota.published',1);
        }
        
        if($params['user_id']!=='')
        $this->db->where('rota_schedule.user_id', $params['user_id']);
        if($params['unit_id']!=='') 
          {
            // if(!empty($result))
            // {
            //   $this->db->group_start();
            //   $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            //   foreach ($result as $value) 
            //   {
            //      $this->db->or_where('rota_schedule.unit_id',$value['id']);
            //   }
            //   $this->db->group_end();
            // }
            // else
            // {
               $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            // }
          }
        
        
          // $this->db->where('users.status=',1);
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
         $query=$this->db->get();
           // print $this->db->last_query();
           //    exit();
          $result = $query->result_array(); 
          return $result;
}
 
public function getWeeklyShiftsStaffs($params = array()) // by admin
{
   //print_r($params);
       if($params['unit_id']!='')
   {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $results = $query->result_array();
   }  
        $result=array();
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id as title, rota_schedule.user_id as id,users.default_shift,master_designation.designation_code,
          master_designation.group,master_designation.sort_order,master_shift.shift_category,master_shift.shift_type,
          master_designation.id as designation_id,
        CONCAT(personal_details.gender,"-",personal_details.fname," ",personal_details.lname," (",users.weekly_hours,")" "(",master_designation.designation_code,")" "(",master_shift.shift_shortcut,")" ) as title,rota_schedule.from_unit');
        
        $this->db->from('rota_schedule');
        
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id' );
        $this->db->join('users', 'users.id = personal_details.user_id','left');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        
        $this->db->group_by('rota_schedule.user_id');
       
        if($this->session->userdata('user_type')==5) //newly added by swaraj 0n nov13
        {
           $this->db->where('rota.published',1);
        }
        
        if($params['user_id']!=='')
        $this->db->where('rota_schedule.user_id', $params['user_id']);
        
        if($params['unit_id']!=='') 
        {
          if(!empty($results))
          {
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            foreach ($results as $value) 
            {
               $this->db->or_where('rota_schedule.unit_id',$value['id']);
            }
          }
          else
          {
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          }
        }
        
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
         $query=$this->db->get();
           // print $this->db->last_query();
           //    exit();
          $result = $query->result_array(); 
          return $result;
}

public function getWeeklyShiftsResourcesbyStaffs($params = array()) // by staff/manager
{
   //print_r($params);
       if($params['unit_id']!='')
   {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $results = $query->result_array();
   }  
        $result=array();
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id as title, rota_schedule.user_id as id,users.default_shift,master_designation.designation_code,
          master_designation.group,master_designation.sort_order,master_shift.shift_category,master_shift.shift_type,
          master_designation.id as designation_id,
        CONCAT(personal_details.gender,"-",personal_details.fname," ",personal_details.lname," (",users.weekly_hours,")" "(",master_designation.designation_code,")" "(",master_shift.shift_shortcut,")" ) as title,rota_schedule.from_unit');
        
        $this->db->from('rota_schedule');
        
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id' );
        $this->db->join('users', 'users.id = personal_details.user_id','left');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        
        $this->db->group_by('rota_schedule.user_id');
        $this->db->where('rota.published',1);
        
        if($params['user_id']!=='')
        $this->db->where('rota_schedule.user_id', $params['user_id']);
        
        if($params['unit_id']!=='') 
        {
          if(!empty($results))
          {
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            foreach ($results as $value) 
            {
               $this->db->or_where('rota_schedule.unit_id',$value['id']);
            }
          }
          else
          {
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          }
        }
        
          $this->db->where('date >=', $params['start_date']);
          $this->db->where('date <=',  $params['end_date']);
         $query=$this->db->get();
           // print $this->db->last_query();
           //    exit();
          $result = $query->result_array(); 
          return $result;
}
public function getWeeklyShiftsOfEmptyBranch($params,$jobe_roles)
{
    //print_r($params);exit();
    $result = array();
    if($params['user_id']=='')
    { //if not staff view
        
        if($params['unit_id']=="" || $params['month']=="" )
            return $result;
    }
    else
    { //staff view check only start and end dates are null
        if($params['month']=="")
            return $result;
    }
    $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,
        rota_schedule.unit_id,rota_id,date,from_unit,
        master_shift.shift_shortcut as shift_name
        ,master_shift.start_time,master_shift.end_time,rota.published,users.default_shift,master_designation.designation_code,master_shift.part_number,master_shift.shift_type,master_shift.background_color,master_shift.targeted_hours,master_shift.unpaid_break_hours');
    
    $this->db->from('rota_schedule');
    $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
    $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
    $this->db->join('users', 'users.id = rota_schedule.user_id');
    $this->db->join('master_designation', 'users.designation_id = master_designation.id');
    
    if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        
        /*       if($params['unit_id']>0)
         $this->db->where('rota_schedule.unit_id',$params['unit_id']);
         $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
         
         $this->db->where('date >=', $params['start_date']);
         $this->db->where('date <=',  $params['end_date']); */
        // added by siva on march 20
        $this->db->group_start();
        if($params['unit_id']>0)
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
            $this->db->group_end();
            
            $this->db->group_start();
            $this->db->where('date >=', $params['start_date']);
            $this->db->where('date <=',  $params['end_date']);
            $this->db->group_end();
            
            if(count($jobe_roles) > 0)
                $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);

            if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
            {
               $this->db->where('rota.published',1);
            }

              $this->db->order_by("rota_schedule.shift_id asc"); 
              $this->db->order_by("rota_schedule.id desc"); 
                //$this->db->where_in('users.designation_id',$jobe_roles);
                $query=$this->db->get();
                //print $this->db->last_query();
                //    exit();
                $result = $query->result_array();
                return $result;
}
public function returnUserUnit($user_id){
  $this->db->select('unit_id');
  $this->db->from('user_unit');
  $this->db->where('user_id', $user_id);
  $query=$this->db->get();
  $result = $query->result_array();
  return $result[0]['unit_id'];
}
public function TestQuery($unit_id){
  $this->db->select('user_id');
  $this->db->from('user_unit');
  $this->db->join('users', 'users.id = user_unit.user_id');
  $this->db->where('user_unit.unit_id', $unit_id);
  $this->db->where('users.group_id', 6);
  $query=$this->db->get();
  $result = $query->result_array(); 
  return $result;
}
public function getWeeklyShiftsOfEmptyBranch_Merge($params,$jobe_roles)
{
  //print_r($params);exit();
        $result = array(); 
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['month']=="" )
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['month']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,
        rota_schedule.unit_id,rota_id,date,from_unit,
        master_shift.shift_shortcut as shift_name
        ,master_shift.start_time,master_shift.end_time,rota.published,users.default_shift,master_designation.designation_code,master_shift.part_number,master_shift.shift_type');
        
        $this->db->from('rota_schedule');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->join('master_designation', 'users.designation_id = master_designation.id');
        
        if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        

  /*       if($params['unit_id']>0)
        $this->db->where('rota_schedule.unit_id',$params['unit_id']);
        $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
        
        $this->db->where('date >=', $params['start_date']);
        $this->db->where('date <=',  $params['end_date']); */
  // added by siva on march 20
      $this->db->group_start();
      if($params['unit_id']>0)
      $this->db->where('rota_schedule.unit_id',$params['unit_id']);
      $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
      $this->db->group_end();
      
      $this->db->group_start();
      $this->db->where('date >=', $params['start_date']);
      $this->db->where('date <=',  $params['end_date']);
      $this->db->group_end();

        
        if(count($jobe_roles) > 0)
          $this->db->where_in('users.designation_id',$jobe_roles);
        $query=$this->db->get();
         //  print $this->db->last_query();
          //    exit();
        $result = $query->result_array(); 
        return $result;
}
public function getWeeklyShiftsViewOfEmptyBranch($params,$jobe_roles)
{  
         $result = array(); 
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['month']=="" )
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['month']=="" )
        return $result;
        }
        $data=array(
          'rota_schedule.user_id' ,
          'rota_schedule.rota_id',
          'rota_schedule.unit_id',
          'rota_schedule.from_unit',
          'users.weekly_hours',
          'master_shift.targeted_hours',
          'users.default_shift',
          'master_designation.sort_order',
          'master_designation.designation_code',
          'master_designation.group',
          'personal_details.gender',
          'personal_details.fname',
          'personal_details.lname',
          'master_designation.designation_code',
          'master_designation.id as designation_id',
          'rota_schedule.from_unit',
          'master_shift.shift_category',
          'master_shift.shift_shortcut',
          'master_shift.part_number',
          'master_shift.shift_type'
        );
         $this->db->select($data);
        
        $this->db->from('rota_schedule');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id','left');
        $this->db->join('users', 'users.id = personal_details.user_id','left');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        
        $this->db->group_by('rota_schedule.user_id');
        
        if($params['user_id']!=='')
        $this->db->where('rota_schedule.user_id', $params['user_id']);
        
        if($params['unit_id']!=='')
        $this->db->group_start();
        $this->db->where('rota_schedule.unit_id',$params['unit_id']); 
        $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['start_date']);
        $this->db->where('rota_schedule.date <=',  $params['end_date']);
        $this->db->group_end();
        // $this->db->where('MONTH(rota_schedule.date)',$params['month']);
        // $this->db->where('users.status', 1);
        if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
            {
               $this->db->where('rota.published',1);
            }
        if(count($jobe_roles) > 0)
        //$this->db->where_in('users.designation_id',$jobe_roles);
          $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);
         $query=$this->db->get();
          // print $this->db->last_query();
          // exit();
           $other_unit_user = array();
           $other_unit_user_with_shift = array();
           $final_result = array();
          $result = $query->result_array();
          // print("<pre>".print_r($result,true)."</pre>");exit();
          foreach ($result as $value) 
          {
            if($value['from_unit'] != null){
              array_push($other_unit_user, $value['user_id']);
            }
          }
          if(count($other_unit_user) > 0){
            foreach ($other_unit_user as $value) 
            {
              $this->db->select('rota_schedule.shift_id,rota_schedule.user_id');
              $this->db->from('rota_schedule');
              $this->db->where('rota_schedule.unit_id',$params['unit_id']);
              $this->db->where('rota_schedule.user_id',$value);
              $this->db->where('rota_schedule.date >=', $params['start_date']);
              $this->db->where('rota_schedule.date <=',  $params['end_date']);
              $this->db->group_start();
              $this->db->where('rota_schedule.shift_id >',4);
              $this->db->or_where('rota_schedule.auto_insert ',2);
              $this->db->group_end();
              $query1=$this->db->get();
              //print $this->db->last_query();echo "<br>";
              $query_result = $query1->result_array();
              if(count($query_result) == 0){
                array_push($other_unit_user_with_shift,$value);
              }
            } 
          }
          if(count($other_unit_user_with_shift) > 0){
            foreach ($result as $value) 
            {
              if (!in_array($value['user_id'], $other_unit_user_with_shift)) {
                  array_push($final_result, $value);
              }
            }
          }
          if(count($final_result) > 0){
            return $final_result;
          }else{
            return $result;
          }
          
}

public function getWeeklyShiftsView($params,$jobe_roles)
{   
    //print_r($params);exit();
   if($params['unit_id']!="")
   { 
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
     //  print $this->db->last_query();
     // exit();
      $results = $query->result_array();
 
   }
    //print_r($result);exit();

        $result = array(); 
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['month']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['month']=="")
        return $result;
        }
        $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,
        rota_schedule.unit_id,rota_id,date,from_unit,
        master_shift.shift_shortcut as shift_name
        ,master_shift.start_time,master_shift.end_time,rota.published,master_shift.part_number,master_shift.shift_type,master_shift.background_color,master_shift.targeted_hours,master_shift.unpaid_break_hours');
        
        $this->db->from('rota_schedule');
        //$this->db->join('calendar', 'rota_schedule.date = calendar.crt_date','left');
        
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->join('master_designation', 'users.designation_id = master_designation.id');
        
         if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        $this->db->group_start();
        if(count($jobe_roles) > 0)
        {
          $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);
           //$this->db->where_in('users.designation_id',$jobe_roles);
            if($params['unit_id']!="")
            {
              if(!empty($results))
              {
                $this->db->group_start();
                $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                foreach ($results as $value) 
                {
                   $this->db->or_where('rota_schedule.unit_id',$value['id']);
                   $this->db->or_where('rota_schedule.from_unit',$value['id']);
                }
                $this->db->group_end();
              }
              else
              {
                 $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                 $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
              }
            }

        }
        else
        {
            if($params['unit_id']!="")
            {
                  if(!empty($results))
                  {
                    $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                    $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                    foreach ($results as $value) 
                    {
                       $this->db->or_where('rota_schedule.unit_id',$value['id']);
                       $this->db->or_where('rota_schedule.from_unit',$value['id']);
                    }
                  }
                  else
                  {
                     $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                     $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                  }
            }


        }
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('date >=', $params['start_date']);
        $this->db->where('date <=',  $params['end_date']);
        // $this->db->where('shift_id > 0');
        $this->db->group_end();
        if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
        {
               $this->db->where('rota.published',1);
        }
        $this->db->order_by("rota_schedule.shift_id asc"); 
        $this->db->order_by("rota_schedule.id desc"); 
        $query=$this->db->get();
        
        
           // print $this->db->last_query();
           // exit();
          $result = $query->result_array(); 
          return $result;
}
public function getWeeklyShiftsViewCount($params,$jobe_roles)
{
    //print_r($params);exit();
    if($params['unit_id']!="")
    {
        $this->db->select('id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        //  print $this->db->last_query();
        // exit();
        $results = $query->result_array();
        
    }
    //print_r($result);exit();
    
    $result = array();
    if($params['user_id']=='')
    { //if not staff view
        
        if($params['unit_id']=="" || $params['month']=="")
            return $result;
    }
    else
    { //staff view check only start and end dates are null
        if($params['month']=="")
            return $result;
    }
    $this->db->select('count(rota_schedule.id ) as rcount, rota_schedule.user_id ');
    
    $this->db->from('rota_schedule');
    //$this->db->join('calendar', 'rota_schedule.date = calendar.crt_date','left');
    
    $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
    $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
    $this->db->join('users', 'users.id = rota_schedule.user_id');
    $this->db->join('master_designation', 'users.designation_id = master_designation.id');
    
    if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        $this->db->group_start();
        if(count($jobe_roles) > 0)
        {
           $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);
            //$this->db->where_in('users.designation_id',$jobe_roles);
            if($params['unit_id']!="")
            {
                if(!empty($results))
                {
                    $this->db->group_start();
                    $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                    $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                    foreach ($results as $value)
                    {
                        $this->db->or_where('rota_schedule.unit_id',$value['id']);
                        $this->db->or_where('rota_schedule.from_unit',$value['id']);
                    }
                    $this->db->group_end();
                }
                else
                {
                    $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                    $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                }
            }
            
        }
        else
        {
            if($params['unit_id']!="")
            {
                if(!empty($results))
                {
                    $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                    $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                    foreach ($results as $value)
                    {
                        $this->db->or_where('rota_schedule.unit_id',$value['id']);
                        $this->db->or_where('rota_schedule.from_unit',$value['id']);
                    }
                }
                else
                {
                    $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                    $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                }
            }
            
            
        }
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('date >=', $params['start_date']);
        $this->db->where('date <=',  $params['end_date']);
        // $this->db->where('shift_id > 0');
        $this->db->group_end();

        if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
        {
               $this->db->where('rota.published',1);
        }
        
        $this->db->group_by('rota_schedule.user_id');
        $this->db->order_by('rota_schedule.user_id', 'asc');
        $this->db->order_by('rota_schedule.date', 'asc');
        
        $query=$this->db->get();
        
        
        //  print $this->db->last_query();
        //   exit();
        $result = $query->result_array();
        
     
        return $result;
}
public function getWeeklyShiftsStaffsView($params,$jobe_roles)
{
    //print_r($params);
        // $result = array(); 
   if($params['unit_id']>0)
   {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
   }


        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['month']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['month']=="")
        return $result;
        }
        $data=array(
          'rota_schedule.user_id' ,
          'rota_schedule.rota_id',
          'rota_schedule.unit_id',
          'users.weekly_hours',
          'master_shift.targeted_hours',
          'users.default_shift',
          'master_designation.sort_order',
          'master_designation.designation_code',
          'master_designation.group',
          'personal_details.gender',
          'personal_details.fname',
          'personal_details.lname',
          'master_designation.designation_code',
          'master_designation.id as designation_id',
          'rota_schedule.from_unit',
          'unit.unit_shortname',
          'master_shift.shift_category',
          'master_shift.shift_shortcut',
          'master_shift.part_number',
          'master_shift.shift_type'
        );
         $this->db->select($data);
        
        $this->db->from('rota_schedule');
        
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
        $this->db->join('users', 'users.id = personal_details.user_id');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id');
        $this->db->join('unit', 'unit.id = user_unit.unit_id');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
       // $this->db->join('calendar', 'rota_schedule.date = calendar.crt_date','left');
        $this->db->group_by('rota_schedule.user_id');
        
        if($params['user_id']!=='')
        $this->db->where('rota_schedule.user_id', $params['user_id']);

        if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
        {
               $this->db->where('rota.published',1);
        }
      
          if(count($jobe_roles) > 0)
        {
          $this->db->where_in('master_designation.jobrole_groupid',$jobe_roles);
           //$this->db->where_in('users.designation_id',$jobe_roles);
            if($params['unit_id']!="")
            {
              if(!empty($result))
              {
                $this->db->group_start();
                $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                foreach ($result as $value) 
                {
                   $this->db->or_where('rota_schedule.unit_id',$value['id']);
                   $this->db->or_where('rota_schedule.from_unit',$value['id']);
                }
                $this->db->group_end();
              }
              else
              {
                 $this->db->group_start();
                     $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                     $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                     $this->db->group_end();
               }
            }

        }
        else
        {
            if($params['unit_id']!="")
            {
                  if(!empty($result))
                  {
                    $this->db->group_start();
                    $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                    $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                    foreach ($result as $value) 
                    {
                       $this->db->or_where('rota_schedule.unit_id',$value['id']);
                       $this->db->or_where('rota_schedule.from_unit',$value['id']);
                    }
                    $this->db->group_end();
                  }
                  else
                  {
                     $this->db->group_start();
                     $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                     $this->db->or_where('rota_schedule.from_unit',$params['unit_id']);
                     $this->db->group_end();
                  }
            }


        }
        $this->db->where('MONTH(rota_schedule.date)',$params['month']);
         // $this->db->where('users.status', 1);
        
          $query=$this->db->get();
         //  print $this->db->last_query();
            //  exit();
          $result = $query->result_array(); 
          return $result;
}
public function getStaffsUserid($params = array())
{
  //print_r($params);exit();
   if($params['unit_id']!=='')
   {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
   }
//print_r($result);exit();

        //$result = array(); 
        if($params['user_id']=='')
        { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        else
        { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
        return $result;
        }
        $this->db->select("COUNT('rota_schedule.user_id'),rota_schedule.user_id,rota_schedule.from_unit");
        
        $this->db->from('rota_schedule'); 
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        // $this->db->where('users.status',1);
        $this->db->group_by('rota_schedule.user_id');
        if($params['user_id']!=='')
        $this->db->where('user_id', $params['user_id']);

      if($params['unit_id']!=='')
        {
          if(!empty($result))
          {
            $this->db->group_start();
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            foreach ($result as $value) 
            {
               $this->db->or_where('rota_schedule.unit_id',$value['id']);
            }
            $this->db->group_end();
          }
          else
          { 
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
           }
        } 
         
        $this->db->group_start();
        $this->db->where('date >=', $params['start_date']);
        $this->db->where('date <=',  $params['end_date']);
        $this->db->group_end();
        $query=$this->db->get();
              // print $this->db->last_query();
              // exit();
        $result = $query->result_array(); 
        return $result;
}
public function getStaffsUseridloadtest($params = array())
{
    //print_r($params);exit();
    if($params['unit_id']!=='')
    {
        $this->db->select('id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
    }
    //print_r($result);exit();
    
    //$result = array();
    if($params['user_id']=='')
    { //if not staff view
        
        if($params['unit_id']=="" || $params['start_date']=="" || $params['end_date']=="")
            return $result;
    }
    else
    { //staff view check only start and end dates are null
        if($params['start_date']=="" || $params['end_date']=="")
            return $result;
    }
    $this->db->select("COUNT('rota_schedule.user_id'),rota_schedule.user_id,rota_schedule.from_unit");
    
    $this->db->from('rota_schedule');
    $this->db->group_by('rota_schedule.user_id');
    if($params['user_id']!=='')
        $this->db->where('user_id', $params['user_id']);
        
        if($params['unit_id']!=='')
        {
            if(!empty($result))
            {
                $this->db->where('rota_schedule.unit_id',$params['unit_id']);
                foreach ($result as $value)
                {
                    $this->db->or_where('rota_schedule.unit_id',$value['id']);
                }
            }
            else
            {
                $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            }
        }
        
        $this->db->where('date >=', $params['start_date']);
        $this->db->where('date <=',  $params['end_date']);
        $query=$this->db->get();
    // print $this->db->last_query();
      // exit();
        $result = $query->result_array();
        return $result;
}
public function getShiftsStaffs($user_id)
{
  $data = array(
            'users.id',
            'users.weekly_hours',
            'personal_details.fname',
            'personal_details.lname',
            'unit.color_unit',
            'unit.unit_shortname',
            'master_shift.shift_name',
            'master_shift.shift_shortcut',
            'master_shift.id as shift_id',
            'master_designation.designation_code',
            'master_shift.shift_category',
            'master_shift.shift_type',
            'master_designation.sort_order',
            'master_designation.group',
            'personal_details.gender',
            'master_designation.id as designation_id'
             );
            $this->db->select($data);
            $this->db->from('users'); 
            $this->db->join('personal_details', 'personal_details.user_id = users.id'); 
            $this->db->join('user_unit', 'user_unit.user_id = users.id');
            $this->db->join('unit', 'unit.id = user_unit.unit_id'); 
            $this->db->join('master_designation', 'master_designation.id = users.designation_id');
            $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
            $this->db->where('users.id', $user_id);
            $query=$this->db->get();
            // print_r($this->db->error());exit();
              // print $this->db->last_query();
              // exit();
            $result = $query->result_array(); 
            return $result;
}
    public function getUsersFromOtherUnit()
    {
      $data = array(
        'rota_schedule.user_id',
        'rota_schedule.date',
        'users.email',
        'personal_details.fname',
        'personal_details.lname'
      );
      $this->db->select($data);
      $this->db->from('rota_schedule');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('users', 'users.id = personal_details.user_id');
      $this->db->where('rota_schedule.status', 0);
      $final_result = array();
      $array_count = 0;
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $count = count($query->result_array());
        $result = $query->result_array();
        for($i=0;$i<$count;$i+=7){
          array_push($final_result, $result[$i]);
        }
        return $final_result;
      }else{
        return [];
      }
    }
    public function getOtherUnitUserShiftDetails($id,$unit_id){
      $shift_array = ['0','1','2','3','4'];
      $this->db->select('*');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('unit','unit.id = rota_schedule.from_unit');
      $this->db->where('rota_schedule.user_id',$id);
      $this->db->where('rota_schedule.unit_id',$unit_id);
      $this->db->where_not_in('rota_schedule.shift_id',$shift_array);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        return $result;
      }else{
        return [];
      }
    }
    public function getOtherUserShiftDetails($id){
      $this->db->select('*');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('unit','unit.id = rota_schedule.from_unit');
      $this->db->where('rota_schedule.user_id',$id);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        return $result;
      }else{
        return [];
      }
    }

    public function checkUserinRota($date,$user_id)
    {
      $this->db->select('id');
      $this->db->from('rota_schedule'); 
      $this->db->where('rota_schedule.user_id',$user_id);
      $this->db->where('rota_schedule.date',$date);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        return $result;
      }else{
        return [];
      }

    }
    public function getSameUserShiftDetailsWithDate($id,$rota_id,$dates_array,$unit_id){
      $this->db->select('*');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('unit','unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.user_id',$id);
      $this->db->where('rota_schedule.unit_id',$unit_id);
      if($rota_id){
        $this->db->where('rota_schedule.rota_id',$rota_id);
      }
      $this->db->where_in('rota_schedule.date',$dates_array);
      $this->db->order_by('rota_schedule.date', 'asc');

      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $final_array = array();
      $shift_array = array();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        $count = count($result);
        $j = 0;
        for ($i = 0; $i < $count; $i++)
        {
          $j++;
          array_push($final_array,$result[$i]);          
          if($j == $count){
            $rota = $this->getRotaDatesFromRotaid($result[$i]['rota_id']);
            $week_array = array(
              'Week' => $final_array,
              'rota' => $rota[0]
            );
            array_push($shift_array,$week_array);
            $j = 0;
            $final_array = array();
          }
        }
        return $shift_array;
      }else{
        return [];
      }
    }
    public function getSameUserShiftDetails($id,$rota_id,$dates){
      $this->db->select('*');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('unit','unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.user_id',$id);
      if($rota_id){
        $this->db->where('rota_schedule.rota_id',$rota_id);
      }
      if(count($dates) > 0){
        $this->db->where_in('rota_schedule.date',$dates);
      }
      
      $query = $this->db->get();
      // echo $this->db->last_query();exit;
      $final_array = array();
      $shift_array = array();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        $count = count($result);
        if(count($dates) > 0){
          $increment_value = $count;
        }else{
          $increment_value = 7;
        }
        $j = 0;
        for ($i = 0; $i < $count; $i++)
        {
          $j++;
          array_push($final_array,$result[$i]);          
          if($j == $increment_value){
            $rota = $this->getRotaDatesFromRotaid($result[$i]['rota_id']);
            $week_array = array(
              'Week' => $final_array,
              'rota' => $rota[0]
            );
            array_push($shift_array,$week_array);
            $j = 0;
            $final_array = array();
          }
        }
        return $shift_array;
      }else{
        return [];
      }
    }
    public function getRotaDatesFromRotaid($rota_id){
      $this->db->select('rota.start_date');
      $this->db->from('rota');
      $this->db->where('rota.id',$rota_id);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function getSameUserShiftDetailsForMailOtherUnit($id,$rota_ids,$unit_id){
      $shift_array = ['0','1','2','3','4'];
      $this->db->select('*');
      $this->db->from('rota_schedule');
      // $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('unit','unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.user_id',$id);
      $this->db->where('rota_schedule.unit_id',$unit_id);
      $this->db->where_in('rota_schedule.rota_id',$rota_ids);
      // $this->db->where_not_in('rota_schedule.shift_id',$shift_array);
      $this->db->order_by('rota_schedule.date','ASC');

      $query = $this->db->get();
      $final_array = array();
      $shift_array = array();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        $count = count($result);
        $j = 0;
        for ($i = 0; $i < $count; $i++)
        {
          $j++;
          if($result[$i]['shift_id'] > 4)
          {
            array_push($final_array,$result[$i]);
          }         
          if($j == 7){
            $rota = $this->getRotaDatesFromRotaid($result[$i]['rota_id']);
            $week_array = array(
              'Week' => $final_array,
              'rota' => $rota[0]
            );
            array_push($shift_array,$week_array);
            $j = 0;
            $final_array = array();
          }
        }
        return $shift_array;
      }else{
        return [];
      }
    }
    public function getSameUserShiftDetailsForMail($id,$rota_ids){
      $this->db->select('*');
      $this->db->from('rota_schedule');
      // $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('unit','unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.user_id',$id);
      $this->db->where_in('rota_schedule.rota_id',$rota_ids);
      $this->db->order_by('rota_schedule.date','ASC');

      $query = $this->db->get();
      $final_array = array();
      $shift_array = array();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        $count = count($result);
        $j = 0;
        for ($i = 0; $i < $count; $i++)
        {
          $j++;
          array_push($final_array,$result[$i]);          
          if($j == 7){
            $rota = $this->getRotaDatesFromRotaid($result[$i]['rota_id']);
            $week_array = array(
              'Week' => $final_array,
              'rota' => $rota[0]
            );
            array_push($shift_array,$week_array);
            $j = 0;
            $final_array = array();
          }
        }
        return $shift_array;
      }else{
        return [];
      }
    }
    public function approveRejectShifts($id,$status,$rota_ids){
      $update_data = array('status' => $status);
      $this->db->where(array('user_id' => $id));
      $this->db->where_in('rota_id',$rota_ids);
      $this->db->update('rota_schedule',$update_data);
      if($this->db->affected_rows() > 0 ){
        return 1;
      }else{
        return 2;
      }
    }
    public function approveRejectShift($id,$status){
      $update_data = array('status' => $status);
      $this->db->where(array('user_id' => $id));
      // $this->db->where_in('rota_id',$rota_ids);
      $this->db->update('rota_schedule',$update_data);
      if($this->db->affected_rows() > 0 ){
        return 1;
      }else{
        return 2;
      }
    }
    public function getUsersFromSameUnit(){
      $data = array(
        'rota_schedule.user_id',
        'rota_schedule.date',
        'users.email',
        'personal_details.fname',
        'personal_details.lname'
      );
      $array = array('rota_schedule.status' => 1, 'rota_schedule.from_unit' => null);
      $this->db->select($data);
      $this->db->from('rota_schedule');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
      $this->db->join('users', 'users.id = personal_details.user_id');
      $this->db->where($array);
      $final_result = array();
      $array_count = 0;
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $count = count($query->result_array());
        $result = $query->result_array();
        for($i=0;$i<$count;$i+=7){
          array_push($final_result, $result[$i]);
        }
        return $final_result;
      }else{
        return [];
      }
    }
    public function checkRotaDataWithStartAndEndDate($start_date,$end_date,$unit_id){
      $this->load->model('Unit_model');
      //$unit_id = $this->Unit_model->returnParentUnit($unit_id);
      $unit_id=$unit_id;
      $array = array(
        'rota.start_date' => $start_date, 
        'rota.end_date'   => $end_date,
        'unit_id' => $unit_id
      );
      $this->db->select('*');
      $this->db->from('rota');
      $this->db->where($array);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      if($query->num_rows() > 0)
      {
        $result = $query->result_array();
        if($result[0]['published'] == 0)
        {
          return array(
            'status' => 1,
            'result' => $result
          );
        }
        else
        {
          return array(
            'status' => 2,
            'result' => $result 
          );
        }
      }
      else
      {
        return array(
          'status' => 3,
          'result' => []
        );
      }
    }
    public function getPrevNextShift($prev_date,$next_date,$user_id){
      $dates = [$prev_date,$next_date];
      $this->db->select('
        rota_schedule.shift_id,
        rota_schedule.unit_id,
        rota_schedule.user_id,
        rota_schedule.date,
        master_shift.start_time,
        master_shift.end_time,
        master_shift.shift_shortcut
      ');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->where_in('rota_schedule.date',$dates);
      $this->db->where('rota_schedule.user_id',$user_id);
      $this->db->where('rota_schedule.shift_id >',4);
      $this->db->where('rota_schedule.shift_id !=',null);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;

    }
    public function getZeroHoursShifts(){
    $shift_id_array = array();
    $this->db->select('id');
    $this->db->from('master_shift');
    $this->db->where('hours',0);
    $query = $this->db->get();
    $result = $query->result_array();
    foreach ($result as $value) 
      {
        array_push($shift_id_array, $value['id']);
      };
      return $shift_id_array;
    }
    public function getPrevNextShiftForHighlightingColor($prev_date,$user_id){
      $shift_id_array = $this->getZeroHoursShifts();
      $this->db->select('
        rota_schedule.shift_id,
        rota_schedule.unit_id,
        rota_schedule.user_id,
        rota_schedule.date,
        master_shift.start_time,
        master_shift.end_time,
        master_shift.shift_shortcut
      ');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->where('rota_schedule.date',$prev_date);
      $this->db->where('rota_schedule.user_id',$user_id);
      $this->db->where_not_in('rota_schedule.shift_id',$shift_id_array);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;

    }
    public function findUnpublishedShiftUsers($params)
    {
      
      $start_date = $params['start_date'];
      $end_date   = $params['end_date'];
      $array = array(
        'rota.start_date' => $start_date, 
        'rota.end_date'   => $end_date
      );
      $this->db->select('*');
      $this->db->from('rota');
      $this->db->join('rota_schedule', 'rota_schedule.rota_id = rota.id');
      $this->db->where($array);
      $final_result = array();
      $query = $this->db->get();
      $result = $query->result_array();
      if($query->num_rows() > 0)
      {
        $count = count($query->result_array());
        $result = $query->result_array();
        for($i=0;$i<$count;$i+=7)
        {
          array_push($final_result, $result[$i]['user_id']);
        }
        array_push($final_result, "1");
        if(count($final_result) > 0)
        {
          $this->db->select('*');
          $this->db->from('users');
          $this->db->join('personal_details', 'personal_details.user_id = users.id');
          $this->db->join('user_unit', 'user_unit.user_id = users.id');
          $this->db->where('user_unit.unit_id', $params['unit_id']);
          $this->db->where('users.default_shift', $params['shift_id']);
          $this->db->where_not_in('users.id', $final_result);
          $data = $this->db->get();
          $users = $data->result_array();
          return $users;
        }
      }
      else
      {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->join('user_unit', 'user_unit.user_id = users.id');
        $this->db->where('user_unit.unit_id', $params['unit_id']);
        $this->db->where('users.default_shift', $params['shift_id']);
        $this->db->where('users.id !=1');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
      }
    }
    public function findUsersShift($id)
      {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('default_shift', $id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
      }
  public function getAvailableShifts()
  {
    $this->db->select('*');
    $this->db->from('available_master_shift');
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }
  public function getUserAvlDefaultShift($shift_id){
    $this->db->select('*');
    $this->db->from('available_master_shift');
    // $this->db->where('available_master_shift.parent_id',$shift_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function checkUserStatus($actual_date,$user_id,$unit_id_for,$shift_id)
  {
    $this->db->select('*');
    $this->db->from('available_requests');
    $this->db->join('available_requested_users', 'available_requested_users.avialable_request_id = available_requests.id');
    $this->db->where('available_requests.date',$actual_date);
    $this->db->where('available_requests.shift_id',$shift_id);
    $this->db->where('available_requests.to_unitid',$unit_id_for);
    $this->db->where('available_requested_users.user_id',$user_id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
  }
  public function getParentShift($shift_id){
    $this->db->select('available_master_shift.parent_id');
    $this->db->from('available_master_shift');
    $this->db->where('available_master_shift.id',$shift_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function findautoinsertbyDate($user_id,$unit_id,$date)
  { 
    $this->db->select('auto_insert');
    $this->db->from('rota_schedule');
    $this->db->where('rota_schedule.user_id',$user_id);
    $this->db->where('rota_schedule.unit_id',$unit_id);
    $this->db->where('rota_schedule.date',$date);
    $query = $this->db->get();
    $result = $query->result_array(); //print_r($result);exit();
    return $result[0]['auto_insert'];
  }
}
?>