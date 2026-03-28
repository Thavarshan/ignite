<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import EmptyState from '@/components/forum/EmptyState.vue';
import LaravelPagination from '@/components/forum/LaravelPagination.vue';
import ThreadCard from '@/components/forum/ThreadCard.vue';
import ThreadFilters from '@/components/forum/ThreadFilters.vue';
import { Button } from '@/components/ui/button';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { create, index } from '@/routes/threads';
import type { BreadcrumbItem, Channel, PaginatedResponse, Thread } from '@/types';

const props = defineProps<{
    threads: PaginatedResponse<Thread>;
    channels?: Channel[];
    filters?: {
        channel?: string;
        sort_by?: string;
        q?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Forum', href: index() },
];
</script>

<template>
    <Head title="Forum" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-foreground">
                        Threads
                    </h1>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Browse and discuss with the community
                    </p>
                </div>
                <Link :href="create()">
                    <Button size="sm" class="gap-1.5">
                        <Plus class="size-4" />
                        New Thread
                    </Button>
                </Link>
            </div>

            <!-- Filters -->
            <ThreadFilters
                :channels="channels"
                :filters="filters"
                :route-url="index.url()"
                :show-channel-filter="true"
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
                title="No threads found"
                description="Be the first to start a discussion or adjust your filters."
            >
                <template #action>
                    <Link :href="create()">
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
