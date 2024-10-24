<script setup lang="ts">
import { Thread } from '@/types/thread';

const props = defineProps<{
    thread: Thread;
}>();
</script>

<template>
    <div>{{ thread.id }}</div>
</template>
