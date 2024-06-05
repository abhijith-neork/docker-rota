<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Password extends CI_Controller {
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
			$this->load->model('Profile_model');
			$this->load->helper('form');
	    }
	    public $header = array();


	    public function index()
	    {
	    	$this->auth->restrict('Admin.Password.Change');
	    	$data = array();        	
			$this->load->helper('form');
			$id= $this->session->userdata('user_id');
			//Calling function getCompanyDetails from company model for fetching company details
			$data['user']=$this->Profile_model->findusers($id);  
	        $data['unit']=$this->Profile_model->findunitdetails($id); 
			
		 
			// title_slug = $this->uri->segment(4);
			$this->load->helper('form');
			$this->load->library('form_validation');
			//Validating all required fields
			$this->form_validation->set_rules('new_password', 'new password', 'required');
        	$this->form_validation->set_rules('confirm_password', 'confirm password', 'required'); 
			$this->load->view('includes/staffs/header');
			if ($this->form_validation->run() == FALSE)
			{
			 
			    $data['error']=  validation_errors();
			    $this->load->view('staffs/password/editpassword', $data);
			}
			else
			{
		       
			    //Getting all input field values
			        $password= $this->input->post('new_password'); 
                    $cpassword= $this->input->post('confirm_password');  
                    $id= $this->session->userdata('user_id'); 
                    if ($password != $cpassword) 
                    {
                    	  $data['error']= "Incorrect Password.";
			             $this->load->view('staffs/password/editpassword', $data);
                    	 
                    }
                    else
                    {
                $dataform=array('password'=>md5($password));  
			    $result =  $this->Profile_model->changepassword($id,$dataform);  
			    $this->session->set_flashdata('message', 'Password changed successfully.');
			    $this->auth->logout();
			}
			}
			$this->load->view('includes/staffs/footer');
	    }

	   
	}
?>