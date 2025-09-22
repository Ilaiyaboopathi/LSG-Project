<?php
require 'vendor/autoload.php';
require 'data/dbconfig.php'; // Ensure your database configuration is included
date_default_timezone_set('Asia/Kolkata');

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use React\EventLoop\Loop;
use React\Socket\Server as ReactSocketServer;

class ReminderServer implements MessageComponentInterface
{
    protected $clients;  // Property for Client Websocket Connection
    protected $conn; // Property for database connection


    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
        $this->clients = new \SplObjectStorage;
        // Start a timer that runs every 5 minutes (300 seconds)
        $loop = Loop::get();  // Use the global event loop
        $loop->addPeriodicTimer(20, function () {
            try {
                $this->RecurringAutoUpdateDbTable();
                $this->checkReminders();
                $this->checkEmail();
                $this->checkRecurringReminders();
                $this->checkDeadline();
                $this->fetchClientJobs();
                $this->fetchClientProjects();
                $this->fetchNewlyAddedProject();
                //$this->fetchUpdatedAddedProject();
            } catch (\Exception $e) {
                echo 'Error in periodic task: ' . $e->getMessage();
            }
        });
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";

        // Send Recurring Reminder 
        // $this->sendRecurringRemindersToClient($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        if ($msg === 'ping') {
            $from->send(json_encode(['type' => 'HeartBeat', 'data' => "pong"]));
            echo "Ping received, sending pong to ({$from->resourceId})\n";
        } else {
            echo "Received message: " . $msg . "\n";
        }
    }


    protected function NotifyClientProject($data)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'NotifyClientProject', 'data' => $data]));
        }
    }



    private function fetchClientProjects()
    {
        $sql = "SELECT 
                    cp.id AS project_id, 
                    cp.*, 
                    e.id AS creator_id, 
                    e.name AS creator_name, 
                    e.picture AS creator_picture
                FROM client_projects AS cp
                JOIN employee AS e ON cp.created_by = e.id
                WHERE cp.update_notified = 'added'";

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Fetch assigned users for the project
                $assigneesSql = "SELECT 
                                    e.id AS assignee_id, 
                                    e.name AS assignee_name, 
                                    e.picture AS assignee_picture 
                                FROM client_project_assignees AS cpa
                                JOIN employee AS e ON cpa.user_id = e.id
                                WHERE cpa.project_id = ?";

                $stmt = $this->conn->prepare($assigneesSql);
                $stmt->bind_param("i", $row['project_id']);
                $stmt->execute();
                $assigneesResult = $stmt->get_result();

                $assignees = [];
                while ($assignee = $assigneesResult->fetch_assoc()) {
                    $assignees[] = $assignee;
                }

                $row['assignees'] = $assignees; // Add assignees to the project data

                // Call the notification function with project details and assignees
                $this->NotifyClientProject($row);

                // Update the notified status
                $updateSql = "UPDATE client_projects SET update_notified = 'notified' WHERE id = ?";
                $updateStmt = $this->conn->prepare($updateSql);
                $updateStmt->bind_param("i", $row['project_id']);
                $updateStmt->execute();
            }
        }
    }




    protected function NotifyClientJob($data)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'NotifyClientJob', 'data' => $data]));
        }
    }


    private function fetchClientJobs()
    {
        $sql = "SELECT 
                    ja.id AS job_id, 
                    ja.*, 
                    e.id AS employee_id, 
                    e.name AS employee_name, 
                    e.picture AS employee_picture
                FROM job_assignments AS ja
                JOIN employee AS e ON ja.user_id = e.id
                WHERE ja.update_notified = 'added'";


        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Call the notification function
                $this->NotifyClientJob($row);

                // Update the notified status
                $updateSql = "UPDATE job_assignments SET update_notified = 'notified' WHERE id = ?";
                $stmt = $this->conn->prepare($updateSql);
                $stmt->bind_param("i", $row['id']);
                $stmt->execute();
            }
        }
    }




    public function onClose(ConnectionInterface $conn)
    {
        //$this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }


    // --------------------------------Sales Task Onmessage Reminder Code Start----------------------------------


    protected function notifyUsers($data)
    {
        foreach ($this->clients as $client) {
            //$client->send(json_encode($data));

            $client->send(json_encode(['type' => 'salesTask', 'data' => $data]));
        }
    }

    private function fetchRemindersFromDatabase()
    {

        $sql = "SELECT t.*, ev.details AS taskDetails,ev.status AS taskStatus
        FROM task t
        JOIN event ev ON ev.task_id = t.taskid
        JOIN employee e ON e.name = t.employeeName
        WHERE t.date = CURDATE() AND e.isOnline = 1";

        $result = $this->conn->query($sql);
        $events = [];

        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    private function fetchEmail()
    {

        $sql = "SELECT t.*, ev.details AS taskDetails,ev.status AS taskStatus,ev.lastUpdatedPerson ,ev.lastUpdatedDesignation,ev.isUpdated
        FROM task t
        JOIN event ev ON ev.task_id = t.taskid
        JOIN employee e ON e.name = t.employeeName
        WHERE t.date = CURDATE()";

        $result = $this->conn->query($sql);
        $events = [];

        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    private function checkEmail()
    {
        //echo ("Checking reminders...\n");
        $events = $this->fetchEmail();
        $currentTimestamp = (new \DateTime())->format('Y-m-d H:i:s');
        foreach ($events as $event) {

            // $delayInterval = new \DateTime($event['notification_delay']);
            // $delayMinutes = $delayInterval->format('i') + ($delayInterval->format('H') * 60); // Convert to minutes
            // $eventTimestamp = (new \DateTime($event['timestamp']))->modify("-{$delayMinutes} minutes")->format('Y-m-d H:i:s');
            $eventTimestamp = (new \DateTime($event['timestamp']))->format('Y-m-d H:i:s');
            require_once 'taskmail.php';


            if ($currentTimestamp >= $eventTimestamp &&  $event['IsEmailSend'] === '0' &&  $event['isUpdated'] === '0') {
                echo (" Event email timestamp: {$eventTimestamp}\n");
                if (sendEventNotification($event)) {
                    echo "Email sent successfully for event ID: {$event['id']}\n";
                    $this->markEmailAsSent($event['id']);
                } else {
                    echo "Failed to send email for event ID: {$event['id']}\n";
                }
            }

            if ($currentTimestamp >= $eventTimestamp &&  $event['IsEmailSend'] === '0' &&  $event['isUpdated'] === '1') {
                echo (" Event email timestamp: {$eventTimestamp}\n");
                if (sendUpdatedEventNotification($event)) {
                    echo "Email sent successfully for event ID: {$event['id']}\n";
                    $this->markEmailAsSent($event['id']);
                } else {
                    echo "Failed to send email for event ID: {$event['id']}\n";
                }
            }
        }
    }


    private function checkReminders()
    {
        //echo ("Checking reminders...\n");
        $events = $this->fetchRemindersFromDatabase();
        $currentTimestamp = (new \DateTime())->format('Y-m-d H:i:s');
        //echo ("Current timestamp {$currentTimestamp}\n");
        foreach ($events as $event) {
            echo (" Event timestamp: {$event['timestamp']}\n");
            // Check if the current timestamp matches the event timestamp
            if ($currentTimestamp >= $event['timestamp'] &&  $event['isAlertsend'] === '0') {
                echo ("Sending notification for event: {$event['taskName']}\n");

                // Notify users
                $this->notifyUsers($event);
                $this->markAlertAsSent($event['id']);
            }
        }
    }


    private function markAlertAsSent($eventId)
    {
        $sql = "UPDATE task SET isAlertsend = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
    }

    private function markEmailAsSent($eventId)
    {
        $sql = "UPDATE task SET IsEmailSend = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
    }

    // --------------------------------Sales Task Onmessage Reminder Code End----------------------------------




    // --------------------------------Send Recurring Reminder Code Start----------------------------------

    protected function sendRecurringRemindersToClient($data)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'recurringReminders', 'data' => $data]));
        }
    }



    // ================ Insert Requiring Notification to Table & Update New Reminder Date Time (20 Secs) =====================

    protected function RecurringAutoUpdateDbTable()
    {

        global $conn;

        $sql = "SELECT * FROM reminder WHERE reminderdatetime <= NOW() AND notified = 0 AND isEnable = 0";
        $result = $conn->query($sql);

        // $RecurringReminder = [];
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $recurring_value = $row['recurring'];
            $name = $row['assignment_name'];
            $date = $row['date'];
            $duration = $row['alert_duration'];
            $formattedDate = date('Y-m-d');
            $assignedTo = explode(',', $row['assignedBy'] . ',' . $row['tagemployee']);;
            $reminderdatetime = $row['reminderdatetime'];
            $isenable = $row['isEnable'];

            if ($recurring_value == 0 && $date == $formattedDate && $isenable == 0) {

                foreach ($assignedTo as $assignee) {
                    $assignee = trim($assignee); // Trim whitespace

                    $sql = "INSERT INTO recurring_queued 
                    (name, date, duration, recurring, createdon, assignedTo) 
                    VALUES 
                    ('$name', '$reminderdatetime', '$duration', '$recurring_value', '$formattedDate', '$assignee')";

                    // Execute the insertion
                    if (!$conn->query($sql)) {
                        // Handle the error if needed
                        error_log("Insert failed: " . $conn->error);
                    }
                }
                // Update reminder after all insertions
                $updateSql = "UPDATE reminder SET notified = 1 WHERE id = $id";
                $conn->query($updateSql);
            }

            if ($recurring_value == 1 && $isenable == 0) {
                foreach ($assignedTo as $assignee) {
                    $assignee = trim($assignee); // Trim whitespace

                    $sql = "INSERT INTO recurring_queued 
                        (name, date, duration, recurring, createdon, assignedTo) 
                        VALUES 
                        ('$name', '$reminderdatetime', '$duration', '$recurring_value', '$formattedDate', '$assignee')";

                    // Execute the insertion
                    if (!$conn->query($sql)) {
                        // Handle the error if needed
                        error_log("Insert failed: " . $conn->error);
                    }
                }



                $reminderDateTime = new DateTime($reminderdatetime);



                $reminderDateTime->modify("+$duration minutes");
                $nextDate = $reminderDateTime->format('Y-m-d H:i:s');




                $updateSql = "UPDATE reminder SET reminderdatetime = '$nextDate' WHERE id = $id";
                $conn->query($updateSql);
            }
        }

        // Broadcast the message to all connected clients   - ** Developer Debug for Understanding

        // foreach ($this->clients as $client) {
        //     $client->send(json_encode(['type' => 'recurringReminders', 'data' => $RecurringReminder]));
        // }

        // echo "Data broadcasted to all clients at " . date('Y-m-d H:i:s') . "\n";
    }

    // ================ Insert Requiring Notification to Table & Update New Reminder Date Time (20 Secs) =====================


    private function fetchRecurringQueuedReminder()
    {
        global $conn;

        $sql = "
        SELECT rq.*, e.name AS assignedByName
        FROM recurring_queued rq
        JOIN employee e ON e.name = rq.assignedTo
        WHERE rq.date <= NOW() AND e.isOnline = 1 AND rq.inAlertSend = 0";
        $result = $conn->query($sql);

        $RecurringReminder = [];
        while ($row = $result->fetch_assoc()) {
            $RecurringReminder[] = $row;
        }

        return $RecurringReminder;
    }

    private function checkRecurringReminders()
    {
        echo ("Checking recurring reminders...\n");
        $RecurringReminder = $this->fetchRecurringQueuedReminder();
        require_once 'taskmail.php';
        foreach ($RecurringReminder as $event) {
            if ($event['isEmailSend'] === '0') {
                echo ("Sending Email for event: {$event['name']}\n");
                sendReminderEmail($event);
                $this->markReminderEmail($event['id']);
            }

            if ($event['inAlertSend'] === '0') {
                //echo ("Sending notification for event: {$event['name']}\n");
                // Notify users
                $this->sendRecurringRemindersToClient($event);
                $this->markReminderAlert($event['id']);
            }
        }
    }


    private function markReminderAlert($eventId)
    {
        $sql = "UPDATE recurring_queued SET inAlertSend = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
    }


    private function markReminderEmail($eventId)
    {
        $sql = "UPDATE recurring_queued SET isEmailSend = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
    }


    // --------------------------------Send Recurring Reminder Code End----------------------------------
    /// ----------------------------------Dealine project starts ----------------------------------


    private function fetchNewlyAddedProject()
    {
        $sql = "
        SELECT 
            np.*, 
            e.id as emp_id,
            e.name as emp_name,
            e.email,
            e.designation,
            cp.status AS ProjectStatus, 
            cp.created_by AS assignedBy
        FROM notifyproject np
        JOIN client_projects cp 
            ON np.ProjectId = cp.id
        JOIN client_project_assignees cpa 
            ON cp.id = cpa.project_id
        JOIN employee e 
            ON cpa.user_id = e.id
        WHERE np.NotifiedDate = CURDATE()
          AND np.isAlertSend = 0
    ";

        $result = $this->conn->query($sql);
        if (!$result) {
            echo "❌ SQL error: " . $this->conn->error . "\n";
            return;
        }

        // Group by project, collect employees
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $pid = $row['ProjectId'];
            if (!isset($events[$pid])) {
                $events[$pid] = $row;
                $events[$pid]['employees'] = [];
            }
            $events[$pid]['employees'][] = [
                'id' => $row['emp_id'],
                'name' => $row['emp_name'],
                'email' => $row['email'],
                'designation' => $row['designation']
            ];
        }

        $currentTimestamp = (new \DateTime())->format('Y-m-d H:i:s');

        foreach ($events as $event) {
            $NotifiedDateTime = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $event['NotifiedDate'] . ' ' . $event['NotifiedTime']
            );
            $NotifiedTimestamp = $NotifiedDateTime->format('Y-m-d H:i:s');

            require_once 'taskmail.php';

            if ($currentTimestamp >= $NotifiedTimestamp && $event['isAlertSend'] === '0') {
                echo (" Event Project email timestamp: {$NotifiedTimestamp}\n");

                if (NewlyAddedProject($event)) {
                    echo "✅ Email(s) sent successfully for event ID: {$event['id']}\n";
                    $this->NotifyProject($event);
                    $this->markNotifyProjectAsSent($event['id']);
                } else {
                    echo "❌ Failed to send email for event ID: {$event['id']}\n";
                }
            } else {
                echo "⏳ Skipped sending for event ID: {$event['id']}\n";
            }
        }
    }



    // private function fetchUpdatedAddedProject()
    // {
    //     $sql = "SELECT np.*, e.email, pj.ProjectStatus, pj.assignedTo, pj.assignedBy, pj.id as org_id
    //     FROM notifyproject np 
    //     JOIN employee e ON np.Name = e.name 
    //     JOIN  pj ON np.ProjectId = pj.ProjectId 
    //     WHERE pj.is_updated = 'Yes'";

    //     $result = $this->conn->query($sql);
    //     $events = [];
    //     $Org_id = "";

    //     while ($row = $result->fetch_assoc()) {
    //         $events[] = $row;
    //     }


    //     if (sizeof($events) != 0) {
    //         foreach ($events as $event) {

    //             require_once 'taskmail.php';

    //             echo (" Event Update Project start");
    //             if (NewlyUpdatedProject($event)) {
    //                 echo "Email sent successfully for event ID: {$event['org_id']}\n";
    //                 $this->NotifyProject($event);
    //             } else {
    //                 echo "Failed to send email for event ID: {$event['org_id']}\n";
    //             }
    //             $Org_id = $event['org_id'];
    //         }
    //         $this->MarkUpdatedStatusProject($Org_id);
    //     }
    // }


    private function fetchDeadline()
    {
        $sql = "SELECT a.*, e.name AS assignedByName
                FROM assignproject a
                JOIN employee e ON e.name = a.Name
                WHERE a.DeadlineDate = CURDATE() AND e.isOnline = 1";

        $result = $this->conn->query($sql);
        $events = [];

        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    private function checkDeadline()
    {

        $events = $this->fetchDeadline();
        $currentTimestamp = (new \DateTime())->format('Y-m-d H:i:s');

        foreach ($events as $event) {

            $deadlineDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $event['DeadlineDate'] . ' ' . $event['DeadlineTime']);


            if ($deadlineDateTime !== false) {
                $deadlineTimestamp = $deadlineDateTime->format('Y-m-d H:i:s');


                if ($currentTimestamp >= $deadlineTimestamp && $event['isAlertSend'] === '0') {
                    echo ("Sending notification for event: {$event['Name']}\n");

                    $this->notifyDeadline($event);
                    $this->markDeadlineAsSent($event['id']);
                }
            } else {
                echo ("Invalid deadline format for event: {$event['Name']}\n");
            }
        }
    }

    protected function notifyDeadline($data)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'DeadlineProject', 'data' => $data]));
        }
    }

    protected function NotifyProject($data)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'ProjectDetails', 'data' => $data]));
        }
    }

    private function markDeadlineAsSent($eventId)
    {
        $sql = "UPDATE assignproject SET isAlertSend = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
    }

    private function markNotifyProjectAsSent($eventId)
    {
        $sql = "UPDATE notifyproject SET isAlertSend = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
    }




    private function MarkUpdatedStatusProject($eventId)
    {
        $sql = "UPDATE project SET is_updated = 'No' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $eventId);
        if ($stmt->execute()) {

            // echo("dlkjfdsajflds");
        }
        $stmt->close();
    }
}


$loop = Loop::get();

$socket = new ReactSocketServer('0.0.0.0:61200', $loop);


// Set up the WebSocket server
$webSocket = new ReminderServer($conn);
$server = new IoServer(
    new HttpServer(
        new WsServer($webSocket)
    ),
    $socket, // Pass the socket server as the second argument
    $loop
);

// Run the WebSocket server
$loop->run();
