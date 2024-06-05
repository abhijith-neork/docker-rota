<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
class S3
{
	/**
	*
	* Get connection
	*
	*/
	public function getConnection(){
		//Replace with config file
		$client = S3Client::factory(array('credentials' => array(
    'key'=> "AKIAIQPW2VI5FHAEUYGA",'secret' => "Ib6R8go6O0zoMSS7VVj+M8BR4W9IGcErEv4+3rL0"),'region' => 'eu-west-2','version'=>'latest'));
		 return $client;
	}
}
?>