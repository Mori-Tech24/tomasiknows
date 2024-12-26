<?php
include('includes/dbconnection.php');

if (isset($_POST['act']) && $_POST['act'] == "book_reservation") {

    $userid = (empty($_POST['userid'])) ? NULL : $_POST['userid'];
    $listing_id = (empty($_POST['listing_id'])) ? NULL : $_POST['listing_id'];
    $listing_name = (empty($_POST['listing_name'])) ? NULL : $_POST['listing_name'];
    $reservation_date = (empty($_POST['reservation_date'])) ? NULL : $_POST['reservation_date'];
    $reservation_time = (empty($_POST['reservation_time'])) ? NULL : $_POST['reservation_time'];
    $reservation_purpose = (empty($_POST['reservation_purpose'])) ? NULL : $_POST['reservation_purpose'];

    try {
        if ($reservation_date < date("Y-m-d")) {
            $output = array("danger", "Error", "Invalid Date or Date already Passed.");
        } else {
            // Insert reservation
            $stmt = $dbh->prepare("
                INSERT INTO tbl_reservations (user_id, mobile_no, listing_id, reservation_date, reservation_time, reservation_purpose)
                VALUES (
                    :user_id, 
                    (SELECT 
                        CASE 
                            WHEN LEFT(MobileNumber, 1) = '9' THEN CONCAT('+639', SUBSTRING(MobileNumber, 2)) 
                            ELSE MobileNumber 
                        END AS MobileNumber 
                    FROM tbluser 
                    WHERE ID = :user_id 
                    ORDER BY ID DESC 
                    LIMIT 1), 
                    :listing_id, 
                    :reservation_date, 
                    :reservation_time, 
                    :reservation_purpose
                )
            ");
            $stmt->execute([
                'user_id' => $userid, 
                'listing_id' => $listing_id, 
                'reservation_date' => $reservation_date, 
                'reservation_time' => $reservation_time, 
                'reservation_purpose' => $reservation_purpose
            ]);

            if ($stmt) {
                // Fetch the user's mobile number
                $stmtMobile = $dbh->prepare("SELECT MobileNumber FROM tbluser WHERE ID = :user_id LIMIT 1");
                $stmtMobile->execute(['user_id' => $userid]);
                $user = $stmtMobile->fetch(PDO::FETCH_ASSOC);
                $mobileNumber = $user['MobileNumber'];

                // Format the mobile number for Semaphore
                if (substr($mobileNumber, 0, 1) == '0') {
                    $mobileNumber = "+63" . substr($mobileNumber, 1);
                }

                // Send SMS via Semaphore
                $apiKey = "1def7651eddb998a05492b48938afb61"; // Replace with your Semaphore API key
                $message = "Your reservation for $listing_name on $reservation_date at $reservation_time has been confirmed. Thank you!";
                $senderName = "TomasiKnows"; // Optional: Replace with your desired sender name

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://api.semaphore.co/api/v4/messages");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'apikey' => $apiKey,
                    'number' => $mobileNumber,
                    'message' => $message,
                    'sendername' => $senderName,
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);

                // Decode and handle the response from Semaphore
                $responseDecoded = json_decode($response, true);
                $output = array("success", "Success", "Reservation Successfully Submitted.");

                // if (isset($responseDecoded['status']) && $responseDecoded['status'] == "Queued") {
                //     $output = array("success", "Success", "Reservation Successfully Submitted and SMS Sent.");
                // } else {
                //     $output = array("warning", "Success", json_decode($response, true));
                // }
            } else {
                $output = array("danger", "Error", "Failed to submit reservation.");
            }
        }
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), "reservation_time")) {
            $output = array("danger", "Error", "Reservation Time is Required.");
        } else if (str_contains($e->getMessage(), "reservation_purpose")) {
            $output = array("danger", "Error", "Reservation Purpose is Required.");
        } else {
            $output = array("danger", "Error", $e->getMessage());
        }
    }

    sleep(1);
    echo json_encode($output);

    exit;
}
?>
