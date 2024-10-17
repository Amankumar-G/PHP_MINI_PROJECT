<?php 
ob_start();
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php'; // Ensure this file connects to your database

// Check if the user is logged in
if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

// Check if email is set in session
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// If delete is triggered
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile'])) {
    $deleteQuery = "DELETE FROM hospital WHERE email = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    if ($deleteStmt) {
        $deleteStmt->bind_param("s", $email);
        if ($deleteStmt->execute()) {
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            echo "<p>Error deleting profile: " . $conn->error . "</p>";
        }
        $deleteStmt->close();
    }
}

// If edit form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_profile'])) {
    $hospital_name = $_POST['hospital_name'];
    $address = $_POST['address'];
    $zip_code = $_POST['zip_code'];
    $contact_number = $_POST['contact_number'];
    $doctor_name = $_POST['doctor_name'];
    $doctor_contact_number = $_POST['doctor_contact_number'];
    $doctor_email = $_POST['doctor_email'];

    $updateQuery = "UPDATE hospital SET hospital_name = ?, address = ?, zip_code = ?, contact_number = ?, doctor_name = ?, doctor_contact_number = ?, doctor_email = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateQuery);
    if ($updateStmt) {
        $updateStmt->bind_param("ssssssss", $hospital_name, $address, $zip_code, $contact_number, $doctor_name, $doctor_contact_number, $doctor_email, $email);
        $updateStmt->execute();
        $updateStmt->close();
    }
}

// Fetch the current profile data
$query = "SELECT * FROM hospital WHERE email = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Patient Profile</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .profile-container {
                    background-color: #ffffff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                }
                h2 {
                    color: #343a40;
                    margin-bottom: 20px;
                }
                h3, h5 {
                    color: #495057;
                }
                .btn-danger {
                    margin-right: 10px;
                }
                .modal-header {
                    background-color: #007bff;
                    color: white;
                }
                .form-control {
                    border-radius: 5px;
                }
                .row {
                    margin-bottom: 15px; /* Add spacing between rows */
                }
                .profile-image {
                    max-height: 200px; /* Limit image height */
                    object-fit: cover; /* Maintain aspect ratio */
                }
                .profile-details {
                    padding-left: 15px; /* Add left padding for alignment */
                }
            </style>
        </head>
        <body>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 profile-container">
                        <h2 class="text-center">Hospital Profile</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <img src="<?php echo $row['image'];?>" class="img-fluid profile-image rounded" alt="Hospital Image">
                            </div>
                            <div class="col-md-6 profile-details">
                                <h3>Hospital: <strong><?php echo htmlspecialchars($row['hospital_name']); ?></strong></h3>
                                <h5>Address: <strong><?php echo htmlspecialchars($row['address']); ?></strong></h5>
                                <h5>Zip Code: <strong><?php echo htmlspecialchars($row['zip_code']); ?></strong></h5>
                                <h5>Contact No: <strong><?php echo htmlspecialchars($row['contact_number']); ?></strong></h5>
                                <h5>Email: <strong><?php echo htmlspecialchars($row['email']); ?></strong></h5>
                                <h5>Doctor Name: <strong><?php echo htmlspecialchars($row['doctor_name']); ?></strong></h5>
                                <h5>Doctor Contact No: <strong><?php echo htmlspecialchars($row['doctor_contact_number']); ?></strong></h5>
                                <h5>Doctor Email: <strong><?php echo htmlspecialchars($row['doctor_email']); ?></strong></h5>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <form method="POST" style="display:inline;">
                                <button type="submit" name="delete_profile" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your profile?');">
                                    DELETE
                                </button>
                            </form>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
                                EDIT
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Hospital Name</label>
                                    <input type="text" name="hospital_name" class="form-control" value="<?php echo htmlspecialchars($row['hospital_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($row['address']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Zip Code</label>
                                    <input type="text" name="zip_code" class="form-control" value="<?php echo htmlspecialchars($row['zip_code']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Doctor Name</label>
                                    <input type="text" name="doctor_name" class="form-control" value="<?php echo htmlspecialchars($row['doctor_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Doctor Contact Number</label>
                                    <input type="text" name="doctor_contact_number" class="form-control" value="<?php echo htmlspecialchars($row['doctor_contact_number']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Doctor Email</label>
                                    <input type="email" name="doctor_email" class="form-control" value="<?php echo htmlspecialchars($row['doctor_email']); ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="edit_profile" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Scripts for Bootstrap modal functionality -->
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "<p>No patient found with the provided email.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Error preparing the statement: " . $conn->error . "</p>";
}

$content = ob_get_clean();
include 'boilerplate.php';
?>
