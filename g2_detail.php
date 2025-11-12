<!-- 商品詳細画面(G2) -->
 <?php
// header.phpを読み込む (後でPHPロジックを追加する際に使用)
require 'common/header.php';
// session_start()は通常header.phpに含まれている
?>

<link rel="stylesheet" href="css/g2.css">

<?php
// PHPの仮データ定義部分 (ロジックは後で追加)
// $product や $quantity の定義は省略し、HTMLに直接ダミーデータを記述します。
?>

<main>
    <div class="product-detail-container">
        <div class="back-link">
            <a href="#">
                <span>＜ 戻る</span>
            </a>
        </div>

        <h1 class="product-title">チェンソーマン 22巻 | ジャンプコミックス</h1>
        <div class="product-author"><a href="#">藤本タツキ</a></div>
        <div class="rating">
            <span class="stars">★★★★☆</span>
            <span class="score">4.0</span>
            <span class="review-count">(5件)</span>
        </div>

        <div class="product-content">
            <div class="product-image-section">
                <div class="product-image">
                    <img src="image/che-n22.jpg" alt="チェンソーマン 22巻">
                </div>
                </div>
            <div class="purchase-section">
                <div class="price-quantity-container">
                    <p class="price-display">
                        <span class="currency">572円</span> 
                        <span class="tax">(税込)</span>
                    </p>
                    <div class="quantity-selector">
                        <label for="quantity">数量</label>
                        <select name="quantity" id="quantity">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>

                <div class="option-selection">
                    <p>オプションを選択してください。</p>
                    
                    <div class="option-selection-box">
                        <label class="option-item radio-option">
                            <input type="radio" name="format" value="ebook" checked>
                            <span class="option-label">電子書籍 (今すぐDL可)</span>
                            <span class="option-price">572円</span>
                        </label>

                        <label class="option-item radio-option">
                            <input type="radio" name="format" value="paper">
                            
                            <span class="option-label-group">
                                <span class="option-label">紙書籍</span>
                                <span class="stock-status">
                                    (○在庫あり)
                                </span>
                            </span>

                            <span class="option-price">572円</span>
                        </label>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="add-to-cart-btn">カートに入れる</button>
                    <button class="add-to-favorite-btn">お気に入りに追加する</button>
                </div>
            </div>
        </div>

        <hr>

        <section class="product-info-section">
            <h2>商品情報</h2>
            <table class="product-info-table">
                <tr>
                    <th>発売日</th>
                    <td>2025年09月04日</td>
                </tr>
                <tr>
                    <th>作者</th>
                    <td>藤本タツキ</td>
                </tr>
                <tr>
                    <th>シリーズ</th>
                    <td>チェンソーマン</td>
                </tr>
                <tr>
                    <th>レーベル</th>
                    <td>ジャンプコミックス</td>
                </tr>
                <tr>
                    <th>出版社</th>
                    <td>集英社</td>
                </tr>
                <tr>
                    <th>ジャンル</th>
                    <td>バトル・アクション</td>
                </tr>
                <tr>
                    <th>ページ数</th>
                    <td>192p</td>
                </tr>
            </table>
        </section>

        <hr>

        <section class="product-description-section">
            <h2>商品説明</h2>
            <p>ついに正体を明かした、ギガちゃん一派の悪魔!飢餓の悪魔と偽チェンソーマンを支配下に置き、"来るべき日"に向けた計画が不気味に進行していく…。デンジとヨルは束の間の平和を享受していたが、2人の前に偽チェンソーマンが突如!!正義の味方チェンソーマンに突きつけられる、究極の選択とは?</p>
        </section>
    </div>
</main>

<?php
// footer.phpを読み込む (後でPHPロジックを追加する際に使用)
require 'common/menu.php';
require 'common/footer.php';
?>