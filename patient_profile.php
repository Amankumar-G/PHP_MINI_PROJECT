<?php 
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    $newEmail = $_SESSION['email'];
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['report_file'])) {
    $reportFile = $_FILES['report_file'];

    if ($reportFile['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $reportFile['tmp_name'];
        $fileName = $reportFile['name'];
        $uploadDir = 'uploads/reports/';
        $destPath = $uploadDir . $fileName;

        // Move file to destination
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Get patient_id from patient table using email
            $patientQuery = "SELECT id FROM patient WHERE email = ?";
            $patientStmt = $conn->prepare($patientQuery);
            if ($patientStmt) {
                $patientStmt->bind_param("s", $email);
                $patientStmt->execute();
                $patientResult = $patientStmt->get_result();
                
                if ($patientRow = $patientResult->fetch_assoc()) {
                    $patient_id = $patientRow['id'];
                    $reportDate = date("Y-m-d"); // Current date for the report

                    // Insert the report details into the patient_report table
                    $reportInsertQuery = "INSERT INTO patient_report (patient_id, report_file, report_date) VALUES (?, ?, ?)";
                    $reportStmt = $conn->prepare($reportInsertQuery);
                    if ($reportStmt) {
                        $reportStmt->bind_param("iss", $patient_id, $destPath, $reportDate);
                        $reportStmt->execute();
                        $reportStmt->close();
                        $success = "Report uploaded and saved successfully!";
                    } else {
                        $error = "Error preparing the report insert statement.";
                    }
                } else {
                    $error = "Patient not found.";
                }
                $patientStmt->close();
            } else {
                $error = "Error preparing the patient query.";
            }
        } else {
            $error = "Error moving the uploaded file.";
        }
    } else {
        $error = "Error uploading the file.";
    }
}

// Query patient details
$query = "SELECT * FROM patient WHERE email = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
?>

    <style>
        .fixed-sidebar {
            position: sticky;
            top: 0;
            height: 80vh; /* Full viewport height */
            overflow-y: auto; /* Allow scrolling */
        }

        .scrollable-container {
            max-height: 80vh; /* Limit height for scrolling */
            display: flex;
            flex-direction: column;
        }

        .table-container {
            height:40vh;
            max-height: 50vh; /* Set height for each table's scrollable area */
            overflow-y: auto; /* Enable vertical scrolling */
            margin-bottom: 20px; /* Space between tables */
            border: 1px solid #dee2e6; /* Optional: Add border for separation */
            border-radius: 5px; /* Optional: Add rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional: Add shadow */
        }

        .table {
            margin-bottom: 0; /* Remove default bottom margin of the table */
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 col-md-12 fixed-sidebar">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="https://i.pinimg.com/236x/93/19/e4/9319e481be9ccc90416cbd1da1404274.jpg" alt="Profile Image" class="rounded-circle img-fluid mb-3" width="150">
                        <h3><?php echo htmlspecialchars($row['patient_name']); ?></h3>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($row['age']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Contact No:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
                        <div class="d-flex justify-content-center mt-4">
                            <form method="POST" action="" class="mr-2">
                                <input type="hidden" name="delete_profile" value="1">
                                <button type="submit" class="btn btn-danger">DELETE</button>
                            </form>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">EDIT</button>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h4 class="card-title text-center">Upload Medical Report</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="report_file">Upload Medical Report (PDF):</label>
                                <input type="file" class="form-control-file" name="report_file" id="report_file" accept=".pdf" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Upload Report</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="scrollable-container">
                    <!-- Appointments Table -->
                    <div class="table-container card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title text-center">Your Appointments</h4>
                            <div class="table-responsive">
                                <?php
                                $query = "SELECT * FROM appointments WHERE email = ?";
                                $tmt = $conn->prepare($query);
                                $tmt->bind_param("s", $email);
                                $tmt->execute();
                                $result_app = $tmt->get_result();

                                if ($result_app->num_rows > 0) {
                                    echo "<table class='table table-bordered text-center'>";
                                    echo "<thead><tr><th>Token Number</th><th>Hospital Name</th></tr></thead><tbody>";

                                    // Loop through each appointment
                                    while ($row_app = $result_app->fetch_assoc()) {
                                        $hos_id = $row_app['hospital_id'];

                                        // Query for hospital data
                                        $query_hos = "SELECT * FROM hospital WHERE id = ?";
                                        $htmt = $conn->prepare($query_hos);
                                        $htmt->bind_param("s", $hos_id);
                                        $htmt->execute();
                                        $result_hos = $htmt->get_result();
                                        $row_hos = $result_hos->fetch_assoc(); // Get hospital details

                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row_app['token_number']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_hos['hospital_name']) . "</td>";
                                        echo "</tr>";

                                        $htmt->close();
                                    }
                                    echo "</tbody></table>";
                                } else {
                                    echo "<p>No appointments found.</p>";
                                }
                                $tmt->close();
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Reports Table -->
                    <div class="table-container card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title text-center">Uploaded Reports</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Report ID</th>
                                            <th>File Name</th>
                                            <th>Upload Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $reportsQuery = "SELECT * FROM patient_report WHERE patient_id = (SELECT id FROM patient WHERE email = ?)";
                                        $reportsStmt = $conn->prepare($reportsQuery);
                                        $reportsStmt->bind_param("s", $email);
                                        $reportsStmt->execute();
                                        $reportsResult = $reportsStmt->get_result();
                                        while ($reportRow = $reportsResult->fetch_assoc()) {
                                            $filePath = $reportRow['report_file'];
                                            $fileName = basename($filePath);
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($reportRow['report_id']) . '</td>';
                                            echo '<td>' . htmlspecialchars($fileName) . '</td>';
                                            echo '<td>' . htmlspecialchars($reportRow['report_date']) . '</td>';
                                            echo '<td class="text-center"><a href="' . htmlspecialchars($reportRow['report_file']) . '" target="_blank" class="btn btn-info">View</a></td>';
                                            echo '</tr>';
                                        }
                                        $reportsStmt->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($row['email']); ?>" disabled required>
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

    <!-- Display error or success message -->
    <?php if (isset($error)) { ?>
        <div class="alert alert-danger alert-dismissible mt-3"><?php echo $error; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>

    <?php if (isset($success)) { ?>
        <div class="alert alert-success alert-dismissible mt-3"><?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<?php
    } else {
        echo "<p>Patient not found.</p>";
    }
    $stmt->close();
}
$conn->close();
$content = ob_get_clean();
include 'boilerplate.php';
?>
