import { PREFECTURES } from './vue_prefectures.js';

// Vue.jsを利用、郵便番号の自動取得など
export const addressMixin = {
    data() {
        return {
            error: "",
            prefectures: [],
            PREFECTURES,
            isSearching: false,
            zipcode: "",
            prefecture: "",
            city: "",
            town: "",
            street_number: "",
            building_name: ""
        };
    },
    computed: {
        // ソート済みの都道府県リスト
        sortedPrefectures() {
            return Object.entries(PREFECTURES)
                .sort(([a], [b]) => a.localeCompare(b))
                .map(([code, name]) => ({ code, name }));
        }
    },
    methods: {
        // 郵便番号入力時の処理
        handleZipcodeInput(event) {
            // 数字のみを許可
            const value = event.target.value.replace(/[^\d]/g, '');
            this.zipcode = value;

            // エラーをクリア
            this.error = "";
        },
        // 郵便番号から住所を検索
        async searchAddress() {
            // 既に検索中の場合は処理をスキップ
            if (this.isSearching) {
                console.log('Already searching...');
                return;
            }

            this.error = "";
            const zipcode = String(this.zipcode || '').trim();

            if (!/^\d{7}$/.test(zipcode)) {
                this.error = "郵便番号は7桁の数字で入力してください。";
                return;
            }

            this.isSearching = true; // 検索開始

            try {
                const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${zipcode}`);

                if (!response.ok) {
                    throw new Error('API通信エラー');
                }

                const data = await response.json();

                if (data.status === 200 && data.results && data.results.length > 0) {
                    const result = data.results[0];
                    const prefName = result.address1;
                    const cityName = result.address2;
                    const townName = result.address3;

                    // 都道府県コードを取得
                    const code = this.findPrefCodeByName(prefName);

                    if (code) {
                        this.prefecture = code;
                        this.city = cityName;
                        this.town = townName;
                        this.error = "";
                    } else {
                        this.error = "都道府県の取得に失敗しました。";
                    }
                } else {
                    this.error = "該当する住所が見つかりません。郵便番号を確認してください。";
                    // 検索失敗時は住所をクリア
                    this.prefecture = "";
                    this.city = "";
                    this.town = "";
                }
            } catch (err) {
                console.error('Address search error:', err);
                this.error = "通信エラーが発生しました。時間をおいて再度お試しください。";
            } finally {
                this.isSearching = false;
            }
        },

        // 都道府県名からコードを取得
        findPrefCodeByName(name) {
            for (const [code, prefName] of Object.entries(PREFECTURES)) {
                if (prefName === name) {
                    return code;
                }
            }
            return null;
        }
    },
    watch: {
        // 郵便番号が7桁になったら自動検索
        zipcode(newValue, oldValue) {
            // 値が変わっていない場合はスキップ
            if (newValue === oldValue) {
                return;
            }

            // 7桁になったら自動検索
            if (/^\d{7}$/.test(newValue)) {
                // 少し遅延させて二重実行を防ぐ
                setTimeout(() => {
                    this.searchAddress();
                }, 100);
            }
        }
    },
    mounted() {
        this.prefectures = this.sortedPrefectures;
    }
};