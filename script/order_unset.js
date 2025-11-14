// 注文をやめた場合の処理

document.getElementById('toSame').addEventListener('click', async function () {
    try {
        // セッション操作用のフラグ
        const body = new URLSearchParams();
        body.append('action', 'order_unset');

        const resp = await fetch('common/order_unset.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        });

        if (!resp.ok) throw new Error('サーバーエラー');

        const json = await resp.json();
        if (json.success) {
            // サーバー処理成功なら遷移
            location.href = 'g11_mycart.php';
        } else {
            alert('処理に失敗しました: ' + (json.message || ''));
        }
    } catch (e) {
        console.error(e);
        alert('通信に失敗しました');
    }
});