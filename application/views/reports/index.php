<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Reports
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Reports</li>
    </ol>
  </section>

  <!-- Invoice Lisiting -->
  <section class="content">
    <div class="row">
      <div class="col-md-6 col-xs-12 pull pull-left">



        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Sale Tax Register</h3>

          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php echo base_url('reports/printDiv') ?>" method="post" class="form-horizontal" target="_blank">

            <div class="box-body">



              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">From</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_from" name="date_from" autocomplete="off" />
                </div>
              </div>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">To</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_to" name="date_to" autocomplete="off" />
                </div>
              </div>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;"></label>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary">Generate Report</button>

                </div>
              </div>

            </div>



          </form>

        </div>

      </div>
      <div class="col-md-6 col-xs-12 ">

<div class="box">
  <div class="box-header">
    <h3 class="box-title">Price List</h3>
  </div>
  <!-- /.box-header -->
  <form role="form" action="<?php echo base_url('reports/priceList') ?>" method="post" class="form-horizontal" target="_blank">

    <div class="box-body">


      <div class="form-group">
        <label for="product" class="col-sm-3 control-label" style="text-align:left;">Select Make</label>
        <div class="col-sm-9">
          <select class="form-control select_group" id="itemMake" name="itemMake" required>

            <?php foreach ($item_make as $k => $v) : ?>
              <option value="<?php echo $v['item_make'] ?>" ><?php echo $v['item_make'] ?></option>
            <?php endforeach ?>
          </select>

        </div>
      </div>


      <div class="form-group">
        <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">Variation %</label>
        <div class="col-sm-9">
          <input type="number" value=0 class="form-control" id="variation" name="variation" autocomplete="off" required />
        </div>
      </div>


      <div class="form-group">
        <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;"></label>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-primary">Generate Report</button>
        </div>
      </div>



    </div>

  </form>

</div>


