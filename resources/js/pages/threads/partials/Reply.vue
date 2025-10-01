<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { diffForHumans } from '@/lib/utils';
import { Link } from '@inertiajs/vue3';

import type { Reply } from '@/types';

defineProps<{
    reply: Reply;
}>();
</script>

<template>
    <div>
        <Link href="/" class="group block">
            <div class="flex items-center space-x-3">
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage
                        v-if="reply.author?.avatar"
                        :src="reply.author?.avatar"
                        :alt="reply.author?.name"
                    />
                    <AvatarFallback
                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                    >
                        {{ getInitials(reply.author?.name) }}
                    </AvatarFallback>
                </Avatar>
                <div>
                    <div class="text-sm font-medium group-hover:underline">
                        {{ reply.author?.name || 'Unknown' }}
                    </div>
                    <div class="text-xs text-primary">
                        {{ diffForHumans(reply.created_at) }}
                    </div>
                </div>
            </div>
        </Link>
        <div
            class="prose prose-sm mt-2 dark:prose-invert"
            v-html="reply.body"
        ></div>
    </div>
</template>
