<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import { index as channelsApi } from '@/routes/api/v1/channels';
import { index } from '@/routes/channels/threads';
import { Link } from '@inertiajs/vue3';

import type { Channel } from '@/types';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const channels = ref<Channel[]>([]);
const isLoading = ref(true);

onMounted(() => {
    fetchChannels();
});

function fetchChannels() {
    axios
        .get(channelsApi.url())
        .then((response) => {
            channels.value = response.data.data;
        })
        .catch((error) => {
            console.error('Failed to fetch channels:', error);
        })
        .finally(() => {
            isLoading.value = false;
        });
}
</script>

<template>
    <Card class="sticky top-7 border-none bg-muted shadow-none">
        <CardHeader>
            <CardTitle>Channels</CardTitle>
            <CardDescription> Browse threads by channel </CardDescription>
        </CardHeader>
        <CardContent>
            <ul v-if="!isLoading" class="space-y-2 text-sm">
                <li v-for="channel in channels" :key="channel.id">
                    <span class="mr-2 text-muted-foreground/50">#</span>
                    <Link
                        :href="index.url({ channel: channel.slug })"
                        class="hover:underline"
                    >
                        {{ channel.name }}
                    </Link>
                </li>
            </ul>

            <!-- Loading skeletons -->
            <ul v-else class="space-y-2">
                <li v-for="i in 5" :key="i">
                    <Skeleton class="h-5 w-full" />
                </li>
            </ul>
        </CardContent>
    </Card>
</template>
