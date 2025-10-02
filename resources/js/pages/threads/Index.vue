<script setup lang="ts">
import AppPagination from '@/components/AppPagination.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import Threads from '@/pages/threads/partials/Threads.vue';
import { create } from '@/routes/threads';
import { Head, Link } from '@inertiajs/vue3';
import { Send } from 'lucide-vue-next';
import { toRefs } from 'vue';
import ChannelsSidebar from './partials/ChannelsSidebar.vue';

import type { Paginated, Thread } from '@/types';

const props = defineProps<{
    threads: Paginated<Thread>;
}>();

const { threads } = toRefs(props);
</script>

<template>
    <Head title="All Threads" />

    <AppHeaderLayout>
        <div class="flex items-start justify-between">
            <Heading
                title="Recent Discussions"
                description="Join the conversation with our community"
            />

            <Button as-child>
                <Link :href="create.url()">
                    <Send class="size-4" />
                    Create new thread
                </Link>
            </Button>
        </div>

        <div class="-mt-6 grid grid-cols-12 gap-6">
            <div class="col-span-8 h-full">
                <Threads :threads="threads" />
            </div>

            <div class="col-span-4">
                <ChannelsSidebar />
            </div>
        </div>

        <AppPagination :meta="threads.meta" class="mt-6" />
    </AppHeaderLayout>
</template>
