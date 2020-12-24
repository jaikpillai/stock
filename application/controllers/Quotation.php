<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Quotaion Orders';

		$this->load->model('model_quotation');
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

		$this->data['page_title'] = 'Manage Quotation';
		$this->render_template('quotation/index', $this->data);	
			
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchQuotationData()
	{
		$result = array('data' => array());

		$data = $this->model_quotation->getQuotationData();
		
		foreach ($data as $key => $value) {

			$count_total_item = $this->model_quotation->countQuotationItem($value['quotation_no']);
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
				$buttons .= '<a target="__blank" href="'.base_url('quotation/printDiv/'.$value['s_no']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('quotation/update/'.$value['s_no'].'/'.$value['quotation_no'].'').'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['s_no'].', '.$value['quotation_no'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			// if($value['is_payment_received'] == 1) {
			// 	$paid_status = '<span class="label label-success">Paid</span>';	
			// }
			// else {
			// 	$paid_status = '<span class="label label-warning">Not Paid</span>';
			// }

		

			$result['data'][$key] = array(
				$value['quotation_no'],
				$party_data['party_name'],
				$value['quotation_date'],
				$count_total_item,
				$value['total_amount'],
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

		$this->data['page_title'] = 'Add Quotation Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$quotation_id = $this->model_quotation->create();
        	
        	if($quotation_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('quotation/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('quotation/create/', 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;


		
			$this->data['products'] = $this->model_products->getActiveProductData(); 
			$this->data['tax_data'] = $this->model_tax->getActiveTax(); 
			
			$this->data['party_data'] =$this->model_party->getActiveParty(); 
            $this->data['getlastquotationid'] = $this->model_quotation->getLastQuotationID();

			$this->render_template('quotation/create', $this->data);
			
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
	public function update($id, $quotation_no)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id || !$quotation_no) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Quotation Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->model_quotation->update($id,  $quotation_no);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('quotation/update/'.$id.'/'.$quotation_no, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('quotation/update/'.$id.'/'.$quotation_no, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$quotation_data = $this->model_quotation->getQuotationData($id);

    		$result['quotation_master'] = $quotation_data;
    		$quotation_item = $this->model_quotation->getQuotationItemData($quotation_data['quotation_no']);
			$this->data['party_data'] = $this->model_party->getActiveParty();
    		foreach($quotation_item as $k => $v) {
    			$result['quotation_item'][] = $v;
    		}

			$this->data['quotation_data'] = $result;
			$this->data['tax_data'] = $this->model_tax->getActiveTax(); 
			

        	$this->data['products'] = $this->model_products->getActiveProductData();      	

            $this->render_template('quotation/edit', $this->data);
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
		$quotation_no = $this->input->post('quotation_no');



        $response = array();
        if($s_no && $quotation_no) {
            $delete = $this->model_quotation->remove($s_no, $quotation_no);
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
			$order_data = $this->model_quotation->getQuotationData($id);
			$orders_items = $this->model_quotation->getQuotationItemData($id);
			$footer_items = $this->model_quotation->getFooter($id);
			$company_info = $this->model_company->getCompanyData(1);
			$party_data = $this->model_party->getPartyData($order_data['party_id']);

			$order_date = strtotime($order_data['quotation_date']);
			$order_date = date( 'd/m/Y', $order_date );
			// $paid_status = ($order_data['is_payment_received'] == 1) ? "Paid" : "Unpaid";



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
					<h6 class="invoice-title-address">
					Phone No:'.$company_info['phone'].'
					</h6>
					<h6 class="invoice-title-address">
			          Email:'.$company_info['address'].'
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
					<b>Quotation No.:</b> '.$order_data['quotation_no'].' </div>
					<div class="col-sm-6 invoice-col padding-0">
					<b>Date.:</b> '.$order_data['quotation_date'].' </div><br>

			      </div>

			      </div>
				  <!-- /.col -->
				  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
				  <div class="padding-5" style="text-align: center;background: lightgray;">
					<b>Credit Memo</b><br>
					</div>
					<div class="invoice-boxes padding-5">
					<b>Ref. No.:</b> '.$order_data['ref_no'].'

				  </div>
				  <div class="invoice-boxes padding-5">
					<b>Ref. Date.:</b> '.$order_data['ref_date'].'

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
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 
					  $total = 0;
					  
			          foreach ($orders_items as $k => $v) {
						
						  $product_data = $this->model_products->getProductData($v['item_id']); 
						  $amount = $v['qty']*$v['rate'];
						  $total = $total + $amount; 
						  $index = $k + 1;

						  $freight_other_charge = $order_data['other_charges'];

						  $discount_amount = $amount - ($amount * $v['discount'])/100;
						  
						  
			          	
						  $html .= '<tr>
							<td>'.$index.'</td>
							<td>'.$product_data['Item_Code'].'</td>
							<td>'.$product_data['Item_Name'].'</td>
							<td>'.$product_data['Item_Make'].'</td>
							<td>'.$v['qty'].'</td>
							<td>'.$v['unit'].'</td>
							<td>'.$v['rate'].'</td>
							<td>'.$v['discount'].'</td>
				            <td>'.$discount_amount.'</td>
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

			    <div class="row">
			    
			      <div class="col-xs-6 pull pull-right" style="page-break-inside: avoid">

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
			            
			            
						$html .='
						<tr>
			              <th>GST ('. $order_data['tax_value'].'%)</th>
			              <td>'.$order_data['total_gst'].'</td>
						</tr>
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
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    </div>
				<!-- /.row -->
				<div style=" border-top: 2px solid;padding: 10px;">
				<div class="row">
				
				<div>

				<b>GST R.No. :</b><br>
				<b>Our Bank Detail :</b><br><br>


				  <b>Terms & Conditions</b><br>

				';
				foreach ($footer_items as $k => $v) {
					
					$index = $k + 1;
					$html .= '  <b>'.$index.'.</b> '.$v['description'].'<br>';
				}
					
					$html.='
					<br><br><br><br><br>
					<b>Receiver\'s Signature</b><br>
			      </div>
			      <!-- /.col -->
	
			      <div class="col-sm-2 invoice-footer">
				  <b>For '.$company_info['company_name'].'</b>
				  <br><br><br>
				  <b>Authorised Signatory</b><br>

			      </div>
				  <!-- /.col -->
				  
			    </div>
			    <!-- /.row -->	
				</div>
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