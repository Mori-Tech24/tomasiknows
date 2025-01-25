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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
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
</head>

<body>
    <?php include_once('includes/header.php');?>
   <section class="featured-wrap">
        <div class="container-fluid container-subpage">
            <div class="row">
                <div class="col-md-12 responsive-wrap">
            
              
                    <div class="row detail-checkbox-wrap">
                       
                       <h3 class="card-title" style="padding-left: 50px;">Search Detail</h3>
                       <hr />
                      
                    </div>
                    <div class="row">
                    <div class="col-md-9">
        <!-- Listings content -->
    </div>


                    <?php
// Get the 'filter' value from the query string
$filter = isset($_GET['filter']) ? $_GET['filter'] : 0;
?>
<div class="col-md-3 d-flex justify-content-end align-items-center">
    <select class="form-control" name="select-filter" id="select-filter">
        <option value="0" <?php echo $filter == 0 ? 'selected' : ''; ?>>Select Filter</option>
        <option value="1" <?php echo $filter == 1 ? 'selected' : ''; ?>>Most Booked</option>
        <option value="2" <?php echo $filter == 2 ? 'selected' : ''; ?>>Most Heart</option>
        <option value="3" <?php echo $filter == 3 ? 'selected' : ''; ?>>Near Me</option>
    </select>
</div>







    <?php
$categories = isset($_GET['categories']) ? $_GET['categories'] : 'all-categories';
$location = isset($_GET['location']) ? $_GET['location'] : '';

if (isset($_GET['filter']) && $_GET['filter'] == 1) {
    $sql = "SELECT a.*, 
                (SELECT COUNT(b.id) 
                 FROM tbl_reservations b 
                 WHERE b.listing_id = a.ID AND b.reservation_status = 1) AS reservation_count 
            FROM tbllisting a 
            WHERE 1=1  AND  a.isDeleted = 0 
            "; // Query for Most Booked

    if ($categories !== 'all-categories') {
        $sql .= " AND a.Category = '$categories'";
    }

    // Add location filter
    $sql .= " AND (a.State LIKE '%$location%') ORDER BY reservation_count DESC";

} else if (isset($_GET['filter']) && $_GET['filter'] == 2) {
    $sql = "SELECT a.*, 
                (SELECT COUNT(b.id) 
                 FROM tbl_listing_ratings b 
                 WHERE b.listing_id = a.ID) AS rating_count 
            FROM tbllisting a 
            WHERE  1=1  AND  isDeleted = 0 
            "; // Query for Most Heart

if ($categories !== 'all-categories') {
    $sql .= " AND a.Category = '$categories'";
}

// Add location filter
$sql .= " AND (a.State LIKE '%$location%') ORDER BY rating_count DESC";



} else if (isset($_GET['filter']) && $_GET['filter'] == 3) {
 // Query for Near Me
    $get_lat  = isset($_GET['lat']) ? (float)$_GET['lat'] : 0; // Get latitude
    $get_long = isset($_GET['long']) ? (float)$_GET['long'] : 0; // Get longitude

    // Check if latitude and longitude are valid
    if ($get_lat && $get_long) {
        $sql = "SELECT a.*, 
                    (6371 * acos(
                        cos(radians($get_long)) * 
                        cos(radians(a.latitude)) * 
                        cos(radians(a.longitude) - radians($get_lat)) + 
                        sin(radians($get_long)) * 
                        sin(radians(a.latitude))
                    )) AS distance 
                FROM tbllisting a 
                
                WHERE  1=1  AND  isDeleted = 0 
                "; // Order by nearest distance

        if ($categories !== 'all-categories') {
            $sql .= " AND a.Category = '$categories'";
        }

        // Add location filter
        $sql .= " AND (a.State LIKE '%$location%') HAVING distance < 50  ORDER BY distance ASC" ;


    } else {
        $sql = "SELECT * FROM tbllisting WHERE  1=1  AND  isDeleted = 0"; // Default query if no lat/long provided
    }
} else {
    $sql = "SELECT * FROM tbllisting WHERE  1=1  AND   isDeleted = 0"; // Default query

    if ($categories !== 'all-categories') {
        $sql .= " AND tbllisting.Category = '$categories'";
    }

    // Add location filter
    $sql .= " AND (tbllisting.State LIKE '%$location%')";
}

    // Build dynamic SQL
    // $sql = "SELECT * FROM tbllisting WHERE 1=1  AND isDeleted = 0";

    // Add category filter if not "all-categories"
  

    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $row) { 
            
            $stmt = $dbh->prepare("SELECT COUNT(id) AS rating_count FROM tbl_listing_ratings WHERE listing_id = :listing_id");
            $stmt->execute(['listing_id' => $row->ID]);
            $rating = $stmt->fetch(PDO::FETCH_ASSOC);
            $ratingCount = $rating['rating_count'] ?? 0; // Default to 0 if no ratings
            
            ?>
            <div class="col-md-2 col-sm-4 col-6 mb-3">
                <div class="card shadow-sm" style="border-radius: 10px;">
                    <a href="listing-detail-copy.php?lid=<?php echo $row->ID; ?>">
                        <img class="card-img-top rounded-circle" 
                             src="<?php echo ltrim(explode(',', $row->Logo)[0], './'); ?>" 
                             height="250" 
                             width="200" 
                             alt="Card image cap">
                    </a>
                    <div class="card-body text-center" style="text-transform: uppercase;">
                        <a href="listing-detail-copy.php?lid=<?php echo $row->ID; ?>" class="text-decoration-none">
                            <?php echo $row->ListingTitle; ?>
                        </a>
                        <p class="card-text"><?php echo $row->State; ?></p>
                        <div class="rating-section mt-2">
                            <i class="fa <?php echo $ratingCount > 0 ? 'fa-heart' : 'fa-heart-o'; ?>" style="color:red; font-size: 24px;"></i>
                            <span><?php echo $ratingCount; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    } else { ?>
        <p style="color:red; font-size:24px; padding-left: 100px;">No Record Found 
            <a href="index.php"> <u>Go back to Search Listing</u> </a>
        </p>
    <?php } ?>
