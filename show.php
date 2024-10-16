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
                <form action="/book/<?php echo $data['id']; ?>" style="display: flex; justify-content: center;">
                    <button class="form-control out" style="text-align: center; font-weight: bolder; color: white; background-color: rgb(214, 132, 0); width: 150px; padding: 12px; border: none; border-radius: 25px;">Book Now</button>
                </form>
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


<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';

// Close the database connection
$conn->close();
?>
