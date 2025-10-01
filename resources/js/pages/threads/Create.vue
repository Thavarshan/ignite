<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { create } from '@/routes/threads';
import { Head, Link } from '@inertiajs/vue3';
import { Send } from 'lucide-vue-next';
import { toRefs } from 'vue';
import ChannelsSidebar from './partials/ChannelsSidebar.vue';

import type { Channel } from '@/types';

const props = defineProps<{
    channels: Channel[];
}>();

const { channels } = toRefs(props);
</script>

<template>
    <Head title="All Threads" />

    <AppHeaderLayout>
        <div class="flex items-start justify-between">
            <Heading
                title="Create New Discussion"
                description="Start a new conversation with our community"
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
                <!-- Create thread form -->
            </div>

            <div class="col-span-4">
                <ChannelsSidebar :channels="channels" />
            </div>
        </div>
    </AppHeaderLayout>
</template>
