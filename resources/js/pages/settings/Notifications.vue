<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit as editNotifications, update as updateNotifications } from '@/routes/notifications';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    preferences: Record<string, boolean>;
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Notification settings', href: editNotifications.url() },
];

const form = useForm({
    thread_replied: props.preferences.thread_replied ?? true,
    best_answer_marked: props.preferences.best_answer_marked ?? true,
    user_mentioned: props.preferences.user_mentioned ?? true,
    reply_added: props.preferences.reply_added ?? true,
});

const notificationTypes = [
    {
        key: 'thread_replied' as const,
        label: 'Thread replies',
        description: 'When someone replies to a thread you created or are subscribed to',
    },
    {
        key: 'best_answer_marked' as const,
        label: 'Best answer marked',
        description: 'When your reply is marked as the best answer',
    },
    {
        key: 'user_mentioned' as const,
        label: 'Mentions',
        description: 'When someone mentions you with @username',
    },
    {
        key: 'reply_added' as const,
        label: 'Reply to your comment',
        description: 'When someone replies directly to your comment',
    },
];

function submit() {
    form.patch(updateNotifications.url());
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Notification settings" />

        <h1 class="sr-only">Notification settings</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    title="Notification preferences"
                    description="Choose which notifications you want to receive"
                />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-4">
                        <div
                            v-for="type in notificationTypes"
                            :key="type.key"
                            class="flex items-start gap-3"
                        >
                            <Checkbox
                                :id="type.key"
                                :checked="form[type.key]"
                                @update:checked="(val: boolean) => form[type.key] = val"
                                class="mt-0.5"
                            />
                            <div>
                                <Label :for="type.key" class="text-sm font-medium">
                                    {{ type.label }}
                                </Label>
                                <p class="text-xs text-muted-foreground">
                                    {{ type.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="form.recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
