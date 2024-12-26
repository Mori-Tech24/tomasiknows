<?php
    include('includes/dbconnection.php');

    if(isset($_POST['checkifaaproved'])) {

        $userid = $_POST['userid'];

        try {
            $stmt = $dbh->prepare("SELECT id FROM tbl_reservations WHERE user_id= :user_id AND reservation_status = 1");
            $stmt->execute(['user_id'=>$userid]);

            $count = $stmt->rowCount();

            if($count) {
                $output = array("success", $count);

            }else {
                $output = array("error", 0);
            }

            

        }catch(PDOException $e) {

            $output = array("error", 0);
        }


        echo json_encode($output);
    }



?>