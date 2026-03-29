<script setup lang="ts">
import {
    destroy as notificationsDestroy,
    index as notificationsIndex,
    read as notificationsRead,
    readAll as notificationsReadAll,
} from '@/actions/App/Http/Controllers/Api/NotificationController';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { show } from '@/routes/threads';
import type { AppNotification } from '@/types';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { Bell, Check, CheckCheck, Trash2 } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const notifications = ref<AppNotification[]>([]);
const loading = ref(false);
const open = ref(false);

const unreadCount = computed(
    () => notifications.value.filter((n) => !n.read_at).length,
);

async function fetchNotifications() {
    loading.value = true;

    try {
        const { data } = await axios.get(notificationsIndex.url());
        notifications.value = data.data;
    } catch {
        // Silently handle auth/network errors
    } finally {
        loading.value = false;
    }
}

async function markAsRead(id: string) {
    await axios.post(notificationsRead.url(id));
    const notification = notifications.value.find((n) => n.id === id);

    if (notification) {
        notification.read_at = new Date().toISOString();
    }
}

async function markAllRead() {
    await axios.post(notificationsReadAll.url());
    notifications.value.forEach(
        (n) => (n.read_at = n.read_at ?? new Date().toISOString()),
    );
}

async function remove(id: string) {
    await axios.delete(notificationsDestroy.url(id));
    notifications.value = notifications.value.filter((n) => n.id !== id);
}

function notificationText(notification: AppNotification): string {
    const data = notification.data as Record<string, string>;
    const type = notification.type.split('\\').pop() ?? '';

    switch (type) {
        case 'ThreadReplied':
            return `${data.replier_name} replied to "${data.thread_title}"`;
        case 'BestAnswerMarked':
            return `Your reply on "${data.thread_title}" was marked as best answer`;
        case 'UserMentioned':
            return `${data.mentioner_name} mentioned you in "${data.thread_title}"`;
        case 'ReplyAdded':
            return `${data.replier_name} replied to your comment`;
        default:
            return 'New notification';
    }
}

function notificationLink(notification: AppNotification): string | null {
    const data = notification.data as Record<string, string>;

    if (data.thread_slug) {
        return show.url(data.thread_slug);
    }

    return null;
}

function handleClick(notification: AppNotification) {
    if (!notification.read_at) {
        markAsRead(notification.id);
    }

    const link = notificationLink(notification);

    if (link) {
        open.value = false;
        router.get(link);
    }
}

onMounted(fetchNotifications);
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="ghost"
                size="icon"
                class="group relative h-9 w-9 cursor-pointer"
                @click="fetchNotifications"
            >
                <Bell class="size-5 opacity-80 group-hover:opacity-100" />
                <span
                    v-if="unreadCount > 0"
                    class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-primary px-1 text-[10px] font-medium text-primary-foreground"
                >
                    {{ unreadCount > 9 ? '9+' : unreadCount }}
                </span>
            </Button>
        </PopoverTrigger>

        <PopoverContent align="end" class="w-80 p-0">
            <div class="flex items-center justify-between border-b px-4 py-3">
                <h3 class="text-sm font-medium">Notifications</h3>
                <Button
                    v-if="unreadCount > 0"
                    variant="ghost"
                    size="sm"
                    class="h-7 text-xs"
                    @click="markAllRead"
                >
                    <CheckCheck class="mr-1 size-3" />
                    Mark all read
                </Button>
            </div>

            <div class="max-h-80 overflow-y-auto">
                <div
                    v-if="loading && !notifications.length"
                    class="px-4 py-8 text-center"
                >
                    <p class="text-sm text-muted-foreground">Loading...</p>
                </div>

                <div
                    v-else-if="!notifications.length"
                    class="px-4 py-8 text-center"
                >
                    <Bell
                        class="mx-auto mb-2 size-6 text-muted-foreground/50"
                    />
                    <p class="text-sm text-muted-foreground">
                        No notifications
                    </p>
                </div>

                <div v-else>
                    <button
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="flex w-full items-start gap-3 px-4 py-3 text-left transition-colors hover:bg-muted/50"
                        :class="{ 'bg-primary/5': !notification.read_at }"
                        @click="handleClick(notification)"
                    >
                        <div
                            class="mt-1.5 size-2 shrink-0 rounded-full"
                            :class="
                                notification.read_at
                                    ? 'bg-transparent'
                                    : 'bg-primary'
                            "
                        />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm">
                                {{ notificationText(notification) }}
                            </p>
                            <p class="mt-0.5 text-xs text-muted-foreground">
                                {{
                                    new Date(
                                        notification.created_at,
                                    ).toLocaleDateString()
                                }}
                            </p>
                        </div>
                        <div class="flex shrink-0 items-center gap-1">
                            <Button
                                v-if="!notification.read_at"
                                variant="ghost"
                                size="sm"
                                class="h-6 w-6 p-0"
                                @click.stop="markAsRead(notification.id)"
                            >
                                <Check class="size-3" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-6 w-6 p-0 text-muted-foreground hover:text-destructive"
                                @click.stop="remove(notification.id)"
                            >
                                <Trash2 class="size-3" />
                            </Button>
                        </div>
                    </button>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
