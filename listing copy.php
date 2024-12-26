<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Page Title -->
    <title>TomasiKnows || Listing</title>
    <!-- Bootstrap CSS (Bootstrap 4) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <!-- Themify Icon -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- Line Icon -->
    <link rel="stylesheet" href="css/simple-line-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Hover Effects -->
    <link href="css/set1.css" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Box styling */
        body {
            background: url('bg/background.jpeg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        span {
            color: blue !important;
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php'); ?>
    <section class="featured-wrap">
        <div class="container-fluid container-subpage">
            <div class="row">
                <div class="col-md-12 responsive-wrap">
                    <div class="row detail-filter-wrap"></div>
                    <div class="map-responsive-wrap">
                        <a class="map-icon btn btn-block" href="#"><i class="icon-location-pin"></i> <small>OPEN LISTING IN MAP</small></a>
                    </div>
                    <div class="row detail-checkbox-wrap">
                        <h3 class="card-title" style="padding-left: 20px;">Listing Detail</h3>
                    </div>
                    <div class="row">
                        <?php
                        // Fetch listings from the database
                        $sql = "SELECT * from tbllisting";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                                // Get the Logo column (may contain a single image or multiple images)
                                $logo = $row->Logo;
                                $imageArray = explode(",", $logo);  // Split by comma if there are multiple images
                                ?>
                                <div class="col-md-3 card-2">
                                    <div class="card">
                                        <?php if (count($imageArray) > 1) { ?>
                                            <!-- Displaying images in a carousel if there are multiple images -->
                                            <div id="carousel<?php echo $row->ID; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php
                                                    $isActive = 'active'; // Set first image as active
                                                    foreach ($imageArray as $image) {
                                                        echo '<div class="carousel-item ' . $isActive . '">';
                                                        echo '<a href="listing-detail.php?lid=' . $row->ID . '">';
                                                        echo '<img class="d-block w-100" src="' . $image . '" height="200" alt="Card image cap">';
                                                        echo '</a>';
                                                        echo '</div>';
                                                        $isActive = ''; // Set subsequent images as non-active
                                                    }
                                                    ?>
                                                </div>
                                                <a class="carousel-control-prev" href="#carousel<?php echo $row->ID; ?>" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carousel<?php echo $row->ID; ?>" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <!-- Display a single image if only one image exists -->
                                            <a href="listing-detail.php?lid=<?php echo $row->ID; ?>">
                                                <img class="card-img-top" src="images/<?php echo $imageArray[0]; ?>" height="200" width="300" alt="Card image cap">
                                            </a>
                                        <?php } ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="listing-detail.php?lid=<?php echo $row->ID; ?>"><?php echo $row->ListingTitle; ?></a>
                                            </h5>
                                            <p class="card-text"><?php echo substr($row->Description, 0, 150); ?> </p>
                                        </div>
                                        <div class="card-bottom">
                                            <p><i class="ti-location-pin"></i><?php echo $row->Country; ?></p>
                                            <span><?php echo $row->State; ?></span>
                                            <span style="padding-left: 10px"><?php echo $row->City; ?></span>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include_once('includes/footer.php'); ?>

    <!-- jQuery, Bootstrap JS (Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include_once('includes/signup_footer.php'); ?>
</body>

</html>

<script>
    $("#navigation_listing").css({ color: "blue" });
</script>
