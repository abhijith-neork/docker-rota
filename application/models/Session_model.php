<?php
class Session_model extends CI_Model {

   public function __construct(){
     	parent::__construct();
   }
   /**
    * 
    * delete user data
    * @param (array) $params
    */
   public function delete($userid){
   	  	if(!empty($userid)){
	   	$this ->db-> where('userid', $userid);
	   	$this ->db-> delete('ci_sessions');
   	  	}
 
   }
 
   
 
   /**
    * 
    * Change your password
    * @param unknown_type $params
    */
   public function update_userid($userid, $session){
	   	$data = array(
	   	               'userid' => $userid 
	   			);
	   	
	   	if(!empty($userid)){
   		$this->db->where('id', $session);
   		$this->db->update('ci_sessions', $data);
	   	}
	  /*  	 echo $this->db->last_query();
	   	 exit */;
   		return true;
   }
 
 
 
   
}
