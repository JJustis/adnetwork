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
    <title>Ad Preview - <?php echo htmlspecialchars($ad['title']); ?></title>
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; /* Change to start from the top */
            height: 100vh;
            padding-top: 20px; /* Add padding to prevent ad from being too close to the top */
        }

        /* Card Layout */
        .preview-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 90%;
            max-width: 600px;
        }

        /* Ad Preview Container */
        .preview-container {
            width: 100%;
            height: auto;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f9f9f9;
        }

        /* Image & Video Styling */
        .preview-image, .preview-video {
            width: 100%;
            height: auto;
            transition: opacity 0.3s ease-in-out;
            z-index: 1; /* Set the z-index to ensure the image and video stack correctly */
        }

        /* Hide image */
        .hidden {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            z-index: 0; /* Move hidden image behind the video */
        }

        /* Editor Section */
        .editor-container {
            margin-top: 20px;
            text-align: left;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-family: monospace;
            font-size: 1em;
        }

        /* Modern Button */
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            font-size: 1em;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<div class="preview-card">
    <h2><?php echo htmlspecialchars($ad['title']); ?></h2>

    <div class="preview-container">
        <?php if (!empty($ad['image_url'])): ?>
            <img id="preview-image" class="preview-image" src="<?php echo htmlspecialchars($ad['image_url']); ?>" alt="Ad Image">
        <?php endif; ?>

        <?php if (!empty($ad['video_url'])): ?>
            <div id="preview-video-container" class="preview-video hidden">
                <?php
                // Check if the video URL is from YouTube
                $videoUrl = htmlspecialchars($ad['video_url']);
                if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                    // Extract video ID from YouTube URL
                    preg_match('/(?:youtube\.com\/(?:[^\/\n]+\/\S+\/|(?:v|e(?:mbed)?)\/|(?:[^\/\n]+\/|(?:watch\?v=))([^&\n]+))|youtu\.be\/([^&\n]+))/i', $videoUrl, $matches);
                    $youtubeId = $matches[1] ?? $matches[2];
                    echo "<iframe class='preview-video' src='https://www.youtube.com/embed/{$youtubeId}?autoplay=1' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>";
                } else {
                    // Display regular video if not from YouTube
                    echo "<video class='preview-video' autoplay muted controls>
                            <source src='{$videoUrl}' type='video/mp4'>
                            Your browser does not support the video tag.
                          </video>";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="editor-container">
        <h3>Edit JavaScript</h3>
        <textarea id="js-editor"><?php echo htmlspecialchars($ad['js_code']); ?></textarea>
        <button class="btn" onclick="previewAd()">Preview JS Effects</button>
    </div>
</div>

<script>
    function previewAd() {
        const jsCode = document.getElementById('js-editor').value;
        try {
            eval(jsCode);
        } catch (error) {
            alert("Error in JS code: " + error.message);
        }
    }

    window.onload = function() {
        const image = document.getElementById('preview-image');
        const videoContainer = document.getElementById('preview-video-container');

        if (image && videoContainer) {
            setTimeout(() => {
                image.classList.add('hidden');
                setTimeout(() => {
                    videoContainer.classList.remove('hidden');
                }, 200);
            }, 100);
        }
    };
</script>

</body>
</html>

