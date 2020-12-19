<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		$result = $query->result_array();
		
		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', $v['date_time']);
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getOrderData($year)
	{	
		if($year) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	


			return $final_data;
			
		}
	}

	public function getInvoiceListing($date_from, $date_to){

		if($date_from && $date_to) {
			$sql = "SELECT * FROM invoice_master where invoice_date >= '$date_from' and invoice_date <= '$date_to' and `status` = 1 ";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}
	
	public function getQuotationListing($date_from, $date_to){

		if($date_from && $date_to) {
			$sql = "SELECT * FROM quotation_master where quotation_date >= '$date_from' and quotation_date <= '$date_to' and `status` = 1 ";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}

	public function getPurchaseListing($date_from, $date_to){

		if($date_from && $date_to) {
			$sql = "SELECT * FROM purchase_master where purchase_date >= '$date_from' and purchase_date <= '$date_to' and `status` = 1 ";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}


	public function geItemSell($item_id, $date_from , $date_to){

		if($item_id && $date_from && $date_to) {
			$sql = "SELECT invoice_item.item_id, invoice_master.invoice_date, invoice_master.invoice_no FROM invoice_item inner join invoice_master on invoice_item.invoice_no = invoice_master.invoice_no where invoice_item.item_id = $item_id and invoice_master.invoice_date >= '$date_from' and invoice_master.invoice_date <= '$date_to' and invoice_master.status = 1";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}

	

}