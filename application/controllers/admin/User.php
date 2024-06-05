<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class User extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        //print_r($this->session->userdata('user_type'));exit();
        //  if ($this->session->userdata('user_type') ==2 )
        //  {
        //     $this->auth->logout();
            
        //     unset($params);
        //     $this->_login(INVALID_LOGIN);
        // }
        $this->load->model('User_model');
        $this->load->model('Rota_model'); 
        $this->load->model('Profile_model'); 
        $this->load->model('Moveshift_model');
        $this->load->model('Activity_model');
        $this->load->helper('form');
        $this->load->helper('user');
    }
    
    public function index()
    {
       $this->auth->restrict('Admin.User.View');
       $result = array(); 
       $this->load->helper('user');
       $this->load->view('includes/home_header'); 
       $data=array(); 
       $id=$this->session->userdata('user_id'); 
       //$unit_id=$this->session->userdata('unit_id'); print_r($unit_id);

      
       // if($this->session->userdata('user_type') ==1)
       // {  //print_r('admin');
       //      // if($this->session->userdata('user_id')==1)
       //      // {
       //          $data['user']=$this->User_model->finduser(); 
       //          $data['categoryList']=$this->User_model->findunitwithoutAgency(''); 
       //      // }
       //      // else
       //      // { 
                
                 
       //      // }  
       // }
       // else
       // {  //print_r('others');
       //     //print_r($data['categoryList']); 
       //          $userUnits= $this->User_model->getUnitIdOfUser($id);    //print_r($userUnits);exit();
       //          if($userUnits=='')
       //          { 
       //            $data['user']=array();
       //            $data['categoryList']=array();
       //          }
       //          else
       //          {  
       //            $data['user']=$this->User_model->findalluserbyadmin($userUnits);
       //            $data['categoryList']=$this->User_model->findunitforadmin($userUnits);  
       //          }
       //  }

        $u_id=$this->session->userdata('user_id');  
        $userUnits= $this->User_model->getUnitIdOfUser($u_id);
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {
            $data['user']=$this->User_model->finduser(); 
            $data['categoryList']=$this->User_model->findunitwithoutAgency('');   
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==9)
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
                $data['user']=$this->User_model->findalluserbyadmin($sub);
                $data['categoryList'] = $this->User_model->fetchSubTree($sub);  
            }
            else
            {     
                $data['user']=$this->User_model->findalluserbyadmin($userUnits);
                $data['categoryList']=$this->User_model->findunitforadmin($userUnits);   
            }

        }
        else
        {
            $data['user']=$this->User_model->findalluserbyadmin($userUnits);
            $data['categoryList']=$this->User_model->findunitforadmin($userUnits);
                    
        }

        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
       $this->load->view("admin/user/manageuser",$data);
       $result['js_to_load'] = array('user/user.js');
       $this->load->view('includes/home_footer',$result);
    }

    //  public function adduser()
    // { 
    //     $this->auth->restrict('Admin.User.Add');

    //     $userInfo = array();
    //     $tmp = array(); 
    //     // $this->load->helper('form');
    //     // $this->load->library('form_validation');
    //     // $this->form_validation->set_rules('firstname', 'firstname', 'required');
    //     // $this->form_validation->set_rules('lastname', 'lastname', 'required');
    //     // $this->form_validation->set_rules('email', 'email', 'required');
    //     // $this->form_validation->set_rules('paymenttype', 'paymenttype', 'required|callback_paymenttype_validate'); 
    //     // $this->form_validation->set_rules('unit', 'unit', 'required|callback_unit_validate'); 
    //     // $this->form_validation->set_rules('designation', 'designation', 'required|callback_select_validate');
    //     // $this->form_validation->set_rules('mob_number', 'Mobile Number', 'required');
    //     // $this->form_validation->set_rules('accept_terms', 'Accept terms and conditions', 'required');

    //    $data = array(); 
    //    $header['title'] = 'Add'.'user';
    //    $this->load->view('includes/home_header',$header);
    //   // print_r($this->input->post());exit();
    //    if($this->input->post()==NUll)
    //    {  
          
    //         $data['user']=$this->User_model->alluser();
    //         $data['designation']=$this->User_model->finddesignation();
    //         $data['categoryList'] = $this->User_model->fetchCategoryTree();
    //         $data['paymenttype']=$this->User_model->findpayment();
    //         $data['error'] = validation_errors();
    //         $this->load->view("admin/user/addUser",$data);
             
    //     }
    //     else
    //     {  
    //         for ($i = 0; $i < 109; $i++)
    //         {
    //         $tmp['firstname'] = $this->input->post('firstname').$i;
    //         $tmp['lastname'] =  $this->input->post('lastname').$this->input->post('unit');
    //         $tmp['email'] = $this->input->post('firstname').$i.$this->input->post('unit').'@gmail.com';
    //         $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    //         $str = substr( str_shuffle( $chars ), 0, 8 );   
    //         $tmp['password']= md5($str); 
    //         if($i==0)
    //         {
    //         $tmp['designation'] = 6;
    //         }
    //         if($i!=0 && $i<15)
    //         {  
              
    //           if($i==1 && $i<7)
    //           {
    //             $tmp['default_shift']= 16;
    //             $tmp['designation'] = 3; 
    //           }
    //           elseif($i==7 && $i<15)
    //           {
    //             $tmp['default_shift']= 15;
    //             $tmp['designation'] = 3; 
    //           }

    //         }
            
    //         if($i!=14 && $i<30)
    //         {
            
    //         if($i==15 && $i<22)
    //           {
    //             $tmp['default_shift']= 16;
    //             $tmp['designation'] = 11;  
    //           }
    //           elseif($i==22 && $i<30)
    //           {
    //             $tmp['default_shift']= 5;
    //             $tmp['designation'] = 11;  
    //           }
    //         }
    //         if($i!=30 && $i<45)
    //         {
            
    //           if($i==30 && $i<38)
    //           {
    //             $tmp['default_shift']= 16;
    //             $tmp['designation'] = 14;
    //           }
    //           elseif($i==38 && $i<45)
    //           {
    //             $tmp['default_shift']= 6;
    //             $tmp['designation'] = 14;
    //           }
    //         }
    //         if($i!=45 && $i<60)
    //         {
    //          if($i==45 && $i<52)
    //           {
    //             $tmp['default_shift']= 16;
    //             $tmp['designation'] = 15;
    //           }
    //           elseif($i==52 && $i<60)
    //           {
    //             $tmp['default_shift']= 7;
    //             $tmp['designation'] = 15;
    //           }
    //         }
    //         if($i!=60 && $i<75)
    //         {
    //           if($i==60 && $i<68)
    //           {
    //             $tmp['default_shift']= 16;
    //             $tmp['designation'] = 16;
    //           }
    //           elseif($i==68 && $i<75)
    //           {
    //             $tmp['default_shift']= 8;
    //             $tmp['designation'] = 16;
    //           }
    //         }
    //         if($i!=75 && $i<90)
    //         {
    //          if($i==75 && $i<83)
    //           {
    //             $tmp['default_shift']= 16;
    //             $tmp['designation'] = 17;
    //           }
    //           elseif($i==83 && $i<90)
    //           {
    //             $tmp['default_shift']= 10;
    //             $tmp['designation'] = 17;
    //           }
    //         }
    //         if($i!=90 && $i<105)
    //         {
    //           if($i==90 && $i<105)
    //             {
    //               $tmp['default_shift']= 16;
    //               $tmp['designation'] = 19;
    //             }
    //             elseif($i==52 && $i<60)
    //             {
    //               $tmp['default_shift']= 9;
    //               $tmp['designation'] = 19;
    //             }
    //         }
    //         if($i==105 && $i<107)
    //         {
             
    //         $tmp['designation'] = 24; 
    //         }
    //         if($i==107 && $i<108)
    //         {
    //         $tmp['default_shift']= '';
    //         $tmp['designation'] = 25; 
    //         }
    //         // if($i>50)
    //         // $tmp['designation'] = 3;
    //         $tmp['unit'] = $this->input->post('unit');
    //         $tmp['paymenttype'] =  $this->input->post('paymenttype');  
    //         $tmp['status'] = 1; 
    //         $tmp['weekly_hours'] = 40;
    //          $user_id = $this->User_model->save($tmp);
    //         }

    //     }

    // }
    public function setSession()
    {
        $user_id=$this->session->userdata('user_id');
        $css_data=$this->input->post('status');
        $this->User_model->insertcssvalue($css_data,$user_id);
        return;
        //exit();
    }

public function senduseremail()
    {
     exit();
     $this->load->helper('string');
     $email=$this->User_model->findemails();  
          foreach ($email as $value){
         //print_r($value);exit();
            $to_email=$value['email']; 
            $user_id=$value['id'];  
            $params['status'] = 1; 
            $params['id']=$value['id'];  
            $params['email']=$value['email']; 
            $token = $this->User_model->insertToken($params['email']);  
            $qstring =urlencode($token['key']);  
            $url = base_url() .'users/reset_password/token/'. $qstring;  
            $link = '<a href="' . $url . '">here</a>'; 
            $site_title = 'St Matthews Healthcare - SMH Rota';
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $url,
                'to' => $to_email,
                'type' => 'signup',
                'supervisor_name'=> 'Super Admin',
                'user_name' => $value['fname'].' '.$value['lname'],
                'subject' => 'Welcome to  SMH-Rota - Set your password to access your account.',
                'content_title'=>'We are glad to have you!',
            );
            //print_r($emailSettings);exit();
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/senduseremail', $emailSettings, true);
            // die($htmlMessage);  exit();
            // load email helper
            $this->load->helper('mail');
            // send welcome mail 
            //print_r('<pre>');
            $check_email_status=$this->User_model->check_email_status($user_id);
            if($check_email_status=='true')
            {
                $status=sendMail($emailSettings, $htmlMessage);  
                $start_date=date('Y-m-d H:i:s');  
                $updated_userid=$this->session->userdata('user_id');
                // $status=1;
                if($status==1)
                {  
                    $this->User_model->updates($params);
                    $datahome=array('user_id'=>$user_id,'mail_send_status'=>1,'password_change_status'=>0,'updation_date'=>$start_date ); 
                    $this->User_model->user_email_send($datahome);
                }
                else
                {  
                   $datahome=array('user_id'=>$user_id,'mail_send_status'=>0,'password_change_status'=>0,'updation_date'=>$start_date ); 
                    $this->User_model->user_email_send($datahome);
                }  

                print 'user_id-'; print_r($user_id);  echo "-  mail send"; print '<br>';
            }
            else
            {

                print 'user_id-'; print_r($user_id);  echo "-  mail already send"; print '<br>';
            }
            
            
        }

    }

