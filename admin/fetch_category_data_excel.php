<?php
include('includes/dbconnection.php');

$startDate = $_GET['startDate'] ?? '';
$endDate = $_GET['endDate'] ?? '';
$barangayDropdownCategory = $_GET['bar'] ?? '';

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

// Execute the query
$result = $dbh->query($query);

// Get the current timestamp and format it (e.g., 2024-12-15_12-30-00)
$currentTimestamp = date('Y-m-d_H-i-s');

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="most_book_category_' . $currentTimestamp . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open the output stream to PHP's output buffer
$output = fopen('php://output', 'w');

// Write column headers to the CSV
fputcsv($output, ['Category', 'Total Count']);

// Write the data rows to the CSV
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['Category'],
        $row['total_count']
    ]);
}

// Close the output stream
fclose($output);
exit;
?>
