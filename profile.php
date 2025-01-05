<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['lssemsuid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $uid = $_SESSION['lssemsuid'];
        $fname = $_POST['fname'];

        $sql = "UPDATE tbluser SET FullName=:fname WHERE ID=:uid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':uid', $uid, PDO::PARAM_STR);
        $query->execute();
        echo '<script>alert("Your profile has been updated")</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>TomasiKnows || Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/set1.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include_once('includes/header.php'); ?>
    <section class="subpage-bg">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="titile-block title-block_subpage">
                        <h2>Profile</h2>
                        <p> <a href="index.php"> Home</a> / Profile</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="main-block">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post">
                                <?php
                                $uid = $_SESSION['lssemsuid'];
                                $sql = "SELECT * FROM tbluser WHERE ID=:uid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) { ?>
                                        <div class="payment-wrap">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="payment-title">
                                                        <span class="ti-files text-primary"></span>
                                                        <h4>Profile Information</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control add-listing_form" value="<?php echo $row->FullName; ?>" name="fname" required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control add-listing_form" value="<?php echo $row->MobileNumber; ?>" name="mobno" readonly="true" maxlength="10" pattern="[0-9]+">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Registration Date</label>
                                                        <input type="text" class="form-control add-listing_form" value="<?php echo $row->RegDate; ?>" readonly="true">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                                <div class="payment-wrap">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="accordion" class="payment-method-collapse">
                                                <button type="submit" class="btn btn-primary" name="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px;">Update NOW</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details Section -->
    <section class="main-block">
        <div class="container-fluid">
            <div class="row justify-content-center">
   
                    <!-- Booking Details -->
                    <div class="col-md-12">
                        <h4>Booking Details</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <!-- Hidden Column for # -->
                                    <th style="display:none;">#</th>
                                    <th>Listing Title</th>
                                    <th>Reservation Date</th>
                                    <th>Reservation Purpose</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th> <!-- Renamed Column -->
                                    <th>Action</th> <!-- New Column -->
                                    <!-- Hidden User ID Column -->
                                    <th style="display:none;">User ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $uid = $_SESSION['lssemsuid'];
                                $sql = "SELECT
                                            a.id,
                                            a.user_id,
                                            b.ListingTitle,
                                            CONCAT(a.reservation_date, ' ', a.reservation_time) AS reservationdate,
                                            a.reservation_purpose,
                                            a.date_submitted,
                                            a.reservation_status
                                        FROM 
                                            tbl_reservations a
                                        LEFT JOIN
                                            tbllisting b ON b.ID = a.listing_id
                                        WHERE a.user_id = :uid
                                        ORDER BY a.id DESC
                                        ";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                        echo '<tr>';
                                        // Hidden # column
                                        echo '<td style="display:none;">' . htmlentities($cnt) . '</td>';
                                        echo '<td>' . htmlentities($row->ListingTitle) . '</td>';
                                        echo '<td>' . htmlentities($row->reservationdate) . '</td>';
                                        echo '<td>' . htmlentities($row->reservation_purpose) . '</td>';
                                        echo '<td>' . htmlentities($row->date_submitted) . '</td>';

                                        // Conditional Badge for Reservation Status
                                        $statusBadge = '';
                                        switch ($row->reservation_status) {
                                            case 0:
                                                $statusBadge = '<span class="badge badge-warning">Pending</span>';
                                                break;
                                            case 1:
                                                $statusBadge = '<span class="badge badge-success">Approved</span>';
                                                break;
                                            case 2:
                                                $statusBadge = '<span class="badge badge-danger">Rejected</span>';
                                                break;
                                            case 3:
                                                    $statusBadge = '<span class="badge badge-info">Cancelled</span>';
                                                    break;
                                            default:
                                                $statusBadge = '<span class="badge badge-secondary">Unknown</span>';
                                        }
                                        echo '<td>' . $statusBadge . '</td>';

                                        // Cancel Button: Show only if status is Pending (0)
                                        $cancelButton = '';
                                        if ($row->reservation_status == 0) {
                                            $cancelButton = '<button class="btn btn-danger btn-sm" onclick="cancelReservation(' . $row->id . ')">Cancel</button>';
                                        }
                                        echo '<td>' . $cancelButton . '</td>';

                                        // Hidden User ID column
                                        echo '<td style="display:none;">' . htmlentities($row->user_id) . '</td>';
                                        echo '</tr>';
                                        $cnt++;
                                    }
                                } else {
                                    echo '<tr><td colspan="7">No records found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                  
     
            </div>
        </div>
    </section>






    <?php include_once('includes/footer.php'); ?>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        // JavaScript function to handle the cancellation
        function cancelReservation(reservationId) {
            // Here you can implement the AJAX request to cancel the reservation
            if (confirm("Are you sure you want to cancel this reservation?")) {
                // Example of how you might send the cancel request using AJAX
                $.ajax({
                    url: "cancel_reservation.php",
                    method: "POST",
                    data: { id: reservationId },
                    success: function(response) {
                        alert(response); // Show the response from the server (success message)
                        location.reload(); // Reload the page to reflect the changes
                    },
                    error: function() {
                        alert("An error occurred while trying to cancel the reservation.");
                    }
                });
            }
        }
    </script>
</body>
</html>
<?php } ?>
