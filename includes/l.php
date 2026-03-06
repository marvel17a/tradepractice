<?php 
require_once 'includes/config.php';
require_once 'includes/header.php'; 
?>

<div class="row align-items-center min-vh-75 py-5">
    <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
        <div class="badge bg-dark border border-secondary rounded-pill px-3 py-2 mb-4 text-primary">
            <i class="fa-solid fa-bolt text-warning me-2"></i> Next-Gen Algorithmic Testing
        </div>
        <h1 class="display-3 fw-bold lh-1 mb-4">Master the markets. <br><span style="color: var(--fintech-primary);">Zero risk.</span></h1>
        <p class="lead text-muted mb-5" style="font-size: 1.2rem;">
            TradePractice is an elite backtesting engine. Build custom strategies, test them against historical market data, and analyze your win rate before risking real capital.
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3">
            <a href="register.php" class="btn btn-primary btn-lg px-5 py-3">Start Building Free</a>
            <a href="login.php" class="btn btn-outline-light btn-lg px-5 py-3">Sign In</a>
        </div>
        <div class="mt-5 text-muted small d-flex gap-4">
            <span><i class="fa-solid fa-check text-success me-1"></i> Live Market Data</span>
            <span><i class="fa-solid fa-check text-success me-1"></i> Advanced Analytics</span>
            <span><i class="fa-solid fa-check text-success me-1"></i> 100% Free</span>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="fintech-card p-4 position-relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="position-absolute top-0 end-0 translate-middle" style="width: 300px; height: 300px; background: rgba(0, 242, 254, 0.15); filter: blur(100px); border-radius: 50%;"></div>
            
            <div class="d-flex justify-content-between align-items-center mb-4 relative z-index-1">
                <h5 class="mb-0 fw-bold">Strategy Performance</h5>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">+24.5% Return</span>
            </div>
            
            <!-- Abstract Chart Representation -->
            <div class="mt-4 border-bottom border-secondary border-opacity-25 pb-3">
                <div class="d-flex align-items-end justify-content-between h-50 gap-2" style="height: 150px !important;">
                    <div class="bg-secondary bg-opacity-25 w-100 rounded-top" style="height: 30%"></div>
                    <div class="bg-secondary bg-opacity-25 w-100 rounded-top" style="height: 50%"></div>
                    <div class="bg-primary w-100 rounded-top shadow-lg" style="height: 70%; background: linear-gradient(180deg, #00f2fe 0%, transparent 100%); border-top: 2px solid #00f2fe;"></div>
                    <div class="bg-success w-100 rounded-top shadow-lg" style="height: 90%; background: linear-gradient(180deg, #10b981 0%, transparent 100%); border-top: 2px solid #10b981;"></div>
                    <div class="bg-secondary bg-opacity-25 w-100 rounded-top" style="height: 60%"></div>
                </div>
            </div>
            
            <div class="row mt-4 text-center">
                <div class="col-4">
                    <div class="text-muted small">Win Rate</div>
                    <div class="fw-bold fs-5 text-light">68.2%</div>
                </div>
                <div class="col-4">
                    <div class="text-muted small">Total Trades</div>
                    <div class="fw-bold fs-5 text-light">142</div>
                </div>
                <div class="col-4">
                    <div class="text-muted small">Max Drawdown</div>
                    <div class="fw-bold fs-5 text-danger">-4.1%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>