</div>
    


    </div>

  </section>



  <!-- Items Sold -->
  <section class="content">


    <div class="row">

    <div class="col-md-6 col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class=" box-title">Quotation Listing</h3>
          </div>
          <div class="box-body">

            <form role="form" action="<?php echo base_url('reports/quotationListing') ?>" method="post" class="form-horizontal" target="_blank">

            <div class="form-group">
                <label for="product" class="col-sm-3 control-label" style="text-align:left;">Select Customer</label>
                <div class="col-sm-9">
                  <select class="form-control select_group party_select" id="quotation_partyid" name="quotation_partyid"  required>

              
                  </select>

                </div>
              </div>
              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">From</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_from_quotation" name="date_from_quotation" autocomplete="off" />
                </div>
              </div>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">To</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_to_quotation" name="date_to_quotation" autocomplete="off" />
                </div>
              </div>




              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;"></label>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
              </div>

            </form>
          </div>
        </div>

      </div>

      <div class="col-md-6 col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class=" box-title">Purchase Register</h3>
          </div>
          <div class="box-body">

            <form role="form" action="<?php echo base_url('reports/purchaseRegister') ?>" method="post" class="form-horizontal" target="_blank">

            <div class="form-group">
                <label for="product" class="col-sm-3 control-label" style="text-align:left;">Select Customer</label>
                <div class="col-sm-9">
                  <select class="form-control select_group party_select" id="purchase_partyid" name="purchase_partyid"  required>

                  </select>

                </div>
              </div>
              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">From</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_from_purchase" name="date_from_purchase" autocomplete="off" />
                </div>
              </div>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">To</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_to_purchase" name="date_to_purchase" autocomplete="off" />
                </div>
              </div>    




              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;"></label>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
              </div>

            </form>
          </div>
        </div>

      </div>

    </div>



  </section>



  <!-- PRICE LIST -->
  <section class="content">


    <div class="row">

    
      <div class="col-md-6 col-xs-12 ">

        <div class="box">
          <div class="box-header">
          <h3 class="box-title">Item Sold</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php echo base_url('reports/stockLedger') ?>" method="post" class="form-horizontal" target="_blank">

          <div class="box-body">
            <div class="form-group">
              <label for="product" class="col-sm-3 control-label" style="text-align:left;">Select Item</label>
              <div class="col-sm-9">
                <select class="form-control select_group product" id="product" name="product" required>

                
                </select>


              </div>
            </div>


            <div class="form-group">
              <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">From</label>
              <div class="col-sm-9">
                <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_from_stock" name="date_from_stock" autocomplete="off" />
              </div>
            </div>

            <div class="form-group">
              <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">To</label>
              <div class="col-sm-9">
                <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_to_stock" name="date_to_stock" autocomplete="off" />
              </div>
            </div>


            <div class="form-group">
              <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;"></label>
              <div class="col-sm-9">
                <button type="submit" class="btn btn-primary">Generate Report</button>
              </div>
            </div>



          </div>

          </form>

          </div>


        </div>
     
      <div class="col-md-6 col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class=" box-title">Customer Ledger</h3>
          </div>
          <div class="box-body">

            <form role="form" action="<?php echo base_url('reports/customerLedger') ?>" method="post" class="form-horizontal" target="_blank">

            <div class="form-group">
                <label for="product" class="col-sm-3 control-label" style="text-align:left;">Select Customer</label>
                <div class="col-sm-9">
                  <select class="form-control select_group party_select" id="partyid" name="partyid"  required>

                   
                  </select>

                </div>
              </div>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">From</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_from_customer" name="date_from_customer" autocomplete="off" />
                </div>
              </div>

              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;">To</label>
                <div class="col-sm-9">
                  <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="date_to_customer" name="date_to_customer" autocomplete="off" />
                </div>
              </div>    




              <div class="form-group">
                <label for="gross_amount" class="col-sm-3 control-label" style="text-align:left;"></label>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
              </div>

            </form>
          </div>
        </div>

      </div>

    </div>



  </section>

  <!-- Test -->

  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#reportNav").addClass('active');
  });

  var report_data = <?php echo '[' . implode(',', $results) . ']'; ?>;


  // $(function() {
  //   /* ChartJS
  //    * -------
  //    * Here we will create a few charts using ChartJS
  //    */
  //   var areaChartData = {
  //     labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
  //     datasets: [{
  //       label: 'Electronics',
  //       fillColor: 'rgba(210, 214, 222, 1)',
  //       strokeColor: 'rgba(210, 214, 222, 1)',
  //       pointColor: 'rgba(210, 214, 222, 1)',
  //       pointStrokeColor: '#c1c7d1',
  //       pointHighlightFill: '#fff',
  //       pointHighlightStroke: 'rgba(220,220,220,1)',
  //       data: report_data
  //     }]
  //   }

  //   //-------------
  //   //- BAR CHART -
  //   //-------------
  //   var barChartCanvas = $('#barChart').get(0).getContext('2d')
  //   var barChart = new Chart(barChartCanvas)
  //   var barChartData = areaChartData
  //   barChartData.datasets[0].fillColor = '#00a65a';
  //   barChartData.datasets[0].strokeColor = '#00a65a';
  //   barChartData.datasets[0].pointColor = '#00a65a';
  //   var barChartOptions = {
  //     //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
  //     scaleBeginAtZero: true,
  //     //Boolean - Whether grid lines are shown across the chart
  //     scaleShowGridLines: true,
  //     //String - Colour of the grid lines
  //     scaleGridLineColor: 'rgba(0,0,0,.05)',
  //     //Number - Width of the grid lines
  //     scaleGridLineWidth: 1,
  //     //Boolean - Whether to show horizontal lines (except X axis)
  //     scaleShowHorizontalLines: true,
  //     //Boolean - Whether to show vertical lines (except Y axis)
  //     scaleShowVerticalLines: true,
  //     //Boolean - If there is a stroke on each bar
  //     barShowStroke: true,
  //     //Number - Pixel width of the bar stroke
  //     barStrokeWidth: 2,
  //     //Number - Spacing between each of the X value sets
  //     barValueSpacing: 5,
  //     //Number - Spacing between data sets within X values
  //     barDatasetSpacing: 1,
  //     //String - A legend template
  //     legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
  //     //Boolean - whether to make the chart responsive
  //     responsive: true,
  //     maintainAspectRatio: true
  //   }

  //   barChartOptions.datasetFill = false
  //   barChart.Bar(barChartData, barChartOptions)
  // })

  $(document).ready(function() {
    $(".select_group").select2();
    var base_url = "<?php echo base_url(); ?>";


  
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
  });





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


  });
</script>