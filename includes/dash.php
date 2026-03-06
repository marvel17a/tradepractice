<?php
require_once 'includes/config.php';

// Protect the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// In the future, we will fetch real stats from the database here.
// For now, we set placeholder values to design the UI.
$total_strategies = 0; 
$total_trades = 0;
$avg_win_rate = "0.00";
$net_profit = "0.00";

require_once 'includes/header.php'; 
?>

<!-- Welcome Banner -->
<div class="d-flex justify-content-between align-items-center mb-5 mt-3">
    <div>
        <h2 class="fw-bold mb-1">Welcome back, <?php echo htmlspecialchars($user_name); ?></h2>
        <p class="text-muted">Here is an overview of your trading strategies.</p>
    </div>
    <div>
        <a href="strategy_builder.php" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i> New Strategy
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon primary me-3">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Strategies</div>
                    <h3 class="fw-bold mb-0"><?php echo $total_strategies; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon warning me-3">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Total Trades</div>
                    <h3 class="fw-bold mb-0"><?php echo $total_trades; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon success me-3">
                    <i class="fa-solid fa-crosshairs"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Avg Win Rate</div>
                    <h3 class="fw-bold mb-0"><?php echo $avg_win_rate; ?>%</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon danger me-3"> <!-- Change to success class when profit is > 0 in backend -->
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Net P&L</div>
                    <h3 class="fw-bold mb-0">$<?php echo $net_profit; ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Strategies Section -->
<h4 class="fw-bold mb-4">Your Strategies</h4>
<div class="card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="text-muted" style="background-color: rgba(0,0,0,0.2);">
                <tr>
                    <th class="ps-4 py-3">Strategy Name</th>
                    <th class="py-3">Created Date</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Win Rate</th>
                    <th class="pe-4 py-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder for empty state -->
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fa-solid fa-chart-pie fa-3x opacity-50"></i>
                        </div>
                        <h5 class="fw-bold">No strategies found</h5>
                        <p class="text-muted small">You haven't built any trading strategies yet.</p>
                        <a href="strategy_builder.php" class="btn btn-outline-light btn-sm mt-2">Build Your First Strategy</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>