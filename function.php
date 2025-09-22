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
// } else {
//     // If the user is not logged in, redirect to login
//     header('HTTP/1.1 403 Forbidden');
//     exit();
// }


// =====================================JWT Validation End=====================================================



// if (!isset($JWT_adminName)) {
//     header('HTTP/1.1 403 Forbidden');
//     echo json_encode(['error' => 'Access denied']);
//     exit;
// }



// ================================  Validatation pass It will work Followign Code  ====================================================






// Add Sales Task Submit

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "1") {

    try {
        // Gather input data
        $name = $_POST['name'];
        $customertype = $_POST['customertype'];
        $phone = $_POST['phone'];
        $platform = $_POST['platform'];
        $details = $_POST['details'];
        $time = $_POST['time'];
        $selectempoyees = $JWT_adminName . ',' . $_POST['selectempoyees'];
        $userName = $JWT_adminName;
        $status = $_POST['status'];
        $date = new DateTime($_POST['date']);

        // Format the date and timestamp
        $formattedDate = $date->format('Y-m-d');
        $timestamp = (new DateTime("$formattedDate $time"))->format('Y-m-d H:i:s');
        $newGuid = uniqid('', true);
        $taskcreatedon = date('Y-m-d H:i:s');
        $assignedBy = $JWT_adminName;
        $assignedDesignation = $JWT_userDesignation;

        // Prepare the insert statement for the event table
        $sql = "INSERT INTO event 
            (name, customertype, phone, platform, details, date, time, timestamp, tagemployee, assignedBy, status, task_id, createdOn,lastUpdatedPerson,lastUpdatedDesignation) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "Error preparing event statement: " . $conn->error;
            exit;
        }

        // Bind parameters for the event insert
        $stmt->bind_param('sssssssssssssss', $name, $customertype, $phone, $platform, $details, $formattedDate, $time, $timestamp, $selectempoyees, $userName, $status, $newGuid, $taskcreatedon, $assignedBy, $assignedDesignation);

        // Execute the statement
        if ($stmt->execute()) {
            // Insert tasks for each employee
            $employees = explode(',', $selectempoyees);
            foreach ($employees as $employee) {
                $employee = trim($employee);
                $createdon = date('Y-m-d H:i:s');

                $insertTask = "INSERT INTO task 
                    (taskid, taskName, assignedBy, employeeName, platform, date, time, timestamp, isAlertsend, createdOn) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmtTask = $conn->prepare($insertTask);
                if (!$stmtTask) {
                    echo "Error preparing task statement: " . $conn->error;
                    continue; // Skip to the next employee
                }

                // Assign 0 to a variable
                $isAlertSend = 0;

                // Bind parameters for task insert
                $stmtTask->bind_param('ssssssssss', $newGuid, $name, $userName, $employee, $platform, $formattedDate, $time, $timestamp, $isAlertSend, $createdon);

                // Execute the task statement
                if (!$stmtTask->execute()) {
                    echo "Error inserting task for employee $employee: " . $stmtTask->error . "<br>";
                }

                $stmtTask->close();
            }
        } else {
            echo "Error inserting event: " . $stmt->error . "<br>";
        }

        // Close the event statement
        $stmt->close();

        // Insert into task_descriptions
        $createdon = date('Y-m-d H:i:s');
        $insertSql = "INSERT INTO task_descriptions (taskid, taskName, platform, details, status, date, time, createdon, addedBy, assignedBy) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtDesc = $conn->prepare($insertSql);
        if (!$stmtDesc) {
            echo "Error preparing task description statement: " . $conn->error;
            exit;
        }

        // Bind parameters for task description insert
        $stmtDesc->bind_param('ssssssssss', $newGuid, $name, $platform, $details, $status, $formattedDate, $time, $createdon, $userName, $userName);

        if ($stmtDesc->execute()) {
            echo "success";
        } else {
            echo "Error inserting task description: " . $stmtDesc->error . "<br>";
        }

        // Close the task descriptions statement
        $stmtDesc->close();

        // Close the database connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "2") {
    // Retrieve and sanitize form data
    $name = $conn->real_escape_string($_POST['name']);
    $customertype = $conn->real_escape_string($_POST['customertype']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $platform = $conn->real_escape_string($_POST['platform']);
    $details = $_POST['details'];
    $time = $conn->real_escape_string($_POST['time']);
    $assignedBy = $conn->real_escape_string($_POST['hemail']);
    $hid = intval($_POST['hid']);
    $selectempoyees = $conn->real_escape_string($_POST['selectempoyees']);
    $status = $conn->real_escape_string($_POST['status']);
    $hguid = $conn->real_escape_string($_POST['hguid']);

    $date = new DateTime($_POST['date']);
    $taskcreatedon = date('Y-m-d H:i:s');
    $formattedDate = $date->format('Y-m-d');
    $timestamp = (new DateTime("$formattedDate $time"))->format('Y-m-d H:i:s');

    $LastAssignedBy = $JWT_adminName;
    $LastAssignedDesignation = $JWT_userDesignation;
    $istaskUpdated = 1;
    $selectSql = "SELECT details, status, isUpdated FROM event WHERE ID = $hid";
    $result = $conn->query($selectSql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $oldDetails = $row['details'];
        $oldStatus = $row['status'];
        $isUpdated = (int)$row['isUpdated'];

        $deleteSql = "DELETE FROM task WHERE taskid = '$hguid'";
        if ($conn->query($deleteSql)) {
            $updateSql = "UPDATE event SET 
                name = ?, 
                customertype = ?, 
                phone = ?, 
                platform = ?, 
                details = ?, 
                date = ?, 
                time = ?, 
                timestamp = ?, 
                tagemployee = ?, 
                assignedBy = ?, 
                status = ?, 
                task_id = ? ,
                isUpdated = ?,
                lastUpdatedPerson = ?, 
                lastUpdatedDesignation = ? 
                WHERE id = ?";

            // Prepare the statement
            $stmt = $conn->prepare($updateSql);
            if (!$stmt) {
                echo "Error preparing update statement: " . $conn->error;
                exit;
            }

            // Bind parameters for the update
            $stmt->bind_param('sssssssssssssssi', $name, $customertype, $phone, $platform, $details, $formattedDate, $time, $timestamp, $selectempoyees, $assignedBy, $status, $hguid, $istaskUpdated, $LastAssignedBy, $LastAssignedDesignation, $hid);
            if ($stmt->execute()) {
                $employees = explode(',', $selectempoyees);
                foreach ($employees as $employee) {
                    $employee = trim($employee);
                    $createdon = date('Y-m-d H:i:s');

                    $insertTask = "INSERT INTO task 
                        (taskid, taskName, assignedBy, employeeName, platform, date, time, timestamp, isAlertsend, createdOn) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmtTask = $conn->prepare($insertTask);
                    if (!$stmtTask) {
                        echo "Error preparing task statement: " . $conn->error;
                        continue; // Skip to the next employee
                    }

                    // Assign 0 to a variable
                    $isAlertSend = 0;

                    // Bind parameters for task insert
                    $stmtTask->bind_param('ssssssssss', $hguid, $name, $assignedBy, $employee, $platform, $formattedDate, $time, $timestamp, $isAlertSend, $createdon);

                    // Execute the task statement
                    if (!$stmtTask->execute()) {
                        echo "Error inserting task for employee $employee: " . $stmtTask->error . "<br>";
                    }

                    $stmtTask->close();
                }
            } else {
                echo "Error updating record: " . $stmt->error;
            }

            $stmt->close();
        }

        // Check if details were changed and insert into task_descriptions
        $userName = $JWT_adminName;
        if ($oldDetails !== $details || $oldStatus !== $status) {
            $createdon = date('Y-m-d H:i:s'); // Current timestamp

            // Use prepared statements for safety
            $insertSql = "INSERT INTO task_descriptions 
                (taskid, taskName, platform, details, status, date, time, createdon, addedBy, assignedBy) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtDesc = $conn->prepare($insertSql);
            if (!$stmtDesc) {
                echo "Error preparing task description statement: " . $conn->error;
                exit;
            }

            // Bind parameters for task description insert
            $stmtDesc->bind_param('ssssssssss', $hguid, $name, $platform, $details, $status, $formattedDate, $time, $createdon, $userName, $assignedBy);

            if ($stmtDesc->execute()) {
                // echo "Task description updated successfully!";
            } else {
                echo "Error inserting into task_descriptions: " . $stmtDesc->error;
            }

            $stmtDesc->close();
            // echo "Details have changed. Proceeding to insert.";
        }
        echo "updated";
    }

    $conn->close();
}




if (isset($_POST['logout'])) {
    session_start();

    // Check if the user is logged in
    if (isset($JWT_adminName)) {

        $updateStmt = $conn->prepare("UPDATE employee SET isOnline = 0 WHERE email = ?");
        $updateStmt->bind_param("s", $JWT_userEmail);
        $updateStmt->execute();
        $updateStmt->close();

        // Get the LoginID from the session
        $loginId = $JWT_userID;
        $currentTime = date('H:i:s'); // Current time


        $updateTimeStmt = $conn->prepare("UPDATE login_report SET totime = ? WHERE id = ?");
        $updateTimeStmt->bind_param("si", $currentTime, $loginId);
        $updateTimeStmt->execute();
        $updateTimeStmt->close();
    }

    // Clear session data
    $_SESSION = array();
    session_destroy();
    session_unset();


    //Clear Token From Cookies
    setcookie("token", "", time() - 3600, "/", "", true, true );

    // Redirect to the home page
    header("Location: https://mbwit.net/");
    exit;
}

use Shuchkin\SimpleXLSX;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "bulkUpload") {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];

        // Load the Excel file
        if ($xlsx = SimpleXLSX::parse($fileTmpPath)) {
            $totalRows = count($xlsx->rows()) - 1;
            $_SESSION['totalRows'] = $totalRows;
            $_SESSION['processedRows'] = 0;

            $Total_query = "SELECT COUNT(*) AS totalEmployees FROM employee";
            $Tatal_result = $conn->query($Total_query);

            if ($Tatal_result) {
                $row = $Tatal_result->fetch_assoc();
                $totalEmployees = $row['totalEmployees'];
            }

            $dbPlusXceltotalRows = $totalEmployees +  $totalRows;

            if ($totalEmployees >= 10) {
                echo json_encode(['status' => 'error', 'message' => 'User license is exhausted. Please buy more or contact MBW']);
                exit; // Stop further execution if the limit is reached
            } else {

                // Prepare SQL statement for inserting data
                $stmt = $conn->prepare("INSERT INTO employee (name, email, department, designation, mobile, address, role, isenable, password, addedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertCount = 0;

                foreach ($xlsx->rows() as $key => $row) {
                    if ($key > 0) {
                        $name = $row[0];
                        $email = $row[1];
                        $department = $row[2];
                        $designation = $row[3];
                        $mobile = $row[4];
                        $address = $row[5];
                        $role = $row[6];
                        $isenable = (isset($row[7]) && $row[7] === 'Enable') ? 1 : 0;
                        $password = generateRandomString(8);
                        $addedBy = $JWT_adminName;

                        // Check for existing records with the same name, email, or phone
                        $checkQuery = "SELECT COUNT(*) FROM employee WHERE name = ? OR email = ? OR mobile = ?";
                        $checkStmt = $conn->prepare($checkQuery);
                        $checkStmt->bind_param("sss", $name, $email, $mobile);
                        $checkStmt->execute();
                        $checkStmt->bind_result($existingCount);
                        $checkStmt->fetch();
                        $checkStmt->close();

                        // Skip the record if a duplicate exists
                        if ($existingCount > 0) {
                            continue; // Skip this row and don't insert it
                        }

                        // Bind parameters and execute if no duplicates found
                        $stmt->bind_param("ssssssssss", $name, $email, $department, $designation, $mobile, $address, $role, $isenable, $password, $addedBy);
                        if ($stmt->execute()) {
                            $insertCount++;
                        }

                        $_SESSION['processedRows'] = $key;
                    }
                }

                // Final response after all insertions
                $stmt->close();

                // Once done, send the final success response
                $response = [
                    'status' => 'success',
                    'count' => $insertCount
                ];
                echo json_encode($response);  // Send back success response

            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error parsing the Excel file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or there was an upload error.']);
    }
    exit; // Stop further execution after handling the upload
}



// Handle progress check request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'getProgress') {

    if (isset($_SESSION['totalRows']) && isset($_SESSION['processedRows'])) {
        $totalRows = $_SESSION['totalRows'];
        $processedRows = $_SESSION['processedRows'];
        $progress = ($processedRows / $totalRows) * 100;

        echo json_encode([
            'progress' => $progress
        ]);
    } else {
        echo json_encode([
            'progress' => 0
        ]);
    }
    exit; // Stop further execution after handling the progress check
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Addemp") {

    try {

        // Assuming $conn is your MySQLi connection
        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $mobile = $_POST['mobile'];
        $role = $_POST['role'];
        $status = $_POST['status'];
        //echo($status);
        $address = $_POST['address'];
        $assignedBy = $JWT_adminName;
        $assignedDesignation = $JWT_userDesignation;
        $password = generateRandomString(8);

        //$file = isset($_FILES['file']);

        


        $Total_query = "SELECT COUNT(*) AS totalEmployees FROM employee";
        $Tatal_result = $conn->query($Total_query);


        if ($Tatal_result) {
            $row = $Tatal_result->fetch_assoc();
            $totalEmployees = $row['totalEmployees'];
        }

        if ($totalEmployees >= 10) {
            echo "Limit Exhausted";
            exit(); 
        }



        //$template = file_get_contents('Mail\AddEmployee.html');
        $filePath = __DIR__ . '/Mail/AddEmployee.html';
        if (file_exists($filePath)) {
            $template = file_get_contents($filePath);
        }



        // Replace placeholders in the template
        $template = str_replace('##Name##', $name, $template);
        $template = str_replace('##Email##', $email, $template);
        $template = str_replace('##Password##', $password, $template);
        $template = str_replace('##AssignedBy##', $assignedBy, $template);
        $template = str_replace('##AssignedDesignation##', $assignedDesignation, $template);
        $Body_message = $template;

        $mail = new PHPMailer(true);

                        // Server settings
                        // $mail->isSMTP();
                        // $mail->SMTPDebug = false; // Disable debugging for production
                        // $mail->Host = 'smtp.gmail.com';
                        // $mail->Port = 587;
                        // $mail->SMTPAuth = true;
                        // $mail->Username = 'taskenginembw@gmail.com';
                        // $mail->Password = 'dwed lrmz jzue bsml';
                        // $mail->SMTPSecure = 'tls'; // Use TLS encryption, `ssl` also accepted

                        // // Recipients
                        // $mail->setFrom('taskenginembw@gmail.com', 'Task manager');
                        // $mail->addAddress($email);

           // ✅ Fetch SMTP settings (latest row or ID=1)

            $sql = "SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $smtp = $result->fetch_assoc();
            } else {
                throw new Exception("SMTP settings not found in database.");
            }

            // ✅ SMTP config
            $mail->isSMTP();
            $mail->SMTPDebug = false;
            $mail->Host       = $smtp['host'];
            $mail->Port       = $smtp['port'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp['username'];
            $mail->Password   = $smtp['password'];
            $mail->SMTPSecure = $smtp['SMTPSecure'];

            // ✅ Sender & Recipient
            $mail->setFrom($smtp['from_email'], 'Task Manager');
            $mail->addAddress($email); // $email = recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Task manager';
        $mail->Body    = $Body_message;

        // Send the email
        if ($mail->send()) {
            // Prepare the SQL query with properly quoted values

            $FindNameSql = "SELECT name FROM employee WHERE name = '$name'";
            $FindPhoneSql = "SELECT mobile FROM employee WHERE mobile = '$mobile'";  // Corrected $mobile
            $FindEmailSql = "SELECT email FROM employee WHERE email = '$email'";  // Corrected $email

            // Execute the queries
            $NameResult = $conn->query($FindNameSql);
            $PhoneResult = $conn->query($FindPhoneSql);
            $EmailResult = $conn->query($FindEmailSql);

            // Initialize an array to hold the duplicate conditions
            $duplicates = array();

            // Check each condition and add to the duplicates array if found
            if ($NameResult->num_rows > 0) {
                $duplicates[] = "duplicate name";
            }

            if ($PhoneResult->num_rows > 0) {
                $duplicates[] = "duplicate mobile";
            }

            if ($EmailResult->num_rows > 0) {
                $duplicates[] = "duplicate email";
            }

            // If any duplicates are found, send the response
            if (count($duplicates) > 0) {
                echo implode(", ", $duplicates); // Join the array items with a comma and output
            } else {

                $profile_filePath = '';

                if (isset($_FILES['file'])) {
                    $file = $_FILES['file'];

                    // Check if there was an error during file upload
                    if ($file['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception('File upload error: ' . $file['error']);
                    }

                    // Get the file extension and name
                    $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

                    // Generate a random string for uniqueness
                    $randomString = bin2hex(random_bytes(4)); // 8 characters

                    $fileName = $name . '_' . $randomString;

                    $uploadDir = 'uploads/profiles/';
                    
                    // Ensure the upload directory exists and is writable
                    if (!is_dir($uploadDir)) {
                        throw new Exception('Upload directory does not exist.');
                    }

                    if (!is_writable($uploadDir)) {
                        throw new Exception('Upload directory is not writable.');
                    }

                    // Define the file path
                    $profile_filePath = $uploadDir . $fileName . '.' . $File_ext;

                    // Move the uploaded file to the server
                    if (!move_uploaded_file($file['tmp_name'], $profile_filePath)) {
                        throw new Exception('Failed to move uploaded file.');
                    }
                } 
                // else {
                //     throw new Exception('No file uploaded or file input name is incorrect.');
                // }



                $uuid = uniqid('', true);
                $sql = "INSERT INTO employee 
                        (id, name, email,department,designation,mobile,address,role,isenable,password,picture,addedBy) 
                        VALUES 
                        ('$uuid', '$name', '$email','$department','$designation','$mobile','$address','$role','$status','$password','$profile_filePath','$assignedBy')";

                // Execute the statement
                if ($conn->query($sql) === TRUE) {
                    //$last_id = $conn->insert_id;

                    //---------- For Insert Permission for Permission table to the User Start-----------//

                    $permissions = [
                        'number_of_employees',
                        'number_of_active_employees',
                        'inactive_employees',
                        'number_of_admin',
                        'active_admin',
                        'inactive_admin',
                        'today_task',
                        'task_for_next_6_days',
                        'task_for_7th_to_31st_day',
                        'TotalTask',
                        'TaskPending',
                        'TaskFollowUp',
                        'TaskCompleted',
                        'TaskNotInterested',
                        'project_all_time',
                        'pending_project',
                        'extended_project',
                        'completed_project',
                        'reminder_count',
                        'document_count',
                        'AddNewEmployee',
                        'BulkUser',
                        'UserRoles',
                        'AddNewSaleTask',
                        'TaskReply',
                        'AddNewProject',
                        'ProjectReply',
                        'AddNewReminder',
                        'ReminderViews',
                        'AddNewDocument',
                        'DocumentViews',
                        'SettingSalesTask',
                        'SettingTaskViews',
                        'SettingAddDepartment',
                        'SettingDepartmentViews',
                        'SettingAddDesignation',
                        'SettingDesignationView',
                        'SettingAddLogo',
                        'SettingLogoView',
                        'ReportDownloadAccess',
                        'ReportEmployeeAccess',
                        'ReportSalesTaskAccess',
                        'ReportProjectTaskAccess',
                        'ReportReminderAccess',
                        'ReportLogAccess',
                        'ProfilePicAdd',
                        'ChangePassword',
                        'UserExcel',
                        'SalesExcel',
                        'ProjectExcel',
                        'ReminderExcel',
                        'LogExcel',
                        'DeleteDocuments',
                        'DeleteLog'
                    ];

                    $permission_value = (in_array($role, ["client", "employee"])) ? 'Disable' : 'Enable';

                    // Prepare the values to bind to the statement
                    $values = array_merge([$uuid], array_fill(0, count($permissions), $permission_value));

                    // Generate the SQL query dynamically
                    $sql = "INSERT INTO permissions (userID, " . implode(', ', $permissions) . ") VALUES (?," . str_repeat('?,', count($permissions) - 1) . "?)";

                    // Prepare the statement
                    $stmt = $conn->prepare($sql);

                    // Dynamically create the bind_param format string
                    $bind_param_format = 's'; // Start with 's' for userID (UUID) parameter
                    foreach ($permissions as $permission) {
                        $bind_param_format .= 's'; // Add 's' for each permission field, assuming they are all strings
                    }

                    // Bind the parameters
                    $stmt->bind_param($bind_param_format, ...$values);

                    //---------- For Insert Permission for Permission table to the User Start-----------//

                    if ($stmt->execute()) {
                        echo "success";
                    }
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                    echo "Error: " . $error_message;
                }
            }


            $conn->close();
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Updateemp") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $designation = $_POST['designation'];
    $department = $_POST['department'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $hid = $_POST['hid'];
    $hpass = $_POST['hpass'];
    $assignedBy = $JWT_adminName;
    // Validate and sanitize inputs
    $updateID = filter_var($hid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Sanitize inputs to prevent SQL injection
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $department = $conn->real_escape_string($department);
    $designation = $conn->real_escape_string($designation);
    $mobile = $conn->real_escape_string($mobile);
    $role = $conn->real_escape_string($role);
    $status = $conn->real_escape_string($status);
    $address = $conn->real_escape_string($address);

    

    $profile_filePath = '';

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Check if there was an error during file upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }

        // Get the file extension and name
        $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Generate a random string for uniqueness
        //$randomString = bin2hex(random_bytes(4)); // 8 characters

        $fileName = $name ;

        $uploadDir = 'uploads/profiles/';
        
        // Ensure the upload directory exists and is writable
        if (!is_dir($uploadDir)) {
            throw new Exception('Upload directory does not exist.');
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('Upload directory is not writable.');
        }

        // Define the file path
        $profile_filePath = $uploadDir . $fileName . '.' . $File_ext;
        if (move_uploaded_file($file['tmp_name'], $profile_filePath)) {
            //echo "File uploaded successfully: " . $profile_filePath;
        } else {
            echo "Error moving uploaded file.";
            exit;
        }
    }
    $FindNameSql = "SELECT name FROM employee WHERE name = '$name' && id != '$hid'";
    $NameResult = $conn->query($FindNameSql);

    if ($NameResult->num_rows > 0) {
        echo "duplicate";
    } else {
        // Construct the SQL query
        $sql = "UPDATE `employee` SET 
        `name` = '$name', 
        `email` = '$email',
        `department` = '$department',
        `designation` = '$designation',
        `mobile` = '$mobile',
        `role` = '$role',
        `isenable` = '$status',
        `address` = '$address',
        `password` = '$hpass',
        `addedBy` = '$assignedBy',
         `picture` = '$profile_filePath'
        WHERE `id` = '$updateID'";



        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
          
          // ---------------- MAIL PART (refer Addemp) ----------------
            $filePath = __DIR__ . '/Mail/UpdateEmployee.html';
            if (file_exists($filePath)) {
                $template = file_get_contents($filePath);
            } else {
                $template = "<p>Hi ##Name##, your profile has been updated.</p>";
            }

            // Replace placeholders
            $template = str_replace('##Name##', $name, $template);
            $template = str_replace('##Email##', $email, $template);
            $template = str_replace('##Password##', $hpass, $template);
            $template = str_replace('##AssignedBy##', $assignedBy, $template);
            //$template = str_replace('##AssignedDesignation##', $assignedDesignation, $template);
            $Body_message = $template;

            try {
                $mail = new PHPMailer(true);



                // $mail->isSMTP();
                // $mail->SMTPDebug = false;
                // $mail->Host = 'smtp.gmail.com';
                // $mail->Port = 587;
                // $mail->SMTPAuth = true;
                // $mail->Username = 'taskenginembw@gmail.com';
                // $mail->Password = 'dwed lrmz jzue bsml';
                // $mail->SMTPSecure = 'tls';

                // $mail->setFrom('taskenginembw@gmail.com', 'Task Manager');
                // $mail->addAddress($email);

                   // ✅ Fetch SMTP settings (latest row or ID=1)
                    $sql = "SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $smtp = $result->fetch_assoc();
                    } else {
                        throw new Exception("SMTP settings not found in database.");
                    }

                    // ✅ SMTP config
                    $mail->isSMTP();
                    $mail->SMTPDebug = false;
                    $mail->Host       = $smtp['host'];
                    $mail->Port       = $smtp['port'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtp['username'];
                    $mail->Password   = $smtp['password'];
                    $mail->SMTPSecure = $smtp['SMTPSecure'];

                    // ✅ Sender & Recipient
                    $mail->setFrom($smtp['username'], 'Task Manager');
                    $mail->addAddress($email); // $email = recipient


                $mail->isHTML(true);
                $mail->Subject = 'Profile Updated - Task Manager';
                $mail->Body    = $Body_message;

                $mail->send();
            } catch (Exception $e) {
                error_log("Mail Error: " . $e->getMessage());
            }
            // ---------------- END MAIL ----------------
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}

//===============================add client page =================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Addcli") {

    try {

        // Assuming $conn is your MySQLi connection
        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $designation = "client";
        $department = "client";
        $mobile = $_POST['mobile'];
        $role = "client";
        $ref = $_POST['ref'];
        $ref_name = $_POST['ref_name'];
        $status = "1";
        //echo($status);
        $address = $_POST['address'];
        $assignedBy = $JWT_adminName;
        $assignedDesignation = $JWT_userDesignation;
        $password = generateRandomString(8);

        //$file = isset($_FILES['file']);

        // $Total_query = "SELECT COUNT(*) AS totalEmployees FROM employee";
        // $Tatal_result = $conn->query($Total_query);


        // if ($Tatal_result) {
        //     $row = $Tatal_result->fetch_assoc();
        //     $totalEmployees = $row['totalEmployees'];
        // }

        // if ($totalEmployees >= 10) {
        //     echo "Limit Exhausted";
        //     exit(); 
        // }


        //$template = file_get_contents('Mail\AddClient.html');
        $filePath = __DIR__ . '/Mail/AddClient.html';
        if (file_exists($filePath)) {
            $template = file_get_contents($filePath);
        }



        // Replace placeholders in the template
        $template = str_replace('##Name##', $name, $template);
        $template = str_replace('##Email##', $email, $template);
        $template = str_replace('##Password##', $password, $template);
        $template = str_replace('##AssignedBy##', $assignedBy, $template);
        $template = str_replace('##AssignedDesignation##', $assignedDesignation, $template);
        $Body_message = $template;

        $mail = new PHPMailer(true);
        // Server settings
        // $mail->isSMTP();
        // $mail->SMTPDebug = false; // Disable debugging for production
        // $mail->Host = 'smtp.gmail.com';
        // $mail->Port = 587;
        // $mail->SMTPAuth = true;
        // $mail->Username = 'taskenginembw@gmail.com';
        // $mail->Password = 'dwed lrmz jzue bsml';
        // $mail->SMTPSecure = 'tls'; // Use TLS encryption, `ssl` also accepted

        // // Recipients
        // $mail->setFrom('taskenginembw@gmail.com', 'Task manager');
        // $mail->addAddress($email);


                        // ✅ Fetch SMTP settings (latest row or ID=1)
                    $sql = "SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $smtp = $result->fetch_assoc();
                    } else {
                        throw new Exception("SMTP settings not found in database.");
                    }

                    // ✅ SMTP config
                    $mail->isSMTP();
                    $mail->SMTPDebug = false;
                    $mail->Host       = $smtp['host'];
                    $mail->Port       = $smtp['port'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtp['username'];
                    $mail->Password   = $smtp['password'];
                    $mail->SMTPSecure = $smtp['SMTPSecure'];

                    // ✅ Sender & Recipient
                    $mail->setFrom($smtp['username'], 'Task Manager');
                    $mail->addAddress($email); // $email = recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Task manager';
        $mail->Body    = $Body_message;

        // Send the email
        if ($mail->send()) {
            // Prepare the SQL query with properly quoted values

            $FindNameSql = "SELECT name FROM employee WHERE name = '$name'";
            $FindPhoneSql = "SELECT mobile FROM employee WHERE mobile = '$mobile'";  // Corrected $mobile
            $FindEmailSql = "SELECT email FROM employee WHERE email = '$email'";  // Corrected $email

            // Execute the queries
            $NameResult = $conn->query($FindNameSql);
            $PhoneResult = $conn->query($FindPhoneSql);
            $EmailResult = $conn->query($FindEmailSql);

            // Initialize an array to hold the duplicate conditions
            $duplicates = array();

            // Check each condition and add to the duplicates array if found
            if ($NameResult->num_rows > 0) {
                $duplicates[] = "duplicate name";
            }

            // if ($PhoneResult->num_rows > 0) {
            //     $duplicates[] = "duplicate mobile";
            // }

            if ($EmailResult->num_rows > 0) {
                $duplicates[] = "duplicate email";
            }

            // If any duplicates are found, send the response
            if (count($duplicates) > 0) {
                echo implode(", ", $duplicates); // Join the array items with a comma and output
            } else {

                $profile_filePath = '';

                if (isset($_FILES['file'])) {
                    $file = $_FILES['file'];

                    // Check if there was an error during file upload
                    if ($file['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception('File upload error: ' . $file['error']);
                    }

                    // Get the file extension and name
                    $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

                    // Generate a random string for uniqueness
                    $randomString = bin2hex(random_bytes(4)); // 8 characters

                    $fileName = $name . '_' . $randomString;

                    $uploadDir = 'uploads/profiles/';
                    
                    // Ensure the upload directory exists and is writable
                    if (!is_dir($uploadDir)) {
                        throw new Exception('Upload directory does not exist.');
                    }

                    if (!is_writable($uploadDir)) {
                        throw new Exception('Upload directory is not writable.');
                    }

                    // Define the file path
                    $profile_filePath = $uploadDir . $fileName . '.' . $File_ext;

                    // Move the uploaded file to the server
                    if (!move_uploaded_file($file['tmp_name'], $profile_filePath)) {
                        throw new Exception('Failed to move uploaded file.');
                    }
                } else {
                    // throw new Exception('No file uploaded or file input name is incorrect.');
                    $profile_filePath = '';
                }



                $uuid = uniqid('', true);
                $sql = "INSERT INTO employee 
                        (id, name, email,department,designation,mobile,address,role,isenable,password,picture,addedBy,ref,ref_name) 
                        VALUES 
                        ('$uuid', '$name', '$email','$department','$designation','$mobile','$address','$role','$status','$password','$profile_filePath','$assignedBy','$ref','$ref_name')";


                // Execute the statement
                if ($conn->query($sql) === TRUE) {
                    //$last_id = $conn->insert_id;

                    //---------- For Insert Permission for Permission table to the User Start-----------//

                    $permissions = [
                        'number_of_employees',
                        'number_of_active_employees',
                        'inactive_employees',
                        'number_of_admin',
                        'active_admin',
                        'inactive_admin',
                        'today_task',
                        'task_for_next_6_days',
                        'task_for_7th_to_31st_day',
                        'TotalTask',
                        'TaskPending',
                        'TaskFollowUp',
                        'TaskCompleted',
                        'TaskNotInterested',
                        'project_all_time',
                        'pending_project',
                        'extended_project',
                        'completed_project',
                        'reminder_count',
                        'document_count',
                        'AddNewEmployee',
                        'BulkUser',
                        'UserRoles',
                        'AddNewSaleTask',
                        'TaskReply',
                        'AddNewProject',
                        'ProjectReply',
                        'AddNewReminder',
                        'ReminderViews',
                        'AddNewDocument',
                        'DocumentViews',
                        'SettingSalesTask',
                        'SettingTaskViews',
                        'SettingAddDepartment',
                        'SettingDepartmentViews',
                        'SettingAddDesignation',
                        'SettingDesignationView',
                        'SettingAddLogo',
                        'SettingLogoView',
                        'ReportDownloadAccess',
                        'ReportEmployeeAccess',
                        'ReportSalesTaskAccess',
                        'ReportProjectTaskAccess',
                        'ReportReminderAccess',
                        'ReportLogAccess',
                        'ProfilePicAdd',
                        'ChangePassword',
                        'UserExcel',
                        'SalesExcel',
                        'ProjectExcel',
                        'ReminderExcel',
                        'LogExcel',
                        'DeleteDocuments',
                        'DeleteLog'
                    ];

                    $permission_value = (in_array($role, ["client", "employee"])) ? 'Disable' : 'Enable';

                    // Prepare the values to bind to the statement
                    $values = array_merge([$uuid], array_fill(0, count($permissions), $permission_value));

                    // Generate the SQL query dynamically
                    $sql = "INSERT INTO permissions (userID, " . implode(', ', $permissions) . ") VALUES (?," . str_repeat('?,', count($permissions) - 1) . "?)";

                    // Prepare the statement
                    $stmt = $conn->prepare($sql);

                    // Dynamically create the bind_param format string
                    $bind_param_format = 's'; // Start with 's' for userID (UUID) parameter
                    foreach ($permissions as $permission) {
                        $bind_param_format .= 's'; // Add 's' for each permission field, assuming they are all strings
                    }

                    // Bind the parameters
                    $stmt->bind_param($bind_param_format, ...$values);

                    //---------- For Insert Permission for Permission table to the User Start-----------//

                    if ($stmt->execute()) {
                        echo "success";
                    }
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                    echo "Error: " . $error_message;
                }
            }


            $conn->close();
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
//===============================add client page =================================================

//===============================update client page =================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Updatecli") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $designation = $_POST['designation'];
    $department = $_POST['department'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $role = "client";
    $status ="1";
    $hid = $_POST['hid'];
    $hpass = $_POST['hpass'];
    $ref = $_POST['ref'];
    $ref_name = $_POST['ref_name'];
    $assignedBy = $JWT_adminName;
    // Validate and sanitize inputs
    $updateID = filter_var($hid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Sanitize inputs to prevent SQL injection
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $department = $conn->real_escape_string($department);
    $designation = $conn->real_escape_string($designation);
    $mobile = $conn->real_escape_string($mobile);
    $role = $conn->real_escape_string($role);
    $status = $conn->real_escape_string($status);
    $address = $conn->real_escape_string($address);
    $ref = $conn->real_escape_string($ref);
    $ref_name = $conn->real_escape_string($ref_name);


    

    $profile_filePath = '';

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Check if there was an error during file upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }

        // Get the file extension and name
        $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Generate a random string for uniqueness
        //$randomString = bin2hex(random_bytes(4)); // 8 characters

        $fileName = $name ;

        $uploadDir = 'uploads/profiles/';
        
        // Ensure the upload directory exists and is writable
        if (!is_dir($uploadDir)) {
            throw new Exception('Upload directory does not exist.');
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('Upload directory is not writable.');
        }

        // Define the file path
        $profile_filePath = $uploadDir . $fileName . '.' . $File_ext;
        if (move_uploaded_file($file['tmp_name'], $profile_filePath)) {
            //echo "File uploaded successfully: " . $profile_filePath;
        } else {
            echo "Error moving uploaded file.";
            exit;
        }
    }
    $FindNameSql = "SELECT name FROM employee WHERE name = '$name' && id != '$hid'";
    $NameResult = $conn->query($FindNameSql);

    if ($NameResult->num_rows > 0) {
        echo "duplicate";
    } else {
        // Construct the SQL query
        $sql = "UPDATE `employee` SET 
        `name` = '$name', 
        `email` = '$email',
        `department` = '$department',
        `designation` = '$designation',
        `mobile` = '$mobile',
        `role` = '$role',
        `isenable` = '$status',
        `address` = '$address',
        `password` = '$hpass',
        `addedBy` = '$assignedBy',
          `ref` = '$ref',
           `ref_name` = '$ref_name',
         `picture` = '$profile_filePath'
        WHERE `id` = '$updateID'";



        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
          
           // ---------------- MAIL PART (refer Addemp) ----------------
            $filePath = __DIR__ . '/Mail/UpdateClient.html';
            if (file_exists($filePath)) {
                $template = file_get_contents($filePath);
            } else {
                $template = "<p>Hi ##Name##, your profile has been updated.</p>";
            }

            // Replace placeholders
            $template = str_replace('##Name##', $name, $template);
            $template = str_replace('##Email##', $email, $template);
            $template = str_replace('##Password##', $hpass, $template);
            $template = str_replace('##AssignedBy##', $assignedBy, $template);
            //$template = str_replace('##AssignedDesignation##', $assignedDesignation, $template);
            $Body_message = $template;

            try {
                $mail = new PHPMailer(true);


                // $mail->isSMTP();
                // $mail->SMTPDebug = false;
                // $mail->Host = 'smtp.gmail.com';
                // $mail->Port = 587;
                // $mail->SMTPAuth = true;
                // $mail->Username = 'taskenginembw@gmail.com';
                // $mail->Password = 'dwed lrmz jzue bsml';
                // $mail->SMTPSecure = 'tls';

                // $mail->setFrom('taskenginembw@gmail.com', 'Task Manager');
                // $mail->addAddress($email);

                                // ✅ Fetch SMTP settings (latest row or ID=1)
                    $sql = "SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $smtp = $result->fetch_assoc();
                    } else {
                        throw new Exception("SMTP settings not found in database.");
                    }

                    // ✅ SMTP config
                    $mail->isSMTP();
                    $mail->SMTPDebug = false;
                    $mail->Host       = $smtp['host'];
                    $mail->Port       = $smtp['port'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtp['username'];
                    $mail->Password   = $smtp['password'];
                    $mail->SMTPSecure = $smtp['SMTPSecure'];

                    // ✅ Sender & Recipient
                    $mail->setFrom($smtp['username'], 'Task Manager');
                    $mail->addAddress($email); // $email = recipient


                $mail->isHTML(true);
                $mail->Subject = 'Profile Updated - Task Manager';
                $mail->Body    = $Body_message;

                $mail->send();
            } catch (Exception $e) {
                error_log("Mail Error: " . $e->getMessage());
            }
            // ---------------- END MAIL ----------------
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}

//===============================update client page =================================================


function generateRandomString($length = 8)
{
    // Define the characters that will be used in the string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    // Generate the random string
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Addtask") {
    $task = $_POST['task'];
    
    $sql_check = "SELECT COUNT(*) FROM task_type WHERE type = '$task'";
    $result = $conn->query($sql_check);

        if ($result) {
            $row = $result->fetch_assoc();
            if ($row['COUNT(*)'] > 0) {
                // The task type already exists
                echo "duplicate";
            } else {
                // The task type does not exist, insert it
                $sql = "INSERT INTO task_type (type) VALUES ('$task')";

                if ($conn->query($sql) === TRUE) {
                    echo "success";
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                    echo "Error: " . $error_message;
                }
            }
        } else {
            echo "Error checking existing task type: " . $conn->error;
        }


    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Updatetask") {
    $task = $_POST['task'];
    $hid = $_POST['hid'];
    // Validate and sanitize inputs
    $updateID = intval($hid);
    $sql = "UPDATE `task_type` SET 
    `type` = '$task'
    
    WHERE `ID` = $updateID";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // echo $sql;
        echo "updated";
    } else {
        // Output SQL error
        echo "Error executing query: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
     $service = $_POST['service'] ?? null;
 $hiddenId = isset($_POST['hid']) ? intval($_POST['hid']) : 0;
  //  $hiddenId = intval($_POST['hid']);
    $submit = $_POST['submit'];

    if ($submit === 'Addservice') {
        // Add new service request
        $sql = "INSERT INTO service_req (service) VALUES ('$service')";
        if ($conn->query($sql)) {
            echo 'success';
        } else {
            echo 'Error adding service: ' . $conn->error;
        }
    } elseif ($submit === 'Updateservice') {
        // Update the existing service request
        $sql = "UPDATE service_req SET service = '$service' WHERE Id = $hiddenId";
        if ($conn->query($sql)) {
            echo 'updated';
        } else {
            echo 'Error updating service: ' . $conn->error;
        }
    }
}



// -----------------------New Concept created so not need-------------------------------

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddProject") {
//     try {
//         // Assuming $conn is your MySQLi connection
//         // Get form data
//         $pname = $conn->real_escape_string($_POST['name']);
//         $link = $conn->real_escape_string($_POST['linkurl']);
//         $platform = $conn->real_escape_string($_POST['platform']);
//         $details = $conn->real_escape_string($_POST['details']);
//         $emaildate = isset($_POST['date']) ? $_POST['date'] : '';
//         $time = $conn->real_escape_string($_POST['time']);
//         $selectempoyees = isset($_POST['selectempoyees']) ?
//             implode(',', array_map([$conn, 'real_escape_string'], $_POST['selectempoyees'])) : '';
//         $date = new DateTime($_POST['date']);
//         $mytime = new DateTime($time);
//         $formattedStrTime = $mytime->format('H:i:s');
//         $formattedDate = $date->format('Y-m-d');

//         $newGuid = uniqid('', true);
//         $assignedBy = $JWT_adminName;
//         $createdon = date('Y-m-d');
//         $currentTimestamp = time();
//         $currentTime = date('H:i:s', $currentTimestamp);
//         $currentDateTime = new DateTime();
//         $currentDateTime->add(new DateInterval('PT10S'));
//         $notifyTimeStamp = $currentDateTime->format('Y-m-d H:i:s');

//         $sql = "INSERT INTO project (name, type, details, assignedTo, assignedBy, deadlineDate, deadlineTime, linkurl, ProjectId, createdDate, notifyTimeStamp) 
//                 VALUES ('$pname', '$platform', '$details', '$selectempoyees', '$assignedBy', '$formattedDate', '$time', '$link', '$newGuid', '$createdon', '$notifyTimeStamp')";

//         $sql2 = "INSERT INTO assignproject (ProjectId, Name,ProjectName,Platform, DeadlineDate, DeadlineTime, Information) 
//                      VALUES ('$newGuid', '$assignedBy', '$pname','$platform','$formattedDate', '$time', '$details')";

//         // Insert new Row for Notify New Project
//         $sql3 = "INSERT INTO NotifyProject (ProjectId, Name,ProjectName, Platform, NotifiedDate, NotifiedTime, Information) 
//                             VALUES ('$newGuid', '$assignedBy', '$pname','$platform','$createdon', '$currentTime', '$details')";
//         // Handle repeated entries
//         foreach ($_POST['repeater'] as $item) {
//             $employeeName = $conn->real_escape_string($item['employeeName']);
//             $deadlineDate = $conn->real_escape_string($item['deadlineDate']);
//             $deadlineTime = $conn->real_escape_string($item['deadlineTime']);
//             $projectInfo = $conn->real_escape_string($item['projectInfo']);

//             $deaddate = new DateTime($deadlineDate);
//             $formattedDeadlineDate = $deaddate->format('Y-m-d');

//             if ($deadlineTime) {
//                 $time = DateTime::createFromFormat('H:i:s', $deadlineTime);
//                 if (!$time) {
//                     $time = DateTime::createFromFormat('H:i', $deadlineTime);
//                 }
//                 $formattedDeadlineTime = $time ? $time->format('H:i:s') : null;
//             } else {
//                 $formattedDeadlineTime = null;
//             }

//             $AssignAddQuery = "INSERT INTO assignproject (ProjectId, Name, ProjectName,Platform, DeadlineDate, DeadlineTime, Information) 
//                      VALUES ('$newGuid', '$employeeName','$pname','$platform', '$formattedDeadlineDate', '$formattedDeadlineTime', '$projectInfo')";

//             //Insert new Row for Notify New Project
//             $NewNotifyEmpQuery = "INSERT INTO NotifyProject (ProjectId, Name, ProjectName, Platform, NotifiedDate, NotifiedTime, Information) 
//                      VALUES ('$newGuid', '$employeeName','$pname','$platform', '$createdon', '$currentTime', '$projectInfo')";

//             $conn->query($AssignAddQuery);
//             $conn->query($NewNotifyEmpQuery); 
//         }

//         if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
//             $assignedToNames = explode(',', $selectempoyees);
//             $assignedToEmails = [];
//             $assignedByEmails = '';

//             foreach ($assignedToNames as $name) {
//                 $name = $conn->real_escape_string(trim($name));
//                 $result = $conn->query("SELECT email FROM employee WHERE name = '$name'");
//                 if ($result->num_rows > 0) {
//                     while ($row = $result->fetch_assoc()) {
//                         $assignedToEmails[] = $row['email'];
//                     }
//                 }
//             }

//             $assignedByName = $conn->real_escape_string($assignedBy);
//             $result = $conn->query("SELECT email FROM employee WHERE name = '$assignedByName'");
//             if ($result->num_rows > 0) {
//                 while ($row = $result->fetch_assoc()) {
//                     $assignedByEmails = $row['email'];
//                 }
//             }

//             // Prepare notification data
//             $notificationData = [
//                 'id' => $newGuid,
//                 'name' => $pname,
//                 'type' => $platform,
//                 'details' => $details,
//                 'deadlineDate' => $formattedDate,
//                 'deadlineTime' => $time,
//                 'assignedTo' => $selectempoyees,
//                 'assignedBy' => $assignedBy,
//                 'notifyTimeStamp' => $notifyTimeStamp,
//             ];

//             // Send response with notification data
//             echo json_encode([
//                 'status' => 'success',
//                 'ProjectName' => $pname,
//                 //'notification' => $notificationData,
//                 'ProjectId' => $newGuid,
//                 'assignedBy' => $assignedBy,
//             ]);
//         } else {
//             $error_message = "Error: " . $sql . "<br>" . $conn->error;
//             echo json_encode(['status' => 'error', 'message' => $error_message]);
//         }

//         $conn->close();
//     } catch (Exception $e) {
//         echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
//     }
// }


// -----------------------New Concept created so not need-------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddProject") {
    try {
        // Assuming $conn is your MySQLi connection
        $conn->autocommit(false); // Start transaction

        // Get form data
        $pname = $conn->real_escape_string($_POST['name']);
        $link = $conn->real_escape_string($_POST['linkurl']);
        $platform = $conn->real_escape_string($_POST['platform']);
        $details = $_POST['details'];
        $emaildate = isset($_POST['date']) ? $_POST['date'] : '';
        $time = $conn->real_escape_string($_POST['time']);
        $selectempoyees = isset($_POST['selectempoyees']) ? $_POST['selectempoyees'] : [];

        $date = new DateTime($emaildate);
        $mytime = new DateTime($time);
        $formattedStrTime = $mytime->format('H:i:s');
        $formattedDate = $date->format('Y-m-d');

        $newGuid = uniqid('', true);
        $assignedBy = $JWT_adminName;
        $createdon = date('Y-m-d');
        $currentTimestamp = time();
        $currentTime = date('H:i:s', $currentTimestamp);
        $currentDateTime = new DateTime();
        $currentDateTime->add(new DateInterval('PT10S'));
        $notifyTimeStamp = $currentDateTime->format('Y-m-d H:i:s');

        // Prepare the insert queries
        $sql = "INSERT INTO project (name, type, details, assignedTo, assignedBy, deadlineDate, deadlineTime, linkurl, ProjectId, createdDate, notifyTimeStamp)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssssss', $pname, $platform, $details, $selectempoyeesStr, $assignedBy, $formattedDate, $formattedStrTime, $link, $newGuid, $createdon, $notifyTimeStamp);

        $selectempoyeesStr = implode(',', $selectempoyees);
        $stmt->execute();

        $sql2 = "INSERT INTO assignproject (ProjectId, Name, ProjectName, Platform, DeadlineDate, DeadlineTime, Information) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);

        // Prepare insert for NotifyProject
        $sql3 = "INSERT INTO notifyproject (ProjectId, Name, ProjectName, Platform, NotifiedDate, NotifiedTime, Information)
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt3 = $conn->prepare($sql3);

        // Handle repeated entries (repeater items)
        foreach ($_POST['repeater'] as $item) {
            $employeeName = $conn->real_escape_string($item['employeeName']);
            $deadlineDate = new DateTime($item['deadlineDate']);
            $formattedDeadlineDate = $deadlineDate->format('Y-m-d');
            $deadlineTime = isset($item['deadlineTime']) ? $item['deadlineTime'] : null;
            $formattedDeadlineTime = ($deadlineTime) ? DateTime::createFromFormat('H:i', $deadlineTime)->format('H:i:s') : null;
            $projectInfo = $item['projectInfo'];

            $stmt2->bind_param('sssssss', $newGuid, $employeeName, $pname, $platform, $formattedDeadlineDate, $formattedDeadlineTime, $projectInfo);
            $stmt2->execute();

            $stmt3->bind_param('sssssss', $newGuid, $employeeName, $pname, $platform, $createdon, $currentTime, $projectInfo);
            $stmt3->execute();
        }

        // Get all emails in one go to optimize performance
        $assignedToNames = implode("','", array_map(function ($name) use ($conn) {
            return $conn->real_escape_string($name);
        }, $selectempoyees));

        $sqlEmails = "SELECT name, email FROM employee WHERE name IN ('$assignedToNames') OR name = ?";
        $stmtEmail = $conn->prepare($sqlEmails);
        $stmtEmail->bind_param('s', $assignedBy);
        $stmtEmail->execute();
        $emailResults = $stmtEmail->get_result();

        $assignedToEmails = [];
        $assignedByEmail = '';
        while ($row = $emailResults->fetch_assoc()) {
            if (in_array($row['name'], $selectempoyees)) {
                $assignedToEmails[] = $row['email'];
            } elseif ($row['name'] == $assignedBy) {
                $assignedByEmail = $row['email'];
            }
        }

        // Commit the transaction
        $conn->commit();

        // Return response with notification data
        echo json_encode([
            'status' => 'success',
            'ProjectName' => $pname,
            'ProjectId' => $newGuid,
            'assignedBy' => $assignedBy,
        ]);
    } catch (Exception $e) {
        // Rollback if error occurs
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    } finally {
        $conn->close();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateProject") {
    // Retrieve and sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $link = $conn->real_escape_string($_POST['linkurl']);
    $platform = $conn->real_escape_string($_POST['platform']);
    $details = $_POST['details'];
    //$date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $selectempoyees = isset($_POST['selectempoyees']) ?
        implode(',', array_map([$conn, 'real_escape_string'], $_POST['selectempoyees'])) : '';
    $hid = $conn->real_escape_string($_POST['hid']); // Project ID
    $hiddenGuid = $conn->real_escape_string($_POST['hiddenGuid']);
    $hiddenassigned = $conn->real_escape_string($_POST['hiddenassigned']);
    $updateID = intval($hid);
    $date = new DateTime($_POST['date']);
    $createdon = date('Y-m-d');
    $currentDateTime = new DateTime();
    $currentDateTime->add(new DateInterval('PT30S'));
    $notifyTimeStamp = $currentDateTime->format('Y-m-d H:i:s');

    $currentTimestamp = time();
    $currentTime = date('H:i:s', $currentTimestamp);

    // Format the date as needed
    $formattedDate = $date->format('Y-m-d');
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the project details
        $sql = "UPDATE `project` SET 
        `name` = '$name',
        `type` = '$platform',
        `details` = '$details',
        `assignedTo` = '$selectempoyees',
        `assignedBy` = '$hiddenassigned',
        `deadlineDate` = '$formattedDate',
        `deadlineTime` = '$time',
        `linkurl` = '$link',
        `createdDate` = '$createdon',
        `notifyTimeStamp` = '$notifyTimeStamp' 
        WHERE `id` = $updateID";

        $sql2 = "INSERT INTO assignproject (ProjectId, Name,ProjectName,Platform, DeadlineDate, DeadlineTime, Information) 
                     VALUES ('$hiddenGuid', '$hiddenassigned','$name','$platform', '$formattedDate', '$time', '$details')";

        // Insert new Row for Notify New Project
        $sql3 = "INSERT INTO NotifyProject (ProjectId, Name,ProjectName, Platform, NotifiedDate, NotifiedTime, Information) 
                    VALUES ('$hiddenGuid', '$hiddenassigned', '$name','$platform','$createdon', '$currentTime', '$details')";

        if ($conn->query($sql) === TRUE) {


            // Query to check for 'pending' values in the status column
            $checkPendingQuery = "SELECT COUNT(*) AS pending_count FROM assignproject WHERE ProjectId = '$hiddenGuid' AND SubTaskStatus = 'Pending'";
            $checkedResult = $conn->query($checkPendingQuery);

            if ($checkedResult) {
                $row = $checkedResult->fetch_assoc();
                if ($row['pending_count'] > 0) {

                    // Pending Rows founded So project is Still pending Start
                    $Project_stmt = "UPDATE project 
                                     SET ProjectStatus = 'Pending', CompletedDate = Null
                                     WHERE ProjectId = '$hiddenGuid'";

                    if ($conn->query($Project_stmt) === TRUE) {
                        //Nothing Happening
                    } else {
                        throw new Exception("Error Was Occurred from Update Whole Project Status : " . $conn->error);
                    }
                    // Pending Rows founded So project is Still pending End
                    // Delete existing assignment records
                    $deleteSql = "DELETE FROM assignproject WHERE ProjectId = '$hiddenGuid' AND SubTaskStatus = 'Pending'";

                    // Delete existing NotifyProject records
                    $deleteNotifySql = "DELETE FROM NotifyProject WHERE ProjectId = '$hiddenGuid'";

                    if ($conn->query($deleteSql) === TRUE && $conn->query($deleteNotifySql) === TRUE) {
                        // Initialize an empty array to hold repeater data
                        $conn->query($sql2);
                        $conn->query($sql3);
                        foreach ($_POST['repeater'] as $item) {

                            $employeeName = $conn->real_escape_string($item['employeeName']);
                            $deadlineDate = $conn->real_escape_string($item['deadlineDate']);
                            $deadlineTime = $conn->real_escape_string($item['deadlineTime']);
                            $projectInfo = $item['projectInfo'];



                            // Process the deadlineDate
                            if ($deadlineDate) {
                                $deaddate = new DateTime($deadlineDate);
                                $formattedDeadlineDate = $deaddate->format('Y-m-d');
                            } else {
                                $formattedDeadlineDate = null;
                                error_log("deadlineDate is not set.");
                            }

                            // Process the deadlineTime
                            if ($deadlineTime) {
                                try {
                                    $time = DateTime::createFromFormat('H:i:s', $deadlineTime);
                                    if (!$time) {
                                        $time = DateTime::createFromFormat('H:i', $deadlineTime);
                                    }
                                    if ($time) {
                                        $formattedDeadlineTime = $time->format('H:i:s');
                                    } else {
                                        $formattedDeadlineTime = null;
                                        error_log("Invalid time format: " . $deadlineTime);
                                    }
                                } catch (Exception $e) {
                                    $formattedDeadlineTime = null;
                                    error_log("Failed to parse deadlineTime: " . $e->getMessage());
                                }
                            } else {
                                $formattedDeadlineTime = null;
                                error_log("deadlineTime is not set.");
                            }

                            // Insert into database
                            $sql1 = "INSERT INTO assignproject 
                                    (ProjectId, Name,ProjectName, Platform,DeadlineDate, DeadlineTime, Information) 
                                    VALUES 
                                    ('$hiddenGuid', '$employeeName','$name','$platform', '$formattedDeadlineDate', '$formattedDeadlineTime', '$projectInfo')";

                            //Insert new Row for Notify New Project
                            $UpdateNewNotifyEmpQuery = "INSERT INTO NotifyProject 
                                    (ProjectId, Name, ProjectName, Platform, NotifiedDate, NotifiedTime, Information) 
                                    VALUES 
                                    ('$hiddenGuid', '$employeeName','$name','$platform', '$createdon', '$currentTime', '$projectInfo')";

                            $conn->query($sql1);
                            $conn->query($UpdateNewNotifyEmpQuery);
                        }



                        echo json_encode([
                            'status' => 'updated',
                            'ProjectName' => $name,
                            'ProjectId' => $hiddenGuid,
                            'assignedBy' => $hiddenassigned
                        ]);
                    } else {
                        echo "Error: " . $conn->error;
                    }
                } else {
                    // Pending Rows founded So project is Still pending Start
                    $Project_stmt = "UPDATE project 
                                     SET ProjectStatus = 'Pending', CompletedDate = Null
                                     WHERE ProjectId = '$hiddenGuid'";

                    if ($conn->query($Project_stmt) === TRUE) {
                        //Nothing Happening
                    } else {
                        throw new Exception("Error Was Occurred from Update Whole Project Status : " . $conn->error);
                    }
                    $conn->query($sql3);
                    foreach ($_POST['repeater'] as $item) {

                        $employeeName = $conn->real_escape_string($item['employeeName']);
                        $deadlineDate = $conn->real_escape_string($item['deadlineDate']);
                        $deadlineTime = $conn->real_escape_string($item['deadlineTime']);
                        $projectInfo = $conn->real_escape_string($item['projectInfo']);



                        // Process the deadlineDate
                        if ($deadlineDate) {
                            $deaddate = new DateTime($deadlineDate);
                            $formattedDeadlineDate = $deaddate->format('Y-m-d');
                        } else {
                            $formattedDeadlineDate = null;
                            error_log("deadlineDate is not set.");
                        }

                        // Process the deadlineTime
                        if ($deadlineTime) {
                            try {
                                $time = DateTime::createFromFormat('H:i:s', $deadlineTime);
                                if (!$time) {
                                    $time = DateTime::createFromFormat('H:i', $deadlineTime);
                                }
                                if ($time) {
                                    $formattedDeadlineTime = $time->format('H:i:s');
                                } else {
                                    $formattedDeadlineTime = null;
                                    error_log("Invalid time format: " . $deadlineTime);
                                }
                            } catch (Exception $e) {
                                $formattedDeadlineTime = null;
                                error_log("Failed to parse deadlineTime: " . $e->getMessage());
                            }
                        } else {
                            $formattedDeadlineTime = null;
                            error_log("deadlineTime is not set.");
                        }

                        // Insert into database
                        $sql1 = "INSERT INTO assignproject 
                                (ProjectId, Name,ProjectName, Platform,DeadlineDate, DeadlineTime, Information) 
                                VALUES 
                                ('$hiddenGuid', '$employeeName','$name','$platform', '$formattedDeadlineDate', '$formattedDeadlineTime', '$projectInfo')";


                        //Insert new Row for Notify New Project
                        $UpdateNewNotifyEmpQuery = "INSERT INTO NotifyProject 
                                    (ProjectId, Name, ProjectName, Platform, NotifiedDate, NotifiedTime, Information) 
                                    VALUES 
                                    ('$hiddenGuid', '$employeeName','$name','$platform', '$createdon', '$currentTime', '$projectInfo')";

                        $conn->query($sql1);
                        $conn->query($UpdateNewNotifyEmpQuery);
                    }



                    echo json_encode([
                        'status' => 'updated',
                        'ProjectName' => $name,
                        'ProjectId' => $hiddenGuid,
                        'assignedBy' => $hiddenassigned
                    ]);
                }
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            throw new Exception("Error updating project: " . $conn->error);
        }

        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the connection
        $conn->close();
    }
}


// -----------Add New Soft Email Code here---------------



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateSoftEmail") {

    try {
        $days = $_POST['soft_mail_days'];
        $hid = 1;
        // Validate and sanitize inputs
        $updateID = intval($hid);

        $sql = "UPDATE `soft_email` SET 
        `days` = '$days'        
        WHERE `id` = $updateID";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}



// -----------Add New Hard Email Code here---------------





if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateHardEmail") {

    try {
        $Hrs_Mins = $_POST['hard_mail_hrs_mins'];
        $hid = 1;
        // Validate and sanitize inputs
        $updateID = intval($hid);

        $sql = "UPDATE `hard_email` SET 
        `hrs_mins` = '$Hrs_Mins'        
        WHERE `id` = $updateID";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}





// -----------Add New Soft Alert Code here---------------



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateSoftAlert") {

    try {
        $days = $_POST['soft_alert_days'];
        $hid = 1;
        // Validate and sanitize inputs
        $updateID = intval($hid);

        $sql = "UPDATE `soft_alert` SET 
        `days` = '$days'        
        WHERE `id` = $updateID";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}



// -----------Add New Soft Alert Code here---------------


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateHardAlert") {

    try {
        $days = $_POST['hard_alert_days'];
        $hid = 1;

        $updateID = intval($hid);

        $sql = "UPDATE `hard_alert` SET 
        `days` = '$days'        
        WHERE `id` = $updateID";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}



// -----------Add New Department Code here---------------


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddDepartment") {

    try {
        // Get the input from the form
        $Name = $_POST['name'];
    
        // Escape the input to prevent SQL injection
        $Name = mysqli_real_escape_string($conn, $Name);
    
        // Step 1: Check if the department name already exists
        $sql_check = "SELECT COUNT(*) FROM department WHERE name = '$Name'";
        $result = $conn->query($sql_check);
    
        if ($result) {
            $row = $result->fetch_assoc();
            
            if ($row['COUNT(*)'] > 0) {
                // If the department name already exists, show an error message
                echo "duplicateName";
            } else {
                // Step 2: If the department name doesn't exist, insert the new department
                $sql = "INSERT INTO department (name) VALUES ('$Name')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "Success";
                } else {
                    // If there's an error during insertion
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                    echo "Error: " . $error_message;
                }
            }
        } else {
            // If there was an error executing the SELECT query
            echo "Error checking department: " . $conn->error;
        }
    
        // Close the database connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateDepartment") {

    try {
        $Name = $_POST['name'];
        $hid = $_POST['hid'];
        // Validate and sanitize inputs
        $updateID = intval($hid);

        $sql = "UPDATE `department` SET 
        `name` = '$Name'        
        WHERE `id` = $updateID";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddDesignation") {

    try {
        // Get the input from the form
        $Name = $_POST['name'];
        $createdon = date('Y-m-d H:i:s');
    
        // Escape the input to prevent SQL injection
        $Name = mysqli_real_escape_string($conn, $Name);
    
        // Step 1: Check if the designation already exists
        $sql_check = "SELECT COUNT(*) FROM designation WHERE name = '$Name'";
        $result = $conn->query($sql_check);
    
        if ($result) {
            $row = $result->fetch_assoc();
            
            if ($row['COUNT(*)'] > 0) {
                // If the designation name already exists, show an error message
                echo "duplicateName ";
            } else {
                // Step 2: If the designation doesn't exist, insert the new designation
                $sql = "INSERT INTO designation (name, createdAt) VALUES ('$Name', '$createdon')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "Success";
                } else {
                    // If there's an error during insertion
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                    echo "Error: " . $error_message;
                }
            }
        } else {
            // If there was an error executing the SELECT query
            echo "Error checking designation: " . $conn->error;
        }
    
        // Close the database connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateDesignation") {

    try {
        $Name = $_POST['name'];
        $hid = $_POST['hid'];
        // Validate and sanitize inputs
        $updateID = intval($hid);
        $createdon = date('Y-m-d H:i:s');
        $sql = "UPDATE `designation` SET 
        `name` = '$Name'        
        WHERE `id` = $updateID";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // echo $sql;
            echo "updated";
        } else {
            // Output SQL error
            echo "Error executing query: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddService") {

    try {
        // Get the input from the form
        $Name = $_POST['name'];
        $createdon = date('Y-m-d H:i:s');
    
        // Escape the input to prevent SQL injection
        $Name = mysqli_real_escape_string($conn, $Name);
    
        // Step 1: Check if the designation already exists
        $sql_check = "SELECT COUNT(*) FROM service_req WHERE service = '$Name'";
        $result = $conn->query($sql_check);
    
        if ($result) {
            $row = $result->fetch_assoc();
            
            if ($row['COUNT(*)'] > 0) {
                // If the designation name already exists, show an error message
                echo "duplicateName ";
            } else {
                // Step 2: If the designation doesn't exist, insert the new designation
                $sql = "INSERT INTO service_req (service, createdAt) VALUES ('$Name', '$createdon')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "success";
                } else {
                    // If there's an error during insertion
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                    echo "Error: " . $error_message;
                }
            }
        } else {
            // If there was an error executing the SELECT query
            echo "Error checking designation: " . $conn->error;
        }
    
        // Close the database connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'forgot_password') {
    if (!isset($_POST['email'])) {
        echo "Email not provided";
        exit;
    }

    $email = $conn->real_escape_string($_POST['email']);

    // Check if user exists
    $sql = "SELECT name, email FROM employee WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $name = htmlspecialchars($row['name']);
        $email = htmlspecialchars($row['email']);

        // Generate token and expiry
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 1800); // 30 mins from now

        // Insert into password_resets
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        // Load email template
        $templatePath = 'Mail/ForgotPassword.html';
        if (!file_exists($templatePath)) {
            echo "Template file not found.";
            exit;
        }

        $template = file_get_contents($templatePath);
        $resetLink = "https://demo.mbwapps.in/psd_change.php?token=" . $token;

        // Replace placeholders
        $template = str_replace('##EmployeeName##', $name, $template);
        $template = str_replace('##Link##', $resetLink, $template);

        // Send email
        try {
            $mail = new PHPMailer(true);

            // $mail->isSMTP();
            // $mail->Host = 'smtp.gmail.com';
            // $mail->SMTPAuth = true;
            // $mail->Username = 'webenquiryformm@gmail.com';
            // $mail->Password = 'fbyz giyy qryp mswn'; // Use Gmail App Password
            // $mail->SMTPSecure = 'tls';
            // $mail->Port = 587;

            // $mail->setFrom('webenquiryformm@gmail.com', 'Task Manager');
            // $mail->addAddress($email);

            
               // ✅ Fetch SMTP settings (latest row or ID=1)
                    $sql = "SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $smtp = $result->fetch_assoc();
                    } else {
                        throw new Exception("SMTP settings not found in database.");
                    }

                    // ✅ SMTP config
                    $mail->isSMTP();
                    $mail->SMTPDebug = false;
                    $mail->Host       = $smtp['host'];
                    $mail->Port       = $smtp['port'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtp['username'];
                    $mail->Password   = $smtp['password'];
                    $mail->SMTPSecure = $smtp['SMTPSecure'];

                    // ✅ Sender & Recipient
                    $mail->setFrom($smtp['username'], 'Task Manager');
                    $mail->addAddress($email); // $email = recipient


            $mail->isHTML(true);
            $mail->Subject = 'Task Manager - Password Reset';
            $mail->Body = $template;

            if ($mail->send()) {
                echo "success";
            } else {
                echo "Failed to send mail";
            }
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

    } else {
        echo "Failed"; // Email not found
    }

}

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Login") {
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     // Determine if the input is an email or a name
//     $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

//     // Prepare the SQL statement to prevent SQL injection
//     if ($isEmail) {
//         // If it's an email, search by email
//         $stmt = $conn->prepare("SELECT * FROM employee WHERE email = ? AND password = ?");
//     } else {
//         // Otherwise, search by name
//         $stmt = $conn->prepare("SELECT * FROM employee WHERE name = ? AND password = ?");
//     }

//     $stmt->bind_param("ss", $username, $password);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         // Update isOnline to 1 for the logged-in user
//         $updateStmt = $conn->prepare("UPDATE employee SET isOnline = 1 WHERE id = ?");
//         $updateStmt->bind_param("s", $row['id']);
//         $updateStmt->execute();
//         $updateStmt->close();

//         // Insert a record into the login_report table
//         $insertStmt = $conn->prepare("INSERT INTO login_report (loginId, name, date, fromtime, totime, status, designation) VALUES (?, ?, ?, ?, ?, ?, ?)");
//         $currentDate = date('Y-m-d'); // Current date
//         $currentTime = date('H:i:s'); // Current time

//         // Using the employee data
//         $loginId = $row['id'];
//         $name = $row['name'];
//         $totime = null; // Assuming it's null at login
//         $status = $row['isenable']; // Adjust based on your employee table
//         $designation = $row['designation']; // Adjust based on your employee table

//         $insertStmt->bind_param("issssss", $loginId, $name, $currentDate, $currentTime, $totime, $status, $designation);
//         $insertStmt->execute();

//         // Get the inserted ID
//         $insertedId = $conn->insert_id; // Get the last inserted ID
//         $insertStmt->close();

//         if ($row['isenable'] == 1) {
//             // Check credentials and set session variables
//             $JWT_adminName = $row['name'];
//             $JWT_userRole = $row['role'];
//             $JWT_userEmail = $row['email'];
//             $JWT_userID = $row['id'];
//             $JWT_userDesignation = $row['designation'];
//             $JWT_userID  =  $insertedId;
//             if ($JWT_userRole == "employee") 
//             {
//                 if (isset($_POST['remember'])) 
//                 {
//                     setcookie("user_login", $username, time() + (10 * 365 * 24 * 60 * 60));
//                     setcookie("userpassword", $password, time() + (10 * 365 * 24 * 60 * 60));
//                 }
//                 else 
//                 {
//                     if (isset($_COOKIE["user_login"])) {
//                         setcookie("user_login", "", time() - 3600);
//                     }
//                     if (isset($_COOKIE["userpassword"])) {
//                         setcookie("userpassword", "", time() - 3600);
//                     }
//                 }
//                 echo "employee";
//                 exit;
//             } 
//             else
//             {
//                 if (isset($_POST['remember'])) {
//                     setcookie("user_login", $username, time() + (10 * 365 * 24 * 60 * 60));
//                     setcookie("userpassword", $password, time() + (10 * 365 * 24 * 60 * 60));
//                 } else {
//                     if (isset($_COOKIE["user_login"])) {
//                         setcookie("user_login", "", time() - 3600);
//                     }
//                     if (isset($_COOKIE["userpassword"])) {
//                         setcookie("userpassword", "", time() - 3600);
//                     }
//                 }
//                 echo "admin";
//                 exit;
//             }
//         } else {
//             echo "You are inactive. Please contact Admin....!";
//         }
//     } else {
//         // Clear cookies if credentials are wrong
//         if (isset($_COOKIE["user_login"])) {
//             setcookie("user_login", "", time() - 3600);
//         }
//         if (isset($_COOKIE["userpassword"])) {
//             setcookie("userpassword", "", time() - 3600);
//         }
//         echo "Wrong";
//     }
//     $stmt->close();
// }





if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "resetpassword") {
    //require 'db.php'; // DB connection file
    $token = $_POST['hiddenToken'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$token || !$password) {
        http_response_code(400);
        echo "Invalid request";
        exit;
    }

    // Fetch email from token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires >= NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];

        // Update password in employee table
        $stmtUpdate = $conn->prepare("UPDATE employee SET password = ? WHERE email = ?");
        $stmtUpdate->bind_param("ss", $password, $email);
        if ($stmtUpdate->execute()) {

            // Delete token after use
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $deleteStmt->bind_param("s", $token);
            $deleteStmt->execute();

            echo "success";
        } else {
            echo "failed";
        }

        $stmtUpdate->close();
    } else {
        echo "failed";
    }

    $stmt->close();
    $conn->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Addreminder") {
    // Retrieve POST data
    $assignmentName = $conn->real_escape_string($_POST['assignmentName']);
    //$date = $conn->real_escape_string($_POST['date']);
    $alertDuration = $conn->real_escape_string($_POST['alertDuration']);

    $recurring = (int)$conn->real_escape_string($_POST['recurring']);
    $assignedBy = $JWT_adminName;
    // $selectempoyees = $JWT_adminName . ',' . $_POST['selectempoyees'];
    $selectempoyees =  $_POST['selectempoyees'];
    $date = new DateTime($_POST['date']);

    // Format the date as needed
    $formattedDate = $date->format('Y-m-d');


    $reminderDateTime = clone $date; // Clone to avoid modifying the original date
    $reminderDateTime->modify("+$alertDuration minutes"); // Add alert duration in minutes
    $formattedReminderDateTime = $reminderDateTime->format('Y-m-d H:i:s');


    $FindAssignmentNameSql = "SELECT assignment_name FROM reminder WHERE assignment_name = '$assignmentName'";

    // Execute the query
    $AssignmentResult = $conn->query($FindAssignmentNameSql);

    // Initialize an array to hold duplicate conditions
    $duplicates = array();

    // Check if the assignment name exists already
    if ($AssignmentResult->num_rows > 0) {
        $duplicates[] = "duplicate assignment name";
    }

    if (count($duplicates) > 0) {
        // If duplicates were found, return the error message
        echo "Duplicate assignment name";
    } else {
        // SQL query to insert data
        $sql = "INSERT INTO reminder (assignment_name, date, alert_duration,reminderdatetime, tagemployee,recurring,assignedBy) VALUES ('$assignmentName', '$formattedDate', '$alertDuration','$formattedReminderDateTime','$selectempoyees', '$recurring','$assignedBy')";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close connection
        $conn->close();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "Updatereminder") {

    $id = intval($_POST['hid']);
    $assignmentName = $conn->real_escape_string($_POST['assignmentName']);
    // $date = $conn->real_escape_string($_POST['date']);
    $alertDuration = $conn->real_escape_string($_POST['alertDuration']);
    $assignedBy = $JWT_adminName;
    $recurring = isset($_POST['recurring']) && $_POST['recurring'] == '1' ? 1 : 0;
    $date = new DateTime($_POST['date']);

    $selectempoyees =  $_POST['selectempoyees'];

    // Format the date as needed
    $formattedDate = $date->format('Y-m-d');
    $reminderDateTime = clone $date; // Clone to avoid modifying the original date
    $reminderDateTime->modify("+$alertDuration minutes"); // Add alert duration in minutes
    $formattedReminderDateTime = $reminderDateTime->format('Y-m-d H:i:s');
    // SQL query to update data
    $sql = "UPDATE reminder 
            SET assignment_name='$assignmentName', 
                date='$formattedDate', 
                alert_duration='$alertDuration', 
                reminderdatetime = '$formattedReminderDateTime', 
                 tagemployee = '$selectempoyees', 
                recurring='$recurring', 
                assignedBy = '$assignedBy' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "updated";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}


if (isset($_POST['submit']) && $_POST['submit'] === 'empReport') {
    header('Content-Type: application/json');

    // Get POST data
    $selectEmployee = $_POST['selectEmployee'] ?? '';
    $SelectedEmployee = $_POST['SelectedEmployee'] ?? '';

    // Initialize response
    $response = array();

    // Check if $SelectedEmployee is empty
    if (!empty($SelectedEmployee)) {
        // Prepare SQL query with employee ID filter
        $stmt = $conn->prepare("
            SELECT * FROM employee
            WHERE role = ? AND id = ?
        ");
        $stmt->bind_param('ss', $selectEmployee, $SelectedEmployee);
    } else {
        // Prepare SQL query without employee ID filter
        $stmt = $conn->prepare("
            SELECT * FROM employee
            WHERE role = ?
        ");
        $stmt->bind_param('s', $selectEmployee);
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Prepare response
    $response['data'] = $rows;
    $response['status'] = 'success';

    // Close connection
    $stmt->close();
    $conn->close();

    // Return response as JSON
    echo json_encode($response);
}




if (isset($_POST['submit']) && $_POST['submit'] === 'taskReport') {
    header('Content-Type: application/json');

    // Get POST data
    $selectClient = $_POST['selectClient'] ?? '';
    $SelecteStatus = $_POST['SelecteStatus'] ?? '';
    $from_date = $_POST['from_date'] ?? '';
    $to_date = $_POST['to_date'] ?? '';
    $SelectType =  $_POST['SelectType'];

    if ($SelectType === 'task') {
        $response = array();
        // GROUP_CONCAT(DISTINCT employee) AS employee, 
        // Prepare SQL query
        $query = "
        SELECT 
           event.id AS event_id,
    event.name,
    event.phone,
    event.platform,
    event.date,
    event.time,
    event.assignedBy,
     event.status,
     event.task_id,
     event.tagemployee,
    TRIM(event.task_id) AS task_id,
    task_descriptions.id AS tid,
    TRIM(task_descriptions.taskid) AS task_ids,
    task_descriptions.details AS task_details,
    task_descriptions.status AS task_status,
    task_descriptions.createdon AS task_createdon,
    task_descriptions.addedBy AS task_addedBy
        FROM event
        LEFT JOIN task_descriptions ON TRIM(event.task_id) = TRIM(task_descriptions.taskid)
        WHERE event.name = ? 
        ";



        $params = [$selectClient];


        if (!empty($SelecteStatus)) {
            if ($SelecteStatus != 'All') {
                $query .= " AND event.status = ?"; // Ensure 'event.' is referenced
                $params[] = $SelecteStatus;
            }
        }

        if (!empty($from_date) && !empty($to_date)) {
            $formattedFromDate = date('Y-m-d', strtotime($from_date));
            $formattedToDate = date('Y-m-d', strtotime($to_date));


            $query .= " AND event.date BETWEEN ? AND ?";
            $params[] = $formattedFromDate;
            $params[] = $formattedToDate;
        }


        $stmt = $conn->prepare($query);
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();


        $data = array();

        while ($row = $result->fetch_assoc()) {


            $event_id = $row['event_id'];


            if (!isset($data[$event_id])) {
                $data[$event_id] = array(
                    'id' => $event_id,
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'platform' => $row['platform'],
                    'date' => $row['date'],
                    'time' => $row['time'],
                    'status' => $row['status'],
                    'tagemployee' => $row['tagemployee'],
                    'assignedBy' => $row['assignedBy'],
                    'task_id' => $row['task_id'],
                    'tasks' => array()
                );
            }

            if ($row['task_ids']) {
                $data[$event_id]['tasks'][] = array(
                    'task_id' => $row['task_ids'],
                    'task_details' => $row['task_details'],
                    'task_status' => $row['task_status'],
                    'task_createdon' => $row['task_createdon'],
                    'task_addedBy' => $row['task_addedBy'],

                );
            }
        }




        $response = array('data' => array_values($data), 'status' => 'success');
        $response['type'] = 'event';


        $stmt->close();
        $conn->close();


        echo json_encode($response);
    } else {

        $response = array();


        $sql = "
            SELECT  td.*  
            FROM task_descriptions td
            WHERE 1=1";


        $params = [];


        if (!empty($selectClient)) {
            if ($SelecteStatus === "All") {
                $sql .= " AND td.addedBy = ?";
                $params[] = $selectClient;
            } else {
                $sql .= " AND td.addedBy = ? AND td.status = ?";
                $params[] = $selectClient;
                $params[] = $SelecteStatus;
            }
        }


        if (!empty($from_date) && !empty($to_date)) {
            $formattedFromDate = date('Y-m-d', strtotime($from_date));
            $formattedToDate = date('Y-m-d', strtotime($to_date));

            $sql .= " AND td.date BETWEEN ? AND ?";
            $params[] = $formattedFromDate;
            $params[] = $formattedToDate;
        }


        // $sql .= " GROUP BY td.details";


        $stmt = $conn->prepare($sql);


        if ($params) {

            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }


        $response['data'] = $rows;
        $response['status'] = 'success';
        $response['type'] = 'employee';


        $stmt->close();
        $conn->close();


        echo json_encode($response);
    }
}


if (isset($_POST['submit']) && $_POST['submit'] === 'projectReport') {
    header('Content-Type: application/json');

    // Get POST data
    $selectProject = $_POST['selectProject'] ?? '';
    $SelecteStatus = $_POST['SelecteStatus'] ?? '';
    $from_date = $_POST['from_date'] ?? '';
    $to_date = $_POST['to_date'] ?? '';
    $SelectType =  $_POST['SelectType'];


    if ($SelectType === 'project') {
        // Initialize response
        $response = array();

        // Base SQL query
        $sql = "
            SELECT 
              p.id AS project_id,
              p.ProjectId AS ProjectId,
              p.name AS project_name,
              p.type AS project_type,
              p.assignedTo AS project_assignedTo,
              p.assignedBy AS project_assignedBy,
              p.deadlineDate AS project_deadlineDate,
              p.deadlineTime AS project_deadlineTime,
              p.ProjectStatus AS ProjectStatus,
              p.CompletedDate AS CompletedDate,
              ap.id AS assignment_id,
              ap.projectId AS assignment_projectId,
              ap.Name AS assignment_name,
              ap.DeadlineDate AS assignment_deadlineDate,
              ap.DeadlineTime AS assignment_deadlineTime,
              ap.Information AS assignment_info,
              ap.SubTaskStatus AS SubTaskStatus,
              ap.SubtaskNote AS SubtaskNote,
              ap.CreateDateTime AS CreateDateTime,
              ap.completed_link AS completed_link  
            FROM project p
            INNER JOIN assignproject ap ON p.projectId = ap.projectId
            WHERE p.name = ?
        ";

        // Initialize parameters array
        $params = [$selectProject];

        // Add status filter
        if (!empty($SelecteStatus)) {

            if ($SelecteStatus !== 'All') {
                $sql .= " AND ap.SubTaskStatus = ?";
                $params[] = $SelecteStatus;
            }
        }

        // Add date filter
        if (!empty($from_date) && !empty($to_date)) {
            $formattedFromDate = date('Y-m-d', strtotime($from_date));
            $formattedToDate = date('Y-m-d', strtotime($to_date));

            $sql .= " AND (ap.DeadlineDate BETWEEN ? AND ?)";
            $params[] = $formattedFromDate;
            $params[] = $formattedToDate;
        }

        // Order the results
        $sql .= " ORDER BY p.id ASC, ap.id DESC";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters dynamically
        if (!empty($params)) {
            // Determine parameter types
            $types = str_repeat('s', count($params)); // assuming all are strings
            $stmt->bind_param($types, ...$params);
        }

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $project_id = $row['project_id'];
            if (!isset($data[$project_id])) {
                $data[$project_id] = array(
                    'id' => $project_id,
                    'pid' => $row['ProjectId'],
                    'name' => $row['project_name'],
                    'type' => $row['project_type'],
                    'assignedTo' => $row['project_assignedTo'],
                    'assignedBy' => $row['project_assignedBy'],
                    'deadlineDate' => $row['project_deadlineDate'],
                    'deadlineTime' => $row['project_deadlineTime'],
                    'ProjectStatus' => $row['ProjectStatus'],
                    'CompletedDate' => $row['CompletedDate'],
                    'assignments' => array()
                );
            }

            // If assignment exists, add it to the assignments array
            if ($row['assignment_id']) {
                $data[$project_id]['assignments'][] = array(
                    'id' => $row['assignment_id'],
                    'pid' => $row['assignment_projectId'],
                    'name' => $row['assignment_name'],
                    'deadlineDate' => $row['assignment_deadlineDate'],
                    'deadlineTime' => $row['assignment_deadlineTime'],
                    'SubTaskStatus' => $row['SubTaskStatus'],
                    'CreateDateTime' => $row['CreateDateTime'],
                    'SubtaskNote' => $row['SubtaskNote'],
                    'info' => $row['assignment_info'],
                    'completed_link' => $row['completed_link']
                );
            }
        }

        // Prepare response
        $response = array('data' => array_values($data));
        $response['status'] = 'success';
        $response['type'] = 'project';

        // Close connection
        $stmt->close();
        $conn->close();

        // Return response as JSON
        echo json_encode($response);
    }

    if ($SelectType === 'employee') {
        $response = array();

        // Initialize the base SQL query
        $sql = "SELECT * FROM assignproject WHERE 1=1"; // 1=1 for easier appending of conditions
        $params = [];

        // Add filters based on input && $selectProject !== 'all'
        if (!empty($selectProject)) {
            $sql .= " AND Name = ?";
            $params[] = $selectProject;
        }

        if ($SelecteStatus !== 'All') {
            $sql .= " AND SubTaskStatus = ?";
            $params[] = $SelecteStatus;
        }

        // Add date filters
        if (!empty($from_date) && !empty($to_date)) {
            $formattedFromDate = date('Y-m-d', strtotime($from_date));
            $formattedToDate = date('Y-m-d', strtotime($to_date));

            $sql .= " AND DeadlineDate BETWEEN ? AND ?";
            $params[] = $formattedFromDate;
            $params[] = $formattedToDate;
        }

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);

        // Dynamically bind parameters if there are any
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // assuming all are strings
            $stmt->bind_param($types, ...$params);
        }

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Prepare response
        $response['data'] = $rows;
        $response['status'] = 'success';
        $response['type'] = 'employee';

        // Close connection
        $stmt->close();
        $conn->close();

        // Return response as JSON
        echo json_encode($response);
    }
}



if (isset($_POST['submit']) && $_POST['submit'] === 'reminReport') {
    header('Content-Type: application/json');

    // Get POST data
    $selectAssignmentName = $_POST['selectAssignmentName'];
    $selectEmpName = $_POST['selectEmpName'];

    // Initialize response
    $response = array();

    if (!empty($selectEmpName)) {
        $stmt = $conn->prepare("
            SELECT * FROM reminder_notification
            WHERE name = ? AND assignedBy = ?
            ORDER BY id ASC
        ");

        $stmt->bind_param('ss', $selectAssignmentName, $selectEmpName);
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM reminder_notification
            WHERE name = ?
            ORDER BY id ASC
        ");

        $stmt->bind_param('s', $selectAssignmentName);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Prepare response
    $response['data'] = $rows;
    $response['status'] = 'success';
    echo json_encode($response);

    // Close connection
    $stmt->close();
    $conn->close();
}


if (isset($_POST['submit']) && $_POST['submit'] === 'logReport') {
    header('Content-Type: application/json');


    $selectEmpName = $_POST['selectEmpName'];

    // Initialize response
    $response = array();

    // Check if $SelectedEmployee is empty
    if (!empty($selectEmpName)) {
        // Prepare SQL query with employee ID filter
        $stmt = $conn->prepare("
            SELECT * FROM login_report
            WHERE name = ?
        ");
        $stmt->bind_param('s',  $selectEmpName);
    } else {
        // Prepare SQL query without employee ID filter
        $stmt = $conn->prepare("
            SELECT * FROM login_report
           
        ");
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Prepare response
    $response['data'] = $rows;
    $response['status'] = 'success';

    // Close connection
    $stmt->close();
    $conn->close();

    // Return response as JSON
    echo json_encode($response);
}

if (isset($_POST['submit']) && $_POST['submit'] === 'deleteLogReport') {
    header('Content-Type: application/json');


    $selectEmpName = $_POST['selectEmpName'];

    // Initialize response
    $response = array();

    // Check if $SelectedEmployee is empty
    if (!empty($selectEmpName)) {
        // Prepare SQL query with employee ID filter
        $stmt = $conn->prepare("
            SELECT * FROM deletelog
            WHERE deleteBy = ?
        ");
        $stmt->bind_param('s',  $selectEmpName);
    } else {
        // Prepare SQL query without employee ID filter
        $stmt = $conn->prepare("
            SELECT * FROM deletelog
           
        ");
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Prepare response
    $response['data'] = $rows;
    $response['status'] = 'success';

    // Close connection
    $stmt->close();
    $conn->close();

    // Return response as JSON
    echo json_encode($response);
}


if (isset($_POST['submit']) && $_POST['submit'] === 'AddPermission') {
    header('Content-Type: application/json');

    // Collect data from POST request
    $UserId = $_POST['UserId'];
    $noOfEmployee = $_POST['noOfEmployee'];
    $activeEmployees = $_POST['activeEmployees'];
    $inActiveEmployees = $_POST['inActiveEmployees'];
    $numberOfAdmin = $_POST['numberOfAdmin'];
    $activeAdmin = $_POST['activeAdmin'];
    $inActiveAdmin = $_POST['inActiveAdmin'];
    $TodayTask = $_POST['TodayTask'];
    $TaskNext6Days = $_POST['TaskNext6Days'];
    $Task7thDayTo31thDay = $_POST['Task7thDayTo31thDay'];
    $TotalTask = $_POST['TaskTotal'];
    $TaskPending = $_POST['TaskPending'];
    $TaskFollowUp = $_POST['TaskFollowUp'];
    $TaskCompleted = $_POST['TaskCompleted'];
    $TaskNotInterested = $_POST['TaskNotInterested'];
    $ProjectAllTime = $_POST['ProjectAllTime'];
    $PendingProject = $_POST['PendingProject'];
    $ExtendedProject = $_POST['ExtendedProject'];
    $CompletedProject = $_POST['CompletedProject'];
    $ReminderCount = $_POST['ReminderCount'];
    $DocumentCount = $_POST['DocumentCount'];
    $AddNewEmployee = $_POST['AddNewEmployee'];
    $AddNewClient = $_POST['AddNewClient'];
    $AssignJob = $_POST['AssignJob'];
    $AssignedJob = $_POST['AssignedJob'];
    $JobsExcel = $_POST['JobsExcel'];
    $BulkUser = $_POST['BulkUser'];
    $UserRoles = $_POST['UserRoles'];
    $AddNewSaleTask = $_POST['AddNewSaleTask'];
    $TaskReply = $_POST['TaskReply'];
    $AddNewProject = $_POST['AddNewProject'];
    $ProjectReply = $_POST['ProjectReply'];
    $AddNewReminder = $_POST['AddNewReminder'];
    $ReminderViews = $_POST['ReminderViews'];
    $AddNewDocument = $_POST['AddNewDocument'];
    $DocumentViews = $_POST['DocumentViews'];
    $SalesTask = $_POST['SalesTask'];
    $TaskViews = $_POST['TaskViews'];
    $AddDepartment = $_POST['AddDepartment'];
    $DepartmentViews = $_POST['DepartmentViews'];
    $SettingAddDesignation  = $_POST['AddDesignation'];
    $SettingDesignationView  = $_POST['DesignationViews'];
    $SettingAddLogo  = $_POST['AddLogo'];
    $SettingLogoView  = $_POST['LogoViews'];

    $ReportDownloadAccess = $_POST['ReportDownloadAccess'];
    $ReportEmployeeAccess = $_POST['ReportEmployeeAccess'];
    $ReportSalesTaskAccess = $_POST['ReportSalesTaskAccess'];
    $ReportProjectTaskAccess = $_POST['ReportProjectTaskAccess'];
    $ReportReminderAccess = $_POST['ReportReminderAccess'];
    $ReportLogAccess = $_POST['ReportLogAccess'];
    $ProfilePicAdd = $_POST['ProfilePicAdd'];
    $ChangePassword = $_POST['ChangePassword'];
    $UserExcel = $_POST['UserExcel'];
    $SalesExcel = $_POST['SalesExcel'];
    $ProjectExcel = $_POST['ProjectExcel'];
    $ReminderExcel = $_POST['ReminderExcel'];
    $LogExcel = $_POST['LogExcel'];
    $DocumentDelete = $_POST['DocumentDelete'];
    $DeleteLog = $_POST['DeleteLog'];

    // Check if the UserId already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM permissions WHERE userID = ?");
    $checkStmt->bind_param("s", $UserId);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // UserId exists, perform an update
        $stmt = $conn->prepare("UPDATE permissions 
            SET number_of_employees = ?, number_of_active_employees = ?, inactive_employees = ?, 
                number_of_admin = ?, active_admin = ?, inactive_admin = ?, today_task = ?, 
                task_for_next_6_days = ?, task_for_7th_to_31st_day = ?,TotalTask=?,TaskPending = ?, TaskFollowUp = ?, TaskCompleted = ?, TaskNotInterested = ?,  project_all_time = ?, 
                pending_project = ?, extended_project = ?, completed_project = ?, 
                reminder_count = ?, document_count = ?, AddNewEmployee = ?, AddNewClient = ?, AssignJob = ?,AssignedJob = ?,JobsExcel = ?,BulkUser = ?, UserRoles = ?, 
                AddNewSaleTask = ?, TaskReply = ?, AddNewProject = ?, ProjectReply = ?, 
                AddNewReminder = ?, ReminderViews = ?, AddNewDocument = ?, DocumentViews = ?, 
                SettingSalesTask = ?, SettingTaskViews = ?, SettingAddDepartment = ?, SettingDepartmentViews = ?, 
                  SettingAddDesignation  = ?, SettingDesignationView  = ?, SettingAddLogo  = ?, SettingLogoView  = ?, 
                ReportDownloadAccess = ?, ReportEmployeeAccess = ?, 
                ReportSalesTaskAccess = ?, ReportProjectTaskAccess = ?, 
                ReportReminderAccess = ?, ReportLogAccess = ?, 
                ProfilePicAdd = ?, ChangePassword = ? ,UserExcel = ? , SalesExcel = ?,ProjectExcel = ?,ReminderExcel = ?,LogExcel = ?,
                DeleteDocuments =?,DeleteLog =? 
            WHERE userID = ?");

        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
            $noOfEmployee,
            $activeEmployees,
            $inActiveEmployees,
            $numberOfAdmin,
            $activeAdmin,
            $inActiveAdmin,
            $TodayTask,
            $TaskNext6Days,
            $Task7thDayTo31thDay,
            $TotalTask,
            $TaskPending,
            $TaskFollowUp,
            $TaskCompleted,
            $TaskNotInterested,
            $ProjectAllTime,
            $PendingProject,
            $ExtendedProject,
            $CompletedProject,
            $ReminderCount,
            $DocumentCount,
            $AddNewEmployee,
            $AddNewClient,
            $AssignJob,
            $AssignedJob,
            $JobsExcel,
            $BulkUser,
            $UserRoles,
            $AddNewSaleTask,
            $TaskReply,
            $AddNewProject,
            $ProjectReply,
            $AddNewReminder,
            $ReminderViews,
            $AddNewDocument,
            $DocumentViews,
            $SalesTask,
            $TaskViews,
            $AddDepartment,
            $DepartmentViews,
            $SettingAddDesignation,
            $SettingDesignationView,
            $SettingAddLogo,
            $SettingLogoView,
            $ReportDownloadAccess,
            $ReportEmployeeAccess,
            $ReportSalesTaskAccess,
            $ReportProjectTaskAccess,
            $ReportReminderAccess,
            $ReportLogAccess,
            $ProfilePicAdd,
            $ChangePassword,
            $UserExcel,
            $SalesExcel,
            $ProjectExcel,
            $ReminderExcel,
            $LogExcel,
            $DocumentDelete,
            $DeleteLog,
            $UserId
        );
    } else {
        // UserId does not exist, perform an insert
        $stmt = $conn->prepare("INSERT INTO permissions 
            (userID, number_of_employees, number_of_active_employees, inactive_employees, 
             number_of_admin, active_admin, inactive_admin, today_task, task_for_next_6_days, 
             task_for_7th_to_31st_day,TotalTask,TaskPending,TaskFollowUp,TaskCompleted,TaskNotInterested, project_all_time, pending_project, extended_project, 
             completed_project, reminder_count, document_count, 
             AddNewEmployee, AddNewClient,AssignJob,AssignedJob, JobsExcel, BulkUser,UserRoles, AddNewSaleTask, TaskReply, 
             AddNewProject, ProjectReply, AddNewReminder, ReminderViews, 
             AddNewDocument, DocumentViews, SettingSalesTask, SettingTaskViews, 
             SettingAddDepartment, SettingDepartmentViews,   SettingAddDesignation, SettingDesignationView, SettingAddLogo, SettingLogoView, ReportDownloadAccess, 
             ReportEmployeeAccess, ReportSalesTaskAccess, 
             ReportProjectTaskAccess, ReportReminderAccess, 
             ReportLogAccess, ProfilePicAdd, ChangePassword,UserExcel,SalesExcel,ProjectExcel,ReminderExcel,LogExcel,DeleteDocuments,DeleteLog) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ,? ,?)");

        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
            $UserId,
            $noOfEmployee,
            $activeEmployees,
            $inActiveEmployees,
            $numberOfAdmin,
            $activeAdmin,
            $inActiveAdmin,
            $TodayTask,
            $TaskNext6Days,
            $Task7thDayTo31thDay,
            $TotalTask,
            $TaskPending,
            $TaskFollowUp,
            $TaskCompleted,
            $TaskNotInterested,
            $ProjectAllTime,
            $PendingProject,
            $ExtendedProject,
            $CompletedProject,
            $ReminderCount,
            $DocumentCount,
            $AddNewEmployee,
            $AddNewClient,
            $AssignJob,
            $AssignedJob,
            $JobsExcel,
            $BulkUser,
            $UserRoles,
            $AddNewSaleTask,
            $TaskReply,
            $AddNewProject,
            $ProjectReply,
            $AddNewReminder,
            $ReminderViews,
            $AddNewDocument,
            $DocumentViews,
            $SalesTask,
            $TaskViews,
            $AddDepartment,
            $DepartmentViews,
            $SettingAddDesignation,
            $SettingDesignationView,
            $SettingAddLogo,
            $SettingLogoView,
            $ReportDownloadAccess,
            $ReportEmployeeAccess,
            $ReportSalesTaskAccess,
            $ReportProjectTaskAccess,
            $ReportReminderAccess,
            $ReportLogAccess,
            $ProfilePicAdd,
            $ChangePassword,
            $UserExcel,
            $SalesExcel,
            $ProjectExcel,
            $ReminderExcel,
            $LogExcel,
            $DocumentDelete,
            $DeleteLog
        );
    }

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $response = [
            'status' => 'success',
            'message' => $count > 0 ? 'Permission updated successfully.' : 'Permission added successfully.',
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Failed to add/update permission: ' . $stmt->error
        ];
    }

    // Close the statement
    $stmt->close();

    // Return response as JSON
    echo json_encode($response);
}

// -----------Add Documents Code here---------------







if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateLogo") {



    $hid = $_POST['hid'];
    $hidden_file_url = $_POST['hidden_file_url'];
    $title = $_POST['title'];
    $subTitle = $_POST['subTitle'];

    // $file = isset($_FILES['file']) ? $_FILES['file'] : null;
    $file = isset($_FILES['file']);

    if (!$file)  // -- > Not Edit File
    {
        try {


            $File_ext = pathinfo($hidden_file_url, PATHINFO_EXTENSION);

            $hiderPathinfo = pathinfo($hidden_file_url);

            $old_filename =  $hiderPathinfo['basename'];

            $Modify_filename = str_replace(" ", "-",  $old_filename);

            $new_filename = $Modify_filename . '.' . $File_ext;
            $uploadDir = 'uploads/logo/';

            // Define the file path
            $filePath = $uploadDir . $Modify_filename;


            $sql = "UPDATE logo SET 
               
                file_url= '$filePath',
                Title = '$title',
                SubTitle = '$subTitle'
                WHERE id = 1";


            if ($conn->query($sql) === TRUE) {
                echo "updated";
            } else {
                echo "Error executing query: " . $conn->error;
            }

            $conn->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else     //  --- > If Edited File
    {
        try {
            $uploadDir = 'uploads/logo/';

            // Check if the file was uploaded without errors
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];

                // Get the file extension and name
                $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = pathinfo($file['name'], PATHINFO_FILENAME);

                // Define the file path
                $filePath = $uploadDir . $fileName . '.' . $File_ext;

                // Move the uploaded file to the server
                if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                    throw new Exception('Failed to move uploaded file.');
                }

                // Prepare SQL statement
                $sql = "UPDATE logo SET 
                                    file_url = '$filePath', 
                                    Title = '$title', 
                                    SubTitle = '$subTitle' 
                                WHERE id = 1";

                if ($conn->query($sql) === TRUE) {
                    echo "updated";
                } else {
                    echo "Error executing query: " . $conn->error;
                }
            } else {
                throw new Exception('File upload error: ' . ($_FILES['file']['error'] ?? 'Unknown error'));
            }

            $conn->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}





// -----------Add Documents Code here---------------


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddDocs") {

    $Name = isset($_POST['name']) ? $_POST['name'] : null;
    $TaggedEmployees = '';
    $addedBy = '';
    if (empty($_POST['TaggedEmployees'])) {
        // If TaggedEmployees is empty or not set, use only AdminName
        $TaggedEmployees = $JWT_adminName;
    } else {
        // If TaggedEmployees is set and not empty, concatenate AdminName and TaggedEmployees
        $TaggedEmployees = $JWT_adminName . ',' . $_POST['TaggedEmployees'];
    }
    // $TaggedEmployees =  $JWT_adminName . ',' . $_POST['TaggedEmployees'];// isset($_POST['TaggedEmployees']) ? $_POST['TaggedEmployees'] : null;
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;
    $addedBy = $JWT_adminName;
    if (!empty($Name) && !empty($file)) {
        $checking_sql = "SELECT * FROM docs_upload WHERE name='$Name'";
        $Exist_result = $conn->query($checking_sql);

        if ($Exist_result->num_rows > 0) { // If Data Exists
            echo "$Name Document Name Is Already Exist, Please Change Document Name.";
            $conn->close();
        } else {
            try {
                // Check for file upload errors
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception('File upload error: ' . $file['error']);
                }

                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $Modify_filename = str_replace(" ", "-", $Name);
                $filePath = $uploadDir . $Modify_filename . '.' . $File_ext;

                // Move the uploaded file to the server
                if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                    throw new Exception('Failed to move uploaded file.');
                }

                // Prepare the SQL query with prepared statements
                $stmt = $conn->prepare("INSERT INTO docs_upload (name, file_url, tagged_emp,addedBy) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $Name, $filePath, $TaggedEmployees, $addedBy);

                // Execute the statement
                if ($stmt->execute()) {
                    // Send email with attachment
                    $taggedNames = explode(',', $TaggedEmployees);
                    $emails = [];

                    foreach ($taggedNames as $employeeName) {
                        $sql = "SELECT email FROM employee WHERE name = '$employeeName'";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $assignedTo = $row['email'];
                            $assignedPerson =  $JWT_adminName ?? 'Your Name'; // Adjust accordingly
                            $designation =  $JWT_userDesignation  ?? 'Your Designation';
                            $template = file_get_contents('Mail/SharedDocument.html');

                            // Replace placeholders in the template
                            $template = str_replace('##EmployeeName##', $employeeName, $template);
                            $template = str_replace('##DocumentName##',  $Name, $template);

                            $template = str_replace('##AssignedBy##', $assignedPerson, $template);
                            $template = str_replace('##AssignedDesignation##', $designation, $template);
                            $Body_message = $template;
                            $mail = new PHPMailer(true);
                            try {


                                // $mail->isSMTP();
                                // $mail->Host = 'smtp.gmail.com';
                                // $mail->SMTPAuth = true;
                                // $mail->Username = 'taskenginembw@gmail.com';
                                // $mail->Password = 'dwed lrmz jzue bsml';
                                // $mail->SMTPSecure = 'tls';
                                // $mail->Port = 587;

                                // $mail->setFrom('taskenginembw@gmail.com', 'Document Notification');

                                  // ✅ Fetch SMTP settings (latest row or ID=1)
                                    $sql = "SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        $smtp = $result->fetch_assoc();
                                    } else {
                                        throw new Exception("SMTP settings not found in database.");
                                    }

                                    // ✅ SMTP config
                                    $mail->isSMTP();
                                    $mail->SMTPDebug = false;
                                    $mail->Host       = $smtp['host'];
                                    $mail->Port       = $smtp['port'];
                                    $mail->SMTPAuth   = true;
                                    $mail->Username   = $smtp['username'];
                                    $mail->Password   = $smtp['password'];
                                    $mail->SMTPSecure = $smtp['SMTPSecure'];

                                    // ✅ Sender & Recipient
                                    $mail->setFrom($smtp['username'], 'Task Manager');

                                $mail->addAddress($assignedTo);


                                $mail->isHTML(true);
                                $mail->Subject = 'Document Notification' . $Name;
                                $mail->Body = $Body_message;
                                $mail->addAttachment($filePath); // Add attachment

                                $mail->send();
                            } catch (Exception $e) {
                                echo "Mailer Error: " . $mail->ErrorInfo;
                            }
                        }
                    }
                    echo "success";
                } else {
                    throw new Exception("Error: " . $stmt->error);
                }

                $stmt->close();
                $conn->close();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo ('Please Select a document and provide a document name.');
    }
}



// -----------Edit Documents Code here---------------



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "UpdateDocs") {


    $Name = isset($_POST['name']) ? $_POST['name'] : null;
    $hid = $_POST['hid'];
    $hidden_file_url = $_POST['hidden_file_url'];
    $updateID = intval($hid);
    $TaggedEmployees = isset($_POST['TaggedEmployees']) ? $_POST['TaggedEmployees'] : null;
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;


    if (!empty($Name)) {
        // ************Edit Allowing Gate Code Start**************

        $allowEdit = "false";

        $checking_sql = "SELECT * FROM docs_upload WHERE name='$Name'";
        $Exist_result = $conn->query($checking_sql);

        // Fetch the result row
        $row = $Exist_result->fetch_assoc();

        // Check if any rows were returned
        if ($Exist_result->num_rows == 0)   // ---------> If no rows are found
        {
            $allowEdit = "true";
        } else    // ---------> If rows are found
        {
            if ($row['id'] != $updateID)        // ---------> If the document exists but has a different id
            {
                $allowEdit = "false";
            } else                                // ---------> If the document exists and has the same id
            {
                $allowEdit = "true";
            }
        }

        // ************Edit Allowing Gate Code End**************

        if ($allowEdit == "true") {

            if (!empty($hidden_file_url))  // -- > Not Edit File
            {
                try {

                    $File_ext = pathinfo($hidden_file_url, PATHINFO_EXTENSION);

                    $hiderPathinfo = pathinfo($hidden_file_url);

                    $old_filename =  $hiderPathinfo['basename'];

                    $Modify_filename = str_replace(" ", "-", $Name);

                    $new_filename = $Modify_filename . '.' . $File_ext;
                    $uploadDir = 'uploads/';

                    // Define the file path
                    $filePath = $uploadDir . $Modify_filename . '.' . $File_ext;


                    if (file_exists($hidden_file_url)) {
                        if (rename($hidden_file_url, $filePath)) {
                            //echo "File renamed successfully.";
                        } else {
                            throw new Exception("Error renaming the file.");
                        }
                    } else {
                        throw new Exception("File does not exist.");
                    }

                    $sql = "UPDATE docs_upload SET 
                name = '$Name',
                file_url= '$filePath',
                tagged_emp= '$TaggedEmployees'
                WHERE id = $updateID";


                    if ($conn->query($sql) === TRUE) {
                        echo "updated";
                    } else {
                        echo "Error executing query: " . $conn->error;
                    }

                    $conn->close();
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else     //  --- > If Edited File
            {
                try {

                    $uploadDir = 'uploads/';

                    $File_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

                    $Modify_filename = str_replace(" ", "-", $Name);

                    // Define the file path
                    $filePath = $uploadDir . $Modify_filename . '.' . $File_ext;


                    // Move the uploaded file to the server
                    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                        throw new Exception('Failed to move uploaded file.');
                    }

                    $sql = "UPDATE docs_upload SET 
                name = '$Name',
                file_url= '$filePath',
                tagged_emp= '$TaggedEmployees'
                WHERE id = $updateID";


                    if ($conn->query($sql) === TRUE) {
                        echo "updated";
                    } else {
                        echo "Error executing query: " . $conn->error;
                    }

                    $conn->close();
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        } else {
            echo "$Name Document Name Already Exists. Please Change Document Name.";
        }
    } else {
        echo ('Please fill and submit document name.');
    }
}



