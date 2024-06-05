<?php

class Designation_Model extends CI_Model 
{

	
	public function insertdesignation($dataform)
	{
		$this->db->insert("master_designation", $dataform);
     $insert_id = $this->db->insert_id();

   return  $insert_id;
	}

  public function insertdesignationrates($data)
  {
    $this->db->insert("designation_rates", $data);

  }

  public function finddesignationidofuser($user_id)
  {
         $this->db->select('designation_id'); 
         $this->db->from('users');
         $this->db->where('id', $user_id);
         $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

  }


	public function alldesignation()
	{
		$this->db->select('*');
		$this->db->from('master_designation');
    $this->db->where('status', 1); 
		$query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
	}

  public function alldesignationgroups()
  {
    $this->db->select('*');
    $this->db->from('jobrole_group');
    $query = $this->db->get();
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

  }

  public function findjobgroup()
  {
    $this->db->select('*');
    $this->db->from('jobrole_group');
    //$this->db->where('status', 1); 
    $query = $this->db->get();
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }
      public function deletedesignation($id)
      { 
        $status=array('status'=>3);
        $this->db->where('id', $id);
        $status=$this->db->update('master_designation',$status);
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


      public function finddesignationbystatus($status)
      {   //print_r($status[0]);
        $this->db->select('*');
        $this->db->from('master_designation');
        // if($status[0]!=4)
        // {
          if(!empty($status))
          {
          $this->db->where_in('status', $status); 
          }
          else if(empty($status))
          {
           $this->db->where_in('status', "1"); 
          }
        // }
        $query = $this->db->get();
            //echo $this->db->last_query();exit;
             $result = $query->result_array();
            // print_r($result);exit();
             return $result; 
      }


      public function  DesignationByUser($id)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('designation_id', $id);
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
  }

   public function  finduserDesignation($id)
  {
    $this->db->select('designation_id');
    $this->db->from('users');
    $this->db->where('id', $id);
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result[0]['designation_id'];
  }
   public function insertDesignationchangehistory($dataform)
  {
    $this->db->insert("User_Unit_Designation_history", $dataform);
    $insert_id = $this->db->insert_id();
  }

      public function finddesignation($id)
      {
        $data=array(
          'master_designation.id',
          'master_designation.designation_name',
          'master_designation.description',
          'master_designation.designation_code',
          'master_designation.availability_requests',
          'designation_rates.normal_rates',
          'designation_rates.overtime_rate',
          'designation_rates.holiday_rate',
          'designation_rates.sickness_rate',
          'designation_rates.maternity_rate',
          'designation_rates.authorised_absence_rate',
          'designation_rates.unauthorised_absence_rate',
          'designation_rates.other_rates',
          'master_designation.jobrole_groupid'
        );
      $this->db->select($data);
  		$this->db->from('master_designation');
      $this->db->join('designation_rates', 'designation_rates.designation_id = master_designation.id','left');
  		$this->db->where('master_designation.id', $id);
  		$query = $this->db->get();
     // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function getdesignationDetails($params)
      {
          $this->db->select('*');
          $this->db->from('master_designation');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['designation_name'])
               $this->db->where('designation_name', $params['designation_name']);
          
          $query = $this->db->get();
         // echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
      }

      public function findUsersDesignation($id)
      {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('designation_id', $id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;

      }
    

    

      public function update($id,$datahome){ 
         $this->db->where('id', $id);
         $this->db->update('master_designation',$datahome); 
         $updated_status = $this->db->affected_rows(); 

        if($updated_status):
            return $id;
        else:
            return false;
        endif;
         }

      public function upadatedesignationrates($result,$data)
      {
        $this->db->where('id', $result);
        $this->db->update('designation_rates',$data);
      }

      public function update_sort_order($sort_order,$id)
      {
         $data=array('sort_order'=>$sort_order);
         $this->db->where('id', $id);
         $this->db->update('master_designation',$data); 
         $updated_status = $this->db->affected_rows(); 
        // print_r($updated_status);exit();
        if($updated_status):
            return $id;
        else:
            return false;
        endif;
      }
 }

?>