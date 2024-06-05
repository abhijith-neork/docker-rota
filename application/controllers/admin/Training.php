<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Training extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type') ==2){
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }  
        $this->load->model('Training_model');
        $this->load->model('Leave_model');
        $this->load->model('User_model');
        $this->load->model('Unit_model');
        $this->load->helper('form');
        $this->load->helper('name');
        $this->load->helper('settings');
    }

    public function report()
    {
        $columns = array( 
            0=> 'title',
            1=> 'description',
            2=> 'date_from',
            3=> 'date_to',
            4=> 'training_hour',
            5=> 'place', 
            6=> 'unit', 
            7=> 'point_of_person', 
            8=> 'creation_date', 
            9=> 'created_userid', 
        );  
        
        $limit = $this->input->post('length'); 
        $start = $this->input->post('start');
        $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
        $dir = $this->input->post('order')[0]['dir']?:'desc';
        if(empty($this->input->post('search')['value']))
        {
          $search = null;
        }
        else
        {
            $search = $this->input->post('search')['value'];
        }
        $data=array(); 
          $userUnits=array(); 
          $id=$this->session->userdata('user_id'); 
          $totalData=$this->Training_model->alltrainingsCount();
  
          $sub=$this->User_model->CheckuserUnit($id);
          $unit=$this->User_model->findunitofuser($id); 
          $parent=$this->User_model->Checkparent($unit[0]['unit_id']);  
          
          //if($this->session->userdata('user_type')==1) //all super admin can access
          if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
          {  
              $data['training']=$this->Training_model->alltrainings($search,$limit,$start,$order,$dir); 
              $totalData=$this->Training_model->alltrainingsCount(); 
              $totalFiltered=$this->Training_model->alltrainingsCount($search);
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
                $data['training']=$this->Training_model->alladmintrainingDetails($search,$sub,$limit,$start,$order,$dir); 
                $totalData=$this->Training_model->alladmintrainingDetails('',$sub,$limit,$start,$order,$dir,true); 
                $totalFiltered=$this->Training_model->alladmintrainingDetails($search,$sub,$limit,$start,$order,$dir,true);
              }
              else
              {    
                $user_unitids=$this->Leave_model->getUnitIdOfUserAsArray($id); //print_r($user_unitids[0]);exit();
                $data['training']=$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir);
                $totalData=$this->Training_model->allmanagertrainingDetails('',$user_unitids[0],$limit,$start,$order,$dir,true);
                $totalFiltered=$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir,true);
              }
  
          }
          else
          { 
              $user_unitids=$this->Leave_model->getUnitIdOfUserAsArray($id);
              $data['training']=$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir);
              $totalData=$this->Training_model->allmanagertrainingDetails('',$user_unitids[0],$limit,$start,$order,$dir,true);
              $totalFiltered=$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir,true);
  
                      
          }
          /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
          $this->load->model('Rota_model');
          $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
          /*End*/
          $data_arr = array();
          foreach($data['training'] as $desig){
            
              if($desig['training_hour']=='' || $desig['training_hour']=='00:00')
              {
                $training_hour = "00.00";
              }
              else
              {
                $training_hour = settimeformat(getPayrollformat1($desig['training_hour']));
              }
              $myArray= explode(',', $desig['unit']); 
              $units = getUnit($myArray); 
              for($i=0;$i<count($units);$i++){ $unit=implode(", ", $units[$i]); }
              $created_userid=getCreator($desig['created_userid']); 
              $user=getTrainingStaffs($desig['id']);
              $dates=Checktrainingdates($desig['id']);
              $training_title = preg_replace('/[^\p{L}\p{N}\s]/u', '', $desig['title']);
              if($dates=='true'){ $status=1;
                $action ='<a style="padding-right: 12px;" class="Edit" data-id="'. $desig['id'].'" href="'. base_url("admin/training/edittraining/").$desig['id'].'/'.$status.'" title="Edit"><i class="fas fa-edit"></i></a>'.

                '<a style="padding-right: 12px;" class="Delete" data-id="'. $desig['id'].'" title="Delete" href="javascript:void(0);" onclick="deleteFunction('.$desig['id'].',\''. $training_title.'\')" ><i class="fas fa-trash"></i></a>';
              }
              else{
                $status=2;

                $action ='<a style="padding-right: 12px;" class="Edit" data-id="'.$desig['id'].'" href="'.base_url("admin/training/edittraining/").$desig['id'].'/'.$status.'" title="Edit"><i class="fas fa-edit" style="padding-bottom: 15px;"></i></a>'.
                '<a style="padding-right: 12px;" id="edit"  href="#" onclick="edit('. $desig['id'].','. $desig['training_hour'].')" value="">Edit Hours</a>'.
                '<a style="padding-right: 12px;" class="Delete" data-id="'. $desig['id'].'" title="Delete" href="javascript:void(0);" onclick="deleteFunction('. $desig['id'].',\''. $training_title.'\')" ><i class="fas fa-trash"></i></a>';

              }
              $comments=getComment($desig['id']); 
              $comment_content = "";
              foreach($comments as $msg){  
                $comment_content .= "<b>User:</b> " . $msg['fname'].' '.$msg['lname'];
                $comment_content .=  '<br>'; 
                $comment_content .=  "<b>Comment:</b> " . $msg['feedback']; 
                $comment_content .=  '<br>';
                $comment_content .=  ("<b>Date:</b> " .date("d/m/Y  H:i:s",strtotime($msg['creation_date'])));  
                $comment_content .=  '<br><br>';                                              
           }  


              $action .=' <a class="Message" data-container="body" title="Comments" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" data-content="'.$comment_content.'" data-original-title="Comments">';
              $action .=' <i class="fas fa-envelope"></i></a>';
              $data_arr[] = array(
                  "title" => $desig['title'],
                  "description" => $desig['description'],
                  "date_from" => '<span style="display: none;">'. $desig['date_from'].'</span>'.date("d/m/Y",strtotime($desig['date_from'])).' '.$desig['time_from'],
                  "date_to" => '<span style="display: none;">'. $desig['date_to'].'</span>'.date("d/m/Y",strtotime($desig['date_to'])).' '.$desig['time_to'],
                  "training_hour" => $training_hour,
                  "place" => $desig['place'],
                  "unit" => $unit,
                  "point_of_person" => $desig['point_of_person'],
                  "creation_date" => '<span style="display: none;">'.$desig['creation_date'] .'</span>'. date("d/m/Y H:i:s",strtotime($desig['creation_date'])),
                  "created_userid" => $created_userid,
                  "action" => $action,
  
              );
          }
        
     
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data_arr
        );
        echo json_encode($json_data);
        
    }
    public function index()
    {
       $this->auth->restrict('Admin.Training.View');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $this->load->view("admin/training/managetraining");
       $result['js_to_load'] = array('training/training.js');
       $this->load->view('includes/home_footer',$result);
    }
     public function addtraining()
    {
        $this->auth->restrict('Admin.Training.Add');
        $result = array();
        $data = array();
        $result['error'] ='';
        $login_user_id = $this->session->userdata('user_id');
        $this->load->helper('form');
        $this->load->library('form_validation');
         
          $header['title'] = 'Add Training';
          $this->load->view('includes/home_header',$result);

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

        $data['locationunit']=$this->Training_model->allunitnoagency();

            // if($this->session->userdata('user_type') == 1)
            // {
            //     if($this->session->userdata('user_id')==1)
            //     {
            //           $data['unit']=$this->User_model->fetchCategoryTree('');
            //     }
            //     else
            //     {
            //           $data['unit']=$this->User_model->findunitforadmin($this->session->userdata('unit_id'));
            //     }
            //     $data['locationunit']=$this->Training_model->allunitnoagency();
                
            // }
            // else
            // {
            //      $data['unit']=$this->Training_model->allunits($login_user_id);
            //      $data['locationunit']=$this->Training_model->allunitnoagency();
                 
            // }
           // print_r($data['unit']);exit();
          $unit_array = $this->getParentAndSubunits();
          $data['unit_array'] = json_encode($unit_array);
          $data['training']=$this->Training_model->getTrainingTitle(); 
          $this->load->view("admin/training/addtraining",$data);
          $jsfile['js_to_load'] = array('training/training_add_edit.js');
          $this->load->view('includes/home_footer',$jsfile);
        // $this->form_validation->set_rules('title', 'Title', 'required');
      }

      public function inserttraininghour()
      {
        $id=$this->input->post('training_id');
        $hour=$this->input->post('hours');
        $status=$this->Training_model->updatetraininghour($id,$hour);
        header('Content-Type: application/json');
        echo json_encode(array('status' => $status,'hours'=>settimeformat(getPayrollformat1($hour))));
        exit();
      }

      public function Sendtraining()
      {
        //print $this->input->post('training_unit');exit;
        try{
        $result = array();
        $data = array(); 
        $this->load->helper('form');
        $this->load->library('form_validation');
        $login_user_id = $this->session->userdata('user_id');
  
                  $sub=$this->User_model->CheckuserUnit($login_user_id);
                  $unit=$this->User_model->findunitofuser($login_user_id); 
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

            //  if($this->session->userdata('user_type') == 1)
            // {
            //      if($this->session->userdata('user_id')==1)
            //     {
            //           $data['unit']=$this->User_model->fetchCategoryTree('');
            //     }
            //     else
            //     {
            //           $data['unit']=$this->User_model->findunitforadmin($this->session->userdata('unit_id'));
            //     } 
            // }
            // else
            // {
            //      $data['unit']=$this->Training_model->allunits($login_user_id);  
            // }
          $this->form_validation->set_rules('title', 'title', 'required');
          $this->form_validation->set_rules('description', 'description', 'required');  
          $this->form_validation->set_rules('address', 'address', 'required');
          $this->form_validation->set_rules('point_of_person', 'point of contact', 'required'); 
          $this->form_validation->set_rules('fromdate', 'from date', 'required');
          $this->form_validation->set_rules('todate', 'to date', 'required');
          $this->form_validation->set_rules('training_unit', 'training unit', 'required');
          $this->form_validation->set_rules('contact_number', 'contact number', 'required');
          $this->form_validation->set_rules('contact_email', 'contact email', 'required'); 
          
          $header['title'] = 'Add Training';
          $this->load->view('includes/home_header',$result);
    
          if ($this->form_validation->run() == FALSE)
          {   
                $data['error']='';
                $login_user_id = $this->session->userdata('user_id'); 
                $sub=$this->User_model->CheckuserUnit($login_user_id);
                  $unit=$this->User_model->findunitofuser($login_user_id); 
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
                // if($this->session->userdata('user_type') == 1)
                // {
                //      if($this->session->userdata('user_id')==1)
                //       {
                //             $data['unit']=$this->User_model->fetchCategoryTree('');
                //       }
                //       else
                //       {
                //             $data['unit']=$this->User_model->findunitforadmin($this->session->userdata('unit_id'));
                //       }
                // }
                // else
                // {
                //      $data['unit']=$this->Training_model->allunits($login_user_id);  
                // }
                $data['training']=$this->Training_model->getTrainingTitle();
                $this->load->view("admin/training/addtraining",$data); 
          }
          else
          {  
                $title= $this->input->post('title');
                $description=$this->input->post('description'); //print_r($description);exit();
                $data['title']=$this->Training_model->findtitleOfTraining($title);
                if($data['title']==0) 
                {  
                    $this->Training_model->inserttrainingTitle($title,$description);
                }
                //print_r($this->input->post());exit();
                
                // $date_from= $this->input->post('fromdate');  
                // $date_to=$this->input->post('todate'); 

                $date_daily=$this->input->post('fromdate');  
                $old_date = explode('/', $date_daily); 
                $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
                $date_from=$new_data;  

                $date_daily=$this->input->post('todate');  
                $old_date = explode('/', $date_daily); 
                $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
                $date_to=$new_data1; 

                $newdate_from = date("d/m/Y", strtotime($date_from));  
                $newdate_to = date("d/m/Y", strtotime($date_to));  
                //print_r($newdate_from); print_r($newdate_to);exit();
                if($date_from > $date_to)
                { 
                    $data['error']='Please select a valid dates';
                    $data['training']=$this->Training_model->getTrainingTitle();
                    $this->load->view("admin/training/addtraining",$data);
                }
                else if($date_from < date('Y-m-d'))
                {  
                    $data['training']=$this->Training_model->getTrainingTitle();
                    $data['error']='The dates must be future dates';
                    $this->load->view("admin/training/addtraining",$data);
                    
                }
                else
                {  
                    $time_from=$this->input->post('wstart_time2').':'.$this->input->post('wstart_time12'); 
                    $time_to=$this->input->post('wend_time2').':'.$this->input->post('wend_time12');  
                    $place=$this->input->post('training_unit'); 
                    $address=$this->input->post('address');
                    $point_of_person=$this->input->post('point_of_person');
                    $contact_num=$this->input->post('contact_number');
                    $contact_email=$this->input->post('contact_email');
                    $unit= $this->input->post('unit');  
                    $hour = $this->input->post('training_hour'); 
                    //print_r($hour);exit();
                    
                      if(is_array($unit))
                      {
                          if(!empty($_POST['usercheck'])) 
                          {
                            $selected_units = $this->input->post('unit');
                            // array_push($selected_units,0);
                            $this->load->model('Reports_model');
                            $search_data = array(
                               'unit_id' => $selected_units,
                               'from_date' => $date_from,
                               'to_date' => $date_to,
                            );
                            $result = $this->Reports_model->checkExistingLockedTimesheet($search_data);
                            if($result['status'] == true){
                              $unit_ids = implode(",", $unit); 
                              $status  = 0;
                              $creation_date = date('Y-m-d H:i:s');
                              $updation_date = date('Y-m-d H:i:s');
                              $updated_userid = $login_user_id;
                              $user_ids = array();
                              $user_emails = array();
                              //$supervisor_name = $this->session->userdata('full_name');
                              $dataform=array(
                                              'title' => $title,
                                              'description'=>$description,
                                              'date_from'=>$date_from,
                                              'date_to'=>$date_to,
                                              'time_from'=>$time_from,
                                              'time_to'=>$time_to,
                                              'training_hour'=>$hour,
                                              'place'=>$place,
                                              'address'=>$address,
                                              'unit'=>$unit_ids,
                                              'point_of_person'=>$point_of_person,
                                              'contact_num'=>$contact_num,
                                              'contact_email'=>$contact_email,
                                              'status'=>$status,
                                              'creation_date'=>date('Y-m-d H:i:s'),
                                              'created_userid'=>$this->session->userdata('user_id')
                                            ); 
                              //print_r($dataform);exit();
                              $new_users=$this->input->post('selected_users');  // selected users from table
                              $users = explode(",", $new_users[0]); //print_r($users);
                              $training_id=$this->Training_model->inserttraining($dataform);
                              $training_user_ids = array();
                              if($training_id)
                              { 
                                  $title='Add Training';
                                  InsertEditedData($dataform,$title);


                                  foreach($users as $user) 
                                  {
                                      $splittedstring = explode("_",$user);
                                      array_push($training_user_ids,$splittedstring[0]);     
                                      $training_staff=array(
                                        'user_id'=>$splittedstring[0],
                                        'training_id'=>$training_id,
                                        'status'=>$status,
                                        'creation_date'=>date('Y-m-d H:i:s'),
                                        'updation_date'=>date('Y-m-d H:i:s'),
                                        'updated_userid'=>$updated_userid,
                                        'published' => 0
                                      );
                                      $training_staff_details = $this->Training_model->insertTrainingStaffDetails($training_staff);
                                      $userInfo=$this->Training_model->finduseremail($splittedstring[0]);
                                      $mobilenumber = $userInfo[0]['mobile_number'];
                                      $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
                                      if(count($session_user)>0){
                                      $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
                                      }else{
                                      $unit_supervisor_name = '';
                                      }
                                      $site_title = 'St Matthews Healthcare:';
                                      $admin_email=getCompanydetails('from_email');
                                      $data['error']='';
                                      $this->session->set_flashdata('message', 'Added successfully');  
                                  }
                                  $this->sendMailAboutTraining($training_user_ids,$training_id);
                                  redirect('admin/training');
                              }
                              else
                              {
                                 $data['training']=$this->Training_model->getTrainingTitle();
                                 $data['locationunit']=$this->Training_model->allunitnoagency();
                                  $data['error']='Training sending failed due to db errors';
                                  $this->load->view("admin/training/addtraining",$data);
                              }
                           }else{
                                $data['training']=$this->Training_model->getTrainingTitle();
                                $data['locationunit']=$this->Training_model->allunitnoagency();
                               $data['error']=$result['message'];
                               $this->load->view("admin/training/addtraining",$data);
                            }
                          }
                          else
                          {
                              $data['training']=$this->Training_model->getTrainingTitle();
                              $data['locationunit']=$this->Training_model->allunitnoagency();
                              $data['error']='Please select users';
                              $this->load->view("admin/training/addtraining",$data);
                          }
                      }

                      else
                      {
                          $data['training']=$this->Training_model->getTrainingTitle();
                          $data['locationunit']=$this->Training_model->allunitnoagency();
                          $data['error']='Please select units';
                          $this->load->view("admin/training/addtraining",$data);
                      }
                    
                }
          }
          $jsfile['js_to_load'] = array('training/training_add_edit.js');
          $this->load->view('includes/home_footer',$jsfile);
        }
         catch (Exception $e) {
            print_r($e->getMessage());exit();
                              }
      }




    public function checkTraining($datefrom,$dateto,$units){
        
        $status = 1;  
        $data = array();
        $data['date_from']= $datefrom;
        $data['date_to']= $dateto;
        $data['units']= $units;  
        $result = $this->Training_model->gettraingDetails($data); 
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
       return $status;
       
    }

    public function findUser(){
            $json = array();
            $unit_id=$this->input->post('unit_id');
            $selected_ids=array();
            $selected_ids=$this->input->post('selected_ids');
            $login_user_id = $this->session->userdata('user_id');
            //if($this->session->userdata('user_type') == 1)
            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $result=$this->Training_model->findUser($unit_id,$selected_ids,'');
            }
            else
            {
                $result=$this->Training_model->findUser($unit_id,$selected_ids,$login_user_id);
            }
            $i=0;
          foreach ($result as $row)
          { $i++;
             

            $user_name = $row['fname'].' '.$row['lname']; 
            $json[] =array($row['fname']." ".$row['lname'],$row['designation_name'],'<input type="checkbox"  name="usercheck[]" class="checkItem" id="checks_'.$i.'" data-unitid="'.$row['unit_id'].'"  data-unit="'.$row['unit_shortname'].'" data-designation="'.$row['designation_name'].'" data-username="'.$user_name.'" data-email="'.$row['email'].'" value="'.$row['user_id'].'___'.$row['email'].'___'.$row['unit_id'].'___'.$row['unit_shortname'].'___'.$user_name.'"  >');
          }
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
      }

       public function findparentuser(){
            $json = array();
            $unit_id=$this->input->post('unit_id');
            $selected_ids=$this->input->post('selected_ids');
            $login_user_id = $this->session->userdata('user_id');
            //if($this->session->userdata('user_type') == 1)
            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $result=$this->Training_model->findparentuser($unit_id,$selected_ids,'');
            }
            else
            {
                $result=$this->Training_model->findparentuser($unit_id,$selected_ids,$login_user_id);
            }
          foreach ($result as $row)
          {
            $json[] =array($row['fname']." ".$row['lname'],$row['designation_name'],'<input type="checkbox" name="usercheck[]" class="checkItem" data-email="'.$row['email'].'" value="'.$row['user_id'].'-'.$row['email'].'"  >');
          }
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
      }
      public function sendMailAboutTraining($userids,$training_id){
        $training_details = $this->Training_model->getTrainingDetailsForMail($training_id);
        $site_title = 'St Matthews Healthcare - SMH Rota';
        $admin_email=getCompanydetails('from_email');
        foreach($userids as $user) {
          $userInfo=$this->Training_model->finduseremail($user);
          $emailSettings = array();
          $emailSettings = array(
            'from' => $admin_email,
            'site_title' => $site_title,
            'site_url' => $this->config->item('base_url'),
            'to' => $userInfo[0]['email'],
            'type' => 'Rota - Training details',
            'user_name'=> $userInfo[0]['fname'].' '. $userInfo[0]['lname'],
            'fromdate'=> corectDateFormat($training_details[0]['date_from']),
            'todate'=>corectDateFormat($training_details[0]['date_to']),
            'time_from'=>$training_details[0]['time_from'],
            'time_to'=>$training_details[0]['time_to'],
            'place'=>$training_details[0]['place'],
            'address'=>$training_details[0]['address'],
            'title'=>$training_details[0]['title'],
            'comments'=>$training_details[0]['description'],
            'point_of_person'=>$training_details[0]['point_of_person'],
            'contact_num'=>$training_details[0]['contact_num'],
            'contact_email'=>$training_details[0]['contact_email'],
            'subject' => $site_title. '- Training: '.$training_details[0]['title'],
          );
          $this->load->library('parser');
          $htmlMessage = $this->parser->parse('emails/training', $emailSettings, true);
          $result = $this->Training_model->updatePublishedStatus($user,$training_id);
          $this->load->helper('mail');
          sendMail($emailSettings, $htmlMessage);
        }
      }
      public function sendMailAboutStaffAddedToTraining($old_training_staffs,$users_checked,$training_id){
        $new_user_ids = array();
        $old_user_ids = array();
        foreach($users_checked as $user) {
          $user_string = explode("_",$user);
          array_push($new_user_ids, $user_string[0]);
        }
        foreach($old_training_staffs as $user) {
          array_push($old_user_ids, $user['user_id']);
        }
        $newly_added_user = array_diff(array_unique($new_user_ids),$old_user_ids);
        $training_details = $this->Training_model->getTrainingDetailsForMail($training_id);
        $site_title = 'St Matthews Healthcare - SMH Rota';
        $admin_email=getCompanydetails('from_email');
        foreach($newly_added_user as $user) {
          $userInfo=$this->Training_model->finduseremail($user);
          $emailSettings = array();
          $emailSettings = array(
            'from' => $admin_email,
            'site_title' => $site_title,
            'site_url' => $this->config->item('base_url'),
            'to' => $userInfo[0]['email'],
            'type' => 'Rota - Training details - New User Added',
            'user_name'=> $userInfo[0]['fname'].' '. $userInfo[0]['lname'],
            'fromdate'=> corectDateFormat($training_details[0]['date_from']),
            'todate'=>corectDateFormat($training_details[0]['date_to']),
            'time_from'=>$training_details[0]['time_from'],
            'time_to'=>$training_details[0]['time_to'],
            'place'=>$training_details[0]['place'],
            'address'=>$training_details[0]['address'],
            'title'=>$training_details[0]['title'],
            'comments'=>$training_details[0]['description'],
            'point_of_person'=>$training_details[0]['point_of_person'],
            'contact_num'=>$training_details[0]['contact_num'],
            'contact_email'=>$training_details[0]['contact_email'],
            'subject' => $site_title. '- Training: '.$training_details[0]['title'],
          );
          $this->load->library('parser');
          $htmlMessage = $this->parser->parse('emails/training', $emailSettings, true);
          $result = $this->Training_model->updatePublishedStatus($user,$training_id);
          $this->load->helper('mail');
          sendMail($emailSettings, $htmlMessage);
        }
      }
  public function sendMailAboutStaffRemovedFromTraining($old_training_staffs,$users_checked,$training_id){
    $new_user_array = array();
    $removed_user_array = array();
    foreach($users_checked as $user) {
      $user_string = explode("_",$user);
      array_push($new_user_array, $user_string[0]);
    }
    foreach($old_training_staffs as $staff) {
      if (!in_array($staff['user_id'], $new_user_array)){
        if($staff['published'] == 1){
          array_push($removed_user_array, $staff['user_id']);
        }
      }
    }
    $training_details = $this->Training_model->getTrainingDetailsForMail($training_id);
    $newdate_from = date("d/m/Y", strtotime($training_details[0]['date_from']));  
    $newdate_to = date("d/m/Y", strtotime($training_details[0]['date_to']));
    $current_time = date("g:i a");
    $current_date = date("d/m/Y");
    if(count($removed_user_array) > 0){
      foreach($removed_user_array as $user) {
        $userInfo=$this->Training_model->finduseremail($user);
        $session_user = $this->User_model->finduserDetailsWithId($this->session->userdata('user_id'));
        if(count($session_user)>0){
          $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
        }else{
          $unit_supervisor_name = '';
        }
        $site_title = 'St Matthews Healthcare:';
        $admin_email=getCompanydetails('from_email');
        $emailSettings = array(
          'from' => $admin_email,
          'site_url' => $this->config->item('base_url'),
          'site_title' => $site_title,
          'to' => $userInfo[0]['email'],
          'type' => 'Removed from training',
          'data' => 'Your training '.$training_details[0]['title'].' has been cancelled.',
          'user_name'=> $userInfo[0]['fname'].' '. $userInfo[0]['lname'],
          'supervisor_name'=>$unit_supervisor_name,
          'subject' => 'e-Rota : Training cancelled : ('.$newdate_from.' to '.$newdate_to.')',
        );
        $this->load->library('parser');
        $htmlMessage = $this->parser->parse('emails/training_removed', $emailSettings, true);
        $this->load->helper('mail'); 
        sendMail($emailSettings, $htmlMessage);
      }
    }
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
  public function edittraining()
  {
           $this->auth->restrict('Admin.Training.Edit');
            $result = array();
            $data = array();
            $user_details = array();
            $result['error'] ='';
            $training_id=$this->uri->segment(4);
            $status=$this->uri->segment(5); //print_r($status);
            $unit_array = $this->getParentAndSubunits();
            $data['unit_array'] = json_encode($unit_array);
            $login_user_id = $this->session->userdata('user_id');
            $this->load->helper('form');
            $this->load->library('form_validation');
             
            $header['title'] = 'Edit Training';
            $this->load->view('includes/home_header',$result);
                  $sub=$this->User_model->CheckuserUnit($login_user_id);
                  $unit=$this->User_model->findunitofuser($login_user_id); 
                  $parent = 0;
                  if(count($unit) > 0){
                    $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
                  }
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
 
            $data['locationunit']=$this->Training_model->allunitnoagency();
            $data['training_title']=$this->Training_model->getTrainingTitle();
            $data['training'] = $this->Training_model->getSingleTraining($training_id); 
            $data['users'] = $this->Training_model->findUsers($training_id); 
            $data['users1'] = $this->Training_model->findUsersforedit($training_id); 
            //print_r($data['users1']);exit(); 
            //$data['users_new']=json_encode($data['users1']); 
            $training_staffs=array();
            if(count($data['users1'])>0)
            {
              foreach ($data['users1'] as $user) {
                 
                 $status_new=$this->Training_model->checkUnitIdinTraining($training_id,$user['unit_id']);
                 //print_r($status_new);
                 if($status_new=='true')
                 {
                   array_push($training_staffs, $user);
                 }
              }
            }

            $data['users_new']=json_encode($training_staffs);
//            print_r('<pre>');
//             print_r($data['users_new']);print '<br>';

// exit();
            $data['trainings']=$this->Training_model->getTrainingTime($training_id); 
            $unit_ids = $data['training'][0]['unit']; 
            $unit_id_array = explode(',', $unit_ids); 
            $data['unit_id_array'] = $unit_id_array; 
            $data['user_all']=$this->Training_model->findusersofTraining($unit_id_array); 
            $data['unit_id_string'] = $data['training'][0]['unit'];
            $data['id'] =$training_id; 
            $data['status']=$status;
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('description', 'description', 'required');  
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('point_of_person', 'point of contact', 'required'); 
            if($status==1)
            {
              $this->form_validation->set_rules('fromdate', 'from date', 'required');
              $this->form_validation->set_rules('todate', 'to date', 'required');
            }
            $this->form_validation->set_rules('training_unit', 'training unit', 'required');
            $this->form_validation->set_rules('contact_number', 'contact number', 'required');
            $this->form_validation->set_rules('contact_email', 'contact email', 'required'); 
            if ($this->form_validation->run() == FALSE)
            {
                $data['error']=''; $a='';$b='';
                      foreach ($data['trainings'] as  $value) 
                {
                  $time1=$value['time_from'];
                  $a=explode(':', $time1);  
                  $time2=$value['time_to'];
                  $b=explode(':', $time2);
                }
                $data['a']=$a; $data['b']=$b;
                $data['status']=$status;
                
            $this->load->view("admin/training/edittraining",$data);
            }
            else
            { 
                $status=$this->input->post('status'); //print 'status-'; print_r($status); print '<br>';
                $status_new=$this->input->post('status1'); //print 'status new-'; print_r($status_new); print '<br>'; exit();
                $date_daily=$this->input->post('fromdate');  
                $old_date = explode('/', $date_daily); 
                $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
                $date_from=$new_data;  

                if($status==2) // new changes oct 7-2020
                {
                  $new_date_from=date('Y-m-d', strtotime(' +1 day'));
                }
                else
                {
                  $new_date_from=$date_from;
                }

                //print_r($status);exit();

                $date_daily=$this->input->post('todate');  
                $old_date = explode('/', $date_daily); 
                $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
                $date_to=$new_data1; 

                $title= $this->input->post('title');
                $description=$this->input->post('description'); //print_r($description);exit();
                $data['title']=$this->Training_model->findtitleOfTraining($title);
                //print_r($data['title']);exit();
                if($data['title']==0) 
                {  
                    $this->Training_model->inserttrainingTitle($title,$description);
                }

                // $title= $this->input->post('title'); 
                // $description=$this->input->post('description');
                //$date_from= $this->input->post('fromdate'); 
                $newdate_from = date("d/m/Y", strtotime($date_from));  
                //$date_to=$this->input->post('todate');
                $newdate_to = date("d/m/Y", strtotime($date_to));  
                if($date_from > $date_to)
                {  
                  $data['error']='Please select a valid dates';
                  $data['trainings']=$this->Training_model->getTrainingTime($training_id); 
                  foreach ($data['trainings'] as  $value) 
                  {
                    $time1=$value['time_from'];
                    $a=explode(':', $time1);  
                    $time2=$value['time_to'];
                    $b=explode(':', $time2);
                  }
                  $data['a']=$a; $data['b']=$b;
                  $data['status']=$status;
                  $data['locationunit']=$this->Training_model->allunitnoagency();
                  $this->load->view("admin/training/edittraining",$data);
                }
                else if($new_date_from >= date('Y-m-d'))
                {
                    $id_train=$training_id;
                    $training_dates=$this->Training_model->findtrainingdates($id_train); // find training dates(from date and to date).
                    $fromdate=$training_dates[0]['date_from'];
                    $todate=$training_dates[0]['date_to'];
                    $time_from=$this->input->post('wstart_time2').':'.$this->input->post('wstart_time12'); 
                    $time_to=$this->input->post('wend_time2').':'.$this->input->post('wend_time12'); 
                    // if($time_from>=12)
                    //   { $newtime_from=$this->input->post('wstart_time2').':'.$this->input->post('wstart_time12').' '."pm";}else
                    //   { $newtime_from=$this->input->post('wstart_time2').':'.$this->input->post('wstart_time12').' '."am";}
                    // if($time_to>=12)
                    //   { $newtime_to=$this->input->post('wend_time2').':'.$this->input->post('wend_time12').' '."pm";}else
                    //   { $newtime_to=$this->input->post('wend_time2').':'.$this->input->post('wend_time12').' '."am";}
                    //print_r($newtime_to); print_r($newtime_from);exit(); 
                    $place=$this->input->post('training_unit');
                    $address=$this->input->post('address');
                    $point_of_person=$this->input->post('point_of_person');
                    $contact_num=$this->input->post('contact_number');
                    $contact_email=$this->input->post('contact_email');
                    $unit=$this->input->post('unit'); 
                    $hour=$this->input->post('training_hour'); 
                        if(is_array($unit))
                        {
                            // $users_checked = isset($_POST['usercheck']) ? $_POST['usercheck'] : NULL; 
                            $users_checked_all=$this->input->post('selected_users');  // selected users from table
                            $users_checked = explode(",",$users_checked_all[0]);
                            
                            if($status==2) // new changes oct 7-2020
                            {
                              $new_users_checked=1;
                            }
                            else
                            {
                              $new_users_checked=$users_checked;
                            }

                            //changed on aug 19  2021 email from 18 augst 2021

                              //if(count($new_users_checked)>0) 
                              //{ //print_r($users_checked);exit();
                                  $unit_ids = implode(",", $unit);
                                  //$status  = $status_new;
                                  $status  = 0;
                                  $creation_date = date('Y-m-d H:i:s');
                                  $updation_date = date('Y-m-d H:i:s');
                                  $updated_userid = $login_user_id;
                                  $user_ids = array();
                                  $user_emails = array();  
                                  $dataform=array(
                                            'title' => $title,
                                            'description'=>$description,
                                            'date_from'=>$date_from,
                                            'date_to'=>$date_to,
                                            'time_from'=>$time_from,
                                            'time_to'=>$time_to,
                                            'training_hour'=>$hour,
                                            'place'=>$place,
                                            'address'=>$address,
                                            'unit'=>$unit_ids,
                                            'point_of_person'=>$point_of_person,
                                            'contact_num'=>$contact_num,
                                            'contact_email'=>$contact_email,
                                            'status'=>$status, 
                                            'updation_date'=>date('Y-m-d H:i:s'),
                                            'created_userid'=>$updated_userid,
                                            );  

                                  $title='Edit Training';
                                  InsertEditedData($dataform,$title);
                     
                                    $Training_users=$this->Training_model->findtraininguser($id_train); //find users using training id
                                    $user_array=array();
                                    foreach ($Training_users as $users) {
                                      array_push($user_array, $users['user_id']);   
                                    }
                                     // print_r($fromdate);print_r($todate);
                                     // exit();
                                    //set shift_id as 0
                                    $this->Training_model->removeTrainingFromRota($user_array,$fromdate,$todate);

                                    //print_r($date_from); print_r($date_to);exit(); 

                                  //print_r($this->input->post());exit();
                                  $old_training_staffs = $this->Training_model->getTrainingStaffsBeforeEdit($training_id);
                                   
                                  $update_status = $this->Training_model->updateTrainingDetails($training_id, $dataform); 
                                  if($update_status == 1)
                                  {
                                      $this->Training_model->deleteStaffTraining($training_id);
                                      $this->sendMailAboutStaffAddedToTraining($old_training_staffs,$users_checked,$training_id);
                                      $this->sendMailAboutStaffRemovedFromTraining($old_training_staffs,$users_checked,$training_id);
                                      $new_users=$this->input->post('selected_users');  // selected users from table
                                       //print_r($new_users);exit();
                                      $users = explode(",", $new_users[0]);
                                      foreach($users as $user) {
                                          $splittedstring = explode("_",$user); 
                                          $dataform=array(
                                            'user_id'=>$splittedstring[0],
                                            'training_id'=>$training_id,
                                            'status'=>$status,
                                            'creation_date'=>date('Y-m-d H:i:s'),
                                            'updation_date'=>date('Y-m-d H:i:s'),
                                            'updated_userid'=>$updated_userid
                                          );
                                          //print_r($dataform); print_r('<br>');
                                          $training_staff_details = $this->Training_model->insertTrainingStaffDetails($dataform);
                                          $userInfo=$this->Training_model->finduseremail($splittedstring[0]);  
                                          //update shift_id in rota schedule table

                                          $this->Training_model->updaterotaByuserid($splittedstring[0],$date_from,$date_to);
                                          $mobilenumber = $userInfo[0]['mobile_number'];
                                          /*if($mobilenumber){
                                            //sms integration
                                            $message = "Training: ".$title." Date: ".$date_from."-".$date_to;
                                            $this->load->model('AwsSnsModel');
                                            $sender_id="02";
                                            $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $message);
                                          }*/
                                          $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
                                                if(count($session_user)>0){
                                                $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
                                                }else{
                                                $unit_supervisor_name = '';
                                                }
                                          $site_title = 'St Matthews Healthcare:';
                                          $admin_email=getCompanydetails('from_email');
                                          $emailSettings = array(
                                                                  'from' => $admin_email,
                                                                  'site_url' => $this->config->item('base_url'),
                                                                  'site_title' => $site_title,
                                                                  'to' => $userInfo[0]['email'],
                                                                  'type' => 'Updated Training Details',
                                                                  'user_name'=> $userInfo[0]['fname'].' '. $userInfo[0]['lname'],
                                                                  'fromdate'=> $newdate_from,
                                                                  'todate'=>$newdate_to,
                                                                  'time_from'=>$time_from,
                                                                  'time_to'=>$time_to,
                                                                  'place'=>$place,
                                                                  'address'=>$address,
                                                                  'title'=>$title,
                                                                  'comments'=>$description,
                                                                  'point_of_person'=>$point_of_person,
                                                                  'contact_num'=>$contact_num,
                                                                  'contact_email'=>$contact_email,
                                                                  'supervisor_name'=>$unit_supervisor_name,
                                                                  'subject' => $site_title.' '. 'Training - Updated ['.$newdate_from.'-'.$newdate_to.']'
                                                              );
                                         // print_r($emailSettings);exit();
                                      $this->load->library('parser');
                                      $htmlMessage = $this->parser->parse('emails/training', $emailSettings, true);
                                     // die($htmlMessage);exit();
                                      $this->load->helper('mail'); 
                                      // sendMail($emailSettings, $htmlMessage);  
                                      } //exit();
                                      $this->session->set_flashdata('message', 'Training updated and sent successfully');
                                      
                                      redirect('admin/training');
                                  }
                                  else
                                  {
                                      $data['training_title']=$this->Training_model->getTrainingTitle();
                                      $data['error']='Training updation failed due to db errors';
                                      $this->load->view("admin/training/edittraining",$data);
                                  }
                              // }
                              // else
                              // {
                              //     $data['error']='Please select users';
                              //     $data['trainings']=$this->Training_model->getTrainingTime($training_id); 
                              //     foreach ($data['trainings'] as  $value) 
                              //     {
                              //       $time1=$value['time_from'];
                              //       $a=explode(':', $time1);  
                              //       $time2=$value['time_to'];
                              //       $b=explode(':', $time2);
                              //     }
                              //     $data['a']=$a; $data['b']=$b;
                              //     $data['status']=$status;
                              //     $data['locationunit']=$this->Training_model->allunitnoagency();
                              //     $this->load->view("admin/training/edittraining",$data);
                              // }
                        }
                        else
                        {
                            $data['error']='Please select units';
                            $data['trainings']=$this->Training_model->getTrainingTime($training_id); 
                                foreach ($data['trainings'] as  $value) 
                                {
                                  $time1=$value['time_from'];
                                  $a=explode(':', $time1);  
                                  $time2=$value['time_to'];
                                  $b=explode(':', $time2);
                                }
                                $data['a']=$a; $data['b']=$b;
                                $data['status']=$status;
                                $data['locationunit']=$this->Training_model->allunitnoagency();
                            $this->load->view("admin/training/edittraining",$data);
                        }
              }
              else
              {//print_r($status);
                $data['trainings']=$this->Training_model->getTrainingTime($training_id); 
                foreach ($data['trainings'] as  $value) 
                {
                  $time1=$value['time_from'];
                  $a=explode(':', $time1);  
                  $time2=$value['time_to'];
                  $b=explode(':', $time2);
                }
                $data['a']=$a; $data['b']=$b;
                $data['error']='The dates must be future dates';
                $data['status']=$status;
                $data['locationunit']=$this->Training_model->allunitnoagency();
                $data['training_title']=$this->Training_model->getTrainingTitle();
                $this->load->view("admin/training/edittraining",$data);
              }
            }
            $jsfile['js_to_load'] = array('training/training_edit.js');
            $this->load->view('includes/home_footer',$jsfile);
        }
        public function editdata_report(){
            $training_id=$this->input->post('training_id');
            $data['training'] = $this->Training_model->getSingleTraining($training_id); 
            $data['trainings']=$this->Training_model->getTrainingTime($training_id); 
            $unit_ids = $data['training'][0]['unit']; 
            $unit_id_array = explode(',', $unit_ids); 
            $data['unit_id_array'] = $unit_id_array; 
              $columns = array( 
                0=> 'fname',
                1=> 'designation_name',
                2=> 'action',
            );  
            
            $limit = $this->input->post('length'); 
            $start = $this->input->post('start');
            $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
            $dir = $this->input->post('order')[0]['dir']?:'desc';
            $designationFilter = $this->input->post('designation_filter');
            if(empty($this->input->post('search')['value']))
            {
              $search = null;
            }
            else
            {
                $search = $this->input->post('search')['value'];
            }
            $data=array(); 

            $user_all=$this->Training_model->findusersofTraining($unit_id_array,$search,$limit,$start,$order,$dir,$designationFilter); 
           

            $totalData=$this->Training_model->findusersofTrainingCount($unit_id_array);
            $totalFiltered=$this->Training_model->findusersofTrainingCount($unit_id_array,$search,$designationFilter);
            $data_arr = array();

            foreach($user_all as $desig){
              
                $action = '<input type="checkbox" id="user_' . $desig['user_id'] . '" data-unitid="' . $desig['unit_id'] . '" data-unit="' . $desig['unit_shortname'] . '" data-username="' . $desig['fname'] . ' ' . $desig['lname'] . '" name="usercheck[]" class="checkItem" value="' . $desig['user_id'] . '___' . $desig['email'] . '___' . $desig['unit_id'] . '___' . $desig['unit_shortname'] . '___' . $desig['fname'] . ' ' . $desig['lname'] . '">';
                $data_arr[] = array(
                    "fname" => $desig['fname']." ".$desig['lname'],
                    "designation_name" => $desig['designation_name'],
                    "action" => $action,
    
                );
            }
            
            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data_arr
            );

            echo json_encode($json_data);
      }
        public function corectDateFormat($date){
          $my_str = $date;
          $date_array = array();
          $date_array = explode("-", $my_str);
          $date_with_slash = $date_array[2]."/".$date_array[1]."/".$date_array[0];
          return $date_with_slash;
        }
    public function deletetraining()
    {
      $this->auth->restrict('Admin.Training.Delete');
      try{
            $id=$this->uri->segment(4);  
            $user_details = $this->Training_model->findUsersOnTraining($id);
            $training_dates=$this->Training_model->findtrainingdates($id); // find training dates(from date and to date).
            $fromdate=$training_dates[0]['date_from'];
            $todate=$training_dates[0]['date_to'];
            $Training_users=$this->Training_model->findtraininguser($id); //find users using training id
            $user_array=array();
            foreach ($Training_users as $users) {
              array_push($user_array, $users['user_id']);   
            }
            //print_r($user_array);exit();

            $this->Training_model->removeTrainingFromRota($user_array,$fromdate,$todate); //remove training from rota

            $data = $this->Training_model->deletetraining($id);
            //print_r($user_details);exit();
            if($data==1)
            {

              $title='Delete Training';
              $abc=array('id'=> 'training_id : '.$id);
              InsertEditedData($abc,$title);


              if(count($user_details) > 0){
                $count = count($user_details);
                for($i=0;$i<$count;$i++){
                  $site_title = 'St Matthews Healthcare - SMH Rota';
                  $admin_email=getCompanydetails('from_email');
                   $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
                                      if(count($session_user)>0){
                                      $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
                                      }else{
                                      $unit_supervisor_name = '';
                                      }
                  $user_name = $user_details[$i]['fname'].' '.$user_details[$i]['lname'];
                  $data = "The training ".$user_details[$i]['title']." on ".corectDateFormat($user_details[$i]['date_from'])." - ".corectDateFormat($user_details[$i]['date_to'])." has been cancelled.";
                  $subject = $user_details[$i]['title']." ( ".corectDateFormat($user_details[$i]['date_from'])."-".corectDateFormat($user_details[$i]['date_to'])." ) : Cancelled";
                  $emailSettings = array();
                  $emailSettings = array(
                    'from' => $admin_email,
                    'site_title' => $site_title,
                    'site_url' => $this->config->item('base_url'),
                    'to' => $user_details[$i]['email'],
                    'type' => 'Training Details',
                    'user_name'=> $user_name,
                    'data' => $data,
                    'supervisor_name'=>$unit_supervisor_name,
                    'subject' => $subject
                  );
                  //print_r($emailSettings);exit();
                  $this->load->library('parser');
                  $htmlMessage = $this->parser->parse('emails/delete_training', $emailSettings, true);
                  //die($htmlMessage);exit();
                  $this->load->helper('mail'); 
                  sendMail($emailSettings, $htmlMessage);
                }
              }
              $this->session->set_flashdata('error',"0");
              $this->session->set_flashdata('message', 'Deleted successfully.');
              redirect('admin/training');
            }
            else{
                if($data==1451){
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Cannot delete this training, this training is assigned to users.');
                }else {
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Error deleting record, please try again.');
                    
                }
                    
                redirect('admin/training');
            }
            
        }
        catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


 public function findunitdetails()
  {

    $unit_id=$this->input->post('unit_id'); 
    $unit_data =$this->Training_model->findunit($unit_id);   
    foreach ($unit_data as $value) {
       $data['unit_name']=$value['unit_name'];
       $data['address']=$value['address'];
       $data['phone']=$value['phone_number'];
    }  
    echo json_encode($data);
  }

  public function findtrainingtitle()
  {

    $unit_id=$this->input->post('unit_id'); 
    $unit_data =$this->Training_model->findtitle($unit_id);   
    foreach ($unit_data as $value) {
       $data['title']=$value['title']; 
       $data['description']=$value['description']; 
    }  
   // print_r($data);exit();
    echo json_encode($data);
  }


    
  }
?>