<?php
include '../includes/db.php';
include 'admin_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: manage_classes.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $schedule = $conn->real_escape_string($_POST['schedule']);
    $current_image = $_POST['current_image'];
    $new_image_path = $current_image;

    if (isset($_FILES['class_image']) && $_FILES['class_image']['error'] == 0) {
        
        $upload_dir = '../assets/img/';
        $file_name = time() . '_' . basename($_FILES['class_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['class_image']['tmp_name'], $target_file)) {
            
            if(!empty($current_image) && file_exists($upload_dir . $current_image)){
                unlink($upload_dir . $current_image);
            }
            $new_image_path = $file_name;
        }
    }
    
    $sql = "UPDATE classes SET name = ?, description = ?, schedule = ?, image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $description, $schedule, $new_image_path, $id);
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Class updated successfully.'];
    header("Location: manage_classes.php");
    exit();
}

$result = $conn->query("SELECT * FROM classes WHERE id = $id");
$class = $result->fetch_assoc();

if (!$class) {
    header("Location: manage_classes.php");
    exit();
}
?>
<h2>Edit Class</h2>
<div class="card">
    <div class="card-body">
        <form method="POST" action="edit_class.php?id=<?php echo $id; ?>" class="needs-validation" novalidate enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($class['image_path']); ?>">
            <div class="form-group">
                <label>Class Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($class['name']); ?>" required class="form-control">
                <div class="invalid-feedback">Please enter a class name.</div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required class="form-control"><?php echo htmlspecialchars($class['description']); ?></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>
            <div class="form-group">
                <label>Schedule</label>
                <input type="text" name="schedule" value="<?php echo htmlspecialchars($class['schedule']); ?>" required class="form-control">
                <div class="invalid-feedback">Please enter a schedule.</div>
            </div>
            <div class="form-group">
                <label>Current Image</label><br>
                <?php 
                
                $image_path = (!empty($class['image_path']) && file_exists('../assets/img/' . $class['image_path'])) 
                              ? '../assets/img/' . $class['image_path'] 
                              : 'https://placehold.co/150x100/112240/00c7b3?text=No+Image';
                ?>
                <img src="<?php echo $image_path; ?>" alt="Current Image" style="max-width: 150px; border-radius: 5px;">
            </div>
            <div class="form-group">
                <label>Upload New Image (optional)</label>
                <input type="file" name="class_image" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Update Class</button>
            <a href="manage_classes.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include 'admin_footer.php'; ?>