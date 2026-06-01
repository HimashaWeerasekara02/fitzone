<?php
include '../includes/db.php';
include 'admin_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0){
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $id);
    } else {
        $sql = "UPDATE users SET username=?, email=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }
    
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'User updated successfully.'];
    header("Location: manage_users.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();

if(!$user){
    header("Location: manage_users.php");
    exit();
}
?>
<h2>Edit User</h2>
<div class="card">
    <div class="card-body">
        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>" class="needs-validation" novalidate>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" <?php if($user['id'] == 1) echo 'disabled'; ?>>
                    <option value="customer" <?php if($user['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                    <option value="staff" <?php if($user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                    <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include 'admin_footer.php'; ?><?php
include '../includes/db.php';
include 'admin_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0){
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $id);
    } else {
        $sql = "UPDATE users SET username=?, email=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }
    
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'User updated successfully.'];
    header("Location: manage_users.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();

if(!$user){
    header("Location: manage_users.php");
    exit();
}
?>
<h2>Edit User</h2>
<div class="card">
    <div class="card-body">
        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>" class="needs-validation" novalidate>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" <?php if($user['id'] == 1) echo 'disabled'; ?>>
                    <option value="customer" <?php if($user['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                    <option value="staff" <?php if($user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                    <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include 'admin_footer.php'; ?>