<?php session_start(); ?>

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