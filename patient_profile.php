<?php 
ob_start();
session_start();
include 'db.php'; // Ensure this file connects to your database

// Check if the user is logged in
if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_profile'])) {
    $deleteQuery = "DELETE FROM patient WHERE email = ?";
    $deleteStmt = $conn->prepare($deleteQuery);

    if ($deleteStmt) {
        $deleteStmt->bind_param("s", $email);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            $error = "Error deleting profile.";
        }

        $deleteStmt->close();
    } else {
        $error = "Error preparing the delete statement.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_profile'])) {
    $newName = $_POST['patient_name'];
    $newAge = $_POST['age'];
    $newEmail = $_POST['email'];
    $newContact = $_POST['contact_number'];

    $updateQuery = "UPDATE patient SET patient_name = ?, age = ?, email = ?, contact_number = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateQuery);

    if ($updateStmt) {
        $updateStmt->bind_param("sisss", $newName, $newAge, $newEmail, $newContact, $email);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            $_SESSION['email'] = $newEmail;
            $success = "Profile updated successfully!";
        } else {
            $error = "No changes made or error updating profile.";
        }

        $updateStmt->close();
    } else {
        $error = "Error preparing the update statement.";
    }
}

$query = "SELECT * FROM patient WHERE email = ?";
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
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .profile-container {
                    max-width: 600px;
                    margin: auto;
                    padding: 20px;
                    background: #f9f9f9;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .profile-image {
                    border-radius: 50%;
                    margin-bottom: 20px;
                }
                .modal-content {
                    background: #e8f0fe;
                    border-radius: 10px;
                }
                .form-control {
                    border-radius: 5px;
                }
                .alert {
                    max-width: 600px;
                    margin: 10px auto;
                }
            </style>
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="profile-container text-center">
                    <img src="https://i.pinimg.com/236x/93/19/e4/9319e481be9ccc90416cbd1da1404274.jpg" alt="Profile Image" class="profile-image img-fluid" width="150">
                    <h3><?php echo htmlspecialchars($row['patient_name']); ?></h3>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($row['age']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Contact No:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>

                    <div class="d-flex justify-content-center mt-4">
                        <form method="POST" action="" class="mr-3">
                            <input type="hidden" name="delete_profile" value="1">
                            <button type="submit" class="btn btn-danger">DELETE</button>
                        </form>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">EDIT</button>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="">
                                <div class="modal-body">
                                    <input type="hidden" name="edit_profile" value="1">
                                    <div class="form-group">
                                        <label for="patient_name">Name</label>
                                        <input type="text" class="form-control" name="patient_name" id="patient_name" value="<?php echo htmlspecialchars($row['patient_name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="number" class="form-control" name="age" id="age" value="<?php echo htmlspecialchars($row['age']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success text-center">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
            </div>

            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
