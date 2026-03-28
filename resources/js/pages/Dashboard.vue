<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Hash, MessageSquare, TrendingUp } from 'lucide-vue-next';
import ThreadCard from '@/components/forum/ThreadCard.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as threadsIndex } from '@/routes/threads';
import type { BreadcrumbItem, Thread } from '@/types';

defineProps<{
    latestThreads?: Thread[];
    stats?: {
        threads_count: number;
        replies_count: number;
        channels_count: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <!-- Stats -->
            <div class="grid gap-4 sm:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Threads</CardTitle>
                        <MessageSquare class="size-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.threads_count ?? 0 }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Replies</CardTitle>
                        <TrendingUp class="size-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.replies_count ?? 0 }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Channels</CardTitle>
                        <Hash class="size-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.channels_count ?? 0 }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Latest threads -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium text-foreground">Latest Threads</h2>
                    <Link
                        :href="threadsIndex.url()"
                        class="text-xs text-muted-foreground hover:text-foreground"
                    >
                        View all
                    </Link>
                </div>

                <div
                    v-if="latestThreads?.length"
                    class="divide-y divide-border/40 rounded-lg border border-border/60"
                >
                    <ThreadCard
                        v-for="thread in latestThreads"
                        :key="thread.id"
                        :thread="thread"
                    />
                </div>

                <p v-else class="py-6 text-center text-sm text-muted-foreground">
                    No threads yet. Start a conversation!
                </p>
            </div>
        </div>
    </AppLayout>
</template>
