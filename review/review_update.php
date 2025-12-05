<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー更新 - BOOK ON</title>
    <link rel="stylesheet" href="css/header_only.css">
    <link rel="stylesheet" href="css/g4.css">
</head>

<body>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../common/db-connect.php';

// ★★★ データベース接続情報と接続処理 ★★★
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "接続成功"; // デバッグ用
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

// ★★★ フォーム送信処理 ★★★

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された値を取得
    $rating = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_FLOAT);
    $comment = filter_input(INPUT_POST, 'review-text');
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT);
    $day = filter_input(INPUT_POST, 'day', FILTER_VALIDATE_INT);
    
    // 鑑賞日の設定 (日付が選択されていなければNULL、全て揃っていればYYYY-MM-DD形式)
    $view_date = null;
    if ($year && $month && $day) {
        // 月と日が1桁の場合にゼロ埋め
        $month_str = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day_str = str_pad($day, 2, '0', STR_PAD_LEFT);
        $view_date = "{$year}-{$month_str}-{$day_str}";
    }

    if ($rating !== false && $rating !== null) {
        try {
            // UPDATE文の準備
            $sql = "UPDATE review SET rating = :rating, comment = :comment, 
            updated_at = NOW(), view_date = :view_date WHERE review_id = :review_id";
            
            $stmt = $pdo->prepare($sql);
            
            // パラメータのバインド
            $stmt->bindParam(':rating', $rating, PDO::PARAM_STR); // ratingはDECIMAL型のためSTRでバインド
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':view_date', $view_date, $view_date === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->bindParam(':review_id', $_GET['review_id'], PDO::PARAM_INT);
            
            // 実行
            $stmt->execute();
            
            $message = 'レビューを正常に更新しました。';
            $url = '../g6_review.php?id=' . $_GET['product_id'];
            
        } catch (PDOException $e) {
            $message = 'レビュー更新中にエラーが発生しました: ' . $e->getMessage();
            $url = '../g7_review_update.php?id=' . $_GET['review_id'];
        }
    } else {
        $message = 'スコアが正しく入力されていません。';
        $url = '../g7_review_update.php?id=' . $_GET['review_id'];
    }
}
// ★★★ フォーム送信処理はここまで ★★★

echo '<script>';
echo 'alert(' . json_encode($message) . ');';
echo 'location.href = ' . json_encode($url) . ';';
echo '</script>';
exit;
?>
</body>
</html>