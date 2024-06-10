<script setup>
import { defineProps } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    keywordPositions: Object,
    filters: Object,
    links: Object,
});

const { props } = usePage();

const form = useForm({
});

const deleteKeywordPosition = (keywordPosition) => {
    if (confirm('Are you sure you want to delete this keyword position?')) {
        form.delete(route('keyword-positions.destroy', keywordPosition.id), {
            preserveScroll: true,
            onSuccess: () => {
                // Code for successful operation
            },
            onError: (errors) => {
                // Show alert in case of error
                alert('An error occurred: ' + errors)
            },
            onFinish: () => form.reset(),
        })
    }
}
</script>

<template>
    <Head title="Keyword Positions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Keyword Positions</h2>
        </template>

        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="">
                        
                        <section class=" p-4 mx-auto">
                            <div class="sm:flex sm:items-center sm:justify-between">

                                <div class="flex items-center mt-4 gap-x-3">
                                    <Link :href="route('dashboard')" class="w-1/2 px-5 py-2 text-sm text-gray-800 transition-colors duration-200 bg-white border rounded-lg sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-white dark:border-gray-700">
                                        Back
                                    </Link>

                                    <Link :href="route('keyword-positions.create')" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                        <span>Add new keywork position</span>
                                    </Link>
                                </div>
                            </div>

                            <div class="flex flex-col mt-6">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-800">
                                                    <tr>
                                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                            <Link :href="filters.id">{{ 'ID' }}</Link>
                                                        </th>

                                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                            <Link :href="filters.position">{{ 'Position' }}</Link>
                                                        </th>

                                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                            <Link :href="filters.country">{{ 'Country' }}</Link>
                                                        </th>

                                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                            <Link :href="filters.language">{{ 'Language' }}</Link>
                                                        </th>

                                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                            <Link :href="filters.created_at">{{ 'Created At' }}</Link>
                                                        </th>

                                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                            <Link :href="filters.created_at">{{ 'Updated At' }}</Link>
                                                        </th>

                                                        <th scope="col" class="relative py-3.5 px-4">
                                                            <span class="sr-only">Edit</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                                    <tr v-for="keywordPosition in keywordPositions.data" :key="keywordPosition.id">
                                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                                            <div class="inline-flex items-center gap-x-3">

                                                                <div class="flex items-center gap-x-2">
                                                                    <div class="flex items-center justify-center w-8 h-8 text-blue-500 bg-blue-100 rounded-full dark:bg-gray-800">
                                                                        {{ keywordPosition.id }}
                                                                    </div>
                                                                    <div>
                                                                        <h2 class="font-normal text-gray-800 dark:text-white ">{{ keywordPosition.keyword.keyword }}</h2>
                                                                        <p class="text-xs font-normal text-gray-500 dark:text-gray-400">{{ keywordPosition.domain.name }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                                            <a
                                                                :href="
                                                                    route(
                                                                        'keyword-positions.report',
                                                                        keywordPosition.keyword_id,
                                                                    )
                                                                "
                                                                :class="[
                                                                    'px-2 py-1 rounded-xl hover:text-white',
                                                                    keywordPosition.position === 0
                                                                    ? 'text-gray-700 bg-gray-200 hover:bg-gray-600'
                                                                    : keywordPosition.position <= 20
                                                                    ? 'text-green-700 bg-green-200 hover:bg-green-600'
                                                                    : keywordPosition.position <= 50
                                                                        ? 'text-orange-700 bg-orange-200 hover:bg-orange-600'
                                                                        : keywordPosition.position <= 100
                                                                        ? 'text-gray-700 bg-gray-200 hover:bg-gray-600'
                                                                        : '',
                                                                ]"
                                                                >{{ keywordPosition.position }}</a
                                                            >
                                                        </td>
                                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ keywordPosition.country }}</td>
                                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ keywordPosition.language }}</td>
                                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ keywordPosition.created_at }}</td>
                                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ keywordPosition.updated_at }}</td>
                                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                            <DeleteButton
                                                                :class="{ 'opacity-25': form.processing }"
                                                                :disabled="form.processing"
                                                                @click="deleteKeywordPosition(keywordPosition)"
                                                            />
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <a hef="#" class="flex items-center"></a>

                                <div class="items-center hidden md:flex gap-x-3">
                                    <Link
                                        v-for=" (link, index) in keywordPositions.links" 
                                        :key="index" 
                                        :href="link.url"
                                        :class="{
                                            'px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60':
                                                link.active,
                                            'px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100':
                                                !link.active,
                                        }"
                                        v-html="link.label"
                                    />
                                </div>

                                <a href="#" class="flex items-center"></a>
                            </div>
                        </section>
                         
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>