const zipcodeInput = document.getElementById("zipcode");
const searchButton = document.getElementById("search");
const prefSelect = document.getElementById("prefecture");
const cityInput = document.getElementById("city");
const townInput = document.getElementById("town");
const errorEl = document.getElementById("error");

// --- ページ読み込み時に都道府県セレクトを生成 ---
window.addEventListener("DOMContentLoaded", () => {
    Object.entries(PREFECTURES)
        .sort(([a], [b]) => a.localeCompare(b)) // 文字列としてソート
        .forEach(([code, name]) => {
            const option = document.createElement("option");
            option.value = code;
            option.textContent = name;
            prefSelect.appendChild(option);
        });
});

// --- 郵便番号の検索 ---
searchButton.addEventListener("click", () => {
    const zipcode = zipcodeInput.value.trim();
    errorEl.textContent = "";

    if (!/^\d{7}$/.test(zipcode)) {
        errorEl.textContent = "郵便番号は7桁の数字で入力してください。";
        return;
    }

    fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${zipcode}`)
        .then(response => response.json())
        .then(data => {
            if (data.results) {
                const result = data.results[0];
                const prefName = result.address1;
                const city = result.address2;
                const town = result.address3;

                // 都道府県名からコードを逆引きして選択状態に
                const code = findPrefCodeByName(prefName);
                prefSelect.value = code || "";

                cityInput.value = city;
                townInput.value = town;
            } else {
                errorEl.textContent = "該当する住所が見つかりません。";
            }
        })
        .catch(() => {
            errorEl.textContent = "通信エラーが発生しました。";
        });
});

// --- 郵便番号が7桁入力されたら自動検索 ---
zipcodeInput.addEventListener("input", () => {
    if (/^\d{7}$/.test(zipcodeInput.value)) {
        searchButton.click();
    }
});

// フォームの送信時に都道府県のkeyをvalueに変更
form.addEventListener("submit", (e) => {
    const code = prefSelect.value;         // "13"
    const prefectureName = PREFECTURES[code]; // "東京都"
});