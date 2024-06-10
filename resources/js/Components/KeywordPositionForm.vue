<template>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mt-4">
        <div class="text-xl font-semibold mb-4">Find Keyword Position</div>
     
        <form @submit.prevent="handleSubmit" id="searchForm">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="service">
                        Service
                    </label>
                    <select v-model="service" class="block appearance-none w-full py-2 px-3 pr-8 rounded  border-gray-200 focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option v-for="option in serviceOptions" :value="option.value" :key="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="w-full md:w-1/6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="domain">
                        Domain
                    </label>
                    <SelectPageList v-model="domain_id" @fetch-data="fetchDomains" />
                </div>
                <div class="w-full md:w-1/4 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="keyword">
                        Keyword
                    </label>
                    <SelectPageList v-model="keyword_id" @fetch-data="fetchKeywords" />
                </div>
                <div class="w-full md:w-1/6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="language">
                        Lang
                    </label>
                    <select v-model="language" class="block appearance-none w-full py-2 px-3 pr-8 rounded border-gray-200 focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option v-for="(lang, code) in languages" :value="code" :key="code">{{ lang }}</option>
                    </select>
                </div>
                <div class="w-full md:w-1/12 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="country">
                        Country
                    </label>
                    <select v-model="country" class="block appearance-none w-full py-2 px-3 pr-8 rounded border-gray-200 focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option v-for="(countryName, code) in countries" :value="code" :key="code">{{ countryName }}</option>
                    </select>
                </div>
                <div class="w-full md:w-1/6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="position">
                        Position
                    </label>
                    <input v-model="position" id="position" name="position" class="block appearance-none w-full py-2 px-3 pr-8 rounded border-gray-200 focus:outline-none focus:bg-white focus:border-gray-500" readonly />
                </div>
            </div>
            <div v-if="!isRecaptchaHidden && isRecaptchaRequired" :id="recaptchaId"></div>
            <div class="flex items-center justify-between mt-4">
                <button @click="fetchKeywordPositions" :disabled="isLoading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <span v-if="isLoading" class="loader mr-2"></span>
                <span v-if="isLoading">Loading...</span>
                <span v-else>Search</span>
                </button>
            </div>
        </form>
    </div>
    
    <KeywordPositionList :keywordPositions="keywordPositions" />

</template>

<script setup>

import KeywordPositionList from '@/Components/KeywordPositionList.vue';
import { SelectPageList } from 'v-selectpage'
import {  useForm } from '@inertiajs/vue3';

import { ref, reactive, computed, onMounted } from 'vue';

var props = defineProps({
    languages: {
        type: Object,
        default: () => ({
            'tr': 'Türkçe',
            'en': 'English'
        })
    },
    countries: {
        type: Object,
        default: () => ({
            'tr': 'Turkiye',
            'us': 'US'
        })
    },
    csrf_token: {
        type: String,
        required: true
    }, 
});

const keywordPositions = ref([]);
const recaptchaKey = ref(null);
const recaptchaId = ref(`recaptcha-${new Date().getTime()}`);
const isRecaptchaHidden = ref(true);
const isLoading = ref(false);
const error = ref(null);
const service = ref('google.selenium');
const domain_id = ref('domain.com');
const keyword_id = ref('domain');
const country = ref('us');
const language = ref('en');
const position = ref('');

const serviceOptions = reactive([
    { value: 'google.selenium', text: 'google.selenium', requiresRecaptcha: false, sitekey: '' },
    { value: 'tools.seo.ai', text: 'tools.seo.ai', requiresRecaptcha: true, sitekey: '6Lcj8BkpAAAAAPzLvHsrB4zDD9v5HOe7pjYHXXp8' }
]);

const isRecaptchaRequired = computed(() => {
    const selectedService = serviceOptions.find(option => option.value === service.value);
    return selectedService ? selectedService.requiresRecaptcha : false;
});

const recaptchaSitekey = computed(() => {
    const selectedService = serviceOptions.find(option => option.value === service.value);
    return selectedService ? selectedService.sitekey : '';
});

const fetchKeywords = (data, callback) => {
    const { search, pageNumber, pageSize } = data;

    fetch(`/api/keywords?search=${search}&page=${pageNumber}&per_page=${pageSize}`)
        .then(response => response.json())
        .then(result => {
            const filtered = result.data;

            callback(
                filtered,
                result.total
            );
        })
        .catch(error => {
            console.error('Error fetching keywords:', error);
            callback([], 0);
        });
}

const fetchDomains = (data, callback) => {
    const { search, pageNumber, pageSize } = data;

    fetch(`/api/domains?search=${search}&page=${pageNumber}&per_page=${pageSize}`)
        .then(response => response.json())
        .then(result => {
            const filtered = result.data;

            callback(
                filtered,
                result.total
            );
        })
        .catch(error => {
            console.error('Error fetching dpöa,ms:', error);
            callback([], 0);
        });
}

const listRecentSearches = () => {
    fetch(route("keyword-positions.json"))
        .then(response => response.json())
        .then(data => {
            keywordPositions.value = data;
        })
        .catch(error => {
            console.error('Error fetching keywords:', error);
        });
};

const loadRecaptcha = () => {
    if (document.getElementById(recaptchaId.value)) {
        showRecaptcha(recaptchaId.value, recaptchaSitekey.value);
    }
};

const generateAndShowRecaptcha = async () => {
    isRecaptchaHidden.value = false;
    const recaptchaKey = await generateRecaptcha();
    isRecaptchaHidden.value = true;
    return recaptchaKey;
};

const generateRecaptcha = () => {
    return new Promise((resolve, reject) => {
        grecaptcha.ready(() => {
            grecaptcha.render(recaptchaId.value, {
                sitekey: recaptchaSitekey.value,
                callback: (response) => resolve(response),
                'error-callback': () => reject(),
                theme: 'dark'
            });
            grecaptcha.execute();
        });
    });
};

const showRecaptcha = (id, sitekey) => {
    return new Promise((resolve, reject) => {
        grecaptcha.ready(() => {
            grecaptcha.render(id, {
                sitekey: sitekey,
                callback: (response) => resolve(response),
                'error-callback': () => reject(),
                theme: 'dark'
            });
            grecaptcha.execute();
        });
    });
};
const handleSubmit = () => {
    isLoading.value = true;
    error.value = null;

    const formData = new FormData();
    formData.append("domain_id", domain_id.value);
    formData.append("keyword_id", keyword_id.value);
    formData.append("country", country.value);
    formData.append("language", language.value);
    formData.append("position", position.value);
    formData.append("service", service.value);

    const fetchData = (recaptchaKey = null) => {
        if (recaptchaKey) {
            formData.append("recaptcha_key", recaptchaKey);
        }

        fetch("/api/keyword-positions/search", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": props.csrf_token,
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    alert(data.error);
                } else {
                    position.value = data.position;
                    listRecentSearches();
                }

                isLoading.value = false;
                console.log(data);
            })
            .catch((error) => {
                isLoading.value = false;
                error.value = "Failed to search position: " + error;
                console.error(error.value);
                if (recaptchaKey) {
                    grecaptcha.reset();
                    recaptchaKey.value = null;
                }
            });
    };

    if (isRecaptchaRequired.value) {
        generateAndShowRecaptcha().then((recaptchaKey) => {
            fetchData(recaptchaKey);
        });
    } else {
        fetchData();
    }
};

onMounted(() => {
    listRecentSearches();
    var recaptcha_script = document.createElement('script');
    recaptcha_script.type = 'text/javascript';
    recaptcha_script.src = 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit';
    document.head.appendChild(recaptcha_script);
});

</script>

<style scoped>

</style>
