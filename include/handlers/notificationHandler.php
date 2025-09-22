<?php

session_start();

// =====================================JWT Validation Start=====================================================

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



// Check for required parameters
if (!isset($data['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Action is required']);
    exit;
}

$action = $data['action'];



switch ($action) 
{

    // =========================Insert Reminder Notification to Notification Table Start ====================================

    case 'InsertReminderNotification':

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }
        
        $stmt = $conn->prepare("
            INSERT INTO reminder_notification (notificationid, name, assignedBy, duration, date, recurring,type,isCancelled,createdOn)
            VALUES (?, ?, ?, ?, ?, ?, ?,?,?)
        ");
        
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
                
        
            $notificationId = $data['notificationid'];
            $name = $data['name'];
            $assignedBy = $data['assignedTo'];
            $duration = $data['duration'];
            $date = $data['date'];
            $recurring = $data['recurring'];
            $type = 'Reminder';
            $isCancelled = 1; 
            $createdon = date('Y-m-d H:i:s');
        
          
            $stmt->bind_param(
                'sssssssss',
                $notificationId,
                $name,
                $assignedBy,
                $duration,
                $date,
                $recurring,
                $type,
                $isCancelled,
                $createdon 
            );
        
            
            if (!$stmt->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to execute statement for employee ' . $assignedBy . ': ' . $stmt->error]);
                $stmt->close();
                $conn->close();
                exit;
            }
        
        
        $stmt->close();
        
        echo json_encode(['success' => true]);

        break;
    
    // =========================Insert Remider Notification to Notification Table End ====================================








    // =========================Insert Project Notification to Notification Table Start ====================================
    
    case 'InsertProjectNotification': 
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }
        
        $stmt = $conn->prepare("
            INSERT INTO notifications (notificationid,rowid, name, platform, employee, details, date, time, status,type)
            VALUES (?, ?,?, ?, ?, ?, ?, ?, ?,?)
        ");
        
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
        
            
            $notificationId = 1;  //Nothing use so We Give 1
            $taskid = $data['taskid'];
            $name = $data['name'];
            $platform = $data['platform'];
            $details = $data['details'];
            $time = $data['time'];
            $status = 1;
            $employee = $data['employee'];
            $type = 'Project';
        
            // Convert date to MySQL format (YYYY-MM-DD)
            $dateString = $data['date'];
        //$date = DateTime::createFromFormat('d-m-Y', $dateString);
        
        //     if ($date) {
        //          $mysqlDate = $date->format('Y-m-d'); // Format for MySQL
        //     } else {
        //         // Handle the error if the date is invalid
        //         error_log("Invalid date format: $dateString");
        //         $mysqlDate = null; // Or set to a default date
        //     }
            
           
            $stmt->bind_param(
                'ssssssssss',
                $notificationId,
                $taskid,
                $name,
                $platform,
                $employee,
                $details,
                $dateString,
                $time,
                $status,
                $type
            );
        
          
            if (!$stmt->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to execute statement for employee ' . $employee . ': ' . $stmt->error]);
                $stmt->close();
                $conn->close();
                exit;
            }
        
            
        $stmt->close();
        
        echo json_encode(['success' => true]);

    // =========================Insert Project Notification to Notification Table End ====================================






    // =========================Insert Sales Notification to Notification Table Start ====================================

    case 'InsertDeadlineSales': 

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }
        
        $stmt = $conn->prepare("
            INSERT INTO notifications (notificationid, name, platform, employee, details, date, time, status,type)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)
        ");
        
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
        
        $employee =  $data['employee'];
        
            //$employee = trim($employee); 
        
            $notificationId = $data['notificationid'];
            $name = $data['name'];
            $platform = $data['platform'];
            $details = $data['details'];
            $date = $data['date'];
            $time = $data['time'];
            $status = 1; 
            $type = 'Project';
        
          
            $stmt->bind_param(
                'sssssssss',
                $notificationId,
                $name,
                $platform,
                $employee,
                $details,
                $date,
                $time,
                $status,
                $type 
            );
        
            
            if (!$stmt->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to execute statement for employee ' . $employee . ': ' . $stmt->error]);
                $stmt->close();
                $conn->close();
                exit;
            }
        
        
        $stmt->close();
        
        echo json_encode(['success' => true]);

    // =========================Insert Sales Notification to Notification Table End ====================================







    // =========================Insert Sales Notification to Notification Table Start ====================================

    case 'InsertNotification': 

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON input']);
            exit;
        }
        
        $stmt = $conn->prepare("
            INSERT INTO notifications (notificationid,rowid, name, platform, employee, details, date, time, status,type)
            VALUES (?, ?, ?,?, ?, ?, ?, ?, ?,?)
        ");
        
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
        
        
        
            $notificationId = $data['notificationid'];
            $name = $data['name'];
            $taskid = $data['taskid'];
            $platform = $data['platform'];
            $details = $data['details'];
            $date = $data['date'];
            $time = $data['time'];
            $employee  = $data['employee'];
            $status = 1; 
            $type = 'Task';
        
          
            $stmt->bind_param(
                'ssssssssss',
                $notificationId,
                $taskid,
                $name,
                $platform,
                $employee,
                $details,
                $date,
                $time,
                $status,
                $type 
            );
        
            
            if (!$stmt->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to execute statement for employee ' . $employee . ': ' . $stmt->error]);
                $stmt->close();
                $conn->close();
                exit;
            }
        
        
        $stmt->close();
        
        echo json_encode(['success' => true]);

        break;

    // =========================Insert Sales Notification to Notification Table End ====================================








    // =========================Cancel Notification Sales and Project Start==========================================

    case 'CancelNotification':

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Notification ID is required']);
            exit;
        }

        $notificationId = $data['id'];

        // Update the status in the notification table
        $stmt = $conn->prepare("UPDATE notifications SET status = 0 WHERE id = ?");
        $stmt->bind_param("s", $notificationId);

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to cancel notification: ' . $stmt->error]);
            exit;
        }

        // Close the statement and connection
        $stmt->close();

        echo json_encode(['success' => true]);

        break;

    
    // =========================Cancel Notification Sales and Project End==========================================







    // =========================Cancel Notification Reminder Start ====================================

    case 'CancelReminderNotification':
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Notification ID is required']);
            exit;
        }
        
        $notificationId = $data['id'];
        
        // Update the status in the notification table
        $stmt = $conn->prepare("UPDATE reminder_notification SET isCancelled = 0 WHERE id = ?");
        $stmt->bind_param("s", $notificationId);
        
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to cancel notification: ' . $stmt->error]);
            exit;
        }
        
        // Close the statement and connection
        $stmt->close();
        
        
        echo json_encode(['success' => true]);

        break;

    // =========================Cancel Notification Reminder End ====================================



    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;


}


$conn->close();


?>