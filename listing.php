<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Fetch filter values from $_GET
$categories = isset($_GET['categories']) ? $_GET['categories'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
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
            background-color: lightgray
            
        }

        span {
            color: blue !important;
        }
        .shadow-box {
            background-color: rgba(106, 112, 116); /* Semi-transparent gray */
            padding: 20px; /* Padding around the text */
            border-radius: 8px; /* Rounded corners */
            color: white; /* Text color */
            text-align: center; /* Center text */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Shadow effect */
        }
    </style>
</head>

<body>
<?php include_once('includes/header.php');?>
    <section class="featured-wrap">
        <div class="container-fluid container-subpage">
            <div class="row">
                <div class="col-md-12 responsive-wrap">
                    <br>
                    <!-- <h3 class="card-title" style="padding-left: 20px;">Listing Detail</h3> -->

                    <?php
// Initialize variables for search criteria message
$searchCriteria = [];

// Query to get the category name if a category ID is provided
if (!empty($categories)) {
    $categoryQuery = $dbh->prepare("SELECT Category FROM tblcategory WHERE ID = :categoryID");
    $categoryQuery->bindParam(':categoryID', $categories, PDO::PARAM_INT);
    $categoryQuery->execute();
    $categoryResult = $categoryQuery->fetch(PDO::FETCH_OBJ);

    if ($categoryResult) {
        $searchCriteria[] = "Category: " . htmlspecialchars($categoryResult->Category);
    }
}
// Build SQL query with flexible conditions
if (isset($_GET['filter']) && $_GET['filter'] == 1) {
    $sql = "SELECT a.*, 
                (SELECT COUNT(b.id) 
                 FROM tbl_reservations b 
                 WHERE b.listing_id = a.ID AND b.reservation_status = 1) AS reservation_count 
            FROM tbllisting a 
            WHERE isDeleted = 0 
            ORDER BY reservation_count DESC"; // Query for Most Booked

} else if (isset($_GET['filter']) && $_GET['filter'] == 2) {
    $sql = "SELECT a.*, 
                (SELECT COUNT(b.id) 
                 FROM tbl_listing_ratings b 
                 WHERE b.listing_id = a.ID) AS rating_count 
            FROM tbllisting a 
            WHERE isDeleted = 0 
            ORDER BY rating_count DESC"; // Query for Most Heart

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
                WHERE isDeleted = 0 
                HAVING distance < 50 -- Distance filter in kilometers
                ORDER BY distance ASC"; // Order by nearest distance
    } else {
        $sql = "SELECT * FROM tbllisting WHERE isDeleted = 0"; // Default query if no lat/long provided
    }
} else {
    $sql = "SELECT * FROM tbllisting WHERE isDeleted = 0"; // Default query
}

$conditions = []; // Array to hold all conditions

// Add condition for category if provided
if (!empty($categories)) {
    $conditions[] = "Category = :categories"; // Exact match for Category
}

// Add condition for location if provided
if (!empty($location)) {
    $conditions[] = "(State LIKE :location)"; // Using LIKE for location
    $searchCriteria[] = "Location: " . htmlspecialchars($location);
}

// Only add WHERE if there are conditions to include
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions); // Combine all conditions with AND
}

// Prepare the main query
$query = $dbh->prepare($sql);

// Bind parameters if filters are used
if (!empty($categories)) {
    $categoryWildcard = $categories; // Direct match for category
    $query->bindParam(':categories', $categoryWildcard, PDO::PARAM_STR); // Bind category
}
if (!empty($location)) {
    $locationWildcard = '%' . $location . '%'; // Use LIKE for location
    $query->bindParam(':location', $locationWildcard, PDO::PARAM_STR); // Bind location
}

// Execute the main query
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Display the search criteria if any
if (!empty($searchCriteria)) {
    echo "<div class='alert alert-info'><strong>Search Criteria:</strong> " . implode(" | ", $searchCriteria) . "</div>";
}
?>


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
<br><br><br>
                    <?php
if ($query->rowCount() > 0) {
    foreach ($results as $row) {
        // Query to get the count of ratings for each listing
        $stmt = $dbh->prepare("SELECT COUNT(id) AS rating_count FROM tbl_listing_ratings WHERE listing_id = :listing_id");
        $stmt->execute(['listing_id' => $row->ID]);
        $rating = $stmt->fetch(PDO::FETCH_ASSOC);
        $ratingCount = $rating['rating_count'] ?? 0; // Default to 0 if no ratings
        ?>
   
        <div class="col-md-3 card-2">
            <div class="card">
                <a href="listing-detail-copy.php?lid=<?php echo $row->ID; ?>">
                    <img class="card-img-top rounded-circle" 
                        src="<?php echo ltrim(explode(',', $row->Logo)[0], './'); ?>" 
                        height="250" 
                        width="200" 
                        alt="Card image cap">
                </a>
                <div class="card-body text-center" style="text-transform: uppercase;">
                    <a href="listing-detail-copy.php?lid=<?php echo $row->ID; ?>" class="text-decoration-none" style="text-transform: uppercase;">
                        <?php echo $row->ListingTitle; ?>
                    </a>
                    <p><?php echo $row->State; ?></p>

                    <!-- Heart icon with rating count -->
                    <div class="rating-section mt-2">
                        <i class="fa <?php echo $ratingCount > 0 ? 'fa-heart' : 'fa-heart-o'; ?>" style="color:red; font-size: 24px;"></i>
                        <span><?php echo $ratingCount; ?></span>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='text-center w-100 mt-5'>
            <h3 class='shadow-box' style='color:white'>No listings found for the given criteria.</h3>
            <a href='index.php' class='btn btn-primary mt-3'>Go Back</a>
          </div>";
}
?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include_once('includes/footer.php'); ?>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php include_once('includes/signup_footer.php'); ?>
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
            location.href ="listing.php";
            break;
        case '1':
            // Example action for Most Booked
            location.href ="listing.php?filter=1";
            break;
        case '2':
            // Example action for Most Heart
            location.href ="listing.php?filter=2";

            break;
        case '3':
            // Example action for Near Me
            location.href =`listing.php?filter=3&&lat=${latitude}&&long=${longitude}`;
            break;
        default:
            // Default action (e.g., reset the filter)
            alert('No filter applied.');
    }
});

</script>