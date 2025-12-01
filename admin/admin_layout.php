<?php
// admin/admin_layout.php - 共通レイアウト、サイドバー

// ★★★ 修正点: 呼び出し元で実行済みなので、以下の2行は削除する！ ★★★
// require_once __DIR__ . '/../config.php'; 
// check_admin_login(); 

// 画面タイトルを設定するための変数
if (!isset($page_title)) {
    $page_title = "管理者トップ";
}

// 現在アクセスしているファイル名を取得し、サイドバーのアクティブ表示に使う
$current_file = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK ON 管理システム | <?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="../css/admin_style.css">
    
    <?php if (isset($extra_css)) echo $extra_css; ?>
    
    <style>
        /* サイドバー共通スタイル (admin_style.cssに入りきらない、ページ配置に重要なもの) */
        .sidebar {
            position: fixed; 
            top: 0; 
            left: 0; 
            height: 100vh; 
            width: 200px; 
            padding: 20px; 
            background-color: #333; 
            color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffc107; /* ロゴ色 */
        }
        .sidebar nav a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #ddd;
            margin-bottom: 5px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .sidebar nav a:hover {
            background-color: #575757;
        }
        /* ★★★ 修正: G番号ファイル名でアクティブを判定する ★★★ */
        .sidebar nav a.active {
            background-color: #007bff;
            color: white;
        }
        .sidebar .icon {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <aside class="sidebar">
        <h2>BOOK ON ADMIN</h2>
        <p style="color: #ccc; font-size: 0.9em; padding: 0 15px;">
            ようこそ、<?= htmlspecialchars($_SESSION['admin_name'] ?? '管理者') ?>様
        </p>
        <nav>
            <a href="g21_admin_manage.php?action=list" class="<?= ($current_file === 'G20_admin_manage.php') ? 'active' : '' ?>">
                <span class="icon">👤</span> 管理者管理
            </a>
            <a href="g18_product_manage.php?action=list" class="<?= ($current_file === 'G17_product_manage.php') ? 'active' : '' ?>">
                <span class="icon">📦</span> 商品管理
            </a>
            <a href="g19_customer_manage.php?action=list" class="<?= ($current_file === 'G18_customer_manage.php') ? 'active' : '' ?>">
                <span class="icon">🧑‍💻</span> 顧客管理
            </a>
            <a href="g20_order_manage.php?action=list" class="<?= ($current_file === 'G19_order_manage.php') ? 'active' : '' ?>">
                <span class="icon">🛒</span> 注文管理
            </a>
            <a href="g17_logout_process.php" style="margin-top: 50px;">
                <span class="icon">➡️</span> ログアウト
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <h1><?= htmlspecialchars($page_title) ?></h1>
        <hr>

        <?php 
        // 成功/エラーメッセージの表示
        if (isset($_SESSION['success_message'])): ?>
            <div class="message success">
                <?= htmlspecialchars($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']);
        endif; 
        
        if (isset($_SESSION['error_message'])): ?>
            <div class="message error">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']);
        endif; 
        
        // ページの中身 (`Gxx_manage.php`の内容) はここから始まる
        ?>