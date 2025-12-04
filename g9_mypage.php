<?php
require 'common/header.php';
$view = isset($_GET['view']) ? $_GET['view'] : 'review';
require 'common/db-connect.php';
?>

<script>document.title = 'マイページ - BOOK ON';</script>
<link rel="stylesheet" href="css/g9.css">
<?php if ($view === 'review'): ?>
    <link rel="stylesheet" href="css/g9_review.css">
<?php else: ?>
    <link rel="stylesheet" href="css/g9_purchase.css">
<?php endif; ?>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <main>
        <!-- ログイン時のみプロフィールを取得、表示 -->
        <?php if (isset($_SESSION['user'])): ?>
            <?php
            // レビューの数と購入履歴の数をカウントする
            try {
            // pdo、SQL文、パラメータを受け取って処理する関数(prepare→executeを行う)
            function getSql($pdo, $sql, $params = []) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return $stmt->fetchColumn();
            }

            // データベースに渡すSQLとパラメータを定義する関数(これをgetSqlに渡す)
            function countReview($pdo) {
                return getSql($pdo, "SELECT COUNT(*) FROM review WHERE user_id = :user_id",
                    [':user_id' => $_SESSION['user']['user_id']]
                );
            }

            function countOrders($pdo) {
                return getSql($pdo, "SELECT COUNT(*) FROM orders WHERE user_id = :user_id",
                    [':user_id' => $_SESSION['user']['user_id']]
                );
            }

            // ↑の処理を呼び出して変数に代入
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $review_count = countReview($pdo);
            $orders_count = countOrders($pdo);

            } catch(PDOException $e) {
            echo '<p>データベースエラーが発生しました。</p>';
            }

            // 自己紹介の処理(未記入の場合は未記入と表示する)
            $self_introduction = !empty($_SESSION['user']['self_introduction']) ? 
            $_SESSION['user']['self_introduction'] : "(自己紹介未記入)";
            ?>

            <!-- ===============================
                プロフィールセクション
            =============================== -->
            <section class="profile">
                <div class="profile-container">
                    <div class="profile-top">
                        <img src="<?= $_SESSION['user']['icon_url'] ?>" alt="ユーザーアイコン" class="profile-icon">

                        <div class="profile-info">
                            <div class="profile-header">
                                <h2 class="profile-name"><?= $_SESSION['user']['user_name'] ?></h2>
                                <button id="toSame" class="btn-profile-edit">会員情報を変更</button>
                            </div>

                            <div class="profile-stats">
                                <div><span><?= $review_count ?></span><span>レビュー</span></div>
                                <div><span><?= $orders_count ?></span><span>購入履歴</span></div>
                            </div>
                        </div>
                    </div>
                    <p class="profile-comment">
                        <?= $self_introduction ?>
                    </p>
                </div>
            </section>

            <!-- ===============================
                レビュー・購入履歴 切り替え
            =============================== -->
            <div class="switch-btns">
                <a href="?view=review" class="switch-btn <?php echo ($view === 'review') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-pen"></i> レビュー
                </a>
                <a href="?view=purchase" class="switch-btn <?php echo ($view === 'purchase') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock"></i> 購入履歴
                </a>
            </div>

            <!-- ===============================
                コンテンツ切り替え
            =============================== -->
            <section class="mypage-content">
                <?php
                    if ($view === 'review') {
                        include("common/mypage_review.php");
                    } else {
                        include("common/mypage_purchase.php");
                    }
                ?>
            </section>

            <!-- ===============================
                並び替えボタン（スマホ対応）
            =============================== -->
            <?php if ($view === 'review'): ?>
                <div class="sort-dropdown">
                    <button id="sort-btn" class="sort-button">
                        <i class="fa-solid fa-arrow-down-short-wide"></i>
                    </button>
                    <div id="sort-options" class="dropdown-content">
                        <a href="?view=review&sort=new">投稿日：新しい順</a>
                        <a href="?view=review&sort=old">投稿日：古い順</a>
                        <a href="?view=review&sort=high">評価：高い順</a>
                        <a href="?view=review&sort=low">評価：低い順</a>
                    </div>
                </div>
            <?php elseif ($view === 'purchase'): ?>
                <div class="sort-dropdown">
                    <button id="sort-btn" class="sort-button">
                        <i class="fa-solid fa-arrow-down-short-wide"></i>
                    </button>
                    <div id="sort-options" class="dropdown-content">
                        <a href="?view=purchase&sort=new">購入日：新しい順</a>
                        <a href="?view=purchase&sort=old">購入日：古い順</a>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <p>マイページの利用にはログインが必要です。</p>
        <?php endif; ?>
    </main>

    <?php include("common/menu.php"); ?>
    <?php include("common/footer.php"); ?>

    <script>
        // 違うページに飛ばす
        document.getElementById('toSame').addEventListener('click', function () {
            // 相対パスや絶対URLを指定できます
            location.href = 'g10_mypage_update.php';
        });

        // スマホでもドロップダウン操作できるよう最小限に留めたJS
        const sortBtn = document.getElementById("sort-btn");
        const sortOptions = document.getElementById("sort-options");
        sortBtn.addEventListener("click", () => {
            sortOptions.classList.toggle("show");
        });
        document.addEventListener("click", (e) => {
            if (!sortBtn.contains(e.target)) sortOptions.classList.remove("show");
        });
    </script>
</body>
</html>