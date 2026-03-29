<script setup lang="ts">
import LaravelPagination from '@/components/forum/LaravelPagination.vue';
import ThreadCard from '@/components/forum/ThreadCard.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import ForumLayout from '@/layouts/ForumLayout.vue';
import type { BreadcrumbItem, PaginatedResponse, Thread } from '@/types';
import { Head } from '@inertiajs/vue3';
import { CalendarDays, MessageSquare, MessagesSquare } from 'lucide-vue-next';

interface ProfileUser {
    id: number;
    name: string;
    created_at: string;
    threads_count: number;
    replies_count: number;
}

const props = defineProps<{
    profileUser: ProfileUser;
    threads: PaginatedResponse<Thread>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Forum', href: '/threads' },
    { title: props.profileUser.name, href: '#' },
];

const memberSince = new Date(props.profileUser.created_at).toLocaleDateString(
    'en-US',
    {
        year: 'numeric',
        month: 'long',
    },
);
</script>

<template>
    <Head :title="profileUser.name" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- User info card -->
            <div class="rounded-lg border border-border/60 bg-card p-6">
                <div class="flex items-center gap-4">
                    <Avatar class="size-16">
                        <AvatarFallback
                            class="bg-neutral-100 text-lg font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                        >
                            {{ getInitials(profileUser.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <div>
                        <h1 class="text-xl font-semibold text-foreground">
                            {{ profileUser.name }}
                        </h1>
                        <div
                            class="mt-1 flex flex-wrap items-center gap-4 text-sm text-muted-foreground"
                        >
                            <span class="flex items-center gap-1.5">
                                <CalendarDays class="size-4" />
                                Member since {{ memberSince }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <MessageSquare class="size-4" />
                                {{ profileUser.threads_count }} threads
                            </span>
                            <span class="flex items-center gap-1.5">
                                <MessagesSquare class="size-4" />
                                {{ profileUser.replies_count }} replies
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Threads -->
            <div class="space-y-2">
                <h2 class="text-sm font-medium text-foreground">Threads</h2>

                <div v-if="threads.data.length" class="space-y-2">
                    <ThreadCard
                        v-for="thread in threads.data"
                        :key="thread.id"
                        :thread="thread"
                    />
                    <LaravelPagination
                        v-if="threads.meta.last_page > 1"
                        :meta="threads.meta"
                    />
                </div>

                <div
                    v-else
                    class="rounded-lg border border-border/60 px-4 py-8 text-center"
                >
                    <p class="text-sm text-muted-foreground">No threads yet.</p>
                </div>
            </div>
        </div>
    </ForumLayout>
</template>
