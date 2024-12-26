<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');


if ($_SESSION['usertype']!=1) {
  header('location:error_404.php');

} 

if (strlen($_SESSION['lssemsaid']==0)) {
  header('location:logout.php');
  } else{


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
      $id = intval($_POST['delete_id']); // Ensure the ID is an integer
  
      $sql = "UPDATE tblcontact SET isDeleted = 1 WHERE ID = :id";
      $query = $dbh->prepare($sql);
      $query->bindParam(':id', $id, PDO::PARAM_INT);
  
      if ($query->execute()) {
          echo json_encode(['success' => true]);
      } else {
          http_response_code(500); // Server error
      }
      exit();
  }

  ?>
<!DOCTYPE html>
<html>
<head>
 
  <title>TomasiKnows | Read Enquiry</title>
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
            <h1>Read Enquiry</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Read Enquiry</li>
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
              <h3 class="card-title">Read Enquiry</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>S.NO</th>
                 <th>Name</th>
                    <th>Email</th>
                    <th>Enquiry Date</th>
                  <th>Action</th>
                
                </tr>
                </thead>
                 <?php
$sql="select * from tblcontact where IsRead='1' AND isDeleted = 0";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                
                <tr>
                  <td><?php echo htmlentities($cnt);?></td>
                  <td><?php  echo htmlentities($row->Name);?></td>
                  <td><?php  echo htmlentities($row->Email);?></td>
                  <td><?php  echo htmlentities($row->EnquiryDate);?></td>
                  <td> <a href="view-enquiry.php?vid=<?php echo htmlentities ($row->ID);?>"><i class="fa fa-eye" aria-hidden="true"></i></a> | 
                  <a href="#" onclick="confirmDelete(<?php echo htmlentities($row->ID); ?>)">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </a>
                    
                </td>
                  
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
<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this enquiry?")) {
        // Send the delete request via AJAX
        $.ajax({
            url: '', // Use the same page
            type: 'POST',
            data: { delete_id: id },
            success: function(response) {
                // Reload the table or update UI dynamically
                alert("Enquiry deleted successfully!");
                location.reload(); // Reload to reflect changes
            },
            error: function() {
                alert("Failed to delete enquiry.");
            }
        });
    }
}
</script>


</body>
</html>
<?php }  ?>