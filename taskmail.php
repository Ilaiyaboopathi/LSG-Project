<?php
//ob_start(); // Start output buffering
//session_start();
require 'vendor/autoload.php';
require 'data/dbconfig.php';
date_default_timezone_set('Asia/Kolkata');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function sendEventNotification($event)
{
    global $conn;
    
    // Ensure $_SESSION is set properly
   // $fromEmail = isset($_SESSION['user_email']); 
    $recipient =  $event['employeeName']; 

    $sql = "SELECT email FROM employee WHERE name= '$recipient'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $assignedToEmail = $row['email']; 
    }

   $template = file_get_contents('Mail/AssignTask.html');
    
    // Replace placeholders in the template
    $template = str_replace('##EmployeeName##', $event['employeeName'], $template);
    $template = str_replace('##TaskName##', $event['taskName'], $template);
    $template = str_replace('##TaskDetails##', $event['taskDetails'], $template);
    $template = str_replace('##TaskDueDate##', $event['date'], $template);
    $template = str_replace('##TaskType##', $event['platform'], $template);
    $template = str_replace('##TaskStatus##', $event['taskStatus'], $template);
    $template = str_replace('##TaskAssignedBy##', $event['assignedBy'], $template);
    $template = str_replace('##AssignedBy##', $event['lastUpdatedPerson'], $template);
    $template = str_replace('##AssignedDesignation##', $event['lastUpdatedDesignation'], $template);
   


    if ($event['taskStatus'] == "Pending")
    {
        $template = str_replace('__stautsColor__', 'orange', $template);
    }
    elseif ($event['taskStatus'] == "Follow Up")
    {
        $template = str_replace('__stautsColor__', 'yellow', $template);
    }
    elseif ($event['taskStatus'] == "Completed ")
    {
        $template = str_replace('__stautsColor__', 'green', $template);
    }
    elseif ($event['taskStatus'] == "Not Interested ")
    {
        $template = str_replace('__stautsColor__', 'red', $template);
    }
    
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

        // $mail->setFrom( 'taskenginembw@gmail.com', 'Sales Task');

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
        $mail->addAddress($assignedToEmail);
        
        $mail->isHTML(true);
        $mail->Subject = 'Sales Task ' . $event['taskName'];
        $mail->Body = $Body_message;

        $isSent = $mail->send();
    //     return $isSent;
    //   //  echo "Notification sent for event: {$event['taskName']}\n";
    // } catch (Exception $e) {
    //    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
    //     return $mail->ErrorInfo;
    // }

     if ($isSent) {
            echo "✅ Mail sent to {$assignedToEmail}\n";
        } else {
            echo "❌ Mail failed: " . $mail->ErrorInfo . "\n";
        }
        return $isSent;

    } catch (Exception $e) {
        echo "❌ Exception: {$e->getMessage()}\n";
        return false;
    }
}

function sendUpdatedEventNotification($event)
{
    global $conn;
    
    $recipient = $event['employeeName']; 
    $sql = "SELECT email FROM employee WHERE name = '" . $conn->real_escape_string($recipient) . "'";
    $result = $conn->query($sql);

    $assignedToEmail = '';
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $assignedToEmail = $row['email']; 
    }

    if (empty($assignedToEmail)) {
        echo "❌ No email found for employee: {$recipient}\n";
        return false;
    }

    $template = file_get_contents('Mail/UpdateSaleTask.html');
    $template = str_replace('##EmployeeName##', $event['employeeName'], $template);
    $template = str_replace('##TaskName##', $event['taskName'], $template);
    $template = str_replace('##TaskStatus##', $event['taskStatus'], $template);
    $template = str_replace('##TaskAssignedBy##', $event['assignedBy'], $template);
    $template = str_replace('##AssignedBy##', $event['lastUpdatedPerson'], $template);
    $template = str_replace('##AssignedDesignation##', $event['lastUpdatedDesignation'], $template);

    // ✅ Fix: handle color mapping properly
    $status = trim($event['taskStatus']); // remove trailing spaces
    $color = 'gray'; // default fallback
    if ($status === "Pending") $color = 'orange';
    elseif ($status === "Follow Up") $color = 'yellow';
    elseif ($status === "Completed") $color = 'green';
    elseif ($status === "Not Interested") $color = 'red';

    $template = str_replace('__stautsColor__', $color, $template);

    $Body_message = $template;

    $mail = new PHPMailer(true);
    try {
        // $mail->isSMTP();
        // $mail->Host = 'smtp.gmail.com'; 
        // $mail->SMTPAuth = true;
        // $mail->Username = 'taskenginembw@gmail.com';
        // $mail->Password = 'dwed lrmz jzue bsml'; // Gmail app password
        // $mail->SMTPSecure = 'tls';
        // $mail->Port = 587;

        // $mail->setFrom('taskenginembw@gmail.com', 'Sales Task');

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
        $mail->addAddress($assignedToEmail);
        
        $mail->isHTML(true);
        $mail->Subject = 'Updated Sales Task - ' . $event['taskName'];
        $mail->Body = $Body_message;

        $isSent = $mail->send();
        if ($isSent) {
            echo "✅ Updated task mail sent to {$assignedToEmail}\n";
        } else {
            echo "❌ Mail failed: " . $mail->ErrorInfo . "\n";
        }
        return $isSent;

    } catch (Exception $e) {
        echo "❌ Exception: {$e->getMessage()}\n";
        return false;
    }
}



