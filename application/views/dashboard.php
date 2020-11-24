

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


  
      
    


      
      <?php if($is_admin == true): ?>




        <div class="row">


        <div class="col-lg-12 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
             
              <h4>Select Financial Year</h4>
              
          
          <form class="form-inline" action="<?php echo base_url('reports/') ?>" method="POST">
            <div class="form-group">
              <!-- <label for="date">Select Financial Year</label> -->
              <select class="form-control" name="select_year" id="select_year">
                <?php $yearCurrent = date("Y"); $dates = range('2014', date('Y')-1);  $finalcialYearCurrent = date("Y") . '-' . ($yearCurrent+1)?>
                     

                  <?php
                    foreach($dates as $date){


                      if (date('m', strtotime($date)) <= 6) {//Upto June
                          $year = ($date-1) . '-' . $date;
                      } else {//After June
                          $year = $date . '-' . ($date + 1);
                      }
                  
                      echo "<option value='$year'>$year</option>";

                  }
                      ?>
                <option value="<?php echo $finalcialYearCurrent ?>" <?php   echo "selected";   ?>><?php echo $finalcialYearCurrent ?></option>
                
                <?php $test = substr($finalcialYearCurrent, 0, 4); echo "<script>var te = '".  $test ."';console.log(te)</script>"?>

              </select>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        

              </div>
             
              <!-- <a href="<?php echo base_url('products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
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
