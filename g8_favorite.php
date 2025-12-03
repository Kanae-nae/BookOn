<?php
// セッション開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'common/db-connect.php';

// ログイン確認
$user_id = isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : null;
if ($user_id === null) $user_id = 0;

// SQL作成
$sql = "SELECT 
            f.favorite_id,
            p.product_id,
            p.series_name,
            p.volume_number,
            p.price,
            p.product_img_url,
            p.release_date,
            p.publisher,
            a.author_name 
        FROM favorite AS f
        JOIN products AS p ON f.product_id = p.product_id
        LEFT JOIN author AS a ON p.author_id = a.author_id
        WHERE f.user_id = :id";

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
    <h2 class="page-title" style="text-align:center; margin: 30px 0;">お気に入り</h2>

    <form action="cart/cart_insert.php" method="post"> 
    
    <div class="fav-list">

        <?php if (empty($favorites)): ?>
            <div class="no-fav-msg" style="text-align:center; padding:40px;">
                <p>お気に入りに登録された商品はありません。</p>
                <a href="index.php" style="color:#007bff; text-decoration:underline;">買い物を続ける</a>
            </div>
        <?php else: ?>
            
            <?php foreach ($favorites as $row): ?>
            <?php 
                $displayName = $row['series_name'] . ' ' . $row['volume_number'];
                $displayAuthor = !empty($row['author_name']) ? $row['author_name'] : $row['publisher'];
            ?>
            
            <div class="fav-item" id="row_<?= $row['product_id'] ?>">
                
                <div class="fav-check">
                    <input type="checkbox" name="product_id[]" value="<?= $row['product_id'] ?>" 
                           class="force-visible product-check" 
                           data-row-id="row_<?= $row['product_id'] ?>">
                </div>

                <div class="fav-image">
                    <a href="g2_detail.php?id=<?= $row['product_id'] ?>">
                        <img src="<?= htmlspecialchars($row['product_img_url']) ?>" alt="商品画像" onerror="this.src='image/no_image.png'">
                    </a>
                </div>

                <div class="fav-details">
                    <div class="fav-title">
                        <a href="g2_detail.php?id=<?= $row['product_id'] ?>"><?= htmlspecialchars($displayName) ?></a>
                    </div>
                    
                    <div style="color:#666; font-size:14px; margin-top:5px;"><?= htmlspecialchars($displayAuthor) ?></div>
                    
                    <div class="fav-rating" style="color:#fcd53f; font-size:14px; margin: 5px 0;">
                        ★★★★☆ <span style="color:#999;">4.0 (5件)</span>
                    </div>

                    <div style="font-size:12px; color:#666; line-height: 1.5;">
                        シリーズ: <?= htmlspecialchars($row['series_name']) ?><br>
                        ジャンル: バトル・アクション<br> 発売日: <?= htmlspecialchars($row['release_date']) ?><br>
                        電子書籍
                    </div>
                    
                    <div class="fav-price">
                        <?= number_format($row['price']) ?>円 <span class="tax">(税込)</span>
                    </div>
                </div>

                <div class="fav-delete-area">
                    <a href="g8-3_favorite_delete.php?id=<?= $row['favorite_id'] ?>" class="btn-delete" onclick="return confirm('削除しますか？');">
                        <i class="far fa-trash-alt"></i>
                        <span>削除</span>
                    </a>
                </div>

            </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
    
    <?php if (!empty($favorites)): ?>
    <div class="bottom-action-area">
        <p class="selection-msg"><span id="select-count">0</span>件選択中</p>
        <button type="submit" class="btn-add-cart">カートに入れる</button>
    </div>
    <?php endif; ?>

    </form>
</main>

<script>
    // チェックボックスの動きを制御するスクリプト
    const checkboxes = document.querySelectorAll('.product-check');
    const countSpan = document.getElementById('select-count');
    
    checkboxes.forEach(ch => {
        ch.addEventListener('change', function() {
            // 選択件数の更新
            const count = document.querySelectorAll('.product-check:checked').length;
            countSpan.textContent = count;

            // 親要素(row)の枠線の色を変える
            const rowId = this.getAttribute('data-row-id');
            const rowElement = document.getElementById(rowId);
            
            if (this.checked) {
                rowElement.classList.add('selected'); // 青枠をつける
            } else {
                rowElement.classList.remove('selected'); // 青枠を消す
            }
        });
    });
</script>

<?php include 'common/menu.php'; ?>
<?php include 'common/footer.php'; ?>
</body>
</html>