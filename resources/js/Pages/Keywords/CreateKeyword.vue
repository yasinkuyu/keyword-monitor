<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DropdownInput from '@/Components/DropdownInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    listDomains: Object,
    keyword: {
        type: String,
    },
    domain_id: {
        type: Number,
    },
});

const keyword = usePage().props.auth.user;

const form = useForm({
    keyword: keyword.keyword,
    domain_id: keyword.domain_id,
});

const submit = () => {
    form.post(route('keywords.store'));
};

</script>

<template>
    <Head title="Create keyword" />
 
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create keyword</h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-5">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Create New Keyword
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Create a new keyword for your domain.
                                </p>
                            </header>

                            <form @submit.prevent="submit" class="mt-6 space-y-6">
                                <div>
                                    <InputLabel for="keyword" value="Keyword" />

                                    <TextInput
                                        id="keyword"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.keyword"
                                        required
                                        autofocus
                                        autocomplete="keyword"
                                    />

                                    <InputError class="mt-2" :message="form.errors.keyword" />
                                </div>

                                <div>
                                    <InputLabel for="domain_id" value="Domain" />

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

                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Keyword saved</p>
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
