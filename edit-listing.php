<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

$listingId = $_GET['editid'];

// Fetch the listing data from the database
$sql = "SELECT * FROM tbllisting WHERE ID = :listingId";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':listingId', $listingId, PDO::PARAM_INT);
$stmt->execute();
$listing = $stmt->fetch(PDO::FETCH_OBJ);

// Get existing images (if any)
$existingImages = explode(",", $listing->Logo); // Assuming 'Logo' column holds comma-separated paths

if (strlen($_SESSION['lssemsuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        // Collect form data
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
        $eid = $_GET['editid'];

        // Handle image uploads
        if (isset($_FILES['logo']) && count($_FILES['logo']['name']) > 0) {
            // Initialize the array to store the paths of the uploaded images
            $uploadedImages = [];
            $uploadDir = 'images/'; // Directory where images will be stored

            // Loop through the uploaded files
            for ($i = 0; $i < count($_FILES['logo']['name']); $i++) {
                // Get the file extension
                $fileTmpName = $_FILES['logo']['tmp_name'][$i];
                $fileName = basename($_FILES['logo']['name'][$i]);
                $filePath = $uploadDir . uniqid() . '_' . $fileName;

                // Move the uploaded file to the server's directory
                if (move_uploaded_file($fileTmpName, $filePath)) {
                    // Store the image path
                    $uploadedImages[] = $filePath;
                }
            }

            // Combine the existing images with the newly uploaded ones
            $allImages = array_merge($existingImages, $uploadedImages);
            $newImageList = implode(",", $allImages);
        } else {
            // No new images uploaded, keep the existing images
            $newImageList = $listing->Logo;
        }

        // Update the listing with new details and image list
        $sql = "UPDATE tbllisting SET 
                    ListingTitle = :listingtitle,
                    Keyword = :keywords,
                    Category = :category,
                    Website = :website,
                    Address = :add,
                    TemporaryAddress = :tadd,
                    City = :city,
                    State = :state,
                    Country = :country,
                    Zipcode = :zipcode,
                    OwnerName = :ownername,
                    Email = :email,
                    Phone = :phone,
                    CompanyWebsite = :comwebsite,
                    OwnerDesignation = :ownerdesi,
                    Company = :company,
                    FacebookLink = :flink,
                    TweeterLink = :twitterlink,
                    Googlepluslink = :googlelink,
                    Linkedinlink = :linkedin,
                    Description = :description,
                    Logo = :logo 
                WHERE ID = :eid";

        // Prepare and execute the query
        $query = $dbh->prepare($sql);
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
        $query->bindParam(':logo', $newImageList, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_INT);

        // Execute the update query
        $query->execute();

        // Redirect to the same page to avoid resubmission of the form
        header("Location: edit-listing.php?editid=$eid");
        exit(); // Make sure the script stops executing here
    }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    
    <!-- Page Title -->
    <title>TomasiKnows|| Edit Listing</title>
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
</head>

<body>
<?php include_once('includes/header.php');?>
   
    <!--============================= SUBPAGE HEADER BG =============================-->
    <section class="subpage-bg">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="titile-block title-block_subpage">
                        <h2>Edit Listing</h2>
                        <p> <a href="index.php"> Home</a> / Edit Listing</p>
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
                            <?php
$eid=$_GET['editid'];
$userid=$_SESSION['lssemsuid'];   
$sql="SELECT tblcategory.Category as cat,tbllisting.*  from tbllisting inner join tblcategory on tblcategory.ID=tbllisting.Category where tbllisting.ID=:eid and tbllisting.UserID=:userid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->bindParam(':userid',$userid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                            <!-- General Information -->
                            <div class="listing-title">
                                <span class="ti-files"></span>
                                <h4>General Information</h4>
                                <p>Update Something General Information About Your Listing</p>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Listing Title</label>
                                        <input type="text" class="form-control add-listing_form" name="listingtitle" required="true" value="<?php  echo $row->ListingTitle;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Keyword</label>
                                        <input type="text" class="form-control add-listing_form" name="keywords" required="true" value="<?php  echo $row->Keyword;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control add-listing_form" name="category" required="true">
                                            <option value="<?php  echo $row->Category;?>"><?php  echo $row->cat;?></option>
       <?php 

$sql2 = "SELECT * from   tblcategory ";
$query2 = $dbh -> prepare($sql2);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);

foreach($result2 as $row2)
{          
    ?>  
<option value="<?php echo htmlentities($row2->ID);?>"><?php echo htmlentities($row2->Category);?></option>
 <?php } ?> 
    </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" class="form-control add-listing_form" name="website" value="<?php  echo $row->Website;?>">
                                    </div>
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
                                        <input type="text" class="form-control add-listing_form" name="add" required="true" value="<?php  echo $row->Address;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Temporary Address</label>
                                        <input type="text" class="form-control add-listing_form" name="tadd" required="true" value="<?php  echo $row->TemporaryAddress;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control add-listing_form" name="city" required="true" value="<?php  echo $row->City;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" class="form-control add-listing_form" name="state" required="true" value="<?php  echo $row->State;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control add-listing_form" name="country" required="true" value="<?php  echo $row->Country;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip-Code</label>
                                        <input type="text" class="form-control add-listing_form" name="zipcode" required="true" value="<?php  echo $row->Zipcode;?>">
                                    </div>
                                </div>
                            </div>
                            <!--//End Add Location -->

                            <!-- Full Details -->
                            <div class="listing-title">
                                <span class="ti-location-pin"></span>
                                <h4>Full Details</h4>
                                <p>Update Something General Information About Your Listing</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Owner Name</label>
                                        <input type="text" class="form-control add-listing_form" name="ownername" required="true" value="<?php  echo $row->OwnerName;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control add-listing_form" name="email" required="true" value="<?php  echo $row->Email;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control add-listing_form" name="phone" required="true" maxlength="10" pattern="[0-9]+" value="<?php  echo $row->Phone;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Website</label>
                                        <input type="text" class="form-control add-listing_form" name="comwebsite" value="<?php  echo $row->CompanyWebsite;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Owner Designation</label>
                                        <input type="text" class="form-control add-listing_form" name="ownerdesi" required="true" value="<?php  echo $row->OwnerDesignation;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" class="form-control add-listing_form" name="company" required="true" value="<?php  echo $row->Company;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Facebook Link</label>
                                        <input type="text" class="form-control add-listing_form" name="flink" value="<?php  echo $row->FacebookLink;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Twitter User</label>
                                        <input type="text" class="form-control add-listing_form" name="twitterlink" value="<?php  echo $row->TweeterLink;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Google Plus</label>
                                        <input type="text" class="form-control add-listing_form" name="googlelink" value="<?php  echo $row->Googlepluslink;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Linked In</label>
                                        <input type="text" class="form-control add-listing_form" name="linkedin" value="<?php  echo $row->Linkedinlink;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Description</label>
                                        <textarea class="form-control add-listing_form" id="exampleFormControlTextarea1" rows="3" name="description" required="true"><?php  echo $row->Description;?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--//End Full Details -->
                            <?php $cnt=$cnt+1;}} ?>
                            <!-- Add Gallery -->
                            <div class="listing-title">
                                <span class="ti-gallery"></span>
                                <h4>Current Featured Images</h4>
                                <p>Current images associated with your listing.</p>
                            </div>

                            <div id="current-image-previews" class="row">
                                <?php
                                // Loop through existing images and display them
                                foreach ($existingImages as $image) {
                                    if (!empty($image)) {
                                        echo '<div class="col-md-4 image-preview">';
                                        echo '<div class="image-preview-wrapper">';
                                        echo '<img src="'.$image.'" alt="Image Preview" class="img-fluid" style="max-height: 150px;">';
                                        echo '<button type="button" class="btn btn-danger delete-btn" data-image="'.$image.'">Delete</button>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="listing-title">
                                <span class="ti-gallery"></span>
                                <h4>Upload New Images</h4>
                                <p>Upload new images for your listing</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="custom-file">
                                        <div class="add-gallery-text">
                                            <i class="ti-gallery"></i>
                                            <span>Drag &amp; Drop To Image</span>
                                        </div>
                                        <input type="file" class="custom-file-input" id="logo" name="logo[]" multiple>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Preview for New Images -->
                            <div id="new-image-previews" class="row mt-3">
                                <!-- New image previews will appear here -->
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-wrap btn-wrap2">
                                        <button type="submit" class="btn btn-primary" name="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px;">Update Listing</button>
                                    </div>
                                </div>
                            </div>

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

    <script>
        // Handle file input for new image uploads and display previews
        document.getElementById("logo").addEventListener("change", function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById("new-image-previews");
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

        // Handle delete image button (for new images)
        document.getElementById("new-image-previews").addEventListener("click", function(event) {
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

     // Delete image functionality with confirmation
            document.getElementById("current-image-previews").addEventListener("click", function(event) {
                if (event.target.classList.contains("delete-btn")) {
                    // Get the image path and listing ID
                    const imagePath = event.target.getAttribute("data-image");
                    const listingId = <?php echo $listingId; ?>;  // Pass the listing ID dynamically

                    // Show confirmation dialog
                    const confirmDelete = confirm("Are you sure you want to delete this image?");
                    
                    if (confirmDelete) {
                        // Make an AJAX request to delete the image
                        $.ajax({
                            url: "delete-image.php",
                            type: "POST",
                            data: {
                                action: 'delete_image',
                                image: imagePath,
                                listing_id: listingId
                            },
                            success: function(response) {

                                const result = JSON.parse(response);
                                if (result.success) {
                                    // Remove the image preview from the DOM
                                    const imagePreview = event.target.closest(".image-preview");
                                    imagePreview.remove();
                                } else {
                                    alert("Failed to delete the image.");
                                }
                            }
                        });
                    }
                }
            });


        // Function to delete an image
        function deleteImage(imagePath) {
            // Create a form data object
            const formData = new FormData();
            formData.append("action", "delete_image");
            formData.append("image", imagePath);
            
            // Send the form data to a PHP script to handle the image deletion
            fetch("delete-image.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image preview from the UI
                    const imagePreview = document.querySelector(`[data-image="${imagePath}"]`).closest(".image-preview");
                    imagePreview.remove();
                } else {
                    alert("Failed to delete the image.");
                }
            });
        }

        // Custom FileList for multiple file deletion
        function FileListItems(files) {
            const b = new ClipboardEvent('').clipboardData || new DataTransfer();
            files.forEach(file => b.items.add(file));
            return b.files;
        }
    </script>


</body>

</html><?php } ?>