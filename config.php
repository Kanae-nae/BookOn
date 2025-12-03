<?php
// config.php - データベース接続情報と接続関数

// データベース接続情報 (Lolipop準拠)
const SERVER = 'mysql323.phy.lolipop.lan';
const DBNAME = 'LAA1658836-bookon'; // データベース名
const USER = 'LAA1658836'; // ユーザー名
const PASS = 'passbookon'; // パスワード

/**
 * データベース接続オブジェクト (PDO) を返します。
 * @return PDO
 */
function get_db_connect() {
    $connect = 'mysql:host='. SERVER .';dbname='. DBNAME .';charset=utf8';
    try {
        $pdo = new PDO($connect, USER, PASS); 
        // エラーモードを例外に設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // デフォルトのフェッチモードを連想配列に設定
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        // 接続失敗時はエラーメッセージを表示して終了
        exit("データベース接続エラー: " . $e->getMessage()); 
    }
}

/**
 * ログイン状態をチェックする関数
 * @return void
 */
function check_admin_login() {
    //session_start(); // ★★★ 修正点: 呼び出し元で実行するためコメントアウトする
    // ログインしていない場合はログイン画面へリダイレクト
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        // 管理者ログイン画面へリダイレクト
        header('Location: G16_login.php');
        exit;
    }
}