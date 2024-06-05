<?php
class Split_payroll_model extends CI_Model 
{

public function findparentunit($unit_id)
{
  $this->db->select('parent_unit');
  $this->db->from('unit');
  $this->db->where('id',$unit_id);
  $query = $this->db->get();
  $result = $query->result_array();
 
  return $result[0]['parent_unit'];
}

public function getadditonalhours($user_id,$date)
{
         $this->db->select('additional_hours.additional_hours');
         $this->db->from('additional_hours');  
         $this->db->where('user_id', $user_id);
         $this->db->where('date', $date);
         $query = $this->db->get();
         //echo $this->db->last_query();exit;
         $result = $query->result_array();
         if(count($result)>0)
         {
           return $result[0]['additional_hours'];
         }
         else
         {
           return 0;
         }
          //exit(); 
}
public function GetPayrollreport_payroll($params,$config,$flag)
    {  //print_r($params);exit();
       if($params['unit_id']!=0)
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      
// print_r($result);  exit();

         $this->db->select('users.id,personal_details.fname,personal_details.lname');
         $this->db->from('users'); 
         $this->db->join('user_unit', 'user_unit.user_id = users.id'); 
         $this->db->join('personal_details', 'personal_details.user_id = users.id');
         if($params['status']!=0)
          {
            if($params['status']==2)
            { //inactive status
              $this->db->group_start();
                  $this->db->group_start();
                    $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
                    $this->db->where('users.status',$params['status']);
                  $this->db->group_end();
                  $this->db->or_group_start();
                    $this->db->where('users.final_date <',date('Y-m-d', strtotime($params['start_date'])));
                    $this->db->where('users.status',1);
                  $this->db->group_end();
              $this->db->group_end();

                $this->db->where('users.final_date !=','0000-00-00');
                $this->db->where('users.final_date !=',NULL);



            }
            else if($params['status']==1)
            {//active users
                  $this->db->where('users.status',$params['status']);
                  $this->db->group_start();
                    $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
                    $this->db->or_where('users.final_date ','0000-00-00');
                  $this->db->group_end();
                 
            }
            else
            {//others
              $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
              $this->db->where('users.status',$params['status']);
            }
            
          }
          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }
          if($params['unit_id']!=0)
          {
              if(!empty($result))
                  {
                    $this->db->group_start();
                    $this->db->where('user_unit.unit_id', $params['unit_id']);
                    foreach ($result as $value) 
                    {
                       $this->db->or_where('user_unit.unit_id',$value['id']);
                    }
                    $this->db->group_end();
                  }
                  else
                  {
                     $this->db->where('user_unit.unit_id', $params['unit_id']);
                  }
          }
        
        if($params['user']==1)
        {
             $this->db->where('users.payment_type !=',1);
        }
        else
        {
              $this->db->where('users.payment_type',1);
        }
        if($flag == 1 || $flag == 3){
          if (!empty($config['search_value'])) {
            $search_value_escaped = $this->db->escape_like_str($config['search_value']);
            $this->db->group_start();
            $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
            $this->db->or_like('users.payroll_id', $search_value_escaped);
            $this->db->group_end();
          }
          if($flag == 1){
            if($config['column_name'] == 'payroll_id'){
              $this->db->order_by('CAST(' . $config['order_table'] . '.' . $config['column_name'] . ' AS UNSIGNED)', $config['order_direction']);
            }else{
              $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
            }
            if($config['length'] != -1){
              $this->db->limit($config['length'], $config['start']);
            }
            $query = $this->db->get();
            // echo $this->db->last_query();exit;
            $result = $query->result_array();
            return $result;
          }else{
            $query = $this->db->get();
            return $query->num_rows();
          }
        }else{
          $query = $this->db->get();
          return $query->num_rows();
        }
    }
     public function GetPayrollactivereport_payroll($params,$config,$flag)
    {
        if ($params['unit_id'] != 0) {
            $this->db->select('id');
            $this->db->from('unit');
            $this->db->where('parent_unit', $params['unit_id']);
            $query = $this->db->get();
            $result = $query->result_array();
        }

        $this->db->select('users.id,personal_details.fname,personal_details.lname');
        $this->db->from('users');
        $this->db->join('user_unit', 'user_unit.user_id = users.id');
        $this->db->join('personal_details', 'personal_details.user_id = users.id');
        // Grouping for status 1 and 2 with date condition
        $this->db->group_start();
        $this->db->where('users.status', 1);
        $this->db->or_group_start();
        $this->db->where_in('users.status', [2, 3]);
        $this->db->where('users.final_date >=', date('Y-m-d', strtotime($params['start_date'])));
        $this->db->group_end();
        $this->db->group_end();

        if ($params['jobrole'] != 0) {
            $this->db->where_in('users.designation_id', $params['jobrole']);
        }

        if ($params['unit_id'] != 0) {
            $this->db->group_start();
            $this->db->where('user_unit.unit_id', $params['unit_id']);
            if (!empty($result)) {
                foreach ($result as $value) {
                    $this->db->or_where('user_unit.unit_id', $value['id']);
                }
            }
            $this->db->group_end();
        }

        if ($params['user'] == 1) {
            $this->db->where('users.payment_type !=', 1);
        } else {
            $this->db->where('users.payment_type', 1);
        }
        if($flag == 1 || $flag == 3){
          if (!empty($config['search_value'])) {
            $search_value_escaped = $this->db->escape_like_str($config['search_value']);
            $this->db->group_start();
            $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
            $this->db->or_like('users.payroll_id', $search_value_escaped);
            $this->db->group_end();
          }
          if($flag == 1){
            if($config['column_name'] == 'payroll_id'){
              $this->db->order_by('CAST(' . $config['order_table'] . '.' . $config['column_name'] . ' AS UNSIGNED)', $config['order_direction']);
            }else{
              $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
            }
            if($config['length'] != -1){
              $this->db->limit($config['length'], $config['start']);
            }
            $query = $this->db->get();
            // echo $this->db->last_query();exit;
            $result = $query->result_array();
            return $result;
          }else{
            $query = $this->db->get();
            return $query->num_rows();
          }
        }else{
          $query = $this->db->get();
          return $query->num_rows();
        }
    }
