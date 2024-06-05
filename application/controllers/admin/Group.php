<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Group extends CI_Controller {
   
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
        $this->load->model('Permission_model');
        $this->load->helper('form');
    }

     public function index()
    {
       $this->auth->restrict('Admin.Group.Add');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array(); 
       $data['group']=$this->Group_model->allgroup(); 
       // $data['count']=count($data['designation']); 
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->load->model('Rota_model');
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/
       $this->load->view("admin/group/managegroup",$data);
       $result['js_to_load'] = array('groups/groups.js');
        $this->load->view('includes/home_footer',$result);

    }

    public  function  addgroup()
 {
        $this->auth->restrict('Admin.Group.Add');
        $header['title'] = 'Add Group';
        $data = array(); 
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_name', 'group name', 'required'); 
        $data = array(); 
        $header['title'] = 'Add'.'AddGroup';
        $this->load->view('includes/home_header',$header);
    
       if ($this->form_validation->run() == FALSE)
       {
          
            $data['group']=$this->Group_model->allgroup();
            $data['error']='';
            $this->load->view("admin/group/addgroup",$data);
             
        }
        else
        {   
                
            $group_name= $this->input->post('group_name');  
    		$status=$this->input->post('status');
    		$subunit_access=$this->input->post('subunit_access');

    		if($this->checkGroupname($group_name,'')==0)
            {
    		    $data['error']= "Group name already exist, please try another name.";
    		    $this->load->view("admin/group/addgroup",$data);
    		}
    		else
            {
    		    $dataform=array('group_name' => $group_name,'creation_date'=>date('Y-m-d H:i:s'),'status'=>$status,'subunit_access'=>$subunit_access);
    		    $this->Group_model->insertgroup($dataform);
                $title='Add Group';
                InsertEditedData($this->input->post(),$title);
    		    $this->session->set_flashdata('message', 'Added successfully.');
    		    $data = array();
    		    redirect('admin/group');
    		}
    	

          }
        
        $this->load->view('includes/home_footer');
    
}
 
 public  function  group_permission()
{ 
        $this->auth->restrict('Admin.Group.Permission');
        $header['title'] = 'Group permission';
        $data = array(); 
        $params = array(); 
        $current_permissions = array();

        $this->load->model('Permission_model');
        $this->load->model('Role_Permission_model');
        $this->load->model('Group_model');

        $role_permissions = $this->Role_Permission_model->findRoles();
        if(count($role_permissions) > 0){
            foreach($role_permissions as $rp) 
            {
                $current_permissions[] = $rp['group_id']. ',' . $rp['permission_id'];
            }
        }
        unset($role_permissions);
        $data['matrix_role_permissions'] = $current_permissions; 
        $data['matrix_roles']=$this->Permission_model->allgroup(); 
        $data['matrix_permissions'] = $this->Permission_model->getPermission();  
        $this->load->view('includes/home_header');
        $this->load->view("admin/group/grouppermission",$data);       
         $result['js_to_load'] = array('groups/groups.js');
        $this->load->view('includes/home_footer',$result);
    
}

