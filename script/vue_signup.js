// Vueを利用、メインの読み込む部分
import { addressMixin } from "./vue_address.js";
import { validationMixin } from "./vue_validation.js";

new Vue({
    el: '#signup',
    // バリデーションの処理と郵便番号関係の処理を集約
    mixins: [validationMixin, addressMixin],
    methods: {
        // 前のページに戻る処理
        goBackPage() {
            window.location.href = 'g4_login_input.php';
        }
    }
});