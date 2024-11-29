<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lgutestdb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Predefined admin code for validation
$predefinedAdminCode = "adminCRFMScode";

// User input from registration form
$username = $_POST["username"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$barangay = $_POST["barangay"];
$password = $_POST["password"];
$adminCode = $_POST["admin_code"];

// Check if the admin code matches the predefined code
if ($adminCode !== $predefinedAdminCode) {
    header("Location: ../AdminLogin.html?error=invalid_admin_code");
    exit();
}

// Check if the username already exists in the admincredentials table
$stmt = $conn->prepare("SELECT * FROM admincredentials WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: ../AdminLogin.html?error=username_taken");
    exit();
}

// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the new admin data into the admincredentials table
$stmt = $conn->prepare("INSERT INTO admincredentials (username, firstname, lastname, barangay, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $firstname, $lastname, $barangay, $hashedPassword);

if ($stmt->execute()) {
    header("Location: ../AdminLogin.html?adminsuccess=registered"); 
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
