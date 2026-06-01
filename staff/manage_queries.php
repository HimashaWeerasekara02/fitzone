<?php
include '../includes/db.php';
include 'staff_header.php';

$queries = $conn->query("SELECT * FROM queries ORDER BY created_at DESC");
?>
<h2>Manage User Queries</h2>
<table class="table table-dark table-striped table-hover">
    <thead><tr><th>ID</th><th>Name</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        <?php while($row = $queries->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars(substr($row['message'], 0, 50)); ?>...</td>
            <td><span class="badge badge-<?php echo $row['status'] == 'pending' ? 'warning' : 'success'; ?>"><?php echo ucfirst($row['status']); ?></span></td>
            <td>
                <a href="reply_inquiry.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-reply"></i> View/Reply</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'staff_footer.php'; ?>