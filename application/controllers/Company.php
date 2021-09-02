<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Company extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Company';

		$this->load->model('model_company');
	}

	/* 
    * It redirects to the company page and displays all the company information
    * It also updates the company information into the database if the 
    * validation for each input field is successfully valid
    */
	public function index()
	{
		if (!in_array('updateCompany', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('company_name', 'Company name', 'trim|required');
		$this->form_validation->set_rules('service_charge_value', 'Charge Amount', 'trim|integer');
		$this->form_validation->set_rules('vat_charge_value', 'Vat Charge', 'trim|integer');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');


		if ($this->form_validation->run() == TRUE) {
			// true case

			$data = array(
				'company_name' => $this->input->post('company_name'),
				'address' => $this->input->post('address'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'country' => $this->input->post('country'),
				'message' => $this->input->post('message'),
				'currency' => $this->input->post('currency'),
				'gst_no' => $this->input->post('gst_number_value')

			);



			$update = $this->model_company->update($data, 1);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('company/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('company/index', 'refresh');
			}
		} else {

			// false case


			$this->data['currency_symbols'] = $this->currency();
			$this->data['company_data'] = $this->model_company->getCompanyData(1);
			$this->data['bank_details'] = $this->model_company->getBankDetails();
			$this->render_template('company/index', $this->data);
		}
	}
}
