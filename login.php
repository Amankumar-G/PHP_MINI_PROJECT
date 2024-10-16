<?php
session_start();

// Include the database connection
include 'db.php'; // Ensure this points to your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the selected role

    $isLoggedIn = false;

    // Determine the SQL statement and the redirect based on the role
    if ($role == 'hospital') {
        $stmt = $conn->prepare("SELECT password FROM hospital WHERE email = ?");
    } elseif ($role == 'patient') {
        $stmt = $conn->prepare("SELECT password FROM patient WHERE email = ?");
    } else {
        $error = "Please select a valid role.";
    }

    if (isset($stmt)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // If the user exists, verify the password
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $isLoggedIn = true;
                $_SESSION['user_role'] = $role; // Set the user role in session
                $_SESSION['email'] = $email;
                // Redirect based on role
                if ($role == 'hospital') {
                    header("Location: hospital_profile.php");
                } else {
                    header("Location: patient_profile.php");
                }
                exit;
            }
        }

        // If no match is found or password verification fails
        $error = "Invalid email or password!";
        
        // Close the statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
}
?>

<div class="container mt-5">
    <div class="row mt-3">
        <h1 class="col-6 offset-3 mb-3">Login</h1>
        <div class="col-6 offset-3">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="role" class="form-label">Login As</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Select role</option>
                        <option value="hospital">Hospital Admin</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input name="email" type="text" placeholder="Enter email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" placeholder="Enter password" id="password" class="form-control" required>
                </div>
                <button class="btn btn-outline-dark mt-3">Login</button>
            </form>
        </div>
    </div>
</div>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';
?>
