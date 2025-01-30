<?php
// gateway-config.php

// Function to get shop configuration
function getShopConfig() {
    $configFile = 'shop_config.json';
    
    // Check if the configuration file exists
    if (!file_exists($configFile)) {
        // If not, use default values
        return [
            'paypal_email' => 'Vikerus1@gmail.com',  // Default PayPal email
            'paypal_sandbox' => true,  // Sandbox environment for testing
            'paypal_currency' => 'USD',  // Default currency
        ];
    }
    
    // Read the JSON configuration file
    $json = file_get_contents($configFile);
    return json_decode($json, true) ?: [];
}

// Get shop configuration (from JSON file or defaults)
$shopConfig = getShopConfig();

// PayPal configuration constants
define('PAYPAL_EMAIL', $shopConfig['paypal_email'] ?? '');
define('PAYPAL_SANDBOX', $shopConfig['paypal_sandbox'] ?? true);
define('PAYPAL_CURRENCY', $shopConfig['paypal_currency'] ?? 'USD');

// Set the appropriate PayPal URL based on sandbox setting
if (PAYPAL_SANDBOX) {
    define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
} else {
    define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
}

// Set other PayPal URLs for success, cancel, and notification (for localhost)
define('PAYPAL_RETURN_URL', 'http://localhost/widgets/adnetwork/success.php');
define('PAYPAL_CANCEL_URL', 'http://localhost/widgets/adnetwork/cancel.php');
define('PAYPAL_NOTIFY_URL', 'http://localhost/widgets/adnetwork/ipn.php');

// Optionally, you could make these URLs dynamic depending on your actual domain
// Example:
// define('PAYPAL_RETURN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/success.php');
// define('PAYPAL_CANCEL_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/cancel.php');
// define('PAYPAL_NOTIFY_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/ipn.php');
?>
