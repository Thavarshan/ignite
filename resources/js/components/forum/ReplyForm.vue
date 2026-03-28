<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Button } from '@/components/ui/button';
import { store } from '@/routes/replies';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    threadSlug: string;
    parentId?: number;
}>();

const emit = defineEmits<{
    cancel: [];
    submitted: [];
}>();

const form = useForm({
    body: '',
    parent_id: props.parentId ?? null,
});

function submit() {
    form.post(store.url(props.threadSlug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('submitted');
        },
    });
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-3">
        <MarkdownEditor
            v-model="form.body"
            :placeholder="parentId ? 'Write a reply...' : 'Share your thoughts...'"
            :rows="parentId ? 4 : 6"
            :has-error="!!form.errors.body"
        />
        <InputError :message="form.errors.body" />

        <div class="flex items-center gap-2">
            <Button
                type="submit"
                size="sm"
                :disabled="form.processing || !form.body.trim()"
            >
                {{ form.processing ? 'Posting...' : 'Post Reply' }}
            </Button>
            <Button
                v-if="parentId"
                type="button"
                variant="ghost"
                size="sm"
                @click="emit('cancel')"
            >
                Cancel
            </Button>
        </div>
    </form>
</template>
