<?php
include '../includes/db.php';
include 'admin_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: manage_blog.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $current_image = $_POST['current_image'];
    $new_image_path = $current_image;

    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
        $upload_dir = '../assets/img/';
        $file_name = time() . '_blog_' . basename($_FILES['post_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $target_file)) {
            if(!empty($current_image) && file_exists($upload_dir . $current_image)){
                unlink($upload_dir . $current_image);
            }
            $new_image_path = $file_name;
        }
    }
    
    $sql = "UPDATE blog_posts SET title = ?, content = ?, category = ?, image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $content, $category, $new_image_path, $id);
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Blog post updated successfully.'];
    header("Location: manage_blog.php");
    exit();
}

$result = $conn->query("SELECT * FROM blog_posts WHERE id = $id");
$post = $result->fetch_assoc();

if (!$post) {
    header("Location: manage_blog.php");
    exit();
}
?>
<h2>Edit Blog Post</h2>
<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($post['image_path']); ?>">
            <div class="form-group"><label>Title</label><input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required class="form-control"></div>
            <div class="form-group"><label>Category</label><input type="text" name="category" value="<?php echo htmlspecialchars($post['category']); ?>" required class="form-control"></div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="10"><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Current Image</label><br>
                <?php 
                $image_path = (!empty($post['image_path']) && file_exists('../assets/img/' . $post['image_path'])) 
                              ? '../assets/img/' . $post['image_path'] 
                              : 'https://placehold.co/150x100/112240/00c7b3?text=No+Image';
                ?>
                <img src="<?php echo $image_path; ?>" alt="Current Image" style="max-width: 150px; border-radius: 5px;">
            </div>
            <div class="form-group">
                <label>Upload New Image (optional)</label>
                <input type="file" name="post_image" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="manage_blog.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include 'admin_footer.php'; ?>