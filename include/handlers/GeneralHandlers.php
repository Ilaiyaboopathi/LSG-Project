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


$action = $_POST['action'];


switch ($action) 
{
    case 'FetchEmployee':
      $type = $_POST['type'];
        //$table = $type === 'admin' ? 'admins' : 'employees';
        
        $sql = "SELECT id, name FROM employee WHERE role = '$type'"; // Make sure to select the correct columns
        $result = $conn->query($sql);
        
        $employees = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $employees[] = $row;
            }
        }
        
        echo json_encode($employees);

        break;

    case 'UpdateEnableStatus':
        // Get the data from the POST request
        $id = $_POST['id'];
        $isEnable = $_POST['isEnable'];

        // Prepare the update query
        $query = "UPDATE reminder SET isEnable = ? WHERE id = ?";

        // Prepare and execute the query
        if ($stmt = $conn->prepare($query)) {
            // Bind parameters: 'ii' means two integers
            $stmt->bind_param('ii', $isEnable, $id);

            // Execute the query and check success
            if ($stmt->execute()) {
                // Return success response if the update was successful
                echo json_encode(['success' => true]);
            } else {
                // Return failure response if the query failed
                echo json_encode(['success' => false, 'message' => 'Database update failed.']);
            }

            $stmt->close();
        } else {
            // Return failure if preparing the statement failed
            echo json_encode(['success' => false, 'message' => 'Query preparation failed.']);
        }
        break;

    // Not in Use Only Code here
    case 'UpdateReminderStatus':
      $id = isset($_POST['id']);
      $status = 1;

      if ($id > 0) {
          // Prepare and bind
          $stmt = $conn->prepare("UPDATE event SET isAlertSend = ? WHERE id = ?");
          $stmt->bind_param("ii", $status, $id);

          if ($stmt->execute()) {
              echo 'success';
          } else {
              echo 'error';
          }

          $stmt->close();
      } else {
          echo 'invalid_id';
      }


    default:
      http_response_code(400);
      echo json_encode(['error' => 'Unknown action']);
      break;

     

}





$conn->close();


?>