<script setup lang="ts">
import { useTimeAgo } from '@vueuse/core';
import { computed } from 'vue';

const props = defineProps<{
    date: string;
}>();

const timeAgo = useTimeAgo(computed(() => new Date(props.date)));
</script>

<template>
    <time :datetime="date" :title="new Date(date).toLocaleString()">
        {{ timeAgo }}
    </time>
</template>
