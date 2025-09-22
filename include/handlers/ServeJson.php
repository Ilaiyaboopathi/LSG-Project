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


// Directory where JSON files are stored
$jsonDir = '../../assets/json/';

// Get the requested file name from the query string
$requestedFile = isset($_GET['file']) ? $_GET['file'] : null;

// Validate the requested file name
if ($requestedFile && preg_match('/^[a-zA-Z0-9_-]+$/', $requestedFile)) {
    // Construct the full file path
    $jsonFilePath = $jsonDir . $requestedFile . '.json';

    // Check if the file exists
    if (file_exists($jsonFilePath)) {
        // Read the JSON file
        $jsonData = file_get_contents($jsonFilePath);

        // Set the content type to JSON
        header('Content-Type: application/json');

        // Output the JSON data
        echo $jsonData;
    } else {
        // Return a 404 error if the file doesn't exist
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'File not found']);
    }
} else {
    // Return a 400 error if the file name is invalid or missing
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Invalid or missing file parameter']);
}

?>