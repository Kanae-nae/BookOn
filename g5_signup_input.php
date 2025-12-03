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
   
    <div class="registration-container" id="signup">
        <form action="g5_signup_output.php" method="post" class="registration-form">
 
            <section class="form-section">
                <h2>ログイン情報</h2>
               
                <div class="form-group">
                    <label for="username">ユーザーネーム</label>
                    <p class="guide-text">※レビュー投稿時に他の会員に表示されます。</p>
                    <input type="text" id="username" name="user_name" placeholder="オサム" class="input" v-model="username" @blur="validateUsername" required>
                    <p v-if="touched.username && errors.username" class="has-text-danger">{{ errors.username }}</p>
                </div>
               
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
                            <select id="birth_year" name="year">
                                <?php
                                $defaultYear = 2000;

                                for ($i = 1925; $i <= 2025; $i++) {
                                    $selected = ($i == $defaultYear) ? 'selected' : '';
                                    echo "<option value=\"$i\" $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <span>年</span>
                        <div class="select">
                            <select id="birth_month" name="month">
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
                            <select id="birth_day" name="date">
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
                <?php require "common/address.php"; ?>
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
                <button type="submit" class="submit-btn button is-info is-fullwidth is-rounded" style="font-weight: bold; font-size: 1.1rem; height: 50px;" :disabled="hasErrors">会員登録</button>
                <button type="button" class="back-btn button is-light is-fullwidth is-rounded" @click="goBackPage" style="font-weight: bold; font-size: 1.1rem; height: 50px; margin-top: 15px;">戻る</button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script type="module" src="script/vue_signup.js"></script>

</body>
</html>