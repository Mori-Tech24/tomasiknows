<?php
include('includes/dbconnection.php');
session_start();
error_reporting(0);

if(isset($_POST['submit'])) {
    $mobile=$_POST['contactnumber'];
    $inputpwd=md5($_POST['password']);

    $sql ="SELECT ID FROM tbluser WHERE  MobileNumber=:mobile";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query-> execute();
    $countMobile = $query->rowCount();
    if($countMobile != 0)
    {
        $results = $query -> fetchAll(PDO::FETCH_OBJ);
    $con="update tbluser set Password=:newpassword where MobileNumber=:mobile";
    $chngpwd1 = $dbh->prepare($con);
    $chngpwd1-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $chngpwd1-> bindParam(':newpassword', $inputpwd, PDO::PARAM_STR);
    $chngpwd1->execute();
    echo "<script>alert('Your Password succesfully changed');</script>";
    }
    else {
    echo "<script>alert(' Mobile no is invalid');</script>"; 
    }
}
  ?>
<!DOCTYPE html>
<html lang="en">

<head>
   
    <!-- Page Title -->
    <title>TomasiKnows || Password Recovery</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <!-- Themify Icon -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Hover Effects -->
    <link href="css/set1.css" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include_once('includes/header.php');?>
    <!--============================= SUBPAGE HEADER BG =============================-->
    <section class="subpage-bg">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="titile-block title-block_subpage">
                        <h2>Password Recovery</h2>
                        <p><a href="index.php"> Home</a> / Password Recovery</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--// SUBPAGE HEADER BG -->
    <!--============================= CONTACT =============================-->
    <section class="main-block">
        <div class="container-fluid">

            <div class="row no-gutters justify-content-center">

                <div class="col-md-10 gray">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-form">

                                <form method="post">
                                    <div class="form-group">
                                        <label>Registered Mobile Number</label>
                                        <input type="number" name="contactnumber" class="form-control" maxlength="11" required="true" placeholder="Registered Mobile Number">
                                    </div>
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input placeholder="New password" type="password" tabindex="4" name="password" required="true" id="password" class="form-control add-listing_form">
                                    </div>
                                   
                              
                                    <div class="form-group">
                                        <button type="submit" class="btn-submit" id="js-contact-btn" name="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px;">Reset</button>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                   
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--//END CONTACT -->
    <!--============================= FOOTER =============================-->
  <?php include_once('includes/footer.php');?>



    <!-- jQuery, Bootstrap JS. -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <!-- Map JS (Please change the API key below. Read documentation for more info) -->
    <script src="https://maps.googleapis.com/maps/api/js?callback=myMap&key=AIzaSyDMTUkJAmi1ahsx9uCGSgmcSmqDTBF9ygg"></script>
    <!-- Validate JS -->
    <script src="js/validate.js"></script>
    <!-- Contact JS -->
    <script src="js/contact.js"></script>
</body>

</html>