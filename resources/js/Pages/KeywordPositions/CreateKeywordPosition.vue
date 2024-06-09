<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DropdownInput from '@/Components/DropdownInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    listDomains: Object,
    listKeywords: Object,
    listLanguages: Object,
    listCountries: Object,
    domain_id: {
        type: Number,
        required: true
    },
    keyword_id: {
        type: Number,
        required: true
    },
    language: {
        type: Number,
        required: true,
        default: () => 'en'
    },
    country: {
        type: Number,
        required: true,
        default: () => 'us'
    },
    position: {
        type: Number,
        required: true
    },
    created_at: {
        type: Date,
        default: () => new Date().toISOString().slice(0, 16)
    }
});

const form = useForm({
    created_at: props.created_at,
    keyword_id: props.keyword_id,
    domain_id: props.domain_id,
    language: props.language,
    country: props.country,
    position: props.position
});

const submit = () => {
    form.post(route('keyword-positions.store'));
};

</script>

<template>
    <Head title="Create keyword position" />
 
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create keyword position</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-5">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Create New Keyword position
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Create a new position for your keyword.
                                </p>
                            </header>

                            <form @submit.prevent="submit" class="mt-6 space-y-6">

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <InputLabel for="created_at" value="created_at" />

                                            <TextInput
                                                id="created_at"
                                                type="datetime-local"
                                                class="mt-1 block w-full"
                                                v-model="form.created_at"
                                                required
                                                autofocus
                                                autocomplete="created_at"
                                                :value="new Date().toISOString().slice(0, 16)"
                                                />

                                            <InputError class="mt-2" :message="form.errors.created_at" />
                                        </div>

                                        <div>
                                            <InputLabel for="position" value="Position" />

                                            <TextInput
                                                id="position"
                                                type="number"
                                                class="mt-1 block w-full"
                                                v-model="form.position"
                                                required
                                                autofocus
                                                autocomplete="position"
                                            />

                                            <InputError class="mt-2" :message="form.errors.position" />
                                            <p class="text-sm text-gray-500">Add a past ranking position for your keyword.</p>
                                        </div>

                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                        <div>
                                            <InputLabel for="domain_id" value="Domain id" />

                                            <DropdownInput
                                                id="domain_id"
                                                class="mt-1 block w-full"
                                                v-model="form.domain_id"
                                                required

                                                :options="listDomains.map(domain => ({ name: domain.name, value: domain.id }))"
                                            >
                                                
                                            </DropdownInput>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.domain_id"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel for="keyword_id" value="Keyword id" />

                                            <DropdownInput
                                                id="keyword_id"
                                                class="mt-1 block w-full"
                                                v-model="form.keyword_id"
                                                required

                                                :options="listKeywords.map(keyword => ({ name: keyword.keyword, value: keyword.id }))"
                                                
                                            </DropdownInput>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.keyword_id"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel for="language" value="Language" />

                                            <DropdownInput
                                                id="language"
                                                class="mt-1 block w-full"
                                                v-model="form.language"
                                                required
                                                :options="listLanguages.map(lang => ({ name: lang.name, value: lang.code }))"> 
                                            </DropdownInput>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.language"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel for="country" value="Country" />

                                            <DropdownInput
                                                id="country"
                                                class="mt-1 block w-full"
                                                v-model="form.country"
                                                required
                                                :options="listCountries.map(country => ({ name: country.name, value: country.code }))">
                                                
                                            </DropdownInput>

                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.country"
                                            />
                                        </div>
                                    </div>
                                     

                                <div class="flex items-center gap-4">
                                    <Link :href="route('keyword-positions.index')" class="w-1/2 px-5 py-2 text-sm text-gray-800 transition-colors duration-200 bg-white border rounded-lg sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-white dark:border-gray-700">
                                        Back
                                    </Link>
                                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Keyword position saved</p>
                                    </Transition>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
 
    </AuthenticatedLayout>

</template>
