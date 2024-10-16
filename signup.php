<?php
// Start output buffering
ob_start();
?>

    <div class="row mt-3">
        <h1 class="col-6 offset-3 mb-3">SignUp</h1>
        <div class="col-6 offset-3">
            <form action="/signup" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input name="username" type="text" placeholder="Enter username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input name="email" type="email" placeholder="Enter email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" placeholder="Enter password" id="password" class="form-control" required>
                </div>
                <button class="btn btn-outline-dark mt-3">SignUp</button>
            </form>
        </div>
    </div>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout
include 'boilerplate.php';
?>
