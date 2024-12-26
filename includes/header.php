<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>


   <div class="nav-menu sticky-top">
        <div class="bg transition">
            <div class="container-fluid fixed">
                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="index.php" style="display: flex; align-items: center;">
                            <img src="bg/logo.jpg" style="width: 35px; height: auto; margin-right: 10px;" alt="Logo">
                             <h3>TomasiKnows</h3>
                        </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="ti-menu"></span>
              </button>
                            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.php" id="navigation_home">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="listing.php" id="navigation_listing">Listings</a>
                                    </li>

                                     <li class="nav-item">
                                        <a class="nav-link" href="about.php" id="navigation_about">About Us</a>
                                    </li>
                                       
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact.php" id="navigation_listing">Contact Us</a>
                                    </li>
                                   <?php if (strlen($_SESSION['lssemsuid']!=0)) {?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            My Account
                                            <span class="ti-angle-down"></span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="profile.php">Profile</a>
                                            <a class="dropdown-item" href="change-password.php">Change Password</a>
                                            <a class="dropdown-item" href="logout.php">Logout</a>
                                        </div>
                                    </li><?php } ?>
                                    <?php if (strlen($_SESSION['lssemsuid']==0)) {?>

                                    <li>
                                        <li class="nav-item">
                                        <a class="nav-link" href="login.php" id="navigation_login"> <img src="images/login_icon.jpeg" alt="Login" width="25px"></a>
                                        </li>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="login.html" data-toggle="modal" data-target="#exampleModal"> <img src="images/signup_icon.jpeg" alt="Login" width="25px" ></a>
                                    </li>

                                    <!-- <li class="nav-item active">
                                        <a class="nav-link" href="admin/login.php">Admin</a>
                                    </li> -->
                                    <?php } ?>
