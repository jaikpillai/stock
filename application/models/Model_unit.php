<?php 

class Model_unit extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveUnit()
	{
		$sql = "SELECT * FROM unit_master WHERE active = 1";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getUnitData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM unit_master WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM unit_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getUnitDataFromsUnit($sUnit = null)
	{
		if($sUnit) {
			$sql = "SELECT * FROM unit_master WHERE sUnit  = ? ";
			$query = $this->db->query($sql, array($sUnit));
			return $query->row_array();
		}

		$sql = "SELECT * FROM unit_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getUnitFromID($id)
	{
		
			$sql = "SELECT * FROM unit_master WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		
		

		// $sql = "SELECT * FROM categories";
		// $query = $this->db->query($sql);
		// return $query->result_array();
	}

	public function getUnitFromUnit($id)
	{
		
			$sql = "SELECT * FROM unit_master WHERE sUnit = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		
		

		// $sql = "SELECT * FROM categories";
		// $query = $this->db->query($sql);
		// return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('unit_master', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('unit_master', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('unit_master');
			return ($delete == true) ? true : false;
		}
	}

}