
<script setup>
import { computed } from 'vue';

const props = defineProps({
    keywordPositions: {
        type: Array,
        required: true
    }
});

const computedKeywordPositions = computed(() => props.keywordPositions);
const isLoading = computed(() => computedKeywordPositions.value.length === 0);
</script>  
  
<template>
    <div v-if="isLoading">Loading...</div> 
    <div v-else class="container mx-auto mt-6">
      <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <div class="text-xl font-semibold mb-4">Keyword Positions</div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
              <tr v-for="keywordPosition in computedKeywordPositions" :key="keywordPosition.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-1">{{ keywordPosition.domain.name }}</th>
                <td class="px-6 py-1">{{ keywordPosition.keyword.keyword }}</td>
                <td class="px-6 py-1 text-base">{{ keywordPosition.position }}</td>
                <td class="px-6 py-1">{{ keywordPosition.country }}</td>
                <td class="px-6 py-1">{{ keywordPosition.language }}</td>
                <td class="px-6 py-1 text-gray-500">{{ keywordPosition.created_at_format }}</td>
                <td class="px-6 py-1 text-gray-500">{{ keywordPosition.updated_at_format }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</template>
  