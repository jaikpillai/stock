<?php
class Crud_model extends CI_Model 
{
	function saverecords($data)
	{
        // $query="insert into categories values('$fname','$lname')";
        $insert = $this->db->insert('categories', $data);
        // $this->db->query($query);
        return ($insert == true) ? true : false;
	}
	
}