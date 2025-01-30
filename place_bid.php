<?php
// place_bid.php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_bid'])) {
    // Get form inputs
    $ad_type = $_POST['ad_type'];
    $bid_amount = $_POST['bid_amount'];
    $time_slot = $_POST['time_slot'];
    $duration = $_POST['duration'];
    $advertiser_id = 1; // Assuming logged-in advertiser has ID 1

    // Sanitize inputs
    $ad_type = mysqli_real_escape_string($conn, $ad_type);
    $bid_amount = floatval($bid_amount);
    $time_slot = mysqli_real_escape_string($conn, $time_slot);
    $duration = floatval($duration);

    // Insert bid into the database
    $query = "INSERT INTO ads (advertiser_id, ad_type, bid_amount, time_slot, duration) 
              VALUES ('$advertiser_id', '$ad_type', '$bid_amount', '$time_slot', '$duration')";
    mysqli_query($conn, $query);
    $message = "Your bid has been placed!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place a Bid</title>
</head>
<body>
    <?php if (isset($message)): ?>
        <div><?php echo $message; ?></div>
    <?php endif; ?>
</body>
</html>

