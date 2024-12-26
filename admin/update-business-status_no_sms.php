<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['businessid']) && isset($_GET['action'])) {
    $businessId = intval($_GET['businessid']);
    $action = $_GET['action'];

    // Determine the status based on the action parameter
    if ($action === 'approve') {
        $status = 1; // Approved
        $message = "Business account approved successfully.";
    } elseif ($action === 'reject') {
        $status = 2; // Rejected
        $message = "Business account rejected successfully.";
    } else {
        $_SESSION['msg'] = "Invalid action.";
        header('location:reg-users.php');
        exit();
    }

    // Update the status in the database
    $sql = "UPDATE tbladmin SET isApproved_Business = :status WHERE ID = :businessid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->bindParam(':businessid', $businessId, PDO::PARAM_INT);

    if ($query->execute()) {
        $_SESSION['msg'] = $message;
    } else {
        $_SESSION['msg'] = "Failed to update the business account status.";
    }

    // Redirect back to the registered users page
    header('location:reg-users.php');
    exit();
} else {
    $_SESSION['msg'] = "Required parameters are missing.";
    header('location:reg-users.php');
    exit();
}
?>
