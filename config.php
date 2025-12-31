    <?php 
date_default_timezone_set('Asia/Manila'); // Set default timezone
session_start(); // Start session management


$db = new DBConnection(); // Create a new database connection instance
$conn = $db->conn; // Get the connection object

function sanitize_input($data) {
    return htmlspecialchars(trim($data)); // Sanitize input data
}

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

function redirect($url=''){
    if(!empty($url)){
        echo '<script> location.href="'.base_url. $url.'"</script>';
    }  
}

function configureErrorLogging($displayErrors = true, $customLogPath = null) {
     // Always report all errors
    error_reporting(E_ALL);

    // Set display behavior (for dev vs prod)
    ini_set('display_errors', $displayErrors ? '1' : '0');
    ini_set('log_errors', '1');

    //Determine log file path
    $logPath = $customLogPath ?: __DIR__ .DIRECTORY_SEPARATOR. 'logs\error.log';
    // echo "Log path set to: $logPath\n"; // Debugging output
    // Set log file location
    ini_set('error_log', $logPath);

    // Optional: flush output for visibility
    ob_implicit_flush(true);
}

?>
