<?php session_start(); ?>
<!-- 実際のログアウト処理 -->

<?php
unset($_SESSION['user']);
exit;
?>