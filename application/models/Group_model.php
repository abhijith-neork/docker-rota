<?php

class Group_model extends CI_Model 
{

	
	public function insertgroup($dataform)
	{
		$this->db->insert("master_group", $dataform);
	}


	public function allgroup()
	{
		$this->db->select('*');
		$this->db->from('master_group');
		$this->db->order_by('group_name', 'ASC');
    $this->db->where('status', 1); 
		$query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
	}

  public function getPermission()
  {
    $this->db->select('*');
    $this->db->from('permissions');
    $this->db->order_by('name', 'ASC');
    $query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;


  }

     public function deletegroup($id)
      { 
        $status=array('status'=>3);
        $this->db->where('id', $id);
        $status=$this->db->update('master_group',$status);
         if($status==true){
             return 1;
         }
         else{
             
                $mysqlerror = $this->db->error();
                return $mysqlerror['code'];
             }
      
      }

      public function findgroup($id)
      {
      	$this->db->select('*');
		    $this->db->from('master_group');
		    $this->db->where('id', $id);
		    $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

       public function findgroupbystatus($status)
      {
        $this->db->select('*');
        $this->db->from('master_group');
        if(!empty($status))
          {
          $this->db->where_in('status', $status); 
          }
          else if(empty($status))
          {
           $this->db->where_in('status', "1"); 
          }
        $query = $this->db->get();
            // echo $this->db->last_query();exit;
             $result = $query->result_array();
             return $result; 
      }
      public function getgroupDetails($params)
      {
          $this->db->select('*');
          $this->db->from('master_group');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['group_name'])
               $this->db->where('group_name', $params['group_name']);
          
          $query = $this->db->get();
         // echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
      }

      public function findUsersGroup($id)
      {
        $this->db->select('status');
        $this->db->from('users');
        $this->db->where('group_id', $id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;

      }
    
    

      public function update($id,$datahome){
         
         $this->db->where('id', $id);
         $this->db->update('master_group',$datahome);
         }
}

?>