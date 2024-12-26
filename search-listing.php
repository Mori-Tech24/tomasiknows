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
    <?php
    $categories = $_POST['categories'];
    $location = $_POST['location'];

    // Build dynamic SQL
    $sql = "SELECT * FROM tbllisting WHERE 1=1"; // Base query

    // Add category filter if not "all-categories"
    if ($categories !== 'all-categories') {
        $sql .= " AND tbllisting.Category = '$categories'";
    }

    // Add location filter
    $sql .= " AND (tbllisting.State LIKE '%$location%')";

    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $row) { ?>
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