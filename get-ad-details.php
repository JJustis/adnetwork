<?php
// get-ad-details.php
include 'db_config.php'; // Include the database connection

if (isset($_GET['id'])) {
    $adId = $_GET['id'];
    
    // Fetch the ad details from the database
    $sql = "SELECT * FROM ads WHERE id = $adId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $ad = $result->fetch_assoc();
        echo json_encode([
            'js_code' => $ad['js_code'] // Return existing JS code if available
        ]);
    } else {
        echo json_encode(['error' => 'Ad not found']);
    }
}
?>
