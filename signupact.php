<?php
    include('includes/dbconnection.php');

    if(isset($_POST['signup'])) {

        $signup_fname = $_POST['signup_fname'];
        $signup_mobile = $_POST['signup_mobile'];
        $signup_password = md5($_POST['signup_password']);
    
        try {
        
            $stmt = $dbh ->prepare("SELECT * FROM tbluser WHERE MobileNumber = :MobileNumber");
            $stmt->execute(['MobileNumber'=>$signup_mobile]);
            $countstmt = $stmt->rowCount();
    
            if($countstmt == 0) {

                $insert = $dbh->prepare("INSERT INTO tbluser(FullName, MobileNumber, Password) VALUES(:FullName, :MobileNumber, :Password) ");
                $insert->execute(['FullName'=>$signup_fname, 'MobileNumber'=>$signup_mobile, 'Password'=>$signup_password ]);

                if($insert) {
                    $output = "success";
                }else {
                    $output = $insert;
                }

            }else {
    
                $output =  "Invalid Mobile NO.";
            }

        }catch(PDOException $e) {

            $output = $e->getMessage();

        }
        echo json_encode($output);

    }




?>