<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User
 *
 * This is  user interaction methods you could use
 * for get supervisor login
 *
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author      Sivaprasad, Neork Technologies
 * @link
 */
require APPPATH.'/controllers/api/Restapi.php';

class Timelog extends Restapi {
    
    public $rest_format = 'json';
    public $user =  array();
    public function __construct()
    {
        parent::__construct();
        
    }
    public function index()
    {
        $data = array('payroll_id'=>'');
        $this->response(array('status'=>'false', 'message' => 'Unauthorized','data'=>$data), 201);
        
    }
    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function log_post()
    {
        try{
            $jsonArray = array();
            $jsonArray = json_decode(file_get_contents('php://input'),true);
            
            $tmp = array();
            $params = array();
            $user_notfound=0;
            $status_insert=0;
            $log_status=0;
            $user_id =  trim($jsonArray['timelog']['user_id']);
            $device_id = trim($jsonArray['timelog']['device_id']);
            $unit_id = trim($jsonArray['timelog']['unit_id']);
            $payroll_id = trim($jsonArray['timelog']['payroll_id']);
            $date = trim($jsonArray['timelog']['date']);
            $status = trim($jsonArray['timelog']['status']);
            $with_userid = trim($jsonArray['timelog']['with_userid']);
            $accuracy = trim($jsonArray['timelog']['accuracy']);
            mail('siva@cloudbydesign.co.uk', 'Time Log1'." Date: ".date('Y-m-d H:i:s')." User#:".$user_id, "Accuracy=".$accuracy." Status=".$status." user#=".$user_id." CWU=".$with_userid." Date".date('Y-m-d H:i:s'));
            $user_unit=0;
            //get  user unit
            if($payroll_id>0){
                
                $this->load->model('Unit_model');
                $userunit = $this->Unit_model->findunitidofuser($payroll_id);
                $user_unit = $userunit[0]['unit_id'];
                
            }
            $messageaccuracy='';
            $time_from='';
            $time_to='';
            if($jsonArray['timelog']['accuracy'])
                $accuracy = trim($jsonArray['timelog']['accuracy']);
                else
                    $accuracy =0;
                    
                    $time=0;
                    $data = array('payroll_id'=>$payroll_id);
                    $response_status=1;
                    
                    if($status==1){ //check in
                        $time_from = trim($jsonArray['timelog']['time']);
                        $time=1;
                        $time_to=0;
                        
                    }
                    if($status==0){ //check out
                        $time_to = trim($jsonArray['timelog']['time']);
                        $time=1;
                        $time_from=0;
                    }
                    
                    if($status==2){ // when accuracy is low than threashold - checkin with user id
                        $time_to = 0;
                        $time=1;
                        //$time_from=0;
                        $time_from = trim($jsonArray['timelog']['time']);
                        
                        
                    }
                    if($status==3){ // when accuracy is low than threashold --checkout with userid
                        //$time_to = 0;
                        $time_to = trim($jsonArray['timelog']['time']);
                        $time=1;
                        $time_from=0;
                        
                        
                    }
                    
                    $this->load->model('Timelog_model');
                    //get user id and shift id
                    $this->load->model('Payroll_model');
                    //check if user with payrollid
                    $paramsf['payroll_id']= '';
                    
                    $result_shift = array();
                    $result_lastlog = array();
                    $result_log= array();
                    $user_name ='';
                    $paramsf['id']=$payroll_id;
                    $result = $this->Payroll_model->finduser($paramsf);
                    
                    
                    $paramst['user_id']=$user_id;
                    $paramst['date']=date("Y-m-d");
                    $paramst['status']=1;
                    
                    
                    $result_lastlog = $this->Timelog_model->getlastUserActivity($paramst);
                    
                     // print_r($result_lastlog);exit;
                    if(count($result_lastlog) > 0 ){
                                if(count($result) > 0 ){
                                    $user_name= $result[0]['fname']." ".$result[0]['lname'];
                                    
                                }
                                
                                $currentlogdate = $date." ".trim($jsonArray['timelog']['time']);
                                
                                if($result_lastlog[0]['status']==1 && $status==1){
                                    $lastlogdatetime = $result_lastlog[0]['date']." ".$result_lastlog[0]['time_from'];
                                    
                                    $datetime1 = new DateTime($currentlogdate);
                                    $datetime2 = new DateTime($lastlogdatetime);
                                    $interval = $datetime1->diff($datetime2);
                                    $elapsed = explode(" ", $interval->format('%y %m %a %h %i'));
                                  
                                    $diff = $datetime1->getTimestamp() - $datetime2->getTimestamp();
                                    
                                   //  print $diff." di 1";
                                    //if($elapsed['3']>=15 || $elapsed['2']>0 || $elapsed['1']>0 || $elapsed['0']>0)
                                    if($diff >= 54000)
                                        $valid=1;
                                    else
                                        $valid=0;
                                            
                                            
                                            if($valid==0){
                                                $message = $user_name.', already checked in';
                                                $response_status=0;
                                                $log_status=1;
                                            }
                                           // print_r( $elapsed);
                                          //  print "<br>";
                                          // print $valid; exit;
                                            
                                }
                                if($result_lastlog[0]['status']==0 && $status==0){
                                    $lastlogdatetime = $result_lastlog[0]['date']." ".$result_lastlog[0]['time_to'];
                                    $datetime1 = new DateTime($currentlogdate);
                                    $datetime2 = new DateTime($lastlogdatetime);
                                    $interval = $datetime1->diff($datetime2);
                                    $elapsed = explode(" ", $interval->format('%y %m %a %h %i'));
                                    $diff = $datetime1->getTimestamp() - $datetime2->getTimestamp();
                                    
                                  //print $diff." di 2";
                                    
                                    //if($elapsed['3']>=15 || $elapsed['3']>=15 || $elapsed['2']>0 || $elapsed['1']>0 || $elapsed['0']>0)
                                    if($diff >= 54000 &&  ($result_lastlog[0]['date']==$date))
                                        $valid=1;
                                    else  if($diff >= 54000 &&  ($result_lastlog[0]['date']!=$date))
                                         $valid=2;
                                    else 
                                         $valid=0;
                                            
                                            if($valid==0){
                                                $message = $user_name.', already checked out';
                                                $response_status=0;
                                                $log_status=1;
                                            }
                                            if($valid==2){
                                                $message = $user_name.', please check in.';
                                                $response_status=0;
                                                $log_status=1;
                                            }
                                           // print_r( $elapsed);
                                           // print "<br>";   print $valid; exit;
                                }
                                if($result_lastlog[0]['status']==1 && $status==0){ //if last entry is check in and now user tries checkout, have to check if  15 hours or greather not block checkout
                                    
                                    $lastlogdatetime = $result_lastlog[0]['date']." ".$result_lastlog[0]['time_from'];
                                    $datetime1 = new DateTime($currentlogdate);
                                    $datetime2 = new DateTime($lastlogdatetime);
                                    $interval = $datetime1->diff($datetime2);
                                    $elapsed = explode(" ", $interval->format('%y %m %a %h %i'));
                                    
                                //   print $lastlogdatetime." d1";
                                 //   print $currentlogdate." d2";
                                    $diff = $datetime1->getTimestamp() - $datetime2->getTimestamp();
                                    
                                  // print $diff." di 3";
                                    
                                   // if($elapsed['3']>=15 || $elapsed['2']>0 || $elapsed['1']>0 || $elapsed['0']>0)
                                   if($diff >= 54000)
                                       
                                           $valid=0;
                                    else
                                            $valid=1;
                                    
                                            
                                            if($valid==0){
                                                $message = $user_name.', you have to check-out within 15 hours after check-in.';
                                                $response_status=0;
                                                $log_status=1;
                                            }
                                         //   print_r( $elapsed);
                                     //     print "<br>";
                                        //    print $valid; exit;
                                    
                                }
                        
                    }
                    
                    
                    if($log_status==0)
                    {
                    if(count($result) > 0 ){ //if user found
                        
                        $user_id = $result[0]['id'];
                        $payroll_id = $result[0]['payroll_id'];
                        $paramst['user_id']=$user_id;
                        $paramst['date']=date("Y-m-d");
                        $user_name= $result[0]['fname']." ".$result[0]['lname'];
                        $result_shift = $this->Timelog_model->getShiftofUserforaDay($paramst);
                        //
                        
                        $ipadd = $this->getUserIpAddr();
                        //check in
                        if($status==1){
                            //check if already chekin for the same day
                            $paramst['status']=1;
                            $result_lastlog = $this->Timelog_model->checkLogofUserforaDay($paramst);
                            
                            if($accuracy >= 45){
                                /*   $response_status=0;
                                 $message="Please try again."; */
                                $response_status=1;
                                $message=$user_name.", check-in successfull.";
                            }
                            elseif($accuracy < 45 && $with_userid==1){
                                /*   $response_status=0;
                                 $message="Please try again."; */
                                $response_status=1;
                                $message=$user_name.", check-in successfull.";
                            }
                            else{
                                $response_status=0;
                                $message="Please try again.";
                            }
                            
                            
                        }
                        //check out
                        if($status==0){
                            
                            unset($paramst);
                            //check if  last activity in check in
                            $paramst['user_id']=$user_id;
                            $result_lastaction = $this->Timelog_model->getlastLogofUserforaDay($paramst);
                            
                            
                            if($accuracy >= 45){
                                
                                $response_status=1;
                                $message=$user_name.", check-out successfull.";
                            }
                            elseif($accuracy < 45 && $with_userid==1){
                                
                                $response_status=1;
                                $message=$user_name.", check-out successfull.";
                            }
                            else{
                                $response_status=0;
                                $message="Please try again.";
                            }
                            
                            
                        }
                        
                        
                    }//end of user
                    else{
                        $message = 'User not found, try again.';
                        $response_status=0;
                        $user_notfound=1;
                        // mail('siva@cloudbydesign.co.uk', 'Time Log2- User Accuracy Low'." Date: ".date('Y-m-d H:i:s')." Accuracy:".$accuracy." Status".$status, $message." Accuracy ".$accuracy)." Date".date('Y-m-d H:i:s');
                        
                        
                        //sendgrid start
                        $subject="Accuracy=".$accuracy." Status=".$status." user#=".$user_id." Check with userID=".$with_userid." Date".date('Y-m-d H:i:s');
                        
                        $site_title = 'Time Log Error Accuracy Low'." Date: ".date('Y-m-d H:i:s')." Accuracy:".$accuracy." Status".$status;
                        $admin_email=getCompanydetails('from_email');
                        /*        $emailSettings = array(
                         'from' => $admin_email,
                         'site_title' => $site_title,
                         'site_url' => $this->config->item('base_url'),
                         'to' => 'sujith.3055@gmail.com',
                         'type' => 'TimeLog',
                         'user_name'=> 'Admin',
                         'subject' => $subject,
                         ); */
                        $emailSettings2 = array(
                            'from' => $admin_email,
                            'site_title' => $site_title,
                            'site_url' => $this->config->item('base_url'),
                            'to' => 'siva@cloudbydesign.co.uk',
                            'type' => 'TimeLog',
                            'user_name'=> 'Admin',
                            'subject' => $subject,
                        );
                        //print_r($emailSettings);exit();
                        $this->load->library('parser');
                        //$htmlMessage = $this->parser->parse('emails/timelog', $emailSettings, true);
                        $htmlMessage2 = $this->parser->parse('emails/timelog', $emailSettings2, true);
                        
                        //die($htmlMessage);exit();
                        $this->load->helper('mail');
                        //sendMail($emailSettings, $htmlMessage);
                        sendMail($emailSettings2, $htmlMessage2);
                        //sendgrid end
                    }
                    
                    
                    unset($result_lastlog);
                    unset($result_lastaction);
                    unset($paramst);
                    unset($paramsf);
                    $params = array('user_id' => $user_id,'payroll_id' => $payroll_id,'device_id' => $device_id,'accuracy'=>$accuracy,
                        'unit_id' => $unit_id,'user_unit' => $user_unit, 'date' => $date,'status' => $status,'time_from' => $time_from
                        ,'time_to' => $time_to,'IPaddress'=>$ipadd,'with_userid'=>$with_userid, 'creation_date'=>date('Y-m-d H:i:s'));
                    
                    if ($user_id=='' ||  $device_id=='' ||  $unit_id=='' ||  $date=='' ||  $status=='' || $time==0)
                    {
                        mail('contactsiva.spc@gmail.com', 'Time Log Invalid details '.date('Y-m-d H:i:s'), "user=".$user_id." device=".$device_id." Unit=".$unit_id." Date=".$date." Status=".$status." time=".$time." timeFrom=".$time_from." timeTo=".$time_to." IP:".$ipadd.' Accuracy='.$accuracy);
                        $message = 'Invalid details, try again.';
                        
                        $response_status=0;
                        
                    }else{
                        //  print $message."--".$response_status;
                        if($response_status==1 && $accuracy >= 45){
                            $status_insert = $this->Timelog_model->insert($params);
                        }
                        if(($accuracy < 45) && $response_status==1 && $with_userid==1){
                            //$message ="Fingerprint accuracy is low, please press your finger again.";
                            $status_insert = $this->Timelog_model->insert($params);
                            $status_insert=1;
                            $messageaccuracy = "Accuracy below expected threshold"." Accuracy=".$accuracy." Userid:".$user_id;
                            mail('contactsiva.spc@gmail.com', 'Accuracy below expected threshold '.date('Y-m-d H:i:s'), "user=".$user_id." device=".$device_id." Unit=".$unit_id." Date=".$date." Status=".$status." time=".$time." timeFrom=".$time_from." timeTo=".$time_to." IP:".$ipadd.' Accuracy='.$accuracy);
                            
                            
                            //sendgrid start
                            $subject="User=".$user_id." device=".$device_id." Unit=".$unit_id." Date=".$date." Status=".$status." time=".$time." timeFrom=".$time_from." timeTo=".$time_to." IP:".$ipadd.' Accuracy='.$accuracy ;
                            
                            $site_title = 'Time Log error - check with user id'." Date: ".date('Y-m-d H:i:s')." Accuracy:".$accuracy." Status".$status;
                            $admin_email=getCompanydetails('from_email');
                            /*        $emailSettings = array(
                             'from' => $admin_email,
                             'site_title' => $site_title,
                             'site_url' => $this->config->item('base_url'),
                             'to' => 'sujith.3055@gmail.com',
                             'type' => 'TimeLog',
                             'user_name'=> 'Admin',
                             'subject' => $subject,
                             ); */
                            $emailSettings2 = array(
                                'from' => $admin_email,
                                'site_title' => $site_title,
                                'site_url' => $this->config->item('base_url'),
                                'to' => 'siva@cloudbydesign.co.uk',
                                'type' => 'TimeLog',
                                'user_name'=> 'Admin',
                                'subject' => $subject,
                            );
                            //print_r($emailSettings);exit();
                            $this->load->library('parser');
                            // $htmlMessage = $this->parser->parse('emails/timelog', $emailSettings, true);
                            $htmlMessage2 = $this->parser->parse('emails/timelog', $emailSettings2, true);
                            
                            //die($htmlMessage);exit();
                            $this->load->helper('mail');
                            //  sendMail($emailSettings, $htmlMessage);
                            sendMail($emailSettings2, $htmlMessage2);
                            //sendgrid end
                        }
                        
                        if($status_insert=='1452'){
                            $message = 'Error,please report this issue, User id or Unit id not found.';
                            $response_status=0;
                            mail('contactsiva.spc@gmail.com', 'Time Log Error, please report this issue  User id or Unit id not found', $status."ERROR");
                        }
                        elseif($status_insert!=1){
                            $message = $message;
                            $response_status=0;
                            mail('contactsiva.spc@gmail.com', 'Time Log Error. Status='.$status." Date: ".date('Y-m-d H:i:s')." ".$message.$messageaccuracy,$messageaccuracy.$message." User# ".$user_id." Date".date('Y-m-d H:i:s'));
                            
                            
                        }
                        //  print $status_insert;
                        if($status_insert==1){
                            
                            $this->response(array('status'=>'true', 'message' => $message,'data'=>$params), 200);
                            // free array
                            unset($params);
                            mail('contactsiva.spc@gmail.com', 'Time Log'." Date: ".date('Y-m-d H:i:s')." ".$message, $message." User# ".$user_id)." Date".date('Y-m-d H:i:s');
                            
                        }
                        else{
                            
                            if($user_notfound==1){
                                $this->response(array('status'=>'true', 'message' => $message,'data'=>$params), 202);
                                mail('contactsiva.spc@gmail.com', 'Time Log - User Accuracy Low'." Date: ".date('Y-m-d H:i:s')." Accuracy:".$accuracy." Status".$status, $message." Accuracy ".$accuracy." Date".date('Y-m-d H:i:s'));
                                
                                
                            }else{
                                $this->response(array('status'=>'true', 'message' => $message,'data'=>$params), 202);
                                
                            }
                            
                        }
                        
                        
                    }
                    }
                    else{
                        $this->response(array('status'=>'true', 'message' => $message,'data'=>array("user_id"=>$user_id)), 202);
                        
                    }
        } catch (Exception $e) {
            mail('contactsiva.spc@gmail.com', 'Rota Log-Time',  "Time Exception ".$e->getMessage());
            $this->response(array('status'=>'false', 'message' => "Time Log Exception ".$e->getMessage(),'data'=>$data), 202);
            
            
        }
        
    }
    
