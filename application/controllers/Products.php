<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Products';

		$this->load->model('model_products');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
        $this->load->model('model_attributes');
        $this->load->model('model_tax');
        $this->load->model('model_unit');
        

	}

    /* 
    * It only redirects to the manage product page
    */
	public function index()
	{
        if(!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('products/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */

    
	public function fetchActiveProductData()
	{
        $result = array('data' => array());
        
        

		$data = $this->model_products->getActiveProductData();

		foreach ($data as $key => $value) {

           
            $amount = $value['Price'];
            // moneyFormatIndia($amount);
            // setlocale(LC_MONETARY, 'en_IN');
            // $price = money_format('%!i', $amount);
            // print $amount;
            
            if($value['Category_ID']){
                $category = $this->model_category->getCategoryFromID($value['Category_ID']);
                $category_name=$category['name'];
            }else{
                $category_name="";
            }
        
            $store_data = $this->model_stores->getStoresData();


			// button
            $buttons = '';
            if(in_array('updateProduct', $this->permission)) {
    			$buttons .= '<a href="'.base_url('products/update/'.$value['Item_ID']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteProduct', $this->permission)) { 
    			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['Item_ID'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }

			// $img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';

            // $availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            // $qty_status = '';
            // if($value['qty'] <= 10) {
            //     $qty_status = '<span class="label label-warning">Low !</span>';
            // } else if($value['qty'] <= 0) {
            //     $qty_status = '<span class="label label-danger">Out of stock !</span>';
            // }

            // print($value['name']);

            // if($category['id']==NULL){
            //     $category['name'] = "";
            // }

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Deleted</span>';
            
            if($value['active'] == 0){
                $buttons = '';
            }

            
        

			$result['data'][$key] = array(
				// $img,
				$value['Item_ID'],
				$value['Item_Name'],
				$status,
                $value['sUnit'],
                $value['Item_Code'],
                $value['Price'],
                $category_name,
                $value['Item_Make'],
                // $store_data['name'],
				// $availability,
				$buttons
			);
		} // /foreach
      
        echo json_encode($result);
     
    }	
    


   

    public function fetchProductData()
	{
        $result = array('data' => array());
        
        

		$data = $this->model_products->getProductData();

		foreach ($data as $key => $value) {

           

            
            
            if($value['Category_ID']){
                $category = $this->model_category->getCategoryFromID($value['Category_ID']);
                $category_name=$category['name'];
            }else{
                $category_name="";
            }
        
            $store_data = $this->model_stores->getStoresData();


			// button
            $buttons = '';
            if(in_array('updateProduct', $this->permission)) {
    			$buttons .= '<a href="'.base_url('products/update/'.$value['Item_ID']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteProduct', $this->permission)) { 
    			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['Item_ID'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }
			

			// $img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';

            // $availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            // $qty_status = '';
            // if($value['qty'] <= 10) {
            //     $qty_status = '<span class="label label-warning">Low !</span>';
            // } else if($value['qty'] <= 0) {
            //     $qty_status = '<span class="label label-danger">Out of stock !</span>';
            // }

            // print($value['name']);

            // if($category['id']==NULL){
            //     $category['name'] = "";
            // }

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Deleted</span>';
            
            if($value['active'] == 0){
                $buttons = '';
            }

           
			$result['data'][$key] = array(
				// $img,
				$value['Item_ID'],
				$value['Item_Name'],
				$status,
                $value['sUnit'],
                $value['Item_Code'],
                $value['Price'],
                $category_name,
                $value['Item_Make'],
                // $store_data['name'],
				// $availability,
				$buttons
			);
		} // /foreach
      
		echo json_encode($result);
	}



    


    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function create()
	{
		if(!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->form_validation->set_rules('product_name', 'Item name', 'trim|required');
		$this->form_validation->set_rules('unit', 'Unit', 'trim|required');
		$this->form_validation->set_rules('purchase_rate', 'Purchase Rate', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        $this->form_validation->set_rules('tax', 'Tax %', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');

        $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim|required');
        $this->form_validation->set_rules('list_price', 'List Price', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {
            // true case
        	$upload_image = $this->upload_image();
            $tax_data = $this->model_tax->getTaxFromID($this->input->post('tax'));
            $unit_data = $this->model_unit->getUnitFromID($this->input->post('unit'));


        	$data = array(
                'Item_ID' => $this->input->post('item_id'),
                'Category_ID' => $this->input->post('category'),
                'Company_ID' => $this->input->post('store'),
                'Item_Name' => $this->input->post('product_name'),
        		'Item_Make' => $this->input->post('make'),
                'sUnit' => $unit_data['sUnit'],
        		'Price' => $this->input->post('list_price'),
                'Item_Code' => $this->input->post('item_code'),
                'Pack_Size' => $this->input->post('pack_size'),
                'Tax' => $tax_data['sValue'],
                'Purchase_Price' => $this->input->post('purchase_rate'),
                'Opening_Balance' => $this->input->post('opening_balance'),
                'Opening_Balance' => $this->input->post('opening_balance'),
                'Current_Balance' => $this->input->post('qty'),
                'iTax_ID' => $tax_data['iTax_ID'],
                'Item_Description' => $this->input->post('description'),
                'ReOrder_Level' => $this->input->post('reorder_level'),
                'Max_Suggested_Qty' => $this->input->post('qty'),
                'active' => 1

                
        		// 'price' => $this->input->post('price'),
        		// 'qty' => $this->input->post('qty'),
        		// 'image' => $upload_image,
        		// 'description' => $this->input->post('description'),
        		// // 'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
        		// // 'brand_id' => json_encode($this->input->post('brands')),
        		// // 'category_id' => json_encode($this->input->post('category')),
        		// 'availability' => $this->input->post('availability'),
        	);

        	$create = $this->model_products->create($data);
        	if($create == true) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('products/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('products/create', 'refresh');
        	}
        }
        else {
            // false case

        	// attributes 
        	$attribute_data = $this->model_attributes->getActiveAttributeData();

        	$attributes_final_data = array();
        	foreach ($attribute_data as $k => $v) {
        		$attributes_final_data[$k]['attribute_data'] = $v;

        		$value = $this->model_attributes->getAttributeValueData($v['id']);

        		$attributes_final_data[$k]['attribute_value'] = $value;
        	}

        	$this->data['attributes'] = $attributes_final_data;
			$this->data['brands'] = $this->model_brands->getActiveBrands();        	
			$this->data['category'] = $this->model_category->getActiveCategroy();        	
            $this->data['stores'] = $this->model_stores->getActiveStore(); 
            $this->data['tax_data']= $this->model_tax->getTaxData();
            $this->data['unit_data']= $this->model_unit->getUnitData();

            // $lastid =  $this->model_products->getLastID();


            
            $this->data['getid'] = $this->model_products->getLastID();
            $this->render_template('products/create', $this->data);
        }	
	}

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */

    public function getId()
    {
        $id = $this->model_products->getLastID();
        return $id;
    }

	public function upload_image()
    {
    	// assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('product_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($product_id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Item name', 'trim|required');
		$this->form_validation->set_rules('unit', 'Unit', 'trim|required');
		$this->form_validation->set_rules('purchase_rate', 'Purchase Rate', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        $this->form_validation->set_rules('tax', 'Tax %', 'trim|required');
        $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim|required');
        $this->form_validation->set_rules('list_price', 'List Price', 'trim|required');

        // $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        // $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        // $this->form_validation->set_rules('price', 'Price', 'trim|required');
        // $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        // $this->form_validation->set_rules('store', 'Store', 'trim|required');
        // $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $tax_data = $this->model_tax->getTaxFromID($this->input->post('tax'));


            // $data = array(
            //     'name' => $this->input->post('product_name'),
            //     'sku' => $this->input->post('sku'),
            //     'price' => $this->input->post('price'),
            //     'qty' => $this->input->post('qty'),
            //     'description' => $this->input->post('description'),
            //     'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
            //     'brand_id' => json_encode($this->input->post('brands')),
            //     'category_id' => json_encode($this->input->post('category')),
            //     'store_id' => $this->input->post('store'),
            //     'availability' => $this->input->post('availability'),
            // );

            $data = array(
                'Item_ID' => $this->input->post('item_id'),
                'Category_ID' => json_encode($this->input->post('category')) ,
                'Company_ID' => $this->input->post('store'),
                'Item_Name' => $this->input->post('product_name'),
        		'Item_Make' => $this->input->post('make'),
                'sUnit' => $this->input->post('unit'),
        		'Price' => $this->input->post('list_price'),
                'Item_Code' => $this->input->post('item_code'),
                'Pack_Size' => $this->input->post('pack_size'),
                'Tax' => $this->input->post('tax'),
                'Purchase_Price' => $this->input->post('purchase_rate'),
                'Opening_Balance' => $this->input->post('opening_balance'),
                'Opening_Balance' => $this->input->post('opening_balance'),
                'Current_Balance' => $this->input->post('qty'),
                'iTax_ID' => json_encode($this->input->post('iTax_ID')) ,
                'Item_Description' => $this->input->post('description'),
                'ReOrder_Level' => $this->input->post('reorder_level'),
                'Max_Suggested_Qty' => $this->input->post('qty'),
                'active' => 1

                
        		// 'price' => $this->input->post('price'),
        		// 'qty' => $this->input->post('qty'),
        		// 'image' => $upload_image,
        		// 'description' => $this->input->post('description'),
        		// // 'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
        		// // 'brand_id' => json_encode($this->input->post('brands')),
        		// // 'category_id' => json_encode($this->input->post('category')),
        		// 'availability' => $this->input->post('availability'),
        	);

            
            if($_FILES['product_image']['size'] > 0) {
                $upload_image = $this->upload_image();
                $upload_image = array('image' => $upload_image);
                
                $this->model_products->update($upload_image, $product_id);
            }

            $update = $this->model_products->update($data, $product_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/update/'.$product_id, 'refresh');
            }
        }
        else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();  
            $this->data['unit_data']= $this->model_unit->getUnitData();


            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;
            
            $this->data['unit_data']= $this->model_unit->getUnitData();
            $this->data['tax_data']= $this->model_tax->getTaxData();

            $this->data['unit_data_sUnit']= $this->model_unit->getUnitDataFromsUnit($product_data['sUnit']);

            $this->render_template('products/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $data = array(
            'active' => 0,);
        

        
        $product_id = $this->input->post('product_id');

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($data, $product_id);
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

}