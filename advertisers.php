<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ad_bidding_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define ad types and their dimensions
$ad_types = [
    'bar' => ['width' => 240, 'height' => 600],
    'horizontalbar' => ['width' => 240, 'height' => 300],
    'largepanel' => ['width' => 480, 'height' => 600],
    'square' => ['width' => 240, 'height' => 240],
];

// Handling Ad Submission (Bid Placement)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_bid'])) {
    // Ensure all POST variables are set or set defaults
    $ad_type = isset($_POST['ad_type']) ? $_POST['ad_type'] : ''; 
    $bid_amount = isset($_POST['bid_amount']) ? $_POST['bid_amount'] : 0;
    $time_slot = isset($_POST['time_slot']) ? $_POST['time_slot'] : ''; 
    $duration = isset($_POST['duration']) ? $_POST['duration'] : 0;
    $ad_image_url = isset($_POST['ad_image_url']) ? $_POST['ad_image_url'] : '';
    $js_code = isset($_POST['js_code']) ? $_POST['js_code'] : ''; // JavaScript code input

    // Get the advertiser ID dynamically or from the session (for now set to static for simplicity)
    $advertiser_id = 1;

    // Get ad dimensions based on the ad type
    if (isset($ad_types[$ad_type])) {
        $width = $ad_types[$ad_type]['width'];
        $height = $ad_types[$ad_type]['height'];
    } else {
        // Default dimensions if ad type is invalid
        $width = 240;
        $height = 600;
    }

    // Start and end time for ad placement (static for now)
    $start_time = date("Y-m-d H:i:s");
    $end_time = date("Y-m-d H:i:s", strtotime("+24 hours"));

    // Prepare statement to insert ad with width, height, and bid details (status is hardcoded to 'Pending')
    $stmt = $conn->prepare("INSERT INTO ads (advertiser_id, ad_type, bid_amount, time_slot, duration, ad_image_url, width, height, start_time, end_time, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters (ensure the right number of parameters are passed)
    $stmt->bind_param("isdsdsssss", $advertiser_id, $ad_type, $bid_amount, $time_slot, $duration, $ad_image_url, $width, $height, $start_time, $end_time);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Ad bid placed successfully!";
    } else {
        $message = "Error placing ad bid: " . $stmt->error;
    }

    $stmt->close();
}

// Retrieve ads for this advertiser
$sql = "SELECT * FROM ads WHERE advertiser_id = 1 ORDER BY start_time DESC";
$result = $conn->query($sql);

$ads = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ads[] = $row;
    }
} else {
    $message = "No ads found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertiser Dashboard</title>
    <style>
        /* Reset and General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }

        header {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        footer {
            text-align: center;
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 50px;
        }

        /* Form Styles */
        .ad-form {
            background-color: #fff;
            padding: 30px;
            margin-bottom: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .ad-form h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #2980b9;
        }

        .ad-form label {
            font-size: 1em;
            margin-bottom: 8px;
            display: block;
            color: #7f8c8d;
        }

        .ad-form input[type="text"],
        .ad-form input[type="url"],
        .ad-form select,
        .ad-form input[type="number"],
        .ad-form input[type="time"],
        .ad-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .ad-form button {
            padding: 12px 25px;
            background-color: #2980b9;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .ad-form button:hover {
            background-color: #3498db;
        }

        /* Active Ads Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .ad-form input[type="text"],
            .ad-form input[type="url"],
            .ad-form select,
            .ad-form input[type="number"],
            .ad-form input[type="time"],
            .ad-form textarea {
                font-size: 0.9em;
            }

            .ad-form button {
                font-size: 1em;
                padding: 10px 20px;
            }

            table {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Advertiser Dashboard</h1>
    <p>Welcome! Place your bids for ad spots below.</p>
</header>

<?php if (isset($message)): ?>
    <div class="message" style="text-align:center; color: #27ae60;"><?php echo $message; ?></div>
<?php endif; ?>

<section class="ad-form">
    <h2>Place a New Bid</h2>
    <form action="advertisers.php" method="POST">
        <div>
            <label for="ad_type">Ad Type:</label>
            <select name="ad_type" id="ad_type" required>
                <option value="bar">Bar</option>
                <option value="horizontalbar">Horizontal Bar</option>
                <option value="largepanel">Large Panel</option>
                <option value="square">Square</option>
            </select>
        </div>
        <!-- Title -->
        <div>
            <label for="ad_title">Ad Title:</label>
            <input type="text" name="ad_title" id="ad_title" required>
        </div>

        <!-- Video URL -->
        <div>
            <label for="ad_video_url">Video URL (Optional):</label>
            <input type="url" name="ad_video_url" id="ad_video_url">
        </div>
        <div>
            <label for="bid_amount">Bid Amount ($):</label>
            <input type="number" name="bid_amount" id="bid_amount" min="1" required>
        </div>

        <div>
            <label for="time_slot">Select Time Slot:</label>
            <input type="time" name="time_slot" id="time_slot" required>
        </div>

        <div>
            <label for="duration">Ad Duration (seconds):</label>
            <input type="number" name="duration" id="duration" min="1.9" max="6" step="0.1" required>
        </div>

        <div>
            <label for="ad_image_url">Ad Image URL:</label>
            <input type="url" name="ad_image_url" id="ad_image_url" required>
        </div>

        <div>
            <label for="js_code">Custom JavaScript:</label>
            <textarea name="js_code" id="js_code" rows="4" placeholder="Enter your custom JS code for this ad"></textarea>
        </div>

        <button type="submit" name="place_bid">Place Bid</button>
    </form>
</section>

<section>
    <h2>Active Ads</h2>
    <table>
        <thead>
            <tr>
                <th>Ad Type</th>
                <th>Bid Amount</th>
                <th>Duration</th>
                <th>Time Slot</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ads as $ad): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ad['ad_type']); ?></td>
                    <td><?php echo htmlspecialchars($ad['bid_amount']); ?></td>
                    <td><?php echo htmlspecialchars($ad['duration']); ?> seconds</td>
                    <td><?php echo htmlspecialchars($ad['time_slot']); ?></td>
                    <td>
                        <a href="ad_preview.php?id=<?php echo $ad['ad_id']; ?>">Preview</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2025 Ad Bidding System. All rights reserved.</p>
</footer>

</body>
</html>
