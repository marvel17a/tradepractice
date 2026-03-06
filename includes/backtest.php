<?php
require_once 'includes/config.php';

// Protect the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's saved strategies for the dropdown
$strategies = [];
$stmt = $conn->prepare("SELECT strategy_id, strategy_name FROM strategies WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $strategies[] = $row;
}
$stmt->close();

require_once 'includes/header.php'; 
?>

<div class="row mb-4 mt-3">
    <div class="col-12">
        <h2 class="fw-bold mb-1">Run Simulation <i class="fa-solid fa-play text-primary ms-2"></i></h2>
        <p class="text-muted">Test your custom strategies against historical market data.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg border-0" style="background-color: var(--fintech-card);">
            
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-server fs-1 text-primary"></i>
                </div>
                <h4 class="fw-bold">Backtesting Engine</h4>
                <p class="text-muted small">Select a strategy to begin historical simulation</p>
            </div>

            <form method="POST" action="run_simulation.php">
                <div class="mb-4">
                    <label class="form-label text-light fw-bold">Select Strategy</label>
                    <select name="strategy_id" class="form-select form-control text-light border-secondary" required style="background-color: rgba(0,0,0,0.3);">
                        <option value="" disabled selected>-- Choose a saved strategy --</option>
                        <?php foreach ($strategies as $strategy): ?>
                            <option value="<?php echo $strategy['strategy_id']; ?>">
                                <?php echo htmlspecialchars($strategy['strategy_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($strategies)): ?>
                        <div class="form-text text-danger mt-2"><i class="fa-solid fa-circle-exclamation me-1"></i> You haven't built any strategies yet. <a href="strategy_builder.php" class="text-primary">Build one now</a>.</div>
                    <?php endif; ?>
                </div>

                <div class="mb-5">
                    <label class="form-label text-light fw-bold">Select Asset to Test</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-building"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 text-muted" value="AAPL (Apple Inc.)" readonly style="background-color: rgba(0,0,0,0.1);">
                    </div>
                    <div class="form-text text-muted small mt-1">Currently restricted to imported AAPL data for demo purposes.</div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold position-relative overflow-hidden" <?php echo empty($strategies) ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-rocket me-2"></i> Launch Backtest
                </button>
            </form>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>