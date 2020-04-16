<style type="text/css">
  div.box-content-input {
    margin: 0 0 10px;
  }
  .input_option {
    font-weight: 600;
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
          <form action="<?php echo base_url().'avvikelse/submit/'.$user_data['id']; ?>" method="post">
        <?php } else { ?>
        <form action="<?php echo base_url().'avvikelse/submit/'; ?>" method="post">
        <?php } ?>
            <div class="box-header with-border">
              <h3 class="box-title" style="color: #00c0ef!important;font-weight:600;">Avvikelserapportering SoL</h3>
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
                <p class="input_option">Kundnamn</p><input type="text" name="customer_name" size="30" value="<?php echo (!empty($user_data['customer_name'])?$user_data['customer_name']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Zon</p><input type="text" name="zone" size="30" value="<?php echo (!empty($user_data['zone'])?$user_data['zone']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Personal</p><input type="text" id="usr_name" name="usr_name" size="30" value="<?php echo (!empty($user_data['usr_name'])?$user_data['usr_name']:$usr_data['name']);?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Alternativt flera brukare berörda av händelsen</p><input type="text" name="several_users" size="30" value="<?php echo (!empty($user_data['several_users'])?$user_data['several_users']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Händelsedatum</p>
                <input type="text" id="datepicker" name="date" size="30" value="<?php echo (!empty($user_data['date'])?$user_data['date']:'');?>">
                <p style="color:gray;">* Cliquez ou appuyez ici pour entrer une date.</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Händelsetid</p><input type="time" name="time" size="15" value="<?php echo (!empty($user_data['time'])?$user_data['time']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Händelseplats*</p><input type="text" name="location" size="30" value="<?php echo (!empty($user_data['location'])?$user_data['location']:'');?>">
                <p style="color:gray;">* Ange var – ordinärt boende, särskilt boende, utomhus eller annan plats</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Händelsebeskrivning (beskriv detaljerat vad sominträffat)</p><textarea name="course" rows="8" style="width:100%"><?php echo (!empty($user_data['course'])?$user_data['course']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Typ av händelse</p>
                <input type="checkbox" class="event_checkbox" id="event1" name="event1" value="Brist i bemötande">
                <label for="event1"> Brist i bemötande</label><br>
                <input type="checkbox" class="event_checkbox" id="event2" name="event2" value="Bristande tillgänglighet">
                <label for="event2"> Bristande tillgänglighet</label><br>
                <input type="checkbox" class="event_checkbox" id="event3" name="event3" value="Brist i/utebliven handläggning eller dokumentation (HSL, SOL, LSS)">
                <label for="event3"> Brist i/utebliven handläggning eller dokumentation (HSL, SOL, LSS)</label><br>
                <input type="checkbox" class="event_checkbox" id="event4" name="event4" value="Brist i/utebliven insats">
                <label for="event4"> Brist i/utebliven insats</label><br>
                <input type="checkbox" class="event_checkbox" id="event5" name="event5" value="Brist i/utebliven vårdåtgärd">
                <label for="event5"> Brist i/utebliven vårdåtgärd</label><br>
                <input type="checkbox" class="event_checkbox" id="event6" name="event6" value="Brist gällande samtycke från brukare">
                <label for="event6"> Brist gällande samtycke från brukare</label><br>
                <input type="checkbox" class="event_checkbox" id="event7" name="event7" value="Brist i information, kommunikation, samverken">
                <label for="event7"> Brist i information, kommunikation, samverken</label><br>
                <input type="checkbox" class="event_checkbox" id="event8" name="event8" value="Läkemedelshändelse">
                <label for="event8"> Läkemedelshändelse</label><br>
                <input type="checkbox" class="event_checkbox" id="event9" name="event9" value="Fall">
                <label for="event9"> Fall</label><br>
                <input type="checkbox" class="event_checkbox" id="event10" name="event10" value="Vård- och omsorgsskada">
                <label for="event10"> Vård- och omsorgsskada</label><br>
                <input type="checkbox" class="event_checkbox" id="event11" name="event11" value="Händelse med produkter och/eller teknik">
                <label for="event11"> Händelse med produkter och/eller teknik</label><br>
                <input type="checkbox" class="event_checkbox" id="event12" name="event12" value="Bruten sekretess">
                <label for="event12"> Bruten sekretess</label><br>
                <input type="checkbox" class="event_checkbox" id="event13" name="event13" value="Fysiskt, psykiskt, sexuellt eller ekonomiskt övergrepp">
                <label for="event13"> Fysiskt, psykiskt, sexuellt eller ekonomiskt övergrepp</label><br>
                <input type="checkbox" class="event_checkbox" id="event14" name="event14" value="Misstanke om självmord">
                <label for="event14"> Misstanke om självmord</label><br>
                <input type="checkbox" class="event_checkbox_annat" id="event15" name="event15" value="Annat">
                <label for="event15"> Annat</label><br>
                <textarea name="type_event" id="event_textarea" rows="2" style="width:100%"><?php echo (!empty($user_data['type_event'])?$user_data['type_event']:'');?></textarea>

              </div>              
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Ev. direkt utförd handling för att undvika mer skada</p><textarea name="suggestion" rows="5" style="width:100%"><?php echo (!empty($user_data['suggestion'])?$user_data['suggestion']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input" style="background: lightblue;">
                <p class="input_option" style="background: lightblue;padding-top:5px;">Verksamhetschefs åtgärder</p>
              </div>
              <div class="col-xs-12 box-content-input">
                <span>Employé
                <select id="vice_admin" name="vice_admin">
                </select>
                </span>
                <textarea id="admin_remark" name="admin_remark" rows="5" style="width:100%"><?php echo (!empty($user_data['admin_remark'])?$user_data['admin_remark']:'');?></textarea>
              </div>
              
              <!-- <div class="col-xs-12 box-content-input">
                <p class="input_option">Händelseförloppet:</p><textarea name="course" rows="10" style="width:100%"><?php echo (!empty($user_data['course'])?$user_data['course']:'');?></textarea>
              </div> -->
              <!-- <div class="col-xs-12 box-content-input">
                <p class="input_option">Eventuella åtgärdsförslag:</p><textarea name="suggestion" rows="3" style="width:100%"><?php echo (!empty($user_data['suggestion'])?$user_data['suggestion']:'');?></textarea>
              </div> -->

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
    $('#event_textarea').hide();
    var str_checked_events = "<?php echo (!empty($user_data['checked_events'])?$user_data['checked_events']:'');?>";
    if(str_checked_events.length > 1) {

      str_checked_events = str_checked_events.substring(0, str_checked_events.length - 1);
      var array_events = str_checked_events.split(';');
      var i;
      for(i = 0; i < array_events.length; i ++) {
        $("#"+array_events[i]).prop("checked", true);
      }
      if ($('#event15').is(':checked')) {
        $('#event_textarea').show();
      }
    }

    
    var user_type = '<?php echo $this->session->userdata('user_details')[0]->user_type; ?>';
    // alert(user_type);
    if(user_type != 'admin') {
      
      $("#vice_admin").attr('disabled','disabled');
      $("#admin_remark").prop('disabled', true);
    }

    $.ajax({
        type: "POST",
        url: '<?php echo base_url()."avvikelse/get_users_list"; ?>',
        dataType: "json", 
        success: function (data) {
          $("#vice_admin").append('<option hidden disabled selected value>- Sélectionner -</option>');
          for(var x=0; x < data.length; x++){
              $("#vice_admin").append('<option value=' + data[x]['users_id'] + '>' + data[x]['name'] + '</option>');
          }

          var vice_admin_sel = '<?php echo (!empty($user_data["vice_admin"])?$user_data["vice_admin"]:'');?>';
          if( (vice_admin_sel.length > 0) ){
            $("#vice_admin option[value='"+vice_admin_sel+"']").prop("selected", true);
          }
        }
    });

    $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
    
    $("#event15").change(function () {
      if(this.checked) {
        $('#event_textarea').show();
      } else {
        $('#event_textarea').hide();
        $('#event_textarea').val('');
      }
    });
  });
</script>