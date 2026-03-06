<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// === VERY IMPORTANT ===
// Get your free API key from: https://www.alphavantage.co/support/#api-key
$api_key = "demo"; // REPLACE "demo" WITH YOUR ACTUAL API KEY
$symbol = "AAPL"; // We will use Apple stock for reliable testing

echo "<div class='row mt-4'><div class='col-12'><div class='card p-4'>";
echo "<h3 class='fw-bold mb-4'><i class='fa-solid fa-cloud-arrow-down text-primary me-2'></i> Market Data Sync</h3>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol={$symbol}&apikey={$api_key}";

    // Use cURL for better compatibility with InfinityFree servers
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['Time Series (Daily)'])) {
        $time_series = $data['Time Series (Daily)'];
        $count = 0;

        // Prepare the insert statement to prevent SQL injection and speed up the loop
        $stmt = $conn->prepare("INSERT IGNORE INTO market_data (symbol, date, open_price, high_price, low_price, close_price, volume) VALUES (?, ?, ?, ?, ?, ?, ?)");

        foreach ($time_series as $date => $values) {
            $open = $values['1. open'];
            $high = $values['2. high'];
            $low = $values['3. low'];
            $close = $values['4. close'];
            $volume = $values['5. volume'];

            $stmt->bind_param("ssddddi", $symbol, $date, $open, $high, $low, $close, $volume);
            
            // Execute and count successful inserts
            // Assuming 'date' and 'symbol' combined should be unique in a real-world scenario, 
            // but for now, we just insert.
            if ($stmt->execute()) {
                $count++;
            }
        }
        $stmt->close();
        echo "<div class='alert alert-success'><i class='fa-solid fa-check-circle me-2'></i> Successfully imported {$count} days of historical data for {$symbol}.</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fa-solid fa-triangle-exclamation me-2'></i> Error fetching data. Please check your API key or try again later. API Response: " . htmlspecialchars(json_encode($data)) . "</div>";
    }
}
?>

<p class="text-muted mb-4">Click the button below to fetch the latest historical daily stock data for <strong><?php echo $symbol; ?></strong> from Alpha Vantage and save it to your database. This data is required for the backtesting engine.</p>

<form method="POST" action="">
    <button type="submit" class="btn btn-primary btn-lg">
        <i class="fa-solid fa-download me-2"></i> Sync Market Data
    </button>
</form>

</div></div></div>

<?php require_once 'includes/footer.php'; ?>