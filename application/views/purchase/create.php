

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Purchase Transactions</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Purchase Transaction</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Add Purchase</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('purchase/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php   date_default_timezone_set('Asia/Kolkata'); echo date('Y-m-d'); ?></label>
                </div>
                <!-- <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php   date_default_timezone_set('Asia/Kolkata'); echo date('h:i a'); ?></label>
                </div> -->

                <div class="col-md-4 col-xs-12 pull pull-left">

                <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">PO Number</label>
                    <div class="col-sm-7">
                    <?php foreach ($getlastpurchaseid as  $key => $value): 
                     $new_id =$value['MAX(purchase_no)']+1?>
                    <input value = "<?php echo $new_id;?>" type="text"  class="form-control" id="purchase_no" name="purchase_no" placeholder="" autocomplete="off" readonly/>
                  <?php endforeach ?>
                  
                      <!-- <input type="text" class="form-control" id="invoice_no" name="invoice_no" disabled autocomplete="off" /> -->
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Party</label>
                    <div class="col-sm-7">
                    <select class="form-control select_group product" id="party" name="party" style="width:100%;" required>
                            <option value="" disabled selected>--Select--</option>
                            <?php foreach ($party_data as $k => $v): ?>
                              <option value="<?php echo $v['party_id'] ?>"><?php echo $v['party_name'] ?></option>
                            <?php endforeach ?>
                          </select>
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Payment Mode</label>
                    <div class="col-sm-7">
                    <select class="form-control select_group product" id="paymode" name="paymode" style="width:100%;" required>
                    <option value="" disabled selected>--Select--</option>
  
                    <option value="Cash"  >Cash</option>
                    <option value="Credit"  >Credit</option>
                    <option value="Personally"  >Personally</option>
                    <option value="Cheque"  >Cheque</option>
                    <option value="UPI"  >UPI</option>
                   
  
                        </select>
                      
                    </div>
                </div>  


                </div>

               

               

                <div class="col-md-4 col-xs-12 pull pull-left">

                <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Date</label>
                    <div class="col-sm-7">
                      <input type="date" class="form-control" id="date" name="date"  autocomplete="off" />
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Reference Number</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="ref_no" name="ref_no" placeholder="Enter Reference Number" autocomplete="off">
                      <input type="date" class="form-control" id="ref_date" name="ref_date"  autocomplete="off" />

                    </div>
                  </div>
                  
                  
                   
                </div>
                
                
                
                
                <br /> <br/>
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr>                   
                      
                      <th style="width:20%">Description</th>
                      <th style="width:10%">Code</th>
                      <th style="width:10%">Make</th>
                      <th style="width:10%">Qty</th>
                      <th style="width:5%">Unit</th>
                      <th style="width:10%">Rate</th>
                      <th style="width:10%">Disc. %</th>
                      <!-- <th style="width:5%">Tax %</th> -->
                      <th style="width:20%">Amount</th>      
                      <th style="width:10%"><button type="button" id="add_row" class="btn btn-default"><i class="fa fa-plus"></i></button></th>
                    </tr>
                  </thead>

                   <tbody>
                     <tr id="row_1">
                    
                        <!-- <td><input type="text" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal(1)"></td> -->

                        
                        <td>
                        <select class="form-control select_group product" data-row-id="row_1" id="product_1" name="product[]" style="width:100%;" onchange="getProductData(1)" required>
                            <option value=""></option>
                            <?php foreach ($products as $k => $v): ?>
                              <option value="<?php echo $v['Item_ID'] ?>"><?php echo $v['Item_Name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                        <td>
                          <input type="text" name="code[]" id="code_1" class="form-control" disabled autocomplete="off">
                          <input type="hidden" name="code_value[]" id="code_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" name="make[]" id="make_1" class="form-control" disabled autocomplete="off">
                          <input type="hidden" name="make_value[]" id="make_value_1" class="form-control" autocomplete="off">
                        </td>
                        <!-- <td>
                          <input type="number" name="qty[]" id="qty_1" class="form-control"  autocomplete="off">
                          <input type="hidden" name="qty_value[]" id="qty_value_1" class="form-control" autocomplete="off">
                        </td> -->

                        <td>
                        <input type="number" name="qty[]" id="qty_1" class="form-control" onchange="getTotal(1)" onkeyup="getTotal(1)">
                        
                        </td>


                        <td>
                          <input type="text" name="unit[]" id="unit_1" class="form-control" disabled autocomplete="off">
                          <input type="hidden" name="unit_value[]" id="unit_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="number" name="rate[]" id="rate_1" class="form-control" onchange="getTotal(1)" onkeyup="getTotal(1)" autocomplete="off">
                          <input type="hidden" name="rate_value[]" id="rate_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="number" name="discount[]"  id="discount_1" class="form-control" onchange="getTotal(1)" onkeyup="getTotal(1)" autocomplete="off">
                         
                        </td>
                        <!-- <td>
                          <input type="number" name="gst[]" id="gst_1" class="form-control" disabled autocomplete="off">
                          <input type="hidden" name="gst_value[]" id="gst_value_1" class="form-control" autocomplete="off">
                        </td> -->
                        <td>
                          <input type="number" name="amount[]" id="amount_1" class="form-control" disabled autocomplete="off">
                          <input type="hidden" name="amount_value[]" id="amount_value_1" class="form-control" autocomplete="off">
                        </td>
                        
                        <td><button type="button" class="btn btn-default" onclick="removeRow(1)"><i class="fa fa-close"></i></button></td>
                     </tr>
                   </tbody>
                </table>

                <br /> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">

                  <!-- <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                    </div>
                  </div> -->

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
                    <label for="other_charge" class="col-sm-5 control-label">Tax</label>
                    <div class="col-sm-7">
                    <select class="form-control select_group product" id="tax"  name = "tax" style="width:100%;" onchange="subAmount()" required>
                            <!-- <option value=""></option> -->
                            <option value="" selected disabled>--Select--</option>
                            <?php foreach ($tax_data as $k => $v): ?>
                              <option value="<?php echo $v['iTax_ID'] ?> " data-tax-value="<?php echo $v['sValue'] ?> "> <?php echo $v['sTax_Description'] ?> </option>
                            <?php endforeach ?>
                          </select>
                    </div>
                  </div>

                 
                  <div class="form-group">
                    <label for="other_charge" class="col-sm-5 control-label">Freight/Others</label>
                    <div class="col-sm-7">
                      <input type="number" class="form-control" id="other_charge" name="other_charge" onkeyup="subAmount()" onchange="subAmount()"  autocomplete="off">
                      <input type="hidden" class="form-control" id="other_charge_value" name="other_charge_value" autocomplete="off">
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
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
                <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">
                <button type="submit" class="btn btn-primary">Create Purchase</button>
                <a href="<?php echo base_url('purchase/') ?>" class="btn btn-warning">Back</a>
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
var removed_row_count =0;
</script>

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#mainPurchaseNav").addClass('active');
    $("#addPurchaseNav").addClass('active');
    $("#mainOrderManage").addClass('active');

    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 


  
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1 + Number(removed_row_count);

      $.ajax({
          url: base_url + '/purchase/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
            
              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'">'+
                   
                       
                   '<td>'+ 
                   
                    '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" onchange="getProductData('+row_id+')">'+

                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                          html += '<option value="'+value.Item_ID+'">'+value.Item_Name+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="text" name="code[]" id="code_'+row_id+'" class="form-control" disabled><input type="hidden" name="code_value[]" id="code_value_'+row_id+'" class="form-control"></td>'+

                    '<td><input type="text" name="make[]" id="make_'+row_id+'" class="form-control" disabled><input type="hidden" name="make_value[]" id="make_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')" onchange="getTotal('+row_id+')"></td>'+
                    '<td><input type="text" name="unit[]" id="unit_'+row_id+'" class="form-control" disabled><input type="hidden" name="unit_value[]" id="unit_value_'+row_id+'" class="form-control"></td>'+                    
                    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control" onchange="getTotal('+row_id+')" onkeyup="getTotal('+row_id+')"><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="discount[]"  id="discount_'+row_id+'" onkeyup="getTotal('+row_id+')" onchange="getTotal('+row_id+')" class="form-control" ><input type="hidden" name="discount_value[]" id="discount_value_'+row_id+'" class="form-control"></td>'+
                    // '<td><input type="text" name="gst[]" id="gst_'+row_id+'" class="form-control" disabled><input type="hidden" name="gst_value[]" id="gst_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" disabled><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
                    '<td><button type="button" class="btn btn-default" onclick="removeRow('+row_id+')"><i class="fa fa-close"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) { 
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              $(".product").select2();

          }
        });

      return false;
    });

  }); // /document

  function getTotal(row = null) {
    if(row) {
      // console.log(row)
      var total = Number($("#rate_"+row).val()) * Number($("#qty_"+row).val());
        // if(!$("#discount_"+row).val()){
      //   temp_total=total;
       
      // }
      // console.log(Number($("#rate_value_"+row).val()),  Number($("#qty_"+row).val()), total)
      
       total = Math.round((total + Number.EPSILON) * 100) / 100
      // total = total.toFixed(2);
      


      // var temp_total = total + ((total/100)* Number($("#gst_"+row).val()));
   

      // temp_total = Math.round((temp_total + Number.EPSILON) * 100) / 100
      // console.log(temp_total)

     
     
      var final_total = total - ((total/100)* Number($("#discount_"+row).val()));
      final_total = Math.round((final_total + Number.EPSILON) * 100) / 100

      // final_total = final_total.toFixed(2);
      $("#amount_"+row).val(final_total.toFixed(2));
      $("#amount_value_"+row).val(final_total.toFixed(2));

      
      
      subAmount();

    } else {

      alert('no row !! please refresh the page');
    }
  }

  // get the product information from the server
  function getProductData(row_id)
  {
    var product_id = $("#product_"+row_id).val();    
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");

      $("#qty_"+row_id).val("");           

      $("#amount_"+row_id).val("");
      $("#amount_value_"+row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'purchase/getProductValueById',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
          
          $("#rate_"+row_id).val(response.Price);
          $("#rate_value_"+row_id).val(response.Price);

          $("#code_"+row_id).val(response.Item_Code);
          $("#code_value_"+row_id).val(response.Item_Code);

          $("#make_"+row_id).val(response.Item_Make);
          $("#make_value_"+row_id).val(response.Item_Make);

          $("#unit_"+row_id).val(response.sUnit);
          $("#unit_value_"+row_id).val(response.sUnit);

          // $("#gst_"+row_id).val(response.Tax);
          // $("#gst_value_"+row_id).val(response.Tax);

          // $("#rate_"+row_id).val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);

          // $("#rate_"+row_id).val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);

          // $("#rate_"+row_id).val(response.Price);
          // $("#rate_value_"+row_id).val(response.Price);


          $("#qty_"+row_id).val(1);
          $("#qty_value_"+row_id).val(1);

          // var tax = $("#gst_"+row_id).val();
          

          var total = Number(response.Price) * 1;
         

          total = total.toFixed(2);
          $("#amount_"+row_id).val(total);
          $("#amount_value_"+row_id).val(total);
          
          getTotal(row_id);
          // subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    // var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    // var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    var total_discount=0;
    var total_gst =0;
    var x,y,z;


    for(i = 0; i < tableProductLength; i++) {
      var tr = $("#product_info_table tbody tr")[i];
      var count = $(tr).attr('id');
      count = count.substring(4);

      x = Number($("#rate_"+count).val()) * Number($("#qty_"+count).val());
      x  = x.toFixed(2);
      // y = Number(x) * Number($("#gst_"+count).val())/100;
      // y = y.toFixed(2);
      z = Number(x) * Number($("#discount_"+count).val())/100;
      z= z.toFixed(2);
      // console.log(x,y,z);

      // total_gst = Number(total_gst) + Number(y);
      total_discount = Number(total_discount) + Number(z);
      totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
      
    }

    var tax = $("#tax").find(':selected').attr('data-tax-value');
    console.log(tax);

    total_gst = Number(totalSubAmount) * Number(tax) /100 
    // total_gst = total_gst.toFixed(2);

    console.log("tgst",total_gst.toFixed(2),"tdsic", total_discount.toFixed(2))
    // total_discount = total_discount.toFixed(2);


    $("#total_discount").val(total_discount);
    $("#total_gst").val(total_gst);
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
    var totalAmount = (Number(totalSubAmount) + Number($("#other_charge").val()));
    totalAmount = totalAmount + total_gst;
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var round_total_amount = Math.round(totalAmount);
    var round_off =  round_total_amount - totalAmount;

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

  }// /sub total amount



  function removeRow(tr_id)
  {

    $("#product_info_table tbody tr#row_"+tr_id).remove();
    removed_row_count = Number(removed_row_count) + 1;
    subAmount();

  }
</script>