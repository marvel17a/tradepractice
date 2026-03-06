<?php
require_once 'includes/config.php';

// Protect the page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['strategy_id'])) {
    $strategy_id = intval($_POST['strategy_id']);
    $user_id = $_SESSION['user_id'];

    // 1. Verify strategy belongs to user and fetch details
    $stmt = $conn->prepare("SELECT * FROM strategies WHERE strategy_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $strategy_id, $user_id);
    $stmt->execute();
    $strategy = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$strategy) {
        die("Invalid strategy.");
    }

    $stop_loss_pct = $strategy['stop_loss'] / 100;
    $target_pct = $strategy['target_price'] / 100;

    // 2. Clear old results for this strategy (so we can re-test cleanly)
    $conn->query("DELETE FROM backtest_results WHERE strategy_id = $strategy_id");
    $conn->query("DELETE FROM trade_logs WHERE strategy_id = $strategy_id");

    // 3. Fetch Market Data (Using AAPL as our demo asset)
    // We order by date ASC so we step through time from past to present
    $market_data = [];
    $res = $conn->query("SELECT * FROM market_data WHERE symbol = 'AAPL' ORDER BY date ASC");
    while ($row = $res->fetch_assoc()) {
        $market_data[] = $row;
    }

    if (count($market_data) < 50) {
        die("Not enough market data to run simulation. Please go to fetch_data.php first.");
    }

    // 4. THE SIMULATION ENGINE
    $in_trade = false;
    $entry_price = 0;
    $entry_date = '';
    
    $total_trades = 0;
    $winning_trades = 0;
    $total_profit = 0;
    
    $equity = 10000; // Start with a hypothetical $10,000 portfolio
    $peak_equity = 10000;
    $max_drawdown = 0;
    
    // Arrays to store history for indicators like SMA (Simple Moving Average)
    $close_prices = [];

    // Prepared statement for inserting trade logs
    $log_stmt = $conn->prepare("INSERT INTO trade_logs (strategy_id, symbol, entry_date, exit_date, entry_price, exit_price, profit_loss) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($market_data as $day) {
        $current_price = $day['close_price'];
        $current_date = $day['date'];
        $close_prices[] = $current_price;
        
        // Keep only last 50 days in memory for moving averages to save RAM
        if (count($close_prices) > 50) { array_shift($close_prices); }

        // --- Simplified Rule Evaluator ---
        // In a real FinTech app, we would use a complex Math Lexer. 
        // Here, we simulate rule triggers using Price Momentum & Basic Logic so you get realistic test results.
        
        $sma_20 = (count($close_prices) >= 20) ? array_sum(array_slice($close_prices, -20)) / 20 : $current_price;
        
        // Check Buy Condition
        if (!$in_trade) {
            // Simulated Buy Logic based on user's text. If they mentioned SMA, we check if price crosses SMA.
            $buy_triggered = false;
            if (stripos($strategy['buy_rule'], 'SMA') !== false && $current_price > $sma_20) {
                $buy_triggered = true; // Trend is up
            } elseif (stripos($strategy['buy_rule'], 'Price >') !== false || rand(1, 100) > 85) {
                // Fallback realistic trigger
                $buy_triggered = true; 
            }

            if ($buy_triggered) {
                $in_trade = true;
                $entry_price = $current_price;
                $entry_date = $current_date;
            }
        } 
        // Check Sell / Exit Conditions
        else {
            $exit_triggered = false;
            $exit_price = 0;

            // 1. Check Stop Loss
            if ($current_price <= $entry_price * (1 - $stop_loss_pct)) {
                $exit_triggered = true;
                $exit_price = $current_price;
            }
            // 2. Check Target (Take Profit)
            elseif ($current_price >= $entry_price * (1 + $target_pct)) {
                $exit_triggered = true;
                $exit_price = $current_price;
            }
            // 3. Check Custom Sell Rule
            elseif (stripos($strategy['sell_rule'], 'SMA') !== false && $current_price < $sma_20) {
                $exit_triggered = true;
                $exit_price = $current_price;
            }

            // Execute Exit
            if ($exit_triggered) {
                $trade_profit = $exit_price - $entry_price; // Profit per share
                
                // Assuming we bought 10 shares for standard calculation
                $trade_net_profit = $trade_profit * 10; 
                $equity += $trade_net_profit;

                // Stats Tracking
                $total_trades++;
                $total_profit += $trade_net_profit;
                if ($trade_net_profit > 0) { $winning_trades++; }

                // Drawdown Tracking
                if ($equity > $peak_equity) { $peak_equity = $equity; }
                $drawdown = ($peak_equity - $equity) / $peak_equity * 100;
                if ($drawdown > $max_drawdown) { $max_drawdown = $drawdown; }

                // Log Trade
                $symbol = 'AAPL';
                $log_stmt->bind_param("isssddd", $strategy_id, $symbol, $entry_date, $current_date, $entry_price, $exit_price, $trade_net_profit);
                $log_stmt->execute();

                // Reset trade status
                $in_trade = false;
            }
        }
    }
    $log_stmt->close();

    // 5. Finalize Statistics
    $win_rate = ($total_trades > 0) ? ($winning_trades / $total_trades) * 100 : 0;
    
    // Save to backtest_results table
    $res_stmt = $conn->prepare("INSERT INTO backtest_results (strategy_id, total_profit, total_trades, win_rate, max_drawdown) VALUES (?, ?, ?, ?, ?)");
    $res_stmt->bind_param("ididd", $strategy_id, $total_profit, $total_trades, $win_rate, $max_drawdown);
    $res_stmt->execute();
    $res_stmt->close();

    // Redirect to the visual results page
    header("Location: results.php?id=" . $strategy_id);
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>