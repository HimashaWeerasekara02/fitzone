<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// Fetch Inquiries and Replies
$inquiries_sql = "
    SELECT q.id, q.message, q.created_at, r.reply_message, r.created_at as reply_date
    FROM queries q
    LEFT JOIN inquiry_replies r ON q.id = r.query_id
    WHERE q.user_id = $user_id
    ORDER BY q.created_at DESC";
$inquiries_result = $conn->query($inquiries_sql);

// Fetch Training Registrations
$registrations_sql = "
    SELECT t.name as trainer_name, t.specialty, tr.status, tr.created_at
    FROM training_registrations tr
    JOIN trainers t ON tr.trainer_id = t.id
    WHERE tr.user_id = $user_id
    ORDER BY tr.created_at DESC";
$registrations_result = $conn->query($registrations_sql);
?>

<div class="container my-5">
    <h1 class="text-center mb-5" style="color: #64ffda;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>

    <div class="row">
        <!-- Training Registrations -->
        <div class="col-md-6">
            <h3>My Training Requests</h3>
            <div class="card">
                <div class="card-body">
                    <?php if ($registrations_result && $registrations_result->num_rows > 0): ?>
                        <ul class="list-group list-group-flush">
                        <?php while ($reg = $registrations_result->fetch_assoc()): ?>
                            <li class="list-group-item" style="background-color: #112240;">
                                <strong>Trainer:</strong> <?php echo htmlspecialchars($reg['trainer_name']); ?><br>
                                <small>Status: <span class="badge badge-info"><?php echo htmlspecialchars($reg['status']); ?></span> | Requested on: <?php echo date('Y-m-d', strtotime($reg['created_at'])); ?></small>
                            </li>
                        <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>You have not made any training requests yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Inquiries and Replies -->
        <div class="col-md-6">
            <h3>My Inquiries</h3>
            <div class="card">
                <div class="card-body">
                    <?php if ($inquiries_result && $inquiries_result->num_rows > 0): ?>
                        <?php while ($inquiry = $inquiries_result->fetch_assoc()): ?>
                            <div class="mb-3">
                                <p class="mb-1"><strong>Your message:</strong> "<?php echo htmlspecialchars($inquiry['message']); ?>"</p>
                                <?php if ($inquiry['reply_message']): ?>
                                    <div class="alert alert-success mt-2">
                                        <strong>Reply:</strong> "<?php echo htmlspecialchars($inquiry['reply_message']); ?>"
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning mt-2">
                                        Awaiting reply from staff.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <hr style="border-color: #233554;">
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>You have not sent any inquiries.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>