<?php
class History_reports_model extends CI_Model {
    public function findUserDesgHistory($status,$config,$flag)
    {
        $this->db->select('
            personal_details.fname,
            personal_details.lname,
            User_Unit_Designation_history.*'
        );
        $this->db->from('User_Unit_Designation_history'); 
        $this->db->join('personal_details','personal_details.user_id=User_Unit_Designation_history.user_id');
        $this->db->join('master_designation','master_designation.id=User_Unit_Designation_history.Previous_designation','left');
        $this->db->join('master_designation as current_desg', 'current_desg.id = User_Unit_Designation_history.Current_designation','left');
        $this->db->join('personal_details as updated_person', 'updated_person.user_id = User_Unit_Designation_history.Updated_by','left');
        if($status>0)
        {
            $this->db->where('User_Unit_Designation_history.Status', $status);
        }
        if($flag == 1 || $flag == 3){
            if (!empty($config['search_value'])) {
                $this->db->group_start();
                $search_value_escaped = $this->db->escape_like_str($config['search_value']);
                $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
                $this->db->or_like('master_designation.designation_name', $search_value_escaped);
                $this->db->or_like('User_Unit_Designation_history.user_id', $search_value_escaped);
                $this->db->or_like('current_desg.designation_name', $search_value_escaped);
                $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
                $this->db->group_end();
            }
            if($flag==1){
                $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
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
    public function findUserDesgHistoryByuser($unit_id,$status,$config,$flag)
    {
        if($unit_id)
        {
            $this->db->select('unit.id');
            $this->db->from('unit');
            $this->db->where('parent_unit', $unit_id);
            $query = $this->db->get();
            //echo $this->db->last_query();exit();
            $result = $query->result_array(); 
        }
        //print_r($result);exit();
        $this->db->select('
            personal_details.fname,
            personal_details.lname,
            User_Unit_Designation_history.*'
        );
        $this->db->from('User_Unit_Designation_history'); 
        $this->db->join('personal_details','personal_details.user_id=User_Unit_Designation_history.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = User_Unit_Designation_history.user_id','left'); 
        $this->db->join('master_designation','master_designation.id=User_Unit_Designation_history.Previous_designation','left');
        $this->db->join('master_designation as current_desg', 'current_desg.id = User_Unit_Designation_history.Current_designation','left');
        $this->db->join('personal_details as updated_person', 'updated_person.user_id = User_Unit_Designation_history.Updated_by','left');
        if($unit_id)
        {
            if(count($result)>0)
            {
                $this->db->group_start();
                $this->db->where('user_unit.unit_id', $unit_id);
                foreach ($result as $value) { 
                    $this->db->or_where('user_unit.unit_id', $value['id']);
                } 
                $this->db->group_end();

            }
            else
            {
                $this->db->where('user_unit.unit_id', $unit_id);
            }
        }
        if($status>0)
        {
            $this->db->where('User_Unit_Designation_history.Status', $status);
        }
        if($flag == 1 || $flag == 3){
            if (!empty($config['search_value'])) {
                $this->db->group_start();
                $search_value_escaped = $this->db->escape_like_str($config['search_value']);
                $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
                $this->db->or_like('master_designation.designation_name', $search_value_escaped);
                $this->db->or_like('User_Unit_Designation_history.user_id', $search_value_escaped);
                $this->db->or_like('current_desg.designation_name', $search_value_escaped);
                $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
                $this->db->group_end();
            }
            if($flag == 1){
                $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
                if($config['length'] != -1){
                    $this->db->limit($config['length'], $config['start']);
                }
                $query = $this->db->get();
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
    public function findUserRatesHistory($config,$flag)
    {
        $data=array(
            'personal_details.fname',
            'personal_details.lname',
            'history_user_rates.day_rate',
            'history_user_rates.night_rate',
            'history_user_rates.day_saturday_rate',
            'history_user_rates.day_sunday_rate',
            'history_user_rates.weekend_night_rate',
            'history_user_rates.updation_date',
            'history_user_rates.updated_userid'
        );
        $this->db->select($data);
        $this->db->from('history_user_rates'); 
        $this->db->join('personal_details','personal_details.user_id=history_user_rates.user_id');
        $this->db->join('personal_details as updated_person', 'updated_person.user_id = history_user_rates.updated_userid','left');
        if($flag == 1 || $flag == 3){
            if (!empty($config['search_value'])) {
                $this->db->group_start();
                $search_value_escaped = $this->db->escape_like_str($config['search_value']);
                $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
                $this->db->or_like('history_user_rates.day_rate', $search_value_escaped);
                $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
                $this->db->group_end();
            }
            if($flag == 1){
                $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
                if($config['length'] != -1){
                    $this->db->limit($config['length'], $config['start']);
                }
                $query = $this->db->get();
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
    public function findUserRatesHistoryByuser($unit_id,$config,$flag)
    {
        if($unit_id)
        {
            $this->db->select('unit.id');
            $this->db->from('unit');
            $this->db->where('parent_unit', $unit_id);
            $query = $this->db->get();
            $result = $query->result_array(); 
        }
        $data=array(
            'personal_details.fname',
            'personal_details.lname',
            'history_user_rates.day_rate',
            'history_user_rates.night_rate',
            'history_user_rates.day_saturday_rate',
            'history_user_rates.day_sunday_rate',
            'history_user_rates.weekend_night_rate',
            'history_user_rates.updation_date',
            'history_user_rates.updated_userid'
        );
        $this->db->select($data);
        $this->db->from('history_user_rates'); 
        $this->db->join('personal_details','personal_details.user_id=history_user_rates.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = history_user_rates.user_id','left');
        $this->db->join('personal_details as updated_person', 'updated_person.user_id = history_user_rates.updated_userid','left');
        if($unit_id)
        {
            if(count($result)>0)
            {
                $this->db->group_start();
                $this->db->where('user_unit.unit_id', $unit_id);
                foreach ($result as $value) { 
                    $this->db->or_where('user_unit.unit_id', $value['id']);
                } 
                $this->db->group_end();
            }
            else
            {
                $this->db->where('user_unit.unit_id', $unit_id);
            }
        }
        if($flag == 1 || $flag == 3){
            if (!empty($config['search_value'])) {
                $this->db->group_start();
                $search_value_escaped = $this->db->escape_like_str($config['search_value']);
                $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
                $this->db->or_like('history_user_rates.day_rate', $search_value_escaped);
                $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
                $this->db->group_end();
            }
            if($flag == 1){
                $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
                if($config['length'] != -1){
                    $this->db->limit($config['length'], $config['start']);
                }
                $query = $this->db->get();
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
    public function findUserContractHoursHistory($config,$flag)
    {
        $data=array(
            'personal_details.fname',
            'personal_details.lname',
            'history_weekly_hours.user_id',
            'history_weekly_hours.previous_contracted_hours',
            'history_weekly_hours.updated_contracted_hours',
            'history_weekly_hours.updation_date',
            'history_weekly_hours.updated_userid'
        );
        $this->db->select($data);
        $this->db->from('history_weekly_hours'); 
        $this->db->join('personal_details','personal_details.user_id=history_weekly_hours.user_id');
        $this->db->join('personal_details as updated_person', 'updated_person.user_id = history_weekly_hours.updated_userid','left');
        if($flag == 1 || $flag == 3){
            if (!empty($config['search_value'])) {
                $this->db->group_start();
                $search_value_escaped = $this->db->escape_like_str($config['search_value']);
                $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
                $this->db->or_like('history_weekly_hours.previous_contracted_hours', $search_value_escaped);
                $this->db->or_like('history_weekly_hours.updated_contracted_hours', $search_value_escaped);
                $this->db->or_like('history_weekly_hours.user_id', $search_value_escaped);
                $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
                $this->db->group_end();
            }
            if($flag == 1){
                $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
                if($config['length'] != -1){
                    $this->db->limit($config['length'], $config['start']);
                }
                $query = $this->db->get();
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
    public function findContractHoursHistoryByuser($unit_id,$config,$flag)
    {
        if($unit_id)
        {
            $this->db->select('unit.id');
            $this->db->from('unit');
            $this->db->where('parent_unit', $unit_id);
            $query = $this->db->get();
            $result = $query->result_array(); 
        }
        $data=array(
            'personal_details.fname',
            'personal_details.lname',
            'history_weekly_hours.user_id',
            'history_weekly_hours.previous_contracted_hours',
            'history_weekly_hours.updated_contracted_hours',
            'history_weekly_hours.updation_date',
            'history_weekly_hours.updated_userid'
        );
        $this->db->select($data);
        $this->db->from('history_weekly_hours'); 
        $this->db->join('personal_details','personal_details.user_id=history_weekly_hours.user_id'); 
        $this->db->join('user_unit', 'user_unit.user_id = history_weekly_hours.user_id','left');
        $this->db->join('personal_details as updated_person', 'updated_person.user_id = history_weekly_hours.updated_userid','left');
        if($unit_id)
        {
        if(count($result)>0)
            {
                $this->db->group_start();
                $this->db->where('user_unit.unit_id', $unit_id);
                foreach ($result as $value) { 
                    $this->db->or_where('user_unit.unit_id', $value['id']);
                } 
                $this->db->group_end();
            }
            else
            {
                $this->db->where('user_unit.unit_id', $unit_id);
            }
        }
        if($flag == 1 || $flag == 3){
            if (!empty($config['search_value'])) {
                $this->db->group_start();
                $search_value_escaped = $this->db->escape_like_str($config['search_value']);
                $this->db->or_like("CONCAT(personal_details.fname, ' ', personal_details.lname)", $search_value_escaped);
                $this->db->or_like('history_weekly_hours.previous_contracted_hours', $search_value_escaped);
                $this->db->or_like('history_weekly_hours.updated_contracted_hours', $search_value_escaped);
                $this->db->or_like('history_weekly_hours.user_id', $search_value_escaped);
                $this->db->or_like("CONCAT(updated_person.fname, ' ', updated_person.lname)", $search_value_escaped);
                $this->db->group_end();
            }
            if($flag == 1){
                $this->db->order_by($config['order_table'].'.'.$config['column_name'], $config['order_direction']);
                if($config['length'] != -1){
                    $this->db->limit($config['length'], $config['start']);
                }
                $query = $this->db->get();
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
}
?>