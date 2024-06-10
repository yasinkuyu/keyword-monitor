<script setup>
import { computed } from 'vue'
import Keyword from '@/Components/Keyword.vue';

const props = defineProps({
    keywordPositions: {
        type: Array,
        required: true,
    },
})

const computedKeywordPositions = computed(() => props.keywordPositions)
const isLoading = computed(() => computedKeywordPositions.value.length === 0)
</script>

<template>
    <div v-if="isLoading" class="flex justify-center items-center mt-6">
        <div
            class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"
        ></div>
    </div>

    <div v-else="!isLoading" class="container mx-auto mt-6">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <div class="text-xl font-semibold mb-4">Recent Searches</div>
            <div class="overflow-x-auto">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">Domain</th>
                            <th scope="col" class="px-6 py-3">Keyword</th>
                            <th scope="col" class="px-6 py-3">Position</th>
                            <th scope="col" class="px-6 py-3">Country</th>
                            <th scope="col" class="px-6 py-3">Language</th>
                            <th scope="col" class="px-6 py-3">Created At</th>
                            <th scope="col" class="px-6 py-3">Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="keywordPosition in computedKeywordPositions.keywordPositions.data"
                            :key="keywordPosition.id"
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700"
                        >
                            <th scope="row" class="px-6 py-1">
                                {{ keywordPosition.domain.name }}
                            </th>
                            <td class="px-6 py-1">
                                <Keyword :keywordPosition="keywordPosition" />
                            </td>
                            <td class="px-6 py-1 text-base">
                                {{ keywordPosition.position }}
                            </td>
                            <td class="px-6 py-1">
                                {{ keywordPosition.country }}
                            </td>
                            <td class="px-6 py-1">
                                {{ keywordPosition.language }}
                            </td>
                            <td class="px-6 py-1 text-gray-500">
                                {{ keywordPosition.created_at }}
                            </td>
                            <td class="px-6 py-1 text-gray-500">
                                {{ keywordPosition.updated_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</template>
