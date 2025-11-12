<?php require 'common/header_detail.php'; ?>
<!-- 注文完了画面(G15) -->
<?php require 'common/db-connect.php'; ?>

<?php
// DBに購入情報を入れこむ
try {
    // DBに入れ込む準備
    $pdo = new PDO($connect, USER, PASS);
    $order_date = date('Y-m-d H:i:s');

    // orderテーブルへの挿入
    $sql = "INSERT INTO orders VALUES (null, :user_id, :order_date, :total_price, :payment_id, :status_id, :zip_code, :prefecture, :city, :town, :street_number, :building_name)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':user_id', htmlspecialchars($_SESSION['user']['user_id']), PDO::PARAM_INT);
    $stmt->bindValue(':order_date', $order_date, PDO::PARAM_STR);
    $stmt->bindValue(':total_price', htmlspecialchars($_SESSION['order']['total_price']), PDO::PARAM_INT);
    $stmt->bindValue(':payment_id', htmlspecialchars($_SESSION['order']['payment_id']), PDO::PARAM_INT);
    $stmt->bindValue(':status_id', 1, PDO::PARAM_INT);
    $stmt->bindValue(':zip_code', htmlspecialchars($_SESSION['order']['zip_code']), PDO::PARAM_INT);
    $stmt->bindValue(':prefecture', htmlspecialchars($_SESSION['order']['prefecture']), PDO::PARAM_STR);
    $stmt->bindValue(':city', htmlspecialchars($_SESSION['order']['city']), PDO::PARAM_STR);
    $stmt->bindValue(':town', htmlspecialchars($_SESSION['order']['town']), PDO::PARAM_STR);
    $stmt->bindValue(':street_number', htmlspecialchars($_SESSION['order']['street_number']), PDO::PARAM_STR);
    $stmt->bindValue(':building_name', htmlspecialchars($_SESSION['order']['building_name']), PDO::PARAM_STR);

    $stmt->execute();

    // order_idの取得(order_itemsテーブルで使用)
    $order_id = $pdo->lastInsertId();

    // order_itemsテーブルへの挿入
    foreach($_SESSION['product'] as $id => $product) {
        $sql = "INSERT INTO order_items VALUES (null, :order_id, :product_id, :quantity, :price, :format_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', htmlspecialchars($id), PDO::PARAM_INT);
        $stmt->bindValue(':quantity', htmlspecialchars($product['count']), PDO::PARAM_INT);
        $stmt->bindValue(':price', htmlspecialchars($product['price']), PDO::PARAM_INT);
        $stmt->bindValue(':format_id', htmlspecialchars($product['format_id']), PDO::PARAM_INT);

        $stmt->execute();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

// 一時的に保管していた購入情報とカートの中身の削除
unset($_SESSION['order']);
unset($_SESSION['product']);
?>

<main>
    <div class="center">
        <h1>注文完了</h1>
        <img src="image/flow4.png" alt="購入フロー" class="flow">
        <p>注文が完了しました。</p>
        <button id="toSame" class="order-btn">トップに戻る</button>
    </div>
</main>

<script>
// 違うページに飛ばす
document.getElementById('toSame').addEventListener('click', function () {
    location.href = 'index.php';
});
</script>

<?php require 'common/footer.php'; ?>