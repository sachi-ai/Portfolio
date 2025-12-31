    <?php 
date_default_timezone_set('Asia/Manila'); // Set default timezone
session_start(); // Start session management

require_once 'init.php'; // Include initialization file
require_once 'classes/DBConnection.php'; // Include database connection class
require_once 'classes/SystemSettings.php'; // Include system settings class

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

function validate_user_input($firstname,$lastname,$username,$password) {
    $alerts = [
        'AlertFirstname' => 0,
        'AlertLastname' => 0,
        'AlertUsername' => 0,
        'AlertPassword' => 0,
    ];

    if(empty($firstname)) {
        $alerts['AlertFirstname'] = 1;
    } 
    if(empty($lastname)) {
        $alerts['AlertLastname'] = 1;
    }
    if(empty($username)) {
        $alerts['AlertUsername'] = 1;
    } elseif(strlen($username) < 3 || strlen(($username)) > 20) {
        $alerts['AlertUsername'] = 2; // Username must be between 3 and 20 characters
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $alerts['AlertUsername'] = 3; // Username can only contain letters, numbers, and underscores
    } elseif(preg_match('/\s/', $username)) {
        $alerts['AlertUsername'] = 4; // Username cannot contain spaces
    }
    if(empty($password)) {
        $alerts['AlertPassword'] = 1;
    } elseif(strlen($password) < 6 || strlen(($password)) > 20) {
        $alerts['AlertPassword'] = 2; // Password must be between 6 and 20 characters
    }

    // echo var_dump($alerts);
    return $alerts;

   //for usage please refer to the register.php file
}

function validate_image($file) {
    if (!empty($file)){
        $full_path = base_app . $file;
        // Check if the file exists
        if (is_file($full_path)) {
            return base_url . $file; // Return the full URL to the image
        } else {
            return base_url . 'dist/img/no-image-available.png'; // Return a default image if not found
        }
    } else {
        return base_url . 'dist/img/no-image-available.png'; // Return a default image if no file is provided
    }
}

function upload_file($field_name, $upload_dir = 'uploads/') {
    $upload_dir_file = base_app . $upload_dir; // Absolute path to the upload directory
     //create the upload directory if it doesn't exist
     if(!is_dir($upload_dir_file)){
        mkdir(($upload_dir_file), 0755, true);
     }
     //check if file was uploaded without errors
     if(!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== UPLOAD_ERR_OK) {
        return false; // Return false if no file was uploaded or if there was an error
        // return json_encode(['status' => 'error', 'message' => 'No file uploaded.']); // Return empty string if no file was uploaded or if there was an error
     }
     // create a unique filename to prevent overwriting
     $filename = uniqid() . '_' . basename($_FILES[$field_name]['name']);
     $target_path = $upload_dir_file . $filename; // Full path to the target file
     error_log("Target path: $target_path"); // Debugging output
     $relative_path = $upload_dir . $filename; // Path to store in the database
     if(move_uploaded_file($_FILES[$field_name]['tmp_name'], $target_path)) {
        return $relative_path; // Return the relative path to the uploaded file
     } else {
        return false; // Return false if the file upload failed
        // return json_encode(['status' => 'error', 'message' => 'Failed to upload file.']); // Return error if file upload failed
     }
}

function debug_post(){
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<strong>--POST Data--</strong>\n";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    }
}

function debug_uploaded_file($file) {
    if(isset($_FILES[$file]) && $_FILES[$file]['error'] == 0) {
        echo "<strong>Uploaded file name: </strong>" . htmlspecialchars($_FILES[$file]['name']) . "<br>";
        echo "<strong>File type: </strong>" . htmlspecialchars($_FILES[$file]['type']) . "<br>";
        echo "<strong>Temp file path: </strong>" . htmlspecialchars($_FILES[$file]['tmp_name']) . "<br>";
        echo "<strong>File size (bytes): </strong>" . $_FILES[$file]['size'] . "<br>";  
    }
}

?>
