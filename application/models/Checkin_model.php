<?php
class Checkin_model extends CI_Model 
{
 
    public function findshiftdetails()
    {
    	$this->db->select('id,shift_name,start_time');
    	$this->db->from('master_shift'); 
    	$this->db->where('id >=',5);
    	$this->db->order_by("id","asc");
    	$query = $this->db->get();
    	//echo $this->db->last_query();exit;
        $result = $query->result_array();
    	return $result;
    }

	public function Checkin_list($params)
	{ 
		 
		$data=array(
			'rota_schedule.user_id',
			'rota_schedule.unit_id',
			'rota_schedule.date as rota_date',
			'rota_schedule.shift_id',
			'time_log.id as time_log_id',
			'time_log.user_id as timelog_userid',
			'time_log.date as timelog_date',
			'time_log.status',
      'personal_details.user_id',
			'personal_details.fname',
			'personal_details.lname'
		);
		$this->db->select($data);
    	$this->db->from('rota_schedule');
    	$this->db->join('time_log','time_log.user_id = rota_schedule.user_id AND time_log.date = rota_schedule.date','left');  
    	$this->db->join('personal_details','personal_details.user_id = rota_schedule.user_id','left');
    	// $this->db->group_start();
    	if($params['date']!='')
    	{
    	$this->db->where('rota_schedule.date',$params['date']);
        }
    	$this->db->where('time_log.date is null');
    	if($params['shift_id']!='')
    	{
    	$this->db->where('rota_schedule.shift_id',$params['shift_id']);
        }
    	$query = $this->db->get();
    	// echo $this->db->last_query();exit;
        $result = $query->result_array();
    	return $result;
	}
	
    public function findlatetime()
    {
        $this->db->select('late_notify');
        $this->db->from('company');   
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result[0]['late_notify']; 
    }
    public function findsuperadminemail()
    {
        $this->db->select('email');
        $this->db->from('users');
        $this->db->where('id',1);   
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        //print_r($result[0]['email']);exit();
        return $result[0]['email']; 
    }
    public function findsupervisorDetails($unitid,$date)
    {
        $query = "SELECT  us.id,us.email,uu.unit_id,pd.fname,pd.lname FROM users as us
                join personal_details as pd on pd.user_id=us.id
                join user_unit as uu on uu.user_id=us.id
                join rota_schedule as rs on rs.user_id=us.id
                where  uu.unit_id=".$unitid." and us.group_id=5 and rs.date='".$date."'
                and (rs.shift_id>0 and rs.shift_id <1000)";
        $sql = $this->db->query($query);
        return $sql->result();
    }
    public function findunitadmin($unit_id)
    {
        if($unit_id>0) //checking parent unit
       {
          $this->db->select('parent_unit');
          $this->db->from('unit'); 
          $this->db->where('id', $unit_id);
          $query = $this->db->get();
          $results = $query->result_array(); 
       }
//print_r($results);
      $this->db->select('personal_details.mobile_number,users.email,personal_details.fname,personal_details.lname');
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('user_unit', 'user_unit.user_id = users.id'); 
      $this->db->or_where('users.group_id =', 1); 
      if($results[0]['parent_unit']==0)
      {  //parent unit is 0
       $this->db->where('user_unit.unit_id', $unit_id);
        }
        else
        {  //parent unit is not 0
         $this->db->group_start();
         $this->db->where('user_unit.unit_id', $unit_id);
       $this->db->or_where('user_unit.unit_id', $results[0]['parent_unit']);
       $this->db->group_end();
        }
        $this->db->group_by('personal_details.user_id');

      $query = $this->db->get();
      //echo $this->db->last_query();
      $result = $query->result_array();
      //print_r($result);exit();
      return $result;
    }
	
}

?>