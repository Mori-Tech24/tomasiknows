<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// if ($_SESSION['usertype']==1) {
//   header('location:error_404.php');

// } 



if (strlen($_SESSION['lssemsaid'] == 0)) {
  header('location:logout.php');
} else {
  // Handle action (Approve or Reject)
  if (isset($_GET['action_id'])) {
    $rid = intval($_GET['action_id']);
    $action = $_GET['action']; // Either 'approve' or 'reject'
    $action_by = $_SESSION['lssemsaid'];  // Logged-in user ID
    $action_date = date('Y-m-d H:i:s');  // Current date and time
    $reservation_remarks = $_GET['reject_reason'];  // Current date and time


    // Determine the new status based on the action
    if ($action == 'approve') {
        $new_status = 1;  // Approved status
        $message = "Your reservation has been approved. Thank you!";
    } elseif ($action == 'reject') {
        $new_status = 2;  // Rejected status
        $message = "Your reservation has been rejected. Please contact us for more information.";
    }

    try {
        // Fetch the mobile number from tbl_reservations
        $stmtMobile = $dbh->prepare("SELECT mobile_no FROM tbl_reservations WHERE id = :rid LIMIT 1");
        $stmtMobile->bindParam(':rid', $rid, PDO::PARAM_INT);
        $stmtMobile->execute();
        $result = $stmtMobile->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $mobileNumber = $result['mobile_no'];

            // Format the mobile number for Semaphore
            if (substr($mobileNumber, 0, 1) == '0') {
                $mobileNumber = "+63" . substr($mobileNumber, 1);
            }

            // Update the reservation status, action_by, and action_date
            $sql = "UPDATE tbl_reservations 
                    SET reservation_status = :status, action_by = :action_by, action_date = :action_date, reservation_remarks = :reservation_remarks
                    WHERE id = :rid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':status', $new_status, PDO::PARAM_INT);
            $query->bindParam(':action_by', $action_by, PDO::PARAM_INT);
            $query->bindParam(':action_date', $action_date, PDO::PARAM_STR);
            $query->bindParam(':reservation_remarks', $reservation_remarks, PDO::PARAM_STR);
            $query->bindParam(':rid', $rid, PDO::PARAM_INT);
            $query->execute();

            // Send SMS via Semaphore
            $apiKey = "1def7651eddb998a05492b48938afb61"; // Replace with your Semaphore API key
            $senderName = "TomasiKnows"; // Optional: Replace with your desired sender name

            // sema start
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, "https://api.semaphore.co/api/v4/messages");
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            //     'apikey' => $apiKey,
            //     'number' => $mobileNumber,
            //     'message' => $message,
            //     'sendername' => $senderName,
            // ]));
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // $response = curl_exec($ch);
            // curl_close($ch);
            // sema end


            // Decode Semaphore response and handle errors if necessary
            echo "<script>alert('Reservation Updated.');</script>";
            // $responseDecoded = json_decode($response, true);
            // if (isset($responseDecoded['status']) && $responseDecoded['status'] == "Queued") {
            //     echo "<script>alert('Reservation updated successfully and SMS sent.');</script>";
            // } else {
            //     echo "<script>alert('Reservation updated successfully, but SMS failed to send.');</script>";
            // }

            // Redirect to the reservations page after updating
            echo "<script>window.location.href = 'reservations.php';</script>";
        } else {
            echo "<script>alert('Mobile number not found for this reservation.');</script>";
            echo "<script>window.location.href = 'reservations.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error updating reservation: " . $e->getMessage() . "');</script>";
        echo "<script>window.location.href = 'reservations.php';</script>";
    }
}
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
            <h1>Reservations</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Reservations</li>
            </ol>
          </div>
        </div>
      </div>
    </section>



<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Reject Reservation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="rejectForm" method="GET">

          <input type="hidden" name="action_id" id="action_id">
          
          <input type="hidden" name="action" id="action" value="reject">

          
          <input type="hidden" id="reject_reservation_id" name="reservation_id">
          <div class="mb-3">
            <label for="reject_reason" class="form-label">Reason for Rejection</label>
            <textarea class="form-control" id="reject_reason" name="reject_reason" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-danger">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>


    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Reservations</h3>
            </div>

            <!-- Tabs -->
            <div class="card-body">
              <ul class="nav nav-tabs" id="reservationTabs" role="tablist">
                <li class="nav-item <?php echo ($_SESSION['usertype']) == 1 ? "d-none" : ""?>">
                  <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">Rejected</a>
                </li>
              </ul>

              <div class="tab-content" id="reservationTabsContent">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                  <table id="pendingTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Fullname</th>
                        <th>Mobile NO.</th>
                        <th>Listing Title</th>
                        <th>Reservation Date</th>
                        <th>Reservation Purpose</th>
                        <th>Date Submitted</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                                  

