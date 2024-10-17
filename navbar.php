<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/listings"><i class="fa-solid fa-compass"></i></a>
        <button class="navbar-toggler toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">All Hospitals</a>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="new.php">Add your Hospitals</a>
                <?php if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true): ?>
                    <!-- If the user is not logged in, show Sign Up and Login options -->
                    <a class="nav-link" href="signup.php">Sign Up</a>
                    <a class="nav-link" href="login.php">Login</a>
                <?php else: ?>
                    <!-- If the user is logged in, show options based on their role -->
                    <?php if ($_SESSION['user_role'] === 'patient'): ?>
                        <a class="nav-link" href="patient_profile.php">Patient Profile</a>
                    <?php elseif ($_SESSION['user_role'] === 'hospital'): ?>
                        <a class="nav-link" href="hospital_profile.php">Hospital Profile</a>
                    <?php endif; ?>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
