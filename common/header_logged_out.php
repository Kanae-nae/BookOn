<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK ON</title>
    <style>
        /* body全体のスタイルをリセットし、ナビゲーション分のスペースを確保 (フッター分小さく) */
        body {
            margin: 0;
            padding-bottom: 60px; /* フッターの高さに合わせて小さく（約60px） */
            font-family: sans-serif;
            background-color: #ffffff;
        }

        /* ヘッダーのスタイル (大きく) */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 15px; /* 上下のパディングを増やして大きく */
            border-bottom: 1px solid hsl(0, 6%, 83%);
            background-color: #ffffff;
        }

        /* ログイン/会員登録ボタンのスタイル (大きく) */
        .login-btn {
            display: flex;
            align-items: center;
            background-color: #005A9C;
            color: white;
            padding: 8px 15px 8px 12px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 14px; 
            font-weight: bold;
        }
        .login-btn span:first-child {
            display: inline-block;
            width: 32px;
            height: 32px;
            margin-right: 8px;
            color: white;
            text-align: center;
            line-height: 32px;
            font-size: 20px; 
        }
        .login-btn span:last-child {
            line-height: 1.2;
        }

        /* --- ログアウトボタンのスタイル (大きく調整 & 「←]」アイコン再々適用) --- */
        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #D95360; /* 画像の色に合わせる */
            color: white;
            padding: 0 18px; /* 左右のパディングを調整 */
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px; 
            font-weight: bold;
            width: 120px; 
            height: 40px; 
            box-sizing: border-box;
            white-space: nowrap;
        }
        
        /* 「←]」アイコンのスタイル (テキストベース) */
        .logout-btn span:first-child {
            display: inline-block;
            color: white;
            font-size: 20px; /* サイズを大きく */
            line-height: 1; 
            /* 今回は「←]」なので反転は不要 */
            transform: none; 
            margin-right: 5px; /* テキストとの間隔 */
            flex-shrink: 0;
        }

        .logout-btn span:last-child {
            line-height: 1.2;
            margin-top: 0;
        }
        /* --------------------------------- */

        /* 下部固定ナビゲーションのスタイル (小さく) */
        nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%; 
            z-index: 1000;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            background-color: #f0e641;
            padding: 8px 0; /* 上下のパディングを減らして小さく */
        }

        /* ナビゲーションリンクの共通スタイル (小さく) */
        nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #707070;
            font-size: 10px; /* テキストを小さく */
            font-weight: 500;
            width: 70px;
            text-align: center;
        }
        nav a div {
            width: 28px; /* アイコン領域を小さく */
            height: 28px; /* アイコン領域を小さく */
            margin-bottom: 2px; /* マージンを減らす */
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="image/logo.png" alt="BOOK ON Logo" style="height: 30px;">
    </div>

    <a href="/logout" class="logout-btn">
        <span>[→</span>
        <span>
            ログアウト
        </span>
    </a>
</header>

<nav>
    <a href="#home">
        <div></div>
        ホーム
    </a>
    <a href="#search">
        <div></div>
        検索
    </a>
    <a href="#library">
        <div>
</div>
        ライブラリ
    </a>
    <a href="#settings">
        <div></div>
        設定
    </a>
</nav>

</body>
</html>