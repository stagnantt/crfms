<?php
require '../config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT email FROM usercredentials WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_stmt = $conn->prepare("UPDATE usercredentials SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);
        
        if ($update_stmt->execute()) {
            header("Location: ../../index.html?password-reset-success");
            exit(); 
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>

