<?php
include('includes/dbconnection.php');

$start_date = $_GET['stdate'];
$end_date = $_GET['eddate'];

// Capture the barangay (State) if it is passed
$barangay = isset($_GET['bar']) ? $_GET['bar'] : ''; 

// SQL query with optional barangay filter
$sql = "SELECT COUNT(a.id) AS total_count, c.Business_name, b.State
        FROM tbl_reservations a
        LEFT JOIN tbllisting b ON b.ID = a.listing_id 
        LEFT JOIN tbladmin c ON c.ID = b.UserID
        WHERE a.reservation_status = 1 AND a.reservation_date BETWEEN :start_date AND :end_date";

// If barangay is provided, filter by it
if ($barangay !== '') {
    $sql .= " AND b.State = :barangay";
}

// Group by business and State
$sql .= " GROUP BY b.UserID ORDER BY total_count DESC";

$query = $dbh->prepare($sql);
$query->bindParam(':start_date', $start_date, PDO::PARAM_STR);
$query->bindParam(':end_date', $end_date, PDO::PARAM_STR);

// Bind the barangay parameter if it's set
if ($barangay !== '') {
    $query->bindParam(':barangay', $barangay, PDO::PARAM_STR);
}

$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

$currentTimestamp = date('Y-m-d_H-i-s');

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="most_book_business_' . $currentTimestamp . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream to PHP's output buffer
$output = fopen('php://output', 'w');

// Write column headers to the CSV
fputcsv($output, ['Business Name', 'Barangay', 'Booked Count']);

// Write the data rows to the CSV
foreach ($results as $row) {
    fputcsv($output, [
        $row['Business_name'],
        ucwords($row['State']),
        $row['total_count']
    ]);
}

// Close the output stream
fclose($output);
exit;
?>
