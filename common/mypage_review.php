<!-- ===============================
     マイページ - レビュー表示
     =============================== -->
     
<link rel="stylesheet" href="css/g9_review.css">

<?php
// DBから情報を取得
try {
    // 並び替えの部分を取得
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';

    $reviews = [
    'new' => 'review.updated_at DESC',
    'old' => 'review.updated_at',
    'high' => 'review.rating DESC',
    'low' => 'review.rating',
    ];
    $review = $reviews[$sort] ?? $reviews['new'];

    // レビュー情報の取得
    $sql = "SELECT review.*, products.*, series.* FROM review
    JOIN products ON review.product_id = products.product_id
    JOIN series ON products.series_id = series.series_id
    WHERE review.user_id = :user_id
    ORDER BY $review";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="review-section">
    <!-- 見出し -->
    <div class="review-header">
        <?php if($sort == 'old'): ?>
            <span><i class="fa-solid fa-list"></i> 投稿日：古い順</span>
        <?php elseif($sort == 'high'): ?>
            <span><i class="fa-solid fa-list"></i> 評価：高い順</span>
        <?php elseif($sort == 'low'): ?>
            <span><i class="fa-solid fa-list"></i> 評価：低い順</span>
        <?php else: ?>
            <span><i class="fa-solid fa-list"></i> 投稿日：新しい順</span>
        <?php endif; ?>
    </div>

    <!-- レビュー一覧 -->
    <div class="review-list">
        <?php
        foreach($result as $row) {
            // 商品名の作成
            $product_name = $row['series_name'] . "（" . strval($row['volume_number']) . "巻）";

            // 記入日、閲覧日の加工
            $date = new DateTime($row['updated_at']);
            $updated_at = $date->format('Y年m月d日');

            $date = new DateTime($row['view_date']);
            $view_date = $date->format('Y年m月d日');
        ?>

        <!-- レビュー -->
        <div class="review-card">
            <div class="book-thumb">
                <a href="g2_detail.php?id=<?= $row['product_id'] ?>">
                <img src="<?= $row["product_img_url"] ?>" alt="<?= $product_name ?>">
                </a>
            </div>
            <div class="review-info">
                <p class="book-title"><?= $product_name ?></p>
                <!-- 星の描画 -->
                <?php
                // 星の画像を表示する
                $rating = floatval($row['rating']);
                $rating = round($rating * 2) / 2;  // ★ 0.5刻みに正規化

                $rating_image = 'image/rating/' . str_replace('.', '_', $rating) . '.png';
                $rating_num = $rating;
                ?>
                <div>
                    <img src="<?= $rating_image ?>" alt="<?= $rating_num ?>" class="rating">
                </div>
                <p class="review-text">
                    鑑賞日：<?= $view_date ?>
                </p>
                <p class="review-text">
                    <?= $row['comment'] ?>
                </p>
                <p class="review-date">投稿日：<?= $updated_at ?></p>
            </div>
        </div>
    <?php } ?>
    </div>
</section>

<?php
if(empty($result)){
    // レビューが無かった場合
    echo '<p class="no-review">レビューがありません</p>';
}

} catch(PDOException $e) {
    echo '<p>データベースエラーが発生しました。</p>' . $e;
}
?>