<?php
// admin/g17_logout_process.php - 管理者ログアウト処理

session_start();

// セッション変数を全て解除する
$_SESSION = array();

// セッションクッキーも削除する
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// セッションを破壊する
session_destroy();

// ログアウト完了後、ログイン画面へリダイレクト
header('Location: g16_login.php');
exit;
?>