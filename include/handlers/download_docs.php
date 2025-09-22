<?php

require '../../data/dbconfig.php';

  // --------- Download the File from Server Start -------------------

  if (isset($_GET['download'])) {

    $id = intval($_GET['download']); // Sanitize and validate the ID
    
    $sql = "SELECT * FROM docs_upload WHERE id='$id' ORDER BY id DESC";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Example file URL from the database
        $file_url = htmlspecialchars($row['file_url']); // e.g., "uploads/Billing.jpg"

        // Prepend the directory path
        $fullPath = __DIR__ . '/' . $file_url; // Use the absolute path to the file

        // Check if the file exists
        if (file_exists($fullPath)) {
            // Get the file extension
            $file_extension = pathinfo($fullPath, PATHINFO_EXTENSION);
            $file_extension = strtolower($file_extension); // Convert to lowercase

            // Define MIME types
            $mimeTypes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'txt' => 'text/plain',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt' => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'zip' => 'application/zip',
                'mp4' => 'video/mp4',
                // Add more as needed
            ];

            // Set the Content-Type based on the extension
            $contentType = isset($mimeTypes[$file_extension]) ? $mimeTypes[$file_extension] : 'application/octet-stream';

            // Set headers to prompt download
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $contentType);
            header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullPath));
            
            // Read the file
            readfile($fullPath);
            exit;
        } else {
            echo "File does not exist.";
        }
    }
}

// --------- Download the File from Server End -------------------


?>