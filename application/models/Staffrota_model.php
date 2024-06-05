<?php
	class Staffrota_model extends CI_Model 
	{
		public function checkRotaDataWithStartAndEndDate($start_date,$end_date,$user_id){
		  $array = array(
		    'start_date' => $start_date, 
		    'end_date'   => $end_date,
		    'user_id' => $user_id
		  );
		  $this->db->select('*');
		  $this->db->from('staff_rota');
		  $this->db->where($array);
		  $query = $this->db->get();
		  if($query->num_rows() > 0){
		    $result = $query->result_array();		    
			return array(
				'status' => 1,
				'result' => $result 
			);		    
		  }else{
		    return array(
		      'status' => 2,
		      'result' => []
		    );
		  }
		}
    public function getDayRota($params){
          $this->db->select('*');
          $this->db->from('staffrota_schedule');
          $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id','left');
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
        public function checkAvailability($unit_id,$user_id,$date){
              $array = array(
                'user_id' => $user_id, 
                'date'   => $date,
                'unit_id' => $unit_id
              );
              $this->db->select('staffrota_schedule.shift_id');
              $this->db->from('staffrota_schedule');
              $this->db->where($array);
              $query = $this->db->get();
                // print $this->db->last_query();
           //    exit();
              if($query->num_rows() > 0){
                $result = $query->result_array();           
                return $result[0]['shift_id'];                        
              }else{
                return array();
              }
        }
        public function checkUserExists($date,$user_id,$unit_id){
          $array = array(
            'user_id' => $user_id, 
            'date'   => $date,
            'unit_id' => $unit_id
          );
          $this->db->select('*');
          $this->db->from('staffrota_schedule');
          $this->db->where($array);
          $query = $this->db->get();
          if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result;
          }else{
            return array();
          }
        }
		public function addRotaDetails($rotaDetails){
		    $query = $this->db->insert('staff_rota',$rotaDetails);
		    $rota_id = $this->db->insert_id();
		    return array(
		        'rota_id' => $rota_id,
		        'flag' => 2
		    );
		}
		public function deleteShifts($rota_id){
		  $this->db->delete('staffrota_schedule', array('rota_id' => $rota_id));
		}
    public function deleteUserSchedule($shift_key){
      $this->db->delete('staffrota_schedule', array('id' => $shift_key));
    }
		public function addShiftDetails($shiftDetails){
		  $query = $this->db->insert('staffrota_schedule',$shiftDetails);
		}
    public function getUserRequestCount($params,$request_dates){
      $this->db->select('staffrota_schedule.date');
      $this->db->from('staffrota_schedule');
      $this->db->where('user_id', $params['user_id']);
      $this->db->where_in('date', $request_dates);
      /*$this->db->where('date >=', $params['start_date']);
      $this->db->where('date <=',  $params['end_date']);*/
      $this->db->where('shift_id =',$params['shift_id']);
      // $this->db->where('shift_id <',100);
      $query=$this->db->get();
      $result = $query->result_array(); 
      return $result;
    }
    public function getWeeklyShiftsForAdmin($params = array(),$user_from_other_unit){
      $unit_ids = array();
      if($params['unit_id']>0)
      {
        array_push($unit_ids, $params['unit_id']);
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $params['unit_id']);
        $query = $this->db->get();
        $results = $query->result_array();
      }
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
      $this->db->select('staffrota_schedule.user_id,staffrota_schedule.shift_id,available_master_shift.shift_shortcut,
      staffrota_schedule.unit_id,rota_id,date,
      master_shift.shift_shortcut as shift_name
      ,master_shift.start_time,master_shift.end_time,master_shift.shift_category');
      $this->db->from('staffrota_schedule');
      $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id','left');
      $this->db->join('available_master_shift', 'available_master_shift.id = staffrota_schedule.shift_id','left');
      $this->db->join('staff_rota', 'staff_rota.id = staffrota_schedule.rota_id');
      if($params['user_id']>0)
      $this->db->where('staffrota_schedule.user_id', $params['user_id']);
      if($params['unit_id']>0)
      { 
        if($user_from_other_unit){
          if(count($user_from_other_unit) > 0){
            for($i=0;$i<count($user_from_other_unit);$i++){
              array_push($unit_ids, $user_from_other_unit[$i]);
            }
          }
        } 
        if(!empty($results))
        { 
          foreach ($results as $value) 
          {  
            array_push($unit_ids, $value['id']);
          }
        }
      }
      array_unique($unit_ids);
      if(count($unit_ids) > 0)
      {$this->db->where_in('staffrota_schedule.unit_id',$unit_ids);}
      $this->db->where('date >=', $params['start_date']);
      $this->db->where('date <=',  $params['end_date']);
      $query=$this->db->get();
      $result = $query->result_array(); 
      return $result;
    }
		public function getWeeklyShifts($params = array())
		{
        
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
		        $this->db->select('staffrota_schedule.user_id,staffrota_schedule.shift_id,
		        staffrota_schedule.unit_id,rota_id,date,
		        master_shift.shift_shortcut as shift_name
		        ,master_shift.start_time,master_shift.end_time,master_shift.shift_category');
		        
		        $this->db->from('staffrota_schedule');
		        $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id','left');
		        $this->db->join('staff_rota', 'staff_rota.id = staffrota_schedule.rota_id');
		        
		        
		        if($params['user_id']>0)
		        $this->db->where('staffrota_schedule.user_id', $params['user_id']);
		        
		        /*if($params['unit_id']>0)
		        $this->db->where('staffrota_schedule.unit_id',$params['unit_id']);*/
            if($params['unit_id']>0){
              $this->db->where('staffrota_schedule.unit_id', $params['unit_id']);
            }
             
		        
		          $this->db->where('date >=', $params['start_date']);
		          $this->db->where('date <=',  $params['end_date']);
                    /*$this->db->or_where('date >', $params['start_date']);              
                    $this->db->or_where('date <', $params['end_date']);   */        
                    // $this->db->or_where('date ==', $params['start_date']);           
                    // $this->db->or_where('date ==', $params['end_date']);           
		         $query=$this->db->get();
                 // print_r($this->db->error());exit();
                 // print_r($this->db->error());exit();
		            //   exit();
		          $result = $query->result_array(); 
		          return $result;
		}
		public function getWeeklyShiftsOfEmptyBranch($params)
{
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
        $this->db->select('staffrota_schedule.user_id,staffrota_schedule.shift_id,
        staffrota_schedule.unit_id,rota_id,date,
        master_shift.shift_shortcut as shift_name
        ,master_shift.start_time,master_shift.end_time');
        
        $this->db->from('staffrota_schedule');
        $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id');
        $this->db->join('staff_rota', 'staff_rota.id = staffrota_schedule.rota_id');
        
        
        if($params['user_id']>0)
        $this->db->where('user_id', $params['user_id']);
        
        if($params['unit_id']>0)
        $this->db->where('staffrota_schedule.unit_id',$params['unit_id']);
        $this->db->where('MONTH(staffrota_schedule.date)',$params['month']);
        //$this->db->where('rota.published',1);

        $query=$this->db->get();
        $result = $query->result_array(); 
        return $result;
}
public function getWeeklyShiftsViewOfEmptyBranch($params)
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
         $this->db->select('staffrota_schedule.user_id as title, staffrota_schedule.user_id as id,users.weekly_hours, 
        CONCAT(personal_details.fname," ",personal_details.lname,"[",master_designation.designation_code,"]","[",master_shift.shift_shortcut,"]") as title');
        
        $this->db->from('staffrota_schedule');
        $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id');
        $this->db->join('staff_rota', 'staff_rota.id = staffrota_schedule.rota_id');
        $this->db->join('personal_details', 'personal_details.user_id = staffrota_schedule.user_id');
        $this->db->join('users', 'users.id = personal_details.user_id');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        
        $this->db->group_by('staffrota_schedule.user_id');
        
        if($params['user_id']!=='')
        $this->db->where('staffrota_schedule.user_id', $params['user_id']);
        
        if($params['unit_id']!=='')
        $this->db->where('staffrota_schedule.unit_id',$params['unit_id']); 
        $this->db->where('MONTH(staffrota_schedule.date)',$params['month']);
         $query=$this->db->get();
           // print $this->db->last_query();
           //    exit();
          $result = $query->result_array(); 
          return $result;
}
public function getWeeklyShiftsView($params)
{    
   //print_r($params);exit();
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
        $this->db->select('staffrota_schedule.user_id,staffrota_schedule.shift_id,
        staffrota_schedule.unit_id,rota_id,date,
        available_master_shift.shift_shortcut as shift_name
        ,available_master_shift.start_time,available_master_shift.end_time');
        
        $this->db->from('staffrota_schedule');
        // $this->db->join('master_shift', 'master_shift.id = staffrota_schedule.shift_id','left');
        $this->db->join('available_master_shift', 'available_master_shift.id = staffrota_schedule.shift_id','left');
        $this->db->join('staff_rota', 'staff_rota.id = staffrota_schedule.rota_id');
        $this->db->join('users', 'users.id = staff_rota.user_id');
        $this->db->where('staffrota_schedule.shift_id >=', 1000);

        if($params['user_id']>0)
        $this->db->where('staffrota_schedule.user_id', $params['user_id']);
        if($params['jobrole']>0)
        $this->db->where('users.designation_id', $params['jobrole']);
        
        if($params['unit_id']>0)
        $this->db->where('staffrota_schedule.unit_id',$params['unit_id']);
        $this->db->where('staffrota_schedule.date >=',$params['s_date']);
        $this->db->where('staffrota_schedule.date <=',$params['e_date']);
         $query=$this->db->get();
        // print_r($this->db->error());exit();
            // print $this->db->last_query();
            //   exit();
          $result = $query->result_array(); 
          return $result;
}
public function getWeeklyShiftsStaffsView($params)
{
     //print_r($params); exit();
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
        $this->db->select('staffrota_schedule.user_id as id, 
        CONCAT(personal_details.fname," ",personal_details.lname,"[",master_designation.designation_code,"]","[",available_master_shift.shift_shortcut,"]","[",unit.unit_shortname,"]") as title');
        
        $this->db->from('staffrota_schedule');
        $this->db->join('available_master_shift', 'available_master_shift.id = staffrota_schedule.shift_id');
        $this->db->join('staff_rota', 'staff_rota.id = staffrota_schedule.rota_id');
        $this->db->join('personal_details', 'personal_details.user_id = staffrota_schedule.user_id');
        $this->db->join('users', 'users.id = personal_details.user_id');
        $this->db->join('user_unit', 'user_unit.user_id = users.id');
        $this->db->join('unit', 'unit.id = user_unit.unit_id');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        $this->db->where('staffrota_schedule.shift_id >=', 1000);

        
        $this->db->group_by('staffrota_schedule.user_id');
        
        if($params['user_id']!=='')
        $this->db->where('staffrota_schedule.user_id', $params['user_id']);

        if($params['jobrole']>0)
        $this->db->where('users.designation_id', $params['jobrole']);
        
        if($params['unit_id']!=='')
        // $this->db->where('staffrota_schedule.from_unit',$params['unit_id']);
        $this->db->where('staffrota_schedule.unit_id',$params['unit_id']); 
        $this->db->where('MONTH(staffrota_schedule.date)',$params['month']);
          $query=$this->db->get();

           // print $this->db->last_query();
           //    exit();
          $result = $query->result_array(); 
          return $result;
}
public function getShiftId($params){
  $shift_id = '';
  $this->db->select('*');
  $this->db->from('staffrota_schedule'); 
  $this->db->where('unit_id=',$params['unit_id']);
  $this->db->where('date=',$params['date']);
  $this->db->where('user_id=',$params['user_id']);
  $query = $this->db->get();
  if($query->num_rows() > 0){
    $result = $query->result_array();
    return $result[0]['shift_id'];
  }else{
    return $shift_id;
  }
}
	}
?>