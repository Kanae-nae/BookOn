// Vueを利用、メインの読み込む部分
import { addressMixin } from "./vue_address.js";
import { validationMixin } from "./vue_validation.js";

new Vue({
    el: '#signup',
    mixins: [validationMixin, addressMixin],
    methods: {
        // 前のページに戻る処理
        goBackPage() {
            window.location.href = 'g4_login_input.php';
        }
    },
    created() {
        // 全てのフィールドを空欄で初期化
        this.username = '';
        this.email = '';
        this.emailConfirm = '';
        this.pass = '';
        this.passConfirm = '';
        this.lastname = '';
        this.firstname = '';
        this.lastnamekana = '';
        this.firstnamekana = '';
        // this.zipcode = '';
        // this.prefecture = '';
        // this.city = '';
        // this.town = '';
        // this.street_number = '';
        // this.building_name = '';
    }
});