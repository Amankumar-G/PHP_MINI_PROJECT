<?php
session_start();

// Include the database connection
include 'db.php'; // Make sure this points to your database connection file

// Check if the form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password']; // Consider hashing the password
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $checkStmt = $conn->prepare("SELECT email FROM patient WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $error = "Email already in use!";
    } else {
        // Prepare an SQL statement for inserting a new user
        $stmt = $conn->prepare("INSERT INTO patient (patient_name, age, gender, contact_number, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $age, $gender, $contact, $email, $hashed_password);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo "Sign-up successful!";
            // Optionally redirect or set a success session variable here
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close the database connection
    $checkStmt->close();
    $conn->close();
}
?>

<!-- Your existing HTML form code here -->

<div class="row mt-3">
    <h1 class="col-6 offset-3 mb-3">Sign Up</h1>
    <div class="col-6 offset-3">
        <form action="signup.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input name="name" type="text" placeholder="Enter your name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" type="email" placeholder="Enter email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input name="contact" type="tel" placeholder="Enter contact number" id="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="password" placeholder="Enter password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input name="age" type="number" placeholder="Enter your age" id="age" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="">Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <button class="btn btn-outline-dark mt-3">Sign Up</button>
        </form><br><br>
    </div>
</div>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout
include 'boilerplate.php';
?>
