<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { cn } from '@/lib/utils';
import type { PaginationMeta } from '@/types/pagination';
import { router } from '@inertiajs/vue3';
import type { HTMLAttributes } from 'vue';
import { computed, ref, watch } from 'vue';

type QueryValue =
    | string
    | number
    | boolean
    | (string | number | boolean)[]
    | null
    | undefined;

const props = withDefaults(
    defineProps<{
        meta: PaginationMeta;
        class?: HTMLAttributes['class'];
        disabled?: boolean;
        autoNavigate?: boolean;
        mergeQuery?: boolean;
        path?: string;
        pageName?: string;
        query?: Record<string, QueryValue>;
        preserveScroll?: boolean;
        preserveState?: boolean;
        showEdges?: boolean;
        siblingCount?: number;
        getUrl?: (page: number) => string;
    }>(),
    {
        disabled: false,
        autoNavigate: true,
        mergeQuery: true,
        pageName: 'page',
        preserveScroll: true,
        preserveState: true,
        showEdges: true,
        siblingCount: 1,
    },
);

const emit = defineEmits<{
    change: [page: number];
    navigating: [isNavigating: boolean];
}>();

const currentPage = ref(props.meta.current_page);
const isNavigating = ref(false);

const isDisabled = computed(() => props.disabled || isNavigating.value);

watch(
    () => props.meta.current_page,
    (page) => {
        currentPage.value = page;
    },
);

const finishNavigate = () => {
    if (!isNavigating.value) {
        return;
    }

    isNavigating.value = false;
    emit('navigating', false);
};

const formatValue = (value: string | number | boolean) => {
    if (value === true) {
        return '1';
    }

    if (value === false) {
        return '0';
    }

    return value.toString();
};

const applyQueryParams = (
    params: URLSearchParams,
    query?: Record<string, QueryValue>,
) => {
    if (!query) {
        return;
    }

    Object.entries(query).forEach(([key, value]) => {
        if (value === null || typeof value === 'undefined') {
            params.delete(key);
            params.delete(`${key}[]`);
            return;
        }

        if (Array.isArray(value)) {
            params.delete(key);
            params.delete(`${key}[]`);

            value.forEach((entry) => {
                params.append(`${key}[]`, formatValue(entry));
            });

            return;
        }

        params.set(key, formatValue(value));
    });
};

const buildUrl = (page: number): string => {
    if (props.getUrl) {
        return props.getUrl(page);
    }

    const basePath = props.path ?? props.meta.path;

    const search = new URLSearchParams(
        props.mergeQuery && typeof window !== 'undefined'
            ? window.location.search
            : '',
    );

    search.set(props.pageName, page.toString());

    applyQueryParams(search, props.query);

    const queryString = search.toString();

    return queryString.length > 0 ? `${basePath}?${queryString}` : basePath;
};

const handlePageChange = (page: number) => {
    if (page === currentPage.value || isDisabled.value) {
        return;
    }

    currentPage.value = page;

    emit('change', page);

    if (!props.autoNavigate) {
        return;
    }

    isNavigating.value = true;
    emit('navigating', true);

    const url = buildUrl(page);

    router.visit(url, {
        preserveScroll: props.preserveScroll,
        preserveState: props.preserveState,
        onCancel: finishNavigate,
        onError: finishNavigate,
        onFinish: finishNavigate,
    });
};

const totalItems = computed(() => props.meta.total ?? 0);
const itemsPerPage = computed(() => props.meta.per_page ?? 0);
const lastPage = computed(() => props.meta.last_page ?? 1);
</script>

<template>
    <Pagination
        v-if="totalItems > 0 && lastPage > 1"
        :page="currentPage"
        :items-per-page="itemsPerPage"
        :total="totalItems"
        :disabled="isDisabled"
        :show-edges="props.showEdges"
        :sibling-count="props.siblingCount"
        :class="cn('flex justify-center', props.class)"
        @update:page="handlePageChange"
    >
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious />

            <template
                v-for="(item, index) in items"
                :key="
                    item.type === 'page'
                        ? `page-${item.value}`
                        : `ellipsis-${index}`
                "
            >
                <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === currentPage"
                >
                    {{ item.value }}
                </PaginationItem>
                <PaginationEllipsis v-else />
            </template>

            <PaginationNext />
        </PaginationContent>
    </Pagination>
</template>
