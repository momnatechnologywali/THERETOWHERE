<?php
// File: search.php
// Trip Search & Filter
 
session_start();
require_once 'db.php';
 
$search = $_GET['q'] ?? '';
$type = $_GET['type'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 5000;
$duration = $_GET['duration'] ?? '';
 
$sql = "SELECT * FROM destinations WHERE 1=1";
$params = [];
 
if ($search) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$search%";
}
if ($type) {
    $sql .= " AND type = ?";
    $params[] = $type;
}
if ($min_price) {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
}
if ($max_price) {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
}
if ($duration) {
    $sql .= " AND duration = ?";
    $params[] = $duration;
}
 
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Trips - ThereToWhere</title>
    <style>
        /* Internal CSS - Search page with filters */
        body { font-family: Arial, sans-serif; background: #f5f7fa; }
        header { background: white; padding: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .filters { background: white; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .filters form { display: flex; flex-wrap: wrap; gap: 1rem; }
        .filters input, .filters select { padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .results { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; }
        .result-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .result-card img { width: 100%; height: 200px; object-fit: cover; }
        .result-card h3 { padding: 1rem; }
        .btn { background: #ff6b35; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between;">
            <h1>Search Trips</h1>
            <a href="#" onclick="redirectTo('index.php')" class="btn">Home</a>
        </div>
    </header>
 
    <div class="container">
        <div class="filters">
            <form method="GET">
                <input type="text" name="q" placeholder="Search destination..." value="<?= $search ?>">
                <select name="type">
                    <option value="">All Types</option>
                    <option value="beach" <?= $type == 'beach' ? 'selected' : '' ?>>Beach</option>
                    <option value="adventure" <?= $type == 'adventure' ? 'selected' : '' ?>>Adventure</option>
                    <option value="city" <?= $type == 'city' ? 'selected' : '' ?>>City</option>
                    <option value="nature" <?= $type == 'nature' ? 'selected' : '' ?>>Nature</option>
                </select>
                <input type="number" name="min_price" placeholder="Min Price" value="<?= $min_price ?>">
                <input type="number" name="max_price" placeholder="Max Price" value="<?= $max_price ?>">
                <input type="number" name="duration" placeholder="Duration (days)" value="<?= $duration ?>">
                <button type="submit" class="btn">Filter</button>
            </form>
        </div>
 
        <div class="results">
            <?php foreach ($results as $dest): ?>
                <div class="result-card">
                    <img src="<?= $dest['image_url'] ?>" alt="<?= $dest['name'] ?>">
                    <h3><?= $dest['name'] ?></h3>
                    <p style="padding: 0 1rem;"><?= substr($dest['description'], 0, 100) ?>...</p>
                    <div style="padding: 1rem;">
                        <strong>$<?= $dest['price'] ?></strong> | <?= $dest['duration'] ?> days
                        <br><button class="btn" onclick="bookTrip(<?= $dest['id'] ?>)">Book</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
 
        function bookTrip(destId) {
            if (!<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>) {
                alert('Login required');
                redirectTo('login.php');
                return;
            }
            redirectTo('book.php?dest=' + destId);
        }
    </script>
</body>
</html>
