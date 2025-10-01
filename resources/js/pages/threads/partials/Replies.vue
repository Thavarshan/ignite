<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Reply from './Reply.vue';
import ReplyForm from './ReplyForm.vue';

import type { Reply as ReplyType, Thread } from '@/types';

const page = usePage();
const auth = computed(() => page.props.auth);

defineProps<{
    thread: Thread;
    replies: ReplyType[];
}>();
</script>

<template>
    <div class="mt-10">
        <ReplyForm v-if="auth.user" :user="auth.user" :thread="thread" />
        <div class="mt-10">
            <h3 class="font-semibold tracking-tight">
                {{ replies.length }} Replies
            </h3>
            <div v-if="replies.length" class="mt-6 space-y-6">
                <Reply
                    v-for="reply in replies"
                    :key="reply.id"
                    :reply="reply"
                />
            </div>
            <div
                v-else
                class="mt-6 rounded-lg border border-dashed p-6 text-center text-sm text-muted-foreground"
            >
                Be the first to reply to this thread.
            </div>
        </div>
    </div>
</template>
