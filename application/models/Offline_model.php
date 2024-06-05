<?php
class Offline_model extends CI_Model 
{

 	public function insert($data)
	{
	    $status = $this->db->insert("time_log_json", $data);

	    if($status==true)
	    {
	        return $this->db->insert_id(); 
	    }
	    else{
	        
	        $mysqlerror = $this->db->error();
	        return $mysqlerror['code'];
	    }
	    
	   // return $this->db->last_query(); 
	}
	
	 
 
	public function insertdata($data)
	{
	    $status = $this->db->insert("time_log_data", $data);
	 //   print  $this->db->last_query(); exit;
	    if($status==true)
	    {
	        return $this->db->insert_id(); 
	    }
	    else{
	        
	        $mysqlerror = $this->db->error();
	        return $mysqlerror['code'];
	    }
	    
	    // return $this->db->last_query();
	}
}

?>