<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Unit extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type')==2){
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        $this->load->model('Unit_model');
        $this->load->model('Rota_model');
        $this->load->model('Designation_model');
        $this->load->helper('form');
    }

    public function index()
    {
       $this->auth->restrict('Admin.Unit.View');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array();  
       $data['unittype']=$this->Unit_model->allunitstypes();
       $data['unittypes']=$this->Unit_model->listallunitstypes1();  
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/
 
       $this->load->view("admin/unit/manageunit",$data);
       $result['js_to_load'] = array('unit/unit.js');
        $this->load->view('includes/home_footer',$result);
    }

    public  function  addunit()
{
        $this->auth->restrict('Admin.Unit.Add');
        $header['title'] = 'Add Unit';
        $data = array(); 
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unit_name', 'unit name', 'required');
        $this->form_validation->set_rules('unit_type', 'unit type', 'required');  
        $this->form_validation->set_rules('unit_shortname', 'unit shortname', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('phone_number', 'phone number', 'required');
        $this->form_validation->set_rules('staff_limit', 'staff limit', 'required');
        $this->form_validation->set_rules('max_patients', 'max patients', 'required');
        $this->form_validation->set_rules('number_of_beds', 'number of beds', 'required');
        $this->form_validation->set_rules('description', 'description', 'required'); 
        $data = array(); 
        $header['title'] = 'Add Unit';
        $this->load->view('includes/home_header',$header);
    
        if ($this->form_validation->run() == FALSE)
        {
           
            $data['unittype']=$this->Unit_model->allunitstypes();
            $data['unittypes']=$this->Unit_model->listallunitstypes1(); 
            $data['parent_unit']=$this->Unit_model->SelectParentUnit(); 
            $data['designation']=$this->Designation_model->alldesignation();  
            $data['error'] ='';
            $this->load->view("admin/unit/addunit",$data);     
        }
        else
        { 
        $id=$this->session->userdata('user_id');   
        $designation=$this->input->post('designation_id');  
        $max_leave=$this->input->post('max_leave');  
        $max_leave_hours=$this->input->post('max_leave_hours');    
        $unit_name= $this->input->post('unit_name'); 
        $unit_type=$this->input->post('unit_type');
        $parent_unit=$this->input->post('parent_unit'); 
        $unit_shortname= $this->input->post('unit_shortname'); 
        $address= $this->input->post('address'); 
        $phone_number=$this->input->post('phone_number');
        $staff_limit=$this->input->post('staff_limit');
        $max_patients=$this->input->post('max_patients');
        $number_of_beds=$this->input->post('number_of_beds');
        $description=$this->input->post('description');
        $color_unit=$this->input->post('color'); 
        $status=$this->input->post('status');
         if($this->checkUnitname($unit_name,'')==0){
                $data['error']= "Unit name already exist, please try another name.";
                $data['unittype']=$this->Unit_model->allunitstypes(); 
                $data['parent_unit']=$this->Unit_model->SelectParentUnit(); 
                $data['designation']=$this->Designation_model->alldesignation();  
                $data['unittypes']=$this->Unit_model->listallunitstypes1();
                $this->load->view("admin/unit/addunit",$data);
            }
            else
            {
            $dataform=array('unit_name' => $unit_name,'unit_type'=>$unit_type,'parent_unit'=>$parent_unit,'unit_shortname'=>$unit_shortname,'address'=>$address,'phone_number'=>$phone_number,'staff_limit'=>$staff_limit,'max_patients'=>$max_patients,'number_of_beds'=>$number_of_beds,'creation_date'=>date('Y-m-d H:i:s'),'description'=>$description,'status'=>$status,'color_unit'=>$color_unit); 
        //print_r($dataform);exit(); 
             $new_id=$this->Unit_model->insertunit($dataform);



             if($new_id)
             {
                $title='Add Unit';
                InsertEditedData($dataform,$title);
             }
            //$result=$this->Unit_model->findMaxLeave($new_id);print_r($result);exit();
            $n=count($designation);
            for($i=0;$i<$n;$i++)
            {
                $datahome=array('unit_id'=>$new_id,'designation_id' => $designation[$i],'max_leave'=>$max_leave[$i],'max_leave_hour'=>$max_leave_hours[$i],'creation_date' => date('Y-m-d'),'created_userid' =>$id);  
                $this->Unit_model->insertMaxLeave($datahome); 
            }   
            $this->session->set_flashdata('message', 'Added successfully.');
            $data = array();
            redirect('admin/unit');
            }

          }
        $result['js_to_load'] = array('unit/unit.js');
        $this->load->view('includes/home_footer',$result);
    
}
 

public function checkUnitname($unitname, $id=''){
        
        $status = 1;
        $data = array();
        $data['unit_name']= $unitname;
        $data['id']= $id;
        $result = $this->Unit_model->getunitDetails($data);
       
        unset($data);
          if(count($result)>0) 
            $status = 0;
          
       return $status;
       
    }

 public function deleteunit()
    {
        $this->auth->restrict('Admin.Unit.Delete');
        try{
                $id=$this->uri->segment(4); 
                $test=array();
                $test=$this->Unit_model->findUsersUnit($id);   
                if (empty($test))
                { 

                    $data = $this->Unit_model->deleteunit($id);
                    if($data==1) 
                   
                        $title='Delete Unit';
                        $abc=array('id'=>'unit_id :'.$id);
                        InsertEditedData($abc,$title);
                 
                    $this->session->set_flashdata('error',"0");
                    $this->session->set_flashdata('message', 'Deleted successfully.');
                    redirect('admin/manageunit');
                }
                else
                {
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Cannot delete this unit, this unit is already assigned to users.');
                    redirect('admin/manageunit');
                }  
                
            }
        catch (Exception $e) {
            print_r($e->getMessage());
        }
        
    }

    public function findunit()
    {
        $status=$this->input->post('status');  
        $result=$this->Unit_model->findunitbystatus($status);  
        if(empty($result))
        { 
           $json = []; 
        }
        else
        {
            foreach ($result as $row)
              {
                if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";}  
                $delete="'".$row['id']."','".$row['unit_name']."'"; 
                $color='<button style="float:right;height:10px;background-color:'.$row['color_unit'].';">'; 
                if($row['id']!=1)
                {
                        $json[] =array('<a href="unit/editunit/'.$row['id'].'">'.$row['unit_shortname'],'<a href="unit/editunit/'.$row['id'].'">'.$row['unit_name'].' '.$color,$row['unit_type'],$row['staff_limit'],$row['max_patients'],$row['number_of_beds'],$row['description'],$stat,'<a class="Edit" data-id="'.$row['id'].'" href="unit/editunit/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a>'.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>');
                }
                else
                {
                        $json[] =array('<a href="unit/editunit/'.$row['id'].'">'.$row['unit_shortname'],'<a href="unit/editunit/'.$row['id'].'">'.$row['unit_name'].' '.$color,$row['unit_type'],$row['staff_limit'],$row['max_patients'],$row['number_of_beds'],$row['description'],$stat,'<a class="Edit" data-id="'.$row['id'].'" href="unit/editunit/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a>');
                }

                    
                   
              } 
        }
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();

    }
 

    public function Editunit()
   { 
        $this->auth->restrict('Admin.Unit.Edit');
        $title_slug = $this->uri->segment(4);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $id=$this->input->post('id');  
        $status=$this->input->post('status'); 
        $parent_unit=$this->input->post('parent_unit'); 
        $this->form_validation->set_rules('unit_name', 'unit name', 'required');
        $this->form_validation->set_rules('unit_type', 'unit type', 'required');  
        $this->form_validation->set_rules('unit_shortname', 'unit shortname', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('phone_number', 'phone number', 'required');
        $this->form_validation->set_rules('staff_limit', 'staff limit', 'required');
        $this->form_validation->set_rules('max_patients', 'max patients', 'required');
        $this->form_validation->set_rules('number_of_beds', 'number of beds', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');  
        $abc=2;
        if($status==$abc) 
        {
            $this->form_validation->set_rules('status', 'status', 'callback_status_check['.$id.']', 'required');
        }
        $this->form_validation->set_rules('parent_unit', 'parent_unit', 'callback_parent_check['.$id.']', 'required'); 
       $header['title'] = 'Edit'.'Unit';
       $data = array(); 
       $this->load->view('includes/home_header',$header);
    

      if ($this->form_validation->run() == FALSE)
       {
            if(empty($title_slug)) { $title_slug=$id;}
            $data['unit']=$this->Unit_model->findunit($title_slug); 
            $data['parent_unit']=$this->Unit_model->SelectParentUnit();
            $data['designation']=$this->Unit_model->getUnitDesignation($title_slug);   
            $data['unittype']=$this->Unit_model->allunitstypes();
            $data['error']='';            
            $this->load->view('admin/unit/editunit', $data);
             
        }
        else
        
         {

       //print_r($this->input->post()); exit();
        $designation=$this->input->post('designation_id');  
        $max_leave=$this->input->post('max_leave');  
        $max_leave_hours=$this->input->post('max_leave_hours');  
        $unit_name= $this->input->post('unit_name');  
        $unit_type=$this->input->post('unit_type');
        $parent_unit=$this->input->post('parent_unit');
        $unit_shortname=$this->input->post('unit_shortname'); 
        $address=$this->input->post('address'); 
        $phone_number=$this->input->post('phone_number');
        $staff_limit=$this->input->post('staff_limit');
        $max_patients=$this->input->post('max_patients');
        $number_of_beds=$this->input->post('number_of_beds');
        $description=$this->input->post('description');
        $color_unit=$this->input->post('color');
        $userid=$this->input->post('userid'); 
        $status=$this->input->post('status');
        $id=$this->input->post('id'); 
        if($this->checkUnitname($unit_name,$id)==0)
        {  
                
                $data['error']='Unit name already exist, please try another name.';
                $title_slug = $this->uri->segment(4); 
                $data['unit']=$this->Unit_model->findunit($id);
                $data['parent_unit']=$this->Unit_model->SelectParentUnit(); 
                $data['designation']=$this->Unit_model->getUnitDesignation($id); 
                $data['unittype']=$this->Unit_model->allunitstypes(); 
                $this->load->view("admin/unit/editunit",$data);
        }
        else
        {
        $dataform=array('unit_name' => $unit_name,'unit_type'=>$unit_type,'parent_unit'=>$parent_unit,'unit_shortname'=>$unit_shortname,'address'=>$address,'phone_number'=>$phone_number,'staff_limit'=>$staff_limit,'max_patients'=>$max_patients,'number_of_beds'=>$number_of_beds,'description'=>$description,'status'=>$status,'color_unit'=>$color_unit,'updation_date'=>date('Y-m-d H:i:s'),'updated_userid'=>$userid); 
        //print_r($dataform);exit();
           $this->Unit_model->update($id,$dataform); 
          
           $title='Edit Unit';
           InsertEditedData($dataform,$title);
            

           $result=$this->Unit_model->findMaxLeave($id); 
           if(!empty($result))
           {
            $this->Unit_model->deleteMaxleave($id);
           }

            $n=count($designation);
            for($i=0;$i<$n;$i++)
            {
                $datahome=array('unit_id'=>$id,'designation_id' => $designation[$i],'max_leave'=>$max_leave[$i],'max_leave_hour'=>$max_leave_hours[$i],'creation_date' => date('Y-m-d H:i:s'),'created_userid' =>$userid);
                $this->Unit_model->insertMaxLeave($datahome); 
            }
        $this->session->set_flashdata('message', 'Updated successfully.'); 
        redirect('admin/Unit');
            //$this->load->view('admin/editmenubanners', $data);
        }

        }
        $result['js_to_load'] = array('unit/unit.js');     
        $this->load->view('includes/home_footer',$result);
         
        }

        public function status_check($str,$id)
        {  
                $test=array();
                $test=$this->Unit_model->findUsersUnit($id);   
                if (empty($test))
                {   
                      return TRUE;
                       
                }
                else
                {   
                     $this->form_validation->set_message('status_check', 'Cannot change status, this unit is already assigned to users..'); 
                        return FALSE;
                }  
        }

        public function parent_check($str,$id)
        {
           $test=array();
           $current_date=date('Y-m-d');
           //$end_date = date("Y-m-t", strtotime($current_date));
           //print_r($end_date);exit();
            //print_r($str);
           $test=$this->Unit_model->getPreviousparent($id);    //print_r($test);exit();
           if($test!=$str)
           {
             //print_r($id);exit();
             $check_parent=$this->Rota_model->findRotadetails($id); //print_r($check_parent); exit();
             $rota_date=date("Y-m-t", strtotime($check_parent[0]['date']));    //print_r($rota_date);exit();
                if (empty($check_parent))
                {   
                      return TRUE;
                       
                }
                else if(strtotime($current_date) <= strtotime($rota_date))
                {   
                    //print_r('hii');exit();
                    $this->form_validation->set_message('parent_check', 'Cannot change parent unit, a rota is already assigned to this unit..'); 
                    return FALSE;
                } 
                else
                {
                    //print_r('hello');exit();
                    return TRUE;
                }
            
           }
           else
           {
             
                return true;
            } 
        }
  


  }

  ?>