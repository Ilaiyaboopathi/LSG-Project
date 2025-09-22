<?php

session_start();

// =====================================JWT Validation Start=====================================================

require '../../vendor/autoload.php';
require '../../JWTValues.php';
require '../../data/dbconfig.php';
require 'google_drive_setup.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
// use DomainException;
// use InvalidArgumentException;
// use UnexpectedValueException;

use Google\Service\Drive\DriveFile;

$secretKey = 'a8d123461b643452fc0b9c8186f80de25b4ab7e8769010d57d309f867fcfcf99';

// Instantiate the JWTValues class
$jwtHandler = new JWTValues($secretKey);

// Decode the token and retrieve user data
$jwtHandler->decodeToken();

// Check if the user is logged in
if ($jwtHandler->isLoggedIn()) {
    // Get the user data
    $userData = $jwtHandler->getUserData();


    // Perform the SQL query using the user ID
    $JWT_userID = $jwtHandler->getUserID();
  	$JWT_adminName = $jwtHandler->getAdminName();
  	$JWT_userRole = $jwtHandler->getUserRole();
  	$JWT_userEmail = $jwtHandler->getUserEmail();
  	$JWT_userDesignation = $jwtHandler->getUserDesignation();
  
  
    $sql = "SELECT * FROM employee WHERE id LIKE '%" . $JWT_userID . "%' AND isenable = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If any inactive employees are found, redirect to login page
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
} else {
    // If the user is not logged in, redirect to login
    header('HTTP/1.1 403 Forbidden');
    exit();
}


// =====================================JWT Validation End=====================================================



if (!isset($JWT_adminName)) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Access denied']);
    exit;
}



// ================================  Validatation pass It will work Followign Code  ====================================================




// Check for required parameters
if (!isset($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Action is required']);
    exit;
}





if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the action from the form
    $action = $_POST['action'];

    // Perform actions based on the submitted action
    switch ($action) {
        case 'AddProject':
            // Handle save operation
            AddClientProject();
            break;

        case 'UpdateProject':
            // Handle update operation
            UpdateClientProject();
            break;

        case 'GetEditedProjectDetails':
            // Handle update operation
            GetEditedProjectDetails();
            break;

        case 'fetch_subtask_details':
            // Handle update operation
            fetch_subtask_details();
            break;
             case 'fetch_subtask_details_final':
            // Handle update operation
            fetch_subtask_details_final();
            break;

        case 'update_subtask_details':
            // Handle update operation
            update_subtask_details();
            break;

              case 'admin_final_files':
            // Handle update operation
            admin_final_files();
            break;

        case 'Status_subtask_change':
            // Handle update operation
            Status_subtask_change();
            break;


        case 'Reply_subtask_Add':
            // Handle update operation
            Reply_subtask_Add();
            break;


        case 'Fetch_all_replies':
            // Handle update operation
            Fetch_all_replies();
            break;        
        
        default:
            echo json_encode(["error" => "Invalid action"]);
            break;
    }
}


function uploadToDrive($filePath, $fileName, $folderId) {

    global $service; // Using the global $service defined in google_drive_setup.php

    // Check if file exists
    if (!file_exists($filePath)) {
        echo "File not found: $filePath";
        return null;
    }

    // Create a new DriveFile object
    $driveFile = new DriveFile();
    $driveFile->setName($fileName);
    $driveFile->setParents([$folderId]); // Set the parent folder ID

    // Create a file media object
    $fileData = file_get_contents($filePath);

    // Upload the file to Google Drive
    try {
        $file = $service->files->create(
            $driveFile,
            [
                'data' => $fileData,
                'mimeType' => mime_content_type($filePath), // Use the MIME type of the file
                'uploadType' => 'multipart',
            ]
        );
        return $file->getId(); // Return the uploaded file's ID
    } catch (Exception $e) {
        echo 'Error during file upload: ' . $e->getMessage();
        return null;
    }
}



