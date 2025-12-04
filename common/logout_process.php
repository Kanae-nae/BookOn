<?php session_start(); ?>
<!-- 実際のログアウト処理 -->

<?php
// ユーザーのセッションの削除
unset($_SESSION['user']);

// カートに何かしらある場合はそちらも削除
if(isset($_SESSION['product'])){
    unset($_SESSION['product']);
}
exit;
?>