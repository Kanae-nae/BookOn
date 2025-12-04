<?php 
// セッション開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'common/header.php'; 
require 'common/db-connect.php'; 
?>

<link rel="stylesheet" href="css/g2.css">

<?php
try {
    // DB接続
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ★修正★: productsテーブルを基準に、authorとgenreを結合して取得
    // ※seriesテーブルは使わず、productsにある author_id, genre_id, series_name を使います
    $sql = 'SELECT 
                products.*, 
                genre.genre_name, 
                author.author_name 
            FROM products
            LEFT JOIN genre ON products.genre_id = genre.genre_id
            LEFT JOIN author ON products.author_id = author.author_id
            WHERE products.product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row) {
        // --- データ整形エリア ---

        // ★修正★ 商品名の作成 (作品名 + 半角スペース + 巻数 + "巻" + " | " + 出版社)
        // 表示例: チェンソーマン 22巻 | 集英社
        $publisher_str = $row['publisher'] ?? '';
        $product_name = $row['series_name'] . " " . $row['volume_number'] . "巻 | " . $publisher_str;

        // 発売日の整形
        $release_date_str = $row['release_date'] ?? '';
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

        // 変数セット
        $author_name = $row['author_name'] ?? '作者不明';
        $genre_name  = $row['genre_name'] ?? '-';
        $series_name  = $row['series_name'] ?? '-';
        // productsテーブルにoverview(あらすじ)がない場合は固定文言を表示
        $overview    = $row['overview'] ?? '商品説明がありません。';

        $product_id = $row['product_id'];
        $publisher_name = $row['publisher'] ?? '-';
        $label_name     = $row['label'] ?? '-';
        $pages          = $row['pages'] ?? '-';
        $price          = $row['price'];
        $stocks         = $row['stocks'];
        $img_url        = $row['product_img_url'];

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

        $ratingNum = isset($avg_rating) && $avg_rating !== null
        ? number_format($avg_rating, 1) : 0.0;
        $ratingCount = isset($row['cnt']) ? $row['cnt'] : 0;
?>

<main>
    <div class="product-detail-container">

        <div class="back-link">
            <a href="#" onclick="history.back(); return false;">
                <span>＜ 戻る</span>
            </a>
        </div>

        <h1 class="product-title"><?= htmlspecialchars($product_name) ?></h1>
        
        <div class="product-author"><a href="g3_search.php?keyword=<?= $author_name ?>">
            <?= htmlspecialchars($author_name) ?>
        </a></div>
        
        <div class="rating-display">
            <a href="g6_review.php?id=<?= $_GET['id'] ?>">
                <img src="<?= 'image/rating/' . str_replace('.', '_', $ratingNum) . '.png' ?>"
                alt="<?= $ratingNum ?>" class="rating"/>
                <span class="score"><?= $ratingNum ?></span>
                <span class="review-count">(<?= $ratingCount ?>件)</span>
            </a>
        </div>

        <div class="product-content">
            <div class="product-image-section">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($img_url) ?>" alt="<?= htmlspecialchars($product_name) ?>">
                </div>
            </div>

            <div class="purchase-section">
                <div class="price-quantity-container">
                    <p class="price-display">
                        <span class="currency"><?= number_format($price) ?>円</span> 
                        <span class="tax">(税込)</span>
                    </p>
                    
                    <form action="cart/cart_insert.php" method="post" onsubmit="prepareCart(this);">
                    <div class="quantity-selector">
                        <label for="quantity">数量</label>
                        <select name="count" id="quantity">
                            <?php
                            for($i=1; $i<=10; $i++){
                                echo '<option value="', $i, '">', $i, '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                    <div class="option-selection">
                        <p>オプションを選択してください。</p>

                        <div class="option-selection-box">
                            <label class="option-item radio-option">
                                <input type="radio" name="format" value="digital" checked>
                                <span class="option-label">電子書籍 (今すぐDL可)</span>
                                <span class="option-price"><?= number_format($price) ?>円</span>
                            </label>

                            <label class="option-item radio-option">
                                <input type="radio" name="format" value="book" <?= ($stocks <= 0) ? 'disabled' : '' ?>>

                                <span class="option-label-group">
                                    <span class="option-label">紙書籍</span>
                                    <span class="stock-status">
                                        <?php
                                            if($stocks > 0){
                                                echo "(○在庫あり)";
                                            } else {
                                                echo "(×在庫なし)";
                                            }
                                        ?>
                                    </span>
                                </span>

                                <span class="option-price"><?= number_format($price) ?>円</span>
                            </label>
                        </div>
                    </div>

                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                <input type="hidden" name="product_img_url" value="<?= htmlspecialchars($img_url) ?>">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product_name) ?>">
                <input type="hidden" name="author_name" value="<?= htmlspecialchars($author_name) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">

                <?php 
                if(isset($_SESSION['user'])){ 
                ?>
                    <div class="action-buttons">
                        <button type="submit" class="add-to-cart-btn">カートに入れる</button>
                    </div>
                <?php } else { ?>
                    <div class="action-buttons">
                        <button class="add-to-cart-btn" disabled>カートに入れる</button>
                    </div>
                <?php } ?>
                </form>

                <?php 
                if(isset($_SESSION['user'])){ 
                ?>
                    <form action="g8-2_favorite_insert.php" method="post" onsubmit="prepareFavorite(this);">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                        <div class="action-buttons">
                            <button type="submit" class="add-to-favorite-btn">お気に入りに追加する</button>
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="action-buttons">
                        <button class="add-to-favorite-btn" disabled>お気に入りに追加する</button>
                        <p class="login-msg">購入およびお気に入り追加はログインが必要です。</p>
                    </div>
                <?php } ?>
        </div>
    </div>

        <hr>

        <section class="product-info-section">
            <h2>商品情報</h2>
            <table class="product-info-table">
                <tr>
                    <th>発売日</th>
                    <td><?= htmlspecialchars($release_date) ?></td>
                </tr>
                <tr>
                    <th>作者</th>
                    <td><a href="g3_search.php?keyword=<?= $author_name ?>">
                        <?= htmlspecialchars($author_name) ?>
                    </a></td>
                </tr>
                <tr>
                    <th>シリーズ</th>
                    <td><a href="g3_search.php?keyword=<?= $series_name ?>">
                        <?= htmlspecialchars($series_name) ?>
                    </a></td>
                </tr>
                <tr>
                    <th>レーベル</th>
                    <td><a href="g3_search.php?keyword=<?= $label_name ?>">
                        <?= htmlspecialchars($label_name) ?>
                    </a></td>
                </tr>
                <tr>
                    <th>出版社</th>
                    <td><a href="g3_search.php?keyword=<?= $publisher_name ?>">
                        <?= htmlspecialchars($publisher_name) ?>
                    </a></td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td><a href="g3_search.php?keyword=<?= $genre_name ?>">
                        <?= htmlspecialchars($genre_name) ?>
                    </a></td>
                </tr>
                <tr>
                    <th>ページ数</th>
                    <td><?= htmlspecialchars($pages) ?>p</td>
                </tr>
            </table>
        </section>

        <hr>

        <section class="product-description-section">
            <h2>商品説明</h2>
            <p><?= nl2br(htmlspecialchars($overview)) ?></p>
        </section>
    </div>
</main>

<script>
(function(){
    const qty = document.getElementById('quantity');
    const formatRadios = document.getElementsByName('format');

    function getSelectedFormat(){
        for(const r of formatRadios) if(r.checked) return r.value;
        return 'digital';
    }

    window.prepareCart = function(form){
        const countField = form.querySelector('input[name="count"]') || document.getElementById('cart-count');
        const formatField = form.querySelector('input[name="format"]') || document.getElementById('cart-format');
        if(countField) countField.value = qty ? qty.value : '1';
        if(formatField) formatField.value = getSelectedFormat();
    };

    window.prepareFavorite = function(form){
        const countField = form.querySelector('input[name="count"]') || document.getElementById('cart-count');
        if(countField && qty) countField.value = qty.value;
    };
})();
</script>

<?php
    } else {
        echo '<p class="error-msg">該当する商品が見つかりませんでした。</p>';
    }
} catch(PDOException $e) {
    echo '<p class="error-msg">データベースエラー: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

<?php
require 'common/menu.php';
require 'common/footer.php';
?>