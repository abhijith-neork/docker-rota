<?php
class Moveshift_model extends CI_Model 
{

/* geting rota details by using date and user_id*/
	public function GetRotaDetailsByUser($params,$stat)
	{ 

		  $data=array(
		  	'personal_details.fname',
		  	'personal_details.lname',
		  	'unit.unit_name',
		  	'master_shift.shift_name',
		  	'rota_schedule.*'
		  );
		  $this->db->select($data);
	      $this->db->from('rota_schedule'); 
	      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id'); 
	      $this->db->join('unit', 'unit.id = rota_schedule.unit_id'); 
	      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 

	      $this->db->where('rota_schedule.date',$params['date']); 
	      
	      if($stat=='get')
	      {
	      	$this->db->where('rota_schedule.user_id',$params['user_id']);
	      	$this->db->group_start();
	        $this->db->where('rota_schedule.shift_id !=',0); 
	      	$this->db->where('rota_schedule.shift_id !=',68);
	      	$this->db->group_end();
	      }
	      else
	      {
            $this->db->where('rota_schedule.unit_id',$params['unitfor']);
	      }
	      $query = $this->db->get();
	      //echo $this->db->last_query();exit;
	      $result = $query->result_array(); 
	     // print_r($result);exit();
	      if(empty($result))
	      {
	        $results=NULL;
	      }
	      else
	      {
	        $results=$result[0];
	      }

      	  return $results;
	}

	public function GetShiftDetailsByforUnitID($params)
	{
		 $data=array(
		  	'personal_details.fname',
		  	'personal_details.lname',
		  	'unit.unit_name',
		  	'master_shift.shift_name',
		  	'rota_schedule.date',
		  	'rota_schedule.unit_id',
		  	'rota_schedule.rota_id'
		  );
		  $this->db->select($data);
	      $this->db->from('rota_schedule'); 
	      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id'); 
	      $this->db->join('unit', 'unit.id = rota_schedule.unit_id'); 
	      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 

	      $this->db->where('rota_schedule.date', $params['date']); 
	      $this->db->where('rota_schedule.user_id', $params['user_id']); 
	      $this->db->where('rota_schedule.unit_id', $params['unitfor']);  
	      $query = $this->db->get();
	       //echo $this->db->last_query();exit();
	      $result = $query->result_array(); 
	     // print_r($result);exit();
	      if(empty($result))
	      {
	        $results=0;
	      }
	      else
	      {
	        $results=$result[0];
	      }

      	  return $results;
	}

	public function GetRotaIdOfOtherUsersInForUnit($params)
	{
		$data=array(
		  	'rota_schedule.rota_id',
		  	'rota.id as primary_id'
		  );
		  $this->db->select($data);
	      $this->db->from('rota_schedule'); 
	      $this->db->join('personal_details', 'personal_details.user_id = rota_schedule.user_id'); 
	      $this->db->join('unit', 'unit.id = rota_schedule.unit_id'); 
	      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
	      $this->db->join('rota', 'rota.id = rota_schedule.rota_id');

	      $this->db->where('rota_schedule.date', $params['date']); 
	      //$this->db->where('rota_schedule.user_id', $params['user_id']); 
	      $this->db->where('rota_schedule.unit_id', $params['unitfor']); 
	      $this->db->group_by('rota_schedule.rota_id','desc'); 
	      $query = $this->db->get();
	       //echo $this->db->last_query();exit();
	      $result = $query->result_array(); 
	     // print_r($result);exit();
	      if(empty($result))
	      {
	        $results=0;
	      }
	      else
	      {
	        $results=$result[0];
	      }

      	  return $results;

	}

	public function insertRotaDetails($response,$params)
	{ 
	   $this->db->insert('rota_settings',$response); 
	   $insert_id = $this->db->insert_id();
	   if($insert_id)
	   {
	   	 $datas=array(
				   	 	'rota_settings'=>$insert_id,
				   	 	'start_date'=>$params['first_date'],
				   	 	'end_date'=>$params['end_date'],
				   	 	'unit_id'=>$params['unitfor'],
				   	 	'created_date'=>date('Y-m-d H:i:s'),
				   	 	'updated_date'=>date('Y-m-d H:i:s'),
				   	 	'created_user_id'=>$params['created_userid'],
				   	 	'published'=>1,
				   	 	'month'=>$params['month'],
				   	 	'year'=>$params['year']
	   	 );
	   	 $this->db->insert('rota',$datas); 
	   	 $rota_id = $this->db->insert_id();
	   }
	   else
	   {
	   	  $rota_id = '';
	   }


	   return $rota_id;
   
	}

	public function inserRotascheduleDetails($rota)
	{
		$status=$this->db->insert('rota_schedule',$rota);
		return $status;
	}
	
	/* updating  shifts as selected shift_id using date ,userid and unitfor */
	public function UpdateShiftOfRotaByUser($params)
	{ //print_r($params);exit();
		    $data=array('shift_id'=>$params['shift'],'shift_hours'=>$params['shift_hours']);
	  		$this->db->where('user_id', $params['user_id']);
	  		$this->db->where('date', $params['date']);
	  		$this->db->where('unit_id', $params['unitfor']);
		    $this->db->update('rota_schedule',$data);
		    //echo $this->db->last_query();exit;
		    if($this->db->affected_rows() > 0){
		    	return 1;
		    }else{
		    	return 0;
		    }
	}

