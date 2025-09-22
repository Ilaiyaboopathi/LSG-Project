<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Auth\Credentials\ServiceAccountCredentials;

// Define the path to your service account credentials
$credentialsPath = 'credentials.json'; 

// Create the Google client and authenticate using the service account
$client = new Client();
$client->setAuthConfig($credentialsPath);
$client->addScope(Google_Service_Drive::DRIVE);

// Create the Drive service
$service = new Google_Service_Drive($client);


if (isset($_GET['file'])) {
    
    $fileId = $_GET['file'];

    // Fetch file metadata
    try {
        $fileMetadata = $service->files->get($fileId);
        $fileName = $fileMetadata->getName();
        $mimeType = $fileMetadata->getMimeType();

        // Get the appropriate file extension from the MIME type
        $fileExtension = getExtensionFromMimeType($mimeType);

        // Set the appropriate headers for downloading the file
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '.' . $fileExtension . '"');
        header('Content-Transfer-Encoding: binary');

        // Fetch the file content with the proper 'alt' parameter to get raw data
        $content = $service->files->get($fileId, array('alt' => 'media'));

        // Output the file content to the browser
        echo $content->getBody()->getContents();

    } catch (Exception $e) {
        echo 'Error downloading the file: ' . $e->getMessage();
    }
}


function getExtensionFromMimeType($mimeType) {
    $mimeToExtension = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/zip' => 'zip',
        'text/plain' => 'txt',
        'application/octet-stream' => 'bin',
        'audio/mpeg' => 'mp3',
        // Add more MIME types and extensions as needed
    ];

    return isset($mimeToExtension[$mimeType]) ? $mimeToExtension[$mimeType] : 'file'; // Default to 'file' if no match
}


?>