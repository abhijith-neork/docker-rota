<?php
class Rota_model extends CI_Model 
{

  public function getRota($id)
      {
      	$this->db->select('*');
		    $this->db->from('rota');
		    $this->db->where('id', $id);
		    $query = $this->db->get();
       // echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
      }
      public function validateRota($sDate,$eDate,$unitID,$rotaId)
      {
          $this->db->select('*');
          $this->db->from('rota');
          $this->db->where('id', $rotaId);
          $this->db->where('start_date', $sDate);
          $this->db->where('end_date', $eDate);
          $this->db->where('unit_id', $unitID);
          $query = $this->db->get();
          // echo $this->db->last_query();exit;
          $result = $query->result_array();
          if(count($result)>0){
              if(count($result)==1){
                  return 1;
              }
              else{
                  return count($result);
              }
              
          }else{
              return 0;
          }
        
      }  
      public function checkifRota($sDate,$eDate,$unitID)
      {
          $this->db->select('*');
          $this->db->from('rota');
          $this->db->where('start_date', $sDate);
          $this->db->where('end_date', $eDate);
          $this->db->where('unit_id', $unitID);
          $query = $this->db->get();
          // echo $this->db->last_query();exit;
          $result = $query->result_array();
         // print_r($result);exit;
          if(count($result)>0){
              if(count($result)==1){
                  $status = array("status"=>1,"publish"=>$result[0]['published']);
                  return $status;
              }
              else{
                  return $status = array("status"=>0,"publish"=>0);
              }
              
          }else{
              return $status = array("status"=>0,"publish"=>0);
          }
          
      }  
      public function getDates($params)
      { 
        $unit_id_array = array();
        $this->db->select('unit.parent_unit');
        $this->db->from('unit'); 
        $this->db->where('id', $params['unit_id']);
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result)){ 
          if($result[0]['parent_unit'] != 0){
            array_push($unit_id_array, $params['unit_id']);
          }else{
            array_push($unit_id_array, $params['unit_id']);
            $this->load->model('Unit_model');
            $sub_units = $this->Unit_model->findsubunits($params['unit_id']);
            for($i=0;$i<count($sub_units);$i++){
              array_push($unit_id_array, $sub_units[$i]['id']);
            }
          }
          $this->db->select('*');
          $this->db->from('rota');
          $this->db->join('unit', 'rota.unit_id = unit.id');
          $this->db->where_in('rota.unit_id', $unit_id_array);
          $this->db->where('MONTH(rota.start_date)',$params['month']);
          $this->db->where('YEAR(rota.start_date)',$params['year']);
          $this->db->order_by("start_date", "asc");
          $query = $this->db->get();
          $result = $query->result_array();
          return $result;
        }
      }
      public function getUnpublishedDatesOfSingleUnit($id)
      {
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where('unit_id', $id);
        $this->db->where('published', 0);
        $this->db->order_by('end_date', "asc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
      }

       public function getShiftgaphours()
      {
        $this->db->select('shift_gap');
        $this->db->from('company'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
      }

       public function findRotadetails($id)
      { //print_r($date);exit();
        $this->db->select('date');
        $this->db->from('rota_schedule');
        $this->db->where('unit_id', $id);
        $this->db->order_by('date',"desc");
        //$this->db->where('date >=', $date); 
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array();
        return $result;
      }

      public function getDatesOfSingleUnit($id)
      {
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where('unit_id', $id);
        $this->db->where('published', 1);
        $this->db->order_by('end_date', "asc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
      }

            public function getData($date)
      {
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where('start_date >=', $date);
        $this->db->where('end_date <=', $date);
        $query = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $query->result_array();
        return $result;
      }
      public function checkRotaStatus($date,$unit_id)
      {
        $this->db->select('rota_schedule.date,rota.id');
        $this->db->from('rota_schedule');
        $this->db->join('rota', 'rota_schedule.rota_id = rota.id');
        $this->db->where('rota_schedule.date', $date);
        $this->db->where('rota.published',1);
        $this->db->where('rota.unit_id',$unit_id);
        $this->db->group_by('rota_schedule.date');
        $query = $this->db->get();
        // echo $this->db->last_query();exit();
        $result = $query->result_array();
        return $result;
      }

      public function getAllUnPublishedRota($unit_id){
        $array = array(            
          'unit_id' => $unit_id,
          'published' => 0
        );
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where($array);
        $this->db->order_by('start_date','ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
          return array(
            'status'=>1,
            'result'=>$query->result_array()
          );
        }else{
          return array(
            'status'=>2,
            'result'=>[]
          );
        }
      }
      public function publishSavedRota($unit_id,$rota_id){
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where('id', $rota_id);
        $query = $this->db->get();
        $rota_ids = $query->result_array();
        $updateData = array(
          'published'=>1
        );
        $this->db->where('id', $rota_id);
        $this->db->update('rota', $updateData);
        if($this->db->affected_rows() > 0 ){
          return array(
            'status'=>1,
            'message'=>"success",
            'rota_ids'=>$rota_ids
          );
        }else{
          return array(
            'status'=>2,
            'message'=>"failed",
            'rota_ids'=>$rota_ids
          );
        }
      }
      public function publishData($r_ids){
        $array = array(            
          // 'unit_id' => $unit_id,
          'published' => 0
        );
        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where_in('id',$r_ids);
        $query = $this->db->get();
        $rota_ids = $query->result_array();

        $this->db->select('*');
        $this->db->from('rota');
        $this->db->where_in('id',$r_ids);
        $this->db->update('rota',array('published'=>1));
        if($this->db->affected_rows() > 0){
          return array(
            'status'=>1,
            'message'=>"success",
            'rota_ids'=>$rota_ids
          );
        }else{
          return array(
            'status'=>2,
            'message'=>"failed",
            'rota_ids'=>$rota_ids
          );
        }
      }
      public function addRotaDetails($rotaDetails){  
         // print("<pre>".print_r($rotaDetails,true)."</pre>");exit();
          $array = array(
              'rota_settings'=>$rotaDetails['rota_settings'],
              'start_date' => $rotaDetails['start_date'],
              'end_date' => $rotaDetails['end_date'],
              'unit_id' => $rotaDetails['unit_id']
          );
          $this->db->select('id');
          $this->db->from('rota');
          $this->db->where($array);
          $query = $this->db->get();
          if(count($query->result_array()) > 0){
              $result  = $query->result_array();
              $rota_id = $result[0]['id'];
              return array(
                  'rota_id' => $rota_id,
                  'status' => 1
              );
          }else{
              $query = $this->db->insert('rota',$rotaDetails);
              $rota_id = $this->db->insert_id();
              return array(
                  'rota_id' => $rota_id,
                  'status' => 2
              );
          }
      }
      public function getRotaDetails($rota_id,$start_date,$end_date)
      {
           $this->db->select('shift_id,shift_hours,day as shift_dayname,shift_category as shiftcat,user_id,date as start,unit_id,from_unit');
           $this->db->from('rota_schedule');
           $this->db->where('rota_id', $rota_id);
           $this->db->where('date >=', $start_date);
           $this->db->where('date <=',  $end_date);
           $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

      }

    public function finddefaultshiftByuser($id)
    {
    $this->db->select('default_shift,total_targeted_hours');
    $this->db->from('users');
    $this->db->join('master_shift', 'master_shift.id = users.default_shift');
    $this->db->where('users.id', $id);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

    }

  public function RotaByUser($id)
  {
    $this->db->select('*');
    $this->db->from('rota_schedule');
    $this->db->where('user_id', $id);
    $query = $this->db->get();
       //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
  }

  public function findSettings($id)
  {
    $this->db->select('*');
    $this->db->from('rota_settings');
    $this->db->where('id', $id);
    $query = $this->db->get();
       //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;

  }
  public function findRotaUnit($id)
  {
    $this->db->select('unit_name');
    $this->db->from('unit');
    $this->db->where('id', $id);
    $query = $this->db->get();
    // print_r($this->db->error());exit();         
       // echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result; 
  }

  public function InsertRotaSettings($response)
  {  
   $params['day_shift_min']=$response['day_shift_min'];
   $params['day_shift_max']=$response['day_shift_max']; 
   $params['night_shift_min']=$response['night_shift_min']; 
   $params['night_shift_max']=$response['night_shift_max']; 
   $params['num_patients']=$response['num_patients']; 
   $params['one_one_patients']=$response['designation'];
   $params['nurse_day_count']=$response['nurse_day_count'];
   $params['nurse_night_count']=$response['nurse_night_count'];
   $params['creation_date']=date('Y-m-d H:i:s');  
   //print_r($params);exit();
   $this->db->insert('rota_settings',$params);
   $insert_id = $this->db->insert_id();

   return  $insert_id;

  }
  public function UpdateRotaSettings($response,$id)
  {
    $params = array();
    $params['day_shift_min']=$response['day_shift_min'];
    $params['day_shift_max']=$response['day_shift_max']; 
    $params['night_shift_min']=$response['night_shift_min']; 
    $params['night_shift_max']=$response['night_shift_max']; 
    $params['num_patients']=$response['num_patients']; 
    $params['one_one_patients']=$response['designation'];
    $params['nurse_day_count']=$response['nurse_day_count'];
    $params['nurse_night_count']=$response['nurse_night_count'];
    $this->db->where('id', $id);
    $this->db->update('rota_settings', $params);
    if($this->db->affected_rows() > 0 ){
      return "true";
    }else{
      return "flase";
    }
  }
  public function getRotaSettings($id)
  {
    $this->db->select('*');
    $this->db->from('rota_settings');
    $this->db->where('id',$id);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return [];
    }
  }

  public function getUserUnitDetails($data)
  { 
     $date = new DateTime("now");
     $curr_date = $date->format('Y-m-d ');
    $this->db->select('*');
          $this->db->from('rota_schedule');
          if($data['user_id'])
            $this->db->where('user_id', $data['user_id']);
          $this->db->where('date >=',$curr_date);
          $this->db->order_by('date', 'desc');
          $this->db->limit(1);
          $query = $this->db->get();
      //echo $this->db->last_query();exit;
          $result = $query->result_array();
        
          return $result;
  }
  public function getUsersForNofifyShift($rota_ids){
    $rota_id_array = array();
    for ($i = 0; $i < count($rota_ids); $i++)
    {
      array_push($rota_id_array, $rota_ids[$i]['id']);
    }
    $this->db->select('user_id,from_unit,unit_id,rota_id');
    $this->db->from('rota_schedule');
    $this->db->where_in('rota_schedule.rota_id', $rota_id_array);
    $this->db->group_by('rota_schedule.user_id');
    $query = $this->db->get();
    return array(
      'user_ids' => $query->result_array(),
      'rota_ids' => $rota_id_array
    );
    return $user_ids;
  }
  public function findUnitShiftFromRotaHistory($user_id,$rota_logid,$date){
    $array = array(
        'history_rota_schedule.user_id' => $user_id,
        'history_rota_schedule.rota_logid' => $rota_logid,
        'history_rota_schedule.date' => $date
    );
    $this->db->select('*');
    $this->db->from('history_rota_schedule'); 
    $this->db->join('master_shift', 'history_rota_schedule.shift_id = master_shift.id');
    $this->db->join('unit', 'history_rota_schedule.unit_id = unit.id');
    $this->db->where($array);
    //$this->db->where('rota_schedule.shift_id !=',0);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function findUnitShift($user_id,$date){
    $array = array(
        'rota_schedule.user_id' => $user_id,
        'rota_schedule.date' => $date
    );
    $this->db->select('*');
    $this->db->from('rota_schedule'); 
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
    $this->db->join('unit', 'rota_schedule.unit_id = unit.id');
    $this->db->where($array);
    $this->db->where('rota_schedule.shift_id !=',0);
    $query = $this->db->get();
    $result = $query->result_array();
    if(count($result) > 0){
      return $result;
    }else{
      $this->db->select('*');
      $this->db->from('rota_schedule'); 
      $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
      $this->db->join('unit', 'rota_schedule.unit_id = unit.id');
      $this->db->where($array);
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
    }
  }
  public function getNextMonthRota($params){
    $this->db->select('*');
    $this->db->from('rota'); 
    $this->db->where('month=',$params['month']);
    $this->db->where('year=',$params['year']); 
    if($params['unit_id']!='' || $params['unit_id'] !='undefined'){ //added bu siva on dec 11
          $this->db->where('unit_id=',$params['unit_id']);

    }
    $query = $this->db->get();
   // echo $this->db->last_query();exit;

    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
  }
  public function getShiftId($params){
    $shift_id = '';
    $this->db->select('rota_schedule.shift_id,master_shift.start_time,master_shift.end_time');
    $this->db->from('rota_schedule');
    $this->db->join('master_shift', 'rota_schedule.shift_id = master_shift.id');
    $this->db->where('unit_id=',$params['unit_id']);
    $this->db->where('date=',$params['date']);
    $this->db->where('user_id=',$params['user_id']);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
  }
  public function getDateDetails($rotaids){
    $this->db->select('rota.start_date,rota.end_date');
    $this->db->from('rota'); 
    $this->db->where_in('id', $rotaids);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $result = $query->result_array();
      return $result;
    }else{
      return array();
    }
  }
  public function insertRotaLog($params){
    $this->db->insert('rota_log',$params);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
  }
  public function insertHistoryRotaSchedule($params){
    $this->db->insert('history_rota_schedule',$params);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
  }
  public function findRotaHistory($limit,$start,$search,$params){
    // print("<pre>".print_r($params,true)."</pre>");exit();
    $this->db->select('rota_log.*,personal_details.fname,personal_details.lname');
    $this->db->from('rota_log');
    $this->db->join('personal_details', 'personal_details.user_id = rota_log.user_id');
    $this->db->join('users', 'users.id = rota_log.user_id');
    if($limit){
      if($limit!=0)
      {
        $this->db->limit($limit,$start);
      }
    }
    if($params['unit']){
      $this->db->where('unit_id',$params['unit']);
    }
    if($params['year']){
      $this->db->where('YEAR(rota_log.start_date)',$params['year']);
    }
    if($params['month']){
      $this->db->where('MONTH(rota_log.start_date)',$params['month']);
    }
    if($params['jobrole']){
      $this->db->where('users.designation_id',$params['jobrole']);
    }
    if($search){
      $serach_term = explode(" ", $search);
      if(count($serach_term) > 1){
        $this->db->group_start();
          $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        $this->db->group_end();
      }else{
        $this->db->group_start();
          $this->db->like('personal_details.fname',$search);
          $this->db->or_like('personal_details.lname',$search);
        $this->db->group_end();
      }
    }
    $this->db->order_by('rota_log.id',"desc");
    $query = $this->db->get();
     // echo $this->db->last_query();exit();
    $result = $query->result_array();
    return $result;
  }
  public function findRotaHistorybysubunit($limit,$start,$search,$params){
    // print("<pre>".print_r($params,true)."</pre>");exit();
    $this->db->select('rota_log.*,personal_details.fname,personal_details.lname');
    $this->db->from('rota_log');
    $this->db->join('personal_details', 'personal_details.user_id = rota_log.user_id');
    $this->db->join('users', 'users.id = rota_log.user_id');
    $this->db->join('rota_schedule', 'rota_schedule.user_id = rota_log.user_id');
    if($limit){
      if($limit!=0)
      {
        $this->db->limit($limit,$start);
      }
    }
    if($params['unit']){
      $this->db->where('rota_schedule.unit_id',$params['unit']);
    }
    if($params['year']){
      $this->db->where('YEAR(rota_log.start_date)',$params['year']);
    }
    if($params['month']){
      $this->db->where('MONTH(rota_log.start_date)',$params['month']);
    }
    if($params['jobrole']){
      $this->db->where('users.designation_id',$params['jobrole']);
    }
    if($search){
      $serach_term = explode(" ", $search);
      if(count($serach_term) > 1){
        $this->db->group_start();
          $this->db->like('concat_ws( " ",personal_details.fname,personal_details.lname )',$search);
        $this->db->group_end();
      }else{
        $this->db->group_start();
          $this->db->like('personal_details.fname',$search);
          $this->db->or_like('personal_details.lname',$search);
        $this->db->group_end();
      }
    }
    $this->db->group_by('rota_log.id');
    $this->db->order_by('rota_log.id',"desc");
    $query = $this->db->get();
     //echo $this->db->last_query();exit();
    $result = $query->result_array();
    return $result;
  }
  public function getPreviousHistory($user_id,$rota_log_id){
    $this->db->select('history_rota_schedule.*,rota_log.start_date');
    $this->db->from('history_rota_schedule');
    $this->db->join('rota_log', 'history_rota_schedule.rota_logid = rota_log.id');
    $this->db->where('history_rota_schedule.user_id',$user_id);
    $this->db->where('history_rota_schedule.rota_logid',$rota_log_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function gerRotaDetailsForPrintOption($params)
  {
    $unit_id_array = array();
    $this->load->model('Dashboard_model');
    //Find selected unit has subunits. If subunits create an array with all
    //sub units ids.
    $sub_units = $this->Dashboard_model->findBranches($params['unit_id']);
    if(count($sub_units) > 0){
      for($i=0;$i<count($sub_units);$i++){
        array_push($unit_id_array, $sub_units[$i]['id']);
      }
    }else{
      //If unit has no subunits create an array with only selected unit id
      $unit_id_array = [$params['unit_id']];
    }
    $this->db->select('rota.id as rota_id,rota.start_date,rota.end_date,unit.unit_name,rota.unit_id');
    $this->db->from('rota');
    $this->db->join('unit', 'rota.unit_id = unit.id');
    $this->db->where_in('rota.unit_id', $unit_id_array);

    $this->db->group_start();
    $this->db->where('MONTH(rota.start_date)',$params['month']);
    $this->db->where('YEAR(rota.start_date)',$params['year']);

    $this->db->or_where('MONTH(rota.end_date)',$params['month']);
    $this->db->where('YEAR(rota.end_date)',$params['year']);
    $this->db->group_end();
    $this->db->where_in('unit_id', $unit_id_array);
    if($this->session->userdata('user_type')==5) // added by swaraj on nov 13
    {
        $this->db->where('rota.published',1);
    }
    $this->db->order_by("start_date", "asc");
    $query = $this->db->get();
    //echo $this->db->last_query();exit();
    $result = $query->result_array();
    return $result;
  }
  public function removeHolidayFromRota($user_id,$fromdate,$todate){
      
      $sql = "UPDATE rota_schedule SET shift_id=0 WHERE  user_id='".$user_id."' and shift_id=1 and (date BETWEEN '".$fromdate."' AND '".$todate."')";
      $query = $this->db->query($sql);
 
     // echo $this->db->last_query();exit;
      return true;
      
  }
  public function getRotaStatus($id){
      $this->db->from('rota');
      $this->db->where('id', $id);
      $query = $this->db->get();
      $status = '';
      if($query->num_rows() > 0){
        $result = $query->result_array();
        if($result[0]['published'] == 1){
          $status = 'Published';
        }else{
          $status = 'Saved';
        }
      }
      return $status;
    }

    public function getRotaForUnitchangeBydateAndunitID($date,$unit_id,$user_id)
    {

      $this->db->select('date,rota_id');
      $this->db->from('rota_schedule');
      $this->db->where('date >=',$date);
      $this->db->where('unit_id',$unit_id);
      $this->db->where('user_id !=',$user_id);
      $this->db->group_by('date');
      $query = $this->db->get();
      if($query !== FALSE && $query->num_rows() > 0){
        //print_r($this->db->last_query());exit();
        $result = $query->result_array();
        if(count($result)>0)
        {
          return $result;
        }
        else
        {
          return 0;
        } 
      }else{
        return 0;
      }
    }

    public function checkRotaByuserDateandID($user_id,$unit_id,$date)
    {
      $this->db->select('date,rota_id');
      $this->db->from('rota_schedule');
      $this->db->where('date',$date);
      $this->db->where('unit_id',$unit_id);
      $this->db->where('user_id',$user_id);
      $query = $this->db->get();
      //print_r($this->db->last_query());exit();
      $result = $query->result_array();
      if(count($result)>0)
      {
        return 1;
      }
      else
      {
        return 0;
      } 
    }

  public function InsertRotaLockData($params){
    $this->db->insert('rota_lock',$params);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
  }
  public function checkRotaLockExist($params){
    $this->db->select('*');
    $this->db->from('rota_lock');
    $this->db->where('unit_id',$params['unit_id']);
    $this->db->where('start_date',$params['start_date']);
    $this->db->where('end_date',$params['end_date']);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function deleteRotaLockTable($userid){
    $this->db->where('user_id', $userid);
    $this->db->delete('rota_lock');
  }
  public function deleteRotaLockEntries(){
    $this->db->empty_table('rota_lock');
  }
  public function deleteSessionTableEntries(){
    $this->db->empty_table('session_table');
  }
  public function updateRotascheduleDayValue(){
    $sql = "UPDATE rota_schedule
            SET `day` = CASE
                WHEN `day` = 'Sat' THEN 'Sa'
                WHEN `day` = 'Sun' THEN 'Su'
                ELSE `day`
            END
            WHERE `day` IN ('Sat', 'Sun')
        ";
    $this->db->query($sql);
    return true;
  }
  public function checkRotaLock($id){
    $this->db->select('lock_status');
    $this->db->from('rota');
    $this->db->where('id',$id);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }
  public function updateRotaLockStatus($status,$id){
    $updateData = array(
        'lock_status' => $status
    );

    $this->db->where('id', $id);
    $this->db->update('rota', $updateData);

    // Check for errors
    if ($this->db->error() && $this->db->error()['message']) {
        // Error occurred, handle it
        $error = $this->db->error();
        return array(
            'status' => 0, // or any other status code to indicate failure
            'message' => "Database error: " . $error['message']
        );
    } else {
        // No error, check affected rows
        if ($this->db->affected_rows() > 0) {
            return array(
                'status' => 1,
                'message' => "Success",
                'url' => base_url().'admin/Rota/viewrota'
            );
        } else {
            return array(
                'status' => 2,
                'message' => "No rows updated",
            );
        }
    }
  }
}

?>