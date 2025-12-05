<?php
session_start();
// DB接続が必要です（cartフォルダから見てcommonフォルダは一つ上にあるため ../ ）
require_once '../common/db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート追加 - BOOK ON</title>
</head>
<body>

<?php
    // 送られてきたIDを取得
    $post_ids = isset($_POST['product_id']) ? $_POST['product_id'] : null;

    // IDがない場合は戻す
    if (empty($post_ids)) {
        echo '<script>alert("商品が選択されていません。"); history.back();</script>';
        exit;
    }

    // セッションの箱を用意
    if (!isset($_SESSION['product'])) {
        $_SESSION['product'] = [];
    }

    // --------------------------------------------------
    // パターンA：お気に入りからの「一括追加」 (IDが配列の場合)
    // --------------------------------------------------
    if (is_array($post_ids)) {
        
        try {
            $pdo = new PDO($connect, USER, PASS);
            
            // SQLの「IN句」を作るための準備 (?,?,?)
            $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
            
            // IDリストを元に、DBから足りない情報（名前、価格、画像など）を取得
            $sql = "SELECT p.*, a.author_name 
                    FROM products AS p 
                    LEFT JOIN author AS a ON p.author_id = a.author_id 
                    WHERE p.product_id IN ($placeholders)";
            
            $stmt = $pdo->prepare($sql);
            // 配列をそのままexecuteに渡して実行
            $stmt->execute($post_ids); 
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 取得した商品をループしてカートに入れる
            foreach ($rows as $row) {
                $id = $row['product_id'];
                
                // お気に入りからの追加は「電子書籍」として扱う（と仮定）
                // ※必要ならDBにformatカラムを持たせて判断してください
                $format_id = 1;
                $format_name = "電子書籍";
                $count = 1;

                $_SESSION['product'][$id] = [
                    'name' => $row['series_name'] . ' ' . $row['volume_number'],
                    'price' => $row['price'],
                    'count' => $count,
                    'product_img_url' => $row['product_img_url'],
                    'author_name' => $row['author_name'],
                    'format_id' => $format_id,
                    'format_name' => $format_name
                ];
            }
            
            $msg = count($rows) . '件の商品をカートに追加しました。';

        } catch (PDOException $e) {
            echo "DB Error: " . $e->getMessage();
            exit();
        }

    // --------------------------------------------------
    // パターンB：商品詳細からの「単体追加」 (IDが単一の値の場合)
    // --------------------------------------------------
    } else {
        
        $id = $post_ids;

        // フォーマットによって処理を変更
        // (詳細画面からは format, product_name, price などがPOSTで送られてくる前提)
        switch ($_POST['format']) {
            case 'digital':
                $format_id = 1;
                $format_name = "電子書籍";
                $count = 1;
                break;
            case 'book':
                $format_id = 2;
                $format_name = "紙書籍";
                $count = $_POST['count'];
                break;
        }

        $_SESSION['product'][$id] = [
            'name' => $_POST['product_name'],
            'price' => $_POST['price'],
            'count' => $count,
            'product_img_url' => $_POST['product_img_url'],
            'author_name' => $_POST['author_name'],
            'format_id' => $format_id,
            'format_name' => $format_name
        ];

        $msg = 'カートに追加しました。';
    }

    // 完了メッセージを出して戻る
    echo '<script>';
    echo 'alert(' . json_encode($msg) . ');';
    // お気に入りから来た場合は、お気に入りに戻るのが自然ですが、
    // history.back() だとチェックボックスが外れた状態に戻ります。
    echo 'history.back();';
    echo '</script>';
    exit;
?>
</body>
</html>