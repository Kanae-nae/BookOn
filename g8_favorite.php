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

<style>
    /* 全体のレイアウト調整 */
    .fav-list {
        max-width: 800px;
        margin: 0 auto;
        padding: 10px;
    }

    /* 商品カード（行） */
    .fav-item {
        display: flex;
        align-items: center;
        border: 1px solid #eee; /* 枠線を薄くつける */
        border-radius: 8px;     /* 角を少し丸く */
        padding: 15px;
        margin-bottom: 15px;    /* 商品ごとの間隔 */
        background-color: #fff;
        transition: all 0.2s;   /* 動きをなめらかに */
    }

    /* 選択されたときのスタイル（青い枠） */
    .fav-item.selected {
        border: 2px solid #5d5fef;
        background-color: #fbfbff;
    }

    /* チェックボックスエリア */
    .fav-check {
        width: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 10px;
    }

    /* ★重要: チェックボックスを強制的に表示させるスタイル */
    input[type="checkbox"].force-visible {
        appearance: auto !important;     /* ブラウザ標準の見た目に戻す */
        -webkit-appearance: auto !important;
        display: inline-block !important; /* 非表示設定を解除 */
        opacity: 1 !important;            /* 透明度解除 */
        width: 20px !important;           /* サイズ指定 */
        height: 20px !important;
        cursor: pointer;
    }

    /* 画像エリア */
    .fav-image {
        width: 100px;
        margin-right: 20px;
        flex-shrink: 0;
    }
    .fav-image img {
        width: 100%;
        height: auto;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* 詳細情報エリア */
    .fav-details {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* タイトル */
    .fav-title a {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        text-decoration: none;
    }

    /* 価格 */
    .fav-price {
        font-size: 18px;
        color: #d00;
        font-weight: bold;
        margin-top: 5px;
    }
    .fav-price .tax {
        font-size: 12px;
        color: #666;
        font-weight: normal;
    }

    /* 削除ボタン */
    .fav-delete-area {
        margin-left: 15px;
        text-align: center;
        min-width: 50px;
    }
    .btn-delete {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #333;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
    }
    .btn-delete i {
        font-size: 24px;
        margin-bottom: 5px;
        color: #555;
    }
    .btn-delete:hover i {
        color: #d00;
    }

    /* 下部のアクションエリア（固定を解除） */
    .bottom-action-area {
        max-width: 800px;
        margin: 30px auto 60px auto; /* 上に30px、下に60pxの余白 */
        text-align: center;
        padding: 20px;
        border-top: 1px solid #ddd;
    }
    .btn-add-cart {
        background-color: #5d5fef;
        color: white;
        border: none;
        padding: 15px 60px;
        font-size: 18px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(93, 95, 239, 0.3);
    }
    .btn-add-cart:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .selection-msg {
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 16px;
    }
</style>
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