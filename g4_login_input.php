<!-- ログイン画面(G4)の入力側 -->

<!-- ヘッダーが他と違うため直接記載 -->
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
</header>

<main style="padding: 20px;">
    <h1>会員登録・ログイン</h1>
    <h2>新規会員登録の方</h2>
    <a href="/bookon/g5_signup_input.php">会員登録</a>
    <h2>アカウントをお持ちの方</h2>
    <form action="" method="post">
        メールアドレス<input type="text" name="mail"><br>
        パスワード<input type="password" name="pass"><br>
        <input type="submit" value="ログイン">
    </form>
</main>

<?php include 'common/footer.php'; ?>