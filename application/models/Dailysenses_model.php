<?php
class Dailysenses_model extends CI_Model 
{

  public function fetchCategoryTree($parent_id = 0, $spacing = '', $user_tree_array = '') {
 
      if (!is_array($user_tree_array))
        $user_tree_array = array();

      $this->db->where('parent_unit', $parent_id);
      $this->db->where('id !=', 1);
      $this->db->where('status', 1);  
      $query = $this->db->get('unit');
      $result = $query->result();
      foreach ($result as $mainCategory) {
        $user_tree_array[] = array("id" => $mainCategory->id, "unit_name" => $spacing . $mainCategory->unit_name);
          $user_tree_array = $this->fetchCategoryTree($mainCategory->id, '--' . '  ', $user_tree_array);
      }
      return $user_tree_array;
    }
     public function findunitnameOfUser($user_id)
      {

         $data = array(
            'unit.id',
            'unit.unit_name',
             );
     $this->db->select($data);
    $this->db->from('users');
    $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
    $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
    $this->db->where('users.id', $user_id);
    $query = $this->db->get();
   //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function finduserdata($unit_id)
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
         $this->db->where('users.status', 1);
         if(!empty($result))
         {
          $this->db->where('unit_id', $unit_id);
           foreach ($result as $value) {
           $this->db->or_where('unit_id', $value['id']);
           }
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

    public function insertsenses($datas)
    {
    	$result=$this->db->insert("daily_senses", $datas);
      if($result==1)
      {
        return 'true';
      }
      else
      {
        return 'false';
      }
    }

    public function findallsenses($id,$params)
    {
         $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,daily_senses.date,daily_senses.comment,daily_senses.creation_date,daily_senses.id,daily_senses.status,daily_senses.created_userid');
         $this->db->from('daily_senses'); 
         $this->db->join('personal_details', 'personal_details.user_id = daily_senses.user_id','left');
         $this->db->join('unit', 'unit.id = daily_senses.unit_id','left');
         if($id)
         {
           $this->db->where('daily_senses.unit_id', $id);
         }

          $this->db->group_start();
            $this->db->where('daily_senses.date >=', $params['start_date']);
            $this->db->where('daily_senses.date <=',  $params['end_date']);
          $this->db->group_end(); 

        
         $this->db->order_by('daily_senses.creation_date', "desc");
         $query = $this->db->get();
        //echo $this->db->last_query();exit; 
         $result = $query->result_array();
         return $result;

    }

    public function fetchDailysenses($unit)
    {   
         if($unit>0)
         {
            $this->db->select('id');
            $this->db->from('unit'); 
            $this->db->where('parent_unit', $unit);
            $query = $this->db->get();
            $results = $query->result_array(); 
         } 

         $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,daily_senses.date,daily_senses.comment,daily_senses.creation_date,daily_senses.id,daily_senses.status,daily_senses.created_userid');
         $this->db->from('daily_senses'); 
         $this->db->join('personal_details', 'personal_details.user_id = daily_senses.user_id','left');
         $this->db->join('unit', 'unit.id = daily_senses.unit_id','left');

         if(!empty($results))
         {
          
           $this->db->group_start();
           $this->db->where('daily_senses.unit_id', $unit);
           foreach ($results as $value) {
             $this->db->or_where('daily_senses.unit_id', $value['id']);
           }
           $this->db->group_end();
         }
         else
         {
           $this->db->where('daily_senses.unit_id', $unit);
         }
        
         $this->db->order_by('daily_senses.creation_date', "desc");
         $query = $this->db->get();
       // echo $this->db->last_query();exit; 
         $result = $query->result_array();
         return $result;


    }

    public function finddailysenses($id)
    {
         $this->db->select('personal_details.fname,personal_details.lname,unit.unit_name,daily_senses.date,daily_senses.id,daily_senses.comment,daily_senses.user_id,daily_senses.unit_id');
         $this->db->from('daily_senses');
         $this->db->join('personal_details', 'personal_details.user_id = daily_senses.user_id');
         $this->db->join('unit', 'unit.id = daily_senses.unit_id'); 
         $this->db->where('daily_senses.id', $id); 
         $query = $this->db->get();
         //echo $this->db->last_query();exit; 
         $result = $query->result_array();
         return $result;

    }

    public function update($id,$datas)
    {
      $this->db->where('id', $id);
      $result=$this->db->update('daily_senses',$datas);
      if($result==1)
      {
        return 'true';
      }
      else
      {
        return 'false';
      }
    }

    public function deleteddailysenses($id)
      {
//print_r($id);exit();
        // $status = $this->db->delete('master_payment_type', array('id' => $id));
        $status=array('status'=>2);
        $this->db->where('id', $id);
        $status=$this->db->update('daily_senses',$status);
        //echo $this->db->last_query();exit;
       
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
 
	
	
}

?>