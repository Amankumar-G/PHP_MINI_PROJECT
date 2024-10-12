<?php 
ob_start(); // Start output buffering

// Ensure $listing and $ogurl are defined here before using them
// Example: $listing = fetchListingFromDatabase(); 
// Example: $ogurl = fetchOriginalImageUrl();

?>
<body>
    <div class="row mt-3">
        <div class="col-8 offset-2">
            <h3>Edit your listing!</h3>
            <form method="POST" action="/listings/<?php echo $listing['_id']; ?>?_method=PUT" novalidate class="needs-validation" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input name="listing[title]" type="text" value="<?php echo htmlspecialchars($listing['title']); ?>" required placeholder="enter title" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="listing[description]" placeholder="enter description" required class="form-control"><?php echo htmlspecialchars($listing['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    Original Image <br>
                    <img src="<?php echo htmlspecialchars($ogurl); ?>" class="ogimg" alt="">
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input name="listing[image]" type="file" class="form-control">
                </div>

                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="price" class="form-label">Price</label>
                        <input name="listing[price]" type="number" required value="<?php echo htmlspecialchars($listing['price']); ?>" placeholder="enter price" class="form-control">
                    </div>

                    <div class="mb-3 col-md-8">
                        <label for="country" class="form-label">Country</label>
                        <input name="listing[country]" type="text" required value="<?php echo htmlspecialchars($listing['country']); ?>" placeholder="enter country" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input name="listing[location]" required type="text" value="<?php echo htmlspecialchars($listing['location']); ?>" placeholder="enter location" class="form-control">
                </div>

                <button class="btn btn-dark add-btn mt-3">Submit</button>
            </form>
        </div>
    </div>
    <br><br>
</body>
<?php

$content = ob_get_clean(); // Get the content and clean the output buffer

include 'boilerplate.php'; // Include the boilerplate layout
?>
