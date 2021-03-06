<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Reports';
		$this->load->model('model_reports');
		$this->load->model('model_quotation');
		$this->load->model('model_purchase');
		$this->load->model('model_orders');
		$this->load->model('model_company');
		$this->load->model('model_party');
		$this->load->model('model_products');
		$this->load->model('model_stores');
		$this->load->model('model_tax');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if (!in_array('viewReports', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$today_year = date('Y');

		if ($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}


		$parking_data = $this->model_reports->getOrderData($today_year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();




		$final_parking_data = array();
		foreach ($parking_data as $k => $v) {

			if (count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if ($v2) {
						$total_amount_earned[] = $v2['gross_amount'];
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_earned);
			} else {
				$final_parking_data[$k] = 0;
			}
		}


		$this->data['selected_year'] = $today_year;

		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;
		$this->data['products'] = $this->model_products->getProductData();
		$this->data['item_make'] = $this->model_reports->getItemMake();
		$this->data['customer'] = $this->model_party->getPartyData();


		$this->render_template('reports/index', $this->data);
	}

	public function invoice($var = null)
	{



		// $this->base_url('reports/printDiv/'.$date_start.'/'.$date_from.'');




	}

	public function printDiv()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');






		if ($date_from && $date_to) {

			$invoice_data = $this->model_reports->getInvoiceListing($date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);
			$grand_total_discount = 0;
			$grand_total_gst = array();
			$grand_total_amount = 0;
			$unique_tax = array();


			setlocale(LC_MONETARY, "en_US");

			if ($invoice_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Sale Tax Register from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.css') . '?v=<?=time();?">
			  <style>@media print{@page {size: landscape}}</style>

			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
				  <h4 class="invoice-title-address">Sale Tax Register</h4>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			   

			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
				<hr>	
			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-bordered"  style="font-size: 10px;" >
					  <thead>';
				foreach ($invoice_data as $k => $v) {
					$orders_items = $this->model_orders->getOrdersItemData($v['invoice_no']);
					foreach ($orders_items as $k => $v) {
						if ($v['tax_id'] > 0) {
							$tax_data = $this->model_tax->getTaxData($v['tax_id']);
							if (!in_array($v['tax_id'], $unique_tax)) {
								array_push($unique_tax, $v['tax_id']);
								$grand_total_gst[$tax_data['sTax_Description']] = 0;
								$grand_gst_amount[$tax_data['sTax_Description']] = 0;


								// $tax_array[$tax_data['sTax_Description']]=0;
							}
						}
					}
				}

				$html .= '
					  <tr>
						<th>S.N.</th>
						<th>Invoice No</th>
						<th>Invoice Date</th>
						<th>Customer</th>
						<th>Amount</th>';
				for ($i = 0; $i < sizeof($unique_tax); $i++) {
					$tax_data = $this->model_tax->getTaxData($unique_tax[$i]);
					$html .= '<th>Amount ' . $tax_data['sTax_Description'] . '</th>
							<th>' . $tax_data['sTax_Description'] . '</th>';
				}
				// 	<th>Discount</th>
				// 	<th>GST</th>

				// 	<th>Payment Received</th>

				//   </tr>
				$html .= ' </thead>
			          <tbody>';

				foreach ($invoice_data as $k => $v) {

					$grand_total_discount = $v['total_discount'] + $grand_total_discount;
					// $grand_total_gst = $v['total_gst'] + $grand_total_gst;
					$grand_total_amount = $v['total_amount'] + $grand_total_amount;



					$party_data = $this->model_party->getPartyData($v['party_id']);
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;


					$unique_tax_temp = array();
					$tax_array = array();


					$orders_items_details = $this->model_orders->getOrdersItemData($v['invoice_no']);
					foreach ($orders_items_details as $t => $f) {
						if ($f['tax_id'] > 0) {
							$tax_data = $this->model_tax->getTaxData($f['tax_id']);
							if (!in_array($f['tax_id'], $unique_tax_temp)) {
								array_push($unique_tax_temp, $f['tax_id']);
								$tax_array[$tax_data['sTax_Description']] = 0;
							}
							$amount = $f['qty'] * $f['rate'];
							$discount_amount = $amount - ($amount * $f['discount']) / 100;

							$tax_array[$tax_data['sTax_Description']] = $tax_array[$tax_data['sTax_Description']] + $discount_amount;
						}
					}

					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['invoice_no'] . '</td>
							<td>' . date('d-m-Y', strtotime($v['invoice_date'])) . '</td>
							<td>' . $party_data['party_name'] . '</td>
							<td>' . $v['total_amount'] . '</td>';

					$total_amount_gst = 0;
					for ($i = 0; $i < sizeof($unique_tax); $i++) {
						if (in_array($unique_tax[$i], $unique_tax_temp)) {
							for ($j = 0; $j < sizeof($unique_tax_temp); $j++) {
								if ($unique_tax[$i] == $unique_tax_temp[$j]) {
									$cgst_total = 0;
									$tax_data = $this->model_tax->getTaxData($unique_tax_temp[$j]);
									$cgst_percent = $tax_data['sValue'];
									$cgst = $tax_array[$tax_data['sTax_Description']] * $cgst_percent / 100;
									$cgst = number_format($cgst, 2, '.', '');
									$total_amount_gst = $total_amount_gst + $tax_array[$tax_data['sTax_Description']];
									$cgst_total = $cgst_total + $cgst;
									$grand_total_gst[$tax_data['sTax_Description']] = $grand_total_gst[$tax_data['sTax_Description']] + $cgst_total;
									$grand_gst_amount[$tax_data['sTax_Description']] = $grand_gst_amount[$tax_data['sTax_Description']] + $tax_array[$tax_data['sTax_Description']];
									$html .= '<td>' . $tax_array[$tax_data['sTax_Description']] . '</td>
								<td>' . $cgst_total . '</td>
								';
								}
							}
						} else {
							$html .= '<td>0</td>
							<td>0</td>';
						}
					}
					$html .= ' </tr>';
				}

				$html .= '<tr style="border-style:inset hidden">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>' . $grand_total_amount . '</b></td>';

				for ($i = 0; $i < sizeof($unique_tax); $i++) {

					$tax_data = $this->model_tax->getTaxData($unique_tax[$i]);
					$html .= '<td>' . $grand_gst_amount[$tax_data['sTax_Description']] . '</td>
								<td>' . $grand_total_gst[$tax_data['sTax_Description']] . '</td>
								';
				}


				$html .= '	</tr>
							</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
			</body>
			</html>';

				echo $html;
			} else {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> No Invoices found between ' . date('d-m-Y', strtotime($date_from)) . ' and ' . date('d-m-Y', strtotime($date_to)) . ' </p>
					<form action="' . base_url("reports") . '">
					<button id = "closeBtn" class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
			 </div>
			</body>
				
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

				echo $html;
			}
		} else {
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="wrapper" style= "overflow: visible">
			 
			<div class="container h-100 d-flex justify-content-center">
			<div class="jumbotron my-auto">
			<p> Please provide necessary dates </p>
			<form action="' . base_url("reports") . '">
			<button id = "closeBtn" class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
		</form>
			</div>
	 </div>

				  <div class="col-md-12 table-responsive">
				
			       </div>
			</body>
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

			echo $html;;
		}
	}


	public function stockLedger()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$date_from = $this->input->post('date_from_stock');
		$date_to = $this->input->post('date_to_stock');
		$item_id = $this->input->post('product');

		$data = array(
			$date_from,
			$date_to,
		);



		if ($date_from && $date_to) {

			$item_data = $this->model_reports->geItemSell($item_id, $date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);
			$product_data = $this->model_products->getProductData($item_id);
			$total_qty_sold = 0;

			setlocale(LC_MONETARY, "en_US");

			if ($item_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Stock Ledger Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.css') . '?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
				<div class="row">
				<div class="col-xs-12 "  style="font-size: 15px;">
				  <h1 class="invoice-title-name">
					' . $company_info['company_name'] . '
				  </h1>
				  <h6 class="invoice-title-address">
					' . $company_info['address'] . '
				  </h6>
				  <div class="display-flex" >
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
				  Phone No:' . $company_info['phone'] . '
				  </h6>
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
					Email:' . $company_info['email'] . '
				  </h6>
				  </div>
				  <br>
					  <h4 class="invoice-title-address">' . $product_data['Item_Code'] . ' - ' . $product_data['Item_Name'] . '</h4>
					  <h5 class="invoice-title-address"> Sold from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</h5>
				</div>
			    
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			   

			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
				<hr>	
			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-bordered"  style="font-size: 10px;" >
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Invoice Number</th>
						<th>Invoice Date</th>
						<th>Party Name</th>

						<th>Qty</th>


						




			        
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($item_data as $k => $v) {

					$total_qty_sold = $total_qty_sold + 1;
					$party_data = $this->model_party->getPartyData($v['party_id']);



					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;


					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['invoice_no'] . '</td>
							<td>' . date('d-m-Y', strtotime($v['invoice_date'])) . '</td>
							<td>' . $party_data['party_name'] . '</td>
							<td>' . $v['qty'] . '</td>


			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</body>
			</html>';

				echo $html;
			} else {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
			<div class="jumbotron my-auto">
			<p><strong> ' . $product_data['Item_Name'] . '</strong> <br>was not sold between ' . date('d-m-Y', strtotime($date_from)) . ' and ' . date('d-m-Y', strtotime($date_to)) . ' </p>
			<form action="' . base_url("reports") . '">
			<button id = "closeBtn" class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
		</form>
			</div>
	 </div>
			</body>
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

				echo $html;
			}
		} else {
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> Please provide necessary dates </p>
					<form action="' . base_url("reports") . '">
					<button class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
			</body>
			</html>';

			echo $html;;
		}
	}


	public function quotationListing()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$date_from = $this->input->post('date_from_quotation');
		$date_to = $this->input->post('date_to_quotation');
		$party = $this->input->post('quotation_partyid');
		$total = 0;
		$total_gst = 0;





		if ($date_from && $date_to) {

			$quotation_data = $this->model_reports->getQuotationListing($date_from, $date_to, $party);
			$company_info = $this->model_company->getCompanyData(1);
			$party_data = $this->model_party->getPartyData($party);
			$grand_total_discount = 0;
			$grand_total_gst = 0;
			$grand_total_amount = 0;



			setlocale(LC_MONETARY, "en_US");

			if ($quotation_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Quotation Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.css') . '?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
				<div class="row">
				<div class="col-xs-12 "  style="font-size: 15px;">
				  <h1 class="invoice-title-name">
					' . $company_info['company_name'] . '
				  </h1>
				  <h6 class="invoice-title-address">
					' . $company_info['address'] . '
				  </h6>
				  <div class="display-flex" >
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
				  Phone No:' . $company_info['phone'] . '
				  </h6>
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
					Email:' . $company_info['email'] . '
				  </h6>
				  </div>
				  <h4 class="invoice-title-address">' . $party_data['party_name'] . '</h4>
				  <p class="invoice-title-address"><small>' . $party_data['address'] . '</small></p>
				  <h5 class="invoice-title-address">Quotation Listing Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</h5>
					
				 </div>
			    
			      <!-- /.col -->
			      
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			   

			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
				<hr>	
			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-bordered"  style="font-size: 10px;">
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Quotation No</th>
						<th>Quotation Date</th>
						<th>Total Products</th>
						<th>Discount</th>
						<th>GST</th>
						<th>Amount</th>




			        
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($quotation_data as $k => $v) {

					$grand_total_discount = $v['total_discount'] + $grand_total_discount;
					$grand_total_gst = $v['total_gst'] + $grand_total_gst;
					$grand_total_amount = $v['total_amount'] + $grand_total_amount;
					$total_products = $this->model_quotation->countQuotationItem($v['quotation_no']);



					// $amount = $v['qty'] * $v['rate'];



					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;



					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['quotation_no'] . '</td>
							<td>' . date('d-m-Y', strtotime($v['quotation_date'])) . '</td>
							<td>' . $total_products . '</td>
							<td>' . round($v['total_discount'], 2) . '</td>
							<td>' . round($v['total_gst'], 2) . '</td>
							<td>' . round($v['total_amount'], 2) . '</td>
					
			          	</tr>';
				}

				$html .= '<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>' . round($grand_total_discount, 2) . '</b></td>
							<td><b>' . round($grand_total_gst, 2) . '</b></td>
							<td><b>' . round($grand_total_amount, 2) . '</b></td>
					
			          	</tr>';




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!--<div class="row">
			    
			      <div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

			        <div class="table-responsive" >
			          <table class="table table-bordered"  style="font-size: 10px;">
			            <tr>
			              <th style="width:50%">Total Discount:</th>
			              <td>' . $grand_total_discount . '</td>
			            </tr>
						<tr>
			              <th>Total GST:</th>
			              <td>' . $grand_total_gst . '</td>
						</tr>
						<tr>
						<th>Total Amount:</th>
						<td>' . $grand_total_amount . '</td>
					  </tr>
					 
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
			</body>
			</html>';

				echo $html;
			} else {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >

			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> No Quotations found between ' . date('d-m-Y', strtotime($date_from)) . ' and ' . date('d-m-Y', strtotime($date_to)) . ' </p>
					<form action="' . base_url("reports") . '">
					<button id = "closeBtn" class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
			 </div>
			 
			 
			
			
			
			</body>
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

				echo $html;
			}
		} else {
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> Please provide necessary dates </p>
					<form action="' . base_url("reports") . '">
					<button class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
 			</div>
			</body>
			</html>';

			echo $html;;
		}
	}


	public function purchaseRegister()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$date_from = $this->input->post('date_from_purchase');
		$date_to = $this->input->post('date_to_purchase');
		$party = $this->input->post('purchase_partyid');





		if ($date_from && $date_to) {

			$purchase_data = $this->model_reports->getPurchaseListing($date_from, $date_to, $party);
			$company_info = $this->model_company->getCompanyData(1);
			$party_data = $this->model_party->getPartyData($party);
			$grand_total_discount = 0;
			$grand_total_gst = 0;
			$grand_total_amount = 0;



			setlocale(LC_MONETARY, "en_US");

			if ($purchase_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>From ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.css') . '?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
				<div class="row">
				
				<div class="col-xs-12 "  style="font-size: 15px;">
				  <h1 class="invoice-title-name">
					' . $company_info['company_name'] . '
				  </h1>
				  <h6 class="invoice-title-address">
					' . $company_info['address'] . '
				  </h6>
				  <div class="display-flex" >
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
				  Phone No:' . $company_info['phone'] . '
				  </h6>
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
					Email:' . $company_info['email'] . '
				  </h6>
				  </div>
				  <h4 class="invoice-title-address">' . $party_data['party_name'] . '</h4>
				  <p class="invoice-title-address"><small>' . $party_data['address'] . '</small></p>
				  <h5 class="invoice-title-address">Purchase Register Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</h5>
					
				 </div>
			    
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			   

			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
				<hr>	
			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-bordered"  style="font-size: 10px;">
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Purchase No</th>
						<th>Purchase Date</th>
						<th>Total Products</th>
						<th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($purchase_data as $k => $v) {

					$grand_total_discount = $v['total_discount'] + $grand_total_discount;
					$grand_total_gst = $v['total_gst'] + $grand_total_gst;
					$grand_total_amount = $v['total_amount'] + $grand_total_amount;
					$total_products = $this->model_purchase->countPurchaseItem($v['purchase_no']);
					$purchase_item_data = $this->model_purchase->getPurchaseItemData($v['purchase_no']);

					$total_purchase_amount = 0;
					foreach ($purchase_item_data as $f => $t) {
						$total_purchase_amount = $total_purchase_amount + $t['rate'];
					}

					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;



					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['purchase_no'] . '</td>
							<td>' . date('d-m-Y', strtotime($v['purchase_date'])) . '</td>
							<td>' . $total_products . '</td>
							<td>' . $total_purchase_amount . '</td>
					
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			  </section>
			  <!-- /.content -->
			</div>
			</body>
			</html>';

				echo $html;
			} else {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >

			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> No Purchase Orders found between ' . date('d-m-Y', strtotime($date_from)) . ' and ' . date('d-m-Y', strtotime($date_to)) . ' </p>
					<form action="' . base_url("reports") . '">
					<button id="closeBtn" class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
			 </div>
			 
			 
			
			
			
			</body>
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

				echo $html;
			}
		} else {
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> Please provide necessary dates </p>
					<form action="' . base_url("reports") . '">
					<button class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
 			</div>
			</body>
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

			echo $html;;
		}
	}


	public function customerLedger()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$date_From = $this->input->post('date_from_customer');
		$date_from = date("d-m-Y", strtotime($date_From));
		$date_To = $this->input->post('date_to_customer');
		$date_to = date("d-m-Y", strtotime($date_To));

		$party_id = $this->input->post('partyid');
		$grand_total = 0;

		$data = array(
			$date_from,
			$date_to,
		);



		if ($date_from && $date_to) {

			$item_data = $this->model_reports->getPartyInvoiceList($party_id, $date_From, $date_To);
			$company_info = $this->model_company->getCompanyData(1);
			// $product_data = $this->model_products->getProductData($item_id);
			$party_data = $this->model_party->getPartyData($party_id);


			setlocale(LC_MONETARY, "en_US");

			if ($item_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Customer Ledger Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.css') . '?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
				<div class="row">
				<div class="col-xs-12 "  style="font-size: 15px;">
				  <h1 class="invoice-title-name">
					' . $company_info['company_name'] . '
				  </h1>
				  <h6 class="invoice-title-address">
					' . $company_info['address'] . '
				  </h6>
				  <div class="display-flex" >
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
				  Phone No:' . $company_info['phone'] . '
				  </h6>
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
					Email:' . $company_info['email'] . '
				  </h6>
				  </div>
				  <h4 class="invoice-title-address">' . $party_data['party_name'] . '</h4>
				  <p class="invoice-title-address"><small>' . $party_data['address'] . '</small></p>
				  <h5 class="invoice-title-address">Invoices between. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</h5>
					
				 </div>
			    
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			   

			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
				<hr>	
			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-bordered"  style="font-size: 10px;">
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Invoice Number</th>
						<th>Invoice Date</th>
						<th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($item_data as $k => $v) {

					// $total_qty_sold = $total_qty_sold + 1;

					$grand_total = $grand_total + $v['total_amount'];

					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;


					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['invoice_no'] . '</td>
							<td>' . date('d-m-Y', strtotime($v['invoice_date'])) . '</td>
							<td>' . $v['total_amount'] . '</td>
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			<div class="row" >
			    
				<div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

				  <div class="table-responsive" >
					<table class="table table-bordered"  style="font-size: 12px;">
					  <tr>
						<th style="width:50%">Grand Total</th>
						<td><b>' . $grand_total . '</b></td>
					  </tr>
				   
					</table>
				  </div>
				</div>
				<!-- /.col -->
			  </div>
			   
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</body>
			</html>';

				echo $html;
			} else {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
			<div class="jumbotron my-auto">
			<p><strong> ' . $party_data['party_name'] . '</strong> <br>have no invoices between ' . date('d-m-Y', strtotime($date_from)) . ' and ' . date('d-m-Y', strtotime($date_to)) . ' </p>
			<form action="' . base_url("reports") . '">
			<button id = "closeBtn" class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
		</form>
			</div>
	 </div>
			</body>
			</html>
			<script>
			document.getElementById("closeBtn").addEventListener("click", function() {
				window.close()
			});
			</script>';

				echo $html;
			}
		} else {
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. ' . date('d-m-Y', strtotime($date_from)) . ' to ' . date('d-m-Y', strtotime($date_to)) . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body >
			
			<div class="container h-100 d-flex justify-content-center">
    				<div class="jumbotron my-auto">
					<p> Please provide necessary dates </p>
					<form action="' . base_url("reports") . '">
					<button class = "btn btn-primary" type="submit" value="Go Back">Go Back</button>
				</form>
    				</div>
			</body>
			</html>';

			echo $html;;
		}
	}


	public function priceList()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$variation = $this->input->post('variation');
		$itemMake = $this->input->post('itemMake');

		$price = 0;

		// $data = array(
		// 	$date_from,
		// 	$date_to,
		// );



		if ($itemMake) {

			$item_data = $this->model_reports->getItemListFromMake($itemMake);
			$company_info = $this->model_company->getCompanyData(1);
			// $product_data = $this->model_products->getProductData($item_id);
			// $party=$this->model_party->getPartyData($party_id);


			setlocale(LC_MONETARY, "en_US");

			if ($item_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Price List </title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.css') . '?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			  <!-- title row -->
			  <div class="row">
				<div class="col-xs-12 "  style="font-size: 15px;">
				  <h1 class="invoice-title-name">
					' . $company_info['company_name'] . '
				  </h1>
				  <h6 class="invoice-title-address">
					' . $company_info['address'] . '
				  </h6>
				  <div class="display-flex" >
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
				  Phone No:' . $company_info['phone'] . '
				  </h6>
				  <h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
					Email:' . $company_info['email'] . '
				  </h6>
				  </div>
				  <br>
				  <h4 class="invoice-title-address">Price List</h4>
				</div>
				<!-- /.col -->
			  </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			   

			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
				<hr>	
			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-bordered"  style="font-size: 10px;" >
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Item Code</th>
						<th>Name of the Item</th>
						<th>Unit</th>
						<th>Price</th>
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($item_data as $k => $v) {

					// $total_qty_sold = $total_qty_sold + 1;

					$price = $v['Price'] + ($v['Price'] * $variation) / 100;
					$price = round($price);

					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;


					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['Item_Code'] . '</td>
							<td>' . $v['Item_Name'] . '</td>
							<td>' . $v['sUnit'] . '</td>
							<td>' . $price . '</td>
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			  </section>
			  <!-- /.content -->
			</body>
			</html>';

				echo $html;
			} else {
				$html = '';

				echo $html;
			}
		} else {
			$html = '';

			echo $html;;
		}
	}


	public function test($var = null)
	{
		echo "ttr";
	}

	function close_window()
	{
		echo  "<script type='text/javascript'>";
		echo "window.close();";
		echo "</script>";
	}
}
