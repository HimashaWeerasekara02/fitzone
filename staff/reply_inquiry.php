<?php
include '../includes/db.php';
include 'staff_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: manage_queries.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply_message = $conn->real_escape_string($_POST['reply_message']);
    $user_id = $_SESSION['user_id']; // Staff ID

    // Check if a reply already exists
    $check_sql = "SELECT id FROM inquiry_replies WHERE query_id = $id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $reply = $check_result->fetch_assoc();
        $reply_id = $reply['id'];
        $update_sql = "UPDATE inquiry_replies SET reply_message = ?, user_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sii", $reply_message, $user_id, $reply_id);
    } else {
        $insert_sql = "INSERT INTO inquiry_replies (query_id, user_id, reply_message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iis", $id, $user_id, $reply_message);
    }
    
    $stmt->execute();

    $conn->query("UPDATE queries SET status = 'answered' WHERE id = $id");

    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Reply sent successfully.'];
    header("Location: manage_queries.php");
    exit();
}

$query_result = $conn->query("SELECT * FROM queries WHERE id = $id");
$query = $query_result->fetch_assoc();

$reply_result = $conn->query("SELECT * FROM inquiry_replies WHERE query_id = $id");
$reply = $reply_result->fetch_assoc();
?>
<h2>Reply to Inquiry</h2>
<div class="card">
    <div class="card-header">
        <strong>From:</strong> <?php echo htmlspecialchars($query['name']); ?> (<?php echo htmlspecialchars($query['email']); ?>)
    </div>
    <div class="card-body">
        <p><strong>Message:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($query['message'])); ?></p>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">Your Reply</h5>
        <form method="POST" class="needs-validation" novalidate>
            <div class="form-group">
                <textarea name="reply_message" class="form-control" rows="5" required><?php echo isset($reply['reply_message']) ? htmlspecialchars($reply['reply_message']) : ''; ?></textarea>
                <div class="invalid-feedback">A reply message is required.</div>
            </div>
            <button type="submit" class="btn btn-primary">Send Reply</button>
            <a href="manage_queries.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
<?php include 'staff_footer.php'; ?>