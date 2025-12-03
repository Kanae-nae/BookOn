<?php


session_start();

// require_onceに変更（もしheader.phpでも読み込んでいてもエラーにならないように）
require_once 'common/db-connect.php';
// セッション確認（なければ1にする）
$user_id = isset($_SESSION['customer']['user_id']) ? $_SESSION['customer']['user_id'] : 1;

// SQL作成
$sql = "SELECT p.*, f.favorite_id 
        FROM favorite AS f
        JOIN products AS p ON f.product_id = p.product_id
        WHERE f.user_id = :id";

try {
    // db-connect.phpで定義された変数$connect, 定数USER, PASSを使用
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 接続エラーがあれば表示して終了
    echo "DB Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お気に入り - BOOK ON</title>
<link rel="stylesheet" href="css/g8.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include 'common/header.php'; ?>
<main class="favorite-main">
<h2 class="page-title">お気に入り</h2>

<p style="font-size:10px; color:#ccc;">User ID: <?= htmlspecialchars($user_id) ?></p>

<form action="cart_insert.php" method="post"> 
<div class="fav-list">

    <?php if (empty($favorites)): ?>
        <p style="text-align:center; padding: 20px;">お気に入りに登録された商品はありません。</p>
    <?php else: ?>
        
        <?php foreach ($favorites as $row): ?>
        <div class="fav-item">
            <div class="fav-check">
                <input type="checkbox" name="select_products[]" value="<?= $row['product_id'] ?>" id="check<?= $row['product_id'] ?>" class="custom-checkbox">
                <label for="check<?= $row['product_id'] ?>" class="check-label"></label>
            </div>
            <div class="fav-image">
                <img src="<?= htmlspecialchars($row['product_img_url']) ?>" alt="<?= htmlspecialchars($row['series_name']) ?>" onerror="this.src='image/no_image.png'">
            </div>
            <div class="fav-details">
                <div class="fav-title">
                    <?= htmlspecialchars($row['series_name']) ?> <?= htmlspecialchars($row['volume_number']) ?>
                </div>
                
                <div class="fav-author"><?= htmlspecialchars($row['publisher']) ?></div>
                
                <div class="fav-rating">
                    <span class="stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                    </span>
                    <span class="rating-text">4.0</span>
                    <span class="review-count">(5件)</span>
                </div>
                <div class="fav-meta">
                    <span>シリーズ <a href="#" class="meta-link"><?= htmlspecialchars($row['series_name']) ?></a></span>
                </div>
                <div class="fav-date"><?= htmlspecialchars($row['release_date']) ?> 発売</div>
                <div class="fav-type">電子書籍</div>
                
                <div class="fav-footer">
                    <div class="fav-price"><?= number_format($row['price']) ?>円 <span class="price-tax">(税込)</span></div>
                    
                    <a href="favorite_delete.php?id=<?= $row['favorite_id'] ?>" class="fav-delete" style="text-decoration:none; color:inherit;">
                        <i class="far fa-trash-alt delete-icon"></i>
                        <span class="delete-text">削除</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>
</main>

<div class="bottom-action-area">
    <span class="selection-count">選択した商品を</span>
    <button type="submit" class="btn-add-cart">カートに入れる</button>
</div>
</form>

<?php include 'common/menu.php'; ?>
<?php include 'common/footer.php'; ?>
</body>
</html>