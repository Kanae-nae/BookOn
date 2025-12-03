<div class="form-group address-group required-field">
    <div class="label-with-badge">
        <div class="icon-label-group">
            <span class="icon"><i class="fas fa-book-open"></i></span>
            <label>住所</label>
        </div>
        <span class="required-badge">必須</span>
    </div>
    <div class="label-with-guide">
        <label>郵便番号</label>
        <p class="guide-text">※郵便番号をもとに自動で入力します</p>
        <p class="guide-text">ハイフン「-」なしで入力</p>
    </div>
    <div class="input-inline zipcode-input">
        <input type="text" name="zip_code" id="zipcode" maxlength="7" class="input zip-part" placeholder="1000001" v-model="zipcode" @blur="validateZipcode" @input="handleZipcodeInput">
        <button type="button" id="search" @click="searchAddress" class="zip-search-btn"  :disabled="isSearching">検索</button>
        <p v-if="touched.zipcode && errors.zipcode" class="has-text-danger">{{ errors.zipcode }}</p>
    </div>

    <div class="address-field">
        <label for="prefecture">都道府県</label>
        <div class="select">
            <select id="prefecture" name="prefecture" v-model="prefecture">
                <option v-for="pref in sortedPrefectures" :key="pref.code" :value="pref.code">
                    {{ pref.name }}
                </option>
            </select>
        </div>
        <p v-if="touched.prefecture && errors.prefecture" class="has-text-danger" style="margin-top: 5px;">{{ errors.prefecture }}</p>
    </div>

    <div class="address-field">
        <label for="city">市区町村</label>
        <input type="text" id="city" name="city" placeholder="千代田区" class="input" v-model="city" required @blur="validateCity">
        <p v-if="touched.city && errors.city" class="has-text-danger">{{ errors.city }}</p>
    </div>

    <div class="address-field">
        <label for="town">町名</label>
        <input type="text" id="town" name="town" placeholder="千代田" class="input" v-model="town" @blur="validateTown" required style="margin-bottom: 0;">
        <p v-if="touched.town && errors.town" class="has-text-danger">{{ errors.town }}</p>
    </div>

    <div class="address-field">
        <label for="street">番地</label>
        <input type="text" id="street" name="street_number" placeholder="1-1" class="input" v-model="street_number" @blur="validateStreetNumber" required style="margin-bottom: 0;">
        <p v-if="touched.street_number && errors.street_number" class="has-text-danger">{{ errors.street_number }}</p>
    </div>

    <div class="address-field">
        <label for="mansion" class="guide-text" style="font-size: 1rem; color: #555; font-weight: bold;">建物名など(任意)</label>
        <input type="text" id="mansion" name="building_name" class="input" v-model="building_name" @blur="validateBuildingName">
        <p v-if="touched.building_name && errors.building_name" class="has-text-danger">{{ errors.building_name }}</p>
    </div>
</div>