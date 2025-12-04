<?php session_start(); ?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー登録 - BOOK ON</title>
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
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

// ★★★ フォーム送信処理 ★★★

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された値を取得
    $user_id = $_SESSION['user']['user_id'];
    $product_id = $_GET['id'];

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

    if ($rating !== false && $rating !== null && $user_id && $product_id) {
        try {
            // 既存レビューの確認
            $check_sql = "SELECT review_id FROM review WHERE user_id = :user_id AND product_id = :product_id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $check_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $check_stmt->execute();
        
        if ($check_stmt->fetch()) {
            // 既にレビューが登録されている場合
            $message = 'この商品には既にレビューを投稿済みです。';
            $url = '../g6_review.php?id=' . $_GET['id'];
        } else {
            // INSERT文の準備
            $sql = "INSERT INTO review (product_id, user_id, rating, comment, created_at, updated_at, view_date) 
                    VALUES (:product_id, :user_id, :rating, :comment, NOW(), NOW(), :view_date)";

            $stmt = $pdo->prepare($sql);
            
            // パラメータのバインド
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_STR); // ratingはDECIMAL型のためSTRでバインド
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':view_date', $view_date, $view_date === null ? 
            PDO::PARAM_NULL : PDO::PARAM_STR);
            
            // 実行
            $stmt->execute();
            
            $message = 'レビューを正常に登録しました。';
            $url = '../g6_review.php?id=' . $_GET['id'];
        }
            
            
        } catch (PDOException $e) {
            $message = 'レビュー登録中にエラーが発生しました: ' . $e->getMessage();
            $url = '../g7_review_add.php?id=' . $_GET['id'];
        }
    } else {
        $message = 'スコアが正しく入力されていません。';
        $url = '../g7_review_add.php?id=' . $_GET['id'];
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