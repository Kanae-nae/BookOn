<?php require 'common/header.php'; ?>
<link rel="stylesheet" href="css/g3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <main class="search-page-main">
    
        <div class="search-top-section"> 
            
            <form action="g3_search.php" method="GET" class="search-form">
                <div class="search-container">
                    <input type="search" class="search-box" placeholder="キーワードで検索する" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>">
                    
                    <button type="submit" style="display: none;"></button>
                </div>
            </form>
            
            <?php
            // ★★★ PHPロジック (JOIN対応・全文検索版) ★★★
            require 'common/db-connect.php';
            $keyword = $_GET['keyword'] ?? '';

            if ($keyword !== '') {
                
                try {
                    $pdo = new PDO($connect, USER, PASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // SQL作成
                    // productsテーブルに、authorテーブルとgenreテーブルを結合(LEFT JOIN)
                    // 検索条件に author_name と genre_name を追加
                    $sql = "SELECT products.*, author.author_name, genre.genre_name 
                            FROM products 
                            LEFT JOIN author ON products.author_id = author.author_id
                            LEFT JOIN genre  ON products.genre_id  = genre.genre_id
                            WHERE products.series_name LIKE :keyword 
                               OR products.publisher   LIKE :keyword 
                               OR products.label       LIKE :keyword
                               OR author.author_name   LIKE :keyword 
                               OR genre.genre_name     LIKE :keyword";
                    
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
                            $id     = $row['product_id'];
                            $img    = $row['product_img_url'];
                            $price  = $row['price'];
                            
                            // タイトル
                            $title  = $row['series_name'] . ' ' . $row['volume_number'];
                            
                            // 結合したテーブルから作者名とジャンル名を取得
                            // (データがない場合は空文字になるのを防ぐため ?? '' を使用しても良いですが、今回はそのまま)
                            $author_name = $row['author_name']; 
                            $genre_name  = $row['genre_name'];

                            $label        = $row['label'];
                            $release_date = $row['release_date'];
                            
                            // レビュー全体の星の数と件数の算出
                            $sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt
                            FROM review
                            WHERE product_id = :product_id";

                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':product_id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                            $avg_rating = floatval($row['avg_rating']);
                            $avg_rating = round($avg_rating * 2) / 2;  // ★ 0.5刻みに正規化

                            $ratingNum = isset($avg_rating) && $avg_rating !== null
                            ? number_format($avg_rating, 1) : 0.0;

                            // HTML出力
                            echo '<a href="g2_detail.php?id=' . htmlspecialchars($id, ENT_QUOTES) . '" class="product-item">';
                            
                            // 画像
                            echo '<div class="product-image">';
                            echo '<img src="' . htmlspecialchars($img, ENT_QUOTES) . '" alt="' . htmlspecialchars($title, ENT_QUOTES) . '">';
                            echo '</div>';
                            
                            // 商品情報
                            echo '<div class="product-info">';
                            echo '<h3 class="product-title">' . htmlspecialchars($title, ENT_QUOTES) . '</h3>';
                            
                            // 作者名を表示 (以前は出版社だった箇所)
                            echo '<p class="product-author">' . htmlspecialchars($author_name, ENT_QUOTES) . '</p>';
                            
                            // 星評価
                            echo '<div class="rating-display">';
                                echo '<img src="image/rating/' . str_replace('.', '_', $ratingNum) . '.png"
                                        alt="' . $ratingNum . '" class="rating"/>';
                                echo '<span class="rating-score">' . htmlspecialchars($ratingNum, ENT_QUOTES) . '</span>';
                            echo '</div>';

                            // ジャンルを表示
                            echo '<p class="product-meta"><strong>ジャンル</strong> ' . htmlspecialchars($genre_name, ENT_QUOTES) . '</p>';
                            // レーベルも表示
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
                echo '<p class="search-helper-text">作品名・作者名・ジャンルなどで<br>検索可能です</p>';
            }
            ?>
            
        </div> 
    </main>

    <?php require 'common/menu.php'; ?>
    <?php require 'common/footer.php'; ?>

</body>
</html>