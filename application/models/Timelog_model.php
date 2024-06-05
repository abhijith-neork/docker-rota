<?php
class Timelog_model extends CI_Model 
{

    //Timelog_model
	public function insert($data)
	{
	    $status = $this->db->insert("time_log", $data);
	    
	    if($status==true)
	    {
	        return 1;
	    }
	    else{
	        
	        $mysqlerror = $this->db->error();
	        return $mysqlerror['code'];
	    }
	    
	   // return $this->db->last_query(); 
	}
	
	public function getShiftofUserforaDay($params = array())
	{
	    $this->db->select('rota_schedule.shift_id,master_shift.start_time,master_shift.id');
	    $this->db->from('rota_schedule');
	    $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id','left');
	    $this->db->where('rota_schedule.user_id', $params['user_id']);
	    $this->db->where('rota_schedule.date', $params['date']);
	  
	    $query = $this->db->get();
	    // echo $this->db->last_query();exit;
	    $result = $query->result_array();
	    return $result;
	}
	
	public function checkLogofUserforaDay($params = array())
	{
	    $this->db->select('*');
	    $this->db->from('time_log');
	    $this->db->where('user_id', $params['user_id']);
	    $this->db->where('date', $params['date']);
	    $this->db->where('status', $params['status']);
	    $query = $this->db->get();
	    // echo $this->db->last_query();exit;
	    $result = $query->result_array();
	    return $result;
	}
	public function getlastLogofUserforaDay($params = array())
	{
	  //  print_r($params);
	    $this->db->select('*');
	    $this->db->from('time_log');
	    $this->db->where('user_id', $params['user_id']);
	    if($params['date'])  
	    $this->db->where('date', $params['date']);
	    $this->db->order_by('id', 'desc');
	    $this->db->limit(1);
	    $query = $this->db->get();
	   // echo $this->db->last_query(); 
	    $result = $query->result_array();
	    //print_r($result);
	    return $result;
	}
	public function getlastUserActivity($params = array())
	{
	    //  print_r($params);
	    $this->db->select('*');
	    $this->db->from('time_log');
	    $this->db->where('user_id', $params['user_id']);
	    $this->db->where('status < 2' );
 
	        $this->db->order_by('id', 'desc');
	        $this->db->limit(1);
	        $query = $this->db->get();
	        // echo $this->db->last_query();
	        $result = $query->result_array();
	        //print_r($result);
	        return $result;
	}
	public function findunique($unique)
	{
 
	    
	    $this->db->select('unique_id');
	    $this->db->from('time_log');
	    $this->db->where('unique_id', $unique);
	    $query2 = $this->db->get();
	     //echo $this->db->last_query(); 
	     $result = $query2->result_array();
	   //  print_r($result);exit;
	    if($query2->num_rows() == 0){
	        return 0;
	        
	    }
	    else{
	        return 1;
	        
	    }
	}
}

?>