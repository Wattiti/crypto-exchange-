<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$db = new Database();
$auth = new Auth($db);

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get pair data
$symbol = $_GET['pair'] ?? 'BTC/USDT';
$db->query("SELECT * FROM trading_pairs WHERE symbol = ?")
   ->bind(1, $symbol);
$pair = $db->single();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade <?= $symbol ?> | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/chart.css">
</head>
<body>
    <div class="container trading-container">
        <div class="trading-header">
            <h2><?= $symbol ?></h2>
            <span class="price-change <?= $pair['price_change_24h'] < 0 ? 'negative' : 'positive' ?>">
                <?= number_format($pair['price_change_24h'], 2) ?>%
            </span>
            <div class="current-price">
                <?= number_format($pair['last_price'], 4) ?>
                <span>≈ <?= number_format($pair['last_price'], 2) ?> USD</span>
            </div>
        </div>

        <div class="timeframe-selector">
            <button class="time-btn">15m</button>
            <button class="time-btn">1h</button>
            <button class="time-btn">4h</button>
            <button class="time-btn active">1D</button>
            <button class="time-btn">More ▼</button>
        </div>

        <div class="chart-container">
            <div id="trading-chart"></div>
            <div class="chart-indicators">
                <span>MA7: <?= number_format($pair['ma7'], 4) ?></span>
                <span>MA14: <?= number_format($pair['ma14'], 4) ?></span>
                <span>MA28: <?= number_format($pair['ma28'], 4) ?></span>
            </div>
        </div>

        <div class="trade-actions">
            <button class="buy-btn">Buy</button>
            <button class="sell-btn">Sell</button>
        </div>
    </div>

    <script src="assets/js/chart.js"></script>
    <script>
        // Initialize trading chart
        const tradingChart = new TradingView.widget({
            "autosize": true,
            "symbol": "<?= str_replace('/', '', $symbol) ?>",
            "interval": "D",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "hide_top_toolbar": true,
            "hide_side_toolbar": false,
            "allow_symbol_change": true,
            "container_id": "trading-chart"
        });
    </script>
</body>
</html>
