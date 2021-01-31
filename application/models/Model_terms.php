<?php 

class Model_terms extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveTerms()
	{
		$sql = "SELECT * FROM terms_and_conditions WHERE is_default = 1 and active = 1 ";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getTermsData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM terms_and_conditions WHERE s_no = ? and active = 1";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM terms_and_conditions WHERE active = 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function getTermsDataInOrder($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT terms_and_conditions.description,terms_and_conditions.s_no FROM invoice_footer JOIN terms_and_conditions ON invoice_footer.t_and_c=terms_and_conditions.s_no AND invoice_footer.invoice_id=? ";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getTermsDataInQuotation($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT terms_and_conditions.description,terms_and_conditions.s_no FROM quotation_footer JOIN terms_and_conditions ON quotation_footer.t_and_c=terms_and_conditions.s_no AND quotation_footer.quotation_id=? ";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}


	public function getTermsFromID($id)
	{
		
			$sql = "SELECT * FROM terms_and_conditions WHERE s_no = ? ";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		
		

		// $sql = "SELECT * FROM categories";
		// $query = $this->db->query($sql);
		// return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('terms_and_conditions', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('s_no', $id);
			$update = $this->db->update('terms_and_conditions', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('s_no', $id);
			$delete = $this->db->delete('terms_and_conditions');
			return ($delete == true) ? true : false;
		}
	}

}