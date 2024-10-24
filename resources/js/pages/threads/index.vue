<script setup lang="ts">
// import ThreadCard from './partials/ThreadCard.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { ResourcePaginationSet as PaginationSet } from '@/components/ui/pagination';
import Default from '@/layouts/default.vue';
import { Thread } from '@/types/thread';
import type { Paginated as PaginationType } from '@/types/paginate';
import { truncate } from '@/lib/utils';
import { router, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { ref } from 'vue';

const channel = ref(route().params.channel);

defineProps<{
    threads: PaginationType<Thread>;
}>();

function changePage(page: number) {
    router.visit(route('channels.threads.index', { channel: channel.value, page }));
}
</script>

<template>
    <Default title="Threads" :with-menu="true">
        <template #menu>
            <Link :href="route('channels.threads.index', { channel })" class="font-semibold text-primary"> All </Link>
            <a href="#"> Popular </a>
            <a href="#"> Newest </a>
            <a href="#"> Unanswered </a>
            <a href="#"> My Threads </a>
        </template>
        <div class="space-y-6">
            <Card v-for="thread in threads.data" :key="thread.id">
                <CardHeader>
                    <Link
                        :href="route('channels.threads.show', { channel: thread.channel.slug, thread })"
                        class="hover:underline"
                    >
                        <CardTitle>{{ thread.title }}</CardTitle>
                    </Link>
                    <CardDescription>
                        Posted by {{ thread.user?.name }} {{ dayjs(thread.created_at).fromNow() }}
                    </CardDescription>
                </CardHeader>
                <CardFooter class="border-t px-6 py-4 flex items-center gap-x-2 text-xs">
                    <span>0 replies</span>
                    <span>&middot;</span>
                    <span>{{ thread.channel.name }}</span>
                    <span>&middot;</span>
                    <span>Laste updated {{ dayjs(thread.updated_at).format('MMM DD') }}</span>
                </CardFooter>
            </Card>
        </div>
        <div class="mt-6 flex items-center justify-center">
            <PaginationSet :pagination="threads" @change="changePage" />
        </div>
    </Default>
</template>
