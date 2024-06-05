<?php // settings_helper.php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function siteSettings($name){
	$result = array();
    $CI =& get_instance();
   	$CI->db->select($name);
	$CI->db->from('site_settings');
    $CI->db->where('id', 1);
	$result = $CI->db->get();
	 // print $CI->db->last_query(); //
	$result = $result->result_array();
 
	if(count($result)> 0){
		return $result[0][$name];
	}else{
		return '';
	}
}
function buttonaccess($permission){
    $CI =& get_instance();
	$status = $CI->auth->restrictbutton($permission); 
    if($status==false){
    	echo 'style="display:none"';
    }
}
function editbuttonaccess($permission){
    $CI =& get_instance();
	$status = $CI->auth->restrictbutton($permission); //print_r($status);
    if($status==false){
    	echo 'disabled';
    }
}
function getCompanydetails($name){
	$result = array();
    $CI =& get_instance();
    	
    	if($name!='')
    	 	$CI->db->select($name);
    	else
   			$CI->db->select('*');
	$CI->db->from('company');
 	$result = $CI->db->get();
	 // print $CI->db->last_query(); //
	$result = $result->result_array();
 
	 if($name!=''){
		return $result[0][$name];
	}else{
		return $result[0];
	}
	 
}
function get_string_between($string, $start, $end){
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) return '';
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}
function getAllSubUnitsIds($unit_id){
	$CI =& get_instance();
	$CI ->load->model('Unit_model');
 	$unit_ids = $CI->Unit_model->returnMainAndSubUnitsIds($unit_id);
 	return $unit_ids;
}
function getParentUnit($unit_id){
	$CI =& get_instance();
	$CI ->load->model('Unit_model');
 	$parent_unit = $CI->Unit_model->getPreviousparent($unit_id);
 	return $parent_unit;
}
function getManagersSupervisorsDetails($unit_ids,$designation_id,$search_value,$user_id){
	$CI =& get_instance();
	$CI->db->select('
		personal_details.mobile_number,
		users.email,
		personal_details.fname,
		personal_details.lname,
		users.group_id,
		users.id,
		user_unit.unit_id'
	);
	$CI->db->from('users');
	$CI->db->join('personal_details', 'personal_details.user_id = users.id');
	$CI->db->join('user_unit', 'user_unit.user_id = users.id');
	$CI->db->where_in('user_unit.unit_id', $unit_ids);
	$CI->db->where_in('users.group_id', $search_value);
	$CI->db->where('users.id !=', $user_id);
	$query = $CI->db->get();
	$result = $query->result_array();
	return $result;
}

function getManagersSupervisors($manager_type,$unit_id,$is_subunit,$designation_id,$user_id){
	$CI =& get_instance();
	$search_value = [];
	$unit_ids = array();
	//check manager type. if it is 6 only unit admins need to send email except the session user.
	if($manager_type == 6){
		//so search array contains only group id 6
		array_push($search_value, 6);
	//for any other user except unit admin, mail send to all managers and unit admins
	//of the same unit except session user
	}else{
		array_push($search_value, 6);
		array_push($search_value, 3);
	}
	//New change. If the unit is a subunit have to send mail to all other 
	//subunits admins/managers
	if($is_subunit == 1){
		$parent_unit = getParentUnit($unit_id);
		$unit_ids = getAllSubUnitsIds($unit_id);
		$index = array_search($parent_unit,$unit_ids,true);
		unset($unit_ids[$index]);
	}else{
		array_push($unit_ids, $unit_id);
	}
	$result = getManagersSupervisorsDetails($unit_ids,$designation_id,$search_value,$user_id);
	return $result;
	/*// print("<pre>".print_r($result,true)."</pre>");exit();
	//Unit has no managers
	if(count($result) == 0){
		//check whether the unit is a subunit.
		if($is_subunit == 1){
			//if subunit find the parent unit. And find managers correspond to parent
			//unit id
			$CI->db->select('*');
          	$CI->db->from('unit');
          	$CI->db->where('id', $unit_id);
          	$parent_query = $CI->db->get();
          	$parent_result = $parent_query->result_array();
          	$parent_unitid = $parent_result[0]['parent_unit'];
          	$result = getManagersSupervisorsDetails($parent_unitid,$designation_id,$search_value,$user_id);
          	return $result;
		}else{
			return $result;
		}
	}else{
		return $result;
	}*/
}
function getUnitAdmins($manager_type,$unit_id,$is_subunit,$designation_id,$user_id){
	$CI =& get_instance();
	$search_value = [];
	array_push($search_value, 6);
	$result = getManagersSupervisorsDetails($unit_id,$designation_id,$search_value,$user_id);
	// print("<pre>".print_r($result,true)."</pre>");exit();
	//Unit has no managers
	if(count($result) == 0){
		//check whether the unit is a subunit.
		if($is_subunit == 1){
			//if subunit find the parent unit. And find managers correspond to parent
			//unit id
			$CI->db->select('*');
          	$CI->db->from('unit');
          	$CI->db->where('id', $unit_id);
          	$parent_query = $CI->db->get();
          	$parent_result = $parent_query->result_array();
          	$parent_unitid = $parent_result[0]['parent_unit'];
          	$result = getManagersSupervisorsDetails($parent_unitid,$designation_id,$search_value,$user_id);
          	return $result;
		}else{
			return $result;
		}
	}else{
		return $result;
	}
}
function getManagersSupervisors_leave($manager_type,$unit_id,$is_subunit,$designation_id){
	$CI =& get_instance();
	$search_value = '';
	//Function return the details of administrators only
	if($manager_type == 2){
		$search_value = 5;
	//Function return the details of managers only
	}else if($manager_type == 3 || $manager_type == 5){
		$search_value = 6;
	}else if($manager_type == 6){
		$search_value = 1;
	}else{
		//both managers and administrators
	}
	$result = getManagersSupervisorsDetails($unit_id,$designation_id,$search_value);
	//Unit has no managers
	if(count($result) == 0){
		//check whether the unit is a subunit.
		if($is_subunit == 1){
			//if subunit find the parent unit. And find managers correspond to parent
			//unit id
			$CI->db->select('*');
          	$CI->db->from('unit');
          	$CI->db->where('id', $unit_id);
          	$parent_query = $CI->db->get();
          	$parent_result = $parent_query->result_array();
          	$parent_unitid = $parent_result[0]['parent_unit'];
          	$result = getManagersSupervisorsDetails($parent_unitid,$designation_id,$search_value);
          	return $result;
		}else{
			return $result;
		}
	}else{
		return $result;
	}
}
?>
