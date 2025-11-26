<?php require 'common/header.php'; ?>
<!-- ホーム画面(G1) -->

<main style="padding: 20px;">
    <h1>メインコンテンツ</h1>
    <p>スクロールできるだけのコンテンツを追加して、下部ナビゲーションが固定されていることを確認してください。</p>
    <p>... (コンテンツが続く)</p>

    <!-- 商品詳細画面(G2)に移るための仮リンク -->
    <?php $num = mt_rand(1, 196); ?>
    <p><a href="g2_detail.php?id=1">本の詳細ページへ(サンプル)</a></p>
    <p><a href="g2_detail.php?id=<?= $num ?>">本の詳細ページへ(ランダム)</a></p>

    <p style="height: 800px;"></p> <p>ページ最下部</p>
</main>

<!-- メニューは本来使わないけど、テスト用に入れている -->
<?php require 'common/menu.php'; ?>

<?php require 'common/footer.php'; ?>