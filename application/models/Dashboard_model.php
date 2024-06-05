<?php
class Dashboard_model extends CI_Model 
{

 /*public function findRotaSettings($source_id)
 {

    $this->db->select('rota_settings.id,day_shift_min,day_shift_max,night_shift_min,night_shift_max,num_patients,one_one_patients,nurse_day_count,nurse_night_count,rota_schedule.date');
    $this->db->from('rota_settings');
    $this->db->join('rota', 'rota.rota_settings = rota_settings.id');
    $this->db->join('rota_schedule', 'rota_schedule.rota_id = rota.id');
    $this->db->where('rota.unit_id', $source_id);
    $this->db->group_by('rota_schedule.date');
    $query = $this->db->get();
  //echo $this->db->last_query();exit;
    $result = $query->result_array();
    return $result;
 }*/
public function findRotaSettingsSum($unit_id,$date,$params){
  $unit_id_array = array();
  $rotday_shift_min = 0;
  $rotday_shift_max = 0;
  $rotnight_shift_min = 0;
  $rotnight_shift_max = 0;
  $rotnurse_day_count = 0;
  $rotnurse_night_count = 0;
  $this->db->select('unit.parent_unit');
  $this->db->from('unit'); 
  $this->db->where('id', $unit_id);
  $query = $this->db->get();
  $result = $query->result_array();
  if($result[0]['parent_unit'] != 0){
    array_push($unit_id_array, $unit_id);
  }else{
    array_push($unit_id_array, $unit_id);
    $this->load->model('Unit_model');
    $sub_units = $this->Unit_model->findsubunits($unit_id);
    for($i=0;$i<count($sub_units);$i++){
      array_push($unit_id_array, $sub_units[$i]['id']);
    }
  }
  if(count($unit_id_array) > 0){
    $this->db->select("
      rota_schedule.date,
      rota_schedule.rota_id,
      rota_settings.*,
      rota.id
    ");
    $this->db->from('rota_schedule');
    $this->db->join('rota', 'rota_schedule.rota_id = rota.id');
    $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings');
    $this->db->where('rota_schedule.date',$date);
    $this->db->where_in('rota_schedule.unit_id',$unit_id_array);
    $this->db->where('MONTH(rota.start_date)',$params['month']);
    $this->db->where('YEAR(rota.start_date)',$params['year']);
    $this->db->where('rota.published', 1);
    $this->db->group_by("rota_schedule.rota_id");
    $query1 = $this->db->get();
    $result1 = $query1->result_array();
        if(count($result1) > 0){
      for($j=0;$j<count($result1);$j++){
        $rotday_shift_min += (float)$result1[$j]['day_shift_min'];
        $rotday_shift_max += (float)$result1[$j]['day_shift_max'];
        $rotnight_shift_min += (float)$result1[$j]['night_shift_min'];
        $rotnight_shift_max += (float)$result1[$j]['night_shift_max'];
        $rotnurse_day_count += (float)$result1[$j]['nurse_day_count'];;
        $rotnurse_night_count += (float)$result1[$j]['nurse_night_count'];
      }
    }
  }
  return array(
    'rotday_shift_min' => $rotday_shift_min,
    'rotday_shift_max' => $rotday_shift_max,
    'rotnight_shift_min' => $rotnight_shift_min,
    'rotnight_shift_max' => $rotnight_shift_max,
    'rotnurse_day_count' => $rotnurse_day_count,
    'rotnurse_night_count' => $rotnurse_night_count
  ); 
}
    
public function checkUnitHasRota($id){ //print_r($id);
  $this->db->select('*');
  $this->db->from('rota'); 
  $this->db->where('unit_id', $id);
  $this->db->where('published', 1);
  $query = $this->db->get();
 // echo $this->db->last_query();exit;
  $result = $query->result_array();
  return $result;
}
public function checkUnitHasSubUnit($id){
  $this->db->select('*');
  $this->db->from('unit'); 
  $this->db->where('parent_unit', $id);
  $query = $this->db->get();
  $result = $query->result_array();
  return $result;
}
public function checkUnitHasParentunit($id){
  $this->db->select('parent_unit');
  $this->db->from('unit'); 
  $this->db->where('id', $id);
  $query = $this->db->get();
  $result = $query->result_array();
  return $result;
}
public function findRotaSettings($source_id,$params)
    {  
      if($source_id!='')
        {
          $this->db->select('id');
          $this->db->from('unit'); 
          $this->db->where('parent_unit', $source_id);
          $query = $this->db->get();
          $result = $query->result_array();
        }
        $this->db->select("
            rota_schedule.shift_id,
            rota_schedule.rota_id,
            rota_schedule.unit_id,
            rota.rota_settings,
            rota_settings.day_shift_min,
            rota_settings.day_shift_max,
            rota_settings.night_shift_min,
            rota_settings.night_shift_max,
            rota_settings.num_patients,
            rota_settings.one_one_patients,
            rota_settings.nurse_day_count,
            rota_settings.nurse_night_count,
            rota_schedule.date
        ");
        $this->db->from('rota_schedule');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings');
        /*if(empty($result))
        {*/
          $this->db->where('rota_schedule.unit_id', $source_id);
          $this->db->group_by('rota_schedule.date');
        /*}
        else
        {*/
           /*$this->db->group_start();
          $this->db->where('rota_schedule.unit_id', $source_id);
          foreach ($result as $value) {
           $this->db->or_where('rota_schedule.unit_id', $value['id']);
          }
          $this->db->group_end();
          $this->db->group_by('rota_schedule.date,rota_schedule.unit_id');
          $this->db->order_by('rota_schedule.unit_id');
          $this->db->order_by('rota_schedule.date', 'ASC');   */ 
        //}
        $this->db->group_start();
        $this->db->where('MONTH(rota_schedule.date)',$params['month']);
        $this->db->where('YEAR(rota_schedule.date)',$params['year']);

        if($params['month']==12){
            $nextyear = $params['year']+1;
            
            $this->db->or_where('MONTH(rota_schedule.date)','01');
            $this->db->where('YEAR(rota_schedule.date)',$nextyear);
           // $this->db->group_end();
        }
        else{
           // $this->db->group_start();
            $nextmonth = $params['month']+1;
            $this->db->or_where('MONTH(rota_schedule.date)',$nextmonth);
            $this->db->where('YEAR(rota_schedule.date)',$params['year']);
           
        }
         $this->db->group_end();

        $this->db->group_by('rota_schedule.date');
        $query = $this->db->get();
         //echo $this->db->last_query();exit;
        if($query->num_rows() > 0){
            $result = $query->result_array();                
            return $result;
        }else{
            return array();
        }
    }

     public function findRotaSettingsbyunit1($params){ //print_r($params);
        $this->db->select("
            rota.rota_settings,
            rota.start_date,
            rota.end_date,
            rota_settings.day_shift_min,
            rota_settings.day_shift_max,
            rota_settings.night_shift_min,
            rota_settings.night_shift_max,
            rota_settings.num_patients,
            rota_settings.one_one_patients,
            rota_settings.nurse_day_count,
            rota_settings.nurse_night_count,
            rota.id, rota.unit_id as rotaunit_id
        ");
        $this->db->from('rota');
        $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings');
        $this->db->where('rota.unit_id',$params['unit']);
        $this->db->where('MONTH(rota.start_date)',$params['month']);
        $this->db->where('YEAR(rota.start_date)',$params['year']);
        $this->db->where('rota.published', 1);

        /*$this->db->where('rota.month',(int)$params['month']);
        $this->db->where('rota.year',$params['year']);*/
        $query = $this->db->get();
          // echo $this->db->last_query();
          // exit;
        if($query->num_rows() > 0){
            $result = $query->result_array();                
            return $result;
        }else{
            return array();
        }
     }
     public function findRotaSettingsbyunit($params)
    {    
      //print_r($params); exit();

     $this->db->select("
            rota.rota_settings,
            rota.start_date,
            rota.end_date,
            rota_settings.day_shift_min,
            rota_settings.day_shift_max,
            rota_settings.night_shift_min,
            rota_settings.night_shift_max,
            rota_settings.num_patients,
            rota_settings.one_one_patients,
            rota_settings.nurse_day_count,
            rota_settings.nurse_night_count,
            rota.id
        ");
        $this->db->from('rota');
        $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings');
        $this->db->join('rota_schedule', 'rota_schedule.rota_id = rota.id');
        $this->db->where('rota_schedule.unit_id',$params['unit']);
        $this->db->where('MONTH(rota.start_date)',$params['month']);
        $this->db->where('YEAR(rota.start_date)',$params['year']);
        $this->db->where('rota.published', 1);
        $this->db->group_by('rota.id');

        /*$this->db->where('rota.month',(int)$params['month']);
        $this->db->where('rota.year',$params['year']);*/
        $query = $this->db->get();
          // echo $this->db->last_query();
          // exit;
        if($query->num_rows() > 0){
            $result = $query->result_array();                
            return $result;
        }else{
            return array();
        }
    }
    public function findRotaIds($sub_units){
      $rota_ids = array();
      $this->db->select("*");
      $this->db->from("rota");
      $this->db->where_in('rota.unit_id', $sub_units);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $result = $query->result_array();
        for($i=0;$i<count($result);$i++){
          array_push($rota_ids, $result[$i]['id']);
        }
      }
      return $rota_ids;
    }
    /*master_shift.shift_shortcut,
          master_designation.designation_code,
          master_shift.part_number,
          rota_settings.day_shift_min,
          rota_settings.day_shift_max,
          rota_settings.night_shift_min,
          rota_settings.night_shift_max,
          rota_settings.nurse_day_count,
          rota_settings.nurse_night_count,
          master_designation.sort_order,
          master_shift.shift_type*/
    public function getShiftDatasOfSubunits($rota_ids,$date){
      $this->db->select("
        rota_schedule.shift_id,
        rota_schedule.date,
        rota_schedule.user_id,
        rota_schedule.unit_id,
        master_shift.shift_shortcut,
        master_designation.designation_code,
        master_shift.part_number,
        master_designation.nurse_count as sort_order,
        master_shift.shift_type,
        unit.*
      ");
      $this->db->from('rota_schedule');
      $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
      $this->db->join('users', 'users.id = rota_schedule.user_id');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where_in('rota_schedule.rota_id', $rota_ids);
      $this->db->where('rota_schedule.date', $date);
      $shift_query = $this->db->get();
      // echo $this->db->last_query();exit();
      $shift_data = $shift_query->result_array();
      return $shift_data;
    }
    public function getShiftDatas1($rota_id,$date)
    { 
        $this->db->select("
            rota_schedule.shift_id,
            rota_schedule.date,
            rota_schedule.user_id,
            rota_schedule.unit_id,
            master_shift.shift_shortcut,
            master_designation.designation_code,
            master_shift.part_number,
            rota_settings.day_shift_min,
            rota_settings.day_shift_max,
            rota_settings.night_shift_min,
            rota_settings.night_shift_max,
            rota_settings.nurse_day_count,
            rota_settings.nurse_night_count,
            master_designation.sort_order,
            master_shift.shift_type
        ");
        $this->db->from('rota_schedule');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings');
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        $this->db->where('rota_schedule.rota_id', $rota_id);
        $this->db->where('rota_schedule.date', $date);
        $shift_query = $this->db->get();
        // echo $this->db->last_query();
        //   exit;
        $shift_data = $shift_query->result_array();
        return $shift_data;
    }
    public function getShiftDatas($rota_id,$date,$unit)
    {
       $this->db->select("
            rota_schedule.shift_id,
            rota_schedule.date,
            rota_schedule.user_id,
            rota_schedule.unit_id,
            master_shift.shift_shortcut,
            master_designation.designation_code,
            master_shift.part_number,
            rota_settings.day_shift_min,
            rota_settings.day_shift_max,
            rota_settings.night_shift_min,
            rota_settings.night_shift_max,
            rota_settings.nurse_day_count,
            rota_settings.nurse_night_count,
            master_designation.sort_order,
            master_shift.shift_type
        ");
        $this->db->from('rota_schedule');
        $this->db->join('rota', 'rota.id = rota_schedule.rota_id');
        $this->db->join('rota_settings', 'rota_settings.id = rota.rota_settings');
        $this->db->join('users', 'users.id = rota_schedule.user_id');
        $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
        $this->db->join('master_designation', 'master_designation.id = users.designation_id');
        $this->db->where('rota_schedule.rota_id', $rota_id);
        $this->db->where('rota_schedule.date', $date);
        $this->db->where('rota_schedule.unit_id', $unit);
        $shift_query = $this->db->get();
        // echo $this->db->last_query();
        //   exit;
        $shift_data = $shift_query->result_array();
        return $shift_data;
    }

    public function findunit($id)
    {

          // $this->db->select('id');
          // $this->db->from('unit'); 
          // $this->db->where('parent_unit', $id);
          // $query = $this->db->get();
          // $result = $query->result_array();
 
        $this->db->select('id,unit_name,parent_unit'); 
         $this->db->from('unit');
         // if(empty($result))
         // {
         $this->db->where('id', $id);
         // }
         // else
         // {
         //    $this->db->where('id', $id);
         //    foreach ($result as $value) {
         //        $this->db->or_where('id', $value['id']);
         //    }
         // }
         $query = $this->db->get();
          //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;
    }

    public function fetchunit()
    {
        $this->db->select('*');
        $this->db->from('unit');
        $this->db->where('parent_unit', 0); 
        $this->db->where('id!=', 1);   
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }

    public function findBranches($unit)
  {
        $this->db->select('*');
        $this->db->from('unit'); 
        $this->db->where('parent_unit', $unit);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
  }
      public function getunitDetails($unit)
  {
        $this->db->select('*');
        $this->db->from('unit'); 
        $this->db->where('id', $unit);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
  }
    public function fetchAllUnits()
    {
        $this->db->select('*');
        $this->db->from('unit');
        $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
         return $result;

    }
}
?>