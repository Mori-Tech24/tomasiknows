<?php
include('includes/dbconnection.php');

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    // Capture the barangay (State) if it is passed
    $barangay = isset($_POST['barangay']) ? $_POST['barangay'] : ''; 
    $category = isset($_POST['category']) ? $_POST['category'] : '';

    // SQL query with optional barangay filter
    $sql = "SELECT COUNT(a.id) AS total_count, c.Business_name, a.reservation_date, b.State
            FROM tbl_reservations a
            LEFT JOIN tbllisting b ON b.ID = a.listing_id 
            LEFT JOIN tbladmin c ON c.ID = b.UserID
            WHERE a.reservation_status = 1 AND a.reservation_date BETWEEN :start_date AND :end_date";

    // If barangay is provided, filter by it
    if (!empty($barangay)) {
        $sql .= " AND b.State = :barangay";
    }

    // If category is provided, filter by it
    if (!empty($category)) {
        $sql .= " AND b.Category = :category";
    }

    // Group by business and State
    $sql .= " GROUP BY listing_id ORDER BY total_count DESC";

    $query = $dbh->prepare($sql);
    $query->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $query->bindParam(':end_date', $end_date, PDO::PARAM_STR);

    // Bind the barangay parameter if it's set
    if (!empty($barangay)) {
        $query->bindParam(':barangay', $barangay, PDO::PARAM_STR);
    }

    // Bind the category parameter if it's set
    if (!empty($category)) {
        $query->bindParam(':category', $category, PDO::PARAM_STR);
    }

    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($results);
}
?>
