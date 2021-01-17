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
			$grand_total_gst = 0;
			$grand_total_amount = 0;


			setlocale(LC_MONETARY, "en_US");

			if ($invoice_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          ' . $company_info['company_name'] . '

					</h2>
			  <h5>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</h5>
			
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
			        <table class="table table-bordered" >
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Invoice No</th>
						<th>Invoice Date</th>
						<th>Discount</th>
						<th>GST</th>
						<th>Amount</th>
						<th>Payment Received</th>




			        
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($invoice_data as $k => $v) {

					$grand_total_discount = $v['total_discount'] + $grand_total_discount;
					$grand_total_gst = $v['total_gst'] + $grand_total_gst;
					$grand_total_amount = $v['total_amount'] + $grand_total_amount;



					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;

					if ($v['is_payment_received'] == 1) {
						$payment = 'Yes';
					} else {
						$payment = 'No';
					}

					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['invoice_no'] . '</td>
							<td>' . $v['invoice_date'] . '</td>
							<td>' . $v['total_discount'] . '</td>
							<td>' . $v['total_gst'] . '</td>
							<td>' . $v['total_amount'] . '</td>
							<td>' . $payment . '</td>
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!--<div class="row">
			    
			      <div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

			        <div class="table-responsive" >
			          <table class="table table-bordered" >
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
			    </div>-->
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
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
					<p> No Invoices found between ' . $date_from . ' and ' . $date_to . ' </p>
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
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
			  <title>Stock Ledger Report from. ' . $date_from . ' to ' . $date_to . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          ' . $company_info['company_name'] . '

					</h2>
					  <h4>Item ID. ' . $item_id . '</h4>
					  <h4>Item Name. ' . $product_data['Item_Name'] . '</h4>
					  <h5> Sold from ' . $date_from . ' to ' . $date_to . '</h5>
			
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
			        <table class="table table-bordered" >
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Invoice Number</th>
						<th>Invoice Date</th>

						




			        
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($item_data as $k => $v) {

					$total_qty_sold = $total_qty_sold + 1;



					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;


					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['invoice_no'] . '</td>
							<td>' . $v['invoice_date'] . '</td>
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			<!--<div class="row">
			    
				<div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

				  <div class="table-responsive" >
					<table class="table table-bordered" >
					  <tr>
						<th style="width:50%">Total Qty Sold:</th>
						<td>' . $total_qty_sold . '</td>
					  </tr>
					  <tr>
						<th style="width:50%">Remaining Qty</th>
						<td>' . $product_data['Max_Suggested_Qty'] . '</td>
					  </tr>
				   
					</table>
				  </div>
				</div>
				<!-- /.col -->
			  </div>
			   
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>-->
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
			  <title>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</title>
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
			<p><strong> ' . $product_data['Item_Name'] . '</strong> <br>was not sold between ' . $date_from . ' and ' . $date_to . ' </p>
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
			  <title>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</title>
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






		if ($date_from && $date_to) {

			$quotation_data = $this->model_reports->getQuotationListing($date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          ' . $company_info['company_name'] . '

					</h2>
			  <h5>Quotation Listing Report from. ' . $date_from . ' to ' . $date_to . '</h5>
			
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
			        <table class="table table-bordered" >
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



					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;



					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['quotation_no'] . '</td>
							<td>' . $v['quotation_date'] . '</td>
							<td>' . $total_products . '</td>
							<td>' . $v['total_discount'] . '</td>
							<td>' . $v['total_gst'] . '</td>
							<td>' . $v['total_amount'] . '</td>
					
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!--<div class="row">
			    
			      <div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

			        <div class="table-responsive" >
			          <table class="table table-bordered" >
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
			    </div>-->
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
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
					<p> No Quotations found between ' . $date_from . ' and ' . $date_to . ' </p>
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
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






		if ($date_from && $date_to) {

			$purchase_data = $this->model_reports->getPurchaseListing($date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          ' . $company_info['company_name'] . '

					</h2>
			  <h5>Purchase Register Report from. ' . $date_from . ' to ' . $date_to . '</h5>
			
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
			        <table class="table table-bordered" >
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Purchase No</th>
						<th>Purchase Date</th>
						<th>Total Products</th>
						<th>Discount</th>
						<th>GST</th>
						<th>Amount</th>




			        
			          </tr>
			          </thead>
			          <tbody>';

				foreach ($purchase_data as $k => $v) {

					$grand_total_discount = $v['total_discount'] + $grand_total_discount;
					$grand_total_gst = $v['total_gst'] + $grand_total_gst;
					$grand_total_amount = $v['total_amount'] + $grand_total_amount;
					$total_products = $this->model_purchase->countPurchaseItem($v['purchase_no']);



					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;



					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['purchase_no'] . '</td>
							<td>' . $v['purchase_date'] . '</td>
							<td>' . $total_products . '</td>
							<td>' . $v['total_discount'] . '</td>
							<td>' . $v['total_gst'] . '</td>
							<td>' . $v['total_amount'] . '</td>
					
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			   <!-- <div class="row">
			    
			      <div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

			        <div class="table-responsive" >
			          <table class="table table-bordered" >
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
			    </div> -->
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
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
					<p> No Purchase Orders found between ' . $date_from . ' and ' . $date_to . ' </p>
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
			  <title>Invoice Report from ' . $date_from . ' to ' . $date_to . '</title>
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

		$date_from = $this->input->post('date_from_customer');
		$date_from = date("d-m-Y", strtotime($date_from));
		$date_to = $this->input->post('date_to_customer');
		$date_to = date("d-m-Y", strtotime($date_to));
	
		$party_id = $this->input->post('partyid');
		$grand_total=0;

		$data = array(
			$date_from,
			$date_to,
		);



		if ($date_from && $date_to) {

			$item_data = $this->model_reports->getPartyInvoiceList($party_id, $date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);
			// $product_data = $this->model_products->getProductData($item_id);
			$party=$this->model_party->getPartyData($party_id);


			setlocale(LC_MONETARY, "en_US");

			if ($item_data) {
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Customer Ledger Report from. ' . $date_from . ' to ' . $date_to . '</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          ' . $company_info['company_name'] . '

					</h2>
					  <h4>Party Name. ' . $party['party_name'] . '</h4>
					 <h5> Invoices between ' . $date_from . ' to ' . $date_to . '</h5>
			
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
			        <table class="table table-bordered" >
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

					$grand_total=$grand_total+$v['total_amount'];

					//   $product_data = $this->model_products->getProductData($v['item_id']); 
					//   $amount = $v['qty']*$v['rate'];
					//   $total = $total + $amount; 
					$index = $k + 1;

					//   $freight_other_charge = $order_data['other_charges'];

					//   $discount_amount = $amount - ($amount * $v['discount'])/100;


					$html .= '<tr>
							<td>' . $index . '</td>
							<td>' . $v['invoice_no'] . '</td>
							<td>' . $v['invoice_date'] . '</td>
							<td>' . $v['total_amount'] . '</td>
			          	</tr>';
				}




				$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			<div class="row">
			    
				<div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

				  <div class="table-responsive" >
					<table class="table table-bordered" >
					  <tr>
						<th style="width:50%">Grand Total</th>
						<td>' . $grand_total . '</td>
					  </tr>
				   
					</table>
				  </div>
				</div>
				<!-- /.col -->
			  </div>
			   
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>-->
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
			  <title>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</title>
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
			<p><strong> ' . $party['party_name'] . '</strong> <br>have no invoices between ' . $date_from . ' and ' . $date_to . ' </p>
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
			  <title>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</title>
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
	
		$price=0;

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
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.css').'?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			  <!-- title row -->
			  <div class="row">
				<div class="col-xs-12 ">
				  <h1 class="invoice-title-name">
					'.$company_info['company_name'].'
				  </h1>
				  <h6 class="invoice-title-address">
					'.$company_info['address'].'
				  </h6>
				  <div class="display-flex">
				  <h6 class="invoice-title-address">
				  Phone No:'.$company_info['phone'].'
				  </h6>
				  <h6 class="invoice-title-address">
					Email:'.$company_info['address'].'
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
			        <table class="table table-bordered" >
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

					$price=$v['Price']+($v['Price']*$variation)/100;

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
				$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</title>
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
			<p><strong> ' . $party['party_name'] . '</strong> <br>have no invoices between ' . $date_from . ' and ' . $date_to . ' </p>
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
			  <title>Invoice Report from. ' . $date_from . ' to ' . $date_to . '</title>
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
