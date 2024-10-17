<?php
// Start output buffering
ob_start();

// Database connection (replace with your actual connection code)
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and get the 'id' from URL
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

// Fetch current token number for this hospital
$token_stmt = $conn->prepare("SELECT current_token FROM hospital WHERE id = ?");
$token_stmt->bind_param("i", $id);
$token_stmt->execute();
$token_result = $token_stmt->get_result();
$token_data = $token_result->fetch_assoc();

if (!$token_data) {
    echo "No data found for this ID.";
    exit;
}

$current_token = $token_data['current_token'] + 1; // Use the current token as-is
$token_stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    // Sanitize and retrieve form data
    $patient_name = trim($_POST['patient_name']);
    $age = intval($_POST['age']);  // Ensure age is an integer
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $reason = trim($_POST['reason']);

    // Prepare an SQL statement for inserting a new appointment with the token number
    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, age, email, contact_number, reason, token_number, hospital_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssii", $patient_name, $age, $email, $contact_number, $reason, $current_token, $id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Appointment booked successfully! Your Token Number is " . $current_token;

        // Increment the current token count in the hospital table
        $new_token = $current_token;
        $up_stmt = $conn->prepare("UPDATE hospital SET current_token = ? WHERE id = ?");
        $up_stmt->bind_param("ii", $new_token, $id);
        if (!$up_stmt->execute()) {
            echo "Error updating token: " . $up_stmt->error;
        }
        $up_stmt->close();

        // Redirect or refresh to show updated token
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close statement
    $stmt->close();
}

if ($id) {
    // Prepare and execute the SQL query to fetch hospital data
    $stmt = $conn->prepare("SELECT * FROM hospital WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "No data found for this ID.";
        exit;
    }
    $stmt->close();
} else {
    echo "Invalid ID.";
    exit;
}
?>


<div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h3 style="display: inline-block;margin-left: 20px; margin-top: 20px;">
            <?php echo htmlspecialchars($data['hospital_name']); ?>
        </h3>
    </div>
</div>

<div class="flex">
    <div>
        <img class="coverImg" src="<?php echo htmlspecialchars($data['image']); ?>" alt="image">
    </div>
    <div class="box-2">
        <div class="flex dibba" style="gap: 20px;">
            <div class="buy middle">
                <div style="display: flex; justify-content: center; flex-direction:column">
                    <p>Current token Number</p>
                    <div class="flex" style="justify-content: center;">
                        <!-- You can add other content here -->
                         <h1><?php echo htmlspecialchars($data['current_token']) ?></h1>
                    </div>
                </div>
                <button class="form-control out" style="text-align: center; font-weight: bolder; color: white; background-color: rgb(214, 132, 0); width: 100%; padding: 8px; border: none; border-radius: 25px; margin-top: 8px;" data-toggle="modal" data-target="#bookNow">Book Now</button>
            </div>
            <div class="buy">
                <div class="flex" style="gap: 55px;">
                    <p style="font-size: 20px; font-weight: 700; position: relative; top: -5px;">Need Help?</p>
                </div>
                <p style="position: relative; top: 14px; left: 5px;">Call us at: <br><?php echo htmlspecialchars($data['contact_number']); ?></p>
            </div>
        </div>
        <hr>
        <?php include 'details.php'; ?>
    </div>
</div>

<div class="modal fade" id="bookNow" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Book Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="book_appointment" value="1">
                    <div class="form-group">
                        <label for="patient_name">Name</label>
                        <input type="text" class="form-control" name="patient_name" id="patient_name" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" class="form-control" name="age" id="age" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" id="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Appointment</label>
                        <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';

// Close the database connection
$conn->close();
?>
