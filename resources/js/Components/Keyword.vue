<script setup>
defineProps({
    keywordPosition: {
        type: String,
    },
});
</script>

<template>
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
        >{{ keywordPosition.keyword.keyword }}</a
    >
</template>
