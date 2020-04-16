<style type="text/css">
  div.box-content-input {
    margin: 0 0 10px;
  }
  .input_option {
    font-weight: 600;
    /*padding: 5px;*/
    /*display: inline;*/
  }
  div.box-content-input input{
    /*display: inline;*/
  }
</style>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<!-- Main content -->
  <section class="content">
  <!-- 
  <?php if($this->session->flashdata("messagePr")){?>
    <div class="alert alert-info">      
      <?php echo $this->session->flashdata("messagePr")?>
    </div>
  <?php } ?> -->

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
        <?php if(!empty($user_data['id'])) { ?>
          <!-- <form action="<?php echo base_url().'gp/submit/'.$user_data['id']; ?>" method="post"> -->
          <form action="" method="post">
        <?php } else { ?>
        <!-- <form action="<?php echo base_url().'gp/submit/'; ?>" method="post"> -->
        <form action="" method="post">
        <?php } ?>
            <div class="box-header with-border">
              <h3 class="box-title" style="color: #00c0ef!important;font-weight:600;">GENOMFÖRANDEPLAN</h3>
              <div class="box-tools">
                <button type="submit" class="btn-sm  btn btn-success modalButtonUser"><i class="glyphicon glyphicon-plus"></i> Submit</button>

                <!-- 
                <?php if(CheckPermission("users", "own_create")){ ?>
                <button type="button" class="btn-sm  btn btn-success modalButtonUser" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Add User</button>
                <?php } if(setting_all('email_invitation') == 1){  ?>
                <button type="button" class="btn-sm  btn btn-success InviteUser" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Invite People</button>
                <?php } ?> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Datum</p><input type="text" name="date" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Kundnamn</p><input type="text" name="customer_name" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Personal</p><input type="text" id="usr_name" name="usr_name" size="30" value="<?php echo (!empty($user_data['usr_name'])?$user_data['usr_name']:$usr_data['name']);?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="address" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="post_address" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
                <h4>Närstående/god man</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="name1" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Relation</p><input type="text" name="relationship1" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Förstahandskontakt</p>
                <input type="radio" name="is_firstcontact1" value="yes" /><span>Ja</span>
                <input type="radio" name="is_firstcontact1" value="no" /><span>Nej</span>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="address1" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="post_address1" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number1" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="name2" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Relation</p><input type="text" name="relationship2" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Förstahandskontakt</p>
                <input type="radio" name="is_firstcontact2" value="yes" /><span>Ja</span>
                <input type="radio" name="is_firstcontact2" value="no" /><span>Nej</span>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="address2" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="post_address2" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number2" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">God man</p><input type="text" name="good_man" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number3" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
                <h4>Viktiga kontakter</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Vårdcentral/Läkare</p><input type="text" name="doctor" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="doctor_phone_number" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Omvårdnadsansvarig sjuksköterska:   KSK/ DSK</p>
                <input type="text" name="nurse" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="nurse_phone_number" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Kontaktpersonal</p><input type="text" name="staff" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="staff_phone_number" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Övrig</p><input type="text" name="other1" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="other1_phone_number" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Övrig</p><input type="text" name="other2" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="other2_phone_number" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Färdtjänst</p>
                <div>
                <input type="checkbox" class="is_service_checkbox" id="is_service_option1" name="is_service" value="yes">
                <label for="is_service_option1"> Ja</label><br>
                <input type="checkbox" class="is_service_checkbox" id="is_service_option2" name="is_service" value="escort">
                <label for="is_service_option2"> Ledsagare</label><br>
                <input type="checkbox" class="is_service_checkbox" id="is_service_option3" name="is_service" value="no">
                <label for="is_service_option3"> Nej</label><br>
                </div>
                <!-- <input type="radio" name="service_radio1" value="yes" /><span>Ja</span>
                <input type="radio" name="service_radio1" value="Ledsagare" /><span>Ledsagare</span>
                <input type="radio" name="service_radio1" value="no" /><span>Nej</span> -->
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Nyckel</p>
                <input type="radio" name="is_key" value="yes" /><span>Ja</span>
                <input type="radio" name="is_key" value="no" /><span>Nej</span>
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
                <h4>Viktiga kontakter</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Trygghetslarm</p>
                <input type="radio" name="is_alarm" value="yes" /><span>Ja</span>
                <input type="radio" name="is_alarm" value="no" /><span>Nej</span>
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Nyckel</p>
                <input type="radio" name="is_key_alarm" value="yes" /><span>Ja</span>
                <input type="radio" name="is_key_alarm" value="no" /><span>Nej</span>
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
                <h4>OMSORGSPLANERING</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">1. Bakgrund (Uppväxt, yrkesliv, familj, viktiga händelser.)</p>
                <textarea name="background" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">2. Social situation, nätverk, intressen (Civilstånd, viktiga kontakter, vänner, intressen)</p>
                <textarea name="social_situation" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">3. Hälsa (Konsekvens av sjukdom, klarar/klarar inte pga sjukdom, allergi, överkänslighet)</p>
                <textarea name="health" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">4. Omsorgsinsatser (Vilka omsorgsinsatser har den enskilde)</p>
                <textarea name="care_effort" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">5. Hälso‐ och sjukvårdsinsatser (Delegerade sjukvårdsinsatser)</p>
                <textarea name="health_care" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">6. Särskilda behov/önskemål (Vardagsrutiner, vanor, aktiviteter, få sin vardag som man är van)</p>
                <textarea name="life" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">7. Mål och arbetsmetoder (Bevarande, utvecklande, rehabiliterande, guldkant = att få/återfå meningsfullhet, accepterande av tillbakagång. Genom vilka metoder kan man nå målen.)</p>
                <textarea name="work_method" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">8. Uppföljning/Utvärdering (Up)</p>
                <textarea name="evaluation" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>

              <div class="col-xs-12 box-content-input">
                <p class="input_option">Ansvarig för upprättelse av plan:</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="responsible_name" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
                <h4>ÖVERSIKT AV OMSORGSINSATSER</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">- HYGIEN</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Bad och dusch</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="bath_self" value="yes" /><span>Ja</span>
                <input type="radio" name="bath_self" value="no" /><span>Nej</span>
                <textarea name="bath_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="bath_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Tvättar sig</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="wash_self" value="yes" /><span>Ja</span>
                <input type="radio" name="wash_self" value="no" /><span>Nej</span>
                <textarea name="wash_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="wash_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Nagelvård</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="nail_self" value="yes" /><span>Ja</span>
                <input type="radio" name="nail_self" value="no" /><span>Nej</span>
                <textarea name="nail_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="nail_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Hårvård och Rakning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="hair_self" value="yes" /><span>Ja</span>
                <input type="radio" name="hair_self" value="no" /><span>Nej</span>
                <textarea name="hair_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="hair_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Inkontinens‐ hjälpmedel</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="incontinence_self" value="yes" /><span>Ja</span>
                <input type="radio" name="incontinence_self" value="no" /><span>Nej</span>
                <textarea name="incontinence_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="incontinence_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• På‐ och avklädning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="dress_self" value="yes" /><span>Ja</span>
                <input type="radio" name="dress_self" value="no" /><span>Nej</span>
                <textarea name="dress_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="dress_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående hygien:</p>
                <textarea name="other_desire_hygiene" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">- AKTIVERING, INTRESSEN, SOCIALA KONTAKTER</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Kontakter</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="contact_self" value="yes" /><span>Ja</span>
                <input type="radio" name="contact_self" value="no" /><span>Nej</span>
                <textarea name="contact_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="contact_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Intressen</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="interest_self" value="yes" /><span>Ja</span>
                <input type="radio" name="interest_self" value="no" /><span>Nej</span>
                <textarea name="interest_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="interest_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Aktiviteter</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="act_self" value="yes" /><span>Ja</span>
                <input type="radio" name="act_self" value="no" /><span>Nej</span>
                <textarea name="act_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="act_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående aktivering, intressen och sociala kontakter:</p>
                <textarea name="other_desire_act" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">- BOSTADEN</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Städning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="clean_self" value="yes" /><span>Ja</span>
                <input type="radio" name="clean_self" value="no" /><span>Nej</span>
                <textarea name="clean_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="clean_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Bäddning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="bed_self" value="yes" /><span>Ja</span>
                <input type="radio" name="bed_self" value="no" /><span>Nej</span>
                <textarea name="bed_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="bed_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Inköp</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="buy_self" value="yes" /><span>Ja</span>
                <input type="radio" name="buy_self" value="no" /><span>Nej</span>
                <textarea name="buy_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="buy_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Tvätt</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="laundry_self" value="yes" /><span>Ja</span>
                <input type="radio" name="laundry_self" value="no" /><span>Nej</span>
                <textarea name="laundry_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="laundry_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Vattna blommor</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="water_self" value="yes" /><span>Ja</span>
                <input type="radio" name="water_self" value="no" /><span>Nej</span>
                <textarea name="water_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="water_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående bostaden:</p>
                <textarea name="other_desire_house" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">- KOMMUNIKATION</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Syn</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="sight_self" value="yes" /><span>Ja</span>
                <input type="radio" name="sight_self" value="no" /><span>Nej</span>
                <textarea name="sight_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="sight_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Hörsel</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="hear_self" value="yes" /><span>Ja</span>
                <input type="radio" name="hear_self" value="no" /><span>Nej</span>
                <textarea name="hear_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="hear_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Kan tala</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="talk_self" value="yes" /><span>Ja</span>
                <input type="radio" name="talk_self" value="no" /><span>Nej</span>
                <textarea name="talk_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="talk_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Ordförståelse</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="vocabulary_self" value="yes" /><span>Ja</span>
                <input type="radio" name="vocabulary_self" value="no" /><span>Nej</span>
                <textarea name="vocabulary_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="vocabulary_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Annat</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="other_self" value="yes" /><span>Ja</span>
                <input type="radio" name="other_self" value="no" /><span>Nej</span>
                <textarea name="other_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="other_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">- SÖMN</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Sömn/nattillsyn</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="sleep_self" value="yes" /><span>Ja</span>
                <input type="radio" name="sleep_self" value="no" /><span>Nej</span>
                <textarea name="sleep_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="sleep_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående sömn/nattillsyn:</p>
                <textarea name="other_desire_sleep" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>

            </div>
            <!-- /.box-body -->
          </form>
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div> 
<script type="text/javascript">
  $(document).ready(function() {  
    $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
  });
</script>