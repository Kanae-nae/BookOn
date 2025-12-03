<?php
session_start();
require 'common/db-connect.php';

// 1. ログイン確認
// ログインしていない人がURLを直接叩いても削除できないようにします
if (!isset($_SESSION['user']['user_id'])) {
    // ログイン画面へ飛ばすか、処理を中断
    header('Location: g8_favorite.php');
    exit();
}

// 2. IDが送られてきているか確認
if (isset($_GET['id'])) {
    
    $favorite_id = $_GET['id'];
    $user_id = $_SESSION['user']['user_id']; // 自分のID

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 3. 削除実行
        // ★重要: 「favorite_idが一致」かつ「自分のuser_idである」データだけを削除します。
        // これにより、他人のデータを勝手に消せないようにします。
        $sql = "DELETE FROM favorite WHERE favorite_id = :fav_id AND user_id = :user_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':fav_id', $favorite_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

    } catch (PDOException $e) {
        echo "DB Error: " . $e->getMessage();
        exit();
    }
}

// 4. 処理が終わったらお気に入り一覧に戻る
header('Location: g8_favorite.php');
exit();
?>