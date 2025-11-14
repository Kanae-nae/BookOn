<!-- カートの共通処理 -->

<?php
//現在のページを取得
$current_page = basename($_SERVER['PHP_SELF']);

// 削除ボタンを表示するかの処理
$delete_page = ['g11_mycart.php'];
$show_delete = in_array($current_page, $delete_page);

// 合計金額を表示するか
$total_page = ['g11_mycart.php', 'g14_check.php'];
$show_total = in_array($current_page, $total_page);

// 変数の事前準備
$total = 0;
$paper = false;

// 商品の表示
foreach($_SESSION['product'] as $id => $product) {
    $subtotal = $product['price'] * $product['count'];
    $total += $subtotal;
    // 紙書籍かどうか判断(紙書籍の場合はTrue)
    if($product['format_id'] == 2){
        $paper = true;
    }
    ?>
    <div class="item">
        <!-- 画像の表示 -->
        <img src="<?= $product['product_img_url'] ?>" alt="<?= $product['name'] ?>" class="item-image"
        width="400" height="500">

        <!-- 商品名等の表示 -->
        <div class="item-details">
            <p class="item-title"><?= $product['name'] ?></p>
            <p class="item-meta"><?= $product['author_name'] ?></p>
            <p><?= $product['format_name'] ?></p>
            <?php if($product['format_id'] == 2): ?>
                <p>数量：<?= $product['count'] ?></p>
            <?php endif; ?>
            <p class="price"><?= $subtotal ?>円（税込）</p>
        </div>

        <!-- 削除ボタンの表示(条件分岐で表示させるか決める) -->
        <?php if($show_delete): ?>
            <a href="cart/cart_delete.php?id=<?= $id ?>">
                <img src="image/delete.png" alt="削除" class="delete">
            </a>
        <?php endif; ?>
    </div>
<?php } ?>

<!-- 合計金額の表示 -->
<?php if($show_total): ?>
    <h2 class="right">合計：<?= $total ?>円</h2>
<?php endif; ?>