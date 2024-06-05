<?php

class Training_Model extends CI_Model 
{

public function allunit()
{
    $this->db->select('id,unit_name');
  $this->db->from('unit');
  $this->db->where('status', 1);
  $query = $this->db->get();
       // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

}
public function allunitnoagency()
{
    $this->db->select('id,unit_name');
    $this->db->from('unit');
    $this->db->where('id !=1');
    $this->db->where('status', 1);
    $query = $this->db->get();
    // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
    
}
public function findunit($id)
{
        $this->db->select('unit_name,address,phone_number');
        $this->db->from('unit');
        $this->db->where('id', $id);
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
}

public function getTrainingTitle()
{
  $this->db->select('*');
  $this->db->from('training_titles'); 
  $query = $this->db->get();
       // echo $this->db->last_query();exit;
  $result = $query->result_array();
  return $result;

}

public function updatetraininghour($id,$hour)
{
  $data=array('training_hour'=>$hour);
  
  $this->db->where('id',$id);
  $this->db->update('master_training',$data);
  // echo $this->db->last_query();exit;
  if($this->db->affected_rows() > 0 )
      {
        return "true";
      }
      else
      {
        return "false";
      }
}

public function findtitle($id)
{
       $this->db->select('title,description');
        $this->db->from('training_titles');
        $this->db->where('id', $id);
        $query = $this->db->get();
      //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
}

public function getComment($id)
{
      $result = array();
        $data = array(
        'training_feedback.feedback',
        'training_feedback.creation_date', 
        'personal_details.fname',
        'personal_details.lname'
      );        
      $this->db->select($data);
      $this->db->from('training_feedback');
      $this->db->join('personal_details', 'personal_details.user_id = training_feedback.user_id');
      $this->db->where('training_id',$id);
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;

}

public function findtitleOfTraining($title)
{
        $this->db->select('title');
        $this->db->from('training_titles');
        $this->db->where('title', $title);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array(); 
         if($result)
         {
          $status=1;
         }
         else
         {
          $status=0;
         }
         return $status;

}

public function inserttrainingTitle($title,$description)
{ 
  $data=array('title'=>$title,'description'=>$description,'creation_date'=>date('Y-m-d H:i:s')); 
  //print_r($data);exit(); 
   $this->db->insert('training_titles',$data);
}

public function allunits($user_id)
{ 
  $this->db->select('unit.id,unit.unit_name');
  $this->db->from('unit');
  $this->db->join('user_unit', 'user_unit.unit_id = unit.id');
  $this->db->where('user_unit.user_id', $user_id);
  $this->db->where('unit.status', 1);
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;


}
 
public function findUser($unit_id,$selected_ids,$login_user_id)
    {

      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where_in('parent_unit', $unit_id);
      if(count($selected_ids)>0)
      {
        $this->db->where_not_in('id', $selected_ids);
      }
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $results = $query->result_array();
      //print_r($results);exit();


      $this->db->select('personal_details.*,user_unit.*,users.*,master_designation.*,unit.unit_shortname,unit.id as unit_id');
      $this->db->from('personal_details');
      $this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
      $this->db->join('users', 'user_unit.user_id = users.id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('unit', 'user_unit.unit_id = unit.id');
      if(empty($results))
      {
          $this->db->where_in('user_unit.unit_id', $unit_id);
      }
      else
      { 
        $this->db->group_start();
         $this->db->where_in('user_unit.unit_id', $unit_id);
         foreach ($results as $value) { 
         $this->db->or_where('user_unit.unit_id', $value['id']);
         }
         $this->db->group_end();
          
      }
      
      $this->db->where('personal_details.user_id != ', 1);
      $this->db->where('users.status', 1);
      if($login_user_id){
          $this->db->where('personal_details.user_id != ', $login_user_id);
      }
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

    // public function findparentuser($unit_id,$selected_ids,$login_user_id)
    // {
 
    //   $this->db->select('*');
    //   $this->db->from('personal_details');
    //   $this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
    //   $this->db->join('users', 'user_unit.user_id = users.id');
    //   $this->db->join('master_designation', 'master_designation.id = users.designation_id');
    //   $this->db->where_in('user_unit.unit_id', $unit_id);
    //   $this->db->where('personal_details.user_id != ', 1);
    //   if()
    //   $this->db->where('users.status', 1);
     
    //   $query = $this->db->get();
    // // echo $this->db->last_query();exit;
    //     $result = $query->result_array();
    //     if($query->num_rows() > 0){
    //       return $result;
    //     }
    //     else{
    //       return [];
    //     }
    // }


    public function getSingleTraining($id){
        $this->db->select('*');
        $this->db->from('master_training');
        $this->db->where_in('id', $id);
        $query  = $this->db->get();
        $result = $query->result_array();
        if($query->num_rows() > 0)
        {
            return $result;
        }else{
            return [];
        }
    }
     public function findUsers($training_id)
    {
      $this->db->select('training_staff.*,personal_details.*,user_unit.*,users.*,master_designation.*,unit.unit_shortname,unit.id as unit_id');
      $this->db->from('training_staff');
      $this->db->join('personal_details', 'training_staff.user_id = personal_details.user_id');
      $this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
      $this->db->join('users', 'training_staff.user_id = users.id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('unit', 'user_unit.unit_id = unit.id');
      $this->db->where('training_staff.training_id', $training_id);
      $query = $this->db->get();
    // echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }

    public function getStafflist($id)
    {
      $data=array(
        'personal_details.fname',
         'personal_details.lname',
         'unit.unit_name'
      );

      $this->db->select($data);
      $this->db->from('training_staff');
      $this->db->join('personal_details', 'personal_details.user_id = training_staff.user_id');
      $this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
      $this->db->join('unit', 'unit.id = user_unit.unit_id');
      $this->db->where('training_staff.training_id', $id);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
        $result = $query->result_array();
        if(count($result)>0)
        {
            return $result;
        }
        else
        {
            return null;
        }
        
    }


    public function findUsersforedit($training_id)
    {
      $this->db->select('personal_details.user_id,personal_details.fname,personal_details.lname,unit.unit_shortname,unit.id as unit_id');
      $this->db->from('training_staff');
      $this->db->join('personal_details', 'training_staff.user_id = personal_details.user_id');
      $this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
      $this->db->join('users', 'training_staff.user_id = users.id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('unit', 'user_unit.unit_id = unit.id');
      $this->db->where('training_staff.training_id', $training_id);
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }


public function checkUnitIdinTraining($training_id,$unit_id)
    {
      if($unit_id)
      {
        $this->db->select('parent_unit');
        $this->db->from('unit');
        $this->db->where('unit.id', $unit_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();  
      }

      $this->db->select('*');
      $this->db->from('master_training');
      $this->db->where('master_training.id', $training_id);
      if($result[0]['parent_unit']!=0)
      {
        $this->db->group_start();
        $this->db->like('master_training.unit', $unit_id);
        $this->db->or_like('master_training.unit', $result[0]['parent_unit']);
        $this->db->group_end();
      }
      else
      {
        $this->db->like('master_training.unit', $unit_id);
      }
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      if(count($result)>0)
      {
        $status='true';
      }
      else
      {
        $status='false';
      }
      return $status;
    }

    
      public function updateTrainingDetails($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('master_training',$data);
        if($this->db->affected_rows() > 0){
          return 1;
        }else{
          return 2;
        }
    }

    public function updaterotaByuserid($user_id,$from_date,$to_date)
    {
      $status=array('shift_id'=>2);
      $this->db->where('user_id', $user_id); 
      $this->db->where('date >=',$from_date);
      $this->db->where('date<=',$to_date);

      $status=$this->db->update('rota_schedule',$status);
     //echo $this->db->last_query();exit;
      return $status;

    }

      public function deleteStaffTraining($id)
    {
        $this->db->delete('training_staff', array('training_id' => $id));
       
        return 1;
    }

    
    public function insertTrainingStaffDetails($staffDetails){
      $query = $this->db->insert("training_staff", $staffDetails);
      $insert_id = $this->db->insert_id();
      if($query){
        return $insert_id;
      }else{
        return '';
      }
    }

// public function findusers($unit_id)
// {
//  $this->db->select('*');
//  $this->db->from('personal_details');
//  $this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
//  $this->db->where_in('user_unit.unit_id', $unit_id);
//  $query = $this->db->get();
//     // echo $this->db->last_query();exit;
//     $result = $query->result_array(); 
//     return $result;


// }
    // public function findUsers($note_id)
    // {
    //   $this->db->select('*');
    //   $this->db->from('notes_staff');
    //   $this->db->join('personal_details', 'notes_staff.user_id = personal_details.user_id');
    //   $this->db->join('users', 'notes_staff.user_id = users.id');
    //   $this->db->where('notes_staff.note_id', $note_id);
    //   $query = $this->db->get();
    //     $result = $query->result_array();
    //     return $result;
    // }

/*public function alltraining()
{
  $this->db->select('*');
  $this->db->from('master_training'); 
  // $this->db->join('unit', 'unit.id = master_training.unit'); 
  $query = $this->db->get();
     //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;
}*/

public function alltrainingsbyadmin($id)
{ 
  //print_r($id);exit();
  if(count($id) > 0)
      {  
                  $this->db->select('unit.id');
                  $this->db->from('unit');
                  $this->db->where('parent_unit',  $id);
                  $query = $this->db->get();
                  //echo $this->db->last_query();exit();
                  $result = $query->result_array();  
      }
     // print_r($result);exit();
  $this->db->select('*');
  $this->db->from('master_training'); 
  // $this->db->join('user_unit', 'user_unit.unit_id = master_training.unit');
  // $this->db->where('user_unit.user_id', $user_id);
  // if(count($unitids) > 0){
 //     foreach ($unitids as $key => $unitid) {
 //      // $this->db->or_where_in($unitid['unit_id'],unit);
 //       if($unitid>0)
  //    $this->db->or_where("unit IN (".$unitid['unit_id'].")",NULL, false);
 //     }

  // }
  //$this->db->order_by('id','desc');
  if(count($id) > 0)
  {
     if(count($result)>0)
     {
        $this->db->or_where("unit IN (".$id.")",NULL, false);
        foreach ($result as $key => $unitid) { //print_r($unitid);exit();
          if($unitid['id']>0){
            $this->db->or_where("unit IN (".$unitid['id'].")",NULL, false);
          }
        }

     }
     else
     {
        $this->db->or_where("unit IN (".$id.")",NULL, false);
     }
  }
  $query = $this->db->get();
    //echo $this->db->last_query(); exit;
    $result = $query->result_array(); 
    return $result;
}

public function alltrainings($search =null,$limit = null,$start = null,$order = null,$dir = null)
{
  $this->db->select('*');
  $this->db->from('master_training'); 
  // $this->db->join('user_unit', 'user_unit.unit_id = master_training.unit');
  // $this->db->where('user_unit.user_id', $user_id);
  // if(count($unitids) > 0){
 //     foreach ($unitids as $key => $unitid) {
 //      // $this->db->or_where_in($unitid['unit_id'],unit);
 //       if($unitid>0)
  //    $this->db->or_where("unit IN (".$unitid['unit_id'].")",NULL, false);
 //     }

  // }
  //$this->db->order_by('id','desc');
  if($search){

    $this->db->like('master_training.title',$search);
    $this->db->or_like('master_training.description',$search);
    $this->db->or_like('master_training.date_from',$search);
    $this->db->or_like('master_training.time_from',$search);
    $this->db->or_like('master_training.date_to',$search);
    $this->db->or_like('master_training.time_to',$search);
    $this->db->or_like('master_training.training_hour',$search);
    $this->db->or_like('master_training.place',$search);
    $this->db->or_like('master_training.point_of_person',$search);
    $this->db->or_like('master_training.creation_date',$search);
  }
  if($limit>0)
  {
  $this->db->limit($limit,$start);
  }
  $this->db->order_by($order,$dir);
  $query = $this->db->get();
     // echo $this->db->last_query(); exit;
    $result = $query->result_array(); 
    return $result;
}

public function alltrainingsCount($search = null)
{
  $this->db->select('*');
  $this->db->from('master_training'); 
  // $this->db->join('user_unit', 'user_unit.unit_id = master_training.unit');
  // $this->db->where('user_unit.user_id', $user_id);
  // if(count($unitids) > 0){
 //     foreach ($unitids as $key => $unitid) {
 //      // $this->db->or_where_in($unitid['unit_id'],unit);
 //       if($unitid>0)
  //    $this->db->or_where("unit IN (".$unitid['unit_id'].")",NULL, false);
 //     }

  // }
  //$this->db->order_by('id','desc');
  if($search){

    $this->db->like('master_training.title',$search);
    $this->db->or_like('master_training.description',$search);
    $this->db->or_like('master_training.date_from',$search);
    $this->db->or_like('master_training.time_from',$search);
    $this->db->or_like('master_training.date_to',$search);
    $this->db->or_like('master_training.time_to',$search);
    $this->db->or_like('master_training.training_hour',$search);
    $this->db->or_like('master_training.place',$search);
    $this->db->or_like('master_training.point_of_person',$search);
    $this->db->or_like('master_training.creation_date',$search);
  }
  $query = $this->db->get();
// echo $this->db->last_query();exit;
  return $query->num_rows();
 }
//newly added on august 19

public function allmanagertrainingDetails($search = null,$unit_id,$limit = null,$start = null,$order = null,$dir = null,$count = false)
{
  $this->db->select('master_training.*');
  $this->db->from('master_training'); 
  $this->db->join('training_staff', 'training_staff.training_id = master_training.id','left');
  $this->db->join('user_unit', 'user_unit.user_id = training_staff.user_id');
  $this->db->where('user_unit.unit_id', $unit_id);
  $this->db->group_by('master_training.id');
  if($search){

    $this->db->group_start();
    $this->db->like('master_training.title',$search);
    $this->db->or_like('master_training.description',$search);
    $this->db->or_like('master_training.date_from',$search);
    $this->db->or_like('master_training.time_from',$search);
    $this->db->or_like('master_training.date_to',$search);
    $this->db->or_like('master_training.time_to',$search);
    $this->db->or_like('master_training.training_hour',$search);
    $this->db->or_like('master_training.place',$search);
    $this->db->or_like('master_training.point_of_person',$search);
    $this->db->or_like('master_training.creation_date',$search);
    $this->db->group_end();
  }
  $this->db->order_by($order,$dir);
  if($count  == true){
    $query = $this->db->get();
    return $query->num_rows();

  }
  else{
    if($limit>0)
    {
    $this->db->limit($limit,$start);
    }
    $query = $this->db->get();
  }
   //echo $this->db->last_query(); exit;
    $result = $query->result_array(); 
    return $result;

}

public function alladmintrainingDetails($search = null,$id,$limit = null,$start = null,$order = null,$dir = null,$count = false)
{

  if($id > 0)
  { 
                  $this->db->select('unit.id');
                  $this->db->from('unit');
                  $this->db->where('parent_unit',  $id);
                  $query = $this->db->get();
                  $result = $query->result_array();  
  }


  $this->db->select('master_training.*');
  $this->db->from('master_training'); 
  $this->db->join('training_staff', 'training_staff.training_id = master_training.id','left');
  $this->db->join('user_unit', 'user_unit.user_id = training_staff.user_id');
  $this->db->order_by($order,$dir);

   if($id > 0)
  {
     if(count($result)>0)
     {
        $this->db->group_start();
          $this->db->where('user_unit.unit_id', $id);
          foreach ($result as $key => $unitid) { //print_r($unitid);exit();
            if($unitid['id']>0){
              $this->db->or_where('user_unit.unit_id', $unitid['id']);
            }
          }
        $this->db->group_end();

     }
     else
     {
        $this->db->where('user_unit.unit_id', $id);
     }
  }

  if($search){

    $this->db->group_start();
    $this->db->like('master_training.title',$search);
    $this->db->or_like('master_training.description',$search);
    $this->db->or_like('master_training.date_from',$search);
    $this->db->or_like('master_training.time_from',$search);
    $this->db->or_like('master_training.date_to',$search);
    $this->db->or_like('master_training.time_to',$search);
    $this->db->or_like('master_training.training_hour',$search);
    $this->db->or_like('master_training.place',$search);
    $this->db->or_like('master_training.point_of_person',$search);
    $this->db->or_like('master_training.creation_date',$search);
    $this->db->group_end();
  }
  $this->db->group_by('master_training.id');
  if($count  == true){
    $query = $this->db->get();
    return $query->num_rows();

  }
  else{
    if($limit>0)
    {
    $this->db->limit($limit,$start);
    }
    $query = $this->db->get();
  }
  //echo $this->db->last_query(); exit;
    $result = $query->result_array(); 
    return $result;

}

//end


public function alltrainingmanager($unit_id)
{
   $this->db->select('*');
  $this->db->from('master_training'); 
  // $this->db->join('user_unit', 'user_unit.unit_id = master_training.unit');
  $this->db->where_in('unit', $unit_id);
  // if(count($unitids) > 0){
 //     foreach ($unitids as $key => $unitid) {
 //      // $this->db->or_where_in($unitid['unit_id'],unit);
 //       if($unitid>0)
  //    $this->db->or_where("unit IN (".$unitid['unit_id'].")",NULL, false);
 //     }

  // }
  //$this->db->order_by('id','desc');
  $query = $this->db->get();
    // echo $this->db->last_query(); exit;
    $result = $query->result_array(); 
    return $result;

}

public function getUnitIdOfUser($id)
{
  $this->db->select('unit');
  $this->db->from('master_training'); 
  $this->db->join('user_unit', 'user_unit.unit_id = master_training.unit');
  $this->db->where('user_id', $id);
 
  $query = $this->db->get();
  //echo $this->db->last_query(); exit;
  $result = $query->result_array(); 
  return $result;
}
 
public function alltrainingByManager($unit)
{
  $this->db->select('*');
  $this->db->from('master_training');
  // $this->db->join('user_unit', 'user_unit.unit_id = master_training.unit');
  $this->db->where_in('master_training.unit', $unit);
  $query = $this->db->get();
    //echo $this->db->last_query(); exit();
    $result = $query->result_array(); 
    return $result;
}

public function alltraingsbyUser($id)
{
  $this->db->select('*');
  $this->db->from('master_training');
  $this->db->join('training_staff', 'training_staff.training_id = master_training.id');
  $this->db->where('training_staff.user_id', $id);
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;


}

public function traingsbyUser($id)
{
    $this->db->select('*');
  $this->db->from('master_training');
  $this->db->join('training_staff', 'training_staff.training_id = master_training.id');
  $this->db->where('training_staff.training_id', $id);
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result; 

}

 
  public function inserttraining($dataform){
      $query = $this->db->insert("master_training", $dataform);
      $insert_id = $this->db->insert_id();
      //echo $this->db->last_query();exit;
      if($query){
        return $insert_id;
      }else{
        return '';
      }
    }


public function getunitname($cat_id)
{ 
  $this->db->select('unit_name');
  $this->db->from('unit'); 
  $this->db->where_in('id', $cat_id);
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;

}
public function getUser($cat_id)
{
  $this->db->select('fname,lname');
  $this->db->from('personal_details'); 
  $this->db->where_in('user_id', $cat_id);
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;

}

 
 // public function updateuser($id,$user_id,$dataform){
         
 //         $this->db->where('training_id', $id);
 //         $this->db->where('user_id', $user_id);
 //         $this->db->update('training_staff',$dataform);
 //        //echo $this->db->last_query();exit;
 //         }
// public function updatetraining($id,$user_id){

//          $this->db->select('*');
//         $this->db->from('training_staff'); 
//         $this->db->where('user_id', $id);
//         $this->db->where('training_id', $user_id);
        
//         // $this->db->join('unit', 'unit.id = master_training.unit'); 
//         $query = $this->db->get();
//           //echo $this->db->last_query();exit;
//           $result = $query->result_array(); 
//           return $result;
//          }

 public function deletetraining($id)
      {
          $this->db->delete('training_staff', array('training_id' => $id));
          $this->db->delete('training_feedback', array('training_id' => $id));
          $this->db->delete('master_training', array('id' => $id));  
          return 1;
      }

// public function getTraining($id)
// {
//     $this->db->select('*');
//  $this->db->from('master_training'); 
//  $this->db->where('id', $id);
//  // $this->db->join('unit', 'unit.id = master_training.unit'); 
//  $query = $this->db->get();
//      //echo $this->db->last_query();exit;
//     $result = $query->result_array(); 
//     return $result;

// }

public function getTrainingTime($id)
{
  $this->db->select('time_from,time_to');
  $this->db->from('master_training'); 
  $this->db->where('id', $id);
  // $this->db->join('unit', 'unit.id = master_training.unit'); 
  $query = $this->db->get();
     //echo $this->db->last_query();exit;
  $result = $query->result_array(); 
  return $result;
}

public function findusersofTraining($unit,$search= null,$limit= null,$start= null,$order= null,$dir= null,$designationFilter = null)
{   

   if(count($unit) > 0)
      {  
                  $this->db->select('unit.id');
                  $this->db->from('unit');
                  $this->db->where_in('parent_unit',  $unit);
                  $query = $this->db->get();
                  //echo $this->db->last_query();exit();
                  $result = $query->result_array();  
      }

  $data=array( 'users.id as user_id','personal_details.fname','personal_details.lname','users.email','unit.id as unit_id','unit.unit_name','unit.unit_shortname','master_designation.designation_name'

  );
//   $data=array( 'users.id as user_id','GROUP_CONCAT(personal_details.fname) as fname','GROUP_CONCAT(personal_details.lname) as lname','GROUP_CONCAT(users.email) as email',
//   'GROUP_CONCAT(unit.id) as unit_id','GROUP_CONCAT(unit.unit_name) as unit_name','GROUP_CONCAT(unit.unit_shortname) as unit_shortname','GROUP_CONCAT(master_designation.designation_name) as designation_name'

// );
      $this->db->select($data);
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      $this->db->join('unit', 'unit.id = user_unit.unit_id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
     
      if(count($result)>0)
      {
         $this->db->where_in('user_unit.unit_id',$unit);
         $this->db->or_group_start();
         foreach ($result as $value) {
          $this->db->or_where_in('user_unit.unit_id',$value['id']);
         }
         $this->db->group_end();
      }
      else
      {
          $this->db->where_in('user_unit.unit_id',$unit);
      }

      if($search){
        $this->db->like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search);
        $this->db->or_like('master_designation.designation_name',$search);
      }
      if($designationFilter){
        $this->db->where('master_designation.designation_name',$designationFilter);
      }
      if($limit>0)
      {
      $this->db->limit($limit,$start);
      }
      $this->db->order_by($order,$dir);
      $this->db->group_by('users.id');
      $query = $this->db->get();
      // echo $this->db->last_query();exit;
      $result = $query->result_array();
      return $result;
}

public function findusersofTrainingCount($unit,$search = null,$designationFilter = null)
{   

   if(count($unit) > 0)
      {  
                  $this->db->select('unit.id');
                  $this->db->from('unit');
                  $this->db->where_in('parent_unit',  $unit);
                  $query = $this->db->get();
                  //echo $this->db->last_query();exit();
                  $result = $query->result_array();  
      }

  $data=array( 'users.id as user_id','GROUP_CONCAT(personal_details.fname) as fname','GROUP_CONCAT(personal_details.lname) as lname','GROUP_CONCAT(users.email) as email',
      'GROUP_CONCAT(unit.id) as unit_id','GROUP_CONCAT(unit.unit_name) as unit_name','GROUP_CONCAT(unit.unit_shortname) as unit_shortname','GROUP_CONCAT(master_designation.designation_name) as designation_name'
    
  );
      $this->db->select($data);
      $this->db->from('users');
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('user_unit', 'user_unit.user_id = users.id');
      $this->db->join('unit', 'unit.id = user_unit.unit_id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
     
      if(count($result)>0)
      {
         $this->db->where_in('user_unit.unit_id',$unit);
         $this->db->or_group_start();
         foreach ($result as $value) {
          $this->db->or_where_in('user_unit.unit_id',$value['id']);
         }
         $this->db->group_end();
      }
      else
      {
          $this->db->where_in('user_unit.unit_id',$unit);
      }
      if($search){
        $this->db->like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search);
        $this->db->or_like('master_designation.designation_name',$search);
      }
      if($designationFilter){
        $this->db->where('master_designation.designation_name',$designationFilter);
      }
      $this->db->group_by('users.id');
      $query = $this->db->get();
      $result = $query->result_array();
      return count($result);
}
// public function getTrainingUnits($id)
// {
//  $this->db->select('unit');
//  $this->db->from('master_training'); 
//  $this->db->where('id', $id);
//  // $this->db->join('unit', 'unit.id = master_training.unit'); 
//  $query = $this->db->get();
//      //echo $this->db->last_query();exit;
//     $result = $query->result_array(); 
//     return $result;
// }
 
public function finduseremail($user_id)
{
  $this->db->select('*');
  $this->db->from('users'); 
  $this->db->join('personal_details', 'personal_details.user_id = users.id'); 
  $this->db->where('users.id', $user_id);
  // $this->db->join('unit', 'unit.id = master_training.unit'); 
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;

}

public function insertfeedback($dataform)
{
   $this->db->insert('training_feedback', $dataform);
}

public function getfeedback($user_id, $trainingid)
{
  $this->db->select('*');
  $this->db->from('training_feedback');  
  $this->db->where('user_id', $user_id); 
  $this->db->where('training_id', $trainingid); 
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    return $result;

}


 public function getWeeklyTraining($params)
 {  
  $this->db->select('*');
  $this->db->from('master_training'); 
  $this->db->join('training_staff', 'training_staff.training_id = master_training.id'); 
  $this->db->where('training_staff.user_id', $params['user_id']);
  // $this->db->join('unit', 'unit.id = master_training.unit'); 
  $query = $this->db->get();
   // echo $this->db->last_query();exit;
  $result = $query->result_array(); 
  return $result;


 }
 public function findtraininguser($id)
 {
    $this->db->select('user_id');
    $this->db->from('training_staff');
    $this->db->join('master_training', 'master_training.id = training_staff.training_id'); 
    $this->db->where('training_staff.training_id', $id); 
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
 }
 public function findtrainingdates($id)
 {  
    $this->db->select('date_from,date_to');
    $this->db->from('master_training'); 
    $this->db->where('master_training.id', $id); 
    $query = $this->db->get();
   //echo $this->db->last_query();exit;
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }

 }

 public function removeTrainingFromRota($users,$from_date,$todate)
{
    $status=array('shift_id'=>0);
    $this->db->where_in('user_id', $users);
    $this->db->where('shift_id', 2);
    $this->db->where('date >=',$from_date);
    $this->db->where('date<=',$todate);

    $status=$this->db->update('rota_schedule',$status);
     //echo $this->db->last_query();exit;
    return $status;

   // $sql = "UPDATE rota_schedule SET shift_id=0 WHERE  user_id='".$user_id."' 
   // and shift_id=1 and (date BETWEEN '".$fromdate."' AND '".$todate."')";
   //    $query = $this->db->query($sql);
   //   // echo $this->db->last_query();exit;
   //    return true;
 }
  public function findUsersOnTraining($training_id){
    $this->db->select('training_staff.training_id,users.id as user_id,users.email,personal_details.fname,personal_details.lname,master_training.title,master_training.date_from,master_training.date_to');
    $this->db->from('training_staff');
    $this->db->join('users', 'training_staff.user_id = users.id'); 
    $this->db->join('personal_details', 'training_staff.user_id = personal_details.user_id');
    $this->db->join('master_training', 'training_staff.training_id = master_training.id');
    $this->db->where('training_staff.training_id', $training_id);
    $this->db->where('training_staff.published',1);
    $query = $this->db->get();
     //echo $this->db->last_query();exit;
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
  }
  public function getTrainingDetails($params){
    $users = implode ( "', '", $params['users'] );
    $sql="SELECT master_training.*, training_staff.user_id,training_staff.training_id,users.email,personal_details.lname,personal_details.fname
    FROM master_training
    JOIN training_staff on training_staff.training_id = master_training.id
    JOIN users on training_staff.user_id = users.id
    JOIN personal_details on personal_details.user_id = training_staff.user_id
    WHERE
    training_staff.published=0
    AND
    training_staff.user_id IN('".$users."')
    AND 
    (( '".$params['to_date']."' >= date_from && '".$params['from_date']."' <= date_to) ||
    (date_from >= '".$params['from_date']."' && date_to <= '".$params['from_date']."'))";

    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
  }
  public function updatePublishedStatus($user_id,$training_id){
    $status=array('published'=>1);
    $this->db->where('user_id', $user_id);
    $this->db->where('training_id', $training_id);
    $status=$this->db->update('training_staff',$status);
    return $status;
  }
  public function getTrainingDatesByid($training_id)
  {
    $this->db->select('date_from,date_to');
    $this->db->from('master_training'); 
    $this->db->where('id', $training_id);
    $query = $this->db->get();
    $result = $query->result_array(); 
    //print_r($result);exit();
    return $result[0]['date_to'];
  }
  public function getTrainingStaffs($training_id){
    $this->db->select('*');
    $this->db->from('training_staff'); 
    $this->db->where('training_staff.training_id', $training_id);
    $query = $this->db->get();
    $result = $query->result_array(); 
    return $result;
  }
  public function getTrainingStaffsBeforeEdit($id){
    $result = $this->getTrainingStaffs($id);
    return $result;
  }
  public function getTrainingDetailsForMail($id){
    $this->db->select('*');
    $this->db->from('master_training');
    $this->db->where('id', $id);
    $query = $this->db->get();
    $result = $query->result_array(); 
    return $result;
  }
}
?>