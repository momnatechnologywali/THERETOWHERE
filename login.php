<?php
// File: login.php
// User Login
 
session_start();
require_once 'db.php';
 
if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
 
    if ($user && verifyPassword($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ThereToWhere</title>
    <style>
        /* Same as signup for consistency */
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-container { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: #333; }
        input { width: 100%; padding: 12px; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        .btn { background: #ff6b35; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 1rem; transition: background 0.3s; }
        .btn:hover { background: #e55a2b; }
        .link { text-align: center; margin-top: 1rem; }
        .link a { color: #ff6b35; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login to Your Account</h2>
        <?php if (isset($error)): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="link"><a href="#" onclick="redirectTo('signup.php')">New user? Signup</a></div>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
