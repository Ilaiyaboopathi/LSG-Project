<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile; // Import the DriveFile class
use Google\Auth\Credentials\ServiceAccountCredentials;
//use finfo; // For better mime type detection

session_start();

$credentialsPath = 'credentials.json';
$folderId = '10hqIlAD29RwlUNlQLNvUKu0bAg7ekE9t';

$client = new Client();
$client->setAuthConfig($credentialsPath);
$client->addScope(Drive::DRIVE);
$service = new Drive($client);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] == "AddDocs") {
    try {
        $Name = $_POST['name'] ?? null;
        $TaggedEmployees = $JWT_adminName;
        if (!empty($_POST['TaggedEmployees'])) {
            $TaggedEmployees .= ',' . $_POST['TaggedEmployees'];
        }
        $file = $_FILES['file'] ?? null;
        $addedBy = $JWT_adminName;

        if (empty($Name) || empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400); // Bad Request
            echo "Please provide a name and a valid file.";
            exit;
        }

        $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $Name); // Include dots for extensions
        $fileName = preg_replace('/\s+/', '_', $fileName);
        $fileName = trim($fileName, "_");

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        $driveFile = new DriveFile();
        $driveFile->setName($fileName);
        $driveFile->setDescription('Uploaded via form');
        $driveFile->setMimeType($mimeType);
        $driveFile->setParents([$folderId]);

        $data = file_get_contents($file['tmp_name']);

        $createdFile = $service->files->create($driveFile, [
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart'
        ]);

        http_response_code(200); // OK
        echo "success";

    } catch (Google\Service\Exception $e) {
        http_response_code(500); // Internal Server Error
        error_log("Google Drive API Error: " . $e->getMessage()); // Log the error
        echo "An error occurred during file upload. Please check the logs.";
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        error_log("File Upload Error: " . $e->getMessage());
        echo "An unexpected error occurred.";
    }
} else {
    http_response_code(400); // Bad Request
    echo "Invalid request.";
}
?>