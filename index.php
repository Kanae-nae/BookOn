<?php require 'common/header.php'; ?>
<script>document.title = 'ホーム - BOOK ON';</script>
<!-- ホーム画面(G1) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/g1.css">

<body>
    <main class="home-page-main">
        <section class="banner-section">
            <div class="banner-content">
                <div class="banner-title">
                    <i class="fas fa-book-open book-icon-illustration"></i>
                    <div>
                        <div style="font-size: 18px;">新刊特集</div>
                        <div style="font-size: 28px;">注目作5選！</div>
                    </div>
                    <i class="fas fa-book book-icon-illustration"></i>
                </div>
                <div class="banner-subtitle">
                    10月の話題作をチェック
                </div>
            </div>

            <div class="horizontal-scroll">
                <a href="g2_detail.php?id=53" class="banner-book-item">
                    <img src="image/product_img_url/series_id_20_1.jpg" alt="Book">
                </a>
                <a href="g2_detail.php?id=39" class="banner-book-item">
                    <img src="image/product_img_url/series_id_15_1.jpg" alt="Book">
                </a>
                <a href="g2_detail.php?id=27" class="banner-book-item">
                    <img src="image/product_img_url/series_id_11_1.jpg" alt="Book">
                </a>
                <a href="g2_detail.php?id=36" class="banner-book-item">
                    <img src="image/product_img_url/series_id_14_1.jpg" alt="Book">
                </a>
                <a href="g2_detail.php?id=33" class="banner-book-item">
                    <img src="image/product_img_url/series_id_13_1.jpg" alt="Book">
                </a>
            </div>
        </section>

        <section class="ranking-section">
            <h2 class="section-title">ジャンル別オススメ</h2>
            
            <div class="genre-tabs">
                <div class="tab-item active" onclick="selectTab(this, 'genre-all')">全体</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-horror')">ホラー・サスペンス</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-action')">バトル・アクション</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-sf')">SF・ファンタジー</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-sports')">スポーツ</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-mystery')">ミステリー</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-love')">恋愛</div>
                <div class="tab-item" onclick="selectTab(this, 'genre-comedy')">コメディー</div>
            </div>

            <div id="genre-all" class="ranking-list-wrapper active-list">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=27" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_11_1.jpg"></div>
                        <div class="ranking-book-title">ONE PIECE</div>
                    </a>
                    <a href="g2_detail.php?id=83" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_31_1.jpg"></div>
                        <div class="ranking-book-title">ハイキュー！！</div>
                    </a>
                    <a href="g2_detail.php?id=56" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_21_1.jpg"></div>
                        <div class="ranking-book-title">葬送のフリーレン</div>
                    </a>
                    <a href="g2_detail.php?id=167" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_61_1.jpg"></div>
                        <div class="ranking-book-title">今際の国のアリス</div>
                    </a>
                    <a href="g2_detail.php?id=137" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_51_1.jpg"></div>
                        <div class="ranking-book-title">銀魂</div>
                    </a>
                </div>
            </div>

            <div id="genre-horror" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=1" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_01_1.jpg"></div>
                        <div class="ranking-book-title">ゲゲゲの鬼太郎</div>
                    </a>
                    <a href="g2_detail.php?id=4" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_02_1.jpg"></div>
                        <div class="ranking-book-title">ひぐらしのなく頃に</div>
                    </a>
                    <a href="g2_detail.php?id=33" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_13_1.jpg"></div>
                        <div class="ranking-book-title">地獄先生ぬ～べ～</div>
                    </a>
                    <a href="g2_detail.php?id=9" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_04_1.jpg"></div>
                        <div class="ranking-book-title">笑ゥせぇるすまん</div>
                    </a>
                    <a href="g2_detail.php?id=12" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_05_1.jpg"></div>
                        <div class="ranking-book-title">サユリ</div>
                    </a>
                </div>
            </div>

            <div id="genre-action" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=27" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_11_1.jpg"></div>
                        <div class="ranking-book-title">ONE PIECE</div>
                    </a>
                    <a href="g2_detail.php?id=30" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_12_1.jpg"></div>
                        <div class="ranking-book-title">HUNTER×HUNTER</div>
                    </a>
                    <a href="g2_detail.php?id=33" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_13_1.jpg"></div>
                        <div class="ranking-book-title">キングダム</div>
                    </a>
                    <a href="g2_detail.php?id=36" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_14_1.jpg"></div>
                        <div class="ranking-book-title">僕のヒーローアカデミア</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_15_1.jpg"></div>
                        <div class="ranking-book-title">チェンソーマン</div>
                    </a>
                </div>
            </div>

            <div id="genre-sf" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=56" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_21_1.jpg"></div>
                        <div class="ranking-book-title">葬送のフリーレン</div>
                    </a>
                    <a href="g2_detail.php?id=59" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_22_1.jpg"></div>
                        <div class="ranking-book-title">ワールドトリガー</div>
                    </a>
                    <a href="g2_detail.php?id=62" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_23_1.jpg"></div>
                        <div class="ranking-book-title">ブラッククローバー</div>
                    </a>
                    <a href="g2_detail.php?id=65" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_24_1.jpg"></div>
                        <div class="ranking-book-title">炎炎ノ消防隊</div>
                    </a>
                    <a href="g2_detail.php?id=68" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_25_1.jpg"></div>
                        <div class="ranking-book-title">Dr.STONE</div>
                    </a>
                </div>
            </div>

            <div id="genre-sports" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=83" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_31_1.jpg"></div>
                        <div class="ranking-book-title">ハイキュー！！</div>
                    </a>
                    <a href="g2_detail.php?id=85" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_32_1.jpg"></div>
                        <div class="ranking-book-title">スラムダンク</div>
                    </a>
                    <a href="g2_detail.php?id=87" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_33_1.jpg"></div>
                        <div class="ranking-book-title">アオアシ</div>
                    </a>
                    <a href="g2_detail.php?id=90" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_34_1.jpg"></div>
                        <div class="ranking-book-title">はじめの一歩</div>
                    </a>
                    <a href="g2_detail.php?id=93" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_35_1.jpg"></div>
                        <div class="ranking-book-title">黒子のバスケ</div>
                    </a>
                </div>
            </div>

            <div id="genre-mystery" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=167" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_61_1.jpg"></div>
                        <div class="ranking-book-title">今際の国のアリス</div>
                    </a>
                    <a href="g2_detail.php?id=170" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_62_1.jpg"></div>
                        <div class="ranking-book-title">金田一少年の事件簿</div>
                    </a>
                    <a href="g2_detail.php?id=173" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_63_1.jpg"></div>
                        <div class="ranking-book-title">テセウスの船</div>
                    </a>
                    <a href="g2_detail.php?id=176" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_64_1.jpg"></div>
                        <div class="ranking-book-title">親愛なる僕へ殺意を込めて</div>
                    </a>
                    <a href="g2_detail.php?id=194" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_70_1.jpg"></div>
                        <div class="ranking-book-title">ギフテッド</div>
                    </a>
                </div>
            </div>

            <div id="genre-love" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=111" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_41_1.jpg"></div>
                        <div class="ranking-book-title">薫る花は凛と咲く</div>
                    </a>
                    <a href="g2_detail.php?id=113" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_42_1.jpg"></div>
                        <div class="ranking-book-title">君に届け</div>
                    </a>
                    <a href="g2_detail.php?id=115" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_43_1.jpg"></div>
                        <div class="ranking-book-title">うるわしの宵の月</div>
                    </a>
                    <a href="g2_detail.php?id=118" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_44_1.jpg"></div>
                        <div class="ranking-book-title">その着せ替え人形は恋をする</div>
                    </a>
                    <a href="g2_detail.php?id=121" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_45_1.jpg"></div>
                        <div class="ranking-book-title">ゆびさきと恋々</div>
                    </a>
                </div>
            </div>

            <div id="genre-comedy" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="g2_detail.php?id=137" class="ranking-item">
                        <div class="rank-badge">1位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_51_1.jpg"></div>
                        <div class="ranking-book-title">銀魂</div>
                    </a>
                    <a href="g2_detail.php?id=140" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_52_1.jpg"></div>
                        <div class="ranking-book-title">SAKAMOTO DAYS</div>
                    </a>
                    <a href="g2_detail.php?id=143" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_53_1.jpg"></div>
                        <div class="ranking-book-title">増田こうすけ劇場</div>
                    </a>
                    <a href="g2_detail.php?id=146" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_54_1.jpg"></div>
                        <div class="ranking-book-title">ワンパンマン</div>
                    </a>
                    <a href="g2_detail.php?id=149" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/product_img_url/series_id_55_1.jpg"></div>
                        <div class="ranking-book-title">スキップとローファー</div>
                    </a>
                </div>
            </div>

        </section>


    </main>

    <?php include 'common/menu.php'; ?>

    <script>
        // タブ切り替えとリスト切り替えを行う関数
        function selectTab(element, genreId) {
            
            // 1. タブの見た目の切り替え
            var tabs = document.getElementsByClassName('tab-item');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            element.classList.add('active');

            // 2. ランキングリストの切り替え
            // まず全部隠す
            var lists = document.getElementsByClassName('ranking-list-wrapper');
            for (var i = 0; i < lists.length; i++) {
                lists[i].classList.remove('active-list');
            }
            // 指定されたIDのリストだけ表示する
            var targetList = document.getElementById(genreId);
            if (targetList) {
                targetList.classList.add('active-list');
            }
        }
    </script>

</body>
</html>