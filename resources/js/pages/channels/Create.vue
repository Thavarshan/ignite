<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import ForumLayout from '@/layouts/ForumLayout.vue';
import { index, store } from '@/routes/channels';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Channels', href: index() },
    { title: 'New Channel', href: '#' },
];

const form = useForm({
    name: '',
    description: '',
});

function submit() {
    form.post(store.url());
}
</script>

<template>
    <Head title="New Channel" />

    <ForumLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-lg space-y-6">
            <h1 class="text-xl font-semibold text-foreground">New Channel</h1>

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

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="form.processing">
                        Create Channel
                    </Button>
                    <Button
                        type="button"
                        variant="ghost"
                        @click="router.get(index.url())"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
        </div>
    </ForumLayout>
</template>