public function GetPayrollreport($params)
    {
       if($params['unit_id']!=0)
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      
// print_r($result);  exit();

         $this->db->select('users.id');
         $this->db->from('users'); 
         $this->db->join('user_unit', 'user_unit.user_id = users.id'); 

         if($params['status']!=0)
          {
            if($params['status']==2)
            { //inactive status
              $this->db->group_start();
                  $this->db->group_start();
                    $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
                    $this->db->where('users.status',$params['status']);
                  $this->db->group_end();
                  $this->db->or_group_start();
                    $this->db->where('users.final_date <',date('Y-m-d', strtotime($params['start_date'])));
                    $this->db->where('users.status',1);
                  $this->db->group_end();
              $this->db->group_end();

                $this->db->where('users.final_date !=','0000-00-00');
                $this->db->where('users.final_date !=',NULL);



            }
            else if($params['status']==1)
            {//active users
                  $this->db->where('users.status',$params['status']);
                  $this->db->group_start();
                    $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
                    $this->db->or_where('users.final_date ','0000-00-00');
                  $this->db->group_end();
                 
            }
            else
            {//others
              $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
              $this->db->where('users.status',$params['status']);
            }
            
          }
          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }
          if($params['unit_id']!=0)
          {
              if(!empty($result))
                  {
                    $this->db->group_start();
                    $this->db->where('user_unit.unit_id', $params['unit_id']);
                    foreach ($result as $value) 
                    {
                       $this->db->or_where('user_unit.unit_id',$value['id']);
                    }
                    $this->db->group_end();
                  }
                  else
                  {
                     $this->db->where('user_unit.unit_id', $params['unit_id']);
                  }
          }
        
        if($params['user']==1)
        {
             $this->db->where('users.payment_type !=',1);
        }
        else
        {
              $this->db->where('users.payment_type',1);
        }
        
          //$this->db->order_by("time_log.user_id asc,time_log.id asc"); 
         $query = $this->db->get();
        //echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       
    }

    public function GetPayrollactivereport($params)
    {  //print_r($params);exit();
       if($params['unit_id']!=0)
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      
// print_r($result);  exit();

         $this->db->select('users.id');
         $this->db->from('users'); 
         $this->db->join('user_unit', 'user_unit.user_id = users.id'); 

         // if($params['status']!=0)
         // {
            // if($params['status']!=1)
            // {
            //     $this->db->where('users.final_date <',date('Y-m-d', strtotime($params['start_date'])));
            // }
            $this->db->where('users.status',1);
          //}
          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }
          if($params['unit_id']!=0)
          {
              if(!empty($result))
                  {
                    $this->db->group_start();
                    $this->db->where('user_unit.unit_id', $params['unit_id']);
                    foreach ($result as $value) 
                    {
                       $this->db->or_where('user_unit.unit_id',$value['id']);
                    }
                    $this->db->group_end();
                  }
                  else
                  {
                     $this->db->where('user_unit.unit_id', $params['unit_id']);
                  }
          }
        if($params['user']==1)
        {
             $this->db->where('users.payment_type !=',1);
        }
        else
        {
              $this->db->where('users.payment_type',1);
        }
       
          //$this->db->order_by("time_log.user_id asc,time_log.id asc"); 
         $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       
    }

    public function GetPayrollinactiveusersreport($params)
    {  //print_r($params);exit();
       if($params['unit_id']!=0)
      {
      $this->db->select('id');
      $this->db->from('unit'); 
      $this->db->where('parent_unit', $params['unit_id']);
      $query = $this->db->get();
      $result = $query->result_array();
      }
      
// print_r($result);  exit();

         $this->db->select('users.id');
         $this->db->from('users'); 
         $this->db->join('user_unit', 'user_unit.user_id = users.id'); 

          $this->db->group_start();
          $this->db->where('users.status',2);
          $this->db->or_where('users.status',3);
          $this->db->group_end();
          $this->db->where('users.final_date >=',date('Y-m-d', strtotime($params['start_date'])));
        
          if($params['jobrole']!=0)
          {
            $this->db->where_in('users.designation_id',$params['jobrole']);
          }
          if($params['unit_id']!=0)
          {
              if(!empty($result))
                  {
                    $this->db->group_start();
                    $this->db->where('user_unit.unit_id', $params['unit_id']);
                    foreach ($result as $value) 
                    {
                       $this->db->or_where('user_unit.unit_id',$value['id']);
                    }
                    $this->db->group_end();
                  }
                  else
                  {
                     $this->db->where('user_unit.unit_id', $params['unit_id']);
                  }
          }
         if($params['user']==1)
        {
             $this->db->where('users.payment_type !=',1);
        }
        else
        {
              $this->db->where('users.payment_type',1);
        }
          //$this->db->order_by("time_log.user_id asc,time_log.id asc"); 
         $query = $this->db->get();
       //echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       
    }

    public function finddays($user_id,$params)
    { 

    $SQL="select 
IF(TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H:%i')  >='00:00', TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H.%i') ,'00.00') as totaldayhours from 
(SELECT  `tbl`.`payroll_id`,
    MAX(`tbl`.`time_from`) AS `fromtime`,
    MAX(`tbl`.`time_to`) AS `totime`,
    
    
    IF( IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) !='00:00' && IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i')) !='00:00',
    IF(
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ) < `master_shift`.`targeted_hours`,
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ),`master_shift`.`targeted_hours`
    )
    ,'00:00'
    )
  as logtime   
    ,
    `tbl`.`date` as `time_log_date`,`rota_schedule`.`user_id`,`rota_schedule`.`additional_hours`,
 `rota_schedule`.`date` as `rota_date`, `rota_schedule`.`shift_id`, `rota_schedule`.`day`
FROM    `time_log` as tbl 
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
JOIN `master_shift` ON `master_shift`.`id` = `rota_schedule`.`shift_id`
WHERE `tbl`.`user_id` = '".$user_id."'  
 AND `tbl`.`date` >= '".$params['start_date']."' 
 AND `tbl`.`date` <= '".$params['end_date']."' 
 AND (`rota_schedule`.`day` !='Sa'
 AND `rota_schedule`.`day` !='Su')
GROUP BY `tbl`.`date`) tot group by payroll_id";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

     public function findsundays($user_id,$params)
    { 
   $SQL="select 
IF(TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H:%i')  >='00:00', TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H.%i') ,'00.00') as totalsundayhours from 
(SELECT  `tbl`.`payroll_id`,
    MAX(`tbl`.`time_from`) AS `fromtime`,
    MAX(`tbl`.`time_to`) AS `totime`,
    
    
    IF( IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) !='00:00' && IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i')) !='00:00',
    IF(
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ) < `master_shift`.`targeted_hours`,
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ),`master_shift`.`targeted_hours`
    )
    ,'00:00'
    )
  as logtime   
    ,
    `tbl`.`date` as `time_log_date`,`rota_schedule`.`user_id`,`rota_schedule`.`additional_hours`,
 `rota_schedule`.`date` as `rota_date`, `rota_schedule`.`shift_id`, `rota_schedule`.`day` 
FROM    `time_log` as tbl 
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
JOIN `master_shift` ON `master_shift`.`id` = `rota_schedule`.`shift_id`
WHERE `tbl`.`user_id` = '".$user_id."'  
 AND (`tbl`.`date` >= '".$params['start_date']."' 
 AND `tbl`.`date` <= '".$params['end_date']."') 
 AND `rota_schedule`.`day` ='Su'
