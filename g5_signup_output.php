<?php
session_start();

// 1. データベース接続情報
const SERVER = 'mysql323.phy.lolipop.lan';
const DBNAME = 'LAA1658836-bookon';
const USER = 'LAA1658836';
const PASS = 'passbookon';
$connect = 'mysql:host='. SERVER .';dbname='. DBNAME .';charset=utf8';

$message = ''; // ユーザーへのメッセージ
$success = false; // 登録成功フラグ

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. 入力値のバリデーション
    if ($_POST['email'] !== $_POST['email_confirm']) {
        $message = 'メールアドレス（確認用）が一致しません。';
    } else if ($_POST['password'] !== $_POST['password_confirm']) {
        $message = 'パスワード（確認用）が一致しません。';
    } else {
        
        // 3. メールアドレスの重複チェック
        $mail_address = $_POST['email'];
        $sql_check = $pdo->prepare('SELECT COUNT(*) FROM user WHERE mail_address = ?');
        $sql_check->execute([$mail_address]);
        $count = $sql_check->fetchColumn();

        if ($count > 0) {
            $message = 'このメールアドレスは既に使用されています。';
        } else {
            // 4. 登録処理
            
            // パスワードのハッシュ化
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // データの結合
            $birth_date = $_POST['birth_year'] . '-' . $_POST['birth_month'] . '-' . $_POST['birth_day'];
            $zip_code = $_POST['zipcode_1'] . $_POST['zipcode_2'];
            
            // 建物名 (空の場合はNULLを挿入)
            $building_name = !empty($_POST['mansion']) ? $_POST['mansion'] : NULL;

            // DB画像に基づくデフォルト値
            $icon_url = 'image/icon/default';
            $review_public = 1;
            $order_public = 1;

            // 5. SQLの準備と実行
            $sql_insert = $pdo->prepare(
                'INSERT INTO user (
                    user_name, mail_address, password, last_name, first_name, 
                    last_name_kana, first_name_kana, birth_date, zip_code, 
                    prefecture, city, street_number, building_name, 
                    mail_magazine, icon_url, review_public, order_public
                ) VALUES (
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?
                )'
            );
            
            $sql_insert->execute([
                $_POST['username'],
                $mail_address,
                $hashed_password,
                $_POST['name_kanji_sei'],
                $_POST['name_kanji_mei'],
                $_POST['name_kana_sei'],
                $_POST['name_kana_mei'],
                $birth_date,
                $zip_code,
                $_POST['prefecture'],
                $_POST['city'],
                $_POST['street'], // フォームの 'street' を 'street_number' カラムへ
                $building_name,
                $_POST['mail_delivery'],
                $icon_url,
                $review_public,
                $order_public
            ]);

            $message = '会員登録が完了しました。';
            $success = true;
        }
    }
} catch (PDOException $e) {
    // データベースエラー
    $message = 'データベースエラーが発生しました。恐れ入りますが、時間をおいて再度お試しください。';
    // $message = $e->getMessage(); // (デバッグ用)
} catch (Exception $e) {
    // その他予期せぬエラー
    $message = '予期せぬエラーが発生しました。';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録 - 登録結果</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/g5.css">
</head>
<body>
    <?php include 'common/header_only.php'; ?>
    <main>
        <h1>登録結果</h1>
        <div class="registration-container">
            <section class="form-section">
                <h2><?php echo $success ? '登録完了' : '登録エラー'; ?></h2>
                
                <div class="content has-text-centered">
                    <p style="font-size: 1.1rem; margin-bottom: 30px;">
                        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    
                    <?php if ($success): ?>
                        <a href="login.php" class="button is-info is-rounded" style="max-width: 300px; margin: 0 auto; display: block; font-weight: bold; font-size: 1.1rem; height: 50px;">
                            ログイン画面へ
                        </a>
                    <?php else: ?>
                        <button type="button" class="button is-light is-fullwidth is-rounded" onclick="history.back()" style="max-width: 300px; margin: 0 auto; font-weight: bold; font-size: 1.1rem; height: 50px;">
                            登録画面に戻る
                        </button>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
</body>
</html>