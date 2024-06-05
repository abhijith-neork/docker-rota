<?php
require_once APPPATH.'third_party/aws/aws-autoloader.php';
class MY_Model extends CI_Model
{
	public function SendAwsSms(){
		$params = array(
			'credentials'=> array(
				'key'=>'AKIA6NLWWFQPCTL45YNF',
				'secret'=>'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',
			),
			'region'=>'us-east-1',
			'version'=>'2010-03-31'
		);
		$sns = new \Aws\Sns\SnsClient($params);
		// $snw = new \Aws\Exception\AwsException(0,0);
		// $result = $sns->listPhoneNumbersOptedOut([]);
		// try {
		    /*$args = array(
    			"SenderID"     => "ST-MAT-HS",
    			"SMSType"      => "Transactional",
    			"Message"      => "Hello ! This is a test message",
    			// "PhoneNumber"  => "+447429178994",
    			"PhoneNumber"  => "918943135148",
    		);
    		$result = $sns->publish($args);*/
    		// print "hellooo";
    		// print("<pre>".print_r($result,true)."</pre>");exit();
		    // var_dump($result);
		/*} catch (AwsException $e) {
		    // output error message if fails
		    error_log($e->getMessage());
		} */
	}
}?>

<!-- Access key ID  AKIA6NLWWFQPCTL45YNF
Secret access key  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 -->

<!-- Access key ID  AKIA6NLWWFQPCTL45YNF
Secret access key  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 -->