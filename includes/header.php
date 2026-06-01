<?php 
session_start(); 
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
    <title>FitZone Fitness Center</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-dumbbell"></i> FitZone</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="classes.php">Classes</a></li>
                <li class="nav-item"><a class="nav-link" href="trainers.php">Trainers</a></li>
                <li class="nav-item"><a class="nav-link" href="membership.php">Membership</a></li>
                <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['user_role'] == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin Panel</a></li>
                    <?php elseif($_SESSION['user_role'] == 'staff'): ?>
                         <li class="nav-item"><a class="nav-link" href="staff/index.php">Staff Panel</a></li>
                    <?php elseif($_SESSION['user_role'] == 'customer'): ?>
                         <li class="nav-item"><a class="nav-link" href="dashboard.php">My Dashboard</a></li>
                    <?php endif; ?>
                    <li class="nav-item ml-2"><a class="btn btn-secondary btn-sm" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link btn btn-secondary btn-sm" href="login.php">Login</a></li>
                    <li class="nav-item ml-2"><a class="nav-link btn btn-secondary btn-sm" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

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