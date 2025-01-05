<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION['usertype']!=1) {
  header('location:error_404.php');

}



if (strlen($_SESSION['lssemsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html>
<head>
  <title>TomasiKnows | Manage Registered Users</title>
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
            <h1>Manage Registered Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Manage Registered Users</li>
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
              <h3 class="card-title">Manage Registered Users</h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Mobile Number</th>                  
                    <th>Registration Date</th>
                    <th>Action</th> <!-- Action column -->
                  </tr>
                </thead>
                <?php
                $sql = "SELECT * from tbluser WHERE isDeleted = 0";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                ?>
                <tr>
                  <td><?php echo htmlentities($cnt); ?></td>
                  <td><?php echo htmlentities($row->FullName); ?></td>
                  <td><?php echo htmlentities($row->MobileNumber); ?></td>
                  <td><?php echo htmlentities($row->RegDate); ?></td>
                  <td>
                    <!-- View Icon with Data Attributes for User Details -->
                    <a href="javascript:void(0);" class="viewBtn" 
                      data-name="<?php echo $row->FullName; ?>" 
                      data-mobile="<?php echo $row->MobileNumber; ?>" 
                      data-email="<?php echo $row->Email; ?>" 
                      data-regdate="<?php echo $row->RegDate; ?>" 
                      title="View">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a> 
                    |
                    <!-- Delete Icon -->
                    <a href="delete-user.php?userid=<?php echo $row->ID; ?>" onclick="return confirm('Do you really want to delete this user?');" title="Delete">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </td>
                </tr>

                <!-- Modal for View User Details -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="viewModalLabel">User Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                      <!-- User details will be loaded here dynamically -->
                      <p><strong>Name:</strong> <span id="userName"></span></p>
                      <p><strong>Mobile Number:</strong> <span id="userMobile"></span></p>
                  
                      <p><strong>Registration Date:</strong> <span id="userRegDate"></span></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>

                <?php
                        $cnt++;
                    }
                }
                ?>
              </table>
            </div>
          </div>

          <!-- Business Registration Card -->
          <div class="card mt-4">
  <div class="card-header">
    <h3 class="card-title">Business Registration</h3>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <div class="row mb-3 d-none">
      <div class="col-md-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by Business Name or Email">
      </div>
      <div class="col-md-3">
        <input type="date" id="startDate" class="form-control" placeholder="Start Date">
      </div>
      <div class="col-md-3">
        <input type="date" id="endDate" class="form-control" placeholder="End Date">
      </div>
      <div class="col-md-3">
        <button id="filterBtn" class="btn btn-primary">Filter</button>
      </div>
    </div>
    
    <!-- Table -->
    <table id="businessTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>S.No</th>
          <th>Business Name</th>
          <th>Mobile Number</th>
          <th>Email</th>
          <th>Registration Date</th>
          <th>Approval Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT ID, Business_name, MobileNumber, Email, AdminRegdate, isApproved_Business FROM tbladmin WHERE usertype = 2";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $cnt = 1;
        if ($query->rowCount() > 0) {
          foreach ($results as $row) {
        ?>
        <tr>
          <td><?php echo htmlentities($cnt); ?></td>
          <td><?php echo htmlentities($row->Business_name); ?></td>
          <td><?php echo htmlentities($row->MobileNumber); ?></td>
          <td><?php echo htmlentities($row->Email); ?></td>
          <td><?php echo htmlentities($row->AdminRegdate); ?></td>
          <td><?php echo $row->isApproved_Business == 1 ? 'Approved' : 'Pending'; ?></td>
          <td>
            <?php if ($row->isApproved_Business == 0) { ?>
              <a href="update-business-status.php?businessid=<?php echo $row->ID; ?>&action=approve" onclick="return confirm('Approve this account?');" title="Approve">Approve</a> |
              <a href="update-business-status.php?businessid=<?php echo $row->ID; ?>&action=reject" onclick="return confirm('Reject this account?');" title="Reject">Reject</a>
            <?php } else { ?>
              <?php echo $row->isApproved_Business == 1 ? '<span class="text-success">Approved</span>' : '<span class="text-danger">Rejected</span>'; ?>
            <?php } ?>
          </td>
        </tr>
        <?php
            $cnt++;
          }
        }
        ?>
      </tbody>
    </table>
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
    $("#example1").DataTable();


    var table = $('#businessTable').DataTable();

  $('#filterBtn').click(function() {
    var searchValue = $('#searchInput').val().toLowerCase();
    var startDate = $('#startDate').val() + " 00:00:01";
    var endDate = $('#endDate').val() + " 23:59:59";

    table.draw();

    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
        var businessName = data[1].toLowerCase();
        var email = data[3].toLowerCase();
        var regDate = data[4];

        if (
          (searchValue === '' || businessName.includes(searchValue) || email.includes(searchValue)) &&
          (startDate === '' || regDate >= startDate) &&
          (endDate === '' || regDate <= endDate)
        ) {
          return true;
        }
        return false;
      }
    );
    table.draw();
  });
    $('.viewBtn').click(function() {
        // Get the data from the clicked icon
        var userName = $(this).data('name');
        var userMobile = $(this).data('mobile');
        var userEmail = $(this).data('email');
        var userRegDate = $(this).data('regdate');
        
        // Set the data into the modal
        $('#userName').text(userName);
        $('#userMobile').text(userMobile);
        $('#userEmail').text(userEmail);
        $('#userRegDate').text(userRegDate);

        // Show the modal
        $('#viewModal').modal('show');
    });
  });
</script>
</body>
</html>
<?php } ?>
