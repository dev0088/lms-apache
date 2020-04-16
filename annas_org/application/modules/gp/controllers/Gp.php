<?php
defined('BASEPATH') OR exit('No direct script access allowed ');
class Gp extends CI_Controller {

    function __construct() {
        parent::__construct(); 
		$this->load->model('Gp_model');
		$this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    /**
      * This function is redirect to users profile page
      * @return Void
      */
    public function index() {
      is_login();
      $this->load->view('include/header');
      $this->load->view('gp');
      $this->load->view('include/footer');
    }

}
?>