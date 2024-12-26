<?php
    include('includes/dbconnection.php');


    $bus_business_name = $_POST['bus_business_name'];
    $bus_email_address = $_POST['bus_email_address'];
    $bus_fullname = $_POST['bus_fullname'];
    $bus_mobile = $_POST['bus_mobile'];
    $bus_password = md5($_POST['bus_password']);

    try {
    
        $stmt = $dbh ->prepare("SELECT * FROM tbladmin WHERE MobileNumber = :MobileNumber");
        $stmt->execute(['MobileNumber'=>$bus_mobile]);
        $countstmt = $stmt->rowCount();

        if($countstmt == 0) {

            $insert = $dbh->prepare("INSERT INTO tbladmin(Business_name, UserName, Email, AdminName, MobileNumber, Password, usertype) 
            VALUES(:Business_name, :UserName, :Email, :AdminName, :MobileNumber, :Password, :usertype) ");
            $insert->execute([
                        'Business_name'=>$bus_business_name,
                        'UserName'=>$bus_business_name,
                        'Email'=>$bus_email_address,
                        'AdminName'=>$bus_fullname,
                        'MobileNumber'=>$bus_mobile, 
                        'Password'=>$bus_password,
                        'usertype'=>2
                    ]);

            if($insert) {
                $output = array("success", "Successfully Registered. Please Wait for verification");
            }else {
                $output =  array("error", $insert);
            }

        }else {
            $output = array("error", "Invalid Mobile NO.");
        }

    }catch(PDOException $e) {

        $output = array("error", $e->getMessage());

    }
    echo json_encode($output);
?>
