<?php
include 'includes/db.php';
include 'includes/header.php';

$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO queries (user_id, name, email, message) VALUES ('$user_id', '$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Thank you for your message! We will get back to you shortly. You can view our reply in your dashboard.'];
        header("Location: contact.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #00c7b3;">Contact Us</h1>
    <p class="text-center mb-5">Have a question or want to learn more? Send us a message or visit us in person!</p>

    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <h3>Send a Query</h3>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form id="inquiryForm" action="contact.php" method="POST" class="needs-validation" novalidate data-logged-in="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" <?php echo isset($_SESSION['user_id']) ? 'readonly' : ''; ?> required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>" <?php echo isset($_SESSION['user_id']) ? 'readonly' : ''; ?> required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    <div class="invalid-feedback">Please enter your message.</div>
                </div>
                <button type="submit" id="inquirySubmitBtn" class="btn btn-primary">Submit Query</button>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <small class="form-text text-muted mt-2">You must be logged in to submit the form.</small>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-6">
            <h3>Our Location</h3>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.84210484503!2d80.3236024410522!3d7.477110000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae3398a7251500f%3A0x23234522f6fbd43e!2sKurunegala!5e0!3m2!1sen!2slk!4v1723882013329!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="mt-3">
                <p><strong>Address:</strong> 123 Fitness Avenue, Kurunegala, Sri Lanka</p>
                <p><strong>Phone:</strong> +94 11 234 5678</p>
                <p><strong>Email:</strong> info@fitzone.com</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>