<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION['usertype']!=2) {
  header('location:error_404.php');

}


$listingId = $_GET['editid'];

// Fetch the listing data from the database
$sql = "SELECT * FROM tbllisting WHERE ID = :listingId";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':listingId', $listingId, PDO::PARAM_INT);
$stmt->execute();
$listing = $stmt->fetch(PDO::FETCH_OBJ);

// Get existing images (if any)
$existingImages = explode(",", $listing->Logo); // Assuming 'Logo' column holds comma-separated paths


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
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];
  $eid = $_GET['editid'];


  // Handle image uploads
  if (isset($_FILES['logo']) && count($_FILES['logo']['name']) > 0 && $_FILES['logo']['name'][0] !== "") { // Check if files are uploaded
    // Initialize the array to store the paths of the uploaded images
    $uploadedImages = [];
    $uploadDir = '../images/'; // Directory where images will be stored

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

    // Combine the existing images with the newly uploaded ones, if any
    if (!empty($existingImages) && $existingImages[0] !== "") {
        $allImages = array_merge($existingImages, $uploadedImages);
    } else {
        $allImages = $uploadedImages; // No existing images
    }

    // Convert the array to a comma-separated string
    $newImageList = implode(",", $allImages);
} else {
    // No new images uploaded, keep the existing images
    $newImageList = implode(",", $existingImages);
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
              Logo = :logo,
                longitude = :longitude,
                latitude = :latitude

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
  $query->bindParam(':longitude', $latitude, PDO::PARAM_STR);
  $query->bindParam(':latitude', $longitude, PDO::PARAM_STR);


  $query->bindParam(':eid', $eid, PDO::PARAM_INT);



  // Execute the update query
  $query->execute();

  echo '<script>alert("Listing Updated.")</script>';
  echo "<script>window.location.href ='edit_listing.php?editid=$eid'</script>";
  // Redirect to the same page to avoid resubmission of the form

  exit(); // Make sure the script stops executing here
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Local Services Search Engine Mgmt System | Edit Listing</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include_once('includes/header.php'); ?>
  <?php include_once('includes/sidebar.php'); ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Listing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Edit Listing</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Edit Listing</h3>
            </div>

            <!-- Tabs -->
                <div class="card-body">
                      <div class="listing-wrap">
                        <form method="post" enctype="multipart/form-data">
                            <?php
$eid=$_GET['editid'];
$userid=$_SESSION['lssemsaid'];   
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
                                    <div class="form-group d-none" >
                                        <label>Keyword</label>
                                        <input type="text" class="form-control add-listing_form" name="keywords"  value="<?php  echo $row->Keyword;?>">
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
                                    <div class="form-group d-none">
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
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" class="form-control add-listing_form" name="state" required="true" value="<?php  echo $row->State;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Temporary Address</label>
                                        <input type="text" class="form-control add-listing_form" name="tadd"  value="<?php  echo $row->TemporaryAddress;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control add-listing_form" name="city" required="true" value="<?php  echo $row->City;?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Zip-Code</label>
                                        <input type="text" class="form-control add-listing_form" name="zipcode" required="true" value="<?php  echo $row->Zipcode;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Country</label>
                                        <input type="text" class="form-control add-listing_form" name="country" value="<?php  echo $row->Country;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <!--//End Add Location -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>longitude</label>
                                        <input type="text" class="form-control add-listing_form" name="longitude" id="longitude" value="<?php  echo $row->longitude;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>latitude</label>
                                        <input type="text" class="form-control add-listing_form" name="latitude" id="latitude" value="<?php  echo $row->latitude;?>">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btngetLoc" style="color:white !important">Get My Current Location</button> <i class="fa fa-info-circle text-primary" title="User to get the current longitude and latitude">What is this?</i>
                                </div>                     
                            </div>

                            <br>

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
                                    <div class="form-group d-none">
                                        <label>Company Website</label>
                                        <input type="text" class="form-control add-listing_form" name="comwebsite" value="<?php  echo $row->CompanyWebsite;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Owner Designation</label>
                                        <input type="text" class="form-control add-listing_form" name="ownerdesi" value="<?php  echo $row->OwnerDesignation;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Company</label>
                                        <input type="text" class="form-control add-listing_form" name="company" value="<?php  echo $row->Company;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Facebook Link</label>
                                        <input type="text" class="form-control add-listing_form" name="flink" value="<?php  echo $row->FacebookLink;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Twitter User</label>
                                        <input type="text" class="form-control add-listing_form" name="twitterlink" value="<?php  echo $row->TweeterLink;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Google Plus</label>
                                        <input type="text" class="form-control add-listing_form" name="googlelink" value="<?php  echo $row->Googlepluslink;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label>Linked In</label>
                                        <input type="text" class="form-control add-listing_form" name="linkedin" value="<?php  echo $row->Linkedinlink;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
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
                                    <div class="custom-file" style="border: 2px dashed #ccc; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; position: relative;">
                                        <div class="add-gallery-text" style="font-size: 16px; color: #666; margin-bottom: 10px;">
                                            <i class="ti-gallery" style="font-size: 24px; display: block; margin-bottom: 5px;"></i>
                                            <span>Drag &amp; Drop or Click to Upload Image(s)</span>
                                        </div>
                                        <input type="file" class="custom-file-input" id="logo" name="logo[]" multiple 
                                            style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
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
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function () {
    $('#pendingTable').DataTable();
    $('#approvedTable').DataTable();
    $('#rejectedTable').DataTable();
  });
</script>
<script>

  
$(document).on("click",".btngetLoc", function() {

     
if (navigator.geolocation) {
// Request the current position
navigator.geolocation.getCurrentPosition(
    function (position) {
    // Success callback
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    $("#longitude").val(longitude);
    $("#latitude").val(latitude);

    // console.log("Latitude: " + latitude);
    // console.log("Longitude: " + longitude);

    // Use the coordinates for your application logic
    },
    function (error) {
    // Error callback
        alert("Error retrieving location: ", error.message);
    }
);
} else {
    alert("Geolocation is not supported by this browser.");
}



});

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
</html>

