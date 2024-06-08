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
        required: true
    },
    country: {
        type: Number,
        required: true
    },
    position: {
        type: Number,
        required: true
    },
    updated_at: {
        type: Date,
        default: () => new Date().toISOString().slice(0, 16)
    }
});

const keyword_position = usePage();

const form = useForm({
    updated_at: keyword_position.updated_at,
    keyword_id: keyword_position.keyword_id,
    domain_id: keyword_position.domain_id,
    language: keyword_position.language,
    country: keyword_position.country,
    position: keyword_position.position
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
                                <div>
                                    <InputLabel for="updated_at" value="Updated At" />

                                    <TextInput
                                        id="updated_at"
                                        type="datetime-local"
                                        class="mt-1 block w-full"
                                        v-model="form.updated_at"
                                        required
                                        autofocus
                                        autocomplete="updated_at"
                                        :value="new Date().toISOString().slice(0, 16)"
                                        />

                                    <InputError class="mt-2" :message="form.errors.updated_at" />
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
                                </div>

                                <div>
                                    <InputLabel for="keyword_id" value="Keyword id" />

                                    <DropdownInput
                                        id="keyword_id"
                                        class="mt-1 block w-full"
                                        v-model="form.keyword_id"
                                        required

                                        :options="listKeywords.map(keyword => ({ name: keyword.keyword }))"
                                        
                                    </DropdownInput>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.keyword_id"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="domain_id" value="Domain id" />

                                    <DropdownInput
                                        id="domain_id"
                                        class="mt-1 block w-full"
                                        v-model="form.domain_id"
                                        required

                                        :options="listDomains"
                                    >
                                        
                                    </DropdownInput>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.domain_id"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="language" value="Language" />

                                    <DropdownInput
                                        id="language"
                                        class="mt-1 block w-full"
                                        v-model="form.language"
                                        required
                                        :options="Object.entries(listLanguages).map(([id, name]) => ({ id, name }))"
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
                                        :options="Object.entries(listCountries).map(([id, name]) => ({ id, name }))"                                    >
                                        
                                    </DropdownInput>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.country"
                                    />
                                </div>

                                <div class="flex items-center gap-4">
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
