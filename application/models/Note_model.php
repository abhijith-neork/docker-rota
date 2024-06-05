<?php
	class Note_Model extends CI_Model 
	{
		public function getUnits($id)
		{
			if($id){
				$array = array('user_unit.user_id' => $id, 'unit.status' => 1);
				$this->db->select('user_unit.*,unit.*');
				$this->db->from('user_unit');
				$this->db->join('unit', 'user_unit.unit_id = unit.id');
				$this->db->where($array);
				$query  = $this->db->get();
				$result = $query->result_array();
							
				if($query->num_rows() > 0)
				{
					return $result;
				}else{
					return [];
				}
			}else{
			    $this->db->select('id,unit_name');
				$this->db->from('unit');
				$this->db->where('status', 1);
				$query = $this->db->get();
				$result = $query->result_array();
				if($query->num_rows() > 0){
					return $result;
				}
				else{
					return [];
				}
			}
		}
		// public function findUser($unit_id,$login_user_id)
		// {
		// 	$this->db->select('*');
		// 	$this->db->from('personal_details');
		// 	$this->db->join('user_unit', 'user_unit.user_id = personal_details.user_id');
		// 	$this->db->join('users', 'user_unit.user_id = users.id');
		// 	$this->db->join('master_designation', 'master_designation.id = users.designation_id');
		// 	$this->db->where_in('user_unit.unit_id', $unit_id);
		// 	$this->db->where('personal_details.user_id != ', 1);
		// 	$this->db->where('users.status', 1);
		// 	if($login_user_id){
		// 		$this->db->where('personal_details.user_id != ', $login_user_id);
		// 	}
		// 	$query = $this->db->get();
		// 	 //echo $this->db->last_query();exit;
		//     $result = $query->result_array();
		//     if($query->num_rows() > 0){
		//     	return $result;
		//     }
		//     else{
		//     	return [];
		//     }
		// }

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
		/*Function to insert details to holliday table*/
		public function insertNoteDetails($noteDetails){
			$query = $this->db->insert("notes", $noteDetails);
			$insert_id = $this->db->insert_id();
			//echo $this->db->last_query();exit;
			if($query){
				return $insert_id;
			}else{
				return '';
			}
		}
		public function insertNoteStaffDetails($staffDetails){
			$query = $this->db->insert("notes_staff", $staffDetails);
			$insert_id = $this->db->insert_id();
			if($query){
				return $insert_id;
			}else{
				return '';
			}
		}
		public function find_Notification_Users($note_id,$config)
		{
			$this->db->select('personal_details.fname,personal_details.lname,master_designation.designation_name');
			$this->db->from('notes_staff');
			$this->db->join('personal_details', 'notes_staff.user_id = personal_details.user_id');
			$this->db->join('users', 'notes_staff.user_id = users.id');
			$this->db->join('master_designation', 'master_designation.id = users.designation_id');
			$this->db->where('notes_staff.note_id', $note_id);
			if (!empty($config['search_value'])) {
		    $search_value_escaped = $this->db->escape_like_str($config['search_value']);
        $this->db->group_start();
        $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
        $this->db->or_like('master_designation.designation_name', $search_value_escaped);
        $this->db->group_end();
			}
			$this->db->order_by($config['column_name'], $config['order_direction']);
			$this->db->limit($config['length'], $config['start']);
			$query = $this->db->get();
	    $result = $query->result_array();
	    return $result;
		}
		public function find_Notification_Users_Filter_Count($note_id,$config)
		{
			$this->db->select('personal_details.fname,personal_details.lname,master_designation.designation_name');
			$this->db->from('notes_staff');
			$this->db->join('personal_details', 'notes_staff.user_id = personal_details.user_id');
			$this->db->join('users', 'notes_staff.user_id = users.id');
			$this->db->join('master_designation', 'master_designation.id = users.designation_id');
			$this->db->where('notes_staff.note_id', $note_id);
			if (!empty($config['search_value'])) {
				$search_value_escaped = $this->db->escape_like_str($config['search_value']);
        $this->db->group_start();
        $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
        $this->db->or_like('master_designation.designation_name', $search_value_escaped);
        $this->db->group_end();
			    /*$this->db->group_start();
			    $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $config['search_value']);
			    $this->db->or_like('master_designation.designation_name', $config['search_value']);
			    $this->db->group_end();*/
			}
			$query = $this->db->get();
	    return $query->num_rows();
		}
		public function getAllNotes($unitids,$config)
		{
			$this->db->select('notes.*,personal_details.fname,personal_details.lname');
			$this->db->from('notes');
			$this->db->join('personal_details', 'notes.updated_userid = personal_details.user_id', 'left'); // Replace 'users' with the actual user table name
			if(count($unitids) > 0){
				foreach ($unitids as $key => $unitid) {
					if($unitid>0){
						$this->db->or_where("unit_id IN (".$unitid.")",NULL, false);
					}
				}
			}
			// print("<pre>".print_r($config['selectedValues'],true)."</pre>");exit(); 
			if(count($config['selectedValues']) > 0){
				$this->db->where_in('notification_type', $config['selectedValues']);
			}
			if (!empty($config['search_value'])) {
			    $this->db->group_start();
			    $this->db->like('notes.title', $config['search_value']);
			    $this->db->or_like('notes.comment', $config['search_value']);
			    
			    // Concatenate fname and lname and apply search
			    $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $config['search_value']);

			    $this->db->group_end();
			}
			// $this->db->order_by('id', 'desc');
			// print $config['column_name'];
			// print $config['order_direction'];exit;
			$this->db->order_by($config['column_name'], $config['order_direction']);
			$this->db->limit($config['length'], $config['start']);
			$query = $this->db->get();
			$result = $query->result_array(); 
			return $result;
		}

		public function getAllNotesforAdmin($unitids,$config)
		{
			if($unitids){
	        $this->db->select('unit.id');
	        $this->db->from('unit');
	        $this->db->where('parent_unit', $unitids);
	        $query = $this->db->get();
	        //echo $this->db->last_query();exit();
	        $result = $query->result_array(); 
	        } 
	       //print_r($result);exit();
			$this->db->select('notes.*,personal_details.fname,personal_details.lname');
			$this->db->from('notes');
			$this->db->join('personal_details', 'notes.updated_userid = personal_details.user_id', 'left');
			/*if(count($result) > 0)
			{
				$this->db->or_where("unit_id IN (".$unitids.")",NULL, false);
				foreach ($result as $key => $unitid) { //print_r($unitid);exit();
					if($unitid['id']>0){
						$this->db->or_where("unit_id IN (".$unitid['id'].")",NULL, false);
					}
				}

			}
			else
			{
				$this->db->where("unit_id IN (".$unitids.")",NULL, false);
			}*/
			// Simplify the unit ID condition using WHERE IN
			    $unitIdsArray = array_merge([$unitids], array_column($result, 'id'));
			    $this->db->where_in('unit_id', $unitIdsArray);
			if(count($config['selectedValues']) > 0){
				// print "test";exit;
				$this->db->where_in('notification_type', $config['selectedValues']);
			}
			if (!empty($config['search_value'])) {
			    $this->db->group_start();
			    $this->db->like('notes.title', $config['search_value']);
			    $this->db->or_like('notes.comment', $config['search_value']);
			    
			    // Concatenate fname and lname and apply search
			    $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $config['search_value']);

			    $this->db->group_end();
			}
			$this->db->order_by($config['column_name'], $config['order_direction']);
			$this->db->limit($config['length'], $config['start']);
			$query = $this->db->get();
			 // echo $this->db->last_query();exit;
			$result = $query->result_array(); 
			return $result;
		}
		public function getAllNotesCount($unitids)
				{
					$this->db->select('*');
					$this->db->from('notes');
					if(count($unitids) > 0){
						foreach ($unitids as $key => $unitid) {
							if($unitid>0){
								$this->db->or_where("unit_id IN (".$unitid.")",NULL, false);
							}
						}
					}
					//$this->db->order_by('id','desc');
					$query = $this->db->get();
					return $query->num_rows();
				}
				public function getAllNotesFilterCount($unitids,$config)
				{
					$this->db->select('*');
					$this->db->from('notes');
					$this->db->join('personal_details', 'notes.updated_userid = personal_details.user_id', 'left');
					if(count($unitids) > 0){
						foreach ($unitids as $key => $unitid) {
							if($unitid>0){
								$this->db->or_where("unit_id IN (".$unitid.")",NULL, false);
							}
						}
					}
					if(count($config['selectedValues']) > 0){
						$this->db->where_in('notification_type', $config['selectedValues']);
					}
					if (!empty($config['search_value'])) {
					    $this->db->group_start();
					    $this->db->like('notes.title', $config['search_value']);
					    $this->db->or_like('notes.comment', $config['search_value']);
					    
					    // Concatenate fname and lname and apply search
					    $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $config['search_value']);

					    $this->db->group_end();
					}
					$query = $this->db->get();
					return $query->num_rows();
				}
				public function getAllNotesforAdminCount($unitids)
				{
					if($unitids){
			        $this->db->select('unit.id');
			        $this->db->from('unit');
			        $this->db->where('parent_unit', $unitids);
			        $query = $this->db->get();
			        //echo $this->db->last_query();exit();
			        $result = $query->result_array(); 
			        } 
			       //print_r($result);exit();
					$this->db->select('*');
					$this->db->from('notes');
					/*if(count($result) > 0)
					{
						$this->db->or_where("unit_id IN (".$unitids.")",NULL, false);
						foreach ($result as $key => $unitid) { //print_r($unitid);exit();
							if($unitid['id']>0){
								$this->db->or_where("unit_id IN (".$unitid['id'].")",NULL, false);
							}
						}

					}
					else
					{
						$this->db->where("unit_id IN (".$unitids.")",NULL, false);
					}*/
					$unitIdsArray = array_merge([$unitids], array_column($result, 'id'));
					$this->db->where_in('unit_id', $unitIdsArray);
					//$this->db->order_by('id','desc');
					$query = $this->db->get();
					return $query->num_rows();
				}
					public function getAllNotesforAdminFilterCount($unitids,$config)
					{
						if($unitids){
				        $this->db->select('unit.id');
				        $this->db->from('unit');
				        $this->db->where('parent_unit', $unitids);
				        $query = $this->db->get();
				        //echo $this->db->last_query();exit();
				        $result = $query->result_array(); 
				        } 
				       //print_r($result);exit();
						$this->db->select('*');
						$this->db->from('notes');
						$this->db->join('personal_details', 'notes.updated_userid = personal_details.user_id', 'left');
						/*if(count($result) > 0)
						{
							$this->db->or_where("unit_id IN (".$unitids.")",NULL, false);
							foreach ($result as $key => $unitid) { //print_r($unitid);exit();
								if($unitid['id']>0){
									$this->db->or_where("unit_id IN (".$unitid['id'].")",NULL, false);
								}
							}

						}
						else
						{
							$this->db->where("unit_id IN (".$unitids.")",NULL, false);
						}*/
						$unitIdsArray = array_merge([$unitids], array_column($result, 'id'));
						$this->db->where_in('unit_id', $unitIdsArray);
						if(count($config['selectedValues']) > 0){
							$this->db->where_in('notification_type', $config['selectedValues']);
						}
						if (!empty($config['search_value'])) {
						    $this->db->group_start();
						    $this->db->like('notes.title', $config['search_value']);
						    $this->db->or_like('notes.comment', $config['search_value']);
						    
						    // Concatenate fname and lname and apply search
						    $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $config['search_value']);

						    $this->db->group_end();
						}
						$query = $this->db->get();
						return $query->num_rows();
					}
		public function getAllNotes1($unitids){
			$this->db->select('notes.*,personal_details.*,user_unit.*');
			$this->db->from('notes');
			$this->db->join('user_unit', 'user_unit.user_id = notes.user_id');
			// $this->db->join('personal_details', 'notes.user_id = personal_details.user_id');
			if(count($unitids) > 0){
				$this->db->where_in('user_unit.unit_id', $unitids);
			}
			$query  = $this->db->get();
			$result = $query->result_array();
			if($query->num_rows() > 0)
			{
				return $result;
			}else{
				return [];
			}
		}
		public function getSingleNote($id){
		    $this->db->select('*');
		    $this->db->from('notes');
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
		
		public function updateNoteDetails($id,$data)
		{
		    $this->db->where('id', $id);
		    $this->db->update('notes',$data);
		    if($this->db->affected_rows() > 0){
		    	return 1;
		    }else{
		    	return 2;
		    }
		}
		
		public function deleteStaffNotes($noteid)
		{
		    $this->db->delete('notes_staff', array('note_id' => $noteid));
		   
		    return 1;
		}
		public function findUsers($note_id)
		{
			$this->db->select('*');
			$this->db->from('notes_staff');
			$this->db->join('personal_details', 'notes_staff.user_id = personal_details.user_id');
			$this->db->join('users', 'notes_staff.user_id = users.id');
			$this->db->join('master_designation', 'master_designation.id = users.designation_id');
			$this->db->where('notes_staff.note_id', $note_id);
			$query = $this->db->get();
		    $result = $query->result_array();
		    return $result;
		}
		public function findstaffname($user_id)
		{
			$this->db->select('fname,lname,email');
			$this->db->from('personal_details'); 
			$this->db->join('users', 'users.id = personal_details.user_id');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
		    $result = $query->result_array();
		    return $result;
		}

		public function getupdatedusernote($id)
		{

			$this->db->select('fname,lname');
			$this->db->from('personal_details'); 
			$this->db->where('user_id', $id);
			$query = $this->db->get();
		    $result = $query->result_array();
		    return $result;

		}
	}
?>