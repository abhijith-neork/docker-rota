<?php
class Unit_model extends CI_Model 
{

	
	public function insertunit($dataform)
	{
		$this->db->insert("unit", $dataform);
    $insert_id = $this->db->insert_id();
    //print_r($insert_id);exit();

   return  $insert_id;
	}
  public function insertUnitchangehistory($dataform)
  {
    $this->db->insert("User_Unit_Designation_history", $dataform);
    $insert_id = $this->db->insert_id();
  }
  public function findAllUnits()
  {
    $this->db->select('*');
    $this->db->from('user_unit');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function findUnits(){
    $this->db->select('*');
    $this->db->from('unit');
    $this->db->where('parent_unit', 0);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }
  public function findUsersOfUnselectedUnit($units,$login_user_id){
    $this->db->select('user_unit.user_id,unit.id as unit_id,unit.unit_shortname,personal_details.fname,personal_details.lname');
    $this->db->from('user_unit');
    $this->db->join('unit', 'user_unit.unit_id = unit.id');
    $this->db->join('users', 'user_unit.user_id = users.id');
    $this->db->join('personal_details', 'user_unit.user_id = personal_details.user_id');
    $this->db->where('personal_details.user_id != ', 1);
    $this->db->where('users.status', 1);
    $this->db->where('personal_details.user_id != ', $login_user_id);
    $this->db->where_in('unit_id', $units);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function insertMaxLeave($datahome)
  {
    $this->db->insert("unit_designation_maxleave", $datahome);
  }

  public function findMaxLeave($new_id)
  {
        $this->db->select('*');
        $this->db->from('unit_designation_maxleave');
        $this->db->where('unit_id', $new_id);
        $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
  }

 public function findunitidofuser($user_id)
 {      
         $this->db->select('unit_id'); 
         $this->db->from('user_unit');
         $this->db->where('user_id', $user_id);
         $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

 }

 public function findsubunits($id)
 {
         $this->db->select('id'); 
         $this->db->from('unit');
         $this->db->where('parent_unit', $id);
         $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;  

 }

     // public function deleteunit($id)
     //  {  

     //    $this->db->delete('unit_designation_maxleave', array('unit_id' => $id));
     //    $this->db->delete('unit', array('id' => $id));
     //    return 1;
         
     //  }

       public function deleteunit($id)
      {

        $status=array('status'=>3);
        $this->db->where('id', $id);
        $status=$this->db->update('unit',$status);
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
      public function findunitbystatus($status)
      {
        $this->db->select('*');
        $this->db->from('unit');
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

      public function deleteMaxleave($id)
      {
        $this->db->delete('unit_designation_maxleave', array('unit_id' => $id));
      }
      public function UnitByUser($id)
      {
        $this->db->select('*');
        $this->db->from('user_unit');
        $this->db->where('unit_id', $id);
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function findMaxleaveByDesignation($id,$designation,$annual_allowance_type)
      {
        if($annual_allowance_type==1)
          { $this->db->select('max_leave'); }
          else
          { $this->db->select('max_leave_hour');}
        $this->db->from('unit_designation_maxleave');
        $this->db->join('user_unit', 'user_unit.unit_id = unit_designation_maxleave.unit_id');
        $this->db->join('users', 'users.id = user_unit.user_id');
        $this->db->where('users.id', $id);
        $this->db->where('unit_designation_maxleave.designation_id', $designation);
        $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public  function SelectDesignationCode($user_type)
      {
        $this->db->select('designation_id,annual_allowance_type');
        $this->db->from('users'); 
        $this->db->where('id', $user_type);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function getUnitDesignation($id)
      {
        $this->db->select('*');
        $this->db->from('unit_designation_maxleave');
        $this->db->join('master_designation', 'master_designation.id = unit_designation_maxleave.designation_id');
        $this->db->where('unit_id', $id);
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

      }

      public function findunit($id)
      {
      	$this->db->select('*');
    		$this->db->from('unit');
    		$this->db->where('id', $id);
    		$query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function findUnitBranch($unit_id)
      {
        $this->db->select('id,unit_shortname');
        $this->db->from('unit');
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

      }

      public function getShiftsUnitShortname($id)
      { 
          $this->db->select('*');
          $this->db->from('unit');
          $this->db->where('id', $id);
          $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function findShiftsUnitShortname($id)
      { 
          $this->db->select('unit_shortname');
          $this->db->from('unit');
          $this->db->where('id', $id);
          $query = $this->db->get();
          // if($uid==604){
          //               echo $this->db->last_query();exit;
 
          // }
         $result = $query->result_array();
         return $result;
      }


      public function findunitforRota()
      {
          $this->db->select('*');
          $this->db->from('unit');
          $this->db->where('status', 1);
          $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
      public function getallunits()
      {
          $this->db->select('id,unit_name');
          $this->db->from('unit');
          $this->db->where('status', 1);
          $query = $this->db->get();
          // echo $this->db->last_query();exit;
          $result = $query->result_array();
          return $result;
      }

      public function getUnitcolor($unitid)
      {
        $this->db->select('color_unit');
        $this->db->from('unit');
        $this->db->where('id', $unitid);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;  return $query->result_array();
        return $query->row_array();
        


      }
    
      public function update($id,$dataform){
         
         $this->db->where('id', $id);
         $this->db->update('unit',$dataform);

         }

      // public function updateMaxLeave($id,$datahome)
      // {  
      //   //print_r($datahome);exit();
      //    $this->db->update('unit_designation_maxleave',$datahome);
      //    $this->db->where('unit_id', $id);
      // }

         
    public function allunitstypes()
	{
		$this->db->select('*');
		$this->db->from('unit_type');
    $this->db->where('status', 1);
		$query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
	}

  public function SelectParentUnit()
  {
    $this->db->select('*');
    $this->db->from('unit');
    $this->db->where('parent_unit', 0);
    $this->db->where('id!=', 1);
    $query = $this->db->get();
        // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }

   public function getunitDetails($params)
      {
          $this->db->select('*');
          $this->db->from('unit');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['unit_name'])
               $this->db->where('unit_name', $params['unit_name']);
          
          $query = $this->db->get();
         // echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
      }
    

 

   public function listallunitstypes1()
   {

      $this->db->select('*');
      $this->db->from('unit_type'); 
      $this->db->join('unit', 'unit.unit_type = unit_type.id');
      $this->db->where('unit.status', 1); 
      $query = $this->db->get();
       //print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }  

   public function getstaffsbyunitShift($shift, $unitid)
   {   //print_r($start);
    //print_r($end);exit();
        // $this->db->select('unit.id');
        // $this->db->from('unit');
        // $this->db->where('parent_unit', $unitid);
        // $query = $this->db->get();
        //  //echo $this->db->last_query();exit();
        // $result = $query->result_array();
        //print_r($result[0]['parent_unit']);exit();


       $this->db->select('*');
       $this->db->from('users');
       $this->db->join('personal_details', 'personal_details.user_id = users.id');
       $this->db->join('user_unit', 'user_unit.user_id = users.id');
       $this->db->join('unit', 'unit.id = user_unit.unit_id');
       $this->db->join('master_designation', 'master_designation.id = users.designation_id');
       $this->db->join('master_shift', 'master_shift.id = users.default_shift');
       $this->db->where('users.default_shift', $shift);
       $this->db->where('users.id !=1');
       $this->db->where('users.status =1');

       // if(!empty($result))
       // {
       //  $this->db->group_start();
       //  $this->db->where('user_unit.unit_id', $unitid);
       //  foreach ($result as $value) { 
       //    $this->db->or_where('user_unit.unit_id', $value['id']);
       //  } 
       //  $this->db->group_end();
       // }
       // else
       // {
        $this->db->where('user_unit.unit_id', $unitid);
       // }
      
       $query = $this->db->get();
      //echo $this->db->last_query();exit();
       $result = $query->result_array();
       return $result;
       
   }

   public function checkrotabyunitStaff($user_id,$start,$end)
   {

       $this->db->select('*');
       $this->db->from('rota_schedule');
       $this->db->where('user_id', $user_id);
       $this->db->where('date >=', $start);
       $this->db->where('date <=', $end);
       // }
      
       $query = $this->db->get();
       //echo $this->db->last_query();exit();
       $result = $query->result_array();
       if(empty($result))
       {
        $status=0;
       }
       else
       {
        $status=1;
       }
       return $status;
       

   }
  public function returnUnitsIds($user_unit_id){
    $unit_ids = array();
    array_push($unit_ids, $user_unit_id);
    $this->db->select('*');
    $this->db->from('unit'); 
    $this->db->where('parent_unit', $user_unit_id);
    $query1 = $this->db->get();
    $sub_units = $query1->result_array();
    for($i=0;$i<count($sub_units);$i++){
      array_push($unit_ids, $sub_units[$i]['id']);
    }
    return array_unique($unit_ids);
  }
  public function returnMainAndSubUnitsIds($user_unit_id){
    $unit_ids = array();
    array_push($unit_ids, $user_unit_id);
    $this->db->select('*');
    $this->db->from('unit'); 
    $this->db->where('id', $user_unit_id);
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0){
      if($result[0]['parent_unit'] != 0){
        $parent_unit = $result[0]['parent_unit'];
        array_push($unit_ids, $result[0]['parent_unit']);
        $this->db->select('*');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $parent_unit);
        $query1 = $this->db->get();
        $sub_units = $query1->result_array();
        for($i=0;$i<count($sub_units);$i++){
          array_push($unit_ids, $sub_units[$i]['id']);
        }
      }else{
        $this->db->select('*');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $user_unit_id);
        $query1 = $this->db->get();
        $sub_units = $query1->result_array();
        for($i=0;$i<count($sub_units);$i++){
          array_push($unit_ids, $sub_units[$i]['id']);
        }
      }
    }
    return array_unique($unit_ids);
  }
  public function deleteShiftsbyunitandsubunit($user_id,$unit_ids,$date)
  {
     $this->db->where('user_id', $user_id);
     $this->db->where('date', $date);
     $this->db->where('unit_id', $unit_ids);
     $this->db->delete('rota_schedule');
     return;
     // print $this->db->last_query();
     // exit();
  }
public function returnParentUnit($unit){
  $parent_unit = $unit;
  $this->db->select('*');
  $this->db->from('unit'); 
  $this->db->where('id', $unit);
  $query = $this->db->get();
  // echo $this->db->last_query();exit;
  $result = $query->result_array();
  if(count($result) > 0){
    if($result[0]['parent_unit'] != 0){
      $parent_unit = $result[0]['parent_unit'];
    }
  }
  return $parent_unit;
}
  public function findBranch($unit)
  {
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
  }

   public function findUsersUnit($id)
   {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('user_unit', 'user_unit.user_id = users.id');
        $this->db->where('unit_id', $id);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
   }

   public function getPreviousparent($id)
   { //print_r($id);
        $this->db->select('parent_unit');
        $this->db->from('unit'); 
        $this->db->where('id', $id);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        $result = $query->result_array();
        //print_r($result[0]['parent_unit']);exit();
        return $result[0]['parent_unit']; 
   }

   public function getUserUnitDetails($id)
   {
        $this->db->select('*');
        $this->db->from('user_unit');
        $this->db->join('users', 'user_unit.user_id = users.id');
        $this->db->join('personal_details', 'user_unit.user_id = personal_details.user_id');
        $this->db->join('master_shift', 'master_shift.id = users.default_shift');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        $this->db->join('unit', 'user_unit.unit_id = unit.id');
        $this->db->where('user_unit.user_id', $id);
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
        $result = $query->result_array();
        if($query->num_rows() > 0){
          $result = $query->result_array();
          return $result;
        }else{
          return array();
        }    
   }
  public function checkIsSubUnit($id){
    $this->db->select('*');
    $this->db->from('unit');
    $this->db->where('id', $id);
    $query = $this->db->get();
    $result = $query->result_array();
    if($result[0]['parent_unit'] == 0){
      return 0;
    }else{
      return 1;
    }
  }
   
}

?>