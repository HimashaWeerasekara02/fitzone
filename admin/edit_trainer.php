<?php
include '../includes/db.php';
include 'admin_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: manage_trainers.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $specialty = $conn->real_escape_string($_POST['specialty']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $certifications = $conn->real_escape_string($_POST['certifications']);
    $price = (float)$_POST['price_per_hour'];
    $current_image = $_POST['current_image'];
    $new_image_path = $current_image;

    if (isset($_FILES['trainer_image']) && $_FILES['trainer_image']['error'] == 0) {
        $upload_dir = '../assets/img/';
        $file_name = time() . '_trainer_' . basename($_FILES['trainer_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['trainer_image']['tmp_name'], $target_file)) {
            if(!empty($current_image) && file_exists($upload_dir . $current_image)){
                unlink($upload_dir . $current_image);
            }
            $new_image_path = $file_name;
        }
    }
    
    $sql = "UPDATE trainers SET name = ?, specialty = ?, bio = ?, image_path = ?, certifications = ?, price_per_hour = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdi", $name, $specialty, $bio, $new_image_path, $certifications, $price, $id);
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Trainer updated successfully.'];
    header("Location: manage_trainers.php");
    exit();
}

$result = $conn->query("SELECT * FROM trainers WHERE id = $id");
$trainer = $result->fetch_assoc();

if (!$trainer) {
    header("Location: manage_trainers.php");
    exit();
}
?>
<h2>Edit Trainer</h2>
<div class="card">
    <div class="card-body">
        <form method="POST" action="edit_trainer.php?id=<?php echo $id; ?>" class="needs-validation" novalidate enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($trainer['image_path']); ?>">
            <div class="form-group"><label>Trainer Name</label><input type="text" name="name" value="<?php echo htmlspecialchars($trainer['name']); ?>" required class="form-control"></div>
            <div class="form-group"><label>Specialty</label><input type="text" name="specialty" value="<?php echo htmlspecialchars($trainer['specialty']); ?>" required class="form-control"></div>
            <div class="form-group"><label>Bio</label><textarea name="bio" required class="form-control"><?php echo htmlspecialchars($trainer['bio']); ?></textarea></div>
            <div class="form-group"><label>Certifications</label><input type="text" name="certifications" value="<?php echo htmlspecialchars($trainer['certifications']); ?>" class="form-control"></div>
            <div class="form-group"><label>Price Per Hour</label><input type="number" step="0.01" name="price_per_hour" value="<?php echo htmlspecialchars($trainer['price_per_hour']); ?>" class="form-control"></div>
            <div class="form-group">
                <label>Current Image</label><br>
                <?php 
                $image_path = (!empty($trainer['image_path']) && file_exists('../assets/img/' . $trainer['image_path'])) 
                              ? '../assets/img/' . $trainer['image_path'] 
                              : 'https://placehold.co/150x150/112240/00c7b3?text=No+Image';
                ?>
                <img src="<?php echo $image_path; ?>" alt="Current Image" class="trainer-img" style="width: 100px; height: 100px;">
            </div>
            <div class="form-group">
                <label>Upload New Image (optional)</label>
                <input type="file" name="trainer_image" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Update Trainer</button>
            <a href="manage_trainers.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include 'admin_footer.php'; ?>