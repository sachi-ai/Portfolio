<?php
require_once('../config.php');

class User extends DBConnection
{
    private $settings;

    public function __construct()
    {
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function update_user()
{
    $id = $_POST['id'] ?? null;
    $firstname = $_POST['firstname'] ?? '';
    $lastname  = $_POST['lastname'] ?? '';
    $username  = $_POST['username'] ?? '';
    $password  = $_POST['password'] ?? '';
    $short_desc = $_POST['short_desc'] ?? '';
    $avatar    = $_FILES['avatar'] ?? null;

    // Hash password if provided
    $passwordHash = null;
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    // Handle avatar upload
    if (!empty($_FILES['avatar']['name'])) {
        $user_avatar = upload_file('avatar');
        if ($user_avatar === false) {
            return json_encode([
                'status'  => 'error',
                'message' => 'Failed to upload avatar.'
            ]);
        } else {
            $avatar = $user_avatar;
        }
    } else {
        $avatar = '';
    }

    if ($id) {
        // --- Build SET clause dynamically ---
        $set = "`firstname` = ?, `lastname` = ?, `username` = ?, `short_desc` = ?";
        $params = [$firstname, $lastname, $username, $short_desc];
        $types  = "ssss";

        if (!empty($passwordHash)) {
            $set    .= ", `password` = ?";
            $params[] = $passwordHash;
            $types   .= "s";
        }

        if (!empty($avatar)) {
            $set    .= ", `avatar` = ?";
            $params[] = $avatar;
            $types   .= "s";
        }

        // Final query
        $update_qry = "UPDATE `user` SET $set WHERE id = ?";

        $params[] = $id;
        $types   .= "i";

        $stmt = $this->conn->prepare($update_qry);
        if (!$stmt) {
            return json_encode([
                'status'  => 'error',
                'message' => 'Database prepare failed',
                'error'   => $this->conn->error
            ]);
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $stmt->close();

            // Refresh user data in session
            $qry = $this->conn->prepare("SELECT * FROM `user` WHERE `id` = ?");
            $qry->bind_param("i", $id);
            $qry->execute();
            $result = $qry->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                foreach ($row as $k => $v) {
                    if (!is_numeric($k) && $k != 'password') {
                        $this->settings->set_userdata($k, $v);
                    }
                }
            }

            $this->settings->set_flashdata('success', 'User profile updated successfully.');
            return json_encode([
                'status'  => 'success',
                'message' => 'User profile updated successfully.'
            ]);
        } else {
            $error = $stmt->error;
            $stmt->close();
            return json_encode([
                'status'  => 'error',
                'message' => 'Failed to update user profile.',
                'error'   => $error
            ]);
        }
    }
}


    private function get_user_id_by_username($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM `user` WHERE `username` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        return $id;
    }

}



$user = new User();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'update':
        echo $user->update_user();
        break;
    default:
        break;
}

?>
