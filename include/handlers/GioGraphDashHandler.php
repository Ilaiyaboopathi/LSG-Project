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
if (!isset($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Action is required']);
    exit;
}


$action = $_GET['action'];

switch ($action) 
{


    case 'FillerData':

        // Get the selected employee, start date, and end date from the POST request
        $employeeName = isset($_POST['employee']) ? $_POST['employee'] : '';
        $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
        $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';

        // Check if employee name and date range are provided
        if ($employeeName && $startDate && $endDate) {
            // Sanitize inputs to prevent SQL injection
            $employeeName = $conn->real_escape_string($employeeName);
            $startDate = $conn->real_escape_string($startDate);
            $endDate = $conn->real_escape_string($endDate);
            // Make sure the dates are in the correct format (YYYY-MM-DD)
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));

            // Query to fetch tasks based on the selected employee and date range
            $sql = "SELECT * FROM task_descriptions WHERE `date` BETWEEN '$startDate' AND '$endDate' 
                    AND addedBy LIKE '%$employeeName%'";

            $result = $conn->query($sql);

            // Initialize counters for each task status
            $pendingCount = 0;
            $followUpCount = 0;
            $completedCount = 0;
            $notInterestedCount = 0;
            $totalCount = 0;

            // Loop through the results to count the tasks based on their status
            while ($row = $result->fetch_assoc()) {
                $status = $row['status']; // Assuming 'status' column contains the task status

                switch ($status) {
                    case 'Processing':
                        $pendingCount++;
                        break;
                    case 'Follow Up':
                        $followUpCount++;
                        break;
                    case 'Completed':
                        $completedCount++;
                        break;
                    case 'Not Interested':
                        $notInterestedCount++;
                        break;
                }
                $totalCount++;
            }

            // Calculate percentages
            $pendingPercentage = ($totalCount > 0) ? ($pendingCount / $totalCount) * 100 : 0;
            $followUpPercentage = ($totalCount > 0) ? ($followUpCount / $totalCount) * 100 : 0;
            $completedPercentage = ($totalCount > 0) ? ($completedCount / $totalCount) * 100 : 0;
            $notInterestedPercentage = ($totalCount > 0) ? ($notInterestedCount / $totalCount) * 100 : 0;

            // Prepare the response data in JSON format
            $response = [
                'html' => 'Updated task summary here...', // You can construct HTML content for table rows, etc.
                'pendingCount' => $pendingCount,
                'followUpCount' => $followUpCount,
                'completedCount' => $completedCount,
                'notInterestedCount' => $notInterestedCount,
                'pendingPercentage' => $pendingPercentage,
                'followUpPercentage' => $followUpPercentage,
                'completedPercentage' => $completedPercentage,
                'notInterestedPercentage' => $notInterestedPercentage
            ];

            // Return JSON response
            echo json_encode($response);
        }

        break;

    case 'GioGraphDashHandler':
        // Get the selected employee name and date range from the AJAX request
        $employeeName = $_POST['employee'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        // Prepare the SQL query
        $sql = "
            SELECT 
                COUNT(CASE WHEN SubTaskStatus = 'Pending' THEN 1 END) AS pcount,
                COUNT(CASE WHEN SubTaskStatus = 'Extended' THEN 1 END) AS ecount,
                COUNT(CASE WHEN SubTaskStatus = 'Completed' THEN 1 END) AS ccount
            FROM assignproject 
            WHERE DeadlineDate BETWEEN '$startDate' AND '$endDate' 
            AND Name LIKE '%" . $conn->real_escape_string($employeeName) . "%'
        ";

        // Execute the query
        $result = $conn->query($sql);

        // Fetch the results
        if ($result && $row = $result->fetch_assoc()) {
            echo json_encode($row); // Return the counts as a JSON response
        } else {
            echo json_encode(['pcount' => 0, 'ecount' => 0, 'ccount' => 0]); // Default if no results found
        }

        break;


    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;



}



$conn->close();


?>




