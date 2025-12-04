<?php require 'common/header.php'; ?>
<!-- マイカート画面(G11) -->
<script>document.title = 'マイカート - BOOK ON';</script>
<link rel="stylesheet" href="css/favorite.css">
<link rel="stylesheet" href="css/detail.css">

<main>
    <h1>マイカート画面</h1>
    <?php
    if(!empty($_SESSION['product'])) {
        // 商品の表示
        require 'cart/product.php';
        ?>
        <!-- 紙書籍の場合は配送情報、電子書籍の場合は決済情報へ -->
        <button id="submit" class="btn order-btn">購入手続きへ進む</button>
        <?php
        // 紙書籍か電子書籍かで飛ばすページを変更
        if ($paper) {
            $target_url = "g12_address.php";
        } else {
            $target_url = "g13_payment.php";
            echo '<p class="center">※デジタル商品のみのため、配送情報は飛ばされます。</p>';
        }
        ?>
        <button id="toSame" class="btn cart-btn">買い物を続ける</button>
    <?php
    } else {
        echo '<p class="center">カートに商品がありません。</p>';
    }
    ?>
</main>

<script>
    // PHPの変数をJSに渡す
    var targetUrl = "<?php echo $target_url; ?>";

    // 実際にページを飛ばす処理
    document.getElementById("submit").addEventListener("click", function() {
        window.location.href = targetUrl;
    });

    document.getElementById('toSame').addEventListener('click', function () {
    location.href = 'index.php';
    });
</script>

<?php require 'common/menu.php'; ?>
<?php require 'common/footer.php'; ?>