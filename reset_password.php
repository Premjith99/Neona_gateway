<?php
session_start();

// Function to read username and password from file
function readCredentialsFromFile($filename) {
    $credentials = [];
    $file = fopen($filename, "r");
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $parts = explode(':', $line);
            if (count($parts) === 2) {
                $credentials[trim($parts[0])] = trim($parts[1]);
            }
        }
        fclose($file);
    }
    return $credentials;
}

// Function to write username and password to file
function writeCredentialsToFile($filename, $credentials) {
    file_put_contents($filename, '');
    foreach ($credentials as $username => $password) {
        file_put_contents($filename, "$username:$password\n", FILE_APPEND | LOCK_EX);
    }
}

$filename = "credentials.txt"; // File containing usernames and passwords

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentUsername = $_POST["current_username"];
    $currentPassword = $_POST["current_password"];
    $newUsername = $_POST["new_username"];
    $newPassword = $_POST["new_password"];

    // Read credentials from file
    $credentials = readCredentialsFromFile($filename);

    // Validate current username and password
    if (isset($credentials[$currentUsername]) && $credentials[$currentUsername] === $currentPassword) {
        // Remove old credentials
        unset($credentials[$currentUsername]);

        // Update credentials with new username and password
        $credentials[$newUsername] = $newPassword;

        // Write updated credentials to file
        writeCredentialsToFile($filename, $credentials);

        // Authentication successful
        $_SESSION["username"] = $newUsername;
        header("Location: /login.html"); // Redirect to a welcome page
        exit();
    } else {
        // Authentication failed
          echo "Invalid current username or password";
    }
}
?>