    public function offline_post()
    {
        try{
            
            $jsonArray = array();
            $jsonArray = json_decode(file_get_contents('php://input'),true);
            
            $tmp = array();
            $jsondata = array();
            $params = array();
            $jsonda  = array();
            $paramscs = array();
            $this->load->model('Offline_model');
            $this->load->model('Timelog_model');
            // print_r($jsonArray['timelog']);
            //  print_r(count($jsonArray['timelog']));
            // print $jsonArray ['total_count'];
            
            // exit;
            if(trim($jsonArray ['total_count']) == count($jsonArray['timeLog']))
            {
                $jsondata['count'] =  trim($jsonArray ['total_count']);
                $jsondata['data'] =json_encode($jsonArray ['timeLog']);
                
                $jsondata['created_at'] = date('Y-m-d H:i:s');
                
                //save complete json array in a table
                $jsonid =  $this->Offline_model->insert($jsondata);
                
                
                foreach ($jsonArray['timeLog'] as $jsond){
                    
                    $unique_id = trim($jsond['unique_id']);
                    //check if uniquie id is already in db
                    $ustatus = $this->Timelog_model->findunique($unique_id);
                    $status = trim($jsond['status']);
                    $checkstatus=0;
                    
                    $paramscs['user_id']=trim($jsond['user_id']);
                    $paramscs['date']=trim($jsond['date']);
                    $paramscs['status']=1;
                    
                    $checkstatus = $this->checkjson($paramscs, $status);
                    
                    if($ustatus==0 && $checkstatus==0){
                        
                        // loop and save in another table
                        $jsonda['json_id'] =  $jsonid;
                        $jsonda['data'] =json_encode($jsond);
                        //  print_r($jsonda);
                        $jsondataid =   $this->Offline_model->insertdata($jsonda);
                        
                        //insert to time_log table
                        if($status==1){ //check in
                            $time_from = trim($jsond['time']);
                            $time_to=0;
                            
                        }
                        if($status==0){ //check out
                            $time_to = trim($jsond['time']);
                            $time_from=0;
                        }
                        if($status==2){ // when accuracy is low than threashold
                            $time_to = 0;
                            $time_from=0;
                            
                        }
                        
                        $user_id =  trim($jsond['user_id']);
                        $device_id = trim($jsond['device_id']);
                        $unit_id = trim($jsond['unit_id']);
                        $payroll_id = trim($jsond['payroll_id']);
                        $date = trim($jsond['date']);
                        $with_userid = trim($jsond['with_userid']);
                        $accuracy = trim($jsond['accuracy']);
                        
                        $ipadd = $this->getUserIpAddr();
                        
                        $user_unit=0;
                        //get  user unit
                        if($payroll_id>0){
                            
                            $this->load->model('Unit_model');
                            $userunit = $this->Unit_model->findunitidofuser($payroll_id);
                            $user_unit = $userunit[0]['unit_id'];
                            
                        }
                        
                        
                        $params = array('user_id' => $user_id,'payroll_id' => $payroll_id,'device_id' => $device_id,'accuracy'=>$accuracy,
                            'unit_id' => $unit_id,'user_unit' => $user_unit, 'date' => $date,'status' => $status,'time_from' => $time_from
                            ,'time_to' => $time_to,'IPaddress'=>$ipadd,'with_userid'=>$with_userid, 'creation_date'=>date('Y-m-d H:i:s'),
                            'offline_status'=>1,'offline_id'=>$jsondataid,'unique_id'=>$unique_id,'jsonid'=>$jsonid
                            
                        );
                        
                        
                        
                        $this->Timelog_model->insert($params);
                    }//end if
                    
                } //end for
                
                $this->response(array('status'=>'true', 'message' => "Sync successful.",'data'=>''), 200);
                
                unset($jsondata);
                unset($params);
                unset($jsonda);
                unset($jsonArray);
                
                
            }
            else{
                unset($jsondata);
                unset($params);
                unset($jsonda);
                unset($jsonArray);
                $this->response(array('status'=>'false', 'message' => "Total count mismatch.",'data'=>'total_count='.$jsonArray ['total_count']."- array count=".count($jsonArray['timelog'])), 202);
                
            }
            
            
            
        } catch (Exception $e) {
            unset($jsondata);
            unset($params);
            unset($jsonda);
            unset($jsonArray);
            mail('contactsiva.spc@gmail.com', 'Rota Log-Time',  "Time Exception ".$e->getMessage());
            $this->response(array('status'=>'false', 'message' => "Time Log Exception ".$e->getMessage(),'data'=>$data), 202);
            
            
        }
        
    }
    
