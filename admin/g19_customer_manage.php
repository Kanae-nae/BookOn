<?php
// admin/g19_customer_manage.php - 顧客管理 (一覧/登録/編集/削除を統合)

require_once __DIR__ . '/../config.php';
session_start();
check_admin_login(); 

$page_title = "顧客管理";
$action = $_REQUEST['action'] ?? 'list'; // URLからアクションを取得

$pdo = get_db_connect();
$errors = []; 

// ------------------------------------------------
// 1. フォーム処理 (POSTリクエスト)
// ------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // データ取得
    $user_id = $_POST['user_id'] ?? null;
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $mail_address = trim($_POST['mail_address'] ?? '');
    $password = $_POST['password'] ?? '';
    $zip_code = trim($_POST['zip_code'] ?? '');
    $prefecture = trim($_POST['prefecture'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $town = trim($_POST['town'] ?? '');
    $street_number = trim($_POST['street_number'] ?? '');
    $building_name = trim($_POST['building_name'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? null);

    try {
        $pdo->beginTransaction();

        // --- 登録・更新処理 ---
        if ($action === 'create' || $action === 'update') {
            
            // バリデーション（簡易版）
            if (empty($last_name) || empty($first_name)) $errors[] = '氏名を入力してください。';
            if (!filter_var($mail_address, FILTER_VALIDATE_EMAIL)) $errors[] = '有効なメールアドレスを入力してください。';
            if ($action === 'create' && (empty($password) || strlen($password) < 8)) $errors[] = 'パスワードは8文字以上で入力してください。';

            if (empty($errors)) {
                
                if ($action === 'create') {
                    // 新規登録
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO `user` (last_name, first_name, mail_address, password, zip_code, prefecture, city, town, street_number, building_name, birth_date) 
                            VALUES (:last_name, :first_name, :mail_address, :password, :zip_code, :prefecture, :city, :town, :street_number, :building_name, :birth_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':password', $hashed_password);
                    
                    $_SESSION['success_message'] = "新規顧客『{$last_name} {$first_name}』を登録しました。";
                    $action = 'list';
                
                } elseif ($action === 'update' && $user_id) {
                    // 更新
                    $sql = "UPDATE `user` SET 
                            last_name = :last_name, first_name = :first_name, mail_address = :mail_address, 
                            zip_code = :zip_code, prefecture = :prefecture, city = :city, town = :town, 
                            street_number = :street_number, building_name = :building_name, birth_date = :birth_date
                            " . (!empty($password) ? ", password = :password" : "") . "
                            WHERE user_id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    if (!empty($password)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt->bindValue(':password', $hashed_password);
                    }
                    $_SESSION['success_message'] = "顧客ID {$user_id} の情報を更新しました。";
                    $action = 'list';
                }
                
                // 共通バインド
                $stmt->bindValue(':last_name', $last_name);
                $stmt->bindValue(':first_name', $first_name);
                $stmt->bindValue(':mail_address', $mail_address);
                $stmt->bindValue(':zip_code', $zip_code);
                $stmt->bindValue(':prefecture', $prefecture);
                $stmt->bindValue(':city', $city);
                $stmt->bindValue(':town', $town);
                $stmt->bindValue(':street_number', $street_number);
                $stmt->bindValue(':building_name', $building_name);
                $stmt->bindValue(':birth_date', $birth_date);
                $stmt->execute();

            } else {
                $action = $user_id ? 'edit' : 'new'; 
                $_SESSION['old_data'] = $_POST;
            }
        } 
        
        // --- 削除処理（個別）---
        elseif ($action === 'delete_single' && $user_id) {
            $sql = "DELETE FROM `user` WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['success_message'] = "顧客ID {$user_id} を削除しました。";
            $action = 'list';
        } 

        // --- 削除処理（複数選択）---
        elseif ($action === 'bulk_delete' && isset($_POST['selected_users']) && is_array($_POST['selected_users'])) {
            $user_ids = $_POST['selected_users'];
            $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
            $sql = "DELETE FROM `user` WHERE user_id IN ({$placeholders})";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($user_ids);
            
            $deleted_count = $stmt->rowCount();
            $_SESSION['success_message'] = "{$deleted_count}件の顧客データを削除しました。";
            $action = 'list'; 
        }

        $pdo->commit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) { $pdo->rollBack(); }
        $_SESSION['error_message'] = "データベースエラーが発生しました: " . $e->getMessage();
        $action = 'list';
    }
    
    if ($action === 'list') {
        header('Location: g19_customer_manage.php?action=list');
        exit;
    }
}


// ------------------------------------------------
// 2. 画面表示の準備
// ------------------------------------------------
require_once __DIR__ . '/admin_layout.php';

if ($action === 'list') { 
    // ======================================
    // ★★★ 顧客一覧表示 ★★★
    // ======================================

    $page_title = "顧客一覧";
    $search_name = $_GET['search_name'] ?? '';
    $search_mail = $_GET['search_mail'] ?? '';
    $sort_column = $_GET['sort'] ?? 'user_id';
    $sort_order = strtoupper($_GET['order'] ?? 'DESC');
    $valid_columns = ['user_id', 'last_name', 'mail_address', 'birth_date']; 

    if (!in_array($sort_column, $valid_columns)) { $sort_column = 'user_id'; }
    if ($sort_order !== 'ASC' && $sort_order !== 'DESC') { $sort_order = 'DESC'; }

    $users = [];
    $where_clauses = [];
    $bind_values = [];

    // 検索条件の追加
    if (!empty($search_name)) {
        $where_clauses[] = "(last_name LIKE :search_name OR first_name LIKE :search_name)";
        $bind_values[':search_name'] = '%' . $search_name . '%';
    }
    if (!empty($search_mail)) {
        $where_clauses[] = "mail_address LIKE :search_mail";
        $bind_values[':search_mail'] = '%' . $search_mail . '%';
    }

    $where = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : '';

    try {
        $sql = "SELECT * FROM `user` {$where} ORDER BY {$sort_column} {$sort_order}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($bind_values);
        $users = $stmt->fetchAll();

    } catch (Exception $e) {
        echo "<p class='message error'>顧客データの取得に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        $users = [];
    }
    
    ?>

    <p><a href="g19_customer_manage.php?action=new" class="btn-primary">新規顧客登録</a></p>

    <form method="GET" action="g19_customer_manage.php" class="form-box">
        <input type="hidden" name="action" value="list">
        <div class="form-group">
            <label for="search_name">氏名検索:</label>
            <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($search_name) ?>">
        </div>
        <div class="form-group">
            <label for="search_mail">メールアドレス検索:</label>
            <input type="text" id="search_mail" name="search_mail" value="<?= htmlspecialchars($search_mail) ?>">
        </div>
        <button type="submit" class="btn-primary">検索</button>
        <button type="button" onclick="location.href='g19_customer_manage.php?action=list'" class="btn-secondary">リセット</button>
    </form>
    
    <form method="POST" action="g19_customer_manage.php" onsubmit="return confirm('選択した顧客を削除してもよろしいですか？');">
        <input type="hidden" name="action" value="bulk_delete">
        
        <table class="data-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th><a href="?action=list&search_name=<?= htmlspecialchars($search_name) ?>&search_mail=<?= htmlspecialchars($search_mail) ?>&sort=user_id&order=<?= ($sort_column == 'user_id' && $sort_order == 'DESC') ? 'ASC' : 'DESC' ?>">ID</a></th>
                    <th><a href="?action=list&search_name=<?= htmlspecialchars($search_name) ?>&search_mail=<?= htmlspecialchars($search_mail) ?>&sort=last_name&order=<?= ($sort_column == 'last_name' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">氏名</a></th>
                    <th>メールアドレス</th>
                    <th>住所</th>
                    <th>生年月日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><input type="checkbox" name="selected_users[]" value="<?= htmlspecialchars($user['user_id']) ?>"></td>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['last_name']) ?> <?= htmlspecialchars($user['first_name']) ?></td>
                    <td><?= htmlspecialchars($user['mail_address']) ?></td>
                    <td>
                        〒<?= htmlspecialchars($user['zip_code']) ?><br>
                        <?= htmlspecialchars($user['prefecture']) ?>
                        <?= htmlspecialchars($user['city']) ?>
                        <?= htmlspecialchars($user['town']) ?>
                        <?= htmlspecialchars($user['street_number']) ?>
                        <?= htmlspecialchars($user['building_name']) ?>
                    </td>
                    <td><?= htmlspecialchars($user['birth_date']) ?></td>
                    <td>
                        <a href="g19_customer_manage.php?action=edit&id=<?= htmlspecialchars($user['user_id']) ?>">編集</a>
                        <a href="#" onclick="if(confirm('顧客ID:<?= $user['user_id'] ?>を削除しますか？')){document.getElementById('delete_form_<?= $user['user_id'] ?>').submit(); return false;}">削除</a>
                        <form id="delete_form_<?= $user['user_id'] ?>" method="POST" action="g19_customer_manage.php" style="display:none;">
                            <input type="hidden" name="action" value="delete_single">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top: 15px;">
            <button type="submit" class="btn-danger">選択ユーザーを削除</button>
        </p>
    </form>
    <script>
        document.getElementById('check-all').onclick = function() {
            const isChecked = this.checked;
            document.querySelectorAll('input[name="selected_users[]"]').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        };
    </script>
    
    <?php
} elseif ($action === 'new' || $action === 'edit') {
    // ======================================
    // ★★★ 登録・編集フォーム表示 ★★★
    // ======================================
    
    $user_id = $_GET['id'] ?? ($_SESSION['old_data']['user_id'] ?? null);
    $is_new = ($action === 'new');
    $page_title = $is_new ? "新規顧客登録" : "顧客情報編集 (ID: {$user_id})";

    $user_data = [];

    // 編集時：既存データ取得
    if (!$is_new && $user_id) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `user` WHERE user_id = :id");
            $stmt->bindValue(':id', (int)$user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_data = $stmt->fetch();
            if (!$user_data) {
                echo "<p class='message error'>指定された顧客IDのデータが見つかりませんでした。</p>";
                require_once __DIR__ . '/admin_footer.php';
                exit;
            }
        } catch (Exception $e) {
            $errors[] = "データ取得エラー: " . $e->getMessage();
        }
    }

    // 入力値保持セッションからのデータ復元（既存データを上書き）
    $user_data = array_merge($user_data, $_SESSION['old_data'] ?? []);
    unset($_SESSION['old_data']);

    // フォーム送信先の設定
    $form_action = 'g19_customer_manage.php';
    $form_method = 'POST';
    $hidden_action = $is_new ? 'create' : 'update';
    
    ?>
    <p><a href="g19_customer_manage.php?action=list">一覧に戻る</a></p>

    <div class="form-box" style="max-width: 800px;">
        <?php if (!empty($errors)): ?>
            <div class="message error">
                <ul><?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form method="<?= $form_method ?>" action="<?= $form_action ?>">
            <input type="hidden" name="action" value="<?= $hidden_action ?>">
            <?php if (!$is_new): ?>
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_data['user_id']) ?>">
            <?php endif; ?>

            <table class="data-table">
                <tr><th><label for="last_name">姓 <span style="color:red;">*</span></label></th><td><input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user_data['last_name'] ?? '') ?>" required></td></tr>
                <tr><th><label for="first_name">名 <span style="color:red;">*</span></label></th><td><input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user_data['first_name'] ?? '') ?>" required></td></tr>
                <tr><th><label for="mail_address">メールアドレス <span style="color:red;">*</span></label></th><td><input type="text" id="mail_address" name="mail_address" value="<?= htmlspecialchars($user_data['mail_address'] ?? '') ?>" required></td></tr>
                <tr><th><label for="password">パスワード <?= $is_new ? '<span style="color:red;">*</span>' : '(変更時のみ)' ?></label></th><td><input type="password" id="password" name="password" placeholder="<?= $is_new ? '8文字以上' : '変更しない場合は空欄' ?>" <?= $is_new ? 'required' : '' ?>></td></tr>
                <tr><th><label for="zip_code">郵便番号</label></th><td><input type="text" id="zip_code" name="zip_code" value="<?= htmlspecialchars($user_data['zip_code'] ?? '') ?>"></td></tr>
                <tr><th><label for="prefecture">都道府県</label></th><td><input type="text" id="prefecture" name="prefecture" value="<?= htmlspecialchars($user_data['prefecture'] ?? '') ?>"></td></tr>
                <tr><th><label for="city">市区町村</label></th><td><input type="text" id="city" name="city" value="<?= htmlspecialchars($user_data['city'] ?? '') ?>"></td></tr>
                <tr><th><label for="town">町域</label></th><td><input type="text" id="town" name="town" value="<?= htmlspecialchars($user_data['town'] ?? '') ?>"></td></tr>
                <tr><th><label for="street_number">番地</label></th><td><input type="text" id="street_number" name="street_number" value="<?= htmlspecialchars($user_data['street_number'] ?? '') ?>"></td></tr>
                <tr><th><label for="building_name">建物名・部屋番号</label></th><td><input type="text" id="building_name" name="building_name" value="<?= htmlspecialchars($user_data['building_name'] ?? '') ?>"></td></tr>
                <tr><th><label for="birth_date">生年月日</label></th><td><input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($user_data['birth_date'] ?? '') ?>"></td></tr>
            </table>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-primary"><?= $is_new ? '登録' : '更新' ?>する</button>
            </div>
        </form>
    </div>
    <?php
} else {
    echo "<p class='message error'>不正なアクションです。</p>";
}

require_once __DIR__ . '/admin_footer.php';
?></main>
</div>
</body>
</html>