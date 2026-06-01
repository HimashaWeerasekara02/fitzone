<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'staff'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['feedback'])) {
    $feedback = $_SESSION['feedback'];
    unset($_SESSION['feedback']);
}

// Delete Query
if(isset($_GET['delete_query'])){
    $id = (int)$_GET['delete_query'];
    $conn->query("DELETE FROM queries WHERE id=$id");
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Inquiry deleted successfully.'];
    header("Location: manage_queries.php");
    exit();
}

$queries = $conn->query("SELECT * FROM queries ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Queries</title>
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
        <a href="manage_memberships.php">Manage Memberships</a>
        <a href="manage_blog.php">Manage Blog</a>
        <a href="manage_queries.php" class="active">Manage Queries</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="dashboard-main">
        <h2>Manage User Queries</h2>
        <?php if (isset($feedback)): ?>
            <div class="alert alert-<?php echo $feedback['type']; ?>"><?php echo $feedback['message']; ?></div>
        <?php endif; ?>
        <table class="table table-dark table-striped">
            <thead><tr><th>ID</th><th>Name</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while($row = $queries->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><span class="badge badge-<?php echo $row['status'] == 'pending' ? 'warning' : 'success'; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                    <td>
                        <a href="reply_inquiry.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View/Reply</a>
                        <a href="?delete_query=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>