GROUP BY `tbl`.`date`) tot group by payroll_id";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

     public function findsaturdays($user_id,$params)
    { 
   $SQL="select 
IF(TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H:%i')  >'00:00', TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H.%i') ,'00.00') as totalsaturdayhours from 
(SELECT  `tbl`.`payroll_id`,
    MAX(`tbl`.`time_from`) AS `fromtime`,
    MAX(`tbl`.`time_to`) AS `totime`,
    
    
    IF( IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) !='00:00' && IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i')) !='00:00',
    IF(
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ) < `master_shift`.`targeted_hours`,
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ),`master_shift`.`targeted_hours`
    )
    ,'00:00'
    )
  as logtime   
    ,
    `tbl`.`date` as `time_log_date`,`rota_schedule`.`user_id`,`rota_schedule`.`additional_hours`,
 `rota_schedule`.`date` as `rota_date`, `rota_schedule`.`shift_id`, `rota_schedule`.`day` 
FROM    `time_log` as tbl 
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
JOIN `master_shift` ON `master_shift`.`id` = `rota_schedule`.`shift_id`
WHERE `tbl`.`user_id` = '".$user_id."'  
 AND (`tbl`.`date` >= '".$params['start_date']."' 
 AND `tbl`.`date` <= '".$params['end_date']."')
 AND `rota_schedule`.`day` ='Sa'
GROUP BY `tbl`.`date`) tot group by payroll_id";

     $query = $this->db->query($SQL);
      //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

    public function calculatepayrolldata($user_id,$params,$day)
    { 
      IF($day=='day')
 {
   $days= "(`rota_schedule`.`day` !='Sa' AND `rota_schedule`.`day` !='Su')";
 }
 else if($day=='sunday')
 {
  $days= "(`rota_schedule`.`day` ='Su')";
 }
 else if($day=='saturday')
 {
  $days= "(`rota_schedule`.`day` ='Sa')";
 }

    $SQL="SELECT  `tbl`.`payroll_id`,
    MAX(`tbl`.`time_from`) AS `fromtime`,
    MAX(`tbl`.`time_to`) AS `totime`,
    IF( IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) !='00:00' && IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i')) !='00:00',
    IF(
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ) < `master_shift`.`targeted_hours`,
    TIME_FORMAT(subtime(IF(TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i' ) > TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ), 
    TIME_FORMAT(MAX(`master_shift`.`end_time`) , '%H:%i' ),
    TIME_FORMAT(MAX(`tbl`.`time_to`) , '%H:%i')) ,IF(TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i' ) < TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ), 
      TIME_FORMAT(MAX(`master_shift`.`start_time`) , '%H:%i' ),
     TIME_FORMAT(MAX(`tbl`.`time_from`) , '%H:%i'))), '%H:%i' ),`master_shift`.`targeted_hours`
    )
    ,'00:00'
    )
  as logtime,
    `tbl`.`date` as `time_log_date`,`rota_schedule`.`user_id`,`rota_schedule`.`additional_hours`,
 `rota_schedule`.`date` as `rota_date`, `rota_schedule`.`shift_id`, `rota_schedule`.`day`,`master_shift`.`unpaid_break_hours` as break
FROM    `time_log` as tbl 
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
JOIN `master_shift` ON `master_shift`.`id` = `rota_schedule`.`shift_id`
WHERE `tbl`.`user_id` = '".$user_id."'  
 AND `tbl`.`date` >= '".$params['start_date']."' 
 AND `tbl`.`date` <= '".$params['end_date']."' 
 AND ".$days."
 AND `rota_schedule`.`shift_id`!='16'
 
