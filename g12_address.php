<?php require 'common/header_detail.php'; ?>
<!-- 配送情報画面(G12) -->
<?php require 'common/prefectures.php'; ?>
<link rel="stylesheet" href="css/detail_address.css">
<main>
    <!-- 購入処理でwindow.history.back();(1ページ戻る処理)を行うとおかしくなるため、別の処理で実装 -->
    <button id="toSame" class="back-btn">＜　戻る</button>
    <br>

    <h1>配送情報</h1>

    <img src="image/flow1.png" alt="購入フロー" class="flow">

    <form action="g12_address_output.php" method="post">
        <div class="form-group address-group required-field">
            <div class="label-with-badge">
                <div class="icon-label-group">
                    <label>住所</label>
                </div>
            </div>
            <div class="label-with-guide">
                <label>郵便番号</label>
                <p class="guide-text">※郵便番号をもとに自動で入力します</p>
                <p class="guide-text">ハイフン「-」なしで入力</p>
            </div>
            <div class="input-inline zipcode-input">
                <input type="text" name="zip_code" id="zipcode" value="<?= $_SESSION['user']['zip_code']?>" class="input zip-part"
                maxlength="7" placeholder="1000001" required>
                <button type="button" id="search" class="zip-search-btn">検索</button>
                <p id="error" style="color: red;"></p>
            </div>

            <div class="address-field">
                <label for="prefecture">都道府県</label>
                <div class="select">
                    <select id="prefecture" name="prefecture">
                        <!-- デフォルトの値を設定する関係でややこしい設定になってます！ごめん！！ -->
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
            </div>

            <div class="address-field">
                <label for="city">市区町村</label>
                <input type="text" id="city" name="city" placeholder="千代田区" value="<?= $_SESSION['user']['city']?>" class="input" required>
            </div>

            <div class="address-field">
                <label for="town">町名</label>
                <input type="text" id="town" name="town" placeholder="千代田" value="<?= $_SESSION['user']['town']?>" class="input" required>
            </div>

            <div class="address-field">
                <label for="street">番地</label>
                <input type="text" id="street" name="street_number" placeholder="1-1" value="<?= $_SESSION['user']['street_number']?>"
                class="input" required>
            </div>

            <div class="address-field">
                <label for="mansion" class="guide-text">建物名など</label>
                <input type="text" id="mansion" name="building_name" value="<?= $_SESSION['user']['building_name']?>" class="input">
            </div>
        </div>
        <input type="submit" class="order-btn" value="決済情報へ">
    </form>
</main>

<!-- 戻るボタンの処理(JavaScript) -->
<script src="script/order_unset.js"></script>

<script src="script/address.js"></script>
<?php require 'common/footer.php'; ?>