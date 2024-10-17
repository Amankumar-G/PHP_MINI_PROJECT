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

if ($id) {
    // Prepare and execute the SQL query
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    // Sanitize and retrieve form data
    $patient_name = trim($_POST['patient_name']);
    $age = $_POST['age'];
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $reason = trim($_POST['reason']);

    // Prepare an SQL statement for inserting a new appointment
    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, age, email, contact_number, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $patient_name, $age, $email, $contact_number, $reason);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Appointment booked successfully!";
        // Optionally redirect or set a success session variable here
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h3 style="display: inline-block;margin-left: 20px; margin-top: 20px;">
            <?php echo htmlspecialchars($data['hospital_name']); ?>
        </h3>
    </div>
    <!-- <div> -->
        <!-- Code for edit And delete button -->
    <!-- </div> -->
</div>

<div class="flex">
    <div>
        <img class="coverImg" src="https://images.unsplash.com/photo-1512678080530-7760d81faba6?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aG9zcGl0YWx8ZW58MHx8MHx8fDA%3D" alt="image">
    </div>
    <div class="box-2">
        <div class="flex dibba" style="gap: 20px;">
            <div class="buy middle" >
                <div style="display: flex; justify-content: center; flex-direction:column">    
                <p>Current Token Number</p>
                <div class="flex" style="gap: 30px;">
                <h1 style="width: 17vw; text-align: center">90</h1>
                </div>
                </div>
                <button class="form-control out" style="text-align: center; font-weight: bolder; color: white; background-color: rgb(214, 132, 0); width: 100%; padding: 8px; border: none; border-radius: 25px; margin-top: 5px;" data-toggle="modal" data-target="#bookNow">Book Now</button>
                
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
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
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