GROUP BY `tbl`.`date`";

     $query = $this->db->query($SQL);
      //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

    public function finduserdetails($user_id)
    {
          $result = array();
          $data = array( 
            'users.id',
            'users.weekly_hours',
            'users.payroll_id',
            'personal_details.fname',
            'personal_details.lname',
            'master_payment_type.payment_type',
            'master_designation.designation_name',
            'user_rates.day_rate');
          
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
      $this->db->join('master_payment_type', 'master_payment_type.id = users.payment_type');
      $this->db->join('master_designation', 'master_designation.id = users.designation_id');
      $this->db->join('user_rates', 'user_rates.user_id = users.id');
      $this->db->where('users.id', $user_id);
       $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

    }

    public function getShiftcount($user_id,$params)
    {
      $result = array();
      $data = array( 
            'rota_schedule.user_id',
            'rota_schedule.shift_id',
            'rota_schedule.shift_hours',
            'rota_schedule.date');
      $this->db->select($data);
      $this->db->from('rota_schedule'); 
      $this->db->where('rota_schedule.user_id', $user_id);
      $this->db->group_start();
      $this->db->where('rota_schedule.date >=', $params['start_date']);
      $this->db->where('rota_schedule.date <=', $params['end_date']);
      $this->db->group_end();
      $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      }

    }

    public function GetTrainingHourbyuser($user_id,$params)
    {
      $SQL="SELECT  
    `training_staff`.`user_id`,`tbl`.`date_from`,`tbl`.`date_to`,
      `tbl`.`training_hour`
    FROM    `master_training` as tbl
    JOIN `training_staff` ON `training_staff`.`training_id` = `tbl`.`id`
    WHERE `training_staff`.`user_id` = '".$user_id."'
    And (( `tbl`.`date_from` between '".$params['start_date']."' and '".$params['end_date']."')OR(`tbl`.`date_to` between '".$params['start_date']."' and '".$params['end_date']."')
    OR('".$params['start_date']."' between `tbl`.`date_from`  and `tbl`.`date_to`) OR('".$params['end_date']."' between `tbl`.`date_from`  and `tbl`.`date_to`))";

   // print_r($SQL);exit();

     $query = $this->db->query($SQL);
     //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

    public function findtraininghourbyuser($user_id,$params)
    {
       $SQL="select TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC(tot.trainingtime))),'%H.%i') as trainingtime from (

SELECT  
    `tbl`.`date_from` AS `fromtime`,
    `tbl`.`date_to` AS `totime`, TIME_FORMAT(subtime(`tbl`.`time_to` ,`tbl`.`time_from`), '%H:%i' ) as trainingtime,
      `training_staff`.`user_id`
    FROM    `master_training` as tbl
    JOIN `training_staff` ON `training_staff`.`training_id` = `tbl`.`id`
    WHERE `training_staff`.`user_id` = '".$user_id."'
    And (( `tbl`.`date_from` between '".$params['start_date']."' and '".$params['end_date']."')OR(`tbl`.`date_to` between '".$params['start_date']."' and '".$params['end_date']."')
    OR('".$params['start_date']."' between `tbl`.`date_from`  and `tbl`.`date_to`) OR('".$params['end_date']."' between `tbl`.`date_from`  and `tbl`.`date_to`))
    
    ) 
    tot group by user_id;";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;

    }

    public function findAnnualLeave($user_id,$params)
    { 


           $SQL="SELECT  
    `tbl`.`from_date` AS `fromtime`,
    `tbl`.`to_date` AS `totime`,
      `holiday_applied`.`user_id`,
      `holiday_applied`.`hours` as apllied_hours,
      `tbl`.`status`
    FROM    `holliday` as tbl
    JOIN `holiday_applied` ON `holiday_applied`.`holiday_id` = `tbl`.`id`
    WHERE `holiday_applied`.`user_id` = '".$user_id."'
    And (( `tbl`.`from_date` between '".$params['start_date']."' and '".$params['end_date']."')OR(`tbl`.`to_date` between '".$params['start_date']."' and '".$params['end_date']."')
    OR('".$params['start_date']."' between `tbl`.`from_date`  and `tbl`.`to_date`) OR('".$params['end_date']."' between `tbl`.`from_date`  and `tbl`.`to_date`))
    and `tbl`.`status`=1";

     $query = $this->db->query($SQL);
     //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;

    }

    public function findbankholiday($user_id,$params)
    {  

$SQL="select 
IF(TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H.%i')  >='00:00', TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.logtime)))),'%H.%i') ,'00.00') as totalbankholidayhours from 
(SELECT  `tbl`.`payroll_id`,
    MAX(`tbl`.`time_from`) AS `fromtime`,
    MAX(`tbl`.`time_to`) AS `totime`,
IF(
TIME_FORMAT(subtime(MAX(`tbl`.`time_to`) ,MAX(`tbl`.`time_from`)), '%H:%i' ) < `master_shift`.`total_targeted_hours`,
TIME_FORMAT(subtime(MAX(`tbl`.`time_to`) ,MAX(`tbl`.`time_from`)), '%H:%i' ),`master_shift`.`total_targeted_hours`
)as logtime,

    `tbl`.`date` as `time_log_date`,`rota_schedule`.`user_id`,`rota_schedule`.`additional_hours`,
 `rota_schedule`.`date` as `rota_date`, `rota_schedule`.`shift_id`, `rota_schedule`.`day`,`bank_holiday`.`id` as holiday_id
FROM    `time_log` as tbl 
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
JOIN `master_shift` ON `master_shift`.`id` = `rota_schedule`.`shift_id`
left JOIN `bank_holiday` ON `bank_holiday`.`date` = `tbl`.`date`
WHERE `tbl`.`user_id` = '".$user_id."'  
 AND (`tbl`.`date` >= '".$params['start_date']."' 
 AND `tbl`.`date` <= '".$params['end_date']."') 
 AND `bank_holiday`.`id` is not null
 AND `bank_holiday`.`status` !='0'
GROUP BY `tbl`.`date`) tot group by payroll_id";

     $query = $this->db->query($SQL);
        // echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

     public function findAdditionlHoursByTransferTraining($user_id,$params,$parent)
    {
      $result = array();
      $this->db->select('additional_hours.user_id,additional_hours.date,rota_schedule.day,rota_schedule.shift_id,additional_hours.additional_hours as extrahour,unit.parent_unit');
      $this->db->from('additional_hours');
      $this->db->join('rota_schedule', 'rota_schedule.user_id = additional_hours.user_id  AND rota_schedule.date = additional_hours.date ');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      $this->db->where('additional_hours.user_id', $user_id); 
      $this->db->group_start();
      $this->db->where('additional_hours.date >=', $params['start_date']);
      $this->db->where('additional_hours.date <=', $params['end_date']);
      $this->db->group_end();
      $this->db->where('rota_schedule.shift_id =',2);
      if($parent!=0)
      {
         $this->db->where('unit.parent_unit !=', $parent); 
      }
      $this->db->group_by('additional_hours.date');  
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result;
    }

 public function findAdditionlHoursByDayforTransfer($user_id,$params,$parent)
    {

      $result = array();
      $this->db->select('additional_hours.user_id,additional_hours.date,rota_schedule.day,rota_schedule.shift_id,additional_hours.day_additional_hours as extrahour,unit.parent_unit');
      $this->db->from('additional_hours');
      $this->db->join('rota_schedule', 'rota_schedule.user_id = additional_hours.user_id  AND rota_schedule.date = additional_hours.date ');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      $this->db->where('additional_hours.user_id', $user_id); 
      $this->db->group_start();
      $this->db->where('additional_hours.date >=', $params['start_date']);
      $this->db->where('additional_hours.date <=', $params['end_date']);
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.day !=', 'Sa');
      $this->db->where('rota_schedule.day !=', 'Su');
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.shift_id !=',3);
      $this->db->where('rota_schedule.shift_id !=',2);
      $this->db->group_end();
      if($parent!=0)
      {
         $this->db->where('unit.parent_unit !=', $parent); 
      }
      $this->db->group_by('additional_hours.date');  
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result;
    }

    public function findAdditionlHoursBysunDayforTransfer($user_id,$params,$parent)
    {
      // $result = array();
      // $this->db->select('rota_schedule.user_id,rota_schedule.date,rota_schedule.day,rota_schedule.shift_id,rota_schedule.day_additional_hours as extrahour,unit.parent_unit');
      // $this->db->from('rota_schedule');
      // $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      // $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      // $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      // $this->db->where('rota_schedule.user_id', $user_id); 
      // $this->db->group_start();
      // $this->db->where('rota_schedule.date >=', $params['start_date']);
      // $this->db->where('rota_schedule.date <=', $params['end_date']);
      // $this->db->group_end();
      // $this->db->where('rota_schedule.day', 'Su');
      // $this->db->group_start();
      // $this->db->where('rota_schedule.shift_id !=',3);
      // $this->db->where('rota_schedule.shift_id !=',2);
      // $this->db->group_end();
      // if($parent!=0)
      // {
      //    $this->db->where('unit.parent_unit !=', $parent); 
      // }
      // $this->db->group_by('rota_schedule.date');  
      // $query = $this->db->get();

      $result = array();
      $this->db->select('additional_hours.user_id,additional_hours.date,rota_schedule.day,rota_schedule.shift_id,additional_hours.day_additional_hours as extrahour,unit.parent_unit');
      $this->db->from('additional_hours');
      $this->db->join('rota_schedule', 'rota_schedule.user_id = additional_hours.user_id  AND rota_schedule.date = additional_hours.date ');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      $this->db->where('additional_hours.user_id', $user_id); 
      $this->db->group_start();
      $this->db->where('additional_hours.date >=', $params['start_date']);
      $this->db->where('additional_hours.date <=', $params['end_date']);
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.day', 'Su');
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.shift_id !=',3);
      $this->db->where('rota_schedule.shift_id !=',2);
      $this->db->group_end();
      if($parent!=0)
      {
         $this->db->where('unit.parent_unit !=', $parent); 
      }
      $this->db->group_by('additional_hours.date');  
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result;
    }

    public function findAdditionlHoursBysaturDayforTransfer($user_id,$params,$parent)
    {
      // $result = array();
      // $this->db->select('rota_schedule.user_id,rota_schedule.date,rota_schedule.day,rota_schedule.shift_id,rota_schedule.day_additional_hours as extrahour,unit.parent_unit');
      // $this->db->from('rota_schedule');
      // $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      // $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      // $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      // $this->db->where('rota_schedule.user_id', $user_id); 
      // $this->db->group_start();
      // $this->db->where('rota_schedule.date >=', $params['start_date']);
      // $this->db->where('rota_schedule.date <=', $params['end_date']);
      // $this->db->group_end();
      // $this->db->where('rota_schedule.day', 'Sa');
      // $this->db->group_start();
      // $this->db->where('rota_schedule.shift_id !=',3);
      // $this->db->where('rota_schedule.shift_id !=',2);
      // $this->db->group_end();
      // if($parent!=0)
      // {
      //    $this->db->where('unit.parent_unit !=', $parent); 
      // }
      // $this->db->group_by('rota_schedule.date');  
      // $query = $this->db->get();

      $result = array();
      $this->db->select('additional_hours.user_id,additional_hours.date,rota_schedule.day,rota_schedule.shift_id,additional_hours.day_additional_hours as extrahour,unit.parent_unit');
      $this->db->from('additional_hours');
      $this->db->join('rota_schedule', 'rota_schedule.user_id = additional_hours.user_id  AND rota_schedule.date = additional_hours.date ');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      $this->db->where('additional_hours.user_id', $user_id); 
      $this->db->group_start();
      $this->db->where('additional_hours.date >=', $params['start_date']);
      $this->db->where('additional_hours.date <=', $params['end_date']);
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.day', 'Sa');
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.shift_id !=',3);
      $this->db->where('rota_schedule.shift_id !=',2);
      $this->db->group_end();
      if($parent!=0)
      {
         $this->db->where('unit.parent_unit !=', $parent); 
      }
      $this->db->group_by('additional_hours.date');  
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result;
    }
     public function findAdditionlHoursByWeekdayforTransfer($user_id,$params,$parent)
    {

      $end=date('Y-m-d', strtotime("+1 day", strtotime($params['end_date'])));
      // $result = array();
      // $this->db->select('rota_schedule.user_id,rota_schedule.date,rota_schedule.day,rota_schedule.shift_id,rota_schedule.night_additional_hours as extrahour,unit.parent_unit');
      // $this->db->from('rota_schedule');
      // $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      // $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      // $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      // $this->db->where('rota_schedule.user_id', $user_id); 
      // $this->db->group_start();
      // $this->db->where('rota_schedule.date >=', $params['start_date']);
      // $this->db->where('rota_schedule.date <=', $end);
      // $this->db->group_end();
      // $this->db->group_start();
      // $this->db->where('rota_schedule.day !=', 'Sa');
      // $this->db->where('rota_schedule.day !=', 'Su');
      // $this->db->group_end();
      // $this->db->group_start();
      // $this->db->where('rota_schedule.shift_id !=',3);
      // $this->db->where('rota_schedule.shift_id !=',2);
      // $this->db->group_end();
      // if($parent!=0)
      // {
      //    $this->db->where('unit.parent_unit !=', $parent); 
      // }
      // $this->db->group_by('rota_schedule.date');  
      // $query = $this->db->get();

      $result = array();
      $this->db->select('additional_hours.user_id,additional_hours.date,rota_schedule.day,rota_schedule.shift_id,additional_hours.night_additional_hours as extrahour,unit.parent_unit');
      $this->db->from('additional_hours');
      $this->db->join('rota_schedule', 'rota_schedule.user_id = additional_hours.user_id  AND rota_schedule.date = additional_hours.date ');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      $this->db->where('additional_hours.user_id', $user_id); 
      $this->db->group_start();
      $this->db->where('additional_hours.date >=', $params['start_date']);
      $this->db->where('additional_hours.date <=', $end);
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.day !=', 'Sa');
      $this->db->where('rota_schedule.day !=', 'Su');
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.shift_id !=',3);
      $this->db->where('rota_schedule.shift_id !=',2);
      $this->db->group_end();
      if($parent!=0)
      {
         $this->db->where('unit.parent_unit !=', $parent); 
      }
      $this->db->group_by('additional_hours.date');  
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result;
    }


    public function findAdditionlHoursByWeekendforTransfer($user_id,$params,$parent)
    { //print_r($params);exit();
     $end=date('Y-m-d', strtotime("+1 day", strtotime($params['end_date'])));
      // $result = array();
      // $this->db->select('rota_schedule.user_id,rota_schedule.date,rota_schedule.day,rota_schedule.shift_id,rota_schedule.night_additional_hours as extrahour,unit.parent_unit');
      // $this->db->from('rota_schedule');
      // $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      // $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      // $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      // $this->db->where('rota_schedule.user_id', $user_id); 
      // $this->db->group_start();
      // $this->db->where('rota_schedule.date >=', $params['start_date']);
      // $this->db->where('rota_schedule.date <=', $end);
      // $this->db->group_end();
      // $this->db->group_start();
      // $this->db->where('rota_schedule.day =', 'Sa');
      // $this->db->where('rota_schedule.day =', 'Su');
      // $this->db->group_end();
      // $this->db->group_start();
      // $this->db->where('rota_schedule.shift_id !=',3);
      // $this->db->where('rota_schedule.shift_id !=',2);
      // $this->db->group_end();
      // if($parent!=0)
      // {
      //    $this->db->where('unit.parent_unit !=', $parent); 
      // }
      // $this->db->group_by('rota_schedule.date');  
      // $query = $this->db->get();

      $result = array();
      $this->db->select('additional_hours.user_id,additional_hours.date,rota_schedule.day,rota_schedule.shift_id,additional_hours.night_additional_hours as extrahour,unit.parent_unit');
      $this->db->from('additional_hours');
      $this->db->join('rota_schedule', 'rota_schedule.user_id = additional_hours.user_id  AND rota_schedule.date = additional_hours.date ');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id');
      $this->db->where('rota_schedule.from_unit', $params['unit_id']); 
      $this->db->where('additional_hours.user_id', $user_id); 
      $this->db->group_start();
      $this->db->where('additional_hours.date >=', $params['start_date']);
      $this->db->where('additional_hours.date <=', $end);
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.day =', 'Sa');
      $this->db->or_where('rota_schedule.day =', 'Su');
      $this->db->group_end();
      $this->db->group_start();
      $this->db->where('rota_schedule.shift_id !=',3);
      $this->db->where('rota_schedule.shift_id !=',2);
      $this->db->group_end();
      if($parent!=0)
      {
         $this->db->where('unit.parent_unit !=', $parent); 
      }
      $this->db->group_by('additional_hours.date');  
      $query = $this->db->get();

      //echo $this->db->last_query();exit;
      $result = $query->result_array();  
      //print '<pre>';  
      //print_r($result[0]); print_r('<br>');
      return $result;
    }

    public function findAdditionlHoursByTraining($user_id,$params)
    {
//         $SQL="SELECT  `tbl`.`user_id`,
// `tbl`.`date`,
// `tbl`.`day`,
// `tbl`.`shift_id`,
// `tbl`.`additional_hours` as extrahour 
// FROM    `rota_schedule` as tbl  
// WHERE `tbl`.`user_id` = '".$user_id."'  
// AND (`tbl`.`date` >= '".$params['start_date']."' 
// AND `tbl`.`date` <= '".$params['end_date']."')  
// AND (`tbl`.`day` !='Sa'
// AND `tbl`.`day` !='Su')
// AND `tbl`.`shift_id`='2'
// GROUP BY`tbl`.`date`";

 $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`additional_hours` as extrahour 
FROM `additional_hours` as `tbl`
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
 WHERE `tbl`.`user_id` = '".$user_id."' 
 AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."') 
 AND `rota_schedule`.`shift_id`='2' 
 GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
     //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
      //print_r($result);
      return $result;
    }

     public function findAdditionlHoursBybankHoliday($user_id,$params)
    {
      
        //         $SQL="SELECT  `tbl`.`user_id`,
        // `tbl`.`date`,
        // `tbl`.`day`,
        // `tbl`.`shift_id`,
        // `tbl`.`additional_hours` as extrahour 
        // FROM    `rota_schedule` as tbl  
        // WHERE `tbl`.`user_id` = '".$user_id."'  
        // AND (`tbl`.`date` >= '".$params['start_date']."' 
        // AND `tbl`.`date` <= '".$params['end_date']."')  
        // AND (`tbl`.`day` !='Sa'
        // AND `tbl`.`day` !='Su')
        // AND `tbl`.`shift_id`='2'
        // GROUP BY`tbl`.`date`";

         $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`additional_hours` as extrahour 
        FROM `additional_hours` as `tbl`
        JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
         WHERE `tbl`.`user_id` = '".$user_id."' 
         AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."')  
         GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
     //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
      //print_r($result);
      return $result;
    }



    public function findAdditionlHoursByDay($user_id,$params)
    {
//         $SQL="SELECT  `tbl`.`user_id`,
// `tbl`.`date`,
// `tbl`.`day`,
// `tbl`.`shift_id`,
// `tbl`.`day_additional_hours` as extrahour 
// FROM    `rota_schedule` as tbl  
// WHERE `tbl`.`user_id` = '".$user_id."'  
// AND (`tbl`.`date` >= '".$params['start_date']."' 
// AND `tbl`.`date` <= '".$params['end_date']."')  
// AND (`tbl`.`day` !='Sa'
// AND `tbl`.`day` !='Su')
// AND  (`tbl`.`shift_id`!='3'
// AND `tbl`.`shift_id`!='2')
// GROUP BY`tbl`.`date`";

      $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`day_additional_hours` as extrahour 
FROM `additional_hours` as `tbl`
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
 WHERE `tbl`.`user_id` = '".$user_id."' 
 AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."') 
 AND (`rota_schedule`.`day` !='Sa' AND `rota_schedule`.`day` !='Su') 
 AND (`rota_schedule`.`shift_id`!='3' AND `rota_schedule`.`shift_id`!='2') 
 GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
      //print_r($result);
      return $result;
    }

    public function findAdditionlHoursBysunDay($user_id,$params)
    {
//         $SQL="SELECT  `tbl`.`user_id`,
// `tbl`.`date`,
// `tbl`.`day`,
// `tbl`.`shift_id`,
// `tbl`.`day_additional_hours` as extrahour 
// FROM    `rota_schedule` as tbl  
// WHERE `tbl`.`user_id` = '".$user_id."'  
// AND (`tbl`.`date` >= '".$params['start_date']."' 
// AND `tbl`.`date` <= '".$params['end_date']."') 
// AND `tbl`.`day` ='Su'
// AND  (`tbl`.`shift_id`!='3'
// AND `tbl`.`shift_id`!='2')
// GROUP BY`tbl`.`date`";

       $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`day_additional_hours` as extrahour 
FROM `additional_hours` as `tbl`
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
 WHERE `tbl`.`user_id` = '".$user_id."' 
 AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."') 
 AND `rota_schedule`.`day` ='Su' 
 AND (`rota_schedule`.`shift_id`!='3' AND `rota_schedule`.`shift_id`!='2') 
 GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;


    }

    public function findAdditionlHoursBysaturDay($user_id,$params)
    {
//         $SQL="SELECT  `tbl`.`user_id`,
// `tbl`.`date`,
// `tbl`.`day`,
// `tbl`.`shift_id`,
// `tbl`.`day_additional_hours` as extrahour 
// FROM    `rota_schedule` as tbl  
// WHERE `tbl`.`user_id` = '".$user_id."'  
// AND (`tbl`.`date` >= '".$params['start_date']."' 
// AND `tbl`.`date` <= '".$params['end_date']."')  
// AND `tbl`.`day` ='Sa'
// AND  (`tbl`.`shift_id`!='3'
// AND `tbl`.`shift_id`!='2')
// GROUP BY`tbl`.`date`";

      $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`day_additional_hours` as extrahour 
FROM `additional_hours` as `tbl`
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
 WHERE `tbl`.`user_id` = '".$user_id."' 
 AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."') 
 AND `rota_schedule`.`day` ='Sa' 
 AND (`rota_schedule`.`shift_id`!='3' AND `rota_schedule`.`shift_id`!='2') 
 GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;


    }

public function findNightShiftdetails($user_id,$params)
{  //print_r($params);exit();
  $where="`rota_schedule`.`shift_id`".' '.'IS NOT NULL'; 
         $data=array('time_log.time_from','time_log.time_to','time_log.date','time_log.user_id','rota_schedule.shift_id','rota_schedule.shift_hours','rota_schedule.date as rota_date','rota_schedule.day as day');
         $this->db->select($data);
         $this->db->from('time_log');  
         $this->db->join('rota_schedule', 'rota_schedule.user_id = time_log.user_id AND rota_schedule.date = time_log.date');
         $this->db->join('master_shift', 'master_shift.id = time_log.shift_id');
         $this->db->join('personal_details', 'personal_details.user_id = time_log.user_id');

         $this->db->where('time_log.user_id', $user_id);
          $this->db->where('time_log.date >=', $params['start_date']);
          $this->db->where('time_log.date <=', $params['end_date']); 
          $this->db->where($where); 
          $this->db->order_by('time_log.date',"asc");
         $query = $this->db->get();
         // echo $this->db->last_query();exit;
         $result = $query->result_array();
 
         return $result;
       

}

 public function findAdditionlHoursByWeekday($user_id,$params)
    {

      $end=date('Y-m-d', strtotime("+1 day", strtotime($params['end_date'])));

//         $SQL="SELECT  `tbl`.`user_id`,
// `tbl`.`date`,
// `tbl`.`day`,
// `tbl`.`shift_id`,
// `tbl`.`night_additional_hours` as extrahour 
// FROM    `rota_schedule` as tbl  
// WHERE `tbl`.`user_id` = '".$user_id."'  
// AND (`tbl`.`date` >= '".$params['start_date']."' 
// AND `tbl`.`date` <= '".$end."')  
// AND (`tbl`.`day` !='Sa'
// AND `tbl`.`day` !='Su')
// AND  (`tbl`.`shift_id`!='3'
// AND `tbl`.`shift_id`!='2')
// GROUP BY`tbl`.`date`";


      $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`night_additional_hours` as extrahour 
FROM `additional_hours` as `tbl`
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
 WHERE `tbl`.`user_id` = '".$user_id."' 
 AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."') 
 AND (`rota_schedule`.`day` !='Sa' AND `rota_schedule`.`day` !='Su')
 AND (`rota_schedule`.`shift_id`!='3' AND `rota_schedule`.`shift_id`!='2') 
 GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }


    public function findAdditionlHoursByWeekend($user_id,$params)
    { //print_r($params);exit();
      $end=date('Y-m-d', strtotime("+1 day", strtotime($params['end_date'])));
     // print_r($end);exit();
//         $SQL="SELECT  `tbl`.`user_id`,
// `tbl`.`date`,
// `tbl`.`day`,
// `tbl`.`shift_id`,
// `tbl`.`night_additional_hours` as extrahour 
// FROM    `rota_schedule` as tbl  
// WHERE `tbl`.`user_id` = '".$user_id."'  
// AND (`tbl`.`date` >= '".$params['start_date']."' 
// AND `tbl`.`date` <= '".$end."')   
// AND (`tbl`.`day` ='Sa'
// OR `tbl`.`day` ='Su')
// AND  (`tbl`.`shift_id`!='3'
// AND `tbl`.`shift_id`!='2')
// GROUP BY`tbl`.`date`";

 $SQL="SELECT `tbl`.`user_id`, `tbl`.`date`, `rota_schedule`.`day`, `rota_schedule`.`shift_id`, `tbl`.`night_additional_hours` as extrahour 
FROM `additional_hours` as `tbl`
JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
 WHERE `tbl`.`user_id` = '".$user_id."' 
 AND (`tbl`.`date` >= '".$params['start_date']."' AND `tbl`.`date` <= '".$params['end_date']."') 
 AND (`rota_schedule`.`day` ='Sa' OR `rota_schedule`.`day` ='Su')
 AND (`rota_schedule`.`shift_id`!='3' AND `rota_schedule`.`shift_id`!='2') 
 GROUP BY`tbl`.`date`";

     $query = $this->db->query($SQL);
       //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result);
      return $result;
    }

    public function findAdditionlHoursBySick($user_id,$params)
    {

        // $SQL="select 
        // IF(TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.extrahour)))),'%H:%i')  >='00:00', TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.extrahour)))),'%H.%i') ,'00.00') as totalsickhours from 
        // (SELECT  `tbl`.`user_id`,
        // `tbl`.`date`,
        // `tbl`.`day`,
        // `tbl`.`shift_id`,
        // `tbl`.`additional_hours` as extrahour 
        // FROM    `rota_schedule` as tbl  
        // WHERE `tbl`.`user_id` = '".$user_id."'  
        // AND (`tbl`.`date` >= '".$params['start_date']."' 
        // AND `tbl`.`date` <= '".$params['end_date']."')  
        // AND (`tbl`.`shift_id`='3')
        // GROUP BY`tbl`.`date`) tot";

        $SQL=" select 
        IF(TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.extrahour)))),'%H:%i')  >='00:00',
        TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC((tot.extrahour)))),'%H.%i') ,'00.00') as totalsickhours from 
        (SELECT  `tbl`.`user_id`,
        `tbl`.`date`,
        `rota_schedule`.`day`,
        `rota_schedule`.`shift_id`,
        `tbl`.`additional_hours` as extrahour 
        FROM    `additional_hours` as `tbl`
        JOIN `rota_schedule` ON `rota_schedule`.`user_id` = `tbl`.`user_id` AND `rota_schedule`.`date` = `tbl`.`date`
        WHERE `tbl`.`user_id` = '".$user_id."'  
        AND (`tbl`.`date` >= '".$params['start_date']."' 
        AND `tbl`.`date` <= '".$params['end_date']."')   
        AND (`rota_schedule`.`shift_id`='3')
        GROUP BY`tbl`.`date`) tot";

        $query = $this->db->query($SQL);
        //echo $this->db->last_query();exit();
        $result = $query->result_array(); 
        //print_r($result);
        return $result;


    }

    public function getBreakhoursbynightshift($user_id,$date)
    {
      $this->db->select('master_shift.unpaid_break_hours');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id'); 
      $this->db->where('rota_schedule.user_id',$user_id);
      $this->db->where('rota_schedule.date ', $date); 
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
      if(empty($result))
      {
         $results='00.00.00';
      }
      else
      { 
        
        $results=$result[0]['unpaid_break_hours'].".00";

      }
      // print_r($results);
      // exit();
 
      return $results;


    }


    public function getBreakhours($user_id,$params)
    {
      $this->db->select('master_shift.unpaid_break_hours,rota_schedule.day,master_shift.shift_category,time_log.id as logid,rota_schedule.date');
      $this->db->from('rota_schedule');
      $this->db->join('master_shift', 'master_shift.id = rota_schedule.shift_id');
      $this->db->join('time_log', 'time_log.date = rota_schedule.date','left');
      $this->db->where('rota_schedule.user_id',$user_id);
      $this->db->where('rota_schedule.date >=', $params['start_date']);
      $this->db->where('rota_schedule.date <=', $params['end_date']); 
      $this->db->group_by('rota_schedule.date');
      $query = $this->db->get();
      //echo $this->db->last_query();exit;
      $result = $query->result_array();
 
      return $result;


    }

    public function checktimelog($user_id,$date)
    {

      $SQL="SELECT MAX(`tbl`.`time_from`) AS `fromtime`,
            MAX(`tbl`.`time_to`) AS `totime`,
            `tbl`.`date` as `time_log_date`
            FROM    `time_log` as tbl 
            WHERE `tbl`.`user_id` = '".$user_id."'  
            AND `tbl`.`date` = '".$date."'  
            GROUP BY `tbl`.`date`";
     $query = $this->db->query($SQL);
    //echo $this->db->last_query();exit();
      $result = $query->result_array(); 
     //print_r($result); 
 
      return $result;
    }

    public function Getcomment($user_id,$month,$year)
    {
      // print_r("<pre>");
      // print_r($user_id." ".$month." ".$year);
      $this->db->select('comment');
      $this->db->from('payroll_comment'); 
      $this->db->where('user_id', $user_id); 
      $this->db->where('month', $month);
      $this->db->where('year', $year);     
      $result = $this->db->get();
      // print_r("prev");
      // print $this->db->last_query();
      // print_r("<br>");
      //   exit();

      $result = $result->result_array(); 
      // print_r("<pre>"); 
      // print_r(count($result));
       //exit();
      if(count($result)==0)
      {
        $results='0';
      }
      else
      { 
        $results=$result[0]['comment'];
      }  
      //print_r("<pre>");
      //print_r($results); 
      return $results;

    }

    public function CheckforUserPayrollComment($params)
    {   
      $this->db->select('id');
      $this->db->from('payroll_comment'); 
      $this->db->where('user_id', $params['user_id']); 
      $this->db->where('month', $params['month']);
      $this->db->where('year', $params['year']);     
      $result = $this->db->get();
      // print $this->db->last_query();
      //   exit();

      $result = $result->result_array();  
      //print_r(count($result));exit();
      if(count($result)==0)
      {
        $results='0';
      }
      else
      { 
        $results=$result[0]['id'];
      }  
      //print_r($results);exit();
      return $results;
    }


    public function insertcomments($params)
    {
      $updatecomments=array(
        'user_id'=>$params['user_id'],
        'month'=>$params['month'],
        'year'=>$params['year'],
        'comment'=>$params['comment'],
        'creation_date'=>$params['creation_date'],
        'created_by'=>$params['created_by']
      );  
      $this->db->insert('payroll_comment', $updatecomments);  
      if($this->db->affected_rows() > 0 )
      {
        return "true";
      }
      else
      {
        return "false";
      }

    }


     public function updatecomments($params,$id)
    {
      $updatecomments=array('comment'=>$params['comment'],'creation_date'=>$params['creation_date'],'created_by'=>$params['created_by']); 
      $this->db->where('id', $id); 
      $this->db->update('payroll_comment', $updatecomments);  
      if($this->db->affected_rows() > 0 )
      {
        return "true";
      }
      else
      {
        return "false";
      }

    }



  

