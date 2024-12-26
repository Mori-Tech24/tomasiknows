<?php
// Include your database connection
include('includes/dbconnection.php');

// Check if the request is a POST and action is delete_image
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_image') {
    $imagePath = $_POST['image'];  // Get the image path to delete

    // Remove image file from the server
    $imagePathOnServer = "images/" . $imagePath;  // Image path on the server
    if (file_exists($imagePathOnServer)) {
        unlink($imagePathOnServer);  // Delete the image file from the server
    }

    // Get the listing ID from the URL or session
    $listingId = $_POST['listing_id'];  // Get the listing ID from POST data (pass listing ID from JS)

    // Fetch the current 'Logo' column from the database
    $sql = "SELECT Logo FROM tbllisting WHERE ID = :listingId";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':listingId', $listingId, PDO::PARAM_INT);
    $stmt->execute();
    $listing = $stmt->fetch(PDO::FETCH_OBJ);

    // Remove the image from the 'Logo' column (comma-separated list of images)
    $images = explode(",", $listing->Logo);  // Split the images string into an array
    if (($key = array_search($imagePath, $images)) !== false) {
        unset($images[$key]);  // Remove the image from the array
    }

    // Update the 'Logo' column with the new image list (rejoin the images into a comma-separated string)
    $newImageList = implode(",", $images);
    $updateSql = "UPDATE tbllisting SET Logo = :images WHERE ID = :listingId";
    $updateStmt = $dbh->prepare($updateSql);
    $updateStmt->bindParam(':images', $newImageList, PDO::PARAM_STR);
    $updateStmt->bindParam(':listingId', $listingId, PDO::PARAM_INT);
    $updateStmt->execute();

    // Return a success response in JSON format
    echo json_encode(["success" => true]);
}
?>
