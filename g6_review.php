<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー詳細 - BOOK ON</title>
    
    <link rel="stylesheet" href="css/g6.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <?php require 'common/header.php'; ?>

    <main class="review-page-main">
        
        <a href="g3_search.php" class="back-btn">
            <i class="fas fa-chevron-left"></i> 戻る
        </a>

        <h1 class="product-title">チェンソーマン 22巻 | ジャンプコミックス</h1>
        
        <div class="product-info-container">
            <div class="product-cover">
                <img src="image/chainsawman_22.jpg" alt="チェンソーマン 22巻" onerror="this.src='image/tyen.png'">
            </div>
            
            <div class="product-details">
                <div class="detail-row">
                    <span class="detail-label">発売日</span>
                    <span class="detail-value text-black">2025年09月04日</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">作者</span>
                    <span class="detail-value">藤本タツキ</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">シリーズ</span>
                    <span class="detail-value">チェンソーマン</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">レーベル</span>
                    <span class="detail-value">ジャンプコミックス</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">出版社</span>
                    <span class="detail-value">集英社</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ジャンル</span>
                    <span class="detail-value">バトル・アクション</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ページ数</span>
                    <span class="detail-value text-black">192p</span>
                </div>
            </div>
        </div>

        <div class="rating-display">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
            </div>
            <span class="rating-value">4.0</span>
            <a href="#" class="review-count-link">(5件)</a>
        </div>

        <div class="action-buttons">
            <a href="#" class="btn-action btn-review">
                <i class="far fa-edit"></i> レビュー
            </a>
            <a href="#" class="btn-action btn-favorite">
                <i class="fas fa-heart"></i> お気に入り
            </a>
        </div>

        <h2 class="section-title">Book Log</h2>

        <div class="review-list">
            
            <div class="review-item">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="review-content">
                    <div class="review-header">
                        <div>
                            <div class="user-name">kanae</div>
                            <span class="review-date">2025/09/07 10:24</span>
                        </div>
                        <i class="fas fa-ellipsis-h menu-dots"></i>
                    </div>
                    <div class="review-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        <span class="review-score-num">4.0</span>
                    </div>
                    <p class="review-text">
                        面白かった！迫力満点！<br>
                        バトルシーンが良かったし、続きが気になる。
                    </p>
                    <div class="read-date">
                        <i class="far fa-calendar"></i> 鑑賞日：2025/09/05
                    </div>
                </div>
            </div>

            <div class="review-item">
                <div class="user-avatar red" style="background-color: #ff6b6b;">
                    <i class="fas fa-user"></i>
                </div>
                <div class="review-content">
                    <div class="review-header">
                        <div>
                            <div class="user-name">マコト</div>
                            <span class="review-date">2025/09/04 0:00</span>
                        </div>
                        <i class="fas fa-ellipsis-h menu-dots"></i>
                    </div>
                    <div class="review-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <span class="review-score-num">5.0</span>
                    </div>
                    <p class="review-text">
                        最高でした。
                    </p>
                </div>
            </div>

        </div>
    </main>

    <div class="sort-dropdown">
        <button id="sort-btn" class="fab-refresh sort-button">
            <i class="fas fa-sync-alt"></i>
        </button>
        
        <div id="sort-options" class="dropdown-content">
            <a href="?view=review&sort=new">投稿日：新しい順</a>
            <a href="?view=review&sort=old">投稿日：古い順</a>
            <a href="?view=review&sort=high">評価：高い順</a>
            <a href="?view=review&sort=low">評価：低い順</a>
        </div>
    </div>

    <a href="#" class="fab-add">
        <i class="fas fa-plus"></i>
    </a>

    <?php include 'common/menu.php'; ?>
    <?php include 'common/footer.php'; ?>

    <script>
        document.getElementById("sort-btn").addEventListener("click", function(event) {
            // メニューの表示/非表示を切り替え
            document.getElementById("sort-options").classList.toggle("show");
            event.stopPropagation(); // クリックイベントが親に伝わらないようにする
        });

        // 画面の他の場所をクリックしたらメニューを閉じる
        window.onclick = function(event) {
            if (!event.target.matches('.sort-button') && !event.target.closest('.sort-button')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

</body>
</html>