<?php session_start(); ?>

<?php
unset($_SESSION['user']);

$msg = 'ログアウトしました。';
$url = 'index.php';

echo '<script>';
echo 'alert(' . json_encode($msg) . ');';
echo 'location.href = ' . json_encode($url) . ';';
echo '</script>';
exit;
?>