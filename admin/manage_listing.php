<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION['usertype']!=2) {
  header('location:error_404.php');

}

if(isset($_GET['delid']))
{
$rid=$_GET['delid'];
$sql="UPDATE tbllisting set isDeleted = 1 where ID=:rid";
$query=$dbh->prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->execute();
 echo "<script>alert('Listing deleted');</script>"; 
  echo "<script>window.location.href = 'manage_listing.php'</script>";     


}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Local Services Search Engine Mgmt System | Reservations</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include_once('includes/header.php'); ?>
  <?php include_once('includes/sidebar.php'); ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Listing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Manage Listing</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Manage Listing</h3>
            </div>

            <!-- Tabs -->
                <div class="card-body">

                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr><th>#</th>
                                                    <th>Listing Title</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Listing Date</th>
                                                    <th>Action</th>
                                                    
                                                </tr>
                                </thead>


                                <tbody>
                                <?php 
$userid=$_SESSION['lssemsuid'];                                     
$ret="SELECT * from tbllisting where UserID=:userid AND isDeleted = 0";
$query = $dbh -> prepare($ret);
$query->bindParam(':userid', $_SESSION['lssemsaid'],PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt);?></td>
                                                        <td><?php  echo htmlentities($row->ListingTitle);?></td>
                                                        </td><td><?php  echo htmlentities($row->Email);?></td><td><?php  echo htmlentities($row->Phone);?></td>
                                                        <td><?php  echo htmlentities($row->ListingDate);?></td>
                                                        <td><a href="edit_listing.php?editid=<?php echo htmlentities ($row->ID);?>">Edit</a> | <a href="manage_listing.php?delid=<?php echo ($row->ID);?>" onclick="return confirm('Do you really want to Delete ?');">Delete</a></td>
                                                        
                                                    </tr>
                                                   <?php $cnt=$cnt+1;}} ?> 
                              
                                </tbody>
                            </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function () {
    $('#pendingTable').DataTable();
    $('#approvedTable').DataTable();
    $('#rejectedTable').DataTable();
  });
</script>

</body>
</html>