</div>
                </div>

            </div>
        </div>
    </section>
     <?php include_once('includes/footer.php');?>
  
    <!-- jQuery, Bootstrap JS. -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <!-- Map JS (Please change the API key below. Read documentation for more info) -->
    <script src="https://maps.googleapis.com/maps/api/js?callback=myMap&key=AIzaSyDMTUkJAmi1ahsx9uCGSgmcSmqDTBF9ygg"></script>

</body>

</html>

<script>

let latitude  = "";
let longitude = "";

if (navigator.geolocation) {
  // Request the current position
  navigator.geolocation.getCurrentPosition(
    function (position) {
      // Success callback
      latitude = position.coords.latitude;
      longitude = position.coords.longitude;

      console.log("Latitude: " + latitude);
      console.log("Longitude: " + longitude);

      // Use the coordinates for your application logic
    },
    function (error) {
      // Error callback
      console.error("Error retrieving location: ", error.message);
    }
  );
} else {
  console.error("Geolocation is not supported by this browser.");
}


$('#select-filter').on('change', function () {
    const selectedValue = $(this).val(); // Get the selected value

    // Perform different actions based on the selected filter
    switch (selectedValue) {
        case '0':
            // Example action for Most Booked
            location.href =`search-listing.php?categories=<?php echo $_GET['categories']?>&location=<?php echo $_GET['location']?>`;
            break;
        case '1':
            // Example action for Most Booked
            location.href =`search-listing.php?filter=1&&categories=<?php echo $_GET['categories']?>&&location=<?php echo $_GET['location']?>`;
         
            break;
        case '2':
            // Example action for Most Heart
            location.href =`search-listing.php?filter=2&categories=<?php echo $_GET['categories']?>&location=<?php echo $_GET['location']?>`;
         

            break;
        case '3':
            // Example action for Near Me
            location.href =`search-listing.php?filter=3&&lat=${latitude}&&long=${longitude}&categories=<?php echo $_GET['categories']?>&location=<?php echo $_GET['location']?>`;
            break;
        default:
            // Default action (e.g., reset the filter)
            alert('No filter applied.');
    }
});

</script>