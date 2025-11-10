<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>会員登録・ログイン</title>

    <link rel="stylesheet" href="css/header_only.css">

    <link rel="stylesheet" href="css/g4.css">

</head>

<body>

    <?php include 'common/header_only.php'; ?>

    <main class="login-container">

        <h1>会員登録・ログイン</h1>
        <section class="card">
            <h2>新規会員登録の方</h2>
            <a href="register.php" class="btn btn-primary">会員登録</a>
        </section>
        <section class="card">

            <h2>アカウントをお持ちの方</h2>

            <form action="login-output.php" method="post">

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" placeholder="bookon@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <ul class="password-rules">
                    <li>半角英数字を1文字以上使用</li>
                    <li>大文字、小文字の区別あり</li>
                    <li>8文字以上16文字以内</li>
                </ul>

                <button type="submit" class="btn btn-primary">ログイン</button>

            </form>
        </section>

    </main>

</body>

</html>