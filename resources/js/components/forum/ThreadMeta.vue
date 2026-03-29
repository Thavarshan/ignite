<script setup lang="ts">
import { Eye, MessageSquare, ThumbsUp } from 'lucide-vue-next';
import RelativeTime from '@/components/forum/RelativeTime.vue';

defineProps<{
    repliesCount: number;
    likesCount: number;
    visits: number;
    lastPostedAt: string | null;
}>();
</script>

<template>
    <div class="flex items-center gap-4 text-xs text-muted-foreground">
        <span
            class="flex items-center gap-1"
            :title="`${repliesCount} replies`"
        >
            <MessageSquare class="size-3.5" />
            {{ repliesCount }}
        </span>
        <span class="flex items-center gap-1" :title="`${likesCount} likes`">
            <ThumbsUp class="size-3.5" />
            {{ likesCount }}
        </span>
        <span class="flex items-center gap-1" :title="`${visits} views`">
            <Eye class="size-3.5" />
            {{ visits }}
        </span>
        <RelativeTime
            v-if="lastPostedAt"
            :date="lastPostedAt"
            class="shrink-0"
        />
    </div>
</template>
