<script setup lang="ts">
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Head } from '@inertiajs/vue3';
import { toRefs } from 'vue';
import ChannelsSidebar from './partials/ChannelsSidebar.vue';
import Replies from './partials/Replies.vue';
import Thread from './partials/Thread.vue';

import type { Channel, Thread as ThreadType } from '@/types';

const props = defineProps<{
    thread: ThreadType;
    channels: Channel[];
}>();

const { thread, channels } = toRefs(props);
</script>

<template>
    <Head :title="thread.title" />

    <AppHeaderLayout>
        <Thread :thread="thread" />

        <div class="-mt-6 grid grid-cols-12 gap-6">
            <div class="col-span-8 h-full">
                <div
                    v-if="thread.body"
                    class="prose dark:prose-invert"
                    v-html="thread.body"
                ></div>

                <div class="mt-6">
                    <Replies
                        v-if="thread.replies"
                        :replies="thread.replies"
                        :thread="thread"
                    />
                </div>
            </div>

            <div class="col-span-4">
                <ChannelsSidebar :channels="channels" />
            </div>
        </div>
    </AppHeaderLayout>
</template>