<!--                                     
                                    <li><a href="add-listing.php" class="btn btn-outline-primary top-btn"><span class="ti-plus"></span> Add Listing</a></li>
                                   <?php if (strlen($_SESSION['lssemsuid']!=0)) {?>
                                    <li style="padding-left: 20px;"><a href="manage-listing.php" class="btn btn-outline-primary top-btn"><span class="ti-plus"></span> Manage Listing</a></li><?php } ?>
                                     -->
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Sign Up as Business Owner -->
    <div class="modal fade" id="mdlBusinessOwner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist"></ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="sign-up">
                        <div class="modal-header">
                            <h5 class="modal-title">Sign Up as Business Owner</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="ti-close"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frm_business_owner">
                                <div class="form-group">
                                    <input placeholder="Business Name" type="text" id="bus_business_name" name="bus_business_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input placeholder="Email Address" type="text" id="bus_email_address" name="bus_email_address" class="form-control">
                                </div>
                                <div class="form-group d-none">
                                    <input placeholder="Full name:" type="text" id="bus_fullname" name="bus_fullname" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input placeholder="Mobile:" type="text"  id="bus_mobile" name="bus_mobile"  maxlength="11" pattern="^\d{11}$" title="Mobile number must be exactly 11 digits" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input placeholder="password" type="password" id="bus_password" name="bus_password" required="true" id="password" class="form-control">
                                </div>
                         
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-primary"  style="background-color:#0076F4" id="btnSignUpasBusnessOwner">Create Account</button>
                                <a href="login.php" class="btn btn-primary"  style="background-color:#0076F4" >Login</a>
                            </div>
                            <br>
                            <div class="text-center">
                                <a href="#" id="txt_signup_user">Sign Up as User</a>
                                <br><br>
                                <a href="index.php">Back to Homepage</a>
                            </div>
                            <br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Signu Up as User -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    
                </ul>
                <div class="tab-content" id="pills-tabContent">
            
                    <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="sign-up">
                        <div class="modal-header">
                            <h5 class="modal-title">SIGN UP</h5>
                            <button type="submit" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="ti-close"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <input placeholder="Full name:" type="text" tabindex="1" required='true' id="signup_fname" name="fname" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input placeholder="Mobile:" type="text" tabindex="3"  id="signup_mobile" name="mobno" required="true" maxlength="11" pattern="^\d{11}$" title="Mobile number must be exactly 11 digits" class="form-control add-listing_form">
                                </div>
                                <div class="form-group">
                                    <input placeholder="password" type="password" tabindex="4" id="signup_password" name="password" required="true" id="password" class="form-control add-listing_form">
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-primary"  style="background-color:#0076F4" id="btnSignUp">Create Account</button>
                                <a href="login.php" class="btn btn-primary"  style="background-color:#0076F4" >Login</a>
                            </div>
                            <br>
                            <div class="text-center">
                                <a href="#" id="txt_signup_business_owner">Sign Up as Business Owner</a><br><br>
                                <a href="index.php">Back to Homepage</a>
                            </div>
                            <br>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>    
        $(document).on("click", "#txt_signup_business_owner",function(){
            $("#mdlBusinessOwner").modal("show");
            $("#exampleModal").modal("hide");
        });
        $(document).on("click", "#txt_signup_user",function(){
            $("#mdlBusinessOwner").modal("hide");
            $("#exampleModal").modal("show");
        });

        $(document).on('click', '#btnSignUpasBusnessOwner', function (e) {
            e.preventDefault();


            const businessName = $('#bus_business_name').val();
            const email = $('#bus_email_address').val();
            const fullname = $('#bus_fullname').val();
            const mobile = $('#bus_mobile').val();
            const password = $('#bus_password').val();

            // Basic validation
            if (!businessName) {
                alert('Business Name is Required.');
                return;
            }else if (!email) {
                alert('Email Address is Required.');
                return;
            // }else if (!fullname) {
            //     alert('Full name is Required.');
            //     return;
            }else if (!mobile) {
                alert('Mobile No. is Required.');
                return;
            } else if (!/^\d{11}$/.test(mobile)) { 
                alert('Mobile No. must be 11 digits and numeric only.');
                return;
            }else if (!password) {
                alert('Password is Required.');
                return;
            }else {

                $.ajax({
                    url: 'signup_business_owner.php',
                    type: 'POST',
                    dataType : "json",
                    data: {
                        bus_business_name: businessName,
                        bus_email_address: email,
                        bus_fullname: fullname,
                        bus_mobile: mobile,
                        bus_password: password
                    },
                    success: function (response) {
                        alert(response[1]);
                        if(response[0]=="success"){
                            $("#frm_business_owner").trigger("reset");
                            location.reload();
                        }
                    },
                    error: function () {
                        alert('An error occurred. Please try again!');
                    }
                });

            }

        });

        
    </script>
<script>
    $(document).on("click", "#btnSignUp", function() {
        var signup = "";
        var signup_fname = $("#signup_fname").val().trim();
        var signup_mobile = $("#signup_mobile").val().trim();
        var signup_password = $("#signup_password").val().trim();

        // Validate first name (required field)
        if (signup_fname === "") {
            alert("Full name is required.");
            return; // Stop execution if validation fails
        }

        // Validate mobile number (exactly 11 digits)
        var mobileRegex = /^\d{11}$/; // Matches exactly 11 digits
        if (!mobileRegex.test(signup_mobile)) {
            alert("Please enter a valid 11-digit mobile number.");
            return; // Stop execution if validation fails
        }

        // Validate password (required field)
        if (signup_password === "") {
            alert("Password is required.");
            return; // Stop execution if validation fails
        }

        // Proceed with other logic if all validations pass
   
        $.ajax({
            url : "signupact.php",
            method : "post",
            dataType : "json",
            data : {
                signup, signup_fname, signup_mobile, signup_password
            },
            beforeSend : function() {
                $("#btnSignUp").addClass("d-none");
            },
            success : function(response) {
            
                $("#btnSignUp").removeClass("d-none");
                if(response =="success") {
                    alert("Signup Success.");
                    location.reload();
                }else {
                    alert(response[0]);
                }
            }

        });

        // Add your AJAX or further form processing logic here
    });
</script>
