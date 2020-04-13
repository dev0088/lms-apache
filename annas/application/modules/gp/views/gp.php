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
                <p class="input_option">Datum</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Kundnamn</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Personal</p><input type="text" id="usr_name" name="usr_name" size="30" value="<?php echo (!empty($user_data['usr_name'])?$user_data['usr_name']:$usr_data['name']);?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
                <h4>Närstående/god man</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Relation</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Förstahandskontakt</p>
                <input type="radio" name="contact_radio1" value="Ja" /><span>Ja</span>
                <input type="radio" name="contact_radio1" value="Nej" /><span>Nej</span>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Relation</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Förstahandskontakt</p>
                <input type="radio" name="contact_radio2" value="Ja" /><span>Ja</span>
                <input type="radio" name="contact_radio2" value="Nej" /><span>Nej</span>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Adress</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Postadress</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">God man</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
              <!-- <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue; padding-top: 15px;"> -->
                <h4>Viktiga kontakter</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Vårdcentral/Läkare</p><input type="text" name="30" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="30" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Omvårdnadsansvarig sjuksköterska:   KSK/ DSK</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Kontaktpersonal</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Övrig</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Övrig</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Telefonnummer</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Färdtjänst</p>
                <div>
                <input type="checkbox" class="event_checkbox" id="option1" name="event_checkbox" value="Ja">
                <label for="option1"> Ja</label><br>
                <input type="checkbox" class="event_checkbox" id="option2" name="event_checkbox" value="Ledsagare">
                <label for="option2"> Ledsagare</label><br>
                <input type="checkbox" class="event_checkbox" id="option3" name="event_checkbox" value="Nej">
                <label for="option3"> Nej</label><br>
                </div>
                <!-- <input type="radio" name="service_radio1" value="Ja" /><span>Ja</span>
                <input type="radio" name="service_radio1" value="Ledsagare" /><span>Ledsagare</span>
                <input type="radio" name="service_radio1" value="Nej" /><span>Nej</span> -->
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Nyckel</p>
                <input type="radio" name="key_radio1" value="Ja" /><span>Ja</span>
                <input type="radio" name="key_radio1" value="Nej" /><span>Nej</span>
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
                <h4>Viktiga kontakter</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Trygghetslarm</p>
                <input type="radio" name="alarm_radio1" value="Ja" /><span>Ja</span>
                <input type="radio" name="alarm_radio1" value="Nej" /><span>Nej</span>
              </div>

              <div class="col-xs-12 box-content-input" style="border-top: 1px solid lightblue;"></div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Nyckel</p>
                <input type="radio" name="key_radio2" value="Ja" /><span>Ja</span>
                <input type="radio" name="key_radio2" value="Nej" /><span>Nej</span>
              </div>

              <div class="col-xs-12 box-content-input" style="background: lightblue;">
                <h4>OMSORGSPLANERING</h4>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">1. Bakgrund (Uppväxt, yrkesliv, familj, viktiga händelser.)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">2. Social situation, nätverk, intressen (Civilstånd, viktiga kontakter, vänner, intressen)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">3. Hälsa (Konsekvens av sjukdom, klarar/klarar inte pga sjukdom, allergi, överkänslighet)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">4. Omsorgsinsatser (Vilka omsorgsinsatser har den enskilde)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">5. Hälso‐ och sjukvårdsinsatser (Delegerade sjukvårdsinsatser)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">6. Särskilda behov/önskemål (Vardagsrutiner, vanor, aktiviteter, få sin vardag som man är van)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">7. Mål och arbetsmetoder (Bevarande, utvecklande, rehabiliterande, guldkant = att få/återfå meningsfullhet, accepterande av tillbakagång. Genom vilka metoder kan man nå målen.)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">8. Uppföljning/Utvärdering (Up)</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
              </div>

              <div class="col-xs-12 box-content-input">
                <p class="input_option">Ansvarig för upprättelse av plan:</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Namn</p><input type="text" name="" size="30" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
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
                <input type="radio" name="self_radio1" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio1" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Tvättar sig</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio2" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio2" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Nagelvård</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio3" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio3" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Hårvård och Rakning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio4" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio4" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Inkontinens‐ hjälpmedel</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio5" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio5" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• På‐ och avklädning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio6" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio6" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>

              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående hygien:</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
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
                <input type="radio" name="self_radio7" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio7" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Intressen</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio8" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio8" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Aktiviteter</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio9" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio9" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående aktivering, intressen och sociala kontakter:</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
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
                <input type="radio" name="self_radio10" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio10" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Bäddning</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio11" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio11" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Inköp</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio12" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio12" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Tvätt</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio13" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio13" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Vattna blommor</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio14" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio14" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående bostaden:</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
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
                <input type="radio" name="self_radio15" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio15" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Hörsel</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio16" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio16" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Kan tala</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio17" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio17" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Ordförståelse</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio18" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio18" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Annat</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Klarar själv</p>
                <input type="radio" name="self_radio19" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio19" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
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
                <input type="radio" name="self_radio20" value="Ja" /><span>Ja</span>
                <input type="radio" name="self_radio20" value="Nej" /><span>Nej</span>
                <textarea name="" rows="3" style="width:100%" placeholder="Behöver hjälp med – hur ges hjälpen"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
                <input type="text" name="" size="30" placeholder="När ges hjälpen" value="<?php echo (!empty($user_data[''])?$user_data['']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Särskilda behov och önskemål angående sömn/nattillsyn:</p>
                <textarea name="" rows="3" style="width:100%"><?php echo (!empty($user_data[''])?$user_data['']:'');?></textarea>
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