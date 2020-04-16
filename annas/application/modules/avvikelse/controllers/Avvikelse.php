<?php
defined('BASEPATH') OR exit('No direct script access allowed ');
class Avvikelse extends CI_Controller {

    function __construct() {
        parent::__construct(); 
		$this->load->model('Avvikelse_model');
		$this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    /**
      * This function is redirect to users profile page
      * @return Void
      */
    public function index() {
    	is_login();
        $this->load->view('include/header');
        $this->load->view('avvikelse');
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
            $customer_data['suggestion'] = $this->input->post('suggestion');
            $customer_data['time'] = $this->input->post('time');
            $customer_data['location'] = $this->input->post('location');
            $customer_data['zone'] = $this->input->post('zone');
            $customer_data['type_event'] = $this->input->post('type_event');
            $customer_data['admin_remark'] = $this->input->post('admin_remark');
            $customer_data['vice_admin'] = $this->input->post('vice_admin');
            // $a=$customer_name['vice_admin'];
            // echo "<script type='text/javascript'>alert('$a');</script>";

            $customer_data['checked_events'] = '';
            for($i = 1; $i <= 15; $i++) {
              if ( !empty($this->input->post('event'.$i)) ) {
                $customer_data['checked_events'] .= 'event'.$i.';';
                $customer_data['event_values'] .= $this->input->post('event'.$i).'; ';
              }
            }

            if( !empty($id) ){
              $this->Avvikelse_model->updateRow('avvikelse', 'id', $id, $customer_data);
            } else {
              $this->Avvikelse_model->insertRow('avvikelse',$customer_data);
            }
            
            $result = $this->Avvikelse_model->get_data_by('avvikelse','', 'id', 'desc');
            $customer_id = $result[0]->id;

            $forum_data['date'] = $customer_data['date'];
            $forum_data['customer_name'] = $this->input->post('customer_name');
            $forum_data['form'] = 'avvikelse';
            $forum_data['form_id'] = $customer_id;
            $forum_data['html_file'] = "data/".$forum_data['form']."_".$forum_data['form_id'].".html";
            $forum_data['users_id'] = $customer_data['users_id'];
            $forum_data['vice_admin'] = $customer_data['vice_admin'];
            if( !empty($id) ) {
              $this->Avvikelse_model->updateRow_M('forum', 'form_id', $id, 'form', 'avvikelse', $forum_data);
            } else {
              $this->Avvikelse_model->insertRow('forum',$forum_data);
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
                  <h1 style="color: #00c0ef!important;">Avvikelserapportering SoL</h1>
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
                <div class="col-xs-12" style="min-height: 150px; margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">• Händelsebeskrivning (beskriv detaljerat vad sominträffat)</h4>
                  <h4 style="font-weight: normal;"><?php echo $customer_data['course'];?></h4>
                </div>
                <div class="col-xs-12" style="margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">• Typ av händelse</h4>
                  <h4 style="font-weight: normal;"><?php echo $customer_data['event_values'];?></h4>
                  <h4 style="font-weight: normal;">Annat: <?php echo $customer_data['type_event'];?></h4>
                </div>
                <div class="col-xs-12" style="min-height: 80px; margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">• Ev. direkt utförd handling för att undvika mer skada</h4>
                  <h4 style="font-weight: normal;"><?php echo $customer_data['suggestion'];?></h4>
                </div>
                <div class="col-xs-12" style="min-height: 50px; margin-bottom: 10px;">
                  <h4 style="font-weight: 600;">• Verksamhetschefs åtgärder</h4>
                  <h4 style="font-weight: normal;"><?php echo $customer_data['admin_remark'];?></h4>
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
            $this->load->view('avvikelse');
            $this->load->view('include/footer');
        }
    }

    public function form($id='') {
        is_login();
        $this->load->view('include/header');
        // $this->load->view('avvikelse');
        // $this->load->view('include/footer');
        if(!empty($id)){
          $result_data = $this->Avvikelse_model->get_data_by('avvikelse',$id, 'id');
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
          $customer_data['suggestion'] = $result_data[0]->suggestion;
          $customer_data['admin_remark'] = $result_data[0]->admin_remark;
          $customer_data['checked_events'] = $result_data[0]->checked_events;
          $customer_data['vice_admin'] = $result_data[0]->vice_admin;
          // $a = $result_data[0]->suggestion;
          // echo "<script type='text/javascript'>alert('$a');</script>";
          $data['user_data'] = $customer_data;
          $this->load->view('avvikelse', $data);
        } else {
          $user['name'] = $this->session->userdata ('user_details')[0]->name;
          $data1['usr_data'] = $user;
          $this->load->view('avvikelse', $data1);
        }
        $this->load->view('include/footer');
    }

    public function get_users_list (){
      $result_data = $this->Avvikelse_model->get_data_by('users');
      $a = 0;
      foreach ($result_data as $row => $value) {
        $output_arr[$a]['users_id'] = $value->users_id;
        $output_arr[$a]['name'] = $value->name;
        $a += 1;
      }
      echo json_encode($output_arr);
    }
}
?>