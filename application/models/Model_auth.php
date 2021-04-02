<?php

class Model_auth extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* 
		This function checks if the email exists in the database
	*/
	public function check_email($email)
	{
		if ($email) {
			$sql = 'SELECT * FROM users WHERE email = ?';
			$query = $this->db->query($sql, array($email));
			$result = $query->num_rows();
			return ($result == 1) ? true : false;
		}

		return false;
	}

	/* 
		This function checks if the email and password matches with the database
	*/
	public function login($email, $password)
	{
		if ($email && $password) {
			$sql = "SELECT * FROM users WHERE email = ?";
			$query = $this->db->query($sql, array($email));

			if ($query->num_rows() == 1) {
				$result = $query->row_array();

				$hash_password = password_verify($password, $result['password']);
				if ($hash_password === true) {

					$financial_years = $this->model_financialyear->getFinancialYear();
					$current_date = date("Y-m-d");

					foreach ($financial_years as $k => $v) {
						$start_date = $v['start_date'];
						$end_date = $v['end_date'];
						$financial_year_id = $v['key_value'];



						if (($current_date >= $start_date) && ($current_date <= $end_date)) {
							// $selected_financial_year = $financial_year_id;
							$this->session->set_userdata("selected_financial_year", $financial_year_id);
						}
					}

					return $result;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}
}
