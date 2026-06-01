<?php
include '../includes/db.php';
include 'admin_header.php';
?>
<div class="dashboard-hero text-center mb-4">
    <h2 class="display-4">Admin Dashboard</h2>
    <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
</div>
<p>From here, you can manage all aspects of the FitZone website. Use the sidebar to navigate between sections.</p>
<?php include 'admin_footer.php'; ?>