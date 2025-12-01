<?php require 'common/header.php'; ?>
<?php require 'common/db-connect.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/g6.css">

<?php
try {
    // 商品情報の取得
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT products.*, series.series_name, genre.genre_name, author.author_name 
    FROM products
    JOIN series ON products.series_id = series.series_id
    LEFT JOIN genre ON series.genre_id = genre.genre_id
    LEFT JOIN author ON series.author_id = author.author_id
    WHERE products.product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // --- データ整形エリア ---

    if($product) {
        // 商品名の作成 (シリーズ名 + 半角スペース + 巻数)
        $product_name = $product['series_name'] . " " . strval($product['volume_number']) . "巻";

        // 発売日の整形 (YYYY-MM-DD -> YYYY年MM月DD日)
        $release_date_str = $product['release_date'] ?? '';
        if (!empty($release_date_str)) {
            $releases = explode("-", $release_date_str);
            if (count($releases) === 3) {
                $release_date = $releases[0]."年".$releases[1]."月".$releases[2]."日";
            } else {
                $release_date = $release_date_str;
            }
        } else {
            $release_date = '不明';
        }

        // 変数セット (データがない場合の対策も含む)
        $author_name = $product['author_name'] ?? '作者不明';
        $genre_name  = $product['genre_name'] ?? '-';
        $series_name = $product['series_name'] ?? '-';

        // productsテーブルにある情報
        $publisher_name = $product['publisher'] ?? '-';
        $label_name     = $product['label'] ?? '-';
        $pages          = $product['pages'] ?? '-';
        $img_url        = $product['product_img_url'];

        // 既にレビュー登録しているかを取得
        $sql = "SELECT * FROM review
        WHERE user_id = :user_id AND product_id = :product_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $review_count = $stmt->fetch(PDO::FETCH_ASSOC);

        // 既にお気に入り登録しているかを取得
        $sql = "SELECT * FROM favorite
        WHERE user_id = :user_id AND product_id = :product_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $favorite_count = $stmt->fetch(PDO::FETCH_ASSOC);

        // レビュー全体の星の数と件数の算出
        $sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt
        FROM review
        WHERE product_id = :product_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $avg_rating = floatval($row['avg_rating']);
        $avg_rating = round($avg_rating * 2) / 2;  // ★ 0.5刻みに正規化

        $ratingAllNum = isset($avg_rating) && $avg_rating !== null
        ? number_format($avg_rating, 1) : 0.0;
        $ratingAllCount = isset($row['cnt']) ? $row['cnt'] : 0;

        // 並び替えの情報を取得(レビュー情報の取得に使う)
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';

        $reviews = [
        'new' => 'review.updated_at DESC',
        'old' => 'review.updated_at',
        'high' => 'review.rating DESC',
        'low' => 'review.rating',
        ];
        $review = $reviews[$sort] ?? $reviews['new'];

        // レビュー情報の取得
        $sql = "SELECT review.*, user.* FROM review
        LEFT JOIN user ON review.user_id = user.user_id
        WHERE review.product_id = :product_id
        ORDER BY $review";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="review-page-main">
    
    <a href="#" onclick="history.back(); return false;" class="back-btn">
        <i class="fas fa-chevron-left"></i> 戻る
    </a>

    <h1 class="product-title"><?= $product_name . " | " . $label_name ?></h1>
    
    <div class="product-info-container">
        <div class="product-cover">
            <img src="<?= $img_url ?>" alt="<?= $product_name ?>">
        </div>
        
        <div class="product-details">
            <div class="detail-row">
                <span class="detail-label">発売日</span>
                <span class="detail-value text-black"><?= $release_date ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">作者</span>
                <a href="g3_search.php?keyword=<?= $author_name ?>" class="detail-value">
                    <?= $author_name ?></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">シリーズ</span>
                <a href="g3_search.php?keyword=<?= $series_name ?>" class="detail-value">
                    <?= $series_name ?></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">レーベル</span>
                <a href="g3_search.php?keyword=<?= $label_name ?>" class="detail-value">
                    <?= $label_name ?></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">出版社</span>
                <a href="g3_search.php?keyword=<?= $publisher_name ?>" class="detail-value">
                    <?= $publisher_name ?></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">ジャンル</span>
                <a href="g3_search.php?keyword=<?= $genre_name ?>" class="detail-value">
                    <?= $genre_name ?></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">ページ数</span>
                <span class="detail-value text-black"><?= $pages ?>p</span>
            </div>
        </div>
    </div>

    <div class="rating-display">
        <img src="<?= 'image/rating/' . str_replace('.', '_', $ratingAllNum) . '.png' ?>"
        alt="<?= $ratingAllNum ?>" class="rating"/>
        <span class="rating-value"><?= $ratingAllNum ?></span>
        <a class="review-count-link">(<?= $ratingAllCount ?>件)</a>
    </div>

    <div class="action-buttons">
        <!-- レビューボタン -->
        <?php if(!empty($review_count)): ?>
        <a class="btn-action btn-after">
            <i class="far fa-edit"></i> レビュー済み
        </a>
        <?php else: ?>
        <a class="btn-action btn-before">
            <i class="far fa-edit"></i> 未レビュー
        </a>
        <?php endif; ?>

        <!-- お気に入りボタン -->
        <?php if(!empty($favorite_count)): ?>
        <a class="btn-action btn-after">
            <i class="fas fa-heart"></i> お気に入り登録済み
        </a>
        <?php else: ?>
        <a class="btn-action btn-before">
            <i class="fas fa-heart"></i> お気に入り未登録
        </a>
        <?php endif; ?>
    </div>

    <p>レビューの追加は右下の「+」ボタンから出来ます。<br>
    ※レビューの追加にはログインが必要です。</p>

    <h2 class="section-title">レビュー一覧</h2>

    <div class="review-list">
        
    <?php
    foreach($result as $row) {
        // 記入日、閲覧日の加工
        $date = new DateTime($row['updated_at']);
        $updated_at = $date->format('Y/m/d H:i');

        $date = new DateTime($row['view_date']);
        $view_date = $date->format('Y/m/d');
    ?>
        <div class="review-item">
            <!-- アイコン画像 -->
            <div class="user-avatar">
                <img src="<?= $row['icon_url'] ?>" alt="アイコン画像" class="icon">
            </div>
            <div class="review-content">
                <div class="review-header">
                    <div>
                        <!-- ユーザーネーム -->
                        <div class="user-name"><?= $row['user_name'] ?></div>
                        <!-- 投稿日 -->
                        <span class="review-date"><?= $updated_at ?></span>
                    </div>
                    <!-- メニュー(ユーザーIDが一致する場合のみ表示) -->
                    <?php if($row['user_id'] === $_SESSION['user']['user_id']): ?>
                        <div class="menu-dropdown">
                            <!-- メニューボタン -->
                            <button id="menu-btn">
                                <i class="fas fa-ellipsis-h menu-dots"></i>
                            </button>
                            <!-- メニュー一覧 -->
                            <div id="menu-options" class="dropdown-content">
                                <a href="g7_review_update.php?id=<?= $row['review_id'] ?>">編集</a>
                                <a href="review/review_delete.php?id=<?= $row['review_id'] ?>">削除</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- 星の数 -->
                <?php
                // 星の画像を表示する
                $rating_image = 'image/rating/' . str_replace('.', '_', $row['rating']) . '.png';
                ?>
                <div class="review-stars">
                    <img src="<?= $rating_image ?>" alt="<?= $row['rating'] ?>" class="rating">
                    <span class="review-score-num"><?= $row['rating'] ?></span>
                </div>
                <!-- コメント -->
                <p class="review-text">
                    <?= $row['comment'] ?>
                </p>
                <!-- 鑑賞日 -->
                <div class="read-date">
                    <i class="far fa-calendar"></i> 鑑賞日：<?= $view_date ?>
                </div>
            </div>
        </div>
    <?php
    }
    if(empty($result)){
    // レビューが無かった場合
    echo '<p>レビューがありません</p>';
    }
    } else {
        // 商品が見つからなかった場合
        echo '<p>該当する商品が見つかりませんでした。</p>';
    }
} catch(PDOException $e) {
    echo '<p>データベースエラーが発生しました。</p>' . $e;
}
?>
    </div>
