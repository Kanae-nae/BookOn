<?php require 'common/header_detail.php'; ?>
<!-- 決済情報画面(G13) -->
<main>
    <button id="toSame" class="btn back-btn">＜　戻る</button>
    <br>

    <div class="center">
        <h1>決済情報</h1>
        <img src="image/flow2.png" alt="購入フロー" class="flow">

        <!-- 決済情報の選択 -->
        <form action="g14_check.php" method="post">
            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment" value="credit" required>
                    クレジットカード
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment" value="convenience">
                    コンビニ払い
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment" value="paypay">
                    PayPay
                </label>
            </div>
            <input type="submit" value="注文確認へ" class="btn order-btn">
        </form>
    </div>
</main>

<!-- 違うページに飛ばす処理 -->
<?php
// 紙書籍か判断する処理(これで「前のページ」を判別する)
foreach($_SESSION['product'] as $id => $product) {
    // 紙書籍かどうか判断(紙書籍の場合はTrue)
    if($product['format_id'] == 2){
        $paper = true;
    }
}

// 判断した上で飛ばすページを変更
if ($paper) {
    $target_url = "g12_address.php";
} else {
    $target_url = "g11_mycart.php";
}
?>

<script>
    // PHPの変数をJSに渡す
    var targetUrl = "<?php echo $target_url; ?>";

    // 実際にページを飛ばす処理
    document.getElementById("toSame").addEventListener("click", function() {
        window.location.href = targetUrl;
    });
</script>

<?php require 'common/footer.php'; ?>