<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Bell, BellOff } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    subscribed: boolean;
    subscribeUrl: string;
    unsubscribeUrl: string;
}>();

const isSubscribed = ref(props.subscribed);

function toggle() {
    if (isSubscribed.value) {
        isSubscribed.value = false;
        router.delete(props.unsubscribeUrl, { preserveScroll: true });
    } else {
        isSubscribed.value = true;
        router.post(props.subscribeUrl, {}, { preserveScroll: true });
    }
}
</script>

<template>
    <Button
        variant="ghost"
        size="sm"
        class="h-8 gap-1.5 px-2 text-muted-foreground"
        :class="{ 'text-primary': isSubscribed }"
        @click="toggle"
    >
        <component :is="isSubscribed ? BellOff : Bell" class="size-4" />
        <span class="text-xs">
            {{ isSubscribed ? 'Unsubscribe' : 'Subscribe' }}
        </span>
    </Button>
</template>
