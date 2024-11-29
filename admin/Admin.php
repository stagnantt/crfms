<?php
session_set_cookie_params(0); // Sets the session cookie to expire when the browser is closed
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.html");
    exit();
}

// Initialize error and success messages
$errorMessage = "";
$successMessage = "";

// Include the database connection details
include '../user/config.php'; // Adjust the path if necessary
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve admin data from the database
$currentUsername = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username, firstname, lastname, barangay FROM admincredentials WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and fetch data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = htmlspecialchars($row['username']);
    $firstname = htmlspecialchars($row['firstname']);
    $lastname = htmlspecialchars($row['lastname']);
    $barangay = htmlspecialchars($row['barangay']);
} else {
    header("Location: AdminLogin.html");
    exit();
}

$stmt->close();

// Check for error or success messages
if (isset($_GET['error'])) {
    $errorMessage = htmlspecialchars($_GET['error']);
} elseif (isset($_GET['success'])) {
    $successMessage = "Profile updated successfully!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>Admin Profile Edit</title>
</head>
<body>

<div class="container">
    <!-- Side bar -->
    <aside id="sidebar">
      <div class="sidebar">
            <a href="AdminDashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="Admin.php" class="active">
                <span class="material-symbols-outlined">shield_person</span>
                <h3>Admin</h3>
            </a>
            <a href="AdminAnnouncement.php">
                <span class="material-symbols-outlined">add_box</span>
                <h3>Announcements</h3>
            </a>
            <a href="Reviewsubmissions.php">
                <span class="material-symbols-outlined">rate_review</span>
                <h3>Review Request & Feedback</h3>
            </a>
        </div>
    </aside>
    <!-- Sidebar end -->

    <!-- Main content per page -->
    <div class="main--content">
        <h2>Edit Admin Profile</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="php/admin_update_profile.php" method="POST">
            <div class="profile-section">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>

            <div class="profile-section">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required>
            </div>

            <div class="profile-section">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required>
            </div>

            <div class="profile-section">
                <label for="barangay">Barangay:</label>
                <input type="text" id="barangay" name="barangay" value="<?php echo $barangay; ?>" required>
            </div>

            <div class="profile-section password-container">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
                <button type="button" class="btn-show" onclick="togglePasswordVisibility('current_password')">Show</button>
            </div>

            <div class="profile-section password-container">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password"
                    pattern="(?=.*\d).{8,}"
                    title="Password must be at least 8 characters long and include at least 1 number">
                <button type="button" class="btn-show" onclick="togglePasswordVisibility('new_password')">Show</button>
            </div>

            <div class="updatebutton">
                <button type="submit">Update Profile</button>
            </div>
        </form>

        <!-- Incorrect Password Popup -->
        <div class="passwordpopup" id="passwordpopup" style="display: <?php echo !empty($errorMessage) ? 'block' : 'none'; ?>;">
            <div class="popup-content">
                <span class="popup-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span>
                <img src="../images/error.png" alt="Error Icon" class="popup-icon">
                <p><?php echo $errorMessage; ?></p>
            </div>
        </div>

        <!-- Profile Updated Popup -->
        <div class="successpopup" id="successpopup" style="display: <?php echo !empty($successMessage) ? 'block' : 'none'; ?>;">
            <div class="popup-content">
                <span class="popup-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span>
                <img src="../images/success.png" alt="Success Icon" class="popup-icon">
                <p><?php echo $successMessage; ?></p>
            </div>
        </div>
    </div>
    
    <nav class="navigation">
        <!-- Left section: Close button and Logo -->
        <div class="left-section">
            <div class="close" id="toggle-btn" tabindex="0" aria-label="Toggle menu">
                <span class="material-icons-sharp">menu_open</span>
            </div>
            <div class="logo">
                <a href="AdminDashboard.php">
                    <img src="../images/crfms.png" alt="LGU Logo">
                </a>
            </div>
        </div>
        <!-- Right section: Theme toggle and Sign up button -->
        <div class="right-section">
            <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                <span class="material-symbols-outlined">light_mode</span>
            </button>
            <button class="btnLogin-popup"><a href="php/admin_logout.php">Logout</a></button>
        </div>
    </nav>
</div>

<!-- toggle password -->
<script>
function togglePasswordVisibility(fieldId, btn) {
    const passwordField = document.getElementById(fieldId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        btn.textContent = "Hide";
    } else {
        passwordField.type = "password";
        btn.textContent = "Show";
    }
}
</script>
<script src="../sidebar.js"></script>
<script src="../script.js"></script>
<script src="../password.js"></script>
</body>
</html>
