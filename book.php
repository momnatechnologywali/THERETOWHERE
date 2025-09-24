<?php
// File: book.php
// Booking System
 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';
 
$dest_id = $_GET['dest'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$dest_id]);
$dest = $stmt->fetch();
 
if ($_POST) {
    $user_id = $_SESSION['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $total_price = $dest['price'];  // Simplified
 
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, destination_id, booking_date, start_date, end_date, total_price, status) VALUES (?, ?, CURDATE(), ?, ?, ?, 'confirmed')");
    $stmt->execute([$user_id, $dest_id, $start_date, $end_date, $total_price]);
 
    // Simulate email
    $booking_id = $pdo->lastInsertId();
    echo "<script>alert('Booking confirmed! ID: $booking_id'); redirectTo('dashboard.php');</script>";
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Trip - ThereToWhere</title>
    <style>
        /* Internal CSS - Booking form */
        body { font-family: Arial, sans-serif; background: #f5f7fa; }
        .container { max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 1rem; }
        .dest-info { margin-bottom: 2rem; padding: 1rem; background: #f0f0f0; border-radius: 5px; }
        input { width: 100%; padding: 12px; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #ff6b35; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book <?= $dest['name'] ?></h2>
        <div class="dest-info">
            <p><strong>Description:</strong> <?= $dest['description'] ?></p>
            <p><strong>Price:</strong> $<?= $dest['price'] ?> per person</p>
            <p><strong>Duration:</strong> <?= $dest['duration'] ?> days</p>
        </div>
        <form method="POST">
            <input type="date" name="start_date" required>
            <input type="date" name="end_date" required>
            <button type="submit" class="btn">Confirm Booking</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;"><a href="#" onclick="redirectTo('search.php')">Back to Search</a></p>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