$sql = "SELECT 
a.id, 
c.Fullname, 
b.ListingTitle, 
a.mobile_no,
CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate, 
a.reservation_purpose, 
a.date_submitted, 
a.reservation_status
FROM 
tbl_reservations a
LEFT JOIN 
tbllisting b ON b.ID = a.listing_id
LEFT JOIN 
tbluser c ON c.id = a.user_id
WHERE 
a.reservation_status = 0
AND a.listing_id IN (SELECT id FROM tbllisting WHERE UserID = ".$_SESSION['lssemsaid'].")";


                            $sql = "SELECT 
                            a.id, 
                            c.Fullname, 
                            b.ListingTitle, 
                            a.mobile_no,
                            CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate, 
                            a.reservation_purpose, 
                            a.date_submitted, 
                            a.reservation_status
                        FROM 
                            tbl_reservations a
                        LEFT JOIN 
                            tbllisting b ON b.ID = a.listing_id
                        LEFT JOIN 
                            tbluser c ON c.id = a.user_id
                        WHERE 
                            a.reservation_status = 0
                            AND a.listing_id IN (SELECT id FROM tbllisting WHERE UserID = ".$_SESSION['lssemsaid'].")";

                      $query = $dbh->prepare($sql);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      $cnt = 1;
                      foreach ($results as $row) {
                      ?>
                        <tr>
                          <td><?php echo htmlentities($cnt); ?></td>
                          <td><?php echo htmlentities($row->Fullname); ?></td>
                          <td><?php echo htmlentities($row->mobile_no); ?></td>
                          <td><?php echo htmlentities($row->ListingTitle); ?></td>
                          <td><?php echo htmlentities($row->reservationdate); ?></td>
                          <td><?php echo htmlentities($row->reservation_purpose); ?></td>
                          <td><?php echo htmlentities($row->date_submitted); ?></td>
                          <td>
                            <a href="reservations.php?action_id=<?php echo htmlentities($row->id); ?>&action=approve" 
                               onclick="return confirm('Are you sure you want to approve this reservation?');">
                               <i class="fa fa-check" aria-hidden="true"></i> Approve
                            </a> |
                            <!-- <a href="reservations.php?action_id=<?php echo htmlentities($row->id); ?>&action=reject" 
                               onclick="return confirm('Are you sure you want to reject this reservation?');">
                               <i class="fa fa-times" aria-hidden="true"></i> Reject
                            </a> -->
                            <a href="#" class="reject-btn" data-id="<?php echo htmlentities($row->id); ?>">
                                <i class="fa fa-times" aria-hidden="true"></i> Reject
                            </a>
                          </td>
                        </tr>
                      <?php 
                        $cnt++;
                      }
                      ?>
                    </tbody>
                  </table>
                </div>

                <!-- Approved Tab -->
                <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                  <table id="approvedTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Fullname</th>
                        <th>Mobile No.</th>
                        <th>Listing Title</th>
                        <th>Reservation Date</th>
                        <th>Reservation Purpose</th>
                        <th>Date Submitted</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                    if($_SESSION['usertype'] == 1) {

                      $sql = "SELECT a.id, c.Fullname, b.ListingTitle, 
                          a.mobile_no,
                        CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate, 
                        a.reservation_purpose, a.date_submitted, a.reservation_status
                        FROM tbl_reservations a
                        LEFT JOIN tbllisting b ON b.ID = a.listing_id
                        LEFT JOIN tbluser c ON c.id = a.user_id
                        WHERE a.reservation_status = 1
                      
                        ";
                        

                      
                    }else {

                      $sql = "SELECT a.id, c.Fullname, b.ListingTitle, 
                                a.mobile_no,
                              CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate, 
                              a.reservation_purpose, a.date_submitted, a.reservation_status
                              FROM tbl_reservations a
                              LEFT JOIN tbllisting b ON b.ID = a.listing_id
                              LEFT JOIN tbluser c ON c.id = a.user_id
                              WHERE a.reservation_status = 1
                              AND a.listing_id IN (SELECT id FROM tbllisting WHERE UserID = ".$_SESSION['lssemsaid'].")
                              ";
                              


                    }


                              
                              
                              
                           // Approved reservations (status = 1)
                      $query = $dbh->prepare($sql);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      $cnt = 1;
                      foreach ($results as $row) {
                      ?>
                        <tr>
                          <td><?php echo htmlentities($cnt); ?></td>
                          <td><?php echo htmlentities($row->Fullname); ?></td>
                          <td><?php echo htmlentities($row->mobile_no); ?></td>
                          <td><?php echo htmlentities($row->ListingTitle); ?></td>
                          <td><?php echo htmlentities($row->reservationdate); ?></td>
                          <td><?php echo htmlentities($row->reservation_purpose); ?></td>
                          <td><?php echo htmlentities($row->date_submitted); ?></td>
                          <td>Approved</td>
                        </tr>
                      <?php 
                        $cnt++;
                      }
                      ?>
                    </tbody>
                  </table>
                </div>

                <!-- Rejected Tab -->
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                  <table id="rejectedTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Fullname</th>
                        <th>Mobile No.</th>
                        <th>Listing Title</th>
                        <th>Reservation Date</th>
                        <th>Reservation Purpose</th>
                        <th>Date Submitted</th>
                        <th>Reject Remarks</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      if($_SESSION['usertype'] == 1) {


                        $sql = "SELECT a.id, c.Fullname, b.ListingTitle, a.mobile_no,
                        CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate, 
                        a.reservation_purpose, a.date_submitted, a.reservation_status
                        FROM tbl_reservations a
                        LEFT JOIN tbllisting b ON b.ID = a.listing_id
                        LEFT JOIN tbluser c ON c.id = a.user_id
                        WHERE a.reservation_status = 2
                       ";
                                            
                      }else {


                      $sql = "SELECT a.id, c.Fullname, b.ListingTitle, a.mobile_no,
                              CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate, 
                              a.reservation_purpose, a.date_submitted, a.reservation_status, a.reservation_remarks
                              FROM tbl_reservations a
                              LEFT JOIN tbllisting b ON b.ID = a.listing_id
                              LEFT JOIN tbluser c ON c.id = a.user_id
                              WHERE a.reservation_status = 2
                              AND a.listing_id IN (SELECT id FROM tbllisting WHERE UserID = ".$_SESSION['lssemsaid'].")
                              
                              "; 
                      }
                              
                              // Rejected reservations (status = 2)
                      $query = $dbh->prepare($sql);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      $cnt = 1;
                      foreach ($results as $row) {
                      ?>
                        <tr>
                          <td><?php echo htmlentities($cnt); ?></td>
                          <td><?php echo htmlentities($row->Fullname); ?></td>
                          <td><?php echo htmlentities($row->mobile_no); ?></td>
                          <td><?php echo htmlentities($row->ListingTitle); ?></td>
                          <td><?php echo htmlentities($row->reservationdate); ?></td>
                          <td><?php echo htmlentities($row->reservation_purpose); ?></td>
                          <td><?php echo htmlentities($row->date_submitted); ?></td>
                          <td><?php echo htmlentities($row->reservation_remarks); ?></td>
                          <td>Rejected</td>
                        </tr>
                      <?php 
                        $cnt++;
                      }
                      ?>
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

    $(document).on("click", ".reject-btn" , function (){ 
      var actid = $(this).data("id");
      $("#action_id").val(actid);
      $("#rejectModal").modal("show");
    });
  
    // document.querySelectorAll('.reject-btn').forEach(button => {
    //     button.addEventListener('click', function() {
    //         let reservationId = this.getAttribute('data-id');
    //         document.getElementById('reject_reservation_id').value = reservationId;
    //     });
    // });

    // // Handle Form Submission
    // document.getElementById('rejectForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     let formData = new FormData(this);

    //     fetch('reject_reservation.php', {
    //         method: 'POST',
    //         body: formData
    //     })
    //     .then(response => response.text())
    //     .then(data => {
    //         alert(data); // Display response message
    //         location.reload(); // Reload the page to update the table
    //     })
    //     .catch(error => console.error('Error:', error));
    // });
  });
</script>

</body>
</html>

