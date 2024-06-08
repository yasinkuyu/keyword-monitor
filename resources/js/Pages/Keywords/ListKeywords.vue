
<script setup>
import { defineProps } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    listKeywords: Object,
    filters: Object,
    links: Object,
});

const { props } = usePage();

const form = useForm({
});

const deleteKeyword = (listKeyword) => {
    if (confirm('Are you sure you want to delete this keyword?')) {
        form.delete(route('keywords.destroy', listKeyword.id), {
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
    <Head title="Keywords" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Keywords</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.id">{{ 'ID' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.keyword">{{ 'Keyword' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.domain_id">{{ 'Domain' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.created_at">{{ 'Created At' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="listKeyword in listKeywords.data" :key="listKeyword.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-1">{{ listKeyword.id }}</th>
                                    <td class="px-6 py-1 font-semibold text-black-300">
                                        <a :href="route('keyword-positions.report', listKeyword.id)">{{ listKeyword.keyword }}</a>
                                    </td>
                                    <td class="px-6 py-1">{{ listKeyword.domain.name }}</td>
                                    <td class="px-6 py-1">{{ listKeyword.created_at }}</td>
                                  
                                    <td class="px-6 py-1">
                                        <DeleteButton
                                            class="ms-3"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click="deleteKeyword(listKeyword)"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                            <Link :href="route('keywords.create')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Add new keyword</Link>
                        </div> 
                        <div className="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6" v-if="listKeywords.links > 1">
                            <div class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link
                                v-for=" (link, index) in listKeywords.links" 
                                :key="index" 
                                :href="link.url"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                v-html="link.label"
                            />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>