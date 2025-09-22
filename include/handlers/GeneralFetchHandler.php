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




// Check for required parameters
if (!isset($_GET['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Action is required']);
    exit;
}


$user = isset($_GET['user']) ? $_GET['user'] : '';
$action = $_GET['action'];



switch ($action) 
{

    case 'GetProjectCount':
        // Prepare the query to count tasks based on status
        $sql_today = "
            SELECT 
                COUNT(CASE WHEN SubTaskStatus = 'Pending' THEN 1 END) AS pendingCount,
                COUNT(CASE WHEN SubTaskStatus = 'Extended' THEN 1 END) AS extendedCount,
                COUNT(CASE WHEN SubTaskStatus = 'Completed' THEN 1 END) AS completedCount
                
            FROM 
                assignproject
            WHERE 
                SubTaskStatus IN ('Pending', 'Extended', 'Completed')
            ";

        // If a specific user is selected, modify the query to filter by that user
        if ($user !== 'AllUsers' && !empty($user)) {
            $sql_today .= " AND Name LIKE '%" . $conn->real_escape_string($user) . "%'";
            }

            // Execute the query
            $result_today = $conn->query($sql_today);

            // Check if the query returned a result
            if ($result_today) {
            // Fetch the result
            $count_today = $result_today->fetch_assoc();

            // Return the result as a JSON response
            echo json_encode([
                'pendingCount' => $count_today['pendingCount'],
                'extendedCount' => $count_today['extendedCount'],
                'completedCount' => $count_today['completedCount']
                
            ]);
        } 
        else {
            // If the query failed, return an error
            echo json_encode(['error' => 'Error executing query.']);
        }

        break;


    case 'GetTodayTaskCount':
        // Get the current date (you can adjust this to your needs)
        $today = date('Y-m-d');

        // Prepare the query based on the user selection
        $sql_today = "SELECT COUNT(*) AS count FROM task_descriptions WHERE date = '$today'";

        // If a specific user is selected, modify the query to filter by that user
        if ($user !== 'AllUsers' && !empty($user)) {
            $sql_today .= " AND addedBy LIKE '%" . $conn->real_escape_string($user) . "%'";
        }

        // Execute the query
        $result_today = $conn->query($sql_today);
        $count_today = $result_today->fetch_assoc()['count'];

        // Return the result as a JSON response
        echo json_encode(['count' => $count_today]);

        break;


    case 'GetTaskCount':
        // Prepare the query to count tasks based on status
        $sql_today = "
            SELECT 
                COUNT(CASE WHEN status = 'Processing' THEN 1 END) AS pendingCount,
                COUNT(CASE WHEN status = 'Follow Up' THEN 1 END) AS followCount,
                COUNT(CASE WHEN status = 'Completed' THEN 1 END) AS completedCount,
                COUNT(CASE WHEN status = 'Not Interested' THEN 1 END) AS niCount
            FROM 
                task_descriptions
            WHERE 
                status IN ('Processing', 'Follow Up', 'Completed', 'Not Interested')
            ";

        // If a specific user is selected, modify the query to filter by that user
        if ($user !== 'AllUsers' && !empty($user)) {
            $sql_today .= " AND addedBy LIKE '%" . $conn->real_escape_string($user) . "%'";
        }

        // Execute the query
        $result_today = $conn->query($sql_today);

        // Check if the query returned a result
        if ($result_today) {
            // Fetch the result
            $count_today = $result_today->fetch_assoc();

            // Return the result as a JSON response
            echo json_encode([
                'pendingCount' => $count_today['pendingCount'],
                'followCount' => $count_today['followCount'],
                'completedCount' => $count_today['completedCount'],
                'niCount' => $count_today['niCount']
            ]);
        }
        else {
            // If the query failed, return an error
            echo json_encode(['error' => 'Error executing query.']);
        }

        break;


    case 'GetSixDaysCount':

        $tomorrow = date('Y-m-d', strtotime('+1 days'));

        // Get the date 6 days from today
        $next_six_days = date('Y-m-d', strtotime('+6 days'));

        // Prepare the query based on the user selection
        $sql_today = "SELECT COUNT(*) AS count FROM task_descriptions  WHERE `date` BETWEEN '$tomorrow' AND '$next_six_days'";

        // If a specific user is selected, modify the query to filter by that user
        if ($user !== 'AllUsers' && !empty($user)) {
            $sql_today .= " AND addedBy LIKE '%" . $conn->real_escape_string($user) . "%'";
        }

        // Execute the query
        $result_today = $conn->query($sql_today);
        $countSixDays = $result_today->fetch_assoc()['count'];

        // Return the result as a JSON response
        echo json_encode(['count' => $countSixDays]);

        break;


    case 'Get30DaysCount':
        $seventhDay = date('Y-m-d', strtotime('+7 days'));

        // Get the date 30 days from today
        $next_thirty_days = date('Y-m-d', strtotime('+31 days'));

        // Prepare the query based on the user selection
        $sql_today = "SELECT COUNT(*) AS count FROM task_descriptions  WHERE `date` BETWEEN '$seventhDay' AND '$next_thirty_days'";

        // If a specific user is selected, modify the query to filter by that user
        if ($user !== 'AllUsers' && !empty($user)) {
            $sql_today .= " AND addedBy LIKE '%" . $conn->real_escape_string($user) . "%'";
        }

        // Execute the query
        $result_today = $conn->query($sql_today);
        $count30Days = $result_today->fetch_assoc()['count'];

        // Return the result as a JSON response
        echo json_encode(['count' => $count30Days]);

        break;
    
    case 'DropFetchEmployee':
                        
            $sql = "SELECT name FROM employee WHERE isenable = 1 "; 
            $result = $conn->query($sql);
            
            $employees = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $employees[] = $row;
                }
            }
            
            echo json_encode($employees);
           
    
    
            break;

    case 'DropFetchTask':


                $sql = "SELECT DISTINCT taskName FROM task_descriptions"; 
                $result = $conn->query($sql);
                
                $employees = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $employees[] = $row;
                    }
                }
                
                echo json_encode($employees);

                break;

    case 'DropFetchProject':
                   
        $sql = "SELECT name FROM project "; 
        $result = $conn->query($sql);
        
        $employees = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employees[] = $row;
            }
        }
        
        echo json_encode($employees);
            
        break;
    
    case 'FetchNotifications':

        $username = $_GET['username'] ?? null; // Get the email from the query parameter

        if (!$username) {
            http_response_code(400);
            echo json_encode(['error' => 'Employee email is required']);
            exit;
        }
        
        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE employee = ? AND status = 1");
        $stmt->bind_param("s", $username);
        
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to execute query: ' . $stmt->error]);
            exit;
        }
        
        $result = $stmt->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
        
        // Close the statement and connection
        $stmt->close();
        
        // Return the notifications as JSON
        echo json_encode($notifications);

        break;

    

    case 'Fetch_GS_Notifications':

        $user_id = $_GET['user_id'] ?? null; // Get the user_id from the query parameter

        if (!$user_id) {
            http_response_code(400);
            echo json_encode(['error' => 'User ID is required']);
            exit;
        }

        // Convert user_id to JSON format for query
        $json_user_id = json_encode($user_id);

        // Query to check if user_id exists in JSON array (notified_users) and status is unread (is_read = 0)
        $sql = "SELECT * FROM gs_notification WHERE JSON_CONTAINS(notified_users, ?, '$') AND is_read = 0";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $json_user_id); // Pass the pre-encoded variable

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to execute query: ' . $stmt->error]);
            exit;
        }

        $result = $stmt->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);

        // Close the statement and connection
        $stmt->close();

        // Return the notifications as JSON
        echo json_encode($notifications);


        break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;

}



$conn->close();


?>