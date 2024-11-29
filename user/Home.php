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
    <title>LGU User Dashboard</title>
</head>
<body>

    <div class="container">
        <!-- Side bar-->
        <aside id="sidebar">
           <div class="sidebar">
                <a href="Home.php" class="active">
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
                <a href="About.php">
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
            <h1>FAQ Chatbot</h1>
   <!--
     <iframe src="user/chatbot.html" style="border: none; width: 85%; height: 600px; position: fixed; bottom: 10px; right: 10px; z-index: 99">
     </iframe> 
    -->         
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
                <div class="user-info">
                        <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
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
    <script>
        window.embeddedChatbotConfig = {
        chatbotId: "ZwgIcdWL8-pCavPUOwEWm",
        domain: "www.chatbase.co"
        }
        </script>
        <script
        src="https://www.chatbase.co/embed.min.js"
        chatbotId="ZwgIcdWL8-pCavPUOwEWm"
        domain="www.chatbase.co"
        defer>
    </script>
</body>
</html>