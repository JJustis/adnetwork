<?php
// get_ads.php - Fetches ads for the widget
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ad_bidding_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$current_time = date("Y-m-d H:i:s");

// Fetch scheduled ads first (ads with a time slot)
$sql = "SELECT * FROM ads WHERE start_time <= ? AND end_time >= ? ORDER BY bid_amount DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $current_time, $current_time);
$stmt->execute();
$result = $stmt->get_result();

$ads = [];

if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
    if (!empty($row['video_url']) && strpos($row['video_url'], "youtu") !== false) {
        // Extract Video ID and format as an embeddable URL
        preg_match("/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([\w-]+)/", $row['video_url'], $matches);
        if (!empty($matches[1])) {
            $row['video_url'] = "https://www.youtube.com/embed/" . $matches[1] . "?autoplay=1&controls=0";
        }
    }
    $ads[] = $row;
}

}

$stmt->close();

// If no scheduled ads, fetch default ads (ads without a time slot)
if (empty($ads)) {
    $sql = "SELECT * FROM ads WHERE start_time IS NULL OR end_time IS NULL ORDER BY RAND()";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        for ($i = 0; $i < max(1, (int) $row['vouchers']); $i++) { // Repeat ad based on vouchers
            $ads[] = $row;
        }
    }
}

$conn->close();

// Return ads as an array
if (!empty($ads)) {
    echo json_encode($ads); // Always return a JSON array
} else {
    echo json_encode([]); // Return an empty array instead of an error message
}

exit;
