<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');


if(isset($_POST['signup'])) {
    $fname = $_POST['fname'];
    $mobno = $_POST['mobno'];
    $password = md5($_POST['password']);

    // Check if mobile number is already registered
    $ret = "SELECT MobileNumber FROM tbluser WHERE MobileNumber = :mobno";
    $query = $dbh->prepare($ret);
    $query->bindParam(':mobno', $mobno, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() == 0) {
        // Insert new user if mobile number is not already taken
        $sql = "INSERT INTO tbluser (FullName, MobileNumber, Password) VALUES (:fname, :mobno, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':mobno', $mobno, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if($lastInsertId) {
            echo "<script>alert('You have signed up successfully.');</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Mobile Number already exists. Please try again.');</script>";
    }
}

if(isset($_POST['login'])) 
{
    $mobno = $_POST['mobno'];
    $password = md5($_POST['password']);

    // Make sure the mobile number has exactly 11 digits
    if(strlen($mobno) != 11) {
        echo "<script>alert('Mobile number must be exactly 11 digits.');</script>";
    } else {
        $sql ="SELECT ID FROM tbluser WHERE MobileNumber=:mobno and Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':mobno', $mobno, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            foreach ($results as $result) {
                $_SESSION['lssemsuid'] = $result->ID;
            }
            $_SESSION['login'] = $_POST['email'];
            echo "<script type='text/javascript'> document.location ='index.php'; </script>";
        } else {
            // echo "<script>alert('Invalid Detailsss');</script>";
            /* admin Login Here */
            

            $sql ="SELECT ID, usertype FROM tbladmin WHERE UserName=:username and Password=:password";
            $query=$dbh->prepare($sql);
            $query-> bindParam(':username', $mobno, PDO::PARAM_STR);
            $query-> bindParam(':password', $password, PDO::PARAM_STR);
                $query-> execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                if($query->rowCount() > 0)
            {
            foreach ($results as $result) {
            $_SESSION['lssemsaid']=$result->ID;
            $_SESSION['usertype']=$result->usertype;
            }
            
            if(!empty($_POST["remember"])) {
            //COOKIES for username
            setcookie ("user_login",$_POST["mobno"],time()+ (10 * 365 * 24 * 60 * 60));
            //COOKIES for password
            setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
            } else {
            if(isset($_COOKIE["user_login"])) {
            setcookie ("user_login","");
            if(isset($_COOKIE["userpassword"])) {
            setcookie ("userpassword","");
                    }
                }
            }
            $_SESSION['login']=$_POST['mobno'];

            echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
            } else{
            echo "<script>alert('Invalid Details');</script>";
            }


        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>TomasiKnows || Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/set1.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: url('bg/Login-Backgroud.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php');?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="login-box">
                <h4 class="text-center">Login</h4>
                <form name="login" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="mobno">Mobile Number:</label>
                        <input type="text" class="form-control" name="mobno" id="mobno" required="true" maxlength="11" pattern="\d{11}" title="Mobile number must be exactly 11 digits">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" required="true">
                    </div>
                    <div class="form-group">
                        <a href="password-recovery.php">Forgot Password</a>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="login" 
                    style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px;">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php include_once('includes/signup_footer.php'); ?>
<script>
    // JavaScript function to validate the mobile number
    function validateForm() {
        var mobno = document.forms["login"]["mobno"].value;
        if (mobno.length != 11) {
            alert("Mobile number must be exactly 11 digits.");
            return false;
        }
        return true;
    }

    function checkpass() {
        var mobno = document.forms["signup"]["mobno"].value;
        
        // Check if the mobile number has exactly 11 digits
        if (mobno.length !== 11) {
            alert("Mobile number must be exactly 11 digits.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
