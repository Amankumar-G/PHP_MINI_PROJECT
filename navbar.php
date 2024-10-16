<nav class="navbar navbar-expand-lg bg-body-tertiary  border-bottom sticky-top">
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
                <a class="nav-link" href="new.php">Add Your Hospital</a>
                <a class="nav-link" href="patient_profile.php">Profile</a>
                <!-- < if(!currUser){ %> -->
                <a class="nav-link " aria-current="page" href="signup.php">Sign Up</a>
                <a class="nav-link" href="login.php">login</a>
                <!-- <} %> -->
                <!-- < if(currUser){ %> -->
                <a class="nav-link" href="logout.php">logout</a>
                <!-- < } %> -->
            </div>
        </div>
    </div>
</nav>