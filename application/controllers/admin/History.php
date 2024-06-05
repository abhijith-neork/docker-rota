<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class History extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type')==2)
        {
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        $this->load->model('History_model');
        $this->load->model('History_reports_model');
        $this->load->model('Unit_model');
        $this->load->model('Rota_model');
        $this->load->model('Training_model');
        $this->load->model('Dashboard_model');
        $this->load->helper('form');
        $this->load->helper('name');
    }
    public function get_addresschangehistory(){
        $order_table = 'history_staff_address';
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        if($column_index == 0){
            $sort_column_name = 'fname';
            $order_table = 'personal_details';
        }else if($column_index == 1){
            $sort_column_name = 'address';
        }else if($column_index == 2){
            $sort_column_name = 'kin_name';
        }else if($column_index == 3){
            $sort_column_name = 'kin_address';
        }else if($column_index == 4){
            $sort_column_name = 'kin_phonenumber';
        }else{
            $sort_column_name = 'changed_date';
        }
        $column_name = $sort_column_name;
        $order_direction = $order[0]['dir'];
        $config = array(
            'draw'            => $this->input->post('draw'),
            'start'           => $this->input->post('start'),
            'length'          => $this->input->post('length'),
            'search_value'    => $this->input->post('search[value]'),
            'column_name'     => $column_name,
            'order_direction' => $order_direction,
            'order_table' => $order_table
        );
        $id=$this->session->userdata('user_id');
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        { 
            $users = $this->History_model->findAddressHistory($config,1); 
            $total_count = $this->History_model->findAddressHistory($config,2); 
            $filter_count = $this->History_model->findAddressHistory($config,3); 
        }
        else
        {
            $units = $this->History_model->getUnitIdOfUser($id);
            if(count($units) > 0)
            { 
                $users = $this->History_model->findAddressHistoryByuser($units[0]['unit_id'],$config,1);
                $total_count = $this->History_model->findAddressHistoryByuser($units[0]['unit_id'],$config,2); 
                $filter_count = $this->History_model->findAddressHistoryByuser($units[0]['unit_id'],$config,3); 
            }
        }
        $data_arr = array();
        foreach ($users as $user) {
            $data_arr[] = array(
               "name" => $user['fname'].' '.$user['lname'],
               "address" => $user['address'],
               "kin_name" => $user['kin_name'],
               "kin_address" => $user['kin_address'],
               "kin_phone" => $user['kin_phonenumber'],
               "updation_date" => date("d/m/Y  H:i:s",strtotime($user['changed_date'])),
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    public function addresschangehistory()
    {
        $this->auth->restrict('Admin.addresshistory.View'); 
        $this->load->view('includes/home_header');
        $result = array(); 
        $this->load->helper('user');
        $data   =array();
        $params =array();
        $id = ''; 
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/history/addresschangehistory',$data);
        $result['js_to_load'] = array('history/addresschangehistory.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function get_userunitchangehistory()
    {
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        $order_table = 'User_Unit_Designation_history';
        if($column_index == 0){
            $sort_column_name = 'user_id';
        }else if($column_index == 1){
            $sort_column_name = 'fname';
            $order_table = 'personal_details';
        }else if($column_index == 2){
            $sort_column_name = 'unit_name';
            $order_table = 'unit';
        }else if($column_index == 3){
            $sort_column_name = 'unit_name';
            $order_table = 'current_unit';
        }else if($column_index == 4){
            $sort_column_name = 'Updation_date';
        }else{
            $sort_column_name = 'fname';
            $order_table = 'updated_person';
        }
        $column_name = $sort_column_name;
        $order_direction = $order[0]['dir'];
        $config = array(
            'draw'            => $this->input->post('draw'),
            'start'           => $this->input->post('start'),
            'length'          => $this->input->post('length'),
            'search_value'    => $this->input->post('search[value]'),
            'column_name'     => $column_name,
            "order_table"     => $order_table,
            'order_direction' => $order_direction,
        );
        $id = ''; 
        $id=$this->session->userdata('user_id');
        $status=2;
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {
            $users = $this->History_model->findUserunitHistory($status,$config,1);
            $total_count = $this->History_model->findUserunitHistory($status,$config,2);
            $filter_count = $this->History_model->findUserunitHistory($status,$config,3);
        }
        else
        {  
            $units = $this->History_model->getUnitIdOfUser($id);
            if(count($units) > 0) {
                $users = $this->History_model->findUserunitHistoryByuser($units[0]['unit_id'],$status,$config,1);
                $total_count = $this->History_model->findUserunitHistoryByuser($units[0]['unit_id'],$status,$config,2);
                $filter_count = $this->History_model->findUserunitHistoryByuser($units[0]['unit_id'],$status,$config,3); 
            }
        }
        $data_arr = array();
        foreach ($users as $user) {
            // print("<pre>".print_r($user,true)."</pre>");exit();
            $old_unit = $this->Unit_model->findunit($user['Previous_unit']);
            $current_unit = $this->Unit_model->findunit($user['Current_unit']);
            $this->load->model('User_model');
            $updated_user = '';
            if($user['Updated_by']){
                $updated_user = $this->User_model->finduserDetailsWithId($user['Updated_by']);
            }
            if(!empty($updated_user)){
                $updated_user = $updated_user[0]['fname'].' '.$updated_user[0]['lname'];
            }
            $data_arr[] = array(
                "user_id" => $user['User_id'],
                "name" => $user['fname'].' '.$user['lname'],
                "old_unit" => $old_unit[0]['unit_name'],
                "new_unit" => $current_unit[0]['unit_name'],
                "updation_date" => date("d/m/Y  H:i:s",strtotime($user['Updation_date'])),
                "updated_user" => $updated_user
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    public function userunitchangehistory()
    {
        $this->auth->restrict('Admin.userunithistory.View'); 
        $this->load->view('includes/home_header');
        $result = array(); 
        $this->load->helper('user');
        $data   =array();
        $params =array();
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/history/userunitchangehistory',$data);
        $result['js_to_load'] = array('history/userunitchangehistory.js');
        $this->load->view('includes/home_footer',$result);
    }

    public function userdesignationchangehistory()
    {
        $this->auth->restrict('Admin.designationhistory.View'); 
        $this->load->view('includes/home_header');
        $result = array(); 
        $this->load->helper('user');
        $data   =array();
        $params =array();
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/history/userdesignationchangehistory',$data);
        $result['js_to_load'] = array('history/userdesignationchangehistory.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function get_userdesignationchangehistory(){
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        $order_table = 'User_Unit_Designation_history';
        if($column_index == 0){
            $sort_column_name = 'user_id';
        }else if($column_index == 1){
            $sort_column_name = 'fname';
            $order_table = 'personal_details';
        }else if($column_index == 2){
            $sort_column_name = 'designation_name';
            $order_table = 'master_designation';
        }else if($column_index == 3){
            $sort_column_name = 'designation_name';
            $order_table = 'current_desg';
        }else if($column_index == 4){
            $sort_column_name = 'Updation_date';
        }else{
            $sort_column_name = 'fname';
            $order_table = 'updated_person';
        }
        $column_name = $sort_column_name;
        $order_direction = $order[0]['dir'];
        $config = array(
            'draw'            => $this->input->post('draw'),
            'start'           => $this->input->post('start'),
            'length'          => $this->input->post('length'),
            'search_value'    => $this->input->post('search[value]'),
            'column_name'     => $column_name,
            "order_table"     => $order_table,
            'order_direction' => $order_direction,
        );
        $id = ''; 
        $id=$this->session->userdata('user_id');
        $status=1;
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {
            $users = $this->History_reports_model->findUserDesgHistory($status,$config,1);
            $total_count = $this->History_reports_model->findUserDesgHistory($status,$config,2);
            $filter_count = $this->History_reports_model->findUserDesgHistory($status,$config,3);
        }
        else
        {  
            $units = $this->History_model->getUnitIdOfUser($id);
            if(count($units) > 0) {
                $users = $this->History_reports_model->findUserDesgHistoryByuser($units[0]['unit_id'],$status,$config,1);
                $total_count = $this->History_reports_model->findUserDesgHistoryByuser($units[0]['unit_id'],$status,$config,2);
                $filter_count = $this->History_reports_model->findUserDesgHistoryByuser($units[0]['unit_id'],$status,$config,3); 
            }
        }
        $data_arr = array();
        foreach ($users as $user) {
            $updated_user = '';
            $old_desig_name = '';
            $current_desg_name = '';
            $this->load->model('User_model');
            $this->load->model('Designation_model');
            // print("<pre>".print_r($user,true)."</pre>");exit();
            $old_desig = $this->Designation_model->finddesignation($user['Previous_designation']);
            if(!empty($old_desig)){
                $old_desig_name = $old_desig[0]['designation_name'];
            }
            $current_desg = $this->Designation_model->finddesignation($user['Current_designation']);
            if(!empty($current_desg)){
                $current_desg_name = $current_desg[0]['designation_name'];
            }
            if($user['Updated_by']){
                $updated_user = $this->User_model->finduserDetailsWithId($user['Updated_by']);
            }
            if(!empty($updated_user)){
                $updated_user = $updated_user[0]['fname'].' '.$updated_user[0]['lname'];
            }
            $data_arr[] = array(
                "user_id" => $user['User_id'],
                "name" => $user['fname'].' '.$user['lname'],
                "old_desg" => $old_desig_name,
                "new_desg" => $current_desg_name,
                "updation_date" => date("d/m/Y  H:i:s",strtotime($user['Updation_date'])),
                "updated_user" => $updated_user
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    public function get_contracthourschangehistory(){
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        $order_table = 'history_weekly_hours';
        if($column_index == 0){
            $sort_column_name = 'user_id';
        }else if($column_index == 1){
            $sort_column_name = 'fname';
            $order_table = 'personal_details';
        }else if($column_index == 2){
            $sort_column_name = 'previous_contracted_hours';
        }else if($column_index == 3){
            $sort_column_name = 'updated_contracted_hours';
        }else if($column_index == 4){
            $sort_column_name = 'fname';
            $order_table = 'updated_person';
        }else{
            $sort_column_name = 'updation_date';
        }
        $column_name = $sort_column_name;
        $order_direction = $order[0]['dir'];
        $config = array(
            'draw'            => $this->input->post('draw'),
            'start'           => $this->input->post('start'),
            'length'          => $this->input->post('length'),
            'search_value'    => $this->input->post('search[value]'),
            'column_name'     => $column_name,
            "order_table"     => $order_table,
            'order_direction' => $order_direction,
        );
        $id = ''; 
        $id=$this->session->userdata('user_id');
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        { 
            $users = $this->History_reports_model->findUserContractHoursHistory($config,1);
            $total_count = $this->History_reports_model->findUserContractHoursHistory($config,2);
            $filter_count = $this->History_reports_model->findUserContractHoursHistory($config,3);
        }
        else
        { 
            $units = $this->History_model->getUnitIdOfUser($id);
            if(count($units) > 0) {
                $users = $this->History_reports_model->findContractHoursHistoryByuser($units[0]['unit_id'],$config,1);
                $total_count = $this->History_reports_model->findContractHoursHistoryByuser($units[0]['unit_id'],$config,2);
                $filter_count = $this->History_reports_model->findContractHoursHistoryByuser($units[0]['unit_id'],$config,3);
            }
        }
        $data_arr = array();
        foreach ($users as $user) {
            $updated_user = '';
            $this->load->model('User_model');
            if($user['updated_userid']){
                $updated_user = $this->User_model->finduserDetailsWithId($user['updated_userid']);
            }
            if(!empty($updated_user)){
                $updated_user = $updated_user[0]['fname'].' '.$updated_user[0]['lname'];
            }
            $data_arr[] = array(
                "user_id" => $user['user_id'],
                "name" => $user['fname'].' '.$user['lname'],
                "prev_hour" => $user['previous_contracted_hours'],
                "current_hour" => $user['updated_contracted_hours'],
                "updated_user" => $updated_user,
                "updation_date" => date("d/m/Y  H:i:s",strtotime($user['updation_date'])),
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    public function contracthourschangehistory()
    {
        $this->auth->restrict('Admin.contracthours.View'); 
        $this->load->view('includes/home_header');
        $result = array(); 
        $this->load->helper('user');
        $data   =array();
        $params =array();
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/history/contract_hours_history',$data);
        $result['js_to_load'] = array('history/contract_hours_history.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function get_userrateschangehistory(){
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        $order_table = 'history_user_rates';
        if($column_index == 0){
            $sort_column_name = 'fname';
            $order_table = 'personal_details';
        }else if($column_index == 1){
            $sort_column_name = 'day_rate';
        }else if($column_index == 2){
            $sort_column_name = 'updation_date';
        }else{
            $sort_column_name = 'fname';
            $order_table = 'updated_person';
        }
        $column_name = $sort_column_name;
        $order_direction = $order[0]['dir'];
        $config = array(
            'draw'            => $this->input->post('draw'),
            'start'           => $this->input->post('start'),
            'length'          => $this->input->post('length'),
            'search_value'    => $this->input->post('search[value]'),
            'column_name'     => $column_name,
            "order_table"     => $order_table,
            'order_direction' => $order_direction,
        );
        $id = ''; 
        $id=$this->session->userdata('user_id');
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        { 
            $users = $this->History_reports_model->findUserRatesHistory($config,1);
            $total_count = $this->History_reports_model->findUserRatesHistory($config,2);
            $filter_count = $this->History_reports_model->findUserRatesHistory($config,3);
        }
        else
        {
            $units = $this->History_model->getUnitIdOfUser($id);
            if(count($units) > 0) {
                $users = $this->History_reports_model->findUserRatesHistoryByuser($units[0]['unit_id'],$config,1);
                $total_count = $this->History_reports_model->findUserRatesHistoryByuser($units[0]['unit_id'],$config,2);
                $filter_count = $this->History_reports_model->findUserRatesHistoryByuser($units[0]['unit_id'],$config,3); 
            }
        }
        foreach ($users as $user) {
            $updated_user = '';
            $this->load->model('User_model');
            if($user['updated_userid']){
                $updated_user = $this->User_model->finduserDetailsWithId($user['updated_userid']);
            }
            if(!empty($updated_user)){
                $updated_user = $updated_user[0]['fname'].' '.$updated_user[0]['lname'];
            }
            $data_arr[] = array(
                "name" => $user['fname'].' '.$user['lname'],
                "pay_rate" => $user['day_rate'],
                "updation_date" => date("d/m/Y  H:i:s",strtotime($user['updation_date'])),
                "updated_user" => $updated_user
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    public function userrateschangehistory()
    {
        $this->auth->restrict('Admin.userrateshistory.View'); 
        $this->load->view('includes/home_header');
        $result = array(); 
        $this->load->helper('user');
        $data   =array();
        $params =array();
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/  
        $this->load->view('admin/history/userrateschangehistory',$data);
        $result['js_to_load'] = array('history/userrateschangehistory.js');
        $this->load->view('includes/home_footer',$result);
    }

    public function rotaupdatehistory()
    {
    $this->auth->restrict('Admin.rotaupdatehistory.View'); 
    $this->load->view('includes/home_header');
    $result = array(); 
    $this->load->helper('user');
    $data   =array(); 
      $data['year']=date('Y');
      $data['month']=date('m');
       // if($this->session->userdata('user_id')==1)
       //  { 
       //      $data['unit'] = $this->User_model->fetchunit('');   
       //  }
       //      else
       //  {
       //      $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); 
       //  } 
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        //if($this->session->userdata('user_type')==1) //all super admin can access
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $data['type']=$this->History_model->findActivityType(); 
        $data['start_date']=date('d/m/Y');  
        $data['end_date']=date('d/m/Y');
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
    $this->load->view('admin/history/rotaupdatehistory',$data);
    $result['js_to_load'] = array('history/rotaupdatehistory.js');
    $this->load->view('includes/home_footer',$result); 
    }
    
    public function rotaupdatehistoryreport()
    {  //print_r($this->input->post('start_time'));exit();
        $columns = array(
            0=> 'id',
            1=> 'add_type',
            2=> 'description',
            3=> 'activity_date',
            4=> 'activity_by', 
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];  
        $dir = $this->input->post('order')[0]['dir'];
        $unit = $this->input->post('unit');  //print_r($unit);exit(); 
        $params = array();
        if($this->input->post('start_time')=='')
        {
            $params['start_time']='';
        }
        else
        {
            $start_time = $this->input->post('start_time');
            $new_date = explode('/', $start_time); 
            $from_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0]; 
            $params['start_time'] = $from_date;
        }
        if($this->input->post('end_time')=='')
        {
            $params['end_time']='';
        }
        else
        {
            $end_time = $this->input->post('end_time'); //
            $old_date = explode('/', $end_time); 
            $to_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $params['end_time'] = $to_date; 
        }
        
        $activity_type = $this->input->post('activity_type'); //
       
        $params['activity_type'] = $activity_type;  
        //if($this->session->userdata('user_type') ==1)
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {  
            $params['unit'] = $unit;

                //print_r($this->input->post('search')['value']);
                if(empty($this->input->post('search')['value']))
                {
                    $totalData = $this->History_model->rotaupdate_count($params);
                    $totalFiltered = $totalData;
                    $posts = $this->History_model->rotaupdate($limit,$start,$order,$dir,$params);
                }
                else
                {
                    $search = $this->input->post('search')['value'];
                    $totalData = $this->History_model->rotaupdatesearch_count($search,$params);
                    $totalFiltered = $totalData; 
                    $posts =  $this->History_model->rotaupdate_search($search,$params,$limit,$start,$order,$dir);
                    
                }
        }
        else 
        {   
            if($this->input->post('unit')=='')
            {
                 $unit_id=$this->session->userdata('unit_id');
            }
            else
            { 
                 $unit_id=$this->input->post('unit');
            }
            $params['unit'] =  $unit_id;  
             
            
            //print_r($this->input->post('search')['value']);
            if(empty($this->input->post('search')['value']))
            {
                $totalData = $this->History_model->rotaupdate_count($params);
                $totalFiltered = $totalData;
                $posts = $this->History_model->rotaupdate($limit,$start,$order,$dir,$params);
            }
            else
            {
                $search = $this->input->post('search')['value'];
                $totalData = $this->History_model->rotaupdatesearch_count($search,$params);
                $totalFiltered = $totalData;
                $posts =  $this->History_model->rotaupdate_search($search,$params,$limit,$start,$order,$dir);
                
            }
            
            
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            { 
                if(($post->activity_date)==''){ $activity_date=" "; } else { $activity_date=date("d/m/Y H:i:s",strtotime($post->activity_date));}
                $nestedData['add_type'] = $post->add_type;
                $nestedData['description'] = $post->description;
                $nestedData['activity_date'] =$activity_date; 
                $nestedData['activity_by'] = $post->fname.' '.$post->lname;
                
                $data[] = $nestedData;
                
            }
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
        
    }
 
    public function CheckinAccuracy()
    { 
    $this->auth->restrict('Admin.History.Checkin_Checkout'); 
    $this->load->view('includes/home_header');
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
     $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        //if($this->session->userdata('user_type')==1) //all super admin can access
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }

        $data['start_date']=date('d/m/Y');  
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
    $this->load->view('admin/history/checkin_accuracy_report',$data);
    $result['js_to_load'] = array('history/checkin_accuracy_report.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function CheckinAccuracyReport()
    {
      //print_r($this->input->post());exit();
        $columns = array(
            0=> 'id',
            1=> 'Accuracy',
            2=> 'User',
            3=> 'Staff Name',
            4=> 'Check With UserID',
            5=> 'Date', 
            6=> 'Status', 
        );  
        
        $limit = $this->input->post('length'); 
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $unit = $this->input->post('unit');  //print_r($unit);exit(); 
        $params = array();
        if($this->input->post('start_time')=='')
        {
            $params['start_time']=date('Y-m-d');
        }
        else
        {
            $start_time = $this->input->post('start_time');
            $new_date = explode('/', $start_time); 
            $from_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0]; 
            $params['start_time'] = $from_date;
        }

        //if($this->session->userdata('user_type') ==1)
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {  
            $params['unit'] = $unit;
        
               if(empty($this->input->post('search')['value']))
                { 
                    $totalData = $this->History_model->getAccuracycount($params); //print_r($totalData);exit();
                    $totalFiltered = $totalData;
                    $posts =$this->History_model->getAcuracydetails($limit,$start,$order,$dir,$params);  //print_r($posts);exit();
                }
                else
                { 
                    $search = $this->input->post('search')['value'];
                    $totalData = $this->History_model->getAccuracySearchcount($search,$params); 
                    $totalFiltered = $totalData;
                    $posts =  $this->History_model->getAccuracySearchdetails($search,$params,$limit,$start,$order,$dir);
                    
                }
        }
        else 
        {   
            if($this->input->post('unit')=='')
            {
                 $unit_id=$this->session->userdata('unit_id');
            }
            else
            { 
                 $unit_id=$this->input->post('unit');
            }
            $params['unit'] =  $unit_id;  
             
            
            //print_r($this->input->post('search')['value']);
                if(empty($this->input->post('search')['value']))
                { 
                    $totalData = $this->History_model->getAccuracycount($params); 
                    $totalFiltered = $totalData;
                    $posts =$this->History_model->getAcuracydetails($limit,$start,$order,$dir,$params); 
                }
                else
                { 
                    $search = $this->input->post('search')['value'];
                    $totalData = $this->History_model->getAccuracySearchcount($search,$params); 
                    $totalFiltered = $totalData;
                    $posts =  $this->History_model->getAccuracySearchdetails($search,$params,$limit,$start,$order,$dir);
                    
                }
            
            
        }
        // print_r('<pre>');
        // print_r($posts); print '<br>';
        // exit();

         $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            { 
                if(($post->date)==''){ $activity_date=" "; } else { $activity_date=date("d/m/Y",strtotime($post->date));}
                if($post->status==2){ $status_new="CheckIn";}else if($post->status==3){$status_new="CheckOut";} 
                if($post->with_userid==1){ $check_status="Yes";}else{ $check_status="No";}

                $nestedData['ID'] = $post->id;
                $nestedData['Accuracy'] = $post->accuracy;
                $nestedData['User'] = $post->user_id;
                $nestedData['Staff Name'] =$post->fname.' '.$post->lname;  
                $nestedData['Check With UserID'] = $check_status;
                $nestedData['Date'] = $activity_date;
                $nestedData['Status'] = $status_new;
                
                $data[] = $nestedData;
                
            }
        }
          $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
        
    }


      public function Email_report()
    { 

    $this->load->view('includes/home_header');
    $result = array(); 
    $this->load->helper('user');
    $data=array();  
    $this->load->view('admin/reports/email_report',$data);
    $result['js_to_load'] = array('history/email_report.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function emailreport()
    {
        $columns = array(
            0=> 'id',
            1=> 'Accuracy',
            2=> 'User',
            3=> 'Staff Name',
            4=> 'Check With UserID',
            5=> 'Date', 
            6=> 'Status', 
        );  
        
        $limit = $this->input->post('length'); 
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

                
                if(empty($this->input->post('search')['value']))
                { 
                    $totalData = $this->History_model->getEmaildetails_count(); 
                    $totalFiltered = $totalData;
                    $posts =$this->History_model->getEmaildetails($limit,$start,$order,$dir); 
                }
                else
                { 
                    $search = $this->input->post('search')['value'];
                    $totalData = $this->History_model->getEmaildetailssearch_count($search); 
                    $totalFiltered = $totalData;
                    $posts =  $this->History_model->getEmaildetails_search($search);
                    
                }



        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {  

              $abc=explode(",",$post->email_settings);  
                                                    
                                                    $new=explode(" ",$abc['13']);  //print_r($new); print '<br>';

                                                    $Accuracy=explode("=",$new['1']);                                                  
                                                    if($Accuracy[0]=='Accuracy')
                                                    {  
                                                      $new_accuracy=$Accuracy['1'];
                                                    }
                                                    else
                                                    {  
                                                      
                                                      $Accuracy1=explode("=",$new['10']);  
                                                      $accu_racy=explode(")",$Accuracy1[1]);  
                                                      $new_accuracy=$accu_racy['0'];
                                                    }

                                                    $User=explode("=",$new[3]);
                                                    if($User[0]=='user#')
                                                    {  
                                                      $new_User=$User['1'];
                                                    }
                                                    else
                                                    {   
                                                      
                                                      $User1=explode("=",$new['1']);  
                                                      $new_User=$User1['1'];
                                                    }  

                                                    $Check=explode("=",$new[6]);  
                                                    if($Check[0]=="userID")
                                                    {
                                                      $new_check=$Check[1];
                                                    }
                                                    else
                                                    {
                                                      $new_check=1;
                                                    }  

                                                    if($new_check==1){ $check_status="Yes";}else{ $check_status="No";}


                                                    $status=explode("=",$new[2]);  
                                                    if($status[0]=="Status")
                                                    {
                                                      $new_status=$status[1];
                                                    }
                                                    else
                                                    {
                                                      $status1=explode("=",$new['5']);  
                                                      $new_status=$status1['1'];
                                                    }
 

                                                    if($new_status==1 || $new_status==2){ $status_new="CheckIn";}else{$status_new="CheckOut";}  

                                                    $new_date= date("d/m/Y  H:i:s",strtotime($post->created_at));

                                                    $result=$this->History_model->getEmailreportstaffname($new_User); //print_r($result); print '<br>';
                                                    

                $nestedData['ID'] = $post->id;
                $nestedData['Accuracy'] = $new_accuracy;
                $nestedData['User'] = $new_User;
                $nestedData['Staff Name'] =$result[0]['full_name'];  
                $nestedData['Check With UserID'] = $check_status;
                $nestedData['Date'] = $new_date;
                $nestedData['Status'] = $status_new;
                
                $data[] = $nestedData;
                
            }  //exit();
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
        
    }

    
}
?>