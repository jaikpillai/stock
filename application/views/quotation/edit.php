<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Quotation Transactions</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Quotation Transactions</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if ($this->session->flashdata('success')) : ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif ($this->session->flashdata('error')) : ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Edit Quotation</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('quotation/update') ?>" method="post" class="form-horizontal">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-12 control-label">Date: <?php date_default_timezone_set('Asia/Kolkata');
                                                                                echo date('Y-m-d'); ?></label>
              </div>
              <!-- <div class="form-group">
                  <label for="time" class="col-sm-12 control-label">Date: <?php echo date('h:i a') ?></label>
                </div> -->

              <div class="col-md-4 col-xs-12 pull pull-left">


                <div class="form-group">
                  <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Quotation Number</label>
                  <div class="col-sm-7">

                    <input value="<?php echo $quotation_data['quotation_master']['quotation_no'] ?>" type="text" class="form-control" id="quotation_no" name="quotation_no" placeholder="" autocomplete="off" readonly />

                    <!-- <input type="text" class="form-control" id="invoice_no" name="invoice_no" disabled autocomplete="off" /> -->

                  </div>
                </div>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Party Name</label>
                  <div class="col-sm-7">
                    <select class="form-control select_group part_select" id="party" name="party" style="width:100%;" required>
                      <!-- <option value="" disabled></option> -->
                      <?php foreach ($party_data as $k => $v) : ?>


                        <option value="<?php echo $v['party_id'] ?>" <?php if (($v['party_id'] == $quotation_data['quotation_master']['party_id'])) {
                                                                        echo 'selected="selected"';
                                                                      } ?>><?php echo $v['party_name'] ?></option>
                      <?php endforeach ?>
                    </select>

                  </div>
                </div>



                <div class="form-group">
                  <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Variation %</label>
                  <div class="col-sm-7">
                    <input type="number" class="form-control" id="variation" name="variation" value="<?php echo $quotation_data['quotation_master']['variation'] ?>" placeholder="Enter Variation Percentage" onchange="variationcalc()" onkeyup="variationcalc()" autocomplete="off">


                  </div>
                </div>

              </div>

              <div class="col-md-4 col-xs-12 pull pull-left">
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Date</label>
                  <div class="col-sm-7">
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $quotation_data['quotation_master']['quotation_date'] ?>" autocomplete="off" required />


                  </div>
                </div>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Reference Number</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="ref_no" name="ref_no" value="<?php echo $quotation_data['quotation_master']['ref_no'] ?>" placeholder="Enter Reference Number" autocomplete="off">
                    <input type="date" class="form-control" id="ref_date" name="ref_date" value="<?php echo $quotation_data['quotation_master']['ref_date'] ?>" autocomplete="off" />

                  </div>
                </div>



              </div>




              <br /> <br />
              <div class="col-xs-12 table-responsive">
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr>
                      <th style="width:20%">Description</th>
                      <!-- <th style="width:10%">Code</th> -->
                      <th style="width:10%">Make</th>
                      <th style="width:5%">Qty</th>
                      <th style="width:10%">Unit</th>
                      <th style="width:10%">Rate</th>
                      <th style="width:10%">Disc. %</th>
                      <th style="width:10%">GST</th>
                      <th style="width:20%">Amount</th>
                      <th style="width:10%"></th>
                    </tr>
                  </thead>

                  <tbody>


                    <tr id="rowSelect">

                      <!-- <td><input type="text" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal(1)"></td> -->

                      <!-- <td>
                      <input type="text" name="code[]" id="code_1" class="form-control" disabled autocomplete="off">
                      <input type="hidden" name="code_value[]" id="code_value_1" class="form-control" autocomplete="off">
                    </td> -->
                      <!-- <td>
                    <select class="form-control select_group product" data-row-id="row_1" name="code[]" id="code_1" style="width:100%;" onchange="getProductDataFromCode(1)" required>
                        <option value=""></option>
                       
                      </select>
                    </td> -->
                      <td>
                        <input type="hidden" id="codeSelect" class="form-control" autocomplete="off">
                        <div style="max-width:300px">
                          <select class="form-control select_group product" data-row-id="row_1" id="productSelect" style="width:100%;">
                            <option value=""></option>

                          </select>
                        </div>
                      </td>

                      <td>
                        <div style="min-width:60px">
                          <input type="text" id="makeSelect" class="form-control" disabled autocomplete="off">
                          <input type="hidden" id="make_value_select" class="form-control" autocomplete="off">
                        </div>
                      </td>
                      <!-- <td>
                      <input type="number" name="qty[]" id="qty_1" class="form-control"  autocomplete="off">
                      <input type="hidden" name="qty_value[]" id="qty_value_1" class="form-control" autocomplete="off">
                    </td> -->

                      <td>
                        <div style="min-width:60px">
                          <input type="number" id="qtySelect" min="0" class="form-control total_calculator_qty">
                        </div>
                      </td>


                      <td>
                        <div style="min-width:60px">
                          <input type="text" id="unitSelect" class="form-control  " disabled autocomplete="off">
                          <input type="hidden" id="unit_value_select" class="form-control" autocomplete="off">
                        </div>
                      </td>
                      <td>
                        <div style="min-width:100px">
                          <input type="number" id="rateSelect" class="form-control total_calculator_rate" autocomplete="off">
                          <input type="hidden" id="rate_value_select" class="form-control" autocomplete="off">
                        </div>
                      </td>
                      <td>
                        <div style="min-width:60px">
                          <input type="number" id="discountSelect" min="0" max="100" class="form-control total_calculator_discount " autocomplete="off">
                        </div>
                      </td>
                      <td>
                        <select class="form-control select_group tax" data-row-id="row_1" id="gstSelect" style="width:100%;">
                          <option value=""></option>
                          <?php foreach ($tax_data as $k => $v) : ?>
                            <option value="<?php echo $v['iTax_ID'] ?>" data-tax-value="<?php echo $v['sValue'] ?> "> <?php echo $v['sTax_Description'] ?> </option>
                          <?php endforeach ?>
                        </select>
                      </td>
                      <!-- <td>
                      <input type="number" name="gst[]" id="gst_1" class="form-control" autocomplete="off">
                      <input type="hidden" name="gst_value[]" id="gst_value_1" class="form-control" autocomplete="off">
                    </td> -->
                      <td>
                        <div style="min-width:100px">
                          <input type="number" id="amountSelect" class="form-control" disabled autocomplete="off">
                          <input type="hidden" id="amount_value_select" class="form-control" autocomplete="off">
                        </div>
                      </td>

                      <td><button type="button" id="add_row" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button></td>
                    </tr>


                    <?php if (isset($quotation_data['quotation_item'])) : ?>
                      <?php $x = 1; ?>
                      <?php foreach ($quotation_data['quotation_item'] as $key => $val) : ?>
                        <?php //print_r($v); 
                        ?>
                        <tr id="row_<?php echo $x; ?>">

                          <td>
                            <input type="hidden" name="code[]" id="code_<?php echo $x; ?>" class="form-control" value="<?php echo $val['item_code'] ?>" autocomplete="off">
                            <div style="max-width:300px">
                              <select class="form-control select_group product" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" name="product[]" style="width:100%;" onchange="getProductData(<?php echo $x; ?>)" required>
                                <option value=""></option>

                                <option value="<?php echo $val['Item_ID'] ?>" <?php echo "selected='selected'"; ?>><?php echo $val['Item_Code'] . ' , ' . $val['Item_Name'] ?></option>



                              </select>
                            </div>
                          </td>

                          <td>
                            <div style="min-width:60px">
                              <input type="text" name="make[]" id="make_<?php echo $x; ?>" value="<?php echo $val['item_make'] ?>" class="form-control" disabled autocomplete="off">
                              <input type="hidden" name="make_value[]" id="make_value_<?php echo $x; ?>" value="<?php echo $val['item_make'] ?>" class="form-control" autocomplete="off">
                            </div>
                          </td>

                          <td>
                            <div style="min-width:60px">
                              <input type="number" onkeydown="if(event.key==='.'){event.preventDefault();}" oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');" name="qty[]" id="qty_<?php echo $x; ?>" value="<?php echo $val['qty'] ?>" class="form-control total_calculator_qty" required autocomplete="off">
                            </div>
                          </td>

                          <td>
                            <div style="min-width:60px">
                              <input type="text" name="unit[]" id="unit_<?php echo $x; ?>" class="form-control" value="<?php echo $val['unit'] ?>" disabled autocomplete="off">
                              <input type="hidden" name="unit_value[]" id="unit_value_<?php echo $x; ?>" value="<?php echo $val['unit'] ?>" class="form-control" autocomplete="off">
                            </div>
                          </td>
                          <td>
                            <div style="min-width:100px">
                              <?php $itemRate = $val['rate'] + $val['rate'] * $quotation_data['quotation_master']['variation'] / 100; ?>
                              <input type="number" name="rate[]" id="rate_<?php echo $x; ?>" class="form-control total_calculator_rate" value="<?php echo $itemRate; ?>" autocomplete="off">
                              <input type="hidden" name="rate_value[]" id="rate_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['rate']; ?>" autocomplete="off">
                            </div>
                          </td>
                          <td>
                            <div style="min-width:60px">
                              <input type="number" name="discount[]" id="discount_<?php echo $x; ?>" class="form-control total_calculator_discount" value="<?php echo $val['discount'] ?>" autocomplete="off">
                            </div>
                          </td>
                          <td>
                            <select class="form-control select_group tax" data-row-id="row_<?php echo $x; ?>" name="gst[]" id="gst_<?php echo $x; ?>" style="width:100%;">
                              <option value=""></option>
                              <?php foreach ($tax_data as $k => $v) : ?>
                                <option value="<?php echo $v['iTax_ID'] ?>" data-tax-value="<?php echo $v['sValue'] ?>" <?php if ($val['tax_id'] == $v['iTax_ID']) {
                                                                                                                          echo "selected='selected'";
                                                                                                                        } ?>><?php echo $v['sTax_Description'] ?></option>
                              <?php endforeach ?>

                            </select>
                          </td>
                          <td>
                            <div style="min-width:100px">
                              <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control" disabled value="<?php  ?>" autocomplete="off">
                              <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" value="<?php  ?>" autocomplete="off">
                            </div>
                          </td>
                          <td><button type="button" class="btn btn-danger" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close"></i></button></td>
                        </tr>
                        <?php $x++; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <br /> <br />

              <div class="col-md-6 col-xs-12 pull pull-right">

                <div class="form-group">
                  <label for="total" class="col-sm-5 control-label">Total</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="total" name="total" disabled autocomplete="off">
                    <input type="hidden" class="form-control" id="total_value" name="total_value" autocomplete="off">
                    <input type="hidden" class="form-control" id="total_discount" name="total_discount" autocomplete="off">
                    <input type="hidden" class="form-control" id="total_gst" name="total_gst" autocomplete="off">


                  </div>
                </div>

                <div class="form-group">
                  <label for="gst_amount" class="col-sm-5 control-label">GST Amount</label>
                  <div class="col-sm-7">
                    <input type="number" class="form-control" id="gst_amount" name="gst_amount" disabled autocomplete="off">
                    <input type="hidden" class="form-control" id="gst_amount_value" name="gst_amount_value" autocomplete="off">
                  </div>
                </div>

                <div class="form-group">
                  <label for="other_charge" class="col-sm-5 control-label">Freight/Others</label>
                  <div class="col-sm-7">
                    <input type="number" class="form-control" id="other_charge" name="other_charge" value="<?php echo $quotation_data['quotation_master']['other_charges'] ?>" onkeyup="subAmount()" onchange="subAmount()" autocomplete="off">
                    <input type="hidden" class="form-control" id="other_charge_value" name="other_charge_value" value="<?php echo $quotation_data['quotation_master']['other_charges'] ?>" autocomplete="off">
                  </div>
                </div>






                <div class="form-group">
                  <label for="roundoff" class="col-sm-5 control-label">Round off</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="roundoff" name="roundoff" disabled autocomplete="off">
                    <input type="hidden" class="form-control" id="roundoff_value" name="roundoff_value" autocomplete="off">
                  </div>
                </div>

                <div class="form-group">
                  <label for="total_amount" class="col-sm-5 control-label">Total Amount</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="total_amount" name="total_amount" disabled autocomplete="off">
                    <input type="hidden" class="form-control" id="total_amount_value" name="total_amount_value" autocomplete="off">
                  </div>
                </div>



              </div>
            </div>

            <div class="col-md-12 col-xs-12 pull pull-right">
              <table class="table table-bordered" id="terms_info_table">
                <thead>
                  <tr>
                    <th style="width:80%">Terms and Conditions</th>
                    <th style="width:10%"><button type="button" id="add_terms" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button></th>
                  </tr>
                </thead>

                <tbody>
                  <?php $n = 1; ?>
                  <?php foreach ($terms as $key => $val) : ?>
                    <?php //print_r($v); 
                    ?>
                    <tr id="terms_<?php echo $n; ?>">
                      <td>

                        <select type="text" class="form-control terms" terms_row_id="<?php echo $n; ?>" id="terms_<?php echo $n; ?>" name="terms[]">
                          <option value=""></option>
                          <?php foreach ($terms_data as $k => $v) : ?>
                            <option value="<?php echo $v['s_no'] ?> " <?php if ($val['s_no'] == $v['s_no']) : ?> selected <?php endif; ?>> <?php echo $v['description'] ?> </option>
                          <?php endforeach ?>
                        </select>

                      </td>
                      <td><button type="button" class="btn btn-danger" onclick="removeTerms('<?php echo $n; ?>')"><i class="fa fa-close"></i></button></td>
                    </tr>
                    <?php $n++; ?>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- /.box-body -->

            <div class="box-footer">
              <a target="__blank" href="<?php echo base_url('quotation/quotation/' . $quotation_data['quotation_master']['s_no'] . '') ?>" class="btn btn-default">Print</a>
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <a href="<?php echo base_url('quotation/') ?>" class="btn btn-warning">Back</a>
            </div>
          </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->


  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  var removed_rows_count = 0;
  var removed_row_count_terms = 0;
