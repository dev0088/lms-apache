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
          <form action="<?php echo base_url().'gp/submit/'.$user_data['id']; ?>" method="post">
        <?php } else { ?>
        <form action="<?php echo base_url().'gp/submit/'; ?>" method="post">
        <form action="" method="post">
        <?php } ?>
            <div class="box-header with-border">
              <h3 class="box-title" style="color: #00c0ef!important;font-weight:600;">GENOMFÖRANDEPLAN</h3>
              <div class="box-tools">
                <button type="submit" class="btn-sm  btn btn-success modalButtonUser"><i class="glyphicon glyphicon-plus"></i> Spara</button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Datum</p><input type="text" name="date" size="30" value="<?php echo (!empty($user_data['date'])?$user_data['date']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Kundnamn</p><input type="text" name="customer_name" size="30" value="<?php echo (!empty($user_data['customer_name'])?$user_data['customer_name']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Personal</p><input type="text" id="usr_name" name="usr_name" size="30" value="<?php echo (!empty($user_data['usr_name'])?$user_data['usr_name']:$usr_data['name']);?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="address" size="30" value="<?php echo (!empty($user_data['address'])?$user_data['address']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="post_address" size="30" value="<?php echo (!empty($user_data['post_address'])?$user_data['post_address']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number" size="30" value="<?php echo (!empty($user_data['phone_number'])?$user_data['phone_number']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
                <h4>Närstående/god man</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="name1" size="30" value="<?php echo (!empty($user_data['name1'])?$user_data['name1']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Relation</p><input type="text" name="relationship1" size="30" value="<?php echo (!empty($user_data['relationship1'])?$user_data['relationship1']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Förstahandskontakt</p>
                <input type="radio" name="is_firstcontact1" value="yes" /><span>Ja</span>
                <input type="radio" name="is_firstcontact1" value="no" /><span>Nej</span>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="address1" size="30" value="<?php echo (!empty($user_data['address1'])?$user_data['address1']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="post_address1" size="30" value="<?php echo (!empty($user_data['post_address1'])?$user_data['post_address1']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number1" size="30" value="<?php echo (!empty($user_data['phone_number1'])?$user_data['phone_number1']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="name2" size="30" value="<?php echo (!empty($user_data['name2'])?$user_data['name2']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Relation</p><input type="text" name="relationship2" size="30" value="<?php echo (!empty($user_data['relationship2'])?$user_data['relationship2']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Förstahandskontakt</p>
                <input type="radio" name="is_firstcontact2" value="yes" /><span>Ja</span>
                <input type="radio" name="is_firstcontact2" value="no" /><span>Nej</span>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="address2" size="30" value="<?php echo (!empty($user_data['address2'])?$user_data['address2']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="post_address2" size="30" value="<?php echo (!empty($user_data['post_address2'])?$user_data['post_address2']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number2" size="30" value="<?php echo (!empty($user_data['phone_number2'])?$user_data['phone_number2']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">God man</p><input type="text" name="good_man" size="30" value="<?php echo (!empty($user_data['good_man'])?$user_data['good_man']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="phone_number3" size="30" value="<?php echo (!empty($user_data['phone_number3'])?$user_data['phone_number3']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
                <h4>Viktiga kontakter</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Vårdcentral/Läkare</p><input type="text" name="doctor" size="30" value="<?php echo (!empty($user_data['doctor'])?$user_data['doctor']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="doctor_phone_number" size="30" value="<?php echo (!empty($user_data['doctor_phone_number'])?$user_data['doctor_phone_number']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Omvårdnadsansvarig sjuksköterska:   KSK/ DSK</p>
                <input type="text" name="nurse" size="30" value="<?php echo (!empty($user_data['nurse'])?$user_data['nurse']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="nurse_phone_number" size="30" value="<?php echo (!empty($user_data['nurse_phone_number'])?$user_data['nurse_phone_number']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Kontaktpersonal</p><input type="text" name="staff" size="30" value="<?php echo (!empty($user_data['staff'])?$user_data['staff']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="staff_phone_number" size="30" value="<?php echo (!empty($user_data['staff_phone_number'])?$user_data['staff_phone_number']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Övrig</p><input type="text" name="other1" size="30" value="<?php echo (!empty($user_data['other1'])?$user_data['other1']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="other1_phone_number" size="30" value="<?php echo (!empty($user_data['other1_phone_number'])?$user_data['other1_phone_number']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Övrig</p><input type="text" name="other2" size="30" value="<?php echo (!empty($user_data['other2'])?$user_data['other2']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="other2_phone_number" size="30" value="<?php echo (!empty($user_data['other2_phone_number'])?$user_data['other2_phone_number']:'');?>">
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
                <textarea name="background" rows="3" style="width:100%"><?php echo (!empty($user_data['background'])?$user_data['background']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">2. Social situation, nätverk, intressen (Civilstånd, viktiga kontakter, vänner, intressen)</p>
                <textarea name="social_situation" rows="3" style="width:100%"><?php echo (!empty($user_data['social_situation'])?$user_data['social_situation']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">3. Hälsa (Konsekvens av sjukdom, klarar/klarar inte pga sjukdom, allergi, överkänslighet)</p>
                <textarea name="health" rows="3" style="width:100%"><?php echo (!empty($user_data['health'])?$user_data['health']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">4. Omsorgsinsatser (Vilka omsorgsinsatser har den enskilde)</p>
                <textarea name="care_effort" rows="3" style="width:100%"><?php echo (!empty($user_data['care_effort'])?$user_data['care_effort']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">5. Hälso‐ och sjukvårdsinsatser (Delegerade sjukvårdsinsatser)</p>
                <textarea name="health_care" rows="3" style="width:100%"><?php echo (!empty($user_data['health_care'])?$user_data['health_care']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">6. Särskilda behov/önskemål (Vardagsrutiner, vanor, aktiviteter, få sin vardag som man är van)</p>
                <textarea name="life" rows="3" style="width:100%"><?php echo (!empty($user_data['life'])?$user_data['life']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">7. Mål och arbetsmetoder (Bevarande, utvecklande, rehabiliterande, guldkant = att få/återfå meningsfullhet, accepterande av tillbakagång. Genom vilka metoder kan man nå målen.)</p>
                <textarea name="work_method" rows="3" style="width:100%"><?php echo (!empty($user_data['work_method'])?$user_data['work_method']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">8. Uppföljning/Utvärdering (Up)</p>
                <textarea name="evaluation" rows="3" style="width:100%"><?php echo (!empty($user_data['evaluation'])?$user_data['evaluation']:'');?></textarea>
              </div>

              <div class="col-xs-12 box-content-input">
                <p class="input_option">Ansvarig för upprättelse av plan:</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="responsible_name" size="30" value="<?php echo (!empty($user_data['responsible_name'])?$user_data['responsible_name']:'');?>">
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
                <textarea name="bath_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['bath_help_with'])?$user_data['bath_help_with']:'');?></textarea>
                <input type="text" name="bath_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['bath_help_given'])?$user_data['bath_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Tvättar sig</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="wash_self" value="yes" /><span>Ja</span>
                <input type="radio" name="wash_self" value="no" /><span>Nej</span>
                <textarea name="wash_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['wash_help_with'])?$user_data['wash_help_with']:'');?></textarea>
                <input type="text" name="wash_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['wash_help_given'])?$user_data['wash_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Nagelvård</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="nail_self" value="yes" /><span>Ja</span>
                <input type="radio" name="nail_self" value="no" /><span>Nej</span>
                <textarea name="nail_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['nail_help_with'])?$user_data['nail_help_with']:'');?></textarea>
                <input type="text" name="nail_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['nail_help_given'])?$user_data['nail_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Hårvård och Rakning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="hair_self" value="yes" /><span>Ja</span>
                <input type="radio" name="hair_self" value="no" /><span>Nej</span>
                <textarea name="hair_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['hair_help_with'])?$user_data['hair_help_with']:'');?></textarea>
                <input type="text" name="hair_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['hair_help_given'])?$user_data['hair_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Inkontinens‐ hjälpmedel</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="incontinence_self" value="yes" /><span>Ja</span>
                <input type="radio" name="incontinence_self" value="no" /><span>Nej</span>
                <textarea name="incontinence_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['incontinence_help_with'])?$user_data['incontinence_help_with']:'');?></textarea>
                <input type="text" name="incontinence_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['incontinence_help_given'])?$user_data['incontinence_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• På‐ och avklädning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="dress_self" value="yes" /><span>Ja</span>
                <input type="radio" name="dress_self" value="no" /><span>Nej</span>
                <textarea name="dress_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['dress_help_with'])?$user_data['dress_help_with']:'');?></textarea>
                <input type="text" name="dress_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['dress_help_given'])?$user_data['dress_help_given']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående hygien:</p>
                <textarea name="other_desire_hygiene" rows="3" style="width:100%"><?php echo (!empty($user_data['other_desire_hygiene'])?$user_data['other_desire_hygiene']:'');?></textarea>
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
                <textarea name="contact_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['contact_help_with'])?$user_data['contact_help_with']:'');?></textarea>
                <input type="text" name="contact_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['contact_help_given'])?$user_data['contact_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Intressen</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="interest_self" value="yes" /><span>Ja</span>
                <input type="radio" name="interest_self" value="no" /><span>Nej</span>
                <textarea name="interest_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['interest_help_with'])?$user_data['interest_help_with']:'');?></textarea>
                <input type="text" name="interest_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['interest_help_given'])?$user_data['interest_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Aktiviteter</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="act_self" value="yes" /><span>Ja</span>
                <input type="radio" name="act_self" value="no" /><span>Nej</span>
                <textarea name="act_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['act_help_with'])?$user_data['act_help_with']:'');?></textarea>
                <input type="text" name="act_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['act_help_given'])?$user_data['act_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående aktivering, intressen och sociala kontakter:</p>
                <textarea name="other_desire_act" rows="3" style="width:100%"><?php echo (!empty($user_data['other_desire_act'])?$user_data['other_desire_act']:'');?></textarea>
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
                <textarea name="clean_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['clean_help_with'])?$user_data['clean_help_with']:'');?></textarea>
                <input type="text" name="clean_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['clean_help_given'])?$user_data['clean_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Bäddning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="bed_self" value="yes" /><span>Ja</span>
                <input type="radio" name="bed_self" value="no" /><span>Nej</span>
                <textarea name="bed_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['bed_help_with'])?$user_data['bed_help_with']:'');?></textarea>
                <input type="text" name="bed_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['bed_help_given'])?$user_data['bed_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Inköp</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="buy_self" value="yes" /><span>Ja</span>
                <input type="radio" name="buy_self" value="no" /><span>Nej</span>
                <textarea name="buy_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['buy_help_with'])?$user_data['buy_help_with']:'');?></textarea>
                <input type="text" name="buy_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['buy_help_given'])?$user_data['buy_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Tvätt</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="laundry_self" value="yes" /><span>Ja</span>
                <input type="radio" name="laundry_self" value="no" /><span>Nej</span>
                <textarea name="laundry_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['laundry_help_with'])?$user_data['laundry_help_with']:'');?></textarea>
                <input type="text" name="laundry_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['laundry_help_given'])?$user_data['laundry_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Vattna blommor</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="water_self" value="yes" /><span>Ja</span>
                <input type="radio" name="water_self" value="no" /><span>Nej</span>
                <textarea name="water_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['water_help_with'])?$user_data['water_help_with']:'');?></textarea>
                <input type="text" name="water_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['water_help_given'])?$user_data['water_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående bostaden:</p>
                <textarea name="other_desire_house" rows="3" style="width:100%"><?php echo (!empty($user_data['other_desire_house'])?$user_data['other_desire_house']:'');?></textarea>
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
                <textarea name="sight_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['sight_help_with'])?$user_data['sight_help_with']:'');?></textarea>
                <input type="text" name="sight_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['sight_help_given'])?$user_data['sight_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Hörsel</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="hear_self" value="yes" /><span>Ja</span>
                <input type="radio" name="hear_self" value="no" /><span>Nej</span>
                <textarea name="hear_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['hear_help_with'])?$user_data['hear_help_with']:'');?></textarea>
                <input type="text" name="hear_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['hear_help_given'])?$user_data['hear_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Kan tala</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="talk_self" value="yes" /><span>Ja</span>
                <input type="radio" name="talk_self" value="no" /><span>Nej</span>
                <textarea name="talk_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['talk_help_with'])?$user_data['talk_help_with']:'');?></textarea>
                <input type="text" name="talk_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['talk_help_given'])?$user_data['talk_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Ordförståelse</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="vocabulary_self" value="yes" /><span>Ja</span>
                <input type="radio" name="vocabulary_self" value="no" /><span>Nej</span>
                <textarea name="vocabulary_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['vocabulary_help_with'])?$user_data['vocabulary_help_with']:'');?></textarea>
                <input type="text" name="vocabulary_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['vocabulary_help_given'])?$user_data['vocabulary_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Annat</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="other_self" value="yes" /><span>Ja</span>
                <input type="radio" name="other_self" value="no" /><span>Nej</span>
                <textarea name="other_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['other_help_with'])?$user_data['other_help_with']:'');?></textarea>
                <input type="text" name="other_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['other_help_given'])?$user_data['other_help_given']:'');?>">
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
                <textarea name="sleep_help_with" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data['sleep_help_with'])?$user_data['sleep_help_with']:'');?></textarea>
                <input type="text" name="sleep_help_given" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data['sleep_help_given'])?$user_data['sleep_help_given']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående sömn/nattillsyn:</p>
                <textarea name="other_desire_sleep" rows="3" style="width:100%"><?php echo (!empty($user_data['other_desire_sleep'])?$user_data['other_desire_sleep']:'');?></textarea>
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

  //check by value

  var val_is_service = '<?php echo (!empty($user_data['is_service'])?$user_data['is_service']:'');?>';
  $('input:checkbox[name="is_service"][value="' + val_is_service + '"]').prop("checked", true);
  var val_is_firstcontact1 = '<?php echo (!empty($user_data['is_firstcontact1'])?$user_data['is_firstcontact1']:'');?>';
  $('input:radio[name=is_firstcontact1][value="' + val_is_firstcontact1 + '"]').attr('checked', 'checked');
  var val_is_firstcontact2 = '<?php echo (!empty($user_data['is_firstcontact2'])?$user_data['is_firstcontact2']:'');?>';
  $('input:radio[name=is_firstcontact2][value="' + val_is_firstcontact2 + '"]').attr('checked', 'checked');
  var val_is_key = '<?php echo (!empty($user_data['is_key'])?$user_data['is_key']:'');?>';
  $('input:radio[name=is_key][value="' + val_is_key + '"]').attr('checked', 'checked');
  var val_is_alarm = '<?php echo (!empty($user_data['is_alarm'])?$user_data['is_alarm']:'');?>';
  $('input:radio[name=is_alarm][value="' + val_is_alarm + '"]').attr('checked', 'checked');
  var val_is_key_alarm = '<?php echo (!empty($user_data['is_key_alarm'])?$user_data['is_key_alarm']:'');?>';
  $('input:radio[name=is_key_alarm][value="' + val_is_key_alarm + '"]').attr('checked', 'checked');
  var val_bath_self = '<?php echo (!empty($user_data['bath_self'])?$user_data['bath_self']:'');?>';
  $('input:radio[name=bath_self][value="' + val_bath_self + '"]').attr('checked', 'checked');
  var val_wash_self = '<?php echo (!empty($user_data['wash_self'])?$user_data['wash_self']:'');?>';
  $('input:radio[name=wash_self][value="' + val_wash_self + '"]').attr('checked', 'checked');
  var val_nail_self = '<?php echo (!empty($user_data['nail_self'])?$user_data['nail_self']:'');?>';
  $('input:radio[name=nail_self][value="' + val_nail_self + '"]').attr('checked', 'checked');
  var val_hair_self = '<?php echo (!empty($user_data['hair_self'])?$user_data['hair_self']:'');?>';
  $('input:radio[name=hair_self][value="' + val_hair_self + '"]').attr('checked', 'checked');
  var val_incontinence_self = '<?php echo (!empty($user_data['incontinence_self'])?$user_data['incontinence_self']:'');?>';
  $('input:radio[name=incontinence_self][value="' + val_incontinence_self + '"]').attr('checked', 'checked');
  var val_dress_self = '<?php echo (!empty($user_data['dress_self'])?$user_data['dress_self']:'');?>';
  $('input:radio[name=dress_self][value="' + val_dress_self + '"]').attr('checked', 'checked');
  var val_contact_self = '<?php echo (!empty($user_data['contact_self'])?$user_data['contact_self']:'');?>';
  $('input:radio[name=contact_self][value="' + val_contact_self + '"]').attr('checked', 'checked');
  var val_interest_self = '<?php echo (!empty($user_data['interest_self'])?$user_data['interest_self']:'');?>';
  $('input:radio[name=interest_self][value="' + val_interest_self + '"]').attr('checked', 'checked');
  var val_act_self = '<?php echo (!empty($user_data['act_self'])?$user_data['act_self']:'');?>';
  $('input:radio[name=act_self][value="' + val_act_self + '"]').attr('checked', 'checked');
  var val_clean_self = '<?php echo (!empty($user_data['clean_self'])?$user_data['clean_self']:'');?>';
  $('input:radio[name=clean_self][value="' + val_clean_self + '"]').attr('checked', 'checked');
  var val_bed_self = '<?php echo (!empty($user_data['bed_self'])?$user_data['bed_self']:'');?>';
  $('input:radio[name=bed_self][value="' + val_bed_self + '"]').attr('checked', 'checked');
  var val_buy_self = '<?php echo (!empty($user_data['buy_self'])?$user_data['buy_self']:'');?>';
  $('input:radio[name=buy_self][value="' + val_buy_self + '"]').attr('checked', 'checked');
  var val_laundry_self = '<?php echo (!empty($user_data['laundry_self'])?$user_data['laundry_self']:'');?>';
  $('input:radio[name=laundry_self][value="' + val_laundry_self + '"]').attr('checked', 'checked');
  var val_water_self = '<?php echo (!empty($user_data['water_self'])?$user_data['water_self']:'');?>';
  $('input:radio[name=water_self][value="' + val_water_self + '"]').attr('checked', 'checked');
  var val_sight_self = '<?php echo (!empty($user_data['sight_self'])?$user_data['sight_self']:'');?>';
  $('input:radio[name=sight_self][value="' + val_sight_self + '"]').attr('checked', 'checked');
  var val_hear_self = '<?php echo (!empty($user_data['hear_self'])?$user_data['hear_self']:'');?>';
  $('input:radio[name=hear_self][value="' + val_hear_self + '"]').attr('checked', 'checked');
  var val_talk_self = '<?php echo (!empty($user_data['talk_self'])?$user_data['talk_self']:'');?>';
  $('input:radio[name=talk_self][value="' + val_talk_self + '"]').attr('checked', 'checked');
  var val_vocabulary_self = '<?php echo (!empty($user_data['vocabulary_self'])?$user_data['vocabulary_self']:'');?>';
  $('input:radio[name=vocabulary_self][value="' + val_vocabulary_self + '"]').attr('checked', 'checked');
  var val_other_self = '<?php echo (!empty($user_data['other_self'])?$user_data['other_self']:'');?>';
  $('input:radio[name=other_self][value="' + val_other_self + '"]').attr('checked', 'checked');
  var val_sleep_self = '<?php echo (!empty($user_data['sleep_self'])?$user_data['sleep_self']:'');?>';
  $('input:radio[name=sleep_self][value="' + val_sleep_self + '"]').attr('checked', 'checked');

  if ($('input:radio[name=bath_self]:checked').val() == "yes") {
    $('textarea[name=bath_help_with]').hide();
    $('input[name=bath_help_given]').hide();
  } else {
    $('textarea[name=bath_help_with]').show();
    $('input[name=bath_help_given]').show();
  }
  if ($('input:radio[name=wash_self]:checked').val() == "yes") {
    $('textarea[name=wash_help_with]').hide();
    $('input[name=wash_help_given]').hide();
  } else {
    $('textarea[name=wash_help_with]').show();
    $('input[name=wash_help_given]').show();
  }
  if ($('input:radio[name=nail_self]:checked').val() == "yes") {
    $('textarea[name=nail_help_with]').hide();
    $('input[name=nail_help_given]').hide();
  } else {
    $('textarea[name=nail_help_with]').show();
    $('input[name=nail_help_given]').show();
  }
  if ($('input:radio[name=hair_self]:checked').val() == "yes") {
    $('textarea[name=hair_help_with]').hide();
    $('input[name=hair_help_given]').hide();
  } else {
    $('textarea[name=hair_help_with]').show();
    $('input[name=hair_help_given]').show();
  }
  if ($('input:radio[name=incontinence_self]:checked').val() == "yes") {
    $('textarea[name=incontinence_help_with]').hide();
    $('input[name=incontinence_help_given]').hide();
  } else {
    $('textarea[name=incontinence_help_with]').show();
    $('input[name=incontinence_help_given]').show();
  }
  if ($('input:radio[name=dress_self]:checked').val() == "yes") {
    $('textarea[name=dress_help_with]').hide();
    $('input[name=dress_help_given]').hide();
  } else {
    $('textarea[name=dress_help_with]').show();
    $('input[name=dress_help_given]').show();
  }
  if ($('input:radio[name=contact_self]:checked').val() == "yes") {
    $('textarea[name=contact_help_with]').hide();
    $('input[name=contact_help_given]').hide();
  } else {
    $('textarea[name=contact_help_with]').show();
    $('input[name=contact_help_given]').show();
  }
  if ($('input:radio[name=interest_self]:checked').val() == "yes") {
    $('textarea[name=interest_help_with]').hide();
    $('input[name=interest_help_given]').hide();
  } else {
    $('textarea[name=interest_help_with]').show();
    $('input[name=interest_help_given]').show();
  }
  if ($('input:radio[name=act_self]:checked').val() == "yes") {
    $('textarea[name=act_help_with]').hide();
    $('input[name=act_help_given]').hide();
  } else {
    $('textarea[name=act_help_with]').show();
    $('input[name=act_help_given]').show();
  }
  if ($('input:radio[name=clean_self]:checked').val() == "yes") {
    $('textarea[name=clean_help_with]').hide();
    $('input[name=clean_help_given]').hide();
  } else {
    $('textarea[name=clean_help_with]').show();
    $('input[name=clean_help_given]').show();
  }
  if ($('input:radio[name=bed_self]:checked').val() == "yes") {
    $('textarea[name=bed_help_with]').hide();
    $('input[name=bed_help_given]').hide();
  } else {
    $('textarea[name=bed_help_with]').show();
    $('input[name=bed_help_given]').show();
  }
  if ($('input:radio[name=buy_self]:checked').val() == "yes") {
    $('textarea[name=buy_help_with]').hide();
    $('input[name=buy_help_given]').hide();
  } else {
    $('textarea[name=buy_help_with]').show();
    $('input[name=buy_help_given]').show();
  }
  if ($('input:radio[name=laundry_self]:checked').val() == "yes") {
    $('textarea[name=laundry_help_with]').hide();
    $('input[name=laundry_help_given]').hide();
  } else {
    $('textarea[name=laundry_help_with]').show();
    $('input[name=laundry_help_given]').show();
  }
  if ($('input:radio[name=water_self]:checked').val() == "yes") {
    $('textarea[name=water_help_with]').hide();
    $('input[name=water_help_given]').hide();
  } else {
    $('textarea[name=water_help_with]').show();
    $('input[name=water_help_given]').show();
  }
  if ($('input:radio[name=sight_self]:checked').val() == "yes") {
    $('textarea[name=sight_help_with]').hide();
    $('input[name=sight_help_given]').hide();
  } else {
    $('textarea[name=sight_help_with]').show();
    $('input[name=sight_help_given]').show();
  }
  if ($('input:radio[name=hear_self]:checked').val() == "yes") {
    $('textarea[name=hear_help_with]').hide();
    $('input[name=hear_help_given]').hide();
  } else {
    $('textarea[name=hear_help_with]').show();
    $('input[name=hear_help_given]').show();
  }
  if ($('input:radio[name=talk_self]:checked').val() == "yes") {
    $('textarea[name=talk_help_with]').hide();
    $('input[name=talk_help_given]').hide();
  } else {
    $('textarea[name=talk_help_with]').show();
    $('input[name=talk_help_given]').show();
  }
  if ($('input:radio[name=vocabulary_self]:checked').val() == "yes") {
    $('textarea[name=vocabulary_help_with]').hide();
    $('input[name=vocabulary_help_given]').hide();
  } else {
    $('textarea[name=vocabulary_help_with]').show();
    $('input[name=vocabulary_help_given]').show();
  }
  if ($('input:radio[name=other_self]:checked').val() == "yes") {
    $('textarea[name=other_help_with]').hide();
    $('input[name=other_help_given]').hide();
  } else {
    $('textarea[name=other_help_with]').show();
    $('input[name=other_help_given]').show();
  }
  if ($('input:radio[name=sleep_self]:checked').val() == "yes") {
    $('textarea[name=sleep_help_with]').hide();
    $('input[name=sleep_help_given]').hide();
  } else {
    $('textarea[name=sleep_help_with]').show();
    $('input[name=sleep_help_given]').show();
  }

  $("input:radio[name=bath_self]").click(function () {
    if ($('input:radio[name=bath_self]:checked').val() == "yes") {
      $('textarea[name=bath_help_with]').hide();
      $('input[name=bath_help_given]').hide();
      $('textarea[name=bath_help_with]').val('');
      $('input[name=bath_help_given]').val('');
    } else {
      $('textarea[name=bath_help_with]').show();
      $('input[name=bath_help_given]').show();
    }
  });
  $("input:radio[name=wash_self]").click(function () {
    if ($('input:radio[name=wash_self]:checked').val() == "yes") {
      $('textarea[name=wash_help_with]').hide();
      $('input[name=wash_help_given]').hide();
      $('textarea[name=wash_help_with]').val('');
      $('input[name=wash_help_given]').val('');
    } else {
      $('textarea[name=wash_help_with]').show();
      $('input[name=wash_help_given]').show();
    }
  });
  $("input:radio[name=nail_self]").click(function () {
    if ($('input:radio[name=nail_self]:checked').val() == "yes") {
      $('textarea[name=nail_help_with]').hide();
      $('input[name=nail_help_given]').hide();
      $('textarea[name=nail_help_with]').val('');
      $('input[name=nail_help_given]').val('');
    } else {
      $('textarea[name=nail_help_with]').show();
      $('input[name=nail_help_given]').show();
    }
  });
  $("input:radio[name=hair_self]").click(function () {
    if ($('input:radio[name=hair_self]:checked').val() == "yes") {
      $('textarea[name=hair_help_with]').hide();
      $('input[name=hair_help_given]').hide();
      $('textarea[name=hair_help_with]').val('');
      $('input[name=hair_help_given]').val('');
    } else {
      $('textarea[name=hair_help_with]').show();
      $('input[name=hair_help_given]').show();
    }
  });
  $("input:radio[name=incontinence_self]").click(function () {
    if ($('input:radio[name=incontinence_self]:checked').val() == "yes") {
      $('textarea[name=incontinence_help_with]').hide();
      $('input[name=incontinence_help_given]').hide();
      $('textarea[name=incontinence_help_with]').val('');
      $('input[name=incontinence_help_given]').val('');
    } else {
      $('textarea[name=incontinence_help_with]').show();
      $('input[name=incontinence_help_given]').show();
    }
  });
  $("input:radio[name=dress_self]").click(function () {
    if ($('input:radio[name=dress_self]:checked').val() == "yes") {
      $('textarea[name=dress_help_with]').hide();
      $('input[name=dress_help_given]').hide();
      $('textarea[name=dress_help_with]').val('');
      $('input[name=dress_help_given]').val('');
    } else {
      $('textarea[name=dress_help_with]').show();
      $('input[name=dress_help_given]').show();
    }
  });
  $("input:radio[name=contact_self]").click(function () {
    if ($('input:radio[name=contact_self]:checked').val() == "yes") {
      $('textarea[name=contact_help_with]').hide();
      $('input[name=contact_help_given]').hide();
      $('textarea[name=contact_help_with]').val('');
      $('input[name=contact_help_given]').val('');
    } else {
      $('textarea[name=contact_help_with]').show();
      $('input[name=contact_help_given]').show();
    }
  });
  $("input:radio[name=interest_self]").click(function () {
    if ($('input:radio[name=interest_self]:checked').val() == "yes") {
      $('textarea[name=interest_help_with]').hide();
      $('input[name=interest_help_given]').hide();
      $('textarea[name=interest_help_with]').val('');
      $('input[name=interest_help_given]').val('');
    } else {
      $('textarea[name=interest_help_with]').show();
      $('input[name=interest_help_given]').show();
    }
  });
  $("input:radio[name=act_self]").click(function () {
    if ($('input:radio[name=act_self]:checked').val() == "yes") {
      $('textarea[name=act_help_with]').hide();
      $('input[name=act_help_given]').hide();
      $('textarea[name=act_help_with]').val('');
      $('input[name=act_help_given]').val('');
    } else {
      $('textarea[name=act_help_with]').show();
      $('input[name=act_help_given]').show();
    }
  });
  $("input:radio[name=clean_self]").click(function () {
    if ($('input:radio[name=clean_self]:checked').val() == "yes") {
      $('textarea[name=clean_help_with]').hide();
      $('input[name=clean_help_given]').hide();
      $('textarea[name=clean_help_with]').val('');
      $('input[name=clean_help_given]').val('');
    } else {
      $('textarea[name=clean_help_with]').show();
      $('input[name=clean_help_given]').show();
    }
  });
  $("input:radio[name=bed_self]").click(function () {
    if ($('input:radio[name=bed_self]:checked').val() == "yes") {
      $('textarea[name=bed_help_with]').hide();
      $('input[name=bed_help_given]').hide();
      $('textarea[name=bed_help_with]').val('');
      $('input[name=bed_help_given]').val('');
    } else {
      $('textarea[name=bed_help_with]').show();
      $('input[name=bed_help_given]').show();
    }
  });
  $("input:radio[name=buy_self]").click(function () {
    if ($('input:radio[name=buy_self]:checked').val() == "yes") {
      $('textarea[name=buy_help_with]').hide();
      $('input[name=buy_help_given]').hide();
      $('textarea[name=buy_help_with]').val('');
      $('input[name=buy_help_given]').val('');
    } else {
      $('textarea[name=buy_help_with]').show();
      $('input[name=buy_help_given]').show();
    }
  });
  $("input:radio[name=laundry_self]").click(function () {
    if ($('input:radio[name=laundry_self]:checked').val() == "yes") {
      $('textarea[name=laundry_help_with]').hide();
      $('input[name=laundry_help_given]').hide();
      $('textarea[name=laundry_help_with]').val('');
      $('input[name=laundry_help_given]').val('');
    } else {
      $('textarea[name=laundry_help_with]').show();
      $('input[name=laundry_help_given]').show();
    }
  });
  $("input:radio[name=water_self]").click(function () {
    if ($('input:radio[name=water_self]:checked').val() == "yes") {
      $('textarea[name=water_help_with]').hide();
      $('input[name=water_help_given]').hide();
      $('textarea[name=water_help_with]').val('');
      $('input[name=water_help_given]').val('');
    } else {
      $('textarea[name=water_help_with]').show();
      $('input[name=water_help_given]').show();
    }
  });
  $("input:radio[name=sight_self]").click(function () {
    if ($('input:radio[name=sight_self]:checked').val() == "yes") {
      $('textarea[name=sight_help_with]').hide();
      $('input[name=sight_help_given]').hide();
      $('textarea[name=sight_help_with]').val('');
      $('input[name=sight_help_given]').val('');
    } else {
      $('textarea[name=sight_help_with]').show();
      $('input[name=sight_help_given]').show();
    }
  });
  $("input:radio[name=hear_self]").click(function () {
    if ($('input:radio[name=hear_self]:checked').val() == "yes") {
      $('textarea[name=hear_help_with]').hide();
      $('input[name=hear_help_given]').hide();
      $('textarea[name=hear_help_with]').val('');
      $('input[name=hear_help_given]').val('');
    } else {
      $('textarea[name=hear_help_with]').show();
      $('input[name=hear_help_given]').show();
    }
  });
  $("input:radio[name=talk_self]").click(function () {
    if ($('input:radio[name=talk_self]:checked').val() == "yes") {
      $('textarea[name=talk_help_with]').hide();
      $('input[name=talk_help_given]').hide();
      $('textarea[name=talk_help_with]').val('');
      $('input[name=talk_help_given]').val('');
    } else {
      $('textarea[name=talk_help_with]').show();
      $('input[name=talk_help_given]').show();
    }
  });
  $("input:radio[name=vocabulary_self]").click(function () {
    if ($('input:radio[name=vocabulary_self]:checked').val() == "yes") {
      $('textarea[name=vocabulary_help_with]').hide();
      $('input[name=vocabulary_help_given]').hide();
      $('textarea[name=vocabulary_help_with]').val('');
      $('input[name=vocabulary_help_given]').val('');
    } else {
      $('textarea[name=vocabulary_help_with]').show();
      $('input[name=vocabulary_help_given]').show();
    }
  });
  $("input:radio[name=other_self]").click(function () {
    if ($('input:radio[name=other_self]:checked').val() == "yes") {
      $('textarea[name=other_help_with]').hide();
      $('input[name=other_help_given]').hide();
      $('textarea[name=other_help_with]').val('');
      $('input[name=other_help_given]').val('');
    } else {
      $('textarea[name=other_help_with]').show();
      $('input[name=other_help_given]').show();
    }
  });
  $("input:radio[name=sleep_self]").click(function () {
    if ($('input:radio[name=sleep_self]:checked').val() == "yes") {
      $('textarea[name=sleep_help_with]').hide();
      $('input[name=sleep_help_given]').hide();
      $('textarea[name=sleep_help_with]').val('');
      $('input[name=sleep_help_given]').val('');
    } else {
      $('textarea[name=sleep_help_with]').show();
      $('input[name=sleep_help_given]').show();
    }
  });
</script>