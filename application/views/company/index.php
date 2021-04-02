<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Company</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">company</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

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
            <h3 class="box-title">Manage Company Information</h3>
          </div>
          <form role="form" action="<?php base_url('company/update') ?>" method="post">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter company name" value="<?php echo $company_data['company_name'] ?>" autocomplete="off">
              </div>
              <!-- 
                <div class="form-group">
                    <label for="financial_year">Financial Year</label>
                   
                    <select class="form-control select_group product" id="party" name="party" style="width:100%;" required>
                            <option value="" disabled selected>--Select--</option>
                            <?php foreach ($party_data as $k => $v) : ?>
                              <option value="<?php echo $v['party_id'] ?>"><?php echo $v['party_name'] ?></option>
                            <?php endforeach ?>
                          </select>
                      
                 
                  </div> -->
              <div class="form-group">
                <label for="gst_number">GST Number</label>
                <input type="text" class="form-control" id="gst_number_value" name="gst_number_value" placeholder="Enter charge amount %" value="<?php echo $company_data['gst_no'] ?>" autocomplete="off">
              </div>

              <div class="form-group">
                <label for="gst_number">Bank Details</label>
                <table class="table table-bordered" id="bank_info_table">
                  <thead>
                    <tr>
                      <th>S.No.</th>
                      <th>Bank Name</th>
                      <th>Bank Address</th>
                      <th>Account Number</th>
                      <th>IFSC Code</th>
                      <th><button type="button" id="add_row" class="btn btn-primary">Add +</button></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php $x = 1; ?>
                    <?php foreach ($bank_details as $k => $v) : ?>
                      <tr id="row_<?php echo $x; ?>">
                        <td>
                          <h6><?php echo $k + 1 ?></h6>
                        </td>
                        <td>
                          <input type="text" class="form-control" id="bank_name_'<?php echo $x; ?>'" name="bank_name[]" placeholder="Bank Name" value="<?php echo $v['bank_name'] ?>" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" class="form-control" id="bank_address_'<?php echo $x; ?>'" name="bank_address[]" placeholder="Address" value="<?php echo $v['bank_address'] ?>" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" class="form-control" id="bank_account_number_'<?php echo $x; ?>'" name="bank_account_number[]" placeholder="Account Number" value="<?php echo $v['acc_no'] ?>" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" class="form-control " id="bank_ifsc_'<?php echo $x; ?>'" name="bank_ifsc[]" placeholder="IFSC" value="<?php echo $v['ifsc'] ?>" autocomplete="off">
                        </td>

                        <td><button type="button" class="btn btn-danger" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close"></i></button></td>
                      </tr>
                      <?php $x++; ?>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>

              <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" value="<?php echo $company_data['address'] ?>" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="<?php echo $company_data['phone'] ?>" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" value="<?php echo $company_data['country'] ?>" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="permission">Message</label>
                <textarea class="form-control" id="message" name="message">
                     <?php echo $company_data['message'] ?>
                  </textarea>
              </div>
              <div class="form-group">
                <label for="currency">Currency</label>
                <?php ?>
                <select class="form-control" id="currency" name="currency">
                  <option value="">~~SELECT~~</option>

                  <?php foreach ($currency_symbols as $k => $v) : ?>
                    <option value="<?php echo trim($k); ?>" <?php if ($company_data['currency'] == $k) {
                                                              echo "selected";
                                                            } ?>><?php echo $k ?></option>
                  <?php endforeach ?>
                </select>
              </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
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
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $("#companyNav").addClass('active');
    $("#message").wysihtml5();
    $("#mainAdmin").addClass('active');
  });
</script>


<script type="text/javascript">
  $(document).ready(function() {

    var count_table_tbody_tr = $("#bank_info_table tbody tr").length;
    var row_id = count_table_tbody_tr + 1;

    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#bank_info_table");
      var count_table_tbody_tr = $("#bank_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1 + Number(removed_rows_count);
      var s_no = row_id - removed_rows_count;

      if (count_table_tbody_tr < 2) {
        var html = '<tr id="row_' + row_id + '">' +
          '  <td><h6>' + s_no + '</h6></td>' +
          '<td><input type="text" class="form-control col" id="bank_name_' + row_id + '" name="bank_name[]" placeholder="Bank Name"  autocomplete="off"></td>' +
          '<td><input type="text" class="form-control col" id="bank_address_' + row_id + '" name="bank_address[]" placeholder="Address"  autocomplete="off"></td>' +
          '<td><input type="text" class="form-control col" id="bank_account_number_' + row_id + '" name="bank_account_number[]" placeholder="Account Number"  autocomplete="off"></td>' +
          '<td><input type="text" class="form-control col" id="bank_ifsc_' + row_id + '" name="bank_ifsc[]" placeholder="IFSC" autocomplete="off"></td>' +
          '<td><button type="button" class="btn btn-danger" onclick="removeRow(' + row_id + ')"><i class="fa fa-close"></i></button></td>' +
          '</tr>';

        if (count_table_tbody_tr >= 1) {
          $("#bank_info_table tbody tr:last").after(html);
        } else {
          $("#bank_info_table tbody").html(html);
        }
      }
      // $(".product").select2();
    });
  });



  function removeRow(tr_id) {
    $("#bank_info_table tbody tr#row_" + tr_id).remove();
    removed_rows_count = Number(removed_rows_count) + 1;
  }
</script>