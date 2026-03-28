<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
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
import { Textarea } from '@/components/ui/textarea';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { destroy, index, show, update } from '@/routes/channels';
import type { BreadcrumbItem, Channel } from '@/types';

const props = defineProps<{
    channel: Channel;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Channels', href: index() },
    { title: props.channel.name, href: show(props.channel.slug) },
    { title: 'Edit', href: '#' },
];

const form = useForm({
    name: props.channel.name,
    description: props.channel.description ?? '',
});

function submit() {
    form.put(update.url(props.channel.slug));
}

function deleteChannel() {
    router.delete(destroy.url(props.channel.slug));
}
</script>

<template>
    <Head :title="`Edit: ${channel.name}`" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-lg space-y-6">
            <h1 class="text-xl font-semibold text-foreground">Edit Channel</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Channel name"
                        :class="{ 'border-destructive': form.errors.name }"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <Label for="description">Description</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Brief description of this channel"
                        :rows="3"
                    />
                    <InputError :message="form.errors.description" />
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <Button type="submit" :disabled="form.processing">
                            Update Channel
                        </Button>
                        <Button
                            type="button"
                            variant="ghost"
                            @click="router.get(show.url(channel.slug))"
                        >
                            Cancel
                        </Button>
                    </div>

                    <Dialog>
                        <DialogTrigger as-child>
                            <Button type="button" variant="destructive" size="sm">
                                Delete
                            </Button>
                        </DialogTrigger>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Delete Channel</DialogTitle>
                                <DialogDescription>
                                    Are you sure? All threads in this channel will be affected.
                                </DialogDescription>
                            </DialogHeader>
                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button variant="outline">Cancel</Button>
                                </DialogClose>
                                <Button variant="destructive" @click="deleteChannel">
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
