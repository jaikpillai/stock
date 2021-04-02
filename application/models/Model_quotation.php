<?php

class Model_quotation extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the orders data */
	public function getQuotationData($id = null)
	{
		$selected_financial_year = $this->session->userdata("selected_financial_year");
		if ($id) {
			$sql = "SELECT * FROM quotation_master JOIN party_master WHERE quotation_master.s_no = ? AND quotation_master.status = 1  AND party_master.party_id = quotation_master.party_id";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}


		if ($selected_financial_year) {
			$sql = "SELECT * FROM quotation_master WHERE `status` = 1 AND financial_year_id = $selected_financial_year ORDER BY quotation_no DESC";
			$query = $this->db->query($sql, array(1));
			return $query->result_array();
		} else {
			$financial_years = $this->model_financialyear->getFinancialYear();
			$current_date = date("Y-m-d");

			foreach ($financial_years as $k => $v) {
				$start_date = $v['start_date'];
				$end_date = $v['end_date'];
				$financial_year_id = $financial_year_id = $v['key_value'];



				if (($current_date >= $start_date) && ($current_date <= $end_date)) {

					$sql = "SELECT * FROM quotation_master WHERE `status` = 1 AND financial_year_id = $financial_year_id ORDER BY quotation_no DESC";
					$query = $this->db->query($sql, array(1));
					return $query->result_array();
				}
			}
		}
	}


	// get the orders item data
	public function getQuotationItemData($order_id = null)
	{
		$selected_financial_year = $this->session->userdata("selected_financial_year");

		if (!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM quotation_item JOIN item_master WHERE quotation_item.quotation_no = ? AND item_master.Item_ID=quotation_item.item_id AND financial_year_id=$selected_financial_year AND quotation_item.status=1";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getFooter($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT terms_and_conditions.description FROM quotation_footer JOIN terms_and_conditions ON quotation_footer.t_and_c=terms_and_conditions.s_no AND quotation_footer.quotation_id=? UNION
		SELECT terms_and_conditions.description FROM terms_and_conditions WHERE is_default=1";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}


	public function getFinancialYearID()
	{


		$sql = "SELECT * FROM financial_year WHERE status = ?";
		$query = $this->db->query($sql, 1);
		return $query->result_array();
	}

	public function create()
	{
		$user_id = $this->session->userdata('id');
		$sql = "SELECT * FROM financial_year WHERE status = ?";
		$financial_id = $this->getFinancialYearID();
		$tax = $this->model_tax->getTaxData($this->input->post('tax'));

		$selected_financial_year = $this->session->userdata("selected_financial_year");

		if ($selected_financial_year) {

			$financial_year_id = $selected_financial_year;
		} else {


			$financial_years = $this->model_financialyear->getFinancialYear();
			$current_date = date("Y-m-d");

			foreach ($financial_years as $k => $v) {
				$start_date = $v['start_date'];
				$end_date = $v['end_date'];



				if (($current_date >= $start_date) && ($current_date <= $end_date)) {

					$financial_year_id = $v['key_value'];
				}
			}
		}




		// $total_discount



		// $bill_no = 'BILPR-'.strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
		$data = array(
			'quotation_no' => $this->input->post('quotation_no'),
			'quotation_date' => $this->input->post('date'),
			'variation' => $this->input->post('variation'),
			'party_id' => $this->input->post('party'),
			'ref_no' => $this->input->post('ref_no'),
			'ref_date' => $this->input->post('ref_date'),
			'status' => 1,
			'total_discount' => $this->input->post('total_discount'),
			'total_gst' => $this->input->post('total_gst'),
			'total_amount' => $this->input->post('total_amount_value'),
			// 'tax_id' =>  $this->input->post('tax'),
			'financial_year_id' => $financial_year_id,
			// 'tax_value' => $tax['sValue'],
			'other_charges' => $this->input->post('other_charge'),
			// 'bill_no' => $bill_no,
			// 'customer_name' => $this->input->post('customer_name'),
			// 'customer_address' => $this->input->post('customer_address'),
			// 'customer_phone' => $this->input->post('customer_phone'),
			// 'date_time' => strtotime(date('Y-m-d h:i:s a')),
			// 'gross_amount' => $this->input->post('gross_amount_value'),
			// 'service_charge_rate' => $this->input->post('service_charge_rate'),
			// 'service_charge' => ($this->input->post('service_charge_value') > 0) ?$this->input->post('service_charge_value'):0,
			// 'vat_charge_rate' => $this->input->post('vat_charge_rate'),
			// 'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
			// 'net_amount' => $this->input->post('net_amount_value'),
			// 'discount' => $this->input->post('discount'),
			// 'paid_status' => 2,
			// 'user_id' => $user_id
		);

		$insert = $this->db->insert('quotation_master', $data);
		$order_id = $this->db->insert_id();

		$this->load->model('model_products');

		$count_product = count($this->input->post('product'));
		for ($x = 0; $x < $count_product; $x++) {

			$items = array(

				'quotation_no' =>  $this->input->post('quotation_no'),
				'item_id' => $this->input->post('product')[$x],
				'item_code' => $this->input->post('code')[$x],
				'item_make' => $this->input->post('make_value')[$x],
				'qty' => $this->input->post('qty')[$x],
				'unit' => $this->input->post('unit_value')[$x],
				'rate' => $this->input->post('rate_value')[$x],
				'discount' => $this->input->post('discount')[$x],
				'tax_id' => $this->input->post('gst')[$x],
				// 'tax' => $this->input->post('gst_value')[$x],
				'financial_year_id' => $financial_year_id,
				'status' => 1,


			);

			$this->db->insert('quotation_item', $items);


			// now decrease the stock from the product
			// $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);

			// $qty = (int) $product_data['Max_Suggested_Qty'] + (int) $this->input->post('qty')[$x];

			// $update_product = array('Max_Suggested_Qty' => $qty);


			// $this->model_products->update($update_product, $this->input->post('product')[$x]);
		}

		if ($this->input->post('terms')) {
			$count_terms = count($this->input->post('terms'));
			for ($z = 0; $z < $count_terms; $z++) {

				$footer = array(
					'quotation_id' => $this->input->post('quotation_no'),
					't_and_c' => $this->input->post('terms')[$z],
				);

				$this->db->insert('quotation_footer', $footer);
			}
		}

		return ($order_id) ? $order_id : false;
	}

	public function getLastQuotationID()
	{
		$selected_financial_year = $this->session->userdata("selected_financial_year");


		if ($selected_financial_year) {
			$sql = "SELECT MAX(quotation_no) FROM quotation_master WHERE `status` = 1 AND financial_year_id = $selected_financial_year";
			$query = $this->db->query($sql);
			return $query->result_array();
		} else {
			$financial_years = $this->model_financialyear->getFinancialYear();
			$current_date = date("Y-m-d");

			foreach ($financial_years as $k => $v) {
				$start_date = $v['start_date'];
				$end_date = $v['end_date'];
				$financial_year_id = $v['key_value'];



				if (($current_date >= $start_date) && ($current_date <= $end_date)) {

					$sql = "SELECT  MAX(quotation_no) FROM quotation_master WHERE financial_year_id = $financial_year_id";
					$query = $this->db->query($sql);
					// $row = mysql_fetch_array($query);
					// echo $row['id'];
					return $query->result_array();
				}
			}
		}
	}

	public function countQuotationItem($quotation_no)
	{
		$selected_financial_year = $this->session->userdata("selected_financial_year");


		if ($quotation_no) {
			if ($selected_financial_year) {
				$sql = "SELECT * FROM quotation_item WHERE quotation_no = ? AND financial_year_id=$selected_financial_year AND `status`=1";
				$query = $this->db->query($sql, array($quotation_no));
				return $query->num_rows();
			} else {
				$financial_years = $this->model_financialyear->getFinancialYear();
				$current_date = date("Y-m-d");

				foreach ($financial_years as $k => $v) {
					$start_date = $v['start_date'];
					$end_date = $v['end_date'];
					$financial_year_id = $v['key_value'];



					if (($current_date >= $start_date) && ($current_date <= $end_date)) {
						$selected_financial_year = $financial_year_id;

						$sql = "SELECT * FROM quotation_item WHERE quotation_no = ? AND financial_year_id=$selected_financial_year AND `status`=1";
						$query = $this->db->query($sql, array($quotation_no));
						return $query->num_rows();
					}
				}
			}
		}
	}

	public function update($id, $quotation_no)
	{
		$user_id = $this->session->userdata('id');
		$sql = "SELECT * FROM financial_year WHERE status = ?";
		$financial_id = $this->getFinancialYearID();
		$tax_id = $this->input->post('tax');
		$tax = $this->model_tax->getTaxData($tax_id);

		$selected_financial_year = $this->session->userdata("selected_financial_year");

		if ($selected_financial_year) {



			$financial_year_id = $selected_financial_year;
		} else {


			$financial_years = $this->model_financialyear->getFinancialYear();
			$current_date = date("Y-m-d");

			foreach ($financial_years as $k => $v) {
				$start_date = $v['start_date'];
				$end_date = $v['end_date'];



				if (($current_date >= $start_date) && ($current_date <= $end_date)) {

					$financial_year_id = $v['key_value'];
				}
			}
		}






		if ($id) {
			$user_id = $this->session->userdata('id');
			// fetch the order data 

			$data = array(

				'quotation_no' => $this->input->post('quotation_no'),
				'quotation_date' => $this->input->post('date'),
				'variation' => $this->input->post('variation'),
				'party_id' => $this->input->post('party'),
				'ref_no' => $this->input->post('ref_no'),
				'ref_date' => $this->input->post('ref_date'),
				'status' => 1,
				'total_discount' => $this->input->post('total_discount'),
				'total_gst' => $this->input->post('total_gst'),
				'total_amount' => $this->input->post('total_amount_value'),
				// 'tax_id' =>  $this->input->post('tax'),
				'financial_year_id' => $financial_year_id,
				// 'tax_value' => $tax['sValue'],
				'other_charges' => $this->input->post('other_charge'),



				// 'customer_name' => $this->input->post('customer_name'),
				// 'customer_address' => $this->input->post('customer_address'),
				// 'customer_phone' => $this->input->post('customer_phone'),
				// 'gross_amount' => $this->input->post('gross_amount_value'),
				// 'service_charge_rate' => $this->input->post('service_charge_rate'),
				// 'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value'):0,
				// 'vat_charge_rate' => $this->input->post('vat_charge_rate'),
				// 'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
				// 'net_amount' => $this->input->post('net_amount_value'),
				// 'discount' => $this->input->post('discount'),
				// 'is_payment_received' => $this->input->post('paid_status'),
				// 'user_id' => $user_id
			);

			$this->db->where('s_no', $id);
			$update = $this->db->update('quotation_master', $data);

			// now the order item 
			// first we will replace the product qty to original and subtract the qty again
			$this->load->model('model_products');

			// foreach ($get_order_item as $k => $v) {
			// 	$product_id = $v['item_id'];
			// 	$qty = $v['qty'];
			// 	// get the product 
			// 	$product_data = $this->model_products->getProductData($product_id);
			// 	$update_qty = $qty - $product_data['Max_Suggested_Qty'];
			// 	$update_product_data = array('Max_Suggested_Qty' => $update_qty);

			// 	// update the product qty
			// 	$this->model_products->update($update_product_data, $product_id);
			// }

			// now remove the order item data 
			// $this->db->where('quotation_no', $quotation_no);
			// $this->db->delete('quotation_item');

			$query = "DELETE FROM quotation_item WHERE quotation_no= $quotation_no AND financial_year_id=$financial_year_id";
			$this->db->query($query);

			// now decrease the product qty
			$count_product = count($this->input->post('product'));
			for ($x = 0; $x < $count_product; $x++) {
				$items = array(

					'quotation_no' =>  $this->input->post('quotation_no'),
					'item_id' => $this->input->post('product')[$x],
					'item_code' => $this->input->post('code')[$x],
					'item_make' => $this->input->post('make_value')[$x],
					'qty' => $this->input->post('qty')[$x],
					'unit' => $this->input->post('unit_value')[$x],
					'rate' => $this->input->post('rate_value')[$x],
					'discount' => $this->input->post('discount')[$x],
					'tax_id' => $this->input->post('gst')[$x],
					'financial_year_id' => $financial_year_id,
					'status' => 1,


					// 'order_id' => $id,
					// 'product_id' => $this->input->post('product')[$x],
					// 'qty' => $this->input->post('qty')[$x],
					// 'rate' => $this->input->post('rate_value')[$x],
					// 'amount' => $this->input->post('amount_value')[$x],
				);
				$this->db->insert('quotation_item', $items);

				// // now decrease the stock from the product
				// $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
				// $qty = (int) $product_data['Max_Suggested_Qty'] + (int) $this->input->post('qty')[$x];

				// $update_product = array('Max_Suggested_Qty' => $qty);
				// $this->model_products->update($update_product, $this->input->post('product')[$x]);
			}

			$this->db->where('quotation_id', $quotation_no);
			$this->db->delete('quotation_footer');

			if ($this->input->post('terms')) {
				$count_terms = count($this->input->post('terms'));
				for ($z = 0; $z < $count_terms; $z++) {

					$footer = array(
						'quotation_id' => $quotation_no,
						't_and_c' => $this->input->post('terms')[$z],
					);
					$this->db->insert('quotation_footer', $footer);
				}
			}
			return true;
		}
	}



	public function remove($id, $quotation_no)
	{
		if ($id) {
			$data = array(

				'status' => 0
			);

			$this->db->where('s_no', $id);
			$delete = $this->db->update('quotation_master', $data);

			$this->db->where('quotation_no', $quotation_no);
			$delete_item = $this->db->update('quotation_item', $data);
			return ($delete == true && $delete_item) ? true : false;
		}
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM quotation_master WHERE `status` = 1";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}
}
