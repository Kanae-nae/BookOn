<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../common/db-connect.php';

// ★★★ データベース接続情報と接続処理 ★★★
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // INSERT文の準備
    $sql = "DELETE FROM review WHERE review_id = :review_id";
    
    // パラメータのバインド、実行
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':review_id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $message = 'レビューを削除しました。';
        
} catch (PDOException $e) {
    $message = 'エラーが発生しました: ' . $e->getMessage();
}

echo '<script>';
echo 'alert(' . json_encode($message) . ');';
echo 'history.back();';
echo '</script>';
exit;
?>