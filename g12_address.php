<?php require 'common/header_detail.php'; ?>
<link rel="stylesheet" href="css/detail_address.css">
<script>document.title = '配送情報 - BOOK ON';</script>
<!-- 配送情報画面(G12) -->
<?php require 'common/prefectures.php'; ?>

<main>
    <!-- 購入処理でwindow.history.back();(1ページ戻る処理)を行うとおかしくなるため、別の処理で実装 -->
    <button id="toSame" class="btn back-btn">＜　戻る</button>
    <br>

    <h1 class="title">配送情報</h1>

    <img src="image/flow1.png" alt="購入フロー" class="flow">

    <form action="g12_address_output.php" method="post">
        <div class="form-container">
            <div class="label-area">
                <label>住所</label>
                <span class="required">必須</span>
            </div>

            <!-- 郵便番号 -->
            <div>
                <label>郵便番号</label>
                <p class="guide-text">※郵便番号をもとに自動で入力します ハイフン「-」なしで入力</p>
            </div>
            <div class="postcode">
                <input type="text" id="zipcode" name="zip_code" maxlength="7" value="<?= $_SESSION['user']['zip_code']?>"
                placeholder="1000001" required>
                <button type="button" id="search">検索</button>
                <p id="error"></p>
            </div>

            <!-- 都道府県 -->
            <label for="prefecture">都道府県</label>
            <div class="select">
                <select id="prefecture" name="prefecture">
                    <!-- デフォルトの値設定 -->
                    <?php
                    $selected_value = $_SESSION['user']['prefecture'];
                    foreach ($PREFECTURES as $key => $value):
                    ?>
                    <option value="<?= $value ?>" <?= $selected_value == $value ? "selected" : "" ?>>
                        <?= $value ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 市区町村 -->
            <label for="city">市区町村</label><br>
            <input type="text" id="city" name="city" placeholder="千代田区" value="<?= $_SESSION['user']['city']?>" required>
            <br>

            <!-- 町名 -->
            <label for="town">町名</label><br>
            <input type="text" id="town" name="town" placeholder="千代田" value="<?= $_SESSION['user']['town']?>" required>
            <br>

            <!-- 番地 -->
            <label for="street">番地</label><br>
            <input type="text" id="street" name="street_number" placeholder="1-1"
            value="<?= $_SESSION['user']['street_number']?>" required>
            <br>

            <!-- 建物名 -->
            <label for="mansion">建物名など(任意)</label><br>
            <input type="text" id="mansion" name="building_name" value="<?= $_SESSION['user']['building_name']?>">
        </div>
        <input type="submit" class="btn order-btn" value="決済情報へ">
    </form>
</main>

<!-- 戻るボタンの処理(JavaScript) -->
<script src="script/order_unset.js"></script>

<script src="script/address.js"></script>
<?php require 'common/footer.php'; ?>