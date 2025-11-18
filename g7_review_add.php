<!-- レビュー登録画面(G7) -->
 <?php
// ヘッダーの読み込み
require 'common/header.php';

// 今年の年を取得
$current_year = date('Y');
// 2000年から現在年までの年の配列を作成
$years = range($current_year, 2000);
// 月と日の配列を作成
$months = range(1, 12);
$days = range(1, 31);
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

    <section class="review-item-info">
        <h2>チェンソーマン 22巻</h2>
        <div class="item-details">
            <img src="image/che-n22.jpg" alt="チェンソーマン 22巻" class="item-cover">

            <div class="item-inputs">
                <div class="input-group score-group">
                    <label for="score">スコア</label>
                    <div class="star-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star empty">★</span>
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
        <textarea id="review-text" name="review-text" rows="5">面白かった！迫力満点！
バトルシーンが良かったし、続きが気になる。</textarea>
    </section>

    <button type="submit" class="submit-btn">登録する</button>
</div>

</body>
</html>

<?php
require 'common/menu.php';
require 'common/footer.php';
?>