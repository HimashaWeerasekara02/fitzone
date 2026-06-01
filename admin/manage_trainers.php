<?php
include '../includes/db.php';
include 'admin_header.php';

// Add Trainer
if(isset($_POST['add_trainer'])){
    $name = $conn->real_escape_string($_POST['name']);
    $specialty = $conn->real_escape_string($_POST['specialty']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $certifications = $conn->real_escape_string($_POST['certifications']);
    $price = (float)$_POST['price_per_hour'];
    $image_path = null;

    if (isset($_FILES['trainer_image']) && $_FILES['trainer_image']['error'] == 0) {
        $upload_dir = '../assets/img/';
        $file_name = time() . '_trainer_' . basename($_FILES['trainer_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['trainer_image']['tmp_name'], $target_file)) {
            $image_path = $file_name;
        }
    }

    $sql = "INSERT INTO trainers (name, specialty, bio, image_path, certifications, price_per_hour) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssd", $name, $specialty, $bio, $image_path, $certifications, $price);
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Trainer added successfully.'];
    header("Location: manage_trainers.php");
    exit();
}

// Delete Trainer
if(isset($_GET['delete_trainer'])){
    $id = (int)$_GET['delete_trainer'];
    
    $res = $conn->query("SELECT image_path FROM trainers WHERE id=$id");
    if($row = $res->fetch_assoc()){
        if(!empty($row['image_path']) && file_exists('../assets/img/' . $row['image_path'])){
            unlink('../assets/img/' . $row['image_path']);
        }
    }

    $conn->query("DELETE FROM trainers WHERE id=$id");
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Trainer deleted successfully.'];
    header("Location: manage_trainers.php");
    exit();
}

$trainers = $conn->query("SELECT * FROM trainers ORDER BY id DESC");
?>
<h2>Manage Trainers</h2>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Add New Trainer</h5>
        <form method="POST" action="manage_trainers.php" class="needs-validation" novalidate enctype="multipart/form-data">
            <div class="form-group"><input type="text" name="name" placeholder="Trainer Name" required class="form-control"></div>
            <div class="form-group"><input type="text" name="specialty" placeholder="Specialty (e.g., Strength Training)" required class="form-control"></div>
            <div class="form-group"><textarea name="bio" placeholder="Short Bio" required class="form-control"></textarea></div>
            <div class="form-group"><input type="text" name="certifications" placeholder="Certifications (e.g., Certified NSCA-CSCS)" class="form-control"></div>
            <div class="form-group"><input type="number" step="0.01" name="price_per_hour" placeholder="Price per hour" class="form-control"></div>
            <div class="form-group">
                <label>Trainer Image (Square recommended)</label>
                <input type="file" name="trainer_image" class="form-control-file">
            </div>
            <button type="submit" name="add_trainer" class="btn btn-primary">Add Trainer</button>
        </form>
    </div>
</div>

<div class="row">
    <?php while($trainer = $trainers->fetch_assoc()): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 text-center trainer-card">
            <div class="card-body">
                <?php 
                $image_path = (!empty($trainer['image_path']) && file_exists('../assets/img/' . $trainer['image_path'])) 
                              ? '../assets/img/' . $trainer['image_path'] 
                              : 'https://placehold.co/150x150/112240/00c7b3?text=Trainer';
                ?>
                <img src="<?php echo $image_path; ?>" class="trainer-img" alt="<?php echo htmlspecialchars($trainer['name']); ?>">
                <h5 class="card-title"><?php echo htmlspecialchars($trainer['name']); ?></h5>
                <h6 class="card-subtitle mb-2" style="color: #8892b0;"><?php echo htmlspecialchars($trainer['specialty']); ?></h6>
                <p class="card-text"><?php echo htmlspecialchars($trainer['bio']); ?></p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="edit_trainer.php?id=<?php echo $trainer['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                <a href="?delete_trainer=<?php echo $trainer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include 'admin_footer.php'; ?>