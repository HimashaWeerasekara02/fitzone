<?php
include '../includes/db.php';
include 'admin_header.php';

// Add User
if(isset($_POST['add_user'])){
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']);
    
    // Set password reset flag for new staff
    $reset_required = ($role == 'staff') ? 1 : 0;
    
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows == 0) {
        $sql = "INSERT INTO users (username, email, password, role, password_reset_required) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $reset_required);
        $stmt->execute();
        $_SESSION['feedback'] = ['type' => 'success', 'message' => 'User added successfully.'];
    } else {
        $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Email already exists.'];
    }
    header("Location: manage_users.php");
    exit();
}

// Delete User
if(isset($_GET['delete_user'])){
    $id = (int)$_GET['delete_user'];
    if ($id != 1) { 
        $sql = "DELETE FROM users WHERE id=$id";
        $conn->query($sql);
        $_SESSION['feedback'] = ['type' => 'success', 'message' => 'User deleted successfully.'];
    }
    header("Location: manage_users.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id ASC");
?>
<h2>Manage Users</h2>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Add New User</h5>
        <form method="POST" action="manage_users.php" class="needs-validation" novalidate>
            <div class="form-row">
                <div class="col-md-3 mb-2"><input type="text" name="username" placeholder="Username" required class="form-control"><div class="invalid-feedback">Username is required.</div></div>
                <div class="col-md-3 mb-2"><input type="email" name="email" placeholder="Email" required class="form-control"><div class="invalid-feedback">A valid email is required.</div></div>
                <div class="col-md-2 mb-2"><input type="password" name="password" placeholder="Password" required class="form-control" minlength="6"><div class="invalid-feedback">Password is required (min 6 chars).</div></div>
                <div class="col-md-2 mb-2">
                    <select name="role" class="form-control">
                        <option value="customer">Customer</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2"><button type="submit" name="add_user" class="btn btn-primary btn-block">Add User</button></div>
            </div>
        </form>
    </div>
</div>
        
<table class="table table-dark table-striped table-hover">
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Registered</th><th>Actions</th></tr></thead>
    <tbody>
        <?php while($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td>
            <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                <?php if ($row['id'] != 1): ?>
                <a href="?delete_user=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'admin_footer.php'; ?>