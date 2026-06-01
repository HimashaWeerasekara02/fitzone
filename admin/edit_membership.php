<?php

include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0){
    header("Location: manage_memberships.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = (float)$_POST['price'];
    $features = $conn->real_escape_string($_POST['features']);
    
    $sql = "UPDATE memberships SET name = ?, price = ?, features = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $name, $price, $features, $id);
    $stmt->execute();
    header("Location: manage_memberships.php");
    exit();
}

$result = $conn->query("SELECT * FROM memberships WHERE id = $id");
$plan = $result->fetch_assoc();

if(!$plan){
    header("Location: manage_memberships.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Membership</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="d-flex">
    <div class="dashboard-sidebar">
        <h3>Admin Panel</h3>
        <a href="index.php">Dashboard</a>
        <a href="manage_classes.php">Manage Classes</a>
        <a href="manage_trainers.php">Manage Trainers</a>
        <a href="manage_memberships.php" class="active">Manage Memberships</a>
        <a href="manage_blog.php">Manage Blog</a>
        <a href="manage_queries.php">Manage Queries</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="dashboard-main">
        <h2>Edit Membership Plan</h2>
        <div class="card"><div class="card-body">
            <form method="POST" action="edit_membership.php?id=<?php echo $id; ?>">
                <div class="form-group">
                    <label>Plan Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($plan['name']); ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($plan['price']); ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Features (separate with semicolon ;)</label>
                    <textarea name="features" required class="form-control"><?php echo htmlspecialchars($plan['features']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Plan</button>
                <a href="manage_memberships.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div></div>
    </div>
</div>
</body>
</html>
