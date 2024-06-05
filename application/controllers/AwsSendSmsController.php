<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AwsSendSmsController extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AwsSnsModel');
	}
    public function index(){
        
        $countrycode= '+44';
        // $mobilenumber=$countrycode.'7429178994 7908606762';
       // $mobilenumber=$countrycode.'7429178994';
        $mobilenumber=$countrycode.'7728414781';
      
        $senderId="SMH01";
        
    /*     $message ="Your rota for next month is ready, for the following week(s).\n 
Week 1: 05/01/2020 to 11/01/2020\n
Week 2: 22/12/2019 to 28/12/2019\n
Week 3: 29/12/2019 to 04/01/2020\n
http://eota.stmatthewshealthcare.com"; */
        $message ="This is a test sms";
        $result = $this->AwsSnsModel->SendSmsTest($mobilenumber, $senderId, $message);
        print "<pre>";
        print_r($result);
        //save the below variables in table sms_log
      /*   $mobilenumber;
        $result[statusCode];
        $result['MessageId'];
        $result['headers']['date'];
        $senderId; //01 for rota, 02 for training, 03 for leave request, 04 for update in rota
         */
        
    }
}?>