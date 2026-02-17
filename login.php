<?php
require_once 'config.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/dashboard.php');
    } elseif (isUser()) {
        redirect('user/dashboard.php');
    }
}

$error = '';
$roleWarning = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {

        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (!password_verify($password, $user['password'])) {
                $error = 'Invalid email or password.';
            } 
            
            elseif (empty($user['role'])) {
                $roleWarning = true;
            } 
            
            else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['email']   = 'email';

                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard.php');
                } elseif ($user['role'] === 'user') {
                    redirect('user/dashboard.php');
                } else {
                    $error = 'Invalid user role.';
                }
            }

        } else {
            $error = 'Invalid email or password.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PAO System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #198754 !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            color: #fff !important;
        }

        .login-container {
            max-width: 500px;
            margin: 120px auto;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #198754;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-primary {
            width: 100%;
            background-color: #198754;
            border-color: #198754;
        }

        .btn-primary:hover {
            background-color: #157347;
            border-color: #157347;
        }

        a {
            color: #198754;
        }

        a:hover {
            color: #157347;
        }
    </style>
</head>

<body>

<div class="container login-container">
    <div class="card">
        <div class="card-header text-center py-3">
            <h4>Login</h4>
        </div>

        <div class="card-body p-4">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                </div>

            </form>

            <div class="text-center">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="roleWarningModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Role Not Assigned</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>
                     <strong>no role has been assigned yet</strong>.
                </p>
                <p>
                    Please wait for the administrator to assign your role before logging in.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($roleWarning): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('roleWarningModal');
        const roleModal = new bootstrap.Modal(modalEl);
        roleModal.show();
    });
</script>
<?php endif; ?>

</body>
</html>
