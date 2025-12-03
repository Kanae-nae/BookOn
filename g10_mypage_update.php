<?php require 'common/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/g10.css">
<link rel="stylesheet" href="css/g10_profile.css">

<script>
window.initialUser = <?= json_encode($_SESSION['user'] ?? []) ?>;
</script>

<!-- デフォルトの生年月日の設定 -->
<?php
// セッションから生年月日を取得してパース（フォーマット: YYYY-MM-DD）
// デフォルト値
$defaultYear = 2000;
$birth_year = $defaultYear;
$birth_month = '01';
$birth_day = '01';

$bd = $_SESSION['user']['birth_date'] ?? '';
if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $bd, $m)) {
    $birth_year = intval($m[1]);
    // 月日を必ずゼロ埋めした文字列で保持
    $birth_month = sprintf('%02d', intval($m[2]));
    $birth_day = sprintf('%02d', intval($m[3]));
}
?>

<main>
    <div class="registration-container" id="signup">
        <!-- 戻るボタン -->
        <button class="g10p-back-btn" @click="goBackPage">
            <i class="fa-solid fa-arrow-left"></i> 戻る
        </button>

        <h1>会員情報編集</h1>

        <!-- プロフィールのフォーム  -->
        <form action="g10_mypage_update_output.php?select=profile" method="post" class="registration-form">
            <section class="g10p-card">
                <h2 class="g10p-title">プロフィール</h2>

                <!-- アイコン -->
                <label class="g10p-label">アイコン</label>
                <div class="g10p-avatar-wrap">
                    <img id="avatarImg" :src="selectedIcon" class="g10p-avatar" alt="icon">
                    <button type="button" class="g10p-avatar-edit" @click="openIconSelector">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                </div>
                <!-- 隠しフィールドでアイコンURLを送信 -->
                <input type="hidden" name="icon_url" :value="selectedIcon">

                <!-- ユーザーネーム入力 -->
                <div class="g10p-input-group">
                    <label class="g10p-label">ユーザー ネーム</label>
                    <p class="g10p-note">※レビュー投稿時に他の会員に表示されます。</p>
                    <input type="text" id="username" name="user_name" placeholder="オサム" class="g10p-input" v-model="username" @blur="validateUsername" required>
                    <p v-if="touched.username && errors.username" class="has-text-danger">{{ errors.username }}</p>
                </div>

                <!-- 自己紹介 -->
                <div class="g10p-input-group">
                    <label class="g10p-label">自己紹介</label>
                    <textarea class="g10p-textarea" name="self_introduction"><?= $_SESSION['user']['self_introduction'] ?></textarea>
                </div>

                <!-- 公開設定(間に合わないかもしれないので一旦コメントアウト) -->
                <!-- <div class="g10p-switch-group">
                    <label class="g10p-switch-row">
                        <span>レビューを公開する</span>
                        <label class="g10p-switch">
                            <input type="checkbox" checked>
                            <span class="g10p-slider"></span>
                        </label>
                    </label>

                    <label class="g10p-switch-row">
                        <span>購入履歴を公開する</span>
                        <label class="g10p-switch">
                            <input type="checkbox">
                            <span class="g10p-slider"></span>
                        </label>
                    </label>
                </div> -->

                <div class="g10p-btn-wrap">
                    <button type="submit" class="g10p-submit">変更</button>
                </div>

            </section>
        </form>

        <!-- ログイン情報のフォーム -->
        <form action="g10_mypage_update_output.php?select=login" method="post" class="registration-form">
            <section class="form-section">
                <h2>ログイン情報</h2>
               
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="mail_address" placeholder="bookon@example.com" class="input" v-model="email" @blur="validateEmail" required>
                    <p v-if="touched.email && errors.email" class="has-text-danger">{{ errors.email }}</p>
                </div>
 
                <div class="form-group">
                    <label for="email_confirm">メールアドレス（確認用）</label>
                    <input type="email" id="email_confirm" name="email_confirm" placeholder="bookon@example.com" class="input" v-model="emailConfirm" @blur="validateEmailConfirm" required>
                    <p v-if="touched.emailConfirm && errors.emailConfirm" class="has-text-danger">{{ errors.emailConfirm }}</p>
                </div>
               
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" placeholder="Bookon123" class="input" v-model="pass" @blur="validatePass" required>
                    <p v-if="touched.pass && errors.pass" class="has-text-danger">{{ errors.pass }}</p>
                    <ul class="guide-text">
                        <li>半角英数字を1文字以上使用</li>
                        <li>大文字、小文字の区別あり</li>
                        <li>8文字以上16文字以内</li>
                    </ul>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">パスワード（確認用）</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Bookon123" class="input" v-model="passConfirm" @blur="validatePassConfirm" required>
                    <p v-if="touched.passConfirm && errors.passConfirm" class="has-text-danger">{{ errors.passConfirm }}</p>
                </div>

                <div class="g10p-btn-wrap">
                    <button type="submit" class="g10p-submit">変更</button>
                </div>
            </section>
        </form>

        <!-- 会員情報のフォーム -->
        <form action="g10_mypage_update_output.php?select=information" method="post" class="registration-form">
            <section class="form-section">
                <h2>会員情報</h2>
               
                <!-- 漢字の姓、名 -->
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
                            <input type="text" id="name_kanji_sei" name="last_name" placeholder="手塚" class="input" v-model="lastname" @blur="validateLastName" required>
                        </div>
                        <div>
                            <label for="name_kanji_mei">名</label>
                            <input type="text" id="name_kanji_mei" name="first_name" placeholder="治虫" class="input" v-model="firstname" @blur="validateFirstName" required>
                        </div>
                    </div>
                    <p v-if="touched.lastname && errors.lastname" class="has-text-danger">{{ errors.lastname }}</p>
                    <p v-if="touched.firstname && errors.firstname" class="has-text-danger">{{ errors.firstname }}</p>
                </div>

                <!-- カナのセイ、メイ -->
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
                            <input type="text" id="name_kana_sei" name="last_name_kana" placeholder="テヅカ" class="input" v-model="lastnamekana" @blur="validateLastNameKana" required>
                        </div>
                        <div>
                            <label for="name_kana_mei">メイ</label>
                            <input type="text" id="name_kana_mei" name="first_name_kana" placeholder="オサム" class="input" v-model="firstnamekana" @blur="validateFirstNameKana" required>
                        </div>
                    </div>
                    <p v-if="touched.lastnamekana && errors.lastnamekana" class="has-text-danger">{{ errors.lastnamekana }}</p>
                    <p v-if="touched.firstnamekana && errors.firstnamekana" class="has-text-danger">{{ errors.firstnamekana }}</p>
                </div>

                <!-- 生年月日 -->
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
                                for ($y = 1925; $y <= 2025; $y++) {
                                    $selected = ($y === $birth_year) ? 'selected' : '';
                                    echo "<option value=\"{$y}\" {$selected}>{$y}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <span>年</span>
                        <div class="select">
                            <select id="birth_month" name="month" required>
                                <?php
                                for ($m = 1; $m <= 12; $m++) {
                                    $val = sprintf('%02d', $m);
                                    $selected = ($val === $birth_month) ? 'selected' : '';
                                    echo "<option value=\"{$val}\" {$selected}>{$val}</option>";
                                }
                                ?>
                            </select> 
                        </div>
                        <span>月</span>
                        <div class="select">
                            <select id="birth_day" name="date" required>
                                <?php
                                for ($d = 1; $d <= 31; $d++) {
                                    $val = sprintf('%02d', $d);
                                    $selected = ($val === $birth_day) ? 'selected' : '';
                                    echo "<option value=\"{$val}\" {$selected}>{$val}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <span>日</span>
                    </div>
                </div>
                <?php require "common/address.php"; ?>

                <!-- メールの配信 -->
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

                <div class="g10p-btn-wrap">
                    <button type="submit" class="g10p-submit">変更</button>
                </div>
            </section>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script type="module" src="script/vue_mypage.js"></script>

<script>
// 子ウィンドウからのメッセージを受信
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'iconSelected') {
        if (window.signup && window.signup.selectedIcon !== undefined) {
            window.signup.selectedIcon = event.data.iconUrl;
        }
    }
});

// ページ読み込み時にlocalStorageをチェック
window.addEventListener('DOMContentLoaded', function() {
    const savedIcon = localStorage.getItem('selectedIcon');
    if (savedIcon) {
        // Vueインスタンスの準備を待つ
        const checkVue = setInterval(function() {
            if (window.signup && window.signup.selectedIcon !== undefined) {
                window.signup.selectedIcon = savedIcon;
                localStorage.removeItem('selectedIcon');
                clearInterval(checkVue);
            }
        }, 100);
    }
});
</script>

</body>
</html>