public function update()
{
        $idArr = array();
        $id = $this->input->post('id');  
        $action = $this->input->post('action'); 
        $this->load->model('Role_Permission_model');
        $idArr = explode(',',$id);  

        if(count($idArr)> 1)
        {
            // update
            $result=$this->Role_Permission_model->checkpermission($idArr[0], $idArr[1]); 
            if(count($result)>0)  
            {    
                $this->Role_Permission_model->deletePermission($idArr[0], $idArr[1]);    
            }
            else
            { 
                $this->Role_Permission_model->updatePermission($idArr[0], $idArr[1]);
            }
             
        }
        exit;
       
}
 
    public function updateonAllselect()
    {
            $id = $this->input->post('id');  
            $this->load->model('Group_model');
            $this->load->model('Role_Permission_model');
            $result=$this->Group_model->getPermission(); 
            foreach ($result as $value) 
            { 
               $result=$this->Role_Permission_model->checkpermission($id, $value['id']);  
                  if(count($result)>0)  
                {    
                     $this->Role_Permission_model->deletePermission($id, $value['id']);    
                }
                else
                { 
                     $this->Role_Permission_model->updatePermission($id, $value['id']);$this->auth->restrict('Admin.Group.Delete');
        try{
            $id=$this->uri->segment(4); 
            $data = $this->Group_model->deletegroup($id);
         
        
            if($data==1)
            {
                $this->session->set_flashdata('error',"0");
                $this->session->set_flashdata('message', 'Deleted successfully.');
                redirect('admin/managegroup');
            }
            else{
                if($data==1451){
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Cannot delete this group, this group is assigned to users.');
                }else {
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Error deleting record, please try again.');
                    
                }
                    
                redirect('admin/managegroup');
            }
            
        }
        catch (Exception $e) {
            print_r($e->getMessage());
        }

                }
             
            } 

    }

     public function deletedgroup()
    {
        $this->auth->restrict('Admin.Group.Delete');
        try{
            $id=$this->uri->segment(4);
            $test=array();
            $test=$this->Group_model->findUsersGroup($id);   
            if (empty($test))
            { 
                $data = $this->Group_model->deletegroup($id);
                if($data==1)

                $title='Delete Group';
                $abc=array('id'=> 'group_id : '.$id);
                InsertEditedData($abc,$title);

                $this->session->set_flashdata('error',"0");
                $this->session->set_flashdata('message', 'Deleted successfully.');
                redirect('admin/managegroup');
            }
            else
            {
                $this->session->set_flashdata('error',"1");
                $this->session->set_flashdata('message', 'Cannot delete this group, this group is assigned to users.');
                redirect('admin/managegroup');
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
        $result = $this->Group_model->getgroupDetails($data);
       
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
       return $status;
       
    }

    public function findgroup()
    {
        $status=$this->input->post('status');
        $result=$this->Group_model->findgroupbystatus($status); 
        if(empty($result))
        { 
           $json = []; 
        }
        else
        {
            foreach ($result as $row)
              {
                if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";} 
                $delete="'".$row['id']."','".$row['group_name']."'"; 
                    if($row['id']!=1 && $row['id']!=2 && $row['id']!=3)
                    {
                    $json[] =array('<a href="group/editgroup/'.$row['id'].'">'.$row['group_name'],$stat,'<a class="Edit" data-id="'.$row['id'].'" href="group/editgroup/'.$row['id'].'" title="Edit"><i class="fa fa-edit"></i></a>'.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>');
                    }
                    else
                    {
                    $json[] =array('<a href="group/editgroup/'.$row['id'].'">'.$row['group_name'],$stat,'<a class="Edit" data-id="'.$row['id'].'" href="group/editgroup/'.$row['id'].'" title="Edit"><i class="fa fa-edit"></i></a>');

                    }
              } 
        }
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();

    }

    public function Editgroup()
   { 
        $this->auth->restrict('Admin.Group.Edit');
        $title_slug = $this->uri->segment(4);
        $this->load->helper('form');
        $this->load->library('form_validation'); 
        $id=$this->input->post('id'); 
        $status=$this->input->post('status');  
        $this->form_validation->set_rules('group_name', 'group name', 'required');
        $abc=2;
        if($status==$abc) 
        {
            $this->form_validation->set_rules('status', 'status', 'callback_status_check['.$id.']', 'required');
        }
       $header['title'] = 'Edit'.'payment_type';
       $data = array(); 
       $this->load->view('includes/home_header',$header);
    

      if ($this->form_validation->run() == FALSE)
       {
            if(empty($title_slug)) { $title_slug=$id;}
            $data['group']=$this->Group_model->findgroup($title_slug);
            $data['error']='';
            $this->load->view('admin/group/editgroup', $data);
             
        }
        else
        
         { 
                $group_name= $this->input->post('group_name');
                $status= $this->input->post('status');
                $id= $this->input->post('id'); 
                $userid= $this->input->post('userid');
                $subunit_access=$this->input->post('subunit_access');
                
                if($this->checkGroupname($group_name,$id)==0){
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Group name already exist, please try another name.');
                    $data['group']=$this->Group_model->findgroup($id);
                    $this->load->view("admin/group/editgroup",$data);
                }
                else{
                $datahome=array('group_name'=>$group_name,'status'=>$status,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$userid,'subunit_access'=>$subunit_access); 
                $result = $this->Group_model->update($id,$datahome);

                $title='Edit Group';
                InsertEditedData($this->input->post(),$title);

                $this->session->set_flashdata('message', 'Updated successfully.');
                $data['group']=$this->Group_model->findgroup($title_slug);
                redirect('admin/Group');
                }
            //$this->load->view('admin/editmenubanners', $data);
            }
              
        $this->load->view('includes/home_footer');
         
        }

         public function status_check($str,$id)
        {  
                $test=array();
                $test=$this->Group_model->findUsersGroup($id);   
                if (empty($test))
                {   
                      return TRUE;
                       
                }
                else
                {   
                        $this->form_validation->set_message('status_check', 'This group is already assigned to users'); 
                        return FALSE;
                }  
        }


}
?>
