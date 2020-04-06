<div class="content-wrapper">
<!-- Content Header (Page header) -->
<!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <?php
        // Start the buffering //
        ob_start();
        ?>
        <div class="box box-success">
            <div class="box-header with-border">
              <div class="box-tools">
                <!-- <button type="submit" class="btn-sm  btn btn-success modalButtonUser"><i class="glyphicon glyphicon-plus"></i> Add Customer</button> -->
              </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <div class="col-xs-12" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <img src="<?php echo base_url().'assets/images/logo.png'; ?>" style="display: inline-block;">
                <h4 style="display: inline-block; margin-right: 15%; padding-top: 50px; font-weight: 600;">DATUM: <span style="font-weight: normal;"><?php echo (isset($user_data['date'])?$user_data['date']:'');?></span></h4>
              </div>
              <div class="col-xs-12" style="text-align:center; margin-bottom: 10px;">
                <h1>AVVIKELSE</h1>
              </div>
              <div class="col-xs-12" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <h4 style="display: inline-block; font-weight: 600;">NAMN: <span style="font-weight: normal;"><?php echo (isset($user_data['customer_name'])?$user_data['customer_name']:'');?></span></h4>
                <h4 style="display: inline-block; margin-right: 25%; font-weight: 600;">PERSONNUMMER: <span style="font-weight: normal;"><?php echo (isset($user_data['code_number'])?$user_data['code_number']:'');?></span></h4>
              </div>
              <div class="col-xs-12" style="margin-bottom: 10px;">
                <h4 style="font-weight: 600;">Vem upptäckte incidenten?</h4>
                <h4 style="font-weight: normal;"><?php echo (isset($user_data['finder'])?$user_data['finder']:'');?></h4>
              </div>
              <div class="col-xs-12" style="min-height: 200px; margin-bottom: 10px;">
                <h4 style="font-weight: 600;">Händelseförloppet:</h4>
                <h4 style="font-weight: normal;"><?php echo (isset($user_data['course'])?$user_data['course']:'');?></h4>
              </div>
              <div class="col-xs-12" style="min-height: 100px; margin-bottom: 10px;">
                <h4 style="font-weight: 600;">Eventuella åtgärdsförslag:</h4>
                <h4 style="font-weight: normal;"><?php echo (isset($user_data['suggestion'])?$user_data['suggestion']:'');?></h4>
              </div>
              <div class="col-xs-12" style="display: flex; justify-content: space-between; margin-bottom: 10px; min-height:50px;">
                <span style="display: inline-block; margin-left: 15%;">
                <h4 style="margin: 0px; font-style: italic;">UNDERSKRIFT</h4>
                <h4 style="margin: 0px; font-style: italic;">PERSONAL:</h4>
                </span>
                <span style="display: inline-block; margin-right: 25%; width:200px;">
                <h4 style="margin: 0px; font-style: italic;">UNDERSKRIFT</h4>
                <h4 style="margin: 0px; font-style: italic;">SAMORDNARE/ARBETSLEDARE:</h4>
                </span>
              </div>
              <div class="col-xs-12" style="display: flex; justify-content: space-between; margin-bottom: 10px; min-height:120px;">
                <span style="display: inline-block; margin-left: 15%;">
                <h4 style="margin: 0px; font-style: italic;">NAMNFÖRTYDLIGANDE:</h4>
                </span>
                <span style="display: inline-block; margin-right: 25%; width:200px;">
                <h4 style="margin: 0px; font-style: italic;">NAMNFÖRTYDLIGANDE:</h4>
                </span>
              </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <?php
        // Get the content that is in the buffer and put it in your file //
        file_put_contents($user_data['customer_name']."_".$user_data['date']."_".$user_data['finder'].".html", ob_get_contents());
        ?>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div> 
