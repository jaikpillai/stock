<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Items</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Item Master</li>
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
            <h3 class="box-title">Add Item</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('users/create') ?>" method="post" enctype="multipart/form-data">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <!-- <div class="form-group">

                  <label for="product_image">Image</label>
                  <div class="kv-avatar">
                      <div class="file-loading">
                          <input id="product_image" name="product_image" type="file">
                      </div>
                  </div>
                </div> -->

              <div class="form-group">
                <label for="item_id">Item ID</label>
                <?php foreach ($getid as  $key => $value) :
                  $new_id = $value['MAX(Item_ID)'] + 1 ?>
                  <input value="<?php echo $new_id; ?>" type="text" class="form-control" id="item_id_display" name="item_id_display" autocomplete="off" disabled />
                  <input value="<?php echo $new_id; ?>" type="hidden" class="form-control" id="item_id" name="item_id" autocomplete="off" />

                <?php endforeach ?>


              </div>
              <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control select_group" id="category" name="category">
                  <?php foreach ($category as $k => $v) : ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>

              <div class="form-group">
                <label for="product_name">Item name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Item Name" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="unit">Unit</label>
                <select class="form-control select_group" id="unit" name="unit">
                  <?php foreach ($unit_data as $k => $v) : ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['sUnit'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>

              <!-- <div class="form-group">
                  <label for="unit">Unit</label>
                  <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter Item Unit" autocomplete="off"/>
                </div> -->

              <div class="form-group">
                <label for="item_code">Item Code</label>
                <input type="text" class="form-control" id="item_code" name="item_code" placeholder="Enter Item Code" autocomplete="off" />
              </div>


              <div class="form-group">
                <label for="item_hsn">HSN</label>
                <input type="text" class="form-control" maxlength="12" id="item_hsn" name="item_hsn" placeholder="Enter HSN" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="pack_size">Pack Size</label>
                <input type="text" class="form-control" id="pack_size" name="pack_size" placeholder="Enter Pack Size" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="list_price">List Price</label>
                <input type="number" step="0.01" class="form-control" id="list_price" name="list_price" placeholder="Enter List Price" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="make">Make</label>
                <input type="text" class="form-control" id="make" name="make" placeholder="Enter Maker" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="purchase_rate">Purchase Rate</label>
                <input type="number" step="0.01" class="form-control" id="purchase_rate" name="purchase_rate" placeholder="Enter Purchase Rate" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="opening_balance">Opening Balance</label>
                <input type="number" class="form-control" id="opening_balance" name="opening_balance" placeholder="Enter Opening Balance" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="tax">Tax %</label>
                <select class="form-control select_group" id="tax" name="tax">
                  <?php foreach ($tax_data as $k => $v) : ?>
                    <option value="<?php echo $v['iTax_ID'] ?>"><?php echo $v['sTax_Description'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
              <!-- <div class="form-group">
                  <label for="tax">Tax %</label>
                  <input type="number" step="0.01" class="form-control" id="tax" name="tax" placeholder="Enter Item Tax" autocomplete="off" />
                </div> -->


              <div class="form-group">
                <label for="description">Description</label>
                <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter description" autocomplete="off">
                  </textarea>
              </div>

              <div class="form-group">
                <label for="reorder_level">Reorder Level</label>
                <input type="text" class="form-control" id="reorder_level" name="reorder_level" placeholder="Enter Reorder Level" autocomplete="off" />
              </div>

              <div class="form-group">
                <label for="qty">Qty</label>
                <input type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" autocomplete="off" />
              </div>

              <?php if ($attributes) : ?>
                <?php foreach ($attributes as $k => $v) : ?>
                  <div class="form-group">
                    <label for="groups"><?php echo $v['attribute_data']['name'] ?></label>
                    <select class="form-control select_group" id="attributes_value_id" name="attributes_value_id[]" multiple="multiple">
                      <?php foreach ($v['attribute_value'] as $k2 => $v2) : ?>
                        <option value="<?php echo $v2['id'] ?>"><?php echo $v2['value'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                <?php endforeach ?>
              <?php endif; ?>

              <!-- <div class="form-group">
                  <label for="brands">Group</label>
                  <select class="form-control select_group" id="brands" name="brands[]" multiple="multiple">
                    <?php foreach ($brands as $k => $v) : ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                

               

                <div class="form-group">
                  <label for="store">Availability</label>
                  <select class="form-control" id="availability" name="availability">
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                  </select>
                </div> -->
              <div class="form-group">
                <label for="store">Store</label>
                <select class="form-control select_group" id="store" name="store">
                  <?php foreach ($stores as $k => $v) : ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <a href="<?php echo base_url('products/') ?>" class="btn btn-warning">Back</a>
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

    $("#mainProductNav").addClass('active');
    $("#addProductNav").addClass('active');
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
      layoutTemplates: {
        main2: '{preview} ' + btnCust + ' {remove} {browse}'
      },
      allowedFileExtensions: ["jpg", "png", "gif"]
    });

  });
</script>