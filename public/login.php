<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Profit Safar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }
        .login-card {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card login-card shadow p-4" style="width: 380px;">
        <h3 class="text-center fw-bold mb-3">Login</h3>
        <p class="text-center text-muted mb-4">Access your Profit Safar dashboard</p>

        <form>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" placeholder="Enter email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Enter password">
            </div>

            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>

        <p class="text-center mt-3 small">
            Don’t have an account?
            <a href="register.php">Register</a>
        </p>
    </div>
</div>

</body>
</html>

