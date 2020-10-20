

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Products</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Products</li>
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
            <h3 class="box-title">Edit Product</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('users/update') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <!-- <div class="form-group">
                  <label>Image Preview: </label>
                  <img src="<?php echo base_url() . $product_data['image'] ?>" width="150" height="150" class="img-circle">
                </div> -->

                <!-- <div class="form-group">
                  <label for="product_image">Update Image</label>
                  <div class="kv-avatar">
                      <div class="file-loading">
                          <input id="product_image" name="product_image" type="file">
                      </div>
                  </div>
                </div> -->

                <div class="form-group">
                  <label for="item_id">Item ID</label>
                  <input type="text" class="form-control" id="item_id" name="item_id" value="<?php echo $product_data['Item_ID']; ?>" placeholder="" autocomplete="off" disabled/>
                </div>

                <div class="form-group">
                  <label for="category">Category</label>
                  <?php $category_data = json_decode($product_data['Category_ID']); ?>
                  <select class="form-control select_group" id="category" name="category">
                    <?php foreach ($category as $k => $v): ?>
                      <option value="<?php echo $product_data['Category_ID'] ?>" <?php if(in_array($product_data['Category_ID'], $v)) { echo 'selected="selected"'; } ?>><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>


                <div class="form-group">
                  <label for="product_name">Item name</label>
                  <input type="text" class="form-control" id="product_name" value="<?php echo $product_data['Item_Name']; ?>" name="product_name" placeholder="Enter Item Name" autocomplete="off"/>
                </div>

                <div class="form-group">
                  <label for="unit">Unit</label>
                  <select class="form-control select_group" id="unit" name="unit" >
                    <?php foreach ($unit_data as $k => $v): ?>
                      <option value="<?php echo $product_data['sUnit'] ?>" <?php if(in_array($unit_data_sUnit['sUnit'] , $v)) { echo 'selected="selected"'; } ?>><?php echo $v['sUnit'] ?></option>

                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="item_code">Item Code</label>
                  <input type="text" class="form-control" id="item_code" name="item_code" value="<?php echo $product_data['Item_Code']; ?>" placeholder="Enter Item Code" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="pack_size">Pack Size</label>
                  <input type="text" class="form-control" id="pack_size" name="pack_size" value="<?php echo $product_data['Pack_Size']; ?>" placeholder="Enter Pack Size" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="list_price">List Price</label>
                  <input type="text" class="form-control" id="list_price" name="list_price" value="<?php echo $product_data['Price']; ?>" placeholder="Enter List Price" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="make">Make</label>
                  <input type="text" class="form-control" id="make" name="make" value="<?php echo $product_data['Item_Make']; ?>" placeholder="Enter Maker" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="purchase_rate">Purchase Rate</label>
                  <input type="text" class="form-control" id="purchase_rate" value="<?php echo $product_data['Purchase_Price']; ?>" name="purchase_rate" placeholder="Enter Purchase Rate" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="opening_balance">Opening Balance</label>
                  <input type="text" class="form-control" id="opening_balance" value="<?php echo $product_data['Opening_Balance']; ?>" name="opening_balance" placeholder="Enter Opening Balance" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="tax">Tax %</label>
                  <select class="form-control select_group" id="tax" name="tax[]" multiple="multiple">
                    <?php foreach ($category as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea type="text" class="form-control" id="description" value="<?php echo $product_data['Item_Description']; ?>" name="description" placeholder="Enter 
                  description" autocomplete="off">
                  </textarea>
                </div>

                <div class="form-group">
                  <label for="reorder_level">Reorder Level</label>
                  <input type="text" class="form-control" id="reorder_level" value="<?php echo $product_data['ReOrder_Level']; ?>" name="reorder_level" placeholder="Enter Reorder Level" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="qty">Qty</label>
                  <input type="text" class="form-control" id="qty" name="qty" value="<?php echo $product_data['Max_Suggested_Qty']; ?>" placeholder="Enter Qty" autocomplete="off" />
                </div>



              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('users/') ?>" class="btn btn-warning">Back</a>
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
    $("#manageProductNav").addClass('active');
    
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