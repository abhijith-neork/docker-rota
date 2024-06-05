<?php
class Crone_model extends CI_Model 
{

  public function finduserholidays()
  {
    $this->db->select('id,annual_holliday_allowance,actual_holiday_allowance,start_date');
    $this->db->from('users');
    $this->db->where('id!=',1);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  } 

  public function update_allowance($id,$actual_holiday_allowance)
  {
  	    $actual_holiday_allowance=array('annual_holliday_allowance'=>$actual_holiday_allowance);
  	  	$this->db->where('id', $id);
        $this->db->update('users',$actual_holiday_allowance);
       //echo $this->db->last_query();exit;
  }

 }
 ?>