<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Heart } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    liked: boolean;
    count: number;
    likeUrl: string;
    unlikeUrl: string;
}>();

const isLiked = ref(props.liked);
const likeCount = ref(props.count);

function toggle() {
    if (isLiked.value) {
        isLiked.value = false;
        likeCount.value--;
        router.delete(props.unlikeUrl, { preserveScroll: true });
    } else {
        isLiked.value = true;
        likeCount.value++;
        router.post(props.likeUrl, {}, { preserveScroll: true });
    }
}
</script>

<template>
    <Button
        variant="ghost"
        size="sm"
        class="h-8 gap-1.5 px-2 text-muted-foreground"
        :class="{ 'text-red-500': isLiked }"
        @click="toggle"
    >
        <Heart class="size-4" :class="{ 'fill-current': isLiked }" />
        <span v-if="likeCount > 0" class="text-xs tabular-nums">
            {{ likeCount }}
        </span>
    </Button>
</template>
