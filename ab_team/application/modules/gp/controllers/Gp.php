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

    public function submit($id='') {
      is_login();
      if($this->input->post()){
          //gp table
          $gp_data['users_id'] = $this->session->userdata('user_details')[0]->users_id;
          $gp_data['date'] = $this->input->post('date');
          $gp_data['customer_name'] = $this->input->post('customer_name');
          $gp_data['usr_name'] = $this->input->post('usr_name');
          $gp_data['address'] = $this->input->post('address');
          $gp_data['post_address'] = $this->input->post('post_address');
          $gp_data['phone_number'] = $this->input->post('phone_number');
          // echo "<script type='text/javascript'>alert('$a');</script>";
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp', 'id', $id, $gp_data);
            $gp_id = $id;
          } else {
            $this->Gp_model->insertRow('gp',$gp_data);
            $result = $this->Gp_model->get_data_by('gp','', 'id', 'desc');
            $gp_id = $result[0]->id;
          }
          //gp_care_plan table
          $gp_care_plan['gp_id'] = $gp_id;
          $gp_care_plan['background'] = $this->input->post('background');
          $gp_care_plan['social_situation'] = $this->input->post('social_situation');
          $gp_care_plan['health'] = $this->input->post('health');
          $gp_care_plan['care_effort'] = $this->input->post('care_effort');
          $gp_care_plan['health_care'] = $this->input->post('health_care');
          $gp_care_plan['life'] = $this->input->post('life');
          $gp_care_plan['work_method'] = $this->input->post('work_method');
          $gp_care_plan['evaluation'] = $this->input->post('evaluation');
          $gp_care_plan['responsible_name'] = $this->input->post('responsible_name');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_care_plan', 'gp_id', $gp_id, $gp_care_plan);
          } else {
            $this->Gp_model->insertRow('gp_care_plan',$gp_care_plan);
          }
          //gp_relation_contact table
          $gp_relation_contact['gp_id'] = $gp_id;
          $gp_relation_contact['name1'] = $this->input->post('name1');
          $gp_relation_contact['relationship1'] = $this->input->post('relationship1');
          $gp_relation_contact['is_firstcontact1'] = $this->input->post('is_firstcontact1');
          $gp_relation_contact['address1'] = $this->input->post('address1');
          $gp_relation_contact['post_address1'] = $this->input->post('post_address1');
          $gp_relation_contact['phone_number1'] = $this->input->post('phone_number1');
          $gp_relation_contact['name2'] = $this->input->post('name2');
          $gp_relation_contact['relationship2'] = $this->input->post('relationship2');
          $gp_relation_contact['is_firstcontact2'] = $this->input->post('is_firstcontact2');
          $gp_relation_contact['address2'] = $this->input->post('address2');
          $gp_relation_contact['post_address2'] = $this->input->post('post_address2');
          $gp_relation_contact['phone_number2'] = $this->input->post('phone_number2');
          $gp_relation_contact['good_man'] = $this->input->post('good_man');
          $gp_relation_contact['phone_number3'] = $this->input->post('phone_number3');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_relation_contact', 'gp_id', $gp_id, $gp_relation_contact);
          } else {
            $this->Gp_model->insertRow('gp_relation_contact',$gp_relation_contact);
          }
          //gp_important_contact table
          $gp_important_contact['gp_id'] = $gp_id;
          $gp_important_contact['doctor'] = $this->input->post('doctor');
          $gp_important_contact['doctor_phone_number'] = $this->input->post('doctor_phone_number');
          $gp_important_contact['nurse'] = $this->input->post('nurse');
          $gp_important_contact['nurse_phone_number'] = $this->input->post('nurse_phone_number');
          $gp_important_contact['staff'] = $this->input->post('staff');
          $gp_important_contact['staff_phone_number'] = $this->input->post('staff_phone_number');
          $gp_important_contact['other1'] = $this->input->post('other1');
          $gp_important_contact['other1_phone_number'] = $this->input->post('other1_phone_number');
          $gp_important_contact['other2'] = $this->input->post('other2');
          $gp_important_contact['other2_phone_number'] = $this->input->post('other2_phone_number');
          $gp_important_contact['is_service'] = $this->input->post('is_service');
          $gp_important_contact['is_key'] = $this->input->post('is_key');
          $gp_important_contact['is_alarm'] = $this->input->post('is_alarm');
          $gp_important_contact['is_key_alarm'] = $this->input->post('is_key_alarm');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_important_contact', 'gp_id', $gp_id, $gp_important_contact);
          } else {
            $this->Gp_model->insertRow('gp_important_contact',$gp_important_contact);
          }
          //gp_overview_act table
          $gp_overview_act['gp_id'] = $gp_id;
          $gp_overview_act['contact_self'] = $this->input->post('contact_self');
          $gp_overview_act['contact_help_with'] = $this->input->post('contact_help_with');
          $gp_overview_act['contact_help_given'] = $this->input->post('contact_help_given');
          $gp_overview_act['interest_self'] = $this->input->post('interest_self');
          $gp_overview_act['interest_help_with'] = $this->input->post('interest_help_with');
          $gp_overview_act['interest_help_given'] = $this->input->post('interest_help_given');
          $gp_overview_act['act_self'] = $this->input->post('act_self');
          $gp_overview_act['act_help_with'] = $this->input->post('act_help_with');
          $gp_overview_act['act_help_given'] = $this->input->post('act_help_given');
          $gp_overview_act['other_desire_act'] = $this->input->post('other_desire_act');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_overview_act', 'gp_id', $gp_id, $gp_overview_act);
          } else {
            $this->Gp_model->insertRow('gp_overview_act',$gp_overview_act);
          }
          //gp_overview_communication
          $gp_overview_communication['gp_id'] = $gp_id;
          $gp_overview_communication['sight_self'] = $this->input->post('sight_self');
          $gp_overview_communication['sight_help_with'] = $this->input->post('sight_help_with');
          $gp_overview_communication['sight_help_given'] = $this->input->post('sight_help_given');
          $gp_overview_communication['hear_self'] = $this->input->post('hear_self');
          $gp_overview_communication['hear_help_with'] = $this->input->post('hear_help_with');
          $gp_overview_communication['hear_help_given'] = $this->input->post('hear_help_given');
          $gp_overview_communication['talk_self'] = $this->input->post('talk_self');
          $gp_overview_communication['talk_help_with'] = $this->input->post('talk_help_with');
          $gp_overview_communication['talk_help_given'] = $this->input->post('talk_help_given');
          $gp_overview_communication['vocabulary_self'] = $this->input->post('vocabulary_self');
          $gp_overview_communication['vocabulary_help_with'] = $this->input->post('vocabulary_help_with');
          $gp_overview_communication['vocabulary_help_given'] = $this->input->post('vocabulary_help_given');
          $gp_overview_communication['other_self'] = $this->input->post('other_self');
          $gp_overview_communication['other_help_with'] = $this->input->post('other_help_with');
          $gp_overview_communication['other_help_given'] = $this->input->post('other_help_given');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_overview_communication', 'gp_id', $gp_id, $gp_overview_communication);
          } else {
            $this->Gp_model->insertRow('gp_overview_communication',$gp_overview_communication);
          }
          //gp_overview_house table
          $gp_overview_house['gp_id'] = $gp_id;
          $gp_overview_house['clean_self'] = $this->input->post('clean_self');
          $gp_overview_house['clean_help_with'] = $this->input->post('clean_help_with');
          $gp_overview_house['clean_help_given'] = $this->input->post('clean_help_given');
          $gp_overview_house['bed_self'] = $this->input->post('bed_self');
          $gp_overview_house['bed_help_with'] = $this->input->post('bed_help_with');
          $gp_overview_house['bed_help_given'] = $this->input->post('bed_help_given');
          $gp_overview_house['buy_self'] = $this->input->post('buy_self');
          $gp_overview_house['buy_help_with'] = $this->input->post('buy_help_with');
          $gp_overview_house['buy_help_given'] = $this->input->post('buy_help_given');
          $gp_overview_house['laundry_self'] = $this->input->post('laundry_self');
          $gp_overview_house['laundry_help_with'] = $this->input->post('laundry_help_with');
          $gp_overview_house['laundry_help_given'] = $this->input->post('laundry_help_given');
          $gp_overview_house['water_self'] = $this->input->post('water_self');
          $gp_overview_house['water_help_with'] = $this->input->post('water_help_with');
          $gp_overview_house['water_help_given'] = $this->input->post('water_help_given');
          $gp_overview_house['other_desire_house'] = $this->input->post('other_desire_house');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_overview_house', 'gp_id', $gp_id, $gp_overview_house);
          } else {
            $this->Gp_model->insertRow('gp_overview_house',$gp_overview_house);
          }
          //gp_overview_hygiene table
          $gp_hygiene['gp_id'] = $gp_id;
          $gp_hygiene['bath_self'] = $this->input->post('bath_self');
          $gp_hygiene['bath_help_with'] = $this->input->post('bath_help_with');
          $gp_hygiene['bath_help_given'] = $this->input->post('bath_help_given');
          $gp_hygiene['wash_self'] = $this->input->post('wash_self');
          $gp_hygiene['wash_help_with'] = $this->input->post('wash_help_with');
          $gp_hygiene['wash_help_given'] = $this->input->post('wash_help_given');
          $gp_hygiene['nail_self'] = $this->input->post('nail_self');
          $gp_hygiene['nail_help_with'] = $this->input->post('nail_help_with');
          $gp_hygiene['nail_help_given'] = $this->input->post('nail_help_given');
          $gp_hygiene['hair_self'] = $this->input->post('hair_self');
          $gp_hygiene['hair_help_with'] = $this->input->post('hair_help_with');
          $gp_hygiene['hair_help_given'] = $this->input->post('hair_help_given');
          $gp_hygiene['incontinence_self'] = $this->input->post('incontinence_self');
          $gp_hygiene['incontinence_help_with'] = $this->input->post('incontinence_help_with');
          $gp_hygiene['incontinence_help_given'] = $this->input->post('incontinence_help_given');
          $gp_hygiene['dress_self'] = $this->input->post('dress_self');
          $gp_hygiene['dress_help_with'] = $this->input->post('dress_help_with');
          $gp_hygiene['dress_help_given'] = $this->input->post('dress_help_given');
          $gp_hygiene['other_desire_hygiene'] = $this->input->post('other_desire_hygiene');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_overview_hygiene', 'gp_id', $gp_id, $gp_hygiene);
          } else {
            $this->Gp_model->insertRow('gp_overview_hygiene',$gp_hygiene);
          }
          //gp_overview_sleep table
          $gp_overview_sleep['gp_id'] = $gp_id;
          $gp_overview_sleep['sleep_self'] = $this->input->post('sleep_self');
          $gp_overview_sleep['sleep_help_with'] = $this->input->post('sleep_help_with');
          $gp_overview_sleep['sleep_help_given'] = $this->input->post('sleep_help_given');
          $gp_overview_sleep['other_desire_sleep'] = $this->input->post('other_desire_sleep');
          if( !empty($id) ){
            $this->Gp_model->updateRow('gp_overview_sleep', 'gp_id', $gp_id, $gp_overview_sleep);
          } else {
            $this->Gp_model->insertRow('gp_overview_sleep',$gp_overview_sleep);
          }
          //forum

          $forum_data['date'] = $gp_data['date'];
          $forum_data['customer_name'] = $gp_data['customer_name'];
          $forum_data['form'] = 'gp';
          $forum_data['form_id'] = $gp_id;
          $forum_data['html_file'] = "data/".$forum_data['form']."_".$forum_data['form_id'].".html";
          $forum_data['users_id'] = $gp_data['users_id'];
          if( !empty($id) ) {
            $this->Gp_model->updateRow_M('forum', 'form_id', $gp_id, 'form', 'gp', $forum_data);
          } else {
            $this->Gp_model->insertRow('forum',$forum_data);
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
              <h1 style="color: #00c0ef!important;">Genomförandeplan</h1>
              <h4 style="color: #00c0ef!important;">Annas vård och hemtjänstteam AB</h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Datum: <span style="font-weight: normal;"><?php echo $gp_data['date'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Kundnamn: <span style="font-weight: normal;"><?php echo $gp_data['customer_name'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Personal: <span style="font-weight: normal;"><?php echo $gp_data['usr_name'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Adress: <span style="font-weight: normal;"><?php echo $gp_data['address'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Postadress: <span style="font-weight: normal;"><?php echo $gp_data['post_address'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_data['phone_number'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="background: lightblue;">
            <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
              <h4>Närstående/god man</h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Namn: <span style="font-weight: normal;"><?php echo $gp_relation_contact['name1'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Relation: <span style="font-weight: normal;"><?php echo $gp_relation_contact['relationship1'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Förstahandskontakt: <span style="font-weight: normal;"><?php echo $gp_relation_contact['is_firstcontact1'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Adress: <span style="font-weight: normal;"><?php echo $gp_relation_contact['address1'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Postadress: <span style="font-weight: normal;"><?php echo $gp_relation_contact['post_address1'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_relation_contact['phone_number1'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Namn: <span style="font-weight: normal;"><?php echo $gp_relation_contact['name2'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Relation: <span style="font-weight: normal;"><?php echo $gp_relation_contact['relationship2'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Förstahandskontakt: <span style="font-weight: normal;"><?php echo $gp_relation_contact['is_firstcontact2'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Adress: <span style="font-weight: normal;"><?php echo $gp_relation_contact['address2'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Postadress: <span style="font-weight: normal;"><?php echo $gp_relation_contact['post_address2'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_relation_contact['phone_number2'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">God man: <span style="font-weight: normal;"><?php echo $gp_relation_contact['good_man'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_relation_contact['phone_number3'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="background: lightblue;">
            <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
              <h4>Viktiga kontakter</h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Vårdcentral/Läkare: <span style="font-weight: normal;"><?php echo $gp_important_contact['doctor'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_important_contact['doctor_phone_number'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Omvårdnadsansvarig sjuksköterska(KSK/ DSK): <span style="font-weight: normal;"><?php echo $gp_important_contact['nurse'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_important_contact['nurse_phone_number'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Kontaktpersonal: <span style="font-weight: normal;"><?php echo $gp_important_contact['staff'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_important_contact['staff_phone_number'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Övrig: <span style="font-weight: normal;"><?php echo $gp_important_contact['other1'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_important_contact['other1_phone_number'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Övrig: <span style="font-weight: normal;"><?php echo $gp_important_contact['other2'];?></span></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Telefonnummer: <span style="font-weight: normal;"><?php echo $gp_important_contact['other2_phone_number'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Färdtjänst: <span style="font-weight: normal;"><?php echo $gp_important_contact['is_service'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Nyckel: <span style="font-weight: normal;"><?php echo $gp_important_contact['is_key'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <h4>Viktiga kontakter</h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Trygghetslarm: <span style="font-weight: normal;"><?php echo $gp_important_contact['is_alarm'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Nyckel: <span style="font-weight: normal;"><?php echo $gp_important_contact['is_key_alarm'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <h4>OMSORGSPLANERING</h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">1. Bakgrund (Uppväxt, yrkesliv, familj, viktiga händelser.)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['background'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">2. Social situation, nätverk, intressen (Civilstånd, viktiga kontakter, vänner, intressen)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['social_situation'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">3. Hälsa (Konsekvens av sjukdom, klarar/klarar inte pga sjukdom, allergi, överkänslighet)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['health'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">4. Omsorgsinsatser (Vilka omsorgsinsatser har den enskilde)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['care_effort'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">5. Hälso- och sjukvårdsinsatser (Delegerade sjukvårdsinsatser)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['health_care'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">6. Särskilda behov/önskemål (Vardagsrutiner, vanor, aktiviteter, få sin vardag som man är van)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['life'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">7. Mål och arbetsmetoder (Bevarande, utvecklande, rehabiliterande, guldkant = att få/återfå meningsfullhet, accepterande av tillbakagång. Genom vilka metoder kan man nå målen.)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['work_method'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">8. Uppföljning/Utvärdering (Up)</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_care_plan['evaluation'];?></h4>
            </div>

            <div class="col-xs-12 box-content-input">
              <p class="input_option">Ansvarig för upprättelse av plan:</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Namn: <span style="font-weight: normal;"><?php echo $gp_care_plan['responsible_name'];?></span></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <h4>ÖVERSIKT AV OMSORGSINSATSER</h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">- HYGIEN</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">1. Bad och dusch</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_hygiene['bath_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['bath_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['bath_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">2. Tvättar sig</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_hygiene['wash_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['wash_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['wash_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">3. Nagelvård</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_hygiene['nail_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['nail_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['nail_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">4. Hårvård och Rakning</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_hygiene['hair_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['hair_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['hair_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">5. Inkontinens- hjälpmedel</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_hygiene['incontinence_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['incontinence_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['incontinence_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">6. På- och avklädning</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_hygiene['dress_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['dress_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['dress_help_given'];?></h4>
            </div>

            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Särskilda behov och önskemål angående hygien: </h4>
              <h4 style="font-weight: normal;"><?php echo $gp_hygiene['other_desire_hygiene'];?></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">- AKTIVERING, INTRESSEN, SOCIALA KONTAKTER</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">1. Kontakter</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_act['contact_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['contact_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['contact_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">2. Intressen</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_act['interest_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['interest_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['interest_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">3. Aktiviteter</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_act['act_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['act_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['act_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Särskilda behov och önskemål angående aktivering, intressen och sociala kontakter:</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_act['other_desire_act'];?></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">- BOSTADEN</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">1. Städning</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_house['clean_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['clean_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['clean_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">2. Bäddning</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_house['bed_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['bed_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['bed_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">3. Inköp</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_house['buy_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['buy_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['buy_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">4. Tvätt</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_house['laundry_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['laundry_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['laundry_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">5. Vattna blommor</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_house['water_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['water_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['water_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Särskilda behov och önskemål angående bostaden:</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_house['other_desire_house'];?></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">- KOMMUNIKATION</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">1. Syn</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_communication['sight_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['sight_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['sight_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">2. Hörsel</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_communication['hear_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['hear_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['hear_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">3. Kan tala</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_communication['talk_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['talk_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['talk_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">4. Ordförståelse</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_communication['vocabulary_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['vocabulary_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['vocabulary_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">5. Annat</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_communication['other_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['other_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_communication['other_help_given'];?></h4>
            </div>

            <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">- SÖMN</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <p class="input_option">1. Sömn/nattillsyn</p>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Klarar själv: <span style="font-weight: normal;"><?php echo $gp_overview_sleep['sleep_self'];?></span></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_sleep['sleep_help_with'];?></h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_sleep['sleep_help_given'];?></h4>
            </div>
            <div class="col-xs-12 box-content-input">
              <h4 style="font-weight: 600;">Särskilda behov och önskemål angående sömn/nattillsyn:</h4>
              <h4 style="font-weight: normal;"><?php echo $gp_overview_sleep['other_desire_sleep'];?></h4>
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
        if(!empty($id)){
          $result_gp = $this->Gp_model->get_data_by('gp',$id, 'id');
          $customer_data['id'] = $id;
          $customer_data['date'] = $result_gp[0]->date;
          $customer_data['customer_name'] = $result_gp[0]->customer_name;
          $customer_data['usr_name'] = $result_gp[0]->usr_name;
          $customer_data['address'] = $result_gp[0]->address;
          $customer_data['post_address'] = $result_gp[0]->post_address;
          $customer_data['phone_number'] = $result_gp[0]->phone_number;
          $result_gp_care = $this->Gp_model->get_data_by('gp_care_plan',$id, 'gp_id');
          $customer_data['background'] = $result_gp_care[0]->background;
          $customer_data['social_situation'] = $result_gp_care[0]->social_situation;
          $customer_data['health'] = $result_gp_care[0]->health;
          $customer_data['care_effort'] = $result_gp_care[0]->care_effort;
          $customer_data['health_care'] = $result_gp_care[0]->health_care;
          $customer_data['life'] = $result_gp_care[0]->life;
          $customer_data['work_method'] = $result_gp_care[0]->work_method;
          $customer_data['evaluation'] = $result_gp_care[0]->evaluation;
          $customer_data['responsible_name'] = $result_gp_care[0]->responsible_name;
          $result_gp_import = $this->Gp_model->get_data_by('gp_important_contact',$id, 'gp_id');
          $customer_data['doctor'] = $result_gp_import[0]->doctor;
          $customer_data['doctor_phone_number'] = $result_gp_import[0]->doctor_phone_number;
          $customer_data['nurse'] = $result_gp_import[0]->nurse;
          $customer_data['nurse_phone_number'] = $result_gp_import[0]->nurse_phone_number;
          $customer_data['staff'] = $result_gp_import[0]->staff;
          $customer_data['staff_phone_number'] = $result_gp_import[0]->staff_phone_number;
          $customer_data['other1'] = $result_gp_import[0]->other1;
          $customer_data['other1_phone_number'] = $result_gp_import[0]->other1_phone_number;
          $customer_data['other2'] = $result_gp_import[0]->other2;
          $customer_data['other2_phone_number'] = $result_gp_import[0]->other2_phone_number;
          $customer_data['is_service'] = $result_gp_import[0]->is_service;
          $customer_data['is_key'] = $result_gp_import[0]->is_key;
          $customer_data['is_alarm'] = $result_gp_import[0]->is_alarm;
          $customer_data['is_key_alarm'] = $result_gp_import[0]->is_key_alarm;
          $result_gp_act = $this->Gp_model->get_data_by('gp_overview_act',$id, 'gp_id');
          $customer_data['contact_self'] = $result_gp_act[0]->contact_self;
          $customer_data['contact_help_with'] = $result_gp_act[0]->contact_help_with;
          $customer_data['contact_help_given'] = $result_gp_act[0]->contact_help_given;
          $customer_data['interest_self'] = $result_gp_act[0]->interest_self;
          $customer_data['interest_help_with'] = $result_gp_act[0]->interest_help_with;
          $customer_data['interest_help_given'] = $result_gp_act[0]->interest_help_given;
          $customer_data['act_self'] = $result_gp_act[0]->act_self;
          $customer_data['act_help_with'] = $result_gp_act[0]->act_help_with;
          $customer_data['act_help_given'] = $result_gp_act[0]->act_help_given;
          $customer_data['other_desire_act'] = $result_gp_act[0]->other_desire_act;
          $result_gp_comm = $this->Gp_model->get_data_by('gp_overview_communication',$id, 'gp_id');
          $customer_data['sight_self'] = $result_gp_comm[0]->sight_self;
          $customer_data['sight_help_with'] = $result_gp_comm[0]->sight_help_with;
          $customer_data['sight_help_given'] = $result_gp_comm[0]->sight_help_given;
          $customer_data['hear_self'] = $result_gp_comm[0]->hear_self;
          $customer_data['hear_help_with'] = $result_gp_comm[0]->hear_help_with;
          $customer_data['hear_help_given'] = $result_gp_comm[0]->hear_help_given;
          $customer_data['talk_self'] = $result_gp_comm[0]->talk_self;
          $customer_data['talk_help_with'] = $result_gp_comm[0]->talk_help_with;
          $customer_data['talk_help_given'] = $result_gp_comm[0]->talk_help_given;
          $customer_data['vocabulary_self'] = $result_gp_comm[0]->vocabulary_self;
          $customer_data['vocabulary_help_with'] = $result_gp_comm[0]->vocabulary_help_with;
          $customer_data['vocabulary_help_given'] = $result_gp_comm[0]->vocabulary_help_given;
          $customer_data['other_self'] = $result_gp_comm[0]->other_self;
          $customer_data['other_help_with'] = $result_gp_comm[0]->other_help_with;
          $customer_data['other_help_given'] = $result_gp_comm[0]->other_help_given;
          $result_gp_house = $this->Gp_model->get_data_by('gp_overview_house',$id, 'gp_id');
          $customer_data['clean_self'] = $result_gp_house[0]->clean_self;
          $customer_data['clean_help_with'] = $result_gp_house[0]->clean_help_with;
          $customer_data['clean_help_given'] = $result_gp_house[0]->clean_help_given;
          $customer_data['bed_self'] = $result_gp_house[0]->bed_self;
          $customer_data['bed_help_with'] = $result_gp_house[0]->bed_help_with;
          $customer_data['bed_help_given'] = $result_gp_house[0]->bed_help_given;
          $customer_data['buy_self'] = $result_gp_house[0]->buy_self;
          $customer_data['buy_help_with'] = $result_gp_house[0]->buy_help_with;
          $customer_data['buy_help_given'] = $result_gp_house[0]->buy_help_given;
          $customer_data['laundry_self'] = $result_gp_house[0]->laundry_self;
          $customer_data['laundry_help_with'] = $result_gp_house[0]->laundry_help_with;
          $customer_data['laundry_help_given'] = $result_gp_house[0]->laundry_help_given;
          $customer_data['water_self'] = $result_gp_house[0]->water_self;
          $customer_data['water_help_with'] = $result_gp_house[0]->water_help_with;
          $customer_data['water_help_given'] = $result_gp_house[0]->water_help_given;
          $customer_data['other_desire_house'] = $result_gp_house[0]->other_desire_house;
          $result_gp_hygiene = $this->Gp_model->get_data_by('gp_overview_hygiene',$id, 'gp_id');
          $customer_data['bath_self'] = $result_gp_hygiene[0]->bath_self;
          $customer_data['bath_help_with'] = $result_gp_hygiene[0]->bath_help_with;
          $customer_data['bath_help_given'] = $result_gp_hygiene[0]->bath_help_given;
          $customer_data['wash_self'] = $result_gp_hygiene[0]->wash_self;
          $customer_data['wash_help_with'] = $result_gp_hygiene[0]->wash_help_with;
          $customer_data['wash_help_given'] = $result_gp_hygiene[0]->wash_help_given;
          $customer_data['nail_self'] = $result_gp_hygiene[0]->nail_self;
          $customer_data['nail_help_with'] = $result_gp_hygiene[0]->nail_help_with;
          $customer_data['nail_help_given'] = $result_gp_hygiene[0]->nail_help_given;
          $customer_data['hair_self'] = $result_gp_hygiene[0]->hair_self;
          $customer_data['hair_help_with'] = $result_gp_hygiene[0]->hair_help_with;
          $customer_data['hair_help_given'] = $result_gp_hygiene[0]->hair_help_given;
          $customer_data['incontinence_self'] = $result_gp_hygiene[0]->incontinence_self;
          $customer_data['incontinence_help_with'] = $result_gp_hygiene[0]->incontinence_help_with;
          $customer_data['incontinence_help_given'] = $result_gp_hygiene[0]->incontinence_help_given;
          $customer_data['dress_self'] = $result_gp_hygiene[0]->dress_self;
          $customer_data['dress_help_with'] = $result_gp_hygiene[0]->dress_help_with;
          $customer_data['dress_help_given'] = $result_gp_hygiene[0]->dress_help_given;
          $customer_data['other_desire_hygiene'] = $result_gp_hygiene[0]->other_desire_hygiene;
          $result_gp_sleep = $this->Gp_model->get_data_by('gp_overview_sleep',$id, 'gp_id');
          $customer_data['sleep_self'] = $result_gp_sleep[0]->sleep_self;
          $customer_data['sleep_help_with'] = $result_gp_sleep[0]->sleep_help_with;
          $customer_data['sleep_help_given'] = $result_gp_sleep[0]->sleep_help_given;
          $customer_data['other_desire_sleep'] = $result_gp_sleep[0]->other_desire_sleep;
          $result_gp_rel = $this->Gp_model->get_data_by('gp_relation_contact',$id, 'gp_id');
          $customer_data['name1'] = $result_gp_rel[0]->name1;
          $customer_data['relationship1'] = $result_gp_rel[0]->relationship1;
          $customer_data['is_firstcontact1'] = $result_gp_rel[0]->is_firstcontact1;
          $customer_data['address1'] = $result_gp_rel[0]->address1;
          $customer_data['post_address1'] = $result_gp_rel[0]->post_address1;
          $customer_data['phone_number1'] = $result_gp_rel[0]->phone_number1;
          $customer_data['name2'] = $result_gp_rel[0]->name2;
          $customer_data['relationship2'] = $result_gp_rel[0]->relationship2;
          $customer_data['is_firstcontact2'] = $result_gp_rel[0]->is_firstcontact2;
          $customer_data['address2'] = $result_gp_rel[0]->address2;
          $customer_data['post_address2'] = $result_gp_rel[0]->post_address2;
          $customer_data['phone_number2'] = $result_gp_rel[0]->phone_number2;
          $customer_data['good_man'] = $result_gp_rel[0]->good_man;
          $customer_data['phone_number3'] = $result_gp_rel[0]->phone_number3;

          $data['user_data'] = $customer_data;
          $this->load->view('gp', $data);
        } else {
          $user['name'] = $this->session->userdata ('user_details')[0]->name;
          $data1['usr_data'] = $user;
          $this->load->view('gp', $data1);
        }
        $this->load->view('include/footer');
    }
}
?>