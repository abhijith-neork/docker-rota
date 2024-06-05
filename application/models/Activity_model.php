<?php
class Activity_model extends CI_Model 
{


public function insertactivity($log)
{ 
	$this->db->insert('activity_log',$log);
}
	
	
}

?>