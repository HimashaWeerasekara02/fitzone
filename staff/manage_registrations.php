<?php
include '../includes/db.php';
include 'staff_header.php';

if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $conn->real_escape_string($_GET['update_status']);
    $conn->query("UPDATE training_registrations SET status = '$status' WHERE id = $id");
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Registration status updated.'];
    header("Location: manage_registrations.php");
    exit();
}

$registrations_sql = "
    SELECT tr.id, u.username, t.name as trainer_name, tr.status, tr.created_at
    FROM training_registrations tr
    JOIN users u ON tr.user_id = u.id
    JOIN trainers t ON tr.trainer_id = t.id
    ORDER BY tr.created_at DESC";
$registrations_result = $conn->query($registrations_sql);
?>
<h2>Manage Training Requests</h2>
<table class="table table-dark table-striped table-hover">
    <thead><tr><th>Customer</th><th>Requested Trainer</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
        <?php while($row = $registrations_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['trainer_name']); ?></td>
            <td><span class="badge badge-info"><?php echo htmlspecialchars($row['status']); ?></span></td>
            <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
            <td>
                <a href="?id=<?php echo $row['id']; ?>&update_status=Confirmed" class="btn btn-success btn-sm"><i class="fas fa-check"></i></a>
                <a href="?id=<?php echo $row['id']; ?>&update_status=Cancelled" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'staff_footer.php'; ?>