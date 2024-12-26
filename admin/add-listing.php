<?php
session_start();

include('includes/dbconnection.php');

    if (isset($_POST['submit'])) {

        // Fetch form data
        $lssemsuid = $_SESSION['lssemsuid'];
        $listingtitle = $_POST['listingtitle'];
        $keywords = $_POST['keywords'];
        $category = $_POST['category'];
        $website = $_POST['website'];
        $add = $_POST['add'];
        $tadd = $_POST['tadd'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $zipcode = $_POST['zipcode'];
        $ownername = $_POST['ownername'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $comwebsite = $_POST['comwebsite'];
        $ownerdesi = $_POST['ownerdesi'];
        $company = $_POST['company'];
        $flink = $_POST['flink'];
        $twitterlink = $_POST['twitterlink'];
        $googlelink = $_POST['googlelink'];
        $linkedin = $_POST['linkedin'];
        $description = $_POST['description'];
        $line_of_business = $_POST['txt_line_of_business'];
        $addressLocation = $_POST['addressLocation'];

        

        // Initialize an array to store image paths
        $imagePaths = [];

        // Check if files are uploaded
        if (isset($_FILES['logo']) && count($_FILES['logo']['name']) > 0) {
            $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

            // Loop through all uploaded files
            foreach ($_FILES['logo']['name'] as $key => $logo) {
                $extension = substr($logo, strlen($logo) - 4, strlen($logo));
                if (in_array($extension, $allowed_extensions)) {
                    $newFileName = md5($logo) . time() . $extension;
                    $uploadPath = "images/" . $newFileName;

                    // Move the file to the desired directory
                    if (move_uploaded_file($_FILES['logo']['tmp_name'][$key], $uploadPath)) {
                        $imagePaths[] = $uploadPath; // Store the path of each uploaded image
                    }
                }
                // } else {
                //     echo "<script>alert('Logo has Invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
                // }
            }
        }

        // If multiple images are uploaded, store their paths in a comma-separated string
        $imagePathsStr = implode(',', $imagePaths);

        // Insert data into the database
        $sql = "INSERT INTO tbllisting (
                    UserID, ListingTitle, Keyword, Category, Website, Address, TemporaryAddress, City, 
                    State, Country, Zipcode, OwnerName, Email, Phone, CompanyWebsite, OwnerDesignation, 
                    Company, FacebookLink, TweeterLink, Googlepluslink, Linkedinlink, Description, Logo, 
                    n_line_of_business, Address_location, n_phone_number
                ) 
                VALUES (
                    :lssemsuid, :listingtitle, :keywords, :category, :website, :add, :tadd, :city, 
                    :state, :country, :zipcode, :ownername, :email, :phone, :comwebsite, :ownerdesi, 
                    :company, :flink, :twitterlink, :googlelink, :linkedin, :description, :logo, 
                    :n_line_of_business, :address_location, :n_phone_number
                )";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':lssemsuid', $lssemsuid, PDO::PARAM_STR);
        $query->bindParam(':listingtitle', $listingtitle, PDO::PARAM_STR);
        $query->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':website', $website, PDO::PARAM_STR);
        $query->bindParam(':add', $add, PDO::PARAM_STR);
        $query->bindParam(':tadd', $tadd, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':state', $state, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':zipcode', $zipcode, PDO::PARAM_STR);
        $query->bindParam(':ownername', $ownername, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':phone', $phone, PDO::PARAM_STR);
        $query->bindParam(':comwebsite', $comwebsite, PDO::PARAM_STR);
        $query->bindParam(':ownerdesi', $ownerdesi, PDO::PARAM_STR);
        $query->bindParam(':company', $company, PDO::PARAM_STR);
        $query->bindParam(':flink', $flink, PDO::PARAM_STR);
        $query->bindParam(':twitterlink', $twitterlink, PDO::PARAM_STR);
        $query->bindParam(':googlelink', $googlelink, PDO::PARAM_STR);
        $query->bindParam(':linkedin', $linkedin, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':logo', $imagePathsStr, PDO::PARAM_STR);  // Store the comma-separated image paths
        $query->bindParam(':n_line_of_business', $line_of_business, PDO::PARAM_STR);
        $query->bindParam(':address_location', $addressLocation, PDO::PARAM_STR);
        $query->bindParam(':n_phone_number', $phone, PDO::PARAM_STR);
        

        $query->execute();
        $LastInsertId = $dbh->lastInsertId();

        // Check if the insertion was successful
        if ($LastInsertId > 0) {
            echo '<script>alert("Listing Detail has been added.")</script>';
            echo "<script>window.location.href ='manage_listing.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <!-- Page Title -->
    <title>TomasiKnows|| Listed</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <!-- Themify Icon -->
    <link rel="stylesheet" href="../css/themify-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <!-- Hover Effects -->
    <link href="../css/set1.css" rel="stylesheet">
    <!-- Main CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   
    <!--============================= SUBPAGE HEADER BG =============================-->
    <section class="subpage-bg">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="titile-block title-block_subpage">
                        <h2>Add Listing</h2>
                        <p> <a href="index.php"> Home</a> / Add Listing</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--// SUBPAGE HEADER BG -->
    <!--============================= ADD LISTING =============================-->
    <section class="main-block">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="listing-wrap">
                        <form method="post" enctype="multipart/form-data">
                            <!-- General Information -->
                            <div class="listing-title">
                                <span class="ti-files text-primary"></span>
                                <h4>General Information</h4>
                                <p>Write Something General Information About Your Listing</p>
                            </div>


                            <div class="form-group">
                                <label>Name of Business</label>
                                <input type="text" class="form-control add-listing_form" name="listingtitle" required="true">
                            </div>
                            <div class="form-group">
                                <label>Business Owner</label>
                                <input type="text" class="form-control add-listing_form" name="ownername"   required="true">
                            </div>
                            <div class="form-group">
                                <label>Business Address</label>
                                <input type="text" class="form-control add-listing_form" name="add"  required="true">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control add-listing_form" name="category" required="true">
                                            <option value="">--Select--</option>
                                        <?php 

                                    $sql2 = "SELECT * from   tblcategory ";
                                    $query2 = $dbh -> prepare($sql2);
                                    $query2->execute();
                                    $result2=$query2->fetchAll(PDO::FETCH_OBJ);

                                    foreach($result2 as $row)
                                    {          
                                        ?>  
                                    <option value="<?php echo htmlentities($row->ID);?>"><?php echo htmlentities($row->Category);?></option>
                                    <?php } ?> 
                                        </select>
                                    </div>
                                </div>
                              
                            </div>
                            <div class="form-group">
                                <label>Line of Business</label>
                                <input type="text" class="form-control add-listing_form" name="txt_line_of_business" required="true">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control add-listing_form" name="email"  required="true">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                  <input type="text" class="form-control add-listing_form" name="phone"  required="true" maxlength="10" pattern="[0-9]+">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="description"></textarea>
                                  <!-- <input type="text" class="form-control add-listing_form" name="phone"  required="true" maxlength="10" pattern="[0-9]+"> -->
                            </div>


                            <div class="row d-none">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Listing Title</label>
                                        <!-- <input type="text" class="form-control add-listing_form" name="listingtitle"> -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Keyword</label>
                                        <input type="text" class="form-control add-listing_form" name="keywords">
                                    </div>
                                </div>
                            </div>

                       
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" class="form-control add-listing_form" name="website">
                                </div>
                            </div>


                            <!--//End General Information -->
                            <!-- Add Location -->
                            <div class="listing-title">
                                <span class="ti-location-pin"></span>
                                <h4>Add Location</h4>
                                <p>Write Something General Information About Your Listing</p>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control add-listing_form" name="addressLocation" required="true">
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label>Temporary Address</label>
                                        <input type="text" class="form-control add-listing_form" name="tadd">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control add-listing_form" name="city" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Barangay</label>
                                        <input type="text" class="form-control add-listing_form" name="state">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip-Code</label>
                                        <input type="text" class="form-control add-listing_form" name="zipcode">
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control add-listing_form" name="country">
                                    </div>
                                </div>
                                
                            </div>
                            <!--//End Add Location -->

                            <!-- Full Details -->
                            <div class="d-none">
                            <div class="listing-title">
                                <span class="ti-location-pin"></span>
                                <h4>Full Details</h4>
                                <p>Write Something General Information About Your Listing</p>
                            </div>

                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Owner Name</label>
                                        <!-- <input type="text" class="form-control add-listing_form" name="ownername" > -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <!-- <input type="email" class="form-control add-listing_form" name="email" required="true"> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control add-listing_form" name="phone"  maxlength="10" pattern="[0-9]+">
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" class="form-control add-listing_form" name="comwebsite">
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Owner Designation</label>
                                        <input type="text" class="form-control add-listing_form" name="ownerdesi">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" class="form-control add-listing_form" name="company">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Facebook Link</label>
                                        <input type="text" class="form-control add-listing_form" name="flink">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Twitter User</label>
                                        <input type="text" class="form-control add-listing_form" name="twitterlink">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Google Plus</label>
                                        <input type="text" class="form-control add-listing_form" name="googlelink">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Linked In</label>
                                        <input type="text" class="form-control add-listing_form" name="linkedin">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row ">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Description</label>
                                        <textarea class="form-control add-listing_form" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
                                    </div>
                                </div>
                            </div> -->
                            </div>
                            <!--//End Full Details -->

                            <!-- Add Gallery -->
                            <div class="listing-title ">
                                <span class="ti-gallery"></span>
                                <h4>Add featured Images</h4>
                                <p>Write Something General Information About Your Listing</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="custom-file" style="position: relative; text-align: center;">
                                        <!-- Placeholder or upload icon -->
                                        <img src="../images/up.png" 
                                            alt="Upload" 
                                            style="width: 150px; height: 150px; object-fit: contain; cursor: pointer;" 
                                            id="uploadPlaceholder">
                                        
                                        <!-- File input (hidden but clickable) -->
                                        <input type="file" 
                                            class="custom-file-inputs" 
                                            id="logo" 
                                            name="logo[]" 
                                            multiple 
                                            style="opacity: 0; position: absolute; top: 0; left: 0; width: 150px; height: 150px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>


                            <!-- Preview Container for Images -->
                            <div id="image-previews" class="row mt-3">
                                <!-- Image previews will be inserted here dynamically -->
                            </div>
                            <!--//End Add Gallery -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-wrap btn-wrap2">
                                       <button type="submit" class="btn btn-primary" name="submit" id="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px;">SUBMIT LISTING</button>
                                    </div>
                                </div>
                            </div>
                            <!--//End Opening Hours -->

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--//END ADD LISTING -->
   <?php include_once('includes/footer.php');?>

    <!-- jQuery, Bootstrap JS. -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>



</body>

</html>
<script>
    document.getElementById("logo").addEventListener("change", function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById("image-previews");
        previewContainer.innerHTML = ''; // Clear previous previews

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function(e) {

                const imageContainer = document.createElement("div");
                imageContainer.classList.add("col-md-4");
                imageContainer.classList.add("image-preview");

                imageContainer.innerHTML = `
                    <div class="image-preview-wrapper">
                        <img src="${e.target.result}" alt="Image Preview" class="img-fluid" style="max-height: 150px;">
                        <button type="button" class="btn btn-danger delete-btn" data-index="${index}">Delete</button>
                    </div>
                `;

                previewContainer.appendChild(imageContainer);
            };

            reader.readAsDataURL(file);
        });
    });

    // Event listener for deleting images
    document.getElementById("image-previews").addEventListener("click", function(event) {
        if (event.target.classList.contains("delete-btn")) {
            const index = event.target.getAttribute("data-index");
            const input = document.getElementById("logo");
            const files = Array.from(input.files);

            // Remove the file from the list
            files.splice(index, 1);

            // Reassign the files back to the input field
            input.files = new FileListItems(files);

            // Remove the preview from the UI
            const imagePreview = event.target.closest(".image-preview");
            imagePreview.remove();
        }
    });

    // Custom FileList for multiple file deletion
    function FileListItems(files) {
        const b = new ClipboardEvent('').clipboardData || new DataTransfer();
        files.forEach(file => b.items.add(file));
        return b.files;
    }
</script>
