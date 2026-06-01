<?php
include 'includes/db.php';
include 'includes/header.php';

// Ensure only staff who need to reset can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$check_sql = "SELECT password_reset_required FROM users WHERE id = $user_id";
$check_result = $conn->query($check_sql);
$user = $check_result->fetch_assoc();

if ($user['password_reset_required'] != 1) {
    header("Location: staff/index.php"); // Already reset, go to dashboard
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        if (strlen($new_password) >= 6) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users SET password = ?, password_reset_required = 0 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
            
            $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Password updated successfully. You can now access your dashboard.'];
            header("Location: staff/index.php");
            exit();
        } else {
            $error = "Password must be at least 6 characters long.";
        }
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center">Reset Your Password</h2>
                    <p class="text-center text-muted">For your security, you must set a new password before you can proceed.</p>
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form action="force_reset_password.php" method="POST" class="needs-validation" novalidate>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                            <div class="invalid-feedback">Password must be at least 6 characters.</div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="invalid-feedback">Please confirm your password.</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Set New Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>