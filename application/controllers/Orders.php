<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_tax');
		$this->load->model('model_company');
		$this->load->model('model_party');
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Manage Orders';
		$this->render_template('orders/index', $this->data);	
			
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		$result = array('data' => array());

		$data = $this->model_orders->getOrdersData();
		
		foreach ($data as $key => $value) {

			$count_total_item = $this->model_orders->countOrderItem($value['invoice_no']);
			// echo $count_total_item;
			// $date = date('Y-m-d', $value['invoice_date']);
			// $time = date('h:i a', $value['date_time']);
			$party_data = $this->model_party->getPartyData($value['party_id']);

			// $date_time = $date;

			if($party_data['address'] == NULL){
				$party_data['address'] ="";
			}
			if($party_data['party_name'] == NULL){
				$party_data['party_name'] ="";
			}
			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('orders/printDiv/'.$value['s_no']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('orders/update/'.$value['s_no'].'/'.$value['invoice_no']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['s_no'].','.$value['invoice_no'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			if($value['is_payment_received'] == 1) {
				$paid_status = '<span class="label label-success">Paid</span>';	
			}
			else {
				$paid_status = '<span class="label label-warning">Not Paid</span>';
			}

		

			$result['data'][$key] = array(
				$value['invoice_no'],
				$party_data['party_name'],
				$party_data['address'],
				$count_total_item,
				$value['total_amount'],
				$paid_status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Add Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
			$order_id = $this->model_orders->create();
			$invoice_no = $this->input->post('invoice_no');
        	
        	if($order_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('orders/update/'.$order_id.'/'.$invoice_no , 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/create/', 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	// $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	// $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;


		
			$this->data['products'] = $this->model_products->getActiveProductData(); 
			$this->data['tax_data'] = $this->model_tax->getActiveTax(); 
			
			$this->data['party_data'] =$this->model_party->getActiveParty(); 
            $this->data['getlastinvoiceid'] = $this->model_orders->getLastInvoiceID();

			$this->render_template('orders/create', $this->data);
			
        }	
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It gets the all the active product inforamtion from the product table 
	* This function is used in the order page, for the product selection in the table
	* The response is return on the json format.
	*/
	public function getTableProductRow()
	{
		$products = $this->model_products->getActiveProductData();

		echo json_encode($products);
	}


	public function getTableTaxData()
	{
		$tax = $this->model_tax->getTaxData();

		echo json_encode($tax);
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id, $invoice_no)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->model_orders->update($id, $invoice_no);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders/update/'.$id.'/'.$invoice_no, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/update/'.$id.'/'.$invoice_no, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	// $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	// $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$orders_data = $this->model_orders->getOrdersData($id);

    		$result['invoice_master'] = $orders_data;
    		$orders_item = $this->model_orders->getOrdersItemData($orders_data['invoice_no']);
			$this->data['party_data'] = $this->model_party->getActiveParty();
    		foreach($orders_item as $k => $v) {
    			$result['invoice_item'][] = $v;
    		}

			$this->data['order_data'] = $result;
			$this->data['tax_data'] = $this->model_tax->getActiveTax(); 
			

        	$this->data['products'] = $this->model_products->getActiveProductData();      	

            $this->render_template('orders/edit', $this->data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$s_no = $this->input->post('s_no');
		$invoice_no = $this->input->post('invoice_no');

        $response = array();
        if($invoice_no && $s_no) {
            $delete = $this->model_orders->remove($s_no, $invoice_no);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response); 
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($id)
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		if($id) {
			$order_data = $this->model_orders->getOrdersData($id);
			$orders_items = $this->model_orders->getOrdersItemData($id);
			$footer_items = $this->model_orders->getFooter($id);
			$company_info = $this->model_company->getCompanyData(1);
			$party_data = $this->model_party->getPartyData($order_data['party_id']);
			$bank_details=$this->model_company->getBankDetails();

			$order_date = strtotime($order_data['invoice_date']);
			$order_date = date( 'd/m/Y', $order_date );
			$paid_status = ($order_data['is_payment_received'] == 1) ? "Paid" : "Unpaid";
			$freight_other_charge = $order_data['other_charges'];



			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title></title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.css').'?v=<?=time();?">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper" style= "overflow: visible">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
				  <div class="col-xs-12 ">
				  <h5 class="invoice-title-address">GST INVOICE</h5>
			        <h1 class="invoice-title-name">
			          '.$company_info['company_name'].'
					</h1>
					<h6 class="invoice-title-address">
			          '.$company_info['address'].'
					</h6>
					<div class="display-flex">
					<h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
					Phone No:'.$company_info['phone'].'
					</h6>
					<h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
			          Email:'.$company_info['email'].'
					</h6>
					</div>
			      </div>
			      <!-- /.col -->
			    </div>
				<!-- info row -->
				<div class="invoice-border">
			    <div class="row invoice-info" style="margin-right: -8px;">
			      
				  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
				  <div class="padding-10">
					<b>Sold To:<br> M/s. </b> '.$party_data ['party_name'].'<br>'.$party_data ['address'].'<br><br>
					<b>GST No.:</b> '.$party_data ['gst_number'].'<br>
					</div>
					<div class="invoice-boxes padding-10">
					<div class="col-sm-6 invoice-col padding-0">
					<b>Order/Challan No.:</b> '.$order_data['order_no'].' </div>
					<div class="col-sm-6 invoice-col padding-0">
					<b>Date.:</b> '.$order_data['order_date'].' </div><br>

			      </div>

			      </div>
				  <!-- /.col -->
				  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
				  <div class="padding-5" style="text-align: center;background: lightgray;">
					<b>'.ucfirst($order_data['mode_of_payment']).' Memo</b><br>
					</div>
					<div class="invoice-boxes padding-5">
					<b>Invoice No.:</b> '.$order_data['invoice_no'].'

				  </div>
				  <div class="invoice-boxes padding-5">
					<b>Date.:</b> '.date('d-m-Y', strtotime($order_data['invoice_date'])).'

				  </div>
				  <div class="invoice-boxes padding-5">
					<b>Dispatch Through.:</b> '.$order_data['dispatched_through'].'
				  </div>
				  
				  <div class="invoice-boxes padding-5">
					<div class="col-sm-6 invoice-col padding-0">
					<b>GR No.</b> '.$order_data['gr_rr_no'].' </div>
					<div class="col-sm-6 invoice-col padding-0">
					<b>Freight Paid.:</b></div>

			      </div>

			      </div>
				  <!-- /.col -->
			    </div>
			    <!-- /.row -->	
			    <!-- Table row -->
			    <div class="row" style="margin-right: -15px;">
			      <div class="col-xs-12 table-responsive table-invoice">
			        <table class="table table-bordered-invoice" >
			          <thead>
					  <tr>
						<th>S.N.</th>
						<th>Code</th>
			            <th>Description</th>
						<th>Make</th>
			            <th>Qty</th>
						<th>Unit</th>
						<th>Rate</th>
						<th>Disc. %</th>
						<th>GST</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 
					  $total = 0;
					  $tax_per_item=0;
					//   $tax_array;
					  $unique_tax=array();
					  
			          foreach ($orders_items as $k => $v) {
						
						  $product_data = $this->model_products->getProductData($v['item_id']); 
						  $amount = $v['qty']*$v['rate'];
						  $total = $total + $amount; 
						  $index = $k + 1;

						  $discount_amount = $amount - ($amount * $v['discount'])/100;
						  $tax_data=$this->model_tax->getTaxData($v['tax_id']); 
						  
						  if(!in_array($v['tax_id'],$unique_tax)){
							array_push($unique_tax,$v['tax_id']);
							$tax_array[$tax_data['sTax_Description']]=0;
						  }

						  $tax_array[$tax_data['sTax_Description']]=$tax_array[$tax_data['sTax_Description']]+$discount_amount;
			          	
						  $html .= '<tr>
							<td>'.$index.'</td>
							<td>'.$product_data['Item_Code'].'</td>
							<td>'.$product_data['Item_Name'].'</td>
							<td>'.$product_data['Item_Make'].'</td>
							<td>'.$v['qty'].'</td>
							<td>'.$v['unit'].'</td>
							<td>'.$v['rate'].'</td>
							<td>'.$v['discount'].'</td>
							<td>'.$tax_data['sValue'].'</td>
				            <td>'.$discount_amount.'</td>
			          	</tr>';
					  }

					$tax_value = $order_data['tax_value'];
					$gross_total = $total - $order_data['total_discount'];
					$total_after_tax = $gross_total + ($gross_total * $tax_value)/100;
					$final_total = $total_after_tax + $freight_other_charge;

			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
				<div class="col-xs-8" style="page-break-inside: avoid">

				<div class="table-responsive" >
				  <table class="table table-bordered" >
				  <thead>
				  <tr>
						<th>Amount</th>
						<th>CGST%</th>
			            <th>CGST</th>
						<th>SGST%</th>
			            <th>SGST</th>
					</tr>
					</thead>
					<tbody>';

					$total_amount_gst=0;
					$cgst_total=0;
			
					
					for($i = 0; $i < sizeof($unique_tax); $i++) {

						$tax_data=$this->model_tax->getTaxData($unique_tax[$i]); 
						$cgst_percent=$tax_data['sValue']/2;
						$cgst=$tax_array[$tax_data['sTax_Description']]*$cgst_percent/100;
						$cgst=number_format($cgst, 2, '.', '');
						$total_amount_gst=$total_amount_gst+$tax_array[$tax_data['sTax_Description']];
						$cgst_total=$cgst_total+$cgst;
		
						$html .= '<tr>
						  <td>'.$tax_array[$tax_data['sTax_Description']].'</td>
						  <td>'.$cgst_percent.'</td>
						  <td>'.$cgst.'</td>
						  <td>'.$cgst_percent.'</td>
						  <td>'.$cgst.'</td>
						</tr>';
					}

					$total_with_gst=$final_total+$cgst_total+$cgst_total;

					$rounded_total_amount = round($total_with_gst);
					$round_off =  ($rounded_total_amount - $total_with_gst);
					$round_off = round($round_off, 2);

					$html .= '<tr>
						  <td><b>'.$total_amount_gst.'</b></td>
						  <td></td>
						  <td><b>'.$cgst_total.'</b></td>
						  <td></td>
						  <td><b>'.$cgst_total.'</b></td>
						</tr>';
		
					  $html .='
					  
					  </tbody>
				  </table>
				</div>
			  </div>
			 <div class="col-xs-4" style="page-break-inside: avoid">

			        <div class="table-responsive" >
			          <table class="table table-bordered" >
			            <tr>
			              <th style="width:50%">Total:</th>
			              <td>'.$gross_total.'</td>
			            </tr>';

			            // if($order_data['service_charge'] > 0) {
			            // 	$html .= '<tr>
				        //       <th>Service Charge ('.$order_data['service_charge_rate'].'%)</th>
				        //       <td>'.$order_data['service_charge'].'</td>
				        //     </tr>';
			            // }

			            // if($order_data['vat_charge'] > 0) {
			            // 	$html .= '<tr>
				        //       <th>Vat Charge ('.$order_data['vat_charge_rate'].'%)</th>
				        //       <td>'.$order_data['vat_charge'].'</td>
				        //     </tr>';
			            // }
			            
			            
						// $html .='
						// // <tr>
			            // //   <th>GST ('. $order_data['tax_value'].'%)</th>
			            // //   <td>'.$order_data['total_gst'].'</td>
						// // </tr>
						$html .='
						<tr>
						<th>Freight/Others</th>
						<td>'.$order_data['other_charges'].'</td>
					  </tr>
					  <tr>
						<th>Round off</th>
						<td>'.$round_off.'</td>
					  </tr>
					  <tr>
					  <th>Total Amount:</th>
					  <td>'.$rounded_total_amount.'</td>
					</tr>
			            <tr>
			              <th>Paid Status:</th>
			              <td>'.$paid_status.'</td>
			            </tr>
			          </table>
			        </div>
				  </div>
			      <!-- /.col -->
			    </div>
				<!-- /.row -->
				<footer>
				<div style=" border-top: 2px solid;padding: 10px;">
				<div class="row">
				
				<div>
				<div>
				<b>GST R.No. :'.$company_info['gst_no'].'</b><br></div>
				<div class="row">
				<div class="col-xs-2">
				<b>Our Bank Detail :</b></div>';
				foreach ($bank_details as $k => $v) {
					
					$html .= '<div class="col-xs-4">  <b>'.$v['bank_name'].',</b> '.$v['bank_address'].'<br>
					<b>A/c No.: '.$v['acc_no'].'</b> <br>					
					<b>IFSC Code: '.$v['ifsc'].'</b></div>';
				}
				$html.='
				</div>
				<div style="page-break-inside: avoid">
				  <b>Terms & Conditions</b><br>

				';
				foreach ($footer_items as $k => $v) {
					
					$index = $k + 1;
					$html .= '  <b>'.$index.'.</b> '.$v['description'].'<br>';
				}
					
					$html.='</div>
					<br><br><br><br><br>
					<b>Receiver\'s Signature</b><br>
			      </div>
			      <!-- /.col -->
			      
			      <div class="col invoice-footer" style="page-break-inside: avoid">
				  <b>For '.$company_info['company_name'].'</b>
				  <br><br><br>
				  <b>Authorised Signatory</b><br>

			      </div>
				  <!-- /.col -->
				  
			    </div>
			    <!-- /.row -->	
				</div>
				</footer>
				<!-- /.border -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

			  echo $html;
		}
	}

}