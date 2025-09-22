<?php

require '../../data/dbconfig.php';
session_start();

if (isset($_POST['id'])) {
    $documentID = $_POST['id'];

    // Query to fetch the document details
    $stmt = $conn->prepare("SELECT * FROM docs_upload WHERE id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Get the file URL and other relevant information
        $filePath = $row['file_url'];
        $deletedBy = $JWT_adminName;
        $deletedDateTime = date('Y-m-d H:i:s');

        // Insert into deletelog table before deletion
        $logStmt = $conn->prepare("INSERT INTO deletelog (fileID, fileURL, deleteBy, deletedDateTime) VALUES (?, ?, ?, ?)");
        $logStmt->bind_param("isss", $documentID, $filePath, $deletedBy, $deletedDateTime);

        // Execute the log insert query
        if ($logStmt->execute()) {

            // If log is inserted successfully, proceed to delete the file
            if (file_exists($filePath)) {
                unlink($filePath);  // Delete the file from the server
            }

            // Delete the document record from docs_upload table
            $stmt = $conn->prepare("DELETE FROM docs_upload WHERE id = ?");
            $stmt->bind_param("i", $documentID);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database deletion failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Log insertion failed']);
        }

    } else {
        // Document not found
        echo json_encode(['success' => false, 'message' => 'Document not found']);
    }
} else {
    // No document ID provided
    echo json_encode(['success' => false, 'message' => 'No document ID provided']);
}

?>

