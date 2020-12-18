<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Party extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'party';

		$this->load->model('model_party');
	}

	/* 
	* It only redirects to the manage category page
	*/
	public function index()
	{

		if(!in_array('viewCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->render_template('party/index', $this->data);	
	}	

	/*
	* It checks if it gets the category id and retreives
	* the category information from the category model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchPartyDataById($id) 
	{
		if($id) {
			$data = $this->model_party->getPartyData($id);
			echo json_encode($data);
		}

		return false;
	}

	/*
	* Fetches the category value from the category table 
	* this function is called from the datatable ajax function
	*/
	public function fetchPartyData()
	{
		$result = array('data' => array());

		$data = $this->model_party->getPartyData();

		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			//Buttons according to permission

			if(in_array('updateCategory', $this->permission)) {
				if(in_array('updateProduct', $this->permission)) {
					$buttons .= '<a href="'.base_url('party/update/'.$value['party_id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
				}
			}

			if(in_array('deleteCategory', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['party_id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}
				

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';


           
			$result['data'][$key] = array(
				$value['party_id'],
				$value['party_name'],
				$value['address'],
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* Its checks the category form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{
		if(!in_array('createCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('party_name', 'Party Name', 'required');
		$this->form_validation->set_rules('party_address', 'Party Address', 'required');
		$this->form_validation->set_rules('state', 'State', 'required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
				'party_name' => $this->input->post('party_name'),
				'address' => $this->input->post('party_address'),
				'state' => $this->input->post('state'),
				'contact_person' => $this->input->post('contact_person'),	
				'contact_number' => $this->input->post('contact_number'),	
				'is_vendor' => $this->input->post('is_vendor'),
				'is_customer' => $this->input->post('is_customer'),
				'is_customer' => $this->input->post('is_customer'),
				'email_id' => $this->input->post('email_id'),
				'gst_number' => $this->input->post('gst_number'),

        	);

			$create = $this->model_party->create($data);
			
			if($create == true) {
        		$this->session->set_flashdata('success', 'Successfully created');
				redirect('party/', 'refresh');

        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('party/create', 'refresh');

        	}
        	
        }
        else {
        	$response['success'] = false;
        	foreach ($_POST as $key => $value) {
        		$response['messages'][$key] = form_error($key);
        	}
		}
		$this->data['getid'] = $this->model_party->getLastID();

		$this->render_template('party/create', $this->data);
		


	}

	/*
	* Its checks the category form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update($id)
	{

		if(!in_array('updateCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			$this->form_validation->set_rules('party_name', 'Party Name', 'required');
			$this->form_validation->set_rules('party_address', 'Party Address', 'required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        	'party_name' => $this->input->post('party_name'),
				'address' => $this->input->post('party_address'),
				'state' => $this->input->post('state'),
				'contact_person' => $this->input->post('contact_person'),	
				'contact_number' => $this->input->post('contact_number'),	
				'is_vendor' => $this->input->post('is_vendor'),
				'is_customer' => $this->input->post('is_customer'),
				'is_customer' => $this->input->post('is_customer'),
				'email_id' => $this->input->post('email_id'),
				'gst_number' => $this->input->post('gst_number'),
				'active' => $this->input->post('active'),

	        	);

	        	$update = $this->model_party->update($data, $id);
	        	if($update == true) {
					$this->session->set_flashdata('success', 'Successfully Updated');
					redirect('party/', 'refresh');
	
				}
				else {
					$this->session->set_flashdata('errors', 'Error occurred!!');
					redirect('party/edit', 'refresh');
	
				}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		$this->data['party_data']= $this->model_party->getPartyData($id);

		$this->render_template('party/edit', $this->data); 


	}

	/*
	* It removes the category information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$party_id = $this->input->post('party_id');
		$data = array(
			'active' => 0,
			);

		$response = array();
		if($party_id) {
			$delete = $this->model_party->update($data ,$party_id);
			if($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";	
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

}