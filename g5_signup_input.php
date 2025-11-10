<?php
// PHPセッション開始（必要であれば）
session_start();
?>
 
<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録 - BOOK ON</title>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/g5.css">
</head>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('birth_year');
    const monthSelect = document.getElementById('birth_month');
    const daySelect = document.getElementById('birth_day');

    // 1. 年の生成 (1950年から2040年まで降順)
    const startYear = 1950;
    const endYear = 2040; 
    
    // 年の初期オプション（"年"）を保持
    const initialYearOption = yearSelect.querySelector('option[value=""]');
    
    for (let year = endYear; year >= startYear; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year; 
        yearSelect.appendChild(option);
    }
    
    // 2. 月の生成 (1月から12月まで)
    // 月の初期オプション（"月"）を保持
    const initialMonthOption = monthSelect.querySelector('option[value=""]');

    for (let month = 1; month <= 12; month++) {
        const option = document.createElement('option');
        // 値は1から12、表示も1から12
        option.value = month; 
        option.textContent = month; 
        monthSelect.appendChild(option);
    }
    
    // 3. 日の生成/更新関数
    function updateDays() {
        // 現在選択されている年と月を取得
        const year = parseInt(yearSelect.value);
        const month = parseInt(monthSelect.value);
        
        // 日のオプションをクリア（初期オプション"日"は残す）
        const selectedDay = daySelect.value; // 現在選択中の日を記憶
        daySelect.innerHTML = daySelect.querySelector('option[value=""]').outerHTML;
        
        // 年または月が選択されていない場合は、日を31日まで表示
        if (isNaN(year) || isNaN(month)) {
            let maxDays = 31;
            // ただし、年と月が未選択で、かつ現在日が記憶されている場合は、その日数が収まるように調整
            if (!isNaN(selectedDay)) {
                maxDays = 31; // デフォルトで31日にリセット
            }

            for (let day = 1; day <= maxDays; day++) {
                const option = document.createElement('option');
                option.value = day;
                option.textContent = day;
                if (day == selectedDay) option.selected = true; // 記憶していた日を選択状態に戻す
                daySelect.appendChild(option);
            }
            return;
        }

        // 翌月の0日目を取得することで、当月の日数を取得できる
        // 例: new Date(2024, 2, 0) は 2024年2月29日 を返す（2024年はうるう年）
        const maxDays = new Date(year, month, 0).getDate();

        // 1日から最大日数までを生成
        for (let day = 1; day <= maxDays; day++) {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            
            // 選択されていた日が新しい最大日数内に収まる場合は、その日を選択状態にする
            if (day == selectedDay && day <= maxDays) {
                option.selected = true;
            }
            daySelect.appendChild(option);
        }
    }

    // 4. イベントリスナーの設定
    // 年または月が変更されたら、日数の更新関数を呼び出す
    yearSelect.addEventListener('change', updateDays);
    monthSelect.addEventListener('change', updateDays);

    // 5. 初回実行
    // 月と日のドロップダウンを初期状態で設定
    // ※年と月が未選択でも日が31日まで表示されるようにするため、一度実行
    updateDays();
    
    // 日の初期オプション（"日"）を保持
    const initialDayOption = daySelect.querySelector('option[value=""]');

    // 月は12個しかないので、先に生成しておく
    for (let day = 1; day <= 31; day++) {
        const option = document.createElement('option');
        option.value = day;
        option.textContent = day;
        daySelect.appendChild(option);
    }
});
</script>
<body>

    <?php include 'common/header_only.php'; ?>
