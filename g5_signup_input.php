<!-- 新規登録画面(G5)の入力側 -->
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
    <h1>新規会員登録</h1>

    <form action="g5_signup_output.php" method="post">
        ユーザーネーム<br>
        <input type="text" name="user_name" required><br>

        メールアドレス<br>
        <input type="text" name="mail_address" required><br>

        パスワード<br>
        <input type="password" name="password" required><br>

        姓<br>
        <input type="text" name="last_name" required><br>

        名<br>
        <input type="text" name="first_name" required><br>

        セイ<br>
        <input type="text" name="last_name_kana" required><br>

        メイ<br>
        <input type="text" name="first_name_kana" required><br>

        年<br>
        <select name="year">
            <?php
            for($i=1925; $i<2026; $i++){
                echo '<option value = "'.$i.'">'.$i.'</option>';
            }
            ?>
        </select><br>

        月<br>
        <select name="month">
            <?php
            for($i=1; $i<13; $i++){
                if($i < 10){
                    $i = '0'.strval($i);
                }
                echo '<option value = "'.$i.'">'.$i.'</option>';
            }
            ?>
        </select><br>

        日<br>
        <select name="date">
            <?php
            for($i=1; $i<32; $i++){
                if($i < 10){
                    $i = '0'.strval($i);
                }
                echo '<option value = "'.$i.'">'.$i.'</option>';
            }
            ?>
        </select><br>

        郵便番号(前半)<br>
        <input type="text" name="zip_cpde1" required><br>

        郵便番号(後半)<br>
        <input type="text" name="zip_cpde2" required><br>

        都道府県<br>
        <input type="text" name="prefecture" required><br>

        市区町村<br>
        <input type="text" name="sity" required><br>

        町名<br>
        <input type="text" name="town" required><br>

        番地<br>
        <input type="text" name="street_number" required><br>

        建物名など<br>
        <input type="text" name="building_name"><br>

        メールの配信<br>
        <div><input type="radio" name="mail_magazine" value="true" required>希望する</div>
        <div><input type="radio" name="mail_magazine" value="false">希望しない</div>

        <input type="submit" value="登録">
    </form>
    <a href="/bookon/g4_login_input.php">戻る</a>

</main>