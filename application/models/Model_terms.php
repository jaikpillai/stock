<?php 

class Model_terms extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveTax()
	{
		$sql = "SELECT * FROM tax_master WHERE active = 1";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getTaxData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM tax_master WHERE iTax_ID = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM tax_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getTaxFromID($id)
	{
		
			$sql = "SELECT * FROM tax_master WHERE iTax_ID = ? and `active` = 1";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		
		

		// $sql = "SELECT * FROM categories";
		// $query = $this->db->query($sql);
		// return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('tax_master', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('iTax_ID', $id);
			$update = $this->db->update('tax_master', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('iTax_ID', $id);
			$delete = $this->db->delete('tax_master');
			return ($delete == true) ? true : false;
		}
	}

}