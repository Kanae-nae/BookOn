<?php
session_start();
$view = isset($_GET['view']) ? $_GET['view'] : 'review';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ | BOOK ON</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/g9.css">
    <?php if ($view === 'review'): ?>
        <link rel="stylesheet" href="css/g9_review.css">
    <?php else: ?>
        <link rel="stylesheet" href="css/g9_purchase.css">
    <?php endif; ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include("common/header.php"); ?>

    <main>
        <!-- ===============================
             プロフィールセクション
        =============================== -->
        <section class="profile">
            <div class="profile-container">
                <div class="profile-top">
                    <img src="image/sample_user.png" alt="ユーザーアイコン" class="profile-icon">

                    <div class="profile-info">
                        <div class="profile-header">
                            <h2 class="profile-name">kanae</h2>
                            <button class="btn-profile-edit">会員情報を変更</button>
                        </div>

                        <div class="profile-stats">
                            <div><span>4</span><span>レビュー</span></div>
                            <div><span>5</span><span>購入履歴</span></div>
                        </div>
                    </div>
                </div>
                <p class="profile-comment">
                    バトル漫画が好き！最近はチェンソーマンにめちゃくちゃハマってます！
                </p>
            </div>
        </section>

        <!-- ===============================
             レビュー・購入履歴 切り替え
        =============================== -->
        <div class="switch-btns">
            <a href="?view=review" class="switch-btn <?php echo ($view === 'review') ? 'active' : ''; ?>">
                <i class="fa-solid fa-pen"></i> レビュー
            </a>
            <a href="?view=purchase" class="switch-btn <?php echo ($view === 'purchase') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clock"></i> 購入履歴
            </a>
        </div>

        <!-- ===============================
             コンテンツ切り替え
        =============================== -->
        <section class="mypage-content">
            <?php
                if ($view === 'review') {
                    include("common/mypage_review.php");
                } else {
                    include("common/mypage_purchase.php");
                }
            ?>
        </section>

        <!-- ===============================
             並び替えボタン（スマホ対応）
        =============================== -->
        <div class="sort-dropdown">
            <button id="sort-btn" class="sort-button">
                <i class="fa-solid fa-arrow-down-short-wide"></i>
            </button>
            <div id="sort-options" class="dropdown-content">
                <a href="?view=review&sort=new">投稿日：新しい順</a>
                <a href="?view=review&sort=old">投稿日：古い順</a>
                <a href="?view=review&sort=high">評価：高い順</a>
                <a href="?view=review&sort=low">評価：低い順</a>
            </div>
        </div>
    </main>

    <?php include("common/menu.php"); ?>
    <?php include("common/footer.php"); ?>

    <script>
        // スマホでもドロップダウン操作できるよう最小限に留めたJS
        const sortBtn = document.getElementById("sort-btn");
        const sortOptions = document.getElementById("sort-options");
        sortBtn.addEventListener("click", () => {
            sortOptions.classList.toggle("show");
        });
        document.addEventListener("click", (e) => {
            if (!sortBtn.contains(e.target)) sortOptions.classList.remove("show");
        });
    </script>
</body>
</html>
