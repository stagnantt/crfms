<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.html");
    exit();
}

// Include the database connection details
include '../../user/config.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$feedback_id = $_GET['feedback_id'];

// Prepared statement to prevent SQL injection
$sql = "SELECT * FROM feedback WHERE feedbackid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $feedback_id);
$stmt->execute();
$result = $stmt->get_result();
$feedback = $result->fetch_assoc();

if (!$feedback) {
    echo "Feedback not found!";
    exit();
}
// Fetch the attached images from the database
$feedback_images = [];
if (!empty($feedback['images']) && $feedback['images'] !== 'NULL') {
    $images = explode(',', $feedback['images']);
    foreach ($images as $image) {
        $imagePath = '../../uploads/' . $image; // Path to the image
        if (file_exists($imagePath)) {
            // Base64 encode the image content
            $imageData = base64_encode(file_get_contents($imagePath)); 
            $feedback_images[] = 'data:image/jpeg;base64,' . $imageData; // Add the base64 image to the array
        }
    }
}

// Pass the base64-encoded images to JavaScript
$feedback_images_json = json_encode($feedback_images);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedack</title>
    <link rel="icon" type="image/x-icon" href="../../images/lguicon.png" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        /* Styling the download buttons */
        .download-buttons {
            margin-top: 20px;
            text-align: center;
        }

        .download-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }

        .download-buttons button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="view-feedback-container">
    <div class="view-feedback-header">
        <h2>Feedback Details</h2>
        
        <!-- Table for Feedback Information -->
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Feedback ID:</td>
                <td><?php echo htmlspecialchars($feedback['feedbackid']); ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo htmlspecialchars($feedback['email']); ?></td>
            </tr>
            <tr>
                <td>Topic:</td>
                <td><?php echo htmlspecialchars($feedback['topic']); ?></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><?php echo htmlspecialchars($feedback['description']); ?></td>
            </tr>
            <tr>
                <td>Submitted Date:</td>
            <td><?php 
                $submittedDate = new DateTime($feedback['submitted_date']);
                echo $submittedDate->format('F/j/Y'); 
            ?></td>
            </tr>
             <tr>
            <td>Time:</td>
            <td><?php 
                echo $submittedDate->format('g:i a');
            ?></td>
        </tr>
        </table>

        <!-- Display Images if available -->
        <div class="images-container">
            <h3>Attached Images</h3>
            <div class="image-gallery">
                <?php if (!empty($feedback['images']) && $feedback['images'] !== 'NULL'): ?>
                    <?php $images = explode(',', $feedback['images']); ?>
                    <?php foreach ($images as $image): ?>
                        <img src="../../uploads/<?php echo htmlspecialchars($image); ?>" class="feedback-image" style="width:100px; margin-right: 5px; cursor: pointer;" alt="Attached Image" onclick="openModal(this.src)">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No image attached.</p>
                <?php endif; ?>
            </div>
        </div>
    <!-- Download buttons for CSV and PDF -->
    <div class="download-buttons">
        <button onclick="downloadCSV()">Download as CSV</button>
        <button onclick="generateFeedbackPDF()">Download as PDF</button>
    </div>
