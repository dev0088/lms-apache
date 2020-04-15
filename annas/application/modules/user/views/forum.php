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
          <div class="box-header with-border">
            <h3 class="box-title" style="color: #00c0ef!important;font-weight:600;">Forum</h3>
            <div class="box-tools">
            <!-- 
              <?php if(CheckPermission("users", "own_create")){ ?>
              <button type="button" class="btn-sm  btn btn-success modalButtonUser" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Add User</button>
              <?php } if(setting_all('email_invitation') == 1){  ?>
              <button type="button" class="btn-sm  btn btn-success InviteUser" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Invite People</button>
              <?php } ?> -->
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="overflow: scroll;">
            <table id="example1" class="cell-border example1 table table-striped table1 delSelTable">
              <thead>
                <tr>
                  <th><input type="checkbox" class="selAll"></th>
                  <th>Datum</th>
                  <th>Kundnamn</th>
                  <th>Godkänd av</th>
                  <th>Skapat av</th>
                  <th>Typ</th>
                  <th>Statut</th>
                  <th>Öppna</th>
                  <th>Redigera</th>
                  <th>Admin</th>
                </tr>
              </thead>
              <tbody>
              </tbody> 
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>  
<!-- Modal Crud Start-->
<div class="modal fade" id="nameModal_user" role="dialog">
  <div class="modal-dialog">
    <div class="box box-primary popup" >
      <div class="box-header with-border formsize">
        <h3 class="box-title">Forum</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- /.box-header -->
      <div class="modal-body" style="padding: 0px 0px 0px 0px;"></div>
    </div>
  </div>
</div><!--End Modal Crud --> 
<script type="text/javascript">
  $(document).ready(function() {  
    var url = '<?php echo base_url();?>';//$('.content-header').attr('rel');
    var table = $('#example1').DataTable({ 
          dom: 'lfBrtip',
          buttons: [
              'copy', 'excel', 'pdf'
          ],
          "processing": true,
          "serverSide": true,
          "ajax": url+"user/forumTable",
          "sPaginationType": "full_numbers",
          "searching": true,
          "language": {
            "search": "_INPUT_", 
            "searchPlaceholder": "Search",
            "paginate": {
                "next": '<i class="fa fa-angle-right"></i>',
                "previous": '<i class="fa fa-angle-left"></i>',
                "first": '<i class="fa fa-angle-double-left"></i>',
                "last": '<i class="fa fa-angle-double-right"></i>'
            }
          },
          "iDisplayLength": 10,
          "aLengthMenu": [[10, 25, 50, 100,500,-1], [10, 25, 50,100,500,"All"]]
      });
    
    setTimeout(function() {
      var add_width = $('.dataTables_filter').width()+$('.box-body .dt-buttons').width()+10;
      $('.table-date-range').css('right',add_width+'px');
      <?php
      if($this->session->userdata ('user_details')[0]->user_type == admin){
      ?>
        $('.dataTables_info').before('<button data-base-url="<?php echo base_url().'user/delete_forum/'; ?>" rel="delSelTable" class="btn btn-default btn-sm delSelected pull-left btn-blk-del"> <i class="fa fa-trash"></i> </button><br><br>');
      <?php } ?>
    }, 300);
    $("button.closeTest, button.close").on("click", function (){});
  });

  function changeForumstatus(object, id) {
    var url =  $('body').attr('data-base-url');
    // var val = object.options[object.selectedIndex].value;
    var val = object.value;
    window.location= url+"user/change_forumstatus/"+id+"/"+val;
  }

</script>