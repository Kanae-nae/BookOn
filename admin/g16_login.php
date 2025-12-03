<?php
// admin/g16_login.php - 管理者ログイン画面 兼 認証処理

session_start(); 
require_once __DIR__ . '/../config.php'; // ルートのconfig.phpを読み込む

// ======================================
// 認証処理を統合
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_address = $_POST['admin_address'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($admin_address) || empty($password)) {
        $_SESSION['login_error'] = 'メールアドレスとパスワードを入力してください。';
        header('Location: g16_login.php'); 
        exit;
    }

    try {
        $pdo = get_db_connect();
        $sql = "SELECT * FROM `admin` WHERE admin_address = :admin_address";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':admin_address', $admin_address);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['pass'])) {
            // 認証成功！
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name']; 
            
            // 管理トップ（G17_product_manage.phpを暫定的に設定）へリダイレクト
            header('Location: g18_product_manage.php'); 
            exit;
        } else {
            $_SESSION['login_error'] = 'メールアドレスまたはパスワードが正しくありません。';
            header('Location: g16_login.php');
            exit;
        }

    } catch (Exception $e) {
        $_SESSION['login_error'] = "認証エラーが発生しました。時間をおいて再度お試しください。";
        // 開発環境では詳細なエラーを表示
        // $_SESSION['login_error'] .= " (" . $e->getMessage() . ")"; 
        header('Location: g16_login.php');
        exit;
    }
}

// ======================================
// 画面表示
// ======================================
$page_title = "管理者ログイン";
$login_message = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK ON 管理システム | <?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="../css/admin_style.css"> 
    <style>
        /* ログイン画面の配置スタイル */
        body { 
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; flex-direction: column; margin: 0;
            background-color: #f8f9fa; /* 背景色 */
            padding-left: 0; /* サイドバーのパディングを解除 */
        }
        .login-box {
            max-width: 400px;
            width: 90%;
            padding: 30px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            background: white;
            text-align: left; /* テキストは左寄せ */
        }
        .logo-area { 
            margin-bottom: 20px; 
            text-align: center; /* ロゴだけ中央寄せ */
        }
        .login-logo { 
            max-height: 50px; 
            display: block;
            margin: 0 auto;
        }
        
        /* 💡 これが入力欄の幅を統一する大事な修正や！ */
        .login-box .form-group input[type="text"],
        .login-box .form-group input[type="password"] {
            width: 100%; /* 親要素の幅いっぱい（400px以内）に広げる */
            padding: 10px;
            box-sizing: border-box; /* paddingを含めて幅100%にするための必須設定 */
            margin-top: 5px;
            border: 1px solid #ccc; /* 枠線もちゃんとつける */
            border-radius: 4px;
        }
        
        /* h1やエラーメッセージは中央寄せに戻す */
        .login-box h1, 
        .login-box .error-message {
            text-align: center;
        }
        /* ボタンの位置調整 */
        .login-box .form-actions {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="logo-area">
    <img src="../image/logo.png" alt="BOOK ON ロゴ" class="login-logo">
</div>

<div class="login-box">
    <h1>管理者ログイン画面</h1>

    <?php if ($login_message): ?>
        <p class="error-message"><?= htmlspecialchars($login_message) ?></p>
    <?php endif; ?>

    <form action="g16_login.php" method="POST">
        <div class="form-group">
            <label for="admin_address">メールアドレス</label>
            <input type="text" id="admin_address" name="admin_address" required>
        </div>
        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary" style="width: 100%;">ログイン</button>
    </form>
</div>

</body>
</html>