<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->








    <?php if ($is_admin == true) : ?>




      <div class="row">


        <div class="col-lg-12 col-xs-12">
          <!-- small box -->

          <div class="small-box bg-purple">
            <div class="inner">

              <h4>Select Financial Year</h4>


              <form role="form" class="form-inline" method="post" action="financialyear/save">
                <div class="form-group">

                  <select class="form-control select_group product" id="financial_year" name="financial_year" style="width:100%;" required>
                    <option value="" disabled>--Select--</option>
                    <?php foreach ($financial_year as $k => $v) : ?>


                      <option value="<?php echo $v['key_value'] ?>" <?php if (($v['key_value'] == $this->session->userdata("selected_financial_year"))) {
                                                                      echo 'selected="selected"';
                                                                    } ?>><?php echo $v['year_range'] ?></option>
                    <?php endforeach ?>
                  </select>


                </div>


                <button type="submit" class="btn btn-default">Submit</button>


              </form>


            </div>
          </div>
        </div>



        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_products ?></h3>

              <p>Total Products</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?php echo base_url('products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $total_paid_orders ?></h3>

              <p>Total Paid Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?php echo base_url('orders/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_users; ?></h3>

              <p>Total Users</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-people"></i>
            </div>
            <a href="<?php echo base_url('users/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $total_stores ?></h3>

              <p>Total Stores</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-home"></i>
            </div>
            <a href="<?php echo base_url('stores/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
    <?php endif; ?>


  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#dashboardMainMenu").addClass('active');
  });
</script>