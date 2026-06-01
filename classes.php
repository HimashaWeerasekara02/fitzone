<?php 
include 'includes/db.php';
include 'includes/header.php'; 
$result = $conn->query("SELECT * FROM classes");
?>

<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #00c7b3;">Our Classes</h1>
    <p class="text-center mb-5">Find the perfect class to fit your goals and schedule. We offer a variety of options for all fitness levels.</p>
    
    <div class="row">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($class = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 class-card">
                        <?php 
                        
                        $image_path = (!empty($class['image_path']) && file_exists('assets/img/' . $class['image_path'])) 
                                      ? 'assets/img/' . $class['image_path'] 
                                      : 'https://placehold.co/600x400/112240/00c7b3?text=' . urlencode(htmlspecialchars($class['name']));
                        ?>
                        <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($class['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($class['name']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($class['description']); ?></p>
                            <div class="class-schedule mt-auto">
                                <i class="far fa-clock"></i> <?php echo htmlspecialchars($class['schedule']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col"><p class="text-center">No classes are available at the moment. Please check back later.</p></div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>