function AddClientProject() {
    global $conn;
    global $JWT_userID;
    global $folderId; // Add this to your existing setup if not already defined

    $conn->begin_transaction(); // Start transaction

    try {
        // Get project details from form data
        //$j_id = $_POST['j_id'];
        $name = $_POST['name'];
        $platform = $_POST['platform'];
        $linkurl = $_POST['linkurl'];
        $project_description = urldecode($_POST['project_description']);
        $priority = $_POST['priority'];
        $date = !empty($_POST['date']) ? DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d') : NULL;
        $file_page_count = $_POST['file_page_count'];
        $tagifyUserList = $_POST['TagifyUserList']; 
        $upload_files = $_POST['upload_files']; 
        $status = $_POST['ChangeStatus_Project'];
        $client_id = $_POST['client_id']; 
        $client_priority = $_POST['client_priority'];
        $created_by = $JWT_userID; // Change this based on session user ID

        // Insert project details into `client_projects`
        $stmt = $conn->prepare("INSERT INTO client_projects (name, type, link, final_date, priority, status, description, page_count, created_by,uploaded_file, client_id,client_priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssissss", $name, $platform, $linkurl, $date, $priority, $status, $project_description, $file_page_count, $created_by, $upload_files, $client_id,$client_priority);
        $stmt->execute();
        $project_id = $stmt->insert_id;
        $stmt->close();

        // Handle project files
        if (!empty($_FILES['files'])) {
            foreach ($_FILES['files']['name'] as $key => $fileName) {
                $fileTmp = $_FILES['files']['tmp_name'][$key];
                
                // Upload to Google Drive instead of local storage
                $filePath = "uploads/Projects/" . time() . "_" . basename($fileName);
                $driveFileId = uploadToDrive($fileTmp, $fileName, $folderId);

                if ($driveFileId) {
                    // File uploaded successfully to Google Drive, now save the file metadata in the database
                    $stmt = $conn->prepare("INSERT INTO client_project_files (project_id, file_name, file_path) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $project_id, $fileName, $driveFileId);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        // Handle project assignees and their files
        if (isset($_POST['repeater'])) {
            foreach ($_POST['repeater'] as $index => $repeaterItem) {
                $empId = $repeaterItem['empId'];
                $deadlineDate = !empty($repeaterItem['deadlineDate']) ? 
    (($date = DateTime::createFromFormat('d-m-Y', $repeaterItem['deadlineDate'])) !== false ? $date->format('Y-m-d') : NULL) 
    : NULL;

                $deadlineTime = $repeaterItem['deadlineTime'];
                $projectInfo = urldecode($repeaterItem['projectInfo']);

                // Insert into `client_project_assignees`
                $stmt = $conn->prepare("INSERT INTO client_project_assignees (project_id, user_id, user_description, status, deadline_date, deadline_time) VALUES (?, ?, ?, ?, ?, ?)");
                $status = "Pending"; // Define status as a variable
                $stmt->bind_param("isssss", $project_id, $empId, $projectInfo, $status, $deadlineDate, $deadlineTime);
                $stmt->execute();
                $assignee_id = $stmt->insert_id;
                $stmt->close();

                // Handle assignee files
                if (!empty($_FILES['repeater']['name'][$index]['files'])) {
                    foreach ($_FILES['repeater']['name'][$index]['files'] as $key => $fileName) {
                        $fileTmp = $_FILES['repeater']['tmp_name'][$index]['files'][$key];

                        // Upload to Google Drive instead of local storage
                        $filePath = "uploads/Projects/" . time() . "_" . basename($fileName);
                        $driveFileId = uploadToDrive($fileTmp, $fileName, $folderId);

                        if ($driveFileId) {
                            // File uploaded successfully to Google Drive, now save the file metadata in the database
                            $stmt = $conn->prepare("INSERT INTO client_assignee_files (project_id, user_id, file_name, file_path) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("isss", $project_id, $empId, $fileName, $driveFileId);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }
            }
        }

        $conn->commit(); // Commit transaction
        echo json_encode(["status" => "success", "message" => "Project added successfully!"]);
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        echo json_encode(["status" => "error", "message" => "An error occurred: " . $e->getMessage()]);
    }
}


function UpdateClientProject() {
    global $conn;
    global $folderId; // Add this if it's not defined in your setup

    $conn->begin_transaction(); // Start transaction

    try {
        // Get project details from form data
        $project_id = $_POST['project_id'];
        $name = $_POST['name'];
        $platform = $_POST['platform'];
        $linkurl = $_POST['linkurl'];
        $project_description = urldecode($_POST['project_description']);
        $priority = $_POST['priority'];
        $date = !empty($_POST['date']) ? DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d') : NULL;
        $file_page_count = $_POST['file_page_count'];
        $status = $_POST['ChangeStatus_Project'];

        // Update existing project
        $stmt = $conn->prepare("UPDATE client_projects SET name=?, type=?, link=?, final_date=?, priority=?, status=?, description=?, page_count=? WHERE id=?");
        $stmt->bind_param("sssssssii", $name, $platform, $linkurl, $date, $priority, $status, $project_description, $file_page_count, $project_id);
        $stmt->execute();
        $stmt->close();

        // Handle project files - Upload files to Google Drive instead of saving locally
        if (!empty($_FILES['files'])) {
            foreach ($_FILES['files']['name'] as $key => $fileName) {
                $fileTmp = $_FILES['files']['tmp_name'][$key];
                
                // Upload file to Google Drive and get the file ID
                $driveFileId = uploadToDrive($fileTmp, $fileName, $folderId);

                if ($driveFileId) {
                    // File uploaded successfully to Google Drive, now save the file metadata in the database
                    $stmt = $conn->prepare("INSERT INTO client_project_files (project_id, file_name, file_path) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $project_id, $fileName, $driveFileId); // Store the Google Drive file ID instead of the local file path
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        // Handle project assignees and their files - Upload assignee files to Google Drive
        if (isset($_POST['repeater'])) {
            $conn->query("DELETE FROM client_project_assignees WHERE project_id = $project_id");
            
            foreach ($_POST['repeater'] as $index => $repeaterItem) {
                $empId = $repeaterItem['empId'];
                $deadlineDate = !empty($repeaterItem['deadlineDate']) ? 
                (($date = DateTime::createFromFormat('d-m-Y', $repeaterItem['deadlineDate'])) !== false ? $date->format('Y-m-d') : NULL) 
                : NULL;
                $deadlineTime = $repeaterItem['deadlineTime'];
                $projectInfo = urldecode($repeaterItem['projectInfo']);
                $status = "Pending";

                // Insert into `client_project_assignees`
                $stmt = $conn->prepare("INSERT INTO client_project_assignees (project_id, user_id, user_description, status, deadline_date, deadline_time) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $project_id, $empId, $projectInfo, $status,  $deadlineDate, $deadlineTime);
                $stmt->execute();
                $assignee_id = $stmt->insert_id;
                $stmt->close();

                // Handle assignee files - Upload files to Google Drive instead of saving locally
                if (!empty($_FILES['repeater']['name'][$index]['files'])) {
                    foreach ($_FILES['repeater']['name'][$index]['files'] as $key => $fileName) {
                        $fileTmp = $_FILES['repeater']['tmp_name'][$index]['files'][$key];

                        // Upload file to Google Drive and get the file ID
                        $driveFileId = uploadToDrive($fileTmp, $fileName, $folderId);

                        if ($driveFileId) {
                            // File uploaded successfully to Google Drive, now save the file metadata in the database
                            $stmt = $conn->prepare("INSERT INTO client_assignee_files (project_id, user_id, file_name, file_path) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("isss", $project_id, $empId, $fileName, $driveFileId); // Store the Google Drive file ID instead of the local file path
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }
            }
        }

        $conn->commit(); // Commit transaction
        echo json_encode(["status" => "success", "message" => "Project updated successfully!", "project_id" => $project_id]);
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        echo json_encode(["status" => "error", "message" => "An error occurred: " . $e->getMessage()]);
    }
}


function GetEditedProjectDetails()
{
    global $conn;

    $project_id = $_POST['project_id'];

    $stmt = $conn->prepare("
        SELECT 
            cp.id AS project_id, 
            cp.name AS project_name, 
            cp.type AS project_type,
             cp.client_id AS client_id, 
            cp.client_priority AS client_priority,
             cp.uploaded_file AS uploaded_file,
            cp.link AS project_link, 
            cp.final_date AS project_deadline, 
            cp.status AS project_status, 
            cp.priority AS project_priority,
            cp.description AS project_description, 
            cp.page_count AS pageCount, 
            cpa.id AS assignment_id, 
            cpa.user_id AS assigned_user, 
            cpa.user_description, 
            cpa.status AS assignment_status, 
            cpa.deadline_date, 
            cpa.deadline_time, 
            u.name AS assigned_username,

             
            cpa.assigned_at AS create_datetime
        FROM client_projects cp
        LEFT JOIN client_project_assignees cpa ON cp.id = cpa.project_id
        LEFT JOIN employee u ON cpa.user_id = u.id
        WHERE cp.id = ?
    ");

    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $projectData = null;
    $assignments = [];

    while ($row = $result->fetch_assoc()) {
        if (!$projectData) {

            $deadlineDate = new DateTime($row["project_deadline"]);
            $formattedDate = $deadlineDate->format('d-m-Y');
            
            $projectData = [
                // "j_id"=>$row["id"],
                "id" => $row["project_id"],
                "name" => $row["project_name"],
                "type" => $row["project_type"],
                "client_id" => $row["client_id"],
                "client_priority" => $row["client_priority"],
                "uploaded_file" => $row["uploaded_file"],
                "link" => $row["project_link"],
                "description" => $row["project_description"],
                "pageCount" => $row["pageCount"],
                "project_status" => $row["project_status"],
                "assignedTo" => $row["assigned_username"], // Example AssignedTo (Replace with DB Value)
                "assignedBy" => "Admin", // Example AssignedBy (Replace with DB Value)
                "deadlineDate" => $formattedDate,
                "deadlineTime" => "00:00:00", // Set Default Time
                "Projectpriority" => $row["project_priority"],
                "CompletedDate" => null,
                "assignments" => []
            ];
        }
        

        if ($row["assignment_id"]) {
            $assignments[] = [
                "id" => $row["assigned_user"],
                "name" => $row["assigned_username"],
                "deadlineDate" => $row["deadline_date"],
                "deadlineTime" => $row["deadline_time"],
                "SubTaskStatus" => $row["assignment_status"],
                "CreateDateTime" => $row["create_datetime"],
                "SubtaskNote" => null,
                "info" => htmlspecialchars($row["user_description"], ENT_QUOTES, 'UTF-8'),
                "completed_link" => null
            ];
        }
    }

    if ($projectData) {
        $projectData["assignments"] = $assignments;
    }

    echo json_encode($projectData, JSON_PRETTY_PRINT);
}

function fetch_subtask_details()
{

    global $conn;
    
    if (isset($_POST['subtask_user_id'])) {
        $subtaskUserId = $_POST['subtask_user_id'];
        $projectId = $_POST['project_id'];
    
        // Fetch Subtask User Details from client_project_assignees
        $query = "SELECT cpa.id, cp.name, cpa.user_id, cpa.user_description, e.name AS employee_name
                  FROM client_project_assignees cpa
                  JOIN client_projects cp ON cpa.project_id = cp.id
                  JOIN employee e ON cpa.user_id = e.id  -- Joining Employee Table
                  WHERE cpa.user_id = ? AND cpa.project_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $subtaskUserId, $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        $subtaskData = $result->fetch_assoc();
    
        if ($subtaskData) {
            // Fetch Files related to this subtask user from client_assignee_files
            $filesQuery = "SELECT caf.file_name, caf.file_path 
                           FROM client_assignee_files caf
                           WHERE caf.user_id = ?";
            $stmt = $conn->prepare($filesQuery);
            $stmt->bind_param("s", $subtaskUserId); // Fetch files using user_id
            $stmt->execute();
            $filesResult = $stmt->get_result();
            $files = [];
            while ($row = $filesResult->fetch_assoc()) {
                $files[] = $row;
            }
    
            echo json_encode([
                "success" => true,
                "assignee_id" => $subtaskData['id'],
                "project_name" => $subtaskData['name'],
                "task_description" => $subtaskData['user_description'],
                "assignee_name" => $subtaskData['employee_name'], // Employee Name
                "files" => $files
            ]);
        } else {
            echo json_encode(["success" => false]);
        }
    }
    
}
function fetch_subtask_details_final()
{
    global $conn;

    if (isset($_POST['subtask_user_id_final']) && isset($_POST['project_id_final'])) {
        $subtaskUserId = $_POST['subtask_user_id_final'];
        $projectId = $_POST['project_id_final'];

        // Fetch only files using JOIN
        $filesQuery = "SELECT f.file_name, f.file_path, f.uploaded_at
                       FROM admin_submit_project_files f
                       JOIN admin_submit_project_client c ON f.cuid = c.cuid
                       WHERE c.user_id = ? AND c.project_id = ?";
        $fileStmt = $conn->prepare($filesQuery);
        $fileStmt->bind_param("si", $subtaskUserId, $projectId);
        $fileStmt->execute();
        $fileResult = $fileStmt->get_result();

        $files = [];
        while ($row = $fileResult->fetch_assoc()) {
            $files[] = $row;
        }

        echo json_encode([
            "success" => true,
            "files" => $files
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Missing required parameters."]);
    }
}





function update_subtask_details()
{
    global $conn;
    global $folderId; 

    if (isset($_POST['subtask_user_id']) && isset($_POST['project_description'])) {
        $subtaskUserId = $_POST['subtask_user_id'];
        $projectDescription = urldecode($_POST['project_description']);
        $projectId = $_POST['projectId'];

        // Update Project Description
        $updateProjectQuery = "UPDATE client_project_assignees SET user_description = ? WHERE project_id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateProjectQuery);
        $stmt->bind_param("sis", $projectDescription, $projectId, $subtaskUserId);
        $stmt->execute();

        // Handle File Upload to Google Drive
        if (isset($_FILES['NewFile_Single'])) {
            $fileTmp = $_FILES['NewFile_Single']['tmp_name'];
            $fileName = $_FILES['NewFile_Single']['name'];

            // Assuming $folderId is predefined or retrieved dynamically

            // Upload file to Google Drive
            $driveFileId = uploadToDrive($fileTmp, $fileName, $folderId);

            if ($driveFileId) {
                // Store Google Drive file ID in database
                $insertQuery = "INSERT INTO client_assignee_files (project_id, user_id, file_name, file_path) 
                                VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("isss", $projectId, $subtaskUserId, $fileName, $driveFileId);
                $stmt->execute();
            }
        }

        echo json_encode(["success" => true, "message" => "Project description updated, file uploaded to Drive"]);
    }
}


function admin_final_files()
{
    global $conn;
    global $folderId; 

    if (isset($_POST['subtask_user_id_final']) && isset($_POST['project_description'])) {
        $subtaskUserId = $_POST['subtask_user_id_final'];
        $projectDescription = urldecode($_POST['project_description']);
        $projectId = $_POST['projectId'];
             $client_id = $_POST['client_id'];
       
                $job_name = $_POST['job_name'];
            

        // Update Project Description
       // $updateProjectQuery = "UPDATE admin_submit_project_client SET user_description = ? WHERE project_id = ? AND user_id = ?";
       $insertOrUpdateQuery = "
                INSERT INTO admin_submit_project_client (project_id, user_id, user_description,cuid,job_name)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE user_description = VALUES(user_description)
                ";
        // $stmt = $conn->prepare($insertOrUpdateQuery);
        // $stmt->bind_param("sis", $projectDescription, $projectId, $subtaskUserId);
        // $stmt->execute();
        $stmt = $conn->prepare($insertOrUpdateQuery);
            $stmt->bind_param("issss", $projectId, $subtaskUserId, $projectDescription, $client_id, $job_name);
            $stmt->execute();

        // Handle File Upload to Google Drive
        if (isset($_FILES['NewFile_Single1'])) {
            $fileTmp = $_FILES['NewFile_Single1']['tmp_name'];
            $fileName = $_FILES['NewFile_Single1']['name'];

            // Assuming $folderId is predefined or retrieved dynamically

            // Upload file to Google Drive
            $driveFileId = uploadToDrive($fileTmp, $fileName, $folderId);

            if ($driveFileId) {
                // Store Google Drive file ID in database
                $insertQuery = "INSERT INTO admin_submit_project_files (project_id, user_id, file_name, file_path,cuid) 
                                VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("issss", $projectId, $subtaskUserId, $fileName, $driveFileId,$client_id);
                $stmt->execute();
            }
        }

        echo json_encode(["success" => true, "message" => "Project description updated, file uploaded to Drive"]);
    }
}

function Status_subtask_change()
{
    global $conn;

    if (!isset($_POST['subtask_user_id'], $_POST['projectId'], $_POST['Subtask_changestatus'])) {
        echo json_encode(["success" => false, "message" => "Invalid request parameters"]);
        return;
    }

    $subtaskUserId = $_POST['subtask_user_id'];
    $projectId = $_POST['projectId'];
    $status = $_POST['Subtask_changestatus'];

    // Update status in client_project_assignees table
    $updateQuery = "UPDATE client_project_assignees SET status = ? WHERE project_id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("sis", $status, $projectId, $subtaskUserId);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true, "message" => "Status updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
}


function Reply_subtask_Add()
{
    global $conn;

    if (!isset($_POST['subtask_user_id'], $_POST['projectId'], $_POST['Address_charCount'])) {
        echo json_encode(["success" => false, "message" => "Invalid request parameters"]);
        return;
    }

    $subtaskUserId = $_POST['subtask_user_id'];
    $projectId = $_POST['projectId'];
    $Comment = $_POST['Address_charCount'];


     // Insert comment into client_project_comments table
     $insertQuery = "INSERT INTO client_project_comments (project_id, user_id, comment) VALUES (?, ?, ?)";
     $stmt = $conn->prepare($insertQuery);
     $stmt->bind_param("iss", $projectId, $subtaskUserId, $Comment);
 
     if ($stmt->execute()) {
         echo json_encode(["success" => true, "message" => "Comment added successfully"]);
     } else {
         echo json_encode(["success" => false, "message" => "Failed to add comment"]);
     }

}


function Fetch_all_replies()
{
    global $conn;

    if (!isset($_POST['project_id'], $_POST['subtask_user_id'])) {
        echo json_encode(["success" => false, "message" => "Invalid parameters"]);
        exit;
    }
    
    $projectId = $_POST['project_id'];
    $subtaskUserId = $_POST['subtask_user_id'];
    
    $sql = "SELECT c.comment, c.created_at, e.name AS user_name 
            FROM client_project_comments c
            JOIN employee e ON c.user_id = e.id
            WHERE c.project_id = ? AND c.user_id = ?
            ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $projectId, $subtaskUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $replies = [];
    while ($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }
    
    echo json_encode(["success" => true, "replies" => $replies]);

}


?>