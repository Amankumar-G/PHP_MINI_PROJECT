<?php
ob_start();
// Start the session
session_start();

// Database connection (replace with your actual connection code)
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the hospital ID based on the logged-in hospital's email from the session
$email = $_SESSION['email']; // Assuming email is stored in the session
$hospital_stmt = $conn->prepare("SELECT id FROM hospital WHERE email = ?");
$hospital_stmt->bind_param("s", $email);
$hospital_stmt->execute();
$hospital_result = $hospital_stmt->get_result();

if ($hospital_result->num_rows > 0) {
    $hospital = $hospital_result->fetch_assoc();
    $hospital_id = $hospital['id'];
} else {
    echo "No hospital found for this email.";
    exit;
}
$hospital_stmt->close();

// Fetch appointments for the current hospital
$appointments_stmt = $conn->prepare("SELECT * FROM appointments WHERE hospital_id = ?");
$appointments_stmt->bind_param("i", $hospital_id);
$appointments_stmt->execute();
$appointments_result = $appointments_stmt->get_result();

// Handle completion of an appointment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    // Prepare an SQL statement to delete the completed appointment
    $delete_stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $delete_stmt->bind_param("i", $appointment_id);

    if ($delete_stmt->execute()) {
        echo "Appointment marked as completed!";
    } else {
        echo "Error: " . $delete_stmt->error;
    }

    $delete_stmt->close();
}

// Handle reset current token
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_token'])) {
    // Prepare an SQL statement to reset current_token for the hospital
    $reset_stmt = $conn->prepare("UPDATE hospital SET current_token = 0 WHERE id = ?");
    $reset_stmt->bind_param("i", $hospital_id);

    if ($reset_stmt->execute()) {
        echo "Current token reset to 0!";
    } else {
        echo "Error: " . $reset_stmt->error;
    }

    $reset_stmt->close();
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Appointment List</h2>
        <form method="POST" action="">
            <button type="submit" name="reset_token" class="btn btn-danger">Reset</button>
        </form>
    </div>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Reason</th>
                <th>Token</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($appointment['age']); ?></td>
                <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                <td><?php echo htmlspecialchars($appointment['contact_number']); ?></td>
                <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                <td><?php echo htmlspecialchars($appointment['token']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                        <button type="submit" name="complete_appointment" class="btn btn-success">Complete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
// Close the database connection
$conn->close();
$content = ob_get_clean();
include 'boilerplate.php';
?>
