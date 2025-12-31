<?php require_once '../config.php';
class Login extends DBConnection
{
    private $settings;

    public function __construct()
    {
        global $_settings;
        $this->settings = $_settings;
        parent::__construct(); // Call the parent constructor to initialize the DB connection
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($password)) {
            echo json_encode(['status' => 'error', 'msg' => 'Missing credentials']);
            exit();
        }
        $stmt = $this->conn->prepare("SELECT * FROM `user` WHERE `username` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // update last_login timestamp
                $update = $this->conn->prepare("UPDATE `user` SET `last_login` = NOW() WHERE `id` = ?");
                $update->bind_param("i", $row['id']);
                $update->execute();
                $update->close();

                // fetch updated user data (including new last_login)
                $stmt2 = $this->conn->prepare("SELECT * FROM `user` WHERE `id` = ?");
                $stmt2->bind_param("i", $row['id']);
                $stmt2->execute();
                $row = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();

                foreach ($row as $k => $v) {
                    if (!is_numeric($k) && $k != 'password') {
                        $this->settings->set_userdata($k, $v);
                    }
                }
                echo json_encode(['status' => 'success']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Invalid username or password']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'User not found']);
            exit();
        }
    }

    public function reset_acc()
    {
        $username = $_POST['forgot_username'] ?? '';
        $password = $_POST['new_password'] ?? '';
        $confirm_pass = $_POST['confirm_new_password'] ?? '';

        if (empty($username) || empty($password) || empty($confirm_pass)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'All fields are required.'
            ]);
            return;
        }

        $stmt = $this->conn->prepare("SELECT * FROM `user` WHERE `username` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            //update password
            if ($password == $confirm_pass) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_qry = $this->conn->prepare("UPDATE `user` SET `password` = ? WHERE `username` = ?");
                $update_qry->bind_param("ss", $password_hash, $username);
                $update_qry->execute();
                echo json_encode(['status' => 'success']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Password does not match.']);
                exit();
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username']);
            exit();
        }
    }

    public function logout()
    {
        $this->settings->sess_des(); // Destroy session
        echo json_encode(['status' => 'success']);
        exit;

        // if($this->settings->sess_des()) {
        //     redirect('admin/login.php');
        // }
    }
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
    case 'login':
        $auth->login();
        break;
    case 'forgot_password':
        $auth->reset_acc();
        break;
    case 'logout':
        $auth->logout();
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     // Check CSRF

        //     if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        //         echo json_encode(['status' => 'error', 'msg' => 'Invalid CSRF token']);
        //         exit;
        //     }

        //     $auth->logout();
        // } else {
        //     echo json_encode(['status' => 'error', 'msg' => 'Invalid request method']);
        // }
        break;
    default:
        break;
}
?>
