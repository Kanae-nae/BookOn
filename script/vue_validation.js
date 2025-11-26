// Vue.jsを利用、正規表現の確認
export const validationMixin = {
    data() {
        return {
            username: '',
            email: '',
            emailConfirm: '',
            pass: '',
            passConfirm: '',
            lastname: '',
            firstname: '',
            lastnamekana: '',
            firstnamekana: '',
            zipcode: '',
            city: '',
            town: '',
            street_number: '',
            building_name: '',
            // 触ったかどうか確かめる(=触っていない状態ではエラーメッセージを表示しない)
            touched: {
                username: false,
                email: false,
                emailConfirm: false,
                pass: false,
                passConfirm: false,
                lastname: false,
                firstname: false,
                lastnamekana: false,
                firstnamekana: false,
                zipcode: false,
                city: false,
                town: false,
                street_number: false,
                building_name: false
            },
            // エラーが出てるかどうか確かめる(全体のバリデーション管理で利用)
            errors: {
                username: null,
                email: null,
                emailConfirm: null,
                pass: null,
                passConfirm: null,
                lastname: null,
                firstname: null,
                lastnamekana: null,
                firstnamekana: null,
                zipcode: null,
                city: null,
                town: null,
                street_number: null,
                building_name: null
            }
        };
    },
    computed: {
        // 全体のバリデーション管理
        hasErrors() {
            return Object.values(this.errors).some(err => err !== null);
        }
    },
    methods: {
        // それぞれのバリデーション管理
        validateUsername() {
            this.touched.username = true;

            if (!this.username) {
                this.errors.username = "ユーザーネームは必須です";
                return;
            }

            if (this.username.length > 32) {
                this.errors.username = "ユーザーネームは32文字以内で入力してください";
                return;
            }

            this.errors.username = null;
        },
        validateEmail() {
            this.touched.email = true;

            if (!this.email) {
                this.errors.email = "メールアドレスは必須です";
                return;
            }

            if (this.email.length > 100) {
                this.errors.email = "メールアドレスは100文字以内で入力してください";
                return;
            }

            const regex = new RegExp(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i);

            this.errors.email = regex.test(this.email) ? null : "Eメールアドレスの形式で入力してください";

            // メールアドレスが変わったら確認用も再チェック
            if (this.touched.emailConfirm) {
                this.validateEmailConfirm();
            }
        },
        validateEmailConfirm() {
            this.touched.emailConfirm = true;

            if (!this.emailConfirm) {
                this.errors.emailConfirm = "確認用メールアドレスを入力してください";
                return;
            }

            if (this.email !== this.emailConfirm) {
                this.errors.emailConfirm = "メールアドレスが一致しません";
                return;
            }

            this.errors.emailConfirm = null;
        },
        validatePass() {
            this.touched.pass = true;
            const pass = this.pass;

            const regex = /^[A-Za-z0-9]+$/;
            if (!regex.test(pass)) {
                this.errors.pass = "パスワードは半角英数字のみ使用できます";
                return;
            }

            // 長さチェック
            if (pass.length < 8 || pass.length > 16) {
                this.errors.pass = "パスワードは8〜16文字で入力してください";
                return;
            }

            this.errors.pass = null;

            // パスワードが変わったら確認用も再チェック
            if (this.touched.passConfirm) {
                this.validatePassConfirm();
            }
        },
        validatePassConfirm() {
            this.touched.passConfirm = true;

            if (!this.passConfirm) {
                this.errors.passConfirm = "確認用パスワードを入力してください";
                return;
            }

            if (this.pass !== this.passConfirm) {
                this.errors.passConfirm = "パスワードが一致しません";
                return;
            }

            this.errors.passConfirm = null;
        },
        validateLastName() {
            this.touched.lastname = true;

            if (!this.lastname) {
                this.errors.lastname = "姓は必須です";
                return;
            }

            if (this.lastname.length > 32) {
                this.errors.lastname = "姓は32文字以内で入力してください";
                return;
            }

            this.errors.lastname = null;
        },
        validateFirstName() {
            this.touched.firstname = true;

            if (!this.firstname) {
                this.errors.firstname = "名は必須です";
                return;
            }

            if (this.firstname.length > 32) {
                this.errors.firstname = "名は32文字以内で入力してください";
                return;
            }

            this.errors.firstname = null;
        },
        validateLastNameKana() {
            this.touched.lastnamekana = true;

            if (!this.lastnamekana) {
                this.errors.lastnamekana = "セイは必須です";
                return;
            }

            if (this.lastnamekana.length > 32) {
                this.errors.lastnamekana = "セイは32文字以内で入力してください";
                return;
            }

            this.errors.lastnamekana = null;
        },
        validateFirstNameKana() {
            this.touched.firstnamekana = true;

            if (!this.firstnamekana) {
                this.errors.firstnamekana = "メイは必須です";
                return;
            }

            if (this.firstnamekana.length > 32) {
                this.errors.firstnamekana = "メイは32文字以内で入力してください";
                return;
            }

            this.errors.firstnamekana = null;
        },
        validateZipcode() {
            this.touched.zipcode = true;
            const regex = /^[0-9]{7}$/;

            if (!this.zipcode) {
                this.errors.zipcode = "郵便番号は必須です";
                return;
            }

            if (!regex.test(this.zipcode)) {
                this.errors.zipcode = "7桁の数字で入力してください";
                return;
            }

            this.errors.zipcode = null;
        },
        validateCity() {
            this.touched.city = true;

            if (!this.city) {
                this.errors.city = "市区町村は必須です";
                return;
            }

            if (this.city.length > 30) {
                this.errors.city = "30文字以内で入力してください";
                return;
            }

            this.errors.city = null;
        },
        validateTown() {
            this.touched.town = true;

            if (!this.town) {
                this.errors.town = "町名は必須です";
                return;
            }

            if (this.town.length > 30) {
                this.errors.town = "30文字以内で入力してください";
                return;
            }

            this.errors.town = null;
        },
        validateStreetNumber() {
            this.touched.street_number = true;

            if (!this.street_number) {
                this.errors.street_number = "番地は必須です";
                return;
            }

            if (this.street_number.length > 30) {
                this.errors.street_number = "30文字以内で入力してください";
                return;
            }

            this.errors.street_number = null;
        },
        validateBuildingName() {
            this.touched.building_name = true;

            if (this.building_name.length > 50) {
                this.errors.building_name = "50文字以内で入力してください";
                return;
            }

            this.errors.building_name = null;
        }
    }
};