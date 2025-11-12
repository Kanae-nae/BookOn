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