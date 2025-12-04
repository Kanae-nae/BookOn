<!-- ===============================
     マイページ - 購入履歴表示
     =============================== -->
<link rel="stylesheet" href="css/g9_purchase.css">

<?php
// DBから情報を取得
try {
    // 並び替えの部分を取得
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';
    $order = ($sort === 'old') ? 'orders.order_date' : 'orders.order_date DESC';

    // 購入履歴の取得
    $sql = "SELECT orders.*, order_items.*, products.*, series.series_name, author.author_name 
    FROM orders
    JOIN order_items ON orders.order_id = order_items.order_id
    JOIN products ON order_items.product_id = products.product_id
    JOIN series ON products.series_id = series.series_id
    LEFT JOIN author ON products.author_id = author.author_id
    WHERE orders.user_id = :user_id
    ORDER BY $order";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="purchase-section">
    <!-- 見出し -->
    <div class="purchase-header">
        <?php if($sort == 'old'): ?>
            <span><i class="fa-solid fa-clock-rotate-left"></i> 購入日：古い順</span>
        <?php else: ?>
            <span><i class="fa-solid fa-clock-rotate-left"></i> 購入日：新しい順</span>
        <?php endif; ?>
    </div>

    <!-- 購入履歴一覧 -->
    <div class="purchase-list">
        <?php
        foreach($result as $row) {
            // 既にレビュー登録しているかを取得
            $sql = "SELECT * FROM review
            WHERE user_id = :user_id AND product_id = :product_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $row['product_id'], PDO::PARAM_INT);
            $stmt->execute();
            $review_count = $stmt->fetch(PDO::FETCH_ASSOC);

            // 商品名の作成
            $product_name = $row['series_name'] . "（" . strval($row['volume_number']) . "巻）";

            // 著者名と出版社の結合
            $author = $row['author_name'] . " / " . $row['publisher'];

            // 購入日の作成
            $date = new DateTime($row['order_date']);
            $order_date = $date->format('Y年m月d日');
        ?>

        <!-- それぞれの履歴 -->
        <div class="purchase-card">
            <div class="book-thumb">
                <img src="<?= $row["product_img_url"] ?>" alt="<?= $product_name ?>">
            </div>
            <div class="purchase-info">
                <p class="book-title"><?= $product_name ?></p>
                <p class="book-author"><?= $author ?></p>
                <p class="purchase-date">購入日：<?= $order_date ?></p>
                <p class="purchase-price">￥<?= $row['price'] ?>（税込）</p>
                <div class="purchase-actions">
                    <button class="btn-detail" onclick="location.href='g2_detail.php?id=<?= $row['product_id']; ?>'">
                        詳細
                    </button>
                    <?php if(empty($review_count)): ?>
                        <button class="btn-review" onclick="location.href='g7_review_add.php?id=<?= $row['product_id']; ?>'">
                            レビューを書く
                        </button>
                    <?php else: ?>
                        <!-- レビュー投稿済み -->
                        <button class="btn-review disabled">
                            レビューを書く
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</section>

<?php
if(empty($result)){
    // 購入履歴が無かった場合
    echo '<p class="no-purchase">購入履歴がありません</p>';
}
} catch(PDOException $e) {
    echo '<p>データベースエラーが発生しました。</p>' . $e;
}
?>