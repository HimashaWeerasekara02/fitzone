<?php
include '../includes/db.php';
include 'staff_header.php';
?>
<div class="dashboard-hero text-center mb-4">
    <h2 class="display-4">Staff Dashboard</h2>
    <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
</div>
<p>You can manage customer training requests and inquiries from the sidebar.</p>
<?php include 'staff_footer.php'; ?>