<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { getInitials } from '@/composables/useInitials';
import { diffForHumans } from '@/lib/utils';
import { index } from '@/routes/channels/threads';
import { index as threadIndex } from '@/routes/threads';
import { Link } from '@inertiajs/vue3';

import type { Thread } from '@/types';

defineProps<{
    thread: Thread;
}>();
</script>

<template>
    <div class="mb-8 space-y-2">
        <Link
            :href="threadIndex.url({ query: { author: thread.author?.name } })"
            class="group block"
        >
            <div class="flex items-center space-x-3">
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage
                        v-if="thread.author?.avatar"
                        :src="thread.author?.avatar"
                        :alt="thread.author?.name"
                    />
                    <AvatarFallback
                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                    >
                        {{ getInitials(thread.author?.name) }}
                    </AvatarFallback>
                </Avatar>
                <div class="group-hover:underline">
                    <span class="text-sm font-medium">
                        {{ thread.author?.name || 'Unknown' }}
                    </span>
                </div>
            </div>
        </Link>
        <h2 class="text-xl font-semibold tracking-tight">{{ thread.title }}</h2>
        <div class="text-sm text-muted-foreground">
            <span>
                <Link
                    :href="index.url({ channel: thread.channel?.slug })"
                    class="hover:underline"
                >
                    <Badge>
                        {{ thread.channel?.name || 'General' }}
                    </Badge>
                </Link>
            </span>
            <span class="mx-2">•</span>
            <span>{{ diffForHumans(thread.created_at) }}</span>
        </div>
    </div>
</template>
