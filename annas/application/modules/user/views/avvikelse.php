<style type="text/css">
  div.box-content-input {
    margin: 0 0 10px;
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
          <form action="<?php echo base_url().'user/submit_customer_avvikelse/'.$user_data['id']; ?>" method="post">
        <?php } else { ?>
        <form action="<?php echo base_url().'user/submit_customer_avvikelse/'; ?>" method="post">
        <?php } ?>
            <div class="box-header with-border">
              <h3 class="box-title">AVVIKELSE</h3>
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
                <p class="input_option">DATUM</p>
                <input type="text" id="datepicker" name="date" size="20" value="<?php echo (!empty($user_data['date'])?$user_data['date']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">NAMN</p><input type="text" name="customer_name" size="20" value="<?php echo (!empty($user_data['customer_name'])?$user_data['customer_name']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">PERSONNUMMER</p><input type="text" name="code_number" size="20" value="<?php echo (!empty($user_data['code_number'])?$user_data['code_number']:'');?>">
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Vem upptäckte incidenten?</p><input type="text" id="finder" name="finder" size="20" value="<?php echo (!empty($user_data['finder'])?$user_data['finder']:$usr_data['name']);?>" disabled>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Händelseförloppet:</p><textarea name="course" rows="10" style="width:100%"><?php echo (!empty($user_data['course'])?$user_data['course']:'');?></textarea>
              </div>
              <div class="col-xs-12 box-content-input">
                <p class="input_option">Eventuella åtgärdsförslag:</p><textarea name="suggestion" rows="3" style="width:100%"><?php echo (!empty($user_data['suggestion'])?$user_data['suggestion']:'');?></textarea>
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