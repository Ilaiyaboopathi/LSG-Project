<?php
require '../../data/dbconfig.php'; // Ensure this file sets up $mysqli connection

$username = $_GET['username'] ?? null; // Get the email from the query parameter

if (!$username) {
    http_response_code(400);
    echo json_encode(['error' => 'Employee email is required']);
    exit;
}

// Prepare and execute the query
$stmt = $conn->prepare("SELECT * FROM reminder_notification WHERE assignedBy = ? AND isCancelled = 1");
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
$conn->close();

// Return the notifications as JSON
echo json_encode($notifications);
?>
