<?php
// Start output buffering
ob_start();
?>

<br>
<div class="row">
    <div class="col-8 offset-2">
        <h3>Create New Listings!</h3>
        <form method="POST" action="/listings" novalidate class="needs-validation" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="hospital_name" class="form-label">Hospital Name</label>
                <input name="listing[hospital_name]" type="text" placeholder="Enter hospital name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input name="listing[address]" type="text" placeholder="Enter address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="zip_code" class="form-label">Zip Code</label>
                <input name="listing[zip_code]" type="text" placeholder="Enter zip code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input name="listing[contact_number]" type="tel" placeholder="Enter contact number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="listing[email]" type="email" placeholder="Enter email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="receptionist_name" class="form-label">Receptionist's Name</label>
                <input name="listing[receptionist_name]" type="text" placeholder="Enter receptionist's name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="receptionist_contact" class="form-label">Receptionist's Contact Number</label>
                <input name="listing[receptionist_contact]" type="tel" placeholder="Enter receptionist's contact number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="listing[password]" type="password" placeholder="Enter password" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input name="listing[image]" type="file" class="form-control">
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
