<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Shift extends CI_Controller {
   
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
        $this->load->model('Shift_model');
        $this->load->helper('form');
    }

    public function index()
    {
       $this->auth->restrict('Admin.Shift.View');
       $result = array(); 
       $this->load->view('includes/home_header'); 
       $data=array(); 
       // $data['count']=count($data['designation']); 
       $data['shifts']=$this->Shift_model->getShift();  
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->load->model('Rota_model');
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/ 
       $this->load->view("admin/shift/manageshift",$data);
       $result['js_to_load'] = array('shift/shift.js');
       $this->load->view('includes/home_footer',$result);

    }
 
     public  function  addshift()
  {
        $this->auth->restrict('Admin.Shift.Add');
        $header['title'] = 'Add Shift';
        $data = array(); 
        $this->load->model('Shift_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('shift_name', 'shift name', 'required');
        $this->form_validation->set_rules('shift_shortname', 'shift short name', 'required'); 
        $this->form_validation->set_rules('wstart_time2', 'start time', 'required');
        $this->form_validation->set_rules('wend_time2', 'end time', 'required');
        $this->form_validation->set_rules('shift_category', 'shift category', 'required'); 
        $this->form_validation->set_rules('targeted_hours', 'targeted hours', 'required'); 
        $this->form_validation->set_rules('unpaid_break_hours', 'unpaid break hours', 'required'); 
        $this->form_validation->set_rules('shift_type', 'shift type', 'required'); 
        $data = array(); 
        $header['title'] = 'Add Shift';
       $this->load->view('includes/home_header',$header);
     
       if ($this->form_validation->run() == FALSE)
       {
            
            $data['error'] ='';
            $this->load->view("admin/shift/addShift",$data);
             
        }
        else
        {   
          $onoffswitch1=$this->input->post('onoffswitch1'); 
          if($onoffswitch1=='off')
          {
              $part_number=0; 
          }
          else
          {
              $part_number=1; 
          }
        $shift_name= $this->input->post('shift_name'); 
        $shift_shortname=$this->input->post('shift_shortname');
        $am="am"; 
        $start_time= $this->input->post('wstart_time2').':'.$this->input->post('wstart_time12');
        $end_time=$this->input->post('wend_time2').':'.$this->input->post('wend_time12');
        // if($start_time > 12)
        // {  

            if($end_time > $start_time)
            {
                $first_time=date("g:i a", strtotime($start_time)); 
                $last_time=date("g:i a", strtotime($end_time)); 
            }
            else
            { 
                $date=date('Y-m-d', strtotime(' +1 day')); 
                $first_time=date("g:i a", strtotime($start_time)); 
                $last_time=date("$date g:i a", strtotime($end_time)); 
            }

        $datetime1 = new DateTime($first_time);    
        $datetime2 = new DateTime($last_time);     
        
        $interval = $datetime1->diff($datetime2);
        $hours = $interval->format('%h.%i');
        if($hours == 0)
        {
            $data['error'] ='Error in time selection, Please select a valid time period'; 
            $this->load->view("admin/shift/addShift",$data);
        } 
        else
        {
             
        $shift_category=$this->input->post('shift_category'); 
        $targeted_hours=$this->input->post('targeted_hours'); 
        $unpaid_break_hours=$this->input->post('unpaid_break_hours'); 
        $total_targeted_hours=$this->input->post('total_targeted_hours'); 
        $color_unit=$this->input->post('color');
        $background_color=$this->input->post('back-color');
        //added by chinchu
        $shift_type=$this->input->post('shift_type'); 
        // echo number_format((float)$unpaid_break_hours, 2, '.', '');
        // print_r($unpaid_break_hours);exit();
        $status=$this->input->post('status'); 

         if($this->checkShiftname($shift_name,'')==0)
           {
                $data['error'] ='Shift name already exist, please try another name.'; 
                $this->load->view("admin/shift/addShift",$data);
            }
            else
            {
        $dataform=array('shift_name' => $shift_name,'shift_shortcut'=>$shift_shortname,'start_time'=>$start_time,'end_time'=>$end_time,'hours'=>$hours,'shift_category'=>$shift_category,'targeted_hours'=>$targeted_hours,'unpaid_break_hours'=>$unpaid_break_hours,'total_targeted_hours'=>$total_targeted_hours,'status'=>$status,'creation_date'=>date('Y-m-d H:i:s'),'part_number'=>$part_number,'color_unit'=>$color_unit,'shift_type'=>$shift_type,'background_color'=>$background_color); 
           
            $insert_id = $this->Shift_model->insertshift($dataform);
            if($insert_id)
                {
                   $title='Add Shift';
                   InsertEditedData($this->input->post(),$title);
                }
            if($insert_id){
              $avldataform=array('shift_name' => 'Available '.$shift_name,'shift_shortcut'=>'AVL-'.$shift_shortname,'start_time'=>$start_time,'end_time'=>$end_time,'shift_category'=>$shift_category,'targeted_hours'=>$targeted_hours,'unpaid_break_hours'=>$unpaid_break_hours,'creation_date'=>date('Y-m-d H:i:s'),'part_number'=>$part_number,'parent_id'=>$insert_id);
               $this->Shift_model->insertAvlshift($avldataform);
            }
            $this->session->set_flashdata('message', 'Added successfully.');
            $data = array();
            redirect('admin/shift');
            }
           }
          }
        $result['js_to_load'] = array('shift/add_edit_shift.js');
        $this->load->view('includes/home_footer',$result);
    
  }

  public function checkShiftname($shiftname, $id=''){
        
        $status = 1;
        $data = array();
        $data['shift_name']= $shiftname;
        $data['id']= $id;
        $result = $this->Shift_model->getshiftDetails($data);  
        unset($data);
        if(count($result)>0) 
            $status = 0;
          
         return $status;
       
    }

    public function findshift()
    {
        $status=$this->input->post('status');
        $result=$this->Shift_model->findshiftbystatus($status);  
        if(empty($result))
        { 
           $json = []; 
        }
        else
        {
            foreach ($result as $row)
              {
                if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";}
                if($row['shift_category']==1) { $category="Day"; } else if($row['shift_category']==2) { $category="Night"; } else { $category=" ";}
                if($row['part_number']==0) { $part_number= "No"; } else { $part_number= "Yes"; }
                $start_time=date("H:i:s", strtotime($row['start_time'])); 
                $end_time=date("H:i:s", strtotime($row['end_time']));
                $delete="'".$row['id']."','".$row['shift_name']."'";  
                if($row['id']!=0)
                {
                    $json[] =array('<a href="shift/editshift/'.$row['id'].'">'.$row['shift_name'],'<a href="shift/editshift/'.$row['id'].'">'.$row['shift_shortcut'],$start_time,$end_time,$category,$row['targeted_hours'],$row['unpaid_break_hours'],$row['total_targeted_hours'],$part_number,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="shift/editshift/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> '.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>');
                  }

                   
              } 
        }
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();

    }

    public function editshift()
    {
       $this->auth->restrict('Admin.Shift.Edit');
       $title_slug = $this->uri->segment(4);
       $this->load->model('Shift_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $id=$this->input->post('id'); 
        $status=$this->input->post('status');  
        
        $this->form_validation->set_rules('shift_name', 'shift name', 'required');
        $this->form_validation->set_rules('shift_shortname', 'shift short name', 'required'); 
        $this->form_validation->set_rules('wstart_time2', 'start time', 'required');
        $this->form_validation->set_rules('wend_time2', 'end time', 'required');
        $this->form_validation->set_rules('shift_category', 'shift category', 'required');   
        $this->form_validation->set_rules('targeted_hours', 'targeted hours', 'required'); 
        $this->form_validation->set_rules('unpaid_break_hours', 'unpaid break hours', 'required');
        $this->form_validation->set_rules('shift_type', 'shift type', 'required'); 
        $abc=2;
        if($status==$abc) 
        {
            $this->form_validation->set_rules('status', 'status', 'callback_status_check['.$id.']', 'required');
        }
        $data = array(); 
       $header['title'] = 'Edit'.'Shift'; 
       $this->load->view('includes/home_header',$header);
    

      if ($this->form_validation->run() == FALSE)
       {
            if(empty($title_slug)) { $title_slug=$id;}
            $data['shift']=$this->Shift_model->findshift($title_slug);  
            //print_r($data['shift']); 
            $data['time']=$this->Shift_model->findtime($title_slug);
            foreach ($data['time'] as $value) { 
              $time1=$value['start_time'];
              $a=explode(':', $time1);  
              $time2=$value['end_time'];
              $b=explode(':', $time2); 
            }
            $data['a']=$a; $data['b']=$b;  
            $data['error']='';            
            $this->load->view('admin/shift/editshift', $data);
             
        }
        else
        
      {
    
          /*$onoffswitch1=$this->input->post('partofnumber');
          print $onoffswitch1;
          if($onoffswitch1){
            if($onoffswitch1=='off')
            {
                $part_number=0;
            }
            else
            {
                $part_number=1;
            }
          }else{
            $part_number=$this->input->post('p_number');
          }*/
        $part_number = $this->input->post('p_number');
        $shift_name= $this->input->post('shift_name'); 
        $shift_shortname=$this->input->post('shift_shortname');
        $am="am"; 
        $start_time= $this->input->post('wstart_time2').':'.$this->input->post('wstart_time12');   
        $end_time=$this->input->post('wend_time2').':'.$this->input->post('wend_time12');  

           if($end_time > $start_time)
            {
                $first_time=date("g:i a", strtotime($start_time)); 
                $last_time=date("g:i a", strtotime($end_time)); 
            }
            else
            { 
                $date=date('Y-m-d', strtotime(' +1 day')); 
                $first_time=date("g:i a", strtotime($start_time)); 
                $last_time=date("$date g:i a", strtotime($end_time)); 
            } 
        $datetime1 = new DateTime($first_time);    
        $datetime2 = new DateTime($last_time);     
        
        $interval = $datetime1->diff($datetime2);
        $hours = $interval->format('%h.%i');  
        if($hours == 0)
        {
            if(empty($title_slug)) { $title_slug=$id;}
            $data['shift']=$this->Shift_model->findshift($title_slug);  
            $data['time']=$this->Shift_model->findtime($title_slug);
            foreach ($data['time'] as $value) { 
              $time1=$value['start_time'];
              $a=explode(':', $time1);  
              $time2=$value['end_time'];
              $b=explode(':', $time2); 
            }
            $data['a']=$a; $data['b']=$b;      
            $data['error'] ='Error in time selection, Please select a valid time period'; 
            $this->load->view("admin/shift/editshift",$data);
        } 
        else
        {  
        $shift_category=$this->input->post('shift_category'); 
        $targeted_hours=$this->input->post('targeted_hours');  
        $unpaid_break_hours=$this->input->post('unpaid_break_hours'); 
        $total_targeted_hours=$this->input->post('total_targeted_hours'); 
        $color_unit=$this->input->post('color');
        $background_color=$this->input->post('back-color');
        $status=$this->input->post('status'); 
        $userid= $this->input->post('userid'); 
        $status=$this->input->post('status');
        $id=$this->input->post('id');
        $shift_type=$this->input->post('shift_type');
        if($this->checkShiftname($shift_name,$id)==0)
           {
                
            $id=$this->input->post('id'); 
            $data['shift']=$this->Shift_model->findshift($id);
            $data['time']=$this->Shift_model->findtime($id); 
            foreach ($data['time'] as $value) {
              $time1=$value['start_time'];
              $a=explode(':', $time1);  $c=explode(" ", $a[1]); 
              $time2=$value['end_time'];
              $b=explode(':', $time2);  $d=explode(" ", $b[1]); 
            }
            $data['a']=$a; $data['b']=$b;  $data['c']=$c; $data['d']=$d;
            //$data['error']= "Shift name already exist, please try another name.";
            $data['error'] ='Shift name already exist, please try another name.';
            $this->load->view("admin/shift/editshift",$data);
         }
        else
         {
        $dataform=array('shift_name' => $shift_name,'shift_shortcut'=>$shift_shortname,
            'start_time'=>$start_time,
            'end_time'=>$end_time,'shift_category'=>$shift_category,
            'status'=>$status,'targeted_hours'=>$targeted_hours,
            'part_number'=>$part_number,
            'unpaid_break_hours'=>$unpaid_break_hours,'total_targeted_hours'=>$total_targeted_hours,
            'hours'=>$hours,'creation_date'=>date('Y-m-d H:i:s'),'color_unit'=>$color_unit,
            'updation_date'=>date('Y-m-d H:i:s'),'updation_userid'=>$userid,'shift_type'=>$shift_type,'background_color'=>$background_color);
        $result = $this->Shift_model->update($id,$dataform);
        if($result)
        {
                   $title='Edit Shift';
                   InsertEditedData($this->input->post(),$title);
        }
        $avldataform=array(
          'shift_name' => 'Available '.$shift_name,
          'shift_shortcut'=>'AVL-'.$shift_shortname,
          'start_time'=>$start_time,
          'end_time'=>$end_time,
          'shift_category'=>$shift_category,
          'targeted_hours'=>$targeted_hours,
          'unpaid_break_hours'=>$unpaid_break_hours,
          // 'shift_type'=>$shift_type
        );  
        $data = $this->Shift_model->updateAvlShift($id,$avldataform);
        $this->session->set_flashdata('message', 'Updated successfully.');
        $data['shift']=$this->Shift_model->findshift($title_slug);
        redirect('admin/Shift');
            //$this->load->view('admin/editmenubanners', $data);
         }
        }  
      
      }
       $result['js_to_load'] = array('shift/add_edit_shift.js');   
        $this->load->view('includes/home_footer',$result);
    }

    public function status_check($str,$id)
        {  
                $test=array();
                $test=$this->Shift_model->findUsersShift($id);   
                if (empty($test))
                {   
                      return TRUE;
                       
                }
                else
                {  
                    $this->form_validation->set_message('status_check', 'Cannot change status, this shift is already assigned to users.'); 
                        return FALSE;
                }  
        }

      public function deleteshift()
    {
        $this->auth->restrict('Admin.Shift.Delete');
        try{
            $id=$this->uri->segment(4);
            $test=array();
            $test=$this->Shift_model->findUsersShift($id);   
            if (empty($test))
            {  

                    $data = $this->Shift_model->deleteshift($id);
                    if($data==1)
                      $abc=array('id'=>'Shift_id : '. $id);
                      $title='Delete Shift';
                      InsertEditedData($abc,$title);
                 
                    $this->session->set_flashdata('error',"0");
                    $this->session->set_flashdata('message', 'Deleted successfully.');
                    redirect('admin/manageshift');
            }
            else
            {
                
                    $this->session->set_flashdata('error',"1");
                    $this->session->set_flashdata('message', 'Cannot delete this shift, this shift is already assigned to users.');
                    redirect('admin/manageshift');
            }
            
        }
        catch (Exception $e) {
            print_r($e->getMessage());
        }
        
    }
}
?>