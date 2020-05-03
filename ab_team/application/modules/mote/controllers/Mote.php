<?php
defined('BASEPATH') OR exit('No direct script access allowed ');
class Mote extends CI_Controller {
  
    function __construct() {
        parent::__construct(); 
		$this->load->model('Mote_model');
		$this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    /**
      * This function is redirect to 
      * @return Void
      */
    public function index() {
      is_login();
      $this->load->view('include/header');
      $this->load->view('mote');
      $this->load->view('include/footer');
    }

    

}
?>