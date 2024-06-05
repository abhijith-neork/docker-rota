<?php
class Bankholiday extends CI_Controller {
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
		$this->load->model('Bankholiday_model');
		$this->load->helper('form');
    }
    public function index()
    {
		$this->auth->restrict('Admin.Holiday.Add');
		$result = array(); 
		$this->load->helper('user');
		$this->load->view('includes/home_header'); 
		$data=array(); 
		$data['holidays'] = $this->Bankholiday_model->findAllHolidays();
		/*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
		$this->load->model('Rota_model');
		$this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
		/*End*/
		$this->load->view("admin/bankholiday/list_bankholidays",$data);
		$result['js_to_load'] = array('holiday/bank_holidays.js');
		$this->load->view('includes/home_footer',$result);
    }
    public function addHolidays(){
    	$this->auth->restrict('Admin.Holiday.Add');
    	$result = array(); 
    	$this->load->helper('user');
    	$this->load->view('includes/home_header'); 
    	$data=array();
    	$data['error']='';
    	$data['success']='';
    	$this->load->view("admin/bankholiday/add_bankholiday",$data);
    	$result['js_to_load'] = array('holiday/bank_holidays.js');
    	$this->load->view('includes/home_footer',$result);
    }
    public function saveHolidays(){
    	$this->auth->restrict('Admin.Holiday.Add');
    	$result = array();
    	$data=array();
    	$this->load->helper('user');
    	$this->load->view('includes/home_header');
		$this->load->helper('form');
		$this->load->library('form_validation'); 
		$this->form_validation->set_rules('start_date', 'start_date', 'required'); 
		if($this->form_validation->run()==FALSE){
			$data['error']= 'Insert Date';
			$this->load->view('admin/bankholiday/add_bankholiday',$data);
		}else{
			//Getting all input field values
			$date_daily = $this->input->post('start_date'); 
			$old_date = explode('/', $date_daily); 
			$new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
			//print_r($date_daily);exit();
			//Creating an array with new values
			$datas	= array(
			    'date'=>$new_data,
			    'created_date'=>date('Y-m-d H:i:s'),
			    'status'=>1
			);
			//print_r($datas);exit();
			//Calling insert function from bank holiday model
			$result = $this->Bankholiday_model->insertHoliday($datas);
			
			 $title='Add Bankholiday';
              InsertEditedData($datas,$title);
             
			$this->session->set_flashdata('message', $result['message']);
			redirect('admin/Bankholiday/index');
		}
    	$result['js_to_load'] = array('holiday/bank_holidays.js');
    	$this->load->view('includes/home_footer',$result);
    }
    public function editHoliday(){
    	$this->auth->restrict('Admin.Holiday.Edit');
    	$id = $this->uri->segment(4);
    	$result = array(); 
    	$this->load->helper('user');
    	$this->load->view('includes/home_header'); 
    	$data=array();
    	$data['error']='';
    	$data['success']='';
    	//Calling insert function from bank holiday model
    	$result = $this->Bankholiday_model->findHoliday($id);
    	$data['holiday'] = $result;
    	$this->load->view("admin/bankholiday/edit_holiday",$data);
    	$result['js_to_load'] = array('holiday/bank_holidays.js');
    	$this->load->view('includes/home_footer',$result);
    }
    public function updateHoliday(){
    	$this->auth->restrict('Admin.Holiday.Edit');
    	$result = array();
    	$data=array();
    	$this->load->helper('user');
    	$this->load->view('includes/home_header');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$id = $this->uri->segment(4);
		$result = $this->Bankholiday_model->findHoliday($id);
		$data['holiday'] = $result;
		$this->form_validation->set_rules('start_date', 'start_date', 'required'); 
		if($this->form_validation->run()==FALSE){
			$data['error']= 'Insert Date';
			$this->load->view('admin/bankholiday/edit_holiday',$data);
		}else{
			//Getting all input field values
			// $date 	= $this->input->post('start_date');
			$date_daily = $this->input->post('start_date'); 
			$old_date = explode('/', $date_daily); 
			$new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];

			$result = $this->Bankholiday_model->updateHoliday($new_data,$id);

                $title='Edit Bankholiday';
                InsertEditedData($this->input->post(),$title);
             
			$this->session->set_flashdata('message', 'Edited successfully.');
			redirect('admin/Bankholiday/index');
		}
    	$result['js_to_load'] = array('holiday/bank_holidays.js');
    	$this->load->view('includes/home_footer',$result);
    }
    public function deleteHoliday(){
      $id = $this->input->post("id");
      $result = $this->Bankholiday_model->deleteHoliday($id);
  
      			$title='Delete Bankholiday';
      			$abc=array('id'=> 'bankholiday_id :' .$id);
                InsertEditedData($abc,$title);
      
      header('Content-Type: application/json');
      echo json_encode(array('status' => 1));
      exit();
    }
}?>