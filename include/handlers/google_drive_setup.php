<?php

require '../../vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

// Start session (if needed)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Google Drive API credentials file (ensure this file is in a secure location)
$credentialsPath = '../../credentials.json'; 

// Google Drive Folder ID where files will be stored
$folderId = '10hqIlAD29RwlUNlQLNvUKu0bAg7ekE9t'; 

// Initialize Google Client
$client = new Client();
$client->setAuthConfig($credentialsPath);
$client->setScopes([Drive::DRIVE_FILE]); // Grants permission to upload files to Drive
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Check and refresh token if necessary
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Refresh the token if expired
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        $_SESSION['access_token'] = $client->getAccessToken();
    }
}

// Create Drive Service
$service = new Drive($client);

?>
