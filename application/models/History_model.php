<?php
	class History_Model extends CI_Model 
	{

    public function findAddressHistory($config,$status)
    {
      $this->db->select('personal_details.fname,personal_details.lname,history_staff_address.*');
      $this->db->from('history_staff_address'); 
      $this->db->join('personal_details','personal_details.user_id=history_staff_address.user_id');
      if($status == 1 || $status == 3){
        if (!empty($config['search_value'])) {
          $this->db->group_start();
          $search_value_escaped = $this->db->escape_like_str($config['search_value']);
          $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
          $this->db->or_like('history_staff_address.address', $search_value_escaped);
          $this->db->or_like('history_staff_address.kin_name', $search_value_escaped);
          $this->db->or_like('history_staff_address.kin_address', $search_value_escaped);
          $this->db->or_like('history_staff_address.kin_phonenumber', $search_value_escaped);
          $this->db->group_end();
        }
        if($status == 1){
          $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
          if($config['length'] != -1){
            $this->db->limit($config['length'], $config['start']);
          }
          $query = $this->db->get();
          // echo $this->db->last_query();exit();
          $result = $query->result_array();
          return $result;
        }else{
          $query = $this->db->get();
          return $query->num_rows();
        }
      }else{
        $query = $this->db->get();
        return $query->num_rows();
      }
    }
    public function findAddressHistoryByuser($unit_id,$config,$status)
    {
      if($unit_id)
      {
        $this->db->select('unit.id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array(); 
      }
      $this->db->select('*');
      $this->db->from('history_staff_address'); 
      $this->db->join('personal_details','personal_details.user_id=history_staff_address.user_id'); 
      $this->db->join('user_unit', 'user_unit.user_id = history_staff_address.user_id','left'); 
      if($unit_id)
      {
        if(count($result)>0)
        {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id', $unit_id);
            foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
        }
        else
        {
          $this->db->where('user_unit.unit_id', $unit_id);
        }
      }
      if($status == 1 || $status == 3){
        if (!empty($config['search_value'])) {
          $this->db->group_start();
          $search_value_escaped = $this->db->escape_like_str($config['search_value']);
          $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
          $this->db->or_like('history_staff_address.address', $search_value_escaped);
          $this->db->or_like('history_staff_address.kin_name', $search_value_escaped);
          $this->db->or_like('history_staff_address.kin_address', $search_value_escaped);
          $this->db->or_like('history_staff_address.kin_phonenumber', $search_value_escaped);
          $this->db->group_end();
        }
        if($status == 1){

          $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
          if($config['length'] != -1){
            $this->db->limit($config['length'], $config['start']);
          }
          $query = $this->db->get();
          // echo $this->db->last_query();exit;
          $result = $query->result_array();
          return $result;
        }else{
          $query = $this->db->get();
          return $query->num_rows();
        }
      }else{
        $query = $this->db->get();
        return $query->num_rows();
      }
    }
    public function findUserunitHistoryByuser($unit_id,$status,$config,$flag)
    {
      if($unit_id)
      {
        $this->db->select('unit.id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array(); 
      }
      //print_r($result);exit();
      $this->db->select('
        personal_details.fname,
        personal_details.lname,
        User_Unit_Designation_history.*'
      );
      $this->db->from('User_Unit_Designation_history'); 
      $this->db->join('personal_details','personal_details.user_id=User_Unit_Designation_history.user_id'); 
      $this->db->join('user_unit', 'user_unit.user_id = User_Unit_Designation_history.user_id','left'); 
      $this->db->join('unit','unit.id=User_Unit_Designation_history.Previous_unit','left');
      $this->db->join('unit as current_unit', 'current_unit.id = User_Unit_Designation_history.current_unit','left');
      $this->db->join('personal_details as updated_person', 'updated_person.user_id = User_Unit_Designation_history.Updated_by','left');
      if($unit_id)
      {
        if(count($result)>0)
        {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id', $unit_id);
            foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();

        }
        else
        {
          $this->db->where('user_unit.unit_id', $unit_id);
        }
      }
       if($status>0)
      {
      $this->db->where('User_Unit_Designation_history.Status', $status);
      }
      if($flag == 1 || $flag == 3){
        if (!empty($config['search_value'])) {
          $this->db->group_start();
          $search_value_escaped = $this->db->escape_like_str($config['search_value']);
          $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
          $this->db->or_like('unit.unit_name', $search_value_escaped);
          $this->db->or_like('User_Unit_Designation_history.user_id', $search_value_escaped);
          $this->db->or_like('current_unit.unit_name', $search_value_escaped);
          $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
          $this->db->group_end();
        }
        if($flag == 1){
          $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
          if($config['length'] != -1){
            $this->db->limit($config['length'], $config['start']);
          }
          $query = $this->db->get();
          $result = $query->result_array();
          return $result;
        }else{
          $query = $this->db->get();
          return $query->num_rows();
        }
      }else{
        $query = $this->db->get();
        return $query->num_rows();
      }
    }
    public function findUserunitHistory($status,$config,$flag)
    {
      $this->db->select('
        personal_details.fname,
        personal_details.lname,
        User_Unit_Designation_history.*'
      );
      $this->db->from('User_Unit_Designation_history'); 
      $this->db->join('personal_details','personal_details.user_id=User_Unit_Designation_history.user_id');
      $this->db->join('unit','unit.id=User_Unit_Designation_history.Previous_unit','left');
      $this->db->join('unit as current_unit', 'current_unit.id = User_Unit_Designation_history.current_unit','left');
      $this->db->join('personal_details as updated_person', 'updated_person.user_id = User_Unit_Designation_history.Updated_by','left');
      if($status>0)
      {
        $this->db->where('User_Unit_Designation_history.Status', $status);
      }
      if($flag == 1 || $flag == 3){
        if (!empty($config['search_value'])) {
          $this->db->group_start();
          $search_value_escaped = $this->db->escape_like_str($config['search_value']);
          $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
          $this->db->or_like('unit.unit_name', $search_value_escaped);
          $this->db->or_like('User_Unit_Designation_history.user_id', $search_value_escaped);
          $this->db->or_like('current_unit.unit_name', $search_value_escaped);
          $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
          $this->db->group_end();
        }
        if($flag == 1){
          $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
          if($config['length'] != -1){
            $this->db->limit($config['length'], $config['start']);
          }
          $query = $this->db->get();
          // echo $this->db->last_query();exit;
          $result = $query->result_array();
          return $result; 
        }else{
          $query = $this->db->get();
          return $query->num_rows();
        }
      }else{
        $query = $this->db->get();
        return $query->num_rows();
      }
    }
    public function getEmaildetails_count()
    {

      $email_to='siva@cloudbydesign.co.uk';
      $type='TimeLog';
      
      $this->db->select('*');
      $this->db->from('email_log');
      if($limit!=0)
      {
        $this->db->limit($limit,$start);
      }  
      $this->db->where('email_to', $email_to);
      $this->db->where('type', $type);
      $this->db->like('email_settings','userID=1');
      $this->db->or_like('email_settings','check with user id');
      $query = $this->db->get();
    // echo $this->db->last_query();exit;
      return $query->num_rows();

    }

    public function getEmailreportstaffname($user_id)
    { 
      $res = preg_replace("/[^0-9]/", "", $user_id); //print_r($res);
      $this->db->select("CONCAT((fname),(' '),(' '),(lname)) as full_name");
      $this->db->from('personal_details'); 
      $this->db->where('user_id', $res); 
      $query = $this->db->get();
      if($query !== FALSE && $query->num_rows() > 0)
      {
        $result = $query->result_array();
      } 

      return $result;
    }

    public function getEmaildetails($limit,$start,$col,$dir)
    {

      $email_to='siva@cloudbydesign.co.uk';
      $type='TimeLog';
      
      $this->db->select('*');
      $this->db->from('email_log');
      if($limit!=0)
      {
        $this->db->limit($limit,$start);
      }  
      $this->db->where('email_to', $email_to);
      $this->db->where('type', $type);
      $this->db->like('email_settings','userID=1');
      $this->db->or_like('email_settings','check with user id');
      $this->db->order_by('id', "desc");
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      if($query->num_rows()>0)
        {
            return $query->result();
        }
        else
        {
            return null;
        }
    }

    function getEmaildetailssearch_count($search)
    {
      
      $email_to='siva@cloudbydesign.co.uk';
      $type='TimeLog';
      
      $this->db->select('*');
      $this->db->from('email_log');
      if($limit!=0)
      {
        $this->db->limit($limit,$start);
      }  
      $this->db->where('email_to', $email_to);
      $this->db->where('type', $type);
      $this->db->group_start();
      $this->db->like('email_settings','userID=1');
      $this->db->or_like('email_settings','check with user id');
      $this->db->group_end();
      $this->db->order_by('id', "desc");
      $this->db->group_start();
      $this->db->like('email_to',$search);
      $this->db->or_like('email_settings',$search);
      $this->db->or_like('email_log.created_at',$search); 
      $this->db->group_end();
      $query = $this->db->get();
     //echo $this->db->last_query();exit;
      return $query->num_rows();

    }

    
    function getEmaildetails_search($search)
    {  
        //print_r($this->session->userdata('user_type'));exit();
      $email_to='siva@cloudbydesign.co.uk';
      $type='TimeLog';
      
      $this->db->select('*');
      $this->db->from('email_log');
      if($limit!=0)
      {
        $this->db->limit($limit,$start);
      }  
      $this->db->where('email_to', $email_to);
      $this->db->where('type', $type);
      $this->db->group_start();
      $this->db->like('email_settings','userID=1');
      $this->db->or_like('email_settings','check with user id');
      $this->db->group_end();
      $this->db->order_by('id', "desc");
      $this->db->group_start();
      $this->db->like('email_to',$search);
      $this->db->or_like('email_settings',$search);
      $this->db->or_like('email_log.created_at',$search); 
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
    public function findUserContractHoursHistory()
    {
      $data=array(
        'personal_details.fname',
        'personal_details.lname',
        'history_weekly_hours.user_id',
        'history_weekly_hours.previous_contracted_hours',
        'history_weekly_hours.updated_contracted_hours',
        'history_weekly_hours.updation_date',
        'history_weekly_hours.updated_userid'
      );
      $this->db->select($data);
      $this->db->from('history_weekly_hours'); 
      $this->db->join('personal_details','personal_details.user_id=history_weekly_hours.user_id');
      $this->db->order_by('history_weekly_hours.created_date', "desc");
      $query = $this->db->get();
      $result = $query->result_array();
      return $result; 
    }
    public function findUserRatesHistory()
    {
      $data=array(
      	'personal_details.fname',
      	'personal_details.lname',
      	'history_user_rates.day_rate',
      	'history_user_rates.night_rate',
      	'history_user_rates.day_saturday_rate',
      	'history_user_rates.day_sunday_rate',
      	'history_user_rates.weekend_night_rate',
      	'history_user_rates.updation_date',
      	'history_user_rates.updated_userid');

      $this->db->select($data);
      $this->db->from('history_user_rates'); 
      $this->db->join('personal_details','personal_details.user_id=history_user_rates.user_id'); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result; 
    }
    public function findContractHoursHistoryByuser($unit_id)
    {
      if($unit_id)
      {
        $this->db->select('unit.id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array(); 
      }
      $data=array(
        'personal_details.fname',
        'personal_details.lname',
        'history_weekly_hours.user_id',
        'history_weekly_hours.previous_contracted_hours',
        'history_weekly_hours.updated_contracted_hours',
        'history_weekly_hours.updation_date',
        'history_weekly_hours.updated_userid'
      );
      $this->db->select($data);
      $this->db->from('history_weekly_hours'); 
      $this->db->join('personal_details','personal_details.user_id=history_weekly_hours.user_id'); 
      $this->db->join('user_unit', 'user_unit.user_id = history_weekly_hours.user_id','left');
      if($unit_id)
      {
        if(count($result)>0)
        {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id', $unit_id);
            foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();
        }
        else
        {
          $this->db->where('user_unit.unit_id', $unit_id);
        }
      }
      $this->db->order_by('history_weekly_hours.created_date', "desc");
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function findUserRatesHistoryByuser($unit_id)
    {
      if($unit_id)
      {
        $this->db->select('unit.id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array(); 
      }
      //print_r($result);exit();
      $data=array(
      	'personal_details.fname',
      	'personal_details.lname',
      	'history_user_rates.day_rate',
      	'history_user_rates.night_rate',
      	'history_user_rates.day_saturday_rate',
      	'history_user_rates.day_sunday_rate',
      	'history_user_rates.weekend_night_rate',
      	'history_user_rates.updation_date',
      	'history_user_rates.updated_userid');

      $this->db->select($data);
      $this->db->from('history_user_rates'); 
      $this->db->join('personal_details','personal_details.user_id=history_user_rates.user_id'); 
      $this->db->join('user_unit', 'user_unit.user_id = history_user_rates.user_id','left');
      if($unit_id)
      {
        if(count($result)>0)
        {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id', $unit_id);
            foreach ($result as $value) { 
              $this->db->or_where('user_unit.unit_id', $value['id']);
            } 
            $this->db->group_end();

        }
        else
        {
          $this->db->where('user_unit.unit_id', $unit_id);
        }
      }
      //$this->db->where('user_unit.unit_id', $unit_id);
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
    }

    public function findActivityType()
    {
      $this->db->select('activity_log.add_type');
      $this->db->from('activity_log');  
      $this->db->where('activity_log.add_type!=','Approve_holiday');
      $this->db->where('activity_log.add_type!=','Reject_holiday');
     // $this->db->where('activity_log.add_type!=','Move Shift');
      $this->db->group_by('activity_log.add_type'); 
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result; 
    }
    

    public function findRotaUpdateHistory()
    {
      $data=array(
        'activity_log.description',
        'activity_log.activity_date',
        'activity_log.add_type',
        'personal_details.fname',
        'personal_details.lname');

      $this->db->select($data);
      $this->db->from('activity_log'); 
      $this->db->join('personal_details','personal_details.user_id=activity_log.activity_by'); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result; 
    }

    function getAccuracycount($params)
    {
       if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }

        $data=array(
            'time_log.id',
            'time_log.user_id',
            'time_log.accuracy',
            'time_log.status',
            'time_log.date',
            'time_log.with_userid',
            'personal_details.fname',
            'personal_details.lname',
            'user_unit.unit_id');
        
        $this->db->select($data);
        $this->db->from('time_log');
        $this->db->join('personal_details','personal_details.user_id=time_log.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = time_log.user_id','left');
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
          if(!empty($result))
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$params['unit']);
            foreach ($result as $value) {
               $this->db->or_where('user_unit.unit_id',$value['id']);
            }
            $this->db->group_end();
          }
          else
          {
             $this->db->where('user_unit.unit_id',$params['unit']);;
          }
        }

        if($params['start_time']!=NULL)
        {
             $this->db->where('time_log.date', $params['start_time']);
        }
        
        $this->db->group_start();
        $this->db->where('time_log.status',2);
        $this->db->or_where('time_log.status',3);
        $this->db->group_end();

        $query = $this->db->get();
       //echo $this->db->last_query();exit;
        if($query !== FALSE && $query->num_rows() > 0)
        {
          return $query->num_rows();
        }
        else
        {
          return 0;
        }
           

    }

    function getAcuracydetails($limit,$start,$order,$dir,$params)
    {
       if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }

        $data=array(
            'time_log.id',
            'time_log.user_id',
            'time_log.accuracy',
            'time_log.status',
            'time_log.date',
            'time_log.with_userid',
            'personal_details.fname',
            'personal_details.lname',
            'user_unit.unit_id');
        
        $this->db->select($data);
        $this->db->from('time_log');
        $this->db->join('personal_details','personal_details.user_id=time_log.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = time_log.user_id','left');
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
          if(!empty($result))
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$params['unit']);
            foreach ($result as $value) {
               $this->db->or_where('user_unit.unit_id',$value['id']);
            }
            $this->db->group_end();
          }
          else
          {
             $this->db->where('user_unit.unit_id',$params['unit']);;
          }
        }

        if($params['start_time']!=NULL)
        {
             $this->db->where('time_log.date', $params['start_time']);
        }
        
        $this->db->group_start();
        $this->db->where('time_log.status',2);
        $this->db->or_where('time_log.status',3);
        $this->db->group_end();

        $query = $this->db->get();
       // echo $this->db->last_query();exit;
        if($query !== FALSE && $query->num_rows() > 0)
        {
         return $query->result();
        }
        else
        {
          return null;
        }
           

    }
        function getAccuracySearchcount($search,$params)
    {
       if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }

        $data=array(
            'time_log.id',
            'time_log.user_id',
            'time_log.accuracy',
            'time_log.status',
            'time_log.date',
            'time_log.with_userid',
            'personal_details.fname',
            'personal_details.lname',
            'user_unit.unit_id');
        
        $this->db->select($data);
        $this->db->from('time_log');
        $this->db->join('personal_details','personal_details.user_id=time_log.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = time_log.user_id','left');
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
          if(!empty($result))
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$params['unit']);
            foreach ($result as $value) {
               $this->db->or_where('user_unit.unit_id',$value['id']);
            }
            $this->db->group_end();
          }
          else
          {
             $this->db->where('user_unit.unit_id',$params['unit']);;
          }
        }

        if($params['start_time']!=NULL)
        {
             $this->db->where('time_log.date', $params['start_time']);
        }
        
        $this->db->group_start();
        $this->db->where('time_log.status',2);
        $this->db->or_where('time_log.status',3);
        $this->db->group_end();


        $this->db->group_start();
        $this->db->like('time_log.id',$search);
        $this->db->or_like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        $this->db->or_like('time_log.user_id',$search);
        $this->db->or_like('time_log.date',$search);
        $this->db->or_like('time_log.accuracy',$search);
        $this->db->group_end();

        $query = $this->db->get();
       // echo $this->db->last_query();exit;
        if($query !== FALSE && $query->num_rows() > 0)
        {
          return $query->num_rows();
        }
        else
        {
          return 0;
        }
           

    }

    function getAccuracySearchdetails($search,$params,$limit,$start,$order,$dir)
    {
       if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }

        $data=array(
            'time_log.id',
            'time_log.user_id',
            'time_log.accuracy',
            'time_log.status',
            'time_log.date',
            'time_log.with_userid',
            'personal_details.fname',
            'personal_details.lname',
            'user_unit.unit_id');
        
        $this->db->select($data);
        $this->db->from('time_log');
        $this->db->join('personal_details','personal_details.user_id=time_log.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = time_log.user_id','left');
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
          if(!empty($result))
          {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id',$params['unit']);
            foreach ($result as $value) {
               $this->db->or_where('user_unit.unit_id',$value['id']);
            }
            $this->db->group_end();
          }
          else
          {
             $this->db->where('user_unit.unit_id',$params['unit']);;
          }
        }

        if($params['start_time']!=NULL)
        {
             $this->db->where('time_log.date', $params['start_time']);
        }
        
        $this->db->group_start();
        $this->db->where('time_log.status',2);
        $this->db->or_where('time_log.status',3);
        $this->db->group_end();

        $this->db->group_start();
        $this->db->like('time_log.id',$search);
        $this->db->or_like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        $this->db->or_like('time_log.user_id',$search);
        $this->db->or_like('time_log.date',$search);
        $this->db->or_like('time_log.accuracy',$search);
        $this->db->group_end();

        $query = $this->db->get();
       //echo $this->db->last_query();exit;
        if($query !== FALSE && $query->num_rows() > 0)
        {
         return $query->result();
        }
        else
        {
          return null;
        }
           

    }
    
    function rotaupdate_count($params)
    {   //print_r($params);exit();

         if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }

        $data=array(
            'activity_log.description',
            'activity_log.activity_date',
            'activity_log.add_type',
            'personal_details.fname',
            'personal_details.lname');
        
        $this->db->select($data);
        $this->db->from('activity_log');
        $this->db->join('personal_details','personal_details.user_id=activity_log.activity_by'); 
        $this->db->join('user_unit', 'user_unit.user_id = activity_log.activity_by','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->where('activity_log.add_type!=','Approve_holiday');
        $this->db->where('activity_log.add_type!=','Reject_holiday');
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
          if($params['activity_type']=='Move shift')
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('activity_log.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('activity_log.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('activity_log.unit_id',$params['unit']);;
                }
           }
           else
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('user_unit.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('user_unit.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('user_unit.unit_id',$params['unit']);;
                }
           }
        }
        
        if($params['activity_type']!=NULL)
        {
            $this->db->where('activity_log.add_type',$params['activity_type']);
        }

        if($params['start_time']!=NULL && $params['end_time']!=NULL)
        {

          if($params['start_time']==$params['end_time'])
          {
             $this->db->where('date(activity_log.activity_date)', $params['start_time']);
          }
          else
          {
             $this->db->where('date(activity_log.activity_date)>=', $params['start_time']);
             $this->db->where('date(activity_log.activity_date)<=', $params['end_time']);
          }
        }

       

        $this->db->order_by('activity_log.activity_date', "desc");
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->num_rows();
        
    }
    
    function rotaupdate($limit,$start,$col,$dir,$params)
    { 

        if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }
        //print_r($result);exit();
        $data=array(
            'activity_log.description',
            'activity_log.activity_date',
            'activity_log.add_type',
            'personal_details.fname',
            'personal_details.lname');
        
        $this->db->select($data);
        $this->db->from('activity_log');
        $this->db->join('personal_details','personal_details.user_id=activity_log.activity_by'); 
        $this->db->join('user_unit', 'user_unit.user_id = activity_log.activity_by','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->where('activity_log.add_type!=','Approve_holiday');
        $this->db->where('activity_log.add_type!=','Reject_holiday');
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
           if($params['activity_type']=='Move shift')
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('activity_log.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('activity_log.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('activity_log.unit_id',$params['unit']);;
                }
           }
           else
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('user_unit.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('user_unit.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('user_unit.unit_id',$params['unit']);;
                }
           }
                
        }
        if($params['activity_type']!=NULL)
        {
            $this->db->where('activity_log.add_type',$params['activity_type']);
        }
        if($params['start_time']!=NULL && $params['end_time']!=NULL)
        {

          if($params['start_time']==$params['end_time'])
          {
             $this->db->where('date(activity_log.activity_date)', $params['start_time']);
          }
          else
          {
             $this->db->where('date(activity_log.activity_date)>=', $params['start_time']);
             $this->db->where('date(activity_log.activity_date)<=', $params['end_time']);
          }
        }

        $this->db->order_by('activity_log.activity_date', "desc");
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

    function rotaupdatesearch_count($search,$params)
    {  

        if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
         }

        $data=array(
            'activity_log.description',
            'activity_log.activity_date',
            'activity_log.add_type',
            'personal_details.fname',
            'personal_details.lname');
        
        $this->db->select($data);
        $this->db->from('activity_log');
        $this->db->join('personal_details','personal_details.user_id=activity_log.activity_by');
        $this->db->join('user_unit', 'user_unit.user_id = activity_log.activity_by','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->where('activity_log.add_type!=','Approve_holiday');
        $this->db->where('activity_log.add_type!=','Reject_holiday');
        //if($this->session->userdata('user_type')!=1)
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
          if($params['activity_type']=='Move shift')
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('activity_log.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('activity_log.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('activity_log.unit_id',$params['unit']);;
                }
           }
           else
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('user_unit.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('user_unit.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('user_unit.unit_id',$params['unit']);;
                }
           }
        }
        
        if($params['activity_type']!=NULL)
        {
            $this->db->where('activity_log.add_type',$params['activity_type']);
        }
        if($params['start_time']!=NULL && $params['end_time']!=NULL)
        {

          if($params['start_time']==$params['end_time'])
          {
             $this->db->where('date(activity_log.activity_date)', $params['start_time']);
          }
          else
          {
             $this->db->where('date(activity_log.activity_date)>=', $params['start_time']);
             $this->db->where('date(activity_log.activity_date)<=', $params['end_time']);
          }
        }

        $this->db->group_start();
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        $this->db->or_like('activity_log.add_type',$search);
        $this->db->or_like('activity_log.activity_date',$search);
        $this->db->group_end();
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->num_rows();
        
    }

    
    function rotaupdate_search($search,$params,$limit,$start,$order,$dir)
    {
        //print_r($this->session->userdata('user_type'));exit();
       if($params['unit']!=NULL)
        {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit']);
        $query = $this->db->get();
        $result = $query->result_array();
        }

        $data=array(
            'activity_log.description',
            'activity_log.activity_date',
            'activity_log.add_type',
            'personal_details.fname',
            'personal_details.lname');
        
        $this->db->select($data);
        $this->db->from('activity_log');
        $this->db->join('personal_details','personal_details.user_id=activity_log.activity_by');
        $this->db->join('user_unit', 'user_unit.user_id = activity_log.activity_by','left');
        $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
        $this->db->where('activity_log.add_type!=','Approve_holiday');
        $this->db->where('activity_log.add_type!=','Reject_holiday');
        //if($this->session->userdata('user_type')!=1)
        if($limit!=0)
        {
        $this->db->limit($limit,$start);
        }
        if($params['unit']!=NULL)
        {
           if($params['activity_type']=='Move shift')
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('activity_log.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('activity_log.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('activity_log.unit_id',$params['unit']);;
                }
           }
           else
           {
              if(!empty($result))
                {
                  $this->db->group_start();
                  $this->db->where('user_unit.unit_id',$params['unit']);
                  foreach ($result as $value) {
                     $this->db->or_where('user_unit.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                }
                else
                {
                   $this->db->where('user_unit.unit_id',$params['unit']);;
                }
           }
        }
        
        if($params['activity_type']!=NULL)
        {
            $this->db->where('activity_log.add_type',$params['activity_type']);
        }

        if($params['start_time']!=NULL && $params['end_time']!=NULL)
        {

          if($params['start_time']==$params['end_time'])
          {
             $this->db->where('date(activity_log.activity_date)', $params['start_time']);
          }
          else
          {
             $this->db->where('date(activity_log.activity_date)>=', $params['start_time']);
             $this->db->where('date(activity_log.activity_date)<=', $params['end_time']);
          }
        }

        $this->db->group_start();
        $this->db->like('personal_details.fname',$search);
        $this->db->or_like('personal_details.lname',$search);
        $this->db->or_like('activity_log.add_type',$search);
        $this->db->or_like('activity_log.activity_date',$search);
        $this->db->group_end();
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


	}
?>