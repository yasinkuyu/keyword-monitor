
<script setup>
import { defineProps } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    listDomains: Object,
    filters: Object,
    links: Object,
});

const { props } = usePage();
</script>


<template>
    <Head title="Domains" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Domains</h2>
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
                                        <Link :href="filters.domain">{{ 'Domain' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.created_at">{{ 'Created At' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="listDomain in listDomains.data" :key="listDomain.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-1">{{ listDomain.id }}</th>
                                    <td class="px-6 py-1">{{ listDomain.name }}</td>
                                    <td class="px-6 py-1">{{ listDomain.created_at }}</td>
                                    <td class="px-6 py-1">
                                        <Link :href="route('domains.edit', listDomain.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-1 rounded">Edit</Link>

                                    </td>
                                    <td class="px-6 py-1">
                                        <form :action="route('domains.destroy', listDomain.id)" method="POST" @submit.prevent="() => { if (confirm('Are you sure?')) $inertia.delete(route('domains.destroy', listDomain.id)) }">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" :value="props.auth.csrf">
                                            <button type="submit" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-1 rounded">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                            <Link :href="route('domains.create')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Add new domain</Link>
                        </div> 
                        <div className="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                            <div class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link
                                v-for=" (link, index) in listDomains.links" 
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