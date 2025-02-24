<?php
session_set_cookie_params(0);
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the database configuration file
require_once '../config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data from the database
$currentUsername = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username, firstname, lastname, email, password FROM usercredentials WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and fetch data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedUsername = $row['username'];
    $storedFirstname = $row['firstname'];
    $storedLastname = $row['lastname'];
    $storedEmail = $row['email'];
    $storedPassword = $row['password']; // Password hash
} else {
    header("Location: ../User.php?error=User not found");
    exit();
}

$stmt->close();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newUsername = $_POST['username'];
    $newFirstname = $_POST['firstname'];
    $newLastname = $_POST['lastname'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Verify current password
    if (!password_verify($currentPassword, $storedPassword)) {
        header("Location: ../User.php?error=Incorrect current password");
        exit();
    }

    // Update profile data
    $stmt = $conn->prepare("UPDATE usercredentials SET username = ?, firstname = ?, lastname = ?, email = ? WHERE username = ?");
    $stmt->bind_param("sssss", $newUsername, $newFirstname, $newLastname, $newEmail, $currentUsername);

    if ($stmt->execute()) {
        // Update password if provided
        if (!empty($newPassword)) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usercredentials SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashedNewPassword, $newUsername);
            $stmt->execute();
        }
        header("Location: ../User.php?success=profile_updated");
    } else {
        header("Location: ../User.php?error=" . urlencode($stmt->error));
    }

    $stmt->close();
}

$conn->close();
?>
