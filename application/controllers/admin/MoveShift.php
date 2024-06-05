<?php
	class MoveShift extends CI_Controller {
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

  			$this->load->model('Moveshift_model');
        $this->load->model('Unit_model');
        $this->load->model('User_model');
        $this->load->model('Shift_model');
        $this->load->model('Rota_model');
        $this->load->model('Activity_model');
  			$this->load->helper('form');
	    }

	    public function index()
	    {  
          $this->auth->restrict('Admin.rota.Moveshift');
		      $result = array(); 
		      $this->load->helper('user');
		      $this->load->view('includes/home_header'); 
		      $data=array(); 
		      $id=$this->session->userdata('user_id');   
            
          $data['start_date']=date('d/m/Y');  // set default date onloading
          $data['locationunit']=$this->User_model->fetchCategoryTree(); //units

          $u_id=$this->session->userdata('user_id');  
          $sub=$this->User_model->CheckuserUnit($u_id);
          $unit=$this->User_model->findunitofuser($u_id); 
          $parent=$this->User_model->Checkparent($unit[0]['unit_id']);
          $data['unit'] = $this->User_model->fetchCategoryTree();
          //Commented by chinchu
          //if($this->session->userdata('user_type')==1) //all super admin can access
          /*if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')))
          {
                  $data['unit'] = $this->User_model->fetchCategoryTree();  
          }
          else if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==5 || $this->session->userdata('user_type')==6 || $this->session->userdata('user_type')==9)
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
                                  
          }*/

          $data['shifts']=$this->Shift_model->getShift();//shifts
          $shift_gap=$this->Rota_model->getShiftgaphours(); 
          $data['shift_gap']=$shift_gap[0]['shift_gap'];  //print_r($data['shift_hours']);exit();;

            if(!empty($this->session->flashdata('success'))) 
            {
                $data['success'] = $this->session->flashdata('success');
            } 
            else if(!empty($this->session->flashdata('error'))) 
            {
                $data['error'] = $this->session->flashdata('error');
            }
            else
            {
                $data['success'] = "";
                $data['error'] = "";
            }
          /*Added by chinchu on 15-09-2023 - to remove edit rota lock table when user switches to any other page than edit*/
          $this->Rota_model->deleteRotaLockTable($this->session->userdata('user_id'));
          /*End*/
		      
		      $this->load->view("admin/moveshift/moveshift",$data);
		      $result['js_to_load'] = array('rota/moveshift.js');
		      $this->load->view('includes/home_footer',$result);


	    }

     /* insertion or updation shift details using date,user_id,shift_id,to unit*/

      public function moveshift()
      {
        $params['start_date'] = $this->input->post('start_date');
        $params['unit'] = $this->input->post('unitdata');
        $params['user_id'] = $this->input->post('user');
        $params['unitfor'] = $this->input->post('unitdatafor');
        $params['shift'] = $this->input->post('shift'); 
        $params['created_userid'] = $this->session->userdata('user_id');

        $current_date = explode('/', $params['start_date']);   
        $new_date = $current_date[2].'-'.$current_date[1].'-'.$current_date[0];

        $params['month'] = $current_date[1];
        $params['year'] = $current_date[2];
        $params['created_userid'] = $this->session->userdata('user_id');

        if(date('D', strtotime($new_date))!='Sun')
        {
           $params['first_date']=date('Y-m-d', strtotime($new_date.'last sunday')); /* previous sundy of the date */
        }
        else
        {
            $params['first_date']=$new_date; /* set current day as first day */
        }

        if(date('D', strtotime($new_date))!='Sat')
        {
            $params['end_date']=date('Y-m-d', strtotime($new_date.'next saturday')); /* next saturdy of the date */
        }
        else
        {
            $params['end_date']=$new_date; /* current date as last date */
        }
       
        $params['shift_hours']=$this->Moveshift_model->getShiftHoursByShiftID($params['shift']);
        $date_range=getDatesFromRange($params['first_date'],$params['end_date']);
       // print_r($date_range);exit();
        $params['date'] = $new_date; 
        
        if($this->User_model->Checkparent($params['unitfor'])==0)
        {
              $result = $this->Moveshift_model->GetRotaDetailsByUser($params,'move'); /* getting rotadetails using date and for_unit */
              if(!empty($result))
              { /* if a rota with for unit present in rota  */

                $email_content = $this->Moveshift_model->GetRotaDetailsByUser($params,'get'); /* old shift details for email*/
                if(!empty($email_content))
                {
                    $datas = array( 
                      'from_unit'   => $email_content['unit_name'],
                      'shift_name'  => $email_content['shift_name']

                    );
                }
                else
                {
                    $datas = array ('');

                } 

                $this->InsertRotascheduleDetails($email_content,$params);


                $this->Moveshift_model->UpdateShiftOfRotaByUserAsZero($params); /* update shifts as zero using userid and date */

                $new_result=$this->Moveshift_model->GetShiftDetailsByforUnitID($params); /* checking for rota with forunit id as unit_id,user_id and date in rotaschedule table */

                if($new_result!=0) /*if rota with forunit id as unit_id */
                {             
                   $this->Moveshift_model->UpdateShiftOfRotaByUser($params); /* update shift_id with selected shift */
                   $params['rota_id']=$new_result['rota_id'];
                   $this->SendUseremailMoveshift($params,$datas,'update'); //sending email about moveshift
                }
                else 
                { /*insert rota details with for_unit as unit_id */

                  $new_results=$this->Moveshift_model->GetRotaIdOfOtherUsersInForUnit($params); /* checking for other users with same date , for unit_id and get rota_id */
                  $rota_id=$new_results['rota_id'];  /* set rota_id */
                  $params['rota_id']=$rota_id;


                  for($i=0;$i<count($date_range);$i++){ /* loop dates and add to rotaschedule */

                    if($date_range[$i]==$new_date)
                    {
                      $shift_id = $params['shift'];
                      $shift_hour=$params['shift_hours'];
                    }
                    else
                    {
                      $shift_id = 0;
                      $shift_hour=0;
                    }

                    if($params['unit']==$params['unitfor'])
                    {
                      $for_unit='NULL';
                    }
                    else
                    {
                      $for_unit=$params['unit'];
                    }
                    $day=date("D",strtotime($date_range[$i]));
                    $rotaschedule_data = array(
                                                'user_id'  => $params['user_id'],
                                                'shift_id' => $shift_id,
                                                'shift_hours' => $shift_hour,
                                                'additional_hours' =>NULL,
                                                'day_additional_hours' =>NULL,
                                                'night_additional_hours' =>NULL,
                                                'additinal_hour_timelog_id'=>NULL,
                                                'comment'=>NULL,
                                                'from_unit'=>$for_unit,
                                                'unit_id'=>$params['unitfor'],
                                                'rota_id'=>$rota_id,
                                                'date'=>$date_range[$i],
                                                'status'=>1,
                                                'creation_date'=>date('Y-m-d H:i:s'),
                                                'created_userid'=>$params['created_userid'],
                                                'updation_date'=>date('Y-m-d H:i:s'),
                                                'updated_userid'=>$params['created_userid'],
                                                'day'=>$day,
                                                'designation_id'=>NULL,
                                                'shift_category'=>0,
                                                'from_userid'=>NULL,
                                                'from_rotaid'=>NULL,
                                                'request_id'=>NULL
                    );
                    $rota_status=$this->Moveshift_model->inserRotascheduleDetails($rotaschedule_data);
                  }
                    
                  $this->SendUseremailMoveshift($params,$datas,'insert'); //sending email about moveshift

                }

                $this->session->set_flashdata('success','Shift moved successfully.');
                redirect('admin/MoveShift/index/');

              }
              else
              { /* if no rota display an error message */

                $unit_name=$this->Moveshift_model->getUnitname($params['unitfor']);

                $this->session->set_flashdata('error','Sorry, you cannot move the shift to this unit!. No rota is created for this week at this '.$unit_name.'.');
                redirect('admin/MoveShift/index/');

              }
          }
          else
          {
              $this->session->set_flashdata('error','Sorry, You cannot move shift to a unit having sub units.');
              redirect('admin/MoveShift/index/');

          }
        
      }


      /* send email about shift move */
      public function SendUseremailMoveshift($params,$data,$status)
      { 

          $user_details=$this->Moveshift_model->getUsername($params['user_id']);
          $unit_name=$this->Moveshift_model->getUnitname($params['unitfor']);
          $shift_name=$this->Moveshift_model->getshiftname($params['shift']);

          $new_date=explode('/', $params['start_date']);
          $new_changed_date=$new_date[0].'-'.$new_date[1].'-'.$new_date[2];

          if($status=='update')
          {
                    if(!empty($data))
                    {
                        $description_activity= 'Your shift '.$data['shift_name'].' on '.$new_changed_date.' at '.$data['from_unit'].' has been moved to '.$shift_name.' at '. $unit_name.'.';
                    }
                    else
                    {
                        $description_activity= 'You have been assigned a new shift on '.$new_changed_date.' at '. $unit_name.'.';
                    }
          }
          else
          {
                    $description_activity= 'You have been assigned a new shift on '.$new_changed_date.' at '. $unit_name.'.';

          }

          $data_activity_log=array(
                          'description'=>'( '.$user_details['fname'].' '.$user_details['lname'].' ) '.$description_activity,
                          'activity_date'=>date('Y-m-d H:i:s'),
                          'activity_by'=>$this->session->userdata('user_id'),
                          'add_type'=>'Move Shift',
                          'user_id'=>$params['user_id'],
                          'unit_id'=>$params['unitfor'],
                          'primary_id'=>$params['rota_id'],
                          'creation_date'=>date('Y-m-d H:i:s')
          );

          $this->Activity_model->insertactivity($data_activity_log);   

           if($status=='update')
          {
                    if(!empty($data))
                    {
                        $description= 'Your shift '.$data['shift_name'].' on '.$params['start_date'].' at '.$data['from_unit'].' has been moved to '.$shift_name.' at '. $unit_name.'.';
                    }
                    else
                    {
                        $description= 'You have been assigned a new shift on '.$params['start_date'].' at '. $unit_name.'.';
                    }
          }
          else
          {
                    $description= 'You have been assigned a new shift on '.$params['start_date'].' at '. $unit_name.'.';

          }


          $session_user = $this->User_model->getSingleUser($this->session->userdata('user_id'));  
          if(count($session_user)>0)
          {
              $unit_supervisor_name = $session_user[0]['fname'].' '.$session_user[0]['lname'];
          }
          else
          {
              $unit_supervisor_name = '';
          }


            $site_title = 'St Matthews Healthcare:';
            $admin_email=getCompanydetails('from_email');
            $emailSettings = array(
                'from' => $admin_email,
                'site_title' => $site_title,
                'site_url' => $this->config->item('base_url') ,
                'to' => $user_details['email'],
                'type' => 'Shift Moved',
                'user_name' => $user_details['fname'].' '.$user_details['lname'],
                'subject' => $site_title.' '.'Shift Moved.',
                'description' => $description,
                'supervisor_name'=>$unit_supervisor_name,
            );

            //print_r($emailSettings);exit();
            $this->load->library('parser');
            $htmlMessage = $this->parser->parse('emails/moveshift', $emailSettings, true);
            //die($htmlMessage);exit();
            // load email helper
            $this->load->helper('mail');
            // send welcome mail
            sendMail($emailSettings, $htmlMessage);   

      }

      public function InsertRotascheduleDetails($datas,$params)
      {
          //creating html for inserting rota log table
          $shift_name = $this->Moveshift_model->getshiftname($params['shift']);
          $html = $this->createRotaHtml($params['start_date'],$params['user_id'],$shift_name); //print_r($prev_history);exit();

          //calling function to insert into rota_log array
          $rota_log_details = array(
            'rota_id' => $datas['rota_id'],
            'start_date' => $params['first_date'],
            'end_date' => $params['end_date'],
            'user_id' => $params['user_id'],
            'rota_details' => $html,
            'updated_by' => $this->session->userdata('user_id'),
            'updated_datetime' => date('Y-m-d H:i:s'),
            'type' => 'Move shift',
            'unit_id' => $params['unitfor']
          );
         
          $rota_log_id = $this->Rota_model->insertRotaLog($rota_log_details);


          $rota_log_history_details = array(
          'user_id' => $params['user_id'],
          'shift_id' => $datas['shift_id'],
          'shift_hours' => $datas['shift_hours'],
          'additional_hours' => $datas['additional_hours'],
          'comment' => $datas['comment'],
          'from_unit' => $datas['from_unit'],
          'unit_id' => $datas['unit_id'],
          'rota_id' => $datas['rota_id'],
          'date' => $params['date'],
          'status' => $datas['status'],
          'creation_date' => $datas['creation_date'],
          'created_userid' => $datas['created_userid'],
          'updation_date' => date('Y-m-d H:i:s'),
          'updated_userid' => $this->session->userdata('user_id'),
          'day' => $day=date("D",strtotime($params['start_date'])),
          'shift_category' => $datas['shift_category'],
          'from_userid' => $datas['from_userid'],
          'from_rotaid' => $datas['from_rotaid'],
          'rota_logid' => $rota_log_id
        ); 
           //print_r($rota_log_history_details);exit();
        $rota_log_history_id = $this->Rota_model->insertHistoryRotaSchedule($rota_log_history_details);
      }


     public function createRotaHtml($date,$user_id,$shift_name){ 
        $html = '';

        $html .= '<p style="text-align:center;font-weight:normal;font-family:sans-serif !important;">Week '.$this->weekOfMonth($date).'</p>'.
          '<table  border="0" cellpadding="0" cellspacing="0"  style="margin: auto; width: 100%; font-weight: normal; font-family:sans-serif !important"><thead><tr><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Shift Name</th><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Date</th></tr></thead><tbody>';

          $result = $this->Rota_model->findUnitShift($user_id,$date);

          $html .= '<td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;">'.$shift_name.'</td>'.
            '<td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;">'.$date.'</td></tr>';
        $html .= '</tbody></table>';
      
      return $html;
    }

    public function weekOfMonth($qDate) { 
      $new_date=explode('/', $qDate);
      $new_qDate=$new_date[0].'-'.$new_date[1].'-'.$new_date[2]; 
    $dt = strtotime($new_qDate);
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






     /* finding rota details using a date and userid*/

      public function findrotadetails()
      {
           
           $params['start_date'] = $this->input->post('date');
           $params['user_id'] = $this->input->post('user');
           $current_date = explode('/', $params['start_date']);   
           $params['date'] = $current_date[2].'-'.$current_date[1].'-'.$current_date[0];

           $result = $this->Moveshift_model->GetRotaDetailsByUser($params,'get');
          //print_r(count($result));exit();
           if(!empty($result))
           {

             if($result['date']=='')
             {
               $dates='';
             }
             else
             {
               $dates=date('d/m/Y',strtotime($result['date']));
             }

             $employee = "<td><label  id='employee' name='employee' value='".$result['fname'].' '.$result['lname']."'>".$result['fname'].' '.$result['lname']."</label></td>";
             $unit = "<td><label id='unit' name='unit' value='".$result['unit_name']."'>".$result['unit_name']."</label></td>";
             $date = "<td><label id='date' name='date' value='".$result['date']."'>".$dates."</label></td>";
             $shift = "<td><label id='shift' name='shift' value='".$result['shift_name']."'>".$result['shift_name']."</label></td>"; 

             $data['rota'] = "<tr id='abc'>" . $employee .$unit . $date . $shift. "</tr>";
             $data['status'] = $result['unit_id'];
 
           }
           else
           {
              $data['rota']       = "<tr id='abc'>" ."<td colspan='4' style='padding-left:48%;'>". "No shift found."."</td>". "</tr>";
              $data['status'] = 0;
           }

           //print_r($data);exit();

           echo json_encode($data);
              exit;
      }

    /* finding rota details using a date and userid for warning message*/

      public function findrotadetailsforAlert()
      {
                   
           $params['start_date'] = $this->input->post('date');
           $params['user_id'] = $this->input->post('user');
           $current_date = explode('/', $params['start_date']);   
           $params['date'] = $current_date[2].'-'.$current_date[1].'-'.$current_date[0];
           $unit_id = $this->input->post('unit');
           $unit_name=$this->Moveshift_model->getUnitname($unit_id);
           $result = $this->Moveshift_model->GetRotaDetailsByUser($params,'get');

           if(!empty($result))
           {

             if($result['date']=='')
             {
               $dates='';
               $data['message']='Do you want to move the shift to '.$unit_name.'?';
             }
             else
             {
               $dates=date('d/m/Y',strtotime($result['date']));
               $data['message']='The user '.$result['fname'].' already has a shift assigned to the unit '.$result['unit_name'].' on '.$dates.'. Do you want to move the shift to '.$unit_name.'?';
             }
 
           }
           else
           { 
               $data['message'] = 'Do you want to move the shift to '.$unit_name.'?';
           }

           //]print_r($data);exit();

           echo json_encode($data);
              exit;


      }


 /* checking shift gap hours - ajax call after clicking move shift button */

      public function findShiftGapHours()
      {
          $params['start_date'] = $this->input->post('date');
          $params['user'] = $this->input->post('user');
          $params['unitfor'] = $this->input->post('unitfor');
          $params['shift'] = $this->input->post('shift'); 
          $shift_gap = $this->input->post('shift_gap');

          $current_date = explode('/', $params['start_date']);   
          $new_date = $current_date[2].'-'.$current_date[1].'-'.$current_date[0];

          $today = $new_date;
          $params['previous'] = date('Y-m-d', strtotime($today . " - 1 day"));  /* find previous date */
          $params['next'] = date('Y-m-d', strtotime($today . " + 1 day"));  /* find next date */

          $shift_details = $this->Moveshift_model->GetShiftDetails($params['shift']); /* getting shift details(start and end time) */
          $previous_shift = $this->Moveshift_model->GetPreviousShiftData($params); /* getting preious shift data */
          $next_shift = $this->Moveshift_model->GetNextShiftData($params); /* getting next shift data */

          if(!empty($previous_shift)) /* difference btw shift start time and previous shift end time */
          {
             $pre_shift_hour=array('p_date'=>$previous_shift['date'],'p_shift_endtime'=>$previous_shift['end_time']);
          }
          else
          {
             $pre_shift_hour=array('p_date'=>$params['previous'],'p_shift_endtime'=>'00:00');
          }

          if(!empty($next_shift)) /* difference btw shift end time and next shift start time */
          {
             $nex_shift_hour=array('n_date'=>$next_shift['date'],'n_shift_starttime'=>$next_shift['start_time']);
          }
          else
          {
             $nex_shift_hour=array('n_date'=>$params['next'],'n_shift_starttime'=>'00:00');
          }

          $current_shift_hour=array('c_date'=>$today,'c_shift_starttime'=>$shift_details['start_time'],'c_shift_endtime'=>$shift_details['end_time']);
           

          $param=array('current'=>$current_shift_hour,'next'=>$nex_shift_hour,'previous'=>$pre_shift_hour);
//print_r($param);exit();
          echo json_encode($param);
          exit;

      }


      public function finduserdataforallforreport()
      {
        $this->load->model('Moveshift_model');
        $unit_id=$this->input->post('unit_id'); 
        $status=$this->input->post('status');
        $unit_data =$this->Moveshift_model->finduserdataforallforreport($unit_id,$status);  
        $parent=$this->User_model->Checkparent($unit_id); 
        $data=array('unit_data'=>$unit_data,'parent'=>$parent);
        echo json_encode($data);
      }


}
?>