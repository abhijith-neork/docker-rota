<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Designation extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type') ==2)
        {
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        $this->load->model('Designation_model');
        $this->load->helper('form');
    }

    public function index()
    {
       $this->auth->restrict('Admin.Designation.View');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array(); 
       $data['designation']=$this->Designation_model->alldesignation();  
       // $data['count']=count($data['designation']); 
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->load->model('Rota_model');
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/
       $this->load->view("admin/designation/managedesignation",$data);
       $result['js_to_load'] = array('designation/designation.js');
       $this->load->view('includes/home_footer',$result);

    }


    public  function  adddesignation()
{
        $this->auth->restrict('Admin.Designation.Add');
        $header['title'] = 'Add Designation';
        $data = array(); 
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('designation_name', 'job role name', 'required');
        $this->form_validation->set_rules('description', 'description', 'required'); 
        $this->form_validation->set_rules('designation_code', 'job role code', 'required|max_length[5]');
        $this->form_validation->set_rules('avl-request-limit', 'avl-request-limit', 'numeric'); 
        // $this->form_validation->set_rules('overtime_rate', 'overtime_rate', 'required'); 
        // $this->form_validation->set_rules('holiday_rate', 'holiday_rate', 'required'); 
        // $this->form_validation->set_rules('sickness_rate', 'sickness_rate', 'required'); 
        // $this->form_validation->set_rules('maternity_rate', 'maternity_rate', 'required'); 
        // $this->form_validation->set_rules('authorised_rate', 'authorised_rate', 'required'); 
        // $this->form_validation->set_rules('unauthorised_rate', 'unauthorised_rate', 'required'); 
        // $this->form_validation->set_rules('normal_rate', 'normal_rate', 'required'); 
        // $this->form_validation->set_rules('other_rate', 'other_rate', 'required'); 
       $data = array(); 
       $header['title'] = 'Add'.'designation';
       $this->load->view('includes/home_header',$header);
    
       if ($this->form_validation->run() == FALSE)
       {
          
            $data['designation']=$this->Designation_model->alldesignation();
            $data['jobgroup']=$this->Designation_model->findjobgroup(); 
            $data['error']='';
            $this->load->view("admin/designation/adddesignation",$data);
             
        }
        else
        {   
                
            $designation_name= $this->input->post('designation_name'); 
    		$description=$this->input->post('description');
            $designation_code=$this->input->post('designation_code');
            $availability_requests=$this->input->post('avl-request-limit');
            $onoffswitch1=$this->input->post('onoffswitch1'); 
            $jobgroup=$this->input->post('jobrole_group');
            if($onoffswitch1=='on')
            {
                $part_number=0; 
            }
            else
            {
                $part_number=1; 
            }
            $overtime_rate=$this->input->post('overtime_rate');
            $holiday_rate=$this->input->post('holiday_rate');
            $sickness_rate=$this->input->post('sickness_rate');
            $maternity_rate=$this->input->post('maternity_rate');
            $authorised_absence_rate=$this->input->post('authorised_rate');
            $unauthorised_absence_rate=$this->input->post('unauthorised_rate');
            $normal_rates=$this->input->post('normal_rate');
            $other_rates=$this->input->post('other_rate');
    		$status=$this->input->post('status');
            
            if($this->checkDesignationname($designation_name,'')==0){
                $data['error']= "Job role name already exist, please try another name.";
              
                $this->load->view("admin/designation/adddesignation",$data);
            }
            else
            {
    		$dataform=array('designation_name' => $designation_name,'creation_date'=>date('Y-m-d H:i:s'),'description'=>$description,'designation_code'=>$designation_code,'jobrole_groupid'=>$jobgroup,'part_number'=>$part_number,'status'=>$status,'availability_requests'=>$availability_requests);  
    		$result=$this->Designation_model->insertdesignation($dataform);
            if($result)
            {
                   $title='Add Designation';
                   InsertEditedData($this->input->post(),$title);
            }
            $data=array('designation_id'=>$result,'overtime_rate' => $overtime_rate,'holiday_rate' => $holiday_rate,'sickness_rate' => $sickness_rate,'maternity_rate' => $maternity_rate,'authorised_absence_rate' => $authorised_absence_rate,'unauthorised_absence_rate' => $unauthorised_absence_rate,'normal_rates' => $normal_rates,'other_rates' => $other_rates,'creation_date'=>date('Y-m-d H:i:s'));
            $this->Designation_model->insertdesignationrates($data); 
            $this->session->set_flashdata('message', 'Added successfully.');
            $data = array();
            redirect('admin/designation');
            }

          }
        
        $this->load->view('includes/home_footer');
    
}

    public function deletedesignation()
    {
        $this->auth->restrict('Admin.Designation.Delete');
        try{
                $id=$this->uri->segment(4);
                $test=array();
                $test=$this->Designation_model->findUsersDesignation($id);   
                if (empty($test))
                {   
                    $data = $this->Designation_model->deletedesignation($id);
                    if($data==1)
                       $abc=array('id'=>'designation_id : '. $id);
                       $title='Delete Designation';
                       InsertEditedData($abc,$title);
                
                    $this->session->set_flashdata('error',"0");
                    $this->session->set_flashdata('message', 'Deleted successfully.');
                    redirect('admin/managedesignation');
                }
                else
                {
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Cannot delete this job role, this job role is already assigned to users.');
                    redirect('admin/managedesignation');
                }
               
            }

        catch (Exception $e){
            print_r($e->getMessage());
            }
 
    }

    public function finddesignation()
    {
        $status=$this->input->post('status'); //print_r($status);exit();
        $result=$this->Designation_model->finddesignationbystatus($status); 
        if(empty($result))
        { 
           $json = []; 
        }
        else
        {
            foreach ($result as $row)
              {
                if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";} 
                $delete="'".$row['id']."','".$row['designation_name']."'";  
                    $json[] =array('<a href="designation/editdesignation/'.$row['id'].'">'.$row['designation_name'],'<a href="designation/editdesignation/'.$row['id'].'">'.$row['designation_code'],$row['description'],$stat,'<a class="Edit" data-id="'.$row['id'].'" href="designation/editdesignation/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> '.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>');
                   
              } 
        }
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();

    }

    public function checkDesignationname($designationname, $id=''){
        
        $status = 1;
        $data = array();
        $data['designation_name']= $designationname;
        $data['id']= $id;
        $result = $this->Designation_model->getdesignationDetails($data); 
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
       return $status;
       
    }
 

    public function Editdesignation()
   { 

        $this->auth->restrict('Admin.Designation.Edit');
        $title_slug = $this->uri->segment(4);  
        $this->load->helper('form');
        $this->load->library('form_validation'); 
        $id=$this->input->post('id'); 
        $status=$this->input->post('status');  
        
        $this->form_validation->set_rules('designation_name', 'job role name', 'required');
        $this->form_validation->set_rules('description', 'description', 'required'); 
        $this->form_validation->set_rules('designation_code', 'job role code', 'required|max_length[5]');
        $this->form_validation->set_rules('avl-request-limit', 'avl-request-limit', 'numeric'); 
        // $this->form_validation->set_rules('overtime_rate', 'overtime_rate', 'required'); 
        // $this->form_validation->set_rules('holiday_rate', 'holiday_rate', 'required'); 
        // $this->form_validation->set_rules('sickness_rate', 'sickness_rate', 'required'); 
        // $this->form_validation->set_rules('maternity_rate', 'maternity_rate', 'required'); 
        // $this->form_validation->set_rules('authorised_rate', 'authorised_rate', 'required'); 
        // $this->form_validation->set_rules('unauthorised_rate', 'unauthorised_rate', 'required'); 
        // $this->form_validation->set_rules('normal_rate', 'normal_rate', 'required'); 
        // $this->form_validation->set_rules('other_rate', 'other_rate', 'required'); 
        $abc=2;
        if($status==$abc) 
        {
            $this->form_validation->set_rules('status', 'status', 'callback_status_check['.$id.']', 'required');
        }
        $header['title'] = 'Edit'.'Designation';
        $data = array(); 
        $this->load->view('includes/home_header',$header);
    

        if ($this->form_validation->run() == FALSE)
        {
           if(empty($title_slug)) { $title_slug=$id;}  
            $data['designation']=$this->Designation_model->finddesignation($title_slug); //print_r($data['designation']); exit();
            $data['jobgroup']=$this->Designation_model->findjobgroup();  
            $data['error']='';
            $this->load->view('admin/designation/editdesignation', $data);
             
        }
        else
        
         { 

                $designation_name= $this->input->post('designation_name'); 
                $description= $this->input->post('description');
                $designation_code= $this->input->post('designation_code');
                $availability_requests=$this->input->post('avl-request-limit');
                $onoffswitch1=$this->input->post('onoffswitch1'); 
                $jobgroup=$this->input->post('jobrole_group');
                if($onoffswitch1=='on')
                {
                    $part_number=0; 
                }
                else
                {
                    $part_number=1; 
                }
                $overtime_rate=$this->input->post('overtime_rate');
                $holiday_rate=$this->input->post('holiday_rate');
                $sickness_rate=$this->input->post('sickness_rate');
                $maternity_rate=$this->input->post('maternity_rate');
                $authorised_absence_rate=$this->input->post('authorised_rate');
                $unauthorised_absence_rate=$this->input->post('unauthorised_rate');
                $normal_rates=$this->input->post('normal_rate');
                $other_rates=$this->input->post('other_rate');
                $status= $this->input->post('status');
                $id= $this->input->post('id'); 
                $userid= $this->input->post('userid'); 
                if($this->checkDesignationname($designation_name,$id)==0)
                {   
                    
                    $data['error']= 'Job role name already exist, please try another name.';
                    $data['designation']=$this->Designation_model->finddesignation($id); 
                    $this->load->view("admin/designation/editdesignation",$data);
                }
                else
                { 
                $datahome=array('designation_name'=>$designation_name,'description'=>$description,'designation_code'=>$designation_code,'jobrole_groupid'=>$jobgroup,'part_number'=>$part_number,'status'=>$status,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$userid,'availability_requests'=>$availability_requests);  
                $result = $this->Designation_model->update($id,$datahome);
                 if($result)
                {
                   $title='Edit Designation';
                   InsertEditedData($this->input->post(),$title);
                }
                   //print_r($result);exit();
                $data=array('overtime_rate' => $overtime_rate,'holiday_rate' => $holiday_rate,'sickness_rate' => $sickness_rate,'maternity_rate' => $maternity_rate,'authorised_absence_rate' => $authorised_absence_rate,'unauthorised_absence_rate' => $unauthorised_absence_rate,'normal_rates' => $normal_rates,'other_rates' => $other_rates,'creation_date'=>date('Y-m-d H:i:s'),'updation_date'=>date('Y-m-d H:i:s'));
                $this->Designation_model->upadatedesignationrates($result,$data); 
                $this->session->set_flashdata('message', 'Updated successfully.');
                $data['desig']=$this->Designation_model->finddesignation($title_slug);
                redirect('admin/Designation');
                }
            //$this->load->view('admin/editmenubanners', $data);
            }
              
        $this->load->view('includes/home_footer');
         
        }


        public function status_check($str,$id)
        {  
                $test=array();
                $test=$this->Designation_model->findUsersDesignation($id);   
                if (empty($test))
                {   
                      return TRUE;
                       
                }
                else
                {  
                    $this->form_validation->set_message('status_check',  'Cannot change status, this job role is already assigned to users.');
                        //$this->form_validation->set_message('status_check', 'The designation is already assigned to users.'); 
                        return FALSE;
                }  
        }

        public function add_sort_order()
        {
            $id=$this->input->post('id');
            $sort_order=$this->input->post('order');
            $result=$this->Designation_model->update_sort_order($sort_order,$id);
            $json=$result;
            echo json_encode($json);
            exit();
        }


}
?>
