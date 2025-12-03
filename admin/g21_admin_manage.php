<?php
// admin/g21_admin_manage.php - 管理者管理 (一覧/登録/編集/削除を統合)

require_once __DIR__ . '/../config.php';
session_start();
check_admin_login(); 

$page_title = "管理者管理";
$action = $_REQUEST['action'] ?? 'list'; // URLからアクションを取得

$pdo = get_db_connect();
$errors = []; 
$admin_types = []; // 権限タイプ一覧

// 権限タイプ一覧の取得
try {
    $stmt = $pdo->query("SELECT admin_type_id, admin_type FROM admin_type ORDER BY admin_type_id ASC");
    $admin_types = $stmt->fetchAll();
} catch (Exception $e) {
    $_SESSION['error_message'] = "権限タイプデータの取得に失敗しました: " . $e->getMessage();
}


// ------------------------------------------------
// 1. フォーム処理 (POSTリクエスト)
// ------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // データ取得
    $admin_id = $_POST['admin_id'] ?? null;
    $admin_name = trim($_POST['admin_name'] ?? '');
    $admin_address = trim($_POST['admin_address'] ?? '');
    $password = $_POST['pass'] ?? ''; // フォーム側は name="pass"
    $admin_type_id = $_POST['admin_type_id'] ?? null;
    $add_date = date('Y-m-d H:i:s');

    // 入力値保持セッションに保存 (失敗時用)
    $_SESSION['old_data'] = [
        'admin_id' => $admin_id, 'admin_name' => $admin_name, 
        'admin_address' => $admin_address, 'admin_type_id' => $admin_type_id
    ];

    try {
        $pdo->beginTransaction();

        // --- 登録・更新処理 ---
        if ($action === 'create' || $action === 'update') {
            
            // バリデーション
            if (empty($admin_name)) $errors[] = '管理者名を入力してください。';
            if (!filter_var($admin_address, FILTER_VALIDATE_EMAIL)) $errors[] = '有効なメールアドレスを入力してください。';
            if ($action === 'create' && (empty($password) || strlen($password) < 8)) $errors[] = 'パスワードは8文字以上で入力してください。';
            if ($action === 'update' && !empty($password) && strlen($password) < 8) $errors[] = '新しいパスワードは8文字以上で入力してください。';
            if (empty($admin_type_id)) $errors[] = '権限タイプを選択してください。';

            // メールアドレス重複チェック
            $sql_check = "SELECT admin_id FROM `admin` WHERE admin_address = :address" 
                . ($action === 'update' ? " AND admin_id != :id" : "");
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindValue(':address', $admin_address);
            if ($action === 'update') $stmt_check->bindValue(':id', $admin_id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) $errors[] = 'そのメールアドレスは既に使用されています。';

            if (empty($errors)) {
                
                if ($action === 'create') {
                    // 新規登録
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO `admin` (admin_name, admin_address, pass, admin_type_id, add_date) 
                            VALUES (:name, :address, :pass, :type_id, :add_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':pass', $hashed_password);
                    $stmt->bindValue(':add_date', $add_date);
                    
                    $_SESSION['success_message'] = "新規管理者『{$admin_name}』を登録しました。";
                    $action = 'list';
                
                } elseif ($action === 'update' && $admin_id) {
                    // 更新
                    $sql = "UPDATE `admin` SET 
                            admin_name = :name, admin_address = :address, admin_type_id = :type_id
                            " . (!empty($password) ? ", pass = :pass" : "") . "
                            WHERE admin_id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $admin_id, PDO::PARAM_INT);
                    if (!empty($password)) {
                        $hashed_password = password_hash($password, PDO::PARAM_INT);
                        $stmt->bindValue(':pass', $hashed_password);
                    }
                    $_SESSION['success_message'] = "管理者ID {$admin_id} の情報を更新しました。";
                    $action = 'list';
                }

                // 共通バインド
                $stmt->bindValue(':name', $admin_name);
                $stmt->bindValue(':address', $admin_address);
                $stmt->bindValue(':type_id', (int)$admin_type_id, PDO::PARAM_INT);
                $stmt->execute();
                
                unset($_SESSION['old_data']);

            } else {
                $_SESSION['error_message'] = implode('<br>', $errors);
                $action = $admin_id ? 'edit' : 'new'; 
            }
        } 
        
        // --- 削除処理（個別）---
        elseif ($action === 'delete_single' && $admin_id) {
            $sql = "DELETE FROM `admin` WHERE admin_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $admin_id, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['success_message'] = "管理者ID {$admin_id} を削除しました。";
            $action = 'list';
        } 

        // --- 削除処理（複数選択）---
        elseif ($action === 'bulk_delete' && isset($_POST['selected_admins']) && is_array($_POST['selected_admins'])) {
            $admin_ids = $_POST['selected_admins'];
            $placeholders = implode(',', array_fill(0, count($admin_ids), '?'));
            $sql = "DELETE FROM `admin` WHERE admin_id IN ({$placeholders})";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($admin_ids);
            
            $deleted_count = $stmt->rowCount();
            $_SESSION['success_message'] = "{$deleted_count}件の管理者データを削除しました。";
            $action = 'list'; 
        }

        $pdo->commit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) { $pdo->rollBack(); }
        $_SESSION['error_message'] = "データベースエラーが発生しました: " . $e->getMessage();
        $action = 'list';
    }
    
    // リダイレクト処理
    if ($action === 'list') {
        header('Location: g21_admin_manage.php?action=list');
        exit;
    } elseif ($action === 'new') {
        header('Location: g21_admin_manage.php?action=new');
        exit;
    } elseif ($action === 'edit') {
        header('Location: g21_admin_manage.php?action=edit&id=' . $admin_id);
        exit;
    }
}


