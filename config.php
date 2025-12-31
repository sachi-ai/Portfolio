<?php 
date_default_timezone_set('Asia/Manila'); // Set default timezone
session_start(); // Start session management


$db = new DBConnection(); // Create a new database connection instance
$conn = $db->conn; // Get the connection object

// Check if the request is from a mobile device
function isMobileDevice() {
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );
    // Check if the user agent matches any of the mobile device patterns
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    return false; // Return false if no mobile device is detected
}

?>
