<script setup>
import { defineProps } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    keywordPositions: Object,
    filters: Object,
    links: Object,
});

const { props } = usePage();
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
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.id">{{ 'ID' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.domain">{{ 'Domain' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.keyword">{{ 'Keyword' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.lang">{{ 'Lang' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.country">{{ 'Country' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.created_at">{{ 'Query Date' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Link :href="filters.position">{{ 'Position' }}</Link>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="keywordPosition in keywordPositions.data" :key="keywordPosition.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.domain.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.keyword.keyword }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.lang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.country }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.created_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ keywordPosition.position }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Link :href="route('keyword-positions.edit', keywordPosition.id)" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form :action="route('keyword-positions.destroy', keywordPosition.id)" method="POST" @submit.prevent="() => { if (confirm('Are you sure?')) $inertia.delete(route('keyword-positions.destroy', keywordPosition.id)) }">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" :value="props.auth.csrf">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <Button :href="route('keyword-positions.create')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Keyword Position</Button>
                        </div> 
                        <div className="my-4 d-flex justify-content-center">
                            <pagination :links="keywordPositions.links" />
                            <ul className="pagination">
                                <li v-for="(link, index) in keywordPositions.links" :key="index" 
                                    :class="`page-item ${link.active ? 'active' : ''}`">
                                    <Link
                                        v-if="link.url !== null"
                                        :href="link.url"
                                        className="page-link"
                                        v-html="link.label">
                                    </Link>
                                    <div
                                        v-else
                                        className="page-link"
                                        v-html="link.label">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
