<?php require 'common/header_detail.php'; ?>
<!-- 注文完了画面(G15) -->
<link rel="stylesheet" href="css/favorite.css">
<?php require 'common/db-connect.php'; ?>

<?php
// 事前準備
$message = "注文が完了しました。";
$cart = $_SESSION['product'] ?? [];
$mode = $_POST['mode'] ?? 'normal';

// DBに購入情報を入れこむ
try {
     // DBに入れ込む準備
    $pdo = new PDO($connect, USER, PASS);
    $order_date = date('Y-m-d H:i:s');

    // トランザクション開始
    $pdo->beginTransaction();

    // 在庫を減らす処理
    foreach ($cart as $id => $p) {
        $product_id = (int)$id; // 明示的に整数型に変換
        if ($p['format_id'] != 2) continue; // 電子書籍は在庫処理なし

        $stmt = $pdo->prepare("SELECT stocks FROM products WHERE product_id = :id FOR UPDATE");
        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $stock = $stmt->fetchColumn();

        if ($stock === false) continue;

        if ($mode === 'skip' && $stock < $p['count']) {
            // skipモード時は不足商品をスキップ
            $sub = $_SESSION['product'][$id]['price'] * $_SESSION['product'][$id]['count'];
            $newtotal = $_SESSION['order']['total_price'] - $sub;
            $_SESSION['order']['total_price'] = $newtotal;
            unset($_SESSION['product'][$id]);
            continue;
        }

        if ($stock < $p['count']) {
            // 通常モードで在庫不足 → 例外で処理中断
            throw new Exception("商品ID {$product_id} の在庫が不足しています。");
        }

        $update = $pdo->prepare("UPDATE products SET stocks = stocks - :count WHERE product_id = :id");
        $update->bindValue(':count', $p['count'], PDO::PARAM_INT);
        $update->bindValue(':id', $product_id, PDO::PARAM_INT);
        $update->execute();
    }

    // カートの内容を再取得(unsetで削除された商品を除外)
    $cart = $_SESSION['product'] ?? [];

    // カート内の全商品がDBに存在するか確認
    foreach ($cart as $id => $p) {
        $product_id = (int)$id; // 整数型にキャスト
        if ($product_id === 0) { // 空キー等のガード
            unset($_SESSION['product'][$id]);
            continue;
        }

        $check = $pdo->prepare("SELECT product_id FROM products WHERE product_id = :id");
        $check->bindValue(':id', $product_id, PDO::PARAM_INT);
        $check->execute();

        if ($check->fetchColumn() === false) {
            // 商品が存在しない場合は削除
            $sub = $p['price'] * $p['count'];
            $_SESSION['order']['total_price'] -= $sub;
            unset($_SESSION['product'][$id]);
        }
    }

    // 再度カート内容を取得
    $cart = $_SESSION['product'] ?? [];

    // $_SESSIONのproductが無い場合は購入処理を行わない
    if(!empty($_SESSION['product'])) {

        // 紙書籍あり→電子書籍のみになった場合は送料を削除
        $paper = false;
        foreach($_SESSION['product'] as $id => $product) {
            if($product['format_id'] == 2){
                $paper = true;
            }
        }
        if($_SESSION['order']['paper'] == true && $paper == false){
            $_SESSION['order']['total_price'] -= 500;
        }

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
            $product_id = (int)$id;

            $sql = "INSERT INTO order_items VALUES (null, :order_id, :product_id, :quantity, :price, :format_id)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', htmlspecialchars($product['count']), PDO::PARAM_INT);
            $stmt->bindValue(':price', htmlspecialchars($product['price']), PDO::PARAM_INT);
            $stmt->bindValue(':format_id', htmlspecialchars($product['format_id']), PDO::PARAM_INT);

            $stmt->execute();
        }
    } else {
        unset($_SESSION['product']);
        $message = "購入可能な商品が無いため、購入処理はキャンセルされました。";
    }

    // すべて成功したらコミット
    $pdo->commit();

} catch (Exception $e) {
    // 失敗時はロールバック
    $pdo->rollBack();
    $message = "注文処理に失敗しました。" . $e->getMessage();
}

// 一時的に保管していた購入情報とカートの中身の削除
$total_price = $_SESSION['order']['total_price'];
unset($_SESSION['order']);
?>

<main>
    <div class="center">
        <h1>注文完了</h1>
        <img src="image/flow4.png" alt="購入フロー" class="flow">
        <p><?= $message ?></p>
        <?php
        if(!empty($_SESSION['product'])){
            echo "<p>購入完了した商品</p>";
            require "cart/product.php";
            ?>
            <h1 class="right red">合計：<?= $total_price ?>円</h1>
        <?php
        } 
        unset($_SESSION['product']);
        ?>
        <button id="toSame" class="btn order-btn">トップに戻る</button>
    </div>
</main>

<script>
    // 違うページに飛ばす
    document.getElementById('toSame').addEventListener('click', function () {
        location.href = 'index.php';
    });
</script>

<?php require 'common/footer.php'; ?>