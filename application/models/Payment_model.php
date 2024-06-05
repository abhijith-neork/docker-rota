<?php

class Payment_model extends CI_Model 
{

	
	public function insertpayment($dataform)
	{
		$result=$this->db->insert("master_payment_type", $dataform);
    if($result==1)
    {
      return 'true';
    }
    else
    {
      return 'false';
    }
	}


	public function allpaymenttype()
	{
		$this->db->select('*');
		$this->db->from('master_payment_type');
    $this->db->where('status', 1); 
		$query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
	}

  public function findpayment($status)
  {
    $this->db->select('*');
    $this->db->from('master_payment_type');
    if($status!=4){
    $this->db->where_in('status', $status); }
    $query = $this->db->get();
        // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result; 
  }
  
  public function deletedpaymenttype($id)
      {

        // $status = $this->db->delete('master_payment_type', array('id' => $id));
        $status=array('status'=>3);
        $this->db->where('id', $id);
        $status=$this->db->update('master_payment_type',$status);
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

      public function findpaymenttype($id)
      {
      $this->db->select('*');
  		$this->db->from('master_payment_type');
  		$this->db->where('id', $id);
  		$query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }


     public function getpaymentDetails($params)
      {
          $this->db->select('*');
          $this->db->from('master_payment_type');
          if($params['id'])
              $this->db->where_not_in('id', $params['id']);
          if($params['payment_type'])
               $this->db->where('payment_type', $params['payment_type']);
          
          $query = $this->db->get();
         // echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
      }
    
       public function findUsersPayment($id)
      {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('payment_type', $id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;

      }
    

    

      public function update($id,$datahome){
         
         $this->db->where('id', $id);
         $result=$this->db->update('master_payment_type',$datahome);
         return $result;
         }
}

?>