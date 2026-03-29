<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CheckCircle, Lock, Pin } from 'lucide-vue-next';
import UserProfileController from '@/actions/App/Http/Controllers/Web/UserProfileController';
import ChannelBadge from '@/components/forum/ChannelBadge.vue';
import ThreadMeta from '@/components/forum/ThreadMeta.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { show } from '@/routes/threads';
import type { Thread } from '@/types';

defineProps<{
    thread: Thread;
}>();
</script>

<template>
    <div
        class="group flex gap-4 border-b border-border/60 px-1 py-4 transition-colors last:border-b-0 hover:bg-muted/30"
    >
        <!-- Author avatar -->
        <div class="hidden shrink-0 sm:block">
            <Avatar class="size-9">
                <AvatarFallback
                    class="bg-neutral-100 text-xs font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                >
                    {{ getInitials(thread.user?.name ?? '') }}
                </AvatarFallback>
            </Avatar>
        </div>

        <!-- Main content -->
        <div class="min-w-0 flex-1">
            <div class="flex items-start gap-2">
                <Link
                    :href="show.url(thread.slug)"
                    class="line-clamp-1 text-sm font-medium text-foreground transition-colors hover:text-primary"
                >
                    {{ thread.title }}
                </Link>

                <div class="flex shrink-0 items-center gap-1">
                    <Pin
                        v-if="thread.pinned"
                        class="size-3.5 text-amber-500"
                        title="Pinned"
                    />
                    <Lock
                        v-if="thread.locked"
                        class="size-3.5 text-muted-foreground"
                        title="Locked"
                    />
                    <CheckCircle
                        v-if="thread.best_reply_id"
                        class="size-3.5 text-green-500"
                        title="Has best answer"
                    />
                </div>
            </div>

            <div class="mt-1 flex flex-wrap items-center gap-2">
                <ChannelBadge v-if="thread.channel" :channel="thread.channel" />
                <span class="text-xs text-muted-foreground">
                    <Link
                        v-if="thread.user"
                        :href="UserProfileController.url(thread.user.id)"
                        class="hover:text-primary hover:underline"
                    >
                        {{ thread.user.name }}
                    </Link>
                </span>
            </div>
        </div>

        <!-- Meta -->
        <div class="hidden shrink-0 items-center sm:flex">
            <ThreadMeta
                :replies-count="thread.replies_count"
                :likes-count="thread.likes_count"
                :visits="thread.visits"
                :last-posted-at="thread.last_posted_at"
            />
        </div>
    </div>
</template>
