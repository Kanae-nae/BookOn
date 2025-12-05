<?php session_start(); ?>

<!-- 会員情報の変更でアイコンを変える処理 -->

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アイコン選択 - BOOK ON</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/g10.css">
    <link rel="stylesheet" href="css/g10_icon.css">
</head>

<body>
    <main>
        <div class="icon-select-container">
            <h1 class="title is-3" style="margin-top: 20px;">アイコンを選択</h1>

            <div class="icon-grid" id="iconGrid">
                <!-- アイコン一覧（実際のアイコンパスに合わせて修正してください） -->
                <div class="icon-item" data-url="image/icon/default.png">
                    <img src="image/icon/default.png" alt="デフォルト">
                    <p>デフォルト</p>
                </div>
                <div class="icon-item" data-url="image/icon/icon1.png">
                    <img src="image/icon/icon1.png" alt="アイコン1">
                    <p>アイコン1</p>
                </div>
                <div class="icon-item" data-url="image/icon/icon2.png">
                    <img src="image/icon/icon2.png" alt="アイコン2">
                    <p>アイコン2</p>
                </div>
                <div class="icon-item" data-url="image/icon/icon3.png">
                    <img src="image/icon/icon3.png" alt="アイコン3">
                    <p>アイコン3</p>
                </div>
                <!-- 必要に応じてアイコンを追加 -->
            </div>

            <div class="button-group">
                <button class="button is-primary is-large" id="confirmBtn" disabled>
                    <i class="fa-solid fa-check"></i> 選択を確定
                </button>
            </div>
        </div>
    </main>

    <script>
    let selectedIconUrl = null;

    console.log('Icon selector page loaded');

    // アイコンクリック処理
    document.querySelectorAll('.icon-item').forEach(item => {
        item.addEventListener('click', function() {
            // 既存の選択を解除
            document.querySelectorAll('.icon-item').forEach(i => i.classList.remove('selected'));
            
            // 新しい選択
            this.classList.add('selected');
            selectedIconUrl = this.getAttribute('data-url');
            
            // 確定ボタンを有効化
            document.getElementById('confirmBtn').disabled = false;
        });
    });

    // 確定ボタンクリック処理
    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (selectedIconUrl) {
            // 親ウィンドウにメッセージを送信
            if (window.opener) {
                window.opener.postMessage({
                    type: 'iconSelected',
                    iconUrl: selectedIconUrl
                }, '*');
                window.close();
            } else {
                // 別ページ遷移の場合はlocalStorageを使用
                localStorage.setItem('selectedIcon', selectedIconUrl);
                window.location.href = 'g10_mypage_update.php';
            }
        }
    });

    console.log('Event listeners attached');
    </script>
</body>
</html>