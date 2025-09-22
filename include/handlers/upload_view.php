<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

class GoogleDriveAPI {

    private $client;
    private $service;
    private $folderId;
    private $result;

    // Constructor: Initialize the client, service, and folderId
    public function __construct($credentialsPath, $folderId) {
        $this->folderId = $folderId;

        // Initialize Google Client
        $this->client = new Client();
        $this->client->setAuthConfig($credentialsPath);
        $this->client->addScope(Google_Service_Drive::DRIVE);

        // Initialize Google Drive Service
        $this->service = new Google_Service_Drive($this->client);

        // Initialize the result array
        $this->result = [];
    }

    // Method to fetch files from Google Drive
    public function fetchFiles() {
        try {
            $optParams = [
                'q' => "'" . $this->folderId . "' in parents", // Query to filter by folder ID
                'fields' => 'nextPageToken, files(id, name, mimeType)', // Fields to retrieve
            ];

            $pageToken = null;
            do {
                // Fetch the files from the Drive
                $response = $this->service->files->listFiles(array_merge($optParams, ['pageToken' => $pageToken]));
                
                // Loop through the response files and add them to result
                foreach ($response->files as $file) {
                    $this->result[] = [
                        'name' => $file->getName(),
                        'file_url' => 'https://drive.google.com/file/d/' . $file->getId() . '/view',
                        'mime_type' => $file->getMimeType(),
                        'id' => $file->getId()
                    ];
                }

                // Set the next page token for pagination
                $pageToken = $response->nextPageToken;

            } while ($pageToken);  // Loop to fetch more files if they exist

        } catch (Exception $e) {
            echo 'Error fetching files: ', $e->getMessage();
        }
    }

    // Getter for the result variable (which contains all the files)
    public function getResult() {
        return $this->result;
    }

    // Method to save the result to a JSON file
    public function saveToJson($filePath) {
        $data = ['data' => $this->result];
        $json = json_encode($data, JSON_PRETTY_PRINT);

        if (file_put_contents($filePath, $json)) {
            //echo "Data saved successfully to $filePath";
        } else {
            echo "Failed to save data.";
        }
    }
}

?>
