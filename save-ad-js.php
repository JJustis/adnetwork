<?php
// save-ad-js.php
include 'db_config.php'; // Include the database connection

if (isset($_POST['id']) && isset($_POST['js_code'])) {
    $adId = $_POST['id'];
    $jsCode = $_POST['js_code'];
    
    // Sanitize the JS code (basic sanitization for security)
    $jsCode = $conn->real_escape_string($jsCode);
    
    // Update the ad with the new JS code
    $sql = "UPDATE ads SET js_code = '$jsCode' WHERE id = $adId";
    
    if ($conn->query($sql) === TRUE) {
        echo "Ad JS code updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
