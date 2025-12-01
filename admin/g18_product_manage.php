<?php
// admin/g18_product_manage.php - 商品管理 (一覧/登録/編集/削除を統合)

// ------------------------------------------------
// ★★★ 修正点 1: エラー表示を有効にする 
ini_set('display_errors', 1);
error_reporting(E_ALL);
// ------------------------------------------------

// 共通ファイルの読み込み
require_once __DIR__ . '/../config.php';

// ★★★ 修正点 2: session_start() はここで実行
session_start(); 
check_admin_login(); 

$page_title = "商品管理";
$action = $_REQUEST['action'] ?? 'list'; // URLからアクションを取得

$pdo = get_db_connect();
$errors = []; // バリデーションエラー用

// ------------------------------------------------
// 1. フォーム処理 (POSTリクエスト)
// ------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // データ取得
    $product_id = $_POST['product_id'] ?? null;
    $series_id = trim($_POST['series_id'] ?? '');
    $volume_number = trim($_POST['volume_number'] ?? '');
    $product_img_url = trim($_POST['product_img_url'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $stocks = (int)($_POST['stocks'] ?? 0);

    try {
        $pdo->beginTransaction();

        // --- 登録・更新処理 ---
        if ($action === 'create' || $action === 'update') {
            // バリデーション
            if (empty($series_id)) $errors[] = 'シリーズIDは必須です。';
            if ($price <= 0) $errors[] = '価格は1円以上に設定してください。';
            
            if (empty($errors)) {
                
                if ($action === 'create') {
                    // 新規登録
                    $sql = "INSERT INTO products (series_id, volume_number, product_img_url, price, stocks) 
                             VALUES (:series_id, :volume_number, :product_img_url, :price, :stocks)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':series_id', $series_id);
                    $stmt->bindValue(':volume_number', $volume_number ?: null);
                    $stmt->bindValue(':product_img_url', $product_img_url);
                    $stmt->bindValue(':price', $price, PDO::PARAM_INT);
                    $stmt->bindValue(':stocks', $stocks, PDO::PARAM_INT);
                    $stmt->execute();
                    $_SESSION['success_message'] = "商品を新規登録しました。";
                    $action = 'list'; // 一覧へ
                
                } elseif ($action === 'update' && $product_id) {
                    // 更新
                    $sql = "UPDATE products SET 
                             series_id = :series_id, volume_number = :volume_number, 
                             product_img_url = :product_img_url, price = :price, stocks = :stocks 
                             WHERE product_id = :product_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':series_id', $series_id);
                    $stmt->bindValue(':volume_number', $volume_number ?: null);
                    $stmt->bindValue(':product_img_url', $product_img_url);
                    $stmt->bindValue(':price', $price, PDO::PARAM_INT);
                    $stmt->bindValue(':stocks', $stocks, PDO::PARAM_INT);
                    $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $_SESSION['success_message'] = "商品ID {$product_id} の情報を更新しました。";
                    $action = 'list'; // 一覧へ
                }
            } else {
                // エラーがある場合はフォームに戻すために$actionを'edit'または'new'に戻す
                $action = $product_id ? 'edit' : 'new'; 
                $_SESSION['old_data'] = $_POST; // 入力値保持
            }
        } 
        
        // --- 削除処理（個別）---
        elseif ($action === 'delete_single' && $product_id) {
            $sql = "DELETE FROM products WHERE product_id = :product_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['success_message'] = "商品ID {$product_id} を削除しました。";
            $action = 'list'; // 一覧へ
        } 

        // --- 削除処理（複数選択）---
        elseif ($action === 'bulk_delete' && isset($_POST['selected_products']) && is_array($_POST['selected_products'])) {
            $product_ids = $_POST['selected_products'];
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            $sql = "DELETE FROM products WHERE product_id IN ({$placeholders})";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute($product_ids)) {
                $deleted_count = $stmt->rowCount();
                $_SESSION['success_message'] = "{$deleted_count}件の商品データを削除しました。";
            }
            $action = 'list'; // 一覧へ
        }

        $pdo->commit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) { $pdo->rollBack(); }
        $_SESSION['error_message'] = "データベースエラーが発生しました: " . $e->getMessage();
        $action = 'list'; // エラー時は一覧へ
    }
    
    // リダイレクト処理
    if ($action === 'list') {
        header('Location: g18_product_manage.php?action=list');
        exit;
    }
}


