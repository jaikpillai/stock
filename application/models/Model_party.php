<?php 

class Model_party extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveParty()
	{
		$sql = "SELECT * FROM party_master WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getPartyData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM party_master WHERE party_id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM party_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getPartyFromID($id)
	{
		
			$sql = "SELECT * FROM party_master WHERE party_id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		
		

		// $sql = "SELECT * FROM categories";
		// $query = $this->db->query($sql);
		// return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('party_master', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('party_id', $id);
			$update = $this->db->update('party_master', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('party_id', $id);
			$delete = $this->db->delete('party_master');
			return ($delete == true) ? true : false;
		}
	}

}