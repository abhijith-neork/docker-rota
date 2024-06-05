<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
     
  class Notes extends CI_Controller {
    /**
       * Get All Data from this method.
       *
       * @return Response
       */
      public function __construct() {
          Parent::__construct();
            
          if($this->session->userdata('user_type') == 2 ){
              $this->auth->logout();            
              unset($params);
              $this->_login(INVALID_LOGIN);
          }
          //Loading models
          $this->load->model('Note_model');
          $this->load->model('User_model');
          $this->load->model('Rota_model');
            $this->load->model('Leave_model');
            $this->load->model('Unit_model');
          $this->load->helper('form');
      }
    public function index()
    {
        $result = array();
        $result['error'] ='';
        $data   = array();
        $login_user_id = $this->session->userdata('user_id');
        $this->load->helper('user');
        $this->load->view('includes/home_header',$result);
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->load->model('Rota_model');
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/          
        $this->load->view("manager/notes/listnotes",$data);
        $jsfile['js_to_load'] = array('notes/notes.js');
        $this->load->view('includes/home_footer',$jsfile);
    }
    public function get_notes_users(){
        $note_id = $this->input->post('note_id');
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        if($column_index == 0){
            $sort_column_name = 'fname';
        }else{
            $sort_column_name = 'designation_name';
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
        );
        
        $users = $this->Note_model->find_Notification_Users($note_id,$config); 
        $total_users = count($this->Note_model->findUsers($note_id)); 
        $total_users_filter_count = $this->Note_model->find_Notification_Users_Filter_Count($note_id,$config); 
        $data_arr = array();
        foreach ($users as $user) {
            $data_arr[] = array(
               "employee_name" => $user['fname'].' '.$user['lname'],
               "designation" => $user['designation_name']
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $total_users,
            'recordsFiltered' => $total_users_filter_count,
            'data' => $data_arr
        );
        echo json_encode($response);
    }
    public function get_notes(){
        $order = $this->input->post('order');
        $column_index = $order[0]['column'];
        if($column_index == 0){
            $sort_column_name = 'title';
        }else if($column_index == 1){
            $sort_column_name = 'comment';
        }else{
            $sort_column_name = 'creation_date';
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
            'selectedValues'  => (array)$this->input->post('selectedValues')
        );
        $login_user_id = $this->session->userdata('user_id');
        $sub=$this->User_model->CheckuserUnit($login_user_id);
        $unit=$this->User_model->findunitofuser($login_user_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']);  
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {  
            $notes=$this->Note_model->getAllNotes([],$config);
            $notes_count=$this->Note_model->getAllNotesCount([]);
            $filter_count=$this->Note_model->getAllNotesFilterCount([],$config);
        }
        else if($this->session->userdata('subunit_access')==1)
        {
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
                $notes=$this->Note_model->getAllNotesforAdmin($sub,$config);  
                $notes_count=$this->Note_model->getAllNotesforAdminCount($sub);  
                $filter_count=$this->Note_model->getAllNotesforAdminFilterCount($sub,$config);  
            }
            else
            {   
                $user_unitids=$this->Leave_model->getUnitIdOfUserAsArray($login_user_id);
                $notes=$this->Note_model->getAllNotes($user_unitids,$config);
                $notes_count=$this->Note_model->getAllNotesCount($user_unitids);
                $filter_count=$this->Note_model->getAllNotesFilterCount($user_unitids,$config);
            }
        }
        else
        { 
           $user_unitids=$this->Leave_model->getUnitIdOfUserAsArray($login_user_id);
           $notes=$this->Note_model->getAllNotes($user_unitids,$config);
           $notes_count=$this->Note_model->getAllNotesCount($user_unitids);
           $filter_count=$this->Note_model->getAllNotesFilterCount($user_unitids,$config);
        }
        $data_arr = array();
        foreach ($notes as $row) {
            $description = $row['comment'];
            $title = '<a href="' . base_url("manager/notes/editNote/") . $row['id'] . '">' . $row['title'] . '</a>';
            if($row['notification_type'] == 1){
                $notification_type = "Both (SMS & Mail)";
            }else if($row['notification_type'] == 2){
                $notification_type = 'SMS';
            }else{
                $notification_type = 'Mail';
            }
            $user = $this->Note_model->getupdatedusernote($row['updated_userid']);
            $added_by = $user[0]['fname']." ".$user[0]['lname'];
            $date_time = date("d/m/Y H:i:s",strtotime($row['creation_date']));
            
            $action = '<center><a class="View"  data-id="'.$row['id'].'" title="View" href="' . base_url("manager/notes/editNote/") . $row['id'] . '"><i class="fas fa-eye"></i></a></center>';

            $data_arr[] = array(
               "title" => $title,
               "description" => $description,
               "notification_type" => $notification_type,
               "date_time" => $date_time,
               "added_by" => $added_by,
               "action" => $action
            );
        }
        $response = array(
            'draw' => $config['draw'],
            'recordsTotal' => $notes_count,
            'recordsFiltered' => $filter_count,
            'data' => $data_arr,
            'uniqueValues' => ['Both (SMS & Mail)','Mail','SMS']
        );
        echo json_encode($response);
    }
      public function getParentAndSubunits(){
        $units = $this->Unit_model->findAllUnits();
        $unit_array = array();
        foreach ($units as  $value) 
        {
          $subunits_ids = array();
          $subunits = $this->Unit_model->findsubunits($value['id']);
          if(count($subunits) > 0){
            foreach ($subunits as $subunit) {
              array_push($subunits_ids, $subunit['id']);
            }
          }
          $unit_array[$value['id']] = $subunits_ids;
        }
        return $unit_array;
      }
        
      public function addNote(){
            $this->auth->restrict('Admin.Notes.Add');
        $result = array();
        $data = array();
        $result['error'] ='';
        $login_user_id = $this->session->userdata('user_id');
        $this->load->helper('form');
        $this->load->library('form_validation');
         
          $header['title'] = 'Add Notifications';
          $this->load->view('includes/home_header',$result);

                  $u_id=$this->session->userdata('user_id');  
                  $sub=$this->User_model->CheckuserUnit($u_id);
                  $unit=$this->User_model->findunitofuser($u_id); 
                  $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                  //if($this->session->userdata('user_type')==1) //all super admin can access
                  if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                  {
                    $data['units'] = $this->User_model->fetchCategoryTree();  
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
                        $data['units'] = $this->User_model->fetchSubTree($sub);  
                    }
                    else
                    {    
                        $data['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                    }

                  }
                  else
                  {
                     $data['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                    
                  }

            $data['notes_type']=3;
            $unit_array = $this->getParentAndSubunits();
            $data['unit_array'] = json_encode($unit_array);
          $this->load->view("manager/notes/addnotes",$data);
          $jsfile['js_to_load'] = array('notes/notes_add_edit.js');
          $this->load->view('includes/home_footer',$jsfile);
        // $this->form_validation->set_rules('title', 'Title', 'required');
      }
      public function findUsersOfUnselectedUnit(){
        $users_array = array();
        $units=$this->input->post('units');
        $login_user_id = $this->session->userdata('user_id');
        $result=$this->Unit_model->findUsersOfUnselectedUnit($units,$login_user_id);
        foreach ($result as $row){
          $user_name = $row['fname']." ".$row['lname'];
          $user_data = $row['user_id']."_".$row['unit_id']."_".$user_name."_".$row['unit_shortname'];
          array_push($users_array, $user_data);
        }
        header("Content-Type: application/json");
        echo json_encode($users_array);
        exit();
      }
      public function findUser()
        {
           
            $json = array();
            $unit_id=$this->input->post('unit_id');
            $selected_ids=array();
            $selected_ids=$this->input->post('selected_ids');
            $login_user_id = $this->session->userdata('user_id');
            //if($this->session->userdata('user_type') == 1)
            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $result=$this->Note_model->findUser($unit_id,$selected_ids,'');
            }
            else
            {
                $result=$this->Note_model->findUser($unit_id,$selected_ids,$login_user_id);
            }
            $i=0;

          foreach ($result as $row)
          {
            $i++;
            $user_name = $row['fname'].' '.$row['lname'];
            // print("<pre>".print_r($user_name,true)."</pre>");
            $json[] =array($row['fname']." ".$row['lname'],$row['designation_name'],'<input type="checkbox"  name="usercheck[]" class="checkItem" id="checks_'.$i.'" data-unitid="'.$row['unit_id'].'"  data-unit="'.$row['unit_shortname'].'" data-designation="'.$row['designation_name'].'" data-username="'.$user_name.'" data-email="'.$row['email'].'" value="'.$row['user_id'].'___'.$row['email'].'___'.$row['unit_id'].'___'.$row['unit_shortname'].'___'.$user_name.'"  >');
          }
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
      }
        public function editNote(){
            $this->auth->restrict('Admin.Notes.Edit');
            $result = array();
            $data = array();
            $user_details = array();
            $result['error'] ='';
            $note_id=$this->uri->segment(4);

            $login_user_id = $this->session->userdata('user_id');
            $this->load->helper('form');
            $this->load->library('form_validation');
             
            $header['title'] = 'Edit Notifications';
            $this->load->view('includes/home_header',$result);

            // if($this->session->userdata('user_type') == 1)
            // {
            //     $data['units']=$this->Note_model->getUnits('');
            // }
            // else
            // {
            //     $data['units']=$this->Note_model->getUnits($login_user_id);
            // }
 
                  $sub=$this->User_model->CheckuserUnit($login_user_id);
                  $unit=$this->User_model->findunitofuser($login_user_id); 
                  $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                  //if($this->session->userdata('user_type')==1) //all super admin can access
                  if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                  {
                    $data['units'] = $this->User_model->fetchCategoryTree();  
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
                        $data['units'] = $this->User_model->fetchSubTree($sub);  
                    }
                    else
                    {    
                        $data['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                    }

                  }
                  else
                  {
                     $data['units']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                    
                  }


            $data['note'] = $this->Note_model->getSingleNote($note_id);
            $data['users'] = $this->Note_model->findUsers($note_id);  
            $unit_ids = $data['note'][0]['unit_id'];
            $unit_id_array = explode(',', $unit_ids); 
            $data['unit_id_array'] = $unit_id_array;
            $data['unit_id_string'] = $data['note'][0]['unit_id'];
            $data['id'] =$note_id;
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('comment', 'comment', 'required');
            $this->form_validation->set_rules('notification_type', 'Notification type', 'required');
            if ($this->form_validation->run() == FALSE)
            {
                $data['error']='';
          
            $this->load->view("manager/notes/editnote",$data);
            }
            else{
             
                $title   = $this->input->post('title');
                $comment = $this->input->post('comment');
                $notification_type = $this->input->post('notification_type');
                $unit    = $this->input->post('unit');
                $id      = $this->input->post('id');
                if(is_array($unit)){
                    $users_checked = isset($_POST['usercheck']) ? $_POST['usercheck'] : NULL;
                    if($users_checked) {
                        $unit_ids = implode(",", $unit);
                        $status  = 0;
                        $creation_date = date('Y-m-d H:i:s');
                        $updation_date = date('Y-m-d H:i:s');
                        $updated_userid = $login_user_id;
                        $user_ids = array();
                        $user_emails = array();
                        $supervisor_name = $this->session->userdata('full_name');
                        $note_details    = array(
                            'title'=>$title,
                            'comment'=>$comment,
                            'notification_type'=>$notification_type, 
                            'updation_date'=>$updation_date,
                            'updated_userid'=>$updated_userid,
                            'unit_id'=> $unit_ids
                        );  
                        $update_status = $this->Note_model->updateNoteDetails($note_id, $note_details);
                        if($update_status == 1){
                            $this->Note_model->deleteStaffNotes($note_id);
                            foreach($_POST['usercheck'] as $user) {
                                $splittedstring = explode("___",$user);
                                $note_staff    = array(
                                    'user_id'=>$splittedstring[0],
                                    'note_id'=>$note_id,
                                    'status'=>$status,
                                    'creation_date'=>$creation_date,
                                    'updation_date'=>$updation_date,
                                    'updated_userid'=>$updated_userid,
                                );
                                $staff_name=$this->Note_model->findstaffname($splittedstring[0]);
                                $note_staff_details = $this->Note_model->insertNoteStaffDetails($note_staff);
                                if($notification_type == 1 || $notification_type == 3){
                                    $site_title = 'St Matthews Healthcare - SMH Rota : Send you a note';
                                    $admin_email=getCompanydetails('from_email');
                                    $emailSettings = array();
                                    $emailSettings = array(
                                        'from' => $admin_email,
                                        'site_title' => $site_title,
                                        'site_url' => $this->config->item('base_url'),
                                        'to' => $splittedstring[1],
                                        'type' => 'Edit Notification',
                                        'user_name' => $supervisor_name,
                                        'comment'=> $comment,
                                        'staff_name' => $staff_name[0]['fname'].' '.$staff_name[0]['lname'],
                                        'subject' => $title,
                                        'content_title'=>'',
                                    );
                                    $this->load->library('parser');
                                    $htmlMessage = $this->parser->parse('emails/sendnote', $emailSettings, true);
                                    $this->load->helper('mail');
                                    sendMail($emailSettings, $htmlMessage);
                                }
                                if($notification_type == 1 || $notification_type == 2){
                                    $user_details = $this->User_model->getSingleUser($splittedstring[0]);
                                    $mobilenumber = $user_details[0]['mobile_number'];
                                    /*if($mobilenumber){
                                      //sms integration
                                      $message = $comment;
                                      $this->load->model('AwsSnsModel');
                                      $sender_id="03";
                                      $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $message);
                                    }*/
                                }
                            }
                            $this->session->set_flashdata('message', 'Notification updated and sent successfully');
                            
                            redirect('manager/notes');
                        }else{
                            $data['error']='Notification updation failed due to db errors';
                            $this->load->view("manager/notes/editnote",$data);
                        }
                    }else{
                        $data['error']='Please select users';
                        $this->load->view("manager/notes/editnote",$data);
                    }
                }else{
                    $data['error']='Please select units';
                    $this->load->view("manager/notes/editnote",$data);
                }
            }
            $jsfile['js_to_load'] = array('notes/notes_edit.js');
            $this->load->view('includes/home_footer',$jsfile);
        }
      public function sendNote(){
        $result = array();
        $data = array(); 
        $this->load->helper('form');
        $this->load->library('form_validation');
        $login_user_id = $this->session->userdata('user_id');
            //if($this->session->userdata('user_type') == 1)
            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $data['units']=$this->Note_model->getUnits('');
            }else{
                $data['units']=$this->Note_model->getUnits($login_user_id);
            }
            if($this->input->post('notification_type')!='')
            {
              $data['notes_type']=$this->input->post('notification_type');
            }
            else
            {
              $data['notes_type']=3;
            }
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('comment', 'comment', 'required');
            $this->form_validation->set_rules('notification_type', 'notification type', 'required');
        
          $header['title'] = 'Send Notification';
          $this->load->view('includes/home_header',$result);
    
          if ($this->form_validation->run() == FALSE)
          {
            $data['error']='';
            $this->load->view("manager/notes/addnotes",$data); 
          }
          else
          {
           
            $title   = $this->input->post('title'); 
            $comment = $this->input->post('comment');
                $notification_type = $this->input->post('notification_type');
                $unit    = $this->input->post('unit');
                if(is_array($unit)){
                    if(!empty($_POST['usercheck'])) {
                        $unit_ids = implode(",", $unit);
                        $status  = 0;
                        $creation_date = date('Y-m-d H:i:s');
                        $updation_date = date('Y-m-d H:i:s');
                        $updated_userid = $login_user_id;
                        $user_ids = array();
                        $user_emails = array();
                        $supervisor_name = $this->session->userdata('full_name');
                        $note_details    = array(
                            'title'=>$title,
                            'comment'=>$comment,
                            'notification_type'=>$notification_type,
                            'status'=>$status,
                            'creation_date'=>$creation_date,
                            'updation_date'=>$updation_date,
                            'updated_userid'=>$updated_userid,
                            'unit_id'=> $unit_ids
                        ); 
                        $new_users=$this->input->post('selected_users');  // selected users from table
                        $users = explode(",", $new_users[0]); //print_r($users);exit();
                        $note_id = $this->Note_model->insertNoteDetails($note_details);
                        if($note_id){

                              $title='Add Notes';
                              InsertEditedData($note_details,$title);

                            foreach($users as $user) {  
                                $splittedstring = explode("_",$user);//print_r($splittedstring); print '<br>';
                                $note_staff    = array(
                                    'user_id'=>$splittedstring[0],
                                    'note_id'=>$note_id,
                                    'status'=>$status,
                                    'creation_date'=>$creation_date,
                                    'updation_date'=>$updation_date,
                                    'updated_userid'=>$updated_userid,
                                );
                                //print_r($note_staff);exit();
                                $staff_name=$this->Note_model->findstaffname($splittedstring[0]); //added by swaraj dec18
                                $note_staff_details = $this->Note_model->insertNoteStaffDetails($note_staff);
                                if($notification_type == 1 || $notification_type == 3){
                                    $site_title = 'St Matthews Healthcare:';
                                    $admin_email=getCompanydetails('from_email');
                                    $emailSettings = array();
                                    $emailSettings = array(
                                        'from' => $admin_email,
                                        'site_title' => $site_title,
                                        'site_url' => $this->config->item('base_url'),
                                        'to' => $staff_name[0]['email'],
                                        'type' => 'Add Notification',
                                        'user_name' => $supervisor_name,
                                        'comment'=> $comment,
                                        'staff_name' => $staff_name[0]['fname'].' '.$staff_name[0]['lname'],
                                        'subject' => $site_title.' '.$title,
                                        'content_title'=>'We are glad to have you!',
                                    );
                                    ///print_r($emailSettings);//exit();
                                    $this->load->library('parser');
                                    $htmlMessage = $this->parser->parse('emails/sendnote', $emailSettings, true);
                                    //die($htmlMessage); 
                                    $this->load->helper('mail');
                                    sendMail($emailSettings, $htmlMessage);
                                }

                                if($notification_type == 1 || $notification_type == 2){
                                    $user_details = $this->User_model->getSingleUser($splittedstring[0]); 
                                    $mobilenumber = $user_details[0]['mobile_number']; 
                                    if($mobilenumber){
                                      //sms integration
                                      $message = $comment;
                                      $this->load->model('AwsSnsModel');
                                      $sender_id="SMHNote";
                                      $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $message);
                                    }
                                }                                  
                            }
                            //exit();
                            $data['error']='';
                            $this->session->set_flashdata('message', 'Added successfully');
                            redirect('manager/notes');
                        }else{
                            $data['error']='Notification sending failed due to db errors';
                            $this->load->view("manager/notes/addnotes",$data);
                        }
                    }else{
                        $data['error']='Please select users';
                        $this->load->view("manager/notes/addnotes",$data);
                    }
                }else{
                    $data['error']='Please select units';
                    $this->load->view("manager/notes/addnotes",$data);
                }
          }
          $jsfile['js_to_load'] = array('notes/notes_add_edit.js');
          $this->load->view('includes/home_footer',$jsfile);
      }
  }
?>