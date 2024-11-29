<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/lguicon.png" />
    <title>Announcements</title>
</head>
<body>
    <div class="container">
        <!-- Side bar-->
        <aside id="sidebar">
          <div class="sidebar">
                <a href="index.html">
                    <span class="material-symbols-outlined">favorite</span>                   
                     <h3>Welcome</h3>
                </a>
                <a href="indexannouncement.php" class="active" aria-current="page">
                    <span class="material-icons-sharp">campaign</span>
                    <h3>Announcements</h3>
                </a>
            </div>
        </aside>
        <!--Sidebar end-->

        <!--Main content per page-->
        <div class="main--content">
            <h1>Announcements</h1>
            <div id="announcement-container">

            <?php
                $conn = new mysqli("localhost", "citi_lgutestdb1", "GGpfr89ly9h6qJF7", "citi_lgutestdb");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT topic, description, images, created_at FROM announcements ORDER BY created_at DESC"; 
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="announcement-card">';
                        echo '<h2>' . htmlspecialchars($row['topic']) . '</h2>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        if (!empty($row['images'])) {
                            echo '<img src="uploads/' . htmlspecialchars($row['images']) . '" alt="' . htmlspecialchars($row['topic']) . ' image">';
                        }

                        // Format created_at date and time
                        $createdAt = new DateTime($row['created_at']);
                        $formattedDate = $createdAt->format('F j, Y'); // e.g., "October 13, 2024"
                        $formattedTime = $createdAt->format('g:i A'); // e.g., "10:25 AM"
                        
                        // Display formatted date and time
                        echo '<p>Posted on: ' . htmlspecialchars($formattedDate) . ' at ' . htmlspecialchars($formattedTime) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No announcements found.</p>';
                }

                $conn->close();
                ?>


            </div>
        </div>
        
         <nav class="navigation">
        <!-- Left section: Close button and Logo -->
        <div class="left-section">
            <div class="close" id="toggle-btn" tabindex="0" aria-label="Toggle menu">
                <span class="material-icons-sharp">menu_open</span>
            </div>
            <div class="logo">
                <a href="admin/AdminLogin.html">
                    <img src="images/crfms.png" alt="LGU Logo">
                </a>
            </div>
        </div>
        <!-- Right section: Theme toggle and Sign up button -->
        <div class="right-section">
            <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                <span class="material-symbols-outlined">light_mode</span>
            </button>
            <button class="btnLogin-popup" aria-label="Sign Up">Sign Up</button>
        </div>
    </nav>
    </div>
    
    <!-- Signup Form --> 
    <div class="wrapper" role="dialog" aria-labelledby="form-dialog" aria-hidden="true">
        <span class="icon-close" onclick="closePopup()"><ion-icon name="close"></ion-icon></span>
        <div class="form-box login">
            <h2>Login</h2>
            <form action="user/php/login.php" method="post">
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="username" required aria-label="Username">
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required aria-label="Password">
                    <label>Password</label>
                </div>

                <button type="submit" class="btn">Login</button>
                <div class="login-register">
                    <label>Don't have an account? <a href="#" class="register-link">Register</a></label>
                </div>
            </form>
        </div>
        <div class="form-box register">
            <h2>Registration</h2>
            <form action="user/php/register.php" method="post" id="registrationForm">
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="username" required aria-label="Username">
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="firstname" required aria-label="First Name">
                    <label>First Name</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="lastname" required aria-label="Last Name">
                    <label>Last Name</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required aria-label="Email">
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" id="password" pattern="(?=.*\d).{8,}" 
                           title="Password must be at least 8 characters long and include at least 1 number" 
                           required aria-label="Password">
                    <label>Password</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox" required> Agree to the terms & conditions</label>
                </div>
                <button type="submit" class="btn">Register</button>
                <div class="login-register">
                    <label>Already have an account? <a href="#" class="login-link">Login</a></label>
                </div>
            </form>
        </div>
    </div>
    <!-- Popups -->
    <div id="username-error-popup" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="popup-close" onclick="closeUserErrorPopup('username')">&times;</span>
            <p id="username-error-message">Username is already in use</p>
        </div>
    </div>
    <div id="email-error-popup" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="popup-close" onclick="closeUserErrorPopup('email')">&times;</span>
            <p id="email-error-message">Email is already in use</p>
        </div>
    </div>
    <div id="user-not-found-popup" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="popup-close" onclick="closeUserNotFoundPopup()">×</span>
            <p>User Not Found. Please check your username and try again.</p>
        </div>
    </div>
    <div id="password-error-popup" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="popup-close" onclick="closePasswordErrorPopup()"></span>
            <p>Incorrect Password. Please try again.</p>
        </div>
    </div>
    <div id="register-success-popup" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="popup-close" onclick="closeRegisterSuccessPopup()">×</span>
            <p>Account Registered Successfully</p>
        </div>
    </div>      

    <script src="script.js"></script>
    <script src="sidebar.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="password.js"></script>
</body>
</html>
