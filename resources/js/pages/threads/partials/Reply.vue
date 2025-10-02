<script setup lang="ts">
import {
    destroy,
    update,
} from '@/actions/App/Http/Controllers/Web/ReplyController';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import { diffForHumans } from '@/lib/utils';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

import type { Reply } from '@/types';

const page = usePage();
const auth = page.props.auth;

const props = defineProps<{
    reply: Reply;
}>();

const isEditing = ref(false);
const editedBody = ref('');
const isSubmitting = ref(false);

function startEditing() {
    isEditing.value = true;
    // Strip HTML tags for editing
    const temp = document.createElement('div');
    temp.innerHTML = props.reply.body;
    editedBody.value = temp.textContent || temp.innerText || '';
}

function cancelEditing() {
    isEditing.value = false;
    editedBody.value = '';
}

function saveReply() {
    if (!editedBody.value.trim()) {
        toast.error('Reply cannot be empty');
        return;
    }

    isSubmitting.value = true;

    router.put(
        update(props.reply.id),
        { body: editedBody.value },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast('Reply updated!', {
                    description: 'Your reply has been updated.',
                    action: { label: 'Close', onClick: () => toast.dismiss() },
                });
                isEditing.value = false;
                editedBody.value = '';
            },
            onError: () => {
                toast.error('Failed to update reply', {
                    description: 'Please try again.',
                });
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        },
    );
}

function deleteReply() {
    router.delete(destroy(props.reply.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast('Reply deleted!', {
                description: 'Your reply has been deleted.',
                action: { label: 'Close', onClick: () => toast.dismiss() },
            });
        },
    });
}
</script>

<template>
    <div>
        <div class="flex items-center space-x-3">
            <Link href="/" class="block">
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage
                        v-if="reply.author?.avatar"
                        :src="reply.author?.avatar"
                        :alt="reply.author?.name"
                    />
                    <AvatarFallback
                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                    >
                        {{ getInitials(reply.author?.name) }}
                    </AvatarFallback>
                </Avatar>
            </Link>
            <div class="pt-1">
                <Link href="/" class="block">
                    <div class="text-sm font-medium hover:underline">
                        {{ reply.author?.name || 'Unknown' }}
                    </div>
                </Link>
                <div class="flex items-center text-xs text-primary">
                    <div>{{ diffForHumans(reply.created_at) }}</div>
                    <div v-if="auth.user.id === reply.user_id">
                        <span class="mx-2">•</span>
                        <Button
                            size="sm"
                            variant="link"
                            class="cursor-pointer p-0 text-xs"
                            @click="startEditing"
                            :disabled="isEditing"
                        >
                            Edit
                        </Button>
                        <span class="mx-2">•</span>
                        <Button
                            size="sm"
                            variant="link"
                            class="cursor-pointer p-0 text-xs text-destructive"
                            @click="deleteReply"
                            :disabled="isEditing"
                        >
                            Delete
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <div v-if="isEditing" class="mt-2 space-y-2">
            <Textarea
                v-model="editedBody"
                rows="4"
                class="w-full"
                placeholder="Edit your reply..."
                :disabled="isSubmitting"
            />
            <div class="flex gap-2">
                <Button size="sm" @click="saveReply" :disabled="isSubmitting">
                    {{ isSubmitting ? 'Saving...' : 'Save' }}
                </Button>
                <Button
                    size="sm"
                    variant="outline"
                    @click="cancelEditing"
                    :disabled="isSubmitting"
                >
                    Cancel
                </Button>
            </div>
        </div>

        <!-- View Mode -->
        <div
            v-else
            class="prose prose-sm mt-2 dark:prose-invert"
            v-html="reply.body"
        ></div>
    </div>
</template>
