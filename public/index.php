<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit Safar | Trading Strategy Analysis</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero {
            background: linear-gradient(120deg, #0b1c2d, #123456);
            color: #fff;
            padding: 100px 0;
        }
        .hero h1 span {
            color: #1abc9c;
        }
        .feature-card {
            border-radius: 12px;
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-8px);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Profit Safar</a>
        <div class="ms-auto">
            <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero text-center">
    <div class="container">
        <h1 class="fw-bold display-5">
            Understand Trading Strategies <br>
            <span>Before You Trade</span>
        </h1>
        <p class="mt-3 fs-5">
            AI-assisted platform for backtesting, simulation, and strategy explanation.
        </p>
        <div class="mt-4">
            <a href="register.php" class="btn btn-success btn-lg me-2">Get Started</a>
            <a href="login.php" class="btn btn-outline-light btn-lg">Login</a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-4">
            <h2 class="fw-bold">Why Profit Safar?</h2>
            <p class="text-muted">Designed for learning, analysis, and decision clarity</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card feature-card p-3 text-center shadow-sm">
                    <h5 class="fw-semibold">Backtesting</h5>
                    <p class="text-muted small">Test strategies on historical data</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card feature-card p-3 text-center shadow-sm">
                    <h5 class="fw-semibold">Live Simulation</h5>
                    <p class="text-muted small">Evaluate strategy on live market price</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card feature-card p-3 text-center shadow-sm">
                    <h5 class="fw-semibold">AI Reasoning</h5>
                    <p class="text-muted small">Understand why decisions happen</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card feature-card p-3 text-center shadow-sm">
                    <h5 class="fw-semibold">Psychology Analysis</h5>
                    <p class="text-muted small">Detect emotional trading behavior</p>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>

