// Vueを利用、メインの読み込む部分
import { addressMixin } from "./vue_address.js";
import { validationMixin } from "./vue_validation.js";
import { PREFECTURES, findPrefCodeByName } from "./vue_prefectures.js";

const signup = new Vue({
    el: '#signup',
    // バリデーションの処理と郵便番号関係の処理を集約
    mixins: [validationMixin, addressMixin],
    data() {
        return {
            // selectedIconをdataに追加
            selectedIcon: (window.initialUser && window.initialUser.icon_url)
                ? window.initialUser.icon_url
                : 'image/icon/default.png',
        };
    },
    methods: {
        // 前のページに戻る処理
        goBackPage() {
            window.location.href = 'g9_mypage.php';
        },
        // 新しいウィンドウでアイコン選択ページを開く
        openIconSelector() {
            const width = 900;
            const height = 700;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;

            window.open(
                'g10_mypage_update_icon.php',
                'iconSelector',
                `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
            );
        }
    },
    created() {
        // mixin の data がマージされた後で、空なら session を適用する
        const u = window.initialUser || {};
        if (!this.username) this.username = u.user_name || '';
        if (!this.email) this.email = u.mail_address || '';
        if (!this.lastname) this.lastname = u.last_name || '';
        if (!this.firstname) this.firstname = u.first_name || '';
        if (!this.lastnamekana) this.lastnamekana = u.last_name_kana || '';
        if (!this.firstnamekana) this.firstnamekana = u.first_name_kana || '';

        this.zipcode = (u.zip_code !== undefined && u.zip_code !== null)
            ? String(u.zip_code)
            : (this.zipcode ? String(this.zipcode) : '');

        // --- 都道府県の判別とコード化（追加） ---
        let initialPref = '';
        if (u.prefecture !== undefined && u.prefecture !== null && u.prefecture !== '') {
            const ps = String(u.prefecture);
            if (Object.prototype.hasOwnProperty.call(PREFECTURES, ps)) {
                initialPref = ps; // 既にコード文字列 ("13" 等)
            } else {
                initialPref = findPrefCodeByName(ps); // 名前 ("東京都") -> コード ("13")
            }
        }
        if (!this.prefecture) this.prefecture = initialPref ? String(initialPref) : '';

        if (!this.city) this.city = u.city || '';
        if (!this.town) this.town = u.town || '';
        if (!this.street_number) this.street_number = u.street_number || u.street || '';
        if (!this.building_name) this.building_name = u.building_name || u.building || '';

        // アイコンURLの初期化（セッションから）
        if (u.icon_url) {
            this.selectedIcon = u.icon_url;
        }
    }
});

// グローバルに公開
window.signup = signup;
// エクスポート（他のモジュールで使用する場合）
export default signup;