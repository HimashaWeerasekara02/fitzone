<?php 
include 'includes/db.php';
include 'includes/header.php'; 
$result = $conn->query("SELECT * FROM memberships ORDER BY price ASC");
?>

<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #00c7b3;">Flexible Membership Plans</h1>
    <p class="text-center mb-5">Choose the plan that fits your lifestyle and start your fitness journey today!</p>
    
    <div class="row justify-content-center">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($plan = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 membership-card <?php echo $plan['is_popular'] ? 'popular-card' : ''; ?>">
                        <?php if($plan['is_popular']): ?>
                            <div class="popular-badge">Popular</div>
                        <?php endif; ?>
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($plan['name']); ?></h5>
                            <h2 class="card-price my-3" style="color: #ccd6f6;">$<?php echo htmlspecialchars(number_format($plan['price'], 0)); ?><span style="font-size: 1rem; color: #8892b0;">/month</span></h2>
                            <ul class="list-unstyled mt-3 mb-4 flex-grow-1 text-left">
                                <?php 
                                $features = explode(';', $plan['features']);
                                foreach($features as $feature) {
                                    $is_negative = (strpos($feature, '-') === 0);
                                    $feature_text = $is_negative ? substr($feature, 1) : $feature;
                                    $icon_class = $is_negative ? 'fas fa-times-circle text-danger' : 'fas fa-check-circle text-success';
                                    echo "<li><i class='{$icon_class} mr-2'></i>" . htmlspecialchars(trim($feature_text)) . "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col"><p class="text-center">Membership plan details are coming soon.</p></div>
        <?php endif; ?>
    </div>


</div>

<?php include 'includes/footer.php'; ?>
