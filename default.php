<?php
// Include any necessary files or database connections
include_once 'db_config.php';

// Fetch all default ads from the database
$query = "SELECT * FROM ads WHERE status = 'Pending'"; // Assuming ads are stored in 'ads' table and have a 'status' field
$result = mysqli_query($conn, $query);
$ads = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Default Ad Placement</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your site's CSS -->
    <style>
        /* General Layout Styles */
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
            color: #2980b9;
            margin-bottom: 40px;
        }

        footer {
            text-align: center;
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 50px;
        }

        /* Ad List Styles */
        .ad-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .ad-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .ad-card:hover {
            transform: translateY(-10px);
        }

        .ad-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .ad-card h3 {
            color: #2980b9;
            font-size: 1.4em;
            margin-bottom: 10px;
        }

        .ad-card p {
            font-size: 1em;
            color: #333;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-content h2 {
            color: #2980b9;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .modal-content p {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 15px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 2em;
            color: #333;
            cursor: pointer;
        }

    </style>
</head>
<body>

<header>
    <h1>Default Ad Placement</h1>
    <p>Here are the default ads. Click on any ad for more details.</p>
</header>

<div class="ad-list">
    <?php foreach ($ads as $ad): ?>
        <div class="ad-card" onclick="openModal('<?php echo $ad['id']; ?>')">
            <img src="<?php echo htmlspecialchars($ad['image_url']); ?>" alt="Ad Image">
            <h3><?php echo htmlspecialchars($ad['ad_type']); ?></h3>
            <p>Status: <?php echo htmlspecialchars($ad['status']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal -->
<div id="adModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Ad Details</h2>
        <p id="ad-description"></p>
        <p id="ad-status"></p>
        <p id="ad-bid"></p>
    </div>
</div>

<footer>
    <p>&copy; 2025 Ad Bidding System - All rights reserved.</p>
</footer>

<script>
    function openModal(adId) {
        // Fetch ad data by ID from PHP or set manually
        // In practice, you'd probably use AJAX to fetch this dynamically

        // Example: You can fetch data by adId using an AJAX request (below is a placeholder for now)
        var adDescription = "This is the description for Ad ID " + adId;
        var adStatus = "Status: Pending"; // You'd dynamically fetch the status based on adId
        var adBid = "Bid Amount: $0.00"; // Similarly, dynamically fetch the bid amount

        document.getElementById("ad-description").innerText = adDescription;
        document.getElementById("ad-status").innerText = adStatus;
        document.getElementById("ad-bid").innerText = adBid;

        // Show the modal
        document.getElementById("adModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("adModal").style.display = "none";
    }
</script>

</body>
</html>
