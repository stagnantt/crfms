<?php
// Include database connection
require 'config.php';
require '../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Asia/Manila');

function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true; 
        $mail->Username = 'shinoyaotowama@gmail.com'; 
        $mail->Password = 'qhxibprawqfytwwc'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('shinoyaotowama@gmail.com', 'Admin Support');
        $mail->addAddress($email);

        // Content
        $resetLink = "https://citizenrequest.lgu2.com/user/reset_password.php?token=$token"; 
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body    = 'Click the link to reset your password: <a href="' . $resetLink . '">Reset Password</a>';
        $mail->AltBody = 'Click the link to reset your password: ' . $resetLink;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; 

    // Token expiry, 1hour only
    $token = bin2hex(random_bytes(16));
    $token_expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Update the usercredentials table
    $stmt = $conn->prepare("UPDATE usercredentials SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $token_expiry, $email);

    if ($stmt->execute()) {
        // Send reset email
        sendResetEmail($email, $token);
    } else {
        echo "Error updating token: " . $conn->error;
    }
}
?>
