<?php require 'common/header.php'; ?>
<!-- 商品詳細画面(G2) -->
<?php require 'common/db-connect.php'; ?>

<link rel="stylesheet" href="css/g2.css">

<?php
try {
    // 商品の取得
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT products.*, series.*, author.*, genre.* FROM products
    JOIN series ON products.series_id = series.series_id
    JOIN author ON author.author_id = series.author_id
    JOIN genre ON genre.genre_id = series.genre_id
    WHERE products.product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($row)) {
        // 商品名の作成
        $product_name = $row['series_name'] . " " . strval($row['volume_number']) . "巻";

        // 発売日の作成
        $releases = explode("-",$row['release_date']);
        $release_date = $releases[0]."年".$releases[1]."月".$releases[2]."日";
?>

<main>
    <div class="product-detail-container">

        <!-- 戻るボタンの表示 -->
        <div class="back-link">
            <a href="#" onclick="history.back(); return false;">
                <span>＜ 戻る</span>
            </a>
        </div>

        <!-- タイトル、著者名等の表示 -->
        <h1 class="product-title"><?= $product_name ?></h1>
        <div class="product-author"><a href="#"><?= $row['author_name'] ?></a></div>
        <div class="rating">
            <span class="stars">★★★★☆</span>
            <span class="score">4.0</span>
            <span class="review-count">(5件)</span>
        </div>

        <!-- 画像の表示 -->
        <div class="product-content">
            <div class="product-image-section">
                <div class="product-image">
                    <img src="<?= $row['product_img_url'] ?>" alt="<?= $product_name ?>">
                </div>
            </div>

            <div class="purchase-section">
                <div class="price-quantity-container">
                    <p class="price-display">
                        <span class="currency"><?= number_format($row['price']) ?>円</span> 
                        <span class="tax">(税込)</span>
                    </p>
                    <!-- カートの追加処理 -->
                    <form action="cart/cart_insert.php" method="post" onsubmit="prepareCart(this);">
                    <!-- 数量の入力 -->
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

                    <!-- 紙書籍か電子書籍かの選択 -->
                    <div class="option-selection">
                        <p>オプションを選択してください。</p>

                        <div class="option-selection-box">
                            <label class="option-item radio-option">
                                <input type="radio" name="format" value="digital" checked>
                                <span class="option-label">電子書籍 (今すぐDL可)</span>
                                <span class="option-price"><?= number_format($row['price']) ?>円</span>
                            </label>

                            <label class="option-item radio-option">
                                <input type="radio" name="format" value="book" <?= ($row['stocks'] <= 0) ? 'disabled' : '' ?>>

                                <span class="option-label-group">
                                    <span class="option-label">紙書籍</span>
                                    <span class="stock-status">
                                        <?php
                                            if($row['stocks'] > 0){
                                                echo "(○在庫あり)";
                                            } else {
                                                echo "(×在庫なし)";
                                            }
                                        ?>
                                    </span>
                                </span>

                                <span class="option-price"><?= number_format($row['price']) ?>円</span>
                            </label>
                        </div>
                    </div>

                <!-- 残りの情報を渡す -->
                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                <input type="hidden" name="product_img_url" value="<?= $row['product_img_url'] ?>">
                <input type="hidden" name="product_name" value="<?= $product_name ?>">
                <input type="hidden" name="author_name" value="<?= $row['author_name'] ?>">
                <input type="hidden" name="price" value="<?= $row['price'] ?>">

                <!-- ログイン時のみカートの追加処理＆お気に入りの登録処理を行う -->
                <?php if(isset($_SESSION['user'])){ ?>
                    <!-- カートに入れる処理 -->
                    <div class="action-buttons">
                        <button type="submit" class="add-to-cart-btn">カートに入れる</button>
                    </div>

                <!-- ログアウト時はボタンを押せなくする -->
                <?php } else { ?>
                    <div class="action-buttons">
                        <button class="add-to-cart-btn" disabled>カートに入れる</button>
                    </div>
                <?php } ?>
                </form>

                <!-- お気に入りの追加処理 -->
                <?php if(isset($_SESSION['user'])){ ?>
                    <form action="" method="post" onsubmit="prepareFavorite(this);">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <div class="action-buttons">
                            <button type="submit" class="add-to-favorite-btn">お気に入りに追加する</button>
                        </div>
                    </form>

                <!-- カート同様、ログアウト時はボタンを押せなくする -->
                <?php } else { ?>
                    <div class="action-buttons">
                        <button class="add-to-favorite-btn" disabled>お気に入りに追加する</button>
                        <p>購入及びお気に入りへの追加はログインが必要です。</p>
                    </div>
                <?php } ?>
        </div>
    </div>

        <hr>

        <section class="product-info-section">
            <h2>商品情報(まだ未反映)</h2>
            <table class="product-info-table">
                <tr>
                    <th>発売日</th>
                    <td>2025年09月04日</td>
                </tr>
                <tr>
                    <th>作者</th>
                    <td>藤本タツキ</td>
                </tr>
                <tr>
                    <th>シリーズ</th>
                    <td>チェンソーマン</td>
                </tr>
                <tr>
                    <th>レーベル</th>
                    <td>ジャンプコミックス</td>
                </tr>
                <tr>
                    <th>出版社</th>
                    <td>集英社</td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td>バトル・アクション</td>
                </tr>
                <tr>
                    <th>ページ数</th>
                    <td>192p</td>
                </tr>
                                <tr>
                    <th>発売日</th>
                    <td><?= $release_date ?></td>
                </tr>
            </table>
        </section>

        <hr>

        <section class="product-description-section">
            <h2>商品説明</h2>
            <p><?= $row['overview'] ?></p>
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
        // お気に入りには数量は必須でないが、必要ならコピーする
        const countField = form.querySelector('input[name="count"]') || document.getElementById('cart-count');
        if(countField && qty) countField.value = qty.value;
    };
})();
</script>

<?php
    } else {
        echo '<p>商品が見つかりません。</p>';
    }
} catch(PDOException $e) {
    echo '<p>データベースエラーが発生しました。</p>';
}
?>

<?php
// footer.phpを読み込む (後でPHPロジックを追加する際に使用)
require 'common/menu.php';
require 'common/footer.php';
?>