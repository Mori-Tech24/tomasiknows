<?php
session_start();
include('includes/dbconnection.php');

function sendSMS($mobileNumber, $message)
{
    $apiKey = "1def7651eddb998a05492b48938afb61"; // Replace with your Semaphore API key
    $url = "https://api.semaphore.co/api/v4/messages";

    $data = [
        'apikey' => $apiKey,
        'number' => $mobileNumber,
        'message' => $message,
        'sendername' => 'TomasiKnows' // Optional: Replace with your registered sender name
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return false; // Failed to send SMS
    }

    curl_close($ch);
    return true; // Successfully sent SMS
}

if (isset($_GET['businessid']) && isset($_GET['action'])) {
    $businessId = intval($_GET['businessid']);
    $action = $_GET['action'];

    // Fetch mobile number and business name
    $sql = "SELECT MobileNumber, Business_name FROM tbladmin WHERE ID = :businessid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':businessid', $businessId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        $_SESSION['msg'] = "Invalid business ID.";
        header('location:reg-users.php');
        exit();
    }

    $mobileNumber = $result->MobileNumber;
    $businessName = $result->Business_name;

    // Determine the status and SMS message based on the action parameter
    if ($action === 'approve') {
        $status = 1; // Approved
        $statusMessage = "approved";
        $smsMessage = "Hello, your business '$businessName' has been approved. Thank you for registering!";
    } elseif ($action === 'reject') {
        $status = 2; // Rejected
        $statusMessage = "rejected";
        $smsMessage = "Hello, your business '$businessName' has been rejected. For assistance, please contact support.";
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
        // Send SMS notification
        if (sendSMS($mobileNumber, $smsMessage)) {
            $_SESSION['msg'] = "Business account $statusMessage successfully, and SMS sent.";
        } else {
            $_SESSION['msg'] = "Business account $statusMessage successfully, but failed to send SMS.";
        }
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
