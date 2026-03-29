<script setup lang="ts">
import { flagThread } from '@/actions/App/Http/Controllers/Web/FlagController';
import { destroy as likeDestroy, store as likeStore } from '@/actions/App/Http/Controllers/Web/ThreadLikeController';
import { destroy as subscribeDestroy, store as subscribeStore } from '@/actions/App/Http/Controllers/Web/ThreadSubscriptionController';
import UserProfileController from '@/actions/App/Http/Controllers/Web/UserProfileController';
import ChannelBadge from '@/components/forum/ChannelBadge.vue';
import FlagButton from '@/components/forum/FlagButton.vue';
import LikeButton from '@/components/forum/LikeButton.vue';
import RelativeTime from '@/components/forum/RelativeTime.vue';
import ReplyCard from '@/components/forum/ReplyCard.vue';
import ReplyForm from '@/components/forum/ReplyForm.vue';
import SubscribeButton from '@/components/forum/SubscribeButton.vue';
import MarkdownContent from '@/components/MarkdownContent.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';
import { getInitials } from '@/composables/useInitials';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { show as channelShow } from '@/routes/channels';
import { destroy as destroyThread, edit, index } from '@/routes/threads';
import type { BreadcrumbItem, Reply, Thread } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Edit, Eye, Lock, MoreHorizontal, Pin, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    thread: Thread;
    replies: Reply[];
    isSubscribed?: boolean;
    isLiked?: boolean;
}>();

const page = usePage();
const auth = computed(() => page.props.auth as { user?: { id: number; is_admin?: boolean } } | undefined);
const isOwner = computed(() => auth.value?.user?.id === props.thread.user_id);
const isAdmin = computed(() => !!auth.value?.user?.is_admin);
const canManage = computed(() => isOwner.value || isAdmin.value);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Forum', href: index() },
    ...(props.thread.channel
        ? [{ title: props.thread.channel.name, href: channelShow(props.thread.channel.slug) }]
        : []),
    { title: props.thread.title, href: '#' },
];

const bestReply = computed(() =>
    props.replies.find((r) => r.id === props.thread.best_reply_id),
);

const otherReplies = computed(() =>
    props.replies.filter((r) => r.id !== props.thread.best_reply_id),
);

function deleteThread() {
    if (!confirm('Are you sure you want to delete this thread?')) {
return;
}

    router.delete(destroyThread.url(props.thread.slug));
}
</script>

<template>
    <Head :title="thread.title" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Thread header -->
            <div>
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <Pin
                                v-if="thread.pinned"
                                class="size-4 text-amber-500"
                            />
                            <Lock
                                v-if="thread.locked"
                                class="size-4 text-muted-foreground"
                            />
                            <h1 class="text-xl font-semibold text-foreground">
                                {{ thread.title }}
                            </h1>
                        </div>

                        <div class="mt-2 flex flex-wrap items-center gap-3">
                            <ChannelBadge
                                v-if="thread.channel"
                                :channel="thread.channel"
                            />
                            <div class="flex items-center gap-2">
                                <Avatar class="size-6">
                                    <AvatarFallback
                                        class="bg-neutral-100 text-[10px] font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                                    >
                                        {{ getInitials(thread.user?.name ?? '') }}
                                    </AvatarFallback>
                                </Avatar>
                                <Link
                                    v-if="thread.user"
                                    :href="UserProfileController.url(thread.user.id)"
                                    class="text-sm text-muted-foreground hover:text-primary hover:underline"
                                >
                                    {{ thread.user.name }}
                                </Link>
                            </div>
                            <RelativeTime
                                :date="thread.created_at"
                                class="text-sm text-muted-foreground"
                            />
                            <span
                                class="flex items-center gap-1 text-sm text-muted-foreground"
                            >
                                <Eye class="size-3.5" />
                                {{ thread.visits }}
                            </span>
                        </div>
                    </div>

                    <!-- Thread actions -->
                    <div class="flex shrink-0 items-center gap-1">
                        <template v-if="auth?.user">
                            <LikeButton
                                :liked="isLiked ?? false"
                                :count="thread.likes_count"
                                :like-url="likeStore.url(thread.slug)"
                                :unlike-url="likeDestroy.url(thread.slug)"
                            />
                            <SubscribeButton
                                :subscribed="isSubscribed ?? false"
                                :subscribe-url="subscribeStore.url(thread.slug)"
                                :unsubscribe-url="subscribeDestroy.url(thread.slug)"
                            />
                            <FlagButton
                                v-if="!isOwner"
                                :url="flagThread.url(thread.slug)"
                            />
                        </template>

                        <DropdownMenu v-if="canManage">
                            <DropdownMenuTrigger as-child>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0"
                                >
                                    <MoreHorizontal class="size-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem as-child>
                                    <Link :href="edit.url(thread.slug)">
                                        <Edit class="mr-2 size-4" />
                                        Edit
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem
                                    @click="deleteThread"
                                    class="text-destructive"
                                >
                                    <Trash2 class="mr-2 size-4" />
                                    Delete
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>

            <!-- Thread body -->
            <div class="rounded-lg border border-border/60 bg-card p-6">
                <MarkdownContent :content="thread.body" />
            </div>

            <!-- Best answer -->
            <div v-if="bestReply" class="space-y-2">
                <h2 class="text-sm font-medium text-green-600 dark:text-green-400">
                    Best Answer
                </h2>
                <ReplyCard
                    :reply="bestReply"
                    :thread-slug="thread.slug"
                    :thread-owner-id="thread.user_id"
                    :best-reply-id="thread.best_reply_id"
                />
            </div>

            <Separator />

            <!-- Replies -->
            <div class="space-y-1">
                <h2 class="text-sm font-medium text-foreground">
                    {{ thread.replies_count }}
                    {{ thread.replies_count === 1 ? 'Reply' : 'Replies' }}
                </h2>

                <div v-if="otherReplies.length" class="divide-y divide-border/40">
                    <ReplyCard
                        v-for="reply in otherReplies"
                        :key="reply.id"
                        :reply="reply"
                        :thread-slug="thread.slug"
                        :thread-owner-id="thread.user_id"
                        :best-reply-id="thread.best_reply_id"
                    />
                </div>
            </div>

            <!-- Reply form -->
            <div v-if="thread.locked" class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-800 dark:bg-amber-950">
                <p class="flex items-center gap-2 text-sm text-amber-700 dark:text-amber-400">
                    <Lock class="size-4" />
                    This thread is locked. No further replies are allowed.
                </p>
            </div>

            <div v-else-if="auth?.user" class="space-y-2">
                <h3 class="text-sm font-medium text-foreground">
                    Leave a Reply
                </h3>
                <ReplyForm :thread-slug="thread.slug" />
            </div>

            <div v-else class="rounded-lg border border-border/60 px-4 py-6 text-center">
                <p class="text-sm text-muted-foreground">
                    <Link href="/login" class="font-medium text-primary hover:underline">
                        Sign in
                    </Link>
                    to join the discussion.
                </p>
            </div>
        </div>
    </ForumLayout>
</template>
