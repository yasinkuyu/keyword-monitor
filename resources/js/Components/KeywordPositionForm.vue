<template>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mt-4">
        <div class="text-xl font-semibold mb-4">Find Keyword Position</div>
     
        <form @submit.prevent="handleSubmit" id="searchForm">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="service">
                        Service
                    </label>
                    <select v-model="service" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option v-for="option in serviceOptions" :value="option.value" :key="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="w-full md:w-1/6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="domain">
                        Domain
                    </label>
                    <input type="text" v-model="domain" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required autocomplete="off">
                </div>
                <div class="w-full md:w-1/4 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="keyword">
                        Keyword
                    </label>
                    <input type="text" v-model="keyword" @input="fetchKeywords" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required autocomplete="off">
                    <div v-if="suggestedKeywords.length" class="autocomplete-list mt-1 bg-white border border-gray-300 rounded shadow-lg">
                        <ul class="list-outside hover:list-inside">
                            <li class="m-2" v-for="suggestedKeyword in suggestedKeywords" :key="suggestedKeyword.keyword" @click="selectKeyword(suggestedKeyword)">
                                <a href="#" class="text-gray-700">{{ suggestedKeyword.keyword }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="w-full md:w-1/6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="language">
                        Lang
                    </label>
                    <select v-model="language" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option v-for="(lang, code) in languages" :value="code" :key="code">{{ lang }}</option>
                    </select>
                </div>
                <div class="w-full md:w-1/12 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="country">
                        Country
                    </label>
                    <select v-model="country" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option v-for="(countryName, code) in countries" :value="code" :key="code">{{ countryName }}</option>
                    </select>
                </div>
                <div class="w-full md:w-1/6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="position">
                        Position
                    </label>
                    <input v-model="position" id="position" name="position" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" readonly />
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
    </div> {{ keywordPositions }}
    <KeywordPositionList  />

</template>

<script setup>

import KeywordPositionList from '@/Components/KeywordPositionList.vue';

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
    keywordPositions:{
        type: Object
    },
    
});

const recaptchaKey = ref(null);
const recaptchaId = ref(`recaptcha-${new Date().getTime()}`);
const isRecaptchaHidden = ref(true);
const isLoading = ref(false);
const error = ref(null);
const suggestedKeywords = ref([]);
const service = ref('google.selenium');
const domain = ref('tekoil.com.tr');
const keyword = ref('madeni yağ');
const country = ref('tr');
const language = ref('tr');
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

const fetchKeywords = () => {
    if (keyword.value.length > 2) {
        fetch(`/api/keywords?query=${keyword.value}`)
            .then(response => response.json())
            .then(data => {
                suggestedKeywords.value = data;
            })
            .catch(error => {
                console.error('Error fetching keywords:', error);
            });
    } else {
        suggestedKeywords.value = [];
    }
};

const selectKeyword = (suggestedKeyword) => {
    keyword.value = suggestedKeyword.keyword;
    suggestedKeywords.value = [];
};

const getKeywords = () => {
    fetch(route("keyword-positions.json"))
        .then(response => response.json())
        .then(data => {
            props.keywordPositions = data;
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
    formData.append('domain', domain.value);
    formData.append('keyword', keyword.value);
    formData.append('country', country.value);
    formData.append('language', language.value);
    formData.append('position', position.value);
    formData.append('service', service.value);

    if (isRecaptchaRequired.value) {
        generateAndShowRecaptcha().then(recaptchaKey => {
            formData.append('recaptcha_key', recaptchaKey);

            fetch('/api/keyword-positions/search', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': props.csrf_token
                }
            })
            .then(response => response.json())
            .then(data => {

                if (data.error) {
                    alert(data.error);
                } else {
                    position.value = data.position + ' sırada.';
                    getKeywords();
                }

                isLoading.value = false;
                console.log(data);
                getKeywords();
                grecaptcha.reset();
                recaptchaKey.value = null;
            })
            .catch(error => {
                isLoading.value = false;
                error.value = 'Failed to search position: ' + error;
                console.error(error.value);
                grecaptcha.reset();
                recaptchaKey.value = null;
            });
        });
    } else {
        fetch('/api/keyword-positions/search', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': props.csrf_token
            }
        })
        .then(response => response.json())
        .then(data => {

            if (data.error) {
                alert(data.error);
            } else {
                position.value = data.position + ' sırada.';
                getKeywords();
            }

            isLoading.value = false;
            console.log(data);
        })
        .catch(error => {
            isLoading.value = false;
            error.value = 'Failed to search position: ' + error;
            console.error(error.value);
        });
    }
};

onMounted(() => {
    getKeywords();
    var recaptcha_script = document.createElement('script');
    recaptcha_script.type = 'text/javascript';
    recaptcha_script.src = 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit';
    document.head.appendChild(recaptcha_script);
});

</script>

<style scoped>

</style>
