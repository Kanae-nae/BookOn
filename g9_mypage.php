<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ｜BOOK ON</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ヘッダー -->
    <?php include('common/header.php'); ?>

    <main class="mypage">

        <!-- プロフィール部分 -->
        <section class="profile">
            <div class="profile-top">
                <img src="image/user_icon.png" alt="プロフィール画像" class="profile-icon">
                <div class="profile-info">
                    <div class="profile-row">
                        <h2 class="username">k a n a e</h2>
                        <button class="btn-edit">会員情報を変更</button>
                    </div>
                    <div class="profile-stats">
                        <span>レビュー <b>4</b></span>　
                        <span>購入履歴 <b>5</b></span>
                    </div>
                </div>
            </div>
            <p class="profile-comment">
                バトル漫画が好き！最近はチェンソーマンにめちゃくちゃハマってます！
            </p>
        </section>

        <!-- 切り替えボタン -->
        <div class="switch-btns">
            <button id="btn-review" class="active">レビュー</button>
            <button id="btn-history">購入履歴</button>
        </div>

        <!-- レビュー表示領域 -->
        <section id="mypage-content">
            <?php include('common/mypage_review.php'); ?>
        </section>

    </main>

    <!-- メニュー・フッター -->
    <?php include('common/menu.php'); ?>
    <?php include('common/footer.php'); ?>

    <script>
        const reviewBtn = document.getElementById('btn-review');
        const historyBtn = document.getElementById('btn-history');
        const content = document.getElementById('mypage-content');

        reviewBtn.addEventListener('click', () => {
            reviewBtn.classList.add('active');
            historyBtn.classList.remove('active');
            fetch('common/mypage_review.php')
                .then(res => res.text())
                .then(html => content.innerHTML = html);
        });

        historyBtn.addEventListener('click', () => {
            historyBtn.classList.add('active');
            reviewBtn.classList.remove('active');
            fetch('common/mypage_purchase.php')
                .then(res => res.text())
                .then(html => content.innerHTML = html);
        });
    </script>

</body>
</html>
