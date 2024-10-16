<?php
// Start output buffering
ob_start();
?>

    <h1>All listings</h1>
    <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-3">
        <?php foreach ($alllistings as $listings): ?>
            <a href="/listings/<?php echo $listings['_id']; ?>" class="listing-link">
                <div class="card listing-card col">
                    <img src="<?php echo $listings['image']; ?>" class="card-img-top" style="height: 20rem;"
                        alt="listing_image">
                        <div class="card-img-overlay">a</div>
                    <div class="card-body">
                        <p class="card-text">
                            <b>
                                <?php echo $listings['title']; ?>
                            </b> <br>
                            &#8377; <?php echo number_format($listings['price'], 0, '', ','); ?>/night
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