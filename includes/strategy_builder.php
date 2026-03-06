<?php
require_once 'includes/config.php';

// Protect the page: Only logged-in users can build strategies
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $strategy_name = trim($_POST['strategy_name']);
    $buy_rule = trim($_POST['buy_rule']);
    $sell_rule = trim($_POST['sell_rule']);
    $stop_loss = floatval($_POST['stop_loss']);
    $target_price = floatval($_POST['target_price']);

    // Basic Validation
    if (empty($strategy_name) || empty($buy_rule) || empty($sell_rule) || $stop_loss <= 0 || $target_price <= 0) {
        $error = "Please fill out all fields with valid numbers for Stop Loss and Target.";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO strategies (user_id, strategy_name, buy_rule, sell_rule, stop_loss, target_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdd", $user_id, $strategy_name, $buy_rule, $sell_rule, $stop_loss, $target_price);
        
        if ($stmt->execute()) {
            $success = "Strategy successfully saved! You can now run simulations on it.";
        } else {
            $error = "Error saving strategy. Please try again.";
        }
        $stmt->close();
    }
}

require_once 'includes/header.php'; 
?>

<div class="row mb-4 mt-3">
    <div class="col-12">
        <h2 class="fw-bold mb-1">Strategy Builder <i class="fa-solid fa-code text-primary ms-2"></i></h2>
        <p class="text-muted">Define your entry and exit conditions to test against historical data.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card p-4 shadow-lg border-0" style="background-color: var(--fintech-card);">
            
            <?php if($error): ?>
                <div class="alert alert-danger border-danger bg-danger bg-opacity-10 text-danger rounded-3 py-2">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success border-success bg-success bg-opacity-10 text-success rounded-3 py-3">
                    <i class="fa-solid fa-circle-check fs-5 me-2 align-middle"></i>
                    <?php echo $success; ?>
                    <div class="mt-2">
                        <a href="dashboard.php" class="btn btn-sm btn-outline-success">View in Dashboard</a>
                        <a href="backtest.php" class="btn btn-sm btn-success ms-2">Run Backtest</a>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Strategy Name -->
                <div class="mb-4">
                    <label class="form-label text-light fw-bold">Strategy Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-primary"><i class="fa-solid fa-tag"></i></span>
                        <input type="text" name="strategy_name" class="form-control border-start-0 ps-0" placeholder="e.g., Golden Cross Moving Average" required>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <!-- Buy Rule -->
                    <div class="col-md-6">
                        <label class="form-label text-success fw-bold"><i class="fa-solid fa-arrow-trend-up me-1"></i> Entry (Buy) Rule</label>
                        <textarea name="buy_rule" class="form-control text-light" rows="4" placeholder="e.g., Price > SMA(50) AND RSI < 30" style="background-color: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2);" required></textarea>
                        <div class="form-text text-muted small mt-1">Define when the bot should enter a trade.</div>
                    </div>
                    
                    <!-- Sell Rule -->
                    <div class="col-md-6">
                        <label class="form-label text-danger fw-bold"><i class="fa-solid fa-arrow-trend-down me-1"></i> Exit (Sell) Rule</label>
                        <textarea name="sell_rule" class="form-control text-light" rows="4" placeholder="e.g., Price < SMA(50) OR RSI > 70" style="background-color: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.2);" required></textarea>
                        <div class="form-text text-muted small mt-1">Define when the bot should exit a trade.</div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <!-- Stop Loss -->
                    <div class="col-md-6">
                        <label class="form-label text-light fw-bold">Stop Loss (%)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-danger"><i class="fa-solid fa-shield-halved"></i></span>
                            <input type="number" step="0.01" name="stop_loss" class="form-control border-start-0 ps-0" placeholder="2.50" required>
                            <span class="input-group-text bg-transparent border-start-0 text-muted">%</span>
                        </div>
                    </div>
                    
                    <!-- Target Price / Take Profit -->
                    <div class="col-md-6">
                        <label class="form-label text-light fw-bold">Target Profit (%)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-success"><i class="fa-solid fa-bullseye"></i></span>
                            <input type="number" step="0.01" name="target_price" class="form-control border-start-0 ps-0" placeholder="5.00" required>
                            <span class="input-group-text bg-transparent border-start-0 text-muted">%</span>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save Strategy
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructions / Guide Sidebar -->
    <div class="col-lg-4">
        <div class="card p-4 border-0 h-100" style="background: linear-gradient(145deg, var(--fintech-card), rgba(0, 242, 254, 0.05)); border-left: 3px solid var(--fintech-primary) !important;">
            <h5 class="fw-bold text-light mb-3"><i class="fa-solid fa-lightbulb text-warning me-2"></i> How to write rules</h5>
            <p class="text-muted small mb-4">TradePractice uses a simple syntax logic. Write your conditions clearly so the backtesting engine can parse them.</p>
            
            <div class="mb-3">
                <h6 class="text-primary text-uppercase small fw-bold mb-2">Supported Indicators</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-1"><code class="bg-dark px-2 py-1 rounded text-light border border-secondary">Price</code> - Current Close Price</li>
                    <li class="mb-1"><code class="bg-dark px-2 py-1 rounded text-light border border-secondary">SMA(X)</code> - Simple Moving Average</li>
                    <li class="mb-1"><code class="bg-dark px-2 py-1 rounded text-light border border-secondary">RSI</code> - Relative Strength Index</li>
                    <li class="mb-1"><code class="bg-dark px-2 py-1 rounded text-light border border-secondary">Volume</code> - Daily Trading Volume</li>
                </ul>
            </div>

            <div class="mb-4">
                <h6 class="text-primary text-uppercase small fw-bold mb-2">Operators</h6>
                <div class="d-flex flex-wrap gap-2 text-muted small">
                    <span class="badge bg-dark border border-secondary">> (Greater)</span>
                    <span class="badge bg-dark border border-secondary">< (Less)</span>
                    <span class="badge bg-dark border border-secondary">== (Equals)</span>
                    <span class="badge bg-dark border border-secondary">AND</span>
                    <span class="badge bg-dark border border-secondary">OR</span>
                </div>
            </div>

            <div class="bg-dark bg-opacity-50 p-3 rounded-3 border border-secondary border-opacity-50 mt-auto">
                <p class="text-light small fw-bold mb-1">Example Strategy:</p>
                <div class="text-muted" style="font-size: 0.8rem; font-family: monospace;">
                    <span class="text-success">Buy:</span> Price > SMA(200) AND RSI < 30<br>
                    <span class="text-danger">Sell:</span> Price < SMA(200) OR RSI > 70
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>