public function adduser()
    {   
        $this->auth->restrict('Admin.User.Add'); 
        //print_r($this->input->post());
        $userInfo = array();
        $tmp = array(); 
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'first name', 'required');
        $this->form_validation->set_rules('lastname', 'last name', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('unit', 'unit', 'required'); 
 
        $this->form_validation->set_rules('paymenttype', 'paymenttype', 'required'); 
        $this->form_validation->set_rules('designation', 'job role', 'required');
 
        // $this->form_validation->set_rules('mob_number', 'Mobile Number', 'required');
        // $this->form_validation->set_rules('accept_terms', 'Accept terms and conditions', 'required');
       $data = array(); 
       $header['title'] = 'Add'.'user';
       $admin_email=getCompanydetails('from_email');
       $this->load->view('includes/home_header',$header);
       $data['error']= '';
       if ($this->form_validation->run() == FALSE)
       {
            $id=$this->session->userdata('user_id');  
            $data['user']=$this->User_model->alluser();
            $data['designation']=$this->User_model->finddesignation();
            //$data['categoryList'] = $this->User_model->fetchCategoryTree();  
            //print_r($this->session->userdata('user_type'));exit();

            $u_id=$this->session->userdata('user_id');  
            $userUnits= $this->User_model->getUnitIdOfUser($u_id);
            $sub=$this->User_model->CheckuserUnit($u_id);
            $unit=$this->User_model->findunitofuser($u_id); 
            $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
            //if($this->session->userdata('user_type')==1) //all super admin can access
            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $data['categoryList']=$this->User_model->findunitwithoutAgency('');   
            }
            // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==9)
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
                    $data['categoryList'] = $this->User_model->fetchSubTree($sub);  
                }
                else
                {     
                    $data['categoryList']=$this->User_model->findunitforadmin($userUnits);   
                }

            }
            else
            {
                $data['categoryList']=$this->User_model->findunitforadmin($userUnits);
                        
            }

             // if($this->session->userdata('user_type') ==1)
             //  { 
             //          $data['categoryList']=$this->User_model->findunitwithoutAgency('');
             //  }  
             //  else
             //  {
             //    $data['categoryList']=$this->User_model->findunitbyusersofmanager($id);  
             //  } 
            $data['paymenttype']=$this->User_model->findpayment(); 
            //print_r($data['categoryList']);exit();
          
            $this->load->view("admin/user/addUser",$data);
             
        }
        else
        {    
          $email = $this->input->post('email');
          //print_r($email);exit();
          //print_r($this->checkUsername($email,''));exit();
          if(count($this->checkUsername($email,''))!=0)
          {
           
            $data['error']= ('Email already exist, please try another email id.');
            $data['user']=$this->User_model->alluser(); 
            $data['designation']=$this->User_model->finddesignation();
            //$data['categoryList'] = $this->User_model->fetchCategoryTree();

            $u_id=$this->session->userdata('user_id');  
            $userUnits= $this->User_model->getUnitIdOfUser($u_id);
            $sub=$this->User_model->CheckuserUnit($u_id);
            $unit=$this->User_model->findunitofuser($u_id); 
            $parent = 0;
            if(count($unit) > 0){
                $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
            }
            //if($this->session->userdata('user_type')==1) //all super admin can access
            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $data['categoryList']=$this->User_model->findunitwithoutAgency('');   
            }
            // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==9)
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
                    $data['categoryList'] = $this->User_model->fetchSubTree($sub);  
                }
                else
                {     
                    $data['categoryList']=$this->User_model->findunitforadmin($userUnits);   
                }

            }
            else
            {
                $data['categoryList']=$this->User_model->findunitforadmin($userUnits);
                        
            }
            $data['paymenttype']=$this->User_model->findpayment();

            $this->load->view("admin/user/addUser",$data);
          }
        else
          {
            $tmp['firstname'] =  $this->input->post('firstname');
            $tmp['lastname'] =  $this->input->post('lastname');
            $tmp['email'] = $this->input->post('email');
            $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $str = substr( str_shuffle( $chars ), 0, 8 );   
            $tmp['password']= md5($str); 
            $tmp['designation'] = $this->input->post('designation');
            $tmp['unit'] = $this->input->post('unit');  
            // $parent=$this->User_model->findparent($tmp['unit']); 
            // if(count($parent)!=0)
            // { 
            //       $this->session->set_flashdata('error',"1");
            //       $this->session->set_flashdata('message', 'Please select branch unit.');
            //       redirect('admin/user/adduser/');
            // }
            // else
            // {  
            $tmp['paymenttype'] =  $this->input->post('paymenttype');  
            $tmp['status'] = 2;  
            //print_r($tmp);exit();
            $user_id = $this->User_model->save($tmp); 
            if($user_id)
            {
                $title='Add User';
                InsertEditedData($this->input->post(),$title);
                
                /* new changes by swaraj on jul 20 unit change ,add x to rotascheule to new units */
                $date=date('Y-m-d');
                if(date('D', strtotime($date))!='Sun')
                {
                    //$nextSunday = date('Y-m-d', strtotime('next sunday'));
                    $nextSunday=date('Y-m-d', strtotime($date.'last sunday')); /* previous sunday of the date */
                }
                else
                {
                    $nextSunday = $date;
                }

                // print_r($nextSunday);
                // exit();
                $rotas=$this->Rota_model->getRotaForUnitchangeBydateAndunitID($nextSunday,$tmp['unit'],$user_id);
                if($rotas!=0)
                {
                   foreach ($rotas as $rota) { //print_r($rota); print '<br>';
                           $day=date("D",strtotime($rota['date']));
                           $rotaschedule_data = array(
                                                                'user_id'  => $user_id,
                                                                'shift_id' => 0,
                                                                'shift_hours' => 0,
                                                                'additional_hours' =>NULL,
                                                                'day_additional_hours' =>NULL,
                                                                'night_additional_hours' =>NULL,
                                                                'additinal_hour_timelog_id'=>NULL,
                                                                'comment'=>NULL,
                                                                'from_unit'=>NULL,
                                                                'unit_id'=>$tmp['unit'],
                                                                'rota_id'=>$rota['rota_id'],
                                                                'date'=>$rota['date'],
                                                                'status'=>1,
                                                                'creation_date'=>date('Y-m-d H:i:s'),
                                                                'created_userid'=>$params['created_userid'],
                                                                'updation_date'=>date('Y-m-d H:i:s'),
                                                                'updated_userid'=>$this->session->userdata('user_id'),
                                                                'day'=>$day,
                                                                'designation_id'=>NULL,
                                                                'shift_category'=>0,
                                                                'from_userid'=>NULL,
                                                                'from_rotaid'=>NULL,
                                                                'request_id'=>NULL
                                    );
                            //print_r($rotaschedule_data); print '<br>';
                            $this->Moveshift_model->inserRotascheduleDetails($rotaschedule_data);
                    }

                    $num=count($rotas)-1; 
                    $admin_details=$this->Moveshift_model->getUsername($this->session->userdata('user_id'));
                    $user_details=$this->Moveshift_model->getUsername($user_id);
                    $unit_name=$this->Moveshift_model->getUnitname($tmp['unit']);
//print_r($admin_details); print_r($user_details);exit();

                    $description = $admin_details['fname'].' '.$admin_details['lname'].' '.'has added a rota for '.$user_details['fname'].' '.$user_details['lname'].' '.' from '.' '.$rotas[0]['date'].' '.'to'.' '.$rotas[$num]['date'].'(Add user- create x entry).'; //print_r($description);exit();

                    $data_activity_log=array(
                                      'description'=>$description,
                                      'activity_date'=>date('Y-m-d H:i:s'),
                                      'activity_by'=>$this->session->userdata('user_id'),
                                      'add_type'=>'Add User - Create Rota',
                                      'user_id'=>$user_id,
                                      'unit_id'=>$tmp['unit'],
                                      'primary_id'=>$rotas[0]['rota_id'],
                                      'creation_date'=>date('Y-m-d H:i:s')
                      );

                      $this->Activity_model->insertactivity($data_activity_log); 


                    //exit();
                }
            } 
            if($tmp['unit']!=1)
            { 
            unset($tmp['status']);

                    $to_email=$this->input->post('email');
                    $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
                    if(count($session_user)>0){
                    $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
                    }else{
                    $unit_supervisor_name = '';
                    }
                    // print_r($to_email); exit();
                    $site_title = 'St Matthews Healthcare - SMH Rota';
                    $emailSettings = array(
                        'from' => $admin_email,
                        'site_title' => $site_title,
                        'site_url' => $this->config->item('base_url'),
                        'confirm_url'=>base_url() .'admin/User/confirmemail?email=' . urlencode($this->input->post('email')) . '&id=' . urlencode($user_id),
                        'to' => $to_email,
                        'type' => 'add user',  
                        'password'=> $str,
                        'supervisor_name'=>$unit_supervisor_name,
                        'subject' => 'Welcome to'.' '.$site_title,

                    ); 
                    $this->load->library('parser');
                    $htmlMessage = $this->parser->parse('emails/useradd', $emailSettings, true);
                     //die($htmlMessage);
                     //exit();
                    // load email helper
                    $this->load->helper('mail'); 
                    sendMail($emailSettings, $htmlMessage); 
                    unset($tmp);  
                $this->session->set_flashdata('Successfully','A verification link has been sent to users email address');
              }
              else
              {
                 $dataform=array('weekly_hours'=>0,'payroll_id'=>000,'status'=>1);
                 $this->User_model->updateAgencyweekhours($user_id,$dataform);
              }
                 $data['user']=$this->User_model->findusers($user_id);
                 redirect('admin/user/edituser/'.$user_id,$data);
          }
        // }
        }
       
       $this->load->view('includes/home_footer');
    }

    public function addDays($day,$date){
        return date('Y-m-d', strtotime($date. ' + 6 days'));
      }

    public function testmail()
    {
      mail("contactsiva.spc@gmail.com","Test server","Test message")or die('mail error');
      mail("ssugunan98@gmail.com","Test server","Test message")or die('mail error');
    }


 public function confirmemail()
    { 
        $params = array();
        $userInfo = array();
        $data = array();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->load->helper('string');
        $this->session->set_flashdata('confirmemail', '');
        $params['id'] =  urldecode($this->input->get('id')); 
        $params['email'] = urldecode($this->input->get('email')); 
        // $params['status'] =2; 
        if (empty($params['id']) && empty($params['email'])) 
        {
            redirect('user');
            // exit;
        }
        $this->load->model('User_model');
        $userInfo = $this->User_model->getUser($params);  
        
        if (count($userInfo) > 0 && $userInfo[0]['status']==2) 
        {
           
            $params['status'] = 1; 
            $this->User_model->updates($params);
            $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
                    if(count($session_user)>0){
                    $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
                    }else{
                    $unit_supervisor_name = '';
                    }
            $token = $this->User_model->insertToken($params['email']); 
            // $this->session->set_flashdata('confirmemail', 'Successfully verified. Login to proceed.');
            // redirect('users');
            // $site_title = (siteSettings('email_title')) ? siteSettings('email_title') : 'ISQ';
            $site_title = 'St Matthews Healthcare:';
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url') ,
                'to' => $userInfo[0]['email'],
                'type' => 'signup',
                'user_name' => $userInfo[0]['fname'].' '.$userInfo[0]['lname'],
                'subject' => $site_title.' '.'Account created.',
                'supervisor_name'=>$unit_supervisor_name,
                'content_title'=>'We are glad to have you!',
            );
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/welcome', $emailSettings, true);
            //die($htmlMessage);exit();
            // load email helper
            $this->load->helper('mail');
            // send welcome mail
             sendMail($emailSettings, $htmlMessage); 

           $this->session->set_flashdata('Successfully','"Your account has been activated successfully. You can now login."'); 
            redirect('admin/user/reset_password','refresh');
           
        } 
        else 
        {
            redirect('admin/User/addUser');
            // exit;
        }
    }
    public function employeedetailspermission()
    {
        $data = array();
        $fields = array();
        $header['title'] = 'Permission'.'employee details'; 
        $this->load->view('includes/home_header',$header);
        $fields = $this->User_model->ger_permission_data();
        $data['personal_details_fields'] = $fields;
        $this->load->view("admin/user/employee_details_permission",$data);
        $this->load->view('includes/home_footer');
    }
    public function ipaddresses()
    {
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array(); 
       $result=array(); 
       // $data['count']=count($data['designation']); 
       $data['ipaddresses']=$this->User_model->getIpAddresses();  
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->load->model('Rota_model');
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/ 
       $this->load->view("admin/user/list_ipaddresses",$data);
       $result['js_to_load'] = array('user/ipaddress.js');
       $this->load->view('includes/home_footer',$result);

    }
    public function addipaddress()
    {
        $data = array();
        $result = array();
        $fields = array();
        $header['title'] = 'Permission'.'employee details'; 
        $this->load->view('includes/home_header',$header);
        $fields = $this->User_model->ger_permission_data();
        $data['personal_details_fields'] = $fields;
        $this->load->view("admin/user/addipaddress");
        $result['js_to_load'] = array('user/ipaddress.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function saveipaddress()
    {
        $header['title'] = 'Add IP Address';
        $data = array(); 
        $result = array(); 
        $this->load->model('User_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ip_address', 'ip address', 'required');
        $data = array(); 
        $header['title'] = 'Add IP Address';
        $this->load->view('includes/home_header',$header);
        if ($this->form_validation->run() == FALSE)
        {
            $data['error'] ='';
            $this->load->view("admin/user/addipaddress",$data);
        }else{
            $ip_address= $this->input->post('ip_address');
            $dataform=array('ip_address' => $ip_address,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')); 
            $insert_id = $this->User_model->insertipaddress($dataform);
            if($insert_id)
            {
                $this->session->set_flashdata('error', '');
                $this->session->set_flashdata('message', 'Added successfully.');
            }
            else
            {
                $this->session->set_flashdata('message', '');
                $this->session->set_flashdata('error', 'IP address already added.');
            }
            $result['js_to_load'] = array('user/ipaddress.js');
            redirect("admin/user/ipaddresses",$data);
        }
        $this->load->view('includes/home_footer',$result);
    }
    public function editipaddress()
    {
        $data = array();
        $result = array();
        $title_slug = $this->uri->segment(4);
        $ip_id = base64_decode($title_slug);
        $ip_details = $this->User_model->getIpDetails($ip_id);
        $data['ip_details'] = $ip_details;
        $this->load->view('includes/home_header',$header);
        $this->load->view("admin/user/editipaddress",$data);
        $result['js_to_load'] = array('user/ipaddress.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function deleteipaddress(){
        $title_slug = $this->uri->segment(4);
        $ip_id = base64_decode($title_slug);
        $ip_details = $this->User_model->delete_ip_address($ip_id);
        $this->session->set_flashdata('error', '');
        $this->session->set_flashdata('message', 'Deleted successfully.');
        redirect("admin/user/ipaddresses");
    }
    public function updateipaddress($value='')
    {
        $ip_id = $this->input->post('ip_id');
        $ip_address = $this->input->post('ip_address');
        $ip_details = $this->User_model->updateIpDetails($ip_id,$ip_address);
        if($ip_details){
            $this->session->set_flashdata('error', '');
            $this->session->set_flashdata('message', 'Edited successfully.');
        }else{
            $this->session->set_flashdata('message', '');
            $this->session->set_flashdata('error', 'IP address exists with another ID.');
        }
        
        redirect("admin/user/ipaddresses");
    }
    public function edit_permission()
    {
        $action = $this->input->post('action');
        $id = $this->input->post('id');  
        $result = $this->User_model->edit_employee_permission($id,$action); 
        exit;
    }
  public function addagencystaff()
  {
      $this->auth->restrict('Admin.User.Add'); 
        //print_r($this->input->post('unit'));
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('firstname', 'first name', 'required');
      $this->form_validation->set_rules('lastname', 'last name', 'required');
      $this->form_validation->set_rules('designation', 'job role', 'required');
      $this->form_validation->set_rules('gender', 'gender', 'required');
      $header['title'] = 'Add'.'user'; 
      $this->load->view('includes/home_header',$header); 
      if ($this->form_validation->run() == FALSE)
       {
          $data['error'] = validation_errors();
          $data['designation']=$this->User_model->finddesignation();
          $this->load->view("admin/user/addagencystaff",$data);
       }
       else
       {
        $params=array();
        $params['firstname']=$this->input->post('firstname'); 
        $params['lastname']=$this->input->post('lastname');
        $params['gender']=$this->input->post('gender'); 
        $params['designation_id']=$this->input->post('designation');
        $designation_code=$this->User_model->finddesignationcode($params['designation_id']);
        if(count($designation_code)>0)
        {
            $last=$designation_code[0]['designation_code'];
        }
        else
        {
            $last=$params['lastname'];
        }
        $new_fname = preg_replace('/[^A-Za-z0-9]/', "", $params['firstname']);  
        $params['email']=$new_fname.$last.'@'.'gmail'.'.com';
        $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $str = substr( str_shuffle( $chars ), 0, 8 );  
        $params['password']=md5($str);
        $randnum = rand(1111111111,9999999999);
        $params['mobile_number']=$randnum;
        $params['dob']='2020-01-01';
        $params['address1']='-';
        $randpost = rand(111111,999999);
        $params['country']='United Kingdom';
        $params['postcode']=$randpost;
        $params['kin_name']='Kin_'.$params['firstname'];
        $params['kin_phone']=$randnum;
        $params['kin_address']='-';
        $params['annual_holliday_allowance']='00.00';
        $params['actual_holiday_allowance']='00.00';
        $params['actual_holiday_allowance_type']=2;
        $params['annual_allowance_type']=2;
        $params['default_shift']=15;
        $params['start_date']='2020-01-01';
        $params['payroll_id']=$randpost; 
        $result=$this->User_model->saveagencystaff($params); 
        if($result)
        {
                   $title='Add Agencystaff';
                   InsertEditedData($this->input->post(),$title);
        }
        $this->session->set_flashdata('error',"0");
        $this->session->set_flashdata('message', 'Agency staff added successfully.');
        redirect('admin/manageuser');
 
       }

      $this->load->view('includes/home_footer');

  }

 public function reset_password()
        {           
                $this->auth->restrict('Admin.Password.Change');
                $this->header['title'] = 'St.Matthews Health Care - rotasystem';
                $this->load->view('includes/header', $this->header);
                $this->session->set_flashdata('message', 'Please enter your password here'); 
                $data['users']=$this->User_model->findTokenbyUser();
                foreach ($data['users'] as $value) {
                  $data['user']=$value;
                } 
                $this->load->view('admin/user/resetpassword',$data);
                $data['js_to_load'] = array();
                $this->load->view('includes/footer',$data);

        }

 public function edituser()
    {
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->auth->restrict('Admin.User.Edit');
        $userInfo = array();
        $tmp = array(); 
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'first name', 'required');
        $this->form_validation->set_rules('lastname', 'last name', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('designation', 'designation', 'required');
        $this->form_validation->set_rules('unit', 'unit', 'required');
        $this->form_validation->set_rules('paymenttype', 'paymenttype', 'required');
        // $this->form_validation->set_rules('mob_number', 'Mobile Number', 'required');
        // $this->form_validation->set_rules('accept_terms', 'Accept terms and conditions', 'required');

       $data = array(); 
       $header['title'] = 'Edit'.'user';
       $this->load->view('includes/home_header',$header);
    
       if ($this->form_validation->run() == FALSE)
       {
 
            $id=$this->uri->segment(4); 
            $data['designation']=$this->User_model->finddesignation(); 
            $data['ethnicity']=$this->User_model->findethnicity(''); 
            // if($this->session->userdata('user_type') ==1)
            //   { 
            //     if($this->session->userdata('user_id')==1)
            //     {
                      $data['unit']=$this->User_model->findunitwithoutAgency('');
              //   }
              //   else
              //   {
              //         $data['unit']=$this->User_model->findunitforadmin($this->session->userdata('unit_id'));
              //   }
              // }  
              // else
              // {
              //   $data['unit']=$this->User_model->findunitbyusersofmanager($id);  
              // } 
            //print_r($data['unit']);exit();  
            $data['paymenttype']=$this->User_model->findpayment();
            $data['user']=$this->User_model->findusers($id); 
            $data['unitbyuser']=$this->User_model->findunitofuser($data['user'][0]['id']);  
            $data['user_rates']=$this->User_model->finduserRates($id);  
            $data['units']=$this->User_model->findunits($id); 
            $data['shifts']=$this->User_model->findshift(); 
            $data['group']=$this->User_model->findgroups();
            $data['groups']=$this->User_model->findgroupswithoutadmin();
            $data['country']=$this->User_model->findcountry();  
            $data['workschedule']=$this->User_model->userworkschedule($id);  
            $data['error'] = validation_errors();

            //print_r($this->session->userdata('unit_id')); print_r($data['unitbyuser'][0]['unit_id']);exit();


                $parent=getParentId($this->session->userdata('unit_id')); //check if main unit is subunit. 
                $status=Checkparent($this->session->userdata('unit_id')); // check logged unit is parent or not

                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                {
                    $this->load->view("admin/user/userdetails",$data);
                }
                else
               // if($this->session->userdata('user_type')!=1)
                {
                    
                    if($status!=0)
                    {   //if logged unit is parent
                       if($this->session->userdata('unit_id')==$data['unitbyuser'][0]['unit_id']) //user unit is logged unit
                       {
                          $access_status=true;
                       }
                       else if($this->session->userdata('unit_id')==getParentId($data['unitbyuser'][0]['unit_id'])) //parent unit of user unit is logged unit
                       {
                          $access_status=true;
                       }
                       else
                       {     //otherwice
                           $access_status=false;
                       }

                    }
                    else if($parent!=0)
                    { //if logged unit is subunit

                       if($this->session->userdata('unit_id')==$data['unitbyuser'][0]['unit_id']) //user unit is logged unit
                       {
                          $access_status=true;
                       }
                       else if($parent==getParentId($data['unitbyuser'][0]['unit_id'])) //parent of loged unit = parent of userunit
                       {
                          $access_status=true;
                       }
                       else
                       {
                          $access_status=false;
                       }

                    }
                    else
                    {

                        if($this->session->userdata('unit_id')==$data['unitbyuser'][0]['unit_id'])
                        {
                            $access_status=true;
                        }
                        else
                        {
                            $access_status=false;
                        }

                    }


                    if(($access_status==true)&&(CheckPermission($this->session->userdata('user_type'),'Admin.User.Edit')=='True'))
                    {  
                          $this->load->view('admin/user/userdetails', $data);
                    }
                    else
                    {  
                          $this->load->view("admin/error",$data);
                    }
                }
                //else
                //{
                //   $this->load->view("admin/user/userdetails",$data);
                //}
     
        }
        else
        {  
            
            $tmp['firstname'] =  $this->input->post('firstname');
            $tmp['lastname'] =  $this->input->post('lastname');
            $tmp['email'] = $this->input->post('email'); 
            $tmp['designation'] = $this->input->post('designation');
            $tmp['unit'] = $this->input->post('unit');
            $tmp['paymenttype'] =  $this->input->post('paymenttype');  
            $tmp['status'] = $this->input->post('status'); 
            $user_id = $this->User_model->save($tmp); 
            $id=$this->uri->segment(4);
            $email =  $this->input->post('email');

            if($this->checkUsername($email,$id)==0)
          {  
            $data['error']= "Unit name already exist, please try another name.";
            $id=$this->uri->segment(4); 
            $data['designation']=$this->User_model->finddesignation();
            $data['ethnicity']=$this->User_model->findethnicity(''); 
            // if($this->session->userdata('user_type') ==1)
            //   { 
                $data['unit']=$this->User_model->findunitwithoutAgency('');
              // }  
              // else
              // {
              //   $data['unit']=$this->User_model->findunitbyusersofmanager($id);  
              // }  
            $data['paymenttype']=$this->User_model->findpayment();
            $data['user']=$this->User_model->findusers($id); 
            $data['unitbyuser']=$this->User_model->findunitofuser($data['user'][0]['id']);  
            $data['units']=$this->User_model->findunits($id); 
            $data['shifts']=$this->User_model->findshift(); 
            $data['group']=$this->User_model->findgroups();
            $data['groups']=$this->User_model->findgroupswithoutadmin();
            $data['workschedule']=$this->User_model->userworkschedule($id); 
            $this->load->view("admin/user/userdetails",$data);
          }
        else
         {
        $dataform=array('unit_name' => $unit_name,'unit_type'=>$unit_type,'staff_limit'=>$staff_limit,'max_patients'=>$max_patients,'description'=>$description,'status'=>$status,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$this->session->userdata('user_id')); 
        $result = $this->Unit_model->update($id,$dataform);
        $this->session->set_flashdata('Successfully', 'Updated successfully.');
        $data['unit']=$this->Unit_model->findunit($title_slug);
        redirect('admin/user');
            //$this->load->view('admin/editmenubanners', $data);
         }
      }
        $data['js_to_load'] = array('user/user.js');
        $this->load->view('includes/home_footer',$data);
  }
public function insertNewUnitDatas(){
    if (!$this->session->userdata('user_id')) {
    // Session has expired
        echo json_encode(array('status' => 'error', 'message' => 'Session expired','url' => base_url()));
        return;
    }
    $user_id=$this->input->post('user_id'); 
    $to_unit=$this->input->post('to_unit'); 
    $unit_change_date=$this->input->post('unit_change_date'); 
    $dataform=array('to_unit' => $to_unit,'unit_change_date' => $unit_change_date,'unit_scheduler_id'=>$this->session->userdata('user_id')); 
    $status = $this->User_model->insertdatas($user_id,$dataform);
    //header('Content-Type: application/json');
    echo json_encode(array('status'=> $status));
    exit();
}
public function insertdata() 
{
  $this->load->model('User_model');
  $id=$this->input->post('id');
  $user_id=$this->input->post('user_id'); 
  $fname=$this->input->post('fname');
  $lname=$this->input->post('lname');
  $email=$this->input->post('email');
  $mobile_number=$this->input->post('mobile_number');
  $date_daily=$this->input->post('dob');  
  $old_date = explode('/', $date_daily); 
  $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
  $dob=$new_data;

  //$dob=$this->input->post('dob');
  $address1=$this->input->post('address1');  
  $address2=$this->input->post('address2');
  $country=$this->input->post('country');
  $city=$this->input->post('city');  
  $postcode=$this->input->post('postcode');
  //$status=$this->input->post('status');
  $kin_name=$this->input->post('kin_name');
  $kin_phone=$this->input->post('kin_phone');
  $kin_address=$this->input->post('kin_address');
  $ethnicity=$this->input->post('ethnicity');
  $visa_status=$this->input->post('visa_status');
  $new_ethinicity=$this->findethnicitynew($ethnicity); //print_r($this->input->post());exit();

  $gender=$this->input->post('gender');
  $address_from_unit=$this->User_model->findaddress($user_id);   
  $updated_userid=$this->session->userdata('user_id');
  if($address_from_unit[0]['address1']!=$address1)
  {  
    $address=$address1.','.$city.','.$country.','.$postcode;
    $datas=array('user_id'=>$user_id,'address'=>$address,'kin_name'=>$kin_name,'kin_phonenumber'=>$kin_phone,'kin_address'=>$kin_address,'changed_date'=>date('Y-m-d H:i:s'));  
    $this->User_model->insertAddresshistory($datas);
  } 

  ///-------- user personaldetails change history
   $data_fromdb=$this->User_model->getPersonalDetails($user_id); //print_r($data_fromdb); print '<br>';
   $data_new=array('email'=>$email,'fname'=>$fname,'lname'=>$lname,'mobile_number'=>$mobile_number,'dob'=>$dob,'gender'=>$gender,'Ethnicity'=>$new_ethinicity,'visa_status'=>$visa_status,'kin_name'=>$kin_name,'kin_phone'=>$kin_phone,'kin_address'=>$kin_address); 


   //print_r($data_new); print '<br>';
   //find difference
   $results=array_diff($data_fromdb,$data_new); //print_r($results); exit();

    $data_new_pre=array('email'=>'email:'.$email,'fname'=>'fname:'.$fname,'lname'=>'lname:'.$lname,'mobile_number'=>'mobile:'.$mobile_number,'dob'=>'dob:'.$dob,'gender'=>'gender:'.$gender,'Ethnicity'=>'ethnicity:'.$new_ethinicity,'visa_status'=>'visa_status:'.$visa_status,'kin_name'=>'kin_name:'.$kin_name,'kin_phone'=>'kin_phone:'.$kin_phone,'kin_address'=>'kin_address:'.$kin_address); // new one


    $data_old=array('email'=>'email:'.$data_fromdb['email'],'fname'=>'fname:'.$data_fromdb['fname'],'lname'=>'lname:'.$data_fromdb['lname'],'mobile_number'=>'mobile:'.$data_fromdb['mobile_number'],'dob'=>'dob:'.$data_fromdb['dob'],'gender'=>'gender:'.$data_fromdb['gender'],'Ethnicity'=>'ethnicity:'.$data_fromdb['Ethnicity'],'visa_status'=>'visa_status:'.$data_fromdb['visa_status'],'kin_name'=>'kin_name:'.$data_fromdb['kin_name'],'kin_phone'=>'kin_phone:'.$data_fromdb['kin_phone'],'kin_address'=>'kin_address:'.$data_fromdb['kin_address']);//oldone

   $data_for_edithistory=array(
    'user_id'=>$user_id,
    'current_data'=>implode(',', $data_new_pre),
    'previous_data'=>implode(',', $data_old),
    'activity_type'=> "Personal Details",
    'activity_date'=>date('Y-m-d H:i:s'),
    'activity_by'=>$updated_userid,

   );

   if(!empty($results))
   {
     $this->User_model->insertuseredithistory($data_for_edithistory); 

    $title='Personal Details';
    InsertEditedData($this->input->post(),$title);
       
   } 
   ///-------------------------------

  // print_r(count($this->checkUsername($email,$user_id)));exit();
  if(count($this->checkUsername($email,$user_id))!=0)
  {  
    $data['error']= "Email already exist."; 
  }
 else
  { 
  $dataformuser['email'] = $email;
  $dataformuser['updation_userid'] = $updated_userid;
  $result =$this->User_model->insertdatas($user_id,$dataformuser);
  //$dataformstatus['status'] = $status;
  $dataformstatus['updation_userid'] = $updated_userid;

  //$result1 =$this->User_model->updatestatus($user_id,$dataformstatus);
  $dataform=array('fname' => $fname,'lname' => $lname,'mobile_number' => $mobile_number,'dob' => $dob,'address1' => $address1,'address2' => $address2,'country' => $country,'city' => $city,'Ethnicity'=>$new_ethinicity,'postcode' => $postcode,'visa_status'=>$visa_status,'gender'=>$gender,'kin_name' => $kin_name,'kin_phone' => $kin_phone,'kin_address' => $kin_address,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid); 
  $this->User_model->insertdata($user_id,$dataform);

  $data['error']= " ";  

  } 
  echo json_encode($data);

}

public function checkUsername($email, $id=''){
        
        $status = 1;
        $data = array();
        $data['email']= $email;
        $data['id']= $id;
        $result = $this->User_model->getuserDetails($data);  
       //print_r($result);exit();
        // unset($data);
        //   if(count($result)>0) 
        //     $status = 0;
          
       return $result;
       
    }

      public function findethnicitynew($ethnicity)
       {
        //$ethnicity=$this->input->post('id');
        $result=$this->Profile_model->getEthnicity($ethnicity);
        //print_r($result);exit();
        if(count($result)>0)
        {
            return $result['Ethnic_group'];
        }
        else
        {
            return NULL;
        } 
       }

 

public function insertdatas()
{
    if($this->input->post('status') == 2 && trim($this->input->post('final_date')) == '' || $this->input->post('status') == 2 && trim($this->input->post('final_date')) == ' ') {
        // Validation failed
        $response['status'] = 'error';
        $response['message'] = 'Please select final working date';
        echo json_encode($response);
        return;
    } else {
   //print_r($this->input->post());exit();
      $this->load->model('Designation_model');
      $user_id=$this->input->post('user_id'); //print_r($user_id);
      $group_id=$this->input->post('group_id');
      $weekly_hours=$this->input->post('weekly_hours'); 
      $annual_holliday_allowance=$this->input->post('annual_holliday_allowance'); 
      $annual_allowance_type=$this->input->post('annual_allowance_type');
      $actual_holiday_allowance=$this->input->post('actual_holiday_allowance');  
      $actual_holiday_allowance_type=$this->input->post('actual_holiday_allowance_type');
      $remaining_holiday_allowance = $this->input->post('remaining_holiday_allowance');
      $designation_id=$this->input->post('designation_id');
      $old_desig=$this->Designation_model->finduserDesignation($user_id); 
      //print_r($old_desig);exit();

      $date_daily=$this->input->post('start_date');  
      $old_date = explode('/', $date_daily); 
      $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
      $start_date=$new_data;

      $date_daily=$this->input->post('final_date');  
      $old_date = explode('/', $date_daily); 
      $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
      
      if($this->input->post('final_date')==' ')
      { 
        $final_date='0000-00-00';
      }
      else
      { 
        $final_date=$new_data1;
      }


      $status=$this->input->post('status');
      if($status==2)
      {
        $final=$final_date;
      }
      else
      {
        $final='';
      }

      // $start_date=$this->input->post('start_date');
      // $final_date=$this->input->post('final_date');
      $notes=$this->input->post('notes');
      $payroll_id=$this->input->post('payroll_id');
      $default_shift=$this->input->post('default_shift');
      $payment_type=$this->input->post('payment_type');
      $day_rate=$this->input->post('day_rate'); 
      $night_rate=$this->input->post('night_rate');
      $saturday_rate=$this->input->post('saturday_rate');
      $sunday_rate=$this->input->post('sunday_rate');
      $updated_userid=$this->session->userdata('user_id');
      $weekend_night_rate=$this->input->post('weekend_night_rate');
      $exit_interview=$this->input->post('exit_interview');
      $exit_reason=$this->input->post('exit_reason');
      $hr_id=$this->input->post('hr_id');



      $ratesbyuser=array('day_rate'=>$day_rate,'night_rate'=>$night_rate,'day_saturday_rate'=>$saturday_rate,'day_sunday_rate'=>$sunday_rate,'weekend_night_rate'=>$weekend_night_rate);
      $data['user_rates']=$this->User_model->findRatesByUser($user_id);
      $data['user_weekly_hours_byuser']=$this->User_model->findWeeklyHoursByUser($user_id);
      $wh_byusers = array($weekly_hours);
      $result=array_diff($ratesbyuser,$data['user_rates'][0]);
      $w_result = array_diff($wh_byusers,$data['user_weekly_hours_byuser'][0]);
      $rates=array('user_id'=>$user_id,'day_rate'=>$day_rate,'night_rate'=>$night_rate,'day_saturday_rate'=>$saturday_rate,'day_sunday_rate'=>$sunday_rate,'weekend_night_rate'=>$weekend_night_rate,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid); 
      $w_working_hours_history = array(
            'user_id'=>$user_id,
            'previous_contracted_hours'=>$data['user_weekly_hours_byuser'][0]['weekly_hours'],
            'updated_contracted_hours'=>$weekly_hours,
            'updation_date'=>date('Y-m-d H:i:s'),
            'updated_userid'=>$updated_userid,
            'created_date' =>date('Y-m-d H:i:s'),
        ); 
      if(!empty($result))
      {
        $this->User_model->inserthistoryuserRates($rates); 
      }
      if(!empty($w_result))
      {
        $this->User_model->inserthistoryweeklyHours($w_working_hours_history); 
      }
      ///-------- user employee details change history
      $data_fromdb=$this->User_model->getUserDetailsforHistory($user_id); //print_r($data_fromdb); print_r('<br>');
      $data_new=array('group_id'=>$group_id,'weekly_hours'=>$weekly_hours,'status'=>$status,'annual_holliday_allowance'=>$annual_holliday_allowance,'actual_holiday_allowance'=>$actual_holiday_allowance,'designation_id'=>$designation_id,'start_date'=>$start_date,'final_date'=>$final_date,'notes'=>$notes,'payroll_id'=>$payroll_id,'default_shift'=>$default_shift,'payment_type'=>$payment_type,'exit_interview'=>$exit_interview,'exit_reason'=>$exit_reason,'hr_ID'=>$hr_id); 
      //print_r($data_new); print_r('<br>');
       $results=array_diff($data_fromdb,$data_new); //print_r($results);exit();

       // new data
       $data_new_pre=array('group_id'=>'group_id:'.$group_id,'weekly_hours'=>'weekly_hours:'.$weekly_hours,'status'=>'status:'.$status,'annual_holliday_allowance'=>'annual_holliday_allowance:'.$annual_holliday_allowance,'actual_holiday_allowance'=>'actual_holiday_allowance:'.$actual_holiday_allowance,'designation_id'=>'designation_id:'.$designation_id,'start_date'=>'start_date:'.$start_date,'final_date'=>'final_date:'.$final,'notes'=>'notes:'.$notes,'payroll_id'=>'payroll_id:'.$payroll_id,'default_shift'=>'default_shift:'.$default_shift,'payment_type'=>'payment_type:'.$payment_type,'exit_interview'=>'exit_interview:'.$exit_interview,'exit_reason'=>'exit_reason:'.$exit_reason,'hr_id'=>'hr_id:'.$hr_id); 

       //old data

       $data_old=array('group_id'=>'group_id:'.$data_fromdb['group_id'],'weekly_hours'=>'weekly_hours:'.$data_fromdb['weekly_hours'],'status'=>'status:'.$data_fromdb['status'],'annual_holliday_allowance'=>'annual_holliday_allowance:'.$data_fromdb['annual_holliday_allowance'],'actual_holiday_allowance'=>'actual_holiday_allowance:'.$data_fromdb['actual_holiday_allowance'],'designation_id'=>'designation_id:'.$data_fromdb['designation_id'],'start_date'=>'start_date:'.$data_fromdb['start_date'],'final_date'=>'final_date:'.$data_fromdb['final_date'],'notes'=>'notes:'.$data_fromdb['notes'],'payroll_id'=>'payroll_id:'.$data_fromdb['payroll_id'],'default_shift'=>'default_shift:'.$data_fromdb['default_shift'],'payment_type'=>'payment_type:'.$data_fromdb['payment_type'],'exit_interview'=>'exit_interview:'.$data_fromdb['exit_interview'],'exit_reason'=>'exit_reason:'.$data_fromdb['exit_reason'],'hr_id'=>'hr_id:'.$data_fromdb['hr_ID']);


       $data_for_edithistory=array(
        'user_id'=>$user_id,
        'current_data'=>implode(',', $data_new_pre),
        'previous_data'=>implode(',', $data_old),
        'activity_type'=> "Employee Details",
        'activity_date'=>date('Y-m-d H:i:s'),
        'activity_by'=>$updated_userid,

       );

       if(!empty($results)  || ($data_fromdb['status']!=$status))
       {

        $this->User_model->insertuseredithistory($data_for_edithistory); 


        $title='Employee Details';
        InsertEditedData($this->input->post(),$title);
               
       } 
       else if(($data_fromdb['annual_holliday_allowance']!=$annual_holliday_allowance)||($data_fromdb['actual_holiday_allowance']!=$actual_holiday_allowance))
       {

        $this->User_model->insertuseredithistory($data_for_edithistory); 


        $title='Employee Details';
        InsertEditedData($this->input->post(),$title);
               
       } 
     ////------------------------------------

      $dataformstatus['status'] = $status;
      $result1 =$this->User_model->updatestatus($user_id,$dataformstatus);

     if($old_desig!=$designation_id)
     {
      $data=array('user_id' => $user_id,'Previous_designation'=> $old_desig,'Current_designation'=>$designation_id,'Updation_date'=>date('Y-m-d H:i:s'),'Updated_by'=>$this->session->userdata('user_id'),'Status'=>1); 
      $this->Designation_model->insertDesignationchangehistory($data);
     }

      $dataforms=array('remaining_holiday_allowance'=>$remaining_holiday_allowance,'group_id' => $group_id,'weekly_hours' => $weekly_hours,'annual_holliday_allowance' => $annual_holliday_allowance,'annual_allowance_type' => $annual_allowance_type,'actual_holiday_allowance'=>$actual_holiday_allowance,'actual_holiday_allowance_type'=>$actual_holiday_allowance_type,'payroll_id' => $payroll_id,'payment_type' =>$payment_type,'default_shift'=>$default_shift,'designation_id' => $designation_id,'start_date' => $start_date,'final_date'=>$final,'notes' => $notes,'exit_interview'=>$exit_interview,'exit_reason'=>$exit_reason,'updation_date'=>date('Y-m-d H:i:s'),'updation_userid'=>$updated_userid,'hr_ID'=>$hr_id); 
      $this->User_model->insertdatas($user_id,$dataforms);
        $data=array('day_rate'=>$day_rate,'night_rate'=>$night_rate,'day_saturday_rate'=>$saturday_rate,'day_sunday_rate'=>$sunday_rate,'weekend_night_rate'=>$weekend_night_rate,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid); 
        $this->User_model->updateuserrates($user_id,$data);
    }
}

public function insertunit()
{
    if (!$this->session->userdata('user_id')) {
    // Session has expired
        echo json_encode(array('status' => 'error', 'message' => 'Session expired','url' => base_url()));
        return;
    }
  $this->load->model('Unit_model');
  $this->load->model('Moveshift_model');
  $user_id=$this->input->post('user_id'); 
  $unit_id=$this->input->post('unit_id');   
  $parent=$this->User_model->findparent($unit_id);
  $old_unit=$this->Unit_model->findunitidofuser($user_id); 
  $updated_userid=$this->session->userdata('user_id'); 
  //print_r(date('Y-m-d'));exit();
  $date=date('Y-m-d'); 
  $nextSaturday = date('Y-m-d', strtotime('next saturday'));
  $t=date('d-m-Y');
  $day=date("D",strtotime($t));
  if($day=='Sat')
  {
    $date_selected=$date;
  }
  // else if($day=='Sun')
  // {
  //   $date_selected=date('Y-m-d', strtotime($date.'last saturday'));
  // }
  else
  {
    $date_selected=$nextSaturday;
  }  
  //print_r($date_selected);exit();
  $this->User_model->checktimelog($user_id,$date_selected,$old_unit[0]['unit_id'],$unit_id);

  

    if(count($this->checkUserstatus($user_id))!=0)
    {  
        $this->User_model->deleteshiftbyuser($user_id,$date_selected,$old_unit[0]['unit_id']);


        $dataform=array('unit_id' => $unit_id,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid);
        $result =$this->User_model->updateunit($user_id,$dataform);

        $this->User_model->deleteavaialabilityByuser($user_id); // delete availability by userid - aug 17 by swaraj
        $this->User_model->deleteTrainingByuser($user_id); // delete training staff table entry by userid - aug 19 by swaraj


        if($old_unit[0]['unit_id']!=$unit_id)
        {
            $data=array('user_id' => $user_id,'Previous_unit'=> $old_unit[0]['unit_id'],'Current_unit'=>$unit_id,'Updation_date'=>date('Y-m-d H:i:s'),'Updated_by'=>$this->session->userdata('user_id'),'Status'=>2); 
            $this->Unit_model->insertUnitchangehistory($data);

            $title='Unit Details';
            InsertEditedData($this->input->post(),$title);
                
        }

        /* new changes by swaraj on jul 20 unit change ,add x to rotascheule to new units */
        if(date('D', strtotime($date))!='Sun')
        {
            //$nextSunday = date('Y-m-d', strtotime('next sunday'));
            $nextSunday=date('Y-m-d', strtotime($date.'last sunday')); /* previous sunday of the date */
        }
        else
        {
            $nextSunday = $date;
        }

        $saturdayDate=date('Y-m-d', strtotime($nextSunday.'next saturday'));
        $date_array=findDatesBtwnDates($nextSunday,$saturdayDate);
        //print_r($date_array);print '<br>';//exit();
        
              
        $rotas=$this->Rota_model->getRotaForUnitchangeBydateAndunitID($nextSunday,$unit_id,$user_id);
        if($rotas!=0)
        {
           foreach ($rotas as $rota) { //print_r($rota['date']); 



                    if (in_array($rota['date'], $date_array)) {
                       $from_unit = $old_unit[0]['unit_id']; 
                       $request = 2;                     
                     } else {
                        $from_unit = NULL;
                        $request = 0;
                     } 

                   $day=date("D",strtotime($rota['date']));
                   $rotaschedule_data = array(
                                                        'user_id'  => $user_id,
                                                        'shift_id' => 0,
                                                        'shift_hours' => 0,
                                                        'additional_hours' =>NULL,
                                                        'day_additional_hours' =>NULL,
                                                        'night_additional_hours' =>NULL,
                                                        'additinal_hour_timelog_id'=>NULL,
                                                        'comment'=>NULL,
                                                        'from_unit'=>$from_unit,
                                                        'unit_id'=>$unit_id,
                                                        'rota_id'=>$rota['rota_id'],
                                                        'date'=>$rota['date'],
                                                        'status'=>1,
                                                        'creation_date'=>date('Y-m-d H:i:s'),
                                                        'created_userid'=>$params['created_userid'],
                                                        'updation_date'=>date('Y-m-d H:i:s'),
                                                        'updated_userid'=>$this->session->userdata('user_id'),
                                                        'day'=>$day,
                                                        'designation_id'=>NULL,
                                                        'shift_category'=>0,
                                                        'from_userid'=>NULL,
                                                        'from_rotaid'=>NULL,
                                                        'request_id'=>NULL,
                                                        'auto_insert'=>$request
                            );
                    //auto_insert=2 (user unit changed rota),auto_insert=1 (missing rota inserted),
                    //print_r($rotaschedule_data); print '<br>';
                    $check_rota_status=$this->Rota_model->checkRotaByuserDateandID($user_id,$unit_id,$rota['date']);
                    if($check_rota_status==0)
                    {
                        $this->Moveshift_model->inserRotascheduleDetails($rotaschedule_data);
                    }
                    else{}
                    
            }

                    $num=count($rotas)-1; 
                    $admin_details=$this->Moveshift_model->getUsername($this->session->userdata('user_id'));
                    $user_details=$this->Moveshift_model->getUsername($user_id);
                    $unit_name=$this->Moveshift_model->getUnitname($unit_id);
                    //print_r($admin_details); print_r($user_details);exit();

                    $description = $admin_details['fname'].' '.$admin_details['lname'].' '.'has added a rota for '.$user_details['fname'].' '.$user_details['lname'].' '.' from '.' '.$rotas[0]['date'].' '.'to'.' '.$rotas[$num]['date'].'(User unit change- create x entry).'; //print_r($description);exit();

                    $data_activity_log=array(
                                      'description'=>$description,
                                      'activity_date'=>date('Y-m-d H:i:s'),
                                      'activity_by'=>$this->session->userdata('user_id'),
                                      'add_type'=>'Unit change - Create Rota',
                                      'user_id'=>$user_id,
                                      'unit_id'=>$unit_id,
                                      'primary_id'=>$rotas[0]['rota_id'],
                                      'creation_date'=>date('Y-m-d H:i:s')
                      );

                      $this->Activity_model->insertactivity($data_activity_log); 


           // exit();
        }


        /* end */

        $data['error']= '';

    }
    else
    {  
    $dataform=array('unit_id' => $unit_id,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid);
    $result =$this->User_model->updateunit($user_id,$dataform);

    $this->User_model->deleteavaialabilityByuser($user_id); // delete availability by userid - aug 17 by swaraj
    $this->User_model->deleteTrainingByuser($user_id); // delete training staff table entry by userid - aug 19 by swaraj


        if($old_unit[0]['unit_id']!=$unit_id)
        {
            $data=array('user_id' => $user_id,'Previous_unit'=> $old_unit[0]['unit_id'],'Current_unit'=>$unit_id,'Updation_date'=>date('Y-m-d H:i:s'),'Updated_by'=>$this->session->userdata('user_id'),'Status'=>2);
            $this->Unit_model->insertUnitchangehistory($data);

            $title='Unit Details';
            InsertEditedData($this->input->post(),$title);
                
        } 

        /* new changes by swaraj on jul 20 unit change ,add x to rotascheule to new units */
        if(date('D', strtotime($date))!='Sun')
        {
            //$nextSunday = date('Y-m-d', strtotime('next sunday'));
            $nextSunday=date('Y-m-d', strtotime($date.'last sunday')); /* previous sundy of the date */
        }
        else
        {
            $nextSunday = $date;
        }

        //$nextSunday = date('Y-m-d', strtotime('next sunday'));
        $rotas=$this->Rota_model->getRotaForUnitchangeBydateAndunitID($nextSunday,$unit_id,$user_id);
        if($rotas!=0)
        {
           foreach ($rotas as $rota) { //print_r($rota); print '<br>';


                     if (in_array($rota['date'], $date_array)) {
                       $from_unit = $old_unit[0]['unit_id']; 
                       $request = 2;                     
                     } else {
                        $from_unit = NULL;
                        $request = 0;
                     } 


                   $day=date("D",strtotime($rota['date']));
                   $rotaschedule_data = array(
                                                        'user_id'  => $user_id,
                                                        'shift_id' => 0,
                                                        'shift_hours' => 0,
                                                        'additional_hours' =>NULL,
                                                        'day_additional_hours' =>NULL,
                                                        'night_additional_hours' =>NULL,
                                                        'additinal_hour_timelog_id'=>NULL,
                                                        'comment'=>NULL,
                                                        'from_unit'=>$from_unit,
                                                        'unit_id'=>$unit_id,
                                                        'rota_id'=>$rota['rota_id'],
                                                        'date'=>$rota['date'],
                                                        'status'=>1,
                                                        'creation_date'=>date('Y-m-d H:i:s'),
                                                        'created_userid'=>$params['created_userid'],
                                                        'updation_date'=>date('Y-m-d H:i:s'),
                                                        'updated_userid'=>$this->session->userdata('user_id'),
                                                        'day'=>$day,
                                                        'designation_id'=>NULL,
                                                        'shift_category'=>0,
                                                        'from_userid'=>NULL,
                                                        'from_rotaid'=>NULL,
                                                        'request_id'=>NULL,
                                                        'auto_insert'=>$request
                            );
                    //print_r($rotaschedule_data); print '<br>';
                    $check_rota_status=$this->Rota_model->checkRotaByuserDateandID($user_id,$unit_id,$rota['date']);
                    if($check_rota_status==0)
                    {
                        $this->Moveshift_model->inserRotascheduleDetails($rotaschedule_data);
                    }
                    else{}
            }

                    $num=count($rotas)-1; 
                    $admin_details=$this->Moveshift_model->getUsername($this->session->userdata('user_id'));
                    $user_details=$this->Moveshift_model->getUsername($user_id);
                    $unit_name=$this->Moveshift_model->getUnitname($unit_id);
                    //print_r($admin_details); print_r($user_details);exit();

                    $description = $admin_details['fname'].' '.$admin_details['lname'].' '.'has added a rota for '.$user_details['fname'].' '.$user_details['lname'].' '.' from '.' '.$rotas[0]['date'].' '.'to'.' '.$rotas[$num]['date'].'(User unit change- create x entry).'; //print_r($description);exit();

                    $data_activity_log=array(
                                      'description'=>$description,
                                      'activity_date'=>date('Y-m-d H:i:s'),
                                      'activity_by'=>$this->session->userdata('user_id'),
                                      'add_type'=>'Unit change - Create Rota',
                                      'user_id'=>$user_id,
                                      'unit_id'=>$unit_id,
                                      'primary_id'=>$rotas[0]['rota_id'],
                                      'creation_date'=>date('Y-m-d H:i:s')
                      );

                      $this->Activity_model->insertactivity($data_activity_log); 

            //exit();
        }

    $data['error']= '';

    } 
  //}
  echo json_encode($data);
}


public function insertworkschedule()
{
    //print_r($this->input->post());exit();
$this->load->model('User_model'); 
$this->load->model('Workschedule_model');
$user_id=$this->input->post('user_id'); 
$sunday=$this->input->post('sunday'); 
$monday=$this->input->post('monday');
$tuesday=$this->input->post('tuesday');
$wednesday=$this->input->post('wednesday');
$thursday=$this->input->post('thursday');
$friday=$this->input->post('friday');
$saturday=$this->input->post('saturday');
$updated_userid=$this->session->userdata('user_id');
///-------- user workschedule change history
  $data_fromdb=$this->User_model->getUserworkschedule($user_id); //print_r($data_fromdb); print_r('<br>');
  $data_new=array('sunday'=>$sunday,'monday'=>$monday,'tuesday'=>$tuesday,'wednesday'=>$wednesday,'thursday'=>$thursday,'friday'=>$friday,'saturday'=>$saturday); 
  //print_r($data_new); print_r('<br>');// exit();
   $results=array_diff($data_fromdb,$data_new); //print_r($results);//exit();

   $data_new_pre=array('sunday'=>'sunday:'.$sunday,'monday'=>'monday:'.$monday,'tuesday'=>'tuesday:'.$tuesday,'wednesday'=>'wednesday:'.$wednesday,'thursday'=>'thursday:'.$thursday,'friday'=>'friday:'.$friday,'saturday'=>'saturday:'.$saturday); 
   $data_old=array('sunday'=>'sunday:'.$data_fromdb['sunday'],'monday'=>'monday:'.$data_fromdb['monday'],'tuesday'=>'tuesday:'.$data_fromdb['tuesday'],'wednesday'=>'wednesday:'.$data_fromdb['wednesday'],'thursday'=>'thursday:'.$data_fromdb['thursday'],'friday'=>'friday:'.$data_fromdb['friday'],'saturday'=>'saturday:'.$data_fromdb['saturday']);


   $data_for_edithistory=array(
    'user_id'=>$user_id,
    'current_data'=>implode(',', $data_new_pre),
    'previous_data'=>implode(',', $data_old),
    'activity_type'=> "workschedule",
    'activity_date'=>date('Y-m-d H:i:s'),
    'activity_by'=>$updated_userid,

   );

 if (($data_fromdb['sunday']!=$sunday)||($data_fromdb['monday']!=$monday)||($data_fromdb['tuesday']!=$tuesday)||($data_fromdb['wednesday']!=$wednesday)||($data_fromdb['thursday']!=$thursday)||($data_fromdb['friday']!=$friday)||($data_fromdb['saturday']!=$saturday)) 
   {
       $this->User_model->insertuseredithistory($data_for_edithistory); 

        $title='Workschedule';
        InsertEditedData($this->input->post(),$title);
   }
////---

$result=$this->Workschedule_model->checkuser($user_id); 
if($result =='')
{ 
$dataforms=array('user_id'=>$user_id,'sunday' => $sunday,'monday' => $monday,'tuesday' => $tuesday,'wednesday' => $wednesday,'thursday' => $thursday,'friday' => $friday,
'saturdy' => $saturday,'creation_date'=>date('Y-m-d H:i:s'),'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid);
$result =$this->Workschedule_model->insertworks($dataforms);
}
else
{ 
$dataforms=array('sunday' => $sunday,'monday' => $monday,'tuesday' => $tuesday,'wednesday' => $wednesday,'thursday' => $thursday,'friday' => $friday,
'saturdy' => $saturday,'creation_date'=>date('Y-m-d H:i:s'),'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$updated_userid);
$result =$this->Workschedule_model->updateworks($user_id,$dataforms); 

}


// redirect('admin/user');
}



public function checkUserstatus($user_id){
        
        $status = 1;
        $data = array();
        $data['user_id']= $user_id; 
        $result = $this->Rota_model->getUserUnitDetails($data);  
     
       return $result;
       
    }


    public function deleteuser()
    {
      $this->auth->restrict('Admin.User.Delete');
      try{
            $id=$this->uri->segment(4); 
            $rota=$this->Rota_model->RotaByUser($id);
            if(empty($rota))
            {
                        $data = $this->User_model->deleteuser($id);  
                        if($data==1) 
                            $abc=array('user_id'=>'user_id :'.$id);
                            $title='Delete User';
                            InsertEditedData($abc,$title);
                 
                        $this->session->set_flashdata('error',"0");
                        $this->session->set_flashdata('message', 'Deleted successfully.');
                        redirect('admin/manageuser');
            }
            else
            {
                        $this->session->set_flashdata('error',"1");
                        $this->session->set_flashdata('message', 'Cannot delete this user, a rota is already assigned to this user.');
                        redirect('admin/manageuser');
            }
          }
        catch (Exception $e) {
            print_r($e->getMessage());
                              }
        
    }

    public function sendlink()
    {
       $this->load->model('User_model');
       $this->load->helper(array('form','string'));
       $this->load->library('form_validation');
       $id=$this->input->post('id'); 
       $userInfo=$this->User_model->getSingleUser($id);  
       $email=$userInfo[0]['email'];
       $token=$this->User_model->insertToken($email); //print_r($token); exit();
                $supervisor_name='Super Admin';
                $qstring =urlencode($token['key']);  
                $url = base_url() .'users/reset_password/token/'. $qstring; 
                $link = '<a href="' . $url . '">here</a>'; 
                $site_title = 'St Matthews Healthcare:';
                $admin_email=getCompanydetails('from_email');
                $emailSettings = array(
                    'site_title' => $site_title,
                    'from' => $admin_email,
                    'to' => $email,
                    'type' => 'Reset password',
                    'subject' => $site_title.' '.'Reset password', 
                    'url'=>$url,
                    'staff_name'=>$userInfo[0]['fname'].' '.$userInfo[0]['lname'],
                    'supervisor_name'=>'Super admin',
                );  
               // print_r($emailSettings);exit(); 
                $this->load->library('parser');
                $htmlMessage = $this->parser->parse('emails/forgot', $emailSettings, true);
                //die($htmlMessage);exit();
                    // unset($tmp); 
                
                $this->load->helper('mail');
                sendMail($emailSettings,$htmlMessage);
                $message='A password reset link has been sent to the user'.' '.$userInfo[0]['fname'].' '.$userInfo[0]['lname'].'.';
                //print_r($message);exit();
                //$this->session->set_flashdata('message','A password reset link has been sent to the user'.' '.$userInfo[0]['fname'].' '.$userInfo[0]['lname'].'('.$email.').');
                echo json_encode($message);

    }

    // public function finduserbystatus()
    // {
    //     $status=$this->input->post('status');  
    //     $result=$this->User_model->finduserbystatus($status);  
    //     if(empty($result))
    //     { 
    //        $json = []; 
    //     }
    //     else
    //     {
    //         foreach ($result as $row)
    //           {
    //             if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";}
    //              $delete="'".$row['id']."','".$row['fname']."'"; 

    //              if($row['lastlogin_date']!=''){$last_login=date("d/m/Y H:i:s",strtotime($row['lastlogin_date']));
    //                                             } else { $last_login=""; }

    //               if($row['id']==1)
    //               {
    //                 $json[] =array($row['id'],'<a href="user/edituser/'.$row['id'].'">'.$row['fname'].' '.$row['lname'],$row['email'],$row['payroll_id'],$row['payment_type'],$row['unit_name'],$row['group_name'],$row['designation_name'],$last_login,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="user/edituser/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> ');

    //               }
    //               else
    //               {

    //                  $json[] =array($row['id'],'<a href="user/edituser/'.$row['id'].'">'.$row['fname'].' '.$row['lname'],$row['email'],$row['payroll_id'],$row['payment_type'],$row['unit_name'],$row['group_name'],$row['designation_name'],$last_login,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="user/edituser/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> '.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>'.' '.'<a class="Edit" data-id="'.$row['id'].'" href="user/changepassword/'.$row['id'].'" target="_blank" title="Change password"><i class="fas fa-key"></i></a>'.' '.'<a class="photochange" data-id="'.$row['id'].'" href="user/changepicture/'.$row['id'].'" target="_blank" title="Change profile picture"><i class="fas fa-image"></i></a>');
    //               }
    //           } 
    //     }
 
    //       header("Content-Type: application/json");
    //       echo json_encode($json);
    //       exit();

    // }
     public function finduserbyunit()
    {
        $unit=$this->input->post('unit'); 
        $status=$this->input->post('status'); 
        $result=$this->User_model->finduserbyunit($unit,$status);   //print_r($result);exit;
        if(empty($result))
        { 
           $json = []; 
        }
        else
        {
            foreach ($result as $row)
              {
                if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";}
                 $delete="'".$row['id']."','".$row['fname']."'"; 
                if($row['lastlogin_date']!=''){$last_login=date("d/m/Y H:i:s",strtotime($row['lastlogin_date']));
                                                } else { $last_login=""; }
                
                  if($row['id']==1)
                  {
                    $json[] =array($row['id'],'<a href="user/edituser/'.$row['id'].'">'.$row['fname'].' '.$row['lname'],$row['email'],$row['payment_type'],$row['unit_name'],$row['group_name'],$row['designation_code'],'<span style="display:none;">'.$row['lastlogin_date'].'</span>'.$last_login,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="user/edituser/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> ');
                  }
                  else
                  {
                     $json[] =array($row['id'],'<a href="user/edituser/'.$row['id'].'">'.$row['fname'].' '.$row['lname'],$row['email'],$row['payment_type'],$row['unit_name'],$row['group_name'],$row['designation_code'],'<span style="display:none;">'.$row['lastlogin_date'].'</span>'.$last_login,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="user/edituser/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> '.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>'.' '.'<a class="Edit" data-id="'.$row['id'].'" href="user/changepassword/'.$row['id'].'" target="_blank" title="Change password"><i class="fas fa-key"></i></a>'.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Send Password Reset Link" onclick="sendFunction('.$delete.')">'.'<i class="fa fa-external-link-alt"></i>'.' '.'<a class="photochange" data-id="'.$row['id'].'" href="user/changepicture/'.$row['id'].'" target="_blank" title="Change profile picture"><i class="fas fa-image"></i></a>');
                  }
              } 
        }
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
    }


    public function error()
    { 
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/ 
      if($this->session->userdata('user_type') ==2)
      {
            $this->load->view('includes/staffs/header'); 
            $this->load->view("admin/error");  
            $this->load->view('includes/staffs/footer');
      }
      else
      {
            $this->load->view('includes/home_header'); 
            $this->load->view("admin/error");  
            $this->load->view('includes/home_footer');
      }
     
    }

    public function changepassword()
        {
            $this->auth->restrict('Admin.User.Changepassword');
            $data = array();
            $params = array();
            $this->header['title'] = 'St.Matthews Health Care - SMH-Rota';
            //$data['id']=$this->uri->segment(4);  
            //print_r($this->input->post('id'));exit();
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('newpassword', 'new password', 'required|min_length[8]|alpha_numeric|callback_newpassword');
            $this->form_validation->set_rules('confirmpassword', 'confirm password', 'required|matches[newpassword]');
            $this->load->view('includes/home_header');
            if ($this->form_validation->run() == FALSE)
            { 
             
                $data['error']='';
                $data['id']=$this->uri->segment(4);
                $data['user']=$this->User_model->getSingleUser($this->uri->segment(4));  
                $data['status']=0;
                $data['status_message']=0;

                $parent=getParentId($this->session->userdata('unit_id')); //check if main unit is subunit. 
                $status=Checkparent($this->session->userdata('unit_id')); // check logged unit is parent or not

                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                {
                    $this->load->view('admin/user/changepassword', $data);
                }
                else
                //if($this->session->userdata('user_type')!=1)
                {
                    
                    if($status!=0)
                    {   //if logged unit is parent
                       if($this->session->userdata('unit_id')==$data['user'][0]['unit_id']) //user unit is logged unit
                       {
                          $access_status=true;
                       }
                       else if($this->session->userdata('unit_id')==getParentId($data['user'][0]['unit_id'])) //parent unit of user unit is logged unit
                       {
                          $access_status=true;
                       }
                       else
                       {     //otherwice
                           $access_status=false;
                       }

                    }
                    else if($parent!=0)
                    { //if logged unit is subunit

                       if($this->session->userdata('unit_id')==$data['user'][0]['unit_id']) //user unit is logged unit
                       {
                          $access_status=true;
                       }
                       else if($parent==getParentId($data['user'][0]['unit_id']))
                       {
                          $access_status=true;
                       }
                       else
                       {
                          $access_status=false;
                       }

                    }
                    else
                    {

                        if($this->session->userdata('unit_id')==$data['user'][0]['unit_id'])
                        {
                            $access_status=true;
                        }
                        else
                        {
                            $access_status=false;
                        }

                    }


                       if(($access_status==true)&&(CheckPermission($this->session->userdata('user_type'),'Admin.User.Changepassword')=='True'))
                       {  
                          $this->load->view('admin/user/changepassword', $data);
                       }
                       else
                       {  
                          $this->load->view("admin/error",$data);
                       }
                }
                //else
                //{
                //$this->load->view('admin/user/changepassword', $data);
                //}

                
            }
            else
            {
               
                //Getting all input field values
                $password= $this->input->post('newpassword');  
                $id= $this->input->post('user_id'); 
                $userInfo = $this->User_model->getuserDetailsforPasswordchange($id);  
                $dataform=array('password'=>md5($password)); 
                $result =  $this->Profile_model->changepassword($id,$dataform);

                $this->session->set_flashdata('error',"0");
                $message="Password updated successfully";

                $site_title = 'St Matthews Healthcare:';
                $url = $this->config->item('base_url'); 
                $link = '<a href="' . $url . '">Click here</a>'; 
                $admin_email=getCompanydetails('from_email');
                $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
                    if(count($session_user)>0){
                    $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
                    }else{
                    $unit_supervisor_name = '';
                    }
                $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'to' => $userInfo[0]['email'],
                'type' => 'Password changed',
                'password'=>$password,
                'url'=>$link,
                'supervisor_name'=>$unit_supervisor_name,
                'user_name' => $userInfo[0]['fname'].' '.$userInfo[0]['lname'],
                'subject' =>  $site_title.' '.'Password reset.', 
                 );     
            //print_r($emailSettings);exit();
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/Passwordchange', $emailSettings, true);
            //die($htmlMessage); exit();
            // load email helper
            $this->load->helper('mail');
            // send welcome mail
            $result=sendMail($emailSettings, $htmlMessage); 
            //print_r($result);exit(); 
             
            if($result==1)
            {
                $this->session->set_flashdata('error',"0");
                $message .=" and sent to email address  ". $userInfo[0]['email'].".";
                $data['status']=1;
            // $this->session->set_flashdata('error',"0");
            // $this->session->set_flashdata('message', 'Password updated successfully.');
            // $this->session->set_flashdata('message', 'Email send successfully.');
            }
            else
            {
                 $this->session->set_flashdata('error',"1");
                 $message .=", error sending email to  email address ".$userInfo[0]['email'].".";
                 $data['status']=0;
            // $this->session->set_flashdata('error',"0");
            // $this->session->set_flashdata('message', 'Password updated successfully.');
            // $this->session->set_flashdata('error',"1");
            // $this->session->set_flashdata('message', 'Error sending email.'); 
            }
            $data['user_id']=$id;
            $data['user']=$this->User_model->getSingleUser($id);
            $data['status_message']='"'.$message.'"'; 
           // print_r($message);exit();
            $this->session->set_flashdata('messages', $message); 
            $this->load->view('admin/user/changepassword', $data);
            }
            $this->load->view('includes/home_footer');
        }

        public function sendapppassword(){
            
            $password_status   =   $this->input->post('appstatus');
            $user_id   =   $this->input->post('id');
            
            $params= array();
            $params['id'] = $user_id;
            
            
            if($user_id>0 && $password_status>0){
                
               // $this->User_model->sendapppassword($user_id,$password_status);
               
                if($password_status==1){
                    
                    
                    $digits = 4;
                    
                    $params['app_pass']= rand(pow(10, $digits-1), pow(10, $digits)-1);
                    
                    $params['pass_enable'] = $password_status;
                    $user_details = $this->User_model->getSingleUser($user_id); 
                    
                   // print_r($user_details);exit;
                    $mobilenumber = $user_details[0]['mobile_number'];
                    if($mobilenumber){
                        //sms integration
                        $comment = "Your 4-digit password for mobile app is, ".$params['app_pass'];
                        $this->load->model('AwsSnsModel');
                        $sender_id="SMHApp";
                        $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $comment);
                    }
                    
                    $message ="4-digit password sent to user, ". $user_details[0]['fname']." ". $user_details[0]['lname'];
                    
                }
                else {
                    $message ="Password login disabled.";
                    $params['pass_enable'] =null;
                    $params['app_pass']=null;
                }
                
                
                $this->User_model->updateappPassword($params);
                    
                    
                    
            }
            else{
                $message ="Invalid user.";
            }
            echo json_encode($message);
            
        }

         public function newpassword($str)
        {
           if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
             return TRUE;
           }
           $this->form_validation->set_message('newpassword', 'Password must be alphanumeric.');
           return FALSE;
        }

        public function matches(){

        $password   =   $this->input->post('newpassword'); 
        $username   =   $this->input->post('confirmpassword');
        if($password!=$username)
        {
        $this->form_validation->set_message('matches', 'Password missmatch.');
        return false;
        }
        else
        {
            return true;
        }
    
        }


   public function changepicture()
   {
            $this->auth->restrict('Admin.User.Changepicture');
            $data = array();
            $params = array();
            $this->header['title'] = 'St.Matthews Health Care - SMH-Rota';
            $this->load->view('includes/home_header');
            $data['id']=$this->uri->segment(4);
            $data['user']=$this->User_model->getSingleUser($this->uri->segment(4));  
            $data['error']=''; 
            $data['status']=0;

            $parent=getParentId($this->session->userdata('unit_id')); //check if main unit is subunit. 
            $status=Checkparent($this->session->userdata('unit_id')); // check logged unit is parent or not

            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
            {
                $this->load->view('admin/user/changepicture', $data);
            }
            else
            {
                
                if($status!=0)
                {   //if logged unit is parent
                   if($this->session->userdata('unit_id')==$data['user'][0]['unit_id']) //user unit is logged unit
                   {
                      $access_status=true;
                   }
                   else if($this->session->userdata('unit_id')==getParentId($data['user'][0]['unit_id'])) //parent unit of user unit is logged unit
                   {
                      $access_status=true;
                   }
                   else
                   {     //otherwice
                       $access_status=false;
                   }

                }
                else if($parent!=0)
                { //if logged unit is subunit

                   if($this->session->userdata('unit_id')==$data['user'][0]['unit_id']) //user unit is logged unit
                   {
                      $access_status=true;
                   }
                   else if($parent==getParentId($data['user'][0]['unit_id']))
                   {
                      $access_status=true;
                   }
                   else
                   {
                      $access_status=false;
                   }

                }
                else
                {

                    if($this->session->userdata('unit_id')==$data['user'][0]['unit_id'])
                    {
                        $access_status=true;
                    }
                    else
                    {
                        $access_status=false;
                    }

                }


                   if(($access_status==true)&&(CheckPermission($this->session->userdata('user_type'),'Admin.User.Changepicture')=='True'))
                   {  
                      $this->load->view('admin/user/changepicture', $data);
                   }
                   else
                   {  
                      $this->load->view("admin/error",$data);
                   }
            }
            // else
            // {
            //         $this->load->view('admin/user/changepicture', $data);

            // }

            
            $result['js_to_load'] = array('profile/profile.js');
            $this->load->view('includes/home_footer',$result);
   }

   public function photoupload()
   {

                $config['image_library'] = 'gd2';
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpeg|jpg|png';
            
                $this->load->library('Upload', $config);
                //print_r($_FILES['image']['name']);exit();
                if ($_FILES['image']['name'] !='' )  
                {
                $this->upload->do_upload('image');
                $filename = $_FILES['image']['name']; 
                $user_id=$this->uri->segment(4);
                $datahome=array('profile_image'=>$filename);
                $result=$this->User_model->changepicture($user_id,$datahome);
                 if($result=='true')
                {
                   $abc=array('user_id'=>'user_id : '.$user_id,'$filename'=>'image name : '. $filename);
                   $title='Upload Photo';
                   InsertEditedData($abc,$title);
                }
                //$this->session->set_flashdata('error',"0");
                $this->session->set_flashdata('messages', 'Photo updated successfully.');
                }
            $data = array();
            $this->header['title'] = 'St.Matthews Health Care - SMH-Rota';
            $this->load->view('includes/home_header');
            $data['user']=$this->User_model->getSingleUser($this->uri->segment(4));  
            $data['error']=''; 
            $data['status']=1;
            $this->load->view('admin/user/changepicture', $data);
            $result['js_to_load'] = array('profile/profile.js');
            $this->load->view('includes/home_footer',$result);
   }
    public function removeInactiveUsers()
    {
        $this->User_model->removeInactiveUsers();
    }
    public function changeUserUnit()
    {  
        $this->User_model->changeUserUnit();
    }
    public function deleteRotaLockEntries()
    {
        $this->Rota_model->deleteRotaLockEntries();
        return true;
    }
    public function deleteSessionTableEntries()
    {
        $this->Rota_model->deleteSessionTableEntries();
        return true;
    }
    public function userStatusChange()
    {
        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        if($status == 2){
            $this->User_model->userStatusChange($user_id);
        }
    }
    public function updateRotascheduleDayValue()
    {
        $this->Rota_model->updateRotascheduleDayValue();
        return true;
    }
}
?>