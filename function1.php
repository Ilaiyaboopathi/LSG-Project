<?php

date_default_timezone_set('Asia/Kolkata');
session_start();

require 'vendor/autoload.php';
require 'JWTValues.php';
require 'data/dbconfig.php';


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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$secretKey = 'a8d123461b643452fc0b9c8186f80de25b4ab7e8769010d57d309f867fcfcf99';

// Instantiate the JWTValues class
$jwtHandler = new JWTValues($secretKey);

// Decode the token and retrieve user data
$jwtHandler->decodeToken();

// Check if the user is logged in
//if ($jwtHandler->isLoggedIn()) {
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



// Start to SMTP mail function dynamically store to the database 

if(isset($_POST['submit'])){
    $host     = $conn->real_escape_string($_POST['host']);
    $port     = intval($_POST['port']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $secure   = $conn->real_escape_string($_POST['secure']);
     $from_email   = $conn->real_escape_string($_POST['from_email']);
    $hid      = intval($_POST['hid']);
    $submit   = $_POST['submit'];

    if($submit === 'AddSMTP'){
        // $sql = "INSERT INTO smtp_settings (host, port, username, password, SMTPSecure, from_email) 
        //         VALUES ('$host','$port','$username','$password','$secure' ,'$from_email')";
        // echo $conn->query($sql) ? 'Success' : $conn->error;

          // ✅ Check if record already exists
        $checkSql = "SELECT COUNT(*) AS total FROM smtp_settings";
        $checkRes = $conn->query($checkSql);
        $row = $checkRes->fetch_assoc();

        if($row['total'] >= 1){
            // Already one record exists
            echo "Limit Exceeded"; // frontend will show popup
        } else {
            // Insert new record
            $sql = "INSERT INTO smtp_settings (host, port, username, password, SMTPSecure, from_email) 
                    VALUES ('$host','$port','$username','$password','$secure','$from_email')";
            echo $conn->query($sql) ? 'Success' : $conn->error;
        }
    }
    elseif($submit === 'UpdateSMTP' && $hid > 0){
        $sql = "UPDATE smtp_settings SET 
                host='$host', port='$port', username='$username', password='$password', SMTPSecure='$secure'
                WHERE id=$hid";
        echo $conn->query($sql) ? 'updated' : $conn->error;
    }
}

//Start to SMTP mail function dynamically store to the database 

