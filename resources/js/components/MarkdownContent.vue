<script setup lang="ts">
import DOMPurify from 'dompurify';
import { marked } from 'marked';
import { computed } from 'vue';

const props = defineProps<{
    content: string;
}>();

const html = computed(() => {
    const raw = marked.parse(props.content, { async: false }) as string;

    return DOMPurify.sanitize(raw);
});
</script>

<template>
    <div
        class="prose max-w-none prose-neutral dark:prose-invert prose-headings:font-semibold prose-a:text-primary prose-code:rounded prose-code:bg-muted prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:before:content-none prose-code:after:content-none prose-pre:bg-muted prose-pre:text-foreground"
        v-html="html"
    />
</template>
