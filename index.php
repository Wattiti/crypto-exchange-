<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$db = new Database();
$auth = new Auth($db);

// Redirect if not logged in
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get market data
$db->query("SELECT * FROM trading_pairs ORDER BY volume_24h DESC LIMIT 6");
$marketPairs = $db->resultset();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="market-header">
            <h1>SKATE/USDT</h1>
            <div class="price-info">
                <span class="price">0.04 USD</span>
                <span class="btc-price">≈ 0.00000045 BTC</span>
            </div>
        </div>

        <div class="quick-actions">
            <a href="#" class="action-btn">P2P Trading</a>
            <a href="#" class="action-btn">Convert</a>
            <a href="#" class="action-btn">Deposit</a>
            <a href="#" class="action-btn">Buy Crypto</a>
            <a href="#" class="action-btn">More</a>
        </div>

        <div class="favorites-section">
            <div class="favorites-tabs">
                <button class="tab active">Hot</button>
                <button class="tab">New</button>
                <button class="tab">Gainers</button>
                <button class="tab">Losers</button>
                <button class="tab">Turnover</button>
            </div>

            <div class="market-pairs">
                <?php foreach($marketPairs as $pair): ?>
                <div class="market-item">
                    <span class="symbol"><?= $pair['symbol'] ?></span>
                    <span class="price"><?= number_format($pair['last_price'], 2) ?></span>
                    <span class="change <?= $pair['price_change_24h'] < 0 ? 'negative' : 'positive' ?>">
                        <?= number_format($pair['price_change_24h'], 2) ?>%
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
