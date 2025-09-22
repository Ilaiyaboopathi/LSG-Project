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


$action = $_POST['action'];



switch ($action) 
{

    case 'AddClientProject':
        
      
                     
    //     $Client_id = "";
    

    //     if ( $JWT_userID) {
    //         // Fetch user details
    //         $stmt = $conn->prepare("SELECT cuid FROM employee WHERE id = ?");
    //         $stmt->bind_param("s",  $JWT_userID);
    //         $stmt->execute();
    //         $result = $stmt->get_result();
            
    //         if ($row = $result->fetch_assoc()) {
    //             $Client_id = $row['cuid'];
               
    //         }
            
    //         $stmt->close();
    //     }

    
       


    //     try {
    //         $name = $_POST['name'] ?? null;
    //         $platform = $_POST['platform'] ?? null;
    //         $referenceLink = $_POST['linkurl'] ?? null;
    //         $finalDate = !empty($_POST['date']) ? DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d') : NULL;
    //         $priority = $_POST['priority'] ?? 'Low Priority';
    //         $project_description = urldecode($_POST['project_description']) ?? null;
    //         $file_page_count =  $_POST['file_page_count'] ?? null;
    //         $job_no = $_POST['jobNo'] ?? null;
    //         $service = $_POST['service'] ?? null;
            
    //         $addedBy = $JWT_userID;
        
    //         if (!$name || !$service ) {
    //             http_response_code(400);
    //             echo json_encode(["error" => "Missing required fields."]);
    //             exit;
    //         }
        
    //         // Insert into job_assignments
    //         $stmt = $conn->prepare("INSERT INTO job_assignments (user_id, job_name, job_type, reference_link, final_date, file_count, priority, description, job_no, service, client_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    //         $stmt->bind_param("sssssississ", $addedBy, $name, $platform, $referenceLink, $finalDate, $file_page_count, $priority, $project_description, $job_no, $service, $Client_id); 
    //         $stmt->execute();
    //         $jobId = $stmt->insert_id;
    //         $stmt->close();
        
        
    //         // Insert into job_comments
    //         $stmt = $conn->prepare("INSERT INTO job_comments (job_id, commented_by, comment) VALUES (?, ?, ?)");
    //         $stmt->bind_param("sss", $jobId, $addedBy, $project_description);
    //         $stmt->execute();
    //         $stmt->close();
        
    //         // Handle file uploads
    //         if (!empty($_FILES['files'])) {
    //             foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
    //                 if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
    //                     $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $_FILES['files']['name'][$index]);
    //                     $mimeType = mime_content_type($tmpName);
    //                     $driveFile = new DriveFile();
    //                     $driveFile->setName($fileName);
    //                     $driveFile->setParents([$folderId]);
    //                     $data = file_get_contents($tmpName);
    //                     $createdFile = $service->files->create($driveFile, [
    //                         'data' => $data,
    //                         'mimeType' => $mimeType,
    //                         'uploadType' => 'multipart'
    //                     ]);
    //                     $driveFileId = $createdFile->getId();
    //                     $stmt = $conn->prepare("INSERT INTO job_files (job_id, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
    //                     $stmt->bind_param("isss", $jobId, $fileName, $driveFileId, $addedBy);
    //                     $stmt->execute();
    //                     $stmt->close();
    //                 }
    //             }
    //         }
        
    //         echo json_encode(["success" => "Job added successfully!"]);
    //     } catch (Exception $e) {
    //         error_log("Error: " . $e->getMessage());
    //         http_response_code(500);
    //         echo json_encode(["error" => $e->getMessage()]);
    //     }


    $Client_id = "";

    // Check if JWT user ID exists
    if ($JWT_userID) {
        // Fetch user details
        $stmt = $conn->prepare("SELECT cuid FROM employee WHERE id = ?");
        $stmt->bind_param("s", $JWT_userID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $Client_id = $row['cuid'];
        }

        $stmt->close();
    }

    try {
        $name = $_POST['name'] ?? null;
        $platform = $_POST['platform'] ?? null;
        $referenceLink = $_POST['linkurl'] ?? null;
        $finalDate = !empty($_POST['date']) ? DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d') : NULL;
        $priority = $_POST['priority'] ?? 'Low Priority';
        $project_description = urldecode($_POST['project_description']) ?? null; // Decode description
        $file_page_count =  $_POST['file_page_count'] ?? null;
      $upload_files =  $_POST['upload_files'] ?? null;
        $job_no = $_POST['jobNo'] ?? null;
        $my_service = $_POST['service'] ?? null;

        $addedBy = $JWT_userID;

        // Check for required fields
        if (!$name || !$my_service ) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields."]);
            exit;
        }

        // Insert into job_assignments table
        $stmt = $conn->prepare("INSERT INTO job_assignments (user_id, job_name, job_type, reference_link, final_date, file_count, priority, description, job_no, service, client_id,upload_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssississs", $addedBy, $name, $platform, $referenceLink, $finalDate, $file_page_count, $priority, $project_description, $job_no, $my_service, $Client_id,$upload_files);
        $stmt->execute();
        $jobId = $stmt->insert_id;
        $stmt->close();

        // Insert into job_comments table (optional) 
        $stmt = $conn->prepare("INSERT INTO job_comments (job_id, commented_by, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $jobId, $addedBy, $project_description);
        $stmt->execute();
        $stmt->close();

        // Handle file uploads
        if (!empty($_FILES['files'])) {
            foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
                if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $_FILES['files']['name'][$index]);
                    $mimeType = mime_content_type($tmpName);
                    $driveFile = new DriveFile();
                    $driveFile->setName($fileName);
                    $driveFile->setParents([$folderId]);
                    $data = file_get_contents($tmpName);
                    $createdFile = $service->files->create($driveFile, [
                        'data' => $data,
                        'mimeType' => $mimeType,
                        'uploadType' => 'multipart'
                    ]);
                    $driveFileId = $createdFile->getId();
                    $stmt = $conn->prepare("INSERT INTO job_files (job_id, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $jobId, $fileName, $driveFileId, $addedBy);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        else
        {
            echo("lkdsjflkdsjflkds");
        }

        echo json_encode(["success" => "Job added successfully!"]);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }

        break;



    case 'UpdateClientProject':

        // try {
        //     $jobId = $_POST['job_id'] ?? null;
        //     $name = $_POST['name'] ?? null;
        //     $platform = $_POST['platform'] ?? null;
        //     $referenceLink = $_POST['linkurl'] ?? null;
        //     $finalDate = !empty($_POST['date']) ? DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d') : NULL;
        //     $priority = $_POST['priority'] ?? 'Low Priority';
        //     $project_description = urldecode($_POST['project_description']) ?? null;
        //     $file_page_count =  $_POST['file_page_count'] ?? null;
        //     $job_no = $_POST['jobNo'] ?? null;
        //     $service = $_POST['service'] ?? null; 
        //     $updatedBy = $JWT_userID;
        
        //     if (!$jobId || !$name || !$service ) {
        //         http_response_code(400);
        //         echo json_encode(["error" => "Missing required fields."]);
        //         exit;
        //     }
        
        //     // Update job_assignments
        //     $stmt = $conn->prepare("UPDATE job_assignments SET job_name = ?, job_type = ?, reference_link = ?, final_date = ?, file_count = ?, priority = ?, description = ?, service = ?, job_no = ? WHERE id = ?");
        //     $stmt->bind_param("ssssissssi", $name, $platform, $referenceLink, $finalDate, $file_page_count, $priority, $project_description, $jobId, $service, $job_no);
        //     $stmt->execute();
        //     $stmt->close();
        
        //     // Update or Insert into job_comments (If a comment already exists, update it; otherwise, insert)
        //     $stmt = $conn->prepare("SELECT id FROM job_comments WHERE job_id = ?");
        //     $stmt->bind_param("i", $jobId);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     $stmt->close();
        
        
        //     // Handle file uploads (Add new files, do not remove existing)
        //     if (!empty($_FILES['files'])) {
        //         foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        //             if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
        //                 $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $_FILES['files']['name'][$index]);
        //                 $mimeType = mime_content_type($tmpName);
        //                 $driveFile = new DriveFile();
        //                 $driveFile->setName($fileName);
        //                 $driveFile->setParents([$folderId]);
        //                 $data = file_get_contents($tmpName);
        //                 $createdFile = $service->files->create($driveFile, [
        //                     'data' => $data,
        //                     'mimeType' => $mimeType,
        //                     'uploadType' => 'multipart'
        //                 ]);
        //                 $driveFileId = $createdFile->getId();
        //                 $stmt = $conn->prepare("INSERT INTO job_files (job_id, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
        //                 $stmt->bind_param("isss", $jobId, $fileName, $driveFileId, $updatedBy);
        //                 $stmt->execute();
        //                 $stmt->close();
        //             }
        //         }
        //     }
        
        //     echo json_encode(["success" => "Job updated successfully!"]);
        // } catch (Exception $e) {
        //     error_log("Error: " . $e->getMessage());
        //     http_response_code(500);
        //     echo json_encode(["error" => $e->getMessage()]);
        // }
        
        try {
            $jobId = $_POST['job_id'] ?? null;
            $name = $_POST['name'] ?? null;
            $platform = $_POST['platform'] ?? null;
            $referenceLink = $_POST['linkurl'] ?? null;
            $finalDate = !empty($_POST['date']) ? DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d') : NULL;
            $priority = $_POST['priority'] ?? 'Low Priority';
            $project_description = urldecode($_POST['project_description']) ?? null; // Decode description
            $file_page_count =  $_POST['file_page_count'] ?? null;
            $job_no = $_POST['jobNo'] ?? null;
            $my_service = $_POST['service'] ?? null; 
            $updatedBy = $JWT_userID;
    
            // Check if required fields are set
            if (!$jobId || !$name || !$my_service ) {
                http_response_code(400);
                echo json_encode(["error" => "Missing required fields."]);
                exit;
            }
    
            // Check if job_no is an integer if it's supposed to be one
            if (!is_numeric($job_no) && $job_no !== null) {
                echo json_encode(["error" => "Invalid job number."]);
                exit;
            }
    
            // Update job_assignments
            $stmt = $conn->prepare("UPDATE job_assignments SET job_name = ?, job_type = ?, reference_link = ?, final_date = ?, file_count = ?, priority = ?, description = ?, service = ?, job_no = ? WHERE id = ?");
            
            // Bind the parameters
            $stmt->bind_param("ssssissssi", $name, $platform, $referenceLink, $finalDate, $file_page_count, $priority, $project_description, $my_service, $job_no, $jobId);
    
            // Execute the query and check if it was successful
            if ($stmt->execute()) {
                $stmt->close();
    
                // Update or Insert into job_comments (If a comment already exists, update it; otherwise, insert)
                $stmt = $conn->prepare("SELECT id FROM job_comments WHERE job_id = ?");
                $stmt->bind_param("i", $jobId);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
    
                // Handle file uploads (Add new files, do not remove existing)
                if (!empty($_FILES['files'])) {
                    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
                        if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
                            $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $_FILES['files']['name'][$index]);
                            $mimeType = mime_content_type($tmpName);
                            $driveFile = new DriveFile();
                            $driveFile->setName($fileName);
                            $driveFile->setParents([$folderId]);
                            $data = file_get_contents($tmpName);
                            $createdFile = $service->files->create($driveFile, [
                                'data' => $data,
                                'mimeType' => $mimeType,
                                'uploadType' => 'multipart'
                            ]);
                            $driveFileId = $createdFile->getId();
                            $stmt = $conn->prepare("INSERT INTO job_files (job_id, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("isss", $jobId, $fileName, $driveFileId, $updatedBy);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }
    
                echo json_encode(["success" => "Job updated successfully!"]);
            } else {
                // If the query didn't execute, show an error
                $error = $stmt->error;
                $stmt->close();
                echo json_encode(["error" => "Failed to update job: " . $error]);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    
        

        break;


    case 'AddJobComments':

        $jobId = $_POST['job_id'] ?? null;
        $comment = $_POST['comment'] ?? null;
        $addedBy = $JWT_userID; // Replace this with the logged-in user ID

        if (!$jobId || !$comment) {
            echo json_encode(["success" => false, "error" => "Missing required fields"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO job_comments (job_id, commented_by, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $jobId, $addedBy, $comment);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
        }

        $stmt->close();


        break;


    case 'Comment_description_show':
        
        $comment_id = intval($_POST["comment_id"]);

        $query = "SELECT comment FROM job_comments WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode(["status" => "success", "comment" => $row["comment"]]);
        } else {
            echo json_encode(["status" => "error", "message" => "Comment not found"]);
        }

        $stmt->close();
        $conn->close();

        break;


    
    
    
    
        case 'Fetch_job_Othes_details':

            $job_id = intval($_POST["job_id"]);

            //echo($job_id);

            $query = "SELECT id, review_page, completed_date, price, status FROM job_assignments WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $job_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                echo json_encode(["status" => "success", "data" => $row]);
            } else {
                echo json_encode(["status" => "error", "message" => "Job not found"]);
            }

            $stmt->close();
            $conn->close();

        break;


    case 'Update_job_submit':

        $job_id = intval($_POST["job_id"]);
$review_page = intval($_POST["review_page"]);
$price = floatval($_POST["price"]);
$status = $_POST["status"];

$completed_date = !empty($_POST['completed_date']) ? DateTime::createFromFormat('d-m-Y', $_POST['completed_date'])->format('Y-m-d') : NULL;

// Ensure that 'completed_date' is NULL or a valid date string
$query = "UPDATE job_assignments SET review_page = ?, price = ?, status = ?, completed_date = ? WHERE id = ?";
$stmt = $conn->prepare($query);

// Bind the parameters
$stmt->bind_param("iisss", $review_page, $price, $status, $completed_date, $job_id); // Corrected bind_param order

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update job."]);
}

$stmt->close();
$conn->close();

        break;



    case 'EditJobFetchAll':

        $jobId = intval($_POST['id']); // Sanitize input

        $query = "SELECT * FROM job_assignments WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Convert final_date to dd-mm-yyyy format
            if (!empty($row['final_date'])) {
                $row['final_date'] = date("d-m-Y", strtotime($row['final_date']));
            }


            $doc_query = "SELECT file_name,file_path FROM job_files WHERE job_id = ?";
            $doc_stmt = $conn->prepare($doc_query);
            $doc_stmt->bind_param("i", $jobId); // Change if your relation is different
            $doc_stmt->execute();
            $doc_result = $doc_stmt->get_result();

            $fileNames = [];
            while ($doc = $doc_result->fetch_assoc()) {
                $fileNames[] = $doc['file_name'];
            }
            $row['file_name'] = implode(", ", $fileNames); // Join with commas

            echo json_encode($row);
        } else {
            echo json_encode(["error" => "No job found"]);
        }

        $stmt->close();

        break;

    
    case 'save_notify_new_job_assigned':

        $job_id = isset($_POST['id']) ? $_POST['id'] : null;
        $job_name = isset($_POST['job_name']) ? $_POST['job_name'] : "Unknown Job";
        $job_type = isset($_POST['job_type']) ? $_POST['job_type'] : "Unknown Type";
        $created_by = isset($_POST['added_by_name']) ? $_POST['added_by_name'] : "Unknown User";
        $action = isset($_POST['action']) ? $_POST['action'] : "unknown_action";

        // Create notification text
        $notification_text = "New Job Added By " . $created_by;

        // Fetch all admin users
        $adminQuery = "SELECT id FROM employee WHERE role = 'admin'";
        $result = $conn->query($adminQuery);

        $notified_users = [];

        if ($result->num_rows > 0) {
            while ($admin = $result->fetch_assoc()) {
                $notified_users[] = $admin['id'];
            }
        }

        // Convert array to JSON format
        $notified_users_json = json_encode($notified_users);

        // Prepare INSERT statement
        $sql = "INSERT INTO gs_notification (job_id, job_name, job_type, created_by, notification_text, notified_users) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $job_id, $job_name, $job_type, $created_by, $notification_text, $notified_users_json);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Notification saved"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to save notification"]);
        }

        $stmt->close();
        $conn->close();


        break;

        case 'Fetch_job_Othes_details1':

        
            $job_id = intval($_POST["job_id"]);

   
            $query = "SELECT id, file_name, file_path, uploaded_at FROM client_project_files WHERE project_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $job_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = []; // Create an array to store all fetched rows

            // Loop through all rows and fetch them
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row; // Add each row to the array
            }

            // Check if any rows were fetched
            if (count($rows) > 0) {
                echo json_encode(["status" => "success", "data" => $rows]);
            } else {
                echo json_encode(["status" => "error", "message" => "Job not found"]);
            }


        
            $stmt->close();
            $conn->close();
            break;
    
     case 'Fetch_job_Othes_details13':

        
            $job_id = intval($_POST["job_id"]);

   
            $query = "SELECT id, file_name, file_path, uploaded_at FROM admin_submit_project_files WHERE project_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $job_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = []; // Create an array to store all fetched rows

            // Loop through all rows and fetch them
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row; // Add each row to the array
            }

            // Check if any rows were fetched
            if (count($rows) > 0) {
                echo json_encode(["status" => "success", "data" => $rows]);
            } else {
                echo json_encode(["status" => "error", "message" => "Job not found"]);
            }


        
            $stmt->close();
            $conn->close();
            break;
    
       case 'Fetch_job_Othes_details12':

        
            $job_id = intval($_POST["job_id"]);

   
            $query = "SELECT id, file_name, file_path, uploaded_at FROM job_files WHERE job_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $job_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = []; // Create an array to store all fetched rows

            // Loop through all rows and fetch them
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row; // Add each row to the array
            }

            // Check if any rows were fetched
            if (count($rows) > 0) {
                echo json_encode(["status" => "success", "data" => $rows]);
            } else {
                echo json_encode(["status" => "error", "message" => "Job not found"]);
            }


        
            $stmt->close();
            $conn->close();
            break;
    
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;

}









?>