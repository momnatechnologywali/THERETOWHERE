<?php
// File: index.php
// Homepage
 
session_start();
require_once 'db.php';
 
$destinations = $pdo->query("SELECT * FROM destinations WHERE featured = TRUE LIMIT 4")->fetchAll();
$articles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThereToWhere - Discover Your Next Adventure</title>
    <style>
        /* Internal CSS - Professional, modern, travel-themed design */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        header { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 1rem 0; position: fixed; width: 100%; top: 0; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        nav { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        nav ul { display: flex; list-style: none; }
        nav ul li { margin-left: 2rem; }
        nav ul li a { text-decoration: none; color: #333; font-weight: bold; transition: color 0.3s; }
        nav ul li a:hover { color: #ff6b35; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #ff6b35; }
        .hero { background: url('https://example.com/hero-bg.jpg') center/cover no-repeat; height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-top: 60px; }
        .hero h1 { font-size: 3rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .hero p { font-size: 1.2rem; margin-bottom: 2rem; }
        .btn { background: #ff6b35; color: white; padding: 12px 30px; border: none; border-radius: 50px; cursor: pointer; font-size: 1rem; transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; display: inline-block; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,107,53,0.4); }
        .section { padding: 5rem 2rem; max-width: 1200px; margin: 0 auto; }
        .destinations { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem; }
        .dest-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .dest-card:hover { transform: translateY(-5px); }
        .dest-card img { width: 100%; height: 200px; object-fit: cover; }
        .dest-card h3 { padding: 1rem; font-size: 1.3rem; }
        .dest-card p { padding: 0 1rem 1rem; color: #666; }
        .price { padding: 0 1rem 1rem; font-weight: bold; color: #ff6b35; }
        .articles { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .article-card { background: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .article-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; }
        footer { background: #333; color: white; text-align: center; padding: 2rem; }
        @media (max-width: 768px) { .hero h1 { font-size: 2rem; } nav ul { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">ThereToWhere</div>
            <ul>
                <li><a href="#" onclick="redirectTo('search.php')">Search</a></li>
                <li><a href="#" onclick="redirectTo('blog.php')">Guides</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="#" onclick="redirectTo('dashboard.php')">Dashboard</a></li>
                    <li><a href="#" onclick="logout()">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" onclick="redirectTo('login.php')">Login</a></li>
                    <li><a href="#" onclick="redirectTo('signup.php')">Signup</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
 
    <section class="hero">
        <div>
            <h1>Discover Your Dream Destination</h1>
            <p>Book amazing trips with personalized recommendations</p>
            <a href="#" onclick="redirectTo('search.php')" class="btn">Start Exploring</a>
        </div>
    </section>
 
    <section class="section">
        <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #333;">Featured Destinations</h2>
        <div class="destinations">
            <?php foreach ($destinations as $dest): ?>
                <div class="dest-card">
                    <img src="<?= $dest['image_url'] ?>" alt="<?= $dest['name'] ?>">
                    <h3><?= $dest['name'] ?></h3>
                    <p><?= substr($dest['description'], 0, 100) ?>...</p>
                    <div class="price">$<?= $dest['price'] ?> / person</div>
                    <button class="btn" style="width: 100%; margin: 1rem; border-radius: 5px;" onclick="bookTrip(<?= $dest['id'] ?>)">Book Now</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
 
    <section class="section" style="background: rgba(255,255,255,0.8);">
        <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #333;">Travel Tips & Guides</h2>
        <div class="articles">
            <?php foreach ($articles as $article): ?>
                <div class="article-card">
                    <img src="<?= $article['image_url'] ?>" alt="<?= $article['title'] ?>">
                    <h3><?= $article['title'] ?></h3>
                    <p><?= substr($article['content'], 0, 150) ?>...</p>
                    <button class="btn" style="margin-top: 1rem;" onclick="redirectTo('blog.php?id=<?= $article['id'] ?>')">Read More</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
 
    <footer>
        <p>&copy; 2025 ThereToWhere. All rights reserved.</p>
    </footer>
 
    <script>
        // Internal JS - For redirections and basic interactions
        function redirectTo(page) {
            window.location.href = page;
        }
 
        function bookTrip(destId) {
            if (!<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>) {
                alert('Please login to book a trip.');
                redirectTo('login.php');
                return;
            }
            // Simulate booking - in real, send to book.php
            alert('Redirecting to booking...');
            redirectTo('book.php?dest=' + destId);
        }
 
        function logout() {
            if (confirm('Are you sure?')) {
                // Simulate logout
                window.location.href = 'logout.php';  // Would need a logout.php
            }
        }
 
        // Add smooth scroll or other effects if needed
        window.addEventListener('load', () => {
            document.querySelector('.hero').style.opacity = '1';
        });
    </script>
</body>
</html>
