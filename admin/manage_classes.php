<?php
include '../includes/db.php';
include 'admin_header.php';

// Add Class
if(isset($_POST['add_class'])){
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $schedule = $conn->real_escape_string($_POST['schedule']);
    $image_path = null;

    if (isset($_FILES['class_image']) && $_FILES['class_image']['error'] == 0) {
        
        $upload_dir = '../assets/img/';
        $file_name = time() . '_' . basename($_FILES['class_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['class_image']['tmp_name'], $target_file)) {
            $image_path = $file_name;
        }
    }

    $sql = "INSERT INTO classes (name, description, schedule, image_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $description, $schedule, $image_path);
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Class added successfully.'];
    header("Location: manage_classes.php");
    exit();
}

// Delete Class
if(isset($_GET['delete_class'])){
    $id = (int)$_GET['delete_class'];
    
    $res = $conn->query("SELECT image_path FROM classes WHERE id=$id");
    if($row = $res->fetch_assoc()){
        
        if(!empty($row['image_path']) && file_exists('../assets/img/' . $row['image_path'])){
            unlink('../assets/img/' . $row['image_path']);
        }
    }

    $sql = "DELETE FROM classes WHERE id=$id";
    $conn->query($sql);
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Class deleted successfully.'];
    header("Location: manage_classes.php");
    exit();
}

$classes = $conn->query("SELECT * FROM classes ORDER BY id DESC");
?>
<h2>Manage Classes</h2>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Add New Class</h5>
        <form method="POST" action="manage_classes.php" class="needs-validation" novalidate enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="name" placeholder="Class Name" required class="form-control">
                <div class="invalid-feedback">Please enter a class name.</div>
            </div>
            <div class="form-group">
                <textarea name="description" placeholder="Description" required class="form-control"></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>
            <div class="form-group">
                <input type="text" name="schedule" placeholder="Schedule (e.g., Mon, Wed, Fri at 6 PM)" required class="form-control">
                <div class="invalid-feedback">Please enter a schedule.</div>
            </div>
            <div class="form-group">
                <label>Class Image (600x400 recommended)</label>
                <input type="file" name="class_image" class="form-control-file">
            </div>
            <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
        </form>
    </div>
</div>

<div class="row">
    <?php while($class = $classes->fetch_assoc()): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 class-card">
            <?php 
            
            $image_path = (!empty($class['image_path']) && file_exists('../assets/img/' . $class['image_path'])) 
                          ? '../assets/img/' . $class['image_path'] 
                          : 'https://placehold.co/600x400/112240/00c7b3?text=' . urlencode(htmlspecialchars($class['name']));
            ?>
            <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($class['name']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($class['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($class['description']); ?></p>
                <div class="class-schedule">
                    <i class="far fa-clock"></i> <?php echo htmlspecialchars($class['schedule']); ?>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                <a href="?delete_class=<?php echo $class['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include 'admin_footer.php'; ?>