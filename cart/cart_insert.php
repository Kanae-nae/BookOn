<?php session_start(); ?>

<?php
    $id = $_POST['product_id'];

    // フォーマットによって処理を変更
    switch ($_POST['format']) {
    case 'digital':
        $format_id = 1;
        $format_name = "電子書籍";
        // 電子書籍の場合は数量を1で固定する
        $count = 1;
        break;
    case 'book':
        $format_id = 2;
        $format_name = "紙書籍";
        $count = $_POST['count'];
        break;
    }

    // $_SESSION['product']自体(箱)が無い場合は箱を作成
    if(!isset($_SESSION['product'])){
        $_SESSION['product'] = [];
    }

    // $_SESSION['product']に格納
    $_SESSION['product'][$id] = [
        'name' => $_POST['product_name'],
        'price' => $_POST['price'],
        'count' => $count,
        'product_img_url' => $_POST['product_img_url'],
        'author_name' => $_POST['author_name'],
        'format_id' => $format_id,
        'format_name' => $format_name
    ];

    // メッセージの格納
    $msg = 'カートに追加しました。';

    // jsのアラートで文章を表示→別画面に飛ばす
    echo '<script>';
    echo 'alert(' . json_encode($msg) . ');';
    echo 'history.back();';
    echo '</script>';
    exit;
?>