<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Stores';
		$this->load->model('model_reports');
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
		if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}
		

		$parking_data = $this->model_reports->getOrderData($today_year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();

	
		

		$final_parking_data = array();
		foreach ($parking_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['gross_amount'];						
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_parking_data[$k] = 0;	
			}
			
		}
		

		$this->data['selected_year'] = $today_year;
	
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;
		$this->data['products'] = $this->model_products->getProductData();

		$this->render_template('reports/index', $this->data);
	}

 public function invoice($var = null)
 {

	

		// $this->base_url('reports/printDiv/'.$date_start.'/'.$date_from.'');



 
	}

	public function printDiv()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
		}
		
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');


	

	
        
		if($date_from && $date_to ) {

			$invoice_data = $this->model_reports->getInvoiceListing($date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);

			setlocale(LC_MONETARY,"en_US");
		
			if($invoice_data){
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from '. $date_from.' to '.$date_to.'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          '.$company_info['company_name'].'

					</h2>
			  <h5>Invoice Report from. '. $date_from.' to '.$date_to.'</h5>
			
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
						<th>Total Discount</th>
						<th>Total GST</th>
						<th>Total Amount</th>
						<th>Payment Received</th>




			        
			          </tr>
			          </thead>
			          <tbody>'; 
					  
			          foreach ($invoice_data as $k => $v) {

					
						
						//   $product_data = $this->model_products->getProductData($v['item_id']); 
						//   $amount = $v['qty']*$v['rate'];
						//   $total = $total + $amount; 
						  $index = $k + 1;

						//   $freight_other_charge = $order_data['other_charges'];

						//   $discount_amount = $amount - ($amount * $v['discount'])/100;
						  
						  if($v['is_payment_received']==1){
							  $payment = 'Yes';
						  }
						  else{
							$payment = 'No';
						  }
			          	
						  $html .= '<tr>
							<td>'.$index.'</td>
							<td>'.$v['invoice_no'].'</td>
							<td>'.$v['invoice_date'].'</td>
							<td>'.$v['total_discount'].'</td>
							<td>'.$v['total_gst'].'</td>
							<td>'.$v['total_amount'].'</td>
							<td>'. $payment.'</td>


							


							
						
			          	</tr>';
					  }



			          
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			   
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
			</body>
			</html>';

			  echo $html;
					}
					else{
						$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from '. $date_from.' to '.$date_to.'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body >
			
			<div class="wrapper" style= "overflow: visible">
			 
				  <div class="col-md-12 table-responsive">
				  <p> No Invoice found between '. $date_from.' and '. $date_to .' </p>
			       </div>
			</body>
			</html>';

			  echo $html;
					}
		
		}
		else{
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from '. $date_from.' to '.$date_to.'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body >
			
			<div class="wrapper" style= "overflow: visible">
			 
				  <div class="col-md-12 table-responsive">
				  <p> Please provide necessary dates </p>
			       </div>
			</body>
			</html>';

			  echo $html;;
		}
	}


	public function stockLedger()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
		}
		
		$date_from = $this->input->post('date_from_stock');
		$date_to = $this->input->post('date_to_stock');
		$item_id = $this->input->post('product');

		$data = array(
			$date_from,
			$date_to,
		);

	
        
		if($date_from && $date_to ) {

			$item_data = $this->model_reports->geItemSell($item_id, $date_from, $date_to);
			$company_info = $this->model_company->getCompanyData(1);

			setlocale(LC_MONETARY,"en_US");
		
			if($item_data){
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. '. $date_from.' to '.$date_to.'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          '.$company_info['company_name'].'

					</h2>
					  <h4>Item ID. '. $item_id.'</h4>
					  <h5> Sold from '. $date_from.' to '. $date_to .'</h5>
			
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
						<th>Invoice Date</th>
						




			        
			          </tr>
			          </thead>
			          <tbody>'; 
					  
			          foreach ($item_data as $k => $v) {

					
						
						//   $product_data = $this->model_products->getProductData($v['item_id']); 
						//   $amount = $v['qty']*$v['rate'];
						//   $total = $total + $amount; 
						  $index = $k + 1;

						//   $freight_other_charge = $order_data['other_charges'];

						//   $discount_amount = $amount - ($amount * $v['discount'])/100;
						  
						
						  $html .= '<tr>
							<td>'.$index.'</td>
							<td>'.$v['invoice_date'].'</td>
					
						
			          	</tr>';
					  }



			          
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			   
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
			</body>
			</html>';

			  echo $html;
					}
					else{
						$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. '. $date_from.' to '.$date_to.'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body >
			
			<div class="wrapper" style= "overflow: visible">
			 
				  <div class="col-md-12 table-responsive">
				  <p> No data found between '. $date_from.' and '. $date_to .' </p>
			       </div>
			</body>
			</html>';

			  echo $html;
					}
		
		}
		else{
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice Report from. '. $date_from.' to '.$date_to.'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body >
			
			<div class="wrapper" style= "overflow: visible">
			 
				  <div class="col-md-12 table-responsive">
				  <p> Please provide necessary dates </p>
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
}	