<main>
    <h1>新規会員登録</h1>
   
    <div class="registration-container">
 
        <form action="register-output.php" method="post" class="registration-form">
 
            <section class="form-section">
                <h2>ログイン情報</h2>
               
                <div class="form-group">
                    <label for="username">ユーザーネーム</label>
                    <p class="guide-text">※レビュー投稿時に他の会員に表示されます。</p>
                    <input type="text" id="username" name="username" placeholder="オサム" class="input" required>
                </div>
               
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" placeholder="bookon@example.com" class="input" required>
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
                            <input type="text" id="name_kanji_sei" name="name_kanji_sei" placeholder="手塚" class="input" required>
                        </div>
                        <div>
                            <label for="name_kanji_mei">名</label>
                            <input type="text" id="name_kanji_mei" name="name_kanji_mei" placeholder="治虫" class="input" required>
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
                            <input type="text" id="name_kana_sei" name="name_kana_sei" placeholder="テヅカ" class="input" required>
                        </div>
                        <div>
                            <label for="name_kana_mei">メイ</label>
                            <input type="text" id="name_kana_mei" name="name_kana_mei" placeholder="オサム" class="input" required>
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
                                    echo '<option value = "'.$i.'">年</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span>年</span>
                        <div class="select">
                            <select id="birth_month" name="birth_month" required>
                                <option value="">月</option>
                            </select> 
                        </div>
                        <span>月</span>
                        <div class="select">
                            <select id="birth_day" name="birth_day" required>
                                <option value="">日</option>
                            </select> 
                        </div>
                        <span>日</span>
                    </div>
                </div>
                <div class="form-group address-group required-field">
                    <div class="label-with-badge">
                         <div class="icon-label-group">
                            <span class="icon"><i class="fas fa-book-open"></i></span>
                            <label>住所</label>
                        </div>
                        <span class="required-badge">必須</span>
                    </div>

                    <div class="label-with-guide">
                        <label>郵便番号</label>
                        <p class="guide-text">※郵便番号をもとに自動で入力します</p>
                    </div>
                    <div class="input-inline zipcode-input">
                        <input type="text" id="zipcode_1" name="zipcode_1" placeholder="100" class="input zip-part" maxlength="3" value="" required>
                        <span>-</span>
                        <input type="text" id="zipcode_2" name="zipcode_2" placeholder="0001" class="input zip-part" maxlength="4" value="" required>
                        <button type="button" class="zip-search-btn">検索</button>
                    </div>
 
                    <div class="address-field">
                        <label for="prefecture">都道府県</label>
                        <div class="select">
                            <select id="prefecture" name="prefecture" required>
                            <option value="北海道">北海道</option>
                            <option value="青森県">青森県</option>
                            <option value="岩手県">岩手県</option>
                            <option value="宮城県">宮城県</option>
                            <option value="秋田県">秋田県</option>
                            <option value="山形県">山形県</option>
                            <option value="福島県">福島県</option>
                            <option value="茨城県">茨城県</option>
                            <option value="栃木県">栃木県</option>
                            <option value="群馬県">群馬県</option>
                            <option value="埼玉県">埼玉県</option>
                            <option value="千葉県">千葉県</option>
                            <option value="東京都" selected>東京都</option>
                            <option value="神奈川県">神奈川県</option>
                            <option value="新潟県">新潟県</option>
                            <option value="富山県">富山県</option>
                            <option value="石川県">石川県</option>
                            <option value="福井県">福井県</option>
                            <option value="山梨県">山梨県</option>
                            <option value="長野県">長野県</option>
                            <option value="岐阜県">岐阜県</option>
                            <option value="静岡県">静岡県</option>
                            <option value="愛知県">愛知県</option>
                            <option value="三重県">三重県</option>
                            <option value="滋賀県">滋賀県</option>
                            <option value="京都府">京都府</option>
                            <option value="大阪府">大阪府</option>
                            <option value="兵庫県">兵庫県</option>
                            <option value="奈良県">奈良県</option>
                            <option value="和歌山県">和歌山県</option>
                            <option value="鳥取県">鳥取県</option>
                            <option value="島根県">島根県</option>
                            <option value="岡山県">岡山県</option>
                            <option value="広島県">広島県</option>
                            <option value="山口県">山口県</option>
                            <option value="徳島県">徳島県</option>
                            <option value="香川県">香川県</option>
                            <option value="愛媛県">愛媛県</option>
                            <option value="高知県">高知県</option>
                            <option value="福岡県">福岡県</option>
                            <option value="佐賀県">佐賀県</option>
                            <option value="長崎県">長崎県</option>
                            <option value="熊本県">熊本県</option>
                            <option value="大分県">大分県</option>
                            <option value="宮崎県">宮崎県</option>
                            <option value="鹿児島県">鹿児島県</option>
                            <option value="沖縄県">沖縄県</option>                            
                        </select>
                        </div>
                    </div>
                    
                    <div class="address-field">
                        <label for="city">市区町村</label>
                        <input type="text" id="city" name="city" placeholder="千代田区" value="" class="input" required>
                    </div>
                   
                    <div class="address-field">
                        <label for="street">町名</label>
                        <input type="text" id="street" name="street" placeholder="千代田" value="" class="input" required style="margin-bottom: 0;">
                    </div>
                   
                    <div class="address-field">
                        <label for="mansion" class="guide-text" style="font-size: 1rem; color: #555; font-weight: bold;">建物名など</label>
                        <input type="text" id="mansion" name="mansion" placeholder="" class="input">
                    </div>
                </div>
                </section>
           
            <section class="form-section email-delivery-section">
                <div class="label-with-badge" style="margin-bottom: 15px; justify-content: flex-start; gap: 10px;">
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                    <label style="font-weight: bold; font-size: 1.1rem;">メールの配信</label>
                </div>
                <p class="guide-text" style="font-size: 0.9rem; color: #333; margin-bottom: 20px;">BookOnより、お気に入りに追加した商品に関連する情報やメールマガジンの配信を希望しますか？</p>
 
                <div class="radio-group control">
                    <label class="radio">
                        <input type="radio" name="mail_delivery" value="1" checked>
                        メール配信を希望する。
                    </label>
                </div>
 
                <div class="radio-group control">
                     <label class="radio">
                        <input type="radio" name="mail_delivery" value="0">
                        メール配信を希望しない。
                    </label>
                </div>
            </section>
            <div class="button-area">
                <button type="submit" class="submit-btn button is-info is-fullwidth is-rounded" style="font-weight: bold; font-size: 1.1rem; height: 50px;">会員登録</button>
                <button type="button" class="back-btn button is-light is-fullwidth is-rounded" onclick="history.back()" style="font-weight: bold; font-size: 1.1rem; height: 50px; margin-top: 15px;">戻る</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>


        年<br>
        <select name="year">
            <?php
            for($i=1925; $i<2026; $i++){
                echo '<option value = "'.$i.'">'.$i.'</option>';
            }
            ?>
        </select><br>

        月<br>
        <select name="month">
            <?php
            for($i=1; $i<13; $i++){
                if($i < 10){
                    $i = '0'.strval($i);
                }
                echo '<option value = "'.$i.'">'.$i.'</option>';
            }
            ?>
        </select><br>

        日<br>
        <select name="date">
            <?php
            for($i=1; $i<32; $i++){
                if($i < 10){
                    $i = '0'.strval($i);
                }
                echo '<option value = "'.$i.'">'.$i.'</option>';
            }
            ?>
        </select><br>

        <?php require "common/address.html"; ?>

        メールの配信<br>
        <div><input type="radio" name="mail_magazine" value="true" required>希望する</div>
        <div><input type="radio" name="mail_magazine" value="false">希望しない</div>

        <input type="submit" value="登録">
    </form>
    <a href="g4_login_input.php">戻る</a>

</main>