<?php
// File: blog.php
// Travel Guide & Blog
 
session_start();
require_once 'db.php';
 
$article_id = $_GET['id'] ?? 0;
if ($article_id) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();
    // Increment views
    $pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$article_id]);
} else {
    $articles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC")->fetchAll();
}
 
if (isset($_SESSION['user_id']) && isset($_GET['save'])) {
    $user_id = $_SESSION['user_id'];
    $art_id = $_GET['id'];
    $stmt = $pdo->prepare("INSERT IGNORE INTO saved_articles (user_id, article_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $art_id]);
    echo "<script>alert('Saved!');</script>";
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Guides - ThereToWhere</title>
    <style>
        /* Internal CSS - Blog style */
        body { font-family: Arial, sans-serif; background: #f5f7fa; }
        .container { max-width: 800px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .articles { display: flex; flex-direction: column; gap: 2rem; }
        .article { padding: 1.5rem; border-bottom: 1px solid #ddd; }
        .article h2 { color: #333; margin-bottom: 1rem; }
        .article p { line-height: 1.8; color: #666; }
        .btn { background: #ff6b35; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; }
        .full-article { line-height: 1.8; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($article_id && $article): ?>
            <article class="full-article">
                <h1><?= $article['title'] ?></h1>
                <img src="<?= $article['image_url'] ?>" alt="<?= $article['title'] ?>" style="width: 100%; border-radius: 10px;">
                <p><?= $article['content'] ?></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn" onclick="saveArticle(<?= $article['id'] ?>)">Save Article</button>
                <?php endif; ?>
            </article>
        <?php else: ?>
            <h1>Travel Guides & Blog</h1>
            <div class="articles">
                <?php foreach ($articles as $art): ?>
                    <div class="article">
                        <h2><a href="?id=<?= $art['id'] ?>"><?= $art['title'] ?></a></h2>
                        <p><?= substr($art['content'], 0, 200) ?>...</p>
                        <small>Views: <?= $art['views'] ?></small>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="btn" onclick="saveArticle(<?= $art['id'] ?>)">Save</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p style="text-align: center;"><a href="#" onclick="redirectTo('index.php')">Back to Home</a></p>
    </div>
 
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
 
        function saveArticle(id) {
            window.location.href = '?id=' + id + '&save=1';
        }
    </script>
</body>
</html>
