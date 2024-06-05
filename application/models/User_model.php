<?php
class User_model extends CI_Model 
{

  public function alluser()
  {
    $this->db->select('*');
    $this->db->from('users');
    $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
  } 
  public function updatelastlogin($id){
      $data = array(
          'lastlogin_date' => date('Y-m-d H:i:s'),
      );
      $this->db->where('id', $id);
      $this->db->update('users', $data);
      return $randString;
      
  }
  public function ger_permission_data(){
    $this->db->select('*');
    $this->db->from('employee_permissions');
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function getvalueforcss($user_id)
  {
    $this->db->select('users.user_size_session');
    $this->db->from('users');
    $this->db->where('id', $user_id);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
         $result = $query->result_array();
         //print_r($result);exit();
         return $result[0]['user_size_session'];
  }
  public function insertcssvalue($css_data,$user_id)
  { 
    $data=array('user_size_session'=>$css_data);
      $this->db->where('id', $user_id);
      $this->db->update('users', $data);
      //echo $this->db->last_query();exit;
      return;
  }
  public function edit_employee_permission($id,$action){
    $data=array('status'=>$action);
    $this->db->where('id', $id);
    $this->db->update('employee_permissions', $data);
    return;
  }
  public function getDesignationCount($id,$users){
    $user_ids = array();
    for($i=0;$i<count($users);$i++){
      array_push($user_ids, $users[$i]['user_id']);
    }
    $this->db->select('users.email');
    $this->db->from('users');
    $this->db->where('designation_id',$id);
    $this->db->where_in('id',$user_ids);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function finduseremail($user_id)
  {
      $this->db->select('id');
      $this->db->from('users'); 
      //$this->db->where('users.status', 2);
      $this->db->where('id',$user_id); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;

  }
  public function finduserdetails($email)
  {
    //print_r($email);
    $this->db->select('*');
      $this->db->from('users'); 
      //$this->db->where('users.status', 2);
      $this->db->where('email',$email); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
  }
  public function findUserStatus($user_id){
    $this->db->select('users.status');
    $this->db->from('users');
    $this->db->where('users.id',$user_id); 
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function finduserDetailsWithId($id)
  {
    //print_r($email);
    $this->db->select('*');
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id'); 
      //$this->db->where('users.status', 2);
      $this->db->where('users.id',$id); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
  }
  public function checkUserIsSupervisor($id){
    $this->db->select('group_id');
    $this->db->from('users');
    $this->db->where('id',$id);
    $query = $this->db->get();
    $result = $query->result_array();
    if($result[0]['group_id'] == 5){
      return 'true';
    }else{
      return 'false';
    }
  }
  public function finduserDesignation($id){
    $this->db->select('group_id');
    $this->db->from('users');
    $this->db->where('id',$id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['group_id'];
  }
  public function CheckuserUnit($user_id)
  {   
      $this->db->select('parent_unit');
      $this->db->from('unit'); 
      $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
      //$this->db->where('users.status', 2);
      $this->db->where('user_unit.user_id',$user_id); 
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array(); 
      if(empty($result[0]['parent_unit']) || $result[0]['parent_unit']==0)
      {
        $results=0;
      }
      else
      {
        $results=$result[0]['parent_unit'];
      } 
      return $results;
  }
  public function Checkparent($user_id)
  {
      $this->db->select('id');
      $this->db->from('unit');   
      $this->db->where('parent_unit',$user_id); 
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      if(empty($result))
      {
        $results=0;
      }
      else
      {
        $results=$user_id;
      }  
      return $results;

  }

  public function updatestatus1($id)
  {
    $data = array(
          'status' => 1,
      );
      $this->db->where('id', $id);
      $this->db->update('users', $data);
      return 1;

  }
  public function checkStat($id)
  {
      $this->db->select('*');
      $this->db->from('users'); 
      //$this->db->where('users.status', 2);
      $this->db->where('id',$id); 
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      // print_r($result);exit();
      if(!empty($result))
      {
        $status=1;
      }
      else
      {
        $status=0;
      }
      return $status;
  }
 

 public function getUserDetailsforHistory($user_id)
  {
     $data=array(
      'users.group_id',
      'users.weekly_hours',
      'users.status',
      'users.annual_holliday_allowance',
      'users.actual_holiday_allowance',
      'users.designation_id',
      'users.start_date',
      'users.final_date',
      'users.notes',
      'users.payroll_id',
      'users.default_shift',
      'users.payment_type',
      'users.exit_interview',
      'users.exit_reason',
      'users.hr_ID'


     );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->where('users.id', $user_id);
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result[0];

  }


  public function getPersonalDetails($user_id)
  {
     $data=array(
      'users.email',
      'personal_details.fname',
      'personal_details.lname',
      'personal_details.mobile_number',
      'personal_details.dob',
      'personal_details.gender',
      'personal_details.Ethnicity',
      'personal_details.visa_status',
      'personal_details.kin_name',
      'personal_details.kin_phone',
      'personal_details.kin_address'
     );
      $this->db->select($data);
      $this->db->from('personal_details'); 
      $this->db->join('users', 'users.id = personal_details.user_id');
      $this->db->where('personal_details.user_id', $user_id);
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result[0];

  }
    public function findemails()
    {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      //$this->db->join('unit', 'unit.id = user_unit.unit_id');
      //$this->db->where('users.status', 2);
      $this->db->where('email!=', 'No email');
      $this->db->where('users.status', 1);
      //$this->db->where('user_unit.unit_id', 3);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
    
    }


    public function check_email_status($user_id)
    {
      $this->db->select('*');
      $this->db->from('user_email_send');
      $this->db->where('user_id', $user_id);
      $this->db->where('mail_send_status', 1); 
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      if(empty($result))
      {
          return 'true';
      }
      else
      {
          return 'false';
      }
    
      
    }


public function getUsersThmbnails($params =array())
{
    
 
    $this->db->select('user_unit.unit_id,users.id as user_id,users.payroll_id,users.thumbnail,
personal_details.fname,personal_details.lname,users.pass_enable,users.app_pass' );
    $this->db->from('users');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->join('user_unit', 'user_unit.user_id = users.id');
    $this->db->where('users.status', 1);
    
    if(count($params['users'])>0){
    $this->db->where_not_in('users.id', $params['users']);
    }
    $this->db->where('users.thumbnail !=', '');
     $query = $this->db->get();
     
 //echo $this->db->last_query();
  //exit;
    $result = $query->result_array();
 //  print_r($result);exit;
    return $result;
    
}
public function getuserIdbyName($fname, $lname)
{
    $this->db->select('users.id');
    $this->db->from('users');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
     $this->db->where('personal_details.fname', $fname);
    $this->db->where('personal_details.lname', $lname);
    $query = $this->db->get();
   // echo $this->db->last_query(); exit;
    $result = $query->result_array();
    return $result;
    
}
public function getuserIdbyPayrolid($payroll_id, $unit)
{
    $this->db->select('users.id');
    $this->db->from('users');
     $this->db->join('user_unit', 'user_unit.user_id = users.id');
    
    $this->db->where('users.payroll_id', $payroll_id);
    $this->db->where('user_unit.unit_id', $unit);
    $query = $this->db->get();
    // echo $this->db->last_query();  
    $result = $query->result_array();
    return $result;
    
}
  public function getAgencyStaffs()
  {
    $this->db->select(
      'unit.id,
      user_unit.user_id,
      user_unit.unit_id,
      personal_details.user_id,
      personal_details.fname,
      personal_details.lname'
    );
    $this->db->from('unit');
    $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
    $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id');
    $this->db->where('unit.unit_shortname', "AS");
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }    
  }
  public function getSingleUser($id){
    $data = array(
        'users.id','users.pass_enable','users.app_pass',
      'users.email','users.thumbnail',
      'personal_details.fname',
      'personal_details.lname',
      'personal_details.mobile_number',
      'personal_details.profile_image',
      'users.designation_id',
      'master_designation.designation_name',
      'master_designation.designation_code',
      'user_unit.unit_id',
      'users.weekly_hours'
    );
    $this->db->select($data);
    $this->db->from('users');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->join('master_designation', 'users.designation_id = master_designation.id');
    $this->db->join('user_unit', 'user_unit.user_id = users.id');
    $this->db->where('users.id', $id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function getSingleUserThumb($id){
      $data = array(
          'users.thumbnail'
      );
      $this->db->select($data);
      $this->db->from('users');

      $this->db->where('users.id', $id);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
  }
  public function changepicture($user_id,$datahome)
  {
     $this->db->where('user_id', $user_id);
     $result=$this->db->update('personal_details',$datahome);
     if($result==1)
     {
      return 'true';
     }
     else
     {
      return 'false';
     }
  }
  public function getSortOrder($id){
    $data = array(
      'users.id',
      'personal_details.gender',
      //Edited by chinchu. The nurse_count field is now taking for
      //taking the count nurse day and night count
      'master_designation.nurse_count as sort_order'
    );
    $this->db->select($data);
    $this->db->from('users');
    $this->db->join('master_designation', 'master_designation.id = users.designation_id');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->where('users.id', $id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
     public function deletedesignation($id)
      {

         $this->db->delete('master_designation', array('id' => $id));
         $this->db->delete('users', array('designation_id' => $id)); 
         
         return true;
      }

      public function finddesignation()
      {
        $this->db->select('*');
        $this->db->from('master_designation');
        $this->db->where('status', 1);
        $this->db->order_by('designation_name','asc');
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function findaddress($user_id)
      {
        $this->db->select('address1');
        $this->db->from('personal_details');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

      public function getUnitIdOfUser($id)
      {
        $this->db->select('unit_id');
        $this->db->from('user_unit');  
        $this->db->where('user_id', $id);
       
        $query = $this->db->get();
         //echo $this->db->last_query(); exit;
        $result = $query->result_array();
        if(count($result) > 0){
          return $result[0]['unit_id'];
        }else{
          return '';
        }
        



      }
      public function findunitofuser($id)
      {
        $this->db->select('unit_id');
        $this->db->from('user_unit');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
      }

      public function findRatesByUser($user_id)
      {
        $this->db->select('day_rate,night_rate,day_saturday_rate,day_sunday_rate,weekend_night_rate');
        $this->db->from('user_rates');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
      public function findWeeklyHoursByUser($user_id)
      {
        $this->db->select('weekly_hours');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
      public function getUserworkschedule($user_id)
      {
        $data=array(

              'sunday',
              'monday',
              'tuesday',
              'wednesday',
              'thursday',
              'friday',
              'saturdy as saturday'

        );

        $this->db->select($data);
        $this->db->from('workschedule');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result[0];


      }

      public function insertAddresshistory($datas)
      {
        $this->db->insert("history_staff_address", $datas);
      }

      public function insertuseredithistory($datas)
      {
        $this->db->insert("user_edit_activitylog", $datas);
        //echo $this->db->last_query();exit;
      }
      

    
      public function insertlogindata($params)
      {
         $this->db->insert("login_log", $params);
      }

      public function inserthistoryuserRates($rates)
      {
        $this->db->insert("history_user_rates", $rates);
      }

       public function inserthistoryweeklyHours($w_working_hours_history)
      {
        $this->db->insert("history_weekly_hours", $w_working_hours_history);
      } 
      public function fetchCategoryTreeforavailability($parent_id = 0, $spacing = '', $user_tree_array = '',$unittype) {  
 
      if (!is_array($user_tree_array))
        $user_tree_array = array();
      $this->db->where('parent_unit', $parent_id);
      $this->db->where('unit_type', $unittype);
      $this->db->where('id !=', 1);
      $this->db->where('status', 1);  
      $query = $this->db->get('unit'); 
      $result = $query->result(); 
      foreach ($result as $mainCategory) {
        $user_tree_array[] = array("id" => $mainCategory->id, "unit_name" => $spacing . $mainCategory->unit_name,"parent_unit" =>$mainCategory->parent_unit);
        
          $user_tree_array = $this->fetchCategoryTree($mainCategory->id, '--' . '  ', $user_tree_array);
      }
      return $user_tree_array;
    }

    
     public function fetchCategoryTree($parent_id = 0, $spacing = '', $user_tree_array = '') {
 
      if (!is_array($user_tree_array))
        $user_tree_array = array();

      $this->db->where('parent_unit', $parent_id);
      $this->db->where('id !=', 1);
      $this->db->where('status', 1);  
      $query = $this->db->get('unit'); 
      $result = $query->result(); 
      foreach ($result as $mainCategory) {
        $user_tree_array[] = array("id" => $mainCategory->id, "unit_name" => $spacing . $mainCategory->unit_name,"parent_unit" =>$mainCategory->parent_unit);
        
          $user_tree_array = $this->fetchCategoryTree($mainCategory->id, '--' . '  ', $user_tree_array);
      }
      return $user_tree_array;
    }

     public function fetchSubTree($parent_id, $spacing = '', $user_tree_array = '') {  
 
      if (!is_array($user_tree_array))
        $user_tree_array = array();
      $this->db->group_start();
      $this->db->where('parent_unit', $parent_id);
      $this->db->or_where('id', $parent_id);
      $this->db->group_end();
      $this->db->where('status', 1);  
      $query = $this->db->get('unit');
     // echo $this->db->last_query();exit;
      $result = $query->result(); 
      foreach ($result as $mainCategory) {
        if($mainCategory->parent_unit==0)
        {
          $user_tree_array[] = array("id" => $mainCategory->id, "unit_name" => $spacing . $mainCategory->unit_name,"parent_unit" =>$mainCategory->parent_unit);
        }
        else
        {
          $user_tree_array[] = array("id" => $mainCategory->id, "unit_name" => '--' .$spacing . $mainCategory->unit_name,"parent_unit" =>$mainCategory->parent_unit);

        }
      }    
      return $user_tree_array;
    }
    public function fetchunit()
    {
        $this->db->select('*');
        $this->db->from('unit');
        $this->db->where('parent_unit', 0);  
        $this->db->where('id!=', 1); 
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }

   public function findparent($parent)
   {
        $this->db->select('parent_unit');
        $this->db->from('unit');
        $this->db->where('parent_unit', $parent);  
     
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
   }
      public function findunit($unit_id)
      {
        //finding sub units
        if($unit_id){
        $this->db->select('unit.id');
        $this->db->from('unit');
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array(); 
        }

        $this->db->select('*');
        $this->db->from('unit');
        $this->db->where('status', 1); 
        if($unit_id)
        {
            if(!empty($result))
           {
            $this->db->group_start();
            $this->db->where('id !=', $unit_id);
            foreach ($result as $value) { 
              $this->db->where('id!=', $value['id']);
            } 
            $this->db->group_end();
           }
           else
           {
            $this->db->where('id !=', $unit_id); 
           }   
        }
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }

   
        public function findunitwithoutAgency($parent_id = 0, $spacing = '', $user_tree_array = '') {
 
      if (!is_array($user_tree_array))
        $user_tree_array = array();

      $this->db->where('parent_unit', $parent_id);
      $this->db->where('status', 1); 
      $this->db->where('id!=', 1);   
      $query = $this->db->get('unit');
      $result = $query->result();
      foreach ($result as $mainCategory) {
        $user_tree_array[] = array("id" => $mainCategory->id, "unit_name" => $spacing . $mainCategory->unit_name);
          $user_tree_array = $this->fetchCategoryTree($mainCategory->id, '--' . '  ', $user_tree_array);
      }
      //print_r($user_tree_array);exit();
      return $user_tree_array;
    } 

      public function findpayment()
      {
      $this->db->select('*');
      $this->db->from('master_payment_type');
      $this->db->where('status', 1);
      $query = $this->db->get();
       // echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
      }

      public function findunitbyusersofmanager($id)
      {
      $data=array(
        'unit.id',
        'unit.unit_name',
        'unit.parent_unit',
        'unit.unit_shortname');
      $this->db->select($data);
      $this->db->from('unit');
      $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
      $this->db->where('user_unit.user_id',$id);
      $this->db->where('unit.status', 1);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;

      }

      public function findunitforadmin($id)
      {                             
    // print_r($id);exit();
      if($id>0)    //finding sub units
      {
            $this->db->select('id');
            $this->db->from('unit'); 
            $this->db->where('parent_unit', $id);
            $query = $this->db->get();
            //echo $this->db->last_query();exit;
            $results = $query->result_array();
      }
//print_r($results);exit();
          $this->db->select('*');
          $this->db->from('unit');
          $this->db->where('unit.status', 1);
          if(empty($results)) //if no subunits
          {
             $this->db->where('unit.id',$id);
          }
          else
          {  //if subunits
                  $this->db->group_start();
                  $this->db->where('unit.id',$id);
                  foreach ($results as $value) 
                  {  
                     $this->db->or_where('unit.id',$value['id']);
                  }
                  $this->db->group_end();

          }
         
          $query = $this->db->get();
          //echo $this->db->last_query();exit;
          $result1 = $query->result_array();
          return $result1;


      }

      public function findalluserbyadmin($id)
      {
        //print_r($id);exit();
         if($id>0)
         {
            $this->db->select('id');
            $this->db->from('unit'); 
            $this->db->where('parent_unit', $id);
            $query = $this->db->get();
            //echo $this->db->last_query();exit;
            $results = $query->result_array();
            //print_r($results);exit();
         }
      //print_r($results);exit();
          $result = array();
          $data = array(
            'users.id',
            'users.email',
            'users.payroll_id',
            'users.group_id',
            'users.status',
            'users.lastlogin_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.visa_status',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_designation.designation_code',
            'unit.unit_name',
             );
          $this->db->select($data);
          $this->db->from('users'); 
          $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
          $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
          $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
          $this->db->join('master_group', 'master_group.id = users.group_id','left');
          $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
          $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
          $this->db->where('users.status', 1);
          if($id>0)
              {  
                if(!empty($results))
                {  
                  $this->db->group_start();
                  $this->db->where('user_unit.unit_id',$id);
                  foreach ($results as $value) 
                  {  
                     $this->db->or_where('user_unit.unit_id',$value['id']);
                  }
                  $this->db->group_end();
                  
                }
                else
                {  
                   $this->db->where('user_unit.unit_id',$id);
                }
              }
 
          $query = $this->db->get();

          //  print $this->db->last_query();
          // exit();
          $result = $query->result_array();
          return $result;
      }

      public function findshift()
      {
      $this->db->select('*');
      $this->db->from('master_shift');
      $this->db->where('status', 1);
      $this->db->order_by('shift_shortcut', 'asc');  //changed on may 28
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;

      }

      public function findunitname($units)
      {
         $this->db->select('unit_name');
    $this->db->from('unit');
    $this->db->where('id', $units);
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
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

  
      public function findgroups()
      {
        $this->db->select('*');
        $this->db->from('master_group');
        $this->db->where('status', 1);
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;


      }

      public function findgroupswithoutadmin()
      {
        $this->db->select('*');
        $this->db->from('master_group');
        $this->db->where('status', 1);
        $this->db->where('id !=', 1);
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

      }

       public function getuserDetails($params)
      {
          $this->db->select('*');
          $this->db->from('users');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['email'])
               $this->db->where('email', $params['email']);
          
          $query = $this->db->get();
          //echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
      }

    public function getuserDetailsforPasswordchange($id)
    {
          $this->db->select('users.email,personal_details.fname,personal_details.lname');
          $this->db->from('users');
          $this->db->join('personal_details', 'personal_details.user_id = users.id');
          $this->db->where('users.id',$id);  
          $query = $this->db->get();
           //echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
    }
    

      public function update($id,$datahome){
         
         $this->db->where('id', $id);
         $this->db->update('master_designation',$datahome);
         }
         public function updatedefaultshift($userid,$data){
             
             $this->db->where('id', $userid);
             $statusupdate = $this->db->update('users',$data);
             //echo $this->db->last_query();
             return $statusupdate;
         }

      public function updateimporteddata($params = array()){   
       //print_r($params);exit(); 
        $current = date('Y-m-d H:i:s');
        $this->email = $params['email'];
        //$this->password = $params['password'];
        $this->group_id=$params['group_id'];
        $this->designation_id = $params['designation'];
        $this->payment_type = $params['payment_type']; 
        $this->status = $params['status'];
        $this->weekly_hours= $params['weekly_hours'];
        $this->annual_holliday_allowance=$params['annual_holliday_allowance'];
        $this->start_date= $params['start_date'];
        $this->final_date= $params['final_date'];
        $this->payroll_id=$params['payroll_id'];
        $this->default_shift=$params['default_shift'];
        $this->annual_allowance_type= $params['annual_allowance_type'];
        $this->actual_holiday_allowance=$params['actual_holliday_allowance'];
        $this->actual_holiday_allowance_type= $params['annual_allowance_type'];
        //$this->creation_date = $current;
        $this->updation_date = $current;
       
        $this->db->where('id', $params['user_id']);
        $result=$this->db->update('users',$this);
        if($result!=1)
        { 
          $status=$params['user_id'].'_'.'users_table';
          //print_r($status);exit();
        }
        else
        {
          $status=$params['user_id'].'_'.'users_updated';
        }
        
        // $this->db->insert("users", $this);
        // $user_id = $this->db->insert_id(); 
//print_r($params['user_id']);exit();
        if($params['user_id'])
        {
          //print_r($params);exit();
          //$data['user_id'] = $params['user_id'];
          $data['fname'] = $params['firstname'];
          $data['lname'] = $params['lastname'];
          $data['gender'] = $params['gender'];  
          $data['mobile_number'] = $params['mobile_number'];  
          $data['telephone'] = $params['telephone']; 
          $data['NINnumbers'] = $params['NINnumbers'];  
          $data['dob'] = $params['dob']; 
          $data['address1'] = $params['address1'];   
          $data['address2'] = $params['address2'];
          $data['city'] = $params['city'];  
          $data['country'] = $params['country'];  
          $data['status'] = $params['status'];   
          $data['Ethnicity'] = $params['Ethnicity'];  
          $data['postcode'] = $params['postcode'];
          $data['kin_name'] = $params['kin_name'];    
          $data['kin_phone'] = $params['kin_phone'];  
          $data['kin_address'] = $params['kin_address']; 
          $data['kin_postcode'] = $params['kin_postcode'];  
          $data['kin_relationship'] = $params['kin_relationship'];  
          $data['kin_email'] = $params['kin_email'];  
          $data['kin_telephone'] = $params['kin_telephone'];  
          $data['bank_account'] = $params['bank_account'];  
          $data['bank_sortcode'] = $params['bank_sortcode'];  
          $data['jobcode'] = $params['jobcode'];
          $data['title'] = $params['title'];  
          $data['taxcode'] = $params['taxcode'];    
          //$data['creation_date'] = $current;
          $data['updation_date'] = $current; 
          $this->db->where('user_id', $params['user_id']);
          $result=$this->db->update('personal_details',$data);
          //print_r($result);exit();
          if($result!=1)
          { 
            $status=$params['user_id'].'_'.'personal_details';
            //print_r($status);exit();
          } 
          else
          {
            $status=$params['user_id'].'_'.'personaldetails_updated';
          }
          //print_r($params);exit();
          $user['user_id'] = $params['user_id']; 
          $user['unit_id']=$params['unit'];
          //$user['creation_date'] = $current;
          $user['updation_date'] = $current;
          $user['status'] = $params['status'];
          $this->db->where('user_id', $params['user_id']);
          $result=$this->db->update('user_unit',$user);
           //print_r($result);exit();
          if($result!=1)
          { 
            $status=$params['user_id'].'_'.'user_unit';
             //print_r($status);exit();
          } 
           else
          {
            $status=$params['user_id'].'_'.'user_unit_updated';
          }
          // $user['class'] = $params['class'];
          //$this->db->insert('user_unit', $user);
// print_r($params);exit();
          $datas['user_id'] = $params['user_id']; 
          $datas['sunday']=$params['sunday']; 
          $datas['monday']=$params['monday']; 
          $datas['tuesday']= $params['tuesday']; 
          $datas['wednesday']= $params['wednesday']; 
          $datas['thursday']= $params['thursday']; 
          $datas['friday']=$params['friday'];
          $datas['saturdy']= $params['saturday']; 
          //$datas['creation_date'] = $current;
          $datas['updation_date'] = $current;
          $this->db->where('user_id', $params['user_id']);
          $result=$this->db->update('workschedule',$datas);
           //print_r($result);exit();
          if($result!=1)
          { 
            $status=$params['user_id'].'_'.'workschedule';
              //print_r($status);exit();
          }
           else
          {
            $status=$params['user_id'].'_'.'workschedule_updated';
          } 
         // $this->db->insert('workschedule', $datas);
//print($params['day_rate']);exit();

          $rates['user_id'] = $params['user_id']; 
          $rates['day_rate']= 0.00;
          $rates['night_rate']= '0.00';
          $rates['day_saturday_rate']= '0.00';
          $rates['day_sunday_rate']= '0.00';
          $rates['weekend_night_rate']= '0.00';
          $rates['updation_date'] = $current;
          $this->db->where('user_id', $params['user_id']);
          $result=$this->db->update('user_rates',$rates);
          //print_r($result);exit();
          if($result!=1)
          { 
            $status=$params['user_id'].'_'.'user_rates';
               //print_r($status);exit();
          }
           else
          {
            $status=$params['user_id'].'_'.'user_rates_updated';
          } 
          //$this->db->insert('user_rates', $result);

        }

        return $status;
   }

        public function saveimporteddata($params = array()){ 
        
        $current = date('Y-m-d H:i:s');
        $this->email = $params['email'];
        $this->password = $params['password'];
        $this->group_id=$params['group_id'];
        $this->designation_id = $params['designation'];
        $this->payment_type = $params['payment_type']; 
        $this->status = $params['status'];
        $this->weekly_hours= $params['weekly_hours'];
        $this->annual_holliday_allowance=$params['annual_holliday_allowance'];
        $this->start_date= $params['start_date'];
        $this->final_date= $params['final_date'];
        $this->payroll_id=$params['payroll_id'];
        $this->default_shift=$params['default_shift'];
        $this->annual_allowance_type= $params['annual_allowance_type'];
        $this->actual_holiday_allowance=$params['actual_holliday_allowance'];
        $this->actual_holiday_allowance_type= $params['annual_allowance_type'];
        $this->creation_date = $current;
        //$this->updation_date = $current;
        $this->db->insert("users", $this);
        $user_id = $this->db->insert_id(); 
        if($user_id)
        {
          $data['user_id'] = $user_id;
          $data['fname'] = $params['firstname'];
          $data['lname'] = $params['lastname'];
          $data['gender'] = $params['gender'];  
          $data['mobile_number'] = $params['mobile_number'];  
          $data['telephone'] = $params['telephone']; 
          $data['NINnumbers'] = $params['NINnumbers'];  
          $data['dob'] = $params['dob']; 
          $data['address1'] = $params['address1'];   
          $data['address2'] = $params['address2'];
          $data['city'] = $params['city'];  
          $data['country'] = $params['country'];  
          $data['status'] = $params['status']; 
          $data['Ethnicity'] = $params['Ethnicity'];      
          $data['postcode'] = $params['postcode'];
          $data['kin_name'] = $params['kin_name'];    
          $data['kin_phone'] = $params['kin_phone'];  
          $data['kin_address'] = $params['kin_address']; 
          $data['kin_postcode'] = $params['kin_postcode'];  
          $data['kin_relationship'] = $params['kin_relationship'];  
          $data['kin_email'] = $params['kin_email'];  
          $data['kin_telephone'] = $params['kin_telephone'];  
          $data['bank_account'] = $params['bank_account'];  
          $data['bank_sortcode'] = $params['bank_sortcode'];  
          $data['jobcode'] = $params['jobcode'];
          $data['title'] = $params['title'];  
          $data['taxcode'] = $params['taxcode'];    
          $data['creation_date'] = $current;
          $data['updation_date'] = $current;
          $this->db->insert('personal_details', $data);
           
          $user['user_id'] = $user_id; 
          $user['unit_id']=$params['unit'];
          $user['creation_date'] = $current;
          $user['updation_date'] = $current;
          $user['status'] = $params['status'];
          // $user['class'] = $params['class'];
          $this->db->insert('user_unit', $user);

          $datas['user_id'] = $user_id; 
          $datas['sunday']=$params['sunday']; 
          $datas['monday']=$params['monday']; 
          $datas['tuesday']= $params['tuesday']; 
          $datas['wednesday']= $params['wednesday']; 
          $datas['thursday']= $params['thursday']; 
          $datas['friday']=$params['friday'];
          $datas['saturdy']= $params['saturday']; 
          $datas['creation_date'] = $current;
          $datas['updation_date'] = $current;
          $this->db->insert('workschedule', $datas);

          $rates['user_id'] = $user_id; 
          $rates['day_rate']= 0.00;
          $rates['night_rate']= 0.00;
          $rates['day_saturday_rate']= 0.00;
          $rates['day_sunday_rate']= 0.00;
          $rates['weekend_night_rate']= 0.00;
          $rates['updation_date'] = $current;
          $this->db->insert('user_rates', $rates);
          //echo $this->db->last_query();exit();
        }
        return $user_id;
   }

      public function updateworkschedule($params)
      {   //print_r($params);exit();
          $current = date('Y-m-d H:i:s');
          $datas['user_id'] = $params['user_id']; 
          $datas['sunday']=$params['sunday']; 
          $datas['monday']=$params['monday']; 
          $datas['tuesday']= $params['tuesday']; 
          $datas['wednesday']= $params['wednesday']; 
          $datas['thursday']= $params['thursday']; 
          $datas['friday']=$params['friday'];
          $datas['saturdy']= $params['saturday'];  
          $datas['updation_date'] = $current;
          $this->db->where('user_id', $params['user_id']);
          $result=$this->db->update('workschedule',$datas);
           //print_r($result);exit();
          if($result!=1)
          { 
            $status=$params['user_id'].'_'.'workschedule';
              //print_r($status);exit();
          }
           else
          {
            $status=$params['user_id'].'_'.'workschedule_updated';
          } 
          return $status;


      }



        public function save($params = array()){ 
        // insert
        // $datahome=array('email'=>$params['email'],'password'=>$params['password'],'designation'=>$params['email'],'email'=>$params['email'],'email'=>$params['email'],'email'=>$params['email'],'email'=>$params['email'],'email'=>$params['email'],)
        $current = date('Y-m-d H:i:s');
        $this->email = $params['email'];
        $this->password = $params['password'];
        $this->designation_id = $params['designation'];
        $this->payment_type = $params['paymenttype']; 
        $this->status = 1;
        $this->creation_date = $current;
        $this->updation_date = $current;
        $this->group_id=2;
        $this->weekly_hours='00:00';
        $this->annual_holliday_allowance='00.00';
        $this->annual_allowance_type=2;
        $this->actual_holiday_allowance='00.00';
        $this->actual_holiday_allowance_type=2;
         
        $this->db->insert("users", $this);
        $user_id = $this->db->insert_id(); 
        if($user_id)
        {
          $data['user_id'] = $user_id;
          $data['fname'] = $params['firstname'];
          $data['lname'] = $params['lastname'];  
          $data['creation_date'] = $current;
          $data['updation_date'] = $current; 
          $this->db->insert('personal_details', $data);
          $user['user_id'] = $user_id; 
          $user['unit_id']=$params['unit'];
          $user['creation_date'] = $current;
          $user['updation_date'] = $current;
          $user['status'] = 1;
          // $user['class'] = $params['class'];
          $this->db->insert('user_unit', $user);
          $datas['user_id'] = $user_id; 
          $datas['sunday']= 0;
          $datas['monday']= 0;
          $datas['tuesday']= 0;
          $datas['wednesday']= 0;
          $datas['thursday']= 0;
          $datas['friday']= 0;
          $datas['saturdy']= 0; 
          $datas['creation_date'] = $current;
          $this->db->insert('workschedule', $datas);
          $result['user_id'] = $user_id; 
          $result['day_rate']= 0;
          $result['night_rate']= 0;
          $result['day_saturday_rate']= 0;
          $result['day_sunday_rate']= 0;
          $result['weekend_night_rate']= 0;
          $result['updation_date'] = $current;
          $this->db->insert('user_rates', $result);
        }
        return $user_id;
   }

        public function saveagencystaff($params = array()){ 
        
   
        $current = date('Y-m-d H:i:s');
        $this->email = $params['email'];
        $this->password = $params['password'];
        $this->designation_id = $params['designation_id'];
        $this->payment_type = 1; 
        $this->status = 1;
        $this->creation_date = $current;
        $this->updation_date = $current;
        $this->group_id=2;
        $this->weekly_hours='00:00';
        $this->annual_holliday_allowance=$params['annual_holliday_allowance'];
        $this->annual_allowance_type=$params['annual_allowance_type'];
        $this->actual_holiday_allowance=$params['actual_holiday_allowance'];
        $this->actual_holiday_allowance_type=$params['actual_holiday_allowance_type'];
        $this->payroll_id=$params['payroll_id'];
        $this->default_shift=$params['default_shift'];
        $this->start_date=$params['start_date'];
        $this->db->insert("users", $this);
        $user_id = $this->db->insert_id(); 
        if($user_id)
        {
          $data['user_id'] = $user_id;
          $data['fname'] = $params['firstname'];
          $data['lname'] = $params['lastname'].'('.$user_id.')';  
          $data['gender']= $params['gender'];
          $data['mobile_number'] = $params['mobile_number'];
          $data['dob'] = $params['dob'];
          $data['address1'] = $params['address1'];
          $data['country']  = $params['country'];
          $data['postcode'] = $params['postcode'];
          $data['kin_name'] = $params['kin_name'];
          $data['kin_address'] = $params['kin_address'];
          $data['kin_phone']  = $params['kin_phone'];
          $data['creation_date'] = $current;
          $data['updation_date'] = $current; 
          $this->db->insert('personal_details', $data);


          $user['user_id'] = $user_id; 
          $user['unit_id']=1;
          $user['creation_date'] = $current;
          $user['updation_date'] = $current;
          $user['status'] = 1; 
          $this->db->insert('user_unit', $user);

          $datas['user_id'] = $user_id; 
          $datas['sunday']= 1;
          $datas['monday']= 1;
          $datas['tuesday']= 1;
          $datas['wednesday']= 1;
          $datas['thursday']= 1;
          $datas['friday']= 1;
          $datas['saturdy']= 1; 
          $datas['creation_date'] = $current;
          $this->db->insert('workschedule', $datas);

          $result['user_id'] = $user_id; 
          $result['day_rate']= 0;
          $result['night_rate']= 0;
          $result['day_saturday_rate']= 0;
          $result['day_sunday_rate']= 0;
          $result['weekend_night_rate']= 0;
          $result['updation_date'] = $current;
          $this->db->insert('user_rates', $result);

          return $user_id;

        } 
   }



   public function finduser()
   {

      $result = array();
          $data = array(
            'users.id',
            'users.payroll_id',
            'users.email',
            'users.group_id',
            'users.status',
            'users.lastlogin_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.visa_status',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_designation.designation_code',
            'unit.unit_name',
             );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
      $this->db->join('master_group', 'master_group.id = users.group_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      $this->db->where('users.status', 1); 
      $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }

   public function deleteShiftsbyunit($user_id,$unit_id,$date)
   {
      $this->db->where('user_id', $user_id);
      $this->db->where('date', $date);
      $this->db->where('unit_id', $unit_id);
      $this->db->delete('rota_schedule');
      return;
      // print $this->db->last_query();
      // exit();
   }

   public function deleteshiftbyuser($user_id,$date_selected,$unit_id)
   { 

    $date=date('Y-m-d');
      if($user_id>0 && $date>0 && $date!='')
      {
        $data = array( 'shift_id' => 0,'shift_hours' => 0, );


          $this->db->where('user_id', $user_id);
          $this->db->where('date >=', $date);
          $this->db->where('unit_id', $unit_id);
          $this->db->update('rota_schedule', $data);



          $this->db->where('user_id', $user_id);
          $this->db->where('date >', $date_selected); 
          $this->db->delete('rota_schedule');


          $this->db->where('user_id', $user_id);
          $this->db->where('date >=', $date); 
          $this->db->delete('staffrota_schedule');
        
        //$this->db->where('unit_id', $unit_id); 
      //     print $this->db->last_query();
      // exit();
        
      }
    return;
   }

   public function deleteavaialabilityByuser($user_id)
   {  
     $date=date('Y-m-d');
     if($user_id>0 && $date>0 && $date!='')
      {
          $this->db->where('user_id', $user_id);
          $this->db->where('date >=', $date); 
          $this->db->delete('staffrota_schedule');

      }
    return;
   }
  
   public function deleteTrainingByuser($user_id)
   {
     $date=date('Y-m-d');
     if($user_id>0 && $date>0 && $date!='')
      {

        $SQL="DELETE `training_staff` FROM `training_staff`
              INNER JOIN `master_training` ON `master_training`.`id` = `training_staff`.`training_id` 
              WHERE ( `master_training`.`date_from` >= '".$date."') 
              AND `training_staff`.`user_id` = '".$user_id."'";
        $query = $this->db->query($SQL);

        //echo $this->db->last_query();exit();
         // $result = $query->result_array(); 
         //print_r($result); 

          return;

      }
    return;
   }

   public function checktimelog($user_id,$date,$unit_id,$new_unit)
   {  
    $this->db->select('*');
    $this->db->from('time_log');
    $this->db->where('user_id', $user_id);
    $this->db->where('date >=', $date);
    $this->db->where('user_unit', $unit_id); 
    $query = $this->db->get(); ;
    $result = $query->result_array();
    //print_r($result);exit();
     
     if(!empty($result))
     {
      $data=array('user_unit'=>$new_unit);
      $this->db->where('user_id', $user_id);
      $this->db->where('date >=', $date);
      $this->db->where('user_unit', $unit_id); 
      $this->db->update('time_log', $data); 
     }

     return;
        
   }

   public function findalluserbymanager($unit_id)
   {
    $result = array();
          $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.lastlogin_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_designation.designation_code',
            'unit.unit_name',
             );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
      $this->db->join('master_group', 'master_group.id = users.group_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      $this->db->where('user_unit.unit_id', $unit_id); 
      $this->db->where('users.status', 1);
      $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }

    public function findstaff($unit_id)
    {
      $data = array();
      $data = array(
            'master_shift.id',
            'master_shift.shift_name');
      $this->db->select($data);
      $this->db->from('master_shift');
      $this->db->join('users', 'users.default_shift = master_shift.id');
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      $this->db->where_in('user_unit.unit_id', $unit_id);
      $this->db->group_by('master_shift.id');
      // if($login_user_id){
      //   $this->db->where('personal_details.user_id = ', $login_user_id);
      // }
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
        $result = $query->result_array();
        if($query->num_rows() > 0){
          return $result;
        }
        else{
          return [];
        }
    }

    public function finddesignationcode($designation_id)
    {
    $this->db->select('designation_code');
    $this->db->from('master_designation'); 
    $this->db->where('id', $designation_id);

    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

    }

  public function getstaffs($shifts)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->where('default_shift', $shifts);

    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }

  public function findethnicity($parent_id, $spacing = '', $user_tree_array = '') {  
 
      if (!is_array($user_tree_array))
        $user_tree_array = array();
      $this->db->where('status', 1);  
      $query = $this->db->get('Ethnicity');
     // echo $this->db->last_query();exit;
      $result = $query->result(); 
      foreach ($result as $mainCategory) { //print_r($mainCategory);
        if($mainCategory->parent==0)
        {
          $user_tree_array[] = array("id" => $mainCategory->id, "Ethnic_group" => $spacing . $mainCategory->Ethnic_group,"parent" =>$mainCategory->parent,"other_status" =>$mainCategory->other_status);
        }
        else
        {
          $user_tree_array[] = array("id" => $mainCategory->id, "Ethnic_group" => '--' .$spacing . $mainCategory->Ethnic_group,"parent" =>$mainCategory->parent,"other_status" =>$mainCategory->other_status);

        }
      }    //exit();
      return $user_tree_array;
    }


  public function findcountry()
  {
    $this->db->select('country');
    $this->db->from('personal_details');  
    $this->db->group_by('country');
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }
  public function getNotAssignedStaffs($unitid)
  {
    $this->db->select('unit.id');
    $this->db->from('unit');
    $this->db->where('parent_unit', $unitid);
    $query = $this->db->get();
        //echo $this->db->last_query();exit();
    $result = $query->result_array();
   // print_r($result);exit();



    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->join('user_unit', 'user_unit.user_id = users.id');
    $this->db->join('master_designation', 'master_designation.id = users.designation_id');
    $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');      
    $this->db->where('users.id !=1'); 
    $this->db->where('users.status', 1);
    $this->db->where('(default_shift IS NULL OR default_shift = 0)');
    if(!empty($result))
       {
        $this->db->group_start();
        $this->db->where('user_unit.unit_id', $unitid);
        foreach ($result as $value) { 
          $this->db->or_where('user_unit.unit_id', $value['id']);
        } 
        $this->db->group_end();
       }
       else
       {
        $this->db->where('user_unit.unit_id', $unitid);
       }
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }

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
            'users.payment_type',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.actual_holiday_allowance',
            'users.actual_holiday_allowance_type',
            'users.payroll_id',
            'users.default_shift',
            'users.start_date',
            'users.final_date',
            'users.notes',
            'users.to_unit',
            'users.unit_change_date',
            'users.exit_interview',
            'users.exit_reason',
            'users.hr_ID',
            'personal_details.fname',
            'personal_details.Ethnicity',
            'Ethnicity.id as ethnic_id',
            'personal_details.lname',
            'personal_details.gender',
            'personal_details.mobile_number',
            'personal_details.dob',
            'personal_details.address1',
            'personal_details.address2',
            'personal_details.city',
            'personal_details.country',
            'personal_details.postcode',
            'personal_details.kin_name',
            'personal_details.kin_phone',
            'personal_details.kin_address', 
            'personal_details.visa_status',
            'master_designation.designation_name',
            'master_designation.group',
            'master_designation.sort_order',
            'master_designation.id as designation_id'
          );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left'); 
      $this->db->join('Ethnicity', 'Ethnicity.Ethnic_group = personal_details.Ethnicity','left');  
      $this->db->where('users.id', $id); 
      $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }


   public function findunits($id)
   {
      $this->db->select('*');
      $this->db->from('users'); 
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      $this->db->where('users.id', $id);
      $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;


   }

   public function userworkschedule($id)
   {
       $this->db->select('*');
       $this->db->from('users');
       $this->db->join('workschedule', 'workschedule.user_id = users.id');
       $this->db->where('users.id', $id);
       $query = $this->db->get();
       //  print $this->db->last_query();
       // exit();
       $result = $query->result_array();
       return $result;
       
       
   }

  public function getuser($params)
  {
   try
    {
          if(empty($params['status']))
            $params['status'] = 1;
          
         
          $result = array();
          $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.password',
            'personal_details.fname',
            'personal_details.lname');
          
        $this->db->select($data);
        $this->db->from('users');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        $this->db->where('users.id', $params['id']);
        $result = $this->db->get();
      // echo $this->db->last_query(); exit;
        $result = $result->result_array();  
        if(count($result) > 0)
        {
          return $result;
        }
        else
        {
          return array();
        }
    }catch(Exception $e)
    {
        throw new Exception("Error Processing Request", 1);
    }


  }

 public function updates($params = array())
   { 
        $data = array('status' => $params['status'],'updation_date' => date('Y-m-d H:i:s'),'updation_userid'=> $params['id']);
          //print_r($data); exit();
        $this->db->where('id', $params['id']);
        $this->db->update('users', $data); 
   }
   public function updateappPassword($params = array())
   {
       $data = array('app_pass' => $params['app_pass'],'pass_enable' => $params['pass_enable'],'updation_date' => date('Y-m-d H:i:s'),'updation_userid'=> $params['id']);
       //print_r($data); exit();
       $this->db->where('id', $params['id']);
       $this->db->update('users', $data);
   }
   
  public function user_email_send($datahome)
  {    
    $this->db->insert('user_email_send',$datahome);
  }

  public function user_email_send_update($user_id)
  {   

        $this->db->where('user_id', $user_id);
        $this->db->set('password_change_status',1);
        $this->db->set('updation_date',date('Y-m-d H:i:s'));
        $result=$this->db->update('user_email_send'); 

  }
  
   public function updateAgencyweekhours($user_id,$dataform)
   { 
         //print_r($data); exit();
        $this->db->where('id', $user_id);
        $this->db->update('users', $dataform); 
   }

  // public function deleteuser($id)
  // {
         
  //          $this->db->delete('user_unit', array('user_id' => $id));
  //          $this->db->delete('personal_details', array('user_id' => $id));
  //          $this->db->delete('workschedule', array('user_id' => $id));
  //          $this->db->delete('user_rates', array('user_id' => $id));
  //          $this->db->delete('holliday', array('user_id' => $id));
  //          $this->db->delete('users', array('id' => $id)); 
  //          return 1;
    
  // }

    public function deleteuser($id)
    {
        $status=array('status'=>3);
        $this->db->where('id', $id);
        $status=$this->db->update('users',$status);
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

    public function finduserbystatus($status)
    {   
      $result = array();
      $data = array(
            'users.payroll_id',
            'users.id',
            'users.email',
            'users.status',
            'users.lastlogin_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'unit.unit_name',
             );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
      $this->db->join('master_group', 'master_group.id = users.group_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      if($status!=4){
      $this->db->where('users.status', $status); }
      $query = $this->db->get();

      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
    }


    public function finduserbyunit($unit,$status)
    {    
      //print_r($status);
      if($unit!="")
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $unit);
      $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      }
      // print_r($result);exit();


      //$result = array();
      $data = array(
            'users.payroll_id',
            'users.id',
            'users.email',
            'users.status',
            'users.lastlogin_date',
            'master_group.group_name',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.visa_status',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'master_designation.designation_code',
            'unit.unit_name',
            'unit.parent_unit'
             );
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id','left');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id','left');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type','left');
      $this->db->join('master_group', 'master_group.id = users.group_id','left');
      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
      $this->db->join('unit', 'unit.id = user_unit.unit_id','left');
      if(!empty($status))
      {
        $this->db->where_in('users.status', $status); 
      }
      else
      {
        $this->db->where_in('users.status', "1"); 
      }
      if($unit!="")
      {
         if(empty($result))
         {   
            $this->db->where('user_unit.unit_id', $unit);
         }
         else
         { 
             $this->db->group_start();
             $this->db->where('user_unit.unit_id', $unit);
             foreach ($result as $value) {
                $this->db->or_where('user_unit.unit_id', $value['id']);
             }
             $this->db->group_end();
            $this->db->order_by('unit.parent_unit','asc');
         }
      }

      $this->db->order_by('users.id','asc');
      $query = $this->db->get();

      //print $this->db->last_query();
      //exit();
      $result = $query->result_array();
      return $result;
    }


      public function insertdata($user_id,$dataform)
      {
        $this->db->where('user_id', $user_id);
        $this->db->update('personal_details',$dataform);
        //print $this->db->last_query();
      }

 
      public function insertdatas($user_id,$dataforms)
      {
        $this->db->where('id', $user_id);
        $this->db->update('users',$dataforms);
        if ($this->db->affected_rows() > 0)
        {
          return true;
        }
        else
        {
          return false;
        }
      }

      public function insertuserrates($data)
      {
        $this->db->insert('user_rates',$data);
      }
      public function updateuserrates($user_id,$data)
      {
        $this->db->where('user_id', $user_id);
        $this->db->update('user_rates',$data);
      }

      public function finduserRates($user_id)
      {
      $this->db->select('*');
       $this->db->from('user_rates');
       $this->db->where('user_id', $user_id);
       $query=$this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      return $result;
      }
 
     public function updatestatus($user_id,$dataformstatus)
     {
        $this->db->where('id', $user_id);
        $this->db->update('users',$dataformstatus);

     }
     public function updatethumbnail($payroll_id,$data)
     {
         $this->db->where('id', $payroll_id);
         $this->db->update('users',$data);
        // print $this->db->last_query();exit;
         
         return $this->db->last_query();
         
     }
      public function updateunit($user_id,$dataform)
      {
        $this->db->where('user_id', $user_id);
        $this->db->update('user_unit',$dataform);
        return 1;
      }
        
     
       public function  getEmail($email)
    {
     $this->db->select('email');
       $this->db->from('users');
       $this->db->where('email', $email);
       $query=$this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      return $result;

    }

    public function getUserInfoByEmail($email)
    {
        $q = $this->db->get_where('users', array('email' => $email), 1);  
        if($this->db->affected_rows() > 0)
        {
            $row = $q->row();
            return $row;
        }
        else
        {
            error_log('no user found getUserInfo('.$email.')');
            return false;
        }
    }

    public function getUserInfoUsingEmail($email)
    {
      $data=array('personal_details.fname','personal_details.lname','users.group_id','users.id');
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->where('users.email',$email);
      $query=$this->db->get();
      $result = $query->result_array(); 
      // print $this->db->last_query();
      // exit();
      return $result;

    }

    public function insertToken($email)
   {
    
        $data = array('reset_password' => 2,'key' => random_string('alnum', 16));
        $this->db->where('email', $email);
        // $this->db->where('status', 1);
        $this->db->update('users', $data);
       //   print $this->db->last_query();
       // exit();
        unset($data['reset_password']);
        $data['email'] = $email; 
        return $data;
      
   }

    public function isTokenValid($cleanToken)
    {
       $this->db->select('*');
       $this->db->from('users');
       $this->db->where('key', $cleanToken);
       $query=$this->db->get();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      return $result;

      # code...
    }

      public function updatesPassword($pwd,$token)
  {
         $this->db->where('key',$token);
         $this->db->set('password',$pwd);
         $this->db->set('status',1);
         $this->db->set('reset_password',1);
         $result=$this->db->update('users');
      // print $this->db->last_query();
      // exit();
        //print_r($result);exit();
      
  }
  public function findholidaydetail($title_slug)
   {

    $result = array();
    $data = array( 
            'holliday.id',
            'holliday.from_date',
            'holliday.to_date',
            'holliday.start_time',
            'holliday.end_time',
            'holliday.days', 
            'personal_details.fname',
            'personal_details.lname');
          
    $this->db->select($data); 
    $this->db->from('users');
    $this->db->join('holliday', 'holliday.user_id = users.id');
    $this->db->join('user_unit', 'user_unit.user_id = users.id');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->where('users.id', $title_slug);
    $query=$this->db->get();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      return $result;

  }

  public function findholidays($id)
   {
    if(date("m") > 8)
    {
      $date_new=date('Y');
    }
    else
    {
      $date_new=date('Y')-1;
    }
    $leaveStart=$date_new.'-09-01';
    $result = array();
    $data = array( 
            'holliday.id',
            'holliday.from_date',
            'holliday.to_date',
            'holliday.leave_remaining',
            'holliday.start_time',
            'holliday.end_time',
            'holliday.days', 
            'holliday.creation_date',
            'holliday.status',
            'personal_details.fname',
            'personal_details.lname');
          
    $this->db->select($data); 
    $this->db->from('users');
    $this->db->join('holliday', 'holliday.user_id = users.id');
    $this->db->join('user_unit', 'user_unit.user_id = users.id');
    $this->db->join('personal_details', 'personal_details.user_id = users.id');
    $this->db->where('users.id', $id);
    $this->db->where('holliday.to_date>=',$leaveStart); 
    $query=$this->db->get();
     // print $this->db->last_query();
     //  exit();
    // print $id;
    // print("<pre>".print_r($query->result_array(),true)."</pre>");
      // exit();
      // exit();
      $result = $query->result_array(); 
      return $result;
  }
 
 
  public function updateholiday($id,$dataform)
  { //print_r($id); print_r($dataform);exit();
    $this->db->where('id', $id);
    $this->db->update('holliday',$dataform);
    return;

  }



   public function getUserInfo($params = array()){ //print_r($params);exit();
          if(empty($params['status']))
              $params['status'] = 1;
              $admin_email=getCompanydetails('from_email');  
              
              $result = array();
              if($params['email']!=$admin_email)
              {
                $data = array(
                    'users.id',
                    'users.email',
                    'users.group_id',
                    'users.user_session',
                    'user_unit.unit_id',
                    'master_group.subunit_access'
                     );
              }
              else
              {
                $data = array(
                    'users.id',
                    'users.email',
                    'users.group_id',
                    'users.user_session' 
                     );
              }
             
              if(!empty($params['role']))
                  $data[] = 'roles.can_delete';
                  
                  if(!empty($params['personal_details'])){
                      $data[] = 'personal_details.fname';
                      $data[] = 'personal_details.lname';
                      $data[] = 'personal_details.mobile_number';
                  }
                  
                  // if(!empty($params['temp_email']))
                  //    $data[] = 'users.temp_email';
                  $this->db->select($data);
                  $this->db->from('users');
                  if($params['email']!= $admin_email)
                  {
                      $this->db->join('user_unit', 'user_unit.user_id = users.id','left');
                  }
                  $this->db->join('master_group', 'master_group.id = users.group_id','left');
                  
                  if(!empty($params['role'])){
                      $this->db->join('roles', 'roles.role_id = users.user_type');
                  }
                  if(!empty($params['user_type']))
                      $this->db->where('users.group_id', $params['group_id']);
                      
                      if(!empty($params['personal_details'])){
                          $this->db->join('personal_details', 'personal_details.user_id = users.id');
                      }
                      if(!empty($params['id']) && !empty($params['email']) && !empty($params['email_check'])){
                          $this->db->where('users.id !=', $params['id']);
                      }
                      else if(!empty($params['id']))
                          $this->db->where('users.id', $params['id']);
                          
                      
                         
                          if(!empty($params['email']))
                              $this->db->where('email', $params['email']);
                              
                              if(!empty($params['password']))
                                  $this->db->where('password', $params['password']);
                                  if(!empty($params['status']))
                                      $this->db->where('users.status', $params['status']);
                     
                                   
                                 
                                                      $this->db->order_by('id', 'ASC');
                                                      $result = $this->db->get();
                                       // echo $this->db->last_query()."<br>";  exit();
                                                      $result = $result->result_array();
                                                    //  print_r($result);exit;
                                                      if(count($result) > 0){
                                                          return $result;
                                                      }
                                                      else{
                                                          return array();
                                                      }
      }
 public function findTokenbyUser()
 {
  $this->db->select('key');
  $this->db->from('users');
  $this->db->order_by('id', 'DESC');
  $this->db->limit(1);
  $query=$this->db->get();
    // print $id;
    // print("<pre>".print_r($query->result_array(),true)."</pre>");
      // exit();
      // print $this->db->last_query();
      // exit();
      $result = $query->result_array(); 
      return $result;
 }
 
    public function getstaffsFromOtherLocation($unit_id)
    {
      // $unit_id = 111;
      $this->db->select('user_unit.user_id');
      $this->db->from('user_unit');
      $user_ids = array();
      // $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->where('user_unit.unit_id', $unit_id);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      if(count($result) > 0){
        foreach($result as $row){
          array_push($user_ids,$row['user_id']);
        }
        if(count($user_ids) > 0){
          $this->db->select('*');
          $this->db->from('users');
          $this->db->join('personal_details', 'personal_details.user_id = users.id');
          $this->db->join('master_shift', 'master_shift.id = users.default_shift');
          $this->db->where_in('personal_details.user_id', $user_ids);
          $this->db->where_in('users.id', $user_ids);
          // $this->db->group_by('master_shift.shift_shortcut');
          $query1  = $this->db->get();
          $result1 = $query1->result_array();
          // print("<pre>".print_r($result1,true)."</pre>");exit();
          // exit();
          

          if(count($result1) > 0){
            return $result1;
          }else{
            return [];
          }
        }else{
          return [];
        }      
      }else{
        return [];
      }
    }
  public function deleteShifts($rota_id)
  {
   // print_r($rota_id);exit();
    $this->db->delete('rota_schedule', array('rota_id' => $rota_id));
  }

  public  function deletesubunitShifts($rota_id,$unit_id)
  {
   $this->db->where('rota_id', $rota_id);
   $this->db->where('unit_id', $unit_id);
   $this->db->delete('rota_schedule');
      //  print $this->db->last_query();
      // exit();

  }
  
  public function addShiftDetails($shiftDetails)
  {  
    $query = $this->db->insert('rota_schedule',$shiftDetails);
  }

  public function updateShiftdetails($id,$shiftDetails)
  { 
      $this->db->where('id', $id);
      $this->db->update('users', $shiftDetails);
      return;
  }

   public function findusername($id)
   {
      $this->db->select('*');
      $this->db->from('personal_details');  
      $this->db->where('user_id', $id);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }

   public function findjobrole($id)
   {

      $this->db->select('id');
      $this->db->from('master_designation');  
      $this->db->where('designation_name', $id);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }
   public function findUnitid($name)
   {
      $this->db->select('id');
      $this->db->from('unit');  
      $this->db->where('unit_name', $name);
       $query = $this->db->get();
       //print $this->db->last_query();
      // exit();
      $result = $query->result_array();  
      return $result;

   }
   public function findGroupid($group)
   {
      $this->db->select('id');
      $this->db->from('master_group');  
      $this->db->where('group_name', $group);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }
    public function findDefaultshiftbystatus($code)
   {
      $this->db->select('id');
      $this->db->from('master_shift');  
      $this->db->where('shift_shortcut', $code);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }

   public function findDefaultshift($code)
   {
      $this->db->select('id');
      $this->db->from('master_shift');  
      $this->db->where('shift_name', $code);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }

   public function findPaymentid($id)
   {
      $this->db->select('id');
      $this->db->from('master_payment_type');  
      $this->db->where('payment_type', $id);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }

   public function getuserstatus($user_id)
   {
      $this->db->select('users.status');
      $this->db->from('users');  
      $this->db->join('rota_schedule', 'rota_schedule.user_id = users.id');
      $this->db->where('user_id', $user_id);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;
   }
  public function addRotaDetails($rotaDetails){ 
    $array = array(
      'start_date' => $rotaDetails['start_date'], 
      'end_date' => $rotaDetails['end_date'],
      'unit_id' => $rotaDetails['unit_id']
    );
    $this->db->select('id');
    $this->db->from('rota');
    $this->db->where($array);
    $query = $this->db->get();  
    if(count($query->result_array()) > 0)
    { 
      $result  = $query->result_array();  
      $rota_id = $result[0]['id'];
      return array(
          'rota_id' => $rota_id,
          'flag' => 1
      );
    }
    else
    { 
      $query = $this->db->insert('rota',$rotaDetails);
      $rota_id = $this->db->insert_id();
      return array(
          'rota_id' => $rota_id,
          'flag' => 2
      );
    }
  }
  /*Function to get the unit manager details by joining two tables users and user_unit*/
    public function getUnitManger($id){
      $array = array('user_unit.unit_id' => $id,'master_designation.sort_order' => 2);
      $this->db->select('user_unit.unit_id,user_unit.user_id,users.email,personal_details.fname,personal_details.lname,master_designation.sort_order');
      $this->db->from('user_unit');
      $this->db->join('users', 'user_unit.user_id = users.id');
      $this->db->join('personal_details','personal_details.user_id = users.id');
      $this->db->join('master_designation','master_designation.id = users.designation_id');
      $this->db->where($array);
      $query = $this->db->get();
      if($query){
        return $query->result_array();
      }else{
        $mysqlerror = $this->db->error();
        return [];
      }
    }
    public function getDefaultShift($user_id){
      $this->db->select('users.id,master_shift.id as shift_id,master_shift.targeted_hours');
      $this->db->from('users');
      $this->db->join('master_shift', 'master_shift.id = users.default_shift','left');
      $this->db->where('users.id=',$user_id);
      $query = $this->db->get();
      if($query -> num_rows()>0){
        return $query->result_array();
      }else{
        return [];
      }
    }
    public function getUserUnitAndGroupId($user_id){
      $this->db->select(
        'users.id,
        users.group_id,
        user_unit.unit_id'
      );
      $this->db->from('users');
      $this->db->join('user_unit', 'users.id = user_unit.user_id');
      $this->db->where('users.id=',$user_id);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    function findDatesBetnDates($start, $end, $format = 'Y-m-d'){
      // Declare an empty array 
          $array = array(); 
            
          // Variable that store the date interval 
          // of period 1 day 
          $interval = new DateInterval('P1D'); 
        
          $realEnd = new DateTime($end); 
          $realEnd->add($interval); 
        
          $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
        
          // Use loop to store date into array 
          foreach($period as $date) {                  
              $array[] = $date->format($format);  
          } 
        
          // Return the array elements 
          return $array; 
    }
    public function userStatusChange($user_id){
      $user_ids = array();
      array_push($user_ids, $user_id);
      $this->deleteShiftOfInactiveUser($user_ids);
    }
    public function removeInactiveUsers(){
      $user_ids = array();
      $current_date=date('Y-m-d');
      $this->db->select('users.id');
      $this->db->from('users');
      //Find users with last working day is current date
      $this->db->where('final_date',$current_date);
      $query = $this->db->get();
      $results = $query->result_array();
      if(count($results) > 0){
        foreach ($results as $result){
          array_push($user_ids, $result['id']);
        }
        //Update query for making users inactive
        $update_value = array('status' => 2);
        $this->db->where_in('id', $user_ids);
        $this->db->update('users', $update_value);
        //Calling function to delete corresponding rota
        $this->deleteShiftOfInactiveUser($user_ids);
      }else{
        return;
      }
    }
    public function deleteShiftOfInactiveUser($user_ids)
    { 
      $cur_date = date('Y-m-d');
      $next_day = date('Y-m-d', strtotime($cur_date . ' + 1 day'));
      $next_sat = date('Y-m-d', strtotime('next saturday'));
      $day=date("D",strtotime($cur_date));
      //Check whether current day is a saturday. If yes
      //we have to delete all rota from the next sunday
      if($day == 'Sat'){
        //so the seleted date will be current date
        $date_selected = $cur_date;
      }else{
        //If it is not a saturday then we have to find the next
        //saturday and seleted date will be the next saturday date
        $date_selected = $next_sat;
      }
      $this->db->where_in('user_id', $user_ids);
      //Delete all rotas from next sunday correponding to
      //current day
      $this->db->where('date >', $date_selected); 
      $this->db->delete('rota_schedule');
      //If current day is not a saturday we have to update the current
      //week rota from the next day with 0 shift id. For this we find the next date //and next saturday and find dates beteween the next day and
      //next saturday and update rotas within the date array
      if($day != 'Sat'){
        $dates_array = $this->findDatesBetnDates($next_day,$next_sat);
        $data = array( 'shift_id' => 0,'shift_hours' => 0);
        $this->db->where_in('user_id', $user_ids);
        $this->db->where_in('date', $dates_array);
        $this->db->update('rota_schedule', $data);
      }
      return;
    }
    public function deleteShiftOfUnitChangeUser($user_id,$unit_id){
      $cur_date = date('Y-m-d');
      $next_day = date('Y-m-d', strtotime($cur_date . ' + 1 day'));
      $next_sat = date('Y-m-d', strtotime('next saturday'));
      $day=date("D",strtotime($cur_date));
      //Check whether current day is a saturday. If yes
      //we have to delete all rota from the next sunday
      if($day == 'Sat'){
        //so the seleted date will be current date
        $date_selected = $cur_date;
      }else{
        //If it is not a saturday then we have to find the next
        //saturday and seleted date will be the next saturday date
        $date_selected = $next_sat;
      }
      $this->db->where('user_id', $user_id);
      $this->db->where('unit_id', $unit_id);
      //Delete all rotas from next sunday correponding to
      //current day
      $this->db->where('date >', $date_selected); 
      $this->db->delete('rota_schedule');
      //If current day is not a saturday we have to update the current
      //week rota from the next day with 0 shift id. For this we find the next date //and next saturday and find dates beteween the next day and
      //next saturday and update rotas within the date array
      if($day != 'Sat'){
        $dates_array = $this->findDatesBetnDates($next_day,$next_sat);
        $data = array( 'shift_id' => 0,'shift_hours' => 0);
        $this->db->where('user_id', $user_id);
        $this->db->where('unit_id', $unit_id);
        $this->db->where_in('date', $dates_array);
        $this->db->update('rota_schedule', $data);
      }
      return;
    }
    public function changeUserUnit(){
      $user_ids = array();
      $current_date=date('Y-m-d');
      $this->db->select(
        'users.id,users.to_unit,
        users.unit_change_date,
        user_unit.unit_id,users.unit_scheduler_id'
      );
      $this->db->from('users');
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      //Find users with unit change day is current date
      $this->db->where('users.unit_change_date',$current_date);
      $query = $this->db->get();
      //echo $this->db->last_query();exit();
      $results = $query->result_array();
      if(count($results) > 0){
        foreach ($results as $result){ //print_r($result);

          //Updating user_unit history table
          $unit_history=array('user_id' => $result['id'],'Previous_unit'=> $result['unit_id'],'Current_unit'=>$result['to_unit'],'Updation_date'=>date('Y-m-d H:i:s'),'Updated_by'=>$result['unit_scheduler_id'],'Status'=>2);
          $this->db->insert("User_Unit_Designation_history", $unit_history);

          //Updating user_unit unit_id value with new unit id
          $dataform=array('unit_id' => $result['to_unit'],'updation_date'=>date('Y-m-d H:i:s'));
          $update_result = $this->updateunit($result['id'],$dataform);


          //Updating users details to_unit and unit_change_date as null
          $update_value = array('to_unit' => null,'unit_change_date'=>null,'unit_scheduler_id'=>null);
          $this->db->where('id', $result['id']);
          $this->db->update('users', $update_value);

          $this->deleteShiftOfUnitChangeUser($result['id'],$result['unit_id']); // delete shifts by userid and unit
          $this->deleteavaialabilityByuser($result['id']); // delete availability by userid - aug 17 by swaraj
          $this->deleteTrainingByuser($result['id']); // delete training staff table entry by userid - aug 19 by swaraj

          //finding next sunday of current date and checking for rota_schedule
          $date=date('Y-m-d');
          if(date('D', strtotime($date))!='Sun')
          {
              //$nextSunday = date('Y-m-d', strtotime('next sunday'));
              $nextSunday=date('Y-m-d', strtotime($date.'last sunday')); /* previous sunday of the date */
          }
          else
          {
              $nextSunday = $date;
          }
          //print_r($nextSunday);exit();
          $saturdayDate=date('Y-m-d', strtotime($nextSunday.'next saturday'));
          $date_array=$this->findDatesBtwnDates($nextSunday,$saturdayDate);
          $this->load->model('Rota_model', 'blog');
          $rotas=$this->blog->getRotaForUnitchangeBydateAndunitID($nextSunday,$result['to_unit'],$result['id']);
          if($rotas!=0)
          {
            $this->changeUserUnits($rotas,$result['to_unit'],$result['id'],$date_array,$result['unit_id']);
           
            $this->load->model('Moveshift_model', 'blog1');
            $num=count($rotas)-1; 
            $admin_details=$this->blog1->getUsername($this->session->userdata('user_id'));
            $user_details=$this->blog1->getUsername($result['id']);
            $unit_name=$this->blog1->getUnitname($result['to_unit']);

            $description = $admin_details['fname'].' '.$admin_details['lname'].' '.'has added a rota for '.$user_details['fname'].' '.$user_details['lname'].' '.' from '.' '.$rotas[0]['date'].' '.'to'.' '.$rotas[$num]['date'].'(User unit change(scheduler)- create x entry).'; //print_r($description);exit();

             $data_activity_log=array(
                                      'description'=>$description,
                                      'activity_date'=>date('Y-m-d H:i:s'),
                                      'activity_by'=>$this->session->userdata('user_id'),
                                      'add_type'=>'Unit change - Create Rota',
                                      'user_id'=>$result['id'],
                                      'unit_id'=>$result['to_unit'],
                                      'primary_id'=>$rotas[0]['rota_id'],
                                      'creation_date'=>date('Y-m-d H:i:s')
                      );
             $this->load->model('Activity_model', 'blog2');
            $this->blog2->insertactivity($data_activity_log);


          }

        }
      }else{
        return;
      }
    }

    public function changeUserUnits($rotas,$unit_id,$user_id,$date_array,$from_unit)
    {
          foreach ($rotas as $rota) { //print_r($rota); print '<br>';


              if (in_array($rota['date'], $date_array)) {
                  $from_unit = $from_unit;
                  $request = 2;                      
              } else {
                  $from_unit = NULL;
                  $request = 0;
              } 
                   
              $day=date("D",strtotime($rota['date']));
              $rotaschedule_data = array(
                                          'user_id'  => $user_id,
                                          'shift_id' => 0,
                                          'shift_hours' => 0,
                                          'additional_hours' =>NULL,
                                          'day_additional_hours' =>NULL,
                                          'night_additional_hours' =>NULL,
                                          'additinal_hour_timelog_id'=>NULL,
                                          'comment'=>NULL,
                                          'from_unit'=>$from_unit,
                                          'unit_id'=>$unit_id,
                                          'rota_id'=>$rota['rota_id'],
                                          'date'=>$rota['date'],
                                          'status'=>1,
                                          'creation_date'=>date('Y-m-d H:i:s'),
                                          'created_userid'=>$params['created_userid'],
                                          'updation_date'=>date('Y-m-d H:i:s'),
                                          'updated_userid'=>$this->session->userdata('user_id'),
                                          'day'=>$day,
                                          'designation_id'=>NULL,
                                          'shift_category'=>0,
                                          'from_userid'=>NULL,
                                          'from_rotaid'=>NULL,
                                          'request_id'=>NULL,
                                          'auto_insert'=>$request
                            );
                    //print_r($rotaschedule_data); print '<br>';
                   $this->db->insert('rota_schedule',$rotaschedule_data);
            }
//exit();
            //return;
    }

    public  function findDatesBtwnDates($start_date,$end_date){ 
       $date_array = array();
       // Specify the start date. This date can be any English textual format  
       $date_from = $start_date;   
       $date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
         
       // Specify the end date. This date can be any English textual format  
       $date_to = $end_date;  
       $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
         
       // Loop from the start date to end date and output all dates inbetween  
       for ($i=$date_from; $i<=$date_to; $i+=86400) {  
         array_push($date_array, date("Y-m-d", $i));
       }
       return $date_array;
   }
    public function getUsersWithUnitIdAndJobRole($unit_id,$job_roles,$status){
      $this->db->select(
        'users.id as user_id,
        users.email,
        personal_details.fname,
        personal_details.lname,
        users.annual_holliday_allowance,
        users.status,
        master_designation.designation_name,
        users.start_date,
        user_rates.day_rate'
      );
      $this->db->from('users');
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('user_rates', 'user_rates.user_id = users.id');
      if($status){
        $this->db->where('users.status',$status);
      }
      $this->db->where('user_unit.unit_id',$unit_id);
      $this->db->where('users.payment_type !=',1);
      $this->db->where_in('users.designation_id',$job_roles);
      $query = $this->db->get();
      //echo $this->db->last_query();exit();
      if($query -> num_rows()>0){
        return $query->result_array();
      }else{
        return [];
      }
    }

    public function getpayrateByUserId($user_new_id)
    {  //print_r($user_id); print '<br>';
 
        $this->db->select('*');
        $this->db->from('user_rates');
        $this->db->where('user_rates.user_id', $user_new_id);
        $query = $this->db->get();
        //print $this->db->last_query(); print '<br>';print '<br>';print '<br>';//exit();
        $result = $query->result_array();

        if(count($result)>0){
          return $result;
        }else{
          return NULL;
        } 

    }

    public function updaterates($user_id,$payrate)
    {
      $data = array(
          'day_rate'      => $payrate,
          'updation_date' => date('Y-m-d H:i:s'),
          'updated_userid' => $this->session->userdata('user_id')
      );
      $this->db->where('user_id', $user_id);
      $this->db->update('user_rates', $data);
      print 'updation'; print '<br>';
      //return 1;
    }

    public function insertuserratevalues($user_id,$payrate)
    {
      $datas = array(
                       'user_id'      => $user_id,
                       'day_rate'     => $payrate,
                       'night_rate'   => $night_rate,
                       'day_saturday_rate' => NULL,
                       'day_sunday_rate'   => NULL,
                       'weekend_night_rate' => NULL,
                       'updation_date'  =>date('Y-m-d H:i:s'),
                       'updated_userid' => $this->session->userdata('user_id')
                    );
      $this->db->insert("user_rates", $datas);
      //print $this->db->last_query();

      print 'insertion'; print '<br>';
      //return 2;
    }

    public function update_user_working_hours($params)
    { 

          $current = date('Y-m-d H:i:s');
          $datas['weekly_hours'] = $params['weekly_hours']; 
          $datas['annual_holliday_allowance']=$params['annual_holliday_allowance']; 
          $datas['updation_date'] = $current;

          $this->db->where('id', $params['user_id']);
          $result=$this->db->update('users',$datas);
          //print $this->db->last_query();
          //print_r($this->db->affected_rows()); 
          //print_r($result);exit();
          if($this->db->affected_rows()!=1)
          { 
            $status=$params['user_id'].'_'.'annual_holliday_allowance- failed';
              //print_r($status);exit();
          }
           else
          {
            $status=$params['user_id'].'_'.'annual_holliday_allowance- updated';
          } 
          return $status;
    }
    public function get_coventry_users()
    {
      $this->db->select('*');
      $this->db->from('users_c');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function get_coventry_user_rates($user_id)
    {
      $this->db->select('*');
      $this->db->from('user_rates_c');
      $this->db->where('user_id', $user_id);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function get_coventry_personal_details($user_id)
    {
      $this->db->select('*');
      $this->db->from('personal_details_c');
      $this->db->where('user_id', $user_id);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function insert_coventry_users($data)
    {
      $this->db->insert('users',$data);
      $user_id = $this->db->insert_id();
      return $user_id;
    }
    public function insert_coventry_user_rates($data)
    {
      $this->db->insert('user_rates',$data);
      return true;
    }
    public function insert_coventry_personal_details($data)
    {
      $this->db->insert('personal_details',$data);
      return true;
    }
    public function check_existing_user($email)
    {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where('email', $email);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function insertipaddress($dataform)
    { 
      // Check if the IP address already exists in the database
         $existingIpAddress = $this->db->get_where('ip_addresses', array('ip_address' => $dataform['ip_address']))->row();

         // If the IP address already exists, return false or handle the duplicate case as needed
         if ($existingIpAddress) {
             return false; // or handle the duplicate case as needed
         }

         // If the IP address is unique, proceed with inserting it into the database
         $this->db->insert("ip_addresses", $dataform);
         $insertId = $this->db->insert_id();
         return $insertId;
         
      /*$this->db->insert("ip_addresses", $dataform);
      $insertId = $this->db->insert_id();
      return  $insertId;*/
    }
    public function getIpAddresses()
    {
      $this->db->select('*');
      $this->db->from('ip_addresses');
      $this->db->order_by('created_at', 'desc');
      $query = $this->db->get(); 
      $result = $query->result_array();
      return $result;
    }
    public function check_ip_address($ip_address)
    {
        // Query the ip_addresses table to check if the IP address exists
        $query = $this->db->get_where('ip_addresses', array('ip_address' => $ip_address));

        // Check if any row is returned
        if ($query->num_rows() > 0) {
            return true; // IP address exists
        } else {
            return false; // IP address does not exist
        }
    }
    public function authenticate_user($username, $password) {
      $this->db->select('users.id,users.email,users.password,personal_details.mobile_number');
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id'); 
      $this->db->where('users.email', $username);
      $this->db->where('users.password', $password);
      $query = $this->db->get(); 
      $result = $query->result_array();
      return $result;
    }
    public function insetOtpLogins($dataform)
    {
      $this->db->insert("otp_logins", $dataform);
      $insertId = $this->db->insert_id();
      return $insertId;
    }
    public function checkValidOTP($user_id,$otp)
    {
      $this->db->select('*');
      $this->db->from('otp_logins');
      $this->db->where('user_id', $user_id);
      $this->db->where('otp', $otp);
      $this->db->where('status', 0);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
    public function updateOTPLogin($user_id,$otp)
    {
      $data = array(
          'status' => 1,
      );
      $this->db->where('user_id', $user_id);
      $this->db->where('otp', $otp);
      $this->db->where('status', 0);
      $this->db->update('otp_logins', $data);
      return true;
    }
    public function updateOTPLoginStatus($user_id)
    {
      $data = array(
          'status' => 1,
      );
      $this->db->where('user_id', $user_id);
      $this->db->where('status', 0);
      $this->db->update('otp_logins', $data);
      return true;
    }
    public function getOtpLogins() {
        $this->db->select('*');
        $this->db->from('otp_logins');
        $this->db->where('status', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function updateExpiredOTP($id) {
      $data = array(
          'status' => 1,
      );
      $this->db->where('id', $id);
      $this->db->update('otp_logins', $data);
      return true;
    }
    public function getIpDetails($id)
    {
      //print_r($email);
      $this->db->select('*');
        $this->db->from('ip_addresses'); 
        //$this->db->where('users.status', 2);
        $this->db->where('id',$id); 
        $query = $this->db->get();
         //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }
    public function updateIpDetails($id,$ip_address) {
      if($this->check_duplicate_ip($ip_address,$id)){
        return false;
      }else{
        $data = array(
          'ip_address' => $ip_address,
        );
        $this->db->where('id', $id);
        $this->db->update('ip_addresses', $data);
        return true;
      }
    }
    public function check_duplicate_ip($ip_address, $current_id) {
        $this->db->where('ip_address', $ip_address);
        $this->db->where('id !=', $current_id); // Exclude the current ID
        $query = $this->db->get('ip_addresses');

        if ($query->num_rows() > 0) {
            // IP address exists with another ID
            return true;
        } else {
            // IP address does not exist with another ID
            return false;
        }
    }
    public function delete_ip_address($id) {
       $this->db->where('id', $id);
       $this->db->delete('ip_addresses');
       return true;
    }
  }
?>