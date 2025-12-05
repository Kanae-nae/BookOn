<?php session_start(); ?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート削除 - BOOK ON</title>
    <link rel="stylesheet" href="css/header_only.css">
    <link rel="stylesheet" href="css/g4.css">
</head>

<body>

<?php
    unset($_SESSION['product'][$_GET['id']]);

    // メッセージの格納
    $msg = 'カートから商品を削除しました。';
    $url = '../g11_mycart.php';

    // jsのアラートで文章を表示→別画面に飛ばす
    echo '<script>';
    echo 'alert(' . json_encode($msg) . ');';
    echo 'location.href = ' . json_encode($url) . ';';
    echo '</script>';
    exit;
?>
</body>
</html>