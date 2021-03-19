<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getProductData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM item_master where Item_ID = ? ";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM item_master ORDER BY Item_ID";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function getActiveProductData()
	{
		$sql = "SELECT * FROM item_master WHERE active = 1 ORDER BY Item_ID";
		// $sql = "SELECT * FROM item_master ORDER BY Item_ID DESC";

		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}


	public function getProductfromSearch($search_text){
		$sql = "SELECT * FROM item_master WHERE active = 1 AND Item_Name  LIKE '%$search_text%' OR Item_Code LIKE '%$search_text%' ORDER BY Item_ID LIMIT 20";
		// $sql = "SELECT * FROM item_master ORDER BY Item_ID DESC";

		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getLastID()
	{
		$sql = "SELECT  MAX(Item_ID) FROM item_master";
		$query = $this->db->query($sql);
		// $row = mysql_fetch_array($query);
		// echo $row['id'];
		return $query->result_array();

	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('item_master', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('Item_ID', $id);
			$update = $this->db->update('item_master', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($data, $id)
	{
		if($id) {
			//TODO: change data object
			$this->db->where('Item_ID', $id);
			$delete = $this->db->update('item_master', $data);
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM item_master";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

}