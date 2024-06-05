<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
class Reports extends CI_Controller {
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
        Parent::__construct(); 
        if ($this->session->userdata('user_type')==2)
        {
            $this->auth->logout();
            
            unset($params);
            $this->_login(INVALID_LOGIN);
        }
        $this->load->model('Reports_model');
        $this->load->model('Unit_model');
        $this->load->model('User_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Shift_model'); 
        $this->load->model('Rotaschedule_model');
        $this->load->model('Designation_model');
        $this->load->model('Training_model');
        $this->load->model('Rota_model');
        $this->load->model('Activity_model');
        $this->load->helper('form');
    }

     public function timelog()
    {
    $this->auth->restrict('Admin.Report.Timelog');
    $header = array();
    $header['headername']=" : Timelog Report";
    $this->load->view('includes/home_header',$header);
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
    //print_r($this->input->post());
    $params['unit']=$this->input->post('unitdata');
    $params['type']=$this->input->post('type'); 
    $params['jobrole']= $this->input->post("jobrole");
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    //print_r($params);exit();
    //$params['start_date']=$this->input->post('start_date');
    $date_daily=$this->input->post('start_date');
    $old_date = explode('/', $date_daily); 
    $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['start_date']=$new_data;

    $date_daily1=$this->input->post('end_date');
    $old_date = explode('/', $date_daily1); 
    $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
    $params['end_date']=$new_data1;

     //print_r($params);exit();
    //if($type==2){$from_date='00:00:00';}else{$from_date=}
    
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18)
        { 
            $data['unit'] = $this->User_model->fetchCategoryTree(''); 
            
            $timerec = $this->Reports_model->timelogreport($params);
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
         /*    print  "<pre>";
            print_r($timeArr);
            $data['timelog']=$timeArr;
            exit; */
            
            $data['timelog']=$timeArr;   
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator

          $u_id=$this->session->userdata('user_id');  
          $sub=$this->User_model->CheckuserUnit($u_id);
          $unit=$this->User_model->findunitofuser($u_id); 
          $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 

            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

            $timerec = $this->Reports_model->timelogreport($params);
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


        }
        else
        {
            $params['unit']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
            $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); //print_r($data['unit']);exit();
            
            $timerec = $this->Reports_model->timelogreport($params); 
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
        }
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
    $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
    $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
    $this->load->view('admin/reports/timelog',$data);
    $result['js_to_load'] = array('reports/timelog.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function payrollreport()
    {
    $this->auth->restrict('Admin.Report.Timesheet');
    $header = array();
    $header['headername']=" : Timesheet";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $posts = array();
    $this->load->helper('user');
    $data=array(); 
    $jobe_roles=array();
    
    
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    
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
    $params['unit_id']=$this->input->post('unitdata');
    
    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id);
    $parent = 0;
    if(count($unit) > 0){
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    } 
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['jobrole']=$this->input->post("jobrole");    
        $params['status']=$this->input->post('status');     
        $date_daily=$this->input->post('from_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;    
        $date_daily1=$this->input->post('to_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1;   
        $data['user_post']=$this->input->post('user');     
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);     
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
          {  
              if($params['unit_id']=='none')
              {
                $params['unit_id']=$this->session->userdata('unit_id');
              }
              else
              { 
                $params['unit_id']=$this->input->post('unitdata');
              }
              $data['payroll'] = $this->Reports_model->findpayrolldata3($params);    
          }
          else
          {  
              $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
              //$params['unit_id']=$this->session->userdata('unit_id');
              $data['payroll'] = $this->Reports_model->findpayrolldata3($params);   
          }  
        
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
        //  $response['weekEvents']=$posts;
        //  print_r("<pre>");
        //  print_r($response['weekEvents']);
        // exit();

        $data['payroll']=$posts;
            
            $data['status']=$this->input->post('status');

            if(empty($data['payroll']))
            {
              $data['payroll_data']=array();
            } 
            else
            {
              $data['payroll_data']=$data['payroll']; 
            } 
             $data['jobrole'] = $this->Reports_model->fetchjobrole();
             $data['payroll_user'] = $this->checkPayrollUser();
             // $data['payroll_user'] = $this->checkPayrollUser();
             $search_unitid = 0;
             if($params['unit_id']){
                $search_unitid = $params['unit_id'];
             }
             $search_data = array(
                'unit_id' => $search_unitid,
                'from_date' => DateTime::createFromFormat('d/m/Y', $data['from_date'])->format('Y-m-d'),
                'to_date' => DateTime::createFromFormat('d/m/Y', $data['to_date'])->format('Y-m-d'),
             );
             $data['timesheet_lock_status'] = $this->checkTimeSheetLockStatus($search_data);
            //print_r($data['payroll']);exit();
            // $data['hours']=$this->Reports_model->rotahours($params); 
            // print_r($payrol);exit();
             /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
             $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
             /*End*/
             $data['job_roles']=json_encode($jobe_roles); 
             $this->load->view('admin/reports/payroll_report',$data); 
             $result['js_to_load'] = array('reports/payrollreport.js');
             $this->load->view('includes/home_footer',$result);

    }
    public function updateTimeSheetLockStatus(){
      $params['status'] = $this->input->post("status");
      $params['unit_id'] = $this->input->post("unit_id");
      $params['user_id'] = $this->session->userdata('user_id');
      // Convert the date format from dd/mm/yyyy to yyyy-mm-dd
      $fromdate_obj = DateTime::createFromFormat('d/m/Y', $this->input->post("from_date"));
      $params['from_date'] = $fromdate_obj->format('Y-m-d');
      $todate_obj = DateTime::createFromFormat('d/m/Y', $this->input->post("to_date"));
      $params['to_date'] = $todate_obj->format('Y-m-d');
      $result = $this->Reports_model->updateTimeSheetLockStatus($params);
      echo json_encode($result);
      return;
    }
    public function checkTimeSheetLockStatus($params){
      $result = $this->Reports_model->checkTimeSheetLockStatus($params);
      if(count($result) > 0){
        return false;
      }else{
        return true;
      }
    }
    public function checkPayrollUser(){
      if(in_array($this->session->userdata('email'),$this->config->item('lock_rota_emails')))
      {
        return true;
      }else{
        return false;
      }
    }
    public  function multi_array_search($array, $search)
  {
      
      // Create the result array
      $result = array();
      
      // Iterate over each array element
      foreach ($array as $key => $value)
      {
          
          // Iterate over each search condition
          foreach ($search as $k => $v)
          {
              
              // If the array element does not meet the search condition then continue to the next element
              if (!isset($value[$k]) || $value[$k] != $v)
              {
                  continue 2;
              }
              
          }
          
          // Add the array element's key to the result array
          $result[$key] = $array['shift_id'];
          
      }
      
      // Return the result array
      return $result;
      
  }



//     public function payrollreport()
//     {
//     $this->auth->restrict('Admin.Report.Payroll');
//     $header = array();
//     $header['headername']=" : Timesheet";
//     $this->load->view('includes/home_header',$header);
//     $result = array(); 
//     $this->load->helper('user');
//     $data=array(); 
//     $jobe_roles=array();
    
    
//     if($this->input->post("jobrole"))
//       $jobe_roles = $this->input->post("jobrole");
    
//     if($this->input->post('from_date')=='')
//     {
//         $data['from_date']=date('d/m/Y');
//     }
//     else
//     {
//          $data['from_date']=$this->input->post('from_date');
//     }
    
//     if($this->input->post('to_date')=='')
//     {
//         $data['to_date']=date('d/m/Y');
//     }
//     else
//     {
//         $data['to_date']=$this->input->post('to_date');
//     }
//     $params['unit_id']=$this->input->post('unitdata');
    
    
//         $u_id=$this->session->userdata('user_id');  
//         $sub=$this->User_model->CheckuserUnit($u_id);
//         $unit=$this->User_model->findunitofuser($u_id); 
//         $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
//         if($this->session->userdata('user_type')==1) //all super admin can access
//         {
//               $data['unit'] = $this->User_model->fetchCategoryTree();  
//         }
//         else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6)
//         { //if unit administrator
//             if($sub!=0 || $parent!=0) //unit administrator in sub unit
//             {   
//                 if($sub==0)
//                 {
//                        $sub=$parent;
//                 }
//                 else
//                 {
//                        $sub=$sub;
//                 }
//                 $data['unit'] = $this->User_model->fetchSubTree($sub);  
//              }
//              else
//              {    
//                 $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
//              }

//         }
//         else
//         {
//            $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
//         }
//             if($this->input->post('user')>0){
//                 $params['user_id']=$this->input->post('user');
//                 $unitUsers = array($this->input->post('user'));
//             }
        
//             else{
                 
//                     $jobRole = $this->input->post("jobrole");
//                     $unitUsers=$this->Reports_model->finduserInunit($params['unit_id'],0, $jobRole);
//                 $params['user_id']=$unitUsers;
//                 $params['user_id']= $selected_sizes_comma_seprated = implode(',', $unitUsers);
        
//             }
        
        
//     $params['jobrole']=$this->input->post("jobrole");
//     $params['status']=$this->input->post('status');
//     $date_daily=$this->input->post('from_date');
//     $old_date = explode('/', $date_daily); 
//     $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0]; 
//     //$params['from_date']=$this->input->post('from_date'); 
//     $params['from_date']=$new_data;
//     $date_daily1=$this->input->post('to_date'); 
//     $old_date = explode('/', $date_daily1); 
//     $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0]; 
    

//     if($new_data1!="--"){
//         $stop_date = date('Y-m-d', strtotime($new_data1 . ' +1 day'));

//     }

//     $params['to_date']=$stop_date;
//     $data['user_post']=$this->input->post('user'); 
//     $data['user']=$this->Reports_model->finduserdata($params['unit_id'],0); 
//      if($this->session->userdata('user_type')==1 || $this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6)
//     {  
//       if($params['unit_id']=='none')
//       {
//         $params['unit_id']=$this->session->userdata('unit_id');
//       }
//       else
//       { 
//         $params['unit_id']=$this->input->post('unitdata');
//       }
//       // print "<pre>";
//       if(count($unitUsers) > 0){
//           $timesheet = array();
//         //  print_r($unitUsers);
//           foreach($unitUsers as $userID){
//                 $params['user_id']=$userID;
//               $paydata[$userID] = $this->Reports_model->findpayrolldata($params); 
              
//               $j=0;
         
//           //  print_r( $paydata[$userID]);
             
//               if(count($paydata[$userID])>0){
                  
//                   for($i=0;$i<count($paydata[$userID]);$i++) {
                      
//                       $j= $i+1;
//                       //print_r( $paydata[$userID][$i]['fname']);
                      
//                       $timesheet[$i][$userID]['user_id'] = $paydata[$userID][$i]['user_id'];
//                       $timesheet[$i][$userID]['name'] = $paydata[$userID][$i]['fname']." ".$paydata[$userID][$i]['lname'];
//                       $timesheet[$i][$userID]['shift_name'] = $paydata[$userID][$i]['shift_name'];
//                       $timesheet[$i][$userID]['date'] = $paydata[$userID][$i]['date'];
//                       $timesheet[$i][$userID]['shift_id'] = $paydata[$userID][$i]['shift_id'];
                      
//                     /*   if($paydata[$userID][$i]['status']==1){
//                           $start = $paydata[$userID][$i]['creation_date'];
                          
//                           $end = $paydata[$userID][$j]['creation_date'];
//                       }
//                       else{
//                           $start = '';
//                           $end = $paydata[$userID][$i]['creation_date'];
                          
//                       } */
                      
                      
//                       $timesheet[$i][$userID]['start'] = $paydata[$userID][$i]['creation_date'];
//                       $timesheet[$i][$userID]['end'] = $paydata[$userID][$j]['creation_date'];
                      
                      
//                       $timesheet[$i][$userID]['status'] = $paydata[$userID][$i]['status'];
//                       $timesheet[$i][$userID]['unit_id'] = $params['unit_id'];
//                       $timesheet[$i][$userID]['timelogid'] = $paydata[$userID][$i]['timelogid'];
                      
//                       $tArray[]= array("user_id"=>$paydata[$userID][$i]['user_id'],'name'=>$paydata[$userID][$i]['fname']." ".$paydata[$userID][$i]['lname'],
//                           'start'=>$paydata[$userID][$i]['creation_date'],'end'=>$paydata[$userID][$j]['creation_date'],
//                           'shift_name'=>$paydata[$userID][$i]['shift_name'],
//                           'shift_id'=>$paydata[$userID][$i]['shift_id'],
//                           'unit_id'=>$paydata[$userID][$i]['unit_id'],
//                           'status'=>$paydata[$userID][$i]['status'],
//                           'timelogid'=>$paydata[$userID][$i]['timelogid'], 'date'=>$paydata[$userID][$i]['date']
//                        );
//                       if($paydata[$userID][$i]['date'])
//                       $date[$userID][$i]= $paydata[$userID][$i]['date'];
//                       $i= $j;
                      
//                   }
//               }
          
//                $ignore_dates['dates'] = array_unique($date[$userID]);
//                $ignore_dates['from_date'] =$new_data;
//                $ignore_dates['to_date'] =$stop_date;
//                $ignore_dates['user_id'] =$userID;
//                if($new_data!='--' && $stop_date!='--'){
//                $rota_schedules = $this->Reports_model->findSchedule($ignore_dates);
//                } 
//               // print_r($ignore_dates);
//               // print_r($rota_schedules);
//               //exit;
//               foreach($rota_schedules as $rota){
              
//               $timesheet[$i]['user_id'] = $rota['user_id'];
//               $timesheet[$i]['name'] = $rota['fname']." ".$rota['lname'];
//               $timesheet[$i]['shift_name'] = $rota['shift_name'];
//               $timesheet[$i]['date'] = $rota['date'];
//               $timesheet[$i]['shift_name'] = $rota['shift_name'];
//               $timesheet[$i]['shift_id'] ="";
//               $timesheet[$i]['start'] = '';
//               $timesheet[$i]['end'] = '';
//               $timesheet[$i]['status'] = $rota['status'];
//               $timesheet[$i]['unit_id'] = $params['unit_id'];
//               $timesheet[$i]['timelogid'] ='';
              
              
//               $tArray[]= array("user_id"=>$rota['user_id'],'name'=>$rota['fname']." ".$rota['lname'],
//                   'start'=>'','end'=>'',
//                   'shift_name'=>$rota['shift_name'],
//                   'shift_id'=>$rota['shift_id'],
//                   'unit_id'=>$params['unit_id'],
//                   'status'=>'',
//                   'timelogid'=>'', 'date'=>$rota['date']
//               );
  
//               } 
             
//           }
         
//       }
// /*       print "<pre>";
//   print_r($tArray); exit; */
//     }
//    else
//     {  
//       $params['unit_id']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
//       //$params['unit_id']=$this->session->userdata('unit_id');
//       $data['payroll'] = $this->Reports_model->findpayrolldata($params);   
//     }  
//   //print_r("<pre>");
//   //print_r($paydata);
//   //exit();
    
//     $data['status']=$this->input->post('status');

//     if(empty($data['payroll']))
//     {
//       $data['payroll_data']=array();
//     } 
//     else
//     {
//       $data['payroll_data']=$data['payroll']; 
//     } 
//      $data['jobrole'] = $this->Reports_model->fetchjobrole();
//   //print_r($data['payroll']);exit();
//    // $data['hours']=$this->Reports_model->rotahours($params); 
//       // print_r($payrol);exit();
//      $data['job_roles']=json_encode($jobe_roles); 
     
     
//      // {
// /*      $j=0;
 
//      $timesheet = array();
//      for($i=0;$i<count($data['payroll']);$i++) {
         
//         $j= $i+1;
     
                                            
//         $timesheet[$i]['user_id'] = $data['payroll'][$i]['user_id']; 
//         $timesheet[$i]['name'] = $data['payroll'][$i]['fname']." ".$data['payroll'][$i]['lname']; 
//         $timesheet[$i]['shift_name'] = $data['payroll'][$i]['shift_name']; 
//         $timesheet[$i]['date'] = $data['payroll'][$i]['date'];
//         $timesheet[$i]['shift_name'] = $data['payroll'][$i]['shift_name'];
//         $timesheet[$i]['shift_id'] = $data['payroll'][$i]['shift_id'];
//         $timesheet[$i]['start'] = $data['payroll'][$i]['creation_date'];
//         $timesheet[$i]['end'] = $data['payroll'][$j]['creation_date'];
//         $timesheet[$i]['status'] = $data['payroll'][$i]['status'];
//         $timesheet[$i]['unit_id'] = $params['unit_id'];
//         $timesheet[$i]['timelogid'] = $data['payroll'][$i]['timelogid'];
        
//         $date[]= $data['payroll'][$i]['date'];
//         $i= $j; 
      
//      } */
//     // print "<pre>";
//      //print_r($timesheet);     
//      //print_r(array_unique($date));
     
//  /*     $ignore_dates['dates'] = array_unique($date);
//      $ignore_dates['from_date'] =$params['from_date'];
//      $ignore_dates['to_date'] =$params['to_date'];
//      $ignore_dates['user_id'] =$params['user_id'];
//       if($params['from_date']!='--' && $params['to_date']!='--'){
//      $rota_schedules = $this->Reports_model->findSchedule($ignore_dates);
//      } */
//     // print_r($ignore_dates);
//     // print_r($rota_schedules);
//      //exit;
// /*      foreach($rota_schedules as $rota){
         
//          $timesheet[$i]['user_id'] = $rota['user_id'];
//          $timesheet[$i]['name'] = $rota['fname']." ".$rota['lname'];
//          $timesheet[$i]['shift_name'] = $rota['shift_name'];
//          $timesheet[$i]['date'] = $rota['date'];
//          $timesheet[$i]['shift_name'] = $rota['shift_name'];
//          $timesheet[$i]['shift_id'] ="";
//          $timesheet[$i]['start'] = '';
//          $timesheet[$i]['end'] = '';
//          $timesheet[$i]['status'] = $rota['status'];
//          $timesheet[$i]['unit_id'] = $params['unit_id'];
//          $timesheet[$i]['timelogid'] ='';
//          $i++;
//       } */
//     // print_r($timesheet);
//       $dates = array_column($timesheet, 'date');
      
//     //  array_multisort($dates, SORT_ASC, $timesheet);
//      ///print "<pre>";
//    /// print_r($timesheet);
//       $data['timesheetdata']=$tArray; 
//       //  exit;
     
//     $this->load->view('admin/reports/payrollreport_2',$data);
//     $result['js_to_load'] = array('reports/payrollreport.js');
//     $this->load->view('includes/home_footer',$result);
//     }

    
    public function insertadditionalhours()
    {  
        //print_r($this->input->post());exit();
        $params=array();
        $params['hours']=$this->input->post('additional_hours');
        $params['day_additional_hours']= $this->input->post('day_additional_hours');
        $params['night_additional_hours'] = $this->input->post('night_additional_hours');
        $params['comment']=$this->input->post('comment');
        $params['user_id']=$this->input->post('user_id');
        $params['date']=$this->input->post('date');
        $params['shift_id']=$this->input->post('shift_id');
        $params['unit_id']=$this->input->post('unit_id');
        $params['timelogid']=$this->input->post('timelogid');
        $params['upated_userid']=$this->session->userdata('user_id');
        $shift_hours=$this->input->post('shiftHour'); 
        $additional=$this->Rotaschedule_model->gethours($params);
        if(count($additional)>0)
        {
          $result=$this->Rotaschedule_model->updateadditionalhours($params); //updation
          $result_status=1;
        }
        else
        {
          $result=$this->Rotaschedule_model->insertadditionalhours($params); //insertion
          $result_status=2;
        }
        
        
        $activity_date= date('Y-m-d H:i:s');
        $activity_by=$this->session->userdata('user_id');
        $addedname=$this->User_model->findusername($activity_by); 
        $username=$this->User_model->findusername($params['user_id']);

        if($result_status==1)
        {
            if($params['day_additional_hours']=='')
            {

                $description= $addedname[0]['fname'].' '.$addedname[0]['lname'].' '.'has edited additional hours(Night)-'.' '.$params['hours'].' '.'and comment-'.' '.$params['comment'].' '.'for the user '.$username[0]['fname'].' '.$username[0]['lname'].' '.'for the date'.' '.$params['date'].'.';

            }
            else
            {
                $description= $addedname[0]['fname'].' '.$addedname[0]['lname'].' '.'has edited additional hours(Day)-'.' '.$params['hours'].' '.'and comment-'.' '.$params['comment'].' '.'for the user '.$username[0]['fname'].' '.$username[0]['lname'].' '.'for the date'.' '.$params['date'].'.';

            }

        }
        else
        {
            if($params['day_additional_hours']=='')
            {

                $description= $addedname[0]['fname'].' '.$addedname[0]['lname'].' '.'has added additional hours(Night)-'.' '.$params['hours'].' '.'and comment-'.' '.$params['comment'].' '.'for the user '.$username[0]['fname'].' '.$username[0]['lname'].' '.'for the date'.' '.$params['date'].'.';

            }
            else
            {
                $description= $addedname[0]['fname'].' '.$addedname[0]['lname'].' '.'has added additional hours(Day)-'.' '.$params['hours'].' '.'and comment-'.' '.$params['comment'].' '.'for the user '.$username[0]['fname'].' '.$username[0]['lname'].' '.'for the date'.' '.$params['date'].'.';

            }
        }

        
         

        $log=array( 
            'description'   => $description,  
            'activity_date' => $activity_date, 
            'activity_by'   => $activity_by,  
            'add_type'      => 'Adding additional hour', 
            'user_id'       => $params['user_id'], 
            'unit_id'       => $params['unit_id'],
            'primary_id'    => $params['timelogid'],
            'creation_date' => date('Y-m-d H:i:s') 
          );  

        //print_r($log);exit();
        if($result=='true')
        {
          $this->Activity_model->insertactivity($log);
        }
        
        // print_r("<pre>");
        // print_r($shift_hours); print '<br>';
        // print_r($params['hours']); print '<br>';
 
        if($shift_hours=='' || $shift_hours==0) 
        { //print 'hii';
           $time= $params['hours'];
        }
        else
        { //print 'hello';

           $time=AddTimesnew1($shift_hours,getPayrollformat1($params['hours']));
           //$time = strtotime($shift_hours)+strtotime($params['hours'])-strtotime('00:00:00');
        }
        //print_r($time);exit();
        $total=$time;
        //$total=AddTimesnew($shift_hours,$params['hours']);
        //print_r(getPayrollformat1($total));exit(); 
        header('Content-Type: application/json');
        echo json_encode(array('status' => $result,'hours'=>settimeformat(getPayrollformat1($total))));
        exit();
    }

     
    public function extrahourreport()
    {
    $this->auth->restrict('Admin.Report.Extrahours');
    $header = array();
    $header['headername']=" : Extra Hour Report";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    if($this->input->post('month')=='')
    {
        $data['month']=date('m');
    }
    else
    {
         $data['month']=$this->input->post('month');
    }
    if($this->input->post('year')=='')
    {
        $data['start_year']=date('Y');
    }
    else
    {
        $data['start_year']=$this->input->post('year');
    }
    
    // if($this->session->userdata('user_id')==1)
    // { 
    //     $data['unit'] = $this->User_model->fetchCategoryTree('');   
    // }
    //     else
    // {
    //     $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
    // }
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }

    $params['user_id']=$this->input->post('user'); 
    $params['month']=$this->input->post('month'); 
    $params['year']=$this->input->post('year'); 
    $params['unit']=$this->input->post('unitdata');
    $data['user_post']=$this->input->post('user');
    if($params['unit']!='none'){
        $data['user']=$this->Reports_model->finduserdata($params['unit'],0);   
      }
    $data['payroll'] = $this->Reports_model->findextrahourdata($params);  
    if(empty($data['payroll']))
    {
      $data['payroll_data']=array();
    } 
    else
    {
      $data['payroll_data']=$this->Reports_model->findextrahourdata($params); 
    } 
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
   // $data['hours']=$this->Reports_model->rotahours($params); 
      // print_r($payrol);exit();
     $data['status']=$this->input->post('status');
    $this->load->view('admin/reports/extrahourreport',$data);
    $result['js_to_load'] = array('reports/extrahourreport.js');
    $this->load->view('includes/home_footer',$result);
    }

   //  public function lastlogin()
   //  {
   //  $this->auth->restrict('Admin.Report.Add');
   //  $this->load->view('includes/home_header');
   //  $result = array(); 
   //  $this->load->helper('user');
   //  $data=array(); 

    
   //  if($this->session->userdata('user_id')==1)
   //  { 
   //          $data['unit'] = $this->User_model->fetchCategoryTree('');   
   //  }
   //          else
   //  {
   //          $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); 
   //  }
   //  $data['jobrole'] = $this->Reports_model->fetchjobrole();   
   // ;
   //  $params['user_id']=$this->input->post('unit'); 
   //  $params['month']=$this->input->post('month'); 
   //  $params['year']=$this->input->post('year'); 
   //  $params['unit']=$this->input->post('unitdata');
   //  $data['user_post']=$this->input->post('user');
   //  $data['user']=$this->Reports_model->finduserdata($params['unit']);   
   //  $data['payroll_data'] = $this->Reports_model->findpayrolldata($params); 
   // // print_r($this->input->post());exit();
   //   if(empty($this->input->post())) 
   //   {
   //    $data['login']=array();
   //   } 
   //   else
   //   {
   //    $data['login']=$this->
   //   }
   // // $data['hours']=$this->Reports_model->rotahours($params); 
   //    // print_r($payrol);exit();
   //  $this->load->view('admin/reports/lastlogindetails',$data);
   //  //$result['js_to_load'] = array('reports/lastloginreport.js');
   //  $this->load->view('includes/home_footer',$result);
   //  }

    public function lastlogin() 
    { 
    $this->auth->restrict('Admin.Report.Lastlogin');
    $header = array();
    $header['headername']=" : Last login Report";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
      // $id=$this->session->userdata('user_id');  
       
      // if($this->session->userdata('user_type') ==1)
      //    { 
      //    $data['user']=$this->Reports_model->finduser(); 
      //    }
      //    else
      //    {  

      //         $userUnits['units'] = $this->Reports_model->getUnitIdOfUser($id); 
      //         if(empty($userUnits['units']))
      //         { 
      //           $data['user']=array();
      //         }
      //         else
      //         { 
      //           foreach ($userUnits['units'] as $value) 
      //           { 
      //           $data['user']=$this->Reports_model->allUsers($value['unit_id']);   
                    
      //           }  
      //         }
      //     } 
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $data['jobrole'] = $this->Reports_model->fetchjobrole();   
    $this->load->view('admin/reports/lastlogin',$data);
    $result['js_to_load'] = array('reports/lastloginreport.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function lastloginreport()
    {

       $columns = array(  
                            0=> 'id', 
                            1=> 'name',
                            2=> 'unit_name',
                            3=> 'designation_name', 
                            4=> 'lastlogin_date', 
                            5=> 'status',
                            6=> 'account_accessed', 
                            7=> 'user_status', 
 

                        );

//print_r($this->input->post());
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];  
        $dir = $this->input->post('order')[0]['dir'];
        $unit = $this->input->post('unit');  
        $jobrole = $this->input->post('jobrole'); 
        $status= $this->input->post('status');
      //  if(!empty($status))
      //  {  
      //   foreach ($status as $value) {
      //     print_r($value);
      //   }
      //   exit();
      // }
        $user_status= $this->input->post('user_status');
        $password_status= $this->input->post('password_status');
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
        {  
  
            
           //print_r($this->input->post('search')['value']);   
            if(empty($this->input->post('search')['value']))
            {     
                $totalData = $this->Reports_model->allemployeelastlogin_count($unit,$jobrole,$status,$password_status,$user_status);   
                $totalFiltered = $totalData;        
                $posts = $this->Reports_model->allemployeelastlogin($limit,$start,$order,$dir,$unit,$jobrole,$status,$password_status,$user_status);   
            }
            else 
            {
                $search = $this->input->post('search')['value'];  
                $totalData = $this->Reports_model->allemployeelastloginsearch_count($search,$units,$jobrole,$status,$password_status,$user_status);
                $totalFiltered = $totalData; 
                $posts =  $this->Reports_model->allemployeelastlogin_search($limit,$start,$search,$unit,$jobrole,$status,$password_status,$user_status); 
              
            }
        }
         else
        { 
            $id=$this->session->userdata('user_id'); 
            $userUnits = $this->User_model->getUnitIdOfUser($id);  
            $units=$userUnits;
             
            //print_r($this->input->post('search')['value']);   
            if(empty($this->input->post('search')['value']))
            {      
                $totalData = $this->Reports_model->allemployeelastlogin_count($units,$jobrole,$status,$password_status,$user_status);
                $totalFiltered = $totalData;      
                $posts = $this->Reports_model->allemployeelastlogin($limit,$start,$order,$dir,$units,$jobrole,$status,$password_status,$user_status);   
            }
            else 
            {
                $search = $this->input->post('search')['value']; 
                $totalData = $this->Reports_model->allemployeelastloginsearch_count($search,$units,$jobrole,$status,$password_status,$user_status);
                $totalFiltered = $totalData;  
                $posts =  $this->Reports_model->allemployeelastlogin_search($limit,$start,$search,$units,$jobrole,$status,$password_status,$user_status); 
              
            }



        }
 //print($posts);exit();
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                if($post->lastlogin_date!='')
                    {$last_date=date("d/m/Y H:i:s",strtotime($post->lastlogin_date)); $status="Yes";} else
                    {$last_date=" ";$status="No";}
                    
                    
                    
             /*    if($post->password_change_status==0 || $post->password_change_status==NULL)
                  { $password_status="No";}
                else
                  { $password_status="Yes";} */
                
                  $pstatus =  $this->Reports_model->passwordstatus($post->id); 
                  if(!empty($pstatus))
               {
                  if($pstatus[0]['password_change_status']==1  )
                  {
                      $password_status="Yes";
                  }
                  else
                  {
                      $password_status="No";
                  }
                }
                else
                {
                    $password_status="No";
                }
                if($post->status==2){$user_status="Inactive";}elseif($post->status==3){ $user_status="Deleted";}else{ $user_status='Active';}

                $nestedData['user_id'] = $post->id;  
                $nestedData['name'] = $post->fname.' '.$post->lname; 
                $nestedData['unit_name'] = $post->unit_name;
                $nestedData['designation_name'] = $post->designation_name; 
                $nestedData['lastlogin_date'] = $last_date; 
                $nestedData['status'] = $status; 
                $nestedData['account_accessed'] =$password_status ; 
                $nestedData['user_status'] =$user_status ; 
                
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );   
       // print($json_data);exit();
        echo json_encode($json_data); 
    exit();
    }

     public function finduserloginlist()
   {
      $json = array();
            $unit=$this->input->post('unit');
            $jobrole = $this->input->post('jobrole'); 
            //print_r($this->input->post());exit();
            $result=$this->Reports_model->finduserloginlist($unit,$jobrole);   
    // print($result);exit();
              foreach ($result as $row)
              {
                $nestedData['name'] = $row['fname']." ".$row['lname']; 
                $nestedData['group_name'] =$row['group_name'];
                $nestedData['designation_name'] = $row['designation_name']; 
                
                $data[] = $nestedData; 
              } 

              $json = array( 
                    "data"            => $data   
                    );    
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
   }
   public function weekOfMonth($qDate) {
    $dt = strtotime($qDate);
    $day  = date('j',$dt);
    $month = date('m',$dt);
    $year = date('Y',$dt);
    $totalDays = date('t',$dt);
    $weekCnt = 0;
    $retWeek = 0;
    for($i=1;$i<=$totalDays;$i++) {
        $curDay = date("N", mktime(0,0,0,$month,$i,$year));
        if($curDay==7) {
            if($i==$day) {
                $retWeek = $weekCnt+1;
            }
            $weekCnt++;
        } else {
            if($i==$day) {
                $retWeek = $weekCnt;
            }
        }
    }
    return $retWeek;
   }
   public function createRotaHtml($data,$rota_logid){
    $count = count($data);
    $html = '';
    if($count > 0){
      $html .= '<p style="text-align:center;font-weight:normal;font-family:sans-serif !important;">Week '.$this->weekOfMonth($data[0]['start_date']).'</p>'.
        '<table  border="0" cellpadding="0" cellspacing="0"  style="margin: auto; width: 100%; font-weight: normal; font-family:sans-serif !important"><thead><tr><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Shift Name</th><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Date</th></tr></thead><tbody>';
       for($k=0;$k<$count;$k++){
         $result = $this->Rota_model->findUnitShiftFromRotaHistory($data[$k]['user_id'],$rota_logid,$data[$k]['date']);
         $html .= '<td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;">'.$result[0]['shift_name'].'</td>'.
           '<td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;">'.$this->corectDateFormat($data[$k]['date']).'</td></tr>';
       }
    }
    return $html;
   }
    public function rotahistoryreport(){
      $columns = array(  
        0=> 'user_name',
        1=> 'type',
        2=> 'rota_details',
        3=> 'previous_history',        
        4=> 'updated_by',
        5=> 'status',
        6=> 'updated_datetime',
        // 6=> 'enrolled_status',  
      );
      $limit = $this->input->post('length');
      $start = $this->input->post('start');
      $order = $columns[$this->input->post('order')[0]['column']];  
      $dir = $this->input->post('order')[0]['dir'];
      $search = $this->input->post('search')['value'];
      $unit = $this->input->post('unit');  //print_r($unit);exit();
      $jobrole = $this->input->post('jobrole');
      $year = $this->input->post('year');
      $month = $this->input->post('month'); //
      $params = array();
      $params['jobrole']=$jobrole;
      $params['year'] = $year;
      $params['month'] = $month;
      if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18)
      {
      $params['unit'] = $unit;
      $rota_history_without_limit = $this->Rota_model->findRotaHistory('','',$search,$params); //print_r($rota_history_without_limit);exit();
      $totalData = count($rota_history_without_limit);
      $rota_history = $this->Rota_model->findRotaHistory($limit,$start,$search,$params); 
      }
      else
      {
       if($this->input->post('unit')=='')
       {
         $unit_id=$this->session->userdata('unit_id');
       }
       else
       {
         $unit_id=$this->input->post('unit');
       }
       $params['unit'] =  $unit_id; 
         $parent_unit=$this->checkUnitHasParentunit($unit_id); //print_r($parent_unit);exit();
         if($parent_unit[0]['parent_unit']==0)
         {
          $rota_history_without_limit = $this->Rota_model->findRotaHistory('','',$search,$params); //print_r($rota_history_without_limit);exit();
          $totalData = count($rota_history_without_limit);
          $rota_history = $this->Rota_model->findRotaHistory($limit,$start,$search,$params); 
         }
         else
         {
          $rota_history_without_limit = $this->Rota_model->findRotaHistorybysubunit('','',$search,$params); //print_r($rota_history_without_limit);exit();
          $totalData = count($rota_history_without_limit);
          $rota_history = $this->Rota_model->findRotaHistorybysubunit($limit,$start,$search,$params); 

         }
      }

     
 
       //print_r($rota_history);exit();
      $data = array();
      if(count($rota_history) > 0)
      {
        for($i=0;$i<count($rota_history);$i++)
        {
          $rota_prev_history = $this->Rota_model->getPreviousHistory($rota_history[$i]['user_id'],$rota_history[$i]['id']);
          $prev_history = $this->createRotaHtml($rota_prev_history,$rota_history[$i]['id']);
          $date_time = $rota_history[$i]['updated_datetime'];
          $date_time_array = explode(' ', $date_time);
          $actual_date = $this->corectDateFormat($date_time_array[0]);
          $new_updated_time = $actual_date." ".$date_time_array[1];
          $nestedData['user_name'] = $rota_history[$i]['fname']." ".$rota_history[$i]['lname'];
          $nestedData['type'] = $rota_history[$i]['type'];
          $nestedData['rota_details'] = $rota_history[$i]['rota_details'];
          $nestedData['previous_history'] = $prev_history;
          $nestedData['updated_by'] = $this->getSingleUser($rota_history[$i]['updated_by']);
          $nestedData['status'] = $this->getRotaStatus($rota_history[$i]['rota_id']);
          $nestedData['updated_datetime'] = $new_updated_time;
          $data[] = $nestedData;
        }
      }
      $json_data = array(
        "draw"            => intval($this->input->post('draw')),  
        "recordsTotal"    => intval($totalData),  
        "recordsFiltered" => intval($totalData), 
        "data"  => $data   
        );   
      echo json_encode($json_data); 
      exit();
    }
    public function getRotaStatus($id){
      $this->load->model('Rota_model');
      $result=$this->Rota_model->getRotaStatus($id);
      return $result;
    }
    public function getSingleUser($id){
      $this->load->model('User_model');
      $result=$this->User_model->getSingleUser($id);
      return $result[0]['fname']." ".$result[0]['lname'];
    }
     public function checkUnitHasParentunit($id){
    $result = $this->Dashboard_model->checkUnitHasParentunit($id);
    return $result;
  }
    public function rotahistorylist(){
      $this->auth->restrict('Admin.Report.Rotahistory');
      $header = array();
      $header['headername']=" : Employee List Report";
      $this->load->view('includes/home_header',$header);
      $result = array(); 
      $this->load->helper('user');
      $data=array();
      $data['year']=date('Y');
      $data['month']=date('m');
       // if($this->session->userdata('user_id')==1)
       //  { 
       //      $data['unit'] = $this->User_model->fetchunit('');   
       //  }
       //      else
       //  {
       //      $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); 
       //  } 
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
      $data['jobrole'] = $this->Reports_model->fetchjobrole();   
      $this->load->view('admin/reports/rotahistorylist',$data);
      $result['js_to_load'] = array('reports/rotahistorylist.js');
      $this->load->view('includes/home_footer',$result);
    }

    public function employeelist()
    { 
    $this->auth->restrict('Admin.Report.Employeelist');
    $header = array();
    $header['headername']=" : Employee List Report";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
      $this->load->helper('user');
    $data=array();  

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $data['jobrole'] = $this->Reports_model->fetchjobrole();  
    $this->load->view('admin/reports/employeelist',$data);
    $result['js_to_load'] = array('reports/userlist.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function employeelistreport()
    {
       $columns = array(  
                            0=> 'id', 
                            1=> 'name',
                            2=> 'unit_name',
                            3=> 'designation_name', 
                            4=> 'address1',
                            5=> 'address2',
                            6=> 'city',
                            7=> 'postcode',
                            8=> 'mobile_number',
                            9=> 'status', 
                            10=> 'enrolled_status', 
                            12=> 'pass_enable',

                        );

//print_r($this->input->post());
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];  
        $dir = $this->input->post('order')[0]['dir'];
        $unit = $this->input->post('unit');  
        $jobrole = $this->input->post('jobrole');
        $enrolled_status = $this->input->post('enrolled_status'); //print_r($enrolled_status);exit();
        $pass_enable = $this->input->post('pass_enable');
        $status = $this->input->post('status');

        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
        {    
  
            $totalData = $this->Reports_model->allemployee_count($unit,$jobrole,$status,$enrolled_status,$pass_enable);   
            $totalFiltered = $totalData;  
            //print_r($this->input->post('search')['value']);   
            if(empty($this->input->post('search')['value']))
            {      
                $totalData = $this->Reports_model->allemployee_count($unit,$jobrole,$status,$enrolled_status,$pass_enable);   
                $totalFiltered = $totalData;       
                $posts = $this->Reports_model->allemployee($limit,$start,$order,$dir,$unit,$jobrole,$status,$enrolled_status,$pass_enable);   
            }
            else 
            {
                $search = $this->input->post('search')['value'];
                $totalData = $this->Reports_model->allemployeesearch_count($search,$unit,$jobrole,$status,$enrolled_status,$pass_enable); 
                $totalFiltered = $totalData;   
                $posts =  $this->Reports_model->allemployee_search($limit,$start,$search,$unit,$jobrole,$status,$enrolled_status,$pass_enable); 
            }
        }
         else
        {  
            $id=$this->session->userdata('user_id'); 
            $userUnits = $this->User_model->getUnitIdOfUser($id);  // print_r($userUnits);exit();
            $units=$userUnits;

            
            //print_r($this->input->post('search')['value']);   
            if(empty($this->input->post('search')['value']))
            {      
                $totalData = $this->Reports_model->allemployee_count($units,$jobrole,$status,$enrolled_status,$pass_enable);
                
                $totalFiltered = $totalData;       
                $posts = $this->Reports_model->allemployee($limit,$start,$order,$dir,$units,$jobrole,$status,$enrolled_status,$pass_enable);   
            }
            else 
            {
                $search = $this->input->post('search')['value'];  
                $totalData = $this->Reports_model->allemployeesearch_count($search,$unit,$jobrole,$status,$enrolled_status,$pass_enable);   
                $totalFiltered = $totalData;  
                $posts =  $this->Reports_model->allemployee_search($limit,$start,$search,$units,$jobrole,$status,$enrolled_status,$pass_enable); 
              
            }



        }
        //print_r($posts);exit();
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
              if($post->status==2){$status="Inactive";}elseif($post->status==3){ $status="Deleted";}else{ $status='Active';} 
              if($post->thumbnail==''){$enrolled_status="Unenrolled";}elseif($post->thumbnail!=''){ $enrolled_status="Enrolled";}
              if($post->pass_enable==1){$pass_enable_status="Enabled";}else{ $pass_enable_status="Disabled";}

                $nestedData['user_id'] = $post->id;  
                $nestedData['name'] = $post->fname.' '.$post->lname; 
                $nestedData['unit_name'] = $post->unit_name;
                $nestedData['designation_name'] = $post->designation_name; 
                $nestedData['address1'] = $post->address1; 
                $nestedData['address2'] = $post->address2; 
                $nestedData['city'] = $post->city; 
                $nestedData['postcode'] = $post->postcode; 
                $nestedData['mobile_number'] = $post->mobile_number; 
                $nestedData['status'] = $status;
                $nestedData['enrolled_status'] = $enrolled_status;
                $nestedData['password_enabled'] = $pass_enable_status;
                
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );   
        echo json_encode($json_data); 
    exit();
    }

     public function finduserlist()
   {
      $json = array();
            $unit=$this->input->post('unit');
            $jobrole = $this->input->post('jobrole'); 
            //print_r($this->input->post());exit();
                $result=$this->Reports_model->finduserlist($unit,$jobrole);   
     
              foreach ($result as $row)
              {
                $nestedData['name'] = $row['fname']." ".$row['lname']; 
                $nestedData['group_name'] =$row['group_name'];
                $nestedData['designation_name'] = $row['designation_name']; 
                
                $data[] = $nestedData; 
              } 

              $json = array( 
                    "data"            => $data   
                    );    
 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
   }


    public function employeelistdetailed()
    {
    $this->auth->restrict('Admin.Report.Employeedetailedlist');
    $header = array();
    $header['headername']=" : Employee List Detailed Report";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    // $id=$this->session->userdata('user_id');
    // if($this->session->userdata('user_type') ==1)
    //    { 
    //    $data['user']=$this->Reports_model->finduser(); 
    //    }
    //    else
    //    {  

    //         $userUnits['units'] = $this->Reports_model->getUnitIdOfUser($id); 
    //         if(empty($userUnits['units']))
    //         { 
    //           $data['user']=array();
    //         }
    //         else
    //         { 
    //           foreach ($userUnits['units'] as $value) 
    //           { 
    //           $data['user']=$this->Reports_model->allUsers($value['unit_id']);   
                  
    //           }  
    //         }
    //     }
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $data['jobrole'] = $this->Reports_model->fetchjobrole();  
  $this->load->view('admin/reports/employeelistdetailed',$data);
  $result['js_to_load'] = array('reports/userlistdetailed.js');
    $this->load->view('includes/home_footer',$result);
    }

    public function employeelistdetailedreport()
    {
       $columns = array(  
                            0=>'id', 
                            1=> 'name',
                            2=> 'email',
                            3=> 'mobile_number',
                            4=> 'dob',
                            5=> 'gender',
                            6=> 'Ethnicity',
                            7=> 'Visa_status',
                            8=> 'address',
                            9=> 'country',
                            10=> 'city',
                            11=> 'postcode',
                            12=> 'next_of_kin',
                            13=> 'group',
                            14=> 'weekly_hours',
                            15=> 'annual_holiday_allowance',
                            16=> 'actual_holiday_allowance',
                            17=> 'shift_name',
                            18=> 'start_date',
                            19=> 'end_date',
                            20=> 'payrollid',
                            21=> 'payment_type',
                            22=> 'basic_pay_rate',
                            23=> 'unit_name',
                            24=> 'designation_name',
                            25=> 'status',
                            26=> 'enrolled_status',
                        );

        //print_r($columns);
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];  
        $dir = $this->input->post('order')[0]['dir'];
        $unit = $this->input->post('unit');
        $jobrole = $this->input->post('jobrole');
        $enrolled_status = $this->input->post('enrolled_status');
        $status = $this->input->post('status');
        //find branches
        //$branch['unit']=$this->Unit_model->findBranch($unit); 

        // if($this->session->userdata('user_type') ==1)
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
        {
  
            $totalData = $this->Reports_model->allemployeedetails_count($unit,$jobrole,$status,$enrolled_status);
                
            $totalFiltered = $totalData;   
            if(empty($this->input->post('search')['value']))
            {  
                $totalData = $this->Reports_model->allemployeedetails_count($unit,$jobrole,$status,$enrolled_status);
                
                $totalFiltered = $totalData;         
                $posts = $this->Reports_model->allemployeedetails($limit,$start,$order,$dir,$unit,$jobrole,$status,$enrolled_status);   
            }
            else 
            {
                $search = $this->input->post('search')['value'];  
                $totalData = $this->Reports_model->allemployeedetailssearch_count($search,$unit,$jobrole,$status,$enrolled_status);
                
                $totalFiltered = $totalData;     
                $posts =  $this->Reports_model->allemployeedetails_search($limit,$start,$search,$unit,$jobrole,$status,$enrolled_status); 
              
            }
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        {
  
            $totalData = $this->Reports_model->allemployeedetails_count($unit,$jobrole,$status,$enrolled_status);
                
            $totalFiltered = $totalData; 
            if($this->input->post('unit')!='')
            {  
                if(empty($this->input->post('search')['value']))
                {  
                    $totalData = $this->Reports_model->allemployeedetails_count($unit,$jobrole,$status,$enrolled_status);
                    
                    $totalFiltered = $totalData;         
                    $posts = $this->Reports_model->allemployeedetails($limit,$start,$order,$dir,$unit,$jobrole,$status,$enrolled_status);   
                }
                else 
                {
                    $search = $this->input->post('search')['value'];  
                    $totalData = $this->Reports_model->allemployeedetailssearch_count($search,$unit,$jobrole,$status,$enrolled_status);
                    
                    $totalFiltered = $totalData;     
                    $posts =  $this->Reports_model->allemployeedetails_search($limit,$start,$search,$unit,$jobrole,$status,$enrolled_status); 
                  
                }
            }
            else
            {
              $totalFiltered = ' ';
              $posts=array();
            }
        }
        else
        {
            $id=$this->session->userdata('user_id'); 
            $userUnits = $this->User_model->getUnitIdOfUser($id);  
            $units=$userUnits;
            
            //print_r($this->input->post('search')['value']);   
            if(empty($this->input->post('search')['value']))
            {     
                $totalData = $this->Reports_model->allemployeedetails_count($units,$jobrole,$status,$enrolled_status);
                
            $totalFiltered = $totalData;        
                $posts = $this->Reports_model->allemployeedetails($limit,$start,$order,$dir,$units,$jobrole,$status,$enrolled_status);   
            }
            else 
            {
                $search = $this->input->post('search')['value'];   
                  $totalData = $this->Reports_model->allemployeedetailssearch_count($search,$unit,$jobrole,$status,$enrolled_status);
                
                $totalFiltered = $totalData; 
                $posts =  $this->Reports_model->allemployeedetails_search($limit,$start,$search,$units,$jobrole,$status,$enrolled_status); 
              
            }

        } 
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
               if($post->status==2){$status="Inactive";}elseif($post->status==3){ $status="Deleted";}else{ $status='Active';} 
               if($post->thumbnail==''){$enrolled_status="Unenrolled";}elseif($post->thumbnail!=''){ $enrolled_status="Enrolled";}
               if($post->visa_status==2){$visa_status="Sponsored";}elseif($post->visa_status==1){ $visa_status="Migrant Worker";}else{ $visa_status='NA';} 

                $nestedData['user_id'] = $post->id;  
                $nestedData['name'] = $post->fname.' '.$post->lname;
                $nestedData['email'] = $post->email;
                $nestedData['mobile_number'] = $post->mobile_number; 
                if($post->dob=='0000-00-00' || $post->dob=='')
                {
                  $dob='00/00/0000';  
                }
                else
                {
                  $dob=date("d/m/Y", strtotime($post->dob));
                   
                }
                 if($post->start_date=='0000-00-00' || $post->start_date=='')
                {
                  $start_date='00/00/0000';  
                }
                else
                {
                  $start_date=date("d/m/Y", strtotime($post->start_date));
                   
                }
                 if($post->final_date=='0000-00-00' || $post->final_date=='')
                {
                  $end_date=' ';  
                }
                else
                {
                  $end_date=date("d/m/Y", strtotime($post->final_date));
                   
                }
                 if($post->gender=='M')
                {
                  $gender='Male';  
                }
                else
                {
                  $gender='Female';
                   
                }

                $nestedData['dob'] = $dob;
                $nestedData['address'] = $post->address1;
                $nestedData['gender'] = $gender;
                $nestedData['Ethnicity'] = $post->Ethnicity;
                $nestedData['Visa_status'] = $visa_status;
                $nestedData['country'] = $post->country; 
                $nestedData['city'] = $post->city; 
                $nestedData['postcode'] = $post->postcode;
                $nestedData['next_of_kin'] = $post->kin_name.','.$post->kin_phone.','.$post->kin_address;
                $nestedData['group'] = $post->group_name;
                $nestedData['weekly_hours'] = $post->weekly_hours; 
                $nestedData['annual_holiday_allowance'] = $post->annual_holliday_allowance; 
                $nestedData['actual_holiday_allowance'] = $post->actual_holiday_allowance;
                $nestedData['shift_name'] = $post->shift_name; 
                $nestedData['start_date'] = $start_date;
                $nestedData['end_date'] = $end_date;
                $nestedData['payrollid'] = $post->payroll_id;
                $nestedData['payment_type'] = $post->payment_type; 
                $nestedData['basic_pay_rate'] = $post->day_rate;
                $nestedData['unit_name'] = $post->unit_name;
                $nestedData['designation_name'] = $post->designation_name; 
                $nestedData['status'] =  $status; 
                $nestedData['enrolled_status'] = $enrolled_status;
                

                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );   
        echo json_encode($json_data); 

    }

    //  public function finduserbyunit()
    // {
    //     //$unit=$this->input->post('unit'); 
    //     $status=$this->input->post('status'); print_r($status);exit();
    //     $result=$this->User_model->finduserbyunit($unit,$status);  
    //     if(empty($result))
    //     { 
    //        $json = []; 
    //     }
    //     else
    //     {
    //         foreach ($result as $row)
    //           {
    //             if($row['status']==1) {$stat="Active"; } else if($row['status']==2) {$stat="Inactive";} else{ $stat="Deleted";}
    //              $delete="'".$row['id']."','".$row['fname']."'"; 

    //              if($row['lastlogin_date']!=''){$last_login=date("d/m/Y H:i:s",strtotime($row['lastlogin_date']));
    //                                             } else { $last_login=""; }

    //               if($row['id']==1)
    //               {
    //                 $json[] =array($row['id'],'<a href="user/edituser/'.$row['id'].'">'.$row['fname'].' '.$row['lname'],$row['email'],$row['payroll_id'],$row['payment_type'],$row['unit_name'],$row['group_name'],$row['designation_code'],$last_login,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="user/edituser/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> ');

    //               }
    //               else
    //               {

    //                  $json[] =array($row['id'],'<a href="user/edituser/'.$row['id'].'">'.$row['fname'].' '.$row['lname'],$row['email'],$row['payroll_id'],$row['payment_type'],$row['unit_name'],$row['group_name'],$row['designation_code'],$last_login,$stat,'<a class="Edit" data-id="'.$row['id'].'" href="user/edituser/'.$row['id'].'" title="Edit"><i class="fas fa-edit"></i></a> '.' '.'<a href="javascript:void(0);" data-id="'.$row['id'].'" title="Delete" onclick="deleteFunction('.$delete.')">'.'<i class="fa fa-trash"></i>'.' '.'<a class="Edit" data-id="'.$row['id'].'" href="user/changepassword/'.$row['id'].'" target="_blank" title="Change password"><i class="fas fa-key"></i></a>'.' '.'<a class="photochange" data-id="'.$row['id'].'" href="user/changepicture/'.$row['id'].'" target="_blank" title="Change profile picture"><i class="fas fa-image"></i></a>');
    //               }
    //           } 
    //     }
 
    //       header("Content-Type: application/json");
    //       echo json_encode($json);
    //       exit();

    // }




     public function annualleave()
    {
    $this->auth->restrict('Admin.Report.Annualleave');
    $this->load->model('User_model');
    $header = array();
    $header['headername']=" : Annual Leave Individual Report";
    $this->load->view('includes/home_header',$header);
    $result = array(); 
    $this->load->helper('user');
    $data=array(); 
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
    {
          $data['unit'] = $this->User_model->fetchCategoryTree();  
    }
    // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
    else if($this->session->userdata('subunit_access')==1)
    { //if unit administrator
        if($sub!=0 || $parent!=0) //unit administrator in sub unit
        {   
            if($sub==0)
            {
                   $sub=$parent;
            }
            else
            {
                   $sub=$sub;
            }
            $data['unit'] = $this->User_model->fetchSubTree($sub);  
         }
         else
         {    
            $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
         }

    }
    else
    {
       $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                        
    }
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $this->load->view('admin/reports/annualleave',$data);
    $result['js_to_load'] = array('reports/annualleave.js');
    $this->load->view('includes/home_footer',$result);
    }

     public function finduserdataforall()
    {
    $this->load->model('Reports_model');
    $unit_id=$this->input->post('unit_id'); 
    $status=$this->input->post('status');
    $unit_data =$this->Reports_model->finduserdataforall($unit_id,$status);   
    echo json_encode($unit_data);
    }

     public function finduserdataforallforreport()
    {
    $this->load->model('Reports_model');
    $unit_id=$this->input->post('unit_id'); 
    $status=$this->input->post('status');
    $unit_data =$this->Reports_model->finduserdataforallforreport($unit_id,$status);   
    echo json_encode($unit_data);
    }


    public function finduserdata()
    {
    $this->load->model('Reports_model');
    $unit_id=$this->input->post('unit_id'); 
    $status=$this->input->post('status');
    $unit_data =$this->Reports_model->finduserdata($unit_id,$status);   
    // foreach ($unit_data as $value) {
    //    $data['name']=$value['fname'].' '.$value['lname'];
    //    $data['id']=$value['user_id'];
    // }   
    echo json_encode($unit_data);
    }
    
    public function finduserdatanew()
    {
    $this->load->model('Reports_model');
    $unit_id=$this->input->post('unit_id'); 
    $unit_data =$this->Reports_model->finduserdatanew($unit_id);   
    // foreach ($unit_data as $value) {
    //    $data['name']=$value['fname'].' '.$value['lname'];
    //    $data['id']=$value['user_id'];
    // }   
    echo json_encode($unit_data);
    }

    public function findunitdata()
    {
    $this->load->model('User_model');
    $unittype=$this->input->post('unit_id'); 
    $unit_data=$this->User_model->fetchCategoryTreeforavailability(' ',' ',' ',$unittype); 
    //print_r($unittype);exit();
    //$unit_data =$this->Reports_model->findunitdata($unittype);   
    // foreach ($unit_data as $value) {
    //    $data['name']=$value['fname'].' '.$value['lname'];
    //    $data['id']=$value['user_id'];
    // }   
    echo json_encode($unit_data);
    }

    public function finduserdetails()
    {
      $this->load->model('Reports_model');
      $user_id=$this->input->post('user_id'); 
      $user_data = $this->Reports_model->finduserdetails($user_id); 
      $message = "";
      $dates = '';
      foreach ($user_data as $value) {  
        if($value['status'] == 1)
        {
          $status = "Accepted";
        }
        else if($value['status'] == 2)
        {
          $status = "Rejected";
        }
        else if($value['status'] ==NULL)
        {
          $status = "";
        }
        else if($value['status'] ==3)
        {
          $status = "Cancelled";
        }
        else if($value['status'] == 0)
        {
          $status = "Pending";
        }
        if($value['from_date']=='' || $value['to_date']=='')
        {
          $from_date='';
          $to_date='';
        } 
        else
        {
          $result = $this->Reports_model->getLeave($value['from_date'],$value['to_date'],$value['user_id']);
          if(count($result)>0){
            if(count($result) == 1){
              $message = "You are taken sick leave on ".$this->corectDateFormat($result[0]['date']);
            }else{
              for ($k = 0; $k < count($result); $k++){
                $dates = $dates.' , '.$this->corectDateFormat($result[$k]['date']);
              }
              $message = "You are taken sick leave on ".$dates;
            }
          }
          $from_date=date("d/m/Y",strtotime($value['from_date'])); 
          $to_date=date("d/m/Y",strtotime($value['to_date']));   
        }
            $total_hours=$value['days'];   
            $total_hours=str_replace(":",".",$total_hours);
            $total_hours=number_format(getPayrollformat(number_format($total_hours,2),2),2);

        $data[] =array($value['user_id'],$value['fname']." ".$value['lname'],$from_date,$to_date,$total_hours,$status,$message,$value['annual_holliday_allowance']);
      }   
      echo json_encode($data); 
    }
    public function corectDateFormat($date){
      $my_str = $date;
      $date_array = array();
      $date_array = explode("-", $my_str);
      $date_with_slash = $date_array[2]."/".$date_array[1]."/".$date_array[0];
      return $date_with_slash;
    }
     public function annualleaveallstaff()
    {
        $this->auth->restrict('Admin.Report.Annualleaveallstaff'); 
        $header = array();
        $header['headername']=" : Annual Leave All Employees Report";
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $jobe_roles=array();
        $this->load->helper('user');
        $data   =array();
        $params =array();
        $id = ''; 
        
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }

        $data['start_date']=date('d/m/Y');  
        $data['end_date']=date('d/m/Y');  
        $data['jobrole'] = $this->Reports_model->fetchjobrole();  
       $data['job_roles']=json_encode($jobe_roles);
       /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
       $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
       /*End*/
      $this->load->view('admin/reports/annualleaveallstaff',$data);
      $result['js_to_load'] = array('reports/annualleaveallstaff.js');
      $this->load->view('includes/home_footer',$result);
    }

    public function annualleaveallstaffreport()
    {
        $dates = '';
        $message = '';
        $result = array();
        $columns = array(  
                            0=> 'user_id',
                            1=> 'hr_ID',
                            2=> 'name',
                            3=> 'unit_name',
                            4=> 'from_date',
                            5=> 'to_date',
                            6=> 'days',
                            7=> 'leave_status',
                            8=> 'annual_holiday_allowance',
                            9=> 'leave',
                            10=> 'status'
                        );

        //print_r($columns);
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];  
        $dir = $this->input->post('order')[0]['dir'];
        $unit = $this->input->post('unit');
        $jobrole = $this->input->post('jobrole');
        $status = $this->input->post('status');
        $start_time = $this->input->post('start_time');
        $new_date = explode('/', $start_time); 
        $from_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0]; 

        $end_time = $this->input->post('end_time');
        $old_date = explode('/', $end_time); 
        $to_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];

        //print_r($from_date); print_r($to_date);exit();
         if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1)
        {  
  //print_r($this->input->post());exit();
                
                //print_r($this->input->post('search')['value']);   
                if(empty($this->input->post('search')['value']))
                {    
                    $totalData = $this->Reports_model->annualleaveallstaff_count($unit,$jobrole,$status,$from_date,$to_date);
                    
                    $totalFiltered = $totalData;         
                    $posts = $this->Reports_model->annualleaveallstaff($limit,$start,$order,$dir,$unit,$jobrole,$status,$from_date,$to_date);   
                }
                else 
                {
                    $search = $this->input->post('search')['value']; 
                    $totalData = $this->Reports_model->annualleaveallstaffsearch_count($search,$units,$jobrole,$status,$from_date,$to_date);
                    
                     $totalFiltered = $totalData;    
                    $posts =  $this->Reports_model->annualleaveallstaff_search($search,$limit,$start,$order,$dir,$unit,$jobrole,$status,$from_date,$to_date); 
                  
                } 
        }
        else
        {
               $id=$this->session->userdata('user_id'); 
                $userUnits = $this->User_model->getUnitIdOfUser($id);
                //print_r($userUnits);exit();  
                $units=$userUnits;
               
                //print_r($this->input->post('search')['value']);   
                if(empty($this->input->post('search')['value']))
                {    
                    $totalData = $this->Reports_model->annualleaveallstaff_count($units,$jobrole,$status,$from_date,$to_date);
                    
                    $totalFiltered = $totalData;         
                    $posts = $this->Reports_model->annualleaveallstaff($limit,$start,$order,$dir,$units,$jobrole,$status,$from_date,$to_date);   
                }
                else 
                {
                    $search = $this->input->post('search')['value']; 
                     $totalData = $this->Reports_model->annualleaveallstaffsearch_count($search,$units,$jobrole,$status,$from_date,$to_date);
                    
                     $totalFiltered = $totalData;  
                    $posts =  $this->Reports_model->annualleaveallstaff_search($search,$limit,$start,$order,$dir,$units,$jobrole,$status,$from_date,$to_date); 
                  
                } 

        }
        $data = array();
        //print_r($posts);exit();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                if($post->from_date && $post->to_date && $post->user_id)
                {
                    
                    $result = $this->Reports_model->getLeave($post->from_date,$post->to_date,$post->user_id);
                    if(count($result)>0)
                    {
                      if(count($result) == 1)
                      {
                        $message = "You are taken sick leave on ".$this->corectDateFormat($result[0]['date']);
                      }
                      else
                      {
                        for ($k = 0; $k < count($result); $k++)
                        {
                          $dates = $dates.' , '.$this->corectDateFormat($result[$k]['date']);
                        }
                        $message = "You are taken sick leave on ".$dates;
                      }
                    }else{
                        $message = "";
                    }
                }else{
                    $message = "";
                }

              if(($post->status)==1)
                  { $status="Approved"; } 
              else if(($post->status)==0) 
                  { $status="Pending";} 
              else if(($post->status)==2)  
                  { $status="Rejected";} 
              else if(($post->status)==3) 
                  {$status="Cancelled"; }
              else { $status=" ";}
                if($post->user_status==2){$user_status="Inactive";}elseif($post->user_status==3){ $user_status="Deleted";}else{ $user_status='Active';} 

               if(($post->from_date)==''){ $from_date=" "; } else { $from_date=date("d/m/Y",strtotime($post->from_date));} 
               if(($post->to_date)==''){ $to_date=" "; } else { $to_date=date("d/m/Y",strtotime($post->to_date));} 
                $total_hours=$post->days;   
                $total_hours=str_replace(":",".",$total_hours);
                $total_hours=number_format(getPayrollformat(number_format($total_hours,2),2),2);

                $nestedData['user_id'] = $post->user_id; 
                $nestedData['hr_ID'] = $post->hr_ID; 
                $nestedData['name'] = $post->fname.' '.$post->lname;
                $nestedData['unit_name'] = $post->unit_name;
                $nestedData['from_date'] = $from_date;
                $nestedData['to_date'] =$to_date;
                $nestedData['days'] =  $total_hours;  
                $nestedData['annual_holiday_allowance'] = $post->annual_holliday_allowance;  
                $nestedData['leave_status'] =  $status; 
                $nestedData['leave'] =  $message; 
                $nestedData['status']= $user_status;
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );   
        echo json_encode($json_data); 

    }

     public function clocking_in_out()
    {
    //$this->auth->restrict('Admin.Report.Add');
    $this->load->view('includes/home_header');
  $result = array(); 
    $this->load->helper('user');
  $data=array(); 
    //$data['user']=$this->Reports_model->finduser();  
  $this->load->view('admin/reports/clocking_in_out');
  $result['js_to_load'] = array('reports/clocking_in_out.js');
    $this->load->view('includes/home_footer',$result);
    }

     public function hoursworked()
    {
    //$this->auth->restrict('Admin.Report.Add');
    $this->load->view('includes/home_header');
  $result = array(); 
    $this->load->helper('user');
  $data=array(); 
    //$data['user']=$this->Reports_model->finduser();  
  $this->load->view('admin/reports/hoursworked');
  $result['js_to_load'] = array('reports/hoursworked.js');
    $this->load->view('includes/home_footer',$result);
    }

     public function weekendsworked()
    {
    //$this->auth->restrict('Admin.Report.Add');
    $this->load->view('includes/home_header');
  $result = array(); 
    $this->load->helper('user');
  $data=array(); 
    //$data['user']=$this->Reports_model->finduser();  
  $this->load->view('admin/reports/weekendsworked');
  $result['js_to_load'] = array('reports/weekendsworked.js');
    $this->load->view('includes/home_footer',$result);
    }

     public function trainingreport()
    {
    $this->auth->restrict('Admin.Report.Training'); 
    $result = array(); 
    $this->load->helper('user');
    $header = array();
    $header['headername']=" : Training Report";
    $this->load->view('includes/home_header',$header);
    $data=array(); 
    $id=$this->session->userdata('user_id');

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }

    $data['from_date']=date('d/m/Y');  
    $data['to_date']=date('d/m/Y'); 
    /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
    $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
    /*End*/
    $this->load->view('admin/reports/trainingreport',$data);
    $result['js_to_load'] = array('reports/trainingreport.js');
    $this->load->view('includes/home_footer',$result);
  }


    public function trainingreportData()
    { 
      $columns = array( 
        0=> 'title',
        1=> 'description',
        2=> 'date_from',
        3=> 'date_to',
        6=> 'unit', 
    );  
    $result = array(); 
    $data=array(); 
    $id=$this->session->userdata('user_id');
    $unit=$this->input->post('unit');  
    $units = $this->Training_model->getunitname($unit);
    $unit_name=$units[0]['unit_name'];
    $user = $this->input->post('user');  
    $status = $this->input->post('status');

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        if($this->input->post('unit')=="none"){
          $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        }
       
        $limit = $this->input->post('length'); 
        $start = $this->input->post('start');
        $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
        $dir = $this->input->post('order')[0]['dir']?:'desc';
        if(empty($this->input->post('search')['value']))
        {
          $search = null;
        }
        else
        {
            $search = $this->input->post('search')['value'];
        }
        
       
        
        if($this->input->post('from_date')=='')
        {
           $date_daily=date('d/m/y');
        }
        else
        {
          $date_daily=$this->input->post('from_date'); 
        }
        $old_date = explode('/', $date_daily); 
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['start_date']=$new_data;  

         if($this->input->post('to_date')=='')
        {
           $date_daily1=date('d/m/y');
        }
        else
        {
          $date_daily1=$this->input->post('to_date'); 
        }
     
        $old_date = explode('/', $date_daily1); 
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['end_date']=$new_data1;

      
        // if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18|| $this->session->userdata('subunit_access')==1 ) //all super admin can access
        {  
            // $data['training']=$this->Training_model->alltrainings(); 
            if($this->input->post('unit')!="none"){
            $totalData =$this->Reports_model->findtrainingreport($unit,$user,$status,$params,'',$limit,$start,$order,$dir,true);   
            $totalFiltered =$this->Reports_model->findtrainingreport($unit,$user,$status,$params,$search,$limit,$start,$order,$dir,true);   
            $data['training'] =$this->Reports_model->findtrainingreport($unit,$user,$status,$params,$search,$limit,$start,$order,$dir,false);   
            }
          //   else{
          //   $totalData =$this->Training_model->alltrainingsCount(''); 
          //   $totalFiltered =$this->Training_model->alltrainingsCount($search); 
          //   $data['training'] =$this->Training_model->alltrainings($search,$limit,$start,$order,$dir); 
          // }
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        // else if($this->session->userdata('subunit_access')==1)
        // { //if unit administrator 
        //     if($sub!=0 || $parent!=0) //unit administrator in sub unit
        //     {  
        //       if($sub==0)
        //       {
        //           $sub=$parent;
        //       } 
        //       else
        //       {
        //           $sub=$sub;
        //       }   
        //       // $data['training']=$this->Training_model->alladmintrainingDetails($sub); 

        //       $totalData =$this->Training_model->allmanagertrainingDetails('',$sub,$limit,$start,$order,$dir,true); 
        //       $totalFiltered =$this->Training_model->allmanagertrainingDetails($search,$sub,$limit,$start,$order,$dir,true); 
        //       $data['training'] =$this->Training_model->allmanagertrainingDetails($search,$sub,$limit,$start,$order,$dir); 
        //     }
        //     else
        //     {    
        //       $user_unitids=$this->Leave_model->getUnitIdOfUserAsArray($id); //print_r($user_unitids[0]);exit();
        //       // $data['training']=$this->Training_model->allmanagertrainingDetails($user_unitids[0]);

        //       $totalData =$this->Training_model->allmanagertrainingDetails('',$user_unitids[0],$limit,$start,$order,$dir,true); 
        //       $totalFiltered =$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir,true); 
        //       $data['training'] =$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir); 
        //     }

        // }
        else
        { 
            $user_unitids=$this->Leave_model->getUnitIdOfUserAsArray($id);
            $data['training']=$this->Training_model->allmanagertrainingDetails($user_unitids[0]);

            if($this->input->post('unit')!="none"){
              $unit=$this->session->userdata('unit_id');
              // $result=$this->Reports_model->findtrainingreport($units,'','','');   
              $totalData =$this->Reports_model->findtrainingreport($unit,'','','','',$limit,$start,$order,$dir,true);   
              $totalFiltered =$this->Reports_model->findtrainingreport($unit,'','','',$search,$limit,$start,$order,$dir,true);   
              $data['training'] =$this->Reports_model->findtrainingreport($unit,'','','',$search,$limit,$start,$order,$dir,false);   
            }
            // else{
            //   $totalData =$this->Training_model->allmanagertrainingDetails('',$user_unitids[0],$limit,$start,$order,$dir,true); 
            //   $totalFiltered =$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir,true); 
            //   $data['training'] =$this->Training_model->allmanagertrainingDetails($search,$user_unitids[0],$limit,$start,$order,$dir); 
            // }
                    
        }
        $data_arr = array();

        foreach($data['training'] as $desig){
              if($desig['date_from']=='') 
                $date_from = $desig['date_from'].' '.$desig['time_from']; 
              else 
                $date_from = date("d/m/Y",strtotime($desig['date_from'])).' '.$desig['time_from'];
              if($desig['date_to']=='') 
                $date_to = $desig['date_to'].' '.$desig['time_to']; 
              else 
                $date_to = date("d/m/Y",strtotime($desig['date_to'])).' '.$desig['time_to'];
              $myArray= explode(',', $desig['unit']); 
              $units = getUnit($myArray); 
              for($i=0;$i<count($units);$i++){ $unit=implode(", ", $units[$i]); }

              $staffs=getTrainingStaff($desig['id']); 
              $name = '';
              foreach($staffs as $msg){ 
                $name .="<b>" . $msg['fname'].' '.$msg['lname']."</b>";  
                $name .= '<br><br>';                                    
              }
            $view = ' <a  class="View" data-container="body" title="View Staffs" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" data-content="'.$name.' "><i class="fas fa-eye"></i></a>';
            $data_arr[] = array(
                "title" => $desig['title'],
                "description" => $desig['description'],
                "date_from" => $date_from,
                "date_to" => $date_to,
                "unit" => $unit,
                "view" => $view,

            );
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data_arr
        );

        echo json_encode($json_data);
    }

    public  function findtraining()
    {  
        $json = array();
            $unit=$this->input->post('unit');  
            $units = $this->Training_model->getunitname($unit);
            $unit_name=$units[0]['unit_name'];
            $user = $this->input->post('user');  
            $status = $this->input->post('status');
            
            if($this->input->post('from_date')=='')
            {
               $date_daily=date('d/m/y');
            }
            else
            {
              $date_daily=$this->input->post('from_date'); 
            }
            $old_date = explode('/', $date_daily); 
            $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $params['start_date']=$new_data;  

             if($this->input->post('to_date')=='')
            {
               $date_daily1=date('d/m/y');
            }
            else
            {
              $date_daily1=$this->input->post('to_date'); 
            }
         
            $old_date = explode('/', $date_daily1); 
            $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $params['end_date']=$new_data1;

            if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1 || $this->session->userdata('user_type')==18)
            {
           
                  $result=$this->Reports_model->findtrainingreport($unit,$user,$status,$params);   
                  //print_r('<pre>');
                  //print_r($result); print '<br>'; 
                  foreach ($result as $row)
                  { 
                     $staffs=getTrainingStaff($row['id']);//print_r($staffs);exit();
                     $messages="";
                     $new_message1=""; 
                     foreach($staffs as $msg){  

                               $messages= '<b>' . $msg['fname'].' '.$msg['lname'].'</b>'.' '.'<br><br>'; 
                               //$messages= '<b>'."Staff Name: ".'</b>' . $msg['fname'].' '.$msg['lname'].''.':'.' ';
                               $new_message1.=$messages;                                  
                    } 
                     $new_message='<a class="View"  data-container="body" title="" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" 
                data-content="'.$new_message1.' <br><br>" data-original-title="View Staffs" >'.'<i class="fas fa-eye"></i>'.'</a>';

                      if($row['status']==1){ $status="Approved";}else{$status="Rejected";}
                      $from_date=date("d/m/Y", strtotime($row['date_from'])).' '.$row['time_from']; 
                      $to_date=date("d/m/Y", strtotime($row['date_to'])).' '.$row['time_to'];
                      $creation_date=date("d-m-Y H:i:s", strtotime($row['creation_date']));
                      $json[] =array($row['title'],$row['description'],$from_date,$to_date,$unit_name,$new_message);
                  } 
            } 
            else
            {
                 $units=$this->session->userdata('unit_id');
                 $result=$this->Reports_model->findtrainingreport($units,'','','');    
                  foreach ($result as $row)
                  {
                    $staffs=getTrainingStaff($row['id']);//print_r($staffs);exit();
                     $messages="";
                     $new_message1=""; 
                     foreach($staffs as $msg){  

                               $messages= '<b>'."Staff Name: ".'</b>' . $msg['fname'].' '.$msg['lname'].''.'.'.' '.'<b>'."Unit:".'</b>' . $msg['unit_name'].':'.' '.'<br><br>'; 
                               //$messages= '<b>'."Staff Name: ".'</b>' . $msg['fname'].' '.$msg['lname'].''.':'.' ';
                               $new_message1.=$messages;                                  
                    } 
                     $new_message='<a class="View"  data-container="body" title="" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" 
                data-content="'.$new_message1.' <br><br>" data-original-title="View Staffs" >'.'<i class="fas fa-eye"></i>'.'</a>';

                    if($row['status']==1){ $status="Approved";}else{$status="Rejected";}
                    $from_date=date("d/m/Y", strtotime($row['date_from'])); 
                    $to_date=date("d/m/Y", strtotime($row['date_to']));
                    $creation_date=date("d-m-Y H:i:s", strtotime($row['creation_date']));
                    $json[] =array($row['title'],$row['description'],$from_date,$to_date,$unit_name,$new_message);
                  } 

            } 
          header("Content-Type: application/json");
          echo json_encode($json);
          exit();
    }
    
     public function overtimereport()
    {
      //$this->auth->restrict('Admin.Report.Overtime');
      $this->load->view('includes/home_header');
      $result = array(); 
      $this->load->helper('user');
      $data=array(); 
      //$data['user']=$this->Reports_model->finduser();  
      $this->load->view('admin/reports/overtimereport');
      $result['js_to_load'] = array('reports/overtimereport.js');
      $this->load->view('includes/home_footer',$result);
    }

     public function late_start_early_finishers()
    {
      //$this->auth->restrict('Admin.Report.Add');
      $this->load->view('includes/home_header');
      $result = array(); 
      $this->load->helper('user');
      $data=array(); 
      //$data['user']=$this->Reports_model->finduser();  
      $this->load->view('admin/reports/late_start_early_finishers');
      $result['js_to_load'] = array('reports/late_start_early_finishers.js');
      $this->load->view('includes/home_footer',$result);
    }
    function weekOfMonthFromDate($string) {
      //print $string;exit();
      $sunday_array = array();
      $full_week_array = array();
      $year_string_array = explode('-',$string);
      $first_date_of_month = $year_string_array[0].'-'.$year_string_array[1].'-01';
      $last_date_of_month = date("Y-m-t", strtotime($first_date_of_month));
      $begin  = new DateTime($first_date_of_month);
      $end    = new DateTime($last_date_of_month);
      while ($begin <= $end) // Loop will work begin to the end date 
      {
        if($begin->format("D") == "Sun") //Check that the day is Sunday here
        {
          array_push($sunday_array, $begin->format("Y-m-d"));
        }
        $begin->modify('+1 day');
      }
      foreach ($sunday_array as $sun) 
      {
        $next_sat = date('Y-m-d', strtotime($sun . ' + 6 day'));
        $week_string = $sun.'_'.$next_sat;
        array_push($full_week_array, $week_string);
      }
      return $full_week_array;
    }
     public function overstaffingreport()
    {
        $this->auth->restrict('Admin.Report.Overstaffing');
        $header = array();
        $header['headername']=" : Overstaffing Report";
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $this->load->helper('user');
        $data=array(); 
        $data=array();  
        $unit=$this->input->post('unitdata');  
        $year=$this->input->post('year');   
        $month=$this->input->post('month');
        /*$date_string = $year.'-'.$month;
        $week_array = $this->weekOfMonthFromDate($date_string);
        $first_week = $week_array[0];
        $week_count = count($week_array);
        $last_week = $week_array[$week_count-1];
        $month_start_date = explode("_",$first_week)[0];
        $month_end_date = explode("_",$last_week)[0];*/
        //$jobrole=$this->input->post('jobrole');
        if($this->input->post("year")=='')
        {
            $data['start_year']=date("Y");
        }
        else
        {
            $data['start_year']=$this->input->post("year"); 
        }
        if($this->input->post('month')=='')
        {
            $data['month']=date('m');
        }
        else
        {
             $data['month']=$this->input->post('month');
        }
        $unit_ids = $this->Unit_model->returnUnitsIds($unit);
        // $unit_ids = $this->Unit_model->returnMainAndSubUnitsIds($unit); 
        // print_r($unit_ids);exit();
        // $start_date=$year.'-'.$month.'-'.'01'; print_r($start_date);
        // $last_date_find = strtotime(date("Y-m-d", strtotime($start_date)) . ", last day of this month");
        // $last_date = date("Y-m-d",$last_date_find); print_r($last_date);
        $result =$this->Reports_model->OverStaffReport($year,$month,$unit_ids);
        if(count($unit_ids) > 1){
          $data['dates']=$result;
        }else{
          $data['dates']=$result['date_array'];
          $data['day_shift_max']=$result['day_shift_max'];
          $data['night_shift_max']=$result['night_shift_max'];
        }
        
        /*$data['Dshifts']=$this->Reports_model->findstaffsDayshift($unit);  
        $data['dshift']=count( $data['Dshifts']);
        $data['Nshifts']=$this->Reports_model->findstaffsNightshift($unit);  
        $data['nshift']=count( $data['Nshifts']);*/
        $data['unit_ids']=$unit_ids;
        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $data['jobrole'] = $this->Reports_model->fetchjobrole();
        if(count($unit_ids) > 1){
          $this->load->view('admin/reports/overstaffingreport_parent',$data);
        }else{
          $this->load->view('admin/reports/overstaffingreport',$data);
        }
      
      $result['js_to_load'] = array('reports/overstaffingreport.js');
        $this->load->view('includes/home_footer',$result);
    }


    public function checkLeaveCount(){
        $unit_id = $this->input->post('unit_id');
        $shift_id = $this->input->post('shift_id');
        $date = $this->input->post('date');
        $date_array  = explode("/",$date);
        // $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        $result = $this->Rotaschedule_model->checkLeaveCount($unit_id,$shift_id,$date);
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'result'=> $result));
        exit();
    }
    public function showAvailabilityList(){
      $this->auth->restrict('Admin.Rota.EmployeeAvailability');
      $this->load->view('includes/home_header');
      $date =$this->input->post('date_filter');
      $actual_date = '';
      if($date){
        $date_array  = explode("/",$date);
        $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
      }
      $data=array();
      $result = array();
      $requests = $this->Reports_model->getLiveStatus($actual_date);
      $data['requests'] = $requests;
      $data['date'] = $date;
      $this->load->view('admin/reports/list_staff_availability_status',$data);
      $result['js_to_load'] = array('reports/list_staff_availability_status.js');
      $this->load->view('includes/home_footer',$result);
    }
    public function staffavailabilityreport()
    { 
        $this->auth->restrict('Admin.Rota.EmployeeAvailability');
        $this->load->view('includes/home_header');
        $result = array(); 
        $this->load->helper('user');
        $data=array();
        $date =$this->input->post('start_date');
        $params['shift_id'] = $this->input->post('shift');
        $parent_shift_id = '';
        if($params['shift_id']){
          $parent_shift_data = $this->Shift_model->getParentShift($params['shift_id']);
          $parent_shift_id = $parent_shift_data[0]['parent_id'];
        }
        $actual_date = '';
        if($date){
          $date_array  = explode("/",$date);
          $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        }
        $params['unit'] = $this->input->post('unitdata');
        $params['unit_id_for'] = $this->input->post('unitdatafor');
        $params['date'] = $actual_date;
        $params['unittype']=$this->input->post('unittype');
        $data['shifts']=$this->Shift_model->getAvailableShifts(); 
        $data['availablie_staffs']=$this->Rotaschedule_model->getAvailableStaffs($params); 
        //print_r($data['availablie_staffs']);exit();
        $leave_details = $this->Rotaschedule_model->checkLeaveCount($params['unit_id_for'],$params['date']);
        $data['leave_details'] = json_encode($leave_details);
        $data['staffs_on_leave'] = $leave_details['staffs_on_leave'];

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18 || in_array($this->session->userdata('user_type'),$this->config->item('unit_group_id'))) //all super admin can access
        {
              $data['locationunit']=$this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator


            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['locationunit']=$this->User_model->fetchCategoryTree();   
             }
             else
             {    
                $data['locationunit']=$this->User_model->fetchCategoryTree();   
             }

        }
        else
        {
           $data['locationunit']=$this->User_model->fetchCategoryTree(); 
                            
        } 
        $data['unittype']=$this->Unit_model->allunitstypes();
        if($this->input->post('unittype')!='')
        {
          $unittype=$this->input->post('unittype');
          $data['unit']=$this->User_model->fetchCategoryTreeforavailability(' ',' ',' ',$unittype);
        }
         if($this->input->post('unitdatafor')!='')
        {
          $unitdata=$this->input->post('unitdatafor');
          $data['selected_unit']=$unitdata;
        }
        $data['unit_id_for'] = $params['unit_id_for'];
        $data['parent_shift_id'] = $parent_shift_id;
        // if($this->session->userdata('user_id')==1)
        // { 
        //   $data['unit'] = $this->User_model->fetchCategoryTree(''); 
        //   $data['locationunit']=$this->Training_model->allunitnoagency();
          
        // }else{
            
        //   $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
        //   $data['locationunit']=$this->Training_model->allunitnoagency();
          
        // }
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/reports/staff_availability_report',$data);
        $result['js_to_load'] = array('reports/staff_availability_report.js');
        $this->load->view('includes/home_footer',$result);
    }
    public function sendRequest()
    {
        $users = $this->input->post('users');
        $unit_id = $this->input->post('unit_id');
        $unit_name = $this->input->post('unit_name');
        $shift_name = $this->input->post('shift_name');
        $date = $this->input->post('date');
        $shift_id = $this->input->post('shift_id');
        $comment = $this->input->post('comment');
        $request_count = $this->input->post('request_count');
        $unittype = $this->input->post('unittype');
        $unit_for_id = $this->input->post('unit_for_id');
        $unit_for_name = $this->input->post('unit_for_name');
        $parent_shift_id = $this->input->post('parent_shift_id');
        $unit_supervisor = $this->User_model->finduserDetailsWithId($this->session->userdata('user_id'));
        $parent_shift_details = $this->Shift_model->findshift($parent_shift_id);
        $parent_shift_name = $parent_shift_details[0]['shift_shortcut'];
        $parent_shift_time = $parent_shift_details[0]['start_time'].'/'.$parent_shift_details[0]['end_time'];

        if($date){
            $date_array  = explode("/",$date);
            $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        }

        $available_requests = array(
          'shift_id' => $parent_shift_id,
          'from_unitid' => $unit_id,
          'to_unitid'  => $unit_for_id,
          'date'     => $actual_date,
          'created_date' => date('Y-m-d H:i:s'),
          'created_userid' => $this->session->userdata('user_id'),
          'comments' => $comment,
          'request_count' => $request_count
        );
        $insert_id = $this->Shift_model->insertToAvailableRequests($available_requests);


        if(count($unit_supervisor)>0){
            $supervisor_name = $unit_supervisor[0]['fname'].' '.$unit_supervisor[0]['lname'];
        }else{
            $supervisor_name = "";
        }

        
        foreach ($users as $value) 
        {
            $available_requested_users = array(
                'avialable_request_id' => $insert_id,
                'user_id' => $value['user_id'],
                'status'=> 0,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
                'unit_id' => $value['user_unitid'],
            );
            $result = $this->Shift_model->insertToAvailableRequestedUsers($available_requested_users);
            $mobilenumber = $value['mobile_number'];
            $staff_name = $value['name'];
            $user_id = $value['user_id'];
            $site_title = 'St Matthews Healthcare:';
            $date1=date("d-m-Y", strtotime($actual_date));
            $subject = $site_title.' '.'Availability request - Approve/Reject ['.$date1.'-'.$unit_for_name.'-'.$parent_shift_name.'-'.$parent_shift_time.']'; 
            $body = 'There is an open shift that matches your availability schedule, if you are interested please accept the shift ' .$parent_shift_name. ' at '.$unit_for_name.' on '.$date1.'.'; 
            $note = 'Note: The first one who accepts will be allocated to the shift.';
            $recover_url = $this->config->item('base_url').'staffs/shift/processRequest/'. $user_id.'/'.$parent_shift_id.'/'.$value['user_unitid'].'/'.$unit_for_id.'/'.$actual_date.'/'.$value['unittype'];
            $details = $user_id.'/'.$parent_shift_id.'/'.$value['user_unitid'].'/'.$unit_for_id.'/'.$date1;
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array();
            /*if($mobilenumber){
              $approve_link = $recover_url.'/1';
              $reject_link = $recover_url.'/2';
              //sms integration
              $message = "Open slot request for ".$shift_name." on ".$date."\nTo approve click on the below link\n".$approve_link."\nTo reject click on the below link\n".$reject_link;
              $this->load->model('AwsSnsModel');
              $sender_id="04";
              $result = $this->AwsSnsModel->SendSms($mobilenumber, $sender_id, $message);
            }*/
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url'),
                'to' => $value['email'],
                'type' => 'Shift Allocation',
                'staff_name' => $staff_name,
                'supervisor_name'=>$supervisor_name,
                'subject' => $subject,
                'data' => $body,
                'note' => $note,
                'content_title'=>'We are glad to have you!',
                'recover_url' => $recover_url,
                'user_id'=>$user_id,
                'details' => $details,
                'base_url' => $this->config->item('base_url')
            );
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/open_shift_request', $emailSettings, true);
            //die($htmlMessage);
            //         exit();
            $this->load->helper('mail');
            sendMail($emailSettings, $htmlMessage);
        }
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'result'=> "send"));
        exit();
    }

    public function absencereport()
    {
      $this->auth->restrict('Admin.Report.Absence');
      $header = array();
      $header['headername']=" : Absence Report";
      $this->load->view('includes/home_header',$header);
      $result = array(); 
      $this->load->helper('user');  
      $data=array(); 
      //print_r($params);
           
      $this->load->model('Checkin_model'); 
      if($this->input->post('start_date')=='')
      { 
          $data['start_date']=date('d/m/Y');  
      }
      else
      {
      $data['start_date']=$this->input->post('start_date');  
      }
      $status=1;
      
      $u_id=$this->session->userdata('user_id');  
      $sub=$this->User_model->CheckuserUnit($u_id);
      $unit=$this->User_model->findunitofuser($u_id); 
      $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
      if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
      {
            $data['unit'] = $this->User_model->fetchCategoryTree();  
      }
      // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
      else if($this->session->userdata('subunit_access')==1)
      { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                      $sub=$parent;
                }
                else
                {
                      $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
            }
            else
            {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
            }

      }
      else
      {
            $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                  
      } 
      
      $data['shift'] = $this->Shift_model->findshiftname($status);  
      $data['error']=' ';  
      $data['jobrole'] = $this->Reports_model->fetchjobrole();
      $data['job_role_group'] = $this->Designation_model->alldesignationgroups();
      /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
      $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
      /*End*/
      $this->load->view('admin/reports/absencereport',$data);
      $result['js_to_load'] = array('reports/absencereport.js');
      
      $this->load->view('includes/home_footer',$result);
    }
      public function absencereportData()
      {

        $columns = array( 
          0=> 'personal_details.user_id',
          1=> 'fname',
          2=> 'kin_phone',
          3=> 'date'
      );  
        $data=array(); 
        $params=array();   

        $params=array();
        // print_r($this->input->post());
        $params['unit_id']=$this->input->post('unitdata');
        //$params['date']=$this->input->post('start_date');
        $params['shift_id']=$this->input->post('shift');
        $params['jobrole']=$this->input->post('jobrole');
        $params['status']=$this->input->post('status');
        
        $date_daily=$this->input->post('start_date');  
        if($date_daily){
          $old_date = explode('/', $date_daily); 
          $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
          $params['date']=$new_data;
        }
        else{
          $params['date']=null;
        }
        $limit = $this->input->post('length'); 
        $start = $this->input->post('start');
        $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
        $dir = $this->input->post('order')[0]['dir']?:'desc';
        if(empty($this->input->post('search')['value']))
        {
          $search = null;
        }
        else
        {
            $search = $this->input->post('search')['value'];
        }
        $date_daily=$this->input->post('start_date');  
        //print_r($params);
        
        $this->load->model('Checkin_model'); 
        if($params['unit_id'] && $params['date']<=date('Y-m-d'))
        {
        //$data['timelog']=$this->Reports_model->findTimelogAbsent($params);
            $totalData =$this->Reports_model->absence_list($params,'',$limit,$start,$order,$dir,true); 
            $totalFiltered =$this->Reports_model->absence_list($params,$search,$limit,$start,$order,$dir,true); 
            $data['timelog'] =$this->Reports_model->absence_list($params,$search,$limit,$start,$order,$dir); 
        }
        else
        { 
          $totalData =0; 
          $totalFiltered =0; 
          $data['timelog']=array();
        }
        $data_arr = array();
        if($data['timelog'])
          foreach($data['timelog'] as $desig){
              $data_arr[] = array(
                  "user_id" =>$desig['user_id'],
                  "fname" => $desig['fname']." ".$desig['lname'],
                  "mobile_number" => $desig['mobile_number'],
                  "date" => date('d/m/Y',strtotime($desig['date'])),

              );
          }
        
        $json_data = array(
          "draw"            => intval($this->input->post('draw')),
          "recordsTotal"    => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data"            => $data_arr
        );

        echo json_encode($json_data);
      } 

          public function annualleaveplanner() 
        {
          $this->auth->restrict('Admin.Report.AnnualLeaveplanner');
          $header = array();  
          $header['headername']=" : Annual Leave Planner"; 
          $this->load->view('includes/home_header',$header);  
          $result = array();  
          $this->load->helper('user');  
          $this->load->library('user_agent');
          $data=array(); 
          $params=array();
          $jobe_roles=array();
          $u_id=$this->session->userdata('user_id');  
          $sub=$this->User_model->CheckuserUnit($u_id);  
          $unit=$this->User_model->findunitofuser($u_id); 
          $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
          if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access 
          { 
                $data['unit'] = $this->User_model->fetchCategoryTree();    
          }  
          // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9) 
          else if($this->session->userdata('subunit_access')==1)
          { //if unit administrator  
                if($sub!=0 || $parent!=0) //unit administrator in sub unit 
                {   
                    if($sub==0)
                    {  
                          $sub=$parent; 
                    }
 
                    else
 
                    {
  
                          $sub=$sub;
  
                    }
 
                    $data['unit'] = $this->User_model->fetchSubTree($sub);  
 
                }
  
                else
  
                {     
                    $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
  
                }
  
 
          } 
          else
 
          {
 
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));

 
          }
        if($this->input->post('unitdata')=='')
        {
          $data['units']=0;
        }
        else
        {
          $data['units']=$this->input->post('unitdata');
        } 
          //Added by chinchu
          $selected_unit_id = $this->input->post("unitdata");
          $selected_unit_details = $this->Unit_model->findunit($selected_unit_id);
          $data['selected_unit_details']=json_encode($selected_unit_details);
          $selected_jobroles = $this->input->post("jobrole");
          $year_string = $this->input->post("year");
          $status = $this->input->post("status");
          if(!$status){
            $status = "0";
          }

          if(date('m')>='09')
          {
             $current_year = date("Y");
          }
          else
          {  
             $current_year = date("Y")-1;
          }
 
          if(!$year_string){

            $current_year = $current_year;
            $next_year = $current_year + 1;
            $year_string = $current_year.'-'.$next_year;
          } 
          $data['user_list'] = $this->User_model->getUsersWithUnitIdAndJobRole($selected_unit_id,$selected_jobroles,$status);
          $data['year_string'] = $year_string; 
          $data['status'] = $status;
          $data['selected_unit_id'] = $selected_unit_id;
          if($this->input->post("jobrole")); 
          $jobe_roles = $this->input->post("jobrole");
          if(!$jobe_roles){
            $jobe_roles = [];
          }
          $data['error']=' ';  
  
          $data['jobrole'] = $this->Reports_model->fetchjobrole();
 
          $data['job_roles']=json_encode($jobe_roles);
          $data['os']=$this->agent->platform();
          /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
          $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
          /*End*/
          if($data['os']=='Linux')
          {
              $this->load->view('admin/reports/annualleaveplanner',$data);
          }
          else
          {
               $this->load->view('admin/reports/annualleaveplanner1',$data);
          }
 
          $result['js_to_load'] = array('reports/annualleaveplanner.js'); 
          $this->load->view('includes/home_footer',$result); 
        }

        public function findannualleave() 
        { 
          $params=array(); 
          $params['unit_id']=$this->input->post('unit');
          $params['designation']=$this->input->post('jobrole');  
          $params['year']=$this->input->post('year');
          $year=explode("-",$params['year']); 
          $params['start_date']=$year[0].'-'.'09'.'-'.'01'; 
          $params['end_date']=$year[1].'-'.'08'.'-'.'31';  
          $result=$this->Reports_model->findholidayUser($params);  
          if(empty($result))  
          { 
            $post=array(''); 
          }  
          else 
          { 
             foreach ($result as $value) {
              $params['user_id']=$value['user_id']; 
              $result_by_user=$this->Reports_model->findholidaydata($params);  
                foreach ($result_by_user as $value) {   

                    $total_hours=$value['days'];  // hours 
                    $beginday = $value['from_date'];
                    $lastday  = $value['to_date'];
                    $nr_work_days = $this->getWorkingDays($beginday, $lastday); //count working days
                    $days_hour=$total_hours/$nr_work_days;   //find hour per day

                    $dates=$this->daterange($beginday,$lastday); print_r($dates);
                  

  //                   for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
  //                       if (date("N", $i) <= 5) $workingDays = $workingDays + 1; 
  //                        $hour_per_day=$total_hours/$workingDays;
  //                        print_r($total_hours.'-');
  //                        print_r($workingDays);
  //                   }/// weekday count
  // exit();





                } exit();
             }
          } 
          exit();   // $weeks=(int)(($interval->days) / 7);
        }

         function getWorkingDays($startDate, $endDate)
        {
            $begin = strtotime($startDate);
            $end   = strtotime($endDate);
            if ($begin > $end)
            {
                echo "startdate is in the future! <br />";
                return 0;
            } 
            else 
            {
                $no_days  = 0;
                $weekends = 0;
                while ($begin <= $end) {
                    $no_days++; // no of days in the given interval
                    $what_day = date("N", $begin);
                    if ($what_day > 5) { // 6 and 7 are weekend days
                        $weekends++;
                    };
                    $begin += 86400; // +1 day
                };
                $working_days = $no_days - $weekends;
                return $working_days;
            }
        }

        function daterange($start_date,$end_date)
        {

          $start_date=date("m/d/Y", strtotime($start_date)); print_r($start_date);
          $end_date=date("m/d/Y", strtotime($end_date));  print_r($end_date);

          $start = new DateTime("'".$start_date."'");
          $end = new DateTime("'".$end_date."'");
          $oneday = new DateInterval("P1D");

          $days = array();
          $data = "7.5";

          /* Iterate from $start up to $end+1 day, one day in each iteration.
             We add one day to the $end date, because the DatePeriod only iterates up to,
             not including, the end date. */
          foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
              $day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
              if($day_num < 6) { /* weekday */
                  $days[$day->format("Y-m-d")] = $data;
                } 
              }     
        }

        function findDatesBtwnDates($start_date,$end_date)
        {
           $date_array = array();
           // Specify the start date. This date can be any English textual format  
           $date_from = $start_date;   
           $date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
             
           // Specify the end date. This date can be any English textual format  
           $date_to = $end_date;  
           $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
             
           // Loop from the start date to end date and output all dates inbetween  
           for ($i=$date_from; $i<=$date_to; $i+=86400) {  
             array_push($date_array, date("Y-m-d", $i));
           }
           return $date_array;
        }


        function checkLeaveyear($date){  
        $time=strtotime($date);
        $month=date("m",$time);// print_r("month".' '.$month); print "<br>";
        $year=date("Y",$time);  //print_r("year".' '.$year); print "<br>";
        if ($month > 8 && $year>=date('Y')) 
        {// print_r('hiii');exit();
            $year = date('Y')."-".(date('Y') +1) ;
        } 
        else
        { //print_r('hello');exit(); 
            if($year>date('Y')) 
            {
                $year = ($year-1)."-".$year; 
            } 
            else 
            { 
                $year = (date('Y')-1)."-".date('Y');
            }
        } 
        return $year;
    }
    public function showUserStatus(){
        $date = $this->input->post('date');
        $actual_date = '';
        if($date){
          $date_array  = explode("/",$date);
          $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        }
        $params['unit_id_in']= $this->input->post('unit_id');
        $params['date']= $actual_date;
        $params['shift_id']= $this->input->post('shift_id');
        $params['user_unit_for']= $this->input->post('unit_for_id');
        $params['parent_shift_id']= $this->input->post('parent_shift_id');
        $params['unittype']=$this->input->post('unittype');
        $result = $this->Rotaschedule_model->showUserStatus($params);
        header('Content-Type: application/json');
        echo json_encode(array('status' => 1,'result'=> $result));
        exit();
    }
    public function deleteRequest(){
      $date = $this->input->post('date');
      $actual_date = '';
      if($date){
        $date_array  = explode("/",$date);
        $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
      }
      $params['unit_id_in']= $this->input->post('unit_id');
      $params['date']= $actual_date;
      $params['shift_id']= $this->input->post('shift_id');
      $params['user_unit_for']= $this->input->post('unit_for_id');
      $params['parent_shift_id']= $this->input->post('parent_shift_id');
      $params['user_id']= $this->input->post('user_id');
      $result = $this->Rotaschedule_model->deleteRequest($params);
      header('Content-Type: application/json');
      echo json_encode(array('status' => $result));
      exit();
    }
    public function deleteAllRequests(){
      $date = $this->input->post('date');
      $actual_date = '';
      if($date){
        $date_array  = explode("/",$date);
        $actual_date = $date_array[2]."-".$date_array[1]."-".$date_array[0];
      }
      $params['unit_id_in']= $this->input->post('unit_id');
      $params['date']= $actual_date;
      $params['shift_id']= $this->input->post('shift_id');
      $params['user_unit_for']= $this->input->post('unit_for_id');
      $params['parent_shift_id']= $this->input->post('parent_shift_id');
      $params['user_id']= $this->input->post('user_id');
      $result = $this->Rotaschedule_model->deleteAllRequests($params);
      header('Content-Type: application/json');
      echo json_encode(array('status' => $result));
      exit();
    }

    public function changehourtodecimal()
    {
      $hour=$this->input->post('hour');
      if($hour!='00:00')
      {
         $hour_new=settimeformat(getPayrollformat1($hour));
      }
      else
      {
        $hour_new='0.00';
      }

      header('Content-Type: application/json');
      echo json_encode($hour_new);
      exit();

    }

    
    public function sicknessreport()
    {
      $this->auth->restrict('Admin.Report.SicknessReport');
	    $header = array();
      $header['headername']=" : Sickness Report";
      $this->load->view('includes/home_header',$header);
	    $result = array(); 

    $this->load->helper('user');
    $data=array(); 
    $jobe_roles=array();
    
    
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    
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
    
    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))  || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['jobrole']=$this->input->post("jobrole");    
        $params['status']=$this->input->post('status');     
        $date_daily=$this->input->post('from_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;    
        $date_daily1=$this->input->post('to_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1;   

        $data['sicknessreport']=$this->Reports_model->FindSicknessData($params);
        $data['user_post']=$this->input->post('user'); 
        $data['status']=$this->input->post('status');    
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);  
        $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
        $data['job_roles']=json_encode($jobe_roles); 
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/  
	  	$this->load->view('admin/reports/sicknessreport',$data);
	  	$result['js_to_load'] = array('reports/sicknessreport.js');
	  	$this->load->view('includes/home_footer',$result);
    }

    public function weekendsreport()
    {
      $this->auth->restrict('Admin.Report.Weekendsreport');
      $header = array();
      $header['headername']=" : Weekends Worked Report";
      $this->load->view('includes/home_header',$header);
      $result = array(); 

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
    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['status']=$this->input->post('status');  
        $data['user_post']=$this->input->post('user'); 
        $data['status']=$this->input->post('status');  
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);  
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/

      $this->load->view('admin/reports/weekendsworked',$data);
      
      $result['js_to_load'] = array('reports/weekendsreport.js');
      
      $this->load->view('includes/home_footer',$result);
    }
    public function weekendsreportData()
    {
      $columns = array( 
        0=> 'personal_details.fname',
        1=> 'unit.unit_name',
        2=> 'master_designation.designation_name',
        3=> 'rota_schedule.date',
        4=> 'master_shift.shift_name'
    );  

      $result = array(); 
      $data=array(); 
          
      $limit = $this->input->post('length'); 
      $start = $this->input->post('start');
      $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
      $dir = $this->input->post('order')[0]['dir']?:'desc';
      if(empty($this->input->post('search')['value']))
      {
        $search = null;
      }
      else
      {
          $search = $this->input->post('search')['value'];
      }

    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['status']=$this->input->post('status');      
        $params['from_date']=date('Y-m-d');     
        $params['to_date']=date('Y-m-d');   
        if($this->input->post('from_date')) { 
        $date_daily=$this->input->post('from_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;    
        }
        if($this->input->post('to_date')){
        $date_daily1=$this->input->post('to_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1;  
        }

        $totalData =$this->Reports_model->FindWeekendData($params,'',$limit,$start,$order,$dir,true); 
        $totalFiltered =$this->Reports_model->FindWeekendData($params,$search,$limit,$start,$order,$dir,true); 
        $data['weekends'] =$this->Reports_model->FindWeekendData($params,$search,$limit,$start,$order,$dir,false); 
       
        $data_arr = array();
        if($data['weekends']) 
          foreach($data['weekends'] as $use){
        
              $data_arr[] = array(
                  "fname" =>$use['fname']." ".$use['lname'],
                  "unit_name" => $use['unit_name'],
                  "designation_name" => $use['designation_name'],
                  "date" => date('d/m/Y',strtotime($use['date'])),
                  "shift_name" => $use['shift_name'],

              );
          }
        
        $json_data = array(
          "draw"            => intval($this->input->post('draw')),
          "recordsTotal"    => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data"            => $data_arr
        );

        echo json_encode($json_data); 
    }
    public function workingreport()
    {
      $this->auth->restrict('Admin.Report.Workingreport');
      $header = array();
      $header['headername']=" : Working Report";
      $this->load->view('includes/home_header',$header);
      $result = array(); 

      $this->load->helper('user');
      $data=array(); 
      $jobe_roles=array();
    
    
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    
    if($this->input->post('from_date')=='')
    {
        $data['from_date']=date('d/m/Y');
    }
    else
    {
         $data['from_date']=$this->input->post('from_date');
    }
    
    
    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))|| $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        } 
        $params['unit_id']=$this->input->post('unitdata');    
        $params['jobrole']=$this->input->post("jobrole");  
        $params['shift_category']=$this->input->post('shift_category');
        $params['part_of_number']=$this->input->post('part_of_number');
  
           if($this->input->post('from_date')){ 
        $date_daily=$this->input->post('from_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;   
           }
           else{    
            $params['from_date']=date('Y-m-d');   
           } 


        $data['shift']=$this->Shift_model->getShift();
        $data['status']=$this->input->post('shifttype');    
        $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
        $data['job_roles']=json_encode($jobe_roles);
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/   
      $this->load->view('admin/reports/workingreport',$data);
      $result['js_to_load'] = array('reports/workingreport.js');
      $this->load->view('includes/home_footer',$result);
    } 
    
    public function workingreportData()
    { 
      $columns = array( 
        0=> 'personal_details.fname',
        1=> 'unit_name',
        2=> 'designation_name',
        3=> 'shift_category',
        4=> 'part_number'
    );  
      $result = array(); 
      $data=array(); 
      $jobe_roles=array();
    
    if($this->input->post("jobrole"))
      $jobe_roles = $this->input->post("jobrole");
    
    if($this->input->post('from_date')=='')
    {
        $data['from_date']=date('d/m/Y');
    }
    else
    {
         $data['from_date']=$this->input->post('from_date');
    }
       
    $limit = $this->input->post('length'); 
    $start = $this->input->post('start');
    $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
    $dir = $this->input->post('order')[0]['dir']?:'desc';
    if(empty($this->input->post('search')['value']))
    {
      $search = null;
    }
    else
    {
        $search = $this->input->post('search')['value'];
    }

    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))|| $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        } 
        $params['unit_id']=$this->input->post('unitdata');    
        $params['jobrole']=$this->input->post("jobrole");  
        $params['shift_category']=$this->input->post('shift_category');
        $params['part_of_number']=$this->input->post('part_of_number');
  
          if($this->input->post('from_date')){ 
            $date_daily=$this->input->post('from_date');
            $old_date = explode('/', $date_daily);   
            $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
            //$params['from_date']=$this->input->post('from_date');       
            $params['from_date']=$new_data;   
          }
          else{    
            $params['from_date']=date('Y-m-d');   
          } 
           $totalData =$this->Reports_model->FindWorkingData($params,'',$limit,$start,$order,$dir,true); 
           $totalFiltered =$this->Reports_model->FindWorkingData($params,$search,$limit,$start,$order,$dir,true); 
           $data['working'] =$this->Reports_model->FindWorkingData($params,$search,$limit,$start,$order,$dir); 
        // $data['working']=$this->Reports_model->FindWorkingData($params);
      
        $data_arr = array();
        if($data['working']) 
          foreach($data['working'] as $desig){
            if($desig['shift_category']==1)   { $cat =  "Day"; } 
            else if($desig['shift_category']==2) {  $cat = "Night"; } 
            else if($desig['shift_category']==3) {  $cat = "Early"; }
            else if($desig['shift_category']==4) {  $cat = "Late"; }
            else  {  $cat = " "; }
            if($desig['part_number']==0) { $part_number= "No"; } 
            else { $part_number= "Yes"; }
              $data_arr[] = array(
                  "fname" =>$desig['fname']." ".$desig['lname'],
                  "unit_name" => $desig['unit_name'],
                  "designation_name" => $desig['designation_name'],
                  "shift_category" => $cat,
                  "part_number" => $part_number,

              );
          }
        
        $json_data = array(
          "draw"            => intval($this->input->post('draw')),
          "recordsTotal"    => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data"            => $data_arr
        );

        echo json_encode($json_data);
    }
  
    public function requestvsactualreport()
    {
      $this->auth->restrict('Admin.Report.Requestvsactualreport');
      $header = array();
      $header['headername']=" : Request v Actual Shift Report";
      $this->load->view('includes/home_header',$header);
      $result = array(); 

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
    
    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['status']=$this->input->post('status');     
        $date_daily=$this->input->post('from_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;    
        $date_daily1=$this->input->post('to_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1;   

        $data['user_post']=$this->input->post('user'); 
        $data['status']=$this->input->post('status');    
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);   
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
      $this->load->view('admin/reports/requestvsactual',$data);
      $result['js_to_load'] = array('reports/requestvsactual.js');
      $this->load->view('includes/home_footer',$result);
    }

    public function requestvsactualreportData()
    {

      $columns = array( 
        0=> 'personal_details.fname',
        1=> 'unit.unit_name',
        2=> 'master_designation.designation_name',
        3=> 'staffrota_schedule.date',
        4=> 'master_shift.shift_name',
        5=> 'master_shift.shift_name'
    );  
      $result = array(); 
      $data=array(); 
    
      $limit = $this->input->post('length'); 
      $start = $this->input->post('start');
      $order = $this->input->post('order')[0]['column']?$columns[$this->input->post('order')[0]['column']]:$columns[0];
      $dir = $this->input->post('order')[0]['dir']?:'desc';
      if(empty($this->input->post('search')['value']))
      {
        $search = null;
      }
      else
      {
          $search = $this->input->post('search')['value'];
      }
    
    $u_id=$this->session->userdata('user_id');  
    $sub=$this->User_model->CheckuserUnit($u_id);
    $unit=$this->User_model->findunitofuser($u_id); 
    $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
    if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unit');    
        $params['status']=$this->input->post('status'); 
        $params['from_date'] = date('Y-m-d');   
        $params['to_date'] = date('Y-m-d');   
        if($this->input->post('from_date')){  
        $date_daily=$this->input->post('from_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['from_date']=$new_data;  
        }
        if($this->input->post('to_date')){  
        $date_daily1=$this->input->post('to_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['to_date']=$new_data1; 
        }  

        // $data['actualvsrequested']=$this->Reports_model->FindActualvRequestedData($params);

        $totalData =$this->Reports_model->FindActualvRequestedData($params,'',$limit,$start,$order,$dir,true); 
        $totalFiltered =$this->Reports_model->FindActualvRequestedData($params,$search,$limit,$start,$order,$dir,true); 
        $data['actualvsrequested'] =$this->Reports_model->FindActualvRequestedData($params,$search,$limit,$start,$order,$dir); 
        $data_arr = array();
        if($data['actualvsrequested'] )
        foreach($data['actualvsrequested'] as $desig){
          $Shift=getActualShift($desig['user_id'],$desig['date']);
          $data_arr[] = array(
               "fname" =>$desig['fname']." ".$desig['lname'],
               "unit_name" => $desig['unit_name'],
               "designation_name" => $desig['designation_name'],
               "date" => date('d/m/Y',strtotime($desig['date'])),
               "shift_name" => $desig['shift_name'],
               "actual_shift_name" => $Shift,

           );
       }
     
     $json_data = array(
       "draw"            => intval($this->input->post('draw')),
       "recordsTotal"    => intval($totalData),
       "recordsFiltered" => intval($totalFiltered),
       "data"            => $data_arr
     );

     echo json_encode($json_data);
    }
      public function additional_hours()
    {
      
      $result=$this->Reports_model->getRotaadditionalData();
      if(count($result)>0)
      {

        foreach ($result  as $value) {

          $datahome=array(
            'date'=> $value['date'],
            'user_id'=> $value['user_id'],
            'unit_id'=> $value['unit_id'],
            'additional_hours'=> $value['additional_hours'],
            'day_additional_hours'=> $value['day_additional_hours'],
            'night_additional_hours'=> $value['night_additional_hours'],
            'additinal_hour_timelog_id'=> $value['additinal_hour_timelog_id'],
            'comment'=> $value['comment'],
            'creation_date'=> $value['creation_date'],
            'created_userid'=> $value['created_userid'],
            'updation_date'=> $value['updation_date'],
            'updated_userid'=> $value['updated_userid']
          );
          
          $this->Reports_model->insertadditionalHourdata($datahome);
          # code...
        }
      }

    }


        public function availability_report_count()
        {
        $this->auth->restrict('Admin.Report.Availability_report_count');
        $header = array();
        $header['headername']=" : Availability Report Count";
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $this->load->helper('user'); 
        $data=array(); 
        $params=array();
        //print_r($this->input->post()); exit();


        $params['unit_id']=$this->input->post('unitdata');
        $params['jobrole']=$this->input->post('jobrole');

        if($this->input->post("jobrole"))
        {
          $jobe_roles = $this->input->post("jobrole");  
        }
        else
        {
          $data['job_role_group'] = $this->Designation_model->alldesignationgroups();
          $jobe_roles = $data['job_role_group'];
        }

        
        $date_daily=$this->input->post('start_date');  
        $old_date = explode('/', $date_daily); 
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['start']=$new_data;


        $date_daily1=$this->input->post('end_date');  
        $old_date = explode('/', $date_daily1); 
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['end']=$new_data;
        if($this->input->post('start_date')=='' || $this->input->post('end_date')=='')

        {

            $data['available']=[];
        }
        else

        {
           
          $date_range=getDatesFromRange($params['start'],$params['end']);
          //print_r($date_range);//exit();
          $i=0;
          foreach ($date_range as $value) { //print_r($value);exit();
                 

                 $count= $this->Reports_model->getAvailabilestaffcount($params,$value);
                 $agencystaff= $this->Reports_model->getAgencystaffcount($params,$value);
                 $result=$this->Reports_model->getAvailabilitydata($params,$value);
                 $accepted=$this->Reports_model->getAvailabilitydataaccedpted($params,$value);


                 //print '<pre>';
                 //print_r(count($count)); print '<br>';
                 //print_r(count($result)); print '<br>';
                 //print_r(count($accepted)); print '<br>';

                 $proll[$i]=array('available'=>count($count),'date'=>$value,'requested'=>count($result),'accepted'=>count($accepted),'agency_staff'=>count($agencystaff));

                 $i++;
             
           }

            $data['available']=$proll;
        }


       
       
      //   print_r('<pre>');
      //   print_r($data['available']); 
      // exit();



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

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }

        $data['error']=' ';  
        $data['job_role_group'] = $this->Designation_model->alldesignationgroups();

        $data['job_roles']=json_encode($jobe_roles); 

        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/reports/availabilitycoutreport',$data);
        $result['js_to_load'] = array('reports/availabilitycountreport.js');
        $this->load->view('includes/home_footer',$result);
        }

         public function availability_report_users()
        {

        $this->auth->restrict('Admin.Report.Availability_report_user_count');
        $header = array();
        $header['headername']=" : Availability Request User Report";
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $this->load->helper('user'); 
        $data=array(); 
        $params=array();
        //print_r($this->input->post()); exit();


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

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        // else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }
    
        $params['status']=$this->input->post('status'); ///
        $params['user_id']=$this->input->post('user'); 
        //print_r($params); exit();

        if($this->input->post('start_date')=='')
        {
           $date_daily=date('d/m/y');
        }
        else
        {
          $date_daily=$this->input->post('start_date'); 
        }
        $old_date = explode('/', $date_daily); 
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['start_date']=$new_data;  

         if($this->input->post('end_date')=='')
        {
           $date_daily1=date('d/m/y');
        }
        else
        {
          $date_daily1=$this->input->post('end_date'); 
        }
     
        $old_date = explode('/', $date_daily1); 
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['end_date']=$new_data1;
        $params['unit_id']=$this->input->post('unitdata');


        if($this->input->post('start_date')=='' || $this->input->post('end_date')=='')

        {

            $data['available']=[];
        }
        else

        {
           
          $date_range=getDatesFromRange($params['start_date'],$params['end_date']);
          //print_r($date_range);exit();
          $i=0;
          foreach ($date_range as $value) { //print_r($value);exit();

                $created_users=$this->Reports_model->GetCreatedUserlist($params,$value);

                if(count($created_users)>0)
                {
                   $result=$this->getAvailabilitydataByuser($params,$value,$created_users);

                } 
                else
                {
                  $result=array('');
                }

                //print_r($result); print '<br>';
 
                

                 

                $proll[$i]=array('requests'=>$result);

                $i++;
             
           }

            $data['available']=$proll;
        }

 
       
       // print_r('<pre>');
       //  print_r($data['available']); 
    // exit();



      if($params['unit_id']!='none'){
        $data['user']=$this->Reports_model->finduserdataforallforreport($params['unit_id'],$params['status']);   
      }
      $data['user_post']=$this->input->post('user');  
      $data['status']=$params['status'];
      /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
      $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
      /*End*/
        $this->load->view('admin/reports/availabilityuserreport',$data);
        $result['js_to_load'] = array('reports/availabilityuserreport.js');
        $this->load->view('includes/home_footer',$result);
        }


        public function getAvailabilitydataByuser($params,$value,$created_users)
        {
           //print_r($created_users); print '<br>'; exit();


            $i=0;
            foreach ($created_users as $users) {

              $created_by=$this->Reports_model->getrequestedusername($users['created_userid']); //print_r($created_by); exit();
             
              $result=$this->Reports_model->getAvailabilitydataByuser($params,$value,$users['created_userid']);
              $accepted=$this->Reports_model->getAvailabilitydataaccedptedByuser($params,$value,$users['created_userid']);
              
              $send=array();
              for($j=0;count($result)>$j;$j++) 
              {
                
                $name=$result[$j]['designation_code'].' '.'-'.' '.$result[$j]['fname'].' '.$result[$j]['lname'].'<br>';

                array_push($send, $name);

              }

              $accept=array();
              for($j=0;count($accepted)>$j;$j++) 
              {
                
                $name=$accepted[$j]['designation_code'].' '.'-'.' '.$accepted[$j]['fname'].' '.$accepted[$j]['lname'].'<br>';

                array_push($accept, $name);

              }

              //print '<pre>';
              //print_r($send); print '<br>';
              //print_r($accept); print '<br>';
              //print_r($accepted); print '<br>';

              $proll[$i]=array('date'=>$value,'sent'=>$send,'accepted'=>$accept,'request_by'=>$created_by);
              
              $i++;

            }

            $available=$proll;

            return $available;


        }


    public function earlyleaver_report() 
    { 
        $this->auth->restrict('Admin.Report.earlyleaver_report');
        $header = array();
        $header['headername']=" : Early Leaver Report";
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $this->load->helper('user');
        $data=array(); 

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }


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
  //print_r($this->input->post());exit();
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['status']=$this->input->post('status');     
        $date_daily=$this->input->post('start_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['start_date']=$new_data;    
        $date_daily1=$this->input->post('end_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['end_date']=$new_data1;   

        $data['user_post']=$this->input->post('user');     
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);    

        $data['user_id'] = $this->Reports_model->GetTimelogDatas($params,'early');

        //print "<pre>"; print_r($data['user_id']);exit();


        if(empty($data['user_id']))
        {
          $posts=array();
        }
        else
        {
          $posts=array();
          foreach ($data['user_id'] as $rota) { 

                $datas_new=$this->Reports_model->GetRotaDetailsByUserForTimelog($rota['user_id'],$params); 

                //print_r($datas_new); print '<br>';
             
                if($datas_new!='')
                {
                    $checkout_data=$this->ChecktimetoData($datas_new,$params['unit_id']);

                    if($checkout_data)
                    {
                      array_push($posts, $checkout_data['check_out']);
                      
                    }
                   //print_r($rota['user_id']);print_r($rota['date']);print_r($datas_new['end_time']); print_r($time_to);  print '<br>';
                }


          }
          
        }
        //  $response['weekEvents']=$posts;
        // print_r("<pre>");
        // print_r($posts);
        //exit();

        $data['payroll']=$posts;
            
            $data['status']=$this->input->post('status');

            if(empty($data['payroll']))
            {
              $data['early_leaver_data']=array();
            } 
            else
            {
              $data['early_leaver_data']=$data['payroll']; 
            }  
            /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
            $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
            /*End*/
        $data['jobrole'] = $this->Reports_model->fetchjobrole();   
        $this->load->view('admin/reports/early_leaver',$data);
        $result['js_to_load'] = array('reports/early_leaver.js');
        $this->load->view('includes/home_footer',$result);
    }

    function ChecktimetoData($datas,$unit_id)
    {
      foreach ($datas as $value) {

          $time_to=$this->Reports_model->getLatestCheckoutDataByuser($value['user_id'],$unit_id,$value['date']);
          if($time_to)
          {
             //print_r($value['date']); print_r($value['end_time']); 
            $new_time_split=explode(':',$time_to['time_to']); //print_r($new_time_split);
            $check_in_time=$new_time_split[0].'.'.$new_time_split[1];  //print_r($check_in_time);
            $shift_end_time=str_replace(':', '.', $value['end_time']); //print_r($shift_end_time); print '<br>';

            if($check_in_time < $shift_end_time)
            { //print 'hii';
               //print_r($value['user_id'].'is early out at'.$value['date']); print '<br>';
               $proll['check_out']=array('user_id'=>$value['user_id'],'name'=>$value['fname'].' '.$value['lname'],'unit_name'=>$value['unit_name'],'shift'=>$value['shift_name'],'date'=>$value['date'],'check_out_time'=>$time_to['time_to']);
            }
            
          }
         
      }
      return $proll;
    }


    public function lateness_report() 
    { 
        $this->auth->restrict('Admin.Report.lateness_report');
        $header = array();
        $header['headername']=" : Lateness Report";
        $this->load->view('includes/home_header',$header);
        $result = array(); 
        $this->load->helper('user');
        $data=array(); 

        $u_id=$this->session->userdata('user_id');  
        $sub=$this->User_model->CheckuserUnit($u_id);
        $unit=$this->User_model->findunitofuser($u_id); 
        $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 
        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18) //all super admin can access
        {
              $data['unit'] = $this->User_model->fetchCategoryTree();  
        }
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator
            if($sub!=0 || $parent!=0) //unit administrator in sub unit
            {   
                if($sub==0)
                {
                       $sub=$parent;
                }
                else
                {
                       $sub=$sub;
                }
                $data['unit'] = $this->User_model->fetchSubTree($sub);  
             }
             else
             {    
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
             }

        }
        else
        {
           $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));
                            
        }


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
  //print_r($this->input->post());exit();
        $params['user_id']=$this->input->post('user');
        $params['unit_id']=$this->input->post('unitdata');    
        $params['status']=$this->input->post('status');     
        $date_daily=$this->input->post('start_date');
        $old_date = explode('/', $date_daily);   
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];    
        //$params['from_date']=$this->input->post('from_date');       
        $params['start_date']=$new_data;    
        $date_daily1=$this->input->post('end_date');     
        $old_date = explode('/', $date_daily1);     
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];   
              
        $params['end_date']=$new_data1;   

        $data['user_post']=$this->input->post('user');     
        $data['user']=$this->Reports_model->finduserdataforall($params['unit_id'],$params['status']);    

        $data['user_id'] = $this->Reports_model->GetTimelogDatas($params,'late');


        if(empty($data['user_id']))
        {
          $posts=array();
        }
        else
        {
          $posts=array();
          foreach ($data['user_id'] as $rota) { 

                $datas_new=$this->Reports_model->GetRotaDetailsByUserForTimelog($rota['user_id'],$params); 

                //print_r($datas_new); print '<br>';
             
                if($datas_new!='')
                {
                    $checkin_data=$this->CheckintimefromData($datas_new,$params['unit_id']);

                    if($checkin_data)
                    {
                      array_push($posts, $checkin_data['check_in']);
                      
                    }
                   //print_r($rota['user_id']);print_r($rota['date']);print_r($datas_new['end_time']); print_r($time_to);  print '<br>';
                }


          }
          
        }
        //  $response['weekEvents']=$posts;
        // print_r("<pre>");
        // print_r($posts);
        // exit();

        $data['payroll']=$posts;
            
            $data['status']=$this->input->post('status');

            if(empty($data['payroll']))
            {
              $data['lateness']=array();
            } 
            else
            {
              $data['lateness']=$data['payroll']; 
            } 
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $data['jobrole'] = $this->Reports_model->fetchjobrole();   
        $this->load->view('admin/reports/lateness_report',$data);
        $result['js_to_load'] = array('reports/lateness_report.js');
        $this->load->view('includes/home_footer',$result);
    }

    function CheckintimefromData($datas,$unit_id)
    {
      foreach ($datas as $value) {

          $time_to=$this->Reports_model->getLatestCheckInDataByuser($value['user_id'],$unit_id,$value['date']); //print_r($time_to);
          if($time_to)
          {
            //print_r($value['date']);  print_r($value['user_id']);
            $new_time_split=explode(':',$time_to['time_from']);  //print_r($new_time_split);
            $check_in_time=$new_time_split[0].'.'.$new_time_split[1];  //print_r($check_in_time);
            $shift_end_time=str_replace(':', '.', $value['start_time']); //print_r($shift_end_time); print '<br>';

            if($check_in_time > $shift_end_time)
            { //print 'hii';
               //print_r($value['user_id'].'is early out at'.$value['date']); print '<br>';
               $proll['check_in']=array('user_id'=>$value['user_id'],'name'=>$value['fname'].' '.$value['lname'],'unit_name'=>$value['unit_name'],'shift'=>$value['shift_name'],'date'=>$value['date'],'check_in_time'=>$time_to['time_from']);
            }
            
          }
         
      }
      return $proll;
    }



    // new reports added on nov 10 2021 by swaraj

     public function agencyloginreport()
    {
        $this->auth->restrict('Admin.Report.Agencyloginreport');
        $header = array();
        $header['headername']=" : Agency Login Report";
        $this->load->view('includes/home_header',$header);
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
        //print_r($this->input->post());
        $params['unit']=$this->input->post('unitdata');
        $params['type']=$this->input->post('type'); 
        $params['jobrole']= $this->input->post("jobrole");
        if($this->input->post("jobrole"))
          $jobe_roles = $this->input->post("jobrole");
        //print_r($params);exit();
        //$params['start_date']=$this->input->post('start_date');
        $date_daily=$this->input->post('start_date');
        $old_date = explode('/', $date_daily); 
        $new_data = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['start_date']=$new_data;

        $date_daily1=$this->input->post('end_date');
        $old_date = explode('/', $date_daily1); 
        $new_data1 = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
        $params['end_date']=$new_data1;

        if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('user_type')==18)
        { 
                $data['unit'] = $this->User_model->fetchCategoryTree('');   
                $timerec = $this->Reports_model->agencytimelogreport($params);
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
        }
        else if($this->session->userdata('subunit_access')==1)
        { //if unit administrator

              $u_id=$this->session->userdata('user_id');  
              $sub=$this->User_model->CheckuserUnit($u_id);
              $unit=$this->User_model->findunitofuser($u_id); 
              $parent=$this->User_model->Checkparent($unit[0]['unit_id']); 

                if($sub!=0 || $parent!=0) //unit administrator in sub unit
                {   
                    if($sub==0)
                    {
                           $sub=$parent;
                    }
                    else
                    {
                           $sub=$sub;
                    }
                    $data['unit'] = $this->User_model->fetchSubTree($sub);  
                 }
                 else
                 {    
                    $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id'));  
                 }

                $timerec = $this->Reports_model->agencytimelogreport($params);
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

        }
        else
        {
                $params['unit']= $this->User_model->getUnitIdOfUser($this->session->userdata('user_id'));
                $data['unit']=$this->User_model->findunitnameOfUser($this->session->userdata('user_id')); //print_r($data['unit']);exit();
                
                $timerec = $this->Reports_model->agencytimelogreport($params); 
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
        }
        $data['jobrole'] = $this->Reports_model->fetchjobrole(); 
        $data['job_roles']=json_encode($jobe_roles); //print_r($data['job_roles']);exit();
        /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
        $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
        /*End*/
        $this->load->view('admin/reports/agencyloginreport',$data);
        $result['js_to_load'] = array('reports/agencyloginreport.js');
        $this->load->view('includes/home_footer',$result);
    }


}
?>