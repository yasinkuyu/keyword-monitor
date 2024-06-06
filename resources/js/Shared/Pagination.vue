<template>
    <div class="flex justify-between mt-4">
        <div v-if="pagination.prev_page_url">
            <inertia-link :href="pagination.prev_page_url" class="btn">Previous</inertia-link>
        </div>
        <div v-if="pagination.next_page_url">
            <inertia-link :href="pagination.next_page_url" class="btn">Next</inertia-link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pagination: Object,
    },
};
</script>
