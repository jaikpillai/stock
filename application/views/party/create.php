

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add
      <small>Party</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Party Master</li>
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
            <h3 class="box-title">Add Party</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('party/create') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <?php echo validation_errors(); ?>

             

                <div class="form-group">
                  <label for="item_id">Party ID</label>
                  <?php foreach ($getid as  $key => $value): 
                     $new_id =$value['MAX(party_id)']+1?>
                    <input value = "<?php echo $new_id;?>"<?php echo $new_id; ?>type="text"  class="form-control" id="party_id" name="party_id" placeholder="" autocomplete="off" disabled/>
                  <?php endforeach ?>

                </div>

             

                <div class="form-group">
                  <label for="party_name">Party name</label>
                  <input type="text" class="form-control" id="party_name" name="party_name" placeholder="Enter Party Name" autocomplete="off"/>
                </div>

                <div class="form-group">
                  <label for="party_address">Party Address</label>
                  <input type="text" class="form-control" id="party_address" name="party_address" placeholder="Enter Party Address" autocomplete="off"/>
                </div>

                <div class="form-group">
                  <label for="state">State Address</label>
                  <select class="form-control select_group" name="state" id="state" class="form-control">
                      <option value="Andhra Pradesh">Andhra Pradesh</option>
                      <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                      <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                      <option value="Assam">Assam</option>
                      <option value="Bihar">Bihar</option>
                      <option value="Chandigarh">Chandigarh</option>
                      <option value="Chhattisgarh">Chhattisgarh</option>
                      <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                      <option value="Daman and Diu">Daman and Diu</option>
                      <option value="Delhi">Delhi</option>
                      <option value="Lakshadweep">Lakshadweep</option>
                      <option value="Puducherry">Puducherry</option>
                      <option value="Goa">Goa</option>
                      <option value="Gujarat">Gujarat</option>
                      <option value="Haryana">Haryana</option>
                      <option value="Himachal Pradesh">Himachal Pradesh</option>
                      <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                      <option value="Jharkhand">Jharkhand</option>
                      <option value="Karnataka">Karnataka</option>
                      <option value="Kerala">Kerala</option>
                      <option value="Madhya Pradesh">Madhya Pradesh</option>
                      <option value="Maharashtra">Maharashtra</option>
                      <option value="Manipur">Manipur</option>
                      <option value="Meghalaya">Meghalaya</option>
                      <option value="Mizoram">Mizoram</option>
                      <option value="Nagaland">Nagaland</option>
                      <option value="Odisha">Odisha</option>
                      <option value="Punjab">Punjab</option>
                      <option value="Rajasthan">Rajasthan</option>
                      <option value="Sikkim">Sikkim</option>
                      <option value="Tamil Nadu">Tamil Nadu</option>
                      <option value="Telangana">Telangana</option>
                      <option value="Tripura">Tripura</option>
                      <option value="Uttar Pradesh">Uttar Pradesh</option>
                      <option value="Uttarakhand">Uttarakhand</option>
                      <option value="West Bengal">West Bengal</option>
                      </select>
                </div>

                <div class="form-group">
                  <label for="contact_person">Contact Person</label>
                  <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Enter Contact Person Name" autocomplete="off"/>
                </div>

                <div class="form-group">
                  <label for="contact_number">Contact Number</label>
                  <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter Contact Person Number" autocomplete="off"/>
                </div>

                <div class="form-group">
                  <label for="is_vendor">Is Vender</label>
                  <select class="form-control select_group" name="is_vendor" id="is_vendor" class="form-control">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                      </select>
                </div>

                <div class="form-group">
                  <label for="is_customer">Is Customer</label>
                  <select class="form-control select_group" name="is_customer" id="is_customer" class="form-control">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                      </select>
                </div>

                <div class="form-group">
                  <label for="email_id">Email</label>
                  <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Enter Email" autocomplete="off"/>
                </div>

                <div class="form-group">
                  <label for="gst_number">GST Number</label>
                  <input type="text" class="form-control" id="gst_number" name="gst_number" placeholder="Enter GST Number" autocomplete="off"/>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('party/') ?>" class="btn btn-warning">Back</a>
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
  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainPartyNav").addClass('active');
    $("#addPartyNav").addClass('active');
  $("#mainMasterForm").addClass('active');

    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
    $("#product_image").fileinput({
        overwriteInitial: true,
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        browseLabel: '',
        removeLabel: '',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-1',
        msgErrorClass: 'alert alert-block alert-danger',
        // defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar">',
        layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });

  });
</script>