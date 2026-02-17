<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerbtn'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $gender   = $_POST['gender'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    $validationErrors = [];

    if (empty($name)) $validationErrors[] = 'Name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $validationErrors[] = 'Valid email is required.';
    if (empty($gender)) $validationErrors[] = 'Gender is required.';
    if (empty($password) || strlen($password) < 6) $validationErrors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $validationErrors[] = 'Passwords do not match.';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $validationErrors[] = 'Email already exists.';
    $stmt->close();

    if (empty($validationErrors)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, gender, password)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $name, $email, $gender, $hashed_password,);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();

    } else {
        $error = implode("<br>", $validationErrors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - PAO System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .register-container { max-width: 500px; margin: 50px auto; }
        .card { border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card-header { background-color: #198754; color: white; }
        .btn-primary { width: 100%; background-color: #198754; }
    </style>
</head>
<body>

<div class="container register-container">
    <div class="card">
        <div class="card-header text-center">
            <h4>Create Account</h4>
        </div>

        <div class="card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" name="registerbtn" class="btn btn-primary">
                    Register
                </button>

            </form>

            <div class="text-center mt-3">
                <a href="login.php">Already have an account? Login here</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
