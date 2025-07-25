<?php

// Check if the request method is POST
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    // Get the raw POST data
    $jsonData = file_get_contents('php://input');

    // Check if the data is valid JSON
    if (
        json_decode($jsonData) !== null
    ) {
        // Generate a hash for the file name
        $hash = hash('sha256', $jsonData);

        // Define the file path
        $filePath = __DIR__ . '/../data/' . $hash . '.json';

        // Encrypt the JSON data using AES-256
        $key = openssl_random_pseudo_bytes(32); // 256-bit key
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt(
            $jsonData,
            'aes-256-cbc',
            $key,
            0,
            $iv
        );

        // Combine IV and encrypted data
        $encryptedData = base64_encode($iv . $encryptedData);

        // Save the encrypted data to the file
        if (
            file_put_contents($filePath, $encryptedData) !== false
        ) {
            echo json_encode(['status' => 'success', 'file' => $hash . '.json']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save the file']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

?>