<!-- ログアウトの確認処理(js) -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト - BOOK ON</title>
</head>
<body>
    <!-- ログアウトの確認処理(js) -->
    <script>
        if(confirm("ログアウトしてもよろしいですか？")) {
        fetch("logout_process.php").then(() => {
            alert("ログアウトしました。");
            location.href = "../index.php";
            });
        } else {
            history.back();
        }
    </script>
</body>