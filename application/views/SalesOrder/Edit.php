<style>
  .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
    background-color: #ddd;
  }
  .custom_scroll{
    min-height: 160px;
    max-height: 160px;
    overflow: auto;
  }
  .custom_scroll::-webkit-scrollbar {
    width: 5px;
  }
  .custom_scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 20px;
  }
  .custom_scroll::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 20px;
  }
  .custom_scroll::-webkit-scrollbar-thumb:hover {
    background: #555; 
  }
  .payment_quick, #payment_clear{
    padding-left: 0px;
    padding-right: 0px;
  }
</style>
<script type="text/javascript">
  function cekform(){
    if($('#code').val()==""){
      $('#payment_modal').modal({ show: true });
      $('#payment_modal').modal('hide');
      alert('Code tidak boleh kosong.');
      $('#code').focus();
      return false;
    }
    else if($('#date').val()==""){
      $('#payment_modal').modal({ show: true });
      $('#payment_modal').modal('hide');
      alert('Date tidak boleh kosong.');
      $('#date').focus();
      return false;
    }
    else if($('#customer_id').val()==""){
      $('#payment_modal').modal({ show: true });
      $('#payment_modal').modal('hide');
      alert('Customer tidak boleh kosong.');
      $('#customer_id').focus();
      return false;
    }
    else if($('.item_id').length == 0) {
      $('#payment_modal').modal({ show: true });
      $('#payment_modal').modal('hide');
      alert('Item tidak boleh kosong.');
      return false;
    }
    else if(parseInt($('#payment_modal_change').val().replace(/\,/g,'')) < 0){
      alert('Pembayaran kurang.');
      $('#payment_modal_paid').focus();
      return false;
    }

    else {
        var cek_code = $.ajax({
                            url : '<?=base_url() ?>index.php/SalesOrder/CekKode/'+$('#code').val()+'/'+$('#id').val(),
                            type: 'get',
                            contentType: 'application/json',
                            dataType: 'json'
                            /*url: 'http://localhost/umdpos/index.php/Item/CekKode',
                            type: 'post',
                            data: JSON.stringify(data),
                            contentType: 'application/json',
                            dataType: 'json'*/
                        });
        cek_code.done(function (data) {
                    if ('status' in data) {
                        if (data.status == true)
                            document.frm.submit();
                        else{
                            if(data.hasOwnProperty("errors")) {
                                $('#payment_modal').modal({ show: true });
                                $('#payment_modal').modal('hide');
                                alert(data.errors.code[0]);
                                return false;
                            }
                        }
                    } else {
                        $('#payment_modal').modal({ show: true });
                        $('#payment_modal').modal('hide');
                        alert('Ada kesalahan, data gagal disimpan.');
                        return false;
                    }
                }).fail(function (data) {
                    $('#payment_modal').modal({ show: true });
                    $('#payment_modal').modal('hide');
                    alert('Ada kesalahan, ' + data.responseJSON);
                    return false;
                });
    }
  }
  <?php
    $item_js = array();
    $i = 0;
    $temp_id = 0;
    foreach ($item->result() as $row) {
		$item_js[$row->id]['id'] = $row->id;
		$item_js[$row->id]['name'] = $row->name;
		$item_js[$row->id]['barcode'] = $row->barcode;
		$item_js[$row->id]['price'] = $row->price;
    }
  ?>
  var item = <?= json_encode($item_js) ?>;
  <?php
    $item_per_barcode_js = array();
    $i = 0;
    $temp_id = 0;
    foreach ($item->result() as $row) {
		$item_per_barcode_js[$row->barcode]['id'] = $row->id;
		$item_per_barcode_js[$row->barcode]['name'] = $row->name;
		$item_per_barcode_js[$row->barcode]['barcode'] = $row->barcode;
		$item_per_barcode_js[$row->barcode]['price'] = $row->price;
    }
  ?>
  var item_per_barcode = <?= json_encode($item_per_barcode_js) ?>;
  function add_item(value, type){
	var found_array = false;
    if(item.hasOwnProperty(value) && type=="id"){
      var item_temp = item[value];
	  found_array = true;
	}
	else if(item_per_barcode.hasOwnProperty(value) && type=="barcode"){
      var item_temp = item_per_barcode[value];
	  found_array = true;
	}
	if(found_array){
      var found_on_table = false;
      if($('.item_id').length > 0) {
        $('.item_id').each(function(index, currentElement){
          if(item_temp['id'] == $('.item_id:eq(' +index+ ')').val()){
            found_on_table = true;
            var quantity = $('.quantity:eq(' +index+ ')').val().replace(/\,/g,'');
            quantity = parseFloat(quantity) + 1;
            $('.quantity:eq(' +index+ ')').val(numberWithCommas(+parseFloat(quantity).toFixed(0)));
          }
        });
      }
      if(!found_on_table){
        $('#table-detail tbody').append(
          '<tr>'+
            '<td style=\"width: 10%;text-align: center;\"><div class=\"no\"></div></td>'+
            '<td style=\"width: 45%;\">'+
              item_temp['name']+
              '<input type=\"hidden\" name=\"item_id[]\" class=\"item_id\" value=\"'+item_temp['id']+'\" />'+
            '</td>'+
            '<td style=\"width: 10%;text-align: center\"><input class=\"form-control quantity\" name=\"quantity[]\" type=\"text\" value=\"1\" style=\"width:40px;height: auto;padding: 0;padding-right: 3px;text-align: right\" onchange=\"calculate_amount()\"></td>'+
            '<td style=\"width: 15%;text-align: center\"><input class=\"form-control price\" name=\"price[]\" type=\"text\" value=\"'+item_temp['price']+'\" style=\"width:60px;height: auto;padding: 0;padding-right: 3px;text-align: right\" onchange=\"calculate_amount()\"></td>'+
            '<td style=\"width: 15%;text-align: right;padding-right: 5px;\" class=\"subtotal\"></td>'+
            '<td style=\"width: 5%;text-align: right;\"><a href=\"#\" class=\"btn bg-maroon btn-circle btn-sm\" onclick=\"delete_detail(this)\" style=\"padding: 0px 5px\"><i class=\"fa fa-trash\"></i></td>'+
          '</tr>'
        );
      }
      $('#find_item_id').val('').trigger('change');
      $('#barcode').val('');
      calculate_amount();
      $('#barcode').focus();
    }
  }
  function calculate_amount(){
    var price_total = 0;
    var noi = 1;
    var total = 0;
    if($('.item_id').length > 0) {
      $('.item_id').each(function(index, currentElement){
        var quantity = $('.quantity:eq(' +index+ ')').val().replace(/\,/g,'');
        var price = $('.price:eq(' +index+ ')').val().replace(/\,/g,'');
        var no = $('.no:eq(' +index+ ')').html(noi+'.');
        if(isInt(quantity)){
          $('.quantity:eq(' +index+ ')').val(numberWithCommas(+parseFloat(quantity).toFixed(0)));
          quantity = $('.quantity:eq(' +index+ ')').val().replace(/\,/g,'');
        }
        else{
          $('.quantity:eq(' +index+ ')').val('0');
          quantity = 0;
        }
        if(isInt(price)){
          $('.price:eq(' +index+ ')').val(numberWithCommas(+parseFloat(price).toFixed(0)));
          price = $('.price:eq(' +index+ ')').val().replace(/\,/g,'');
        }
        else{
          $('.price:eq(' +index+ ')').val('0');
          price = 0;
        }
        var subtotal = price * quantity;
        total = total + subtotal;
        $('.subtotal:eq(' +index+ ')').html(numberWithCommas(+parseFloat(subtotal).toFixed(0)));
        noi++;
      });
    }
    $('#total').html(numberWithCommas(+parseFloat(total).toFixed(0)));
  }
  function delete_detail(ele){
    ele.closest('tr').remove();
    calculate_payment();
  }
  function isInt(n) {
    return !isNaN(parseInt(n)) && isFinite(n);
  }
  function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
  }
  function show_payment_modal() {
    //$('#payment_modal_total').val('141,000');
    $('#payment_modal_total').val($('#total').html());
    $('#payment_quick_total').html($('#total').html());
    calculate_payment()
    $('#payment_modal').modal({ show: false });
    $('#payment_modal').modal('show');
  }
  function calculate_payment(){
	calculate_amount()
    var payment_modal_total = $('#payment_modal_total').val().replace(/\,/g,'');
    var payment_modal_paid = $('#payment_modal_paid').val().replace(/\,/g,'');
    if(isInt(payment_modal_paid)){
      $('#payment_modal_paid').val((+parseFloat(payment_modal_paid).toFixed(0)).toLocaleString(undefined, { maximumFractionDigits: 0 }));
      payment_modal_paid = $('#payment_modal_paid').val().replace(/\,/g,'');
    }
    else{
      $('#payment_modal_paid').val('0');
      payment_modal_paid = 0;
    }
    $('#paid').val(payment_modal_paid);
    var payment_modal_change = payment_modal_paid - payment_modal_total;
    payment_modal_change = (+parseFloat(payment_modal_change).toFixed(0)).toLocaleString(undefined, { maximumFractionDigits: 0 })
    $('#payment_modal_change').val(payment_modal_change);
  }
  function clear_payment(){
    $('#payment_modal_paid').val(0);
    calculate_payment();
  }
