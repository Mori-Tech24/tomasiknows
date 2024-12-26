<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['lssemsaid'] == 0)) {
    header('location:logout.php');
    exit();
}

// Check if the 'userid' parameter is passed in the URL
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Validate that the ID is an integer (or adjust based on your needs)
    if (filter_var($userid, FILTER_VALIDATE_INT)) {
        // Prepare the delete query
        $sql = "UPDATE tbluser SET isDeleted = 1 WHERE ID = :userid";

        // Prepare the statement
        $query = $dbh->prepare($sql);

        // Bind the parameter to prevent SQL injection
        $query->bindParam(':userid', $userid, PDO::PARAM_INT);

        // Execute the query
        $result = $query->execute();

        // Check if the deletion was successful
        if ($result) {
            // Redirect back to the user management page with a success message
            echo "<script>alert('User deleted successfully');window.location='reg-users.php';</script>";
        } else {
            // Show an error message if deletion fails
            echo "<script>alert('Error deleting user');window.location='reg-users.php';</script>";
        }
    } else {
        // If the ID is not valid, show an error message
        echo "<script>alert('Invalid user ID');window.location='reg-users.php';</script>";
    }
} else {
    // If 'userid' is not set in the URL, show an error
    echo "<script>alert('User ID not provided');window.location='reg-users.php';</script>";
}
?>
