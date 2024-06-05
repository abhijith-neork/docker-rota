<?php
	class Agency_model extends CI_Model 
	{
		public function addAgencyData($agency_staff_data){
			$this->db->insert("agency_staffs", $agency_staff_data);
		}
		public function getAgencyStaff($date)
		{
			$agency_array = array();
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
			$this->db->where('unit.unit_shortname', "AG");
			$query = $this->db->get();
			if($query->num_rows() > 0){
			    $agency_result = $query->result_array();
			    $count = count($agency_result);
			    for($i=0;$i<$count;$i++){
			    	$user_id = $agency_result[$i]['user_id'];
			    	$this->db->select('*');
			    	$this->db->from('rota_schedule');
			    	$this->db->where('date', $date);
			    	$this->db->where('user_id', $user_id);
			    	$this->db->where('user_id', $user_id);
			    	$this->db->where('shift_id!=0');
			    	$new_query = $this->db->get();
			    	if($new_query->num_rows() > 0){
			    		//nthing
			    	}else{
			    		array_push($agency_array, $agency_result[$i]);
			    	}
			    }
			    return $agency_array;
		  }else{
		    return array();
		  }    
		}
		public function getShiftDetailsOfAbsentUser($params){
			$this->db->select('leave_log.shift_id');
			$this->db->from('leave_log');
			$this->db->where('user_id', $params['user_id']);
			$this->db->where('date', $params['date']);
			$this->db->where('unit_id', $params['unit_id']);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();
				$this->db->select('*');
				$this->db->from('master_shift');
				$this->db->where('id', $result[0]['shift_id']);
				$queryshift = $this->db->get();
				if($queryshift->num_rows() > 0){
					$shift_details = $queryshift->result_array();
					return $shift_details;
				}else{
					return array();
				}
			}
			else{
				return array();
			}
		}
		public function checkAgencyStaffAssigned($user_id,$unit_id,$date){
			$this->db->select('*');
			$this->db->from('agency_staffs');
			$this->db->where('user_id', $user_id);
			$this->db->where('unit_id', $unit_id);
			$this->db->where('date', $date);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return "false";
			}else{
				return "true";
			}   
		}
		public function checkSystemStaffAssigned($unit_id,$date){
			$this->db->select('*');
			$this->db->from('available_requests');
			$this->db->join('available_requested_users', 'available_requested_users.avialable_request_id = available_requests.id');
			$this->db->where('available_requests.to_unitid', $unit_id);
			$this->db->where('available_requests.date', $date);
			$this->db->where('available_requested_users.status', 1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return "false";
			}else{
				return "true";
			}   
		}
		public function checkUserIsAgencyStaff($user_id){
			$this->db->select(
				'rota_schedule.*,
				user_unit.unit_id'
			);
			$this->db->from('rota_schedule');
			$this->db->join('user_unit', 'user_unit.user_id = rota_schedule.user_id');
			$this->db->join('unit', 'user_unit.unit_id = unit.id');
			$this->db->where('rota_schedule.user_id', $user_id);
			$this->db->where('unit.unit_shortname', "AG");
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$result = $query->result_array();
				return "true";
			}else{
				return "false";
			}
		}  
	}
?>