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
		$sql = "SELECT * FROM party_master WHERE active = 1";
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

	public function getPartyfromSearch($search_text){
		$sql = "SELECT * FROM party_master WHERE active = 1 AND party_name  LIKE '%$search_text%' LIMIT 20";
		// $sql = "SELECT * FROM item_master ORDER BY Item_ID DESC";

		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getLastID()
	{
		$sql = "SELECT  MAX(party_id) FROM party_master";
		$query = $this->db->query($sql);
		// $row = mysql_fetch_array($query);
		// echo $row['id'];
		return $query->result_array();

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