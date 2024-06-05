<?php

class Role_Permission_model extends CI_Model 
{


  public function findRole($role_id)
  {
    $this->db->select('*');
    $this->db->from('group_permissions');
    $this->db->join('master_group', 'master_group.id = group_permissions.group_id');
    $this->db->join('permissions', 'permissions.id = group_permissions.permission_id');
    $this->db->where('group_permissions.group_id', $role_id);
    $query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;


  }

  public function findRoles()
  {
    $this->db->select('*');
    $this->db->from('group_permissions');
    $this->db->join('master_group', 'master_group.id = group_permissions.group_id');
    $this->db->join('permissions', 'permissions.id = group_permissions.permission_id'); 
    $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;


  }

public function updatePermission($rId = null, $pId= null){
   		// delete & insert
   		if($this->deletePermission($rId,$pId)){
	   		$this->group_id = $rId;
	   		$this->permission_id = $pId;
        $this->status = 1;
	   		$this->db->insert('group_permissions', $this);
	   		$id = $this->db->insert_id();
   		}
	   	return true;
   }
  
 public function checkpermission($rId = null, $pId= null){
 	$this->db->select('group_id,permission_id');
 	$this->db->from('group_permissions');
 	$this->db->where ('group_id',$rId); 
	$this->db->where('permission_id',$pId);
	$query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

 }

  public function deletePermission($rId,$pId)
  { 
   		$this->db->delete('group_permissions', array('group_id'=>$rId,'permission_id'=>$pId)); 
   		return true;
   }
  
}

?>