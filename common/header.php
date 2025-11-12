<?php session_start(); ?>

<!-- ユーザー側ヘッダー -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK ON</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/favorite.css">
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php">
            <img src="image/logo.png" alt="BOOK ON Logo" style="height: 40px;">
        </a>
    </div>

    <?php if(isset($_SESSION['user'])){ ?>
        <!-- ログイン時の処理(ログアウト) -->
        <a href="common/logout.php" class="logout-btn">
            <span>[→</span>
            <span>
                ログアウト
            </span>
        </a>
    <?php } else { ?>
        <!-- ログアウト時の処理(ログイン) -->
        <a href="g4_login_input.php" class="login-btn">
            <span>→]</span>
            <span>
                会員登録<br>ログイン
            </span>
        </a>

        <!-- ログアウトボタンの確認用(普段はコメントアウト) -->
        <!-- <a href="" class="logout-btn">
            <span>[→</span>
            <span>
                ログアウト
            </span>
        </a> -->
    <?php } ?>
</header>