// ------------------------------------------------
// 2. 画面表示の準備
// ------------------------------------------------
require_once __DIR__ . '/admin_layout.php';

if ($action === 'list') { 
    // ======================================
    // ★★★ 管理者一覧表示 ★★★
    // ======================================

    $page_title = "管理者一覧";
    // ソートの取得
    $sort_column = $_GET['sort'] ?? 'admin_id';
    $sort_order = strtoupper($_GET['order'] ?? 'ASC');
    $valid_columns = ['admin_id', 'admin_name', 'admin_address', 'admin_type', 'add_date']; 

    if (!in_array($sort_column, $valid_columns)) { $sort_column = 'admin_id'; }
    if ($sort_order !== 'ASC' && $sort_order !== 'DESC') { $sort_order = 'ASC'; }

    $admins = [];
    try {
        $sql = "
            SELECT 
                a.admin_id, a.admin_name, a.admin_address, a.add_date, 
                at.admin_type
            FROM `admin` a
            JOIN `admin_type` at ON a.admin_type_id = at.admin_type_id
            ORDER BY {$sort_column} {$sort_order}
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $admins = $stmt->fetchAll();

    } catch (Exception $e) {
        echo "<p class='message error'>管理者データの取得に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        $admins = [];
    }
    
    ?>

    <p><a href="g21_admin_manage.php?action=new" class="btn-primary">新規管理者追加</a></p>
    
    <form method="POST" action="g21_admin_manage.php" onsubmit="return confirm('選択した管理者を削除してもよろしいですか？');">
        <input type="hidden" name="action" value="bulk_delete">
        
        <table class="data-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th><a href="?action=list&sort=admin_id&order=<?= ($sort_column == 'admin_id' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">ID</a></th>
                    <th><a href="?action=list&sort=admin_name&order=<?= ($sort_column == 'admin_name' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">管理者名</a></th>
                    <th>メールアドレス</th>
                    <th><a href="?action=list&sort=admin_type&order=<?= ($sort_column == 'admin_type' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">権限タイプ</a></th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><input type="checkbox" name="selected_admins[]" value="<?= htmlspecialchars($admin['admin_id']) ?>"></td>
                    <td><?= htmlspecialchars($admin['admin_id']) ?></td>
                    <td><?= htmlspecialchars($admin['admin_name']) ?></td>
                    <td><?= htmlspecialchars($admin['admin_address']) ?></td>
                    <td><?= htmlspecialchars($admin['admin_type']) ?></td>
                    <td><?= htmlspecialchars($admin['add_date']) ?></td>
                    <td>
                        <a href="g21_admin_manage.php?action=edit&id=<?= htmlspecialchars($admin['admin_id']) ?>">編集</a>
                        <a href="#" onclick="if(confirm('管理者ID:<?= $admin['admin_id'] ?>を削除しますか？')){document.getElementById('delete_form_<?= $admin['admin_id'] ?>').submit(); return false;}">削除</a>
                        <form id="delete_form_<?= $admin['admin_id'] ?>" method="POST" action="g21_admin_manage.php" style="display:none;">
                            <input type="hidden" name="action" value="delete_single">
                            <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin['admin_id']) ?>">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top: 15px;">
            <button type="submit" class="btn-danger">選択管理者を削除</button>
        </p>
    </form>
    <script>
        document.getElementById('check-all').onclick = function() {
            const isChecked = this.checked;
            document.querySelectorAll('input[name="selected_admins[]"]').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        };
    </script>
    
    <?php
} elseif ($action === 'new' || $action === 'edit') {
    // ======================================
    // ★★★ 登録・編集フォーム表示 ★★★
    // ======================================
    
    $admin_id = $_GET['id'] ?? ($_SESSION['old_data']['admin_id'] ?? null);
    $is_new = ($action === 'new');
    $page_title = $is_new ? "新規管理者追加" : "管理者情報編集 (ID: {$admin_id})";

    $admin_data = [];

    // 編集時：既存データ取得
    if (!$is_new && $admin_id) {
        try {
            $stmt = $pdo->prepare("SELECT admin_id, admin_name, admin_address, admin_type_id FROM `admin` WHERE admin_id = :id");
            $stmt->bindValue(':id', (int)$admin_id, PDO::PARAM_INT);
            $stmt->execute();
            $admin_data = $stmt->fetch();
            if (!$admin_data) {
                echo "<p class='message error'>指定された管理者IDのデータが見つかりませんでした。</p>";
                require_once __DIR__ . '/admin_footer.php';
                exit;
            }
        } catch (Exception $e) {
            $errors[] = "データ取得エラー: " . $e->getMessage();
        }
    }

    // 入力値保持セッションからのデータ復元
    $admin_data = array_merge($admin_data, $_SESSION['old_data'] ?? []);
    unset($_SESSION['old_data']);

    // フォーム送信先の設定
    $form_action = 'g21_admin_manage.php';
    $form_method = 'POST';
    $hidden_action = $is_new ? 'create' : 'update';
    
    ?>
    <p><a href="g21_admin_manage.php?action=list">一覧に戻る</a></p>

    <div class="form-box">
        <?php if (!empty($errors) && !isset($_SESSION['error_message'])): ?>
            <div class="message error">
                <ul><?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form method="<?= $form_method ?>" action="<?= $form_action ?>">
            <input type="hidden" name="action" value="<?= $hidden_action ?>">
            <?php if (!$is_new): ?>
                <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin_data['admin_id']) ?>">
            <?php endif; ?>

            <table class="data-table">
                <tr><th><label for="admin_name">管理者名 <span style="color:red;">*</span></label></th><td><input type="text" id="admin_name" name="admin_name" value="<?= htmlspecialchars($admin_data['admin_name'] ?? '') ?>" required></td></tr>
                <tr><th><label for="admin_address">メールアドレス <span style="color:red;">*</span></label></th><td><input type="text" id="admin_address" name="admin_address" value="<?= htmlspecialchars($admin_data['admin_address'] ?? '') ?>" required></td></tr>
                <tr><th><label for="password">パスワード <?= $is_new ? '<span style="color:red;">*</span>' : '(変更時のみ)' ?></label></th><td>
                    <p style="margin-bottom: 10px; font-size: 0.9em; color: #856404;">※ 変更しない場合は、**空欄のまま**でお願いします。</p>
                    <input type="password" id="password" name="pass" placeholder="<?= $is_new ? '8文字以上' : '' ?>" <?= $is_new ? 'required' : '' ?>>
                </td></tr>
                <tr><th><label for="admin_type_id">権限タイプ <span style="color:red;">*</span></label></th><td>
                    <select id="admin_type_id" name="admin_type_id" required>
                        <option value="">選択してください</option>
                        <?php 
                        $current_type_id = $admin_data['admin_type_id'] ?? null;
                        foreach ($admin_types as $type): ?>
                        <option value="<?= htmlspecialchars($type['admin_type_id']) ?>"
                            <?= ((string)$current_type_id === $type['admin_type_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['admin_type']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </td></tr>
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
?>
</main>
</div>
</body>
</html>