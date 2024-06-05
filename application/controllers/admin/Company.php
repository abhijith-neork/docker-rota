
<?php
	class Company extends CI_Controller {
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
			 
			//Loading company model
			$this->load->model('Company_model');
			$this->load->model('Rota_model');
			$this->load->helper('form');
			$this->load->helper('name');
	    }

	    public function index()
	    {
	    	$this->auth->restrict('Admin.Company.Edit');
	    	$companyDetails = array();        	
			$this->load->helper('form');
			//Calling function getCompanyDetails from company model for fetching company details
			$companyDetails['values']=$this->Company_model->getCompanyDetails();
			
		 
			// title_slug = $this->uri->segment(4);
			$this->load->helper('form');
			$this->load->library('form_validation');
			//Validating all required fields
			$this->form_validation->set_rules('companyName', 'company name', 'required');
			$this->form_validation->set_rules('industry', 'industry', 'required');
			//$this->form_validation->set_rules('timezone', 'timezone', 'required');
			$this->form_validation->set_rules('currency', 'phone', 'required');
			$this->form_validation->set_rules('address1', 'address1', 'required');
			$this->form_validation->set_rules('address2', 'addres2', 'required');
			$this->form_validation->set_rules('city', 'city', 'required');
			$this->form_validation->set_rules('postcode', 'postcode', 'required');
			$this->form_validation->set_rules('country', 'country', 'required');
			$this->form_validation->set_rules('shift_gap', 'Gap between shifts', 'required');
			$this->form_validation->set_rules('email', 'Reply email', 'required');
			$this->form_validation->set_rules('late_notify', 'Late Notification', 'required');
			$data = array();
			$this->load->view('includes/home_header');
			if ($this->form_validation->run() == FALSE)
			{
			 
			    $companyDetails['error']= '';
			    $this->load->view('admin/company/managecompany',$companyDetails);
			}
			else{
		 
			    //Getting all input field values
			    $company_name 	= $this->input->post('companyName');
			    $industry 		= $this->input->post('industry');
			    $timezone 		= $this->input->post('timezone');
			    $currency 		= $this->input->post('currency');
			    $address1 		= $this->input->post('address1');
			    $address2 		= $this->input->post('address2');
			    $city 			= $this->input->post('city');
			    $postcode 		= $this->input->post('postcode');
			    $shift_gap 		= $this->input->post('shift_gap');
			    $country 		= $this->input->post('country');
			    $from_email 	= $this->input->post('email');
			    $late_notify 	= $this->input->post('late_notify');
			    $id 			= 1;
			    
			    //Creating an array with new values
			    $updatedData	= array(
			        'company_name'=>$company_name,
			        'industry'=>$industry,
			        'timezone'=>$timezone,
			        'currency'=>$currency,
			        'Address1'=>$address1,
			        'Address2'=>$address2,
			        'city'=>$city,
			        'country'=>$country,
			        'postcode'=>$postcode,
			        'shift_gap'=>$shift_gap,
			        'from_email'=>$from_email,
			        'late_notify'=>$late_notify,
			        'updation_date'=>date('Y-m-d H:i:s')
			    );
			    
			    //Calling update function from company model
			    $result = $this->Company_model->update($id,$updatedData);
			    $title='Edit company details';
                InsertEditedData($updatedData,$title);
			    $this->session->set_flashdata('message', 'Updated successfully.');
			    redirect('admin/company');
			}
			/*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
			$this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
			/*End*/
			$this->load->view('includes/home_footer');
	    }
	    public function editCompanyDetails()
	    {
	    	$this->auth->restrict('Admin.Company.Edit');
			// title_slug = $this->uri->segment(4);
	        $this->load->helper('form');
	        $this->load->library('form_validation');
	        //Validating all required fields
	        $this->form_validation->set_rules('companyName', 'company name', 'required');
	        $this->form_validation->set_rules('industry', 'industry', 'required');
	        $this->form_validation->set_rules('timezone', 'timezone', 'required');
	        $this->form_validation->set_rules('currency', 'currency', 'required');
	        $this->form_validation->set_rules('address1', 'address1', 'required');
	        $this->form_validation->set_rules('address2', 'addres2', 'required');
	        $this->form_validation->set_rules('city', 'city', 'required');
	        $this->form_validation->set_rules('postcode', 'postcode', 'required');
	        $this->form_validation->set_rules('shift_gap', 'Gap between shifts', 'required');
	        $this->form_validation->set_rules('email', 'Reply email', 'required');
			$this->form_validation->set_rules('late_notify', 'Late Notification', 'required');			
			$this->form_validation->set_rules('country', 'country', 'required');
			$data = array();
			$this->load->view('includes/home_header');
			if ($this->form_validation->run() == FALSE)
			{
	            $data['error']='';
	            $this->load->view('admin/company/managecompany',$data);
        	}
        	else{
        		//Getting all input field values
        		$company_name 	= $this->input->post('companyName');
				$industry 		= $this->input->post('industry');
        		$timezone 		= $this->input->post('timezone');
        		$currency 		= $this->input->post('currency');
        		$address1 		= $this->input->post('address1');
        		$address2 		= $this->input->post('address2');
        		$city 			= $this->input->post('city');
        		$postcode 		= $this->input->post('postcode');
        		$shift_gap 		= $this->input->post('shift_gap');
        		$country 		= $this->input->post('country');
        		$from_email 	= $this->input->post('email');
			    $late_notify 	= $this->input->post('late_notify');
        		$id 			= $this->input->post('id');        		
        		//Creating an array with new values
        		$updatedData	= array(
        								'company_name'=>$company_name,
        								'industry'=>$industry,
        								'timezone'=>$timezone,
        								'currency'=>$currency,
        								'Address1'=>$address1,
        								'Address2'=>$address2,
        								'city'=>$city,
        								'country'=>$postcode,
        								'postcode'=>$country,
        								'shift_gap'=>$shift_gap,
        								'from_email'=>$from_email,
        								'late_notify'=>$late_notify,
        								'updation_date'=>date('Y-m-d H:i:s')
        							);
        		//print_r($updatedData); exit;
        		//Calling update function from company model 
                $result = $this->Company_model->update($id,$updatedData);

                $title='Edit company details';
                InsertEditedData($updatedData,$title);

                $this->session->set_flashdata('message', 'Updated successfully.');
                redirect('admin/company');
        	}
	    }
	}
?>