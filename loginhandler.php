<?php


require 'data/dbconfig.php';
require 'vendor/autoload.php';



// ================ Login Code for that ========================== 
use Firebase\JWT\JWT;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Login") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection code (assuming $conn is the connection object)
    // $conn = new mysqli(...);

    // Determine if the input is an email or a name
    $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

    // Prepare the SQL statement to prevent SQL injection
    if ($isEmail) {
        // If it's an email, search by email
        $stmt = $conn->prepare("SELECT * FROM employee WHERE email = ? AND password = ?");
    } else {
        // Otherwise, search by name
        $stmt = $conn->prepare("SELECT * FROM employee WHERE name = ? AND password = ?");
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Update isOnline to 1 for the logged-in user
        $updateStmt = $conn->prepare("UPDATE employee SET isOnline = 1 WHERE id = ?");
        $updateStmt->bind_param("s", $row['id']);
        $updateStmt->execute();
        $updateStmt->close();

        // Insert a record into the login_report table
        $insertStmt = $conn->prepare("INSERT INTO login_report (loginId, name, date, fromtime, totime, status, designation) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $currentDate = date('Y-m-d'); // Current date
        $currentTime = date('H:i:s'); // Current time

        // Using the employee data
        $loginId = $row['id'];
        $name = $row['name'];
        $totime = null; // Assuming it's null at login
        $status = $row['isenable']; // Adjust based on your employee table
        $designation = $row['designation']; // Adjust based on your employee table

        $insertStmt->bind_param("issssss", $loginId, $name, $currentDate, $currentTime, $totime, $status, $designation);
        $insertStmt->execute();

        // Get the inserted ID
        $insertedId = $conn->insert_id; // Get the last inserted ID
        $insertStmt->close();

        if ($row['isenable'] == 1) {
            // Generate JWT token
            $key = "a8d123461b643452fc0b9c8186f80de25b4ab7e8769010d57d309f867fcfcf99";
            $issuedAt = time();
            $expirationTime = $issuedAt + 43200;  // jwt valid for 1 hour from the issued time
            $payload = array(
                "iat" => $issuedAt,      // Issued at: timestamp
                "nbf" => $issuedAt,
                "exp" => $expirationTime, // Expiration time                
                "data" => array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'role' => $row['role'],
                    'designation' => $row['designation']
                )
            );

            $token = JWT::encode($payload, $key, 'HS256'); // Encode the payload to generate the JWT token

            setcookie("token", $token, time() + 43200, "/", "", true, true);

            // Check the user's role and send the appropriate response
            if ($row['role'] == "client") {
                echo "client";
                exit;
            } else {
                echo "admin";
                exit;
            }
        } else {
            //echo json_encode(array("error" => "You are inactive. Please contact Admin....!"));
           echo "Wrong";
                exit;
        }
    } else {
        // Invalid credentials, send error response
        //echo json_encode(array("error" => "Invalid credentials"));
       echo "Wrong";
                exit;
    }
    $stmt->close();
}
