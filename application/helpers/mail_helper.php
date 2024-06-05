<?php // settings_helper.php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 function sendMailOld($emailSettings, $htmlMessage = '<p></p>'){
   return true;
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $emailSettings['bcc']='contactsiva.spc@gmail.com';
  /*   print_r($emailSettings);
    print_r($htmlMessage); */
	if(empty($emailSettings['to']) || empty($emailSettings['subject']) || empty($emailSettings['from']))
		return;
    $CI =& get_instance();
    $CI->load->library('email');
    $config['protocol'] = 'sendmail';
    $config['mailpath'] = '/usr/sbin/sendmail';
    $config['charset'] = 'iso-8859-1';
    $config['wordwrap'] = TRUE;
    
    $CI->email->initialize($config);
    
    $CI->email->from($emailSettings['from'], $emailSettings['site_title']);
    $CI->email->to($emailSettings['to']);
    if(!empty($emailSettings['cc'])){       
        $CI->email->cc($emailSettings['cc']);
    }
   // $CI->email->bcc($emailSettings['bcc']);
    $CI->email->set_mailtype("html");
    $CI->email->subject($emailSettings['subject']);
    $CI->email->message($htmlMessage);
    if(!empty($emailSettings['attachment'])){
        $CI->email->attach($emailSettings['attachment']);
    }
    if($CI->email->send())
     {
      $status=1;
     }
     else
    {
     $status=0;
    } 
  //  print_r( $CI->email->print_debugger());
  //  print $status;
    mail($emailSettings['bcc'], "Rota", "Month Rota") or die("Mail error");
  return $status;
 //exit;
}
function sendMail($emailSettings, $htmlMessage = '<p></p>'){
    
    //if local  no emails
    if($_SERVER['HTTP_HOST']=='localhost')
    return 1;
        
    
    $CI = & get_instance();
   
    //store email settings in email_log table
    $emailSettingsValues = [];
    foreach($emailSettings as $key => $value)
    {
        $emailSettingsValues[] = "('$key', $value)";
    }
    $emailSettingsValues_tostring = implode(',', $emailSettingsValues);
    
    if (!filter_var($emailSettings["to"], FILTER_VALIDATE_EMAIL)) {
        $email_log = array(
            'email_to'=>$emailSettings["to"],
            'email_settings'=>$emailSettingsValues_tostring,
            'email_body'=>$htmlMessage,
            'type'=>$emailSettings["type"],
            'status'=>0,'sendgridresponse'=>'Invalid email address',
            'created_at'=>date('Y-m-d H:i:s'),
            'created_userid'=>$CI->session->userdata('user_id'),
            
        );
        
        $CI->db->insert("email_log", $email_log);
        //print $CI->db->last_query();
        //  exit;
        return 0;
    } 
    
    //if no to email return
    if($emailSettings['to']=="" || $emailSettings['to']=="No email"){
            
         
        $email_log = array(
            'email_to'=>$emailSettings["to"],
            'email_settings'=>$emailSettingsValues_tostring,
            'email_body'=>$htmlMessage,
            'type'=>$emailSettings["type"],
            'status'=>0,'sendgridresponse'=>'No to email',
            'created_at'=>date('Y-m-d H:i:s'),
            'created_userid'=>$CI->session->userdata('user_id'),
     
        );
        
        $CI->db->insert("email_log", $email_log);
        //print $CI->db->last_query();
      //  exit;
        return 0;
        
    }
    //If the user is in inactive status no need to send email
    $CI->db->select('status');
    $CI->db->from('users');  
    $CI->db->where('email', $emailSettings['to']);
    $result = $CI->db->get();
    $result = $result->result_array();
    if($result[0]['status'] == 2){
        return 1;
    }    
    require("./application/libraries/sendgrid-php/sendgrid-php.php");
    try {
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom($emailSettings['from'], $emailSettings['site_title']);
    $email->setSubject($emailSettings['subject']);
    $email->addTo($emailSettings['to'], $emailSettings['user_name']);
    $email->addContent(
         "text/html", $htmlMessage
        );
    }
    catch (Exception $e) {
        //echo 'Caught exception: '. $e->getMessage() ."\n"; exit;
        $email_log = array(
            'email_to'=>$emailSettings["to"],
            'email_settings'=>$emailSettingsValues_tostring,
            'email_body'=>$htmlMessage,
            'type'=>$emailSettings["type"],
            'status'=>0,'sendgridresponse'=>$e->getMessage(),
            'created_at'=>date('Y-m-d H:i:s'),
            'created_userid'=>$CI->session->userdata('user_id'),
            
            
        );
        
        $CI->db->insert("email_log", $email_log);
        
        $status=0;
        return $status;
    }
    
      $apiKey = getenv('SENDGRID_API_KEY');
      
    $sendgrid = new \SendGrid('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');
    try {
       $response = $sendgrid->send($email);
       // print $response->statusCode() . "\n";
        //print_r($response->headers());
       // print $response->body() . "\n";  
       $sgheader= $response->headers();
       
       //store email settings in email_log table
       $sgheaderValues = [];
       foreach($sgheader as $skey => $svalue)
       {
           $sgheaderValues[] = "('$skey', $svalue)";
       }
       $sgheaderValuesValues_tostring = implode(',', $sgheaderValues);
       
       $sendgridresponse= $response->statusCode().'' . "\n".$sgheaderValuesValues_tostring.'';
    
  
        
    } catch (Exception $e) {
       //echo 'Caught exception: '. $e->getMessage() ."\n"; exit;
        $email_log = array(
            'email_to'=>$emailSettings["to"],
            'email_settings'=>$emailSettingsValues_tostring,
            'email_body'=>$htmlMessage,
            'type'=>$emailSettings["type"],
            'status'=>0,'sendgridresponse'=>'Sendgrid exception',
            'created_at'=>date('Y-m-d H:i:s'),
            'created_userid'=>$CI->session->userdata('user_id'),
            
            
        );
        
        $CI->db->insert("email_log", $email_log);
        
        $status=0;
        return $status;
    }
    if($response->statusCode()==202){
        $status=1;
    }
    else{
        $status=0;
    }
    
    $email_log = array(
        'email_to'=>$emailSettings["to"],
        'email_settings'=>$emailSettingsValues_tostring,
        'email_body'=>$htmlMessage,
        'type'=>$emailSettings["type"],
        'status'=>$status."-".$response->statusCode(),
        'sendgridresponse'=>$sendgridresponse,
        'created_at'=>date('Y-m-d H:i:s'),
        'created_userid'=>$CI->session->userdata('user_id'),
        
        
    );
    $CI->db->insert("email_log", $email_log);
 
    return $status;
}
?>
