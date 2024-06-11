<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import KeywordPositionForm from '@/Components/KeywordPositionForm.vue';

defineProps({
    keywordCount: {
        type: String,
        default: () => 0
    },
    
    domainCount: {
        type: String,
        default: () => 0
    },
    
    positionCount: {
        type: String,
        default: () => 0
    },

    csrf_token: {
        type: String,
        required: true
    },

    services: {
        type: Array,
        required: true,
    },

});

</script>

<template>
    <Head title="Dashboard" />
 
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div> -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Keywords</h3>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ keywordCount }}</p>
                        </div>
                        <div class="text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 10v1m0-6v1m8-4h-1m0 10h-1m-10-6h1m4-2a6 6 0 100 12 6 6 0 000-12zm0 0V4m0 14v1m0-6v1m0-6v1" />
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Domains</h3>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ domainCount }}</p>
                        </div>
                        <div class="text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l4-4m0 0l4 4m-4-4v8m0 2a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Positions</h3>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ positionCount }}</p>
                        </div>
                        <div class="text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2M3 14h2M5 10h14M5 14h14M5 10V5a2 2 0 012-2h10a2 2 0 012 2v5M5 14v5a2 2 0 002 2h10a2 2 0 002-2v-5" />
                            </svg>
                        </div>
                    </div>
                </div>
                <hr>
                <KeywordPositionForm :csrf_token="csrf_token" :services="services"></KeywordPositionForm>
            </div>

        </div> 
    </AuthenticatedLayout> 
</template>
