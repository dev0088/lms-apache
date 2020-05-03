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
            <h3 class="box-title" style="color: #00c0ef!important;font-weight:600;">Mallar</h3>
            <div class="box-tools">
              <?php if($this->session->userdata ('user_details')[0]->user_type == 'admin'){ ?>
                <form method="post" enctype="multipart/form-data" action="<?php echo base_url().'mallar/upload_mallar' ?>" data-parsley-validate>
                  <input type="file" class="upload" name="pdf_upload" id="mallar_upload" value="" accept="pdf/*" style="display:inline;">
                  <button type="submit" class="btn-sm  btn btn-success"><i class="glyphicon"></i> Lägg till mall</button>
                </form>
              <?php } ?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="overflow: scroll;">           
            <table id="example1" class="cell-border example1 table table-striped table1 delSelTable">
              <thead>
                <tr>
                  <th><input type="checkbox" class="selAll"></th>
                  <th>Datum</th>
                  <th>Dokumenttitel</th>
                  <th>Skapat av</th>
                  <th>Öppna</th>
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
          "ajax": url+"mallar/dataTable",
          "sPaginationType": "full_numbers",
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

        $('.dataTables_info').before('<button data-base-url="<?php echo base_url().'user/delete/'; ?>" rel="delSelTable" class="btn btn-default btn-sm delSelected pull-left btn-blk-del"> <i class="fa fa-trash"></i> </button><br><br>');  
    }, 300);
    $("button.closeTest, button.close").on("click", function (){});
  });

  $("#mallar_upload").on('change', function () {
    if (typeof (FileReader) != "undefined") {
      var reader = new FileReader();
      reader.readAsDataURL($(this)[0].files[0]);
    }
    else { alert("This browser does not support FileReader."); }
  });
</script>