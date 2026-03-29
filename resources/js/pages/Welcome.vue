<script setup lang="ts">
import ChannelCard from '@/components/forum/ChannelCard.vue';
import ThreadCard from '@/components/forum/ThreadCard.vue';
import { Button } from '@/components/ui/button';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { index as channelsIndex } from '@/routes/channels';
import { create as threadCreate, index as threadsIndex } from '@/routes/threads';
import type { Channel, Thread } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, Hash, MessageSquare, Users } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps<{
    latestThreads: Thread[];
    popularChannels: Channel[];
}>();

const page = usePage();
const auth = computed(() => page.props.auth as { user?: { id: number } } | undefined);
const canRegister = computed(() => page.props.canRegister as boolean);
</script>

<template>
    <Head title="Welcome" />

    <ForumLayout>
        <div class="space-y-10">
            <!-- Hero -->
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                    {{ $page.props.appName ?? 'Ignite' }}
                </h1>
                <p class="mt-2 text-base text-muted-foreground">
                    A place to discuss, learn, and share knowledge with the community.
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <Link :href="threadsIndex()">
                        <Button size="lg" class="gap-2">
                            <MessageSquare class="size-4" />
                            Browse Threads
                        </Button>
                    </Link>
                    <template v-if="auth?.user">
                        <Link :href="threadCreate()">
                            <Button variant="outline" size="lg">
                                Start a Discussion
                            </Button>
                        </Link>
                    </template>
                    <template v-else>
                        <Link href="/login">
                            <Button variant="outline" size="lg">
                                Sign In
                            </Button>
                        </Link>
                    </template>
                </div>
            </div>

            <!-- Popular Channels -->
            <section v-if="popularChannels.length">
                <div class="flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-foreground">
                        <Hash class="size-4 text-muted-foreground" />
                        Popular Channels
                    </h2>
                    <Link :href="channelsIndex()" class="text-xs text-muted-foreground hover:text-foreground">
                        View all
                        <ArrowRight class="ml-1 inline size-3" />
                    </Link>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    <ChannelCard
                        v-for="channel in popularChannels"
                        :key="channel.id"
                        :channel="channel"
                    />
                </div>
            </section>

            <!-- Latest Threads -->
            <section v-if="latestThreads.length">
                <div class="flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-foreground">
                        <MessageSquare class="size-4 text-muted-foreground" />
                        Latest Threads
                    </h2>
                    <Link :href="threadsIndex()" class="text-xs text-muted-foreground hover:text-foreground">
                        View all
                        <ArrowRight class="ml-1 inline size-3" />
                    </Link>
                </div>
                <div class="mt-3 rounded-lg border border-border/60">
                    <ThreadCard
                        v-for="thread in latestThreads"
                        :key="thread.id"
                        :thread="thread"
                    />
                </div>
            </section>

            <!-- Empty state / CTA -->
            <div v-if="!latestThreads.length && !popularChannels.length" class="py-12 text-center">
                <Users class="mx-auto size-10 text-muted-foreground/40" />
                <h3 class="mt-4 text-sm font-medium text-foreground">No discussions yet</h3>
                <p class="mt-1 text-sm text-muted-foreground">Be the first to start a conversation.</p>
                <div class="mt-6">
                    <Link v-if="auth?.user" :href="threadCreate()">
                        <Button>Start a Discussion</Button>
                    </Link>
                    <Link v-else-if="canRegister" href="/register">
                        <Button>Create an Account</Button>
                    </Link>
                </div>
            </div>
        </div>
    </ForumLayout>
</template>
