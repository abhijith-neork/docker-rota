<?php
class Workschedule_model extends CI_Model 
{

  

    public function getUserworkschedule($userid)
    {
        $this->db->select('user_id,sunday,monday,tuesday,wednesday,thursday,friday,saturdy');
        $this->db->from('workschedule');
        $this->db->where('workschedule.user_id', $userid);
        $query = $this->db->get();
        //  print $this->db->last_query();
      // exit();
        $offday = array();
        if($query->num_rows()>0){
          $result = $query->result_array();
          if($result[0]['sunday'] == 1){
            array_push($offday, "sunday");
          }
          if($result[0]['monday'] == 1){
            array_push($offday, "monday");
          }
          if($result[0]['tuesday'] == 1){
            array_push($offday, "tuesday");
          }
          if($result[0]['wednesday'] == 1){
            array_push($offday, "wednesday");
          }
          if($result[0]['thursday'] == 1){
            array_push($offday, "thursday");
          }
          if($result[0]['friday'] == 1){
            array_push($offday, "friday");
          }
          if($result[0]['saturdy'] == 1){
            array_push($offday, "saturdy");
          }
          return $offday;
        }else{
          return array();
        }
    }


    public function insertworks($dataforms)
      {

        // $this->db->where('user_id', $user_id);
         $this->db->insert('workschedule',$dataforms); 
         $this->db->insert_id(); 
       
      }

    public function updateworks($user_id,$dataforms)
      {
        $this->db->where('user_id', $user_id);
        $this->db->update('workschedule',$dataforms);
      }
    
    public function checkuser($user_id)
      {
        $this->db->select('id');
        $this->db->from('workschedule');
        $this->db->where('user_id', $user_id);
         $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
         
        return $query->row_array();

      }
    
  }
?>