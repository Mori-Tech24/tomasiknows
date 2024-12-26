<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Page Title -->
    <title>TomasiKnows || About Us</title>
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
    <style>
        /* General Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            overflow-x: hidden;
        }

        /* Parallax Background */
        .parallax {
            background: url('bg/Login-Backgroud.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh; /* Takes full height of the screen */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: -1;
        }

        /* About Us Section Styling */
        .about-us-background {
            background-color: rgba(255, 255, 255, 1); /* Full opacity white background for About Us */
            padding: 60px 0;
            margin-top: 80vh; /* Adjusted to 80vh for lower positioning */
            position: relative;
        }

        .about-us-sticky {
            position: sticky;
            top: 80px; /* Distance from the top */
            z-index: 2;
        }

        /* Blog Section Styling */
        .blog-background {
            background-color: rgba(255, 255, 255, 1);  /* Full opacity white for blogs */
            padding: 60px 0;
            display: none; /* Initially hidden */
        }

        /* About Us Title */
        .about-us-title {
            font-size: 40px;
            font-weight: 500;
            text-align: center;
            margin-top: 0;
        }

        .scroll-text {
            font-size: 16px;
            margin-top: 10px;
            font-weight: 400;
            text-align: center;
        }

        /* Blog Styling */
        .full-blog {
            color: black;
            padding: 20px;
            margin-bottom: 30px;
            font-size: 1.2rem;
            line-height: 1.6;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease, transform 1s ease;
        }

        /* Blog Headings Styling */
        .full-blog h3 {
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .full-blog p {
            font-size: 1rem;
        }

        /* When content becomes visible after scroll */
        .content-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            padding: 20px 0;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php');?>

    <!-- Parallax Section -->
    <section class="parallax"></section>

    <!-- About Us Section with Sticky Behavior -->
    <div class="about-us-background about-us-sticky">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- About Us Title -->
                    <div class="about-us-title">
                        <h2>About Us</h2>
                    </div>
                    <div class="scroll-text">
                        <p>Scroll to see more</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Section with Full Opacity Background (Initially Hidden) -->
    <div class="blog-background">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- First Blog -->
                    <div class="full-blog">
                        <h3>Welcome to TomasiKnows</h3>
                        <p>TomasiKnows is a dynamic and comprehensive local business directory and reservation system built to support the thriving community of Sto. Tomas. Our platform is designed to bridge the gap between businesses and customers, providing an innovative and user-friendly way to discover, connect, and book services with local establishments. Whether you’re looking for restaurants, beauty salons, repair shops, fitness centers, or any other services, TomasiKnow is here to simplify the process and help you make the most of your time.</p>
                    </div>

                    <!-- Second Blog -->
                    <div class="full-blog">
                        <h3>What is TomasiKnow?</h3>
                        <p>TomasiKnow is more than just a directory. It is a one-stop solution for residents and visitors of Sto. Tomas to explore the vibrant local business scene. Our platform features a curated list of businesses across various categories, ensuring that every user finds exactly what they’re looking for. From finding the perfect dining spot to scheduling your next salon visit, TomasiKnow ensures that everything is just a few clicks away.</p>
                    </div>

                    <!-- Third Blog -->
                    <div class="full-blog">
                        <h3>Why Choose TomasiKnow?</h3>
                        <p>At TomasiKnow, we believe in empowering the community by promoting local businesses and simplifying life for residents. By providing a centralized platform for discovery and reservations, we make it easier for customers to access the services they need while helping businesses reach a wider audience.</p>
                    </div>

                    <!-- Fourth Blog -->
                    <div class="full-blog">
                        <h3>Join Us Today!</h3>
                        <p>Explore the best Sto. Tomas has to offer with TomasiKnow. Whether you’re planning your day, looking for recommendations, or booking a service, our platform is your ultimate companion. Discover, reserve, and support your community all in one place!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>TomasiKnows @ 2024</p>
    </div>

    <!-- jQuery, Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Scroll Event to Trigger the Reveal of Blog Section -->
    <script>
        $(document).ready(function() {
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();
                var windowHeight = $(window).height();
                var aboutUsHeight = $('.about-us-background').offset().top + $('.about-us-background').height();
                // Reveal blog section after scrolling past About Us
                if (scroll + windowHeight > aboutUsHeight) {
                    $('.blog-background').fadeIn(1000); // Fade in blog section
                    // Trigger the reveal of individual blog items as user scrolls
                    $('.full-blog').each(function() {
                        var position = $(this).offset().top;
                        if (scroll + windowHeight > position + 100) {
                            $(this).addClass('content-visible');
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>

<script>
    $("#navigation_about").css({ color: "blue" });
</script>