// ------------------------------------------------
// 2. 画面表示の準備 (GETリクエスト or 処理後のフォーム表示)
// ------------------------------------------------
require_once __DIR__ . '/admin_layout.php';

if ($action === 'list') { 
    // ======================================
    // ★★★ 商品一覧表示 ★★★
    // ======================================

    $page_title = "商品一覧";
    // 検索条件とソートの取得
    $search_title = $_GET['search_title'] ?? '';
    $sort_column = $_GET['sort'] ?? 'product_id';
    $sort_order = strtoupper($_GET['order'] ?? 'ASC');
    $valid_columns = ['product_id', 'series_id', 'volume_number', 'price', 'stocks']; 

    if (!in_array($sort_column, $valid_columns)) { $sort_column = 'product_id'; }
    if ($sort_order !== 'ASC' && $sort_order !== 'DESC') { $sort_order = 'ASC'; }

    $products = [];
    $where_clauses = [];
    $bind_values = [];

    // 検索条件の追加
    if (!empty($search_title)) {
        // ★★★ 修正箇所：s.title を s.series_name に修正 ★★★
        $where_clauses[] = "s.series_name LIKE :search_title";
        $bind_values[':search_title'] = '%' . $search_title . '%';
    }

    $where = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : '';

    try {
        $sql = "
            SELECT 
                p.product_id, p.series_id, p.volume_number, p.product_img_url, p.price, p.stocks, 
                s.series_name AS title  /* ★★★ 修正箇所：s.series_name を title として取得 ★★★ */
            FROM products p
            JOIN series s ON p.series_id = s.series_id
            {$where}
            ORDER BY {$sort_column} {$sort_order}
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($bind_values);
        $products = $stmt->fetchAll();

    } catch (Exception $e) {
        // SQLエラーがここでキャッチされる
        echo "<p class='message error'>商品データの取得に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        $products = [];
    }
    
    ?>

    <p><a href="g18_product_manage.php?action=new" class="btn-primary">新規商品登録</a></p>

    <form method="GET" action="g18_product_manage.php" class="form-box">
        <input type="hidden" name="action" value="list">
        <div class="form-group">
            <label for="search_title">作品名（シリーズ名）検索:</label>
            <input type="text" id="search_title" name="search_title" value="<?= htmlspecialchars($search_title) ?>">
        </div>
        <button type="submit" class="btn-primary">検索</button>
        <button type="button" onclick="location.href='g18_product_manage.php?action=list'" class="btn-secondary">リセット</button>
    </form>
    
    <form method="POST" action="g18_product_manage.php" onsubmit="return confirm('選択した商品を削除してもよろしいですか？');">
        <input type="hidden" name="action" value="bulk_delete">
        
        <table class="data-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th><a href="?action=list&search_title=<?= htmlspecialchars($search_title) ?>&sort=product_id&order=<?= ($sort_column == 'product_id' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">ID</a></th>
                    <th>作品名</th>
                    <th>巻数</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><input type="checkbox" name="selected_products[]" value="<?= htmlspecialchars($product['product_id']) ?>"></td>
                    <td><?= htmlspecialchars($product['product_id']) ?></td>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['volume_number']) ?></td>
                    <td>¥<?= number_format(htmlspecialchars($product['price'])) ?></td>
                    <td><?= htmlspecialchars($product['stocks']) ?></td>
                    <td>
                        <a href="g18_product_manage.php?action=edit&id=<?= htmlspecialchars($product['product_id']) ?>">編集</a>
                        <a href="#" onclick="if(confirm('商品ID:<?= $product['product_id'] ?>を削除しますか？')){document.getElementById('delete_form_<?= $product['product_id'] ?>').submit(); return false;}">削除</a>
                        <form id="delete_form_<?= $product['product_id'] ?>" method="POST" action="g18_product_manage.php" style="display:none;">
                            <input type="hidden" name="action" value="delete_single">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top: 15px;">
            <button type="submit" class="btn-danger">選択商品を削除</button>
        </p>
    </form>
    <script>
        document.getElementById('check-all').onclick = function() {
            const isChecked = this.checked;
            document.querySelectorAll('input[name="selected_products[]"]').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        };
    </script>
    
    <?php
} elseif ($action === 'new' || $action === 'edit') {
    // ======================================
    // ★★★ 登録・編集フォーム表示 ★★★
    // ======================================
    
    $product_id = $_GET['id'] ?? ($_SESSION['old_data']['product_id'] ?? null);
    $is_new = ($action === 'new');
    $page_title = $is_new ? "新規商品登録" : "商品情報編集 (ID: {$product_id})";

    $product_data = [
        'product_id' => '', 'series_id' => '', 'volume_number' => '', 
        'product_img_url' => '', 'price' => '', 'stocks' => ''
    ];

    // 編集時：既存データ取得
    if (!$is_new && $product_id) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :id");
            $stmt->bindValue(':id', (int)$product_id, PDO::PARAM_INT);
            $stmt->execute();
            $product_data = $stmt->fetch() ?: $product_data;
        } catch (Exception $e) {
            $errors[] = "データ取得エラー: " . $e->getMessage();
        }
    }

    // 入力値保持セッションからのデータ復元
    $product_data = $_SESSION['old_data'] ?? $product_data;
    unset($_SESSION['old_data']);

    // フォーム送信先の設定
    $form_action = 'g18_product_manage.php';
    $form_method = 'POST';
    $hidden_action = $is_new ? 'create' : 'update';
    
    ?>
    <p><a href="g18_product_manage.php?action=list">一覧に戻る</a></p>

    <div class="form-box">
        <?php if (!empty($errors)): ?>
            <div class="message error">
                <ul><?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form method="<?= $form_method ?>" action="<?= $form_action ?>">
            <input type="hidden" name="action" value="<?= $hidden_action ?>">
            <?php if (!$is_new): ?>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_data['product_id']) ?>">
            <?php endif; ?>

            <table class="data-table">
                <tr>
                    <th><label for="series_id">シリーズID <span style="color:red;">*</span></label></th>
                    <td><input type="text" id="series_id" name="series_id" value="<?= htmlspecialchars($product_data['series_id']) ?>" required></td>
                </tr>
                <tr>
                    <th><label for="volume_number">巻数</label></th>
                    <td><input type="number" id="volume_number" name="volume_number" value="<?= htmlspecialchars($product_data['volume_number']) ?>" min="1"></td>
                </tr>
                <tr>
                    <th><label for="price">価格 <span style="color:red;">*</span></label></th>
                    <td><input type="number" id="price" name="price" value="<?= htmlspecialchars($product_data['price']) ?>" min="1" required></td>
                </tr>
                <tr>
                    <th><label for="stocks">在庫数</label></th>
                    <td><input type="number" id="stocks" name="stocks" value="<?= htmlspecialchars($product_data['stocks']) ?>" min="0"></td>
                </tr>
                <tr>
                    <th><label for="product_img_url">画像URL</label></th>
                    <td><input type="text" id="product_img_url" name="product_img_url" value="<?= htmlspecialchars($product_data['product_img_url']) ?>"></td>
                </tr>
            </table>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-primary"><?= $is_new ? '登録' : '更新' ?>する</button>
            </div>
        </form>
    </div>
    <?php
} else {
    // 不正なアクション
    echo "<p class='message error'>不正なアクションです。</p>";
}

// ★★★ g18_product_manage.phpはここでHTMLを閉じている (修正済み) ★★★
?>
    </main>
</div>
</body>
</html>