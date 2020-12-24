<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financialyear extends Admin_Controller {

    public function __construct() {

        parent::__construct();
     
        // load base_url
        $this->load->helper('url');
      }
     

    public function save()
    {
      //code goes here
      // for example: getting the post values of the form:
      $form_data = $this->input->post();
      // or just the username:
      $selected_year = $this->input->post("financial_year");
      $this->session->set_userdata("selected_financial_year", $selected_year);
   
      redirect($_SERVER['HTTP_REFERER']);
        

      // then do whatever you want with it :)

    }
}
?>