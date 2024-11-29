<?php
session_set_cookie_params(0); 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.html");
    exit();
}

// Initialize error and success messages
$errorMessage = "";
$successMessage = "";

// Connection
$conn = new mysqli("localhost", "root", "", "lgutestdb");


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all requests
$sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM request";
$result_requests = $conn->query($sql_requests);

// Fetch all feedbacks, including FeedbackID
$sql_feedbacks = "SELECT feedbackid, email, topic, submitted_date FROM feedback";
$result_feedbacks = $conn->query($sql_feedbacks);
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
    <title>Admin Review Submissions</title>
    <style>
        .submitted { 
            background-color: blue; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .reviewed { 
            background-color: orange; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .in-progress { 
            background-color: yellow; 
            color: black; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .cancelled { 
            background-color: red; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .completed { 
            background-color: green; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .positive {
            background-color: blue;
            color: white;
            padding: 0.2rem;
            border-radius: 4px;
        }
        .negative{
            background-color: red; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
    </style>
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
            <a href="Admin.php">
                <span class="material-symbols-outlined">shield_person</span>
                <h3>Admin</h3>
            </a>
            <a href="AdminAnnouncement.php">
                <span class="material-symbols-outlined">add_box</span>
                <h3>Announcements</h3>
            </a>
            <a href="Reviewsubmissions.php" class="active">
                <span class="material-symbols-outlined">rate_review</span>
                <h3>Review Request & Feedback</h3>
            </a>
        </div>
    </aside>
    <!-- Sidebar end -->

    <!-- Main content per page -->
    <div class="main--content">
    <h2>Review Submissions from Users</h2>
    <div class="review--submission">
        <h3>Requests</h3>

        <form method="GET" action="">
            <input type="text" name="request_search" placeholder="Search by Reference ID, Email, Topic, or Status" value="<?php echo isset($_GET['request_search']) ? htmlspecialchars($_GET['request_search']) : ''; ?>">
            <button class="searchbtn" type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="resetbtn" type="reset" onclick="window.location.href='?';">
                <span class="material-symbols-outlined">restart_alt</span>
            </button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Reference ID</th>
                    <th>Email</th>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get the search query from the request form
                $requestSearchQuery = isset($_GET['request_search']) ? $_GET['request_search'] : '';

                // Create a prepared statement for the search
                $sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM request WHERE 
                                reference_id LIKE ? OR 
                                email LIKE ? OR 
                                topic LIKE ? OR 
                                status LIKE ?";
                $stmt = $conn->prepare($sql_requests);

                // Prepare search parameters
                $searchParam = "%" . $requestSearchQuery . "%";
                $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_requests = $stmt->get_result();

                if ($result_requests->num_rows > 0) {
                    while ($row = $result_requests->fetch_assoc()) {
                        // Format the submitted_date
                        $submittedDate = new DateTime($row['submitted_date']);
                        echo "<tr>";
                        echo "<td><a href='php/view_request.php?reference_id=" . $row['reference_id'] . "'>" . $row['reference_id'] . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                        echo "<td><span class='" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td>" . $submittedDate->format('F d, Y | h:i A') . "</td>"; // Modified format
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No requests found.</td></tr>";
                }

                // Close the statement
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="review--submission">
        <h3>Feedbacks</h3>
        <form method="GET" action="">
            <input type="text" name="feedback_search" placeholder="Search by Feedback ID, Email, or Topic" value="<?php echo isset($_GET['feedback_search']) ? htmlspecialchars($_GET['feedback_search']) : ''; ?>">
            <button class="searchbtn" type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="resetbtn" type="reset" onclick="window.location.href='?';">
                <span class="material-symbols-outlined">restart_alt</span>
            </button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>Email</th>
                    <th>Topic</th>
                    <th>Submitted Date</th>
                    <th>Analysis</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $feedbackSearchQuery = isset($_GET['feedback_search']) ? $_GET['feedback_search'] : '';

                $sql_feedbacks = "SELECT feedbackid, email, topic, submitted_date FROM feedback WHERE 
                                  feedbackid LIKE ? OR 
                                  email LIKE ? OR 
                                  topic LIKE ?";
                $stmt = $conn->prepare($sql_feedbacks);

                $searchParam = "%" . $feedbackSearchQuery . "%";
                $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_feedbacks = $stmt->get_result();

                if ($result_feedbacks->num_rows > 0) {
                    while ($row = $result_feedbacks->fetch_assoc()) {
                        // Format the submitted_date
                        $submittedDate = new DateTime($row['submitted_date']);
                        echo "<tr>";
                        echo "<td><a href='php/view_feedback.php?feedback_id=" . htmlspecialchars($row['feedbackid']) . "'>" . htmlspecialchars($row['feedbackid']) . "</a></td>"; 
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                        echo "<td>" . $submittedDate->format('F  d, Y | h:i A') . "</td>"; 
                        echo "<td><span class='positive'> Positive</span>||<span class='negative'>Negative</span> </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No feedbacks found.</td></tr>";
                }

                // Close the statement
                $stmt->close();
                ?>
            </tbody>
        </table>
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
<script src="../script.js"></script>
<script src="../sidebar.js"></script>
</body>
</html>

<?php
$conn->close();
?>
