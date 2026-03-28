<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import EmptyState from '@/components/forum/EmptyState.vue';
import LaravelPagination from '@/components/forum/LaravelPagination.vue';
import ThreadCard from '@/components/forum/ThreadCard.vue';
import ThreadFilters from '@/components/forum/ThreadFilters.vue';
import { Button } from '@/components/ui/button';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { index as channelsIndex, show } from '@/routes/channels';
import { create as threadCreate } from '@/routes/threads';
import type { BreadcrumbItem, Channel, PaginatedResponse, Thread } from '@/types';

const props = defineProps<{
    channel: Channel;
    threads: PaginatedResponse<Thread>;
    filters?: {
        sort_by?: string;
        q?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Channels', href: channelsIndex() },
    { title: props.channel.name, href: show(props.channel.slug) },
];
</script>

<template>
    <Head :title="channel.name" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">
                        {{ channel.name }}
                    </h1>
                    <p
                        v-if="channel.description"
                        class="mt-0.5 text-sm text-muted-foreground"
                    >
                        {{ channel.description }}
                    </p>
                </div>
                <Link :href="threadCreate()">
                    <Button size="sm" class="gap-1.5">
                        <Plus class="size-4" />
                        New Thread
                    </Button>
                </Link>
            </div>

            <!-- Filters (no channel filter — already scoped) -->
            <ThreadFilters
                :filters="filters"
                :route-url="show.url(channel.slug)"
                :show-channel-filter="false"
            />

            <!-- Thread list -->
            <div v-if="threads.data.length" class="divide-y-0">
                <ThreadCard
                    v-for="thread in threads.data"
                    :key="thread.id"
                    :thread="thread"
                />
            </div>

            <EmptyState
                v-else
                title="No threads in this channel"
                description="Start a discussion in this channel."
            >
                <template #action>
                    <Link :href="threadCreate()">
                        <Button size="sm" class="gap-1.5">
                            <Plus class="size-4" />
                            Start a Thread
                        </Button>
                    </Link>
                </template>
            </EmptyState>

            <!-- Pagination -->
            <LaravelPagination :meta="threads.meta" />
        </div>
    </ForumLayout>
</template>
