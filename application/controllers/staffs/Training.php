<?php
if (!defined('BASEPATH'))
    die();
class Training extends CI_Controller {
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        Parent::__construct();
      
        
        //Loading company model 
        $this->load->model('Training_model');
        $this->load->helper('form');
        $this->load->helper('name');
    }
    public function index() {
        $this->auth->restrict('Admin.Mytraining');
        $result = array();  
        $id=$this->session->userdata('user_id');
        if($this->session->userdata('user_type') ==2)
        {
        $this->load->view('includes/staffs/header',$result);
        }
        else
        {
        $this->load->view('includes/home_header',$result); 
        }
        $data['training']=$this->Training_model->alltraingsbyUser($id);  //print_r($data['training']);exit();
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->load->model('Rota_model');
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/ 
        $this->load->view("staffs/mytrainings",$data);
        $jsfile['js_to_load'] = array('training/listtraining.js');
        if($this->session->userdata('user_type') ==2)
        {
        $this->load->view('includes/staffs/footer',$jsfile);
        }
        else
        {
        $this->load->view('includes/home_footer',$jsfile);    
        } 
    }
    public function listtraining() {
        $this->auth->restrict('Admin.Mytraining');
        $result = array();  
        $id=$this->session->userdata('user_id');
        if($this->session->userdata('user_type') ==2)
        {
        $this->load->view('includes/staffs/newstaff_headerrota',$result);
        }
        else
        {
        $this->load->view('includes/managerheaderrota',$result); 
        }
        $data['training']=$this->Training_model->alltraingsbyUser($id);  //print_r($data['training']);exit();
        $this->load->view("staffs/training_mob",$data);
    }


     public function editFeedback()
    {
        $result = array();  
        $this->load->helper('form');
        $this->load->helper('name');
        $id=$this->uri->segment(4);
        $device=$this->uri->segment(5); //print_r($device);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('feedback', 'Feedback', 'required'); 
        $data = array(); 
        $header['title'] = 'Edit Comments';
        if($this->session->userdata('user_type') ==2)
        {
        $this->load->view('includes/staffs/header',$header);
        }
        else
        {
        $this->load->view('includes/home_header',$header);
        }

        if ($this->form_validation->run() == FALSE)
        {
       
        $data['error']='';
        $id=$this->uri->segment(4); 
        // $user_id=$this->session->userdata('user_id'); 
        $data['training']=$this->Training_model->traingsbyUser($id);
        $data['device']=$this->uri->segment(5);
        //$data['training']=$this->Training_model->alltraingsbyUser($id);  
        $this->load->view("staffs/editFeedback",$data);
        }
        else
        { 

            $feedback=$this->input->post('feedback');
            $user_id=$this->input->post('id');
            $training_id=$this->input->post('training_id');
            $device=$this->input->post('device');
            $dataform=array('user_id'=>$user_id,'training_id'=>$training_id,'feedback'=>$feedback,'creation_date'=>date('Y-m-d H:i:s'),'status'=>1);  
            
            $this->Training_model->insertfeedback($dataform);

            $title='My training - add comment';
            InsertEditedData($dataform,$title);
            $this->session->set_flashdata('message', 'Added successfully.');
            if($device=='mobile')
            {
              redirect('staffs/training/listtraining');
            }
            else
            {
              redirect('staffs/training');
            }
            
            
        }
        $jsfile['js_to_load'] = array('training/listtraining.js');
            if($this->session->userdata('user_type') ==2)
            {
            $this->load->view('includes/staffs/footer',$jsfile);
            }
            else
            {
            $this->load->view('includes/home_footer',$jsfile);
            }
       
    } 
     
}