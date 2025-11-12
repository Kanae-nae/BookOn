<!-- 新規登録画面(G5)の入力側 -->
<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録 - BOOK ON</title>   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/g5.css">
    <script src="script/prefectures.js"></script>
</head>

<body>
    <header class="logo-only-header">
        <div class="logo">
            <a href="index.php">
                <img src="image/logo.png" alt="BOOK ON Logo">
            </a>
        </div>
    </header>
  
    <main>
    <h1>新規会員登録</h1>
   
    <div class="registration-container">
 
        <form action="g5_signup_output.php" method="post" class="registration-form">
 
            <section class="form-section">
                <h2>ログイン情報</h2>
               
                <div class="form-group">
                    <label for="username">ユーザーネーム</label>
                    <p class="guide-text">※レビュー投稿時に他の会員に表示されます。</p>
                    <input type="text" id="username" name="user_name" placeholder="オサム" class="input" required>
                </div>
               
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="mail_address" placeholder="bookon@example.com" class="input" required>
                </div>
 
                <div class="form-group">
                    <label for="email_confirm">メールアドレス（確認用）</label>
                    <input type="email" id="email_confirm" name="email_confirm" placeholder="bookon@example.com" class="input" required>
                </div>
               
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" placeholder="Bookon123" class="input" required>
                </div>
                <div class="form-group">
                    <label for="password_confirm">パスワード（確認用）</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Bookon123" class="input" required>
                </div>
            </section>
           
            <section class="form-section">
                <h2>会員情報</h2>
               
                <div class="form-group required-field">
                    <div class="label-with-badge">
                        <div class="icon-label-group">
                            <span class="icon"><i class="fas fa-book-open"></i></span>
                            <label>氏名（漢字）</label>
                        </div>
                        <span class="required-badge">必須</span>
                    </div>
                    <div class="input-inline">
                        <div>
                            <label for="name_kanji_sei">姓</label>
                            <input type="text" id="name_kanji_sei" name="last_name" placeholder="手塚" class="input" required>
                        </div>
                        <div>
                            <label for="name_kanji_mei">名</label>
                            <input type="text" id="name_kanji_mei" name="first_name" placeholder="治虫" class="input" required>
                        </div>
                    </div>
                </div>
                <div class="form-group required-field">
                    <div class="label-with-badge">
                         <div class="icon-label-group">
                            <span class="icon"><i class="fas fa-book-open"></i></span>
                            <label>氏名（カナ）</label>
                        </div>
                        <span class="required-badge">必須</span>
                    </div>
                    <div class="input-inline">
                        <div>
                            <label for="name_kana_sei">セイ</label>
                            <input type="text" id="name_kana_sei" name="last_name_kana" placeholder="テヅカ" class="input" required>
                        </div>
                        <div>
                            <label for="name_kana_mei">メイ</label>
                            <input type="text" id="name_kana_mei" name="first_name_kana" placeholder="オサム" class="input" required>
                        </div>
                    </div>
                </div>
                <div class="form-group required-field">
                    <div class="label-with-badge">
                         <div class="icon-label-group">
                            <span class="icon"><i class="fas fa-book-open"></i></span>
                            <label>生年月日</label>
                        </div>
                        <span class="required-badge">必須</span>
                    </div>
                    <div class="input-inline date-select">
                        <div class="select">
                            <select id="birth_year" name="year" required>
                                <?php
                                for($i=1925; $i<2026; $i++){
                                    echo '<option value = "'.$i.'">'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span>年</span>
                        <div class="select">
                            <select id="birth_month" name="month" required>
                                <?php
                                    for($i=1; $i<13; $i++){
                                        if($i < 10){
                                            $i = '0'.strval($i);
                                        }
                                        echo '<option value = "'.$i.'">'.$i.'</option>';
                                    }
                                ?>
                            </select> 
                        </div>
                        <span>月</span>
                        <div class="select">
                            <select id="birth_day" name="date" required>
                                <?php
                                for($i=1; $i<32; $i++){
                                    if($i < 10){
                                        $i = '0'.strval($i);
                                    }
                                    echo '<option value = "'.$i.'">'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span>日</span>
                    </div>
                </div>
                <?php require "common/address.html"; ?>
            </section>

            <section class="form-section email-delivery-section">
                <div class="label-with-badge" style="margin-bottom: 15px; justify-content: flex-start; gap: 10px;">
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                    <label style="font-weight: bold; font-size: 1.1rem;">メールの配信</label>
                </div>
                <p class="guide-text" style="font-size: 0.9rem; color: #333; margin-bottom: 20px;">BookOnより、お気に入りに追加した商品に関連する情報やメールマガジンの配信を希望しますか？</p>
 
                <div class="radio-group control">
                    <label class="radio">
                        <input type="radio" name="mail_magazine" value="true" checked>
                        メール配信を希望する。
                    </label>
                </div>
 
                <div class="radio-group control">
                     <label class="radio">
                        <input type="radio" name="mail_magazine" value="false">
                        メール配信を希望しない。
                    </label>
                </div>
            </section>
            <div class="button-area">
                <button type="submit" class="submit-btn button is-info is-fullwidth is-rounded" style="font-weight: bold; font-size: 1.1rem; height: 50px;">会員登録</button>
                <button type="button" class="back-btn button is-light is-fullwidth is-rounded" id="toSame" style="font-weight: bold; font-size: 1.1rem; height: 50px; margin-top: 15px;">戻る</button>
            </div>
        </form>
    </div>
</main>

<script>
// 違うページに飛ばす
document.getElementById('toSame').addEventListener('click', function () {
    // 相対パスや絶対URLを指定できます
    location.href = 'g4_login_input.php';
});
</script>

</body>
</html>