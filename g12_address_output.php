<?php session_start(); ?>
<!-- 購入用の住所の登録 -->
<?php
// 購入用のSESSIONに格納
if(empty($_SESSION['order'])){
    $_SESSION['order'] = [
    'zip_code'          => $_POST['zip_code'],
    'prefecture'        => $_POST['prefecture'],
    'city'              => $_POST['city'],
    'town'              => $_POST['town'],
    'street_number'     => $_POST['street_number'],
    'building_name'     => $_POST['building_name']
    ];
} else {
    $_SESSION['order']['zip_code'] = $_POST['zip_code'];
    $_SESSION['order']['prefecture'] = $_POST['prefecture'];
    $_SESSION['order']['city'] = $_POST['city'];
    $_SESSION['order']['town'] = $_POST['town'];
    $_SESSION['order']['street_number'] = $_POST['street_number'];
    $_SESSION['order']['building_name'] = $_POST['building_name'];
}

// jsのアラートで文章を表示→別画面に飛ばす
// (json_encodeでエスケープ出力)
$url = 'g13_payment.php';
echo '<script>';
echo 'location.href = ' . json_encode($url) . ';';
echo '</script>';
exit;
?>