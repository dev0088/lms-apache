<?php if($this->session->flashdata("alert_msg")){?>
  <div class="alert alert-danger">      
    <?php echo $this->session->flashdata("alert_msg")?>
  </div>
<?php } ?>
	<body class="hold-transition register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="<?php echo base_url(); ?>"><b>Welcome to Online Elearning Platform</b></a>
      </div>
      <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
        <?php if($this->session->flashdata("messagePr")){?>
          <div class="alert alert-info">      
            <?php echo $this->session->flashdata("messagePr")?>
          </div>
        <?php } ?>
        <form action="<?php echo base_url().'user/registration'; ?>" method="post">
          
						<div class="form-group has-feedback">
			            <input type="text" name="name" class="form-control" data-validation="required" placeholder="Name">
			            <span class="glyphicon glyphicon-user form-control-feedback"></span>
			          </div>
					
						<div class="form-group has-feedback">
			            <input type="text" name="email" class="form-control" data-validation="required" placeholder="Email">
			            <span class="glyphicon glyphicon-user form-control-feedback"></span>
			          </div>
					
           <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password_confirmation" placeholder="Password" data-validation="required">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Retype password" data-validation="confirmation">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('user_type')); ?>
            <select name="user_type" id="type_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                if($value != 'admin') {
                  echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
                  $role = $value;
                }
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>

          <!-- student register -->
          
          <!-- <div class="form-group has-feedback">
            <input type="text" name="father_name" id ="father_name" class="form-control" data-validation="required" placeholder="Father Name">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('faculty')); ?>
            <select name="faculty" id="faculty_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('department1')); ?>
            <select name="department1" id="department1_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('department2')); ?>
            <select name="department2" id="department2_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('department3')); ?>
            <select name="department3" id="department3_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('department4')); ?>
            <select name="department4" id="department4_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('semester')); ?>
            <select name="semester" id="semester_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'"> Semester '.ucfirst($value).'</option>';
              }
              ?>
            </select>
            
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <?php $type = json_decode(setting_all('class')); ?>
            <select name="class" id="class_select" class="form-control">
              <?php 
              foreach ($type as $key => $value) {
                  echo '<option value="'.$value.'"> Class '.ucfirst($value).'</option>';
              }
              ?>
            </select>
            <span class="glyphicon glyphicon-random form-control-feedback"></span>
          </div> -->

          <!-- Calendar -->




    	     <div class="row">
              <div class="col-xs-12">
               <!--  <input type="hidden" name="user_type" value="<?php //echo setting_all('user_type');?>"> -->
                <input type="hidden" name="call_from" value="reg_page">
                <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat btn-color">Register</button>
              </div>
            </div>
        </form>
    	  <br>
        <a href="<?php echo base_url('user/login');?>" class="text-center">I already have a membership</a>
      </div>
      <!-- /.form-box -->
    </div>
<!-- /.register-box -->
  </body>
<script>
$(document).ready(function(){
  <?php if($this->input->get('invited') && $this->input->get('invited') != ''){ ?>
    $burl = '<?php echo base_url() ?>';
    $.ajax({
      url: $burl+'user/chekInvitation',
      method:'post',
      data:{
        code: '<?php echo $this->input->get('invited'); ?>'
      },
      dataType: 'json'
    }).done(function(data){
      console.log(data);
      if(data.result == 'success') {
        $('[name="email"]').val(data.email);
        $('form').attr('action', $burl + 'user/register_invited/' + data.users_id);
      } else{
        window.location.href= $burl + 'user/login';
      }
    });
  <?php } ?>
});

// $('#father_name').hide();
// $('#faculty_select').hide();
// $('#department1_select').hide();
// $('#department2_select').hide();
// $('#department3_select').hide();
// $('#department4_select').hide();
// $('#semester_select').hide();
// $('#class_select').hide();

// $('#type_select').change(function() {
//   if($(this).val() == 'teacher') {
//     $('#father_name').hide();
//     $('#faculty_select').hide();
//     $('#semester_select').hide();
//     $('#class_select').hide();
//     $('#department1_select').hide();
//     $('#department2_select').hide();
//     $('#department3_select').hide();
//     $('#department4_select').hide();
//   } else {
//     $('#father_name').show();
//     $('#faculty_select').show();
//     $('#department1_select').show();
//     $('#semester_select').show();
//     $('#class_select').show();
//   }
// });
// $('#faculty_select').change(function() {
//   // alert($(this).val());
//   if($(this).val() == 'Law & politial Science') {
//     $('#department1_select').show();
//     $('#department2_select').hide();
//     $('#department3_select').hide();
//     $('#department4_select').hide();
//   }
//   else if($(this).val() == 'Sharia') {
//     $('#department2_select').show();
//     $('#department1_select').hide();
//     $('#department3_select').hide();
//     $('#department4_select').hide();
//   }
//   else if($(this).val() == 'Economics') {
//     $('#department3_select').show();
//     $('#department1_select').hide();
//     $('#department2_select').hide();
//     $('#department4_select').hide();
//   }
//   else if($(this).val() == 'Education') {
//     $('#department4_select').show();
//     $('#department1_select').hide();
//     $('#department2_select').hide();
//     $('#department3_select').hide();
//   }
// });
</script>
