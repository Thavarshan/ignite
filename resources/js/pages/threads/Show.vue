<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { index } from '@/routes/threads';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { toRefs } from 'vue';
import ChannelsSidebar from './partials/ChannelsSidebar.vue';
import Replies from './partials/Replies.vue';
import Thread from './partials/Thread.vue';

import type { Thread as ThreadType } from '@/types';

const props = defineProps<{
    thread: ThreadType;
}>();

const { thread } = toRefs(props);
</script>

<template>
    <Head :title="thread.title" />

    <AppHeaderLayout>
        <div>
            <!-- Header Section -->
            <div class="flex items-start justify-between">
                <Thread :thread="thread" />

                <Button variant="ghost" size="sm" as-child>
                    <Link :href="index.url()">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Link>
                </Button>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-8 h-full">
                    <div
                        v-if="thread.body"
                        class="prose max-w-xl dark:prose-invert"
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

                <div class="col-span-4 -mt-16 pt-2">
                    <ChannelsSidebar />
                </div>
            </div>
        </div>
    </AppHeaderLayout>
</template>
