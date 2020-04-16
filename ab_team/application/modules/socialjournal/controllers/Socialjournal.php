<?php
defined('BASEPATH') OR exit('No direct script access allowed ');
class Socialjournal extends CI_Controller {
  
    function __construct() {
        parent::__construct(); 
		$this->load->model('Socialjournal_model');
		$this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    /**
      * This function is redirect to 
      * @return Void
      */
    public function index() {
      is_login();
      $this->load->view('include/header');
      $this->load->view('socialjournal');
      $this->load->view('include/footer');
    }

    public function submit($id='') {
        is_login();
        if($this->input->post()){
            $customer_data['users_id'] = $this->session->userdata('user_details')[0]->users_id;
            $customer_data['date'] = $this->input->post('date');
            $customer_data['customer_name'] = $this->input->post('customer_name');
            $customer_data['usr_name'] = $this->input->post('usr_name');
            $customer_data['several_users'] = $this->input->post('several_users');
            $customer_data['course'] = $this->input->post('course');
            $customer_data['time'] = $this->input->post('time');
            $customer_data['location'] = $this->input->post('location');
            $customer_data['zone'] = $this->input->post('zone');
            $customer_data['type_event'] = $this->input->post('type_event');
            if( !empty($id) ){
              $this->Socialjournal_model->updateRow('socialjournal', 'id', $id, $customer_data);
            } else {
              $this->Socialjournal_model->insertRow('socialjournal',$customer_data);
            }
            $result = $this->Socialjournal_model->get_data_by('socialjournal','', 'id', 'desc');
            $customer_id = $result[0]->id;
            $forum_data['date'] = $customer_data['date'];
            $forum_data['customer_name'] = $this->input->post('customer_name');
            $forum_data['form'] = 'socialjournal';
            $forum_data['form_id'] = $customer_id;
            $forum_data['html_file'] = "data/".$forum_data['form']."_".$forum_data['form_id'].".html";
            $forum_data['users_id'] = $customer_data['users_id'];
            if( !empty($id) ) {
              $this->Socialjournal_model->updateRow_M('forum', 'form_id', $id, 'form', 'socialjournal', $forum_data);
            } else {
              $this->Socialjournal_model->insertRow('forum',$forum_data);
            }
            ?>

            <?php
            ob_start();
            ?>
            <html>
            <meta charset="UTF-8">
            <body>
              <div class="box-body">
                <div class="col-xs-12" style="text-align:center; margin-bottom: 10px;">
                  <h1 style="color: #00c0ef!important;">SOCIAL JOURNAL FÖR GENOMFÖRANDE</h1>
                  <h1 style="color: #00c0ef!important;">(ENLIGT SOL OCH LSS)</h1>
                  <h4 style="color: #00c0ef!important;">Annas vård och hemtjänstteam AB</h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">Kundnamn: <span style="font-weight: normal;"><?php echo $customer_data['customer_name'];?></span></h4>
                  <h4 style="font-weight: 600;">Zon: <span style="font-weight: normal;"><?php echo $customer_data['zone'];?></span></h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">Personal: <span style="font-weight: normal;"><?php echo $customer_data['usr_name'];?></span></h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">Alternativt flera brukare berörda av händelsen: <span style="font-weight: normal;"><?php echo $customer_data['several_users'];?></span></h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">Händelsedatum & tid: <span style="font-weight: normal;"><?php echo $customer_data['date'];?> <?php echo $customer_data['time'];?></span></h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">Händelseplats*: <span style="font-weight: normal;"><?php echo $customer_data['location'];?></span></h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">• Typ av händelse</h4>
                  <h4 style="font-weight: normal;"><?php echo $customer_data['type_event'];?></h4>
                </div>
                <div class="col-xs-12" style="min-height: 150px; margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">• Händelsebeskrivning (beskriv detaljerat vad sominträffat)</h4>
                  <h4 style="font-weight: normal;"><?php echo $customer_data['course'];?></h4>
                </div>
              </div>
            </body>
            </html>
            <?php
            file_put_contents($forum_data['html_file'], ob_get_contents());
            ?>
            <?php
            header("Location: ". base_url()."user/forum");
        } else {
            $this->load->view('include/header'); 
            $this->load->view('socialjournal');
            $this->load->view('include/footer');
        }
    }

    public function form($id='') {
        is_login();
        $this->load->view('include/header');        
        if(!empty($id)){
          $result_data = $this->Socialjournal_model->get_data_by('socialjournal',$id, 'id');
          $customer_data['id'] = $id;
          $customer_data['date'] = $result_data[0]->date;
          $customer_data['time'] = $result_data[0]->time;
          $customer_data['location'] = $result_data[0]->location;
          $customer_data['customer_name'] = $result_data[0]->customer_name;
          $customer_data['zone'] = $result_data[0]->zone;
          $customer_data['usr_name'] = $result_data[0]->usr_name;
          $customer_data['several_users'] = $result_data[0]->several_users;
          $customer_data['course'] = $result_data[0]->course;
          $customer_data['type_event'] = $result_data[0]->type_event;
          $data['user_data'] = $customer_data;
          $this->load->view('socialjournal', $data);
        } else {
          $user['name'] = $this->session->userdata ('user_details')[0]->name;
          $data1['usr_data'] = $user;
          $this->load->view('socialjournal', $data1);
        }
        $this->load->view('include/footer');
    }

}
?>