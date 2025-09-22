<?php


session_start(); 


require '../../vendor/autoload.php';
require '../../JWTValues.php';
require '../../data/dbconfig.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
// use DomainException;
// use InvalidArgumentException;
// use UnexpectedValueException;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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



$input = file_get_contents('php://input');
$data = json_decode($input, true);


if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}
$fromEmail = $JWT_userEmail;


////   -----------------------------------Update Sub Task Status Pending Start-------------------------------------------------------- ///

if (isset($data['action']) && $data['action'] === 'UpdateTaskStatusToPending') {
    $Status = sanitizeInput($data['Status']);
    $TaskId = sanitizeInput($data['TaskId']);
    $ProjectId = sanitizeInput($data['ProjectId']);

    $sql = "UPDATE assignproject 
    SET SubTaskStatus = '$Status' WHERE id = '$TaskId' AND ProjectId = '$ProjectId'";

    if ($conn->query($sql) === TRUE) {
        $Project_stmt = "UPDATE project 
                        SET ProjectStatus = 'Pending', CompletedDate = Null
                        WHERE ProjectId = '$ProjectId'";
        if ($conn->query($Project_stmt) === TRUE) {
            echo json_encode(['status' => 'Update_Subtask_Success', 'message' => 'SubTask Status updated successfully.']);
        }
    }
    else
    {
        echo json_encode(['error' => 'Failed to execute update Sub Task Status: ' . $conn->error]);
    }

}

////   -----------------------------------Update Sub Task Status InProgress Start-------------------------------------------------------- ///

if (isset($data['action']) && $data['action'] === 'UpdateTaskStatusToInProgress') {

    $Status = sanitizeInput($data['Status']);
    $Notes = sanitizeInput($data['Notes']);
    $TaskId = sanitizeInput($data['TaskId']);
    $ProjectId = sanitizeInput($data['ProjectId']);

    $sql = "UPDATE assignproject 
    SET SubTaskStatus = '$Status', SubtaskNote = '$Notes' WHERE id = '$TaskId' AND ProjectId = '$ProjectId'";

    if ($conn->query($sql) === TRUE) {
        $Project_stmt = "UPDATE project 
                        SET ProjectStatus = 'Pending', CompletedDate = Null
                        WHERE ProjectId = '$ProjectId'";
        if ($conn->query($Project_stmt) === TRUE) {
            echo json_encode(['status' => 'Update_Subtask_Success', 'message' => 'SubTask Status updated successfully.']);
        }
    }
    else
    {
        echo json_encode(['error' => 'Failed to execute update Sub Task Status: ' . $conn->error]);
    }
}

////   -----------------------------------Update Sub Task Status Completed Start-------------------------------------------------------- ///

