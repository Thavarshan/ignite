<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell class="mx-auto max-w-4xl flex-col pb-20">
        <AppHeader :breadcrumbs="breadcrumbs" />
        <AppContent class="mt-10 max-w-full">
            <slot />
        </AppContent>
    </AppShell>
</template>
