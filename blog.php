<?php 
include 'includes/db.php';
include 'includes/header.php'; 

$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT blog_posts.*, users.username FROM blog_posts JOIN users ON blog_posts.author_id = users.id";
if (!empty($search_term)) {
    $sql .= " WHERE blog_posts.title LIKE '%$search_term%' OR blog_posts.content LIKE '%$search_term%'";
}
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="container my-5">
    <h1 class="text-center mb-4">FitZone Blog: Your Guide to Wellness</h1>
    <p class="text-center mb-5">Discover workout routines, healthy recipes, meal plans, and inspiring success stories.</p>

    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <form action="blog.php" method="GET" class="form-inline">
                <input class="form-control mr-sm-2 flex-grow-1" type="search" name="search" placeholder="Search blog posts..." aria-label="Search" value="<?php echo htmlspecialchars($search_term); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
    </div>
    
    <div class="row">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($post = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <?php 
                        $image_path = (!empty($post['image_path']) && file_exists('assets/img/' . $post['image_path'])) 
                                      ? 'assets/img/' . $post['image_path'] 
                                      : 'https://placehold.co/600x400/112240/00c7b3?text=' . urlencode(htmlspecialchars($post['category']));
                        ?>
                        <img src="<?php echo $image_path; ?>" class="blog-card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <p class="card-text"><small class="text-muted" style="color: #8892b0 !important;">By <?php echo htmlspecialchars($post['username']); ?> | <?php echo date('F j, Y', strtotime($post['created_at'])); ?> | Category: <?php echo htmlspecialchars($post['category']); ?></small></p>
                            <p class="card-text flex-grow-1"><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 100))); ?>...</p>
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary mt-auto">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col text-center">
                <p>No blog posts found. Try a different search term or check back later!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>