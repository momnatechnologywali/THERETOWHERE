<?php
// File: signup.php
// User Signup
 
session_start();
require_once 'db.php';
 
if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = hashPassword($_POST['password']);
    $full_name = $_POST['full_name'];
 
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $full_name]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: dashboard.php');  // But use JS redirect? Wait, for post, use PHP then JS
        exit;
    } catch (PDOException $e) {
        $error = "Signup failed: " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - ThereToWhere</title>
    <style>
        /* Internal CSS - Clean, modern form design */
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
        <h2>Create Your Account</h2>
        <?php if (isset($error)): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Signup</button>
        </form>
        <div class="link"><a href="#" onclick="redirectTo('login.php')">Already have an account? Login</a></div>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
