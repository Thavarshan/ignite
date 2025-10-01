<script setup lang="ts">
import type { Paginated, Thread as ThreadType } from '@/types';
import Thread from './ThreadExcerpt.vue';

defineProps<{
    threads: Paginated<ThreadType>;
}>();
</script>

<template>
    <div class="divide space-y-6 divide-y">
        <div v-for="thread in threads.data" :key="thread.id" class="pb-6">
            <Thread :thread="thread" />
        </div>
    </div>
</template>
