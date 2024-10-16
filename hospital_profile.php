<?php 
ob_start();
session_start();
include 'db.php'; // Ensure this file connects to your database

// Check if the user is logged in
if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {
    header("Location: login.php");
    exit(); // Always call exit after redirecting
}

// Check if email is set in session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Redirect if email is not set
}

$email = $_SESSION['email'];

// Use a prepared statement to prevent SQL injection
$query = "SELECT * FROM hospital WHERE email = ?";
$stmt = $conn->prepare($query); // Assuming $db is your mysqli connection

if ($stmt) {
    $stmt->bind_param("s", $email); // Bind the email as a string
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($row = $result->fetch_assoc()) {
        // Start output buffering
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Patient Profile</title>
            <!-- Include any required CSS files here -->
        </head>
        <body>
            <div class="row">
                <div class="col-5 offset-5">
                    <img src="data:image/jpeg;base64,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" alt="">
                </div>
                <div class="col-5 offset-5">
                    <h1>
                        <?php echo htmlspecialchars($row['hospital_name']); ?>
                    </h1>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<p>No patient found with the provided email.</p>";
    }
    $stmt->close(); // Close the statement
} else {
    echo "<p>Error preparing the statement: " . $db->error . "</p>";
}

// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout
include 'boilerplate.php';
?>
