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
                    <input type-="search" class="search-box" placeholder="キーワードで検索する" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES); ?>">
                    
                    <button type="submit" style="display: none;"></button>
                </div>
            </form>
            
            <?php
            // ★★★ PHPロジック ★★★
            
            // 'keyword' がURLで送られてきて、かつ空でないかチェック
            if (!empty($_GET['keyword'])) {
                
                // --- 1. 検索が実行された場合の表示 ---
                
                // (これはダミーデータです。本来はデータベースから検索します)
                $keyword = $_GET['keyword']; // 検索キーワードを取得
                $total_items = 0;
                $total_pages = 0;
                $current_page = 1;
                $products = []; // 商品配列を初期化

                // ★ キーワードによってダミーデータを切り替える
                if (mb_strpos($keyword, 'チェンソーマン') !== false) {
                    // --- 「チェンソーマン」が検索された場合 ---
                    $total_items = 24; // 仮の合計件数
                    $total_pages = 3;  // 仮の合計ページ

                    $products = [
                        [
                            'id' => 1, // 商品ID (詳細ページ用)
                            'img' => 'image/tyen.png', // ★ あなたのファイル名
                            'title' => 'チェンソーマン 1',
                            'author' => '藤本タツキ',
                            'rating' => 4.0,
                            'reviews' => 5,
                            'series' => 'チェンソーマン',
                            'genre' => 'バトル・アクション',
                            'release_date' => '2019年3月4日',
                            'price' => '543～572円'
                        ],
                        [
                            'id' => 2,
                            'img' => 'image/tyen.png', // ★ あなたのファイル名
                            'title' => 'チェンソーマン 2',
                            'author' => '藤本タツキ',
                            'rating' => 4.2,
                            'reviews' => 8,
                            'series' => 'チェンソーマン',
                            'genre' => 'バトル・アクション',
                            'release_date' => '2019年5月2日',
                            'price' => '543～572円'
                        ]
                    ];

                } else {
                    // --- それ以外のキーワードが検索された場合 (例: 鬼滅) ---
                    $total_items = 1;
                    $total_pages = 1;

                    $products = [
                        [
                            'id' => 100, // 商品ID
                            'img' => 'image/kimetsu_23.jpg', // 既存の画像 (なければ tyen.pun に変えてください)
                            'title' => '鬼滅の刃 23',
                            'author' => '吾峠 呼世晴',
                            'rating' => 4.8,
                            'reviews' => 120,
                            'series' => '鬼滅の刃',
                            'genre' => 'バトル・アクション',
                            'release_date' => '2020年12月4日',
                            'price' => '506円'
                        ]
                    ];
                }
                // (ダミーデータここまで)


                // 検索結果の件数を表示
                echo '<div class="search-result-count">';
                echo htmlspecialchars($total_items, ENT_QUOTES) . '件見つかりました';
                echo ' (' . htmlspecialchars($current_page, ENT_QUOTES) . 'ページ/' . htmlspecialchars($total_pages, ENT_QUOTES) . 'ページ)';
                echo '</div>';

                // 商品リストを表示
                echo '<div class="product-list">';
                
                // 配列をループして商品アイテムを表示
                foreach ($products as $product) {
                    // 商品詳細ページへのリンク (仮で g2_detail.php を指定)
                    echo '<a href="g2_detail.php?id=' . $product['id'] . '" class="product-item">';
                    
                    // 商品画像
                    echo '<div class="product-image">';
                    echo '<img src="' . htmlspecialchars($product['img'], ENT_QUOTES) . '" alt="' . htmlspecialchars($product['title'], ENT_QUOTES) . '">';
                    echo '</div>';
                    
                    // 商品情報
                    echo '<div class="product-info">';
                    echo '<h3 class="product-title">' . htmlspecialchars($product['title'], ENT_QUOTES) . '</h3>';
                    echo '<p class="product-author">' . htmlspecialchars($product['author'], ENT_QUOTES) . '</p>';
                    
                    // 星評価 (Font Awesome使用)
                    echo '<div class="product-rating">';
                    echo '<span class="stars">';
                    // (簡易的に星4.0を表示)
                    echo '<i class="fas fa-star"></i>'; // 満星
                    echo '<i class="fas fa-star"></i>'; // 満星
                    echo '<i class="fas fa-star"></i>'; // 満星
                    echo '<i class="fas fa-star"></i>'; // 満星
                    echo '<i class="far fa-star"></i>'; // 空星
                    echo '</span>'; 
                    echo '<span class="rating-score">' . htmlspecialchars($product['rating'], ENT_QUOTES) . ' (' . htmlspecialchars($product['reviews'], ENT_QUOTES) . '件)</span>';
                    echo '</div>';

                    echo '<p class="product-meta"><strong>シリーズ</strong> ' . htmlspecialchars($product['series'], ENT_QUOTES) . '</p>';
                    echo '<p class="product-meta"><strong>ジャンル</strong> ' . htmlspecialchars($product['genre'], ENT_QUOTES) . '</p>';
                    echo '<p class="product-meta">' . htmlspecialchars($product['release_date'], ENT_QUOTES) . ' 発売</p>';
                    
                    // 価格 (一番下に表示されるように調整)
                    echo '<p class="product-price">' . htmlspecialchars($product['price'], ENT_QUOTES) . ' (税込)</p>';
                    
                    echo '</div>'; // .product-info
                    echo '</a>'; // .product-item
                }
                echo '</div>'; // .product-list

            } else {
                
                // --- 2. 検索前の場合 (キーワードが空) ---
                
                // ヘルパーテキストを表示
                echo '<p class="search-helper-text">作品名・作者名・ジャンルなどで<br>検索可能です</p>';
            }
            ?>
            
        </div> 
    </main>

    <?php require 'common/menu.php'; ?>
     <?php require 'common/footer.php'; ?>

</body>
</html>                                                                                                                                                   