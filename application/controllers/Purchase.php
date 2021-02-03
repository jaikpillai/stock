<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Purchase Orders';

		$this->load->model('model_purchase');
		// $this->load->model('model_orders');
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

		$this->data['page_title'] = 'Manage Purchase';
		$this->render_template('purchase/index', $this->data);	
			
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchPurchaseData()
	{
		$result = array('data' => array());

		$data = $this->model_purchase->getPurchaseData();
		
		foreach ($data as $key => $value) {

			$count_total_item = $this->model_purchase->countPurchaseItem($value['purchase_no']);
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
				$buttons .= '<a target="__blank" href="'.base_url('purchase/printDiv/'.$value['s_no']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('purchase/update/'.$value['s_no'].'/'.$value['purchase_no'].'').'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['s_no'].', '.$value['purchase_no'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			// if($value['is_payment_received'] == 1) {
			// 	$paid_status = '<span class="label label-success">Paid</span>';	
			// }
			// else {
			// 	$paid_status = '<span class="label label-warning">Not Paid</span>';
			// }

		

			$result['data'][$key] = array(
				$value['purchase_no'],
				$party_data['party_name'],
				$value['purchase_date'],
				$count_total_item,
				// $value['total_amount'],
				// $value['mode_of_payment'],
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

		$this->data['page_title'] = 'Add Purchase Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$purchase_id = $this->model_purchase->create();
        	
        	if($purchase_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('purchase/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('purchase/create/', 'refresh');
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
            $this->data['getlastpurchaseid'] = $this->model_purchase->getLastPurchaseID();

			$this->render_template('purchase/create', $this->data);
			
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

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id, $purchase_no)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id || !$purchase_no) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Purchase Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->model_purchase->update($id,  $purchase_no);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('purchase/update/'.$id.'/'.$purchase_no, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('purchase/update/'.$id.'/'.$purchase_no, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	// $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	// $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$purchase_data = $this->model_purchase->getPurchaseData($id);

    		$result['purchase_master'] = $purchase_data;
    		$purchase_item = $this->model_purchase->getPurchaseItemData($purchase_data['purchase_no']);
			$this->data['party_data'] = $this->model_party->getActiveParty();
    		foreach($purchase_item as $k => $v) {
    			$result['purchase_item'][] = $v;
    		}

			$this->data['purchase_data'] = $result;
			$this->data['tax_data'] = $this->model_tax->getActiveTax(); 
			

        	$this->data['products'] = $this->model_products->getActiveProductData();      	

            $this->render_template('purchase/edit', $this->data);
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
		$purchase_no = $this->input->post('purchase_no');



        $response = array();
        if($s_no && $purchase_no) {
            $delete = $this->model_purchase->remove($s_no, $purchase_no);
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
			$order_data = $this->model_purchase->getPurchaseData($id);
			$orders_items = $this->model_purchase->getPurchaseItemData($id);
			// $footer_items = $this->model_orders->getFooter($id);
			$company_info = $this->model_company->getCompanyData(1);
			$party_data = $this->model_party->getPartyData($order_data['party_id']);

			$order_date = strtotime($order_data['purchase_date']);
			$order_date = date( 'd/m/Y', $order_date );
			// $paid_status = ($order_data['is_payment_received'] == 1) ? "Paid" : "Unpaid";
			$freight_other_charge = $order_data['other_charges'];

			$purchase_date=date('d-m-Y', strtotime($order_data['purchase_date']));
			$ref_date=date('d-m-Y', strtotime($order_data['ref_date']));
			
			if(strtotime($order_data['purchase_date'])<0){
				$purchase_date='';
			}

			if(strtotime($order_data['ref_date'])<0){
				$ref_date='';
			}


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
				  <h4 class="invoice-title-address">Purchase Order</h4>
			      </div>
			      <!-- /.col -->
			    </div>
				<!-- info row -->
				<div class="invoice-border">
			    <div class="row invoice-info" style="margin-right: -8px;">
			      
				  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
				  <div class="padding-10">
					<b>To:<br> M/s. </b> '.$party_data ['party_name'].'<br>'.$party_data ['address'].'<br><br>
					<b>GST No.:</b> '.$party_data ['gst_number'].'<br>
					</div>

			      </div>
				  <!-- /.col -->
				  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
				  <div class="padding-5">
				  <b>Purchase No.:</b> '.$order_data['purchase_no'].'
					</div>
					<div class="invoice-boxes padding-5" >
					<b>Date.:</b> '.$purchase_date.' 
					</div>
					<div class="invoice-boxes padding-5">
					<b>Ref. No.:</b> '.$order_data['ref_no'].'

				  </div>
				  <div class="invoice-boxes padding-5">
					<b>Ref. Date.:</b> '.$ref_date.'

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
			          </tr>
			          </thead>
			          <tbody>'; 
					  $total = 0;
					  
			          foreach ($orders_items as $k => $v) {
						
						  $product_data = $this->model_products->getProductData($v['item_id']); 
						  $amount = $v['qty']*$v['rate'];
						  $total = $total + $amount; 
						  $index = $k + 1;

				

						  $discount_amount = $amount - ($amount * $v['discount'])/100;
						  
						  
			          	
						  $html .= '<tr>
							<td>'.$index.'</td>
							<td>'.$product_data['Item_Code'].'</td>
							<td>'.$product_data['Item_Name'].'</td>
							<td>'.$product_data['Item_Make'].'</td>
							<td>'.$v['qty'].'</td>
							<td>'.$v['unit'].'</td>
							<td>'.$v['rate'].'</td>
			          	</tr>';
					  }

					$tax_value = $order_data['tax_value'];
					$gross_total = $total - $order_data['total_discount'];
					$total_after_tax = $gross_total + ($gross_total * $tax_value)/100;
					$final_total = $total_after_tax + $freight_other_charge;

					
					$rounded_total_amount = round($final_total);
					$round_off =  ($rounded_total_amount - $final_total);
					$round_off = round($round_off, 2);

			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			   
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