<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// if ($_SESSION['usertype']!=1) {
//   header('location:error_404.php');

// }


if (strlen($_SESSION['lssemsaid']==0)) {
  header('location:logout.php');
  } else{



  ?>
<!DOCTYPE html>
<html>
<head>
 
  <title>TomasiKnows | All Reviews</title>
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Reviews</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">All Reviews</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
        
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">All Reviews</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>S.NO</th>
                  <th>Listing Title</th>
                  <th>Name</th>
                  <th>Review Title</th>
                  <th>Date of Review</th>
                  <th>Action</th>
                
                </tr>
                </thead>
                 <?php

          if($_SESSION['usertype'] == 1 ) {

            $sql="select tblreview.ID,tblreview.ListingID,tblreview.ReviewTitle,tblreview.Name,tblreview.DateofReview,tbllisting.ListingTitle from tblreview join tbllisting on tbllisting.ID=tblreview.ListingID";
            $query = $dbh -> prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
  

          }else {
            $sql="            
                SELECT 
                    tblreview.ID,
                    tblreview.ListingID,
                    tblreview.ReviewTitle,
                    tblreview.Name,
                    tblreview.DateofReview,
                    tbllisting.ListingTitle
                FROM 
                    tblreview
                JOIN 
                    tbllisting 
                ON 
                    tbllisting.ID = tblreview.ListingID
                WHERE 
                    tbllisting.ID IN (
                        SELECT ID 
                        FROM tbllisting 
                        WHERE UserID = :UserIDs AND isDeleted = 0
                    );
            
            ";
            $query = $dbh -> prepare($sql);
            $query->execute(['UserIDs'=>$_SESSION['lssemsaid']]);
            $results=$query->fetchAll(PDO::FETCH_OBJ);


          }

    
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                
                <tr>
                  <td><?php echo htmlentities($cnt);?></td>
                  <td><?php  echo htmlentities($row->ListingTitle);?></td>
                  <td><?php  echo htmlentities($row->Name);?></td>
                  <td><?php  echo htmlentities($row->ReviewTitle);?></td>
                  <td><?php  echo htmlentities($row->DateofReview);?></td>
                  <td> <a href="view-reviews.php?rid=<?php echo htmlentities ($row->ID);?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                  
                </tr>
                              
                <?php $cnt=$cnt+1;}} ?> 
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
</body>
</html>
<?php }  ?>