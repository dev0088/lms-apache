<style type="text/css">
  div.box-content-input {
    margin: 0 0 10px;
  }
  .input_option {
    font-weight: 600;
  }
</style>
<div class="content-wrapper">
<!-- Content Header (Page  header) -->
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
          <form action="<?php echo base_url().'socialjournal/submit/'.$user_data['id']; ?>" method="post">
        <?php } else { ?>
        <form action="<?php echo base_url().'socialjournal/submit/'; ?>" method="post">
        <?php } ?>
            <div class="box-header with-border">
              <h3 class="box-title" style="color: #00c0ef!important;font-weight:600;">SOCIAL JOURNAL FÖR GENOMFÖRANDE</h3>
              <div class="box-tools">
                <button type="submit" class="btn-sm  btn btn-success modalButtonUser"><i class="glyphicon glyphicon-plus"></i> Spara</button>

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
                <p class="input_option">• Typ av händelse</p><textarea name="type_event" rows="2" style="width:100%"><?php echo (!empty($user_data['type_event'])?$user_data['type_event']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">• Händelsebeskrivning (beskriv detaljerat vad sominträffat)</p><textarea name="course" rows="8" style="width:100%"><?php echo (!empty($user_data['course'])?$user_data['course']:'');?></textarea>
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