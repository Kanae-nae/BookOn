<?php require 'common/header.php'; ?>
<!-- マイカート画面(G11) -->
<link rel="stylesheet" href="css/detail.css">

<main style="padding: 20px;">
    <h1>マイカート画面</h1>
    <?php
    if(!empty($_SESSION['product'])) {
        $total = 0;
        $paper = false;
        foreach($_SESSION['product'] as $id => $product) {
            $subtotal = $product['price'] * $product['count'];
            $total += $subtotal;
            // 紙書籍かどうか判断(紙書籍の場合はTrue)
            if($product['format_id'] == 2){
                $paper = true;
            }
    ?>
            <!-- 画像の表示 -->
            <div>
                <img src="<?= $product['product_img_url'] ?>" alt="<?= $product['name'] ?>"
                width="400" height="500">
            </div>
            <!-- 商品名等の表示 -->
            <p><?= $product['name'] ?></p>
            <p><?= $product['author_name'] ?></p>
            <p><?= $product['format_name'] ?></p>
            <p>数量：<?= $product['count'] ?></p>
            <p class="red"><?= $subtotal ?>円（税込）</p>
            <p><a href="cart/cart_delete.php?id=<?= $id ?>">削除</a></p>
            <hr>
        <?php } ?>
            <h2 class="right">合計：<?= $total ?>円</h2>
        <!-- 紙書籍の場合は配送情報、電子書籍の場合は決済情報へ -->
        <?php if($paper == true){ ?>
            <a href="g12_address.php">購入手続きへ進む</a><br>
        <?php } else { ?>
            <a href="g13_payment.php">購入手続きへ進む</a><br>
            <p>※デジタル商品のみのため、配送情報は飛ばされます。</p>
        <?php } ?>
        <a href="index.php">買い物を続ける</a>
    <?php
    } else {
        echo 'カートに商品がありません。';
    }
    ?>
</main>

<?php require 'common/menu.php'; ?>
<?php require 'common/footer.php'; ?>