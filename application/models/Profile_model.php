<?php
class Profile_model extends CI_Model 
{

   public function findusers($id)
   {

      $result = array();
          $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.password',
            'users.group_id',
            'users.designation_id',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.payroll_id',
            'users.start_date',
            'users.final_date',
            'users.notes',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.gender',
            'personal_details.address1',
            'personal_details.address2',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'personal_details.Ethnicity',
            'Ethnicity.id as ethnic_id',
            'personal_details.profile_image',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address', 
            'master_payment_type.payment_type',
            'master_designation.designation_name');
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type'); 
      $this->db->join('Ethnicity', 'Ethnicity.Ethnic_group = personal_details.Ethnicity','left'); 
      $this->db->where('users.id', $id); 
      $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }

   public function getEthnicity($id)
   {
       $this->db->select('Ethnic_group,other_status');
       $this->db->from('Ethnicity'); 
       $this->db->where('id', $id);
       $query=$this->db->get();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      if(count($result)>0)
      {
          return $result[0];
      }
      else
      {
          return null;
      }
      

   }

   public function findunitdetails($id)
{
       $this->db->select('*');
       $this->db->from('unit');
       $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
       $this->db->where('user_id', $id);
       $query=$this->db->get();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      return $result;
}

// public function findAdminmobilenum($id)
// {  
//        $this->db->select('mobile_number');
//        $this->db->from('personal_details');  
//        $this->db->where('user_id', 1);
//        $query=$this->db->get(); 
//        $result = $query->result_array(); 
//        //print_r($result);exit();
//        if($result[0]['mobile_number']=='')
//        {

//            $this->db->select('unit.id');
//            $this->db->from('unit');
//            $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
//            $this->db->where('user_unit.user_id', $id);
//            $query=$this->db->get();
//           $result1 = $query->result_array(); 
//            //print_r($result1);exit();
//           if(!empty($result1))
//           {
//            $this->db->select('personal_details.mobile_number');
//            $this->db->from('personal_details');
//            $this->db->join('users', 'users.id = personal_details.user_id','left');
//            $this->db->join('user_unit', 'user_unit.user_id = users.id');
//            $this->db->where('user_unit.unit_id', $result1[0]['id']);
//            $this->db->where('users.group_id',3);
//            $query=$this->db->get();
//            $results = $query->result_array(); 
//            $result=$results[0]['mobile_number'];
//           }
//         }
//         else
//         {
//           $result=$result[0]['mobile_number'];
//         }
//         //print_r($result);exit();
//       return $result;

// }

// public function findAdminemail($id)
// {
//   $this->db->select('unit.id');
//   $this->db->from('unit');
//   $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
//   $this->db->where('user_unit.user_id', $id);
//   $query=$this->db->get();
//   $result1 = $query->result_array(); 

//   if($result1)
//            $this->db->select('users.email');
//            $this->db->from('users'); 
//            $this->db->join('user_unit', 'user_unit.user_id = users.id');
//            $this->db->where('user_unit.unit_id', $result1[0]['id']);
//            $this->db->where('users.group_id >=',3);
//            $query=$this->db->get(); 
//            $results = $query->result_array(); 
//            //print_r($results);exit();
//    $result=$results[0]['email'];       
//    return $result;

// }

    public function getAdminUsers($unit_id)
    { //print_r($date);exit();

      if($unit_id>0) //checking parent unit
       {
          $this->db->select('parent_unit');
          $this->db->from('unit'); 
          $this->db->where('id', $unit_id);
          $query = $this->db->get();
          $results = $query->result_array(); 
       }

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
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
    }
    public function findAdminDetails()
    {
      $this->db->select('personal_details.mobile_number,users.email,personal_details.fname,personal_details.lname');
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->where('users.id', 1);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
    }

public function finduserdetails($id,$profile_image)
{
  if($profile_image!='')
  {
        $data = array(
            
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.address1',
            'personal_details.address2',
            'personal_details.country',
            'personal_details.city',
            'personal_details.postcode',
            'personal_details.profile_image',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address',
            'users.email');
  }
  else 
  { 
        $data = array( 
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number',
            'personal_details.address1',
            'personal_details.address2',
            'personal_details.country',
            'personal_details.city',
            'personal_details.postcode', 
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address',
            'users.email');

  }
       $this->db->select($data);
       $this->db->from('personal_details'); 
       $this->db->join('users', 'users.id = personal_details.user_id');  
       $this->db->where('user_id', $id);
       $query=$this->db->get();
       // print $this->db->last_query();
       // exit();
       $result = $query->result_array(); 
       return $result;
}

public function findhistory($id)
{
       $this->db->select('*');
       $this->db->from('history_staff_address'); 
       $this->db->where('user_id', $id);
       $query=$this->db->get();
       // print $this->db->last_query();
       // exit();
       $result = $query->result_array(); 
       return $result;

}

public function userupdate($id,$datahome)
{
 //print_r($datahome); print_r("<br>"); 
	$this->db->where('user_id', $id);
  $this->db->update('personal_details', $datahome);
  //print $this->db->last_query(); exit();
      // exit();
}

public function updatemanagerdatas($id,$dataformuser)
{
	$this->db->where('id', $id);
  $this->db->update('users', $dataformuser);

}
public function updatestaffdatas($id,$dataformuser)
{
  $this->db->where('id', $id);
  $this->db->update('users', $dataformuser);

}

 public function getuserDetails($params)
      {
          $this->db->select('*');
          $this->db->from('users');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['email'])
               $this->db->where('email', $params['email']);
             $this->db->where('status', 1);
          
          $query = $this->db->get();
          //echo $this->db->last_query();exit;
          $result = $query->result_array();
        //print_r($result);exit();
          return $result;
      }
     

public function changepassword($id,$dataform)
{

$this->db->where('id',$id);
$this->db->update('users', $dataform);


}

}

?>