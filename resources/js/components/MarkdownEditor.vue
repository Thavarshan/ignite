<script setup lang="ts">
import MarkdownContent from '@/components/MarkdownContent.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Textarea } from '@/components/ui/textarea';
import { ref } from 'vue';

const model = defineModel<string>({ default: '' });

defineProps<{
    placeholder?: string;
    rows?: number;
    id?: string;
    hasError?: boolean;
}>();

const activeTab = ref('write');
</script>

<template>
    <Tabs v-model="activeTab" class="w-full">
        <TabsList class="h-9 w-auto">
            <TabsTrigger value="write" class="text-xs">Write</TabsTrigger>
            <TabsTrigger value="preview" class="text-xs">Preview</TabsTrigger>
        </TabsList>

        <TabsContent value="write" class="mt-2">
            <Textarea
                :id="id"
                v-model="model"
                :placeholder="placeholder ?? 'Write your content using Markdown...'"
                :rows="rows ?? 8"
                class="resize-y font-mono text-sm"
                :class="{ 'border-destructive': hasError }"
            />
        </TabsContent>

        <TabsContent value="preview" class="mt-2">
            <div
                class="min-h-30 rounded-md border border-input bg-background px-3 py-2"
            >
                <MarkdownContent
                    v-if="model.trim()"
                    :content="model"
                />
                <p v-else class="text-sm text-muted-foreground">
                    Nothing to preview
                </p>
            </div>
        </TabsContent>
    </Tabs>
</template>
