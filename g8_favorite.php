<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お気に入り - BOOK ON</title>
<link rel="stylesheet" href="css/g8.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include 'common/header.php'; ?>
<main class="favorite-main">
<h2 class="page-title">お気に入り</h2>
<div class="fav-list">
<div class="fav-item">
<div class="fav-check">
<input type="checkbox" id="check1" class="custom-checkbox" checked>
<label for="check1" class="check-label"></label>
</div>
<div class="fav-image">
<img src="image/kimetsu_23.jpg" alt="鬼滅の刃 23" onerror="this.src='image/tyen.png'">
</div>
<div class="fav-details">
<div class="fav-title">鬼滅の刃　23</div>
<div class="fav-author">吾峠呼世晴</div>
<div class="fav-rating">
<span class="stars">
<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
</span>
<span class="rating-text">4.0</span>
<span class="review-count">(5件)</span>
</div>
<div class="fav-meta">
<span>シリーズ <a href="#" class="meta-link">鬼滅の刃</a></span>
<span>ジャンル <a href="#" class="meta-link">バトル・アクション</a></span>
</div>
<div class="fav-date">2023年12月4日 発売</div>
<div class="fav-type">電子書籍</div>
<div class="fav-footer">
<div class="fav-price">543円 <span class="price-tax">(税込)</span></div>
<div class="fav-delete">
<i class="far fa-trash-alt delete-icon"></i>
<span class="delete-text">削除</span>
</div>
</div>
</div>
</div>
<div class="fav-item">
<div class="fav-check">
<input type="checkbox" id="check2" class="custom-checkbox">
<label for="check2" class="check-label"></label>
</div>
<div class="fav-image">
<img src="image/mune.jpg" alt="胸が鳴るのは君のせい 1">
</div>
<div class="fav-details">
<div class="fav-title">胸が鳴るのは君のせい　１</div>
<div class="fav-author">紺野りさ</div>
<div class="fav-rating">
<span class="stars">
<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
</span>
<span class="rating-text">4.0</span>
<span class="review-count">(5件)</span>
</div>
<div class="fav-meta">
<span>シリーズ <a href="#" class="meta-link">胸が鳴るのは君のせい</a></span>
<span>ジャンル <a href="#" class="meta-link">恋愛</a></span>
</div>
<div class="fav-date">2021年5月7日 発売</div>
<div class="fav-type">紙書籍（<span style="color:green;">●</span>在庫あり）</div>
<div class="qty-select">

                        数量
<select>
<option>1</option>
<option>2</option>
</select>
</div>
<div class="fav-footer">
<div class="fav-price">572円 <span class="price-tax">(税込)</span></div>
<div class="fav-delete">
<i class="far fa-trash-alt delete-icon"></i>
<span class="delete-text">削除</span>
</div>
</div>
</div>
</div>
</div>
</main>
<div class="bottom-action-area">
<span class="selection-count">1件選択中</span>
<button class="btn-add-cart">カートに入れる</button>
</div>
<?php include 'common/menu.php'; ?>
<?php include 'common/footer.php'; ?>
</body>
</html>
