<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION['usertype']!=2) {
  header('location:error_404.php');

}
// Fetch filter values from $_GET
$categories = isset($_GET['categories']) ? $_GET['categories'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

if (strlen($_SESSION['lssemsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html>
<head>
  <title>TomasiKnows | Search And View Business</title>
  <!-- Tell the browser to be responsive to screen width -->
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include_once('includes/header.php');?>
  <?php include_once('includes/sidebar.php');?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Search And View Business</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Search And View Business</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Search And View Business</h3>
            </div>
            <div class="card-body">
                <form name="search" action="search-listing.php" method="post">
                    <div class="search-box">
                        <div class="row">
                            <div class="col-md-4 search-box_line">
                                <div class="search-box1">
                                    <div class="search-box-title shadow-box">
                                        <select class="search-form form-control" id="categories" name="categories" style="color:black">
                                      <option class="options" value="" style="color:black">--Select Category--</option>
                                      <option class="options" value="" style="color:black" 
                                          <?php echo (isset($_GET['categories']) && $_GET['categories'] === 'all-categories') ? 'selected' : ''; ?>>
                                          All categories
                                      </option>
                                      <?php 
                                      $sql2 = "SELECT * from tblcategory";
                                      $query2 = $dbh->prepare($sql2);
                                      $query2->execute();
                                      $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                                      foreach($result2 as $row) { 
                                          // Check if the current category matches the GET parameter
                                          $selected = (isset($_GET['categories']) && $_GET['categories'] == $row->ID) ? 'selected' : '';         
                                      ?>  
                                      <option style="color:black" value="<?php echo htmlentities($row->ID); ?>" <?php echo $selected; ?>>
                                          <?php echo htmlentities($row->Category); ?>
                                      </option>
                                      <?php } ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="search-box2 ">
                                    <div class="search-box-title shadow-box">
                                        <input class="form-control" type="text" id="location" name="location" value="<?php echo (isset($_GET['location'])) ? $_GET['location'] : "" ?>" required="true" placeholder="Where"> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" style="color:white !important" type="button" id="search" name="search">
                                    Search
                                </button>  
                                <a href="search.php" class="btn btn-danger">Clear Searches</a>
        
                            </div>
                        </div>
                    </div>
<br>
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
?>
            <div class="col-md-3 card-2">
                <div class="card">
                    <div class="container">
                        <a href="search_listing_detail.php?lid=<?php echo $row->ID; ?>">
                            <img  class="card-img-top rounded-circle"   src="<?php echo explode(',', "".$row->Logo)[0]; ?>" height="250" width="200" alt="Card image cap">
                        </a>
                        <div class="card-body text-center" style="text-transform: uppercase;">
                     
                                <a href="search_listing_detail.php?lid=<?php echo $row->ID; ?>" class="text-decoration-none" style="text-transform: uppercase;"><?php echo $row->ListingTitle; ?></a>
                       
                            <p class="card-text"><?php echo $row->State; ?></p>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        echo "
        <div class='text-center w-100 mt-5'>
            <h3 class='shadow-box' style='color:red'>No listings found for the given criteria.</h3>
        </div>";
    }
?>
</div>

                </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include_once('includes/footer.php');?>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
  $(function () {
    $(document).on("click", "#search", function () {
        var categories = $("#categories").val().trim(); // Trim to remove any extra spaces
        var location = $("#location").val().trim(); // Trim to remove any extra spaces

        if(categories == "" && location == "") {
            alert("Please Select Category or Enter a Location.");
            return; 

        }
        var redirectUrl = "search_listing.php?categories=" + encodeURIComponent(categories) + "&location=" + encodeURIComponent(location);
        window.location.href = redirectUrl;
    });


    
  });
</script>
</body>
</html>
<?php } ?>
