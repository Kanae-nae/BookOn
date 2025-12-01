<?php require 'common/header.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'common/db-connect.php';

// デバッグ用
if(!empty($_POST)){
    var_dump($_POST);
}

// 今年の年を取得
$current_year = date('Y');
// 2000年から現在年までの年の配列を作成
$years = range($current_year, 2000);
// 月と日の配列を作成
$months = range(1, 12);
$days = range(1, 31);

try {
    // ★★★ データベース接続情報と接続処理 ★★★
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ★★★ 情報の取得 ★★★
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT products.* FROM products
    WHERE products.product_id = :product_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // --- データ整形エリア ---

    if($row) {
        // 商品名の作成 (シリーズ名 + 半角スペース + 巻数)
        $product_name = $row['series_name'] . " " . strval($row['volume_number']) . "巻";
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
        <a href="#" onclick="history.back(); return false;" class="back-btn">
            <svg class="back-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            戻る
        </a>
        <h1>レビュー登録</h1>
    </div>

    <form action="review/review_insert.php?id=<?= $row['product_id'] ?>" method="POST"> 
    
    <section class="review-item-info">
        <h2><?= $product_name ?></h2>
        <div class="item-details">
            <img src="<?= $row['product_img_url'] ?>" alt="<?= $product_name ?>" class="item-cover">

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
                        <select name="year" required>
                            <option value="">年</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="month" required>
                            <option value="">月</option>
                            <?php foreach ($months as $month): ?>
                                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="day" required>
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

<?php
    } else {
        // 商品が見つからなかった場合
        echo '<p>該当する商品が見つかりませんでした。</p>';
    }
} catch(PDOException $e) {
    echo '<p>データベースエラーが発生しました。</p>' . $e;
}
?>

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