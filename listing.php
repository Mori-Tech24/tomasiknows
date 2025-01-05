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
$sql = "SELECT * FROM tbllisting"; // Starting SQL query
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
                        <i class="fa <?php echo $ratingCount > 0 ? 'fa-heart' : 'fa-heart-o'; ?>" style="color:red;"></i>
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