    /* updating  shifts as 0 using date ,userid */
	public function UpdateShiftOfRotaByUserAsZero($params)
	{  
            $data=array('shift_id'=>0,'shift_hours'=>0);
	  		$this->db->where('user_id', $params['user_id']);
	  		$this->db->where('date', $params['date']);
		    $this->db->update('rota_schedule',$data);
		   //echo $this->db->last_query();exit;
		    if($this->db->affected_rows() > 0){
		    	return 1;
		    }else{
		    	return 0;
		    }
	}

   /* shift start time and end time from master shift table using shift_id */

	public function GetShiftDetails($shift_id)
	{

		  $this->db->select('start_time,end_time');
	      $this->db->from('master_shift');
	      $this->db->where('id',$shift_id);
	      $query = $this->db->get();
	      //echo $this->db->last_query();exit;
	      $result = $query->result_array(); 
	     // print_r($result);exit();
	      if(empty($result))
	      {
	        $results=NULL;
	      }
	      else
	      {
	        $results=$result[0];
	      }

      	  return $results;

	}

	/* get previous shifts start time and end time from master shift table using shift_id joined with rota_schedule table*/

	public function GetPreviousShiftData($params)
	{
		  $this->db->select('master_shift.start_time,master_shift.end_time,rota_schedule.date');
	      $this->db->from('rota_schedule');
	      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
	      $this->db->where('rota_schedule.user_id',$params['user']); 
	      $this->db->where('rota_schedule.date',$params['previous']);
	      $this->db->order_by('master_shift.start_time','desc');
	      $query = $this->db->get();
	      //echo $this->db->last_query();exit;
	      $result = $query->result_array(); 
	      //print_r($result);exit();
	      if(empty($result))
	      {
	        $results=NULL;
	      }
	      else
	      {
	        $results=$result[0];
	      }

      	  return $results;

	}

	/* get next shifts start time and end time from master shift table using shift_id joined with rota_schedule table*/
	public function GetNextShiftData($params)
	{
  		  $this->db->select('master_shift.start_time,master_shift.end_time,rota_schedule.date');
	      $this->db->from('rota_schedule');
	      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
	      $this->db->where('rota_schedule.user_id',$params['user']); 
	      $this->db->where('rota_schedule.date',$params['next']);
	      $this->db->order_by('master_shift.start_time','desc');
	      $query = $this->db->get();
	      //echo $this->db->last_query();exit;
	      $result = $query->result_array(); 
	      //print_r($result);exit();
	      if(empty($result))
	      {
	        $results=NULL;
	      }
	      else
	      {
	        $results=$result[0];
	      }

      	  return $results;

	}


	public function getUsername($id)
	{

			    $data = array(
			      'personal_details.fname',
			      'personal_details.lname',
			      'users.email'
			    );
			    $this->db->select($data);
			    $this->db->from('personal_details');
			    $this->db->join('users', 'users.id = personal_details.user_id');
			    $this->db->where('personal_details.user_id', $id);
			    $query = $this->db->get();
			    $result = $query->result_array();
			    if(empty($result))
			    {
			    	return NUll;
			    }
			    else
			    {

			    	return $result[0];
			    }
  	}

  	public function getshiftname($id)
	{

			    $data = array(
			      'master_shift.shift_name'
			    );
			    $this->db->select($data);
			    $this->db->from('master_shift');
			    $this->db->where('master_shift.id', $id);
			    $query = $this->db->get();
			    $result = $query->result_array();
			    //echo $this->db->last_query();exit;
			    if(empty($result))
			    {
			    	return NUll;
			    }
			    else
			    {

			    	return $result[0]['shift_name'];
			    }
  	}

  	public function getUnitname($id)
	{

			    $data = array(
			      'unit.unit_name'
			    );
			    $this->db->select($data);
			    $this->db->from('unit');
			    $this->db->where('unit.id', $id);
			    $query = $this->db->get();
			    $result = $query->result_array();
			    if(empty($result))
			    {
			    	return NUll;
			    }
			    else
			    {

			    	return $result[0]['unit_name'];
			    }
  	}

  	 public function finduserdataforallforreport($unit_id,$status)
    {
        //print_r($status);exit();
        $this->db->select('id');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit_id);
        $query = $this->db->get();
        $result = $query->result_array();
 
         $this->db->select('personal_details.fname,personal_details.lname,user_unit.user_id');
         $this->db->from('user_unit'); 
         $this->db->join('personal_details', 'personal_details.user_id = user_unit.user_id','left');
         $this->db->join('users', 'users.id = personal_details.user_id');
         // if($status!=0)
         // {
         $this->db->where('users.status', 1);
        
         // }
         if(!empty($result))
         {
          $this->db->group_start();
          $this->db->where('unit_id', $unit_id);
           foreach ($result as $value) {
           $this->db->or_where('unit_id', $value['id']);
           }
           $this->db->group_end();
         }
         else
         {
          $this->db->where('unit_id', $unit_id);
         }
         $this->db->order_by('personal_details.fname', "asc");
          
         $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }


    public function getShiftHoursByShiftID($shift_id)
    {
    	$data = array(
			      'master_shift.targeted_hours'
			    );
			    $this->db->select($data);
			    $this->db->from('master_shift');
			    $this->db->where('master_shift.id', $shift_id);
			    $query = $this->db->get();
			    //echo $this->db->last_query();exit;
			    $result = $query->result_array();
			    if(empty($result))
			    {
			    	return 0;
			    }
			    else
			    {

			    	return $result[0]['targeted_hours'];
			    }

    }

	
	
}

?>