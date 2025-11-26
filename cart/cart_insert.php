<?php session_start(); ?>

<?php
    $id = $_POST['product_id'];
    switch ($_POST['format']) {
    case 'digital':
        $format_id = 1;
        $format_name = "電子書籍";
        break;
    case 'book':
        $format_id = 2;
        $format_name = "紙書籍";
        break;
    }

    if(!isset($_SESSION['product'])){
        $_SESSION['product'] = [];
    }
    $count = 0;
    $_SESSION['product'][$id] = [
        'name' => $_POST['product_name'],
        'price' => $_POST['price'],
        'count' => $_POST['count'],
        'product_img_url' => $_POST['product_img_url'],
        'author_name' => $_POST['author_name'],
        'format_id' => $format_id,
        'format_name' => $format_name
    ];

    // メッセージの格納
    $msg = 'カートに追加しました。';
    // $url = 'g2_detail.php?id='.$row['id'];

    // jsのアラートで文章を表示→別画面に飛ばす
    echo '<script>';
    echo 'alert(' . json_encode($msg) . ');';
    echo 'history.back();';
    // echo 'location.href = ' . json_encode($url) . ';';
    echo '</script>';
    exit;
?>