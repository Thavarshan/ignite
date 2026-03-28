<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { index, show, update } from '@/routes/threads';
import type { BreadcrumbItem, Channel, Thread } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    thread: Thread;
    channels: Channel[];
}>();

const page = usePage();
const isAdmin = computed(() => !!(page.props.auth as { user?: { is_admin?: boolean } })?.user?.is_admin);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Forum', href: index() },
    { title: props.thread.title, href: show(props.thread.slug) },
    { title: 'Edit', href: '#' },
];

const form = useForm({
    channel_id: String(props.thread.channel_id),
    title: props.thread.title,
    body: props.thread.body,
    pinned: props.thread.pinned ?? false,
    locked: props.thread.locked ?? false,
});

function submit() {
    form.put(update.url(props.thread.slug));
}

function deleteThread() {
    router.delete(`/threads/${props.thread.slug}`);
}
</script>

<template>
    <Head :title="`Edit: ${thread.title}`" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl space-y-6">
            <h1 class="text-xl font-semibold text-foreground">Edit Thread</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label for="channel">Channel</Label>
                    <Select v-model="form.channel_id">
                        <SelectTrigger id="channel">
                            <SelectValue placeholder="Select a channel" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="channel in channels"
                                :key="channel.id"
                                :value="String(channel.id)"
                            >
                                {{ channel.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.channel_id" />
                </div>

                <div class="space-y-2">
                    <Label for="title">Title</Label>
                    <Input
                        id="title"
                        v-model="form.title"
                        placeholder="Thread title"
                        :class="{ 'border-destructive': form.errors.title }"
                    />
                    <InputError :message="form.errors.title" />
                </div>

                <div class="space-y-2">
                    <Label for="body">Body</Label>
                    <MarkdownEditor
                        id="body"
                        v-model="form.body"
                        :has-error="!!form.errors.body"
                        :rows="12"
                    />
                    <InputError :message="form.errors.body" />
                </div>

                <div v-if="isAdmin" class="space-y-3 rounded-lg border border-border/60 p-4">
                    <Label class="text-sm font-medium">Admin Options</Label>
                    <div class="flex items-center gap-2">
                        <Checkbox id="pinned" :checked="form.pinned" @update:checked="(val: boolean) => form.pinned = val" />
                        <Label for="pinned" class="text-sm font-normal">Pin this thread</Label>
                    </div>
                    <div class="flex items-center gap-2">
                        <Checkbox id="locked" :checked="form.locked" @update:checked="(val: boolean) => form.locked = val" />
                        <Label for="locked" class="text-sm font-normal">Lock this thread</Label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <Button type="submit" :disabled="form.processing">
                            Update Thread
                        </Button>
                        <Button
                            type="button"
                            variant="ghost"
                            @click="router.get(show.url(thread.slug))"
                        >
                            Cancel
                        </Button>
                    </div>

                    <Dialog>
                        <DialogTrigger as-child>
                            <Button type="button" variant="destructive" size="sm">
                                Delete Thread
                            </Button>
                        </DialogTrigger>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Delete Thread</DialogTitle>
                                <DialogDescription>
                                    Are you sure you want to delete this thread? This action cannot be undone.
                                </DialogDescription>
                            </DialogHeader>
                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button variant="outline">Cancel</Button>
                                </DialogClose>
                                <Button variant="destructive" @click="deleteThread">
                                    Delete
                                </Button>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>
                </div>
            </form>
        </div>
    </ForumLayout>
</template>
