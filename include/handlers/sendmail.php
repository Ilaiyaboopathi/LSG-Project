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






// Get user email from session
$fromEmail = $JWT_userEmail ?? 'default@example.com'; 

$recipients = $_POST['selectempoyees'] ?? [];


$adminName = $JWT_adminName ?? null;
if ($adminName) {
   
    $recipients[] = $adminName; 
}

$id=$_POST['hid'];
if (empty($id)) {
    $template = file_get_contents('Mail/AssignProject.html');
} else {
    $template = file_get_contents('Mail/UpdateProject.html');
}


$hstatus=$_POST['hstatus'];

// Get data from repeater
$repeaterData = $_POST['repeater'] ?? [];
$projectName = $_POST['name'] ?? 'Project';
$startDate = $_POST['date'] ?? 'N/A';
$endDate = $_POST['time'] ?? 'N/A'; 
$taskType = $_POST['platform'] ?? 'N/A';
//$status = $_POST['status'] ?? 'N/A'; 
$assignedBy = $JWT_adminName ?? 'Manager'; // Adjust according to your session
$projectDescription = $_POST['details'] ?? 'No description provided.';
$assignedPerson =  $JWT_adminName?? 'Your Name'; // Adjust accordingly
$designation =  $JWT_userDesignation  ?? 'Your Designation';

if (empty($id)) {
    $sub = 'New Project Assigned:' . $projectName;
} else {
    $sub = 'Project Updated:' . $projectName;
}

// Send emails to each recipient
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'taskenginembw@gmail.com';
    $mail->Password = 'dwed lrmz jzue bsml';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('taskenginembw@gmail.com', 'Project Management');
    
    foreach ($recipients as $recipientName) {
        // Get email of recipient from database
        $sql = "SELECT email FROM employee WHERE name = '$recipientName'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $assignedToEmail = $row['email'];

            // Prepare email body
            $emailBody = $template;
            $emailBody = str_replace('##ProjectName##', $projectName, $emailBody);
            $emailBody = str_replace('##EmployeeName##', $recipientName, $emailBody);
            $emailBody = str_replace('##FinalDate##', $startDate, $emailBody);
            $emailBody = str_replace('##FinalTime##', $endDate, $emailBody);
            $emailBody = str_replace('##TaskType##', $taskType, $emailBody);
            $emailBody = str_replace('##Status##', $hstatus, $emailBody);
            $emailBody = str_replace('##TaggedEmployees##', implode(', ', $recipients), $emailBody);
            $emailBody = str_replace('##AssignedBy##', $assignedBy, $emailBody);
            $emailBody = str_replace('##ProjectDescription##', $projectDescription, $emailBody);
            $emailBody = str_replace('##AssignedPerson##', $assignedPerson, $emailBody);
            $emailBody = str_replace('##Designation##', $designation, $emailBody);

            // Send email
            $mail->addAddress($assignedToEmail);
            $mail->isHTML(true);
            $mail->Subject = $sub;
            $mail->Body = $emailBody;

            $mail->send();
            $mail->clearAddresses(); // Clear address for the next iteration
           
        } else {
            echo "Email not found for: {$recipientName}\n";
        }
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
}
?>
