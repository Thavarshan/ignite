<script setup lang="ts">
import type { PaginationMeta } from '@/types';
import { Link } from '@inertiajs/vue3';

defineProps<{
    meta: PaginationMeta;
}>();
</script>

<template>
    <nav
        v-if="meta.last_page > 1"
        role="navigation"
        aria-label="Pagination"
        class="flex justify-center"
    >
        <ul class="flex items-center gap-1">
            <li v-for="link in meta.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-state
                    preserve-scroll
                    class="inline-flex h-8 items-center justify-center rounded-md text-xs font-medium transition-colors"
                    :class="[
                        link.active
                            ? 'min-w-8 bg-primary px-2.5 text-primary-foreground'
                            : 'hover:bg-accent hover:text-accent-foreground',
                        link.label.includes('Previous') ||
                        link.label.includes('Next')
                            ? 'px-3'
                            : 'min-w-8 px-2.5',
                    ]"
                >
                    <span v-html="link.label" />
                </Link>
                <span
                    v-else-if="link.label.includes('...')"
                    class="flex h-8 min-w-8 items-center justify-center text-xs"
                >
                    &hellip;
                </span>
                <span
                    v-else
                    class="inline-flex h-8 items-center justify-center rounded-md text-xs font-medium opacity-50"
                    :class="[
                        link.label.includes('Previous') ||
                        link.label.includes('Next')
                            ? 'px-3'
                            : 'min-w-8 px-2.5',
                    ]"
                    v-html="link.label"
                />
            </li>
        </ul>
    </nav>
</template>
