<?php session_start(); ?>
<!-- 新規登録画面(G5)の登録側 -->

<?php require 'common/db-connect.php'; ?>
<?php require 'common/prefectures.php'; ?>

<?php

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // メールアドレスの重複が無いかどうか、SQLを実行して確かめる
    $sql = 'SELECT * FROM user WHERE mail_address=:mail';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mail', htmlspecialchars($_POST['mail_address']), PDO::PARAM_STR);
    $stmt->execute();

    // メールアドレスの重複が無いか確認
    if(empty($stmt->fetchAll())){
        // 重複が無かった場合
        // 初期値の設定
        $icon_url = 'image/icon/default';
        $self_introduction = '';
        $review_public = 1;
        $order_public = 1;

        // SQLの実行
        // 補足：user_idはAUTOで割り振る
        $sql = "INSERT INTO user VALUES (
            null, :user_name, :mail_address, :password, :birth_date, 
            :last_name, :first_name, :last_name_kana, :first_name_kana, :zip_code, :prefecture,
            :city, :town, :street_number, :self_introduction, :icon_url, :building_name, :mail_magazine, :review_public, :order_public
        )";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':user_name',         htmlspecialchars($_POST['user_name']),         PDO::PARAM_STR);
        $stmt->bindValue(':mail_address',      htmlspecialchars($_POST['mail_address']),      PDO::PARAM_STR);
        $stmt->bindValue(':password',          password_hash($_POST['password'], PASSWORD_DEFAULT),              PDO::PARAM_STR);
        $stmt->bindValue(':birth_date',        htmlspecialchars($birth_date),        PDO::PARAM_STR);
        $stmt->bindValue(':icon_url',          $icon_url, PDO::PARAM_STR);
        $stmt->bindValue(':self_introduction', $self_introduction, PDO::PARAM_STR);
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
        $stmt->bindValue(':review_public',     $review_public,     PDO::PARAM_INT);
        $stmt->bindValue(':order_public',      $order_public,      PDO::PARAM_INT);

        $success = $stmt->execute();

        if (!$success) {
            $err = $stmt->errorInfo();
            $msg = 'エラー：システム上のトラブルが発生しました。';
            $url = 'g5_signup_input.php';
        } else {
            $msg = '会員登録に成功しました。';
            $url = 'g4_login_input.php';
        }
    } else {
        // メールアドレスの重複がある場合
        $msg = 'エラー：メールアドレスの重複があります。';
        $url = 'g5_signup_input.php';
    }
} catch (PDOException $e) {
    // DBの接続で何かしらのエラーが発生した場合
    $msg = 'エラー：システム上のトラブルが発生しました。'.$e;
    $url = 'g5_signup_input.php';
    $pdo = null;

    // jsのアラートで文章を表示→別画面に飛ばす
    echo '<script>';
    echo 'alert(' . json_encode($msg) . ');';
    echo 'location.href = ' . json_encode($url) . ';';
    echo '</script>';

    exit;
}

$pdo = null;

echo '<script>';
echo 'alert(' . json_encode($msg) . ');';
echo 'location.href = ' . json_encode($url) . ';';
echo '</script>';
exit;
?>