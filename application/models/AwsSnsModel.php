<?php
require_once APPPATH.'third_party/aws/aws-autoloader.php';
class AwsSnsModel extends CI_Model {
    
    public function SendSms($mobilenumber, $senderId, $message) {
        
        if($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='13.59.121.248')
            return true;
        
        //us-east-1
        //eu-west-1
        
        $mobilenumber = ltrim($mobilenumber,"0");
        $mobilenumber = ltrim($mobilenumber,"+44");
        $countrycode= '+44';
        $mobilenumber=$countrycode.$mobilenumber;
	    
	 /*    $params = array(
	        'credentials'=> array(
	            'key'=>'AKIA6NLWWFQPCTL45YNF',
	            'secret'=>'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',
	        ),
	        'region'=>'us-east-1',
	        'version'=>'2010-03-31'
	    ); */
        $params = array(
            'credentials'=> array(
                'key'=>$this->config->item('aws_key'),
                'secret'=>$this->config->item('aws_secret'),
            ),
            'region'=>'eu-west-1',
            'version'=>'latest'
        );
	    $sns = new \Aws\Sns\SnsClient($params);

	    if($senderId!=''){
			$senderId=$senderId;
	    }
	    else{
	    	$senderId="SMHRota";
	    }
	    $args = array(
	        "SenderID"=>$senderId,
	        "SMSType"=>"Promotional",
	        "Message"=>$message,
	        "PhoneNumber"=>$mobilenumber,
	    );
	    
	    //print_r($args);exit();
	    
	    $pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/";
	    $match = preg_match($pattern,$mobilenumber);
	    
	    if ($match != false) {
	        $result = $sns->publish($args);
	    }
	    else{
	        $result['@metadata']['statusCode']="404";
	        $result['MessageId']=$match;
	        $result['@metadata']['headers']['date']="error";
	    }
	    
	    $save_result = $this->saveSmslog($mobilenumber,$result,$senderId,$message); 
	  	return $save_result;
 
	}
	public function SendSmsTest($mobilenumber, $senderId, $message) {
	    
	    $params = array(
	        'credentials'=> array(
	            'key'=>$this->config->item('aws_key'),
	            'secret'=>$this->config->item('aws_secret'),
	        ),
	        'region'=>'eu-west-1',
	        'version'=>'latest'
	    );
	    $sns = new \Aws\Sns\SnsClient($params);
	    $args = array(
	        "SenderID"=>"SMHRota",
	        "SMSType"=>"Promotional",
	        "Message"=>$message,
	        "PhoneNumber"=>$mobilenumber,
	    );
	    //print_r($args);
	    $result = $sns->publish($args);
 	    return $result;
	    
	}
	public function SendOtpTest($mobilenumber, $senderId, $message) {
	    
	    $params = array(
	        'credentials'=> array(
	            'key'=>'AKIA6GBMBKF475ULKI52',
	            'secret'=>'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',
	        ),
	        'region'=>'ap-south-1',
	        'version'=>'latest'
	    );
	    $sns = new \Aws\Sns\SnsClient($params);
	    $args = array(
	        "SenderID"=>"SMHRota",
	        "SMSType"=>"Promotional",
	        "Message"=>$message,
	        "PhoneNumber"=>$mobilenumber,
	    );
	    //print_r($args);
	    $result = $sns->publish($args);
 	    return $result;
	    
	}
	public function saveSmslog($mobilenumber,$result,$senderId,$message){
		$sns_details = array(
		    'mobile_number'=> $mobilenumber,
		    'status_code' => $result['@metadata']['statusCode'],
		    'message_id' => $result['MessageId'],
		    'date' => $result['@metadata']['headers']['date'],
		    'sender_id' => $senderId,
		    'message' => $message
		);
		//Wed, 18 Sep 2019 10:12:31 GMT
		$query = $this->db->insert('sms_log',$sns_details);
		$sns_id = $this->db->insert_id();
		return true;
	}
}
?>