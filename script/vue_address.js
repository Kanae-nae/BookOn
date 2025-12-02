import { PREFECTURES } from './vue_prefectures.js';

// Vue.jsを利用、郵便番号の自動取得など
export const addressMixin = {
    data() {
        return {
            error: "",
            prefectures: [],
            PREFECTURES
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
        // 郵便番号から住所を検索
        async searchAddress() {
            this.error = "";
            const zipcode = String(this.zipcode || '').trim();

            if (!/^\d{7}$/.test(zipcode)) {
                this.error = "郵便番号は7桁の数字で入力してください。";
                return;
            }

            try {
                const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${zipcode}`);
                const data = await response.json();

                if (data.results) {
                    const result = data.results[0];
                    const prefName = result.address1;
                    const city = result.address2;
                    const town = result.address3;

                    // 都道府県名からコードを逆引きして選択状態に
                    const code = this.findPrefCodeByName(prefName);
                    this.prefecture = code || "";
                    this.city = city;
                    this.town = town;
                } else {
                    this.error = "該当する住所が見つかりません。";
                }
            } catch {
                this.error = "通信エラーが発生しました。";
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
        zipcode(newValue) {
            if (/^\d{7}$/.test(newValue)) {
                this.searchAddress();
            }
        }
    },
    mounted() {
        this.prefectures = this.sortedPrefectures;
    }
};