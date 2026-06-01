<?php
include '../includes/db.php';
include 'admin_header.php';

// Add Post
if(isset($_POST['add_post'])){
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $author_id = $_SESSION['user_id'];
    $image_path = null;

    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
        $upload_dir = '../assets/img/';
        $file_name = time() . '_blog_' . basename($_FILES['post_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $target_file)) {
            $image_path = $file_name;
        }
    }
    
    $sql = "INSERT INTO blog_posts (title, content, author_id, category, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $title, $content, $author_id, $category, $image_path);
    $stmt->execute();
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Blog post added successfully.'];
    header("Location: manage_blog.php");
    exit();
}

// Delete Post
if(isset($_GET['delete_post'])){
    $id = (int)$_GET['delete_post'];
    
    $res = $conn->query("SELECT image_path FROM blog_posts WHERE id=$id");
    if($row = $res->fetch_assoc()){
        if(!empty($row['image_path']) && file_exists('../assets/img/' . $row['image_path'])){
            unlink('../assets/img/' . $row['image_path']);
        }
    }

    $conn->query("DELETE FROM blog_posts WHERE id=$id");
    $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Blog post deleted successfully.'];
    header("Location: manage_blog.php");
    exit();
}

$posts = $conn->query("SELECT blog_posts.*, users.username FROM blog_posts JOIN users ON blog_posts.author_id = users.id ORDER BY created_at DESC");
?>
<h2>Manage Blog Posts</h2>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Add New Post</h5>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><input type="text" name="title" placeholder="Post Title" required class="form-control"></div>
            <div class="form-group"><input type="text" name="category" placeholder="Category (e.g., Workout)" required class="form-control"></div>
            <div class="form-group">
                <textarea name="content" placeholder="Post Content" class="form-control" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label>Post Image (800x400 recommended)</label>
                <input type="file" name="post_image" class="form-control-file">
            </div>
            <button type="submit" name="add_post" class="btn btn-primary">Add Post</button>
        </form>
    </div>
</div>

<div class="row">
    <?php while($post = $posts->fetch_assoc()): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <?php 
            $image_path = (!empty($post['image_path']) && file_exists('../assets/img/' . $post['image_path'])) 
                          ? '../assets/img/' . $post['image_path'] 
                          : 'https://placehold.co/600x400/112240/00c7b3?text=' . urlencode(htmlspecialchars($post['category']));
            ?>
            <img src="<?php echo $image_path; ?>" class="blog-card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                <p><small class="text-muted">By <?php echo htmlspecialchars($post['username']); ?> | Category: <?php echo htmlspecialchars($post['category']); ?></small></p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="edit_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                <a href="?delete_post=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include 'admin_footer.php'; ?>