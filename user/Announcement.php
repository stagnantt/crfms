<?php
session_set_cookie_params(0); 
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../indexannouncement.php');
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
    <title>Announcements</title>
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
                <a href="announcements.php" class="active">
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
            <h1>Announcements</h1>
            <div id="announcement-container">

            <?php
                // Include the database configuration file
                require_once 'config.php';

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch announcements from the announcements table, sorted by created_at in descending order
                    $sql = "SELECT topic, description, images, created_at FROM announcements ORDER BY created_at DESC"; 
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="announcement-card">';
                            echo '<h2>' . htmlspecialchars($row['topic']) . '</h2>';
                            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                            if (!empty($row['images'])) {
                                echo '<img src="../uploads/' . htmlspecialchars($row['images']) . '" alt="' . htmlspecialchars($row['topic']) . ' image">';
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
