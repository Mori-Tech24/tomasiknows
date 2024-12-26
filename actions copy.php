<?php
    include('includes/dbconnection.php');


    if(isset($_POST['act']) == "book_reservation") {

        $userid = (empty($_POST['userid'])) ? NULL : $_POST['userid'] ;
        $listing_id = (empty($_POST['listing_id'])) ? NULL : $_POST['listing_id'];
        $listing_name = (empty($_POST['listing_name'])) ? NULL : $_POST['listing_name'];
        $reservation_date = (empty($_POST['reservation_date'])) ? NULL : $_POST['reservation_date'];
        $reservation_time = (empty($_POST['reservation_time'])) ? NULL : $_POST['reservation_time'];
        $reservation_purpose =  (empty($_POST['reservation_purpose'])) ? NULL : $_POST['reservation_purpose'];
        
        try {

            if($reservation_date < date("Y-m-d")) {

                $output = array("danger", "Error", "Invalid Date or Date already Passed.");
            }else {



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
                $stmt->execute(['user_id'=>$userid, 'listing_id'=>$listing_id, 'reservation_date'=>$reservation_date, 'reservation_time'=>$reservation_time, 'reservation_purpose'=>$reservation_purpose ]);

                if($stmt) {
                    $output = array("success", "Success", "Reservation Successfully Submitted.");
                }else {
                    $output = array("danger", "Error", $e->getMessage());
                }


            }

        }catch(PDOException $e) {

            if (str_contains($e->getMessage(), "reservation_time")) {
                $output = array("danger", "Error", "Reservation Time is Required.");
            }else if (str_contains($e->getMessage(), "reservation_purpose")) {
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