<?php require 'common/header_detail.php'; ?>
<script>document.title = '確認画面 - BOOK ON';</script>
<!-- 注文確認画面(G14) -->
<link rel="stylesheet" href="css/favorite.css">

<?php
switch ($_POST['payment']) {
    case 'credit':
        $payment_id = 1;
        $payment_name = "クレジットカード";
        break;
    case 'convenience':
        $payment_id = 2;
        $payment_name = "コンビニ払い";
        break;
    case 'paypay':
        $payment_id = 3;
        $payment_name = "PayPay";
        break;
}

// 電子書籍の場合も、一応DBに配送情報は入れる
if(empty($_SESSION['order'])){
    $_SESSION['order'] = [
    'zip_code'          => $_SESSION['user']['zip_code'],
    'prefecture'        => $_SESSION['user']['prefecture'],
    'city'              => $_SESSION['user']['city'],
    'town'              => $_SESSION['user']['town'],
    'street_number'     => $_SESSION['user']['street_number'],
    'building_name'     => $_SESSION['user']['building_name'],
    'payment_id' => $payment_id
    ];
} else {
    $_SESSION['order']['payment_id'] = $payment_id;
}
?>

<main>
    <h1>確認画面</h1>

    <img src="image/flow3.png" alt="購入フロー" class="flow">
    <h2>商品</h2>
    <?php
    // 商品の表示
    require "cart/product.php";
    // 紙書籍の場合は配送先を表示＆配送料を入れる
    if($paper == true){
        $_SESSION['order']['paper'] = true;
        $total += 500;
    ?>
        <br>
        <h2>配送先</h2>
        <!-- 郵便番号 -->
        <p>〒<?= $_SESSION['order']['zip_code'] ?></p>
        <!-- 住所1つ目(都道府県～番地) -->
        <p><?= $_SESSION['order']['prefecture'].$_SESSION['order']['city'].
        $_SESSION['order']['town'].$_SESSION['order']['street_number'] ?></p>
        <!-- 住所2つ目(建物名・部屋番号) -->
        <p><?= $_SESSION['order']['building_name'] ?></p>
        <hr>
        <h2 class="right">送料：500円</h2>
    <?php } ?>

    <?php $_SESSION['order']['total_price'] = $total; ?>
    <br>

    <h2>決済情報</h2>
    <p><?= $payment_name ?></p>
    <hr>

    <h1 class="right red">合計：<?= $total ?>円</h1>

    <!-- 通常購入 -->
    <form id="normal-form" action="g15_finish.php" method="post">
        <input type="hidden" name="mode" value="normal">
    </form>

    <!-- 在庫不足分をスキップして購入 -->
    <form id="skip-form" action="g15_finish.php" method="post">
        <input type="hidden" name="mode" value="skip">
    </form>

    <button id="submit" class="btn order-btn">
        注文確定する
    </button>
    <button id="toSame" class="btn cart-btn">
        カートに戻る
    </button>
</main>

<!-- 注文確定のページ遷移 -->
<script>
    document.getElementById("submit").addEventListener("click", function() {
        location.href = 'g14_checkstock.php';
    });
</script>

<!-- カートに戻るボタンの処理(JavaScript) -->
<script src="script/order_unset.js"></script>

<?php require 'common/footer.php'; ?>