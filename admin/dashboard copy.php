<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html>
<head>
 
  <title>TomasiKnows | Dashboard</title>
 
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<style>
  .fixed-height-chart {
    height: 400px; /* Adjust this value as needed */
    max-height: 400px;
}
</style>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">


  <?php include_once('includes/header.php');?>

 
<?php include_once('includes/sidebar.php');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-4">
           <?php 

            if($_SESSION['usertype'] == 1) {

                $sql ="SELECT ID from tblcategory ";
                $query = $dbh -> prepare($sql);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $totalcat=$query->rowCount();
            }else {

                $sql ="SELECT ID FROM tbllisting WHERE UserID = :userid AND isDeleted = 0 GROUP BY Category";
                $query = $dbh -> prepare($sql);
                $query->execute(['userid'=>$_SESSION['lssemsaid']]);
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $totalcat=$query->rowCount();

            }



            ?>

       
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo htmlentities($totalcat);?></h3>

                <p>Total Category</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="manage-category.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-4 <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
            <?php 
$sql ="SELECT ID from tbluser ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$totperson=$query->rowCount();
?>
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo htmlentities($totperson);?><sup style="font-size: 20px"></sup></h3>

                <p>Total Reg Person</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="reg-users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

    <!-- <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?> -->
    <!-- <?php echo $_SESSION['lssemsaid']; ?> -->



         <div class="col-lg-4 col-4">
           <?php 

              if ($_SESSION['usertype'] == 1) {
                  $sql ="SELECT ID from tbllisting ";
                  $query = $dbh -> prepare($sql);
                  $query->execute();
                  $results=$query->fetchAll(PDO::FETCH_OBJ);
                  $totallisting=$query->rowCount();

              }else {

                $sql ="SELECT * FROM tbllisting WHERE UserID =:userid2 AND isDeleted = 0";
                $query = $dbh -> prepare($sql);
                $query->execute(['userid2'=>$_SESSION['lssemsaid'] ]);
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $totallisting=$query->rowCount();


              }



            ?>





       
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo htmlentities($totallisting);?></h3>

                <p>Total Listing</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="listing.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>


        <div class="row <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Most Booked Business</h3>
                <div class="card-tools">
                    <div class="row">
                        <!-- Barangay Dropdown -->
                        <div class="col-md-5 mb-2">
                            <select id="barangayDropdown" class="form-control">
                                <option value="">All Barangay</option>
                                <?php 
                                // Fetch distinct barangays from tbllisting table
                                $sql = "SELECT DISTINCT State AS barangay FROM tbllisting";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                foreach ($results as $row) {
                                    echo '<option value="' . $row->barangay . '">' . $row->barangay . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Date Range Picker -->
                        <div class="col-md-7 mb-2">
                            <input type="text" id="daterange" class="form-control" placeholder="Select Date Range">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="businessChart" class="fixed-height-chart"></canvas>
            </div>
        </div>
    </div>
</div>



        <div class="row <?php echo ($_SESSION['usertype']) == 1 ? "" : "d-none"?>">
          <div class="col-12">
              <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                      <h3 class="card-title">Most Booked Category</h3>

                      <div class="col-md-5 mb-2" style="max-width: 400px;">
                            <select id="barangayDropdownCategory" class="form-control">
                                <option value="">All Barangay</option>
                                <?php 
                                // Fetch distinct barangays from tbllisting table
                                $sql = "SELECT DISTINCT State AS barangay FROM tbllisting";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                foreach ($results as $row) {
                                    echo '<option value="' . $row->barangay . '">' . $row->barangay . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                      <!-- Date Range Filter -->
                      <div class="input-group" style="max-width: 400px;">
                          <input type="date" id="categoryStartDate" class="form-control" placeholder="Start Date">
                          <input type="date" id="categoryEndDate" class="form-control" placeholder="End Date">
                          <button id="filterCategoryDateBtn" class="btn btn-primary">Filter</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                      <!-- Canvas for Chart -->
                      <canvas id="categoryChart" class="fixed-height-chart"></canvas>
                  </div>
                  <!-- /.card-body -->
              </div>
              <!-- /.card -->
          </div>
          <!-- /.col -->
      </div>




        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>

 

    <!-- /.content -->
  </div>

  
  <!-- /.content-wrapper -->
  <?php include_once('includes/footer.php');?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>

<script>
  let categoryChart = null;
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('businessChart').getContext('2d');
        let businessChart = null;

        // Initialize the date range picker
        $('#daterange').daterangepicker({
            locale: { format: 'YYYY-MM-DD' },
            opens: 'left'
        });

        // Function to fetch and update the chart
        function updateChart(startDate, endDate, barangay) {
            $.ajax({
                url: 'fetch_booked_business.php',
                type: 'POST',
                data: { start_date: startDate, end_date: endDate,      barangay: barangay  },
                dataType: 'json',
                success: function (response) {
                    const labels = response.map(data => data.Business_name);
                    const dataPoints = response.map(data => data.total_count);

                    if (businessChart) {
                        businessChart.destroy(); // Destroy previous chart instance
                    }

                    // Create a new chart
                    businessChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Number of Bookings',
                                data: dataPoints,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Ensure height remains fixed
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            });
        }
        const initialRange = $('#daterange').data('daterangepicker');
        // Fetch initial data
        const initialBarangay = $('#barangayDropdown').val(); // Get selected barangay

        // Update chart with initial data
        updateChart(initialRange.startDate.format('YYYY-MM-DD'), initialRange.endDate.format('YYYY-MM-DD'), initialBarangay);

        // Update chart on date range change
        $('#daterange').on('apply.daterangepicker', function (ev, picker) {
            const selectedBarangay = $('#barangayDropdown').val(); // Get selected barangay
            updateChart(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'), selectedBarangay);
        });

        $('#barangayDropdown').on('change', function () {
        const selectedBarangay = $(this).val(); // Get selected barangay
        const selectedRange = $('#daterange').data('daterangepicker'); // Get selected date range
        updateChart(selectedRange.startDate.format('YYYY-MM-DD'), selectedRange.endDate.format('YYYY-MM-DD'), selectedBarangay);
    });
    });


    function loadCategoryChart(startDate = '', endDate = '', barangayDropdownCategory = '') {
    $.ajax({
        url: 'fetch_category_data.php', // Replace with your PHP file for data
        method: 'POST',
        data: { startDate: startDate, endDate: endDate, barangayDropdownCategory: barangayDropdownCategory },
        dataType: 'json',
        success: function (data) {
            // Clear existing chart if it exists
            if (categoryChart) {
                categoryChart.destroy();
            }

            const labels = data.map(item => item.Category); // Extract category names
            const dataPoints = data.map(item => item.total_count); // Extract booking counts

            const ctx = document.getElementById('categoryChart').getContext('2d');
            categoryChart = new Chart(ctx, {
                type: 'bar', // Chart type (e.g., bar, pie, line)
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Bookings',
                        data: dataPoints,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Ensure fixed height
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
}

// Load chart on page load
loadCategoryChart();

// Add event listener for date range filter
$('#filterCategoryDateBtn').on('click', function () {
    const startDate = $('#categoryStartDate').val(); // Get start date input value
    const endDate = $('#categoryEndDate').val(); // Get end date input value
    const barang = $('#barangayDropdown').val(); // Get end date input value
    loadCategoryChart(startDate, endDate, barang);
});

</script>