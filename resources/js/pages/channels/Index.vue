<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { computed } from 'vue';
import ChannelCard from '@/components/forum/ChannelCard.vue';
import EmptyState from '@/components/forum/EmptyState.vue';
import LaravelPagination from '@/components/forum/LaravelPagination.vue';
import { Button } from '@/components/ui/button';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { create, index } from '@/routes/channels';
import type { BreadcrumbItem, Channel, PaginatedResponse } from '@/types';

defineProps<{
    channels: PaginatedResponse<Channel>;
}>();

const page = usePage();
const isAdmin = computed(() => !!(page.props.auth as { user?: { is_admin?: boolean } })?.user?.is_admin);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Channels', href: index() },
];
</script>

<template>
    <Head title="Channels" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-foreground">Channels</h1>
                <Button v-if="isAdmin" as-child size="sm">
                    <Link :href="create.url()">
                        <Plus class="mr-1.5 size-4" />
                        New Channel
                    </Link>
                </Button>
            </div>

            <div
                v-if="channels.data.length"
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <ChannelCard
                    v-for="channel in channels.data"
                    :key="channel.id"
                    :channel="channel"
                />
            </div>

            <EmptyState
                v-else
                title="No channels yet"
                description="Channels help organize threads by topic."
            />

            <LaravelPagination :meta="channels.meta" />
        </div>
    </ForumLayout>
</template>
