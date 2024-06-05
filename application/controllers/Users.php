<?php
if (!defined('BASEPATH'))
    die();

class Users extends CI_Controller {
        
        public function __construct() {
            parent::__construct();
            $this->load->helper('name_helper');
        }
        
        /**
         *
         * customer login
         */
        public $header = array();
        public function login() {
                    
                    
            // if already logged in, then redirect to user home
            if ($this->auth->is_logged_in()) 
            {
               // if ($this->session->userdata('user_type') ==1)
                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                {
                    redirect('admin/manageuser');
                }
                else if ($this->session->userdata('user_type') ==2)
                {
                    redirect('staffs/profile');
                }
                else if ($this->session->userdata('user_type') >=3)
                {
                    $status = get_group_permission_status($this->session->userdata('user_type'),'Profile.View');
                    if($status == 0){
                        redirect('manager/Reports_staff/timelogview');
                    }else{
                        redirect('manager/profile');
                    }
                }
            }
            
            $userInfo = array();
            $params = array();
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() == FALSE) 
            {
                $this->_login();
            } 
            else 
            {
                $params = array('email' => trim($this->input->post('email')),'password' => md5($this->input->post('password')));
                if ($this->auth->login($params)) 
                {
                      
                   // print_r($this->session->userdata('user_type')); exit();
                   // if ($this->session->userdata('user_type') ==1)
                    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                    {
                        redirect('admin/manageuser');
                    }
                    else if ($this->session->userdata('user_type') ==2)
                    {
                        redirect('staffs/profile');
                    }
                    else if ($this->session->userdata('user_type') >=3)
                    {  
                        $status = get_group_permission_status($this->session->userdata('user_type'),'Profile.View');
                        if($status == 0){
                            redirect('manager/Reports_staff/timelogview');
                        }else{
                            redirect('manager/profile');
                        }
                    }
                    else
                    {
                        $this->auth->logout();
                        unset($params);
                        $this->_login(INVALID_LOGIN);
                    }
                }
                unset($params);
                $this->_login(INVALID_LOGIN);
            }
        }
        public function login_old_with_MFA() {
            // if already logged in, then redirect to user home
            if ($this->auth->is_logged_in()) 
            {
               // if ($this->session->userdata('user_type') ==1)
                if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                {
                    redirect('admin/manageuser');
                }
                else if ($this->session->userdata('user_type') ==2)
                {
                    redirect('staffs/profile');
                }
                else if ($this->session->userdata('user_type') >=3)
                {
                    $status = get_group_permission_status($this->session->userdata('user_type'),'Profile.View');
                    if($status == 0){
                        redirect('manager/Reports_staff/timelogview');
                    }else{
                        redirect('manager/profile');
                    }
                }
            }
            
            $userInfo = array();
            $params = array();
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() == FALSE) 
            {
                $this->_login();
            } 
            else 
            {
                $params = array('email' => trim($this->input->post('email')),'password' => md5($this->input->post('password')));
                $authnticate_user = $this->User_model->authenticate_user(trim($this->input->post('email')),md5($this->input->post('password')));
                if(count($authnticate_user) > 0){
                    // Retrieve user's IP address
                    $user_ip = $this->get_client_ip();
                    // Query database to check if IP address exists in ip_addresses table
                    $ip_exists = $this->User_model->check_ip_address($user_ip);
                    if($ip_exists){
                        if ($this->auth->login($params)) 
                        {
                            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                            {
                                redirect('admin/manageuser');
                            }
                            else if ($this->session->userdata('user_type') ==2)
                            {
                                redirect('staffs/profile');
                            }
                            else if ($this->session->userdata('user_type') >=3)
                            {  
                                $status = get_group_permission_status($this->session->userdata('user_type'),'Profile.View');
                                if($status == 0){
                                    redirect('manager/Reports_staff/timelogview');
                                }else{
                                    redirect('manager/profile');
                                }
                            }
                            else
                            {
                                $this->auth->logout();
                                unset($params);
                                $this->_login(INVALID_LOGIN);
                            }
                        }
                        unset($params);
                        $this->_login(INVALID_LOGIN);
                    }else{
                        $mobile_number = $authnticate_user[0]['mobile_number'];
                        if($mobile_number){
                            $user_id = $authnticate_user[0]['id'];
                            $this->User_model->updateOTPLoginStatus($user_id);
                            $otp = $this->generate_otp(6); // Function to generate OTP
                            $otp_send_result = $this->send_otp_sms($user_id);
                            // Assuming mobile number is stored in session
                            //sms integration
                            /*$message = "Your OTP for login SMHRota is:".$otp;
                            $this->load->model('AwsSnsModel');
                            $result = $this->AwsSnsModel->SendOtpTest('+971542452584', '', $message);
                            // Set expiry time (e.g., 5 minutes)
                            $expiry_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                            $dataform=array('user_id' => $user_id,'otp' => $otp,'otp_expiry_time' => $expiry_time,'created_date'=>date('Y-m-d H:i:s'),'updated_date'=>date('Y-m-d H:i:s'),'status' => 0); 
                            $insert_id = $this->User_model->insetOtpLogins($dataform);*/


                            // $otp_id = $this->send_otp_sms($user_id);
                            $encrypted_user_id = openssl_encrypt($user_id, 'AES-256-CBC', 'encryption_key', 0, 'encryption_iv');
                            $encrypted_otp = openssl_encrypt($otp, 'AES-256-CBC', 'encryption_key', 0, 'encryption_iv');
                            // Encode the encrypted user_id and OTP using Base64
                            $encoded_user_id = base64_encode($encrypted_user_id);
                            $encoded_otp = base64_encode($encrypted_otp);
                            redirect('users/Otplogin/' . $encoded_user_id);
                        }else{
                            $this->_login(INVALID_IPLOGIN);
                        }
                    }
                }else{
                    unset($params);
                    $this->_login(INVALID_LOGIN);
                }
            }
        }
        public function updateExpiredOtpRows(){
            $results = $this->User_model->getOtpLogins();
            foreach ($results as $result) {
                $expiry_timestamp = strtotime($result['otp_expiry_time']);
                if (time() > $expiry_timestamp) {
                    $results = $this->User_model->updateExpiredOTP($result['id']);
                }
            }
            return 'true';
        }
        public function send_otp_sms($user_id){
            $user_details = $this->User_model->finduserDetailsWithId($user_id);
            $mobile_number = $user_details[0]['mobile_number'];

            // 3. Send OTP to Registered Mobile Number if IP Address Not Found
            $otp = $this->generate_otp(6); // Function to generate OTP
            // Assuming mobile number is stored in session
            //sms integration
            $message = "Your OTP for login SMHRota is: $otp";
            $sender_id="SMHOTP";
            $this->load->model('AwsSnsModel');
            $result = $this->AwsSnsModel->SendSms($mobile_number, $sender_id, $message);
            // Set expiry time (e.g., 2 minutes)
            $expiry_time = date('Y-m-d H:i:s', strtotime('+2 minutes'));

            $dataform=array('user_id' => $user_id,'otp' => $otp,'otp_expiry_time' => $expiry_time,'created_date'=>date('Y-m-d H:i:s'),'updated_date'=>date('Y-m-d H:i:s'),'status' => 0); 
            $insert_id = $this->User_model->insetOtpLogins($dataform);
            return $insertd_id;
        }
        public function resendOtp(){
            $user_id_before_encryption = $this->input->post('user_id');
            $user_id = $this->input->post('user_id');
            $encrypted_user_id = base64_decode($this->input->post('user_id'));
            $user_id = openssl_decrypt($encrypted_user_id, 'AES-256-CBC', 'encryption_key', 0, 'encryption_iv');
            $this->User_model->updateOTPLoginStatus($user_id);
            $otp_id = $this->send_otp_sms($user_id);
            echo json_encode(array('status'=> true,'user_id'=>$user_id_before_encryption));
            exit();
        }
        public function verify_otp() {
            // Get the submitted OTP and expiry time from the session
            $submitted_otp = $this->input->post('otp');
            $encrypted_user_id = base64_decode($this->input->post('user_id'));
            
            $user_id = openssl_decrypt($encrypted_user_id, 'AES-256-CBC', 'encryption_key', 0, 'encryption_iv');
            $otp_result = $this->User_model->checkValidOTP($user_id,$submitted_otp);
            // print("<pre>".print_r(strtotime($otp_result[0]['otp_expiry_time']),true)."</pre>");exit();
            $user_details = $this->User_model->finduserDetailsWithId($user_id);
            if(count($otp_result) > 0){
                $expiry_timestamp = strtotime($otp_result[0]['otp_expiry_time']);
                $result = $this->User_model->updateOTPLogin($user_id,$submitted_otp);
                if (time() > $expiry_timestamp) {
                    // OTP has expired
                    // Display an error message to the user
                    $status = 1;
                    redirect('users/Otplogin/'.$this->input->post('user_id').'/'.$status);
                }else{
                    $params = array('email' => trim($user_details[0]['email']),'password' => $user_details[0]['password']);
                    if ($this->auth->login($params)) 
                    {
                        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
                        {
                            redirect('admin/manageuser');
                        }
                        else if ($this->session->userdata('user_type') ==2)
                        {
                            redirect('staffs/profile');
                        }
                        else if ($this->session->userdata('user_type') >=3)
                        {  
                            $status = get_group_permission_status($this->session->userdata('user_type'),'Profile.View');
                            if($status == 0){
                                redirect('manager/Reports_staff/timelogview');
                            }else{
                                redirect('manager/profile');
                            }
                        }
                        else
                        {
                            $this->auth->logout();
                            unset($params);
                            $this->_login(INVALID_LOGIN);
                        }
                    }
                }
            }else{
                $this->session->set_flashdata('error_message', 'Invalid OTP. Please try again.');
                $status = 0;
                redirect('users/Otplogin/'.$this->input->post('user_id').'/'.$status);
            }
        }
        public function mask_phonenumber($mobile_number){
            // Get the first two digits
            $prefix = substr($mobile_number, 0, 2);

            // Get the last three digits
            $suffix = substr($mobile_number, -3);

            // Mask the middle digits
            $masked_middle = str_repeat('*', strlen($mobile_number) - 5);

            // Construct the masked mobile number
            $masked_number = $prefix . $masked_middle . $suffix;

            return $masked_number; // Output: 07*************962
        }
        public function Otplogin($encoded_user_id = null, $status = null) {
            // Decrypt the user_id and OTP

            // Decode the Base64 encoded strings to get the encrypted data
            $encrypted_user_id = base64_decode($encoded_user_id);
            $encrypted_otp = base64_decode($encoded_otp);
            $user_id = openssl_decrypt($encrypted_user_id, 'AES-256-CBC', 'encryption_key', 0, 'encryption_iv');
            $otp = openssl_decrypt($encrypted_otp, 'AES-256-CBC', 'encryption_key', 0, 'encryption_iv');
            $user_details = $this->User_model->finduserDetailsWithId($user_id);
            $result = array();
            $this->header['title'] = 'Login';
            $result['error'] = $status;
            $result['error_status'] = $status;
            $result['stored_otp'] = $otp;
            $result['user_id'] = $encoded_user_id;
            $result['otp_mobilenumber'] = $this->mask_phonenumber($user_details[0]['mobile_number']);
            $result['css_to_load'] = array( );
            $this->load->view('includes/header',$result);
            $this->load->view("admin/login/otp_login",$result);
            $result['js_to_load'] = array('user/otp_login.js');
            $this->load->view('includes/footer',$result);
        }
        public function generate_otp($length)
        {
            $otp = '';
            $characters = '0123456789'; // Characters to use for OTP

            $max = strlen($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $otp .= $characters[mt_rand(0, $max)];
            }
            return $otp;
        }
        public function get_client_ip() {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }
        /**
         *
         * render login view
         */
        private function _login($status = null) {
            $result = array();
            $this->header['title'] = 'Login';
            $result['error'] = $status;
       
            $result['css_to_load'] = array( );
            $this->load->view('includes/header',$result);
            $this->load->view("admin/login/login");
            $result['js_to_load'] = array();
            $this->load->view('includes/footer',$result);
        }
         public function forgotpassword()
    { 
            $this->load->model('User_model');
            $this->load->model('Leave_model');
            $this->load->helper(array('form','string'));
            $this->load->library('form_validation');
            
            $data = array();
            $this->header['title'] = 'St.Matthews Health Care - SMH-Rota';
            $this->load->view('includes/header', $this->header);
            
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');  
            if($this->form_validation->run() == FALSE) 
            { 
                $message = '';                     
                $message = '<strong>Enter your email id</strong><br>';
             $this->load->view('admin/login/forgot');
            }
            else
            { 
                $email = $this->input->post('email'); 
                $data=$this->User_model->getEmail($email);
                $dcount=count($data); 
                if($dcount > 0)
                { 
                $this->load->helper('mail');
                $clean = $this->security->xss_clean($email);
                $userInfo = $this->User_model->getUserInfoByEmail($clean);   
                $userdetails=$this->User_model->getUserInfoUsingEmail($email);   
                $token = $this->User_model->insertToken($email); 
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
                    'type' => 'Forgot password',
                    'subject' => $site_title.' '.'Forgot password',
                    'from_email'=>$email,
                    'url'=>$url,
                    'staff_name'=>$userdetails[0]['fname'].' '.$userdetails[0]['lname'],
                    'supervisor_name'=>'Super admin',
                );   
               /*  $message = '';                     
                $message .= '<strong>A password reset has been requested for this email account</strong><br>';
                $message .= '<strong>Please click:</strong> ' . $link;  */     
           
                //$htmlMessage = $this->load->view('emails/forgot',$emailSettings, true);
                $this->load->library('parser');
                $htmlMessage = $this->parser->parse('emails/forgot', $emailSettings, true);
                //die($htmlMessage);exit();
                    // unset($tmp); 
                
                $this->load->helper('mail');
                sendMail($emailSettings,$htmlMessage);
                $this->session->set_flashdata('message','A password reset link has been sent to your email address. Please click on the link to complete the reseting process');
                redirect('adminlogin','refresh'); 
                }
                else
                {  
                $this->session->set_flashdata('message','Please enter a valid email-ID');
                redirect('forgotpassword','refresh'); 
                }
    
            } 
        $this->load->view('includes/footer');
    }
      public function reset_password()
        {           
            $this->load->model('User_model');
            $token =urldecode($this->uri->segment(4)); 
            $cleanToken = $this->security->xss_clean($token);  
            $user_info = $this->User_model->isTokenValid($cleanToken);  
            //print_r($user_info);exit();
            if(!$user_info)
            {
                $this->session->set_flashdata('message', 'Token is invalid or expired');
                redirect(site_url().'users/login');
            }  
            else
            {
              $data['user'] = array('email'=>$user_info[0]['email'],'key'=>urlencode($user_info[0]['key']),'user_id'=>$user_info[0]['id']); 
              // print_r($data);exit();
                $this->header['title'] = 'St.Matthews Health Care - SMH-Rota';
                $this->load->view('includes/header', $this->header);
                $this->session->set_flashdata('message', 'Please enter your password here.'); 
                $this->load->view('admin/user/resetpassword', $data);
                $data['js_to_load'] = array();
                $this->load->view('includes/footer',$data);
            }
        }
        public function resetpassword()
        {
            $data = array();
            $params = array();
         
            $this->header['title'] = 'St.Matthews Health Care - SMH-Rota';
            $this->load->helper('form');
            $this->load->library('form_validation');

         
            $this->form_validation->set_rules('newpassword', 'new password', 'required|min_length[8]|alpha_numeric|callback_newpassword');
            //$this->form_validation->set_rules('password', 'Password', 'required|matches[passconf]|min_length[8]|alpha_numeric|callback_password_check');

            //$this->form_validation->set_rules('confirmpassword', 'confirm password', 'required');
            $this->form_validation->set_rules('confirmpassword', 'confirm password', 'required|matches[newpassword]');
            $this->form_validation->set_rules('terms_and_conditions', 'terms and conditions', 'callback_terms_and_conditions');
            //$this->form_validation->set_rules('accept_terms', '...', 'callback_accept_terms');
            $this->load->view('includes/header', $this->header);
            if ($this->form_validation->run() == FALSE)
            {
            $data['error']='';
            $data['user']=array('key'=>$this->input->post('token'),'user_id'=>$this->input->post('user_id'));  
            $this->load->view('admin/user/resetpassword', $data);
            }
            else
            {
            // print_r('hello');exit();
                $this->load->model('User_model'); 
                //print_r($this->input->post());exit();
                $pwd = md5($this->input->post('newpassword'));   
                $token =  $this->input->post('token'); 
                $key['key']=$token; 
                $user_id=$this->input->post('user_id');
                $start_date=date('Y-m-d H:i:s'); 
                //$datahome=array('user_id'=>$user_id,'mail_send_status'=>1,'password_change_status'=>1,'updation_date'=>$start_date);

                 if($this->input->post('terms_and_conditions')==1)
                {  
                    //print_r($this->User_model->updatesPassword($pwd,$token));exit();
                    if(!$this->User_model->updatesPassword($pwd,$token))
                    {
                        $this->User_model->user_email_send_update($user_id);
                        $this->session->set_flashdata('message', 'Your password has been updated. You may now login.');
                        
                    }
                    redirect(site_url().'users/login'); 
                }        
            }
            $data['js_to_load'] = array();
            $this->load->view('includes/footer',$data);
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

        // public function has_match(){

        // $terms_and_conditions   =   $this->input->post('terms_and_conditions.');  
        // if($terms_and_conditions!=1)
        // {
        // $this->form_validation->set_message('has_match', 'Please accept terms and conditions.');
        // return false;
        // }
        // else
        // {
        //     return true;
        // }
        // }

        function terms_and_conditions() 
        {
            if (isset($_POST['terms_and_conditions']))
            {
             return true; 
            }
            else
            {
            $this->form_validation->set_message('terms_and_conditions', 'Please read and accept our terms and conditions.');
           return false;
             }
        }
        public function shiftAprove(){
            $user_id  = $this->uri->segment(3);
            $rota_ids = $this->uri->segment(4);
            parse_str($rota_ids,$rota_ids_array);
            $count = count($rota_ids_array);
            $final_result = array();
            $rotaid_string = "";
            $j = 0;
            for($i=0;$i<$count;$i++)
            { 
                $j++;
                $rotaid_string .= $rota_ids_array[$i];
                if($j<$count){
                   $rotaid_string .=',' ;
                }
            }
            $this->load->model('Shift_model');
            $result = array();
            $this->load->view('includes/staffs/header_rota');
            $user_shift_details['user_details'] = $this->Shift_model->getSameUserShiftDetailsForMail($user_id,$rota_ids_array);
            $unit_deatils = $user_shift_details['user_details'][0]['Week'][0];
            $user_shift_details['user_id']= $user_id;
            $user_shift_details['unit_id'] = $unit_deatils['unit_id'];
            $user_shift_details['unit_name'] = $unit_deatils['unit_name'];
            $user_shift_details['from_unit'] = $unit_deatils['from_unit'];
            $user_shift_details['rota_id'] = $rotaid_string;
            $this->load->view("admin/rota/list_multipleweek_rota",$user_shift_details);
            $result['js_to_load'] = array('user/shift.js');
            $this->load->view('includes/staffs/footer_rota',$result);
        }
            public function manageShift(){
                $this->load->model('Shift_model');
                $this->load->model('Leave_model');
            $user_id = $this->input->post("user_id");
            $status  = $this->input->post("status");
            $unit_id = $this->input->post("unit_id");
            $from_unit = $this->input->post("from_unit");
            $rota_ids = $this->input->post("rota_ids");
            $rota_ids_array = explode(',', $rota_ids);
            if($status == 1){
                $message = "approved";
            }else{
                $message = "rejected";
            }
            $actionStatus = $this->Shift_model->approveRejectShifts($user_id,$status,$rota_ids_array);
            $user_shift_details = $this->Shift_model->getSameUserShiftDetailsForMail($user_id,$rota_ids_array);
            $unit_deatils = $user_shift_details[0]['Week'][0];
            $staff_name = $unit_deatils['fname'].' '.$unit_deatils['lname'];
            $unit_supervisor_same_unit = $this->Leave_model->getUsersFromUnit($unit_id);
            if(count($unit_supervisor_same_unit)>0){
              $supervisor_name = $unit_supervisor_same_unit[0]['fname'].' '.$unit_supervisor_same_unit[0]['lname'];
              $supervisor_email = $unit_supervisor_same_unit[0]['email'];
              $unit_supervisor_other_unit = $this->Leave_model->getUsersFromUnit($from_unit);
              if(count($unit_supervisor_other_unit)>0){
                $other_supervisor_email = $unit_supervisor_other_unit[0]['email'];
              }else{
                $other_supervisor_email = '';
              }
              $subject = $staff_name.' has ' .$message. ' shift for the following days';
              $site_title = 'St Matthews Healthcare - SMH Rota';
              $admin_email=getCompanydetails('from_email');
              $emailSettings = array();
              $emailSettings = array(
                  'from' => $admin_email,
                  'site_title' => $site_title,
                  'site_url' => $this->config->item('base_url'),
                  'to' => $supervisor_email,
                  'cc'=> $other_supervisor_email,
                  'type' => 'Shift Allocation- approve/reject',
                  'staff_name' => $staff_name,
                  'supervisor_name' => $supervisor_name,
                  'data' => $user_shift_details,
                  'subject' => $subject,
                  'content_title'=>'We are glad to have you!',
              );
              $this->load->library('parser');
              $htmlMessage = $this->parser->parse('emails/shift_approve_reject_email', $emailSettings, true);
              //die($htmlMessage);exit();
              $this->load->helper('mail');
              sendMail($emailSettings, $htmlMessage);
              header('Content-Type: application/json');
              echo json_encode(array('status' => 1,'message' => "Mail sending successfull"));
                exit();
            }else{
              header('Content-Type: application/json');
              echo json_encode(array('status' => 1,'message' => "Mail sending failed"));
                  exit();
            }
        }
 
   
        /**
         * remove
         * protected function to set session
         * @param (array) $userInfo
         */
        protected function setSession($userInfo = array()) {
            // store this to session
            $user = array(
                'email' => $userInfo['email'],
                'user_id' => $userInfo['user_id'],
                'user_type' => $userInfo['user_type'],
                'islogin' => true,
            );
            // store customer dumpster data to session
            $this->session->set_userdata($user);
            return true;
        }
        
      public function terms_and_condition()
      {
            $result = array();
            $this->header['title'] = 'Terms And Conditions'; 
       
            $result['css_to_load'] = array( );
            $this->load->view('includes/header',$result);
            $this->load->view("admin/login/terms_and_conditions");
            $result['js_to_load'] = array();
            $this->load->view('includes/footer',$result);


      }

        public function logout() {
            /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
            $this->load->model('Rota_model');
            $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
            /*End*/
            $this->ci =& get_instance();
            $this->ci->session->sess_destroy();
            unset(
                $_SESSION['user_type'],
                $_SESSION['user_id'],
                $_SESSION['islogin']
                );
            $this->auth->logout();
        }
        public function import_tables()
        {
            $this->load->model('User_model');
            $users = $this->User_model->get_coventry_users();
            $myfile = fopen("existing_email.txt", "w");
            foreach($users as $user){
                $c_user_id = $user['id'];
                if($user['id'] != 1){
                    $user_info = $this->User_model->check_existing_user($user['email']);
                    if(count($user_info) == 0){
                        $status = $this->import_new_tables($c_user_id,$user);
                    }else{
                        $splitted_string = strtolower(substr($user_info[0]['email'], 0, 6));
                        if($splitted_string == 'agency'){
                            $status = $this->import_new_tables($c_user_id,$user);
                        }else{
                            $existing_emails = $user_info[0]['email']."\n";
                            fwrite($myfile, $existing_emails);
                        }
                    }
                }
            }
            return true;
        }
        public function import_new_tables($c_user_id,$user){
            $params = array();
            $user_rates_params = array();
            $personal_details_params = array();
            $params['email'] = $user['email'];
            $params['password'] = $user['password'];
            $params['group_id'] = $user['group_id'];
            $params['designation_id'] = $user['designation_id'];
            $params['payment_type'] = $user['payment_type'];
            $params['status'] = $user['status'];
            $params['weekly_hours'] = $user['weekly_hours'];
            $params['annual_holliday_allowance'] = $user['annual_holliday_allowance'];
            $params['annual_allowance_type'] = $user['annual_allowance_type'];
            $params['actual_holiday_allowance'] = $user['actual_holiday_allowance'];
            $params['actual_holiday_allowance_type'] = $user['actual_holiday_allowance_type'];
            $params['remaining_holiday_allowance'] = $user['remaining_holiday_allowance'];
            $params['payroll_id'] = $user['payroll_id'];
            $params['default_shift'] = $user['default_shift'];
            $params['thumbnail'] = $user['thumbnail'];
            $params['start_date'] = $user['start_date'];
            $params['final_date'] = $user['final_date'];
            $params['notes'] = $user['notes'];
            $params['creation_date'] = $user['creation_date'];
            $params['updation_date'] = $user['updation_date'];
            $params['reset_password'] = $user['reset_password'];
            $params['key'] = $user['key'];
            $params['temp_email'] = $user['temp_email'];
            $params['lastlogin_date'] = $user['lastlogin_date'];
            $params['unit_change_date'] = $user['unit_change_date'];
            $params['to_unit'] = $user['to_unit'];
            $params['pass_enable'] = $user['pass_enable'];
            $params['app_pass'] = $user['app_pass'];
            $params['user_size_session'] = $user['user_size_session'];
            $params['exit_interview'] = $user['exit_interview'];
            $params['exit_reason'] = $user['exit_reason'];
            $params['hr_ID'] = $user['hr_ID'];
            $inserted_id = $this->User_model->insert_coventry_users($params);
            $c_user_rates = $this->User_model->get_coventry_user_rates($c_user_id);
            $c_personal_details = $this->User_model->get_coventry_personal_details($c_user_id);
            $user_rates_params['user_id'] = $inserted_id;
            $user_rates_params['day_rate'] = $c_user_rates[0]['day_rate'];
            $user_rates_params['night_rate'] = $c_user_rates[0]['night_rate'];
            $user_rates_params['day_saturday_rate'] = $c_user_rates[0]['day_saturday_rate'];
            $user_rates_params['day_sunday_rate'] = $c_user_rates[0]['day_sunday_rate'];
            $user_rates_params['weekend_night_rate'] = $c_user_rates[0]['weekend_night_rate'];
            $user_rates_params['updation_date'] = $c_user_rates[0]['updation_date'];
            $user_rates_params['updated_userid'] = $c_user_rates[0]['updated_userid'];

            $user_rates_status = $this->User_model->insert_coventry_user_rates($user_rates_params);

            $personal_details_params['user_id'] = $inserted_id;
            $personal_details_params['fname'] = $c_personal_details[0]['fname'];
            $personal_details_params['gender'] = $c_personal_details[0]['gender'];
            $personal_details_params['mobile_number'] = $c_personal_details[0]['mobile_number'];
            $personal_details_params['telephone'] = $c_personal_details[0]['telephone'];
            $personal_details_params['profile_image'] = $c_personal_details[0]['profile_image'];
            $personal_details_params['NINnumbers'] = $c_personal_details[0]['NINnumbers'];
            $personal_details_params['dob'] = $c_personal_details[0]['dob'];
            $personal_details_params['address1'] = $c_personal_details[0]['address1'];
            $personal_details_params['address2'] = $c_personal_details[0]['address2'];
            $personal_details_params['address3'] = $c_personal_details[0]['address3'];
            $personal_details_params['address4'] = $c_personal_details[0]['address4'];
            $personal_details_params['city'] = $c_personal_details[0]['city'];
            $personal_details_params['country'] = $c_personal_details[0]['country'];
            $personal_details_params['status'] = $c_personal_details[0]['status'];
            $personal_details_params['Ethnicity'] = $c_personal_details[0]['Ethnicity'];
            $personal_details_params['postcode'] = $c_personal_details[0]['postcode'];
            $personal_details_params['visa_status'] = $c_personal_details[0]['visa_status'];
            $personal_details_params['kin_name'] = $c_personal_details[0]['kin_name'];
            $personal_details_params['kin_phone'] = $c_personal_details[0]['kin_phone'];
            $personal_details_params['kin_address'] = $c_personal_details[0]['kin_address'];
            $personal_details_params['kin_address1'] = $c_personal_details[0]['kin_address1'];
            $personal_details_params['kin_address2'] = $c_personal_details[0]['kin_address2'];
            $personal_details_params['kin_address3'] = $c_personal_details[0]['kin_address3'];
            $personal_details_params['kin_postcode'] = $c_personal_details[0]['kin_postcode'];
            $personal_details_params['kin_relationship'] = $c_personal_details[0]['kin_relationship'];
            $personal_details_params['kin_email'] = $c_personal_details[0]['kin_email'];
            $personal_details_params['kin_telephone'] = $c_personal_details[0]['kin_telephone'];
            $personal_details_params['bank_account'] = $c_personal_details[0]['bank_account'];
            $personal_details_params['bank_sortcode'] = $c_personal_details[0]['bank_sortcode'];
            $personal_details_params['jobcode'] = $c_personal_details[0]['jobcode'];
            $personal_details_params['creation_date'] = $c_personal_details[0]['creation_date'];
            $personal_details_params['updation_date'] = $c_personal_details[0]['updation_date'];
            $personal_details_params['updated_userid'] = $c_personal_details[0]['updated_userid'];
            $personal_details_params['updated_userid'] = $c_personal_details[0]['updated_userid'];
            $personal_details_params['title'] = $c_personal_details[0]['title'];
            $personal_details_params['taxcode'] = $c_personal_details[0]['taxcode'];

            $personal_details_status = $this->User_model->insert_coventry_personal_details($personal_details_params);
            return true;
        }
        
    }
    
    /* End of file login.php */
    /* Location: ./application/controllers/customer/auth.php */
    