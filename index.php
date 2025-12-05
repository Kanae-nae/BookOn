<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム - BOOK ON</title>
    
    <link rel="stylesheet" href="css/g1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* 切り替え用スタイル */
        .ranking-list-wrapper {
            display: none;
        }
        .ranking-list-wrapper.active-list {
            display: block;
        }
        /* 本のタイトルのスタイル */
        .ranking-book-title {
            font-size: 10px;
            text-align: center;
            margin-top: 5px;
            color: #333;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            width: 100%;
        }
        /* 画像の高さを固定して揃える */
        .ranking-book-cover img {
            height: 140px;
            width: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php require 'common/header.php'; ?>

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
                <a href="g2_detail.php?id=1" class="banner-book-item"><img src="image/kimetsu_23.jpg" alt="Book" onerror="this.src='image/tyen.png'"></a>
                <a href="g6_review.php" class="banner-book-item"><img src="image/chainsawman.jpg" alt="Book" onerror="this.src='image/tyen.png'"></a>
                <a href="#" class="banner-book-item"><img src="image/onepiece.jpg" alt="Book" onerror="this.src='image/tyen.png'"></a>
                <a href="#" class="banner-book-item"><img src="image/heroaca.jpg" alt="Book" onerror="this.src='image/tyen.png'"></a>
                <a href="#" class="banner-book-item"><img src="image/kingdom.jpg" alt="Book" onerror="this.src='image/tyen.png'"></a>
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
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/onepiece.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">ONE PIECE</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/haikyu.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">ハイキュー！！</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/frieren.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">葬送のフリーレン</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/alice.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">今際の国のアリス</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/gintama.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">銀魂</div>
                    </a>
                </div>
            </div>

            <div id="genre-horror" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/gegege.jpg" onerror="this.src='image/'"></div>
                        <div class="ranking-book-title">ゲゲゲの鬼太郎</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/higurashi.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">ひぐらしのなく頃に</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/nube.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">地獄先生ぬ～べ～</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/salesman.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">笑ゥせぇるすまん</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/sayuri.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">サユリ</div>
                    </a>
                </div>
            </div>

            <div id="genre-action" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/onepiece.jpg" onerror="this.src='image/series_id_11_1.jpg'"></div>
                        <div class="ranking-book-title">ONE PIECE</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/hunter.jpg" onerror="this.src='image/series_id_12_1.jpg'"></div>
                        <div class="ranking-book-title">HUNTER×HUNTER</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/kingdom.jpg" onerror="this.src='image/series_id_13_2.jpg'"></div>
                        <div class="ranking-book-title">キングダム</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/heroaca.jpg" onerror="this.src='image/series_id_14_2.jpg'"></div>
                        <div class="ranking-book-title">僕のヒーローアカデミア</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/chainsawman.jpg" onerror="this.src='image/series_id_15_2.jpg'"></div>
                        <div class="ranking-book-title">チェンソーマン</div>
                    </a>
                </div>
            </div>

            <div id="genre-sf" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/frieren.jpg" onerror="this.src='image/series_id_21_1.jpg'"></div>
                        <div class="ranking-book-title">葬送のフリーレン</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/worldtrigger.jpg" onerror="this.src='image/series_id_22_1.jpg'"></div>
                        <div class="ranking-book-title">ワールドトリガー</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/blackclover.jpg" onerror="this.src='image/series_id_23_1.jpg'"></div>
                        <div class="ranking-book-title">ブラッククローバー</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/enen.jpg" onerror="this.src='image/series_id_24_1.jpg'"></div>
                        <div class="ranking-book-title">炎炎ノ消防隊</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/drstone.jpg" onerror="this.src='image/series_id_25_1.jpg'"></div>
                        <div class="ranking-book-title">Dr.STONE</div>
                    </a>
                </div>
            </div>

            <div id="genre-sports" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/haikyu.jpg" onerror="this.src='image/series_id_31_1.jpg'"></div>
                        <div class="ranking-book-title">ハイキュー！！</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/slamdunk.jpg" onerror="this.src='image/series_id_32_1.jpg'"></div>
                        <div class="ranking-book-title">スラムダンク</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/aoashi.jpg" onerror="this.src='image/series_id_33_1.jpg'"></div>
                        <div class="ranking-book-title">アオアシ</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/ippo.jpg" onerror="this.src='image/series_id_34_1.jpg'"></div>
                        <div class="ranking-book-title">はじめの一歩</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/kuroko.jpg" onerror="this.src='image/series_id_35_1.jpg'"></div>
                        <div class="ranking-book-title">黒子のバスケ</div>
                    </a>
                </div>
            </div>

            <div id="genre-mystery" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/alice.jpg" onerror="this.src='image/series_id_61_3.jpg'"></div>
                        <div class="ranking-book-title">今際の国のアリス</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/kindaichi.jpg" onerror="this.src='image/series_id_62_3.jpg'"></div>
                        <div class="ranking-book-title">金田一少年の事件簿</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/theseus.jpg" onerror="this.src='image/series_id_63_1.jpg'"></div>
                        <div class="ranking-book-title">テセウスの船</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/shinai.jpg" onerror="this.src='image/series_id_64_1.jpg'"></div>
                        <div class="ranking-book-title">親愛なる僕へ殺意を込めて</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/gifted.jpg" onerror="this.src='image/series_id_70_1.jpg'"></div>
                        <div class="ranking-book-title">ギフテッド</div>
                    </a>
                </div>
            </div>

            <div id="genre-love" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/kaoru.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">薫る花は凛と咲く</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/kimini.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">君に届け</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/uruwashi.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">うるわしの宵の月</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/kisekoi.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">その着せ替え人形は恋をする</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/yubisaki.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">ゆびさきと恋々</div>
                    </a>
                </div>
            </div>

            <div id="genre-comedy" class="ranking-list-wrapper">
                <div class="ranking-list">
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-1"><i class="fas fa-crown"></i> 1位</div>
                        <div class="ranking-book-cover"><img src="image/gintama.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">銀魂</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-2"><i class="fas fa-crown"></i> 2位</div>
                        <div class="ranking-book-cover"><img src="image/sakamoto.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">SAKAMOTO DAYS</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge rank-3"><i class="fas fa-crown"></i> 3位</div>
                        <div class="ranking-book-cover"><img src="image/masuda.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">増田こうすけ</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">4位</div>
                        <div class="ranking-book-cover"><img src="image/onepunch.jpg" onerror="this.src='image/tyen.png'"></div>
                        <div class="ranking-book-title">ワンパンマン</div>
                    </a>
                    <a href="#" class="ranking-item">
                        <div class="rank-badge">5位</div>
                        <div class="ranking-book-cover"><img src="image/skip.jpg" onerror="this.src='image/tyen.png'"></div>
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