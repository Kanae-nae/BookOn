<?php
// admin/g20_order_manage.php - 注文管理 (一覧/詳細/ステータス更新を統合)

require_once __DIR__ . '/../config.php';
session_start();
check_admin_login(); 

$page_title = "注文管理";
$action = $_REQUEST['action'] ?? 'list'; // URLからアクションを取得

$pdo = get_db_connect();
$statuses = []; // ステータス一覧

// データベースから注文ステータス一覧を取得
try {
    $stmt = $pdo->query("SELECT status_id, status FROM `order_status` ORDER BY status_id ASC");
    $statuses = $stmt->fetchAll();
} catch (Exception $e) {
    $_SESSION['error_message'] = "ステータスデータの取得に失敗しました: " . $e->getMessage();
}


// ------------------------------------------------
// 1. フォーム処理 (POSTリクエスト)
// ------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $errors = [];

    try {
        $pdo->beginTransaction();

        // --- ステータス更新処理 ---
        if ($action === 'update_status' && $order_id) {
            $new_status_id = $_POST['new_status_id'] ?? null;
            
            if (!$new_status_id || !is_numeric($new_status_id)) {
                $errors[] = "新しいステータスが指定されていません。";
            }
            
            if (empty($errors)) {
                $sql = "UPDATE `orders` SET status_id = :status_id WHERE order_id = :order_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':status_id', $new_status_id, PDO::PARAM_INT);
                $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->execute();
                
                $_SESSION['success_message'] = "注文ID {$order_id} のステータスを更新しました。";
                $action = 'detail'; // 詳細画面に戻る
                $pdo->commit();

            } else {
                $_SESSION['error_message'] = implode('<br>', $errors);
                $action = 'detail';
            }
        } else {
            // その他の処理 (例: 複数削除)
            // 今回はステータス更新のみ実装
        }

    } catch (Exception $e) {
        if ($pdo->inTransaction()) { $pdo->rollBack(); }
        $_SESSION['error_message'] = "データベースエラーが発生しました: " . $e->getMessage();
        $action = 'list';
    }
    
    // リダイレクト処理
    if ($action === 'detail' && $order_id) {
        header('Location: g20_order_manage.php?action=detail&id=' . $order_id);
        exit;
    } elseif ($action === 'list') {
        header('Location: g20_order_manage.php?action=list');
        exit;
    }
}


// ------------------------------------------------
// 2. 画面表示の準備
// ------------------------------------------------
require_once __DIR__ . '/admin_layout.php';