    public function checkjson($paramst,$status) {
        
    /*      $paramst['user_id']=$user_id;
         $paramst['date']=date("Y-m-d");
         $paramst['status']=1;
          */
         
         $log_status=0;
         
         $result_lastlog = $this->Timelog_model->getlastUserActivity($paramst);
         
         // print_r($result_lastlog);exit;
         if(count($result_lastlog) > 0 ){
             if(count($result) > 0 ){
                 $user_name= $result[0]['fname']." ".$result[0]['lname'];
                 
             }
             
             $currentlogdate = $date." ".trim($jsonArray['timelog']['time']);
             
             if($result_lastlog[0]['status']==1 && $status==1){
                 $lastlogdatetime = $result_lastlog[0]['date']." ".$result_lastlog[0]['time_from'];
                 
                 $datetime1 = new DateTime($currentlogdate);
                 $datetime2 = new DateTime($lastlogdatetime);
                 $interval = $datetime1->diff($datetime2);
                 $elapsed = explode(" ", $interval->format('%y %m %a %h %i'));
                 
                 $diff = $datetime1->getTimestamp() - $datetime2->getTimestamp();
                 
                 //  print $diff." di 1";
                 //if($elapsed['3']>=15 || $elapsed['2']>0 || $elapsed['1']>0 || $elapsed['0']>0)
                 if($diff >= 54000)
                     $valid=1;
                     else
                         $valid=0;
                         
                         
                         if($valid==0){
                             $message = $user_name.', already checked in';
                             $response_status=0;
                             $log_status=1;
                         }
                         // print_r( $elapsed);
                         //  print "<br>";
                         // print $valid; exit;
                         
             }
             if($result_lastlog[0]['status']==0 && $status==0){
                 $lastlogdatetime = $result_lastlog[0]['date']." ".$result_lastlog[0]['time_to'];
                 $datetime1 = new DateTime($currentlogdate);
                 $datetime2 = new DateTime($lastlogdatetime);
                 $interval = $datetime1->diff($datetime2);
                 $elapsed = explode(" ", $interval->format('%y %m %a %h %i'));
                 $diff = $datetime1->getTimestamp() - $datetime2->getTimestamp();
                 
                 //print $diff." di 2";
                 
                 //if($elapsed['3']>=15 || $elapsed['3']>=15 || $elapsed['2']>0 || $elapsed['1']>0 || $elapsed['0']>0)
                 if($diff >= 54000 &&  ($result_lastlog[0]['date']==$date))
                     $valid=1;
                     else  if($diff >= 54000 &&  ($result_lastlog[0]['date']!=$date))
                         $valid=2;
                         else
                             $valid=0;
                             
                             if($valid==0){
                                 $message = $user_name.', already checked out';
                                 $response_status=0;
                                 $log_status=1;
                             }
                             if($valid==2){
                                 $message = $user_name.', please check in.';
                                 $response_status=0;
                                 $log_status=1;
                             }
                             // print_r( $elapsed);
                             // print "<br>";   print $valid; exit;
             }
             if($result_lastlog[0]['status']==1 && $status==0){ //if last entry is check in and now user tries checkout, have to check if  15 hours or greather not block checkout
                 
                 $lastlogdatetime = $result_lastlog[0]['date']." ".$result_lastlog[0]['time_from'];
                 $datetime1 = new DateTime($currentlogdate);
                 $datetime2 = new DateTime($lastlogdatetime);
                 $interval = $datetime1->diff($datetime2);
                 $elapsed = explode(" ", $interval->format('%y %m %a %h %i'));
                 
                 //   print $lastlogdatetime." d1";
                 //   print $currentlogdate." d2";
                 $diff = $datetime1->getTimestamp() - $datetime2->getTimestamp();
                 
                 // print $diff." di 3";
                 
                 // if($elapsed['3']>=15 || $elapsed['2']>0 || $elapsed['1']>0 || $elapsed['0']>0)
                 if($diff >= 54000)
                     
                     $valid=0;
                     else
                         $valid=1;
                         
                         
                         if($valid==0){
                             $message = $user_name.', you have to check-out within 15 hours after check-in.';
                             $response_status=0;
                             $log_status=1;
                         }
                         //   print_r( $elapsed);
                         //     print "<br>";
                         //    print $valid; exit;
                         
             }
             
         }
         return $log_status;
     }
    
}