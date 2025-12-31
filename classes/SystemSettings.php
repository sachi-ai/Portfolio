<?php
require_once 'DBConnection.php';
require_once base_app . 'config.php'; // Include configuration file for constants
// configureErrorLogging(); // Ensure error logging is configured
class SystemSettings extends DBConnection
{
    public function __construct()
    {
        parent::__construct(); // Call the parent constructor to initialize the DB connection
    }

    // Handles image upload and returns the file path or name
    function upload_image($file)
    {
        if ($file && $file['error'] == UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $filename = uniqid() . '_' . basename($file['name']);
            $target_file = $upload_dir . $filename;
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                return 'uploads/' . $filename;
            }
        }
        return false;
    }

    function load_system_info()
    {
        // if(isset($_SESSION['system_info'])){
        //     unset($_SESSION['system_info']); // Clear all existing session data 
        // }   
        if (!isset($_SESSION['system_info'])) {
            $sql = "SELECT * FROM `system_info`";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                // Store system info in session
                $_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
            }
        }
    }
    function update_settings_info()
    {
        // $this->conn->query("TRUNCATE `system_info`"); // Clear existing settings

        $system_name = isset($_POST['system_name']) ? $_POST['system_name'] : '';
        $short_name = isset($_POST['short_name']) ? $_POST['short_name'] : '';
        $welcome_content = isset($_POST['welcome_content']) ? $_POST['welcome_content'] : '';
        // Default to existing logo from session
        $system_logo = $_SESSION['system_info']['system_logo'] ?? '';

        // error_log('System logo path: ' . $system_logo);

        // Handle file upload
        if (!empty($_FILES['system_logo']['name'])) {
            $system_logo_path = upload_file('system_logo');
            if ($system_logo_path == false) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to upload logo.'
                ]);
            } else {
                $system_logo = $system_logo_path; // Use the uploaded file path
            }
        }

        // $system_logo_path = upload_file('system_logo');
        // if ($system_logo_path == false && isset($_FILES['system_logo']) && $_FILES['system_logo']['error'] !== UPLOAD_ERR_NO_FILE) {
        //     return json_encode([
        //         'status' => 'error',
        //         'message' => 'Failed to upload logo.'
        //     ]);
        // } else {
        //     $system_logo = $system_logo_path; // Use the uploaded file path
        // }

        // if (isset($_FILES['system_logo']) && $_FILES['system_logo']['error'] === UPLOAD_ERR_OK) {
        //     $upload_dir = 'uploads/';
        //     $upload_abs_dir = dirname(__DIR__, 1) . '/' . $upload_dir; // Absolute path to the upload directory
        //     if (!is_dir($upload_dir)) {
        //         mkdir($upload_dir, 0755, true);
        //     }

        //     $filename = basename($_FILES['system_logo']['name']);
        //     $target_path = $upload_dir . $filename;
        //     error_log("Target path: $target_path"); // Debugging output

        //     if (move_uploaded_file($_FILES['system_logo']['tmp_name'], $target_path)) {
        //         $system_logo = $target_path; // Save path to DB
        //     } else {
        //         return json_encode([
        //             'status' => 'error',
        //             'message' => 'Failed to upload logo.'
        //         ]);
        //     }
        // }

        $data = [
            'system_name' => $system_name,
            'short_name' => $short_name,
            'welcome_content' => $welcome_content,
            'system_logo' => $system_logo
        ];

        $errors = [];

        foreach ($data as $field => $value) {
            $stmt = $this->conn->prepare("INSERT INTO `system_info` (`meta_field`, `meta_value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `meta_value` = VALUES(`meta_value`)");
            if (!$stmt) {
                $errors[] = "Prepare failed for field '$field': " . $this->conn->error;
                continue;
            }

            $stmt->bind_param("ss", $field, $value);

            if ($stmt->execute()) {
                unset($_SESSION['system_info']); // Clear existing session data for this field
            } else {
                $errors[] = "Execute failed for field '$field': " . $stmt->error;
            }

            $stmt->close();
        }

        if (empty($errors)) {
            $this->set_flashdata('success', 'Settings updated successfully.');
            // $this->load_system_info(); // Reload system info after update
            return json_encode(['status' => 'success', 'message' => 'Settings updated successfully.']);
        } else {
            $this->set_flashdata('error', 'Some settings failed to update.');
            // $this->load_system_info(); // Reload system info after update
            return json_encode([
                'status' => 'error',
                'message' => 'Some settings failed to update.',
                'errors' => $errors
            ]);
        }
    }

    function set_flashdata($flash = '', $value = '')
    {
        if (!empty($flash) && !empty($value)) {
            $_SESSION['flashdata'][$flash] = $value; // Store flash data in session
            // return true; // Return true if flash data is set successfully
        }
    }

    function chk_flashdata($flash = '')
    {
        if (isset($_SESSION['flashdata'][$flash])) {
            return true; // Check if flash data exists
        } else {
            return false; // Return false if flash data does not exist
        }
    }

    function flashdata($flash = '')
    {
        if (!empty($flash)) {
            $_tmp = $_SESSION['flashdata'][$flash]; // Retrieve flash data
            unset($_SESSION['flashdata']); // Clear flash data after retrieving it
            return $_tmp; // Return the flash data
        } else {
            return false; // Return false if no flash data is requested
        }
    }

    function set_userdata($field = '', $value = '')
    {
        if (is_array($field)) {
            // Clear old userdata completely
            unset($_SESSION['userdata']);

            // Replace with new userdata array
            $_SESSION['userdata'] = $field;
        } elseif (!empty($field) && !empty($value)) {
            // Single field update
            $_SESSION['userdata'][$field] = $value;
        }
    }

    function userdata($field = '')
    {
        if (!empty($field)) {
            if (isset($_SESSION['userdata'][$field])) {
                return $_SESSION['userdata'][$field];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function set_info($field = '', $value = '')
    {
        if (!empty($field) && !empty($value)) {
            $_SESSION['system_info'][$field] = $value; // Store system info in session
        }
    }

    function info($field = '')
    {
        if (!empty($field)) {
            if (isset($_SESSION['system_info'][$field]))
                return $_SESSION['system_info'][$field];
            else
                return false;
        } else {
            return false;
        }
    }

    function sess_des()
    {
        // Unset all session variables
        // $_SESSION = [];
        // // Delete session cookie
        // if (ini_get("session.use_cookies")) {
        //     $params = session_get_cookie_params();
        //     setcookie(
        //         session_name(),
        //         '',
        //         time() - 42000,
        //         $params["path"],
        //         $params["domain"],
        //         $params["secure"],
        //         $params["httponly"]
        //     );
        // }

        // // Destroy the session
        // session_destroy();

        // return true;
        if (isset($_SESSION['userdata'])) {
            unset($_SESSION['userdata']); // Clear user data session
        }
        if (isset($_SESSION['system_info'])) {
            unset($_SESSION['system_info']); // Clear system info session
        }
        // 1. Clear all session variables
        $_SESSION = array();

        // 2. Remove session cookie (if exists)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        // 3. Destroy session on server
        session_destroy();
        return true; // Return true if no user data session exists
    }

}

$_settings = new SystemSettings();
// Load system info if user is logged in
if (isset($_SESSION['userdata'])) {
    $_settings->load_system_info();
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'update_settings':
        echo $_settings->update_settings_info();
        break;
    default:
        break;
}

?>
