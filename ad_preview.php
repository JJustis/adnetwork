<?php
$ad_id = $_GET['id'] ?? 0;
if ($ad_id == 0) {
    die('Invalid Ad ID');
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "ad_bidding_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch ad details
$stmt = $conn->prepare("SELECT * FROM ads WHERE ad_id = ?");
$stmt->bind_param("i", $ad_id);
$stmt->execute();
$result = $stmt->get_result();
$ad = $result->fetch_assoc();
$stmt->close();

if (!$ad) {
    die('Ad not found');
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Preview - <?php echo htmlspecialchars($ad['ad_title']); ?></title>
    <style>
        /* Basic Styling for Preview */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
        }

        .preview-container {
            width: <?php echo $ad['width']; ?>px;
            height: <?php echo $ad['height']; ?>px;
            border: 2px solid #ccc;
            margin-bottom: 20px;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .preview-container img {
            max-width: 100%;
            max-height: 100%;
        }

        .editor-container {
            margin-top: 30px;
        }

        textarea {
            width: 100%;
            height: 200px;
            padding: 10px;
            border: 1px solid #ccc;
            font-family: monospace;
            font-size: 1em;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<h1>Preview Ad: <?php echo htmlspecialchars($ad['title']); ?></h1>

<div class="preview-container" id="preview-container">
    <img src="<?php echo htmlspecialchars($ad['ad_image_url']); ?>" alt="Ad Preview">
</div>

<div class="editor-container">
    <h2>Edit JavaScript</h2>
    <textarea id="js-editor"><?php echo htmlspecialchars($ad['js_code']); ?></textarea>
    <button onclick="previewAd()">Preview JS Effects</button>
</div>

<script>
    function previewAd() {
        const jsCode = document.getElementById('js-editor').value;
        const previewContainer = document.getElementById('preview-container');

        try {
            eval(jsCode); // This will execute the JS code and apply effects to the preview
        } catch (error) {
            alert("Error in JS code: " + error.message);
        }
    }
</script>

</body>
</html>
