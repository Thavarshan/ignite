<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { getInitials } from '@/composables/useInitials';
import { diffForHumans } from '@/lib/utils';
import { index } from '@/routes/channels/threads';
import { destroy, edit, index as threadIndex } from '@/routes/threads';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import type { Thread } from '@/types';

const page = usePage();
const auth = page.props.auth;

const props = defineProps<{
    thread: Thread;
}>();

const isDeleting = ref(false);

function deleteThread() {
    isDeleting.value = true;

    router.delete(destroy(props.thread.slug), {
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}
</script>

<template>
    <div class="mb-8 space-y-2">
        <Link
            :href="threadIndex.url({ query: { author: thread.author?.name } })"
            class="group block"
        >
            <div class="flex items-center space-x-3">
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage
                        v-if="thread.author?.avatar"
                        :src="thread.author?.avatar"
                        :alt="thread.author?.name"
                    />
                    <AvatarFallback
                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                    >
                        {{ getInitials(thread.author?.name) }}
                    </AvatarFallback>
                </Avatar>
                <div class="group-hover:underline">
                    <span class="text-sm font-medium">
                        {{ thread.author?.name || 'Unknown' }}
                    </span>
                </div>
            </div>
        </Link>
        <h2 class="max-w-xl text-xl font-semibold tracking-tight">
            {{ thread.title }}
        </h2>
        <div class="flex items-center text-sm text-muted-foreground">
            <div>
                <Link
                    :href="index.url({ channel: thread.channel?.slug })"
                    class="hover:underline"
                >
                    <Badge>
                        {{ thread.channel?.name || 'General' }}
                    </Badge>
                </Link>
            </div>
            <div class="mx-2">•</div>
            <div>{{ diffForHumans(thread.created_at) }}</div>
            <div
                class="flex items-center"
                v-if="auth.user.id === thread.user_id"
            >
                <div class="mx-2">•</div>
                <div>
                    <Button
                        variant="link"
                        size="sm"
                        class="cursor-pointer p-0 text-xs"
                        @click="$inertia.visit(edit({ thread }))"
                    >
                        Edit
                    </Button>
                </div>
                <div class="mx-2">•</div>
                <div>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button
                                variant="link"
                                size="sm"
                                class="cursor-pointer p-0 text-xs text-destructive"
                                :disabled="isDeleting"
                            >
                                Delete
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle
                                    >Are you absolutely sure?</AlertDialogTitle
                                >
                                <AlertDialogDescription>
                                    This action cannot be undone. This will
                                    permanently delete your thread and remove
                                    all associated replies from our servers.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel :disabled="isDeleting"
                                    >Cancel</AlertDialogCancel
                                >
                                <AlertDialogAction
                                    @click="deleteThread"
                                    :disabled="isDeleting"
                                    class="bg-destructive hover:bg-destructive/90"
                                >
                                    {{ isDeleting ? 'Deleting...' : 'Delete' }}
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </div>
    </div>
</template>
