<?php
// File: review.php
// Review Submission (simple, can be integrated)
 
session_start();
require_once 'db.php';
 
if ($_POST && isset($_SESSION['user_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
 
    $stmt = $pdo->prepare("INSERT INTO reviews (booking_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$booking_id, $user_id, $rating, $comment]);
 
    echo "<script>alert('Review submitted!'); redirectTo('dashboard.php');</script>";
}
?>
 
<script>
    function redirectTo(page) {
        window.location.href = page;
    }
</script>
