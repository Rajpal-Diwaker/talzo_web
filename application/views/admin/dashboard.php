  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/Chart.css">
  <script src="<?php echo base_url();?>assets/js/Chart.min.js" type="text/javascript"></script>      
  <!-- Dashboard Section Start Here-->
  <div class="dashboard_data_wrap">
    <div class="dahaboard_heading">
      <span>Dashboard</span>
    </div>
    <div class="dashbord_cont_wrapper">

     <div class="dashboard_wrap">
      <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
          <div class="dashboard_cont">
            <h2> <?php echo $data['all_users'];?></h2>
            <span>No. of Users</span>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
          <div class="dashboard_cont">
            <h2><?php echo $data['all_posts'];?></h2>
            <span>No. of Posts</span>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
          <div class="dashboard_cont">
            <h2><?php echo $data['all_add'];?></h2>
            <span>Total Advertisements</span>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
          <div class="dashboard_cont">
            <h2><?php echo $data['active_add'];?></h2>
            <span>Active Advertisements</span>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
          <div class="dashboard_cont">
            <h2>500</h2>
            <span>Revenue from Ads</span>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
          <div class="dashboard_cont">
            <h2>2500</h2>
            <span>Revenue from Memberships</span>
          </div>
        </div>
      </div>

      <br>
      <div class="row all_charts_wrap">
        <div class="col-sm-12 col-md-12 col-lg-6 chart_full_width"> 
          <div class="chart_wrap_analytics">
            <div class="chart_heading">
              <h2>Total Revenue</h2>
              <canvas id="myChart" style="height: 300px; width: 100%;"></canvas>
            </div>
          </div> 
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6 chart_full_width">

          <div class="chart_wrap_analytics">
            <div class="chart_heading">
              <h2>Total Users</h2>
             <canvas id="myChart1" style="height: 300px; width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- Dashboard Section Start Here-->
</div>
</div>
<?php 
$second=[];
foreach ($graph as $key => $value) {
 $second[]=$value['y']; 
}
$second_label= '"'.implode('","', $second).'"';

$second_data=[];
foreach ($graph as $key => $value) {
 $second_data[]=$value['a']; 
}
$second_Alldata= '"'.implode('","', $second_data).'"';
?>
<script type="text/javascript">
    //=============first chart============//
    var ctx = document.getElementById('myChart').getContext("2d");
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
    // labels: newmonth,
    labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
    datasets: [{
      label: "New User",
      borderColor: "#fbd45f",
      pointBorderColor: "#fbd45f",
      pointBackgroundColor: "#fe4571",
      pointHoverBackgroundColor: "#fbd45f",
      pointHoverBorderColor: "#485ba0",
      pointBorderWidth: 9,
      pointHoverRadius: 5,
      pointHoverBorderWidth: 1,
      pointRadius: 1,
      fill: false,
      borderWidth: 5,
      // data:newcount
      data:[100,400,200,250,375,500,350,300,400,450,350,700]
    }]
  },
  options: {
    legend: {
      position: ""
    },


    scales: {
      yAxes: [{
        ticks: {
          fontColor: "rgba(0,0,0,0.5)",
          fontStyle: "bold",
          beginAtZero: true,
          maxTicksLimit: 5,
          padding: 20,
          beginAtZero: true,
          min:0,
          // stepSize: 20,
          suggestedMin: 0,
          suggestedMax: 10,
          userCallback: function(label, index, labels) {
           if (Math.floor(label) === label) {
             return label;
           }

         },
       },
       gridLines: {
        drawTicks: false,
        display: true
      }

    }],
    xAxes: [{
      gridLines: {
        zeroLineColor: "transparent",
        display: false
      },
      ticks: {
        padding: 20,
        precision:0,
        fontColor: "rgba(0,0,0,0.5)",
        fontStyle: "bold"
      }
    }]
  }

}
});

//==============second chart====================//
    var ctx = document.getElementById('myChart1').getContext("2d");
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
    // labels: newmonth,
    labels: [<?php echo $second_label;?>],
    datasets: [{
      label: "New User",
      borderColor: "#d48131",
      pointBorderColor: "#0f0101",
      pointBackgroundColor: "#fe4571",
      pointHoverBackgroundColor: "#fbd45f",
      pointHoverBorderColor: "#d48131",
      pointBorderWidth: 9,
      pointHoverRadius: 5,
      pointHoverBorderWidth: 1,
      pointRadius: 1,
      fill: false,
      borderWidth: 5,
      // data:newcount
      data:[<?php echo $second_Alldata;?>]
    }]
  },
  options: {
    legend: {
      position: ""
    },


    scales: {
      yAxes: [{
        ticks: {
          fontColor: "rgba(0,0,0,0.5)",
          fontStyle: "bold",
          beginAtZero: true,
          maxTicksLimit: 5,
          padding: 20,
          beginAtZero: true,
          min:0,
          // stepSize: 20,
          suggestedMin: 0,
          suggestedMax: 10,
          userCallback: function(label, index, labels) {
           if (Math.floor(label) === label) {
             return label;
           }

         },
       },
       gridLines: {
        drawTicks: false,
        display: true
      }

    }],
    xAxes: [{
      gridLines: {
        zeroLineColor: "transparent",
        display: false
      },
      ticks: {
        padding: 20,
        precision:0,
        fontColor: "rgba(0,0,0,0.5)",
        fontStyle: "bold"
      }
    }]
  }

}
});


</script>