<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Hash, MessageSquare } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { show } from '@/routes/channels';
import type { Channel } from '@/types';

defineProps<{
    channel: Channel;
}>();
</script>

<template>
    <Link :href="show.url(channel.slug)" class="group block">
        <Card
            class="h-full transition-colors hover:border-primary/30 hover:bg-muted/30"
        >
            <CardHeader class="pb-2">
                <CardTitle class="flex items-center gap-2 text-sm font-medium">
                    <Hash class="size-4 text-muted-foreground" />
                    {{ channel.name }}
                </CardTitle>
            </CardHeader>
            <CardContent>
                <p
                    v-if="channel.description"
                    class="line-clamp-2 text-xs text-muted-foreground"
                >
                    {{ channel.description }}
                </p>
                <div
                    v-if="channel.threads_count !== undefined"
                    class="mt-3 flex items-center gap-1 text-xs text-muted-foreground"
                >
                    <MessageSquare class="size-3" />
                    {{ channel.threads_count }}
                    {{ channel.threads_count === 1 ? 'thread' : 'threads' }}
                </div>
            </CardContent>
        </Card>
    </Link>
</template>
