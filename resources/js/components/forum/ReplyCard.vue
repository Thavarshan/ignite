<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    CheckCircle,
    CornerDownRight,
    Edit,
    MoreHorizontal,
    Trash2,
    X,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { store as bestReplyStore } from '@/actions/App/Http/Controllers/Web/BestReplyController';
import { flagReply } from '@/actions/App/Http/Controllers/Web/FlagController';
import {
    destroy,
    update,
} from '@/actions/App/Http/Controllers/Web/ReplyController';
import {
    destroy as likeDestroy,
    store as likeStore,
} from '@/actions/App/Http/Controllers/Web/ReplyLikeController';
import UserProfileController from '@/actions/App/Http/Controllers/Web/UserProfileController';
import BestAnswerBadge from '@/components/forum/BestAnswerBadge.vue';
import FlagButton from '@/components/forum/FlagButton.vue';
import LikeButton from '@/components/forum/LikeButton.vue';
import RelativeTime from '@/components/forum/RelativeTime.vue';
import ReplyForm from '@/components/forum/ReplyForm.vue';
import MarkdownContent from '@/components/MarkdownContent.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { getInitials } from '@/composables/useInitials';
import type { Reply } from '@/types';

const props = defineProps<{
    reply: Reply;
    threadSlug: string;
    threadOwnerId: number;
    bestReplyId: number | null;
    depth?: number;
}>();

const page = usePage();
const auth = computed(
    () => page.props.auth as { user?: { id: number } } | undefined,
);
const currentDepth = computed(() => props.depth ?? 0);
const isBestAnswer = computed(() => props.reply.id === props.bestReplyId);
const isOwner = computed(() => auth.value?.user?.id === props.reply.user_id);
const isThreadOwner = computed(
    () => auth.value?.user?.id === props.threadOwnerId,
);
const showReplyForm = ref(false);
const editing = ref(false);
const editBody = ref('');
const saving = ref(false);

function startEditing() {
    editBody.value = props.reply.body;
    editing.value = true;
}

function cancelEditing() {
    editing.value = false;
    editBody.value = '';
}

function saveEdit() {
    saving.value = true;
    router.put(
        update.url({ thread: props.threadSlug, reply: props.reply.id }),
        {
            body: editBody.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                editing.value = false;
                editBody.value = '';
            },
            onFinish: () => {
                saving.value = false;
            },
        },
    );
}

function markBestAnswer() {
    router.post(
        bestReplyStore.url({ thread: props.threadSlug, reply: props.reply.id }),
        {},
        {
            preserveScroll: true,
        },
    );
}

function deleteReply() {
    if (!confirm('Are you sure you want to delete this reply?')) {
        return;
    }

    router.delete(
        destroy.url({ thread: props.threadSlug, reply: props.reply.id }),
        {
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <div
        class="relative"
        :class="{
            'border-l-2 border-green-400 dark:border-green-600': isBestAnswer,
            'ml-6 border-l border-border pl-4': currentDepth > 0,
        }"
    >
        <div class="py-4" :class="{ 'pl-4': isBestAnswer }">
            <!-- Header -->
            <div class="flex items-center gap-2">
                <Avatar class="size-7">
                    <AvatarFallback
                        class="bg-neutral-100 text-xs font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                    >
                        {{ getInitials(reply.user?.name ?? '') }}
                    </AvatarFallback>
                </Avatar>
                <Link
                    v-if="reply.user"
                    :href="UserProfileController.url(reply.user.id)"
                    class="text-sm font-medium text-foreground hover:text-primary hover:underline"
                >
                    {{ reply.user.name }}
                </Link>
                <RelativeTime
                    :date="reply.created_at"
                    class="text-xs text-muted-foreground"
                />
                <BestAnswerBadge v-if="isBestAnswer" />
            </div>

            <!-- Body -->
            <div class="mt-2">
                <template v-if="editing">
                    <MarkdownEditor
                        v-model="editBody"
                        placeholder="Edit your reply..."
                    />
                    <div class="mt-2 flex items-center gap-2">
                        <Button
                            size="sm"
                            :disabled="saving || !editBody.trim()"
                            @click="saveEdit"
                        >
                            {{ saving ? 'Saving...' : 'Save' }}
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            :disabled="saving"
                            @click="cancelEditing"
                        >
                            <X class="mr-1 size-3.5" />
                            Cancel
                        </Button>
                    </div>
                </template>
                <MarkdownContent v-else :content="reply.body" />
            </div>

            <!-- Actions -->
            <div v-if="!editing" class="mt-2 flex items-center gap-1">
                <LikeButton
                    :liked="reply.is_liked ?? false"
                    :count="reply.likes_count"
                    :like-url="
                        likeStore.url({ thread: threadSlug, reply: reply.id })
                    "
                    :unlike-url="
                        likeDestroy.url({ thread: threadSlug, reply: reply.id })
                    "
                />

                <Button
                    v-if="auth?.user && currentDepth < 3"
                    variant="ghost"
                    size="sm"
                    class="h-8 gap-1.5 px-2 text-xs text-muted-foreground"
                    @click="showReplyForm = !showReplyForm"
                >
                    <CornerDownRight class="size-3.5" />
                    Reply
                </Button>

                <Button
                    v-if="isThreadOwner && !isBestAnswer"
                    variant="ghost"
                    size="sm"
                    class="h-8 gap-1.5 px-2 text-xs text-muted-foreground"
                    @click="markBestAnswer"
                >
                    <CheckCircle class="size-3.5" />
                    Best Answer
                </Button>

                <FlagButton
                    v-if="auth?.user && !isOwner"
                    :url="
                        flagReply.url({ thread: threadSlug, reply: reply.id })
                    "
                />

                <DropdownMenu v-if="isOwner">
                    <DropdownMenuTrigger as-child>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="h-8 w-8 p-0 text-muted-foreground"
                        >
                            <MoreHorizontal class="size-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem @click="startEditing">
                            <Edit class="mr-2 size-4" />
                            Edit
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem
                            @click="deleteReply"
                            class="text-destructive"
                        >
                            <Trash2 class="mr-2 size-4" />
                            Delete
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <!-- Inline reply form -->
            <div v-if="showReplyForm" class="mt-3">
                <ReplyForm
                    :thread-slug="threadSlug"
                    :parent-id="reply.id"
                    @cancel="showReplyForm = false"
                    @submitted="showReplyForm = false"
                />
            </div>
        </div>

        <!-- Nested replies -->
        <template v-if="reply.children?.length && currentDepth < 3">
            <ReplyCard
                v-for="child in reply.children"
                :key="child.id"
                :reply="child"
                :thread-slug="threadSlug"
                :thread-owner-id="threadOwnerId"
                :best-reply-id="bestReplyId"
                :depth="currentDepth + 1"
            />
        </template>
    </div>
</template>
