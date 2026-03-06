<?php
require_once 'includes/config.php';

// Protect the page
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$strategy_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Verify strategy belongs to user
$stmt = $conn->prepare("SELECT * FROM strategies WHERE strategy_id = ? AND user_id = ?");
$stmt->bind_param("ii", $strategy_id, $user_id);
$stmt->execute();
$strategy = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$strategy) { header("Location: dashboard.php"); exit(); }

// Fetch Results
$stmt = $conn->prepare("SELECT * FROM backtest_results WHERE strategy_id = ?");
$stmt->bind_param("i", $strategy_id);
$stmt->execute();
$results = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch Trade Logs for Chart & Table
$trades = [];
$chart_labels = [];
$chart_equity = [];
$equity_counter = 10000; // Starting hypothetical capital

$stmt = $conn->prepare("SELECT * FROM trade_logs WHERE strategy_id = ? ORDER BY exit_date ASC");
$stmt->bind_param("i", $strategy_id);
$stmt->execute();
$res = $stmt->get_result();

$chart_labels[] = 'Start';
$chart_equity[] = $equity_counter;

while ($row = $res->fetch_assoc()) {
    $trades[] = $row;
    $equity_counter += $row['profit_loss'];
    $chart_labels[] = date('M d', strtotime($row['exit_date']));
    $chart_equity[] = $equity_counter;
}
$stmt->close();

require_once 'includes/header.php'; 
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <a href="dashboard.php" class="text-muted text-decoration-none small"><i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard</a>
        <h2 class="fw-bold mb-1 mt-2">Simulation Results</h2>
        <p class="text-muted">Analysis for strategy: <strong class="text-primary"><?php echo htmlspecialchars($strategy['strategy_name']); ?></strong></p>
    </div>
    <div>
        <a href="backtest.php" class="btn btn-outline-light"><i class="fa-solid fa-rotate-right me-2"></i> Rerun Test</a>
    </div>
</div>

<?php if (!$results): ?>
    <div class="alert alert-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i> No simulation data found. Please run a backtest first.</div>
<?php else: ?>

    <!-- KPI Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3 border-0 shadow-sm">
                <div class="text-muted small fw-bold text-uppercase mb-2">Net Profit</div>
                <h3 class="fw-bold mb-0 <?php echo ($results['total_profit'] >= 0) ? 'text-success' : 'text-danger'; ?>">
                    $<?php echo number_format($results['total_profit'], 2); ?>
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3 border-0 shadow-sm">
                <div class="text-muted small fw-bold text-uppercase mb-2">Win Rate</div>
                <h3 class="fw-bold mb-0 text-light"><?php echo number_format($results['win_rate'], 2); ?>%</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3 border-0 shadow-sm">
                <div class="text-muted small fw-bold text-uppercase mb-2">Total Trades</div>
                <h3 class="fw-bold mb-0 text-light"><?php echo $results['total_trades']; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 p-3 border-0 shadow-sm">
                <div class="text-muted small fw-bold text-uppercase mb-2">Max Drawdown</div>
                <h3 class="fw-bold mb-0 text-danger"><?php echo number_format($results['max_drawdown'], 2); ?>%</h3>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background-color: var(--fintech-card);">
                <h5 class="fw-bold mb-4">Equity Curve</h5>
                <canvas id="equityChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Trade History Table -->
    <h5 class="fw-bold mb-3">Trade History Log</h5>
    <div class="card p-0 overflow-hidden border-0 shadow-sm mb-5">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle border-0">
                <thead class="text-muted" style="background-color: rgba(0,0,0,0.2);">
                    <tr>
                        <th class="ps-4 py-3">Asset</th>
                        <th class="py-3">Entry Date</th>
                        <th class="py-3">Exit Date</th>
                        <th class="py-3">Entry Price</th>
                        <th class="py-3">Exit Price</th>
                        <th class="pe-4 py-3 text-end">P&L</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($trades)): ?>
                        <tr><td colspan="6" class="text-center py-4">No trades executed during this period. Try adjusting your rules.</td></tr>
                    <?php else: ?>
                        <?php foreach ($trades as $t): ?>
                            <tr>
                                <td class="ps-4 py-3 fw-bold text-light"><?php echo htmlspecialchars($t['symbol']); ?></td>
                                <td class="py-3 text-muted small"><?php echo date('Y-m-d', strtotime($t['entry_date'])); ?></td>
                                <td class="py-3 text-muted small"><?php echo date('Y-m-d', strtotime($t['exit_date'])); ?></td>
                                <td class="py-3 text-light">$<?php echo number_format($t['entry_price'], 2); ?></td>
                                <td class="py-3 text-light">$<?php echo number_format($t['exit_price'], 2); ?></td>
                                <td class="pe-4 py-3 text-end fw-bold <?php echo ($t['profit_loss'] >= 0) ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo ($t['profit_loss'] >= 0 ? '+' : '') . '$' . number_format($t['profit_loss'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script to render the Chart -->
    <script>
        const ctx = document.getElementById('equityChart').getContext('2d');
        
        // Setup Gradient Fill
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 242, 254, 0.5)');   
        gradient.addColorStop(1, 'rgba(0, 242, 254, 0.0)');

        const equityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Portfolio Value ($)',
                    data: <?php echo json_encode($chart_equity); ?>,
                    borderColor: '#00f2fe',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointRadius: <?php echo count($chart_labels) > 50 ? 0 : 3; ?>, // Hide dots if too many trades
                    pointBackgroundColor: '#fff',
                    fill: true,
                    tension: 0.3 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { 
                            color: '#94a3b8',
                            callback: function(value) { return '$' + value; }
                        }
                    }
                }
            }
        });
    </script>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>