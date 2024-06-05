<?php
class Bankholiday_model extends CI_Model 
{
	public function findAllHolidays()
	{
		$this->db->select('*');
		$this->db->from('bank_holiday');
		$this->db->where('status', 1);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function checkHoliday($date){
		$this->db->select('*');
		$this->db->from('bank_holiday');
		$this->db->where('date', $date);
		$this->db->where('status', 1);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return array(
				'status' => "true", 
				'result' => $query->result_array()
			);
		}else{
			return array(
				'status' => "false", 
				'result' => []
			);
		}
	}
	public function insertHoliday($datas)
	{
		$this->db->select('*');
		$this->db->from('bank_holiday');
		$this->db->where('date', $datas['date']);
		$this->db->where('status', 1);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return array(
				'status' => "false",
				'message'=> "Already Added"
			);
		}else{
			$this->db->insert("bank_holiday", $datas);
			return array(
				'status'=> "true",
				'message' =>"Added Successfully"
			);
		}
		
	}
	public function findHoliday($id)
	{
		$this->db->select('*');
		$this->db->from('bank_holiday'); 
		$this->db->where('id', $id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function updateHoliday($date,$id){
		$data=array('date'=>$date);
		$this->db->where('id', $id);
		$status=$this->db->update('bank_holiday',$data);
	}
	public function deleteHoliday($id){
		$data=array('status'=>0);
		$this->db->where('id', $id);
		$status=$this->db->update('bank_holiday',$data);
	}
}?>