<?php
session_set_cookie_params(0); 
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../index.html');
    exit();
}

$username   = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "lgutestdb");

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"  rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>About Us</title>
</head>
<body>

    <div class="container">
        <!-- Side bar-->
        <aside id="sidebar">
          <div class="sidebar">
                <a href="Home.php">
                    <span class="material-icons-sharp">
                        home
                    </span>
                    <h3>Home</h3>
                </a>
                <a href="User.php">
                    <span class="material-icons-sharp">
                        person_outline
                    </span>
                    <h3>User</h3>
                </a>
                <a href="Announcement.php">
                    <span class="material-icons-sharp">
                        campaign
                    </span>
                    <h3>Announcement</h3>
                </a>
                <a href="Submit.php">
                    <span class="material-symbols-outlined">
                        rate_review
                    </span>         
                    <h3>Submit a Request or Feedback</h3>
                </a>
                <a href="track.php">
                    <span class="material-symbols-outlined">
                        query_stats
                    </span>
                    <h3>Track</h3>
                </a>
                <a href="Contact.php">
                    <span class="material-symbols-outlined">
                        call
                    </span>
                    <h3>Contact Us</h3>
                </a>
                <a href="About.php"  class="active">
                    <span class="material-symbols-outlined">
                        info
                    </span>
                    <h3>About Us</h3>
                </a>
            </div>
        </aside>
        <!--Sidebar end-->


        <!--Main content per page-->
        <div class="main--content">
            <h1>About Us</h1>
            <div class="about-container">
                <h2>Information</h2>
                <div class="about-content">
                  <p>Welcome to the Citizen Request and Feedback Management System! Our platform is designed to streamline communication between citizens and local authorities by providing a real-time request tracking system, feedback analysis, and an automated chatbot to handle Frequently Asked Questions (FAQ). With our system, we ensure that every request is addressed efficiently, feedback is analyzed for continuous improvement, and citizens receive quick answers to their inquiries. Our goal is to enhance public service through transparent, accessible, and responsive tools.</p>
                  <h3>Our Mission</h3>
                  <p>Our mission is to deliver high-quality solutions that help our clients achieve their goals. We are committed to innovation, integrity, and customer satisfaction.</p>
                  <h3>Why Choose Us</h3>
                  <p>We prioritize our customers' needs and provide personalized services that are tailored to meet their specific requirements. Our team is always ready to assist you and provide the best solutions.</p>
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
                        <img src="../images/crfms.png" alt="LGU Logo">
                </div>
            </div>
            <!-- Right section: Theme toggle and Sign up button -->
            <div class="right-section">
                <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                    <span class="material-symbols-outlined">light_mode</span>
                </button>
                <button class="btnLogin-popup"><a href="php/logout.php">Logout</a></button>
            </div>
        </nav>
    </div>

    <script src="../script.js"></script>
    <script src="../sidebar.js"></script>
</body>
</html>