public function finduser($params = array())
    {

      $result = array();
          $data = array(
            'users.id',
            'users.email',
            'users.status',
            'users.password',
            'users.group_id',
            'users.designation_id',
            'users.weekly_hours',
            'users.annual_holliday_allowance',
            'users.annual_allowance_type',
            'users.payroll_id',
            'users.default_shift',
            'users.start_date',
            'users.final_date',
            'users.notes',
            'personal_details.fname',
            'personal_details.lname',
            'personal_details.mobile_number');
          
      $this->db->select($data);
      $this->db->from('users'); 
      $this->db->join('personal_details', 'personal_details.user_id = users.id');
 
     /*  if($params['id']=='' && $params['payroll_id'])
          return $result; */
      
      if($params['id']!='')
         $this->db->where('users.id', $params['id']); 
      if($params['payroll_id']!='')
          $this->db->where('users.payroll_id', $params['payroll_id']); 
          $this->db->where('users.status', 1); 
      $query = $this->db->get();
      //  print $this->db->last_query();
      // exit();
      $result = $query->result_array();
      return $result;

   }

   public function getotherunitname($user_id,$params)
   {
     $this->db->select('unit.unit_name');
      $this->db->from('rota_schedule'); 
      $this->db->join('unit', 'unit.id = rota_schedule.unit_id','left');
      $this->db->where('rota_schedule.user_id',$user_id);
      $this->db->where('rota_schedule.from_unit',$params['unit_id']);

      $this->db->group_start();
        $this->db->where('rota_schedule.shift_hours !=',0);
        $this->db->where('rota_schedule.shift_hours !=', NULL); 
      $this->db->group_end();

      $this->db->group_start();
        $this->db->where('rota_schedule.date >=', $params['start_date']);
        $this->db->where('rota_schedule.date <=', $params['end_date']); 
      $this->db->group_end();
      $this->db->group_by('rota_schedule.unit_id');
      $query = $this->db->get();
       //echo $this->db->last_query();exit;
      $result = $query->result_array();

      if(count($result)>0)
      {
        return $result;
      }
      else
      {
        return null;
      } 
   }

   public function getunitname($cat_id)
{ 
  $this->db->select('unit_name');
  $this->db->from('unit'); 
  $this->db->where_in('id', $cat_id);
  $query = $this->db->get();
    //echo $this->db->last_query();exit;
    $result = $query->result_array(); 
    if(count($result)>0)
    {
      return $result[0]['unit_name'];
    }
    else
    {
      return null;
    } 
}


   public function finduserwithunit($params = array())
   {
       
       $result = array();
       $data = array(
           'users.id',
           'users.email',
           'users.status',
           'users.password',
           'users.group_id',
           'users.designation_id',
           'users.weekly_hours',
           'users.annual_holliday_allowance',
           'users.annual_allowance_type',
           'users.payroll_id',
           'users.default_shift',
           'users.start_date',
           'users.final_date',
           'users.notes',
           'personal_details.fname',
           'personal_details.lname',
           'personal_details.mobile_number');
       
       $this->db->select($data);
       $this->db->from('users');
       $this->db->join('personal_details', 'personal_details.user_id = users.id');
       $this->db->join('user_unit', 'user_unit.user_id = users.id');
       /*  if($params['id']=='' && $params['payroll_id'])
        return $result; */
       
       
       if($params['id']!='')
           $this->db->where('users.id', $params['id']);
       if($params['payroll_id']!='')
           $this->db->where('users.payroll_id', $params['payroll_id']);
       if($params['unit_id']!='')
           $this->db->where('user_unit.unit_id', $params['unit_id']);
       
           $this->db->where('users.status', 1);
           
               $query = $this->db->get();
               //  print $this->db->last_query();
               // exit();
               $result = $query->result_array();
               return $result;
               
   

  }

  }
?>