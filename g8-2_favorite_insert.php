<?php
session_start();
require 'common/db-connect.php'; // DB接続

// 1. ログインチェック (セッション名は user に統一)
if (!isset($_SESSION['user']['user_id'])) {
    // ログインしていない場合
    echo "ログインが必要です。";
    exit();
}

$user_id = $_SESSION['user']['user_id'];
$product_id = $_POST['product_id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. すでにお気に入りに登録済みかチェック
    $sql_check = "SELECT COUNT(*) FROM favorite WHERE user_id = :user_id AND product_id = :product_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_check->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    // 3. まだ登録されていなければ INSERT する
    if ($count == 0) {
        $sql_insert = "INSERT INTO favorite (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_insert->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $stmt_insert->execute();
    }

    // 4. 元の商品詳細ページに戻る
    header("Location: g2_detail.php?id=" . $product_id);
    exit();

} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
    exit();
}
?>