if (isset($data['action']) && $data['action'] === 'UpdateTaskStatusToCompleted') {

    $Status = sanitizeInput($data['Status']);
    $Notes = sanitizeInput($data['Notes']);
    $Link = sanitizeInput($data['Link']);
    $TaskId = sanitizeInput($data['TaskId']);
    $ProjectId = sanitizeInput($data['ProjectId']);

    $sql = "UPDATE assignproject 
        SET SubTaskStatus = '$Status', SubtaskNote = '$Notes', completed_link = '$Link' 
        WHERE id = '$TaskId' AND ProjectId = '$ProjectId'";

    if ($conn->query($sql) === TRUE) {

        $query = "SELECT p.* FROM project p WHERE p.ProjectId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $ProjectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $assignedByEmail = $row['assignedBy'] ?? null;
            $assignedToEmail = $row['assignedTo'] ?? null;
            $projectName = $row['name'] ?? 'No Name';  // Store project name for later use
            $projectId = $row['id'] ?? null;  // Store project ID for later use
            $projectType = $row['type'] ?? null;  // Store project type for later use
            $deadlineDate = $row['deadlineDate'] ?? null;  // Store deadline date
            $deadlineTime = $row['deadlineTime'] ?? null;  // Store deadline time
            $assignedPerson =  $JWT_adminName?? 'Your Name'; // Adjust accordingly
            $designation =  $JWT_userDesignation  ?? 'Your Designation';
            $selectempoyees = array_merge(
                explode(',', $assignedToEmail),
                explode(',', $assignedByEmail)
            );
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'taskenginembw@gmail.com';
            $mail->Password = 'dwed lrmz jzue bsml';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
        
            $mail->setFrom('taskenginembw@gmail.com', 'Project Management');
            
            foreach ($selectempoyees as $recipientName) {
                $sql = "SELECT email FROM employee WHERE name = '$recipientName'";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $assignedTo = $row['email'];

                    $template = file_get_contents('Mail/UpdateProject.html');
                    $emailBody = $template;
                    $emailBody = str_replace('##ProjectName##', $projectName, $emailBody);
                    $emailBody = str_replace('##EmployeeName##', $recipientName, $emailBody);
                    $emailBody = str_replace('##FinalDate##',$deadlineDate, $emailBody);
                    $emailBody = str_replace('##FinalTime##', $deadlineTime, $emailBody);
                    $emailBody = str_replace('##TaskType##', $projectType, $emailBody);
                    $emailBody = str_replace('##Status##',  $Status, $emailBody);
                    $emailBody = str_replace('##TaggedEmployees##',implode(', ', $selectempoyees) , $emailBody);
                    $emailBody = str_replace('##AssignedBy##', $assignedByEmail, $emailBody);
                    $emailBody = str_replace('##ProjectDescription##', $Notes, $emailBody);
                    $emailBody = str_replace('##AssignedPerson##', $assignedPerson, $emailBody);
                    $emailBody = str_replace('##Designation##', $designation, $emailBody);
                    $mail->addAddress($assignedTo);
                    $mail->isHTML(true);
                    $mail->Subject = 'Project Task Completed '. $projectName ;
                    $mail->Body = $emailBody;
    
                    $mail->send();
                    $mail->clearAddresses();

                }
            }
            // $previousMessageId = getPreviousMessageIdFromDatabase($ProjectId); 

            // if ($previousMessageId) {
            //     //$mail->MessageID = $previousMessageId;
            //     //$mail->MessageID = $previousMessageId;
            //     $mail->addCustomHeader('In-Reply-To', $previousMessageId);
            //     $mail->addCustomHeader('References', $previousMessageId);
            //     $mail->Subject = 'RE: ' . $subject; // Prefix with "Re:"
            //     } else {
            //         // New message
            //         $mail->Subject = $subject; 
            //     }


            // $mail->send();
            // $messageId = $mail->getLastMessageID(); 
            // if (!$previousMessageId) {

            //     $updateSql = "UPDATE project SET messageID = ? WHERE ProjectId = ?";
            //     $updateStmt = $conn->prepare($updateSql);
            //     $updateStmt->bind_param('ss', $messageId, $ProjectId);
            //     $updateStmt->execute();
            // }

         
            $check_pending_sql = "SELECT COUNT(*) as count FROM assignproject WHERE ProjectId = ? AND SubTaskStatus = 'Pending'";
            $check_stmt = $conn->prepare($check_pending_sql);
            $check_stmt->bind_param('s', $ProjectId);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();

            // Check if any rows are pending
            if ($row['count'] > 0) {
                echo json_encode(['status' => 'Update_Subtask_Success', 'message' => 'SubTask Status updated successfully. waiting for other team to complete project.']);
            } else {
                // No pending rows
                $Project_stmt = "UPDATE project 
                        SET ProjectStatus = 'Completed', CompletedDate = NOW()
                        WHERE ProjectId = '$ProjectId'";

                if ($conn->query($Project_stmt) === TRUE) {
                    echo json_encode([
                        'status' => 'Update_Subtask_Success',
                        'assignedBy' => $assignedByEmail,
                        'assignedTo' => $assignedToEmail,
                        'name' => $projectName,
                        'id' => $projectId,
                        'platform' => $projectType,
                        'details' => $Status . ' ' . $Notes,
                        'date' => $deadlineDate,
                        'time' => $deadlineTime,
                        'message' => 'Good, Your SubTask Status updated successfully. Project Completed Successfully'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Only SubTask Status updated. But Failed to execute project status update: ' . $conn->error]);
                    exit;
                }
            }
        } else {
            // Handle case where no project data is found
            echo json_encode(['error' => 'No project found with the specified ProjectId']);
        }
    } else {
        echo json_encode(['error' => 'Failed to execute update Sub Task Status: ' . $conn->error]);
    }
}



////   -----------------------------------Update Sub Task Status Completed End-------------------------------------------------------- ///



////   -----------------------------------Update Sub Task Status Extended Start-------------------------------------------------------- ///


