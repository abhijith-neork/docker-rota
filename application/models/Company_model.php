<?php
	class Company_model extends CI_Model 
	{
		public function getCompanyDetails()
		{
			$this->db->select('*');
			$this->db->from('company');
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
		}
		public function update($id,$updatedDetails){
		    
			$this->db->where('id', $id);
			$this->db->update('company',$updatedDetails);
			
			//echo $this->db->last_query();exit;
		}
		public function findsitetitle()
		{
			$this->db->select('company_name');
			$this->db->from('company');
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			$result = $query->result_array();
			return $result;
		}

		public function getAdminUsers($unit_id,$date)
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
			$this->db->join('rota_schedule', 'rota_schedule.user_id = users.id');
			$this->db->group_start();
			$this->db->where('users.group_id >=', 3); 
			$this->db->or_where('users.group_id =', 1);
			$this->db->group_end();
			$this->db->where('rota_schedule.date', $date); 
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

		public function findunit($user_id)
		{
			$this->db->select('unit_id');
			$this->db->from('user_unit'); 
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			$result = $query->result_array();
			return $result;

		}

		// 
	}
?>