</script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-bottom: -30px">

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <form class="box" name="frm" onsubmit="return cekform()" enctype="multipart/form-data" method="POST" action="<?php echo base_url()?>index.php/SalesOrder/EditDb">
            <input type="hidden" name="id" id="id" value="<?= $tunggal->id ?>">
            <input type="hidden" name="paid" id="paid" value="">
            <!--<div class="box-header">
              <h3 class="box-title">Data Table With Full Features</h3>
            </div>-->
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                <div class="col-xs-6">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Nama</th>
                  <th>Barcode</th>
                  <th>Harga</th>
                  <th style="width:15px"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=0;
                foreach($item->result() as $row){
                    $i++;
                ?>
                  <tr>
                      <td><?= $row->name ?></td>
                      <td><?= $row->barcode ?></td>
                      <td class="text-right">Rp. <?= number_format($row->price, 0, ',', '.') ?></td>
                      <td class="text-right">
                        <a href="#" class="btn bg-blue btn-circle btn-xs" title="Add Item" onclick="add_item(<?= $row->id ?>, 'id')"><i class="fa fa-list"></i></a>
                      </td>
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
                </div>
                  <div class="col-xs-6">
                    <div class="well well-sm">
                      <div class="row" style="margin: 0px;height: 200px">
                        <div class="form-group" style="margin-bottom:5px;">
                          <input type="text" name="code" id="code" class="form-control" placeholder="Code" value="<?= $tunggal->code ?>" >
                        </div>
                        <div class="form-group" style="margin-bottom:5px;">
                          <div class="input-group date" id="date_div" style="width:100%!important;">
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar">
                              </span>
                            </span>
                            <input type="text" name="date" id="date" placeholder="Date" value="<?= date("Y-m-d H:i", strtotime($tunggal->code)) ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px;">
                          <select name="customer_id" id="customer_id" class="form-control select2" data-placeholder="Pilih Customer..." style="width:100%!important;">
                            <option value=""></option>
                            <?php
                              foreach($customer->result() as $row){
                                if($row->id == $tunggal->customer_id)
                                  echo "<option value=\"".$row->id."\" selected>".$row->name."</option>";
                                else
                                  echo "<option value=\"".$row->id."\">".$row->name."</option>";
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group" style="margin-bottom:5px;">
                          <select id="find_item_id" class="form-control select2" data-placeholder="Pilih Item..." style="width:100%!important;" onchange="add_item(this.value, 'id')">
                            <option value=""></option>
                              <?php
                                foreach($item->result() as $row)
                                  echo "<option value=\"".$row->id."\">".$row->name."</option>";
                              ?>
                          </select>
                          <!--<div class="input-group" style="width: 100%">
                            <select id="find_item_id" class="form-control select2" data-placeholder="Pilih Item..." style="width:100%!important;" onchange="add_item()">
                              <option value=""></option>
                                <?php
                                  foreach($item->result() as $row)
                                    echo "<option value=\"".$row->id."\">".$row->name."</option>";
                                ?>
                            </select>
                            <a class="btn btn-primary btn-block btn-flat" style="width: 1%; display: table-cell;" onclick="add_item()">
                              <span class="fa fa-plus"></span>
                            </a>
                          </div>-->
                        </div>
                        <div class="form-group" style="margin-bottom:5px;">
                          <input type="text" id="barcode" class="form-control" placeholder="Scan Barcode" autocomplete="off" onkeydown="add_item(this.value, 'barcode')">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="custom_scroll">
                            <table class="table table-striped table-condensed table-hover list-table" id="table-detail" style="margin-bottom: -3px">
                              <thead>
                                <tr class="success">
                                  <th style="width: 10%;text-align: center;">No</th>
                                  <th style="width: 45%;text-align: center;">Item</th>
                                  <th style="width: 10%;text-align: center;">Qty</th>
                                  <th style="width: 15%;text-align: center;">Price</th>
                                  <th style="width: 15%;text-align: center;">Subtotal</th>
                                  <th style="width: 5%;text-align: center;"><i class="fa fa-trash-o"></i></th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
                              foreach($jamak_detail->result() as $row){
                              ?>
                                <tr>
                                  <td style="width: 10%;text-align: center;"><div class="no"></div></td>
                                  <td style="width: 45%;">
								    <?= $row->name ?>
									<input type="hidden" name="item_id[]" class="item_id" value="<?= $row->item_id ?>" />
                                  </td>
                                  <td style="width: 10%;text-align: center"><input class="form-control quantity" name="quantity[]" type="text" value="<?= $row->quantity ?>" style="width:40px;height: auto;padding: 0;padding-right: 3px;text-align: right" onchange="calculate_amount()"></td>
                                  <td style="width: 15%;text-align: center"><input class="form-control price" name="price[]" type="text" value="<?= $row->price ?>" style="width:60px;height: auto;padding: 0;padding-right: 3px;text-align: right" onchange="calculate_amount()"></td>
                                  <td style="width: 15%;text-align: right;padding-right: 5px;" class="subtotal"></td>
                                  <td style="width: 5%;text-align: right;"><a href="#" class="btn bg-maroon btn-circle btn-sm" onclick="delete_detail(this)" style="padding: 0px 5px"><i class="fa fa-trash"></i></td>
                                </tr>
							  <?php
							  }
							  ?>
                              </tbody>
                            </table>
                          </div>
                          <table class="table table-striped table-condensed table-hover list-table" style="margin-bottom: 5px">
                            <tr class="success">
                              <td style="font-weight:bold;font-size:20px">Total</td>
                              <td class="text-right" colspan="2" style="font-weight:bold;font-size:20px;padding-right:10px" id="total">0</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div class="row" style="height: 60px">
                        <div class="col-xs-12">
                          <button type="button" class="btn btn-success btn-block btn-flat" style="height:60px;" onclick="show_payment_modal()">Payment</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<div class="modal fade" id="payment_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-8">
            <div class="form-horizontal">
              <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Total</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" style="text-align: right;font-weight: bold;" id="payment_modal_total" readonly>
                </div>
              </div>
              <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Payment</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" style="text-align: right;font-weight: bold;" id="payment_modal_paid" onchange="calculate_payment()" value="<?= $tunggal->paid ?>">
                </div>
              </div>
              <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Change</label>
                <div class="col-sm-8">
                  <input type="email" class="form-control" style="text-align: right;font-weight: bold;" id="payment_modal_change" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-2">
            <div class="btn-group btn-group-vertical btn-group-lg" style="width:100%;">
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-info" id="payment_quick_total">0</button>
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-warning">2,000</button>
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-warning">5,000</button>
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-warning">10,000</button>
            </div>
          </div>
          <div class="col-xs-2">
            <div class="btn-group btn-group-vertical btn-group-lg" style="width:100%;">
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-warning">20,000</button>
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-warning">50,000</button>
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="payment_quick btn btn-warning">100,000</button>
              <button type="button" style="padding-left: 0px;padding-right: 0px" class="btn btn-danger" onclick="clear_payment()">Clear</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" id="edit_modal_footer">
        <button type="button" class="btn btn-primary btn-lg btn-block btn-flat" onclick="cekform()">PAY</button>
        <button type="button" class="btn btn-secondary btn-lg btn-block btn-flat" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
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
<script src="<?php echo base_url()?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/chosen.jquery.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/moment/moment.js"></script>
<script src="<?php echo base_url()?>assets/bower_components/bootstrap-datetimepicker-dist/js/bootstrap-datetimepicker.js"></script>
<script>
$('.payment_quick').click(function() {
  var val = $(this).html().replace(/\,/g,'');
  var payment_modal_paid = $('#payment_modal_paid').val().replace(/\,/g,'');
  var total = parseInt(payment_modal_paid) + parseInt(val);
  $('#payment_modal_paid').val((+parseFloat(total).toFixed(0)).toLocaleString(undefined, { maximumFractionDigits: 0 }));
  calculate_payment();
});
$('.select2').select2();
$('#date_div').datetimepicker({
  format: 'YYYY-MM-DD HH:mm:ss',
});
$('#barcode').focus();
calculate_payment();
function table_size(){
  var height = $(window).height()/2.5;
  $('.custom_scroll').css('min-height',height+'px');
  $('.custom_scroll').css('max-height',height+'px');
}
$(window).on('resize', table_size);
$(document).ready(table_size);
$(function () {
  $('#example1').DataTable({
    "pagingType": "simple"
  })
});
//$(".chosen-select").chosen({no_results_text: "Oops, nothing found!"}); 
</script>
