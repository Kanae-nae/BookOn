<?php session_start(); ?>

<!-- 購入時のヘッダー -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($GLOBALS['page_title']) ?
    $GLOBALS['page_title'] : 'BOOK ON'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="image/logo.png" alt="BOOK ON Logo" style="height: 40px;">
    </div>
</header>