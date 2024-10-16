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
$query = "SELECT * FROM patient WHERE email = ?";
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
                    <img src="https://i.pinimg.com/236x/93/19/e4/9319e481be9ccc90416cbd1da1404274.jpg" alt="">
                </div>
                <div class="col-5 offset-5">
                    <h1>
                        <?php echo htmlspecialchars($row['patient_name']); ?>
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
