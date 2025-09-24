<?php
// File: profile.php
// Profile Management
 
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
 
if ($_POST) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
    $stmt->execute([$full_name, $phone, $user_id]);
    $user = $pdo->query("SELECT * FROM users WHERE id = $user_id")->fetch();  // Refresh
    echo "<script>alert('Profile updated!');</script>";
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ThereToWhere</title>
    <style>
        /* Internal CSS - Profile form */
        body { font-family: Arial, sans-serif; background: #f5f7fa; }
        .container { max-width: 500px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #ff6b35; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <input type="text" name="full_name" value="<?= $user['full_name'] ?>" required>
            <input type="tel" name="phone" value="<?= $user['phone'] ?? '' ?>">
            <button type="submit" class="btn">Update</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;"><a href="#" onclick="redirectTo('dashboard.php')">Back to Dashboard</a></p>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
