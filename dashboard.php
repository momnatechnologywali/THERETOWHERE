<?php
// File: dashboard.php
// User Dashboard
 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';
 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
 
$bookings = $pdo->prepare("SELECT b.*, d.name as dest_name FROM bookings b JOIN destinations d ON b.destination_id = d.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
$bookings->execute([$user_id]);
$bookings = $bookings->fetchAll();
 
$wishlist = $pdo->prepare("SELECT d.* FROM wishlist w JOIN destinations d ON w.destination_id = d.id WHERE w.user_id = ?");
$wishlist->execute([$user_id]);
$wishlist = $wishlist->fetchAll();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ThereToWhere</title>
    <style>
        /* Internal CSS - Dashboard style */
        body { font-family: Arial, sans-serif; background: #f5f7fa; margin: 0; }
        header { background: white; padding: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .user-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .user-info h2 { color: #333; }
        .btn { background: #ff6b35; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .section { background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .bookings-grid, .wishlist-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
        .booking-card, .wish-card { padding: 1rem; border: 1px solid #ddd; border-radius: 5px; }
        .rating { color: #ff6b35; }
    </style>
</head>
<body>
    <header>
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between;">
            <h1>ThereToWhere Dashboard</h1>
            <a href="#" onclick="redirectTo('index.php')" class="btn">Home</a>
        </div>
    </header>
 
    <div class="container">
        <div class="user-info">
            <h2>Welcome, <?= $user['full_name'] ?>!</h2>
            <a href="#" onclick="redirectTo('profile.php')" class="btn">Edit Profile</a>
        </div>
 
        <div class="section">
            <h3>Upcoming Trips</h3>
            <div class="bookings-grid">
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">
                        <h4><?= $booking['dest_name'] ?></h4>
                        <p>From: <?= $booking['start_date'] ?> to <?= $booking['end_date'] ?></p>
                        <p>Status: <?= ucfirst($booking['status']) ?></p>
                        <p>Total: $<?= $booking['total_price'] ?></p>
                        <?php if ($booking['status'] == 'confirmed'): ?>
                            <div class="rating">
                                <form method="POST" action="review.php" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <input type="number" name="rating" min="1" max="5" placeholder="Rate 1-5">
                                    <input type="text" name="comment" placeholder="Comment">
                                    <button type="submit" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Review</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
 
        <div class="section">
            <h3>Wishlist</h3>
            <div class="wishlist-grid">
                <?php foreach ($wishlist as $dest): ?>
                    <div class="wish-card">
                        <h4><?= $dest['name'] ?></h4>
                        <p>$<?= $dest['price'] ?></p>
                        <button class="btn" onclick="bookTrip(<?= $dest['id'] ?>)">Book Now</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
 
        function bookTrip(destId) {
            redirectTo('book.php?dest=' + destId);
        }
    </script>
</body>
</html>
