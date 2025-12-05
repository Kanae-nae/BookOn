<?php session_start(); ?>
<!-- 会員情報変更画面(G10)の登録側 -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員情報変更 - BOOK ON</title>
</head>
<body>

<?php require 'common/db-connect.php'; ?>
<?php require 'common/prefectures.php'; ?>

<?php

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($_GET['select'] === 'profile') {
        // 一旦レビューと購入履歴の非公開は考えないこととする

        // SQLの実行
        $sql = "UPDATE user SET icon_url = :icon_url, user_name = :user_name, self_introduction = :self_introduction
        WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':icon_url',         htmlspecialchars($_POST['icon_url']), PDO::PARAM_STR);
        $stmt->bindValue(':user_name',        htmlspecialchars($_POST['user_name']),        PDO::PARAM_STR);
        $stmt->bindValue(':self_introduction',    htmlspecialchars($_POST['self_introduction']),    PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);

        $success = $stmt->execute();

        // セッション情報の更新
        $_SESSION['user']['icon_url'] = $_POST['icon_url'];
        $_SESSION['user']['user_name'] = $_POST['user_name'];
        $_SESSION['user']['self_introduction'] = $_POST['self_introduction'];

    } else if($_GET['select'] === 'login') {
        // メールアドレスの重複が無いかどうか、SQLを実行して確かめる
        // (自分は除く)
        $sql = 'SELECT * FROM user WHERE user_id!=:user_id AND mail_address=:mail';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':mail', htmlspecialchars($_POST['mail_address']), PDO::PARAM_STR);
        $stmt->execute();

        // メールアドレスの重複が無いか確認
        if(empty($stmt->fetchAll())){
            // SQLの実行
            $sql = "UPDATE user SET mail_address = :mail_address, password = :password 
            WHERE user_id = :user_id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':mail_address',      htmlspecialchars($_POST['mail_address']),      PDO::PARAM_STR);
            $stmt->bindValue(':password',          password_hash($_POST['password'], PASSWORD_DEFAULT),              PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);

            $success = $stmt->execute();

            // セッション情報の更新
            $_SESSION['user']['mail_address'] = $_POST['mail_address'];

        } else {
            // メールアドレスの重複がある場合
            $msg = 'エラー：メールアドレスの重複があります。';
            $url = 'g10_mypage_update.php';
        }
    } else if($_GET['select'] === 'information') {
        // DBに入れ込む形式に変更
        $year = strval($_POST['year']);
        $month = strval($_POST['month']);
        $date = strval($_POST['date']);
        $mail_magazine = $_POST['mail_magazine'] == "true" ? 1 : 0;

        // 誕生日をDBに入れるために加工
        // (誕生日はセレクトボックスで、年月日に分割されて渡される)
        $birth_date = $year."-".$month."-".$date;

        // 都道府県の加工
        // (数字の方が渡されるので都道府県名に変更)
        $prefCode = $_POST['prefecture'];
        $prefecture = $PREFECTURES[$prefCode] ?? null;

        // SQLの実行
        $sql = "UPDATE user SET birth_date = :birth_date, last_name = :last_name, first_name = :first_name,
        last_name_kana = :last_name_kana, first_name_kana = :first_name_kana, zip_code = :zip_code, prefecture = :prefecture, 
        city = :city, town = :town, street_number = :street_number, building_name = :building_name, mail_magazine = :mail_magazine
        WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':birth_date',        htmlspecialchars($birth_date),        PDO::PARAM_STR);
        $stmt->bindValue(':last_name',         htmlspecialchars($_POST['last_name']), PDO::PARAM_STR);
        $stmt->bindValue(':first_name',        htmlspecialchars($_POST['first_name']),        PDO::PARAM_STR);
        $stmt->bindValue(':last_name_kana',    htmlspecialchars($_POST['last_name_kana']),    PDO::PARAM_STR);
        $stmt->bindValue(':first_name_kana',   htmlspecialchars($_POST['first_name_kana']),   PDO::PARAM_STR);
        $stmt->bindValue(':zip_code',          htmlspecialchars($_POST['zip_code']),          PDO::PARAM_INT);
        $stmt->bindValue(':prefecture',        $prefecture,        PDO::PARAM_STR);
        $stmt->bindValue(':city',              htmlspecialchars($_POST['city']),              PDO::PARAM_STR);
        $stmt->bindValue(':town',              htmlspecialchars($_POST['town']),              PDO::PARAM_STR);
        $stmt->bindValue(':street_number',     htmlspecialchars($_POST['street_number']),     PDO::PARAM_STR);
        $stmt->bindValue(':building_name',     htmlspecialchars($_POST['building_name']),     PDO::PARAM_STR);
        $stmt->bindValue(':mail_magazine',     $mail_magazine,     PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $_SESSION['user']['user_id'], PDO::PARAM_INT);

        $success = $stmt->execute();

        // セッション情報の更新
        $_SESSION['user']['birth_date'] = $birth_date;
        $_SESSION['user']['last_name'] = $_POST['last_name'];
        $_SESSION['user']['first_name'] = $_POST['first_name'];
        $_SESSION['user']['last_name_kana'] = $_POST['last_name_kana'];
        $_SESSION['user']['first_name_kana'] = $_POST['first_name_kana'];
        $_SESSION['user']['zip_code'] = $_POST['zip_code'];
        $_SESSION['user']['prefecture'] = $prefecture;
        $_SESSION['user']['city'] = $_POST['city'];
        $_SESSION['user']['town'] = $_POST['town'];
        $_SESSION['user']['street_number'] = $_POST['street_number'];
        $_SESSION['user']['building_name'] = $_POST['building_name'];
        $_SESSION['user']['mail_magazine'] = $mail_magazine;

    } else {
        echo "データが正確に入力されていません。";
        $url = 'g10_mypage_update.php';
        $success = false;
        
        echo '<script>';
        echo 'alert(' . json_encode($msg) . ');';
        echo 'location.href = ' . json_encode($url) . ';';
        echo '</script>';
        exit;
    }
} catch (PDOException $e) {
    // DBの接続で何かしらのエラーが発生した場合
    $msg = 'エラー：システム上のトラブルが発生しました。'.$e;
    $url = 'g10_mypage_update.php';
    $pdo = null;

    // jsのアラートで文章を表示→別画面に飛ばす
    echo '<script>';
    echo 'alert(' . json_encode($msg) . ');';
    echo 'location.href = ' . json_encode($url) . ';';
    echo '</script>';

    exit;
}

if (!$success) {
    $err = $stmt->errorInfo();
    $msg = 'エラー：システム上のトラブルが発生しました。';
    $url = 'g10_mypage_update.php';
} else {
    $msg = '会員情報の更新に成功しました。';
    $url = 'g9_mypage.php';
}

$pdo = null;

echo '<script>';
echo 'alert(' . json_encode($msg) . ');';
echo 'location.href = ' . json_encode($url) . ';';
echo '</script>';
exit;
?>
</body>
</html>