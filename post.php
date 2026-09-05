<?php 
include 'includes/db.php';
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: blog.php");
    exit();
}

$result = $conn->query("SELECT blog_posts.*, users.username FROM blog_posts JOIN users ON blog_posts.author_id = users.id WHERE blog_posts.id = $id");
$post = $result->fetch_assoc();

if (!$post) {
    header("Location: blog.php");
    exit();
}
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <p class="text-muted" style="color: #8892b0 !important;">
                By <?php echo htmlspecialchars($post['username']); ?> | <?php echo date('F j, Y', strtotime($post['created_at'])); ?> | Category: <?php echo htmlspecialchars($post['category']); ?>
            </p>
            <hr style="border-color: #233554;">
            
            <?php 
            $image_path = (!empty($post['image_path']) && file_exists('assets/img/' . $post['image_path'])) 
                          ? 'assets/img/' . $post['image_path'] 
                          : 'https://placehold.co/800x400/112240/00c7b3?text=Blog+Post';
            ?>
            <img src="<?php echo $image_path; ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($post['title']); ?>">

            <div class="mt-4 blog-content">
                <?php
                $safe_content = htmlspecialchars($post['content']);
                // Handle both literal escaped string \r\n from DB and actual newlines
                $final_content = str_replace(['\r\n\r\n', '\r\n', '\n', '\r'], '<br>', $safe_content);
                echo nl2br($final_content);
                ?>
            </div>
            <a href="blog.php" class="btn btn-primary mt-5"><i class="fas fa-arrow-left"></i> Back to Blog</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
