<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { diffForHumans, excerptText } from '@/lib/utils';
import { index } from '@/routes/channels/threads';
import { show, index as threadIndex } from '@/routes/threads';
import { Link } from '@inertiajs/vue3';

import type { Thread } from '@/types';

defineProps<{
    thread: Thread;
}>();
</script>

<template>
    <div>
        <Link :href="show({ thread: thread.slug })" class="hover:underline">
            <h2 class="text-lg font-semibold">{{ thread.title }}</h2>
        </Link>
        <div
            class="mt-1 text-sm text-muted-foreground"
            v-html="excerptText(thread.body)"
        ></div>
        <div class="mt-2 text-xs text-primary">
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
            <span v-if="thread.author">
                <Link
                    :href="
                        threadIndex.url({
                            query: { author: thread.author?.name },
                        })
                    "
                    class="hover:underline"
                >
                    {{ thread.author?.name || 'Unknown' }}
                </Link>
            </span>
            <span class="mx-2">•</span>
            <span>{{ diffForHumans(thread.created_at) }}</span>
        </div>
    </div>
</template>