if ($action === 'list') { 
    // ======================================
    // ★★★ 注文一覧表示 ★★★
    // ======================================

    $page_title = "注文一覧";
    // 検索条件とソートの取得
    $search_id = $_GET['search_id'] ?? '';
    $search_name = $_GET['search_name'] ?? '';
    $search_status = $_GET['search_status'] ?? '';

    $sort_column = $_GET['sort'] ?? 'order_date';
    $sort_order = strtoupper($_GET['order'] ?? 'DESC');
    $valid_columns = ['order_id', 'user_name', 'total_price', 'status_name', 'order_date'];

    if (!in_array($sort_column, $valid_columns)) { $sort_column = 'order_date'; }
    if ($sort_order !== 'ASC' && $sort_order !== 'DESC') { $sort_order = 'DESC'; }

    $orders = [];
    $where_clauses = [];
    $bind_values = [];

    // 検索条件の追加
    if (!empty($search_id)) {
        $where_clauses[] = "o.order_id = :search_id";
        $bind_values[':search_id'] = $search_id;
    }
    if (!empty($search_name)) {
        $where_clauses[] = "CONCAT(u.last_name, u.first_name) LIKE :search_name";
        $bind_values[':search_name'] = '%' . $search_name . '%';
    }
    if (!empty($search_status)) {
        $where_clauses[] = "os.status_id = :search_status";
        $bind_values[':search_status'] = $search_status;
    }

    $where = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : '';

    try {
        $sql = "
            SELECT 
                o.order_id, o.total_price, o.order_date, o.status_id, 
                CONCAT(u.last_name, ' ', u.first_name) AS user_name, 
                os.status AS status_name
            FROM `orders` o
            JOIN `user` u ON o.user_id = u.user_id
            JOIN `order_status` os ON o.status_id = os.status_id
            {$where}
            ORDER BY {$sort_column} {$sort_order}
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($bind_values);
        $orders = $stmt->fetchAll();

    } catch (Exception $e) {
        echo "<p class='message error'>注文データの取得に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        $orders = [];
    }
    
    ?>

    <form method="GET" action="g20_order_manage.php" class="form-box">
        <input type="hidden" name="action" value="list">
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <div>
                <label for="search_id">注文ID:</label>
                <input type="text" id="search_id" name="search_id" value="<?= htmlspecialchars($search_id) ?>" style="width: 100px;">
            </div>
            <div>
                <label for="search_name">氏名:</label>
                <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($search_name) ?>" style="width: 150px;">
            </div>
            <div>
                <label for="search_status">ステータス:</label>
                <select id="search_status" name="search_status">
                    <option value="">全て</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= htmlspecialchars($status['status_id']) ?>" <?= ($search_status == $status['status_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['status']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="align-self: flex-end;">
                <button type="submit" class="btn-primary" style="margin-right: 0;">検索</button>
                <button type="button" onclick="location.href='g20_order_manage.php?action=list'" class="btn-secondary">リセット</button>
            </div>
        </div>
    </form>
    
    <table class="data-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="check-all" disabled></th>
                <th><a href="?action=list&search_id=<?= htmlspecialchars($search_id) ?>&search_name=<?= htmlspecialchars($search_name) ?>&search_status=<?= htmlspecialchars($search_status) ?>&sort=order_id&order=<?= ($sort_column == 'order_id' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">ID</a></th>
                <th>氏名</th>
                <th>合計金額</th>
                <th>ステータス</th>
                <th><a href="?action=list&search_id=<?= htmlspecialchars($search_id) ?>&search_name=<?= htmlspecialchars($search_name) ?>&search_status=<?= htmlspecialchars($search_status) ?>&sort=order_date&order=<?= ($sort_column == 'order_date' && $sort_order == 'DESC') ? 'ASC' : 'DESC' ?>">注文日時</a></th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><input type="checkbox" name="selected_orders[]" value="<?= htmlspecialchars($order['order_id']) ?>" disabled></td>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['user_name']) ?></td>
                <td>¥<?= number_format(htmlspecialchars($order['total_price'])) ?></td>
                <td><?= htmlspecialchars($order['status_name']) ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
                <td>
                    <a href="g20_order_manage.php?action=detail&id=<?= htmlspecialchars($order['order_id']) ?>">詳細</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php

} elseif ($action === 'detail') {
    // ======================================
    // ★★★ 注文詳細表示・ステータス更新フォーム ★★★
    // ======================================
    $order_id = $_GET['id'] ?? null;
    if (!$order_id) {
        $_SESSION['error_message'] = '注文IDが指定されていません。';
        header('Location: g20_order_manage.php?action=list');
        exit;
    }

    $order = [];
    $items = [];

    try {
        // A. 注文概要の取得
        $sql_order = "
            SELECT 
                o.*, 
                CONCAT(u.last_name, ' ', u.first_name) AS user_name, 
                u.mail_address,
                os.status AS status_name
            FROM `orders` o
            JOIN `user` u ON o.user_id = u.user_id
            JOIN `order_status` os ON o.status_id = os.status_id
            WHERE o.order_id = :order_id
        ";
        $stmt_order = $pdo->prepare($sql_order);
        $stmt_order->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_order->execute();
        $order = $stmt_order->fetch();

        if (!$order) {
            echo "<p class='message error'>指定された注文IDのデータが見つかりませんでした。</p>";
            require_once __DIR__ . '/admin_footer.php';
            exit;
        }
        
        // B. 注文アイテムの詳細取得
        $sql_items = "
            SELECT 
                oi.*,
                s.series_name AS title
            FROM `order_items` oi
            JOIN `products` p ON oi.product_id = p.product_id
            JOIN `series` s ON p.series_id = s.series_id
            WHERE oi.order_id = :order_id
        ";
        $stmt_items = $pdo->prepare($sql_items);
        $stmt_items->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt_items->execute();
        $items = $stmt_items->fetchAll();

    } catch (Exception $e) {
        echo "<p class='message error'>詳細データの取得に失敗しました: " . htmlspecialchars($e->getMessage()) . "</p>";
        require_once __DIR__ . '/admin_footer.php';
        exit;
    }

    $page_title = "注文詳細 (ID: {$order['order_id']})";
    ?>

    <p><a href="g20_order_manage.php?action=list">一覧に戻る</a></p>

    <div style="display: flex; gap: 30px; margin-bottom: 30px;">
        <div class="form-box" style="flex: 1;">
            <h2>注文概要</h2>
            <table class="data-table">
                <tr><th>注文ID</th><td><?= htmlspecialchars($order['order_id']) ?></td></tr>
                <tr><th>注文日時</th><td><?= htmlspecialchars($order['order_date']) ?></td></tr>
                <tr><th>合計金額</th><td>¥<?= number_format(htmlspecialchars($order['total_price'])) ?></td></tr>
                <tr><th>現在のステータス</th><td><span style="font-weight: bold; color: <?= ($order['status_id'] >= 4) ? 'green' : 'orange' ?>;"><?= htmlspecialchars($order['status_name']) ?></span></td></tr>
                <tr><th>顧客名</th><td><?= htmlspecialchars($order['user_name']) ?></td></tr>
                <tr><th>メールアドレス</th><td><?= htmlspecialchars($order['mail_address']) ?></td></tr>
            </table>
        </div>
        
        <div class="form-box" style="flex: 1;">
            <h2>お届け先</h2>
            <table class="data-table">
                <tr><th>郵便番号</th><td><?= htmlspecialchars($order['zip_code']) ?></td></tr>
                <tr><th>住所</th><td>
                    <?= htmlspecialchars($order['prefecture']) ?><br>
                    <?= htmlspecialchars($order['city']) ?><br>
                    <?= htmlspecialchars($order['town']) ?>
                    <?= htmlspecialchars($order['street_number']) ?>
                    <?= htmlspecialchars($order['building_name']) ?>
                </td></tr>
            </table>
        </div>
    </div>

    <h2>注文商品一覧</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>商品ID</th>
                <th>作品名</th>
                <th>巻数</th>
                <th>価格</th>
                <th>数量</th>
                <th>小計</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_id']) ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['volume_number']) ?></td>
                <td>¥<?= number_format(htmlspecialchars($item['price'])) ?></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td>¥<?= number_format(htmlspecialchars($item['price'] * $item['quantity'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 style="margin-top: 30px;">注文ステータス変更</h2>
    <form action="g20_order_manage.php" method="POST" class="form-box" style="max-width: none;">
        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
        <input type="hidden" name="action" value="update_status">
        
        <div class="form-group">
            <label for="new_status_id">新しいステータス:</label>
            <select id="new_status_id" name="new_status_id" required>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= htmlspecialchars($status['status_id']) ?>" 
                        <?= ($status['status_id'] == $order['status_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($status['status']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="btn-primary">ステータスを更新する</button>
    </form>

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