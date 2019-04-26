  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales Order
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <!--<h3 class="box-title">Data Table With Full Features</h3>-->
              <a class="btn btn-primary" href="<?php echo base_url()?>index.php/SalesOrder/Create">Add</a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php
              if(ISSET($message)){
                echo "<p style='padding: 10px; padding-left: -10px; background: #FF0; width: 100%;'>$message</p>";
              }
            ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Code</th>
                  <th>Date</th>
                  <th>Customer</th>
                  <th>Total</th>
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
                      <td><?= $row->code ?></td>
                      <td><?= $row->date ?></td>
                      <td><?= $row->customer_name ?></td>
                      <td class="text-right">Rp. <?= number_format($row->total, 0, ',', '.') ?></td>
                      <td class="text-right">
                        <a href="<?php echo base_url()?>index.php/SalesOrder/View/<?= $row->id ?>" class="btn bg-blue btn-circle btn-xs" title="View Data"><i class="fa fa-list"></i></a>
                        <a href="<?php echo base_url()?>index.php/SalesOrder/Edit/<?= $row->id ?>" class="btn bg-orange btn-circle btn-xs" title="Edit Data"><i class="fa fa-edit"></i></a>
                        <a href="<?php echo base_url()?>index.php/SalesOrder/Delete/<?= $row->id ?>" class="btn bg-maroon btn-circle btn-xs" title="Delete Data" onclick="return confirm('Are you sure Want to DELETE?')"><i class="fa fa-trash"></i></a>
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
<script src="<?php echo base_url()?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>