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
		if($id) {
			$sql = "SELECT * FROM quotation_master WHERE quotation_no = ? AND status = 1 "  ;
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM quotation_master WHERE status = 1 ORDER BY quotation_no DESC ";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	// get the orders item data
	public function getQuotationItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM quotation_item WHERE quotation_no = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getFooter($order_id = null)
	{
		if(!$order_id) {
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

		foreach ($financial_id as  $key => $value): 
			// $new_id =$value['MAX(Item_ID)']+1
			$financial_year_id = $value['key_value'];
		endforeach;

	



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
			'tax_id' =>  $this->input->post('tax'),
			'financial_year_id' => $financial_year_id,
			'tax_value' => $tax['sValue'],
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
    	for($x = 0; $x < $count_product; $x++) {
    		$items = array(
				
    			'quotation_no' =>  $this->input->post('quotation_no'),
				'item_id' => $this->input->post('product')[$x],
				'item_code' => $this->input->post('code_value')[$x],
				'item_make' => $this->input->post('make_value')[$x],
				'qty' => $this->input->post('qty')[$x],
				'unit' => $this->input->post('unit_value')[$x],
				'rate' => $this->input->post('rate')[$x],
				'discount' => $this->input->post('discount')[$x],
				// 'tax' => $this->input->post('gst_value')[$x],
				'financial_year_id' => $financial_year_id,
				'status' => 1
				
				
    		);

    		$this->db->insert('quotation_item', $items);
    		// now decrease the stock from the product
			// $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
			
    		// $qty = (int) $product_data['Max_Suggested_Qty'] + (int) $this->input->post('qty')[$x];

    		// $update_product = array('Max_Suggested_Qty' => $qty);


    		// $this->model_products->update($update_product, $this->input->post('product')[$x]);
    	}

		return ($order_id) ? $order_id : false;
	}

	public function getLastQuotationID()
	{
		$sql = "SELECT  MAX(quotation_no) FROM quotation_master";
		$query = $this->db->query($sql);
		// $row = mysql_fetch_array($query);
		// echo $row['id'];
		return $query->result_array();

	}

	public function countQuotationItem($quotation_no)
	{
		if($quotation_no) {
			$sql = "SELECT * FROM quotation_item WHERE quotation_no = ?";
			$query = $this->db->query($sql, array($quotation_no));
			return $query->num_rows();
		}
	}

	public function update($id,$quotation_no)
	{
		$user_id = $this->session->userdata('id');
		$sql = "SELECT * FROM financial_year WHERE status = ?";
		$financial_id = $this->getFinancialYearID();
		$tax_id = $this->input->post('tax');
		$tax = $this->model_tax->getTaxData($tax_id);

		foreach ($financial_id as  $key => $value): 
			// $new_id =$value['MAX(Item_ID)']+1
			$financial_year_id = $value['key_value'];
		endforeach;

	




		if($id) {
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
				'tax_id' =>  $this->input->post('tax'),
				'financial_year_id' => $financial_year_id,
				'tax_value' => $tax['sValue'],
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
			$this->db->where('quotation_no', $quotation_no);
			$this->db->delete('quotation_item');

			// now decrease the product qty
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {
	    		$items = array(

					'quotation_no' =>  $this->input->post('quotation_no'),
					'item_id' => $this->input->post('product')[$x],
					'item_code' => $this->input->post('code_value')[$x],
					'item_make' => $this->input->post('make_value')[$x],
					'qty' => $this->input->post('qty')[$x],
					'unit' => $this->input->post('unit_value')[$x],
					'rate' => $this->input->post('rate')[$x],
					'discount' => $this->input->post('discount')[$x],
					'financial_year_id' => $financial_year_id,
					'status' => 1


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

			return true;
		}
	}



	public function remove($id, $quotation_no)
	{
		if($id) {
			$data = array(

				'status' => 0);
			
			$this->db->where('s_no', $id);
			$delete = $this->db->update('quotation_master',$data);

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