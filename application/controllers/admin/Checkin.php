<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Checkin extends CI_Controller {
       
        /**
         * Get All Data from this method.
         *
         * @return Response
        */
        public function __construct() {
            Parent::__construct(); 
            $this->load->model('Checkin_model'); 
            $this->load->helper('form');
            $this->load->helper('name');
        
        }

        public function index()
        { 
        	$params=array();
            
            $shift_data=$this->Checkin_model->findshiftdetails(); //print_r($shift_data);exit();
            $latetime=$this->Checkin_model->findlatetime(); //print_r($latetime);exit();
            $admin_email=getCompanydetails('from_email');
            $ds =date("Y-m-d");
            $site_title="eROTA - St Matthews Healthcare";
            foreach ($shift_data as $value) {
             
            	if($value['start_time'])
            	{   

            		$time = $value['start_time'];
					$time2 = "00:"."$latetime".":00";  
		        	$secs = strtotime($time2)-strtotime("00:00:00");  
		            $result = date("H:i",strtotime($time)+$secs); //print_r($result);exit(); // shift date+15minute
		            $current_time=date('H:i');  //current time
		           if(strtotime($result)==strtotime($current_time)) //comparing current time with shift time
		          //  { //if shift time is already completed.

		            	//$params['date']="2020-01-18";
/* 		               $params['date']=date('Y-m-d');
		               $params['shift_id']=$value['id'];
		               $params['shift_time']=$result;
		               $checkin_list=$this->Checkin_model->Checkin_list($params); */
		               $ds=date('Y-m-d');;
		               $params['date']=$ds; 
	 
		            	$params['shift_id']=$value['id'];
		            	$params['shift_time']=$result; 
		            	$checkin_list=$this->Checkin_model->Checkin_list($params);
		            	
		            	
		            	
                        $userlist=array();
                        $user_id=array();
                        if(!empty($checkin_list))
                        {
                        //print_r("<pre>");
                        // print_r($checkin_list);
                        foreach ($checkin_list as $value2) {
                           //print_r($value['unit_id']);
                            // $admin_email=$this->Checkin_model->findunitadmin($value['unit_id']);
                            // $admin=array(); 
                            // if($admin_email)
                            // {
                            //  foreach ($admin_email as $values){
                            //    array_push($admin,$values['email']);
                            //   }
                            // }
                            // else
                            // {
                            //     $admin_email=getCompanydetails('from_email'); print_r($admin_email);

                            // }
                        
                            $unitID=$value2['unit_id'];
                            
                            array_push($userlist,$value2['fname'].' '.$value2['lname']);
                            array_push($user_id,$value2['user_id']);
                            } 
                           //print_r($userlist);
                         

                          
                        //print_r($admin);
                       
                        }
                        $supervisors=$this->Checkin_model->findsupervisorDetails($unitID,$ds); //print_r($superadminemail);exit();
                        
                        
                        $subject='Shift:'.' '.$value['shift_name'].' '.'-'.' '.'Late staffs on '.date("d/m/y");
                        $emailSettings = array();
                        $emailSettings = array(
                            'from' => $admin_email,
                            'site_title' => $site_title,
                            'to' => 'contactsiva.spc@gmail.com',
                            'type' => 'Late checkin',
                            'user_name' => 'Super admin',
                            'supervisor_name'=>'',
                            'userID'=> $user_id,
                            'data' => $userlist,
                            'subject' => $subject,
                        );
                        //print_r($emailSettings);exit();
                        $this->load->library('parser');
                        $htmlMessage = $this->parser->parse('emails/latelogin', $emailSettings, true);
                        //die($htmlMessage);exit();
                       // $this->load->helper('mail');
                       // sendMail($emailSettings, $htmlMessage);
                        
                        foreach ($supervisors as $supervisor){
                            
                            $emailSettings2 = array();
                            //  print $supervisor->fname." ".$supervisor->lname."<br>";
                            $emailSettings2 = array(
                                'from' => $admin_email,
                                'site_title' => $site_title,
                                'to' => $supervisor->email,
                                'type' => 'Late check-in',
                                'user_name' => $supervisor->fname." ".$supervisor->lname,
                                'supervisor_name'=>$supervisor->fname." ".$supervisor->lname,
                                'userID'=> $user_id,
                                'data' => $userlist,
                                'subject' => $subject,
                            );
                            //print_r($emailSettings2);exit();
                            $this->load->library('parser');
                            $htmlMessage2 = $this->parser->parse('emails/latelogin', $emailSettings2, true);
                            //die($htmlMessage);exit();
                          //  $this->load->helper('mail');
                           // sendMail($emailSettings2, $htmlMessage2);
                        }
                       
                       
		          } 
		            
            	}
            	 
            	
            }
            //exit();

      
       
    }
?>