</script>

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  function printOrder(id) {
    if (id) {
      $.ajax({
        url: base_url + 'quotation/quotation/' + id,
        type: 'post',
        success: function(response) {
          var mywindow = window.open('', 'new div', 'height=1000,width=1500');
          // mywindow.document.write('<html><head><title></title>');
          // mywindow.document.write('<link rel="stylesheet" href="<?php //echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') 
                                                                    ?>" type="text/css" />');
          // mywindow.document.write('</head><body >');
          mywindow.document.write(response);
          // mywindow.document.write('</body></html>');
          myWindow.document.close();
          myWindow.focus();
          mywindow.print();
          mywindow.close();

          return true;
        }
      });
    }
  }

  $(document).ready(function() {


    $(".select_group").select2();
    // $("#description").wysihtml5();

    initailizeSelect2();
    initailizeParty();

    $(".tax").select2()
      .on('change', function(e) {
        var row_id = $(this).attr('id').replace('gst_', '');
        getTotal(row_id)
      })
      .on('select', function(e) {
        var row_id = $(this).attr('id').replace('gst_', '');
        getTotal(row_id)
      });

    $("#mainQuotationNav").addClass('active');
    $("#manageQuotationNav").addClass('active');
    $("#mainOrderManage").addClass('active');

    var count_table_tbody_tr = $("#product_info_table tbody tr").length;
    var row_id = count_table_tbody_tr + 1;


    // Add new row in the table 
    $("#add_terms").unbind('click').bind('click', function() {
      var table = $("#terms_info_table");
      var count_table_tbody_tr_terms = $("#terms_info_table tbody tr").length;
      var row_id = count_table_tbody_tr_terms + 1 + Number(removed_row_count_terms);

      $.ajax({
        url: base_url + '/terms/getTableTermsRow/',
        type: 'post',
        dataType: 'json',
        success: function(response) {


          // console.log(reponse.x);
          var html = '<tr id="terms_' + row_id + '">' +
            '<td>' +
            '<select class="form-control terms" terms-row-id="' + row_id + '" id="terms_' + row_id + '" name="terms[]" style="width:100%;" >' +

            '<option value=""></option>';
          $.each(response, function(index, value) {
            html += '<option value="' + value.s_no + '">' + value.description + '</option>';
          });

          html += '</select>' +
            '</td>' +

            '<td><button type="button" class="btn btn-danger" onclick="removeTerms(' + row_id + ')"><i class="fa fa-close"></i></button></td>' +
            '</tr>';

          if (count_table_tbody_tr_terms >= 1) {
            $("#terms_info_table tbody tr:last").after(html);
          } else {
            $("#terms_info_table tbody").html(html);
          }

          $(".terms").select2();

        }
      });

      return false;
    });


    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      $("#add_row").attr("disabled", true);
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1 + Number(removed_rows_count);

      var productSelect = $("#productSelect").val();
      var productName = $("#productSelect :selected").text();
      var makeSelect = $("#makeSelect").val();
      var qtySelect = $("#qtySelect").val();
      var unitSelect = $("#unitSelect").val();
      var rateSelect = $("#rateSelect").val();
      var discountSelect = $("#discountSelect").val();
      var gstSelect = $("#gstSelect").val();
      var amountSelect = $("#amountSelect").val();


      $.ajax({
        url: base_url + '/quotation/getTableTaxData/',
        type: 'post',
        dataType: 'json',
        success: function(response) {


          // console.log(reponse.x);
          var html = '<tr id="row_' + row_id + '">' +

            //  '<td><input type="text" name="code[]" id="code_'+row_id+'" class="form-control" disabled><input type="hidden" name="code_value[]" id="code_value_'+row_id+'" class="form-control"></td>'+
            // '<td>'+ 

            //  '<select class="form-control select_group product" data-row-id="'+row_id+'" id="code_'+row_id+'" name="code[]" style="width:100%;" onchange="getProductDataFromCode('+row_id+')">'+

            //      '<option value=""></option>';
            //      $.each(response, function(index, value) {
            //        html += '<option value="'+value.Item_Code+'" data-code-id="'+value.Item_ID+'">'+value.Item_Code+'</option>';             
            //      });

            //    html += '</select>'+
            //  '</td>'+ 

            '<td><input type="hidden" value=' + codeSelect + ' name="code[]" id="code_' + row_id + '" class="form-control" autocomplete="off"> <div style="max-width:300px">' +

            '<select class="form-control select_group product" data-row-id="' + row_id + '" id="product_' + row_id + '" name="product[]" style="width:100%;" >' +

            '<option value=' + productSelect + '>' + productName + '</option>';
          // $.each(response, function(index, value) {
          //   html += '<option value="'+value.Item_ID+'">'+value.Item_Code+' , '+value.Item_Name+'</option>';    onchange="getProductData('+row_id+')"         
          // });

          html += '</select>' +





            // '<input class="testproduct form-control" name="product[]" id="product_'+row_id+'" autocomplete="off" list="data_product_'+row_id+'"><datalist id="data_product_'+row_id+'"> </datalist>
            '</div></td>' +


            '<td><div style="min-width:60px"><input type="text" value = "' + makeSelect + '" name="make[]" id="make_' + row_id + '" class="form-control" disabled><input type="hidden" name="make_value[]" value = "' + makeSelect + '" id="make_value_' + row_id + '" class="form-control"></div></td>' +
            '<td><div style="min-width:60px"><input type="number" value = "' + qtySelect + '" name="qty[]" id="qty_' + row_id + '" class="form-control total_calculator_qty " ></div></td>' +
            '<td><div style="min-width:60px"><input type="text" value = "' + unitSelect + '" name="unit[]" id="unit_' + row_id + '" class="form-control" disabled><input type="hidden" name="unit_value[]" value = "' + unitSelect + '" id="unit_value_' + row_id + '" class="form-control"></div></td>' +
            '<td><div style="min-width:80px"><input type="number" value = "' + rateSelect + '" name="rate[]" id="rate_' + row_id + '" class="form-control total_calculator_rate " ><input type="hidden" value = "' + rateSelect + '" name="rate_value[]" id="rate_value_' + row_id + '" class="form-control"></div></td>' +
            '<td><div style="min-width:60px"><input type="number" value = "' + discountSelect + '" name="discount[]"  id="discount_' + row_id + '" class="form-control total_calculator_discount " ><input type="hidden" value = "' + discountSelect + '" name="discount_value[]" id="discount_value_' + row_id + '" class="form-control"></div></td>' +


            '<td>' +

            '<select class="form-control select_group tax" data-row-id="' + row_id + '" id="gst_' + row_id + '" name="gst[]" style="width:100%;">' +

            '<option value=""></option>';


          $.each(response, function(index, value) {

            if (Number(value.iTax_ID) == Number(gstSelect)) {

              html += '<option value="' + value.iTax_ID + '" data-tax-value="' + value.sValue + '" selected>' + value.sTax_Description + '</option>';
            } else {
              html += '<option value="' + value.iTax_ID + '" data-tax-value="' + value.sValue + '">' + value.sTax_Description + '</option>';
            }
          });

          html += '</select>' +
            '</td>' +

            // '<td><input type="text" name="gst[]" id="gst_'+row_id+'" class="form-control"><input type="hidden" name="gst_value[]" id="gst_value_'+row_id+'" class="form-control"></td>'+

            '<td><div style="min-width:100px"><input type="text" name="amount[]" value = ' + amountSelect + ' id="amount_' + row_id + '" class="form-control" disabled><input type="hidden" name="amount_value[]" id="amount_value_' + row_id + '" class="form-control"></div></td>' +
            '<td><button id="removeProduct_' + row_id + '" type="button" class="btn btn-danger removeProduct" ><i class="fa fa-close"></i></button></td>' +
            '</tr>';

          if (count_table_tbody_tr >= 1) {
            $("#product_info_table tbody tr:last").after(html);
            // console.log("End add");
            $("#add_row").attr("disabled", false);
          } else {
            $("#product_info_table tbody").html(html);
            $("#add_row").attr("disabled", false);
          }
          subAmount();

          resetValue();


          $(".tax").select2()
            .on('change', function(e) {
              var row_id = $(this).attr('id').replace('gst_', '');
              getTotal(row_id);

            }).on('select', function(e) {
              var row_id = $(this).attr('id').replace('gst_', '');
              getTotal(row_id)
            });

          initailizeSelect2();

        }
      });

      return false;
    });


    var count_table_tbody_tr = $("#product_info_table tbody tr").length;
    console.log("aagya");
    var i;

    for (i = 1; i < count_table_tbody_tr + 1; i++) {
      getTotal(i);

    }

  }); // /document

  function resetValue() {
    $("#productSelect").val("");
    $("#productSelect :selected").text("");
    $("#makeSelect").val("");
    $("#qtySelect").val("");
    $("#unitSelect").val("");
    $("#rateSelect").val("");
    $("#discountSelect").val("");
    $("#gstSelect").val("").change();
    $("#amountSelect").val("");
  }
  //purana
  // function getTotal(row = null) {
  //   if(row) {
  //     var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
  //     total = total.toFixed(2);
  //     $("#amount_"+row).val(total);
  //     $("#amount_value_"+row).val(total);

  //     subAmount();

  //   } else {
  //     alert('no row !! please refresh the page');
  //   }
  // }

  function getTotal(row = null) {
    if (row) {
      // console.log(row)
      var total = Number($("#rate_" + row).val()) * Number($("#qty_" + row).val());
      // if(!$("#discount_"+row).val()){
      //   temp_total=total;

      // }
      // console.log(Number($("#rate_value_"+row).val()),  Number($("#qty_"+row).val()), total)

      total = Math.round((total + Number.EPSILON) * 100) / 100
      // total = total.toFixed(2);



      // var temp_total = total + ((total/100)* Number($("#gst_"+row).val()));


      // temp_total = Math.round((temp_total + Number.EPSILON) * 100) / 100
      // console.log(temp_total)



      var final_total = total - ((total / 100) * Number($("#discount_" + row).val()));
      final_total = Math.round((final_total + Number.EPSILON) * 100) / 100

      // final_total = final_total.toFixed(2);
      $("#amount_" + row).val(final_total.toFixed(2));
      $("#amount_value_" + row).val(final_total.toFixed(2));



      subAmount();

    } else {
      var total = Number($("#rateSelect").val()) * Number($("#qtySelect").val());
      // if(!$("#discount_"+row).val()){
      //   temp_total=total;

      // }
      // console.log(Number($("#rate_value_"+row).val()),  Number($("#qty_"+row).val()), total)

      total = Math.round((total + Number.EPSILON) * 100) / 100
      // total = total.toFixed(2);



      // var temp_total = total + ((total/100)* Number($("#gst_"+row).val()));


      // temp_total = Math.round((temp_total + Number.EPSILON) * 100) / 100
      // console.log(temp_total)



      var final_total = total - ((total / 100) * Number($("#discountSelect").val()));
      final_total = Math.round((final_total + Number.EPSILON) * 100) / 100

      // final_total = final_total.toFixed(2);
      $("#amountSelect").val(final_total.toFixed(2));
      // $("#amount_value_"+row).val(final_total.toFixed(2));


    }
  }


  function initailizeSelect2() {


    var search;

    $('.product').select2({
      placeholder: "--Select item--",
      width: '100%',
      ajax: {
        type: "GET",
        // dataType: 'json',


        url: function(params) {
          // console.log("ajax func", params);
          var url = base_url + '/orders/getProductfromSearch/' + params.term
          search = params.term;
          return url;
        },

        processResults: function(data, page) {
          // console.log(data);
          // return { results: data };
          return {
            results: $.map(JSON.parse(data), function(item) {
              return {
                text: item.text,
                id: item.id
              }
            })
          };
        },
        minimumInputLength: 1
      }
    }).on('change', function(e) {

      var row_id = $(this).attr('id').replace('product_', '');
      var whichId = $(this).attr('id');
      if (whichId == "productSelect") {
        getSelectProductData();
      } else {
        getProductData(row_id);
      }




      //this.value
    }).on('select', function(e) {
      console.log("select");

    });

  }

  function initailizeParty() {
    var search;

    $('.party_select').select2({
      placeholder: "--Select Party--",
      width: '100%',
      ajax: {
        type: "GET",
        // dataType: 'json',


        url: function(params) {
          // console.log("ajax func", params);
          var url = base_url + '/orders/getPartyfromSearch/' + params.term
          search = params.term;
          return url;
        },

        processResults: function(data, page) {
          // console.log(data);
          // return { results: data };
          return {
            results: $.map(JSON.parse(data), function(item) {
              return {
                text: item.text,
                id: item.id
              }
            })
          };
        },
        minimumInputLength: 1
      }
    });

  }

  // get the product information from the server
  function getProductData(row_id) {
    var product_id = $("#product_" + row_id).val();
    if (product_id == "") {


      $("#rate_" + row_id).val("");
      $("#rate_value_" + row_id).val("");

      $("#qty_" + row_id).val("");

      $("#amount_" + row_id).val("");
      $("#amount_value_" + row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'quotation/getProductValueById',
        type: 'post',
        data: {
          product_id: product_id
        },
        dataType: 'json',
        success: function(response) {
          // setting the rate value into the rate input field


          $("#code_" + row_id).val(response.Item_Code);
          $("#code_value_" + row_id).val(response.Item_Code);

          $("#make_" + row_id).val(response.Item_Make);
          $("#make_value_" + row_id).val(response.Item_Make);

          $("#unit_" + row_id).val(response.sUnit);
          $("#unit_value_" + row_id).val(response.sUnit);

          // $("#gst_"+row_id).val(response.Tax);
          // $("#gst_value_"+row_id).val(response.Tax);

          $("#rate_" + row_id).val(response.Price);
          $("#rate_value_" + row_id).val(response.Price);

          var variation = $("#variation").val();
          if (variation) {
            var rate_new = Number($("#rate_value_" + row_id).val()) + Number(variation) * Number($("#rate_value_" + row_id).val()) / 100;
            $("#rate_" + row_id).val(rate_new);
          }

          $("#qty_" + row_id).val(1);
          $("#qty_value_" + row_id).val(1);

          var total = Number(response.Price) * 1;
          total = total.toFixed(2);
          $("#amount_" + row_id).val(total);
          $("#amount_value_" + row_id).val(total);

          getTotal(row_id);
          // subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }

  function subAmount() {

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    var total_discount = 0;
    var total_gst = 0;
    var x, y, z;


    for (i = 1; i < tableProductLength; i++) {
      var tr = $("#product_info_table tbody tr")[i];
      var count = $(tr).attr('id');
      count = count.substring(4);
      var gst = $("#gst_" + count).find(':selected').attr('data-tax-value');
      if (!gst) {
        gst = 0;
      }
      // console.log(gst);
      var item_amount = $("#amount_" + count).val();
      total_gst = Number(total_gst) + (Number(item_amount) * Number(gst)) / 100;

      // console.log(gst,item_amount);
      x = Number($("#rate_" + count).val()) * Number($("#qty_" + count).val());
      x = x.toFixed(2);
      // y = Number(x) * Number($("#gst_"+count).val())/100;
      // y = y.toFixed(2);
      z = Number(x) * Number($("#discount_" + count).val()) / 100;
      z = z.toFixed(2);
      // console.log(x,y,z);

      // total_gst = Number(total_gst) + Number(y);
      total_discount = Number(total_discount) + Number(z);
      totalSubAmount = Number(totalSubAmount) + Number($("#amount_" + count).val());

    }

    // var tax = $("#tax").find(':selected').attr('data-tax-value');data-code-id
    // console.log(tax);

    // total_gst = Number(totalSubAmount) * Number(tax) /100 
    // total_gst = Number(total_gst);
    // total_gst = total_gst.toFixed(2);

    console.log("tgst", total_gst.toFixed(2), "tdsic", total_discount.toFixed(2))
    // total_discount = total_discount.toFixed(2);


    $("#total_discount").val(total_discount.toFixed(2));
    $("#total_gst").val(total_gst);
    $("#gst_amount").val(total_gst.toFixed(2));
    $("#gst_amount_value").val(total_gst.toFixed(2));
    // /for

    totalSubAmount = totalSubAmount.toFixed(2);

    // sub total
    $("#total").val(totalSubAmount);
    $("#total_value").val(totalSubAmount);

    // // vat
    // var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    // vat = vat.toFixed(2);
    // $("#vat_charge").val(vat);
    // $("#vat_charge_value").val(vat);

    // // service
    // var service = (Number($("#gross_amount").val())/100) * service_charge;
    // service = service.toFixed(2);
    // $("#service_charge").val(service);
    // $("#service_charge_value").val(service);

    // total amount
    // var freight=$("#paid_status").find(':selected').val();
    // console.log(freight);
    var totalAmount = 0;
    // if(freight==0){
    totalAmount = Number(totalSubAmount) + Number($("#other_charge").val());
    // else{
    // totalAmount = Number(totalSubAmount);
    // }

    totalAmount = totalAmount + total_gst;

    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var round_total_amount = Math.round(totalAmount);
    var round_off = round_total_amount - totalAmount;


    $("#roundoff").val(round_off.toFixed(2));
    var discount = $("#discount").val();
    // if(discount) {
    //   var grandTotal = Number(totalAmount) - Number(discount);
    //   grandTotal = grandTotal.toFixed(2);
    //   $("#total_amount").val(grandTotal);
    //   $("#total_amount_value").val(grandTotal);
    // } else {
    $("#total_amount").val(round_total_amount);
    $("#total_amount_value").val(round_total_amount);

    // } // /else discount 

  } // /sub total amount

  function getSelectProductData() {
    var product_id = $('#productSelect').val();


    // $("#code_"+row_id).val(product_id);
    // $("#code_"+row_id).trigger('change');


    if (product_id == "") {
      $("#rateSelect").val("");
      // $("#rate_value_select").val("");

      $("#qtySelect").val("");

      $("#amountSelect").val("");
      // $("#amount_value_"+row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'orders/getProductValueById',
        type: 'post',
        data: {
          product_id: product_id
        },
        dataType: 'json',
        success: function(response) {
          // setting the rate value into the rate input field

          $("#rateSelect").val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);

          $("#codeSelect").val(response.Item_Code);
          // $("#code_"+row_id).trigger('change');
          // $("#code_value_"+row_id).val(response.Item_Code);

          $("#makeSelect").val(response.Item_Make);
          // $("#make_value_"+row_id).val(response.Item_Make);

          $("#unitSelect").val(response.sUnit);
          // $("#unit_value_"+row_id).val(response.sUnit);

          var tax_id = response.iTax_ID;
          $("#gstSelect").val(tax_id).change();

          // $("#gst_"+row_id).val(response.Tax);
          // $("#gst_value_"+row_id).val(response.Tax);

          // $("#rate_"+row_id).val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);

          // $("#rate_"+row_id).val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);

          // $("#rate_"+row_id).val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);


          $("#qtySelect").val(1);
          // $("#qty_value_"+row_id).val(1);

          // var tax = $("#gst_"+row_id).val();


          var total = Number(response.Price) * 1;


          total = total.toFixed(2);
          $("#amountSelect").val(total);
          // $("#amount_value_"+row_id).val(total);

          getTotal();
          // subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }
  //purana sub amount
  // calculate the total amount of the order
  // function subAmount() {

  //   var tableProductLength = $("#product_info_table tbody tr").length;
  //   var totalSubAmount = 0;
  //   for(x = 0; x < tableProductLength; x++) {
  //     var tr = $("#product_info_table tbody tr")[x];
  //     var count = $(tr).attr('id');
  //     count = count.substring(4);

  //     totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
  //   } // /for

  //   totalSubAmount = totalSubAmount.toFixed(2);

  //   // sub total
  //   $("#gross_amount").val(totalSubAmount);
  //   $("#gross_amount_value").val(totalSubAmount);

  //   // vat
  //   var vat = (Number($("#gross_amount").val())/100) * vat_charge;
  //   vat = vat.toFixed(2);
  //   $("#vat_charge").val(vat);
  //   $("#vat_charge_value").val(vat);

  //   // service
  //   var service = (Number($("#gross_amount").val())/100) * service_charge;
  //   service = service.toFixed(2);
  //   $("#service_charge").val(service);
  //   $("#service_charge_value").val(service);

  //   // total amount
  //   var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
  //   totalAmount = totalAmount.toFixed(2);
  //   // $("#net_amount").val(totalAmount);
  //   // $("#totalAmountValue").val(totalAmount);

  //   var discount = $("#discount").val();
  //   if(discount) {
  //     var grandTotal = Number(totalAmount) - Number(discount);
  //     grandTotal = grandTotal.toFixed(2);
  //     $("#net_amount").val(grandTotal);
  //     $("#net_amount_value").val(grandTotal);
  //   } else {
  //     $("#net_amount").val(totalAmount);
  //     $("#net_amount_value").val(totalAmount);

  //   } // /else discount 

  //   var paid_amount = Number($("#paid_amount").val());
  //   if(paid_amount) {
  //     var net_amount_value = Number($("#net_amount_value").val());
  //     var remaning = net_amount_value - paid_amount;
  //     $("#remaining").val(remaning.toFixed(2));
  //     $("#remaining_value").val(remaning.toFixed(2));
  //   }

  // } // /sub total amount

  function paidAmount() {
    var grandTotal = $("#net_amount_value").val();

    if (grandTotal) {
      var dueAmount = Number($("#net_amount_value").val()) - Number($("#paid_amount").val());
      dueAmount = dueAmount.toFixed(2);
      $("#remaining").val(dueAmount);
      $("#remaining_value").val(dueAmount);
    } // /if
  } // /paid amoutn function

  function variationcalc() {
    var tableProductLength = $("#product_info_table tbody tr").length;
    console.log(tableProductLength);
    for (c = 0; c < tableProductLength; c++) {
      var tr = $("#product_info_table tbody tr")[c];
      var count_new = $(tr).attr('id');
      count_new = count_new.substring(4);
      console.log(count_new);

      var variation = $("#variation").val();
      console.log(variation);

      if (variation) {
        var rate_new = Number($("#rate_value_" + count_new).val()) + Number(variation) * Number($("#rate_value_" + count_new).val()) / 100;
        $("#rate_" + count_new).val(rate_new);
        console.log(rate_new);

        getTotal(count_new);
      }

    }
  }


  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_" + tr_id).remove();
    removed_rows_count = Number(removed_rows_count) + 1;
    subAmount();
  }

  function removeTerms(tr_id) {

    $("#terms_info_table tbody tr#terms_" + tr_id).remove();
    removed_row_count_terms = Number(removed_row_count_terms) + 1;
  }

  $(function() {
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();

    if (month < 10)
      month = '0' + month.toString();
    if (day < 10)
      day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    $('#date').attr('max', maxDate);
    $('#ref_date').attr('max', maxDate);

  });

  $(document).on('keyup change', ".total_calculator_qty", function() {
    // do stuff!
    var row_id = $(this).attr('id').replace('qty_', '');
    if (row_id == "qtySelect") {
      row_id = null;

    }
    getTotal(row_id);
  })

  $(document).on('keyup change', ".total_calculator_rate", function() {
    // do stuff!
    var row_id = $(this).attr('id').replace('rate_', '');
    if (row_id == "rateSelect") {
      row_id = null;

    }
    getTotal(row_id);
  })

  $(document).on('keyup change', ".total_calculator_discount", function() {
    // do stuff!
    var row_id = $(this).attr('id').replace('discount_', '');
    if (row_id == "discountSelect") {
      row_id = null;

    }
    getTotal(row_id);
  })



  $(document).on('keyup change', "#other_charge", function() {
    // do stuff!
    subAmount();
  })


  $(document).on('click', ".tandc", function() {
    // do stuff!
    var row_id = $(this).attr('id').replace('remove_', '');
    removeTerms(row_id);
  })


  $(document).on('click', ".removeProduct", function() {
    // do stuff!
    var row_id = $(this).attr('id').replace('removeProduct_', '');
    removeRow(row_id);
  })
</script>