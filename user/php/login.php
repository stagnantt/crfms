<?php
session_start();
require_once '../config.php';

$username = $_POST["username"];
$password = $_POST["password"];
$stmt = $conn->prepare("SELECT * FROM usercredentials WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user["password"])) {
        // Successful login
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        header("Location: ../Home.php");
        exit();
    } else {
        header("Location: ../../index.html?error=password");
        exit();
    }
} else {
    header("Location: ../../index.html?error=user_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>