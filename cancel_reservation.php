<?php

include('includes/dbconnection.php');

if (isset($_POST['id'])) {
    $reservationId = $_POST['id'];

    // Step 1: Retrieve the current reservation status
    $sql = "SELECT reservation_status FROM tbl_reservations WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $reservationId, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $currentStatus = $result['reservation_status'];

        // Step 2: Check if the status is Pending (0) before canceling
        if ($currentStatus == 0) {
            // Proceed with the cancellation (set status to Cancelled)
            $updateSql = "UPDATE tbl_reservations SET reservation_status = 3 WHERE id = :id";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':id', $reservationId, PDO::PARAM_INT);

            if ($updateQuery->execute()) {
                echo "Reservation cancelled successfully.";
            } else {
                echo "Failed to cancel reservation.";
            }
        } else {
            // If the status is not Pending, do not allow cancellation
            if ($currentStatus == 1) {
                echo "Cannot cancel. The reservation has already been approved.";
            } elseif ($currentStatus == 2) {
                echo "Cannot cancel. The reservation has been rejected.";
            } else {
                echo "Cannot cancel. Unknown reservation status.";
            }
        }
    } else {
        echo "Reservation not found.";
    }
}
?>
