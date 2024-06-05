<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class JobRoleGroup extends CI_Controller {
   
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
        $this->load->model('Group_model');
        $this->load->model('JobRole_Group_model');
        $this->load->helper('form');
    }

     public function index()
    {
       //$this->auth->restrict('Admin.Group.Add');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array(); 
       $data['group']=$this->JobRole_Group_model->allgrouprolegroup(); 
       // $data['count']=count($data['designation']); 
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->load->model('Rota_model');
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/
       $this->load->view("admin/jobrolegroup/managejobrolegroup",$data);
       $result['js_to_load'] = array('groups/jobrolegroups.js');
        $this->load->view('includes/home_footer',$result);

    }

    public  function  addjobrolegroup()
 {
        //$this->auth->restrict('Admin.Group.Add');
        $header['title'] = 'Add Job Role Group';
        $data = array(); 
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_name', 'Job role group name', 'required'); 
        $data = array(); 
        $this->load->view('includes/home_header',$header);
    
       if ($this->form_validation->run() == FALSE)
       {
          
            $data['error']='';
            $this->load->view("admin/jobrolegroup/addjobrolegroup",$data);
             
        }
        else
        {   
                
            $group_name= $this->input->post('group_name');  
    		
    		if($this->checkGroupname($group_name,'')==0)
            {
    		    $data['error']= "Job role group name already exist, please try another name.";
    		    $this->load->view("admin/jobrolegroup/addjobrolegroup",$data);
    		}
    		else
            {
    		    $dataform=array('group_name' => $group_name,'creation_date'=>date('Y-m-d H:i:s'),'created_by'=>$this->session->userdata('user_id'));
    		    $this->JobRole_Group_model->insertjobrolegroupgroup($dataform);
                $title='Add Job Role Group';
                InsertEditedData($this->input->post(),$title);
    		    $this->session->set_flashdata('message', 'Added successfully.');
    		    $data = array();
    		    redirect('admin/JobRoleGroup');
    		}
    	

          }
        
        $this->load->view('includes/home_footer');
    
}
 

     public function deletejobrolegroup()
    {
        //$this->auth->restrict('Admin.Group.Delete');
        try{
            $id=$this->uri->segment(4);
            $test=array();
            $test=$this->JobRole_Group_model->findUsersGroup($id);   
            if (empty($test))
            { 
                $data = $this->JobRole_Group_model->deletegroup($id);
                if($data==1)

                $title='Delete Group';
                $abc=array('id'=> 'group_id : '.$id);
                InsertEditedData($abc,$title);

                $this->session->set_flashdata('error',"0");
                $this->session->set_flashdata('message', 'Deleted successfully.');
                redirect('admin/managejobrolegroup');
            }
            else
            {
                $this->session->set_flashdata('error',"1");
                $this->session->set_flashdata('message', 'Cannot delete this group, this group is assigned to users.');
                redirect('admin/managejobrolegroup');
            }
            
        }
        catch (Exception $e) {
            print_r($e->getMessage());
        }
        
    }
 
    /**
     * Check email already exists or not
     *
     */
    
    public function checkGroupname($groupname, $id=''){
        
        $status = 1;
        $data = array();
        $data['group_name']= $groupname;
        $data['id']= $id;
        $result = $this->JobRole_Group_model->getjobrolegroupDetails($data);
       
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
       return $status;
       
    }

    public function editjobrolegroup()
   { 
        //$this->auth->restrict('Admin.Group.Edit');
        $title_slug = $this->uri->segment(4);
        $this->load->helper('form');
        $this->load->library('form_validation'); 
        $id=$this->input->post('id'); 
        $status=$this->input->post('status');  
        $this->form_validation->set_rules('group_name', 'group name', 'required');
       $header['title'] = 'Edit'.'Job role group';
       $data = array(); 
       $this->load->view('includes/home_header',$header);
    

      if ($this->form_validation->run() == FALSE)
       {
            if(empty($title_slug)) { $title_slug=$id;}
            $data['group']=$this->JobRole_Group_model->findgroup($title_slug);
            $data['error']='';
            $this->load->view('admin/jobrolegroup/editjobrolegroup', $data);
             
        }
        else
        
         { 
                $group_name= $this->input->post('group_name');
                $id= $this->input->post('id'); 
                $userid= $this->input->post('userid'); 
                
                if($this->checkGroupname($group_name,$id)==0)
                {
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Job role group name already exist, please try another name.');
                    $data['group']=$this->JobRole_Group_model->findgroup($id);
                    $this->load->view("admin/jobrolegroup/editjobrolegroup",$data);
                }
                else
                {
                    $datahome=array('group_name'=>$group_name,'creation_date'=>date('Y-m-d H:i:s'),'created_by'=>$userid); 
                    $result = $this->JobRole_Group_model->update($id,$datahome);

                    $title='Edit Job role group';
                    InsertEditedData($this->input->post(),$title);

                    $this->session->set_flashdata('message', 'Updated successfully.');
                    $data['group']=$this->JobRole_Group_model->findgroup($title_slug);
                    redirect('admin/managejobrolegroup');
                }
            //$this->load->view('admin/editmenubanners', $data);
            }
              
        $this->load->view('includes/home_footer');
         
        }

}
?>
