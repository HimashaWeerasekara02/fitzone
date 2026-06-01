<?php 
session_start(); 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_SESSION['feedback'])) {
    $feedback = $_SESSION['feedback'];
    unset($_SESSION['feedback']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FitZone</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="d-flex">
    <div class="dashboard-sidebar">
        <h3>Admin Panel</h3>
        <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="manage_classes.php"><i class="fas fa-chalkboard-teacher"></i> Manage Classes</a>
        <a href="manage_trainers.php"><i class="fas fa-user-shield"></i> Manage Trainers</a>
        <a href="manage_memberships.php"><i class="fas fa-id-card"></i> Manage Memberships</a>
        <a href="manage_blog.php"><i class="fas fa-blog"></i> Manage Blog</a>
        <a href="manage_queries.php"><i class="fas fa-question-circle"></i> Manage Queries</a>
        <a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="dashboard-main">
        <!-- Universal Feedback Modal -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true" data-message="<?php echo isset($feedback) ? htmlspecialchars($feedback['message']) : ''; ?>">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="feedbackModalBody">
                <!-- Message will be inserted here by JavaScript -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>