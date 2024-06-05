<?php

class Device_Model extends CI_Model 
{

	
	public function insert($data)
	{
	    $this->db->insert("devices", $data);
	    //echo $this->db->last_query(); exit;
	}


	 
}

?>