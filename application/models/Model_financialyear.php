<?php 

class Model_financialyear extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveFinancialYear()
	{
		$sql = "SELECT * FROM financial_year WHERE `status` = 1";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getFinancialYear($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM financial_year WHERE key_value = ? ORDER BY year_range";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM financial_year ORDER BY year_range";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('financial_year', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('key_value', $id);
			$update = $this->db->update('financial_year', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('key_value', $id);
			$delete = $this->db->delete('financial_year');
			return ($delete == true) ? true : false;
		}
	}

}