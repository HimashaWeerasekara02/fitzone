<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Add Plan
if(isset($_POST['add_plan'])){
    $name = $conn->real_escape_string($_POST['name']);
    $price = (float)$_POST['price'];
    $features = $conn->real_escape_string($_POST['features']);
    $sql = "INSERT INTO memberships (name, price, features) VALUES ('$name', '$price', '$features')";
    $conn->query($sql);
    header("Location: manage_memberships.php");
    exit();
}

// Delete Plan
if(isset($_GET['delete_plan'])){
    $id = (int)$_GET['delete_plan'];
    $sql = "DELETE FROM memberships WHERE id=$id";
    $conn->query($sql);
    header("Location: manage_memberships.php");
    exit();
}

$plans = $conn->query("SELECT * FROM memberships ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Memberships</title>
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
        <h2>Manage Membership Plans</h2>
        <div class="card mb-4"><div class="card-body">
            <h5 class="card-title">Add New Plan</h5>
            <form method="POST" action="manage_memberships.php">
                <div class="form-group"><input type="text" name="name" placeholder="Plan Name (e.g., Basic)" required class="form-control"></div>
                <div class="form-group"><input type="number" step="0.01" name="price" placeholder="Price per month" required class="form-control"></div>
                <div class="form-group"><textarea name="features" placeholder="Features (separate with semicolon ;)" required class="form-control"></textarea></div>
                <button type="submit" name="add_plan" class="btn btn-primary">Add Plan</button>
            </form>
        </div></div>
        <table class="table table-dark table-striped">
            <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while($row = $plans->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>$<?php echo htmlspecialchars($row['price']); ?></td>
                    <td>
                        <a href="edit_membership.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                        <a href="?delete_plan=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>