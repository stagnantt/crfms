<?php
// Include database connection
require 'config.php'; // Ensure this file contains the correct database connection code

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token and expiry
    $stmt = $conn->prepare("SELECT email FROM usercredentials WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

if ($result->num_rows > 0) {
        echo '<!DOCTYPE html>
              <html lang="en">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Password Reset</title>
                  <link rel="stylesheet" href="../style.css">
                  <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>

              </head>
              <body>
                  <div class="update-password-container">
                      <form method="POST" action="php/update_password.php" id="resetPasswordForm">
                          <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                          <label for="new_password">New Password:</label>
                          <div class="input-box">
                              <input type="password" name="new_password" id="new_password" 
                                     pattern="(?=.*\d).{8,}" 
                                     title="Password must be at least 8 characters long and include at least 1 number" 
                                     required>
                          </div>
                          <label for="confirm_password">Confirm Password:</label>
                          <div class="input-box">
                              <input type="password" name="confirm_password" id="confirm_password" 
                                     pattern="(?=.*\d).{8,}" 
                                     title="Password must be at least 8 characters long and include at least 1 number" 
                                     required>
                          </div>
                          <input type="submit" value="Reset Password">
                      </form>
                  </div>
                  <script>
                      document.getElementById("resetPasswordForm").addEventListener("submit", function(event) {
                          const newPassword = document.getElementById("new_password").value;
                          const confirmPassword = document.getElementById("confirm_password").value;

                          if (newPassword !== confirmPassword) {
                              alert("Passwords do not match. Please try again.");
                              event.preventDefault(); // Prevent form submission if passwords do not match
                          }
                      });
                  </script>
              </body>
              </html>';
    } else {
        echo 'Invalid or expired token.';
    }
} else {
    echo 'No token provided.';
}
?>