</main>

<div class="sort-dropdown">
    <button id="sort-btn" class="fab-refresh sort-button">
        <i class="fas fa-sync-alt"></i>
    </button>

    <div id="sort-options" class="dropdown-content">
        <a href="?id=<?= $_GET['id'] ?>&sort=new">投稿日：新しい順</a>
        <a href="?id=<?= $_GET['id'] ?>&sort=old">投稿日：古い順</a>
        <a href="?id=<?= $_GET['id'] ?>&sort=high">評価：高い順</a>
        <a href="?id=<?= $_GET['id'] ?>&sort=low">評価：低い順</a>
    </div>
</div>

<!-- 追加ボタンはログイン時のみ表示する -->
<?php if(isset($_SESSION['user'])): ?>
    <a href="g7_review_add.php?id=<?= $_GET['id'] ?>" class="fab-add">
        <i class="fas fa-plus"></i>
    </a>
<?php else: ?>
    <div class="fab-add fab-add-logout">
        <i class="fas fa-plus"></i>
    </div>
<?php endif; ?>

<?php include 'common/menu.php'; ?>
<?php include 'common/footer.php'; ?>

<script>
    // ページを訪れたら再読み込み(history.back()用)
    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // メニューの表示/非表示を切り替え
    document.getElementById("sort-btn").addEventListener("click", function(event) {
        closeDropdowns();
        document.getElementById("sort-options").classList.toggle("show");
    });

    document.getElementById("menu-btn").addEventListener("click", function(event) {
        closeDropdowns();
        document.getElementById("menu-options").classList.toggle("show");
    });

    // 画面の他の場所をクリックしたらメニューを閉じる
    window.onclick = function(event) {
        // sort, menu ボタン以外を押したら閉じる
        if (!event.target.closest("#sort-btn") &&
            !event.target.closest("#menu-btn")) {
            closeDropdowns();
        }
    };

    function closeDropdowns() {
        document.querySelectorAll(".dropdown-content").forEach(el => {
            el.classList.remove("show");
        });
    }
</script>

</body>
</html>