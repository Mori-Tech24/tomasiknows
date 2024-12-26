<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION['usertype']!=2) {
  header('location:error_404.php');

}

if (strlen($_SESSION['lssemsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html>
<head>
  <title>TomasiKnows | Search And View Business</title>
  <!-- Tell the browser to be responsive to screen width -->
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include_once('includes/header.php');?>
  <?php include_once('includes/sidebar.php');?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Search And View Business</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Search And View Business</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Search And View Business</h3>
            </div>
            <div class="card-body">
                <form name="search" action="search-listing.php" method="post">
                    <div class="search-box">
                        <div class="row">
                            <div class="col-md-5 search-box_line">
                                <div class="search-box1">
                                    <div class="search-box-title shadow-box">
                                        <select class="search-form form-control" id="categories" name="categories" style="color:black">
                                        <option class="options" value="" style="color:black">--Select Category--</option>
                                            <option class="options" value="all-categories" style="color:black">All categories</option>
                                            <?php 
                                            $sql2 = "SELECT * from tblcategory";
                                            $query2 = $dbh->prepare($sql2);
                                            $query2->execute();
                                            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                                            foreach($result2 as $row) {          
                                            ?>  
                                            <option style="color:black" value="<?php echo htmlentities($row->ID); ?>"><?php echo htmlentities($row->Category); ?></option>
                                            <?php } ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="search-box2 ">
                                    <div class="search-box-title shadow-box">
                                        <input class="form-control" type="text" id="location" name="location"required="true" placeholder="Where"> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" style="color:white !important" type="button" id="search" name="search">
                                    Search
                                </button>  
                            </div>
                        </div>
                    </div>
                    <div class="btn-search">
                                               
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include_once('includes/footer.php');?>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
  $(function () {
    $(document).on("click", "#search", function () {
        var categories = $("#categories").val().trim(); // Trim to remove any extra spaces
        var location = $("#location").val().trim(); // Trim to remove any extra spaces

        if(categories == "" && location == "") {
            alert("Please Select Category or Enter a Location.");
            return; 

        }
        var redirectUrl = "search_listing.php?categories=" + encodeURIComponent(categories) + "&location=" + encodeURIComponent(location);
        window.location.href = redirectUrl;
    });


    
  });
</script>
</body>
</html>
<?php } ?>
