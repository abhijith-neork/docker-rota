<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Reports_staff extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        // if ($this->session->userdata('user_type')==2)
        // {
        //     $this->auth->logout();
            
        //     unset($params);
        //     $this->_login(INVALID_LOGIN);
        // }
        $this->load->model('Reports_model');
        $this->load->model('Unit_model');
        $this->load->model('User_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Shift_model'); 
        $this->load->model('Rotaschedule_model');
        $this->load->model('Training_model');
        $this->load->model('Rota_model');
        $this->load->model('Activity_model');
        $this->load->helper('form');
    }


    public function timesheet() {
        //$this->auth->restrict('Admin.Mytraining');
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
        $result = array(); 
    $posts = array();
    $this->load->helper('user');
    $data=array(); 
    
    if($this->input->post('from_date')=='')
    {
        $data['from_date']=date('d/m/Y');
    }
    else
    {
         $data['from_date']=$this->input->post('from_date');
    }
    
    if($this->input->post('to_date')=='')
    {
        $data['to_date']=date('d/m/Y');
    }
    else
    {
        $data['to_date']=$this->input->post('to_date');
    }
    
    $params['user_id']=$this->session->userdata('user_id');
       
        $date_daily=$data['from_date'];
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;    
        $date_daily1=$data['to_date'];     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1;   
        $data['user_post']=$this->input->post('user');     
        $data['user']=$this->Reports_model->finduserdata($params['unit_id'],0);     

//print_r($params);
       
        //$params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
        $data['payroll'] = $this->Reports_model->findpayrolldataIndividual($params);   
      
        
        foreach ($data['payroll'] as $rota) {

                        $s =$this->multi_array_search($posts, array('date' =>$rota['date'], 'user_id' => $rota['user_id']));
                        if(count($s)>0){
                            if($s['shift_id']<$rota['shift_id']){
                                $keyS = array_search ($s['shift_id'], $s);
                                //print $keyS." ---KEY";
                                unset($posts[$keyS]);
                                $posts = array_values($posts);
                            }
                        }

                      $posts[] = array(
                      "user_id"          =>  $rota['user_id'],
                      "shift_name"       =>  $rota['shift_name'],
                      "shift_hours"      =>  $rota['shift_hours'],
                      "break"            =>  $rota['break'], 
                      "start_time"       =>  $rota['start_time'],
                      "end_time"         =>  $rota['end_time'],
                      "shift_category"   =>  $rota['shift_category'],
                      "date"             =>  $rota['date'],
                      "shift_id"         =>  $rota['shift_id'],
                      "timelogid"        =>  $rota['timelogid'],
                      "from_unit"        =>  $rota['from_unit'],
                      "unit_id"          =>  $rota['unit_id'],
                      "fname"            =>  $rota['fname'],
                      "lname"            =>  $rota['lname']
                  ); 
          

        }

        $data['payroll']=$posts;

            if(empty($data['payroll']))
            {
              $data['payroll_data']=array();
            } 
            else
            {
              $data['payroll_data']=$data['payroll']; 
            } 


        $data['training']=$this->Training_model->alltraingsbyUser($id);  //print_r($data['training']);exit(); 
        $this->load->view("staffs/listtimesheetforstaff",$data);
    }

    public function payrollreport()
    {
    //$this->auth->restrict('Staffs.Report.Payroll');
    $header = array();
    $header['headername']=" : Timesheet";
    if($this->session->userdata('user_type') ==2)
    {
        $this->load->view('includes/staffs/header',$result);
    }
    else
    {
        $this->load->view('includes/home_header',$result); 
    }
    $result = array(); 
    $posts = array();
    $this->load->helper('user');
    $data=array(); 
    
    if($this->input->post('from_date')=='')
    {
        $data['from_date']=date('d/m/Y');
    }
    else
    {
         $data['from_date']=$this->input->post('from_date');
    }
    
    if($this->input->post('to_date')=='')
    {
        $data['to_date']=date('d/m/Y');
    }
    else
    {
        $data['to_date']=$this->input->post('to_date');
    }
    
    $params['user_id']=$this->session->userdata('user_id');
       
        $date_daily=$data['from_date'];
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;    
        $date_daily1=$data['to_date'];     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1;   
        $data['user_post']=$this->input->post('user');     
        $data['user']=$this->Reports_model->finduserdata($params['unit_id'],0);     

//print_r($params);
       
        //$params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
        $data['payroll'] = $this->Reports_model->findpayrolldataIndividual($params);   
      
        
        foreach ($data['payroll'] as $rota) {

                        $s =$this->multi_array_search($posts, array('date' =>$rota['date'], 'user_id' => $rota['user_id']));
                        if(count($s)>0){
                            if($s['shift_id']<$rota['shift_id']){
                                $keyS = array_search ($s['shift_id'], $s);
                                //print $keyS." ---KEY";
                                unset($posts[$keyS]);
                                $posts = array_values($posts);
                            }
                        }

                      $posts[] = array(
                      "user_id"          =>  $rota['user_id'],
                      "shift_name"       =>  $rota['shift_name'],
                      "shift_hours"      =>  $rota['shift_hours'],
                      "break"            =>  $rota['break'], 
                      "start_time"       =>  $rota['start_time'],
                      "end_time"         =>  $rota['end_time'],
                      "shift_category"   =>  $rota['shift_category'],
                      "date"             =>  $rota['date'],
                      "shift_id"         =>  $rota['shift_id'],
                      "timelogid"        =>  $rota['timelogid'],
                      "from_unit"        =>  $rota['from_unit'],
                      "unit_id"          =>  $rota['unit_id'],
                      "fname"            =>  $rota['fname'],
                      "lname"            =>  $rota['lname']
                  ); 
          

        }

        $data['payroll']=$posts;

            if(empty($data['payroll']))
            {
              $data['payroll_data']=array();
            } 
            else
            {
              $data['payroll_data']=$data['payroll']; 
            } 
            /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
            $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
            /*End*/ 
            $this->load->view('admin/reports/staffs_timesheet',$data); 
            $result['js_to_load'] = array('reports/payrollreportindividual.js');
      if($this->session->userdata('user_type') ==2)
      {
          $this->load->view('includes/staffs/footer',$result);
      }
      else
      {
          $this->load->view('includes/home_footer',$result);    
      } 

    }

    public  function multi_array_search($array, $search)
    {
        $result = array();
        foreach ($array as $key => $value)
        {
            foreach ($search as $k => $v)
            {
                if (!isset($value[$k]) || $value[$k] != $v)
                {
                    continue 2;
                }
            }
            $result[$key] = $array['shift_id'];
            
        }
        return $result;
        
    }

    public function timelogmobileview()
    {
    //$this->auth->restrict('Admin.Report.Timelog');
      $header = array();
      $header['headername']=" : Timelog Report";

      if($this->session->userdata('user_type') ==2)
      {
        $this->load->view('includes/staffs/newstaff_headerrota',$result);
      }
      else
      {
        $this->load->view('includes/managerheaderrota',$result); 
      }

      $result = array(); 
      $this->load->helper('user');
      $data=array(); 
      $params=array();
      $jobe_roles=array();
      if($this->input->post('start_date')=='')
      { 
          $data['start_date']=date('d/m/Y');  
      }
      else
      {
         $data['start_date']=$this->input->post('start_date');  
      }
      if($this->input->post('end_date')=='')
      { 
          $data['end_date']=date('d/m/Y');  
      }
      else
      {
         $data['end_date']=$this->input->post('end_date');  
      }

      $params['user_id']=$this->session->userdata('user_id');

      $date_daily=$this->input->post('start_date');
      $old_date = explode('/', $date_daily); 
      $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
      $params['start_date']=$new_data;

      $date_daily1=$this->input->post('end_date');
      $old_date = explode('/', $date_daily1); 
      $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
      $params['end_date']=$new_data1;
    
      $timerec = $this->Reports_model->staffstimelogreport($params);
      $timeArr = array();
      /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
      $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
      /*End*/    
      foreach($timerec as $timer){
                  
                  if($timer['shiftid']> 2){
                     
                      $timeArr[]=$timer;
                  }
                  else if($timer['time_from']!='' && $timer['shiftid']< 2){
                      $timeArr[]=$timer;
                  }
                  else if($timer['time_to']!='' && $timer['shiftid']< 2){
                      $timeArr[]=$timer;
                  }
      }
      $data['timelog']=$timeArr;    
      $this->load->view('staffs/staffsmobileview_timelog',$data);
    }

    public function timelogview()
    {
    //$this->auth->restrict('Admin.Report.Timelog');
    $header = array();
    $header['headername']=" : Timelog Report";
    if($this->session->userdata('user_type') ==2)
    {
        $this->load->view('includes/staffs/header',$result);
    }
    else
    {
        $this->load->view('includes/home_header',$result); 
    }
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $params=array();
    $jobe_roles=array();
    if($this->input->post('start_date')=='')
    { 
        $data['start_date']=date('d/m/Y');  
    }
    else
    {
       $data['start_date']=$this->input->post('start_date');  
    }
    if($this->input->post('end_date')=='')
    { 
        $data['end_date']=date('d/m/Y');  
    }
    else
    {
       $data['end_date']=$this->input->post('end_date');  
    }

    $params['user_id']=$this->session->userdata('user_id');

    $date_daily=$this->input->post('start_date');
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;

    $date_daily1=$this->input->post('end_date');
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;
  
    $timerec = $this->Reports_model->staffstimelogreport($params);
    $timeArr = array();
    foreach($timerec as $timer){
                
                if($timer['shiftid']> 2){
                   
                    $timeArr[]=$timer;
                }
                else if($timer['time_from']!='' && $timer['shiftid']< 2){
                    $timeArr[]=$timer;
                }
                else if($timer['time_to']!='' && $timer['shiftid']< 2){
                    $timeArr[]=$timer;
                }
    }
    $data['timelog']=$timeArr; 
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/    
    $this->load->view('admin/reports/staffs_timelog',$data);
    $result['js_to_load'] = array('reports/staffs_timelog.js');
    if($this->session->userdata('user_type') ==2)
    {
          $this->load->view('includes/staffs/footer',$result);
    }
    else
    {
          $this->load->view('includes/home_footer',$result);    
    } 
  }





  }
  ?>