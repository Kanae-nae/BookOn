<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'common/header.php';

// ★★★ データベース接続情報と接続処理を追加 ★★★
const SERVER = 'mysql323.phy.lolipop.lan';
const DBNAME = 'LAA1658836-bookon';
const USER = 'LAA1658836';
const PASS = 'passbookon';
$connect = 'mysql:host='. SERVER .';dbname='. DBNAME .';charset=utf8';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "接続成功"; // デバッグ用
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}
// ★★★ データベース接続情報と接続処理はここまで ★★★


// 今年の年を取得
$current_year = date('Y');
// 2000年から現在年までの年の配列を作成
$years = range($current_year, 2000);
// 月と日の配列を作成
$months = range(1, 12);
$days = range(1, 31);


// ★★★ フォーム送信処理を追加 ★★★
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された値を取得
    // user_idとproduct_idは、ここでは仮の値 (実際のECサイトではセッションやURLパラメータから取得)
    $user_id = 1; // 仮のユーザーID
    $product_id = 100; // 仮の商品ID（「チェンソーマン 22巻」に対応）

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
            // INSERT文の準備
            $sql = "INSERT INTO review (product_id, user_id, rating, comment, created_at, updated_at, view_date) 
                    VALUES (:product_id, :user_id, :rating, :comment, NOW(), NOW(), :view_date)";
            
            $stmt = $pdo->prepare($sql);
            
            // パラメータのバインド
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_STR); // ratingはDECIMAL型のためSTRでバインド
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':view_date', $view_date, $view_date === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            
            // 実行
            $stmt->execute();
            
            $success_message = 'レビューを正常に登録しました。';
            
            // 登録成功後のリダイレクト処理などを追加しても良い
            // header('Location: success_page.php');
            // exit;
            
        } catch (PDOException $e) {
            $error_message = 'レビュー登録中にエラーが発生しました: ' . $e->getMessage();
        }
    } else {
        $error_message = 'スコアが正しく入力されていません。';
    }
}
// ★★★ フォーム送信処理はここまで ★★★
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー登録</title>
    <link rel="stylesheet" href="css/g7.css">
</head>
<body>

<div class="review-container">
    <div class="review-header">
        <a href="#" class="back-btn">
            <svg class="back-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            戻る
        </a>
        <h1>レビュー登録</h1>
    </div>

    <?php if (!empty($success_message)): ?>
        <p style="color: green; font-weight: bold;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="" method="POST"> 
    
    <section class="review-item-info">
        <h2>チェンソーマン 22巻</h2>
        <div class="item-details">
            <img src="image/che-n22.jpg" alt="チェンソーマン 22巻" class="item-cover">

            <div class="item-inputs">
                <div class="input-group score-group">
                    <label for="score">スコア</label>
                    <div class="star-rating">
                        <div id="filled-stars" class="filled-stars"></div>
                    </div>                    
                    <select id="score" name="score"> 
                        <option value="5.0">5.0</option>
                        <option value="4.5">4.5</option>
                        <option value="4.0" selected>4.0</option>
                        <option value="3.5">3.5</option>
                        <option value="3.0">3.0</option>
                        <option value="2.5">2.5</option>
                        <option value="2.0">2.0</option>
                        <option value="1.5">1.5</option>
                        <option value="1.0">1.0</option>
                        <option value="0.5">0.5</option>
                    </select>
                </div>

                <div class="date-group-wrapper"> 
                    <label class="date-label">鑑賞日</label>
                    <div class="date-selects">
                        <select name="year">
                            <option value="">年</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="month">
                            <option value="">月</option>
                            <?php foreach ($months as $month): ?>
                                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="day">
                            <option value="">日</option>
                            <?php foreach ($days as $day): ?>
                                <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                </div>
        </div>
    </section>

    <section class="review-content">
        <label for="review-text">レビュー内容（無記入でも投稿可）</label>
        <textarea id="review-text" name="review-text" rows="5"></textarea>
    </section>

    <button type="submit" class="submit-btn">登録する</button>
    
    </form> 
    </div>
<script>
    // 必要な要素を取得
    const scoreSelect = document.getElementById('score');
    const filledStars = document.getElementById('filled-stars');

    // スコアに基づいて星の幅（％）を計算し、CSSを更新する関数
    function updateStars(score) {
        // スコア (例: 4.0) を5で割ってパーセントを計算 (例: 4.0 / 5 * 100 = 80%)
        const percentage = (parseFloat(score) / 5) * 100;
        
        // filled-stars の幅を更新
        filledStars.style.width = percentage + '%';
    }

    // 1. ページ読み込み時の初期表示を設定
    // selectで選択されている値を取得
    const initialScore = scoreSelect.value;
    updateStars(initialScore);

    // 2. select ボックスの値が変更されたときに星を更新するイベントリスナーを設定
    scoreSelect.addEventListener('change', (event) => {
        const newScore = event.target.value;
        updateStars(newScore);
    });

</script>
<?php
require 'common/menu.php';
require 'common/footer.php';
?>
</body>
</html>

