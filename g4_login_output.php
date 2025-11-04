<?php session_start(); ?>
<!-- ログイン画面(G4)の認証側 -->

<?php require 'common/db-connect.php'; ?>

<?php
$pdo = new PDO($connect, USER, PASS);
$mail_address = $_POST['mail'];
$password = $_POST['pass'];

// ログイン名とパスワードが一致するか確かめる
$sql = 'SELECT * FROM user WHERE mail_address = :mail_address';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mail_address', htmlspecialchars($mail_address), PDO::PARAM_STR);
$stmt->execute();

// 一致した場合はセッションを作成する(一致しない場合は実行しない)
foreach($stmt as $row){
    // パスワードはハッシュ化しているので、password_verifyで確認する
    if(password_verify($password, $row['password'])){
        $_SESSION['user'] = [
        'user_id'           => $row['user_id'],
        'user_name'         => $row['user_name'],
        'mail_address'      => $row['mail_address'],
        'password'              => $row['password'],
        'birth_date'        => $row['birth_date'],
        'self_introduction' => $row['self_introduction'],
        'icon_url'          => $row['icon_url'],
        'last_name'         => $row['last_name'],
        'first_name'        => $row['first_name'],
        'last_name_kana'    => $row['last_name_kana'],
        'first_name_kana'   => $row['first_name_kana'],
        'zip_code'          => $row['zip_code'],
        'prefecture'        => $row['prefecture'],
        'sity'              => $row['sity'],
        'town'              => $row['town'],
        'street_number'     => $row['street_number'],
        'building_name'     => $row['building_name'],
        'mail_magazine'     => $row['mail_magazine'],
        'review_public'     => $row['review_public'],
        'order_public'      => $row['order_public']
        ];
    }
}

if (isset($_SESSION['user'])) {
    // ログインに成功した場合
    $msg = 'ログインに成功しました。';
    $url = 'index.php';
} else {
    // ログインに失敗した場合
    $msg = 'エラー：ログインに失敗しました。';
    $url = 'g4_login_input.php';
}

// jsのアラートで文章を表示→別画面に飛ばす
// (json_encodeでエスケープ出力)
echo '<script>';
echo 'alert(' . json_encode($msg) . ');';
echo 'location.href = ' . json_encode($url) . ';';
echo '</script>';
exit;
?>

<?php require 'common/footer.php'; ?>