if (isset($data['action']) && $data['action'] === 'UpdateTaskStatusToExtended') {

    $Status = sanitizeInput($data['Status']);
    $Notes = sanitizeInput($data['Notes']);
    $Ext_date = sanitizeInput($data['ExtDate']);
    $Ext_time = sanitizeInput($data['ExtTime']);
    $TaskId = sanitizeInput($data['TaskId']);
    $ProjectId = sanitizeInput($data['ProjectId']);

    $sql = "UPDATE assignproject 
        SET SubTaskStatus = '$Status', SubtaskNote = '$Notes'
        WHERE id = '$TaskId' AND ProjectId = '$ProjectId'";

    if ($conn->query($sql) === TRUE) {

      
          $query = "SELECT p.* FROM project p WHERE p.ProjectId = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('s', $ProjectId);
          $stmt->execute();
          $result = $stmt->get_result();

        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $ProjectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $assignedByEmail = $row['assignedBy'] ?? null;
            $assignedToEmail = $row['assignedTo'] ?? null;
            $projectName = $row['name'] ?? 'No Name';  // Store project name for later use
            $projectId = $row['id'] ?? null;  // Store project ID for later use
            $projectType = $row['type'] ?? null;  // Store project type for later use
            $deadlineDate = $row['deadlineDate'] ?? null;  // Store deadline date
            $deadlineTime = $row['deadlineTime'] ?? null;  // Store deadline time
            $assignedPerson =  $JWT_adminName?? 'Your Name'; // Adjust accordingly
            $designation =  $JWT_userDesignation  ?? 'Your Designation';
            $selectempoyees = array_merge(
                explode(',', $assignedToEmail),
                explode(',', $assignedByEmail)
            );

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'taskenginembw@gmail.com';
            $mail->Password = 'dwed lrmz jzue bsml';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
        
            $mail->setFrom('taskenginembw@gmail.com', 'Project Management');
            
            foreach ($selectempoyees as $recipientName) {
                $sql = "SELECT email FROM employee WHERE name = '$recipientName'";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $assignedTo = $row['email'];
                    
                    $template = file_get_contents('Mail/UpdateProject.html');
                    $emailBody = $template;
                    $emailBody = str_replace('##ProjectName##', $projectName, $emailBody);
                    $emailBody = str_replace('##EmployeeName##', $recipientName, $emailBody);
                    $emailBody = str_replace('##FinalDate##',$Ext_date, $emailBody);
                    $emailBody = str_replace('##FinalTime##', $Ext_time, $emailBody);
                    $emailBody = str_replace('##TaskType##', $projectType, $emailBody);
                    $emailBody = str_replace('##Status##',  $Status, $emailBody);
                    $emailBody = str_replace('##TaggedEmployees##',implode(', ', $selectempoyees), $emailBody);
                    $emailBody = str_replace('##AssignedBy##', $assignedByEmail, $emailBody);
                    $emailBody = str_replace('##ProjectDescription##', $Notes, $emailBody);
                    $emailBody = str_replace('##AssignedPerson##', $assignedPerson, $emailBody);
                    $emailBody = str_replace('##Designation##', $designation, $emailBody);
                    $mail->addAddress($assignedTo);
                    $mail->isHTML(true);
                    $mail->Subject = 'Project Extended'. $projectName ;
                    $mail->Body = $emailBody;
    
                    $mail->send();
                    $mail->clearAddresses();

                }
            }
        }

        $result = $conn->query("SELECT * FROM assignproject WHERE id = '$TaskId' AND ProjectId = '$ProjectId'");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name =  $row['Name'];
                $info =  $row['Information'];
                $ProjectName = $row['ProjectName'];
                $Platform = $row['Platform'];
                //$ProjectName = $row['ProjectName'];
                $NowTime = date("Y-m-d H:i:s");


                $dead_line_date = new DateTime(datetime: $Ext_date);
                $formattedDeadlineDate = $dead_line_date->format('Y-m-d');

                // For Time Formatting Start
                if ($Ext_time) {
                    $time = DateTime::createFromFormat('H:i:s', $Ext_time);
                    if (!$time) {
                        $time = DateTime::createFromFormat('H:i', $Ext_time);
                    }
                    $formattedDeadlineTime = $time ? $time->format('H:i:s') : null;
                } else {
                    $formattedDeadlineTime = null;
                }
                // For Time Formatting End

                // After all sub task completed again change extended any one Start
                $checkPendingQuery = "SELECT COUNT(*) AS pending_count FROM assignproject WHERE `ProjectId` = '$ProjectId' AND SubTaskStatus = 'Pending'";
                $checkedResult = $conn->query($checkPendingQuery);
                if ($checkedResult) {
                    $row = $checkedResult->fetch_assoc();
                    if ($row['pending_count'] == 0) {

                        $Project_stmt = "UPDATE project 
                                     SET ProjectStatus = 'Pending', CompletedDate = Null
                                     WHERE ProjectId = '$ProjectId'";

                        if ($conn->query($Project_stmt) === TRUE) {
                            //Nothing Happening
                        } else {
                            echo json_encode(['error' => 'Failed to execute update Whole Project Status: ' . $conn->error]);
                        }
                    }
                }
                // After all sub task completed again change extended any one End


                $CreateNewOne = "INSERT INTO assignproject (ProjectId, Name, ProjectName,Platform,DeadlineDate, DeadlineTime, Information, SubTaskStatus, CreateDateTime) 
                                VALUES ('$ProjectId', '$name', '$ProjectName','$Platform','$formattedDeadlineDate', '$formattedDeadlineTime', '$info', 'Pending', '$NowTime')";

                if ($conn->query($CreateNewOne)) {
                    echo json_encode(value: ['status' => 'Update_Subtask_Success', 'message' => 'Okay, Your SubTask Status updated. Next Time try to Complete within ' . $Ext_date . ' ' . $Ext_time . '']);
                } else {
                    echo json_encode(['error' => 'Failed to execute update Sub Task Status: ' . $conn->error]);
                }
            }
        }
    }
}


////   -----------------------------------Update Sub Task Status Extended End-------------------------------------------------------- ///



function sanitizeInput($input)
{
    return htmlspecialchars(trim($input)); // Simple sanitization
}


function getPreviousMessageIdFromDatabase($projectId) {
    global $conn; // Ensure you have access to your database connection
    $sql = "SELECT messageID FROM project WHERE ProjectId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['messageID'];
    }
    return null; // Return null if not found
}