</div>
</div>
<script>
    // Pass the PHP array of base64 images to JavaScript
    const feedbackImages = <?php echo $feedback_images_json; ?>;
    // Function to open the image in a modal
   function openModal(src) {
        var modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        modal.style.display = 'flex';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';
        modal.style.zIndex = '9999';

        var img = document.createElement('img');
        img.src = src;
        img.style.maxWidth = '90%';
        img.style.maxHeight = '90%';
        img.style.border = '5px solid white';

        modal.appendChild(img);

        modal.onclick = function() {
            document.body.removeChild(modal);
        };

        document.body.appendChild(modal);
    }
    // Function to download data as CSV
    function downloadCSV() {
        const feedbackId = "<?php echo htmlspecialchars($feedback['feedbackid']); ?>"; 
        const csvContent = [
            ["Field", "Value"],
            ["Feedback ID", feedbackId],
            ["Email", "<?php echo htmlspecialchars($feedback['email']); ?>"],
            ["Topic", "<?php echo htmlspecialchars($feedback['topic']); ?>"],
            ["Description", "<?php echo htmlspecialchars($feedback['description']); ?>"],
            ["Location", "<?php echo htmlspecialchars($feedback['location']); ?>"],
            ["Submitted Date", "<?php echo $submittedDate->format('F/j/Y'); ?>"],
            ["Time", "<?php echo $submittedDate->format('g:i a'); ?>"]
        ];

        let csvFile = "";
        csvContent.forEach(function(rowArray) {
            let row = rowArray.join(",");
            csvFile += row + "\r\n";
        });

        let hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvFile);
        hiddenElement.target = '_blank';
        hiddenElement.download = `${feedbackId}-Feedback-Form.csv`;
        hiddenElement.click();
    }

    // Function to generate PDF
    function generateFeedbackPDF() {
    const feedbackId = "<?php echo htmlspecialchars($feedback['feedbackid']); ?>"; 
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFont("helvetica");

    // Set blue header at the top of the table
    doc.setFillColor(0, 0, 255); // Blue color
    doc.rect(20, 10, 160, 10, 'F'); // Rectangle header above the table
    doc.setTextColor(255, 255, 255); // White text color
    doc.text("Feedback Details", 100, 17, null, null, "center"); // Centered header text

    // Fields for the feedback details
    const fields = [
        { label: "Feedback ID", value: feedbackId },
        { label: "Email", value: "<?php echo htmlspecialchars($feedback['email']); ?>" },
        { label: "Topic", value: "<?php echo htmlspecialchars($feedback['topic']); ?>" },
        { label: "Description", value: "<?php echo htmlspecialchars($feedback['description']); ?>" },
        { label: "Location", value: "<?php echo htmlspecialchars($feedback['location']); ?>" },
        { label: "Submitted Date", value: "<?php echo $submittedDate->format('F/j/Y'); ?>" },
        { label: "Time", value: "<?php echo $submittedDate->format('g:i a'); ?>" },
    ];

    // Table settings
    const margin = 20; // Table margin
    const x = 20; // Left padding
    let y = 25; // Starting position for table content below header
    const labelCellWidth = 60; // Width of label cell
    const valueCellWidth = 100; // Width of value cell
    const lineHeight = 8; // Height of each text line

    // Loop through fields to create rows in the table with precise spacing for text
    fields.forEach((field) => {
        // Determine the row height based on the text's actual size in the value cell
        const splitText = doc.splitTextToSize(field.value, valueCellWidth - 4); // Split text based on cell width
        const rowHeight = lineHeight * splitText.length; // Row height is lineHeight times the number of text lines

        // Draw cell for label
        doc.setTextColor(0, 0, 0); // Black text color
        doc.rect(x, y, labelCellWidth, rowHeight, 'S'); // Label cell border
        doc.text(field.label + ":", x + 2, y + lineHeight); // Label text

        // Draw cell for value with adjusted height and wrapped text
        doc.rect(x + labelCellWidth, y, valueCellWidth, rowHeight, 'S'); // Value cell border
        doc.text(splitText, x + labelCellWidth + 2, y + lineHeight); // Display wrapped text

        // Move to the next row position
        y += rowHeight;
    });
    // Add a margin before the images (add space between table and images)
    const imageMargin = 10;
    y += imageMargin;
    // Add attached images (if any)
    if (feedbackImages && feedbackImages.length > 0) {
        feedbackImages.forEach((imageBase64) => {
            // Set image dimensions: same width as the table and height 60px
            const imageWidth = labelCellWidth + valueCellWidth; // Total width of the table
            const imageHeight = 100; // Fixed height for images

            // Add the image to the PDF
            doc.addImage(imageBase64, 'JPEG', x, y, imageWidth, imageHeight); // Adjust image placement
            y += imageHeight + 10; // Move the cursor down for next content (adding 10px for spacing)
        });
    }


    // Save the PDF
    doc.save(`${feedbackId}-Feedback-Form.pdf`);
}

</script>
<div class="backtoreview">
    <a href="../Reviewsubmissions.php">Back to Review Submissions</a>
</div>
</body>
</html>

<?php
$conn->close();
?>
