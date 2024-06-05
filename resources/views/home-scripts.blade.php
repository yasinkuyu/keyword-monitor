    <script src="{{ route('recaptcha-js') }}" async defer></script>
    <script>
        function onloadCallback() {
            app.loadRecaptcha();
        }
        const app = new Vue({
            el: '#app',
            data() {
                return {
                    recaptcha_key: null,
                    keywordPositions: [],
                    recaptchaId: `recaptcha-${new Date().getTime()}`,
                    isRecaptchaHidden: true,
                    isLoading: false,
                    error: null,

                    suggestedKeywords: [],
                    keywords: [],

                    serviceOptions: [
                        { value: 'google.selenium', text: 'google.selenium', requiresRecaptcha: false, sitekey: '' },
                        { value: 'tools.seo.ai', text: 'tools.seo.ai', requiresRecaptcha: true, sitekey: '6Lcj8BkpAAAAAPzLvHsrB4zDD9v5HOe7pjYHXXp8' },
                    ],

                    service: 'google.selenium',
                    domain: 'tekoil.com.tr',
                    keyword: 'madeni yağ',
                    country: 'tr',
                    language: 'tr',
                    position: '',
                };
            },
            computed: {
                isRecaptchaRequired() {
                    const selectedService = this.serviceOptions.find(option => option.value === this.service);
                    return selectedService ? selectedService.requiresRecaptcha : false;
                },
                recaptchaSitekey() {
                    const selectedService = this.serviceOptions.find(option => option.value === this.service);
                    return selectedService ? selectedService.sitekey : '';
                }
            },
            mounted() {
                this.getKeywords();
                var recaptcha_script = document.createElement('script');
                recaptcha_script.type = 'text/javascript';
                recaptcha_script.src = '{{ route('recaptcha-js') }}';
                document.head.appendChild(recaptcha_script);
            },
            methods: {
                fetchKeywords() {
                    if (this.keyword.length > 2) {
                        fetch(`/api/keywords?query=${this.keyword}`)
                            .then(response => response.json())
                            .then(data => {
                                this.suggestedKeywords = data;
                            })
                            .catch(error => {
                                console.error('Error fetching keywords:', error);
                            });
                    } else {
                        this.suggestedKeywords = [];
                    }
                },
                selectKeyword(keyword) {
                    this.keyword = keyword.keyword;
                    this.suggestedKeywords = [];
                },
                getKeywords() {
                    fetch('{{ route("keyword-positions.json") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.keywordPositions = data;
                        })
                        .catch(error => {
                            console.error('Error fetching keywords:', error);
                        });
                },
                saveKeyword() {
                    fetch('{{ route("keywords.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ keyword: this.keyword })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Keyword saved:', data.keyword);
                    })
                    .catch(error => {
                        console.error('Error saving keyword:', error);
                    });
                },
                loadRecaptcha() {
                    if (document.getElementById(this.recaptchaId)) {
                        this.showRecaptcha(this.recaptchaId, this.recaptchaSitekey);
                    }
                },
                async generateAndShowRecaptcha() {
                    this.isRecaptchaHidden = false;
                    const recaptchaKey = await this.generateRecaptcha();
                    this.isRecaptchaHidden = true;
                    return recaptchaKey;
                },
                async generateRecaptcha() {
                    let newRecaptchaKey = "";
                    try {
                        newRecaptchaKey = await this.showRecaptcha(this.recaptchaId, this.recaptchaSitekey);
                    } catch (error) {
                        this.isLoading = false;
                        this.error = "Error occurred. Please try again.";
                    }
                    this.recaptchaId = `recaptcha-${new Date().getTime()}`;
                    return newRecaptchaKey;
                },
                showRecaptcha(id, sitekey) {
                    return new Promise((resolve, reject) => {
                        grecaptcha.ready(() => {
                            grecaptcha.render(id, {
                                sitekey: sitekey,
                                callback: (response) => resolve(response),
                                'error-callback': () => reject(),
                                theme: 'dark',
                            });
                            grecaptcha.execute();
                        });
                    });
                },
                handleSubmit() {
                    this.isLoading = true;
                    this.error = null;

                    const formData = new FormData();
                    formData.append('domain', this.domain);
                    formData.append('keyword', this.keyword);
                    formData.append('country', this.country);
                    formData.append('language', this.language);
                    formData.append('position', this.position);
                    formData.append('service', this.service);

                    if (this.isRecaptchaRequired) {
                        this.generateAndShowRecaptcha().then(recaptchaKey => {
                            formData.append('recaptcha_key', recaptchaKey);

                            fetch('{{ route("keyword-positions.search") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.saveKeyword();

                                if (data.error) {
                                    alert(data.error);
                                } else {
                                    this.position = data.position + ' sırada.';
                                    this.getKeywords();
                                }

                                this.isLoading = false;
                                console.log(data);
                                this.getKeywords();
                                grecaptcha.reset(this.recaptchaWidgetId);
                                this.recaptcha_key = null;
                            })
                            .catch(error => {
                                this.isLoading = false;
                                this.error = 'Failed to search position: ' + error;
                                console.error(this.error);
                                grecaptcha.reset(this.recaptchaWidgetId);
                                this.recaptcha_key = null;
                            });
                        });
                    } else {
                        fetch('{{ route("keyword-positions.search") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.saveKeyword();

                            if (data.error) {
                                alert(data.error);
                            } else {
                                this.position = data.position + ' sırada.';
                                this.getKeywords();
                            }

                            this.isLoading = false;
                            console.log(data);
                        })
                        .catch(error => {
                            this.isLoading = false;
                            this.error = 'Failed to search position: ' + error;
                            console.error(this.error);
                        });
                    }
                }
            }
        });
    </script>