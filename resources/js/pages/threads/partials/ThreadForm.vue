<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { index } from '@/routes/threads';
import { Form } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

import type { Channel, Thread } from '@/types';

type ResetConfig = boolean | string[] | undefined;

const props = withDefaults(
    defineProps<{
        channels?: Channel[];
        thread?: Thread | null;
        form: Record<string, unknown>;
        submitLabel?: string;
        resetOnSuccess?: ResetConfig;
        submitButtonTestId?: string;
    }>(),
    {
        channels: () => [],
        thread: null,
        submitLabel: 'Submit',
        submitButtonTestId: 'thread-form-submit',
    },
);

const emit = defineEmits<{
    success: [];
}>();

const selectedChannelId = ref<string>('');
const channelOptions = computed(() => props.channels ?? []);
const hasChannels = computed(() => channelOptions.value.length > 0);
const isEditing = computed(() => !!props.thread);

const selectedChannel = computed(() =>
    channelOptions.value.find(
        (channel) => String(channel.id) === selectedChannelId.value,
    ),
);

const syncSelectedChannel = (
    channelList: Channel[],
    preferredId?: string | null,
) => {
    if (!channelList.length) {
        selectedChannelId.value = '';
        return;
    }

    const normalizedPreferred = preferredId ? String(preferredId) : '';
    const channelExists = (id: string) =>
        channelList.some((channel) => String(channel.id) === id);

    if (normalizedPreferred && channelExists(normalizedPreferred)) {
        selectedChannelId.value = normalizedPreferred;
    } else if (!channelExists(selectedChannelId.value)) {
        selectedChannelId.value = String(channelList[0]?.id ?? '');
    }
};

watch(
    () => [channelOptions.value, props.thread?.channel?.id] as const,
    ([channelList, threadChannelId]) => {
        syncSelectedChannel(channelList, threadChannelId);
    },
    { immediate: true },
);

const handleSuccess = () => emit('success');
</script>

<template>
    <Form
        v-bind="form"
        :reset-on-success="resetOnSuccess"
        v-slot="{ errors, processing }"
        class="space-y-0"
        @success="handleSuccess"
    >
        <div class="overflow-hidden rounded-lg border bg-card shadow-sm">
            <!-- Channel Selection Bar -->
            <div class="border-b bg-muted/30 px-6 py-3">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-muted-foreground"
                        >Post to:</span
                    >
                    <Select
                        v-model="selectedChannelId"
                        :disabled="!hasChannels"
                    >
                        <SelectTrigger
                            class="h-8 w-auto border-0 bg-background/60 font-medium hover:bg-background"
                        >
                            <div class="flex items-center gap-1.5">
                                <SelectValue placeholder="Select channel" />
                            </div>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="channel in channelOptions"
                                :key="channel.id"
                                :value="String(channel.id)"
                            >
                                <div class="flex items-center gap-1.5">
                                    <span class="text-muted-foreground">#</span>
                                    <span>{{ channel.name }}</span>
                                </div>
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <input
                        name="channel_id"
                        type="hidden"
                        :value="selectedChannelId"
                    />
                </div>
                <InputError :message="errors.channel_id" class="mt-2" />
            </div>

            <!-- Form Content -->
            <div class="space-y-4 p-6">
                <!-- Title Input -->
                <div>
                    <Input
                        id="title"
                        name="title"
                        type="text"
                        required
                        :autofocus="!isEditing"
                        :default-value="thread?.title"
                        placeholder="New discussion title here..."
                        class="border-0 text-3xl font-bold placeholder:text-muted-foreground/40 focus-visible:ring-0 focus-visible:ring-offset-0"
                    />
                    <InputError :message="errors.title" class="mt-2" />
                </div>

                <!-- Body Textarea -->
                <div>
                    <Textarea
                        id="body"
                        name="body"
                        required
                        :default-value="thread?.body"
                        class="min-h-[300px] resize-none border-0 text-base placeholder:text-muted-foreground/60 focus-visible:ring-0 focus-visible:ring-offset-0"
                        placeholder="Share your thoughts, ask questions, or start a conversation..."
                    />
                    <InputError :message="errors.body" class="mt-2" />
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="flex items-center justify-between border-t bg-muted/20 px-6 py-4"
            >
                <div class="text-sm text-muted-foreground">
                    <span v-if="selectedChannel">
                        Posting to
                        <span class="font-medium text-foreground"
                            >#{{ selectedChannel.name }}</span
                        >
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <Button
                        type="button"
                        variant="ghost"
                        size="sm"
                        @click="$inertia.visit(index.url())"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        size="default"
                        :disabled="processing || !hasChannels"
                        :data-test="submitButtonTestId"
                        class="min-w-24"
                    >
                        <LoaderCircle
                            v-if="processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        <span>{{ submitLabel }}</span>
                    </Button>
                </div>
            </div>
        </div>
    </Form>
</template>
