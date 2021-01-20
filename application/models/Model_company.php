<?php 

class Model_company extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getCompanyData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM company WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	/* get the bank details */
	public function getBankDetails()
	{
			$sql = "SELECT * FROM bank_details";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('company', $data);


			// now decrease the product qty
			$count_bank_details = count($this->input->post('bank_name'));
			for ($x = 0; $x < $count_bank_details; $x++) {

				
			// now remove the order item data 
			$this->db->where('bank_name', $this->input->post('bank_name')[$x]);
			$this->db->delete('bank_details');

				$items = array(
					'acc_no' => $this->input->post('bank_account_number')[$x],
					'bank_name' => $this->input->post('bank_name')[$x],
					'ifsc' => $this->input->post('bank_ifsc')[$x],
					'bank_address' => $this->input->post('bank_address')[$x]
					
				);
				$this->db->insert('bank_details', $items);
		}
		
	}
}


}