<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索 - BOOK ON</title>
    
    <link rel="stylesheet" href="css/g3.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <?php require 'common/header.php'; ?>

    <main class="search-page-main">
    
        <div class="search-top-section"> 
            
            <form action="g3_search.php" method="GET" class="search-form">
                <div class="search-container">
                    <input type="search" class="search-box" placeholder="キーワードで検索する" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>">
                    
                    <button type="submit" style="display: none;"></button>
                </div>
            </form>
            
            <?php
            // ★★★ PHPロジック (DB接続版：カラム名修正済み) ★★★
            
            $keyword = $_GET['keyword'] ?? '';

            if ($keyword !== '') {
                
                try {
                    // ▼▼▼ 設定エリア (Lolipop設定) ▼▼▼
                    $db_host = 'mysql323.phy.lolipop.lan';
                    $db_name = 'LAA1658836-bookon';
                    $db_user = 'LAA1658836';
                    $db_pass = 'passbookon';
                    $db_char = 'utf8';
                    // ▲▲▲ 設定エリア終了 ▲▲▲

                    $dsn = "mysql:dbname={$db_name};host={$db_host};charset={$db_char}";
                    
                    $pdo = new PDO($dsn, $db_user, $db_pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // SQL作成
                    // ★修正★: title列がないので、series_name(シリーズ名) や publisher(出版社) を検索対象に変更
                    // ※ テーブル名が 'products' でない場合はここも修正が必要です
                    $sql = "SELECT * FROM products 
                            WHERE series_name LIKE :keyword 
                               OR publisher LIKE :keyword 
                               OR label LIKE :keyword";
                    
                    $stmt = $pdo->prepare($sql);

                    $searchTerm = '%' . $keyword . '%';
                    $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);

                    $stmt->execute();
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $total_items = count($products);

                    // --- 結果表示 ---
                    echo '<div class="search-result-count">';
                    echo htmlspecialchars($total_items, ENT_QUOTES) . '件見つかりました';
                    echo '</div>';

                    if ($total_items > 0) {
                        echo '<div class="product-list">';
                        
                        foreach ($products as $row) {
                            // ★修正★: DBの実際のカラム名に合わせてデータを取得
                            $id     = $row['product_id'];           // product_id
                            $img    = $row['product_img_url'];      // product_img_url
                            $price  = $row['price'];                // price
                            
                            // タイトルとして「シリーズ名 + 巻数」を表示
                            $title  = $row['series_name'] . ' ' . $row['volume_number'];
                            
                            // 作者カラムがないため、出版社を表示に使用
                            $publisher = $row['publisher']; 

                            // その他
                            $series       = $row['series_name'];
                            $label        = $row['label'];
                            $release_date = $row['release_date'];
                            
                            // 評価などはDBにないので仮の値 (必要ならカラムを追加してください)
                            $rating       = 4.0; 
                            $reviews      = 0;

                            // HTML出力
                            echo '<a href="g2_detail.php?id=' . htmlspecialchars($id, ENT_QUOTES) . '" class="product-item">';
                            
                            // 画像
                            echo '<div class="product-image">';
                            // 画像パスが正しいか注意してください
                            echo '<img src="' . htmlspecialchars($img, ENT_QUOTES) . '" alt="' . htmlspecialchars($title, ENT_QUOTES) . '">';
                            echo '</div>';
                            
                            // 商品情報
                            echo '<div class="product-info">';
                            echo '<h3 class="product-title">' . htmlspecialchars($title, ENT_QUOTES) . '</h3>';
                            
                            // 作者の代わりに出版社を表示
                            echo '<p class="product-author">' . htmlspecialchars($publisher, ENT_QUOTES) . '</p>';
                            
                            // 星評価 (仮)
                            echo '<div class="product-rating">';
                            echo '<span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></span>'; 
                            echo '<span class="rating-score">' . htmlspecialchars($rating, ENT_QUOTES) . '</span>';
                            echo '</div>';

                            echo '<p class="product-meta"><strong>レーベル</strong> ' . htmlspecialchars($label, ENT_QUOTES) . '</p>';
                            echo '<p class="product-meta">' . htmlspecialchars($release_date, ENT_QUOTES) . ' 発売</p>';
                            
                            // 価格
                            echo '<p class="product-price">' . number_format($price) . '円 (税込)</p>';
                            
                            echo '</div>'; // .product-info
                            echo '</a>'; // .product-item
                        }
                        echo '</div>'; // .product-list

                    } else {
                        echo '<p style="margin-top:20px; text-align:center;">検索条件に一致する商品は見つかりませんでした。</p>';
                    }

                } catch (PDOException $e) {
                    echo '<p style="color:red;">エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
                }

            } else {
                echo '<p class="search-helper-text">作品名・出版社などで<br>検索可能です</p>';
            }
            ?>
            
        </div> 
    </main>

    <?php require 'common/menu.php'; ?>
    <?php require 'common/footer.php'; ?>

</body>
</html>