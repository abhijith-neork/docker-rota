<?php

class Permission_model extends CI_Model 
{


  public function getPermission()
  {
    $this->db->select('*');
    $this->db->from('permissions'); 
    //$this->db->where('group_id', $role);
   $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
  }

  public function allgroup()
	{
		$this->db->select('*');
		$this->db->from('master_group');
		$this->db->order_by('group_name', 'ASC');
    $this->db->where('status', 1);
		$query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
	}
  
}

?>