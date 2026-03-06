<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($insert_stmt->execute()) {
                $success = "Account created! You can now <a href='login.php' class='text-primary fw-bold'>Sign In</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
            $insert_stmt->close();
        }
        $stmt->close();
    }
}
require_once 'includes/header.php'; 
?>

<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Join TradePractice</h3>
                <p class="text-muted small">Start testing your strategies today</p>
            </div>
            
            <?php if($error): ?>
                <div class="alert alert-danger border-danger bg-danger bg-opacity-10 text-danger rounded-3 text-center py-2"><small><i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo $error; ?></small></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success border-success bg-success bg-opacity-10 text-success rounded-3 text-center py-3"><i class="fa-solid fa-circle-check fs-4 mb-2"></i><br><?php echo $success; ?></div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="John Doe" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="name@example.com" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Create a strong password" required minlength="6">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Create Account</button>
                </form>
            <?php endif; ?>
            <div class="text-center">
                <p class="text-muted small mb-0">Already have an account? <a href="login.php" class="text-primary text-decoration-none fw-bold">Sign In</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>