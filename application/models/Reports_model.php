<?php
  class Reports_Model extends CI_Model 
  {

  public function getRotaadditionalData()
  {

    $data=array(
      'rota_schedule.date',
      'rota_schedule.user_id',
      'rota_schedule.unit_id',
      'rota_schedule.additional_hours',
      'rota_schedule.day_additional_hours',
      'rota_schedule.night_additional_hours',
      'rota_schedule.additinal_hour_timelog_id',
      'rota_schedule.comment',
      'rota_schedule.creation_date',
      'rota_schedule.created_userid',
      'rota_schedule.updation_date',
      'rota_schedule.updated_userid');

      $this->db->select($data);
      $this->db->from('rota_schedule');
      $this->db->where('additional_hours !='," "); 
      $this->db->or_where('day_additional_hours !='," ");
      $this->db->or_where('night_additional_hours !='," ");
      $this->db->or_where('comment !='," ");
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }

  //---------------------------------------------------------------------new reports

  public function getAvailabilestaffcount($params,$date)
  {  
    //print_r($params);pexit();
    
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }

      $this->db->select('*'); 
      $this->db->from('staffrota_schedule');
      $this->db->join('users','users.id=staffrota_schedule.user_id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');

      if($params['unit_id']>0)
      {
          if(empty($result))
          {
             $this->db->where('staffrota_schedule.unit_id',$params['unit_id']);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('staffrota_schedule.unit_id',$params['unit_id']);
             foreach ($result as $value) { 
              $this->db->or_where('staffrota_schedule.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
      }
      if($params['jobrole']>0)
      { 
        $this->db->where_in('master_designation.jobrole_groupid',$params['jobrole']);
      }
       
      $this->db->where('staffrota_schedule.date',$date);
      $this->db->where('staffrota_schedule.shift_id >=',1000);
      
      $query = $this->db->get();
      $result = $query->row_array();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }


  public function getAgencystaffcount($params,$date)
  {  
    //print_r($params);pexit();
    
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }

      $this->db->select('*'); 
      $this->db->from('rota_schedule');
      $this->db->join('users','users.id=rota_schedule.user_id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');
      $this->db->join('master_payment_type','master_payment_type.id=users.payment_type');
      $this->db->join('master_shift','master_shift.id=rota_schedule.shift_id','left'); 

      if($params['unit_id']>0)
      {
          if(empty($result))
          {
             $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
             foreach ($result as $value) { 
              $this->db->or_where('rota_schedule.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
      }
      if($params['jobrole']>0)
      { 
        $this->db->where_in('master_designation.jobrole_groupid',$params['jobrole']);
      }
       
      
      $this->db->where('rota_schedule.date',$date);
      $this->db->where('master_shift.shift_category >',0);

      $this->db->where('users.payment_type =',1);
      
      $query = $this->db->get();
      $result = $query->row_array();
     //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }



  public function getAvailabilitydata($params,$date)
  {  
    //print_r($params); exit();
    
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }

      $data= array(
      
      'available_requests.id',
      'available_requested_users.user_id',
      'available_requests.from_unitid',
      'available_requested_users.status',
      'available_requests.shift_id',
      'available_requests.date',
      'master_designation.jobrole_groupid',
      'master_designation.availability_requests'

      );

      $this->db->select($data); 
      $this->db->from('available_requests');
      $this->db->join('available_requested_users','available_requests.id=available_requested_users.avialable_request_id');
      $this->db->join('users','users.id=available_requested_users.user_id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');
      if($params['unit_id']>0)
      {
          if(empty($result))
          {
             $this->db->where('available_requests.from_unitid',$params['unit_id']);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('available_requests.from_unitid',$params['unit_id']);
             foreach ($result as $value) { 
              $this->db->or_where('available_requests.from_unitid', $value['id']);
            } 
            $this->db->group_end();
          }
      }
      if($params['jobrole']>0)
      { 
        $this->db->where_in('master_designation.jobrole_groupid',$params['jobrole']);
      }
       
      $this->db->where('available_requests.date',$date);
      
      $query = $this->db->get();
      //echo $this->db->last_query();//exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }

  public function getAvailabilitydataaccedpted($params,$date)
  {
     //print_r($params);pexit();
    
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }

      $data= array(
      
      'available_requests.id',
      'available_requested_users.user_id',
      'available_requests.from_unitid',
      'available_requested_users.status',
      'available_requests.shift_id',
      'available_requests.date',
      'master_designation.jobrole_groupid',
      'master_designation.availability_requests'

      );

      $this->db->select($data); 
      $this->db->from('available_requests');
      $this->db->join('available_requested_users','available_requests.id=available_requested_users.avialable_request_id');
      $this->db->join('users','users.id=available_requested_users.user_id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');
      if($params['unit_id']>0)
      {
          if(empty($result))
          {
             $this->db->where('available_requests.from_unitid',$params['unit_id']);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('available_requests.from_unitid',$params['unit_id']);
             foreach ($result as $value) { 
              $this->db->or_where('available_requests.from_unitid', $value['id']);
            } 
            $this->db->group_end();
          }
      }
      if($params['jobrole']>0)
      { 
        $this->db->where_in('master_designation.jobrole_groupid',$params['jobrole']);
      }
       
      $this->db->where('available_requests.date',$date);
      $this->db->where('available_requested_users.status',1);
      
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }


  public function GetCreatedUserlist($params,$date)
  {
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }


      $this->db->select('available_requests.created_userid'); 
      $this->db->from('available_requests');
      $this->db->join('available_requested_users','available_requests.id=available_requested_users.avialable_request_id');
      $this->db->join('users','users.id=available_requested_users.user_id');
      $this->db->join('personal_details','personal_details.user_id=users.id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');

      if($params['user_id']>0)
      { 
          $this->db->where('available_requested_users.user_id',$params['user_id']);
      }
      else
      {
          if($params['unit_id']>0)
          {
              if(empty($result))
              {
                 $this->db->where('available_requests.from_unitid',$params['unit_id']);
              }
              else
              {
                $this->db->group_start();
                $this->db->where('available_requests.from_unitid',$params['unit_id']);
                 foreach ($result as $value) { 
                  $this->db->or_where('available_requests.from_unitid', $value['id']);
                } 
                $this->db->group_end();
              }
          }

          if($params['status']>0)
          {
            $this->db->where('users.status',$params['status']);
          }
      }
    
      $this->db->where('available_requests.date',$date);
      $this->db->group_by('available_requests.created_userid');
      
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }


  public function getrequestedusername($id)
  {
    $this->db->select('personal_details.fname,personal_details.lname');
    $this->db->from('personal_details'); 
    $this->db->where('user_id', $id);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result[0]['fname'].' '.$result[0]['lname'];
  }

  
  public function getAvailabilitydataByuser($params,$date,$createdby)
  {  
    //print_r($createdby); exit();
    
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }

      $data= array(
      
      'available_requests.date',
      'available_requests.created_userid',
      'personal_details.fname',
      'personal_details.lname',
      'master_designation.designation_code'

      );

      $this->db->select($data); 
      $this->db->from('available_requests');
      $this->db->join('available_requested_users','available_requests.id=available_requested_users.avialable_request_id');
      $this->db->join('users','users.id=available_requested_users.user_id');
      $this->db->join('personal_details','personal_details.user_id=users.id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');

      if($params['user_id']>0)
      { 
          $this->db->where('available_requested_users.user_id',$params['user_id']);
      }
      else
      {
          if($params['unit_id']>0)
          {
              if(empty($result))
              {
                 $this->db->where('available_requests.from_unitid',$params['unit_id']);
              }
              else
              {
                $this->db->group_start();
                $this->db->where('available_requests.from_unitid',$params['unit_id']);
                 foreach ($result as $value) { 
                  $this->db->or_where('available_requests.from_unitid', $value['id']);
                } 
                $this->db->group_end();
              }
          }

          if($params['status']>0)
          {
            $this->db->where('users.status',$params['status']);
          }
      }
    
      $this->db->where('available_requests.date',$date);
      $this->db->where('available_requests.created_userid',$createdby);
      $this->db->order_by('master_designation.designation_code','desc');
      
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }

  public function getAvailabilitydataaccedptedByuser($params,$date,$createdby)
  {
     //print_r($params);pexit();
    
      if($params['unit_id']!='')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
      }

      $data= array(
      
      'available_requests.date',
      'available_requests.created_userid',
      'personal_details.fname',
      'personal_details.lname',
      'master_designation.designation_code'

      );

      $this->db->select($data); 
      $this->db->from('available_requests');
      $this->db->join('available_requested_users','available_requests.id=available_requested_users.avialable_request_id');
      $this->db->join('users','users.id=available_requested_users.user_id');
      $this->db->join('personal_details','personal_details.user_id=users.id');
      $this->db->join('master_designation','master_designation.id=users.designation_id');

      if($params['user_id']>0)
      { 
          $this->db->where('available_requested_users.user_id',$params['user_id']);
      }
      else
      {
          if($params['unit_id']>0)
          {
              if(empty($result))
              {
                 $this->db->where('available_requests.from_unitid',$params['unit_id']);
              }
              else
              {
                $this->db->group_start();
                $this->db->where('available_requests.from_unitid',$params['unit_id']);
                 foreach ($result as $value) { 
                  $this->db->or_where('available_requests.from_unitid', $value['id']);
                } 
                $this->db->group_end();
              }
          }
          if($params['status']>0)
          {
            $this->db->where('users.status',$params['status']);
          }
          
      }
    
      $this->db->where('available_requests.date',$date);
      $this->db->where('available_requested_users.status',1);
      $this->db->where('available_requests.created_userid',$createdby);
      $this->db->order_by('master_designation.designation_code','desc');
      
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print '<pre>';
      // print_r($result); print '<br>';
      // exit(); 
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 

  }


//----------------------------- new report end

  public function insertadditionalHourdata($datahome)
  {
    $this->db->insert('additional_hours',$datahome);
  }
  
   public function findTraining()
   {
 
      $this->db->select('*');
      $this->db->from('master_training');  
      // $this->db->where('personal_details.status', 1); 
      $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }

   public function findTrainingByuser($unit)
   {   //print_r(count($unit));exit();
        $this->db->select('*');
        $this->db->from('master_training');
        if(count($unit) > 0){
                 //print_r($unit);exit();
                $this->db->or_where("unit IN (".$unit.")",NULL, false);

            }
          
 
        $query = $this->db->get();
         // print $this->db->last_query();
         // exit();
     
        $result = $query->result_array(); 
        return $result;

   }
   public function fetchjobrole()
    {
        $data = array('master_designation.id','master_designation.designation_name');
        $this->db->select($data);
        $this->db->from('master_designation');
        $this->db->where('status', 1); 
        $this->db->order_by('designation_name','asc');
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
    }
    public function getTotalUsersOnLeave($start_date,$end_date,$selected_unit_id){
      $SQL="SELECT * FROM `holliday` where ( '".$start_date."' BETWEEN `from_date` AND `to_date`
      OR '".$end_date."' BETWEEN `from_date` AND `to_date`
      OR '".$end_date."' >= `from_date` AND '".$start_date."' <= `to_date`
      ) ANd  `unit_id`=".$selected_unit_id."  group by user_id" ;

       $query = $this->db->query($SQL);
       // echo $this->db->last_query();exit();
        $result = $query->result_array(); 
       //print_r($result);
        return $result;
    }

        public function getUserWeeklyLeaves($user_id,$start_date,$end_date)
    {

        $SQL="SELECT * FROM `holliday` where ( '".$start_date."' BETWEEN `from_date` AND `to_date`
        OR '".$end_date."' BETWEEN `from_date` AND `to_date`
        OR '".$end_date."' >= `from_date` AND '".$start_date."' <= `to_date`
        )
        ANd  `user_id`=".$user_id." order by status asc" ;

         $query = $this->db->query($SQL);
         //echo $this->db->last_query();exit();
          $result = $query->result_array(); 
         //print_r($result);
          return $result;

    }

    public function getUserWeeklyLeavesbyStartandEndDate($start_date,$end_date,$unit,$jobrole)
    {
      $SQL="SELECT `user_id`,`from_date`,`to_date`,`status`,`days` FROM `holliday` where ( '".$start_date."' BETWEEN `from_date` AND `to_date`
        OR '".$end_date."' BETWEEN `from_date` AND `to_date`
        OR '".$end_date."' >= `from_date` AND '".$start_date."' <= `to_date`
        )
        AND  `unit_id`=$unit
        AND  `status`=1 order by user_id asc" ;

         $query = $this->db->query($SQL);
          //echo $this->db->last_query();exit();
          $result = $query->result_array(); 
         //print_r($result);
          return $result;
    }

    public function findholiday($from_date,$to_date,$unit_id,$jobrole)
{  
    $data=array(
        'holliday.from_date',
        'holliday.to_date',
        'holliday.days',
        'holliday.status',
        'holliday.user_id',
        'holliday.unit_id',
        'users.designation_id');
      $this->db->select($data);
      $this->db->from('holliday');
      $this->db->join('users', 'users.id = holliday.user_id','left'); 
      $this->db->where('holliday.unit_id', $unit_id);
      $this->db->where('holliday.status', 1);
      if(count($jobrole)>0)
      {
        $this->db->where_in('users.designation_id',$jobrole);
      }

      $this->db->group_start();

      $this->db->group_start();
      $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($from_date)). '" and "'. date('Y-m-d', strtotime($to_date)).'"');
      $this->db->group_end();
      $this->db->or_group_start(); 
      $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($from_date)). '" and "'. date('Y-m-d', strtotime($to_date)).'"');
      $this->db->group_end(); 
      $this->db->or_group_start(); 
      $this->db->or_where("'".$from_date."'".'BETWEEN from_date and to_date');
      $this->db->group_end();  
      $this->db->or_group_start(); 
      $this->db->or_where("'".$to_date."'".'BETWEEN from_date and to_date');
      $this->db->group_end();

      $this->db->group_end();
      $this->db->group_by('holliday.user_id');
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array(); 
      return $result;
}

    public function getHoursWorkedbyUser($user_id,$year)
    { 
      $data=array('holiday_applied.hours');
      $this->db->select($data);
      $this->db->from('holliday');
      $this->db->join('holiday_applied', 'holiday_applied.holiday_id = holliday.id');
      $this->db->where('holliday.user_id',$user_id); 
      $this->db->where('holliday.status!=',2);
      $this->db->where('holliday.status!=',0);
      $this->db->where('holliday.status!=',3);
      $this->db->where('holiday_applied.year',$year);
      $query = $this->db->get();
     // print $this->db->last_query();
      //exit();
      $result = $query->result_array();
      if(count($result)>0)
      {
           $results=$result;
      }
      else
      {
          $results=0;
      }
      return $results; 

    }
 

   public function findTimelogData()
   {
        $this->db->select('*');
        $this->db->from('time_log');
        $this->db->join('users', 'users.id = time_log.user_id');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->join('master_shift', 'master_shift.id = time_log.shift_id','left');  
        $this->db->join('unit', 'unit.id = time_log.unit_id','left');
      // $this->db->where('personal_details.status', 1); 
        $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
        $result = $query->result_array();
        
   } 

    public function findholidayUser($params)
   {
      $data=array('holliday.user_id');
      $this->db->select($data);
      $this->db->from('holliday');
      $this->db->join('holiday_applied', 'holiday_applied.holiday_id = holliday.id');
      $this->db->join('personal_details', 'personal_details.user_id = holliday.user_id','left'); 
      $this->db->join('users', 'users.id = holliday.user_id','left'); 
      $this->db->where('holliday.status', 1);
      $this->db->where('holliday.unit_id',$params['unit_id']); 
      if(count($params['designation'])>0)
      {
          $this->db->where_in('users.designation_id',$params['designation']);
      }
      $this->db->group_start();

      $this->db->group_start();
      $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
      $this->db->group_end();
      $this->db->or_group_start(); 
      $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
      $this->db->group_end(); 
      $this->db->or_group_start(); 
      $this->db->or_where("'".$params['start_date']."'".'BETWEEN from_date and to_date');
      $this->db->group_end();  
      $this->db->or_group_start(); 
      $this->db->or_where("'". $params['end_date']."'".'BETWEEN from_date and to_date');
      $this->db->group_end(); 

      $this->db->group_end(); 

      $this->db->group_by('holliday.user_id');

      $query = $this->db->get();

      // print $this->db->last_query();
      // exit();

      $result = $query->result_array();
      return $result;
   }


   public function findholidaydata($params)
   {  
      $data=array(
        'personal_details.fname',
        'personal_details.lname',
        'holliday.from_date',
        'holliday.to_date',
        'holliday.days',
        'holliday.status',
        'holliday.user_id',
        'holliday.unit_id',
        'users.designation_id',
        'holiday_applied.year');
      $this->db->select($data);
      $this->db->from('holliday');
      $this->db->join('holiday_applied', 'holiday_applied.holiday_id = holliday.id');
      $this->db->join('personal_details', 'personal_details.user_id = holliday.user_id','left'); 
      $this->db->join('users', 'users.id = holliday.user_id','left'); 
      $this->db->where('holliday.status', 1);
      $this->db->where('holliday.unit_id',$params['unit_id']); 
      if(count($params['designation'])>0)
      {
          $this->db->where_in('users.designation_id',$params['designation']);
      }
      if($params['user_id']!=1)
      {
        $this->db->where('holliday.user_id=', $params['user_id']);
      }
      $this->db->group_start();

      $this->db->group_start();
      $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
      $this->db->group_end();
      $this->db->or_group_start(); 
      $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
      $this->db->group_end(); 
      $this->db->or_group_start(); 
      $this->db->or_where("'".$params['start_date']."'".'BETWEEN from_date and to_date');
      $this->db->group_end();  
      $this->db->or_group_start(); 
      $this->db->or_where("'". $params['end_date']."'".'BETWEEN from_date and to_date');
      $this->db->group_end(); 

      $this->db->group_end(); 

      $query = $this->db->get();

      // print $this->db->last_query();
      // exit();

      $result = $query->result_array();
      return $result;
   }
 
   public function findTimelogAbsent($params)
   {    
      if($params['unit_id']!='')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
       }

      $time=date('H:i:s');
      $this->db->select('personal_details.user_id,personal_details.fname,personal_details.lname,personal_details.mobile_number,personal_details.telephone,personal_details.kin_phone,rota_schedule.date,time_log.status,time_log.time_from,
        time_log.time_to','users.status');
      $this->db->from('rota_schedule');
      $this->db->join('time_log', 'time_log.user_id = rota_schedule.user_id','left');
      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id','left');
      $this->db->join('users', 'users.id = personal_details.user_id','left');
      if(!empty($result))
      {
        $this->db->group_start();
        $this->db->where('rota_schedule.unit_id',$params['unit_id']);
        foreach ($result as $value) {
           $this->db->or_where('rota_schedule.unit_id',$value['id']);
        }
        $this->db->group_end();
      }
      else
      {
        $this->db->where('rota_schedule.unit_id',$params['unit_id']);
      }
     
      if($params['shift_id']!='')
      {
      $this->db->where('rota_schedule.shift_id',$params['shift_id']);
      }
      if($params['jobrole']!='')
      {
      $this->db->where('users.designation_id',$params['jobrole']);
      }
      $this->db->where('rota_schedule.date',$params['date']);
      $this->db->group_start(); // Open bracket
      //$this->db->where('time_log.date!=',$params['date']);
      $this->db->where('time_log.id is null');
      $this->db->group_end();
      
    // if($params['status']!='')
      //  {
         $this->db->where('users.status', $params['status']);
        //}

      $this->db->group_by('rota_schedule.user_id');
      //$this->db->where('master_shift.start_time <=',$time);
      $query = $this->db->get();
      //echo $this->db->last_query(); exit();
      $result= $query->result_array(); 
      return $result; 

   }
   public function absence_list($params,$search= null,$limit= null,$start= null,$order= null,$dir= null,$count = false)
   {
       // print_r($params);exit();
     if($params['unit_id']!='')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }

       $data=array(
           'rota_schedule.user_id',
           'rota_schedule.unit_id',
           'rota_schedule.date',
           'rota_schedule.shift_id',
           'time_log.id as time_log_id',
           'time_log.user_id as timelog_userid',
           'time_log.date as timelog_date','time_log.time_from','time_log.time_to',
           'time_log.status',
           'personal_details.user_id',
           'personal_details.fname',
           'personal_details.lname',
           'personal_details.mobile_number',
           'personal_details.telephone',
           'personal_details.kin_name',
           'personal_details.kin_phone',
           'users.status',
           'master_designation.jobrole_groupid',
           'master_shift.shift_category'

       );
   
       $this->db->select($data);
       $this->db->from('rota_schedule');
       $this->db->join('time_log','time_log.user_id = rota_schedule.user_id AND time_log.date = rota_schedule.date','left');
       $this->db->join('personal_details','personal_details.user_id = rota_schedule.user_id','left');
       $this->db->join('users', 'users.id = personal_details.user_id','left');
       $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
       $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
       
       // $this->db->group_start();
       if($params['date']!='')
       {
           $this->db->where('rota_schedule.date',$params['date']);
       }
       $this->db->where('time_log.date is null');
       if($params['shift_id']!='none')
       {
           $this->db->where('master_shift.shift_category',$params['shift_id']);
       }
        if($params['unit_id']!='')
       {
         if(!empty($result))
          {
            $this->db->group_start();
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
            foreach ($result as $value) {
               $this->db->or_where('rota_schedule.unit_id',$value['id']);
            }
            $this->db->group_end();
          }
          else
          {
            $this->db->where('rota_schedule.unit_id',$params['unit_id']);
          }
       }

       if($params['jobrole']!='')
       {
           $this->db->where('master_designation.jobrole_groupid',$params['jobrole']);
       }
       if($params['status']!=0)
       {
          $this->db->where('users.status',$params['status']);
       }
       
       $this->db->where('rota_schedule.shift_id > 1');
       $this->db->where('rota_schedule.shift_id != 68');

      if($search){
        $this->db->group_start();
        $this->db->like('rota_schedule.user_id',$search);
        $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search);
        $this->db->or_like('personal_details.mobile_number',$search);
        $this->db->group_end();

      }
     
      $this->db->order_by($order,$dir);

      if($count  == true){
        $query = $this->db->get();
        return $query->num_rows();
    
      }
      else{
        if($limit>0)
        {
        $this->db->limit($limit,$start);
        }
        $query = $this->db->get();
      }
    //  echo $this->db->last_query();exit;
       $result = $query->result_array();
       return $result;
   }
   public function getTimelogOfUser($id)
   {
        $this->db->select('*');
        $this->db->from('time_log');
        $this->db->join('users', 'users.id = time_log.user_id');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->join('master_shift', 'master_shift.id = time_log.shift_id','left');  
        $this->db->join('unit', 'unit.id = time_log.unit_id','left');
       $this->db->where('time_log.user_id',$id); 
        $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
        $result = $query->result_array();
        return $result;

   }

     public function findunit()
      {
         $this->db->select('*');
         $this->db->from('unit'); 
         $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function finduserdataforall($unit_id,$status)
    {
        //print_r($status);exit();
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array();
 
         $this->db->select('personal_details.fname,personal_details.lname,user_unit.user_id');
         $this->db->from('user_unit'); 
         $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id','left');
         $this->db->join('users', 'users.id = personal_details.user_id');
         // if($status!=0)
         // {
         if($status!=0) 
         {
         $this->db->where('users.status', $status);
         }
         // }
         if(!empty($result))
         {
          $this->db->group_start();
          $this->db->where('unit_id', $unit_id);
           foreach ($result as $value) {
           $this->db->or_where('unit_id', $value['id']);
           }
           $this->db->group_end();
         }
         else
         {
          $this->db->where('unit_id', $unit_id);
         }
         $this->db->where('users.payment_type !=', 1);
         $this->db->order_by('personal_details.fname', "asc");
          
         $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }

      public function finduserdataforallforreport($unit_id,$status)
    {
        //print_r($status);exit();
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array();
 
         $this->db->select('personal_details.fname,personal_details.lname,user_unit.user_id');
         $this->db->from('user_unit'); 
         $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id','left');
         $this->db->join('users', 'users.id = personal_details.user_id');
         // if($status!=0)
         // {
         if($status!=0) 
         {
         $this->db->where('users.status', $status);
         }
         // }
         if(!empty($result))
         {
          $this->db->group_start();
          $this->db->where('unit_id', $unit_id);
           foreach ($result as $value) {
           $this->db->or_where('unit_id', $value['id']);
           }
           $this->db->group_end();
         }
         else
         {
          $this->db->where('unit_id', $unit_id);
         }
         $this->db->order_by('personal_details.fname', "asc");
          
         $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }


    public function finduserdata($unit_id,$status)
    {
        //print_r($status);exit();
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array();
 
         $this->db->select('personal_details.fname,personal_details.lname,user_unit.user_id');
         $this->db->from('user_unit'); 
         $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id','left');
         $this->db->join('users', 'users.id = personal_details.user_id');
         // if($status!=0)
         // {
         if($status=='' || $status==0) {
         $this->db->where('users.status', 1); }else{$this->db->where('users.status', $status);}
         // }
         if(!empty($result))
         {
          $this->db->group_start();
          $this->db->where('unit_id', $unit_id);
           foreach ($result as $value) {
           $this->db->or_where('unit_id', $value['id']);
           }
           $this->db->group_end();
         }
         else
         {
          $this->db->where('unit_id', $unit_id);
         }
         //$this->db->where('users.payment_type !=', 1);
         $this->db->order_by('personal_details.fname', "asc");
          
         $query = $this->db->get();
          //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }

    public function finduserdatanew($unit_id)
    {

        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array();
 
         $this->db->select('personal_details.fname,personal_details.lname,user_unit.user_id');
         $this->db->from('user_unit'); 
         $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id','left');
         $this->db->join('users', 'users.id = personal_details.user_id');
         // if($status!=0)
         // {
         //if($status=='' || $status==0) {
         //$this->db->where('users.status', 1); }else{$this->db->where('users.status', $status);}
         // }
         if(!empty($result))
         {
          $this->db->group_start();
          $this->db->where('unit_id', $unit_id);
           foreach ($result as $value) {
           $this->db->or_where('unit_id', $value['id']);
           }
           $this->db->group_end();
         }
         else
         {
          $this->db->where('unit_id', $unit_id);
         }
         $this->db->where('users.payment_type !=', 1);
         $this->db->order_by('personal_details.fname', "asc");
          
         $query = $this->db->get();
          //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }
   
    public function findextrahourdata($params)
    {  
     
      // if($params['unit_id']!='')
      // {
      // $this->db->select('id');
      // $this->db->from('unit'); 
      // $this->db->where('parent_unit', $params['unit_id']);
      // $query = $this->db->get();
      // $result = $query->result_array();
      // }
      // else
      // {
      //   return;
      // }
//print_r($result);  exit();
      $data=array(
          'master_shift.shift_name',
          'master_shift.targeted_hours as shift_hours',
          'master_shift.start_time',
          'master_shift.end_time',
          'time_log.time_to',
          'time_log.time_from',
          'time_log.status',
          'time_log.date',
          'time_log.user_id',
          'time_log.shift_id',
          'time_log.unit_id',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.user_id');
         $this->db->select($data);
         $this->db->from('time_log'); 
         $this->db->join('master_shift', 'master_shift.id = time_log.shift_id');
         $this->db->join('personal_details', 'personal_details.user_id = time_log.user_id');
         $this->db->where('time_log.user_id', $params['user_id']);
        
         // if($params['unit_id']!='')
         //  {
         //      if(!empty($result))
         //          {
         //            $this->db->group_start();
         //            $this->db->where('time_log.unit_id', $params['unit_id']);
         //            foreach ($result as $value) 
         //            {
         //               $this->db->or_where('time_log.unit_id',$value['id']);
         //            }
         //            $this->db->group_end();
         //          }
         //          else
         //          {
         //             $this->db->where('time_log.unit_id', $params['unit_id']);
         //          }
         //  }
       
         $this->db->where('MONTH(time_log.date)',$params['month']);
         $this->db->where('YEAR(time_log.date)',$params['year']); 
          // $this->db->where('time_log.date >=', $params['from_date']);
          // $this->db->where('time_log.date <=',  $params['to_date']);
         $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       }
       public function findpayrolldata($params){
           
           if($params['user_id']!='none')
           {
               $user_id = $params['user_id'];
           }
           
           $SQL = "SELECT 
 a.creation_date ,    a.time_to, a.time_from, a.status,  
 a.user_id, a.shift_id, a.unit_id, a.id as timelogid, 
 a.date as a,
 master_shift.shift_name, master_shift.targeted_hours as shift_hours, master_shift.id as shift_id,
 master_shift.unpaid_break_hours as break, master_shift.start_time, master_shift.end_time,
 master_shift.shift_category,  rota_schedule.date,
 personal_details.fname, personal_details.lname, personal_details.user_id 
  FROM 
     ( SELECT x.*
            , CASE WHEN @prev <> x.status THEN @stamp:=x.creation_date END stamp
            , @prev:=x.status prev 
         FROM time_log x
            , (SELECT @prev:=null,@creation_date:=null) vars 
            
            WHERE ";
            
          // if($user_id > 0)
               $SQL .= "x.user_id IN('".$user_id."') AND ";
           
               $SQL .= " ( x.date >= '".$params['from_date']."'    AND x.date <= '".$params['to_date']."' ) 
        ORDER 
           BY x.creation_date
     ) a
      LEFT JOIN rota_schedule  ON rota_schedule.user_id = a.user_id 
 AND rota_schedule.date = a.date 
 JOIN master_shift   ON master_shift.id = rota_schedule.shift_id 
 JOIN personal_details    ON personal_details.user_id = rota_schedule.user_id
 WHERE stamp IS NOT NULL  
 ORDER 
    BY a.creation_date;";
            
            $query = $this->db->query($SQL);
           //  echo $this->db->last_query(); exit;
            $result = $query->result_array();
            //print_r($result);
            return $result;
           
       }
       public function finduserInunit($unit_id,$status, $jobrole)
       {
           //print_r($status);exit();
           $this->db->select('id');
           $this->db->from('unit');
           $this->db->where('parent_unit', $unit_id);
           $query = $this->db->get();
           $result = $query->result_array();
           $arr = array();
           $this->db->select('personal_details.user_id');
           $this->db->from('user_unit');
           $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id','left');
           $this->db->join('users', 'users.id = personal_details.user_id');
           // if($status!=0)
           // {
           if($status=='' || $status==0) {
               $this->db->where('users.status', 1); }else{$this->db->where('users.status', $status);}
               // }
               if(!empty($result))
               {
                   $this->db->group_start();
                   $this->db->where('unit_id', $unit_id);
                   foreach ($result as $value) {
                       $this->db->or_where('unit_id', $value['id']);
                   }
                   $this->db->group_end();
               }
               else
               {
                   $this->db->where('unit_id', $unit_id);
               }
     
               if(count($jobrole) > 0)
               {      
                   $this->db->where_in('users.designation_id',$jobrole); 
               }
               $this->db->order_by('personal_details.fname', "asc");
               
               $query = $this->db->get();
             //   echo $this->db->last_query();exit;
               $result = $query->result_array();
               if(count($result)>0){
               $arr = array_map (function($value){
                   return $value['user_id'];
               } , $result);
               }
                   
                   return $arr;
                   
           }
       public function findSchedule($params)
       {  //print_r($params);exit();
    
           
           // print_r($result);  exit();
           $data=array(
               'master_shift.shift_name',
               'master_shift.targeted_hours as shift_hours',
               'master_shift.unpaid_break_hours as break',
               'master_shift.start_time',
               'master_shift.end_time',
               'master_shift.shift_category','rota_schedule.date','rota_schedule.shift_id','rota_schedule.id','rota_schedule.from_unit',
               'personal_details.fname',
               'personal_details.lname',
               'personal_details.user_id'
           );
           $this->db->select($data);
           $this->db->from('rota_schedule');
           $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
           $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
        
           
           
           
           $this->db->group_start();
           $this->db->where('rota_schedule.date >=', $params['from_date']);
           $this->db->where('rota_schedule.date <=',  $params['to_date']);
           $this->db->group_end();
           
           $this->db->where_not_in('rota_schedule.date', $params['dates']);
           //print count($params['user_id']); exit;
           if(count($params['user_id'])>0)
           $this->db->where_in('rota_schedule.user_id', $params['user_id']);
           
          // $this->db->order_by("rota_schedule.user_id asc,rota_schedule.date asc");
           $query = $this->db->get();
            // echo $this->db->last_query(); 
           $result = $query->result_array();
      
           return $result;
           
       }


           public function findpayrolldataIndividual($params)
    {  //print_r($params);exit();


         $data=array(
          'master_shift.shift_name',
          'master_shift.targeted_hours as shift_hours',
          'master_shift.unpaid_break_hours as break',
          'master_shift.start_time',
          'master_shift.end_time',
          'master_shift.shift_category',
          'master_shift.shift_name',
          'rota_schedule.date',
          'rota_schedule.shift_id',
          'rota_schedule.id as timelogid',
          'rota_schedule.from_unit',
          'rota_schedule.unit_id',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.user_id');
          $this->db->select($data);
          $this->db->from('rota_schedule');  
          $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
          $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
          $this->db->where('rota_schedule.user_id', $params['user_id']);
          $this->db->group_start();
          $this->db->where('rota_schedule.date >=', $params['from_date']);
          $this->db->where('rota_schedule.date <=',  $params['to_date']);
          $this->db->group_end();
          $this->db->order_by("rota_schedule.user_id asc,rota_schedule.date asc,rota_schedule.shift_hours asc"); 
         $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       
    }


     public function findpayrolldata2($params)
    {  //print_r($params);exit();
       if($params['unit_id']!='none')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      
// print_r($result);  exit();
         $data=array(
          'master_shift.shift_name',
          'master_shift.targeted_hours as shift_hours',
          'master_shift.unpaid_break_hours as break',
          'master_shift.start_time',
          'master_shift.end_time',
          'master_shift.shift_category',
          'time_log.time_to',
          'time_log.time_from',
          'time_log.status',
          'rota_schedule.date',
          'time_log.user_id',
          'time_log.shift_id',
          'time_log.unit_id',
          'time_log.id as timelogid',
          'time_log.date as time_log_date',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.user_id');
         $this->db->select($data);
         $this->db->from('rota_schedule'); 
         $this->db->join('time_log', 'time_log.user_id = rota_schedule.user_id AND `time_log`.`date` = `rota_schedule`.`date`','left');
         $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
         $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
         if($params['user_id']!='none')
         {
         $this->db->where('rota_schedule.user_id', $params['user_id']);
         }
         else
         {
          $this->db->join('users', 'users.id = rota_schedule.user_id');
          if($params['status'])
          {
            $this->db->where('users.status',$params['status']);
          }
          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }

          if($params['unit_id']!='')
            {
                if(!empty($result))
                    {
                      $this->db->group_start();
                      $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                      foreach ($result as $value) 
                      {
                         $this->db->or_where('rota_schedule.unit_id',$value['id']);
                      }
                      $this->db->group_end();
                    }
                    else
                    {
                       $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                    }
            }

         }
           
        
       
          $this->db->group_start();
          $this->db->where('rota_schedule.date >=', $params['from_date']);
          $this->db->where('rota_schedule.date <=',  $params['to_date']);
          $this->db->group_end();
          $this->db->order_by("rota_schedule.user_id asc,rota_schedule.date asc"); 
         $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       
    }

    public function findpayrolldata3($params)
    {  //print_r($params);exit();
       if($params['unit_id'])
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
// print_r($result);  exit();
         $data=array(
          'master_shift.shift_name',
          'master_shift.targeted_hours as shift_hours',
          'master_shift.unpaid_break_hours as break',
          'master_shift.start_time',
          'master_shift.end_time',
          'master_shift.shift_category',
          'master_shift.shift_name',
          'rota_schedule.date',
          'rota_schedule.shift_id',
          'rota_schedule.id as timelogid',
          'rota_schedule.from_unit',
          'rota_schedule.unit_id',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.user_id');
         $this->db->select($data);
         $this->db->from('rota_schedule');  
         $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
         $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');
         if($params['user_id']!='none')
         {
         $this->db->where('rota_schedule.user_id', $params['user_id']);
         }
         else
         {
          $this->db->join('users', 'users.id = rota_schedule.user_id');
          $this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
          if($params['status']!=0)
          {
            $this->db->where('users.status',$params['status']);
          }
          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }

          if($params['unit_id']!='')
            {
                if(!empty($result))
                    { 
                      $this->db->group_start(); 
                        $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                        $this->db->or_where('rota_schedule.from_unit', $params['unit_id']);
                        foreach ($result as $value) 
                        {
                           $this->db->or_where('rota_schedule.unit_id',$value['id']);
                           $this->db->or_where('rota_schedule.from_unit',$value['id']);
                        }
                      $this->db->group_end();

                       $this->db->group_start(); 
                        $this->db->where('user_unit.unit_id', $params['unit_id']);
                        foreach ($result as $value) 
                        {
                           $this->db->or_where('user_unit.unit_id', $value['id']);
                        }
                      $this->db->group_end();

                    }
                    else
                    {  
                       $this->db->group_start();
                       $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                       $this->db->or_where('rota_schedule.from_unit', $params['unit_id']);
                       $this->db->group_end();
                       $this->db->where('user_unit.unit_id', $params['unit_id']);

                    }
                    
            }
          $this->db->where('users.payment_type !=',1);

         }
           
          $this->db->group_start();
          $this->db->where('rota_schedule.date >=', $params['from_date']);
          $this->db->where('rota_schedule.date <=',  $params['to_date']);
          $this->db->group_end();
          $this->db->order_by("rota_schedule.user_id asc,rota_schedule.date asc,rota_schedule.shift_hours asc"); 
         $query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       
    }

     function staffstimelogreport($params)
    {    
        //print_r($params);exit();
        $data=array(
            'rota_schedule.user_id',
            'personal_details.fname',
            'personal_details.lname',
            'master_shift.shift_name','master_shift.id as shiftid',
            'unit.unit_name',
            'time_log.device_id',
            'time_log.date as timelogdate',
            'rota_schedule.date as rotadate',
            'time_log.time_to',
            'time_log.time_from',
            'time_log.unit_id',
            'rota_schedule.unit_id as rota_unit'
        );
        $this->db->select($data);
        $this->db->from('rota_schedule'); 
        $this->db->join('time_log', 'time_log.user_id = rota_schedule.user_id AND `time_log`.`date` = `rota_schedule`.`date`','left');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');  
        $this->db->join('unit', 'unit.id = time_log.user_unit','left'); 
         $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->where('users.status',1); 
        $this->db->where('rota_schedule.user_id', $params['user_id']);
        $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['start_date']);
        $this->db->where('rota_schedule.date <=',  $params['end_date']);
        $this->db->group_end();
        
        $this->db->group_start();
        $this->db->where('time_log.status', 1);
        $this->db->or_where('time_log.status', 0);
        $this->db->group_end();

        $this->db->where('time_log.accuracy >', 0);


        $this->db->order_by("rota_schedule.date asc,time_log.id asc");  
        
        $query = $this->db->get();
        //echo $this->db->last_query();
       // exit;
        $result = $query->result_array();
        return $result;
    
    }


       function timelogreport($params)
    {    
        //print_r($params);exit();
      if($params['unit']!='none')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();     
      }
        $data=array(
            'rota_schedule.user_id',
            'personal_details.fname',
            'personal_details.lname',
            'master_shift.shift_name','master_shift.id as shiftid',
            'unit.unit_name',
            'time_log.device_id',
            'time_log.date as timelogdate',
            'rota_schedule.date as rotadate',
            'time_log.time_to',
            'time_log.time_from',
            'time_log.unit_id',
            'rota_schedule.unit_id as rota_unit'
        );
        $this->db->select($data);
        $this->db->from('rota_schedule'); 
        $this->db->join('time_log', 'time_log.user_id = rota_schedule.user_id AND `time_log`.`date` = `rota_schedule`.`date`','left');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');  
        $this->db->join('unit', 'unit.id = time_log.user_unit','left'); 
         $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->where('users.status',1); 
        $this->db->where('rota_schedule.shift_id !=',0); 
        if($params['unit']!='none')
        {

          if(empty($result))
          {
            $this->db->group_start();
            $this->db->where('rota_schedule.unit_id',$params['unit']);
            $this->db->or_where('rota_schedule.from_unit',$params['unit']);
            $this->db->group_end();
          }
          else
          {
            $this->db->group_start();
                $this->db->group_start();
                $this->db->where('rota_schedule.unit_id',$params['unit']);
                $this->db->or_where('rota_schedule.from_unit',$params['unit']);
                $this->db->group_end();
             foreach ($result as $value) { 
              $this->db->or_where('rota_schedule.unit_id', $value['id']);
            } 
            $this->db->group_end();
          } 

        
        }
        if($params['jobrole']!='')
        {   
        $this->db->where_in('users.designation_id',$params['jobrole']);
        }
        $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['start_date']);
        $this->db->where('rota_schedule.date <=',  $params['end_date']);
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where('time_log.status', 1);
        $this->db->or_where('time_log.status', 0);
        $this->db->group_end();

        $this->db->where('time_log.accuracy >', 0);

        $this->db->order_by("rota_schedule.user_id asc,rota_schedule.date asc,time_log.id asc"); 
        
        $query = $this->db->get();
       //  echo $this->db->last_query();
       // exit;
        $result = $query->result_array();
        return $result;
    
    }
    

    ///
     function alltimelog_count($units)
    {   
        $this->db->select('*');
        $this->db->from('time_log');   
        if($units!=NULL)
        {
             $this->db->where('unit_id',$units);
        }
        $query = $this->db->get();
         //echo $this->db->last_query();exit;
        return $query->num_rows();  

    }
    
    function alltimelog($limit,$start,$col,$dir,$units)
    {    
        $data=array(
            'time_log.payroll_id',
            'personal_details.fname',
            'personal_details.lname',
            'master_shift.shift_name',
            'unit.unit_name',
            'time_log.device_id',
            'time_log.date',
            'time_log.time_to',
            'time_log.time_from'
        );
        $this->db->select($data);
        $this->db->from('time_log');
        $this->db->join('users', 'users.id = time_log.user_id');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->join('master_shift', 'master_shift.id = time_log.shift_id','left');  
        $this->db->join('unit', 'unit.id = time_log.unit_id','left'); 
        $this->db->limit($limit,$start);

         if($units!=NULL)
        {
             $this->db->where('unit_id',$units);
        }
        //$this->db->order_by($col,$dir); 
        $query = $this->db->get(); 
       //echo $this->db->last_query();exit();
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function timelog_search($search,$units)
    {   
        $this->db->select('*');
        $this->db->from('time_log');
        $this->db->join('users', 'users.id = time_log.user_id');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->join('master_shift', 'master_shift.id = time_log.shift_id','left');  
        $this->db->join('unit', 'unit.id = time_log.unit_id','left');
        if($units!=NULL)
        {
             $this->db->where('unit_id',$units);
        }
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        $this->db->or_like('users.payroll_id',$search);
        $this->db->or_like('master_shift.shift_name',$search);
        $this->db->or_like('unit.unit_name',$search);  
        $query = $this->db->get();
         //echo $this->db->last_query();exit();
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

  function allemployee_count($unit,$jobrole,$status,$enrolled_status,$pass_enable)
    {  //print_r($enrolled_status);exit();

      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
      //print_r($unit);exit();
        $data=array(
          'users.id',
          'users.payroll_id',
          'users.status',
          'users.thumbnail',
          'users.pass_enable',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.address1',
          'personal_details.address2',
          'personal_details.city',
          'personal_details.postcode',
          'personal_details.mobile_number',
          'unit.unit_name',
          'master_designation.designation_name'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        if($status!='')
        {
           if($status!=0)
          {
            $this->db->where('users.status',$status);
          }

        }

         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
              $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
        if($pass_enable!=0)
        {
          if($pass_enable==1)
          {
             $this->db->where('users.pass_enable',1);
          }
          else
          {
            $this->db->group_start();
             $this->db->where('users.pass_enable',0);
             $this->db->or_where('users.pass_enable is null');
            $this->db->group_end();
          }
          
        }
        $this->db->order_by('users.id');
         $query = $this->db->get(); 
       //echo $this->db->last_query();exit;
         if($status!='')
        {
           $result= $query->num_rows();
        }
        else
        {
          $result= 0;
        }
       //print_r($result);exit();
        return   $result;

    }
    
    function allemployee($limit,$start,$col,$dir,$unit,$jobrole,$status,$enrolled_status,$pass_enable)
    {    
        //print_r($limit); print_r('<br>'); print_r($start);exit();
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        $data=array(
          'users.id',
          'users.payroll_id',
          'users.status',
          'users.thumbnail',
          'users.pass_enable',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.address1',
          'personal_details.address2',
          'personal_details.city',
          'personal_details.postcode',
          'personal_details.mobile_number',
          'unit.unit_name',
          'master_designation.designation_name'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left'); 
        // if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!='')
        {
           if($status!=0)
          {
            $this->db->where('users.status',$status);
          }

        }
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
          if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
        if($pass_enable!=0)
        {
          if($pass_enable==1)
          {
             $this->db->where('users.pass_enable',1);
          }
          else
          {
            $this->db->group_start();
             $this->db->where('users.pass_enable',0);
             $this->db->or_where('users.pass_enable is null');
            $this->db->group_end();
          }
          
        }
        //$this->db->order_by($col,$dir); 
        $this->db->order_by('users.id');
        $query = $this->db->get(); 
      // echo $this->db->last_query();exit();

         if($status!='')
        {
              if($query->num_rows()>0)
            {
                return $query->result(); 
            }
            else
            {
                return null;
            }
        }
        else
        {
           return null;
        }

        
        
    }

    function allemployeesearch_count($search,$unit,$jobrole,$status,$enrolled_status,$pass_enable)
    {  
       if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
         $data=array(
          'users.id',
          'users.payroll_id',
          'users.status',
          'users.thumbnail',
          'users.pass_enable',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.address1',
          'personal_details.address2',
          'personal_details.city',
          'personal_details.postcode',
          'personal_details.mobile_number',
          'unit.unit_name',
          'master_designation.designation_name'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left'); 
        // if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!='')
        {
           if($status!=0)
          {
            $this->db->where('users.status',$status);
          }

        }
         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
        if($pass_enable!=0)
        {
          if($pass_enable==1)
          {
             $this->db->where('users.pass_enable',1);
          }
          else
          {
            $this->db->group_start();
             $this->db->where('users.pass_enable',0);
             $this->db->or_where('users.pass_enable is null');
            $this->db->group_end();
          }
          
        }
        $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('users.payroll_id',$search);
        $this->db->group_end();
        $this->db->order_by('users.id');
        
        //$this->db->or_like('master_group.group_name',$search);
        //$this->db->or_like('master_designation.designation_name',$search);  
        //$this->db->->limit($limit,$start);
        $query = $this->db->get(); 
          //echo $this->db->last_query();exit;
          if($status!='')
        {
           $result= $query->num_rows();
        }
        else
        {
          $result= null;
        }
       // print_r($result);exit();
        return   $result;

    }
   
    function  allemployee_search($limit,$start,$search,$unit,$jobrole,$status,$enrolled_status,$pass_enable)
    {   
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
         $data=array(
          'users.id',
          'users.payroll_id',
          'users.status',
          'users.thumbnail',
          'users.pass_enable',
          'personal_details.fname',
          'personal_details.lname',
          'personal_details.address1',
          'personal_details.address2',
          'personal_details.city',
          'personal_details.postcode',
          'personal_details.mobile_number',
          'unit.unit_name',
          'master_designation.designation_name'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left'); 
        // if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!='')
        {
           if($status!=0)
          {
            $this->db->where('users.status',$status);
          }

        }
         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
        if($pass_enable!=0)
        {
          if($pass_enable==1)
          {
             $this->db->where('users.pass_enable',1);
          }
          else
          {
            $this->db->group_start();
             $this->db->where('users.pass_enable',0);
             $this->db->or_where('users.pass_enable is null');
            $this->db->group_end();
          }
          
        }
        $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('users.payroll_id',$search);
        $this->db->group_end();
        $this->db->order_by('users.id');
        
        //$this->db->or_like('master_group.group_name',$search);
        //$this->db->or_like('master_designation.designation_name',$search);  
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
       
         if($status!='')
        {
              if($query->num_rows()>0)
            {
                return $query->result(); 
            }
            else
            {
                return null;
            }
        }
        else
        {
           return null;
        }

    }

    function allemployeelastlogin_count($unit,$jobrole,$status,$password_status,$user_status)
    {  
      //print_r($status);exit();
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        $data=array(
          'users.id',
          'users.payroll_id',
          'users.lastlogin_date',
          'users.status',
          'personal_details.fname',
          'personal_details.lname',
          'unit.unit_name',
          'master_designation.designation_name',
          'user_email_send.password_change_status'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_email_send', 'user_email_send.user_id = users.id','left'); 
        //  if($user_status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($user_status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($user_status!='')
        { 
          if($user_status!=0)
          {

            $this->db->where('users.status',$user_status);
          }
        }

         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        //print_r(count($status));exit();
        if(!empty($status))
        {
          if(count($status)==1)
          {
             foreach ($status as $value) {
              //print_r($value);
                    if($value==1)
                    {
                        $this->db->where('users.lastlogin_date!='," ");
                    }
                    elseif($value==2)
                    {
                        $this->db->where('users.lastlogin_date',NULL);
                    } 
               
             }
          }
        }

         if(!empty($password_status))
        {
          if(count($password_status)==1)
          {
             foreach ($password_status as $value) {
                    if($value==1)
              {
                  $this->db->where('user_email_send.password_change_status',1);
              }
              elseif($value==2)
              {
                  $this->db->group_start();
                  $this->db->where('user_email_send.password_change_status',0);
                  $this->db->or_where('user_email_send.password_change_status',NULL);
                  $this->db->group_end();
              } 

             }

          }

        }
        
        
        // $this->db->order_by('user_email_send.id','desc');
        
        // $this->db->order_by('users.lastlogin_date','desc');
        // $this->db->group_by('user_email_send.user_id');
      
         $query = $this->db->get(); 
        //echo $this->db->last_query();exit;
         if($user_status=='')
         {
            return 0;
         }
         else
         {
           return $query->num_rows(); 
         }
    }
    
    function allemployeelastlogin($limit,$start,$col,$dir,$unit,$jobrole,$status,$password_status,$user_status)
    {    
        //print_r($limit); print_r('<br>'); print_r($start);exit();,$password_status
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        $data=array(
          'users.id',
          'users.payroll_id',
          'users.lastlogin_date',
          'users.status',
          'personal_details.fname',
          'personal_details.lname',
          'unit.unit_name',
          'master_designation.designation_name',
          'user_email_send.password_change_status'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left'); 
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_email_send', 'user_email_send.user_id = users.id','left');
        //  if($user_status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($user_status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
         if($user_status!='')
        { 
          if($user_status!=0)
          {

            $this->db->where('users.status',$user_status);
          }
        }
  
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
          if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if(!empty($status))
        {
          if(count($status)==1)
          {
             foreach ($status as $value) {
              //print_r($value);
                    if($value==1)
                    {
                        $this->db->where('users.lastlogin_date!='," ");
                    }
                    elseif($value==2)
                    {
                        $this->db->where('users.lastlogin_date',NULL);
                    } 
               
             }
          }
        }
        if(!empty($password_status))
        {
          if(count($password_status)==1)
          {
             foreach ($password_status as $value) {
                    if($value==1)
              {
                  $this->db->where('user_email_send.password_change_status',1);
              }
              elseif($value==2)
              {
                  $this->db->group_start();
                  $this->db->where('user_email_send.password_change_status',0);
                  $this->db->or_where('user_email_send.password_change_status',NULL);
                  $this->db->group_end();
              } 

             }

          }

        }
        //$this->db->order_by($col,$dir); 
       
        // $this->db->order_by('user_email_send.id','desc');
        
        // $this->db->order_by('users.lastlogin_date','desc');
        // $this->db->group_by('user_email_send.user_id');
        $query = $this->db->get(); 
        //echo $this->db->last_query();exit();
        if($user_status=='')
       {
          return null;
       }
       else
       {

          if($query->num_rows()>0)
          {
              return $query->result();  
          }
          else
          {
              return null;
          }
       }
        
    }


     function allemployeelastloginsearch_count($search,$unit,$jobrole,$status,$password_status,$user_status)
    {  
     if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
         $data=array(
          'users.id',
          'users.payroll_id',
          'users.lastlogin_date',
          'users.status',
          'personal_details.fname',
          'personal_details.lname',
          'unit.unit_name',
          'master_designation.designation_name',
          'user_email_send.password_change_status'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_email_send', 'user_email_send.user_id = users.id','left'); 
        //  if($user_status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($user_status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
         if($user_status!='')
        { 
          if($user_status!=0)
          {

            $this->db->where('users.status',$user_status);
          }
        }
  
         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if(!empty($status))
        {
          if(count($status)==1)
          {
             foreach ($status as $value) {
              //print_r($value);
                    if($value==1)
                    {
                        $this->db->where('users.lastlogin_date!='," ");
                    }
                    elseif($value==2)
                    {
                        $this->db->where('users.lastlogin_date',NULL);
                    } 
               
             }
          }
        }
        if(!empty($password_status))
        {
          if(count($password_status)==1)
          {
             foreach ($password_status as $value) {
                    if($value==1)
              {
                  $this->db->where('user_email_send.password_change_status',1);
              }
              elseif($value==2)
              {
                  $this->db->group_start();
                  $this->db->where('user_email_send.password_change_status',0);
                  $this->db->or_where('user_email_send.password_change_status',NULL);
                  $this->db->group_end();
              } 

             }

          }

        }
        $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('users.payroll_id',$search);
        // $this->db->or_like('master_group.group_name',$search);
        // $this->db->or_like('master_designation.designation_name',$search);  
        $this->db->group_end();
        //  $this->db->order_by('user_email_send.id','desc');
        
        // $this->db->order_by('users.lastlogin_date','desc');
        // $this->db->group_by('user_email_send.user_id');
        
         $query = $this->db->get(); 
         //echo $this->db->last_query();exit;
        if($user_status=='')
         {
            return 0;
         }
         else
         {
           return $query->num_rows(); 
         } 

    }
   
    function  allemployeelastlogin_search($limit,$start,$search,$unit,$jobrole,$status,$password_status,$user_status)
    {   
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
         $data=array(
          'users.id',
          'users.payroll_id',
          'users.status',
          'users.lastlogin_date',
          'personal_details.fname',
          'personal_details.lname',
          'unit.unit_name',
          'master_designation.designation_name',
          'user_email_send.password_change_status'
      );
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_email_send', 'user_email_send.user_id = users.id','left');  
        //  if($user_status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($user_status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }

        if($user_status!='')
        { 
          if($user_status!=0)
          {

            $this->db->where('users.status',$user_status);
          }
        }
  
         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if(!empty($status))
        {
          if(count($status)==1)
          {
             foreach ($status as $value) {
              //print_r($value);
                    if($value==1)
                    {
                        $this->db->where('users.lastlogin_date!='," ");
                    }
                    elseif($value==2)
                    {
                        $this->db->where('users.lastlogin_date',NULL);
                    } 
               
             }
          }
        }
        if(!empty($password_status))
        {
          if(count($password_status)==1)
          {
             foreach ($password_status as $value) {
                    if($value==1)
              {
                  $this->db->where('user_email_send.password_change_status',1);
              }
              elseif($value==2)
              {
                  $this->db->group_start();
                  $this->db->where('user_email_send.password_change_status',0);
                  $this->db->or_where('user_email_send.password_change_status',NULL);
                  $this->db->group_end();
              } 

             }

          }

        }
        $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('users.payroll_id',$search);
        // $this->db->or_like('master_group.group_name',$search);
        // $this->db->or_like('master_designation.designation_name',$search);  
        $this->db->group_end();
        // $this->db->order_by('user_email_send.id','desc');
        
        // $this->db->order_by('users.lastlogin_date','desc');
        // $this->db->group_by('user_email_send.user_id');
        //$this->db->or_like('master_group.group_name',$search);
        //$this->db->or_like('master_designation.designation_name',$search);  
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
       if($user_status=='')
       {
          return null;
       }
       else
       {

          if($query->num_rows()>0)
          {
              return $query->result();  
          }
          else
          {
              return null;
          }
        }
    }

    function passwordstatus($userid)
    {
         
            $this->db->select('password_change_status');
            $this->db->from('user_email_send');
            $this->db->where('user_id', $userid);
            $this->db->order_by('id','desc');
            $this->db->limit(1);
            $query = $this->db->get();
            //echo $this->db->last_query();exit();
            $result = $query->result_array();
            return $result;
         
        
    }
 
 

    function allemployeedetails_count($unit,$jobrole,$status,$enrolled_status)
    {   
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        
        $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.actual_holiday_allowance',
            'users.actual_holiday_allowance_type',
            'users.payroll_id',
            'users.thumbnail',
            'users.payroll_id',
            'users.start_date',
            'users.final_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'personal_details.address1',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address',
            'personal_details.gender',
            'personal_details.Ethnicity',
            'personal_details.visa_status',
            'master_shift.shift_name',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_group.group_name',
            'unit.unit_name',
            'user_rates.day_rate'
             );
     
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_rates', 'user_rates.user_id = users.id','left'); 
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!='' && $status!=0) 
             $this->db->where('users.status',$status);

        if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
        $this->db->order_by('users.id');
        $query = $this->db->get(); 
       //echo $this->db->last_query();exit;
        return $query->num_rows();    

    }
    
    function allemployeedetails($limit,$start,$col,$dir,$unit,$jobrole,$status,$enrolled_status)
    {    
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.actual_holiday_allowance',
            'users.actual_holiday_allowance_type',
            'users.payroll_id',
            'users.thumbnail',
            'users.payroll_id',
            'users.start_date',
            'users.final_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'personal_details.address1',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address',
            'personal_details.gender',
            'personal_details.Ethnicity',
            'personal_details.visa_status',
            'master_shift.shift_name',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_group.group_name',
            'unit.unit_name',
            'user_rates.day_rate'
             );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
      $this->db->join('master_shift','master_shift.id=users.default_shift','left');
      $this->db->join('master_group', 'master_group.id = users.group_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      $this->db->join('user_rates', 'user_rates.user_id = users.id','left');
       // if($status==2)
       //  {
       //   $this->db->where('users.status', 2);
       //  }
       //  else if($status==3)
       //  {
       //   $this->db->where('users.status', 3);
       //  }
       //  else 
       //  {
       //   $this->db->where('users.status', 1);
       //  }
       if($status!='' && $status!=0) 
             $this->db->where('users.status',$status);

      if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
         if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
         $this->db->order_by('users.id');
        $query = $this->db->get(); 
         //echo $this->db->last_query();exit();
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function allemployeedetailssearch_count($search,$unit,$jobrole,$status,$enrolled_status)
    {   
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.actual_holiday_allowance',
            'users.actual_holiday_allowance_type',
            'users.payroll_id',
            'users.thumbnail',
            'users.payroll_id',
            'users.start_date',
            'users.final_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'personal_details.address1',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address',
             'personal_details.gender',
            'personal_details.Ethnicity',
            'personal_details.visa_status',
            'master_shift.shift_name',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_group.group_name',
            'unit.unit_name',
            'user_rates.day_rate'
             );
        $this->db->select($data);
        $this->db->from('users'); 
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_rates', 'user_rates.user_id = users.id','left');
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!='' && $status!=0) 
             $this->db->where('users.status',$status);
       else
        $this->db->where('users.status',$status);
         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
       if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
         $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('personal_details.mobile_number',$search);
        $this->db->or_like('personal_details.dob',$search);
        $this->db->or_like('personal_details.city',$search);
        $this->db->or_like('personal_details.country',$search);
        $this->db->or_like('master_group.group_name',$search);
        $this->db->or_like('master_shift.shift_name',$search);
        $this->db->or_like('master_payment_type.payment_type',$search);
       // $this->db->or_like('master_designation.designation_name',$search);
       // $this->db->or_like('unit.unit_name',$search); 
        $this->db->or_like('users.email',$search); 
        $this->db->or_like('users.payroll_id',$search);
        $this->db->group_end();
         $this->db->order_by('users.id');
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
        return $query->num_rows();    

    }
   
    function  allemployeedetails_search($limit,$start,$search,$unit,$jobrole,$status,$enrolled_status)
    {   
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }
        $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.actual_holiday_allowance',
            'users.actual_holiday_allowance_type',
            'users.payroll_id',
            'users.thumbnail',
            'users.payroll_id',
            'users.start_date',
            'users.final_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'personal_details.address1',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address',
            'personal_details.gender',
            'personal_details.Ethnicity',
            'personal_details.visa_status',
            'master_shift.shift_name',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_group.group_name',
            'unit.unit_name',
            'user_rates.day_rate'
             );
        $this->db->select($data);
        $this->db->from('users'); 
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
        $this->db->join('master_shift','master_shift.id=users.default_shift','left');
        $this->db->join('master_group', 'master_group.id = users.group_id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('user_rates', 'user_rates.user_id = users.id','left');
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!='' && $status!=0) 
             $this->db->where('users.status',$status);


         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
       if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('user_unit.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }
        if($jobrole>0)
        { 
              $this->db->where('users.designation_id',$jobrole);
        }
        if($enrolled_status>0)
        {
          if($enrolled_status==1)
          {
              $this->db->where('users.thumbnail !=',NULL);
              $this->db->where('users.thumbnail !=','');
          }
          else
          {
               $this->db->group_start();
               $this->db->where('users.thumbnail',NULL);
               $this->db->or_where('users.thumbnail','');
              $this->db->group_end();
          }
        }
         $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('personal_details.mobile_number',$search);
        $this->db->or_like('personal_details.dob',$search);
        $this->db->or_like('personal_details.city',$search);
        $this->db->or_like('personal_details.country',$search);
        $this->db->or_like('master_group.group_name',$search);
        $this->db->or_like('master_shift.shift_name',$search);
        $this->db->or_like('master_payment_type.payment_type',$search);
       // $this->db->or_like('master_designation.designation_name',$search);
       // $this->db->or_like('unit.unit_name',$search); 
        $this->db->or_like('users.email',$search); 
        $this->db->or_like('users.payroll_id',$search);
        $this->db->group_end();
         $this->db->order_by('users.id');
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
       //echo $this->db->last_query();exit();
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function annualleaveallstaff_count($unit,$jobrole,$status,$start_time,$end_time)
    {   
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }

        $this->db->select('*');
        $this->db->from('holliday');  
        $this->db->join('users', 'users.id = holliday.user_id','left');
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!=0)
        {
           $this->db->where('users.status',$status);
        }
       

        if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('holliday.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('holliday.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('holliday.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }

        if($jobrole>0)
        { 
              $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
              $this->db->where_in('users.designation_id',$jobrole);
        }
        $this->db->where('users.id!=',1);
        $this->db->group_start();

        $this->db->group_start();
        $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end();
        $this->db->or_group_start(); 
        $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end(); 
        $this->db->or_group_start(); 
        $this->db->or_where("'".$start_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end();  
        $this->db->or_group_start(); 
        $this->db->or_where("'".$end_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end(); 

        $this->db->group_end();  

         $query = $this->db->get(); 
        //echo $this->db->last_query();exit;
        return $query->num_rows();      

    }
    
    function annualleaveallstaff($limit,$start,$col,$dir,$unit,$jobrole,$status,$start_time,$end_time)
    {    
      if($unit>0)
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        $result = $query->result_array();  
      }

        $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,holliday.from_date,holliday.start_time,holliday.end_time,holliday.to_date,holliday.days,users.annual_holliday_allowance,users.status as user_status,holliday.status,personal_details.user_id,users.hr_ID');
        $this->db->from('holliday');  
        $this->db->join('users', 'users.id = holliday.user_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = holliday.unit_id','left');
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }

        if($status!=0)
        {
           $this->db->where('users.status',$status);
        }

        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('holliday.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('holliday.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('holliday.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }

        if($jobrole>0)
        {  
              $this->db->where_in('users.designation_id',$jobrole);
        }
        $this->db->where('users.id!=',1);
        $this->db->group_start();

        $this->db->group_start();
        $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end();
        $this->db->or_group_start(); 
        $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end(); 
        $this->db->or_group_start(); 
        $this->db->or_where("'".$start_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end();  
        $this->db->or_group_start(); 
        $this->db->or_where("'".$end_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end(); 

        $this->db->group_end();  
        //$this->db->order_by($col,$dir); 
        $query = $this->db->get(); 
       // echo $this->db->last_query();exit();
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
    function annualleaveallstaffsearch_count($search,$unit,$jobrole,$status,$start_time,$end_time)
    {
       if($unit>0)
        {
          $this->db->select('id');
          $this->db->from('unit'); 
          $this->db->where('parent_unit', $unit);
          $query = $this->db->get();
          $result = $query->result_array();  
        }

         $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,holliday.from_date,holliday.start_time,holliday.end_time,holliday.to_date,holliday.days,users.annual_holliday_allowance,users.status as user_status,holliday.status,personal_details.user_id,users.hr_ID');
        $this->db->from('holliday');  
        $this->db->join('users', 'users.id = holliday.user_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = holliday.unit_id','left');
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!=0)
        {
           $this->db->where('users.status',$status);
        }

         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('holliday.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('holliday.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('holliday.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }

        if($jobrole>0)
        { 
              $this->db->where_in('users.designation_id',$jobrole);
        }

        $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('holliday.from_date',$search); 
        $this->db->or_like('holliday.to_date',$search); 
        $this->db->or_like('holliday.days',$search);
        $this->db->or_like('unit.unit_name',$search);  
        $this->db->group_end();
         $this->db->where('users.id!=',1);
       $this->db->group_start();

        $this->db->group_start();
        $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end();
        $this->db->or_group_start(); 
        $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end(); 
        $this->db->or_group_start(); 
        $this->db->or_where("'".$start_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end();  
        $this->db->or_group_start(); 
        $this->db->or_where("'".$end_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end(); 

        $this->db->group_end();  
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
         return $query->num_rows();      

    }
   
    function  annualleaveallstaff_search($search,$limit,$start,$col,$dir,$unit,$jobrole,$status,$start_time,$end_time)
    {   
      if($unit>0)
        {
          $this->db->select('id');
          $this->db->from('unit'); 
          $this->db->where('parent_unit', $unit);
          $query = $this->db->get();
          $result = $query->result_array();  
        }

         $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,holliday.from_date,holliday.start_time,holliday.end_time,holliday.to_date,holliday.days,users.annual_holliday_allowance,users.status as user_status,holliday.status,personal_details.user_id,users.hr_ID');
        $this->db->from('holliday');  
        $this->db->join('users', 'users.id = holliday.user_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = holliday.unit_id','left');
        //  if($status==2)
        // {
        //  $this->db->where('users.status', 2);
        // }
        // else if($status==3)
        // {
        //  $this->db->where('users.status', 3);
        // }
        // else 
        // {
        //  $this->db->where('users.status', 1);
        // }
        if($status!=0)
        {
           $this->db->where('users.status',$status);
        }
         if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
         if($unit>0)
        {
          if(empty($result))
          {
             $this->db->where('holliday.unit_id',$unit);
          }
          else
          {
            $this->db->group_start();
            $this->db->where('holliday.unit_id',$unit);
             foreach ($result as $value) { 
              $this->db->or_where('holliday.unit_id', $value['id']);
            } 
            $this->db->group_end();
          }
        }

        if($jobrole>0)
        { 
              $this->db->where_in('users.designation_id',$jobrole);
        }
         $serach_term = explode(" ", $search);
        $this->db->group_start();
        if(count($serach_term) > 1){
        $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        }else{
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        }
        $this->db->or_like('holliday.from_date',$search); 
        $this->db->or_like('holliday.to_date',$search); 
        $this->db->or_like('holliday.days',$search);
        $this->db->or_like('unit.unit_name',$search);  
        $this->db->group_end();

         $this->db->where('users.id!=',1);
        $this->db->group_start();

        $this->db->group_start();
        $this->db->where('from_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end();
        $this->db->or_group_start(); 
        $this->db->or_where('to_date BETWEEN "'. date('Y-m-d', strtotime($start_time)). '" and "'. date('Y-m-d', strtotime($end_time)).'"');
        $this->db->group_end(); 
        $this->db->or_group_start(); 
        $this->db->or_where("'".$start_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end();  
        $this->db->or_group_start(); 
        $this->db->or_where("'".$end_time."'".'BETWEEN from_date and to_date');
        $this->db->group_end(); 

        $this->db->group_end();  
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
        // echo $this->db->last_query();exit();
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }


    function trainingreport_count()
    {   
        $query = $this->db->get('master_training');
        //echo $this->db->last_query();exit;
        return $query->num_rows();  

    }
    
    function trainingreport($limit,$start,$col,$dir)
    {    
        $this->db->select('*');
        $this->db->from('master_training');  
        //$this->db->order_by($col,$dir); 
        $query = $this->db->get(); 
        //echo $this->db->last_query();exit();
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function  trainingreport_search($search)
    {    
        $this->db->select('*');
        $this->db->from('master_training');   
        // $this->db->like('master_training.title',$search);
        // $this->db->or_like('personal_details.lname',$search);
        // $this->db->or_like('holliday.from_date',$search); 
        // $this->db->or_like('holliday.to_date',$search); 
        // $this->db->or_like('holliday.days',$search); 
        //$this->db->->limit($limit,$start);
        $query = $this->db->get();
        // echo $this->db->last_query();exit();
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    public function findtrainingreport($unit,$user,$status,$params,$search = null,$limit = null,$start = null,$order = null,$dir = null,$count = false)
    {   //print_r($status);exit();
      if(($unit) > 0)
      {  
                  $this->db->select('unit.id');
                  $this->db->from('unit');
                  $this->db->where('parent_unit',  $unit);
                  $query = $this->db->get();
                  //echo $this->db->last_query();exit();
                  $result = $query->result_array();  
      }

        $data=array(
          'master_training.title',
          'master_training.description',
          'master_training.time_from',
          'master_training.time_to',
          'master_training.date_from',
          'master_training.date_to',
          'master_training.address',
          'master_training.point_of_person',
          'master_training.contact_num',
          'master_training.contact_email',
          'master_training.id',
          'master_training.unit'
        );
        $this->db->select($data);
        $this->db->from('master_training'); 

        if($user!=0)
        {
            $this->db->join('training_staff', 'training_staff.training_id = master_training.id','left');
            $this->db->where("training_staff.user_id",$user);
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('date_from BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
            $this->db->group_end();
            $this->db->or_group_start(); 
            $this->db->or_where('date_to BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
            $this->db->group_end(); 
            $this->db->or_group_start(); 
            $this->db->or_where("'".$params['start_date']."'".'BETWEEN date_from and date_to');
            $this->db->group_end();  
            $this->db->or_group_start(); 
            $this->db->or_where("'".$params['end_date']."'".'BETWEEN date_from and date_to');
            $this->db->group_end();
            $this->db->group_end();

        }
        else
        {
          $this->db->join('training_staff', 'training_staff.training_id = master_training.id','left');
          $this->db->join('user_unit', 'user_unit.user_id = training_staff.user_id');

            if(count($result)>0)
            {
                $this->db->group_start();
                  $this->db->where('user_unit.unit_id', $unit);
                  foreach ($result as $key => $unitid) { //print_r($unitid);exit();
                    if($unitid['id']>0){
                      $this->db->or_where('user_unit.unit_id', $unitid['id']);
                    }
                  }
                $this->db->group_end();

            }
            else
            {
                $this->db->where('user_unit.unit_id', $unit);
            }

            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('date_from BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
            $this->db->group_end();
            $this->db->or_group_start(); 
            $this->db->or_where('date_to BETWEEN "'. date('Y-m-d', strtotime($params['start_date'])). '" and "'. date('Y-m-d', strtotime($params['end_date'])).'"');
            $this->db->group_end(); 
            $this->db->or_group_start(); 
            $this->db->or_where("'".$params['start_date']."'".'BETWEEN date_from and date_to');
            $this->db->group_end();  
            $this->db->or_group_start(); 
            $this->db->or_where("'".$params['end_date']."'".'BETWEEN date_from and date_to');
            $this->db->group_end();
            $this->db->group_end();

            $this->db->group_by('master_training.id');
        }
        

        if($search){

          $this->db->group_start();
          $this->db->like('master_training.title',$search);
          $this->db->or_like('master_training.description',$search);
          $this->db->or_like('master_training.date_from',$search);
          $this->db->or_like('master_training.time_from',$search);
          $this->db->or_like('master_training.date_to',$search);
          $this->db->or_like('master_training.time_to',$search);
          $this->db->group_end();
        }
        $this->db->order_by($order,$dir);
        if($count  == true){
          $query = $this->db->get();
          return $query->num_rows();
      
        }
        else{
          if($limit>0)
          {
          $this->db->limit($limit,$start);
          }
          $query = $this->db->get();
        }

        // if($status!=0) 
        // {
        //   $this->db->where('master_training.status', $status);
        // }
        //$this->db->order_by($col,$dir); 
        // $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array();
        return $result;


    }



    public function finduserdetails($user_id)
    {
     $this->db->select('personal_details.user_id,personal_details.fname,personal_details.lname,master_designation.designation_name,holliday.from_date,holliday.start_time,holliday.end_time,holliday.to_date,holliday.days,users.annual_holliday_allowance,holliday.status,personal_details.user_id');
      $this->db->from('users'); 
      $this->db->where('users.id', $user_id);
      $this->db->join('holliday', 'holliday.user_id = users.id','left');
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      $this->db->order_by( 'holliday.from_date','desc');
      
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }

 

    public function findholidaydetailsByuser($unit_id)
    {
         $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,holliday.from_date,holliday.start_time,holliday.end_time,holliday.to_date,holliday.days,users.annual_holliday_allowance,holliday.status,personal_details.user_id');
      $this->db->from('users'); 
      $this->db->join('holliday', 'holliday.user_id = users.id','left');
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      $this->db->where('user_unit.unit_id', $unit_id);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
    }
    public function countShiftStaffsForParentUnit($date,$unit_ids,$category){
      if($category == 1){
        $cat_array = ['1','3','4'];
      }else{
        $cat_array = ['2'];
      }
      $count = 0;
      $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,master_shift.shift_type,master_shift.shift_category');
      $this->db->from('rota_schedule');
      $this->db->join('users', 'users.id = rota_schedule.user_id','left');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
      $this->db->where_in('rota_schedule.unit_id', $unit_ids); 
      $this->db->where('rota_schedule.date', $date); 
      $this->db->where_in('master_shift.shift_category', $cat_array);
      $this->db->where('master_shift.part_number', 1);
      $this->db->group_by('user_id');
      $this->db->order_by('rota_schedule.date','ASC');
      $query = $this->db->get();
      // echo $this->db->last_query();exit;
      $result = $query->result_array();
      foreach ($result as $value) {
        $count = $count + $value['shift_type'];
      }
      return $count; 
    }
    //added new function for getting day and night shifts staff count
    public function countShiftStaffs($date,$unit_ids,$category){
      if($category == 1){
        $cat_array = ['1','3','4'];
      }else{
        $cat_array = ['2'];
      }
      $count = 0;
      $this->db->select('rota_schedule.user_id,rota_schedule.shift_id,master_shift.shift_type,master_shift.shift_category');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
      $this->db->where_in('rota_schedule.unit_id', $unit_ids); 
      $this->db->where('rota_schedule.date', $date); 
      $this->db->where_in('master_shift.shift_category', $cat_array);
      $this->db->where('master_shift.part_number', 1);
      $this->db->group_by('user_id'); 
      $query = $this->db->get();
      // echo $this->db->last_query();exit;
      $result = $query->result_array();
      foreach ($result as $value) {
        $count = $count + $value['shift_type'];
      }
      return $count; 
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
    function findDatesBetnDates($start, $end, $format = 'Y-m-d'){
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
    //edited by chinchu..Adding subunits ids also if preset
    public function OverStaffReport($start_year,$start_month,$unit_ids)
    {
      if(count($unit_ids) > 1) {
        if($start_year && $start_month){
          $final_data = array();
          $date_string = $start_year.'-'.$start_month;
          $week_array = $this->weekOfMonthFromDate($date_string);
          foreach ($week_array as $value) {
            $rt_ids = array();
            $rt_settings_ids = array();
            $start_date = explode("_",$value)[0];
            $end_date = explode("_",$value)[1];
            $this->db->select('
              rota.id,
              rota.rota_settings,
              rota.start_date,
              rota.end_date,
              rota.unit_id'
            );
            $this->db->from('rota');
            $this->db->where_in('rota.unit_id', $unit_ids);
            $this->db->where('rota.start_date',$start_date);
            $this->db->where('rota.end_date',$end_date);
            $query = $this->db->get();
            $result = $query->result_array();
            foreach ($result as $value) {
              array_push($rt_ids, $value['id']);
              array_push($rt_settings_ids, $value['rota_settings']);
            }
            $this->db->select("SUM(day_shift_max) AS day_shift_max_value,SUM(night_shift_max) AS night_shift_max_value");
            $this->db->from('rota_settings');
            $this->db->where_in('rota_settings.id', $rt_settings_ids);
            $settings_query = $this->db->get();
            $settings_result = $settings_query->result_array();
            $date_array = $this->findDatesBetnDates($start_date,$end_date);
            for($i=0;$i<count($date_array);$i++){
              $day_count = $this->countShiftStaffsForParentUnit($date_array[$i],$unit_ids,1);
              $night_count = $this->countShiftStaffsForParentUnit($date_array[$i],$unit_ids,2);
              $day_overstaffed = $day_count-$settings_result[0]['day_shift_max_value'];
              $night_overstaffed = $night_count-$settings_result[0]['night_shift_max_value'];
              /*print 'day_shift_max_value = '.$settings_result[0]['day_shift_max_value'];
              echo "<br>";
              print 'night_shift_max_value = '.$settings_result[0]['night_shift_max_value'];
              print 'day_count = '.$day_count;
              echo "<br>";
              print 'night_count = '.$night_count;
              echo "<br>";
              print 'day_overstaffed = '.$day_overstaffed;
              echo "<br>";
              print 'night_overstaffed = '.$night_overstaffed;
              echo "<br>";exit();*/

              if($day_overstaffed > 0 || $night_overstaffed >0){
                $data = array(
                  'date' => $date_array[$i],
                  'day_shift_max' => $settings_result[0]['day_shift_max_value'],
                  'night_shift_max' => $settings_result[0]['night_shift_max_value'],
                  'unit_ids' => $unit_ids,
                  'day_count' => $day_count,
                  'night_count' => $night_count,
                  'day_overstaffed'=>$day_overstaffed,
                  'night_overstaffed'=>$night_overstaffed
                );
                array_push($final_data, $data);
              }
            }
          }
          return $final_data;
        }
      }else{
        $date_array = array();
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->join('rota_schedule', 'rota.id = rota_schedule.rota_id','left');
        $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings','left');
        $this->db->join('users', 'users.id = rota_schedule.user_id','left');  
        $this->db->where_in('rota.unit_id', $unit_ids);
        $this->db->where('YEAR(rota.start_date)',$start_year);
        $this->db->where('MONTH(rota.start_date)',$start_month);
        if($jobrole!='')
        {
          $this->db->where('users.designation_id',$jobrole);
        }
        $this->db->group_by('rota_schedule.date');
        $this->db->order_by('rota_schedule.date','ASC');
        $query = $this->db->get();
         //echo $this->db->last_query();exit;

        $result = $query->result_array();
        if(count($result) > 0){
          for($i=0;$i<count($result);$i++){
            $day_count = $this->countShiftStaffs($result[$i]['date'],$unit_ids,1);
            $night_count = $this->countShiftStaffs($result[$i]['date'],$unit_ids,2);
            $day_overstaffed = $day_count-$result[$i]['day_shift_max'];
            $night_overstaffed = $night_count-$result[$i]['night_shift_max'];
            if($day_overstaffed > 0 || $night_overstaffed >0){
              array_push($date_array, $result[$i]['date']);
            }
          }
          return array(
            'date_array' => $date_array,
            'day_shift_max' => $result[0]['day_shift_max'],
            'night_shift_max' => $result[0]['night_shift_max']
          );
        }else{
          return array();
        }
      }
    }

    public function findstaffsDayshift($unit)
    {
      $this->db->select('rota_schedule.user_id');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
      $this->db->where_in('rota_schedule.unit_id', $unit); 
      $this->db->where('master_shift.shift_category', 1); 
      $this->db->group_by('user_id'); 
      $query = $this->db->get();
       // echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result; 

    }

     public function findstaffsNightshift($unit)
    {
      $this->db->select('rota_schedule.user_id');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
      $this->db->where('rota_schedule.unit_id', $unit); 
      $this->db->where('master_shift.shift_category', 2); 
      $this->db->group_by('user_id'); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result; 


    }

    

    public function findAddressHistory()
    {
      $this->db->select('*');
      $this->db->from('history_staff_address'); 
      $this->db->join('personal_details','personal_details.user_id=history_staff_address.user_id'); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result; 
    }

    public function findAddressHistoryByuser($unit_id)
    {
         $this->db->select('*');
      $this->db->from('history_staff_address'); 
      $this->db->join('personal_details','personal_details.user_id=history_staff_address.user_id'); 
      $this->db->join('user_unit', 'user_unit.user_id = history_staff_address.user_id','left'); 
      $this->db->where('user_unit.unit_id', $unit_id);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
    }

    public function getUnitIdOfUser($id)
    {
      $this->db->select('unit_id');
      $this->db->from('user_unit'); 
      $this->db->join('users', 'users.id = user_unit.user_id');
      $this->db->where('user_id', $id);
     
      $query = $this->db->get();
       //echo $this->db->last_query(); exit;
      $result = $query->result_array(); 
      return $result;
    }
    public function getLeave($from_date,$to_date,$user_id){
      $this->db->select('leave_log.date');
      $this->db->from('leave_log'); 
      $this->db->where('date >=', $from_date);
      $this->db->where('date <=', $to_date);
      $this->db->where('user_id=',$user_id);
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      if($query->num_rows() > 0){
        $result = $query->result_array();
        return $result;
      }else{
        return array();
      }
    }
    public function allUsers($unit_id)
    { 
       $result = array();
          $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.actual_holiday_allowance',
            'users.actual_holiday_allowance_type',
            'users.payroll_id',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'master_shift.shift_name',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'unit.unit_name',
             );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
      $this->db->join('master_shift','master_shift.id=users.default_shift','left');
      $this->db->join('master_group', 'master_group.id = users.group_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      $this->db->where('user_unit.unit_id', $unit_id); 
      $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;



    }
    public function getLiveStatus($date){
      $current_date = date("Y-m-d");
      $this->db->select('
        available_requests.id as available_requestid,
        available_requests.date,
        unit.unit_name,
        master_shift.shift_name,
        available_requests.shift_id,
        available_requests.from_unitid,
        available_requests.to_unitid,
        available_requested_users.unit_id'
      );
      $this->db->from('available_requests');
      $this->db->join('unit','available_requests.to_unitid=unit.id');
      $this->db->join('master_shift','available_requests.shift_id=master_shift.id');
      $this->db->join('available_requested_users','available_requests.id=available_requested_users.avialable_request_id');
      if($date){
        $this->db->where('date =',$date);
      }else{
        $this->db->where('date >=',$current_date);
      }
      $this->db->order_by('available_requests.date','ASC');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function showUserStatus($id){
      $this->db->select('available_requests.id,personal_details.user_id,personal_details.fname,personal_details.lname,available_requested_users.status');
      $this->db->from('available_requests');
      $this->db->join('available_requested_users', 'available_requested_users.avialable_request_id = available_requests.id');
      $this->db->join('personal_details', 'personal_details.user_id = available_requested_users.user_id');
      $this->db->where('available_requests.id',$id);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }

    public function FindSicknessData($params)
    {
      //print_r($params);//exit();
      if($params['unit_id']!='none')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
     $shifts=array('93','3','4');
      $data=array( 
        'personal_details.user_id',
        'personal_details.fname',
        'personal_details.lname',
        'unit.unit_name',
        'master_designation.designation_name',
        'rota_schedule.date',
        'master_shift.shift_name',
        'rota_schedule.shift_id',
        'users.hr_ID'
      );
      
        $this->db->select($data);
        $this->db->from('users'); 
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->join('rota_schedule', 'rota_schedule.user_id = users.id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');

         if($params['user_id']!='none')
         {  
         $this->db->where('users.id', $params['user_id']);
         }
         else
         {  
          
          if($params['status']!=0)
          {
            $this->db->where('users.status',$params['status']);
          }

          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }

            if($params['unit_id']!='none')
            {
                  if(!empty($result))
                      {
                        $this->db->group_start();
                        $this->db->where('user_unit.unit_id', $params['unit_id']);
                        foreach ($result as $value) 
                        {
                           $this->db->or_where('user_unit.unit_id',$value['id']);
                        }
                        $this->db->group_end();
                      }
                      else
                      {
                         $this->db->where('user_unit.unit_id', $params['unit_id']);
                      }
            }
             $this->db->where('users.payment_type !=',1);

         }
           
        
       
        $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['from_date']);
        $this->db->where('rota_schedule.date <=',  $params['to_date']);
        $this->db->group_end();
        $this->db->where_in('rota_schedule.shift_id',  $shifts);
      $query = $this->db->get();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

    }

    
    public function FindWorkingData($params,$search = null,$limit = null,$start = null,$order = null,$dir = null,$count = false)
    {
      //print_r($params);//exit();
      if($params['unit_id']!='none')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      $data=array( 
        'personal_details.fname',
        'personal_details.lname',
        'unit.unit_name',
        'master_designation.designation_name',
        'master_shift.shift_name',
        'master_shift.part_number',
        'master_shift.shift_category',
        'rota_schedule.shift_id',
        'master_shift.shift_type',
        'rota_schedule.shift_id',
        'users.status'
      );
      
        $this->db->select($data);
        $this->db->from('rota_schedule'); 
        $this->db->join('users', 'users.id = rota_schedule.user_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = rota_schedule.unit_id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
        if($params['jobrole']!=0)
        {
          $this->db->where_in('users.designation_id',$params['jobrole']);
        }

        if($params['unit_id']!='')
        {
              if(!empty($result))
              {
                        $this->db->group_start();
                        $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                        foreach ($result as $value) 
                        {
                           $this->db->or_where('rota_schedule.unit_id',$value['id']);
                        }
                        $this->db->group_end();
              }
              else
              {
                         $this->db->where('rota_schedule.unit_id', $params['unit_id']);
              }
        }

      $this->db->where('rota_schedule.date ', $params['from_date']);
      if($params['shift_category']!='0')
      {
        $this->db->where('master_shift.shift_category', $params['shift_category']);
      }
      if($params['part_of_number']!='none')
      {
        $this->db->where('master_shift.part_number', $params['part_of_number']);
      }
      if($search){
        $this->db->group_start();
        $this->db->like('unit.unit_name',$search);
        $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search);
        $this->db->or_like('master_designation.designation_name',$search);
        $this->db->or_like('master_shift.shift_category',$search);
        $this->db->or_like('master_shift.part_number',$search);
        $this->db->group_end();

      }
     
      $this->db->order_by($order,$dir);

      if($count  == true){
        $query = $this->db->get();
        return $query->num_rows();
    
      }
      else{
        if($limit>0)
        {
        $this->db->limit($limit,$start);
        }
        $query = $this->db->get();
      }
    //  echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;

    }

    public function FindActualvRequestedData($params,$search = null,$limit = null,$start = null,$order = null,$dir = null,$count = false)
    {
      if($params['unit_id']!='none')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      $data=array( 
        'personal_details.fname',
        'personal_details.lname',
        'unit.unit_name',
        'master_designation.designation_name',
        'master_shift.shift_name',
        'staffrota_schedule.shift_id',
        'master_shift.shift_type',
        'staffrota_schedule.user_id',
        'staffrota_schedule.date'
      );
      
        $this->db->select($data);
        $this->db->from('staffrota_schedule'); 
        $this->db->join('users', 'users.id = staffrota_schedule.user_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = staffrota_schedule.unit_id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id','left');

        if($params['user_id']!='none')
        {
         $this->db->where('users.id', $params['user_id']);
        }
        else
        {
            if($params['status']!=0)
            {
              $this->db->where('users.status',$params['status']);
            }
            $this->db->where('users.payment_type !=',1);

            if($params['unit_id']!='')
            {
                if(!empty($result))
                    { 
                      $this->db->group_start(); 
                        $this->db->where('staffrota_schedule.unit_id', $params['unit_id']);
                        foreach ($result as $value) 
                        {
                           $this->db->or_where('staffrota_schedule.unit_id',$value['id']);
                        }
                      $this->db->group_end();
                    }
                    else
                    {  
                       $this->db->where('staffrota_schedule.unit_id', $params['unit_id']);

                    }
            }  
        }
        

        $this->db->group_start();
        $this->db->where('staffrota_schedule.date >=', $params['from_date']);
        $this->db->where('staffrota_schedule.date <=',  $params['to_date']);
        $this->db->group_end();
        $this->db->where('master_shift.shift_type !=', 0);
        
        if($search){
          $this->db->group_start();
          $this->db->like('unit.unit_name',$search);
          $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search);
          $this->db->or_like('master_designation.designation_name',$search);
          $this->db->or_like('master_shift.shift_name',$search);  
          $this->db->or_like('staffrota_schedule.date',$search);  
          $this->db->group_end();
        }
        $this->db->order_by($order,$dir);
  
        if($count  == true){
          $query = $this->db->get();
          return $query->num_rows();
      
        }
        else{
          if($limit>0)
          {
          $this->db->limit($limit,$start);
          }
          $query = $this->db->get();
        }
      //  echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
    }
    
    public function FindWeekendData($params,$search= null,$limit= null,$start= null,$order= null,$dir= null,$count = false)
    {  

      if($params['unit_id']!='none')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      } 
      $data=array( 
        'personal_details.fname',
        'personal_details.lname',
        'unit.unit_name',
        'master_designation.designation_name',
        'master_shift.shift_name',
        'rota_schedule.shift_id',
        'master_shift.shift_type',
        'rota_schedule.user_id',
        'rota_schedule.date',
        'rota_schedule.day'
      );
      
        $this->db->select($data);
        $this->db->from('rota_schedule'); 
        $this->db->join('users', 'users.id = rota_schedule.user_id','left');
        $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
        $this->db->join('unit', 'unit.id = rota_schedule.unit_id','left');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
        if($params['user_id']!='none')
        {
         $this->db->where('users.id', $params['user_id']);
        }
        else
        {
            if($params['status']!=0)
            {
              $this->db->where('users.status',$params['status']);
            }

            if($params['unit_id']!='')
            {
                if(!empty($result))
                    { 
                      $this->db->group_start(); 
                        $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                        $this->db->or_where('rota_schedule.from_unit', $params['unit_id']);
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
                       $this->db->where('rota_schedule.unit_id', $params['unit_id']);
                       $this->db->or_where('rota_schedule.from_unit', $params['unit_id']);
                       $this->db->group_end();

                    }
            }  
          $this->db->where('users.payment_type !=',1);
        }
        $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['from_date']);
        $this->db->where('rota_schedule.date <=',  $params['to_date']);
        $this->db->group_end();
        $this->db->where('master_shift.shift_type !=', 0);
        $this->db->group_start();
        $this->db->where('rota_schedule.day', 'Su');
        $this->db->or_where('rota_schedule.day', 'Sa');
        $this->db->group_end();

        if($search){
          $this->db->group_start();
          $this->db->like('unit.unit_name',$search);  
          $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search);
          $this->db->or_like('master_designation.designation_name',$search);  
          $this->db->or_like('master_shift.shift_name',$search);  
          $this->db->group_end();
  
        }
       
        $this->db->order_by($order,$dir);
  
        if($count  == true){
          $query = $this->db->get();
          return $query->num_rows();
      
        }
        else{
          if($limit>0)
          {
          $this->db->limit($limit,$start);
          }
          $query = $this->db->get();
        }
      //  echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;


    }


    //<-------------------------------- sep28 new reports ------------------------------->//


     public function GetTimelogDatas($params,$stat)
     {

      //print_r($params);exit();

      if($params['unit_id']!='none')
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      } 

        $this->db->select($data);
        $this->db->from('time_log'); 
        $this->db->join('users', 'users.id = time_log.user_id','left');

        if($params['user_id']!=0)
        {
         $this->db->where('time_log.user_id', $params['user_id']);
        }
        else
        {
            if($params['status']!=0)
            {
              $this->db->where('users.status',$params['status']);
            }

            if($params['unit_id']!='')
            {
                if(!empty($result))
                    { 
                      $this->db->group_start(); 
                        $this->db->where('time_log.unit_id', $params['unit_id']);
                        foreach ($result as $value) 
                        {
                           $this->db->or_where('time_log.unit_id',$value['id']);
                        }
                      $this->db->group_end();
                    }
                    else
                    {  
                       $this->db->group_start();
                       $this->db->where('time_log.unit_id', $params['unit_id']); 
                       $this->db->group_end();

                    }
            }  
        }
        $this->db->group_start();
        $this->db->where('time_log.date >=', $params['start_date']);
        $this->db->where('time_log.date <=',  $params['end_date']);
        $this->db->group_end();

        if($stat=='early')
        {
            $this->db->where('time_log.time_to !=', '00:00:00');
        }
        else
        {
            $this->db->where('time_log.time_from !=', '00:00:00');
        }
        

        $this->db->group_start();
        $this->db->where('time_log.status', 1);
        $this->db->or_where('time_log.status', 0);
        $this->db->group_end();

        $this->db->where('time_log.accuracy >', 0);
        $this->db->group_by('time_log.user_id');

      $query = $this->db->get();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

     }

     public function GetRotaDetailsByUserForTimelog($user_id,$params)
    { 

        $data=array(
          'personal_details.fname',
          'personal_details.lname',
          'unit.unit_name',
          'master_shift.shift_name',
          'master_shift.start_time',
          'master_shift.end_time',
          'rota_schedule.date',
          'personal_details.user_id'
        );
        $this->db->select($data);
          $this->db->from('rota_schedule'); 
          $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id'); 
          $this->db->join('unit', 'unit.id = rota_schedule.unit_id'); 
          $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
          $this->db->where('rota_schedule.user_id',$user_id);
          //$this->db->where('rota_schedule.unit_id',$unit_id);

          $this->db->group_start();
          $this->db->where('rota_schedule.date >=', $params['start_date']);
          $this->db->where('rota_schedule.date <=',  $params['end_date']);
          $this->db->group_end();

          $this->db->where('master_shift.shift_category !=', 0);
          $this->db->where('master_shift.shift_category !=',  NULL);

          $this->db->group_by('rota_schedule.date');
          
          $query = $this->db->get();
           //echo $this->db->last_query();exit;
          $result = $query->result_array(); 
         //print_r($result);exit();
          if(empty($result))
          {
            $results=NULL;
          }
          else
          {
            $results=$result;
          }

            return $results;
    }

    public function getLatestCheckoutDataByuser($user_id,$unit_id,$date)
    {
        $data=array(
          'time_log.time_to'
        );
          $this->db->select($data);
          $this->db->from('time_log'); 

          $this->db->where('time_log.date',$date); 
          $this->db->where('time_log.user_id',$user_id);
          //$this->db->where('time_log.unit_id',$unit_id);

          $this->db->where('time_log.time_to !=', '00:00:00');
          $this->db->group_start();
          $this->db->where('time_log.status', 1);
          $this->db->or_where('time_log.status', 0);
          $this->db->group_end();

          $this->db->where('time_log.accuracy >', 0);
          $this->db->order_by('time_log.time_to', 'asc');
          
          $query = $this->db->get();
          //echo $this->db->last_query();exit;
          $result = $query->result_array(); 
         //print_r($result);exit();
          if(empty($result))
          {
            $results=NULL;
          }
          else
          {
            $results=$result[0];
          }

          //print_r($results);exit();

            return $results;
    }

    public function getLatestCheckInDataByuser($user_id,$unit_id,$date)
    {
        $data=array(
          'time_log.time_from'
        );
          $this->db->select($data);
          $this->db->from('time_log'); 

          $this->db->where('time_log.date',$date); 
          $this->db->where('time_log.user_id',$user_id);
          //$this->db->where('time_log.unit_id',$unit_id);

          $this->db->where('time_log.time_from !=', '00:00:00');
          $this->db->group_start();
          $this->db->where('time_log.status', 1);
          $this->db->or_where('time_log.status', 0);
          $this->db->group_end();

          $this->db->where('time_log.accuracy >', 0);
          $this->db->order_by('time_log.time_from', 'asc');
          
          $query = $this->db->get();
          //echo $this->db->last_query();exit;
          $result = $query->result_array(); 
         //print_r($result);exit();
          if(empty($result))
          {
            $results=NULL;
          }
          else
          {
            $results=$result[0];
          }

          //print_r($results);exit();

            return $results;
    }


    function agencytimelogreport($params)
    {    
        //print_r($params);exit();
      if($params['unit']!='none')
      {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();     
      }
        $data=array(
            'rota_schedule.user_id',
            'personal_details.fname',
            'personal_details.lname',
            'master_shift.shift_name','master_shift.id as shiftid',
            'unit.unit_name',
            'time_log.device_id',
            'time_log.date as timelogdate',
            'rota_schedule.date as rotadate',
            'time_log.time_to',
            'time_log.time_from',
            'time_log.unit_id',
            'rota_schedule.unit_id as rota_unit'
        );
        $this->db->select($data);
        $this->db->from('rota_schedule'); 
        $this->db->join('time_log', 'time_log.user_id = rota_schedule.user_id AND `time_log`.`date` = `rota_schedule`.`date`','left');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id');  
        $this->db->join('unit', 'unit.id = time_log.user_unit','left'); 
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->where('users.status',1); 
        $this->db->where('users.payment_type',1);
        
        if($params['unit']!='none')
        {

          if(empty($result))
          {
            $this->db->group_start();
            $this->db->where('rota_schedule.unit_id',$params['unit']);
            $this->db->or_where('rota_schedule.from_unit',$params['unit']);
            $this->db->group_end();
          }
          else
          {
            $this->db->group_start();
                $this->db->group_start();
                $this->db->where('rota_schedule.unit_id',$params['unit']);
                $this->db->or_where('rota_schedule.from_unit',$params['unit']);
                $this->db->group_end();
             foreach ($result as $value) { 
              $this->db->or_where('rota_schedule.unit_id', $value['id']);
            } 
            $this->db->group_end();
          } 

        
        }
        if($params['jobrole']!='')
        {   
          $this->db->where_in('users.designation_id',$params['jobrole']);
        }
        $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['start_date']);
        $this->db->where('rota_schedule.date <=',  $params['end_date']);
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where('time_log.status', 1);
        $this->db->or_where('time_log.status', 0);
        $this->db->group_end();

        $this->db->where('time_log.accuracy >', 0);

        $this->db->order_by("rota_schedule.user_id asc,rota_schedule.date asc,time_log.id asc"); 
        
        $query = $this->db->get();
        // echo $this->db->last_query();
        // exit;
        $result = $query->result_array();
        return $result;
    
    }
    public function checkTimeSheetLockStatus($params){
      $this->db->select('*');
      $this->db->from('lock_timesheets');
      $this->db->where('from_date',$params['from_date']);
      $this->db->where('to_date',$params['to_date']);
      $this->db->where_in('unit_id', [0,$params['unit_id']]);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function checkExistingLockedTimesheet($params){
      $this->db->select('*');
      $this->db->from('lock_timesheets');
      // Check for existing lock records overlapping with the specified date range
      $this->db->where('unit_id', 0);
      $this->db->group_start();
      $this->db->where('from_date <=', $params['to_date']);
      $this->db->where('to_date >=', $params['from_date']);
      $this->db->group_end();
      $query = $this->db->get();
      $existing_locks = $query->result_array();
      if (count($existing_locks) > 0) {
        return array(
            'status' => false,
            'message' => "Unable to add training - This selected date is locked from editing"
        );
      }else{
        $this->db->select('*');
        $this->db->from('lock_timesheets');
        $this->db->join('unit','unit.id=lock_timesheets.unit_id');
        // Check for existing lock records overlapping with the specified date range
        $this->db->where_in('unit_id', $params['unit_id']);
        $this->db->group_start();
        $this->db->where('from_date <=', $params['to_date']);
        $this->db->where('to_date >=', $params['from_date']);
        $this->db->group_end();
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        $existing_locks = $query->result_array();
        if (count($existing_locks) > 0) {
          $formatted_locks = array();
          // Loop through the result set and format the strings
          foreach ($existing_locks as $lock) {
              $unit_name = $lock['unit_name'];
              $from_date = date('d/m/Y', strtotime($lock['from_date']));
              $to_date = date('d/m/Y', strtotime($lock['to_date']));
              $formatted_locks[] = "$unit_name [$from_date - $to_date]";
          }
          // Join the formatted strings with commas
          $formatted_locks_string = implode(', ', $formatted_locks);
          return array(
              'status' => false,
              'message' => "Unable to add training - This selected date is locked from editing for unit(s) ".$formatted_locks_string
          );
        }else{
          return array(
              'status' => true,
              'message' => "Success"
          );
        }
      }
    }
    public function updateTimeSheetLockStatus($params) {

      $data = array(
          'unit_id' => $params['unit_id'],
          'from_date' => $params['from_date'],
          'to_date' => $params['to_date'],
          'updated_by' => $params['user_id'],
      );
      if($params['status'] == 1){
        // Check for existing lock records overlapping with the specified date range
        $this->db->where_in('unit_id', [0,$params['unit_id']]);
        $this->db->group_start();
        $this->db->where('from_date <=', $params['to_date']);
        $this->db->where('to_date >=', $params['from_date']);
        $this->db->group_end();
        $existing_locks = $this->db->get('lock_timesheets')->result();
        if (!empty($existing_locks)) {
          $from_date = date('d/m/Y', strtotime($existing_locks[0]->from_date));
          $to_date = date('d/m/Y', strtotime($existing_locks[0]->to_date));
            // Dates overlap with existing lock records, show a message
            return array(
                'status' => 2,
                'message' => "The timesheet is already locked for the dates ".$from_date." and ".$to_date." . Please ensure there are no overlapping dates and try again."
            );
        }

        // Insert data into the 'lock_timesheets' table
        $result = $this->db->insert('lock_timesheets', $data);

        if (!$result) {
            // Error occurred during the insertion
            $error = $this->db->error();
            return array(
                'status' => 0,
                'message' => "Database error: " . $error['message']
            );
        } else {
            // Insertion was successful
            return array(
                'status' => 1,
                'message' => "Success",
                'url' => base_url() . 'admin/Reports/payrollreport'
            );
        }
      }else{
        try {
          $this->db->where_in('unit_id', [0, $params['unit_id']]);
          $this->db->group_start();
          $this->db->where('from_date', $params['from_date']);
          $this->db->where('to_date', $params['to_date']);
          $this->db->group_end();
          $this->db->delete('lock_timesheets');
          //echo $this->db->last_query();exit;
          return array(
              'status' => 1,
              'message' => "Success",
              'url' => base_url() . 'admin/Reports/payrollreport'
          );
        } catch (Exception $e) {
          return array(
              'status' => 0,
              'message' => "Database error: " . $e->getMessage()
          );
        }
      }
    }


  }
?>