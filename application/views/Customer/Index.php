<style>
  .chosen-container {width: 100% !important;}
</style>
<script type="text/javascript">
function cekform(mode){
  if($('#'+mode+'_name').val()==""){
    alert('Name tidak boleh kosong.');
    $('#'+mode+'_name').focus();
    return false;
  }
  else {
    $('#'+mode+'_frm').submit();
  }
}
function show_modal(mode, data_arr) {
    if(mode == 'add'){
        $('#add_modal').modal({ show: false });
        $('#add_modal').modal('show');
    }
    else{
        $('#edit_modal').modal({ show: false });
        $('#edit_id').val(data_arr['id']);
        $('#edit_name').val(data_arr['name']);
        var var_dis = false;
        if(mode == 'edit'){
          var_dis = false;
          $('#edit_modal_title').html('Edit Customer');
          $('#edit_modal_footer').show();
        }
        else{
          var_dis = true;
          $('#edit_modal_title').html('View Customer');
          $('#edit_modal_footer').hide();
        }
        $('#edit_id').prop('disabled', var_dis);
        $('#edit_name').prop('disabled', var_dis);
        $('#edit_modal').modal('show');
    }
}

</script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <!--<h3 class="box-title">Data Table With Full Features</h3>-->
              <button type="button" class="btn btn-primary" class="btn btn-primary" onclick="show_modal('add', null)">Add</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php
              if(ISSET($message)){
                echo "<p style='padding: 10px; padding-left: -10px; background: #FF0; width: 100%;'>$message</p>";
              }
            ?>
              <table id="example1" class="table table-bordered table-striped" style="width: 50%">
                <thead>
                <tr>
                  <th>Nama</th>
                  <th style="width:60px"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=0;
                foreach($jamak->result() as $row){
                    $i++;
                ?>
                  <tr>
                      <td><?= $row->name ?></td>
                      <td class="text-right">
                        <button onclick="show_modal('view', {<?php
                      echo "id : '".$row->id."',
                      name : '".$row->name."'";
                      ?>})" class="btn bg-blue btn-circle btn-xs" title="View Data"><i class="fa fa-list"></i></button>
                        <button onclick="show_modal('edit', {<?php
                      echo "id : '".$row->id."',
                      name : '".$row->name."'";
                      ?>})" class="btn bg-orange btn-circle btn-xs" title="Edit Data"><i class="fa fa-edit"></i></button>
                        <a href="<?php echo base_url()?>index.php/Customer/Delete/<?= $row->id ?>" class="btn bg-maroon btn-circle btn-xs" title="Delete Data" onclick="return confirm('Are you sure Want to DELETE?')"><i class="fa fa-trash"></i></a>
                      </td>
                  </tr>
                <?php
                }
                ?>
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
  <!-- /.content-wrapper -->
<div class="modal fade" id="add_modal">
  <div class="modal-dialog">
    <form class="modal-content" name="add_frm" id="add_frm" method="post" enctype="multipart/form-data" name="frm" action="<?php echo base_url()?>index.php/Customer/CreateDb">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="add_modal_title">Add Customer</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-3 control-label">Nama</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" id="add_name">
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="cekform('add')">Submit</button>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="edit_modal">
  <div class="modal-dialog">
    <form class="modal-content" name="edit_frm" id="edit_frm" method="post" enctype="multipart/form-data" name="frm" action="<?php echo base_url()?>index.php/Customer/EditDb">
      <input type="hidden" name="id" id="edit_id" value="">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="edit_modal_title">Edit Customer</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-3 control-label">Nama</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" id="edit_name">
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer" id="edit_modal_footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="cekform('edit')">Submit</button>
      </div>
    </form>
  </div>
</div>
<script src="<?php echo base_url()?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/chosen.jquery.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>