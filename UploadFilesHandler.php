<?php
header("Content-Type: application/json");

$receivedFiles = [];

// Debug: Print uploaded files
foreach ($_FILES as $key => $fileGroup) {
    foreach ($fileGroup['name'] as $index => $fileName) {
        $receivedFiles[] = [
            "field_name" => $key,
            "file_name" => $fileName,
            "temp_name" => $fileGroup['tmp_name'][$index],
            "size" => $fileGroup['size'][$index],
            "type" => $fileGroup['type'][$index],
            "error" => $fileGroup['error'][$index]
        ];
    }
}

echo json_encode([
    "status" => "received",
    "message" => "File data received successfully",
    "files" => $receivedFiles
]);
?>
