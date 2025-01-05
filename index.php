<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?><!DOCTYPE html>
<html lang="en">

<head>
    <title>TomasiKnows|| Home Page</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/set1.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: 
            linear-gradient(rgba(103, 169, 255, 0.8), rgba(255, 255, 255, 0.7)),
            url('bg/Login-Backgroud.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .shadow-box {
            background-color: rgba(106, 112, 116);
            padding: 20px;
            border-radius: 8px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .hero-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
            position: relative;
        }

        .hero-button {
            background-color: rgba(255, 255, 255, 0.6);
            color: black;
            font-weight: bold;
            padding: 15px 30px;
            border-radius: 8px;
            text-align: center;
            border: none;
            cursor: pointer;
            position: fixed; /* Make it stick at a specific position */
            top: 80px; /* Positioned below the header */
            left: 50%;
            transform: translateX(-50%);
            z-index: 999; /* Lower than the modal (z-index of 1050 in Bootstrap) */
        }

        .hero-button:hover {
            background-color: rgba(255, 255, 255, 0.8);
        }

        .modal-content {
            /* background-color: #000; */
            color: white;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px;
        }

        .footer-fixed {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-weight: bold;
            background: transparent;
            color: white;
            padding: 10px;
        }

        .nav-link-contact {
            float: right;
        }

        /* Adjusting space for the fixed button */
        .hero-wrap {
            padding-top: 150px; /* Ensure proper padding for header and button */
        }
    </style>
</head>

<body>
    <!--============================= HEADER =============================-->
    <?php include_once('includes/header.php'); ?>

    <!--============================= MAIN TITLE =============================-->
    <section class="hero-wrap d-flex align-items-center">
        <div class="container text-center">
        <button class="hero-button" data-toggle="modal" data-target="#searchModal" style="top: 200px; position: fixed; z-index: 999;">
        <span style="font-size: 36px; font-weight: bold;">What’s your plan today?</span> <br> 
        <span style="font-size: 20px; font-weight: bold;">Find out the perfect place to hang out in Sto. Tomas City</span>
        <br>
             <span style="color: blue;">Click Here</span>
    </button>
        </div>
    </section>

    <!--============================= MODAL =============================-->
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search Listings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="search" action="search-listing.php" method="get">
                        <div class="form-group">
                            <label style="color:black">What?</label>
                            <select class="form-control" id="categories" name="categories">
                                <option value="all-categories" selected>Select Category</option>
                                <option value="all-categories">All categories</option>
                                <?php 
                                $sql2 = "SELECT * from tblcategory";
                                $query2 = $dbh->prepare($sql2);
                                $query2->execute();
                                $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                                foreach($result2 as $row) {          
                                ?>  
                                <option value="<?php echo htmlentities($row->ID); ?>"><?php echo htmlentities($row->Category); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="color:black">Where?</label>
                            <input type="text" id="location" name="location" class="form-control" placeholder="Barangay">
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: rgba(31, 139, 255, 0.8); border-color: rgba(31, 139, 255, 0.8);">Search →</button>
                    </form>
                    <p class="terms" style="color:black">By using this website, you are agreeing to our <a href="#">terms and conditions</a></p>
                </div>
            </div>
        </div>
    </div>

    <!--============================= FOOTER =============================-->
    <footer class="footer-fixed">
        <p style="color:black">TomasiKnows @ 2024</p>
    </footer>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
