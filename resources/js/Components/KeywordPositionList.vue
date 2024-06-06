
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
    <div v-if="isLoading">Yükleniyor...</div> 
    <div v-else class="container mx-auto mt-6">
      <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <div class="text-xl font-semibold mb-4">Keyword Positions</div>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Domain</th>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Keyword</th>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Position</th>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Country</th>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Language</th>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Created At</th>
                <th class="py-2 px-4 border-b-2 border-gray-300 text-left leading-tight">Last Update</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="keywordPosition in computedKeywordPositions" :key="keywordPosition.id" class="hover:bg-gray-100">
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.domain.name }}</td>
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.keyword.keyword }}</td>
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.position }}</td>
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.country }}</td>
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.language }}</td>
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.created_at_format }}</td>
                <td class="py-2 px-4 border-b border-gray-300">{{ keywordPosition.updated_at_format }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</template>
  