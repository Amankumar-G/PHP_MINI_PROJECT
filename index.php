<?php
// Start output buffering
ob_start();

// Include the database connection
include 'db.php';

// Query to fetch all hospital records
$sql = "SELECT * FROM hospital";
$result = $conn->query($sql);

$alllistings = [];
if ($result->num_rows > 0) {
    // Fetch all records as an associative array
    while($row = $result->fetch_assoc()) {
        $alllistings[] = $row;
    }
} else {
    echo "No records found.";
}

// Close the database connection
$conn->close();
?>

<h1>All Hospitals</h1>
<div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-3">
    <?php foreach ($alllistings as $listings): ?>
        <a href="/listings/<?php echo $listings['id']; ?>" class="listing-link">
            <div class="card listing-card col">
                <img src="https://img.freepik.com/free-vector/people-walking-sitting-hospital-building-city-clinic-glass-exterior-flat-vector-illustration-medical-help-emergency-architecture-healthcare-concept_74855-10130.jpg" class="card-img-top" style="height: 20rem;" alt="listing_image">
                <div class="card-img-overlay">a</div>
                <div class="card-body">
                    <p class="card-text">
                        <b>
                            <?php echo htmlspecialchars($listings['hospital_name']); ?>
                        </b>
                    </p>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';
?>
