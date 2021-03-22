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
		$this->load->model('model_terms');

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
				$buttons .= '<a style="font-size: 25px;" target="__blank" href="'.base_url('quotation/quotation/'.$value['s_no']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a style="font-size: 25px;" href="'.base_url('quotation/update/'.$value['s_no'].'/'.$value['quotation_no'].'').'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button style="font-size: 25px;" type="button" class="btn btn-default" onclick="removeFunc('.$value['s_no'].', '.$value['quotation_no'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
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


		public function getTableTaxData()
	{
		$tax = $this->model_tax->getTaxData();

		echo json_encode($tax);
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
        	// $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	// $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;


		
			$this->data['products'] = $this->model_products->getActiveProductData(); 
			$this->data['tax_data'] = $this->model_tax->getActiveTax(); 
			$this->data['terms'] = $this->model_terms->getActiveTerms(); 
			$this->data['terms_data'] = $this->model_terms->getTermsData(); 
			
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
		$products['tax_data']=$this->model_tax->getTaxData();

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
        	// $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	// $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

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
			$this->data['terms_data'] = $this->model_terms->getTermsData(); 
			$this->data['terms'] = $this->model_terms->getTermsDataInQuotation($quotation_data['quotation_no']); 
			
			

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
	// public function quotation($id)
	// {
	// 	if(!in_array('viewOrder', $this->permission)) {
    //         redirect('dashboard', 'refresh');
    //     }
        
	// 	if($id) {
	// 		$order_data = $this->model_quotation->getQuotationData($id);
	// 		$orders_items = $this->model_quotation->getQuotationItemData($id);
	// 		$footer_items = $this->model_terms->getTermsDataInQuotation($id);
	// 		$company_info = $this->model_company->getCompanyData(1);
	// 		$party_data = $this->model_party->getPartyData($order_data['party_id']);
	// 		$bank_details=$this->model_company->getBankDetails();

	// 		$order_date = strtotime($order_data['quotation_date']);
	// 		$order_date = date( 'd/m/Y', $order_date );
	// 		$freight_other_charge = $order_data['other_charges'];
	// 		// $paid_status = ($order_data['is_payment_received'] == 1) ? "Paid" : "Unpaid";

	// 		$quotation_date=date('d-m-Y', strtotime($order_data['quotation_date']));
	// 		$ref_date=date('d-m-Y', strtotime($order_data['ref_date']));
			
	// 		if(strtotime($order_data['quotation_date'])<0){
	// 			$quotation_date='';
	// 		}

	// 		if(strtotime($order_data['ref_date'])<0){
	// 			$ref_date='';
	// 		}


	// 		$html = '<!-- Main content -->
	// 		<!DOCTYPE html>
	// 		<html>
	// 		<head>
	// 		  <meta charset="utf-8">
	// 		  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	// 		  <title></title>
	// 		  <!-- Tell the browser to be responsive to screen width -->
	// 		  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	// 		  <!-- Bootstrap 3.3.7 -->
	// 		  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
	// 		  <!-- Font Awesome -->
	// 		  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
	// 		  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
	// 		  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.css').'?v=<?=time();?">
	// 		</head>
	// 		<body>
			
	// 		<div class="wrapper" style= "overflow: visible">
	// 		  <section class="invoice">
	// 		    <!-- title row -->
	// 		    <div class="row">
	// 			  <div class="col-xs-12 " >
	// 			  <h5 class="invoice-title-address">QUOTATION CUM PROFORMA INVOICE</h5>
	// 		        <h1 class="invoice-title-name" >
	// 		          '.$company_info['company_name'].'
	// 				</h1>
	// 				<h6 class="invoice-title-address">
	// 		          '.$company_info['address'].'
	// 				</h6>
	// 				<div class="display-flex">
	// 				<h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
	// 				Phone No:'.$company_info['phone'].'
	// 				</h6>
	// 				<h6 class="invoice-title-address" style="padding: 10px; padding-top: 0px;">
	// 		          Email:'.$company_info['email'].'
	// 				</h6>
	// 				</div>
	// 		      </div>
	// 		      <!-- /.col -->
	// 		    </div>
	// 			<!-- info row -->
	// 			<div class="invoice-border">
	// 		    <div class="row invoice-info" style="margin-right: -8px;">
			      
	// 			  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
	// 			  <div class="padding-10" style="font-size: 12px;">
	// 				<b>To:<br> M/s. </b> '.$party_data ['party_name'].'<br>'.$party_data ['address'].'<br><br>
	// 				<b>GST No.:</b> '.$party_data ['gst_number'].'<br>
	// 				</div>
	// 		      </div>
	// 			  <!-- /.col -->
	// 			  <div class="col-sm-6 invoice-col table-bordered-invoice invoice-top">
	// 			  <div class="padding-5">
	// 			  <b>Quotation No.: '.$order_data['quotation_no'].'</b><br>
	// 				</div>
	// 				<div class="invoice-boxes padding-5" style="font-size: 12px;">
	// 			  <b>Date.:</b> '.$quotation_date.'<br>
	// 				</div>
	// 				<div class="invoice-boxes padding-5" style="font-size: 12px;">
	// 				<b>Ref. No.:</b> '.$order_data['ref_no'].'<br>
	// 				<b>Ref. Date.:</b> '.$ref_date.'

	// 			  </div>
				  

	// 			  </div>
				  
	// 			  <!-- /.col -->
	// 			</div>
	// 			<div class="row invoice-boxes padding-10">
	// 			<p><b>Dear Sir/Ma\'am <br> We thank you for the above inquiry and are pleased to quote our rates as follows:</b></p>
	// 			</div>
	// 		    <!-- /.row -->	
	// 		    <!-- Table row -->
	// 		    <div class="row" style="margin-right: -15px;">
	// 		      <div class="col-xs-12 table-responsive table-invoice" style="font-size: 10px;">
	// 		        <table class="table table-bordered-invoice" >
	// 		          <thead>
	// 				  <tr>
	// 					<th>S.N.</th>
	// 					<th>Code</th>
	// 					<th>Description</th>
	// 					<th>Make</th>
	// 					<th>Qty</th>
	// 					<th>Unit</th>
	// 					<th>Rate</th>
	// 					<th>Disc. %</th>
	// 					<th>GST</th>
	// 					<th>Amount</th>
	// 		          </tr>
	// 		          </thead>
	// 		          <tbody>'; 
	// 				  $total = 0;
	// 				  $less_discount=0;
	// 				  $tax_per_item=0;
	// 				  $total_with_gst=0;
	// 				  $tax_value_temp=0;
				
	// 				//   $tax_array;
	// 				  $unique_tax=array();
					  
	// 		          foreach ($orders_items as $k => $v) {
						
	// 					  $product_data = $this->model_products->getProductData($v['item_id']); 
	// 					  $amount = $v['qty']*$v['rate'];
	// 					  $total = $total + $amount; 
	// 					  $index = $k + 1;
	// 					  $discount_amount = $amount - ($amount * $v['discount'])/100;
	// 					  $less_discount=$less_discount+($amount * $v['discount'])/100;
						  
	// 					  if($v['tax_id']){
	// 					  $tax_data=$this->model_tax->getTaxData($v['tax_id']); 
	// 					  $tax_value_temp=$tax_value_temp+$tax_data['sValue'];
	// 					  if(!in_array($v['tax_id'],$unique_tax)){
	// 						array_push($unique_tax,$v['tax_id']);
	// 						$tax_array[$tax_data['sTax_Description']]=0;
	// 					  }

	// 					  $tax_array[$tax_data['sTax_Description']]=$tax_array[$tax_data['sTax_Description']]+$discount_amount;
	// 					}else{
	// 						$tax_data['sValue']=0;
	// 					}
			          	
	// 					  $html .= '<tr>
	// 						<td>'.$index.'</td>
	// 						<td>'.$product_data['Item_Code'].'</td>
	// 						<td>'.$product_data['Item_Name'].'</td>
	// 						<td>'.$product_data['Item_Make'].'</td>
	// 						<td>'.$v['qty'].'</td>
	// 						<td>'.$v['unit'].'</td>
	// 						<td>'.$v['rate'].'</td>
	// 						<td>'.$v['discount'].'</td>
	// 						<td>'.$tax_data['sValue'].'</td>
	// 						<td>'.$discount_amount.'</td>
	// 		          	</tr>';
	// 				  }

	// 				// $tax_value = $order_data['tax_value'];
	// 				// $tax_value = $order_data['tax_value'];
	// 				$gross_total = $total - $order_data['total_discount'];
	// 				// $total_after_tax = $gross_total + ($gross_total * $tax_value)/100;
	// 				$final_total = $gross_total + $freight_other_charge;
	// 				$rounded_total_amount = round($final_total);
	// 				$round_off =  ($rounded_total_amount - $total_with_gst);
	// 				$round_off = round($round_off, 2);

	// 		          $html .= '</tbody>
	// 		        </table>
	// 		      </div>
	// 		      <!-- /.col -->
	// 		    </div>
	// 		    <!-- /.row -->

	// 		    <div class="row" style="overflow: hidden; ">
	// 			<div class="col-xs-8">';
	// 			$gst_total_amount=0;
				
	// 			if(!empty($unique_tax) && $tax_value_temp>0)
	// 			{$html .='
	// 			<div class="table-responsive" style="font-size: 10px;">
	// 			  <table class="table table-bordered" >
	// 			  <thead>
	// 			  <tr>
	// 					<th>Amount</th>
	// 					<th>CGST%</th>
	// 		            <th>CGST</th>
	// 					<th>SGST%</th>
	// 		            <th>SGST</th>
	// 				</tr>
	// 				</thead>
	// 				<tbody>';

	// 				$total_amount_gst=0;
	// 				$cgst_total=0;
					
	// 				for($i = 0; $i < sizeof($unique_tax); $i++) {

	// 					$tax_data=$this->model_tax->getTaxData($unique_tax[$i]); 
	// 					$cgst_percent=$tax_data['sValue']/2;
	// 					$cgst=$tax_array[$tax_data['sTax_Description']]*$cgst_percent/100;
	// 					$cgst=number_format($cgst, 2, '.', '');
	// 					$total_amount_gst=$total_amount_gst+$tax_array[$tax_data['sTax_Description']];
	// 					$cgst_total=$cgst_total+$cgst;

	// 					if($cgst>0){
	// 					$html .= '<tr>
	// 					  <td>'.$tax_array[$tax_data['sTax_Description']].'</td>
	// 					  <td>'.$cgst_percent.'</td>
	// 					  <td>'.$cgst.'</td>
	// 					  <td>'.$cgst_percent.'</td>
	// 					  <td>'.$cgst.'</td>
	// 					</tr>';}
	// 				}

	// 				$gst_total_amount=$cgst_total+$cgst_total;

	// 				$total_with_gst=$final_total+$cgst_total+$cgst_total;

	// 				$rounded_total_amount = round($total_with_gst);
	// 				$round_off =  ($rounded_total_amount - $total_with_gst);
	// 				$round_off = round($round_off, 2);
	// 				// $amount_in_words=getIndianCurrency(floatval($rounded_total_amount));

	// 				$html .= '<tr>
	// 					  <td><b>'.$total_amount_gst.'</b></td>
	// 					  <td></td>
	// 					  <td><b>'.$cgst_total.'</b></td>
	// 					  <td></td>
	// 					  <td><b>'.$cgst_total.'</b></td>
	// 					</tr>';
		
	// 				  $html .='
					  
	// 				  </tbody>
	// 			  </table>
	// 			</div>';}
	// 			$html .='<div>
	// 			<h5><b>'.strtoupper(getIndianCurrency(floatval($rounded_total_amount))).'</b></h5>
	// 			</div>
	// 		  </div>
	// 		 <div class="col-xs-4" style="font-size: 10px;">

	// 		        <div class="table-responsive" >
	// 				  <table class="table table-bordered" >
	// 				  <tbody style="font-size:small">
	// 		            <tr>
	// 		              <th style="width:50%">Total:</th>
	// 		              <td>'.$total.'</td>
	// 					</tr>
	// 					<tr>
	// 		              <th style="width:50%">Less Discount:</th>
	// 		              <td>'.$less_discount.'</td>
	// 					</tr>
	// 					<tr>
	// 		              <th style="width:50%">Net Amount:</th>
	// 		              <td>'.$gross_total.'</td>
	// 					</tr>
	// 					<tr>
	// 		              <th style="width:50%">GST Amount:</th>
	// 		              <td>'.$gst_total_amount.'</td>
	// 					</tr>
	// 					<tr>
	// 					<th>Freight/Others</th>
	// 					<td>'.$order_data['other_charges'].'</td>
	// 				  </tr>
	// 				  <tr>
	// 					<th>Round off</th>
	// 					<td>'.$round_off.'</td>
	// 				  </tr>
	// 				  <tr>
	// 				  <th><b style="font-size: 12px;">Total Amount:</b></th>
	// 				  <td><b style="font-size: 12px;">'.$rounded_total_amount.'</b></td>
	// 				</tr>
	// 					</tbody>
	// 		          </table>
	// 		        </div>
	// 			  </div>
	// 		      <!-- /.col -->
	// 		    </div>
	// 			<!-- /.row -->
	// 			<footer style="font-size: 10px;">
	// 			<div style=" border-top: 2px solid;padding: 10px;">
	// 			<div class="row">
				
	// 			<div>
	// 			<div>
	// 			<b>GST R.No. :'.$company_info['gst_no'].'</b><br></div>
	// 			<div class="row" style="page-break-inside: avoid">
	// 			<div class="col-xs-2">
	// 			<b>Our Bank Details :</b></div>';
	// 			foreach ($bank_details as $k => $v) {
					
	// 				$html .= '<div class="col-xs-4">  <b>'.$v['bank_name'].',</b> '.$v['bank_address'].'<br>
	// 				<b>A/c No.: '.$v['acc_no'].'</b> <br>					
	// 				<b>IFSC Code: '.$v['ifsc'].'</b></div>';
	// 			}
	// 			$html.='
	// 			</div>';
				
	// 			if($footer_items){
	// 			$html.='
	// 			<div style="page-break-inside: avoid">
	// 			  <b>Terms & Conditions</b><br>

	// 			';}
	// 			foreach ($footer_items as $k => $v) {
					
	// 				$index = $k + 1;
	// 				$html .= '  <b>'.$index.'.</b> '.$v['description'].'<br>';
	// 			}
					
	// 				$html.='</div>
	// 				<br><br><br><br><br>
	// 				<b>Receiver\'s Signature</b><br>
	// 		      </div>
	// 		      <!-- /.col -->
			      
	// 		      <div class="col invoice-footer" style="page-break-inside: avoid">
	// 			  <b>For '.$company_info['company_name'].'</b>
	// 			  <br><br><br>
	// 			  <b>Authorised Signatory</b><br>

	// 		      </div>
	// 			  <!-- /.col -->
				  
	// 		    </div>
	// 		    <!-- /.row -->	
	// 			</div>
	// 			</footer>
	// 			<!-- /.border -->
	// 		  </section>
	// 		  <!-- /.content -->
	// 		</div>
	// 	</body>
	// </html>';

	// 		  echo $html;
	// 	}
	// }

	public function quotation($id){
		
		
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		if($id) {
			$order_data = $this->model_quotation->getQuotationData($id);
			$orders_items = $this->model_quotation->getQuotationItemData($id);
			$footer_items = $this->model_terms->getTermsDataInQuotation($id);
			$company_info = $this->model_company->getCompanyData(1);
			$party_data = $this->model_party->getPartyData($order_data['party_id']);
			$bank_details=$this->model_company->getBankDetails();

			$order_date = strtotime($order_data['quotation_date']);
			$order_date = date( 'd/m/Y', $order_date );
			$freight_other_charge = $order_data['other_charges'];
			// $paid_status = ($order_data['is_payment_received'] == 1) ? "Paid" : "Unpaid";

			$quotation_date=date('d-m-Y', strtotime($order_data['quotation_date']));
			$ref_date=date('d-m-Y', strtotime($order_data['ref_date']));
			
			if(strtotime($order_data['quotation_date'])<0){
				$quotation_date='';
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
			  <title>Quotation No.: '.$order_data['quotation_no'].'</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.css').'?v=<?=time();?">
			  <style>
			  
			  body{
				width: 21cm;
				margin: auto;
				margin-top:20px;
				margin-bottom:30px;
		   		} 

			
				.page-footer, .page-footer-space {
				height: 1000px;

				}

				.margin-0{
				margin: 0px;
				}

				.margin-top-10{
				margin-top: 10px;
				}

				.margin-bottom-10{
				margin-bottom: 10px;
				}

				.page-footer {
				bottom: 0;
				width: 100%;
				}

				.page-header {
				top: 0mm;
				text-align: start;
				font-size: 10px;
				width: 100%;
				margin-bottom: 0;
				padding-bottom:0px;
				border-bottom: 0;
				}

				table,th,td{
				border: 1px solid;
				margin: 0;
				padding: 0px;
				border-collapse: collapse;
				text-align: start;
				vertical-align: top;
				}

				.table{
					margin-bottom: 0;
				}

				h5{
					margin:0px;
				}

				.padding-10{
				padding: 10;
				}

				.table-invoice {
				padding-left: 0;
				padding-right: 0;
				}

				.page-header table{
				width: 100%;
				padding: 0px;
				border: 1px solid;
				margin: 0;
				border-collapse: inherit;
				text-align: start;
				}

				.no-border{
				border: 0px;
				}

				.page-header th,.page-header td{
				border: 0px;
				border-collapse: inherit;
				}

				.page-footer th,.page-footer td{
					border: 0px;
					border-collapse: inherit;
				}

				.center{
				text-align: center;
				}

				tr{
				border: 0px;
				}

				.background{
					background: lightgray;
					-webkit-print-color-adjust: exact !important;
				}

				.page {
				page-break-after: always;
				}

				@page {
				margin: 10mm
				}

				@media print {
					
				thead {
					display: table-header-group;
					.background h5{
						background: lightgray;
						-webkit-print-color-adjust: exact !important;
					}
	
				} 
				tfoot {display: table-footer-group;}

				@page {
					counter-increment: page;
					counter-reset: page 1;

					span.page-number:after {
						content: "Page " counter(page) " of " counter(pages);
					}
				}
				
				button {display: none;}
				
				body {margin: 0;
					-webkit-print-color-adjust: exact !important;}
				}
			  </style>
			</head>
			<body>

			<table style="width: 100%;">
			<thead>
			<tr>
			  <td>
				<div class="page-header">
				  <table style="border:0px ">
			<thead> 
			  <h6 class="center margin-0 margin-top-10"><b>QUOTATION CUM PROFORMA INVOICE</b></h6>
			  <h2 class="center invoice-title-name margin-0"><b>'.$company_info['company_name'].'</b></h2>
			  <p class="center margin-0">'.$company_info['address'].'</p>
			  <p class="center margin-0 margin-bottom-10">Phone:'.$company_info['phone'].'&ensp;&ensp;Email:'.$company_info['email'].' </p>
			  <span class="page-number">
			</thead>
			<tbody>
			<td style="border:1px solid;width:50%;">
			  <table style="border:0px ">
				<tr >
				  <th style="padding:5px " ><u>To:</u></th>
				  <td style="padding:5px "><h5><small>M/s. </small></b><b>'.$party_data ['party_name'].'</b></h5>'.$party_data ['address'].'</td>
				</tr>
				<tr>
				  <th style="padding:5px ">GST No:</th>
				  <td style="padding:5px ">'.$party_data ['gst_number'].'</td>
				</tr>
				
			  </table>
			</td>
			 <td style="border:1px solid;width:50%;">
			   <table style="border:0px ">
				
				 <tr style="border-bottom: 1px solid;" >
				  <th style="padding:5px;width: 30%; "><h5><b>Quotation No:</b></h5></th>
				  <td style="padding:5px "><h5><b>'.$order_data['quotation_no'].'</b></h5></td>
				</tr>
				<tr style="border-bottom: 1px solid;">
				  <th style="padding:5px "><h5><b>Date:</b></h5></th>
				  <td style="padding:5px "><h5><b>'.$quotation_date.'</b></h5></td>
				</tr>
				<tr >
				  <th style="padding:5px ">Ref No.:</th>
				  <td style="padding:5px ">'.$order_data['ref_no'].'</td>
				</tr>
				<tr>
				  <th style="padding:5px ">Date:</th>
				  <td style="padding:5px ">'.$ref_date.'</td>
				</tr>
			   </table>
			</td>
			</tbody>
		  </table>
				</div>
			  </td>
			</tr>
		  </thead>
				<tbody>
					<tr><td>
					<div class="col-xs-12 table-responsive table-invoice page">
					  <div style="padding:5px"><small><b>Dear Sir/Ma\'am <br> We thank you for the above inquiry and are pleased to quote our rates as follows:</b></small></div>
			        <table class="table table-bordered-invoice" style="font-size: 10px;display: table;width:100%">
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
					  $less_discount=0;
					  $tax_per_item=0;
					  $total_with_gst=0;
					//   $tax_array;
					  $unique_tax=array();
					  
			          foreach ($orders_items as $k => $v) {
						
						  $product_data = $this->model_products->getProductData($v['item_id']); 
						  $amount = $v['qty']*round($v['rate']);
						  $total = $total + $amount; 
						 
						  $index = $k + 1;

						  $discount_amount = $amount - ($amount * $v['discount'])/100;
						  $less_discount=$less_discount+($amount * $v['discount'])/100;
						  
						  $tax_data=$this->model_tax->getTaxData($v['tax_id']); 

						 if($v['tax_id']){
							
							if(!in_array($v['tax_id'],$unique_tax)){
							  array_push($unique_tax,$v['tax_id']);
							  $tax_array[$tax_data['sTax_Description']]=0;
							}
  
							$tax_array[$tax_data['sTax_Description']]=$tax_array[$tax_data['sTax_Description']]+$discount_amount;
						  }else{
							  $tax_data['sValue']=0;
						  }
							
			          	
						  $html .= '<tr>
							<td>'.$index.'</td>
							<td>'.$product_data['Item_Code'].'</td>
							<td>'.$product_data['Item_Name'].'</td>
							<td>'.$product_data['Item_Make'].'</td>
							<td>'.$v['qty'].'</td>
							<td>'.$v['unit'].'</td>
							<td>'.round($v['rate']).'</td>
							<td>'.$v['discount'].'</td>
							<td>'.$tax_data['sValue'].'</td>
				            <td>'.$discount_amount.'</td>
			          	</tr>';
					  }

					// $tax_value = $order_data['tax_value'];
					// $gross_total = $total - $order_data['total_discount'];
					$gross_total = $total - $less_discount;
					// $total_after_tax = $gross_total + ($gross_total * $tax_value)/100;
					$final_total = $gross_total + $freight_other_charge;
					$rounded_total_amount = round($final_total);
					$round_off =  ($rounded_total_amount - $total_with_gst);
					$round_off = round($round_off, 2);

			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
				</tr></td>
				<tr><td>
			    <!-- /.row -->

			<div style="overflow: hidden; margin-top:2px">
			<div class="col-xs-8">';
			$gst_total_amount=0;
			
			if(!empty($unique_tax))
			{$html .='
			<div class="table-responsive" >
			  <table class="table table-bordered" style="font-size: 10px;" >
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

					if($cgst>0){
					$html .= '<tr>
					  <td>'.$tax_array[$tax_data['sTax_Description']].'</td>
					  <td>'.$cgst_percent.'</td>
					  <td>'.$cgst.'</td>
					  <td>'.$cgst_percent.'</td>
					  <td>'.$cgst.'</td>
					</tr>';}
				}

				$gst_total_amount=$cgst_total+$cgst_total;

				$total_with_gst=$final_total+$cgst_total+$cgst_total;

				$rounded_total_amount = round($total_with_gst);
				$round_off =  ($rounded_total_amount - $total_with_gst);
				$round_off = round($round_off, 2);
				// $amount_in_words=getIndianCurrency(floatval($rounded_total_amount));

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
			</div>';}
			$html .='<div style="padding-top:10px">
			<h5><b>'.strtoupper(getIndianCurrency(floatval($rounded_total_amount))).'</b></h5>
			</div>
		  </div>
			 <div class="col-xs-4" style="font-size: 10px;padding-bottom:5px">

			        <div class="table-responsive" >
					  <table class="table table-bordered " style="font-size: 10px;" >
					  <tbody>
			            <tr>
			              <th style="width:50%">Total:</th>
			              <td>'.$total.'</td>
						</tr>
						<tr>
			              <th style="width:50%">Less Discount:</th>
			              <td>'.$less_discount.'</td>
						</tr>
						<tr>
			              <th style="width:50%">Net Amount:</th>
			              <td>'.$gross_total.'</td>
						</tr>
						<tr>
			              <th style="width:50%">GST Amount:</th>
			              <td>'.$gst_total_amount.'</td>
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
					  <th><h5><b>Total Amount:</b></h5></th>
					  <td><h5><b>'.$rounded_total_amount.'</b></h5></td>
					</tr>
						</tbody>
			          </table>
			        </div>
				  </div>
			      <!-- /.col -->
			    </div>
					</td></tr>
				</tbody>


				<tfoot class="page-footer" >
					<tr><td style="padding:5px;border: 1px solid;">
					<table style="border:0px;font-size:10px;width:100%;">
					<thead>
						<tr>
						<th>GST R. No: '.$company_info['gst_no'].'</th>
						</tr>
						<tr>
						<th>Our Bank Details :</th>
						<td>';
						foreach ($bank_details as $k => $v) {
							
							$html .= '<div class="col-xs-6">  <b>'.$v['bank_name'].',</b> '.$v['bank_address'].'<br>
							<b>A/c No.: '.$v['acc_no'].'</b> <br>					
							<b>IFSC Code: '.$v['ifsc'].'</b></div>';
						}
						$html.='
						</td>
						</tr>
					</thead>
					<tbody>
					<table style="border:0px;font-size:10px;width:100%  ">
					<tr>
					<div style="font-size:10px;">';
				
					if($footer_items){
					$html.='
					<p><b>Terms & Conditions</b><small style="float:right;"><b>Contact Person: S.L.Ajaria (9928189392)</b></small></p><p>';}
					foreach ($footer_items as $k => $v) {
						
						$index = $k + 1;
						$html .= '  <b>'.$index.'.</b> '.$v['description'].'<br>';
					}
					$html .= '</p>
					</div>
					</tr>
						<tr>
						<th style="vertical-align: bottom;">
						<br>Receiver\'s Signature
						</th>
						<th style="float:right;text-align:center">
						For  Bharat Scienitic Agencies
						<br><br><br>Authorised Signatory
						</th></tr>
					</table>
					</tbody>
					</table>
					</td></tr>
				</tfoot>
			</table>

			<div style="margin: auto;width: max-content;">
			<button type="button" style="margin:20px" onClick="window.print()">PRINT</button></div>

			</body>
		</html>';

			  echo $html;
		}
	}

}

// Calculate number into words
function getIndianCurrency(float $number)
{
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(0 => '', 1 => 'one', 2 => 'two',
		3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
		7 => 'seven', 8 => 'eight', 9 => 'nine',
		10 => 'ten', 11 => 'eleven', 12 => 'twelve',
		13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
		16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
		19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
		40 => 'forty', 50 => 'fifty', 60 => 'sixty',
		70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
	$digits = array('', 'hundred','thousand','lakh', 'crore');
	while( $i < $digits_length ) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? '' : null;
			$hundred = ($counter == 1 && $str[0]) ? '' : null;
			$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
		} else $str[] = null;
	}
	$Rupees = implode('', array_reverse($str));
	$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	return 'Rupees '.($Rupees ? $Rupees . 'Only ' : '') . $paise;
}
