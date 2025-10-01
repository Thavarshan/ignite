<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { index, store } from '@/routes/threads';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { toRefs } from 'vue';
import { toast } from 'vue-sonner';
import ThreadForm from './partials/ThreadForm.vue';

import type { Channel } from '@/types';

const props = defineProps<{
    channels: Channel[];
}>();

const { channels } = toRefs(props);
const storeForm = store.form();

const guidelines = [
    'Think of your title as a compelling description — an overview in one short sentence.',
    'Use keywords to help others find your discussion through search.',
    'Provide context and details to encourage meaningful responses.',
    'Be respectful and constructive in your language.',
];

function handleSuccess() {
    toast('Thread submitted successfully!', {
        description: "We'll publish it once everything looks good.",
        action: { label: 'Close', onClick: () => toast.dismiss() },
    });
}
</script>

<template>
    <Head title="Create Thread" />

    <AppHeaderLayout>
        <div class="mx-auto max-w-7xl">
            <!-- Header Section -->
            <div class="flex items-start justify-between">
                <Heading
                    title="Create Thread"
                    description="Start a new discussion and engage with the community."
                />
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="index.url()">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Link>
                </Button>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Thread Form - Takes 2 columns -->
                <div class="lg:col-span-2">
                    <ThreadForm
                        :channels="channels"
                        :form="storeForm"
                        submit-label="Publish"
                        :reset-on-success="['title', 'body']"
                        submit-button-test-id="create-thread-button"
                        @success="handleSuccess"
                    />
                </div>

                <!-- Sidebar - Takes 1 column -->
                <div class="space-y-4">
                    <!-- Guidelines Card -->
                    <div class="rounded-lg border bg-card p-5">
                        <h3 class="mb-3 text-base font-semibold">
                            Writing a Great Discussion
                        </h3>
                        <ul class="space-y-2.5 text-sm text-muted-foreground">
                            <li
                                v-for="(guideline, index) in guidelines"
                                :key="index"
                                class="flex gap-2"
                            >
                                <span class="mt-0.5 flex-shrink-0 text-primary"
                                    >•</span
                                >
                                <span>{{ guideline }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Available Channels -->
                    <div class="rounded-lg border bg-card p-5">
                        <h3 class="mb-3 text-base font-semibold">
                            Available Channels
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div
                                v-for="channel in channels"
                                :key="channel.id"
                                class="flex items-center gap-2 text-muted-foreground transition-colors hover:text-foreground"
                            >
                                <span class="text-muted-foreground/50">#</span>
                                <span>{{ channel.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppHeaderLayout>
</template>
