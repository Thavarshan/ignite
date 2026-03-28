<script setup lang="ts">
import { Inbox } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div
            class="mb-4 flex size-12 items-center justify-center rounded-full bg-muted"
        >
            <Inbox class="size-6 text-muted-foreground" />
        </div>
        <h3 class="text-sm font-medium text-foreground">
            {{ title ?? 'Nothing here yet' }}
        </h3>
        <p
            v-if="description"
            class="mt-1 max-w-sm text-sm text-muted-foreground"
        >
            {{ description }}
        </p>
        <div v-if="$slots.action" class="mt-4">
            <slot name="action" />
        </div>
    </div>
</template>
