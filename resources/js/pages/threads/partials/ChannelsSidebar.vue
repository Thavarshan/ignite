<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { index } from '@/routes/channels/threads';
import { Link } from '@inertiajs/vue3';

import type { Channel } from '@/types';

defineProps<{
    channels: Channel[];
}>();
</script>

<template>
    <Card class="sticky top-7 border-none bg-muted shadow-none">
        <CardHeader>
            <CardTitle>Channels</CardTitle>
            <CardDescription> Browse threads by channel </CardDescription>
        </CardHeader>
        <CardContent>
            <ul class="space-y-2 text-sm">
                <li v-for="channel in channels" :key="channel.id">
                    <Link
                        :href="index.url({ channel: channel.slug })"
                        class="hover:underline"
                    >
                        {{ channel.name }}
                    </Link>
                </li>
            </ul>
        </CardContent>
    </Card>
</template>
