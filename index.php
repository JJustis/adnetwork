<?php
// index.php - A simple page to showcase the widget
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Bidding Widget Showcase</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
            line-height: 1.6;
        }
        header {
            background-color: #2980b9;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 1.8em;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
        }
        .widget-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .widget-container:hover {
            transform: translateY(-5px);
        }
        .widget-container img,
        .widget-container video {
            border-radius: 8px;
            max-width: 100%;
            height: auto;
        }
        .widget-description {
            margin: 20px 0;
            font-size: 16px;
            color: #666;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
            font-size: 0.9em;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }
        .footer p {
            margin: 0;
        }
        @media (max-width: 768px) {
            header {
                font-size: 1.6em;
            }
            .widget-container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Ad Bidding Widget Showcase</h1>
</header>

<div class="widget-container" id="ad-widget-container">
    <p class="widget-description">This is where the ad widget will be displayed. Ads will appear here based on the latest bids and will be presented in a visually appealing manner.</p>
</div>

<!-- Embed the widget script -->
<script src="widget.php"></script>

<div class="footer">
    <p>&copy; 2025 Ad Bidding System. All Rights Reserved.</p>
</div>

</body>
</html>

