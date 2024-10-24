<script setup lang="ts">
import { MessagesSquare } from 'lucide-vue-next';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Channel } from '@/types/channel';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';

defineProps<{
    channel: Channel;
}>();
</script>

<template>
    <Link :href="route('channels.threads.index', { channel: channel.slug })">
        <Card>
            <CardHeader>
                <CardTitle>{{ channel.name }}</CardTitle>
                <CardDescription> {{ channel.description }} </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="flex justify-between items-center text-xs text-muted-foreground">
                    <div>Latest posted {{ dayjs(channel.updated_at).format('MMM DD') }}</div>
                    <div v-if="channel.threads_count" class="flex items-center">
                        <MessagesSquare class="mr-1 h-3 w-3" />
                        <span>{{ channel.threads_count }}</span>
                    </div>
                </div>
            </CardContent>
        </Card>
    </Link>
</template>
