<?php session_start(); ?>
<!-- 在庫の確認処理 -->
<?php require 'common/db-connect.php'; ?>

<?php
$pdo = new PDO($connect, USER, PASS);
$cart = $_SESSION['product'] ?? [];

$insufficient = [];

foreach ($cart as $id => $p) {
    if ($p['format_id'] != 2) continue; // 電子書籍は在庫管理不要

    $stmt = $pdo->prepare("SELECT stocks FROM products WHERE product_id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['stocks'] < $p['count']) {
        $insufficient[] = [
            'product_id' => $id,
            'name' => $p['name'],
            'stock' => $row['stocks'],
            'count' => $p['count']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>在庫確認</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/detail.css">
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const insufficient = <?php echo json_encode($insufficient, JSON_UNESCAPED_UNICODE); ?>;

        if (insufficient.length > 0) {
            let msg = "以下の商品は在庫が不足しています：\n\n";
            insufficient.forEach(item => {
                msg += `・${item.name}（在庫: ${item.stock} / 必要: ${item.count}）\n`;
            });
            msg += "\n在庫が足りない商品をスキップして購入を続けますか？";

            const result = confirm(msg);

            if (result) {
                // 「はい」→ スキップして購入
                document.getElementById('skip-form').submit();
            } else {
                // 「いいえ」→ 購入キャンセル（カート画面に戻す）
                alert("購入をキャンセルしました。");
                window.location.href = "g11_mycart.php";
            }
        } else {
            // 在庫が普通にある場合
            document.getElementById('normal-form').submit();
        }
    });
    </script>
</head>

<body>
    <h2>在庫確認中...</h2>
    <p>しばらくお待ちください。</p>

    <!-- 通常購入 -->
    <form id="normal-form" action="g15_finish.php" method="post">
        <input type="hidden" name="mode" value="normal">
    </form>

    <!-- 在庫不足スキップ購入 -->
    <form id="skip-form" action="g15_finish.php" method="post">
        <input type="hidden" name="mode" value="skip">
    </form>
<?php require 'common/footer.php'; ?>