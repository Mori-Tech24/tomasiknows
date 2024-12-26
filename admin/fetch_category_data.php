<?php
include('includes/dbconnection.php');

$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';
$barangayDropdownCategory = $_POST['barangayDropdownCategory'] ?? '';

// Base Query
$query = "
    SELECT COUNT(a.id) AS total_count, b.Category, a.reservation_date, d.State
    FROM tbl_reservations a
    LEFT JOIN tbllisting d ON d.ID = a.listing_id
    LEFT JOIN tblcategory b ON b.ID = d.Category
    WHERE a.reservation_status = 1
";

// Add date filter if provided
if (!empty($startDate) && !empty($endDate)) {
    $query .= " AND a.reservation_date BETWEEN '$startDate' AND '$endDate'";
}

// Add barangay filter if provided
if (!empty($barangayDropdownCategory)) {
    $query .= " AND d.State = '$barangayDropdownCategory'";
}

$query .= " GROUP BY b.Category";

$result = $dbh->query($query);
$data = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        'Category' => $row['Category'],
        'total_count' => $row['total_count']
    ];
}

echo json_encode($data);
?>
