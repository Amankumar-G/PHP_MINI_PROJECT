<?php
// Start session for error handling (optional)
session_start();

// Include the database connection
include 'db.php'; // Make sure this points to your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $hospital_name = $_POST['hospital_name'];
    $address = $_POST['address'];
    $zip_code = $_POST['zip_code'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $receptionist_name = $_POST['receptionist_name'];
    $receptionist_contact = $_POST['receptionist_contact'];
    $password = $_POST['password']; // Consider hashing the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle image upload
    $image = '';
    if (isset($_FILES['name']['image']) && $_FILES['error']['image'] == 0) {
        $image = $_FILES['name']['image'];
        $upload_dir = 'uploads/'; // Ensure this directory exists and is writable
        move_uploaded_file($_FILES['tmp_name']['image'], $upload_dir . $image);
    }

    // Prepare an SQL statement
    $stmt = $conn->prepare("INSERT INTO hospital (hospital_name, address, zip_code, contact_number, email, doctor_name, doctor_contact_number, doctor_email, image, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    // Use the receptionist's information as placeholders for the doctor's fields
    $stmt->bind_param("ssssssssss", $hospital_name, $address, $zip_code, $contact_number, $email, $receptionist_name, $receptionist_contact, $email, $image, $hashed_password);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Hospital added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!-- Your existing form code -->
<br>
<div class="row">
    <div class="col-8 offset-2">
        <h3>Create New s!</h3>
        <form method="POST" action="new.php" novalidate class="needs-validation" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="hospital_name" class="form-label">Hospital Name</label>
                <input name="hospital_name" type="text" placeholder="Enter hospital name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input name="address" type="text" placeholder="Enter address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="zip_code" class="form-label">Zip Code</label>
                <input name="zip_code" type="text" placeholder="Enter zip code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input name="contact_number" type="tel" placeholder="Enter contact number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" type="email" placeholder="Enter email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="receptionist_name" class="form-label">Receptionist's Name</label>
                <input name="receptionist_name" type="text" placeholder="Enter receptionist's name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="receptionist_contact" class="form-label">Receptionist's Contact Number</label>
                <input name="receptionist_contact" type="tel" placeholder="Enter receptionist's contact number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="password" placeholder="Enter password" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input name="image" type="file" class="form-control">
            </div>
            <button class="btn btn-dark add-btn">ADD</button>
        </form>
    </div>
</div>
<br><br>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';
?>
