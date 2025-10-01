<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Web/ThreadReplyController';
import InputError from '@/components/InputError.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import { Form, Link } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

import type { Thread, User } from '@/types';

defineProps<{
    thread: Thread;
    user: User;
}>();

function handleSuccess() {
    toast('Reply posted!', {
        description: 'Your reply has been posted.',
        action: { label: 'Close', onClick: () => toast.dismiss() },
    });
}
</script>

<template>
    <div>
        <Link href="/" class="group block">
            <div class="flex items-center space-x-3">
                <Avatar class="size-8 overflow-hidden rounded-full">
                    <AvatarImage
                        v-if="user.avatar"
                        :src="user.avatar"
                        :alt="user.name"
                    />
                    <AvatarFallback
                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                    >
                        {{ getInitials(user.name) }}
                    </AvatarFallback>
                </Avatar>
                <div class="group-hover:underline">
                    <span class="text-sm font-medium">
                        {{ user.name || 'Unknown' }}
                    </span>
                </div>
            </div>
        </Link>

        <Form
            :action="store({ thread })"
            method="post"
            :reset-on-success="true"
            v-slot="{ errors, processing }"
            class="mt-4"
            @success="handleSuccess"
            :options="{
                preserveScroll: true,
                preserveState: true,
                preserveUrl: true,
                replace: true,
            }"
        >
            <Textarea
                id="body"
                name="body"
                required
                autofocus
                :tabindex="1"
                placeholder="Have something to say?"
                class="min-h-40"
            />
            <InputError :message="errors.email" />
            <Button
                type="submit"
                class="mt-4"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Post reply
            </Button>
        </Form>
    </div>
</template>