function sendReminderEmail($event)
{

    global $conn;
    
    // Ensure $_SESSION is set properly
   // $fromEmail = isset($_SESSION['user_email']); 
    $recipient =  $event['assignedTo']; 

    $sql = "SELECT email FROM employee WHERE name= '$recipient'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $assignedToEmail = $row['email']; 
    }
    $template = file_get_contents('Mail/ReminderNotify.html');
    $template = str_replace('##EmployeeName##', $event['assignedTo'], $template);
    $template = str_replace('##AssignmentName##', $event['name'], $template);
    $eventDate = new DateTime($event['date']);
    $formattedDate = $eventDate->format('d-m-Y');
  
    $template = str_replace('##Date##', $formattedDate, $template);
   
    $template = str_replace('##AssignedBy##',  $event['assignedTo'], $template);
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

        // $mail->setFrom( 'taskenginembw@gmail.com', 'Reminder Task');


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
        $mail->addAddress($assignedToEmail);
        
        $mail->isHTML(true);
        $mail->Subject = 'Reminder ' . $event['name'];
        $mail->Body = $Body_message;

        $mail->send();
        //echo "Notification sent for event: {$event['taskName']}\n";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
    }




}


function NewlyAddedProject($event)
{

  global $conn;

    $projectId = $event['ProjectId'];
    $projectName = $event['ProjectName'] ?? 'Project';
    $startDate   = $event['NotifiedDate'] ?? 'N/A';
    $endDate     = $event['NotifiedTime'] ?? 'N/A';
    $taskType    = $event['Platform'] ?? 'N/A';
    $projectDescription = $event['Information'] ?? 'No description provided.';
    $hstatus     = $event['ProjectStatus'] ?? 'N/A';
    $assignedBy  = $event['assignedBy'] ?? 'N/A';

    $template = file_get_contents('Mail/AssignProject.html');
    $mail = new PHPMailer(true);

    try {
        // $mail->isSMTP();
        // $mail->Host = 'smtp.gmail.com';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'taskenginembw@gmail.com';
        // $mail->Password = 'dwed lrmz jzue bsml'; // app password
        // $mail->SMTPSecure = 'tls';
        // $mail->Port = 587;
        // $mail->setFrom('taskenginembw@gmail.com', 'Project Management');

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
        $mail->isHTML(true);

        $allSent = true;

        // 🔑 Send one email per assigned employee
        foreach ($event['employees'] as $emp) {
            $emailBody = str_replace(
                [
                    '##ProjectName##', '##EmployeeName##', '##FinalDate##', '##FinalTime##',
                    '##TaskType##', '##Status##', '##TaggedEmployees##', '##AssignedBy##',
                    '##ProjectDescription##', '##Designation##', '##AssignedPerson##'
                ],
                [
                    $projectName, $emp['name'], $startDate, $endDate,
                    $taskType, $hstatus, implode(', ', array_column($event['employees'], 'name')), $assignedBy,
                    $projectDescription, $emp['designation'], $emp['name']
                ],
                $template
            );

            $mail->clearAddresses();
            $mail->addAddress($emp['email'], $emp['name']);
            $mail->Subject = "New Project Assigned - {$projectName}";
            $mail->Body    = $emailBody;

            if (!$mail->send()) {
                echo "❌ Failed to send mail to {$emp['email']}: " . $mail->ErrorInfo . "\n";
                $allSent = false;
            } else {
                echo "✅ Mail sent to {$emp['email']} for Project ID: {$projectId}\n";
            }
        }

        return $allSent;

    } catch (Exception $e) {
        echo "❌ Exception while sending: {$e->getMessage()}\n";
        return false;
    }
}



function NewlyUpdatedProject($event)
{
    $recipient =  $event['assignedTo']; 
     
    global $conn;

    $projectName = $event['ProjectName'] ?? 'Project';
    $startDate = $event['NotifiedDate'] ?? 'N/A';
    $endDate = $event['NotifiedTime'] ?? 'N/A';
    $taskType = $event['Platform'] ?? 'N/A';
    $recipientName = $event['name'] ?? 'N/A';
    $projectDescription = $event['Information'] ?? 'No description provided.';
    $designation = $event['Platform'] ?? 'Your Designation';
    $assignedToEmail = $event['email'] ?? 'N/A';
    $hstatus = $event['ProjectStatus'] ?? 'N/A';
    $assignedBy = $event['assignedBy'] ?? 'N/A';
    $Recipient_array = explode(",", $event['assignedTo']);
    $recipients =  $Recipient_array ?? [];
    $assignedPerson =  $JWT_adminName?? 'Your Name'; // Adjust accordingly


    $template = file_get_contents('Mail/UpdateProject.html');

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
        $emailBody = str_replace('##Designation##', $designation, $emailBody);
        $emailBody = str_replace('##AssignedPerson##', $assignedPerson, $emailBody);



        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            // $mail->Host = 'smtp.gmail.com';
            // $mail->SMTPAuth = true;
            // $mail->Username = 'taskenginembw@gmail.com';
            // $mail->Password = 'dwed lrmz jzue bsml';
            // $mail->SMTPSecure = 'tls';
            // $mail->Port = 587;

            // $mail->setFrom('taskenginembw@gmail.com', 'Project Management');

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

            $mail->addAddress($assignedToEmail);
            $mail->isHTML(true);
            $mail->Subject = "Project";
            $mail->Body = $emailBody;

            $mail->send();
            $mail->clearAddresses(); // Clear address for the next iteration
            return true;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
            return false;
        }
}
?>
