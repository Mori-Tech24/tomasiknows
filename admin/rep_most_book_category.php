<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['lssemsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html>
<head>
  <title>TomasiKnows | Reports</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
            <h1>Most Book Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Most Book Category</li>
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
              <form method="GET">
                <div class="row">
                  <div class="col-md-4">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                  </div>
                  <div class="col-md-4">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                  </div>
                  <div class="col-md-4">
                    <label for="barangay">Barangay:</label>
                    <select name="barangay" id="barangay" class="form-control">
                      <option value="">All</option>
                      <?php
                      $barangayQuery = $dbh->prepare("SELECT DISTINCT(State) FROM tbllisting");
                      $barangayQuery->execute();
                      $barangays = $barangayQuery->fetchAll(PDO::FETCH_OBJ);
                      foreach ($barangays as $barangay) {
                          echo '<option value="' . $barangay->State . '"' . (isset($_GET['barangay']) && $_GET['barangay'] == $barangay->State ? ' selected' : '') . '>' . $barangay->State . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="rep_most_book_category.php" class="btn btn-secondary">Reset</a>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Barangay</th>
                    <th>Total Count</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT 
                              c.Category,
                              b.State,
                              COUNT(b.Category) AS total_count
                          FROM 
                              tbl_reservations a
                          LEFT JOIN 
                              tbllisting b ON b.ID = a.listing_id
                          LEFT JOIN 
                              tblcategory c ON c.ID = b.Category
                          WHERE 
                              a.reservation_status = 1";

                  if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                      $sql .= " AND DATE(a.reservation_date) BETWEEN :start_date AND :end_date";
                  }
                  if (!empty($_GET['barangay'])) {
                      $sql .= " AND b.State = :barangay";
                  }

                  $sql .= " GROUP BY c.Category, b.State";

                  $query = $dbh->prepare($sql);

                  if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                      $query->bindParam(':start_date', $_GET['start_date'], PDO::PARAM_STR);
                      $query->bindParam(':end_date', $_GET['end_date'], PDO::PARAM_STR);
                  }
                  if (!empty($_GET['barangay'])) {
                      $query->bindParam(':barangay', $_GET['barangay'], PDO::PARAM_STR);
                  }

                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);

                  $cnt = 1;
                  if ($query->rowCount() > 0) {
                      foreach ($results as $row) {
                  ?>
                  <tr>
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td><?php echo htmlentities($row->Category); ?></td>
                    <td><?php echo htmlentities($row->State); ?></td>
                    <td><?php echo htmlentities($row->total_count); ?></td>
                  </tr>
                  <?php $cnt++; }} ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php include_once('includes/footer.php'); ?>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<!-- Add DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      dom: 'Bfrtip', // Add this for the buttons
      buttons: [
        {
          extend: 'excelHtml5',
          text: 'Export to Excel',
          filename: function () {
            const now = new Date();
            const formattedDate = now.toISOString().slice(0, 10); // YYYY-MM-DD
            const formattedTime = now.toTimeString().slice(0, 8).replace(/:/g, '-'); // HH-MM-SS
            return `Most_Book_Category_Report_${formattedDate}_${formattedTime}`;
          },
          title: null // Removes the title from the sheet
        }
      ]
    });


  });
</script>
</body>
</html>
<?php } ?>
