<?php
require_once('../config.php');
require_once('../vendors/htmlpurifier/library/HTMLPurifier.auto.php');
class Content extends DBConnection
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

    public function about()
    {
        $file = $_POST['file'];
        $content = $_POST['content'];
        $content_file = "../{$file}.html";

        // Check if content is effectively empty (after stripping tags and whitespace)
        $plain_content = trim(strip_tags($content));
        $file_exists = is_file($content_file);
        $prev_content = $file_exists ? trim(file_get_contents($content_file)) : '';

        if ($plain_content === '') {
            // User cleared input
            if ($file_exists && $prev_content !== '') {
                // File had content before, now cleared
                $update = file_put_contents($content_file, '');
                $this->settings->set_flashdata('success', "Content successfully updated.");
                return json_encode(["status" => 'success']);
            } else {
                // File was already empty or never existed
                $update = file_put_contents($content_file, '');
                $this->settings->set_flashdata('info', "Nothing to update.");
                return json_encode(["status" => 'info', "msg" => "Nothing to update."]);
            }
        } else {
            // No changes made
            if ($file_exists && $prev_content === trim($content)) {
                $this->settings->set_flashdata('info', "No changes. Nothing to update.");
                return json_encode(["status" => 'info', "msg" => "No changes. Nothing to update."]);
            }
            // User entered content
            $update = file_put_contents($content_file, $content);
            if ($update !== false) {
                $this->settings->set_flashdata('success', "Content successfully updated.");
                return json_encode(["status" => 'success']);
            } else {
                $this->settings->set_flashdata('error', "An error occurred while updating the content.");
                return json_encode([
                    "status" => 'error',
                    "msg" => "Error writing to file."
                ]);
            }
        }
    }

    public function contact()
    {
        $this->conn->query("TRUNCATE `contacts`");
        $stmt = $this->conn->prepare("INSERT INTO `contacts` (`meta_field`, `meta_value`) VALUES (?, ?)");

        $fields = [
            'mobile' => $_POST['mobile'],
            'email' => $_POST['email'],
            'facebook' => $_POST['facebook'],
            'telegram' => $_POST['telegram'],
            'linkedin' => $_POST['linkedin'],
            'address' => $_POST['address'],
        ];

        $success = true;

        foreach ($fields as $key => $value) {
            $stmt->bind_param("ss", $key, $value);
            if (!$stmt->execute()) {
                $success = false;
                break;  // stop inserting on first failure
            }
        }
        $stmt->close();

        if ($success) {
            $resp['status'] = 'success';
            $resp['message'] = 'Contact details updated successfully.';
            $this->settings->set_flashdata('success', $resp['message']);
        } else {
            $resp['status'] = 'error';
            $resp['message'] = 'Failed to update contact details.';
            $this->settings->set_flashdata('error', $resp['message']);
        }

        return json_encode($resp);
    }

    public function get_education_fields()
    {
        return [
            'id' => $_POST['id'] ?? '',
            'school' => $_POST['school'] ?? '',
            'degree' => $_POST['degree'] ?? '',
            'year' => $_POST['year'] ?? '',
            'month' => $_POST['month'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
    }

    public function education()
    {
        $fields = $this->get_education_fields();
        // Sanitize description using HTMLPurifier
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        // ✅ Purify all fields in one loop
        foreach ($fields as $key => $value) {
            $fields[$key] = $purifier->purify($value);
        }
        // $descriptionClean = $purifier->purify($fields['description']);

        $id = $fields['id'];
        if (empty($id)) {
            $sql = "INSERT INTO `education` (`school`, `degree`, `year`, `month`, `description`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssss", $fields['school'], $fields['degree'], $fields['year'], $fields['month'], $fields['description']);
                $save = $stmt->execute();
                $action = 'added';
            }
        } else {
            $sql = "UPDATE `education` SET `school` = ?, `degree` = ?, `year` = ?, `month` = ?, `description` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssi", $fields['school'], $fields['degree'], $fields['year'], $fields['month'], $fields['description'], $id);
                $save = $stmt->execute();
                $action = 'updated';
            }
        }

        if (isset($save) && $save) {
            $response = [
                "status" => 'success',
                "msg" => "Education successfully {$action}."
            ];
            $this->settings->set_flashdata('success', $response['msg']);
        } else {
            $response = [
                "status" => 'error',
                "msg" => "An error occurred while saving the education." . ($this->conn->error ?? 'Unknown error')
            ];
        }
        return json_encode($response);
    }

    public function education_delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return json_encode([
                "status" => "error",
                "msg" => "ID is required for deletion."
            ]);
        }
        $stmt = $this->conn->prepare("DELETE FROM `education` WHERE `id` = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $delete = $stmt->execute();
            if ($delete) {
                $response = [
                    "status" => "success",
                    "msg" => "Education entry deleted successfully."
                ];
                $this->settings->set_flashdata('success', $response['msg']);
            } else {
                $response = [
                    "status" => "error",
                    "msg" => "Failed to delete education entry." . ($this->conn->error ?? 'Unknown error')
                ];
            }
            return json_encode($response);
        } else {
            return json_encode([
                "status" => "error",
                "msg" => "Failed to prepare the delete statement." . ($this->conn->error ?? 'Unknown error')
            ]);
        }


    }

    public function get_work_fields()
    {
        return [
            'id' => $_POST['id'] ?? '',
            'company' => $_POST['company'] ?? '',
            'position' => $_POST['position'] ?? '',
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
    }

    public function work()
    {
        $fields = $this->get_work_fields();
        // Sanitize description using HTMLPurifier
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        // ✅ Purify all fields in one loop
        foreach ($fields as $key => $value) {
            $fields[$key] = $purifier->purify($value);
        }
        // $descriptionClean = $purifier->purify($fields['description']);

        $id = $fields['id'];
        if (empty($id)) {
            $sql = "INSERT INTO `work` (`company`, `position`, `start_date`, `end_date`, `description`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssss", $fields['company'], $fields['position'], $fields['start_date'], $fields['end_date'], $fields['description']);
                $save = $stmt->execute();
                $action = 'added';
            }
        } else {
            $sql = "UPDATE `work` SET `company` = ?, `position` = ?, `start_date` = ?, `end_date` = ?, `description` = ? WHERE `id` = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssi", $fields['company'], $fields['position'], $fields['start_date'], $fields['end_date'], $fields['description'], $id);
                $save = $stmt->execute();
                $action = 'updated';
            }
        }

        if (isset($save) && $save) {
            $response = [
                "status" => 'success',
                "msg" => "Work successfully {$action}."
            ];
            $this->settings->set_flashdata('success', $response['msg']);
        } else {
            $response = [
                "status" => 'error',
                "msg" => "An error occurred while saving the work." . ($this->conn->error ?? 'Unknown error')
            ];
        }
        return json_encode($response);
    }

    public function work_delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return json_encode([
                "status" => "error",
                "msg" => "ID is required for deletion."
            ]);
        }
        $stmt = $this->conn->prepare("DELETE FROM `work` WHERE `id` = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $delete = $stmt->execute();
            if ($delete) {
                $response = [
                    "status" => "success",
                    "msg" => "Work entry deleted successfully."
                ];
                $this->settings->set_flashdata('success', $response['msg']);
            } else {
                $response = [
                    "status" => "error",
                    "msg" => "Failed to delete work entry." . ($this->conn->error ?? 'Unknown error')
                ];
            }
            return json_encode($response);
        } else {
            return json_encode([
                "status" => "error",
                "msg" => "Failed to prepare the delete statement." . ($this->conn->error ?? 'Unknown error')
            ]);
        }
    }

    public function get_project_fields()
    {
        return [
            'id' => $_POST['id'] ?? '',
            'project_name' => $_POST['project_name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'project_link' => $_POST['project_link'] ?? ''

        ];
    }

    public function project()
    {
        $fields = $this->get_project_fields();
        $project_banner = $_FILES['project_banner'] ?? null;
        // Sanitize fields except project_banner using HTMLPurifier
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        // ✅ Purify all fields in one loop
        foreach ($fields as $key => $value) {
            $fields[$key] = $purifier->purify($value);
        }
        // $descriptionClean = $purifier->purify($fields['description']);

        // Handle project_banner upload
        if (!empty($_FILES['project_banner']['name'])) {
            $project_banner_path = upload_file('project_banner');
            if ($project_banner_path === false) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to upload project banner.'
                ]);
            } else {
                $project_banner = $project_banner_path;
            }
        } else {
            $project_banner ='';
        }

        $id = $fields['id'];
        if (empty($id)) {
            $sql = "INSERT INTO `projects` (`project_banner`, `project_name`, `description`, `project_link`) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssss", $project_banner, $fields['project_name'], $fields['description'], $fields['project_link']);
                $save = $stmt->execute();
                $action = 'added';
            }
        } else {
            // --- Build SET clause dynamically ---
            $set = "`project_name` = ?, `description` = ?, `project_link` = ?";
            $params = [$fields['project_name'], $fields['description'], $fields['project_link']];
            $types  = "sss";

            if(!empty($project_banner)) {
                $set    .= ", `project_banner` = ?";
                $params[] = $project_banner;
                $types   .= "s";
            }

            // error_log("Project Banner: " . print_r($project_banner, true)); // Debugging output

            // Final query
            $update_qry = "UPDATE `projects` SET $set WHERE id = ?";
            $params[] = $id;
            $types   .= "i";
            $stmt = $this->conn->prepare($update_qry);
            if($stmt) {
                $stmt->bind_param($types, ...$params);
                $save = $stmt->execute();
                $action = 'updated';
            } else {
                return json_encode([
                    'status'  => 'error',
                    'message' => 'Database prepare failed',
                    'error'   => $this->conn->error
                ]);
            }

            
            // $sql = "UPDATE `projects` SET `project_banner` = ?, `project_name` = ?, `description` = ?, `project_link` = ? WHERE `id` = ?";
            // $stmt = $this->conn->prepare($sql);
            // if ($stmt) {
            //     $stmt->bind_param("ssssi", $project_banner, $fields['project_name'], $fields['description'], $fields['project_link'], $id);
            //     $save = $stmt->execute();
            //     $action = 'updated';
            // }
        }
        $stmt->close();

        if (isset($save) && $save) {
            $response = [
                "status" => 'success',
                "msg" => "Project successfully {$action}."
            ];
            $this->settings->set_flashdata('success', $response['msg']);
        } else {
            $response = [
                "status" => 'error',
                "msg" => "An error occurred while saving the project." . ($this->conn->error ?? 'Unknown error')
            ];
        }
        return json_encode($response);
    }

        public function project_delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return json_encode([
                "status" => "error",
                "msg" => "ID is required for deletion."
            ]);
        }
        $stmt = $this->conn->prepare("DELETE FROM `projects` WHERE `id` = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $delete = $stmt->execute();
            if ($delete) {
                $response = [
                    "status" => "success",
                    "msg" => "Project entry deleted successfully."
                ];
                $this->settings->set_flashdata('success', $response['msg']);
            } else {
                $response = [
                    "status" => "error",
                    "msg" => "Failed to delete project entry." . ($this->conn->error ?? 'Unknown error')
                ];
            }
            return json_encode($response);
        } else {
            return json_encode([
                "status" => "error",
                "msg" => "Failed to prepare the delete statement." . ($this->conn->error ?? 'Unknown error')
            ]);
        }
    }



}

$content = new Content();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'about':
        echo $content->about();
        break;
    case 'contact':
        echo $content->contact();
        break;
    case 'education':
        echo $content->education();
        break;
    case 'education_delete':
        echo $content->education_delete();
        break;
    case 'work':
        echo $content->work();
        break;
    case 'work_delete':
        echo $content->work_delete();
        break;
    case 'project':
        echo $content->project();
        break;
    case 'project_delete':
        echo $content->project_delete();
        break;
    default:
        echo json_encode(["status" => "error", "msg" => "No Function Found"]);
        break;
}
?>
