<?php
// Start output buffering
ob_start();
?>

    <br>
    <div class="row">
        <div class="col-8 offset-2">
            <h3>Create new listings!</h3>
            <form method="POST" action="/listings" novalidate class="needs-validation" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input name="listing[title]" type="text" placeholder="Enter title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="listing[description]" required placeholder="Enter description"
                        class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input name="listing[image]" type="file" class="form-control">
                </div>
                <div class="row">
                    <div class="mb-3 col-4">
                        <label for="price" class="form-label">Price</label>
                        <input name="listing[price]" required placeholder="Enter price" class="form-control">
                    </div>
                    <div class="mb-3 col-8">
                        <label for="location" class="form-label">Country</label>
                        <input name="listing[country]" type="text" required placeholder="Enter country"
                            class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input name="listing[location]" type="text" required placeholder="Enter location"
                        class="form-control">
                </div>
                <br><br>
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
