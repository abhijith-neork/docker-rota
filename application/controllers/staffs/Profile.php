<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Profile extends CI_Controller {
		  /**
     * Get All Data from this method.
     *
     * @return Response
    */
	    public function __construct() {
			Parent::__construct();
			if ($this->session->userdata('user_type') !=2){
			    $this->auth->logout();
			    
			    unset($params);
			    $this->_login(INVALID_LOGIN);
			}
			 
			//Loading company model
			$this->load->model('User_model');
			$this->load->model('Company_model');
			$this->load->model('Profile_model');
			$this->load->model('Unit_model');
			$this->load->helper('form');
	    }
	    public $header = array();


	    public function index()
	    { 
	    	$this->auth->restrict('Profile.View');
	    	$data = array();        	
			$this->load->helper('form');
			$id= $this->session->userdata('user_id'); 
			$data = array();
        	$data['home']['image']='';
			//Calling function getCompanyDetails from company model for fetching company details
			$data['user']=$this->Profile_model->findusers($id);   
	        $data['unit']=$this->Profile_model->findunitdetails($id);
	        $data['country']=$this->User_model->findcountry();
	        $data['ethnicity']=$this->User_model->findethnicity(''); 
	        //print_r($data['ethnicity']);exit();
			
			//print_r($this->input->post());exit();
		 
			// title_slug = $this->uri->segment(4);
			$this->load->helper('form');
			$this->load->library('form_validation');
			//Validating all required fields
			 
			//$this->form_validation->set_rules('firstName', 'firstname', 'required');
        	//$this->form_validation->set_rules('lastName', 'lastname', 'required');
        	$this->form_validation->set_rules('emailAddress', 'emailAddress', 'required');
        	$this->form_validation->set_rules('phone_number', 'phone_number', 'required');
        	//$this->form_validation->set_rules('dob', 'date of birth', 'required');
        	$this->form_validation->set_rules('address1', 'address1', 'required'); 
        	$this->form_validation->set_rules('wcountry2', 'country', 'required');
        	$this->form_validation->set_rules('wcity2', 'city', 'required');
        	$this->form_validation->set_rules('postcode', 'postcode', 'required'); 
        	$this->form_validation->set_rules('kin_name', 'kin name', 'required');
        	$this->form_validation->set_rules('kin_phone', 'kin phone', 'required');
        	$this->form_validation->set_rules('kin_address', 'kinn address', 'required'); 
			$this->load->view('includes/staffs/header');
			if ($this->form_validation->run() == FALSE)
			{
			// print('hiii');exit();
			    $data['error']=validation_errors();
			    $this->load->view('staffs/profile/editprofile', $data);
			}
			else
			{ 
    //             $config['image_library'] = 'gd2';
	   //          $config['upload_path'] = './uploads/';
			 //    $config['allowed_types'] = 'jpeg|jpg|png';
			
				// $this->load->library('Upload', $config);
			 

			
				//  if ($_FILES['image']['name'] !='' )  
	   //          {
	   //                  $this->upload->do_upload('image');
	   //                  $filename = $_FILES['image']['name']; 
				//     //Getting all input field values
				//         $firstname= $this->input->post('firstName'); 
	   //                  $lastname= $this->input->post('lastName');
	   //                  $profile_image= $filename; 
	   //                  $email= $this->input->post('emailAddress');
	   //                  $phone_number= $this->input->post('phone_number');
	   //                  $dob= $this->input->post('dob');
	   //                  $gender= $this->input->post('gender');
	   //                  $address1= $this->input->post('address1');
	   //                  $address2= $this->input->post('address2');
	   //                  $country= $this->input->post('wcountry2');
	   //                  $city= $this->input->post('wcity2');
	   //                  $postcode= $this->input->post('postcode');
	   //                  $kin_name=$this->input->post('kin_name');
				// 	    $kin_phone=$this->input->post('kin_phone');
				// 	    $kin_address=$this->input->post('kin_address');
				// 	    $id= $this->session->userdata('user_id');
				// 	    $userdetails=$this->Profile_model->finduserdetails($id,$profile_image); 
				// 	    $address_from_unit=$this->User_model->findaddress($id);   
				// 		if($address_from_unit[0]['address1']!=$address1)
				// 		{  
				// 		    $address=$address1.','.$city.','.$country.','.$postcode;
				// 		    $datas=array('user_id'=>$id,'address'=>$address,'kin_name'=>$kin_name,'kin_phonenumber'=>$kin_phone,'kin_address'=>$kin_address,'changed_date'=>date('Y-m-d H:i:s'));  
				// 		    $this->User_model->insertAddresshistory($datas);
				// 		} 
	                    
	   //                  if($this->checkEmail($email,$id)==0) //checking email existance
	   //                  { 
	   //                    $this->session->set_flashdata('message', 'Email already exist.'); 
	   //                    $this->load->view('staffs/profile/editprofile', $data); 

	   //                  }
	  	// 					else
	  	// 				{
	   //                  $dataformuser['email'] = $email;
	   //                  $result =$this->Profile_model->updatestaffdatas($id,$dataformuser);
				    
				// 	    //Creating an array with new values
				// 	    $datahome=array('fname' => $firstname,'lname'=>$lastname,'mobile_number'=>$phone_number,'dob'=>$dob,'gender'=>$gender,'address1'=>$address1,'address2'=>$address2,'country'=>$country,'city'=>$city,'postcode'=>$postcode,'profile_image'=>$filename,'kin_name'=>$kin_name,'kin_phone'=>$kin_phone,'kin_address'=>$kin_address,'email'=>$email); 
				// 	    $difference = array_diff($datahome,$userdetails[0]); //finding difference

				// 	    $datas=array('fname' => $firstname,'lname'=>$lastname,'mobile_number'=>$phone_number,'dob'=>$dob,'gender'=>$gender,'address1'=>$address1,'address2'=>$address2,'country'=>$country,'city'=>$city,'postcode'=>$postcode,'profile_image'=>$filename,'kin_name'=>$kin_name,'kin_phone'=>$kin_phone,'kin_address'=>$kin_address,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$id); 
				// 	    $result =  $this->Profile_model->userupdate($id,$datas);  
				// 		}
				// }
				// else
				// {

					 	$firstname= $this->input->post('firstName'); 
	                    $lastname= $this->input->post('lastName');
	                    $email= $this->input->post('emailAddress');
	                    $phone_number= $this->input->post('phone_number');
	                    $date_daily= $this->input->post('dob');
	                    $old_date = explode('/', $date_daily); 
						$new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
						$dob=$new_data;
	                    //print_r($dob);exit();
	                    $gender= $this->input->post('gender');
	                    $address1= $this->input->post('address1');
	                    $address2= $this->input->post('address2');
	                    $country= $this->input->post('wcountry2');
	                    $city= $this->input->post('wcity2');
	                    $postcode= $this->input->post('postcode');
	                    $kin_name=$this->input->post('kin_name');
					    $kin_phone=$this->input->post('kin_phone');
					    $kin_address=$this->input->post('kin_address');
					    $ethnicity=$this->input->post('ethnicity');
					    $new_ethinicity=$this->findethnicitynew($ethnicity);


					    $userdetails=$this->Profile_model->finduserdetails($id,'');  
					    $id= $this->session->userdata('user_id'); 
					    if(count($this->Profile_model->findhistory($id))=='')
					    {
					    	$address=$address1.','.$city.','.$country.','.$postcode;
						    $datas=array('user_id'=>$id,'address'=>$address,'kin_name'=>$kin_name,'kin_phonenumber'=>$kin_phone,'kin_address'=>$kin_address,'changed_date'=>date('Y-m-d H:i:s'));   
					    }
					    else
					    {
					    	$address_from_unit=$this->User_model->findaddress($id);   
							if($address_from_unit[0]['address1']!=$address1)
							{  
							    $address=$address1.','.$city.','.$country.','.$postcode;
							    $datas=array('user_id'=>$id,'address'=>$address,'kin_name'=>$kin_name,'kin_phonenumber'=>$kin_phone,'kin_address'=>$kin_address,'changed_date'=>date('Y-m-d H:i:s'));  
							} 
					    }
					    $this->User_model->insertAddresshistory($datas);
	                    
	                    if($this->checkEmail($email,$id)==0) //checking email existance
	                    {   
	                      $this->session->set_flashdata('message', 'Email already exist.'); 
	                      $this->load->view('staffs/profile/editprofile', $data); 
	                    }
	  						else
	  					{
	                    $dataformuser['email'] = $email;
	                    $result =$this->Profile_model->updatestaffdatas($id,$dataformuser);
				    
					    //Creating an array with new values
					    $datahome=array('fname' => $firstname,'lname'=>$lastname,'mobile_number'=>$phone_number,'address1'=>$address1,'address2'=>$address2,'country'=>$country,'city'=>$city,'postcode'=>$postcode,'kin_name'=>$kin_name,'kin_phone'=>$kin_phone,'kin_address'=>$kin_address,'email'=>$email);  
					    $difference = array_diff($datahome,$userdetails[0]);   //finding difference

					    $datas=array('fname' => $firstname,'lname'=>$lastname,'mobile_number'=>$phone_number,'address1'=>$address1,'address2'=>$address2,'country'=>$country,'city'=>$city,'postcode'=>$postcode,'kin_name'=>$kin_name,'kin_phone'=>$kin_phone,'kin_address'=>$kin_address,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$id); 
					    $result =  $this->Profile_model->userupdate($id,$datas);  
					     }
				  
				// }
				   // $mobile_number=$this->Profile_model->findAdminmobilenum($id);
				   // $to_email=$this->Profile_model->findAdminemail($id);

				   $unit=$this->Company_model->findunit($id);
				   $unit_id=$unit[0]['unit_id'];
				   $superadmin=array();
				   $admindetails= $this->Profile_model->getAdminUsers($unit_id); 
				   if(empty($admindetails))
				   {
                     $superadmin=$this->Profile_model->findAdminDetails(); 
				   }
				   else
				   {
                     $superadmin=$admindetails;
				   }
				   $login_user_details = $this->User_model->getUserUnitAndGroupId($this->session->userdata('user_id'));
				   $user_group_id = $login_user_details[0]['group_id'];
				   $user_login_id = $this->session->userdata('user_id');
				   $user_unit_id  = $login_user_details[0]['unit_id'];
				   $is_subunit = $this->Unit_model->checkIsSubUnit($user_unit_id);
				   $superadmin = getManagersSupervisors($user_group_id,$user_unit_id,$is_subunit,'',$user_login_id);
				   // print("<pre>".print_r($superadmin,true)."</pre>");exit();
				   //print_r($to_email);exit();
                   if(!empty($difference))
                   {
						$site_title = 'St Matthews Healthcare - SMH Rota';
                        $admin_email=getCompanydetails('from_email');

                       
                        foreach ($superadmin as $value) {
                        	if($value['email'])
	            			{
	            	        $emailSettings = array();
                        	$emailSettings = array(
                                                'from' => $admin_email,
                                                'site_title' => $site_title,
                                                'site_url' => $this->config->item('base_url'),
                                                'to' => $value['email'],
                                                'data'=>$difference,
                                                'staff_name'=>$value['fname'].' '.$value['lname'],
                                                'type' => 'Staff-Address change',
                                                'subject' => 'User'.' '.$firstname.' '.$lastname.' updated following details',
                                                'username'=> $firstname.' '.$lastname,
                                                );
                                      //print_r($emailSettings);
                                      $this->load->library('parser');
                                      $htmlMessage = $this->parser->parse('emails/profilechange', $emailSettings, true);
                                      //die($htmlMessage);exit();
                                      $this->load->helper('mail'); 
                                      sendMail($emailSettings, $htmlMessage); 
                            }
                           
                            /*if($value['mobile_number'])
	                        {    
	                                      //sms integration
	                                $message = 'User'.' '.$firstname.' '.$lastname.' updated address.';
	                                 //print_r($message);exit();
	                                $this->load->model('AwsSnsModel');
	                                $sender_id="SMHRotaADD";
	                                $result = $this->AwsSnsModel->SendSms($value['mobile_number'], $sender_id, $message);
	                                 //print_r($result);exit();
	                        } */  
                        } //exit();
                        
                        
                        
                    }
 
				$this->session->set_flashdata('Successfully', 'Updated successfully.'); 
                redirect('staffs/profile');
			}
			$result['js_to_load'] = array('profile/profile.js');
			$this->load->view('includes/staffs/footer',$result);
	    }

	    public function checkEmail($email, $id=''){
        
        $status = 1;
        $data = array();
        $data['email']= $email;
        $data['id']= $id;
        $result = $this->Profile_model->getuserDetails($data);  
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
          return $status;  
       
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
       
       public function findethnicity()
       {
       	$ethnicity=$this->input->post('id');
       	$result=$this->Profile_model->getEthnicity($ethnicity);
       	//print_r($result);exit();
       	echo json_encode($result);
       }
       public function editprofile1()
       {
       	$this->load->view('includes/staffs/header');
       	$this->load->view('staffs/profile/neweditfile');
       }

	  //   public function editUserDetails(){

			// // title_slug = $this->uri->segment(4);
	  //      $this->load->helper('form');
	  //      $id= $this->session->userdata('user_id');
			// //Calling function getCompanyDetails from company model for fetching company details
			// $data['user']=$this->Profile_model->findusers($id);  
	  //       $data['unit']=$this->Profile_model->findunitdetails($id);  
			
		 
			// // title_slug = $this->uri->segment(4);
			// $this->load->helper('form');
			// $this->load->library('form_validation');
			// //Validating all required fields
			// $this->form_validation->set_rules('firstName', 'firstname', 'required');
   //      	$this->form_validation->set_rules('lastName', 'lastname', 'required');
   //      	$this->form_validation->set_rules('emailAddress', 'emailAddress', 'required');
   //      	$this->form_validation->set_rules('phone_number', 'phone_number', 'required');
   //      	$this->form_validation->set_rules('dob', 'date of birth', 'required');
   //      	$this->form_validation->set_rules('address1', 'address1', 'required');
   //      	$this->form_validation->set_rules('address2', 'address2', 'required');
   //      	$this->form_validation->set_rules('wcountry2', 'country', 'required');
   //      	$this->form_validation->set_rules('wcity2', 'city', 'required');
   //      	$this->form_validation->set_rules('postcode', 'postcode', 'required'); 
   //      	$this->form_validation->set_rules('wkin_name2', 'kin_name', 'required');
   //      	$this->form_validation->set_rules('wkin_phone2', 'kin_phone', 'required');
   //      	$this->form_validation->set_rules('wkin_address2', 'kin_address', 'required');   
			// $this->load->view('includes/staffs/header');
			// if ($this->form_validation->run() == FALSE)
			// {
			 
			//     $data['error']=  validation_errors();
			//     $this->load->view('staffs/profile/editprofile', $data);
			// }
			// else
			// {
		 
			//     //Getting all input field values
			//         $firstname= $this->input->post('firstName'); 
   //                  $lastname= $this->input->post('lastName');
   //                  $email= $this->input->post('emailAddress');
   //                  $phone_number= $this->input->post('phone_number');
   //                  $dob= $this->input->post('dob');
   //                  $address1= $this->input->post('address1');
   //                  $address2= $this->input->post('address2');
   //                  $country= $this->input->post('wcountry2');
   //                  $city= $this->input->post('wcity2');
   //                  $postcode= $this->input->post('postcode');
   //                  $kin_name=$this->input->post('kin_name');
			// 	    $kin_phone=$this->input->post('kin_phone');
			// 	    $kin_address=$this->input->post('kin_address');
			// 	    $id= $this->session->userdata('user_id');
			// 	    $address_from_unit=$this->User_model->findaddress($id);   
			// 		  if($address_from_unit[0]['address1']!=$address1)
			// 		  {  
			// 		    $address=$address1.','.$city.','.$country.','.$postcode;
			// 		    $datas=array('user_id'=>$id,'address'=>$address,'kin_name'=>$kin_name,'kin_phonenumber'=>$kin_phone,'kin_address'=>$kin_address,'changed_date'=>date('Y-m-d H:i:s'));  
			// 		    $this->User_model->insertAddresshistory($datas);
			// 		  } 
   //                  if($this->checkEmail($email,$id)==0)
   //                  { 
   //                    $data['error']= "Email already exist.";
   //                    $this->load->view('staffs/profile/editprofile', $data);

   //                  }
  	// 					else
  	// 				{
   //                  $dataformuser['email'] = $email;
   //                  $result =$this->Profile_model->insertdatas($id,$dataformuser); 
			    
			//     //Creating an array with new values
			//     $datahome=array('fname' => $firstname,'lname'=>$lastname,'mobile_number'=>$phone_number,'dob'=>$dob,'address1'=>$address1,'address2'=>$address2,'country'=>$country,'city'=>$city,'postcode'=>$postcode,'kin_name'=>$kin_name,'kin_phone'=>$kin_phone,'kin_address'=>$kin_address,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$id);
			//     $result =  $this->Profile_model->userupdate($id,$datahome);  
			//     $this->session->set_flashdata('message', 'Updated successfully.');
			//     redirect('staffs/profile');
			// }
   //      	}
	  //   }
	}
?>