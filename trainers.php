<?php 
include 'includes/db.php';
include 'includes/header.php'; 

if (isset($_GET['request_trainer']) && isset($_SESSION['user_id'])) {
    $trainer_id = (int)$_GET['request_trainer'];
    $user_id = (int)$_SESSION['user_id'];

    // Prevent duplicate requests
    $check = $conn->query("SELECT id FROM training_registrations WHERE user_id = $user_id AND trainer_id = $trainer_id");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO training_registrations (user_id, trainer_id) VALUES ($user_id, $trainer_id)");
    }
    header("Location: trainers.php?status=requested");
    exit();
}

$result = $conn->query("SELECT * FROM trainers");
?>

<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #00c7b3;">Meet Our Trainers</h1>
    <p class="text-center mb-5">Our certified and experienced trainers are here to guide and motivate you on your fitness journey.</p>
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'requested'): ?>
        <div class="alert alert-success text-center">Your training request has been sent! You can check its status on your dashboard.</div>
    <?php endif; ?>

    <div class="row">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($trainer = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 text-center trainer-card">
                        <div class="card-body d-flex flex-column">
                            <?php 
                            $image_path = (!empty($trainer['image_path']) && file_exists('assets/img/' . $trainer['image_path'])) 
                                          ? 'assets/img/' . $trainer['image_path'] 
                                          : 'https://placehold.co/150x150/112240/00c7b3?text=Trainer';
                            ?>
                            <img src="<?php echo $image_path; ?>" class="trainer-img" alt="<?php echo htmlspecialchars($trainer['name']); ?>">
                            <h5 class="card-title"><?php echo htmlspecialchars($trainer['name']); ?></h5>
                            <h6 class="card-subtitle mb-2" style="color: #8892b0;"><?php echo htmlspecialchars($trainer['specialty']); ?></h6>
                            <p class="card-text"><?php echo htmlspecialchars($trainer['bio']); ?></p>
                            
                            <ul class="trainer-details text-left flex-grow-1">
                                <?php if(!empty($trainer['certifications'])): ?>
                                <li><i class="fas fa-certificate" style="color: #00c7b3;"></i> <?php echo htmlspecialchars($trainer['certifications']); ?></li>
                                <?php endif; ?>
                                <?php if(!empty($trainer['price_per_hour'])): ?>
                                <li><i class="fas fa-dollar-sign" style="color: #00c7b3;"></i> $<?php echo htmlspecialchars($trainer['price_per_hour']); ?>/hour</li>
                                <?php endif; ?>
                            </ul>

                            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'customer'): ?>
                                <a href="?request_trainer=<?php echo $trainer['id']; ?>" class="btn btn-primary mt-auto">Book Session</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col"><p class="text-center">Trainer information is not available yet.</p></div>
        <?php endif; ?>
    </div>

    <hr class="my-5" style="border-color: #233554;">

    <div class="text-center">
        <p>Ready for a personalized fitness journey? Contact us to discuss your goals!</p>
        <a href="contact.php" class="btn btn-primary">Inquire About Trainers</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>