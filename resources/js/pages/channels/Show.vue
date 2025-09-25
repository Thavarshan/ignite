<script setup lang="ts">
import type { Channel } from '@/types';

defineProps<{
    channel: Channel
}>();
</script>

<template>
    <div>
        Hello!
        {{ channel.name }}
    </div>
</template>

