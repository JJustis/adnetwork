<?php
// widget.php - This is the main file for displaying the ad widget
header('Content-Type: application/javascript'); // Send JS content type for embedding

// Database connection
$servername = "localhost"; // Your server
$username = "root"; // Your username
$password = ""; // Your password
$dbname = "ad_bidding_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get the highest-bid ad
function getHighestBidAd($conn) {
    // Get current time for ad selection
    $current_time = date("Y-m-d H:i:s");

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM ads WHERE start_time <= ? AND end_time >= ? ORDER BY bid_amount DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $current_time, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // No ads available
    }
}

// Check if the ad data is being returned
$ad = getHighestBidAd($conn);
if (!$ad) {
    // If no active ad, return default ad data
    $ad = [
        'image_url' => '/uploads/6600c0a58d4bc.jpg', // Earth default image
        'advertiser_id' => null,
        'ad_title' => 'Earth Ad',
        'video_url' => null,
    ];
} else {
    error_log("Ad data: " . print_r($ad, true));  // Log the ad data
}

// Close the database connection
$conn->close();
?>
(function () {
    let adQueue = [];
    let defaultAds = [];
    let currentAdIndex = 0;

    function fetchAds() {
        fetch('get_ads.php')
            .then(response => response.json())
            .then(data => {
                console.log("Ad data received:", data);
                
                adQueue = data.scheduledAds || [];
                defaultAds = data.defaultAds || [];

                if (adQueue.length > 0) {
                    startAdRotation();
                } else {
                    cycleDefaultAds();
                }
            })
            .catch(error => console.error('Error fetching ads:', error));
    }

    function startAdRotation() {
        if (adQueue.length > 0) {
            displayAd(adQueue.shift());
        } else {
            cycleDefaultAds();
        }
    }

    function cycleDefaultAds() {
        if (defaultAds.length === 0) return;

        const ad = defaultAds[currentAdIndex];
        displayAd(ad);

        currentAdIndex = (currentAdIndex + 1) % defaultAds.length;
        setTimeout(cycleDefaultAds, 5000); // Rotate ads every 5 seconds
    }

    function displayAd(ad) {
        const adContainer = document.getElementById('ad-container') || createAdContainer();
        adContainer.innerHTML = ''; // Clear previous content

        if (!ad) {
            console.warn("Invalid ad data.");
            return;
        }

        let adContent;

        if (ad.video_url && ad.video_url.includes("youtu")) {
            // Handle YouTube links
            const videoId = ad.video_url.split("/").pop().split("?")[0];
            const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&controls=0`;

            const iframe = document.createElement("iframe");
            iframe.src = embedUrl;
            iframe.width = "100%";
            iframe.height = "300px";
            iframe.allow = "autoplay; encrypted-media";
            iframe.frameBorder = "0";
            adContent = iframe;
        } else if (ad.video_url) {
            // Handle regular video ads
            adContent = document.createElement("video");
            adContent.src = ad.video_url;
            adContent.autoplay = true;
            adContent.muted = true;
            adContent.loop = true;
            adContent.controls = false;
            adContent.style.width = "100%";
        } else if (ad.image_url) {
            // Handle image ads
            adContent = document.createElement("img");
            adContent.src = ad.image_url;
            adContent.style.width = "100%";
        }

        if (adContent) {
            adContainer.appendChild(adContent);
            setTimeout(startAdRotation, (ad.duration || 5) * 1000);
        }
    }

    function createAdContainer() {
        const adContainer = document.createElement("div");
        adContainer.id = "ad-container";
        adContainer.style.textAlign = "center";
        adContainer.style.margin = "20px auto";
        adContainer.style.border = "1px solid #ddd";
        adContainer.style.padding = "15px";
        adContainer.style.borderRadius = "10px";
        adContainer.style.boxShadow = "0 6px 12px rgba(0, 0, 0, 0.1)";
        adContainer.style.maxWidth = "600px";
        adContainer.style.transition = "opacity 0.5s ease-in-out";

        document.body.appendChild(adContainer);
        return adContainer;
    }

    fetchAds();
})();

function playAd(ad) {
  const adContainer = document.getElementById("ad-container");
  adContainer.innerHTML = ""; // Clear previous content

  if (ad.video_url && ad.video_url.includes("youtu")) {
    // Extract Video ID and format the correct YouTube Embed URL
    const videoId = ad.video_url.split("/").pop().split("?")[0];
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&controls=0`;

    const iframe = document.createElement("iframe");
    iframe.src = embedUrl;
    iframe.width = "100%";
    iframe.height = "100%";
    iframe.allow = "autoplay; encrypted-media";
    iframe.frameBorder = "0";

    adContainer.appendChild(iframe);
  } else if (ad.image_url) {
    // Display image ad
    const img = document.createElement("img");
    img.src = ad.image_url;
    img.style.width = "100%";
    adContainer.appendChild(img);
  }
}

// JavaScript to display the ad
(function() {
    const adData = <?php echo json_encode($ad); ?>; // Embed PHP ad data into JS

    console.log("Ad data received:", adData); // Debug log to see what adData contains

    // Default ad image for Earth
    const defaultAdImage = "http://localhost/dashboard/uploads/6600c0a58d4bc.jpg"; // Replace with the actual path to Earth image

    // Create the ad container and apply a modern card style
    const adContainer = document.createElement('div');
    adContainer.style.border = "1px solid #ddd";
    adContainer.style.borderRadius = "15px";
    adContainer.style.boxShadow = "0 6px 12px rgba(0, 0, 0, 0.1)";
    adContainer.style.margin = "20px auto";
    adContainer.style.padding = "20px";
    adContainer.style.maxWidth = "600px";
    adContainer.style.textAlign = "center";
    adContainer.style.transition = "transform 0.3s ease, box-shadow 0.3s ease";
    adContainer.style.backgroundColor = "#fff";

    // Add hover effect for a more dynamic feel
    adContainer.onmouseover = function() {
        adContainer.style.transform = "scale(1.05)";
        adContainer.style.boxShadow = "0 12px 24px rgba(0, 0, 0, 0.2)";
    };

    adContainer.onmouseleave = function() {
        adContainer.style.transform = "scale(1)";
        adContainer.style.boxShadow = "0 6px 12px rgba(0, 0, 0, 0.1)";
    };

    let adContent;

    // Check if ad data has image_url or video_url and create adContent accordingly
    if (adData && (adData.image_url || adData.video_url)) {
        if (adData.image_url) {
            // If the ad has an image
            adContent = document.createElement('img');
            adContent.src = adData.image_url;
            adContent.alt = adData.ad_title || "Advertiser Ad";
            adContent.style.width = "100%";  // Make the image responsive
            adContent.style.borderRadius = "10px"; // Round the edges of the ad content
        } else if (adData.video_url) {
            // If the ad has a video
            adContent = document.createElement('video');
            adContent.src = adData.video_url;
            adContent.controls = true;
            adContent.style.width = "100%";  // Make the video responsive
            adContent.style.borderRadius = "10px"; // Round the edges of the video
        }
    } else {
        // If no valid ad content, fall back to the Earth image
        adContent = document.createElement('img');
        adContent.src = defaultAdImage;
        adContent.alt = "Earth Ad";
        adContent.style.width = "100%";  // Make the image responsive
        adContent.style.borderRadius = "10px"; // Round the edges of the default image
    }

    // Check if adContent was created successfully
    if (adContent instanceof Node) {
        adContainer.appendChild(adContent);

        // Add a smooth fade-in effect when the ad loads
        adContainer.style.opacity = 0;
        setTimeout(function() {
            adContainer.style.transition = "opacity 1s ease-in";
            adContainer.style.opacity = 1;
        }, 100);

        // If there's an active ad, link to the advertiser's site
        if (adData && adData.advertiser_id) {
            const adLink = document.createElement('a');
            adLink.href = `https://example.com/advertiser/${adData.advertiser_id}`; // Replace with actual link
            adLink.target = "_blank";
            adLink.innerHTML = "Click to visit advertiser";
            adLink.style.display = "block";
            adLink.style.marginTop = "15px";
            adLink.style.fontSize = "16px";
            adLink.style.color = "#2980b9";
            adLink.style.fontWeight = "bold";
            adLink.style.textDecoration = "none";
            adLink.style.transition = "color 0.3s ease";

            adLink.onmouseover = function() {
                adLink.style.color = "#3498db";
            };

            adLink.onmouseleave = function() {
                adLink.style.color = "#2980b9";
            };

            adContainer.appendChild(adLink);
        }

        // Insert the ad container into the page
        document.body.appendChild(adContainer);
    } else {
        console.error("Invalid ad content to append. Ad content may be null or undefined.");
    }
})();

