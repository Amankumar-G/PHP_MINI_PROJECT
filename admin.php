<?php
// Start output buffering
ob_start();

// Include the database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['hospital_id'])) {
    $hospitalId = intval($_POST['hospital_id']);

    if ($_POST['action'] === 'verify') {
        // Verify hospital
        $sql = "UPDATE hospital SET verified = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hospitalId);
        $stmt->execute();
        $stmt->close();
    } elseif ($_POST['action'] === 'unverify') {
        // Unverify hospital
        $sql = "UPDATE hospital SET verified = 0 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hospitalId);
        $stmt->execute();
        $stmt->close();
    } elseif ($_POST['action'] === 'delete') {
        // Delete hospital
        $sql = "DELETE FROM hospital WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hospitalId);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch hospitals
$sql = "SELECT * FROM hospital";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center">Hospitals</h2>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Hospital ID</th>
                <th scope="col">Hospital Name</th>
                <th scope="col">Location</th>
                <th scope="col">Contact Number</th>
                <th scope="col">Action</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['hospital_name']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td>
                            <?php if ($row['verified'] == 0): ?>
                                <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="verify">
                                    <input type="hidden" name="hospital_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to verify this hospital?');">Verify</button>
                                </form>
                            <?php else: ?>
                                <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="unverify">
                                    <input type="hidden" name="hospital_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to unverify this hospital?');">Unverify</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="hospital_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this hospital?');">Delete</button>
                            </form>
                        </td>     
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hospitals found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';
?>
